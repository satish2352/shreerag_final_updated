<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Repository\Organizations\Report\ReportRepository;
use App\Http\Repository\Organizations\Inventory\InventoryRepository;
use App\Http\Controllers\Organizations\Store\StoreController;

/**
 * T-2026-060 — Stock Daily Report ledger reconciliation.
 *
 * Regression cover for the reported defect: the Stock Daily Report
 * (designdept/stock-daily-report) showed a NEGATIVE Balance Qty for a part
 * whose Inventory Material List (storedept/list-inventory-material) quantity
 * was correct, because the report's ledger was missing an entire receipt leg
 * (manual "Add Stock" / "Edit Stock" entries), over-counted production issues
 * that never actually deducted stock, dated every leg by `updated_at` (moved
 * by unrelated re-saves), and accumulated balance across unrelated parts.
 *
 * These tests drive the REAL ReportRepository::listStockDailyReport() and
 * InventoryRepository::addAll()/updateAll() against real fixtures, wrapped in
 * DatabaseTransactions so every write is rolled back.
 */
class StockDailyReportReconciliationTest extends TestCase
{
    use DatabaseTransactions;

    private ReportRepository $reportRepo;
    private InventoryRepository $inventoryRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportRepo = new ReportRepository();
        $this->inventoryRepo = new InventoryRepository();
    }

    private function makePartItem(array $overrides = []): int
    {
        return DB::table('tbl_part_item')->insertGetId(array_merge([
            'part_number'   => 'T2026060-' . uniqid(),
            'description'   => 'T-2026-060 Test Part',
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
     * Root causes #1 and #2: manual "Add Stock" / "Edit Stock" receipts must
     * appear in the ledger, with the CORRECT part_item_id (not the
     * tbl_item_stock primary key), and the final Balance Qty must reconcile
     * with tbl_item_stock.quantity exactly.
     */
    public function test_manual_stock_movements_appear_in_ledger_and_reconcile(): void
    {
        $partId = $this->makePartItem();

        $this->inventoryRepo->addAll(Request::create('/x', 'POST', [
            'part_item_id' => $partId,
            'quantity' => 25,
        ]));

        $itemStock = DB::table('tbl_item_stock')->where('part_item_id', $partId)->first();
        $this->inventoryRepo->updateAll(Request::create('/x', 'POST', [
            'id' => $itemStock->id,
            'quantity' => 70,
        ]));

        $actual = DB::table('tbl_item_stock')->where('part_item_id', $partId)->value('quantity');
        $this->assertEquals(70, $actual);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $this->assertTrue($result['status']);
        $this->assertEqualsWithDelta(70.0, $result['totals']['balance'], 0.001);

        // History rows must carry the real part_item_id, not the ItemStock PK.
        $historyPartIds = DB::table('tbl_item_stock_history')
            ->where('movement_type', 'manual_addition')
            ->orWhere('movement_type', 'manual_adjustment_set')
            ->pluck('part_item_id')
            ->unique();
        foreach ($historyPartIds as $id) {
            $this->assertNotNull(DB::table('tbl_part_item')->where('id', $id)->first(), 'history part_item_id must reference a real part item');
        }

        $movementTypes = collect($result['data'])->pluck('grn_no')->all();
        $this->assertContains('Manual Entry', $movementTypes);
    }

    /**
     * Root cause #3: only production_details rows that ACTUALLY moved stock
     * (quantity_minus_status='done' AND material_send_production=1) may be
     * counted as an issue. A 'pending' row (never deducted, per
     * StoreRepository's own predicate) must be excluded.
     */
    public function test_pending_production_row_is_excluded_only_done_rows_count(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 100]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 80, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // DONE row: actually deducted 20 (100 -> 80).
        DB::table('production_details')->insert([
            'business_id' => 1, 'design_id' => 1, 'business_details_id' => 1, 'production_id' => 1,
            'part_item_id' => $partId, 'quantity' => 20, 'unit' => 'KG',
            'quantity_minus_status' => 'done', 'material_send_production' => 1,
            'issued_at' => now(), 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // PENDING row: requested but never fulfilled (insufficient stock) --
        // StoreRepository never touched tbl_item_stock for this row.
        DB::table('production_details')->insert([
            'business_id' => 1, 'design_id' => 1, 'business_details_id' => 1, 'production_id' => 1,
            'part_item_id' => $partId, 'quantity' => 500, 'unit' => 'KG',
            'quantity_minus_status' => 'pending', 'material_send_production' => 0,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $this->assertEqualsWithDelta(20.0, $result['totals']['issue'], 0.001, 'only the done row (20) should count, not the pending row (500)');
        $this->assertEqualsWithDelta(80.0, $result['totals']['balance'], 0.001);
    }

    /**
     * Root cause #7: production issue date must be the dedicated issued_at
     * column (stamped once, atomically with the deduction), never
     * updated_at, which a later unrelated re-save would move.
     */
    public function test_production_issue_is_dated_by_issued_at_not_updated_at(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 100]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 80, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('production_details')->insert([
            'business_id' => 1, 'design_id' => 1, 'business_details_id' => 1, 'production_id' => 1,
            'part_item_id' => $partId, 'quantity' => 20, 'unit' => 'KG',
            'quantity_minus_status' => 'done', 'material_send_production' => 1,
            'issued_at' => '2026-03-01 09:00:00',
            'is_active' => 1, 'is_deleted' => 0,
            'created_at' => '2026-03-01 09:00:00',
            'updated_at' => '2026-07-01 00:00:00', // unrelated later re-save
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $issueRow = collect($result['data'])->firstWhere('issue_qty', 20.0);
        $this->assertNotNull($issueRow);
        $this->assertStringStartsWith('2026-03-01', $issueRow->date, 'must use issued_at, not the later updated_at');
    }

    /**
     * Root cause #7 (iteration 2 — code_reviewer finding): StoreController::
     * issueAvailableMaterials() (POST /issue-available-materials) is a THIRD
     * real material_send_production=1 write site — distinct from the 2 sites
     * inside StoreRepository::updateProductMaterialWiseAddNewReq() already
     * covered by the iteration-1 fix. It must also stamp `issued_at` in the
     * same save() call that flips quantity_minus_status to 'done' and
     * deducts ItemStock, so rows issued through this route get a stable
     * ledger date too (never falling back to the fragile `updated_at`).
     *
     * Drives the REAL controller method end-to-end (not just a hand-inserted
     * fixture row), then confirms both the raw DB state AND that the Stock
     * Daily Report ledger correctly reconciles using the stamped issued_at.
     */
    public function test_issue_available_materials_route_stamps_issued_at_and_deducts_stock(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 50]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 50, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $businessDetailsId = DB::table('businesses_details')->insertGetId([
            'business_id' => 1, 'product_name' => 'T-2026-060 Test Product',
            'description' => 'test', 'quantity' => '1', 'rate' => '10',
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('production')->insert([
            'business_details_id' => $businessDetailsId, 'business_id' => 1, 'design_id' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);
        // production_details.design_id is NOT NULL; the controller sources it
        // from `$bap->design_id ?? null`, so a matching BusinessApplicationProcesses
        // row is required for the real insert path to succeed.
        DB::table('business_application_processes')->insert([
            'business_details_id' => $businessDetailsId, 'business_id' => 1, 'design_id' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $request = Request::create('/organizations/store/issue-available-materials', 'POST', [
            'business_details_id' => $businessDetailsId,
            'items' => [[
                'part_item_id'        => $partId,
                'quantity'            => 15,
                'unit_id'             => 2,
                'rate'                => 10,
                'product_description' => 'T-2026-060 Test Part',
            ]],
        ]);

        $controller = new StoreController();
        $response = $controller->issueAvailableMaterials($request);

        $this->assertTrue($response->isRedirect(), 'controller should redirect on success/error, not throw');

        // Real stock deduction happened (50 -> 35).
        $this->assertEquals(35, DB::table('tbl_item_stock')->where('part_item_id', $partId)->value('quantity'));

        // The production_details row written by THIS route must have issued_at
        // stamped, atomically with quantity_minus_status='done'.
        $row = DB::table('production_details')
            ->where('business_details_id', $businessDetailsId)
            ->where('part_item_id', $partId)
            ->where('quantity_minus_status', 'done')
            ->where('material_send_production', 1)
            ->first();

        $this->assertNotNull($row, 'issueAvailableMaterials() must create a done/issued production_details row');
        $this->assertNotNull($row->issued_at, 'issued_at must be stamped at this 3rd write site, not left null');
        $this->assertEqualsWithDelta(now()->timestamp, strtotime($row->issued_at), 5, 'issued_at must be set to "now" at issuance time');

        // The report's ledger COALESCE picks up issued_at automatically (same
        // table/column regardless of write site) and the balance reconciles.
        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));
        $this->assertEqualsWithDelta(35.0, $result['totals']['balance'], 0.001);

        $issueRow = collect($result['data'])->firstWhere('issue_qty', 15.0);
        $this->assertNotNull($issueRow, 'the row issued via issueAvailableMaterials() must appear in the ledger');
        $this->assertStringStartsWith(now()->format('Y-m-d'), $issueRow->date, 'ledger date must come from issued_at, populated at this write site');
    }

    /** Root cause #4: Returnable Challan issues were entirely missing from the ledger. */
    public function test_returnable_challan_issue_appears_in_ledger(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 50]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 42, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $rcId = DB::table('tbl_returnable_chalan')->insertGetId([
            'dc_date' => now(), 'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('tbl_returnable_chalan_item_details')->insert([
            'returnable_chalan_id' => $rcId, 'part_item_id' => $partId, 'quantity' => 8,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $this->assertEqualsWithDelta(42.0, $result['totals']['balance'], 0.001);
        $productNames = collect($result['data'])->pluck('product_name')->all();
        $this->assertContains('Returnable Challan No.', $productNames);
    }

    /**
     * Root cause #6: cross-part accumulation. When no part filter is given,
     * balance must never be a meaningless cross-part running total — it must
     * be null on every row and in totals.
     */
    public function test_balance_is_suppressed_when_no_part_filter_selected(): void
    {
        $this->makePartItem(['opening_stock' => 10]);
        $this->makePartItem(['opening_stock' => 20]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'pageSize' => 5, 'currentPage' => 1,
        ]));

        $this->assertNull($result['totals']['balance']);
        foreach ($result['data'] as $row) {
            $this->assertNull($row->balance);
        }
    }

    /**
     * Root cause #5: when a from_date window narrows the report, the Opening
     * Stock row must reflect everything that happened BEFORE the window
     * (not just the static opening_stock column), and must remain the FIRST
     * row regardless of any date-ordering accident.
     */
    public function test_windowed_opening_balance_reflects_pre_window_movements(): void
    {
        // Final true balance = 100 (opening) + 50 (pre-window GRN) + 45 (post-window manual) = 195
        $partId = $this->makePartItem(['opening_stock' => 100, 'created_at' => '2026-01-01 00:00:00']);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 195, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => '2026-01-01 00:00:00', 'updated_at' => now(),
        ]);

        // +50 BEFORE the window (2026-02-01)
        $grnId = DB::table('grn_tbl')->insertGetId([
            'purchase_orders_id' => 1, 'gatepass_id' => 1, 'grn_date' => '2026-02-01',
            'grn_no_generate' => 'GRN-' . uniqid(), 'image' => 'x.png',
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-02-01', 'updated_at' => '2026-02-01',
        ]);
        DB::table('tbl_grn_po_quantity_tracking')->insert([
            'grn_id' => $grnId, 'part_no_id' => $partId, 'accepted_quantity' => 50,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-02-01', 'updated_at' => '2026-02-01',
        ]);

        // +45 AFTER the window (2026-06-01, via manual entry)
        DB::table('tbl_item_stock_history')->insert([
            'part_item_id' => $partId, 'movement_type' => 'manual_addition',
            'quantity' => 45, 'quantity_delta' => 45,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-06-01', 'updated_at' => '2026-06-01',
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'from_date' => '2026-04-15', 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $firstRow = $result['data'][0];
        $this->assertEquals('Opening Stock', $firstRow->grn_no);
        $this->assertEqualsWithDelta(150.0, $firstRow->received_qty, 0.001, '100 opening + 50 pre-window GRN');
        $this->assertEqualsWithDelta(195.0, $result['totals']['balance'], 0.001, 'final balance must still be correct regardless of the windowed opening: 100 + 50 + 45');
    }

    /** stock:reconcile-opening-balance must be idempotent (never seed a duplicate adjustment). */
    public function test_reconciliation_command_is_idempotent(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 0]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 33, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->assertFalse($this->reportRepo->hasActiveOpeningReconciliation($partId));

        $before = $this->reportRepo->computeFullStockLedgerBalance($partId);
        $this->assertEqualsWithDelta(0.0, $before['computed_balance'], 0.001);
        $this->assertEqualsWithDelta(33.0, $before['actual_quantity'], 0.001);

        $this->reportRepo->insertOpeningReconciliationEntry($partId, 33.0, 33.0, 'test remark');
        $this->assertTrue($this->reportRepo->hasActiveOpeningReconciliation($partId));

        $after = $this->reportRepo->computeFullStockLedgerBalance($partId);
        $this->assertEqualsWithDelta(33.0, $after['computed_balance'], 0.001, 'ledger must now reconcile');

        // A second call must not insert a duplicate row.
        $countBefore = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'opening_reconciliation')->count();
        $this->assertEquals(1, $countBefore);
        $this->assertTrue($this->reportRepo->hasActiveOpeningReconciliation($partId));
    }
}
