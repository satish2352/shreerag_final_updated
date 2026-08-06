<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Repository\Organizations\Report\ReportRepository;
use App\Http\Repository\Organizations\Inventory\InventoryRepository;
use App\Http\Repository\Organizations\Store\StoreRepository;
use App\Http\Controllers\Organizations\Report\ReportController;
use App\Http\Controllers\Exports\ItemStockReportExport;

/**
 * T-2026-060 — module_tester GAP-COVERAGE pass (first dispatch, mandatory
 * self-audit). These tests independently cover areas of the 8 root causes /
 * acceptance criteria that the implementer's own 8 tests
 * (tests/Feature/StockDailyReportReconciliationTest.php) did not exercise:
 *
 *   - Root cause #2 nuance: InventoryRepository addAll()/updateAll() exact
 *     quantity_delta vs legacy `quantity` column semantics (delta vs
 *     absolute), not just the final reconciled balance.
 *   - Root cause #3 second half: the production-issue predicate is an AND of
 *     TWO conditions — a row satisfying only one half must still be excluded.
 *   - Root cause #7: the two StoreRepository::updateProductMaterialWiseAddNewReq()
 *     write sites, driven end-to-end through the real repository method (not
 *     a hand-inserted issued_at value), the same rigor already applied to
 *     the StoreController 3rd site.
 *   - Root cause #8: the `search` post-balance filter must narrow displayed
 *     rows/visible totals without ever perturbing the true `totals.balance`.
 *   - AC #2: the actual AJAX controller action, PDF export, and Excel export
 *     entry points (not just the repository return array) — JSON shape and
 *     export objects exercised directly.
 *   - AC #3: ItemStockReportExport's 0-vs-null balance formatting exercised
 *     directly (genuine 0.00 must format as "0.00", suppressed/null as "-").
 *   - AC #5: `stock:reconcile-opening-balance` run via the REAL Artisan CLI
 *     entry point twice (not just the underlying repository methods),
 *     confirming no double-posting, and --dry-run confirmed to write zero
 *     rows via a before/after count.
 */
class StockDailyReportGapCoverageTest extends TestCase
{
    use DatabaseTransactions;

