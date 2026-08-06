<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BusinessApplicationProcesses;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\UnitMaster;
use App\Models\PartItem;
use App\Models\DesignModel;
use App\Http\Controllers\Organizations\Store\AllListController;
use App\Http\Controllers\Organizations\Store\StoreController;

/**
 * T-2026-059 — MANDATORY TEST-COVERAGE SELF-AUDIT (2026-07-31, module_tester).
 *
 * Follow-up to the first GENUINE module_tester dispatch (tester.md
 * "T-2026-059 (2026-07-31T20:00:00Z)"). This file is NOT a re-run of that
 * pass — every test here targets a gap identified by a fresh, skeptical
 * re-read of the production code (StoreController.php, AllListController.php,
 * AllListRepository.php, list-material-sent-to-purchase.blade.php) that the
 * prior pass's 7 tests did not actually exercise, even though some of the
 * prior pass's prose summary could be misread as covering them.
 *
 * Gaps closed here:
 *   - Print/CSV export handlers structurally read from the SAME modal DOM as
 *     the header (audit item 1) — verified via rendered-HTML assertions, not
 *     just a code-read argument.
 *   - Multi-requisition-per-project edge case (audit item 2) — genuinely
 *     reachable (no DB unique constraint on requisition.business_details_id;
 *     see also the standalone concurrency note for audit item 3) — a REAL
 *     test demonstrating current (broken) behavior when it occurs.
 *   - MTR-vs-METER case sensitivity specifically through the WRITE path
 *     (storeAdditionalShortageRequisition(), already_scaled=0) — audit item 5.
 *   - Null-part_item_id BOM row through BOTH the write path
 *     (storeShortageRequisition()) and re-read (showBomInventoryCheck())
 *     — audit item 6 — which uncovers a genuine double-surfacing display bug.
 *   - Real 3-page pagination boundary correctness with distinct fixtures,
 *     asserting zero duplication/drop across page boundaries — audit item 7.
 */
class StoreTrolleyRequisitionCoverageAuditTest extends TestCase
{
    use DatabaseTransactions;

