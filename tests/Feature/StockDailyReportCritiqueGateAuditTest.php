<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Repository\Organizations\Report\ReportRepository;
use App\Http\Repository\Organizations\Store\StoreRepository;

/**
 * T-2026-060 — MANDATORY CRITIQUE-GATE test-coverage self-audit.
 *
 * This is a dedicated, skeptical, second-pass gap audit performed AFTER the
 * first genuine module_tester dispatch (see tester.md / shared_task_log.md,
 * 2026-08-06T23:30:00Z entry, which itself already closed 5 gaps in
 * tests/Feature/StockDailyReportGapCoverageTest.php and flagged ONE residual
 * gap explicitly). This file:
 *
 *   1. Closes the residual gap explicitly flagged by the prior dispatch:
 *      StoreRepository::updateProductMaterialWiseAddNewReq()'s SECOND
 *      issued_at write site (the "brand-new entry" else-branch,
 *      ~lines 493-546) driven end-to-end through the real method.
 *   2. A regression guard that would fail if a future 4th
 *      material_send_production=1 write site were added without also
 *      stamping issued_at — the exact bug pattern code_reviewer found in
 *      iteration 1 (StoreController's 3rd site originally missed it).
 *   3. Stronger idempotency assertions for stock:reconcile-opening-balance:
 *      not just "no exception" but the exact same history row (same id,
 *      same computed values) after a second real run.
 *   4. A migration re-runnability / migrate:status-clean check via a real
 *      Artisan::call(), scoped to only this task's 2 migrations (there is a
 *      pre-existing, unrelated Pending migration in this project that must
 *      NOT be touched by this test).
 *   5. Windowed opening-balance edge cases not covered by the implementer's
 *      single from_date-only test: year-only (no month), year+month, and
 *      the documented month-only-without-year fallback to the static
 *      tbl_part_item.opening_stock (while the underlying legs are still
 *      calendar-month filtered).
 */
class StockDailyReportCritiqueGateAuditTest extends TestCase
{
    use DatabaseTransactions;