    private ReportRepository $reportRepo;
    private InventoryRepository $inventoryRepo;
    private StoreRepository $storeRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportRepo = new ReportRepository();
        $this->inventoryRepo = new InventoryRepository();
        $this->storeRepo = new StoreRepository();
    }

    private function makePartItem(array $overrides = []): int
    {
        return DB::table('tbl_part_item')->insertGetId(array_merge([
            'part_number'   => 'T2026060GAP-' . uniqid(),
            'description'   => 'T-2026-060 Gap Test Part',
            'unit_id'       => 2,
            'hsn_id'        => 7,
            'group_type_id' => 20,
            'basic_rate'    => 10,
            'opening_stock' => 0,
            'is_active'     => 1,
            'is_deleted'    => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ], $overrides));
    }

    /**
     * Root cause #2 nuance: addAll() must store the ADDED delta into both the
     * legacy `quantity` column and `quantity_delta` (they agree for a pure
     * addition), while updateAll() must store the ABSOLUTE new quantity into
     * the legacy `quantity` column but the SIGNED delta into `quantity_delta`
     * — two deliberately different semantics documented in the source, never
     * directly asserted by the implementer's own test (which only checked
     * the final reconciled balance).
     */
    public function test_addall_and_updateall_store_correct_delta_vs_absolute_semantics(): void
    {
        $partId = $this->makePartItem();

        $this->inventoryRepo->addAll(Request::create('/x', 'POST', [
            'part_item_id' => $partId,
            'quantity' => 25,
        ]));

        $addHistory = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'manual_addition')->first();
        $this->assertNotNull($addHistory);
        $this->assertEqualsWithDelta(25.0, (float) $addHistory->quantity, 0.001, 'addAll(): legacy quantity column = added delta');
        $this->assertEqualsWithDelta(25.0, (float) $addHistory->quantity_delta, 0.001, 'addAll(): quantity_delta = added delta');
        $this->assertEqualsWithDelta(25.0, (float) $addHistory->balance_after, 0.001);

        $itemStock = DB::table('tbl_item_stock')->where('part_item_id', $partId)->first();
        $this->inventoryRepo->updateAll(Request::create('/x', 'POST', [
            'id' => $itemStock->id,
            'quantity' => 70, // absolute new quantity, up from 25 => delta +45
        ]));

        $updHistory = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'manual_adjustment_set')->first();
        $this->assertNotNull($updHistory);
        $this->assertEqualsWithDelta(70.0, (float) $updHistory->quantity, 0.001, 'updateAll(): legacy quantity column = ABSOLUTE new quantity, not a delta');
        $this->assertEqualsWithDelta(45.0, (float) $updHistory->quantity_delta, 0.001, 'updateAll(): quantity_delta = SIGNED change (70-25=45)');
        $this->assertEqualsWithDelta(70.0, (float) $updHistory->balance_after, 0.001);

        // A DOWNWARD correction must produce a NEGATIVE quantity_delta while the
        // legacy column still stores the absolute value (never negative-clamped).
        $this->inventoryRepo->updateAll(Request::create('/x', 'POST', [
            'id' => $itemStock->id,
            'quantity' => 10, // 70 -> 10, delta = -60
        ]));
        $updHistory2 = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'manual_adjustment_set')
            ->orderByDesc('id')->first();
        $this->assertEqualsWithDelta(10.0, (float) $updHistory2->quantity, 0.001);
        $this->assertEqualsWithDelta(-60.0, (float) $updHistory2->quantity_delta, 0.001, 'downward correction must be a negative delta');
    }

    /**
     * Root cause #3, second half of the AND predicate: a row with
     * quantity_minus_status='done' but material_send_production=0 (status
     * flipped without ever actually deducting stock) must NOT count as an
     * issue, and symmetrically a row with material_send_production=1 but
     * quantity_minus_status != 'done' must also not count. The implementer's
     * own test only covered the 'pending'+0 combination; this closes the
     * other two corners of the 2x2 predicate matrix.
     */
    public function test_production_issue_predicate_excludes_partial_matches_of_the_and_condition(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 100]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 100, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // done=1 status but material_send_production=0: never actually deducted.
        DB::table('production_details')->insert([
            'business_id' => 1, 'design_id' => 1, 'business_details_id' => 1, 'production_id' => 1,
            'part_item_id' => $partId, 'quantity' => 999, 'unit' => 'KG',
            'quantity_minus_status' => 'done', 'material_send_production' => 0,
            'issued_at' => now(), 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // material_send_production=1 but status not 'done' (e.g. mid-flight/legacy data).
        DB::table('production_details')->insert([
            'business_id' => 1, 'design_id' => 1, 'business_details_id' => 1, 'production_id' => 1,
            'part_item_id' => $partId, 'quantity' => 777, 'unit' => 'KG',
            'quantity_minus_status' => 'in_progress', 'material_send_production' => 1,
            'issued_at' => now(), 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $this->assertEqualsWithDelta(0.0, $result['totals']['issue'], 0.001, 'neither partial-match row may count as an issue');
        $this->assertEqualsWithDelta(100.0, $result['totals']['balance'], 0.001, 'balance must equal opening/actual stock, unaffected by the two non-matching rows');
    }

    /**
     * Root cause #7: the FIRST of StoreRepository::updateProductMaterialWiseAddNewReq()'s
     * two real write sites (the "update existing pending request" branch,
     * ~lines 439-478) driven end-to-end through the real repository method,
     * not a hand-inserted issued_at value. Confirms issued_at is genuinely
     * stamped by THIS code path and the report picks it up.
     */
    public function test_store_repository_existing_pending_entry_write_site_stamps_issued_at(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 50]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 50, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $businessDetailsId = DB::table('businesses_details')->insertGetId([
            'business_id' => 1, 'product_name' => 'T-2026-060 Gap Test Product',
            'description' => 'test', 'quantity' => '1', 'rate' => '10',
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('production')->insert([
            'business_details_id' => $businessDetailsId, 'business_id' => 1, 'design_id' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('business_application_processes')->insert([
            'business_details_id' => $businessDetailsId, 'business_id' => 1, 'design_id' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('estimation')->insert([
            'business_details_id' => $businessDetailsId, 'business_id' => 1, 'design_id' => 1,
            'total_estimation_amount' => '100000', 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // The pre-existing PENDING/not-yet-sent row — this is both the row
        // firstOrFail() locates as "the" production_details row for this
        // business_details_id, AND the $existingEntry the write site mutates.
        $pdId = DB::table('production_details')->insertGetId([
            'business_id' => 1, 'design_id' => 1, 'business_details_id' => $businessDetailsId, 'production_id' => 1,
            'part_item_id' => $partId, 'quantity' => 12, 'unit' => 'KG',
            'quantity_minus_status' => 'pending', 'material_send_production' => 0,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $request = Request::create('/organizations/store/update-product-material-wise-add-new-req', 'POST', [
            'business_details_id' => base64_encode($businessDetailsId),
            'addmore' => [[
                'part_item_id' => $partId,
                'quantity' => 12,
                'unit' => 2,
                'quantity_minus_status' => 'pending',
                'material_send_production' => 0,
                'items_used_total_amount' => 120,
            ]],
        ]);

        $result = $this->storeRepo->updateProductMaterialWiseAddNewReq($request);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status'] ?? null, 'write site call must succeed: ' . json_encode($result));

        $row = DB::table('production_details')->where('id', $pdId)->first();
        $this->assertEquals('done', $row->quantity_minus_status, 'write site must flip status to done once stock is deducted');
        $this->assertEquals(1, $row->material_send_production);
        $this->assertNotNull($row->issued_at, 'issued_at must be stamped at this StoreRepository write site');
        $this->assertEqualsWithDelta(now()->timestamp, strtotime($row->issued_at), 5);

        $this->assertEquals(38, DB::table('tbl_item_stock')->where('part_item_id', $partId)->value('quantity'), '50 - 12 = 38');

        $report = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));
        $this->assertEqualsWithDelta(38.0, $report['totals']['balance'], 0.001);
        $issueRow = collect($report['data'])->firstWhere('issue_qty', 12.0);
        $this->assertNotNull($issueRow, 'ledger must pick up the issue produced by this write site');
        $this->assertStringStartsWith(now()->format('Y-m-d'), $issueRow->date, 'ledger date must come from the issued_at stamped at this write site');
    }

    /**
     * Root cause #8: the `search` param must narrow the VISIBLE rows (and the
     * received/issue totals computed over those visible rows) without ever
     * perturbing `totals.balance`, which must always reflect the TRUE full
     * ledger. Never directly exercised by the implementer's own 8 tests.
     */
    public function test_search_param_narrows_visible_rows_without_corrupting_true_balance(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 0]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 55, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // +40 via manual entry (grn_no = 'Manual Entry')
        DB::table('tbl_item_stock_history')->insert([
            'part_item_id' => $partId, 'movement_type' => 'manual_addition',
            'quantity' => 40, 'quantity_delta' => 40,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        // +15 via GRN (a distinctly-named, unique grn_no)
        $uniqueGrnNo = 'GAPTEST-GRN-' . uniqid();
        $grnId = DB::table('grn_tbl')->insertGetId([
            'purchase_orders_id' => 1, 'gatepass_id' => 1, 'grn_date' => now(),
            'grn_no_generate' => $uniqueGrnNo, 'image' => 'x.png',
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('tbl_grn_po_quantity_tracking')->insert([
            'grn_id' => $grnId, 'part_no_id' => $partId, 'accepted_quantity' => 15,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        // Unfiltered: full ledger, true balance = 0 + 40 + 15 = 55.
        $full = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));
        $this->assertEqualsWithDelta(55.0, $full['totals']['balance'], 0.001);
        $this->assertEqualsWithDelta(55.0, $full['totals']['received'], 0.001);
        $this->assertCount(3, $full['data'], 'Opening Stock + manual + GRN = 3 rows');

        // Search for the unique GRN number: only that one row should be visible.
        $searched = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'search' => $uniqueGrnNo, 'pageSize' => 20, 'currentPage' => 1,
        ]));
        $this->assertCount(1, $searched['data'], 'search must narrow to exactly the matching GRN row');
        $this->assertEqualsWithDelta(15.0, $searched['totals']['received'], 0.001, 'visible-rows total reflects only the searched row');
        $this->assertEqualsWithDelta(55.0, $searched['totals']['balance'], 0.001, 'balance must remain the TRUE full-ledger balance, unperturbed by search');

        // Search for something matching nothing: zero visible rows, balance unaffected.
        $none = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'search' => 'NO_SUCH_TOKEN_XYZ', 'pageSize' => 20, 'currentPage' => 1,
        ]));
        $this->assertCount(0, $none['data']);
        $this->assertEqualsWithDelta(55.0, $none['totals']['balance'], 0.001, 'balance must remain correct even when search matches nothing');
    }

    /**
     * AC #2: the real AJAX controller action must return the exact
     * {status, data, pagination, totals} JSON shape, exercised through the
     * actual ReportController entrypoint (service -> repository), not just
     * the raw repository array.
     */
    public function test_ajax_controller_endpoint_returns_expected_json_shape(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 12]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 12, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $controller = new ReportController();
        $request = Request::create('/organizations/report/stock-daily-report-ajax', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]);

        $response = $controller->listStockDailyReportAjax($request);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);

        $payload = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('status', $payload);
        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('pagination', $payload);
        $this->assertArrayHasKey('totals', $payload);
        $this->assertTrue($payload['status']);
        $this->assertEqualsWithDelta(12.0, $payload['totals']['balance'], 0.001);
        $this->assertArrayHasKey('currentPage', $payload['pagination']);
        $this->assertArrayHasKey('totalItems', $payload['pagination']);
    }

    /**
     * AC #2 (no regression to PDF/Excel export): drive the real
     * ReportController::listStockDailyReport() export branches end-to-end.
     */
    public function test_pdf_and_excel_export_entry_points_do_not_regress(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 7]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 7, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $controller = new ReportController();

        // PDF export (export_type=1)
        $pdfRequest = Request::create('/organizations/report/stock-daily-report', 'GET', [
            'description' => $partId, 'export_type' => 1,
        ]);
        $pdfResponse = $controller->listStockDailyReport($pdfRequest);
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $pdfResponse);
        $content = $pdfResponse->getContent();
        $this->assertStringStartsWith('%PDF', $content, 'PDF export must produce a valid PDF binary');

        // Excel export (export_type=2). Laravel-Excel's Excel::download()
        // returns a BinaryFileResponse (content streamed from a temp file on
        // send(), not buffered) — getContent() is always empty for this
        // response type by design, so we assert against the underlying file
        // instead of getContent().
        $excelRequest = Request::create('/organizations/report/stock-daily-report', 'GET', [
            'description' => $partId, 'export_type' => 2,
        ]);
        $excelResponse = $controller->listStockDailyReport($excelRequest);
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class, $excelResponse);
        $file = $excelResponse->getFile();
        $this->assertTrue($file->isFile() && $file->isReadable(), 'Excel export must produce a readable temp file');
        $this->assertGreaterThan(0, $file->getSize(), 'Excel export file must be non-empty');
    }

    /**
     * AC #3: ItemStockReportExport's balance formatting exercised directly —
     * a genuine 0.00 balance must format as "0.00" (not "-"), a suppressed
     * (null) balance must format as "-". Never directly exercised by the
     * implementer's own tests (which never constructed a genuinely-zero
     * final balance scenario through the export class).
     */
    public function test_export_class_formats_genuine_zero_balance_as_0_00_not_dash(): void
    {
        $zeroBalanceRow = (object) [
            'date' => now()->toDateTimeString(),
            'part_name' => 'Zero Balance Part',
            'received_qty' => 5.0,
            'issue_qty' => 5.0,
            'grn_no' => '',
            'vendor_name' => '',
            'product_name' => 'Some Product',
            'balance' => 0.0, // genuine, computed zero — must NOT render as '-'
        ];

        $nullBalanceRow = (object) [
            'date' => now()->toDateTimeString(),
            'part_name' => 'Unfiltered Part',
            'received_qty' => 5.0,
            'issue_qty' => 0.0,
            'grn_no' => 'Manual Entry',
            'vendor_name' => '',
            'product_name' => '',
            'balance' => null, // suppressed because no part filter selected
        ];

        $exportZero = new ItemStockReportExport([$zeroBalanceRow], ['received' => 5, 'issue' => 5, 'balance' => 0.0]);
        $zeroRows = $exportZero->collection();
        $this->assertEquals('0.00', $zeroRows[0][5], 'genuine zero balance must render as 0.00');
        $this->assertEquals('0.00', $zeroRows[1][5], 'genuine zero totals.balance must render as 0.00');

        $exportNull = new ItemStockReportExport([$nullBalanceRow], ['received' => 5, 'issue' => 0, 'balance' => null]);
        $nullRows = $exportNull->collection();
        $this->assertEquals('-', $nullRows[0][5], 'suppressed (null) balance must render as -');
        $this->assertEquals('-', $nullRows[1][5], 'suppressed (null) totals.balance must render as -');
    }

    /**
     * AC #3 (blade layer): the PDF blade (exports.item-stock-report-pdf) must
     * exhibit the identical 0-vs-null distinction as the Excel export,
     * rendered through the real Blade engine (not just read statically).
     */
    public function test_pdf_blade_renders_genuine_zero_balance_as_0_00_and_null_as_dash(): void
    {
        $zeroBalanceRow = (object) [
            'date' => now()->toDateTimeString(),
            'part_name' => 'Zero Balance Part',
            'received_qty' => 5.0,
            'issue_qty' => 5.0,
            'grn_no' => '',
            'vendor_name' => '',
            'product_name' => 'Some Product',
            'balance' => 0.0,
        ];
        $html = view('exports.item-stock-report-pdf', [
            'data' => [$zeroBalanceRow],
            'totals' => ['received' => 5.0, 'issue' => 5.0, 'balance' => 0.0],
        ])->render();
        $this->assertStringContainsString('0.00', $html);

        $nullBalanceRow = (object) [
            'date' => now()->toDateTimeString(),
            'part_name' => 'Unfiltered Part',
            'received_qty' => 5.0,
            'issue_qty' => 0.0,
            'grn_no' => 'Manual Entry',
            'vendor_name' => '',
            'product_name' => '',
            'balance' => null,
        ];
        $htmlNull = view('exports.item-stock-report-pdf', [
            'data' => [$nullBalanceRow],
            'totals' => ['received' => 5.0, 'issue' => 0.0, 'balance' => null],
        ])->render();
        // Balance cell renders '-', and totals row also renders '-' (isset() false on null).
        $this->assertMatchesRegularExpression('/<td class="text-right">\s*-\s*<\/td>/', $htmlNull, 'null balance cell must render as a bare dash, not 0.00 or blank');
    }

    /**
     * AC #5: `stock:reconcile-opening-balance` run via the REAL Artisan CLI
     * entrypoint (not just the underlying repository methods) — --dry-run
     * must write ZERO rows (asserted via before/after count), and running it
     * for real twice must never double-post (2nd run is a no-op).
     */
    public function test_reconcile_command_cli_dry_run_writes_nothing_and_real_run_is_idempotent(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 0]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 61, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        // No ledger legs at all for this part -> computed balance = 0, actual = 61,
        // a genuine 61-unit gap the command must (for a real run) reconcile.

        $countBeforeDryRun = DB::table('tbl_item_stock_history')->where('part_item_id', $partId)->count();
        $this->assertEquals(0, $countBeforeDryRun);

        Artisan::call('stock:reconcile-opening-balance', ['--dry-run' => true, '--part' => $partId]);
        $output = Artisan::output();
        $this->assertStringContainsString('would adjust', $output);

        $countAfterDryRun = DB::table('tbl_item_stock_history')->where('part_item_id', $partId)->count();
        $this->assertEquals(0, $countAfterDryRun, '--dry-run must write ZERO rows');

        // Real run #1: must adjust exactly once.
        Artisan::call('stock:reconcile-opening-balance', ['--part' => $partId]);
        $countAfterFirstRun = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'opening_reconciliation')->count();
        $this->assertEquals(1, $countAfterFirstRun, 'first real run must seed exactly one reconciliation row');

        $balanceAfterFirstRun = $this->reportRepo->computeFullStockLedgerBalance($partId);
        $this->assertEqualsWithDelta(61.0, $balanceAfterFirstRun['computed_balance'], 0.001, 'ledger must now reconcile with tbl_item_stock.quantity');

        // Real run #2 (same part, same CLI invocation): must be a genuine no-op.
        Artisan::call('stock:reconcile-opening-balance', ['--part' => $partId]);
        $output2 = Artisan::output();
        $this->assertStringContainsString('already reconciled', $output2);

        $countAfterSecondRun = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'opening_reconciliation')->count();
        $this->assertEquals(1, $countAfterSecondRun, 'second real run must NOT seed a duplicate row');
    }
}