    private $storeController;
    private $allListController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeController   = new StoreController();
        $this->allListController = new AllListController();
    }

    private function makePartItem(string $description, int $unitId): PartItem
    {
        return PartItem::create([
            'part_number'   => 'T2026059AUDIT-' . uniqid(),
            'description'   => $description,
            'unit_id'       => $unitId,
            'hsn_id'        => 1,
            'group_type_id' => 1,
            'basic_rate'    => '10',
            'opening_stock' => '0',
            'is_active'     => 1,
            'is_deleted'    => 0,
        ]);
    }

    private function setStock(int $partItemId, float $qty): void
    {
        \App\Models\ItemStock::create([
            'part_item_id' => $partItemId,
            'quantity'     => $qty,
            'is_active'    => 1,
            'is_deleted'   => 0,
        ]);
    }

    private $sentStatusId;

    private function sentStatusId(): int
    {
        if ($this->sentStatusId === null) {
            $this->sentStatusId = (int) config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');
        }
        return $this->sentStatusId;
    }

    private function makeProject(string $productName, int $trolleyQty, ?int $storeStatusId = null): array
    {
        $bizId = DB::table('businesses')->insertGetId([
            'organization_id'          => 1,
            'project_name'             => $productName . ' Project',
            'customer_po_number'       => 'PO-T2026059AUDIT-' . uniqid(),
            'title'                    => $productName,
            'po_validity'              => date('Y-m-d'),
            'customer_payment_terms'   => 'NA',
            'customer_terms_condition' => 'NA',
            'is_active'                => 1,
            'is_deleted'               => 0,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        $bdId = DB::table('businesses_details')->insertGetId([
            'business_id'  => $bizId,
            'product_name' => $productName,
            'description'  => 'T-2026-059 coverage-audit fixture',
            'quantity'     => 1,
            'rate'         => 0,
            'is_active'    => 1,
            'is_deleted'   => 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        DesignModel::create([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'design_image'        => 'fixture.png',
            'trolley_qty'         => $trolleyQty,
            'is_approve'          => 1,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        $bap = BusinessApplicationProcesses::create([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'store_status_id'     => $storeStatusId,
            'business_status_id'  => 0,
            'design_id'           => 0,
            'production_id'       => 0,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        return ['bdId' => $bdId, 'bizId' => $bizId, 'bapId' => $bap->id];
    }

    // ==================================================================
    // GAP 1 — Print/CSV export handlers: verify they are structurally tied
    // to the SAME record as the modal header (not a separate derivation),
    // via an actual rendered-HTML assertion, for both distinct-name and
    // identical-name+description scenarios.
    // ==================================================================
    public function test_gap1_print_and_csv_handlers_use_same_record_as_header(): void
    {
        $unit = UnitMaster::first();
        $suffix = uniqid();

        $partA = $this->makePartItem('Gap1 part A', $unit->id);
        $partB = $this->makePartItem('Gap1 part B', $unit->id);

        // Two projects sharing an IDENTICAL product_name+description (the old
        // collapsing key) — the strongest case for proving the print/csv
        // buttons cannot cross-reference the wrong project's name.
        $sharedName = "Gap1Shared-{$suffix}";
        $sharedDesc = 'Gap1 shared description';
        $item1 = "Gap1OwnItemA-{$suffix}";
        $item2 = "Gap1OwnItemB-{$suffix}";

        [$reqIdA] = $this->makeSentProjectWithItem($sharedName, $sharedDesc, $item1, $partA->id, $unit->id, "Gap1 Customer A - {$suffix}");
        [$reqIdB] = $this->makeSentProjectWithItem($sharedName, $sharedDesc, $item2, $partB->id, $unit->id, "Gap1 Customer B - {$suffix}");

        $view = $this->allListController->getAllListMaterialSentToPurchase();
        $data = $view->getData();
        $dataOutput = $data['data_output'];
        $requisitionItemsMap = $data['requisitionItemsMap'];

        $html = view('organizations.store.list.list-material-sent-to-purchase', [
            'data_output' => $dataOutput, 'requisitionItemsMap' => $requisitionItemsMap,
        ])->render();

        foreach ([$reqIdA => $item1, $reqIdB => $item2] as $reqId => $ownItem) {
            $modalStart = strpos($html, 'id="storeBomModal' . $reqId . '"');
            $this->assertNotFalse($modalStart, "Modal for requisition_id={$reqId} not rendered.");
            // Bound the chunk to end at the START of the NEXT modal's markup (or end of
            // html) — a fixed 8000-char window is large enough to spill INTO the next
            // sibling modal's own content (modals are only ~2000 chars each), which would
            // cause a false "foreign item leaked" failure that has nothing to do with an
            // actual production defect.
            $nextModalPos = strpos($html, 'class="modal fade" id="storeBomModal', $modalStart + 10);
            $chunkEnd = $nextModalPos !== false ? $nextModalPos : strlen($html);
            $chunk = substr($html, $modalStart, $chunkEnd - $modalStart);

            // The Print button's onclick must json_encode the SAME product_name
            // that appears in this modal's own header — i.e. json_encode($sharedName)
            // must appear inside THIS modal's own chunk (both modals share the same
            // product_name text, so this alone doesn't prove isolation — the real
            // proof is below: the correct OWN item description must be present in
            // the Print/CSV-adjacent markup and the foreign item must not be).
            $this->assertStringContainsString('printBomReq(', $chunk, "Print button missing for requisition_id={$reqId}.");
            $this->assertStringContainsString('downloadBomReqCsv(', $chunk, "CSV button missing for requisition_id={$reqId}.");
            // json_encode() then Blade's {{ }} auto-escaping turns the wrapping double
            // quotes into &quot; in the final HTML — compare against that actual
            // rendered form, not the raw json_encode() output.
            $escapedJsonName = htmlspecialchars(json_encode(ucwords($sharedName)), ENT_QUOTES, 'UTF-8');
            $this->assertStringContainsString($escapedJsonName, $chunk, "Print/CSV button must reference this modal's own product name.");

            // Critically: the OWN item must appear (proves body loaded for the
            // right requisition) and the FOREIGN item must NOT appear anywhere
            // in this modal's chunk, INCLUDING within reach of the print/csv
            // buttons — since printBomReq()/downloadBomReqCsv() read the table
            // via modal.querySelector('.modal-body table') scoped to THIS
            // specific modal id, they can only ever emit what's inside this
            // chunk. Zero cross-leakage here structurally guarantees Print/CSV
            // cannot misattribute either.
            $this->assertStringContainsString($ownItem, $chunk);
            $foreignItem = ($reqId === $reqIdA) ? $item2 : $item1;
            $this->assertStringNotContainsString($foreignItem, $chunk, "Modal for requisition_id={$reqId} leaked foreign item into the region Print/CSV would read.");
        }

        // Confirm the CSV filename itself (also derived from $data->product_name)
        // is present for both modals distinctly, keyed off the same shared name
        // but scoped per-modal — since both projects share the same product_name,
        // the filenames will look the same textually, which is expected (the
        // collision is in the SOURCE data, not a code defect) — what matters is
        // each modal's own CSV call sits inside that modal's own chunk, which
        // the assertions above already proved.
        $this->assertStringContainsString('BOM_Requisition_', $html);
    }

    private function makeSentProjectWithItem(string $productName, string $description, string $itemDescription, int $partItemId, int $unitId, ?string $customerProjectName = null): array
    {
        $proj = $this->makeProject($productName, 1, $this->sentStatusId());
        DB::table('businesses_details')->where('id', $proj['bdId'])->update([
            'product_name' => $productName,
            'description'  => $description,
        ]);
        if ($customerProjectName !== null) {
            DB::table('businesses')->where('id', $proj['bizId'])->update([
                'project_name' => $customerProjectName,
            ]);
        }
        $req = Requisition::create([
            'req_name' => 'Auto', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
            'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d'),
        ]);
        RequisitionItem::create([
            'requisition_id' => $req->id, 'business_details_id' => $proj['bdId'],
            'part_item_id' => $partItemId, 'product_description' => $itemDescription,
            'required_quantity' => 10, 'available_quantity' => 2, 'shortage_quantity' => 8,
            'unit_id' => $unitId, 'rate' => 5, 'trolley_qty' => 1, 'is_qty_trolley_scaled' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 1, 'source' => 'bom_shortage',
        ]);
        $bap = BusinessApplicationProcesses::where('id', $proj['bapId'])->first();
        $bap->requisition_id = $req->id;
        $bap->save();

        return [$req->id, $proj['bdId']];
    }

    // ==================================================================
    // GAP 2 — multi-requisition-per-project: originally genuinely reachable (no
    // unique DB constraint on requisition.business_details_id; find-or-create in
    // StoreController/StoreRepository was not lock-protected against a race).
    // T-2026-059 iteration 3: system_architect closed this structurally with a
    // unique index (see that migration's docblock) — this test now proves the
    // fix, rather than demonstrating the (now-impossible) old broken behavior.
    // ==================================================================
    public function test_gap2_multi_requisition_per_project_edge_case(): void
    {
        // T-2026-059 iteration 3: system_architect's chosen fix for this gap was
        // OPTION A -- add a real unique index on requisition.business_details_id
        // (migration 2026_07_31_130000_add_unique_index_to_requisition_business_
        // details_id.php), making the 2-requisitions-per-project state this test
        // used to construct directly (bypassing the app's own find-or-create
        // guard) IMPOSSIBLE at the DB layer, not just undocumented. This test
        // now proves the fix: attempting to create a second Requisition row for
        // the SAME business_details_id must fail with a unique-constraint
        // violation, and the original single-requisition-per-project state must
        // remain completely unaffected (confirming the fix is additive/safe and
        // does not disturb the normal find-or-create write path).
        $proj = $this->makeProject('T2026059-Gap2-' . uniqid(), 1, $this->sentStatusId());

        $reqOld = Requisition::create([
            'req_name' => 'Auto-Old', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
            'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d', strtotime('-1 day')),
        ]);
        $this->assertNotNull($reqOld->id, 'Fixture precondition: the FIRST requisition for a project must still be creatable normally.');

        $threw = false;
        try {
            Requisition::create([
                'req_name' => 'Auto-New', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
                'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d'),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $threw = true;
        }

        $this->assertTrue(
            $threw,
            'GAP 2 NOW STRUCTURALLY CLOSED: a second Requisition row for the SAME business_details_id ' .
            'must be rejected by the new unique index (requisition_business_details_id_unique) -- proving ' .
            'the "2 requisitions silently hide one project\'s items" edge case (previously DB-reachable ' .
            'even though never reachable via any current UI action) is now genuinely impossible at the ' .
            'DB layer, closing both this gap and part of the Gap-3 concurrent-resync race risk.'
        );

        // Confirm the fix did not disturb the normal, single-requisition case: the original
        // row must still be present and untouched (the failed 2nd insert must not have
        // rolled back or mutated it).
        $this->assertEquals(1, Requisition::where('business_details_id', $proj['bdId'])->count());
        $this->assertEquals($reqOld->id, Requisition::where('business_details_id', $proj['bdId'])->first()->id);
    }

    private function searchAllPagesForRequisition(int $requisitionId): ?array
    {
        request()->merge(['page' => 1]);
        $page = 1;
        do {
            request()->merge(['page' => $page]);
            $view = $this->allListController->getAllListMaterialSentToPurchase();
            $data = $view->getData();
            $dataOutput = $data['data_output'];
            $row = collect($dataOutput->items())->first(fn($r) => (int) $r->requistition_id === $requisitionId);
            if ($row) {
                request()->merge(['page' => 1]);
                return ['row' => $row, 'requisitionItemsMap' => $data['requisitionItemsMap']];
            }
            $lastPage = $dataOutput->lastPage();
            $page++;
        } while ($page <= $lastPage);
        request()->merge(['page' => 1]);
        return null;
    }

    private function allListingRequisitionItemsMapsAcrossAllPages(): array
    {
        request()->merge(['page' => 1]);
        $maps = [];
        $page = 1;
        do {
            request()->merge(['page' => $page]);
            $view = $this->allListController->getAllListMaterialSentToPurchase();
            $data = $view->getData();
            $maps[] = $data['requisitionItemsMap'];
            $lastPage = $data['data_output']->lastPage();
            $page++;
        } while ($page <= $lastPage);
        request()->merge(['page' => 1]);
        return $maps;
    }

    // ==================================================================
    // GAP 5 — MTR vs METER case/variant sensitivity specifically through the
    // NEW trolley-scaling WRITE path (storeAdditionalShortageRequisition(),
    // already_scaled=0 — server recomputes via resolveUnitNameFromId() +
    // BomTotalCalculator::scaledQuantity()), not just the display-only
    // showBomInventoryCheck() path already covered by the prior test pass.
    // ==================================================================
    public function test_gap5_meter_unit_name_case_insensitive_through_write_path(): void
    {
        // A tbl_unit row whose name is literally "METER" (not "MTR") — the
        // project's own known Excel/master naming variance (see memory note
        // project_shreerag_bom_unit_naming). No unique constraint on tbl_unit.name
        // (confirmed via 2024_09_13_140040_..._create_tbl_unit.php), safe to create.
        $meterUnit = UnitMaster::create(['name' => 'METER', 'is_active' => 1, 'is_deleted' => 0]);
        $part = $this->makePartItem('Gap5 METER-unit part', $meterUnit->id);

        $trolleyQty = 3;
        $proj = $this->makeProject('T2026059-Gap5-' . uniqid(), $trolleyQty, $this->sentStatusId());
        $this->setStock($part->id, 1.0);

        $requisition = Requisition::create([
            'req_name' => 'Auto', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
            'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d'),
        ]);

        // Genuinely free-typed manual row: raw per-1-trolley mtr_for_01_nos_trolley=4,
        // already_scaled=0 — server must resolve unit_id -> "METER" -> length-unit
        // branch -> scaledQuantity = mtr_for_01_nos_trolley(4) x trolleyQty(3) = 12,
        // NOT the piece-unit branch (which would use required_quantity=999 verbatim
        // x3=2997, a decoy value deliberately supplied to catch a wrong branch).
        $request = Request::create('/store-additional-shortage-requisition', 'POST', [
            'business_details_id' => $proj['bdId'],
            'manual_shortage'     => [
                0 => [
                    'part_item_id'           => $part->id,
                    'product_description'    => 'Gap5 manual METER row',
                    'required_quantity'      => 999, // decoy — must be ignored (length branch)
                    'available_quantity'     => 1,
                    'unit_id'                => $meterUnit->id,
                    'rate'                   => 8,
                    'mtr_for_01_nos_trolley' => 4,
                    'length'                 => 50,
                    'already_scaled'         => '0',
                ],
            ],
        ]);

        $this->storeController->storeAdditionalShortageRequisition($request);

        $row = RequisitionItem::where('requisition_id', $requisition->id)->where('part_item_id', $part->id)->first();
        $this->assertNotNull($row, 'Manual METER-unit row must be persisted.');
        $this->assertEqualsWithDelta(
            12.0,
            (float) $row->required_quantity,
            0.001,
            '"METER" (case-sensitive-looking, non-"MTR" variant) must be treated as a length unit through the WRITE path: mtr_for_01_nos_trolley(4) x trolleyQty(3) = 12, decoy required_quantity=999 ignored.'
        );
        $this->assertEquals(1, (int) $row->is_qty_trolley_scaled);
        $this->assertEquals($trolleyQty, $row->trolley_qty);
    }

    // ==================================================================
    // GAP 6 — null-part_item_id BOM row through the FULL write-then-reread
    // cycle: storeShortageRequisition() persistence + showBomInventoryCheck()
    // re-matching. bom_material_items.part_item_id IS nullable (confirmed via
    // 2026_04_25_200002_add_part_unit_ids_to_bom_material_items_table.php) —
    // reachable in practice whenever the BOM Excel import's fuzzy part-matching
    // (T-2026-052) fails to resolve a row to any master part.
    // ==================================================================
    public function test_gap6_null_part_item_id_bom_row_write_then_reread(): void
    {
        $unitNos = UnitMaster::where('name', 'NOS')->first();
        $trolleyQty = 2;
        $proj = $this->makeProject('T2026059-Gap6-' . uniqid(), $trolleyQty, null);

        // A BOM row with NO part_item_id (unmatched during import) — quantity=5,
        // piece-unit NOS, fully short (no stock possible: part_item_id is null so
        // showBomInventoryCheck() forces availableStock=0 for this row).
        \App\Models\BomMaterialItem::create([
            'business_id'            => $proj['bizId'],
            'business_details_id'    => $proj['bdId'],
            'design_id'              => 0,
            'part_item_id'           => null,
            'serial_no'              => 1,
            'product_description'    => 'Gap6 unmatched BOM row (null part_item_id)',
            'length'                 => null,
            'quantity'               => 5,
            'mtr_for_01_nos_trolley' => 0,
            'rate'                   => 11,
            'unit'                   => 'NOS',
            'unit_id'                => $unitNos->id,
            'created_by'             => 1,
            'created_dept_role_id'   => 3,
            'is_active'              => 1,
            'is_deleted'             => 0,
        ]);

        // --- Confirm it surfaces as a shortage entry pre-send ---
        $view0 = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $shortage0 = collect($view0->getData()['shortage']);
        $nullRowPre = $shortage0->first(fn($s) => empty($s->part_item_id) && ($s->product_description ?? '') === 'Gap6 unmatched BOM row (null part_item_id)');
        $this->assertNotNull($nullRowPre, 'Null-part_item_id BOM row must surface as a shortage candidate before sending.');

        // --- Submit through the REAL storeShortageRequisition() write path ---
        $items = [
            0 => [
                'part_item_id'           => '', // no part selected — must persist as NULL
                'product_description'    => 'Gap6 unmatched BOM row (null part_item_id)',
                'required_quantity'      => $nullRowPre->required_quantity,
                'available_quantity'     => $nullRowPre->available_stock,
                'shortage_quantity'      => $nullRowPre->shortage_quantity,
                'unit_id'                => $unitNos->id,
                'rate'                   => 11,
                'mtr_for_01_nos_trolley' => 0,
                'length'                 => '',
            ],
        ];
        $request = Request::create('/store-shortage-requisition', 'POST', [
            'business_details_id' => $proj['bdId'],
            'business_id'         => $proj['bizId'],
            'design_id'           => 0,
            'items'               => $items,
        ]);
        $resp = $this->storeController->storeShortageRequisition($request);
        $this->assertNotEquals('error', $resp->getSession()->get('status'), 'storeShortageRequisition must succeed: ' . $resp->getSession()->get('msg'));

        $requisition = Requisition::where('business_details_id', $proj['bdId'])->first();
        $this->assertNotNull($requisition);

        $persisted = RequisitionItem::where('requisition_id', $requisition->id)->get();
        $this->assertCount(1, $persisted, 'The null-part_item_id row must be persisted (write path does not skip null part_item_id, unlike the manual-shortage loops).');
        $this->assertNull($persisted->first()->part_item_id, 'Persisted row must genuinely have part_item_id = NULL, not 0 or empty string.');
        $this->assertEquals(1, (int) $persisted->first()->is_sent_to_purchase);

        // --- Re-render the page: how many times does this row appear now? ---
        $view1 = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $shortage1 = collect($view1->getData()['shortage']);

        $matches = $shortage1->filter(fn($s) => empty($s->part_item_id) && ($s->product_description ?? '') === 'Gap6 unmatched BOM row (null part_item_id)');

        // EXPECTED correct behavior: exactly ONE entry, correctly matched to the
        // persisted requisition_item_id, with is_sent_to_purchase=1.
        // ACTUAL/investigated behavior: the BOM loop's re-derivation of this same
        // bom_material_items row produces a shortage entry with empty part_item_id,
        // and the matching logic (`if (!empty($sItem->part_item_id))`) can never
        // match ANY null-part_item_id shortage entry back to its own persisted
        // requisition_items row (the composite-key indexes are keyed by
        // part_item_id, which is null here) — so it is left with
        // requisition_item_id=null/is_sent_to_purchase=null. Separately, the
        // leftover-row loop ALSO surfaces the same persisted row (correctly, via
        // its own PK) as a SECOND entry. Net result: this row appears TWICE.
        if ($matches->count() > 1) {
            $withReqItemId = $matches->filter(fn($s) => !empty($s->requisition_item_id ?? null));
            $withoutReqItemId = $matches->filter(fn($s) => empty($s->requisition_item_id ?? null));
            $this->fail(
                "CONFIRMED GAP (Defect 2ii residual, null-part_item_id specific): a BOM row with NULL " .
                "part_item_id that has already been sent to Purchase is displayed TWICE on reload — " .
                "{$matches->count()} entries found ({$withReqItemId->count()} correctly matched with a " .
                "requisition_item_id, {$withoutReqItemId->count()} unmatched, re-derived fresh from the " .
                "BOM row itself as if never sent). Root cause: showBomInventoryCheck()'s BOM-loop-to-" .
                "requisition_item matching block guards on `!empty(\$sItem->part_item_id)` before ever " .
                "attempting a match, so a null-part_item_id BOM row can NEVER be matched to its own " .
                "already-persisted requisition_items row via that path — it always falls through with " .
                "is_sent_to_purchase=null — while the SEPARATE leftover-row loop (keyed by PK, matching " .
                "ALL requisition_items rows not yet claimed) independently surfaces the same persisted " .
                "row a second time. This is a real display-layer bug, not merely a hypothetical: it is " .
                "reachable in production whenever a BOM Excel row fails part-matching (T-2026-052) and " .
                "is submitted anyway, since storeShortageRequisition()'s own items[] loop places no guard " .
                "against a blank part_item_id (only the separate manual_shortage loops in " .
                "storeShortageRequisition()/storeAdditionalShortageRequisition() explicitly skip blank-part rows)."
            );
        }

        $this->assertCount(1, $matches, 'Null-part_item_id row must be displayed exactly once after being sent.');
        $only = $matches->first();
        $this->assertEquals(1, (int) $only->is_sent_to_purchase, 'The single surfaced entry must correctly reflect is_sent_to_purchase=1.');
        $this->assertEquals($persisted->first()->id, $only->requisition_item_id, 'The single surfaced entry must be correctly matched to its own persisted requisition_item_id.');
    }

    // ==================================================================
    // GAP 7 — real 3-page pagination boundary correctness: 25 distinct
    // fixtures forcing 3 full pages (10/10/5), confirming zero duplication
    // and zero drops across page boundaries (not just "total increased by
    // N" / "page 2 structurally works", which the prior pass already did).
    // ==================================================================
    public function test_gap7_three_page_pagination_boundary_correctness(): void
    {
        $unitNos = UnitMaster::where('name', 'NOS')->first();
        $part = $this->makePartItem('Gap7 pagination filler part', $unitNos->id);
        $suffix = uniqid();

        $before = $this->allListController->getAllListMaterialSentToPurchase()->getData()['data_output'];
        $baselineTotal = $before->total();

        $createdReqIds = [];
        for ($n = 1; $n <= 25; $n++) {
            $proj = $this->makeProject("T2026059-Gap7-{$suffix}-{$n}", 1, $this->sentStatusId());
            $req  = Requisition::create([
                'req_name' => 'Auto', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
                'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d'),
            ]);
            RequisitionItem::create([
                'requisition_id' => $req->id, 'business_details_id' => $proj['bdId'],
                'part_item_id' => $part->id, 'product_description' => "Gap7Item-{$n}",
                'required_quantity' => 1, 'available_quantity' => 0, 'shortage_quantity' => 1,
                'unit_id' => $unitNos->id, 'rate' => 1, 'trolley_qty' => 1, 'is_qty_trolley_scaled' => 1,
                'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 1, 'source' => 'bom_shortage',
            ]);
            $bap = BusinessApplicationProcesses::where('id', $proj['bapId'])->first();
            $bap->requisition_id = $req->id;
            $bap->save();
            $createdReqIds[] = $req->id;
        }
        $this->assertCount(25, array_unique($createdReqIds));

        $afterTotalView = $this->allListController->getAllListMaterialSentToPurchase()->getData()['data_output'];
        $this->assertEquals($baselineTotal + 25, $afterTotalView->total(), 'Total must increase by exactly 25.');
        $this->assertEquals(10, $afterTotalView->perPage());
        $lastPage = $afterTotalView->lastPage();
        $this->assertGreaterThanOrEqual(3, $lastPage, 'Must span at least 3 pages given baseline+25 rows at 10/page.');

        // Walk EVERY page from 1 to lastPage, collecting every requistition_id seen.
        $seenReqIds = [];
        $perPageCounts = [];
        for ($p = 1; $p <= $lastPage; $p++) {
            request()->merge(['page' => $p]);
            $pageView = $this->allListController->getAllListMaterialSentToPurchase()->getData()['data_output'];
            $this->assertEquals($p, $pageView->currentPage(), "Page {$p} must report its own currentPage correctly.");
            $ids = collect($pageView->items())->pluck('requistition_id')->filter()->all();
            $perPageCounts[$p] = count($ids);
            foreach ($ids as $id) {
                $this->assertArrayNotHasKey(
                    (string) $id,
                    $seenReqIds,
                    "requistition_id={$id} appeared on page {$p} AND on an earlier page — pagination is duplicating rows across boundaries."
                );
                $seenReqIds[(string) $id] = $p;
            }
        }
        request()->merge(['page' => 1]);

        // Every one of the 25 fixture requisition ids must have been seen exactly
        // once across all pages combined (zero drop, zero duplication).
        foreach ($createdReqIds as $id) {
            $this->assertArrayHasKey((string) $id, $seenReqIds, "Fixture requisition_id={$id} was never seen on ANY page (dropped).");
        }
        $this->assertCount($afterTotalView->total(), $seenReqIds, 'Total distinct requisition ids seen across all pages must equal the reported total() (no duplication, no drop, anywhere in the paginated set).');

        // Full-page counts (all pages except the last) must be exactly perPage(); the
        // last page must hold the remainder — standard LengthAwarePaginator boundary
        // shape, now concretely confirmed against this rewritten query specifically.
        for ($p = 1; $p < $lastPage; $p++) {
            $this->assertEquals(10, $perPageCounts[$p], "Page {$p} (not the last page) must be completely full (10 rows).");
        }
        $expectedLastPageCount = $afterTotalView->total() - (10 * ($lastPage - 1));
        $this->assertEquals($expectedLastPageCount, $perPageCounts[$lastPage], 'Last page must hold exactly the remainder.');
    }
}