    private ReportRepository $reportRepo;
    private StoreRepository $storeRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportRepo = new ReportRepository();
        $this->storeRepo = new StoreRepository();
    }

    private function makePartItem(array $overrides = []): int
    {
        return DB::table('tbl_part_item')->insertGetId(array_merge([
            'part_number'   => 'T2026060AUDIT-' . uniqid(),
            'description'   => 'T-2026-060 Audit Test Part',
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
     * GAP 1 (explicitly flagged by the prior dispatch): the SECOND of
     * StoreRepository::updateProductMaterialWiseAddNewReq()'s two real
     * issued_at write sites — the "brand-new entry" else-branch
     * (~lines 493-546, entered when NO existing pending/material_send_production=0
     * row is found for this business_details_id) — driven end-to-end through
     * the real repository method, not a hand-inserted issued_at value.
     *
     * To genuinely reach the else-branch, `$existingEntry` (queried by
     * business_details_id + quantity_minus_status='pending' +
     * material_send_production=0 + is_deleted=0) must be null, while
     * `ProductionDetails::where('business_details_id', ...)->firstOrFail()`
     * (an unconditional lookup a few lines earlier) must still find SOME row
     * for this business_details_id — so the fixture seeds one already-'done'
     * production_details row (satisfies firstOrFail(), does not match the
     * stricter $existingEntry filter) before submitting a NEW item for a
     * different part with material_send_production=1 and no
     * quantity_minus_status key at all (the else-branch's own gate
     * condition).
     */
    public function test_store_repository_brand_new_entry_write_site_stamps_issued_at(): void
    {
        $seedPartId = $this->makePartItem(['opening_stock' => 0]);
        $newPartId = $this->makePartItem(['opening_stock' => 60]);

        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $newPartId, 'quantity' => 60, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $businessDetailsId = DB::table('businesses_details')->insertGetId([
            'business_id' => 1, 'product_name' => 'T-2026-060 Audit New-Entry Product',
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

        // Seed row: satisfies the unconditional firstOrFail() lookup on
        // business_details_id, but is ALREADY 'done'/material_send_production=1
        // so it does NOT satisfy $existingEntry's stricter
        // pending+material_send_production=0+is_deleted=0 filter — this is
        // what forces the loop into the else (brand-new-entry) branch.
        DB::table('production_details')->insert([
            'business_id' => 1, 'design_id' => 1, 'business_details_id' => $businessDetailsId, 'production_id' => 1,
            'part_item_id' => $seedPartId, 'quantity' => 5, 'unit' => 2,
            'quantity_minus_status' => 'done', 'material_send_production' => 1,
            'issued_at' => now()->subDays(3), 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now()->subDays(3), 'updated_at' => now()->subDays(3),
        ]);

        $request = Request::create('/organizations/store/update-product-material-wise-add-new-req', 'POST', [
            'business_details_id' => base64_encode($businessDetailsId),
            'addmore' => [[
                // Deliberately NO 'quantity_minus_status' key — the else
                // branch's own gate is
                // `material_send_production == 1 && !array_key_exists('quantity_minus_status', $item)`.
                'part_item_id' => $newPartId,
                'quantity' => 22,
                'unit' => 2,
                'material_send_production' => 1,
                'items_used_total_amount' => 220,
            ]],
        ]);

        $result = $this->storeRepo->updateProductMaterialWiseAddNewReq($request);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status'] ?? null, 'brand-new-entry write site call must succeed: ' . json_encode($result));

        $newRow = DB::table('production_details')
            ->where('business_details_id', $businessDetailsId)
            ->where('part_item_id', $newPartId)
            ->first();

        $this->assertNotNull($newRow, 'the else-branch must INSERT a brand-new production_details row');
        $this->assertEquals('done', $newRow->quantity_minus_status, 'brand-new entry must be flipped to done once stock is deducted');
        $this->assertEquals(1, $newRow->material_send_production);
        $this->assertNotNull($newRow->issued_at, 'issued_at must be stamped at THIS write site (the else/new-entry branch)');
        $this->assertEqualsWithDelta(now()->timestamp, strtotime($newRow->issued_at), 5, 'issued_at must be set to "now" at issuance time, not backdated like the seed row');

        // The seed row (a different write site's data, inserted 3 days ago)
        // must be completely untouched — proves we are asserting on the NEW
        // write, not accidentally re-reading the seed.
        $seedRow = DB::table('production_details')->where('part_item_id', $seedPartId)->first();
        $this->assertEqualsWithDelta(now()->subDays(3)->timestamp, strtotime($seedRow->issued_at), 5, 'seed row must remain untouched');

        $this->assertEquals(38, DB::table('tbl_item_stock')->where('part_item_id', $newPartId)->value('quantity'), '60 - 22 = 38');

        $report = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $newPartId, 'pageSize' => 20, 'currentPage' => 1,
        ]));
        $this->assertEqualsWithDelta(38.0, $report['totals']['balance'], 0.001);
        $issueRow = collect($report['data'])->firstWhere('issue_qty', 22.0);
        $this->assertNotNull($issueRow, 'ledger must pick up the issue produced by the brand-new-entry write site');
        $this->assertStringStartsWith(now()->format('Y-m-d'), $issueRow->date, 'ledger date must come from the issued_at stamped at THIS write site');
    }

    /**
     * GAP 2 (regression guard): would catch a regression if a future 4th
     * write site set `material_send_production = 1` without also stamping
     * `issued_at` nearby — the EXACT bug pattern code_reviewer found in
     * iteration 1 (StoreController::issueAvailableMaterials() originally
     * missed the stamp). This is a static-analysis-style assertion over the
     * real source of the 2 files that own all 3 documented real write sites,
     * not a DB/runtime test — deliberately so it fails FAST (no DB fixture
     * required) the moment a new site is added incorrectly, and it is
     * insensitive to which exact line numbers the sites live at.
     */
    public function test_regression_guard_every_material_send_production_write_site_also_stamps_issued_at(): void
    {
        $files = [
            base_path('app/Http/Repository/Organizations/Store/StoreRepository.php'),
            base_path('app/Http/Controllers/Organizations/Store/StoreController.php'),
        ];

        $totalSitesChecked = 0;

        foreach ($files as $file) {
            $this->assertFileExists($file);
            $contents = file_get_contents($file);
            $lines = explode("\n", $contents);

            // Match assignments of the form `$var->material_send_production = 1;`
            // (the only real WRITE pattern for this predicate half; plain
            // ->where('material_send_production', 1) READ filters, which
            // exist elsewhere in the codebase, e.g. ProductionRepository.php,
            // are correctly NOT matched by this pattern).
            foreach ($lines as $lineNumber => $lineContent) {
                if (preg_match('/->material_send_production\s*=\s*1\s*;/', $lineContent)) {
                    $totalSitesChecked++;

                    $windowStart = max(0, $lineNumber - 15);
                    $windowEnd = min(count($lines) - 1, $lineNumber + 15);
                    $window = implode("\n", array_slice($lines, $windowStart, $windowEnd - $windowStart + 1));

                    $this->assertMatchesRegularExpression(
                        '/->issued_at\s*=\s*now\(\)/',
                        $window,
                        sprintf(
                            "REGRESSION: %s line %d sets material_send_production=1 but no `->issued_at = now()` stamp was found within +/-15 lines. ".
                            "Every real stock-deduction write site must stamp issued_at atomically with flipping this flag (T-2026-060 root cause #7); ".
                            "silently omitting it makes the Stock Daily Report fall back to the fragile updated_at column, reproducing the exact ".
                            "iteration-1 code_reviewer finding for StoreController::issueAvailableMaterials().",
                            $file,
                            $lineNumber + 1
                        )
                    );
                }
            }
        }

        // Sanity: this guard is only meaningful if it actually found the 3
        // documented real write-site assignment lines (2 in StoreRepository's
        // existing-entry/new-entry branches' own dead-code if/else mirrors,
        // 2 more in the new-entry branch's own if/else mirror, 1 in
        // StoreController) — if this drops to 0 the regex itself is broken
        // and the guard is silently vacuous.
        $this->assertGreaterThanOrEqual(3, $totalSitesChecked, 'regression guard found suspiciously few write sites — pattern may be stale/broken, making this test vacuously green');
    }

    /**
     * GAP 3 (stronger idempotency): the prior dispatch's idempotency test
     * only asserted the reconciliation row COUNT stays at 1 after a second
     * real run. This closes the gap by asserting the row's PRIMARY KEY and
     * its computed values (quantity_delta / balance_after) are byte-identical
     * before and after the second run — proving the command is a genuine
     * no-op on repeat, not merely "didn't insert a 2nd row for some other
     * reason".
     */
    public function test_reconcile_command_second_real_run_leaves_the_exact_same_row_untouched(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 0]);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 47, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        Artisan::call('stock:reconcile-opening-balance', ['--part' => $partId]);

        $rowAfterFirstRun = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'opening_reconciliation')->first();
        $this->assertNotNull($rowAfterFirstRun);

        $balanceAfterFirstRun = $this->reportRepo->computeFullStockLedgerBalance($partId);
        $this->assertEqualsWithDelta(47.0, $balanceAfterFirstRun['computed_balance'], 0.001);

        Artisan::call('stock:reconcile-opening-balance', ['--part' => $partId]);

        $rowAfterSecondRun = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)->where('movement_type', 'opening_reconciliation')->first();

        $this->assertEquals($rowAfterFirstRun->id, $rowAfterSecondRun->id, 'second real run must not touch/replace the existing reconciliation row (same primary key)');
        $this->assertEqualsWithDelta((float) $rowAfterFirstRun->quantity_delta, (float) $rowAfterSecondRun->quantity_delta, 0.001);
        $this->assertEqualsWithDelta((float) $rowAfterFirstRun->balance_after, (float) $rowAfterSecondRun->balance_after, 0.001);
        $this->assertEquals((string) $rowAfterFirstRun->created_at, (string) $rowAfterSecondRun->created_at, 'row must not be re-touched/re-saved by the second run');

        $balanceAfterSecondRun = $this->reportRepo->computeFullStockLedgerBalance($partId);
        $this->assertEqualsWithDelta(47.0, $balanceAfterSecondRun['computed_balance'], 0.001, 'computed ledger balance must be identical after the idempotent second run');

        $this->assertEquals(
            1,
            DB::table('tbl_item_stock_history')->where('part_item_id', $partId)->where('movement_type', 'opening_reconciliation')->count(),
            'exactly one reconciliation row total, never doubled'
        );
    }

    /**
     * GAP 4 (migration re-runnability / migrate:status clean): a real
     * Artisan::call('migrate', ...) scoped ONLY to this task's 2 migration
     * files (there is a pre-existing, unrelated Pending migration in this
     * project — 2024_09_18_154029_2024_05_27_065437_create_tbl_item_stock_history
     * — that this test must NOT run, since applying it is out of scope for
     * T-2026-060 and could have unrelated side effects). Both of this task's
     * migrations are already "Ran" (confirmed live below via migrate:status
     * before this test even runs its own migrate call), so re-invoking
     * `migrate` scoped to just these 2 files must be a genuine, error-free
     * no-op ("Nothing to migrate").
     */
    public function test_migrations_are_safely_rerunnable_and_migrate_status_is_clean(): void
    {
        $migrationFiles = [
            'database/migrations/2026_08_06_100000_add_movement_tracking_columns_to_tbl_item_stock_history_table.php',
            'database/migrations/2026_08_06_100100_add_issued_at_to_production_details_table.php',
        ];

        foreach ($migrationFiles as $relativePath) {
            $this->assertFileExists(base_path($relativePath));
        }

        // Confirm both are already "Ran" via the real Artisan migrate:status.
        Artisan::call('migrate:status');
        $statusOutput = Artisan::output();
        $this->assertMatchesRegularExpression(
            '/2026_08_06_100000_add_movement_tracking_columns_to_tbl_item_stock_history_table.*Ran/',
            $statusOutput
        );
        $this->assertMatchesRegularExpression(
            '/2026_08_06_100100_add_issued_at_to_production_details_table.*Ran/',
            $statusOutput
        );

        // Re-run migrate scoped ONLY to these 2 files (via --path, which is
        // additive/restrictive, not a full `migrate` invocation) — must be a
        // clean, error-free no-op since they are already applied.
        $exitCode = Artisan::call('migrate', [
            '--path' => $migrationFiles,
            '--force' => true,
        ]);
        $migrateOutput = Artisan::output();

        $this->assertEquals(0, $exitCode, 'scoped re-migrate must exit cleanly: ' . $migrateOutput);
        $this->assertStringContainsString('Nothing to migrate', $migrateOutput, 'both migrations are already Ran, so a scoped re-run must be a genuine no-op, not a re-apply attempt');

        // Confirm the columns these migrations add are still present and the
        // schema was not perturbed by the re-run attempt.
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumns('tbl_item_stock_history', ['movement_type', 'quantity_delta', 'balance_after', 'remark']));
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('production_details', 'issued_at'));

        // Sanity: the known pre-existing unrelated Pending migration in this
        // project must remain exactly where it was — confirms this test's
        // --path scoping did not accidentally sweep it in.
        Artisan::call('migrate:status');
        $statusOutputAfter = Artisan::output();
        $this->assertMatchesRegularExpression(
            '/2024_09_18_154029_2024_05_27_065437_create_tbl_item_stock_history.*Pending/',
            $statusOutputAfter,
            'this test must not have touched the pre-existing unrelated Pending migration'
        );
    }

    /**
     * GAP 5a (windowed opening balance, year-only filter, no month): the
     * implementer's own test only covers `from_date`. `resolveStockReportWindowStart()`
     * has a distinct branch for `year` filled + `month` NOT filled ->
     * Carbon::createFromDate($year, 1, 1). This closes that branch.
     */
    public function test_windowed_opening_balance_with_year_only_filter(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 100, 'created_at' => '2024-01-01 00:00:00']);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 170, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => '2024-01-01 00:00:00', 'updated_at' => now(),
        ]);

        // +50 BEFORE the 2026 window (in 2025).
        $grnId = DB::table('grn_tbl')->insertGetId([
            'purchase_orders_id' => 1, 'gatepass_id' => 1, 'grn_date' => '2025-06-01',
            'grn_no_generate' => 'GRN-YEARONLY-' . uniqid(), 'image' => 'x.png',
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2025-06-01', 'updated_at' => '2025-06-01',
        ]);
        DB::table('tbl_grn_po_quantity_tracking')->insert([
            'grn_id' => $grnId, 'part_no_id' => $partId, 'accepted_quantity' => 50,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2025-06-01', 'updated_at' => '2025-06-01',
        ]);

        // +20 INSIDE the 2026 window.
        DB::table('tbl_item_stock_history')->insert([
            'part_item_id' => $partId, 'movement_type' => 'manual_addition',
            'quantity' => 20, 'quantity_delta' => 20,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-03-01', 'updated_at' => '2026-03-01',
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'year' => 2026, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $firstRow = $result['data'][0];
        $this->assertEquals('Opening Stock', $firstRow->grn_no);
        $this->assertEqualsWithDelta(150.0, $firstRow->received_qty, 0.001, '100 static opening + 50 pre-2026-window GRN');
        $this->assertStringStartsWith('2025-12-31', $firstRow->date, 'opening leg date must be just before Jan 1 2026 (year-only window start)');

        $manualRow = collect($result['data'])->firstWhere('received_qty', 20.0);
        $this->assertNotNull($manualRow, 'the in-window (2026) manual movement must be present');

        $this->assertEqualsWithDelta(170.0, $result['totals']['balance'], 0.001, '150 windowed opening + 20 in-window movement');
    }

    /**
     * GAP 5b (windowed opening balance, year+month filter together):
     * `resolveStockReportWindowStart()` has a third branch —
     * year+month both filled -> Carbon::createFromDate($year, $month, 1).
     * Never exercised by any prior test in this task.
     */
    public function test_windowed_opening_balance_with_year_and_month_filter(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 10, 'created_at' => '2025-01-01 00:00:00']);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 55, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => '2025-01-01 00:00:00', 'updated_at' => now(),
        ]);

        // +30 BEFORE the March-2026 window (Feb 2026).
        DB::table('tbl_item_stock_history')->insert([
            'part_item_id' => $partId, 'movement_type' => 'manual_addition',
            'quantity' => 30, 'quantity_delta' => 30,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-02-15', 'updated_at' => '2026-02-15',
        ]);

        // +15 INSIDE the March-2026 window.
        DB::table('tbl_item_stock_history')->insert([
            'part_item_id' => $partId, 'movement_type' => 'manual_addition',
            'quantity' => 15, 'quantity_delta' => 15,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-03-10', 'updated_at' => '2026-03-10',
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'year' => 2026, 'month' => 3, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $firstRow = $result['data'][0];
        $this->assertEquals('Opening Stock', $firstRow->grn_no);
        $this->assertEqualsWithDelta(40.0, $firstRow->received_qty, 0.001, '10 static opening + 30 pre-March-2026-window movement');
        $this->assertStringStartsWith('2026-02-28', $firstRow->date, 'opening leg date must be just before March 1 2026 (year+month window start)');

        $inWindowRow = collect($result['data'])->firstWhere('received_qty', 15.0);
        $this->assertNotNull($inWindowRow, 'the in-window (March 2026) movement must be present');

        // Feb movement must NOT appear as its own leg row (it is folded into
        // the windowed opening figure, not double-counted as a separate row).
        $febRowAsLeg = collect($result['data'])->first(function ($row) {
            return $row->grn_no !== 'Opening Stock' && (float) $row->received_qty === 30.0;
        });
        $this->assertNull($febRowAsLeg, 'the pre-window Feb movement must not ALSO appear as a separate leg row (would double-count it)');

        $this->assertEqualsWithDelta(55.0, $result['totals']['balance'], 0.001, '40 windowed opening + 15 in-window movement');
    }

    /**
     * GAP 5c (documented month-only-without-year fallback): per
     * `resolveStockReportWindowStart()`'s own docblock, a month filter
     * WITHOUT a year has no single contiguous "before" boundary, so the
     * report falls back to the STATIC tbl_part_item.opening_stock (same as
     * fully unfiltered) for the opening leg specifically — while the
     * underlying legs are STILL calendar-month filtered (matching that month
     * across any year) via applyStockReportDateFilters(), which is applied
     * independently of resolveStockReportWindowStart(). This dual behavior
     * (opening leg unwindowed, other legs still month-filtered) was
     * explicitly documented by the implementer but never exercised by any
     * test in this task until now.
     */
    public function test_month_only_without_year_falls_back_to_static_opening_stock(): void
    {
        $partId = $this->makePartItem(['opening_stock' => 100, 'created_at' => '2024-01-01 00:00:00']);
        DB::table('tbl_item_stock')->insert([
            'part_item_id' => $partId, 'quantity' => 130, 'is_active' => 1, 'is_deleted' => 0,
            'created_at' => '2024-01-01 00:00:00', 'updated_at' => now(),
        ]);

        // A GRN dated in MARCH (matches month=3, regardless of year).
        $grnMarch = DB::table('grn_tbl')->insertGetId([
            'purchase_orders_id' => 1, 'gatepass_id' => 1, 'grn_date' => '2025-03-05',
            'grn_no_generate' => 'GRN-MARCH-' . uniqid(), 'image' => 'x.png',
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2025-03-05', 'updated_at' => '2025-03-05',
        ]);
        DB::table('tbl_grn_po_quantity_tracking')->insert([
            'grn_id' => $grnMarch, 'part_no_id' => $partId, 'accepted_quantity' => 30,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2025-03-05', 'updated_at' => '2025-03-05',
        ]);

        // A GRN dated in APRIL (must NOT match month=3 filter).
        $grnApril = DB::table('grn_tbl')->insertGetId([
            'purchase_orders_id' => 1, 'gatepass_id' => 1, 'grn_date' => '2026-04-05',
            'grn_no_generate' => 'GRN-APRIL-' . uniqid(), 'image' => 'x.png',
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-04-05', 'updated_at' => '2026-04-05',
        ]);
        DB::table('tbl_grn_po_quantity_tracking')->insert([
            'grn_id' => $grnApril, 'part_no_id' => $partId, 'accepted_quantity' => 999,
            'is_active' => 1, 'is_deleted' => 0, 'created_at' => '2026-04-05', 'updated_at' => '2026-04-05',
        ]);

        $result = $this->reportRepo->listStockDailyReport(Request::create('/x', 'GET', [
            'description' => $partId, 'month' => 3, 'pageSize' => 20, 'currentPage' => 1,
        ]));

        $firstRow = $result['data'][0];
        $this->assertEquals('Opening Stock', $firstRow->grn_no);
        // Falls back to the STATIC opening_stock value — NOT windowed/summed,
        // unlike every other test in this file.
        $this->assertEqualsWithDelta(100.0, $firstRow->received_qty, 0.001, 'month-only-without-year must fall back to the static opening_stock, not a windowed sum');
        $this->assertEquals('2024-01-01 00:00:00', (string) $firstRow->date, 'opening leg date must be the raw part created_at, unmodified (no window subtraction)');

        // Only the March row is visible; the April (999-qty) row must be
        // excluded by the still-applied month=3 filter.
        $this->assertCount(2, $result['data'], 'Opening Stock + the one March-matching GRN row only');
        $marchRow = collect($result['data'])->firstWhere('received_qty', 30.0);
        $this->assertNotNull($marchRow, 'the March-matching GRN row must be present');
        $aprilRow = collect($result['data'])->firstWhere('received_qty', 999.0);
        $this->assertNull($aprilRow, 'the April GRN row must be excluded by the month=3 filter');

        $this->assertEqualsWithDelta(130.0, $result['totals']['balance'], 0.001, '100 static opening + 30 March-matching row (April 999 excluded by filter)');
    }
}
