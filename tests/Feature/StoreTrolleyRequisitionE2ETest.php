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
use App\Models\BomMaterialItem;
use App\Models\ItemStock;
use App\Models\DesignModel;
use App\Http\Controllers\Organizations\Store\AllListController;
use App\Http\Controllers\Organizations\Store\StoreController;

/**
 * T-2026-059 — module_tester's independent end-to-end verification pass.
 *
 * Does NOT trust system_architect.md / code_reviewer.md self-reports. Every
 * scenario here calls the REAL controller methods (StoreController,
 * AllListController) and, where relevant, the REAL blade views, against real
 * DB fixtures created fresh for this task (new PartItem/UnitMaster-linked
 * BomMaterialItem/ItemStock rows — never reusing existing production part_item_ids,
 * to avoid any pre-existing stock contaminating the "available_stock must not be
 * trolley-multiplied" assertions). Wrapped in DatabaseTransactions so every write
 * performed by this test file is rolled back when each test finishes.
 */
class StoreTrolleyRequisitionE2ETest extends TestCase
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

    /**
     * Create a fresh, isolated PartItem row (never a shared production part_item_id)
     * so ItemStock sums used by these tests can never be polluted by real data.
     */
    private function makePartItem(string $description, int $unitId): PartItem
    {
        return PartItem::create([
            'part_number'   => 'T2026059-' . uniqid(),
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
        ItemStock::create([
            'part_item_id' => $partItemId,
            'quantity'     => $qty,
            'is_active'    => 1,
            'is_deleted'   => 0,
        ]);
    }

    /**
     * Build a full project fixture: businesses + businesses_details + designs
     * (with the given trolley_qty) + business_application_processes.
     *
     * @return array{bdId:int, bizId:int}
     */
    private function makeProject(string $productName, int $trolleyQty, ?int $storeStatusId = null): array
    {
        $bizId = DB::table('businesses')->insertGetId([
            'organization_id'          => 1,
            'project_name'             => $productName . ' Project',
            'customer_po_number'       => 'PO-T2026059-' . uniqid(),
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
            'description'  => 'T-2026-059 tester fixture',
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

    private function makeBomItem(int $bdId, int $bizId, int $partItemId, ?int $unitId, string $unitName, float $quantity, ?float $mtr1, ?float $length, float $rate): BomMaterialItem
    {
        return BomMaterialItem::create([
            'business_id'            => $bizId,
            'business_details_id'    => $bdId,
            'design_id'               => 0,
            'part_item_id'            => $partItemId,
            'serial_no'               => 1,
            'product_description'     => 'BOM row for part ' . $partItemId,
            'length'                  => $length,
            'quantity'                => $quantity,
            'mtr_for_01_nos_trolley'  => $mtr1,
            'rate'                    => $rate,
            'unit'                    => $unitName,
            'unit_id'                 => $unitId,
            'created_by'              => 1,
            'created_dept_role_id'    => 3,
            'is_active'               => 1,
            'is_deleted'              => 0,
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

    // ==================================================================
    // 1. Defect 1 end-to-end: same-part-different-length pair (length unit)
    //    + a separate piece-unit row, trolleyQty > 1.
    // ==================================================================
    public function test_defect1_trolley_scaled_write_path_end_to_end(): void
    {
        $unitMtr = UnitMaster::where('name', 'MTR')->first();
        $unitNos = UnitMaster::where('name', 'NOS')->first();
        $this->assertNotNull($unitMtr, 'Fixture precondition: MTR unit must exist.');
        $this->assertNotNull($unitNos, 'Fixture precondition: NOS unit must exist.');

        $trolleyQty = 4;
        $proj = $this->makeProject('T2026059-Defect1-' . uniqid(), $trolleyQty);

        // Fresh, isolated part items — never a shared/production part id.
        $partLen = $this->makePartItem('Length-unit part (MTR)', $unitMtr->id);
        $partPc  = $this->makePartItem('Piece-unit part (NOS)', $unitNos->id);

        // Same part_item_id, TWO different lengths, both length-unit rows.
        $rowA = $this->makeBomItem($proj['bdId'], $proj['bizId'], $partLen->id, $unitMtr->id, 'MTR', 1, 6.0, 100.000, 10);
        $rowB = $this->makeBomItem($proj['bdId'], $proj['bizId'], $partLen->id, $unitMtr->id, 'MTR', 1, 3.0, 200.000, 10);
        // Separate piece-unit row.
        $rowC = $this->makeBomItem($proj['bdId'], $proj['bizId'], $partPc->id, $unitNos->id, 'NOS', 5.0, 0.0, null, 20);

        // Real, isolated stock — must NOT be trolley-multiplied.
        $this->setStock($partLen->id, 5.0);
        $this->setStock($partPc->id, 3.0);

        // --- Call the REAL showBomInventoryCheck() ---
        $view = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $data = $view->getData();
        $shortage = $data['shortage'];

        $byPartLen = collect($shortage)->filter(fn($s) => $s->part_item_id == $partLen->id)->values();
        $byPartPc  = collect($shortage)->filter(fn($s) => $s->part_item_id == $partPc->id)->values();

        $this->assertCount(2, $byPartLen, 'Both distinct-length rows for the same part must appear as 2 separate shortage entries, not collapsed.');
        $this->assertCount(1, $byPartPc, 'The piece-unit row must appear as its own shortage entry.');

        // available_stock must be the RAW physical stock, never trolley-multiplied.
        foreach ($byPartLen as $s) {
            $this->assertEqualsWithDelta(5.0, $s->available_stock, 0.001, 'available_stock must not be trolley-scaled.');
        }
        $this->assertEqualsWithDelta(3.0, $byPartPc[0]->available_stock, 0.001, 'available_stock must not be trolley-scaled.');

        // Required/shortage must be unit-aware + trolley-scaled: 6*4=24, 3*4=12, 5*4=20.
        $lenByLength = $byPartLen->keyBy(fn($s) => (string) $s->length);
        $this->assertArrayHasKey('100.000', $lenByLength->toArray());
        $this->assertArrayHasKey('200.000', $lenByLength->toArray());
        $rowAOut = $lenByLength['100.000'];
        $rowBOut = $lenByLength['200.000'];

        $this->assertEqualsWithDelta(24.0, $rowAOut->required_quantity, 0.001, 'mtr=6 x trolley=4 = 24');
        $this->assertEqualsWithDelta(19.0, $rowAOut->shortage_quantity, 0.001, '24 - stock(5) = 19');
        $this->assertEqualsWithDelta(12.0, $rowBOut->required_quantity, 0.001, 'mtr=3 x trolley=4 = 12');
        $this->assertEqualsWithDelta(7.0, $rowBOut->shortage_quantity, 0.001, '12 - stock(5) = 7');

        $this->assertEqualsWithDelta(20.0, $byPartPc[0]->required_quantity, 0.001, 'qty=5 x trolley=4 = 20');
        $this->assertEqualsWithDelta(17.0, $byPartPc[0]->shortage_quantity, 0.001, '20 - stock(3) = 17');

        // --- Submit via the REAL storeShortageRequisition() ---
        $items = [];
        $i = 0;
        foreach ([$rowAOut, $rowBOut, $byPartPc[0]] as $s) {
            $items[$i] = [
                'part_item_id'           => $s->part_item_id,
                'product_description'    => $s->product_description ?? '',
                'required_quantity'      => $s->required_quantity,
                'available_quantity'     => $s->available_stock,
                'shortage_quantity'      => $s->shortage_quantity,
                'unit_id'                => $s->unit_id,
                'rate'                   => $s->rate,
                'mtr_for_01_nos_trolley' => $s->mtr_for_01_nos_trolley ?? '',
                'length'                 => $s->length ?? '',
            ];
            $i++;
        }

        $request = Request::create('/store-shortage-requisition', 'POST', [
            'business_details_id' => $proj['bdId'],
            'business_id'         => $proj['bizId'],
            'design_id'           => 0,
            'items'               => $items,
        ]);

        $resp = $this->storeController->storeShortageRequisition($request);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $resp);
        $this->assertNotEquals('error', $resp->getSession()->get('status'), 'storeShortageRequisition must succeed: ' . $resp->getSession()->get('msg'));

        $requisition = Requisition::where('business_details_id', $proj['bdId'])->first();
        $this->assertNotNull($requisition, 'Requisition must be created.');

        $persisted = RequisitionItem::where('requisition_id', $requisition->id)->get();
        $this->assertCount(3, $persisted, 'All 3 rows (2 same-part-different-length + 1 piece) must persist without collapsing/dropping.');

        $persistedByPartLen = $persisted->where('part_item_id', $partLen->id)->keyBy(fn($r) => (string) $r->length);
        $this->assertEqualsWithDelta(24.0, (float) $persistedByPartLen['100.000']->required_quantity, 0.001);
        $this->assertEqualsWithDelta(12.0, (float) $persistedByPartLen['200.000']->required_quantity, 0.001);
        foreach ($persistedByPartLen as $r) {
            $this->assertEquals(4, $r->trolley_qty, 'trolley_qty must be persisted.');
            $this->assertEquals(1, (int) $r->is_qty_trolley_scaled, 'is_qty_trolley_scaled must be 1.');
        }
        $persistedPc = $persisted->where('part_item_id', $partPc->id)->first();
        $this->assertEqualsWithDelta(20.0, (float) $persistedPc->required_quantity, 0.001);
        $this->assertEquals(4, $persistedPc->trolley_qty);
        $this->assertEquals(1, (int) $persistedPc->is_qty_trolley_scaled);

        // --- Re-render the page after sending: confirm correct re-matching by part+length ---
        $view2 = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $data2 = $view2->getData();
        $shortage2 = collect($data2['shortage']);

        $rowA2 = $shortage2->first(fn($s) => $s->part_item_id == $partLen->id && (string) ($s->length ?? '') === '100.000');
        $rowB2 = $shortage2->first(fn($s) => $s->part_item_id == $partLen->id && (string) ($s->length ?? '') === '200.000');
        $this->assertNotNull($rowA2);
        $this->assertNotNull($rowB2);
        $this->assertNotEquals(
            $rowA2->requisition_item_id,
            $rowB2->requisition_item_id,
            'The two distinct-length BOM rows sharing one part_item_id must re-match to DIFFERENT requisition_item_id values, not be confused with each other.'
        );
        $this->assertEquals((string) $persistedByPartLen['100.000']->id, (string) $rowA2->requisition_item_id);
        $this->assertEquals((string) $persistedByPartLen['200.000']->id, (string) $rowB2->requisition_item_id);
    }

    // ==================================================================
    // 2. Manual/additional path: piece + length manual rows, raw free-typed
    //    values, already_scaled=0; length-aware dedup.
    // ==================================================================
    public function test_manual_additional_path_scaling_and_length_aware_dedup(): void
    {
        $unitMtr = UnitMaster::where('name', 'MTR')->first();
        $unitNos = UnitMaster::where('name', 'NOS')->first();

        $trolleyQty = 4;
        $proj = $this->makeProject('T2026059-Manual-' . uniqid(), $trolleyQty, $this->sentStatusId());

        $partPc  = $this->makePartItem('Manual piece part', $unitNos->id);
        $partLen = $this->makePartItem('Manual length part', $unitMtr->id);

        $this->setStock($partPc->id, 1.0);
        $this->setStock($partLen->id, 1.0);

        // An existing requisition must already exist for storeAdditionalShortageRequisition.
        $requisition = Requisition::create([
            'req_name'            => 'Auto-Requisition',
            'business_id'         => $proj['bizId'],
            'business_details_id' => $proj['bdId'],
            'design_id'           => 0,
            'production_id'       => 0,
            'req_date'            => date('Y-m-d'),
        ]);

        // Genuinely free-typed manual rows: raw per-1-trolley quantity, already_scaled=0.
        $request = Request::create('/store-additional-shortage-requisition', 'POST', [
            'business_details_id' => $proj['bdId'],
            'manual_shortage'     => [
                0 => [
                    'part_item_id'           => $partPc->id,
                    'product_description'    => 'manual piece row',
                    'required_quantity'      => 7,      // raw, per-1-trolley
                    'available_quantity'     => 1,
                    'unit_id'                => $unitNos->id,
                    'rate'                   => 15,
                    'mtr_for_01_nos_trolley' => '',
                    'length'                 => 10,
                    'already_scaled'         => '0',
                ],
                1 => [
                    'part_item_id'           => $partLen->id,
                    'product_description'    => 'manual length row',
                    'required_quantity'      => 999,    // decoy — must be ignored for length unit
                    'available_quantity'     => 1,
                    'unit_id'                => $unitMtr->id,
                    'rate'                   => 12,
                    'mtr_for_01_nos_trolley' => 5,
                    'length'                 => 150,
                    'already_scaled'         => '0',
                ],
            ],
        ]);

        $resp = $this->storeController->storeAdditionalShortageRequisition($request);
        $this->assertNotInstanceOf(\Illuminate\Http\JsonResponse::class, $resp); // not ajax -> redirect

        $rows = RequisitionItem::where('requisition_id', $requisition->id)->get();
        $this->assertCount(2, $rows, 'Both manual rows must be inserted.');

        $pcRow  = $rows->firstWhere('part_item_id', $partPc->id);
        $lenRow = $rows->firstWhere('part_item_id', $partLen->id);

        // Piece-unit: 7 (raw qty) x trolley(4) = 28. Must NOT be double-scaled (would be 112).
        $this->assertEqualsWithDelta(28.0, (float) $pcRow->required_quantity, 0.001, 'Server must scale raw manual qty exactly once: 7x4=28.');
        // Length-unit: mtr_for_01_nos_trolley=5 x trolley(4) = 20, ignoring the decoy required_quantity=999.
        $this->assertEqualsWithDelta(20.0, (float) $lenRow->required_quantity, 0.001, 'Length branch must use mtr_for_01_nos_trolley, not the decoy required_quantity.');

        // --- Length-aware dedup: same part_item_id, DIFFERENT length must be INSERTED ---
        $request2 = Request::create('/store-additional-shortage-requisition', 'POST', [
            'business_details_id' => $proj['bdId'],
            'manual_shortage'     => [
                0 => [
                    'part_item_id'           => $partPc->id,
                    'product_description'    => 'manual piece row (diff length)',
                    'required_quantity'      => 2,
                    'available_quantity'     => 0,
                    'unit_id'                => $unitNos->id,
                    'rate'                   => 15,
                    'mtr_for_01_nos_trolley' => '',
                    'length'                 => 20, // different length than the first (10)
                    'already_scaled'         => '0',
                ],
            ],
        ]);
        $this->storeController->storeAdditionalShortageRequisition($request2);

        $rowsAfterDiffLength = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('part_item_id', $partPc->id)->get();
        $this->assertCount(2, $rowsAfterDiffLength, 'A same-part-different-length manual row must be INSERTED, not silently dropped.');

        // --- TRUE duplicate (same part+length as the first submission, length=10) must be SKIPPED ---
        $request3 = Request::create('/store-additional-shortage-requisition', 'POST', [
            'business_details_id' => $proj['bdId'],
            'manual_shortage'     => [
                0 => [
                    'part_item_id'           => $partPc->id,
                    'product_description'    => 'true duplicate attempt',
                    'required_quantity'      => 99,
                    'available_quantity'     => 0,
                    'unit_id'                => $unitNos->id,
                    'rate'                   => 15,
                    'mtr_for_01_nos_trolley' => '',
                    'length'                 => 10, // SAME length as the very first submission
                    'already_scaled'         => '0',
                ],
            ],
        ]);
        $this->storeController->storeAdditionalShortageRequisition($request3);

        $rowsAfterDup = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('part_item_id', $partPc->id)->get();
        $this->assertCount(2, $rowsAfterDup, 'A TRUE duplicate (same part_item_id + same length) must be SKIPPED, row count stays at 2.');
    }

    // ==================================================================
    // 3. Legacy-row backward compatibility, end-to-end through
    //    showBomInventoryCheck() AND list-material-sent-to-purchase.blade.php.
    // ==================================================================
    public function test_legacy_row_backward_compatibility_end_to_end(): void
    {
        $unitNos = UnitMaster::where('name', 'NOS')->first();
        $projectTrolleyQty = 5; // current design trolley_qty (fallback for legacy rows with no own trolley_qty)

        $proj = $this->makeProject('T2026059-Legacy-' . uniqid(), $projectTrolleyQty, $this->sentStatusId());
        $partLegacy = $this->makePartItem('Legacy pre-fix part', $unitNos->id);

        $requisition = Requisition::create([
            'req_name'            => 'Auto-Requisition',
            'business_id'         => $proj['bizId'],
            'business_details_id' => $proj['bdId'],
            'design_id'           => 0,
            'production_id'       => 0,
            'req_date'            => date('Y-m-d'),
        ]);
        $bapLegacy = BusinessApplicationProcesses::where('id', $proj['bapId'])->first();
        $bapLegacy->requisition_id = $requisition->id;
        $bapLegacy->save();

        // Construct a row SHAPED LIKE a pre-fix row: is_qty_trolley_scaled=0, quantity
        // on the OLD raw per-1-trolley basis, trolley_qty NULL (never captured pre-fix).
        $legacyItem = RequisitionItem::create([
            'requisition_id'        => $requisition->id,
            'business_details_id'   => $proj['bdId'],
            'part_item_id'          => $partLegacy->id,
            'product_description'   => 'Legacy row (pre-T-2026-059)',
            'required_quantity'     => 6,     // raw, per-1-trolley basis
            'available_quantity'    => 2,
            'shortage_quantity'     => 4,     // OLD naive shortage (6-2), pre-fix
            'unit_id'               => $unitNos->id,
            'rate'                  => 9,
            'mtr_for_01_nos_trolley' => null,
            'trolley_qty'           => null,  // legacy: never captured
            'length'                => null,
            'is_qty_trolley_scaled' => 0,      // legacy flag
            'is_active'             => 1,
            'is_deleted'            => 0,
            'is_sent_to_purchase'   => 1,      // immutable/sent
            'source'                => null,
        ]);

        // --- Exercise via showBomInventoryCheck()'s leftover-row surfacing loop ---
        $view = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $data = $view->getData();
        $shortage = collect($data['shortage']);

        $surfaced = $shortage->first(fn($s) => ($s->requisition_item_id ?? null) == $legacyItem->id);
        $this->assertNotNull($surfaced, 'Legacy row must be surfaced via the leftover-row loop.');

        // Effective required = 6 (raw) x 5 (fallback trolleyQty, since row has none) = 30.
        $this->assertEqualsWithDelta(30.0, $surfaced->required_quantity, 0.001, 'Legacy row must be retroactively scaled for DISPLAY: 6x5=30.');
        $this->assertEqualsWithDelta(28.0, $surfaced->shortage_quantity, 0.001, '30 - available(2) = 28, not the old naive 4.');

        // --- Must NOT be re-persisted ---
        $legacyItem->refresh();
        $this->assertEqualsWithDelta(6.0, (float) $legacyItem->required_quantity, 0.001, 'Legacy row must remain unchanged in the DB (display-only correction).');
        $this->assertEquals(0, (int) $legacyItem->is_qty_trolley_scaled, 'Legacy flag must remain 0 (never silently upgraded).');

        // --- Now flow through list-material-sent-to-purchase.blade.php's modal ---
        $view2 = $this->allListController->getAllListMaterialSentToPurchase();
        $viewData = $view2->getData();
        $dataOutput = $viewData['data_output'];
        $requisitionItemsMap = $viewData['requisitionItemsMap'];

        $row = collect($dataOutput->items())->first(fn($r) => (int) $r->requistition_id === $requisition->id);
        $this->assertNotNull($row, 'Legacy-row project must appear in the sent-to-purchase listing.');

        $html = view('organizations.store.list.list-material-sent-to-purchase', [
            'data_output'         => $dataOutput,
            'requisitionItemsMap' => $requisitionItemsMap,
        ])->render();

        $modalStart = strpos($html, 'id="storeBomModal' . $row->requistition_id . '"');
        $this->assertNotFalse($modalStart);
        // Bound the chunk to end at the next sibling modal (or end of html) — see
        // the coverage self-audit note in assertModalHeaderBodyIsolated() below for why.
        $nextModalPos = strpos($html, 'class="modal fade" id="storeBomModal', $modalStart + 10);
        $chunkEnd = $nextModalPos !== false ? $nextModalPos : strlen($html);
        $chunk = substr($html, $modalStart, $chunkEnd - $modalStart);

        $this->assertStringContainsString('30.000', $chunk, 'Modal must render the retroactively-corrected required qty (30.000), not the raw 6.000.');
        $this->assertStringContainsString('28.000', $chunk, 'Modal must render the retroactively-corrected shortage qty (28.000), not the old naive 4.000.');
    }

    // ==================================================================
    // 4. Defect 2(i): draft row genuinely excluded from the "already sent" modal.
    // ==================================================================
    public function test_defect2i_draft_row_excluded_from_sent_modal(): void
    {
        $unitNos = UnitMaster::where('name', 'NOS')->first();
        $proj = $this->makeProject('T2026059-Draft-' . uniqid(), 1, $this->sentStatusId());
        $part = $this->makePartItem('Draft-vs-sent part', $unitNos->id);

        $requisition = Requisition::create([
            'req_name'            => 'Auto-Requisition',
            'business_id'         => $proj['bizId'],
            'business_details_id' => $proj['bdId'],
            'design_id'           => 0,
            'production_id'       => 0,
            'req_date'            => date('Y-m-d'),
        ]);

        $sentItem = RequisitionItem::create([
            'requisition_id' => $requisition->id, 'business_details_id' => $proj['bdId'],
            'part_item_id' => $part->id, 'product_description' => 'SENT_ROW_MARKER',
            'required_quantity' => 5, 'available_quantity' => 1, 'shortage_quantity' => 4,
            'unit_id' => $unitNos->id, 'rate' => 1, 'trolley_qty' => 1, 'is_qty_trolley_scaled' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 1, 'source' => 'bom_shortage',
        ]);
        $draftItem = RequisitionItem::create([
            'requisition_id' => $requisition->id, 'business_details_id' => $proj['bdId'],
            'part_item_id' => $part->id, 'product_description' => 'DRAFT_ROW_MARKER',
            'required_quantity' => 3, 'available_quantity' => 0, 'shortage_quantity' => 3,
            'unit_id' => $unitNos->id, 'rate' => 1, 'trolley_qty' => 1, 'is_qty_trolley_scaled' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 0, 'source' => 'manual_shortage',
        ]);

        $view = $this->allListController->getAllListMaterialSentToPurchase();
        $viewData = $view->getData();
        $requisitionItemsMap = $viewData['requisitionItemsMap'];

        $items = $requisitionItemsMap->get($requisition->id) ?? collect();
        $ids = $items->pluck('id')->all();

        $this->assertContains($sentItem->id, $ids, 'The genuinely-sent row must be present in the modal map.');
        $this->assertNotContains($draftItem->id, $ids, 'The draft (is_sent_to_purchase=0) row must be EXCLUDED from the already-sent modal.');
    }

    // ==================================================================
    // 6. Defect 2(iii) + Added Scope: distinct names / identical name+desc,
    //    header/body pairing, zero cross-leakage, PLUS pagination check.
    // ==================================================================
    public function test_defect2iii_header_body_pairing_and_pagination(): void
    {
        $unitNos = UnitMaster::where('name', 'NOS')->first();
        $part = $this->makePartItem('Pagination filler part', $unitNos->id);
        $suffix = uniqid();

        // Baseline total before adding fixtures (some real "sent" rows may already exist).
        $before = $this->allListController->getAllListMaterialSentToPurchase()->getData()['data_output'];
        $baselineTotal = $before->total();

        // Create 12 distinct "sent" projects (each its own requisition) to force
        // pagination beyond a single 10-per-page.
        $createdReqIds = [];
        for ($n = 1; $n <= 12; $n++) {
            $proj = $this->makeProject("T2026059-Page-{$suffix}-{$n}", 1, $this->sentStatusId());
            $req  = Requisition::create([
                'req_name' => 'Auto', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
                'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d'),
            ]);
            RequisitionItem::create([
                'requisition_id' => $req->id, 'business_details_id' => $proj['bdId'],
                'part_item_id' => $part->id, 'product_description' => "PageItem-{$n}",
                'required_quantity' => 1, 'available_quantity' => 0, 'shortage_quantity' => 1,
                'unit_id' => $unitNos->id, 'rate' => 1, 'trolley_qty' => 1, 'is_qty_trolley_scaled' => 1,
                'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 1, 'source' => 'bom_shortage',
            ]);
            $bap = BusinessApplicationProcesses::where('id', $proj['bapId'])->first();
            $bap->requisition_id = $req->id;
            $bap->save();
            $createdReqIds[] = $req->id;
        }
        $this->assertCount(12, array_unique($createdReqIds));

        $afterPage1 = $this->allListController->getAllListMaterialSentToPurchase()->getData()['data_output'];
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $afterPage1, 'Result must still be a real paginator.');
        $this->assertEquals($baselineTotal + 12, $afterPage1->total(), 'Total count must increase by exactly 12 (no collapsing, no dropping).');
        $this->assertEquals(10, $afterPage1->perPage(), 'Per-page size must be unchanged (10).');
        $this->assertGreaterThanOrEqual(2, $afterPage1->lastPage(), 'Must span at least 2 pages given >10 total rows.');

        // Fetch explicit page 2 and confirm it structurally works (no crash, real data).
        request()->merge(['page' => 2]);
        $page2 = $this->allListController->getAllListMaterialSentToPurchase()->getData()['data_output'];
        $this->assertEquals(2, $page2->currentPage());
        $this->assertGreaterThan(0, $page2->count());
        request()->merge(['page' => 1]); // reset for subsequent assertions in this test

        // --- Distinct product names: no cross-leakage ---
        $unit  = UnitMaster::first();
        $partA = $this->makePartItem('Distinct-name part A', $unit->id);
        $partB = $this->makePartItem('Distinct-name part B', $unit->id);
        $item1 = "OwnItemDistinctA-{$suffix}";
        $item2 = "OwnItemDistinctB-{$suffix}";
        [$reqIdA, $bdA] = $this->makeSentProjectWithItem("DistinctNameA-{$suffix}", 'DescA', $item1, $partA->id, $unit->id);
        [$reqIdB, $bdB] = $this->makeSentProjectWithItem("DistinctNameB-{$suffix}", 'DescB', $item2, $partB->id, $unit->id);

        $this->assertModalHeaderBodyIsolated($reqIdA, $item1, [$item2]);
        $this->assertModalHeaderBodyIsolated($reqIdB, $item2, [$item1]);

        // --- Identical product_name + description: still no collapse/leak ---
        $sharedName = "SharedName-{$suffix}";
        $sharedDesc = 'Shared description — the old collapsing key';
        $item3 = "OwnItemSharedC-{$suffix}";
        $item4 = "OwnItemSharedD-{$suffix}";
        $partC = $this->makePartItem('Shared-name part C', $unit->id);
        $partD = $this->makePartItem('Shared-name part D', $unit->id);
        [$reqIdC] = $this->makeSentProjectWithItem($sharedName, $sharedDesc, $item3, $partC->id, $unit->id);
        [$reqIdD] = $this->makeSentProjectWithItem($sharedName, $sharedDesc, $item4, $partD->id, $unit->id);

        $this->assertNotEquals($reqIdC, $reqIdD);
        $this->assertModalHeaderBodyIsolated($reqIdC, $item3, [$item4]);
        $this->assertModalHeaderBodyIsolated($reqIdD, $item4, [$item3]);
    }

    private function makeSentProjectWithItem(string $productName, string $description, string $itemDescription, int $partItemId, int $unitId): array
    {
        $proj = $this->makeProject($productName, 1, $this->sentStatusId());
        // Override product_name/description precisely (makeProject sets a fixed description).
        DB::table('businesses_details')->where('id', $proj['bdId'])->update([
            'product_name' => $productName,
            'description'  => $description,
        ]);
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

    private function assertModalHeaderBodyIsolated(int $requisitionId, string $ownItemDescription, array $foreignItemDescriptions): void
    {
        // Search across ALL pages (this test file's own pagination fixtures may push
        // any given row onto a page other than 1) rather than assuming page 1 — avoids
        // a false failure caused by timestamp-tie ordering between fixtures created in
        // the same test, not a production defect.
        request()->merge(['page' => 1]);
        $view = $this->allListController->getAllListMaterialSentToPurchase();
        $data = $view->getData();
        $dataOutput = $data['data_output'];
        $requisitionItemsMap = $data['requisitionItemsMap'];

        $row = collect($dataOutput->items())->first(fn($r) => (int) $r->requistition_id === $requisitionId);
        $lastPage = $dataOutput->lastPage();
        $page = 2;
        while (!$row && $page <= $lastPage) {
            request()->merge(['page' => $page]);
            $view = $this->allListController->getAllListMaterialSentToPurchase();
            $data = $view->getData();
            $dataOutput = $data['data_output'];
            $requisitionItemsMap = $data['requisitionItemsMap'];
            $row = collect($dataOutput->items())->first(fn($r) => (int) $r->requistition_id === $requisitionId);
            $page++;
        }
        request()->merge(['page' => 1]);
        $this->assertNotNull($row, "Listing row for requisition_id={$requisitionId} missing/collapsed (searched all " . $lastPage . " pages).");

        $items = $requisitionItemsMap->get($row->requistition_id) ?? collect();
        $this->assertCount(1, $items, "Modal body for requisition_id={$requisitionId} must contain exactly its own item.");
        $this->assertSame($ownItemDescription, $items->first()->product_description);

        $html = view('organizations.store.list.list-material-sent-to-purchase', [
            'data_output' => $dataOutput, 'requisitionItemsMap' => $requisitionItemsMap,
        ])->render();

        $modalStart = strpos($html, 'id="storeBomModal' . $row->requistition_id . '"');
        $this->assertNotFalse($modalStart);
        // Bound the chunk to end at the START of the NEXT sibling modal (or end of
        // html) rather than a fixed 6000-char window — when 2 modals happen to be
        // rendered back-to-back with nothing else in between (each modal is only
        // ~2000 chars), a fixed window can spill INTO the next modal's own
        // (legitimately different) content, which would cause a false "foreign item
        // leaked" failure unrelated to any real production defect. Found + fixed
        // during T-2026-059's MANDATORY TEST-COVERAGE SELF-AUDIT (this exact false
        // failure was reproduced in the audit's own Gap-1 Print/CSV test, which
        // creates exactly 2 adjacent projects with nothing else rendered between them).
        $nextModalPos = strpos($html, 'class="modal fade" id="storeBomModal', $modalStart + 10);
        $chunkEnd = $nextModalPos !== false ? $nextModalPos : strlen($html);
        $chunk = substr($html, $modalStart, $chunkEnd - $modalStart);
        $this->assertStringContainsString($ownItemDescription, $chunk);
        foreach ($foreignItemDescriptions as $foreign) {
            $this->assertStringNotContainsString($foreign, $chunk, "Modal for requisition_id={$requisitionId} leaked foreign item \"{$foreign}\".");
        }
    }

    // ==================================================================
    // 8. Resync: PO-locked part with growing need; idempotency; shrunk-need.
    // ==================================================================
    public function test_resync_locked_part_growth_idempotency_and_shrink(): void
    {
        $unitMtr = UnitMaster::where('name', 'MTR')->first();
        $trolleyQty = 2;
        $proj = $this->makeProject('T2026059-Resync-' . uniqid(), $trolleyQty, $this->sentStatusId());
        $part = $this->makePartItem('Resync-locked part', $unitMtr->id);

        // BOM row: mtr_for_01_nos_trolley=5 -> scaled required = 5*2=10.
        $this->makeBomItem($proj['bdId'], $proj['bizId'], $part->id, $unitMtr->id, 'MTR', 1, 5.0, 300.000, 7);
        $this->setStock($part->id, 0.0); // fully short

        $requisition = Requisition::create([
            'req_name' => 'Auto', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
            'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d'),
        ]);
        $bap = BusinessApplicationProcesses::where('id', $proj['bapId'])->first();
        $bap->requisition_id = $requisition->id;
        $bap->save();

        $originalRow = RequisitionItem::create([
            'requisition_id' => $requisition->id, 'business_details_id' => $proj['bdId'],
            'part_item_id' => $part->id, 'product_description' => 'BOM row for part ' . $part->id,
            'required_quantity' => 10, 'available_quantity' => 0, 'shortage_quantity' => 10,
            'unit_id' => $unitMtr->id, 'rate' => 7, 'mtr_for_01_nos_trolley' => 5,
            'trolley_qty' => 2, 'length' => 300.000, 'is_qty_trolley_scaled' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 1, 'source' => 'bom_shortage',
        ]);

        // Lock the part via a real Purchase Order against this requisition.
        $poId = DB::table('purchase_orders')->insertGetId([
            'purchase_orders_id' => 88880001,
            'requisition_id'     => $requisition->id,
            'business_id'        => $proj['bizId'],
            'business_details_id'=> $proj['bdId'],
            'production_id'      => 0,
            'vendor_id'          => 1,
            'contact_person_name'   => 'Test Vendor Contact',
            'contact_person_number' => '9999999999',
            'image'              => '',
            'tax_type'           => '',
            'tax_id'             => '',
            'invoice_date'       => '',
            'payment_terms'      => '',
            'note'               => '',
            'po_date'            => date('Y-m-d'),
            'is_active'          => 1,
            'is_deleted'         => 0,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
        DB::table('purchase_order_details')->insert([
            'purchase_id'       => $poId,
            'part_no_id'        => $part->id,
            'description'       => 'locked',
            'quantity'          => 10,
            'unit'              => (string) $unitMtr->id,
            'hsn_id'            => 1,
            'discount'          => '0',
            'actual_quantity'   => '0',
            'accepted_quantity' => '0',
            'rejected_quantity' => '0',
            'rate'              => '7',
            'amount'            => '70',
            'is_active'         => 1,
            'is_deleted'        => 0,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // --- GROWTH: BOM need grows from 10 to 16 (mtr_for_01_nos_trolley 5 -> 8, x2 trolley) ---
        BomMaterialItem::where('business_details_id', $proj['bdId'])->update(['mtr_for_01_nos_trolley' => 8.0]);

        $req1 = Request::create('/resync-shortage-requisition', 'POST', ['business_details_id' => $proj['bdId']]);
        $resp1 = $this->storeController->resyncShortageRequisition($req1);
        $body1 = json_decode($resp1->getContent(), true);
        $this->assertEquals('success', $body1['status']);
        $this->assertEquals(1, $body1['inserted'], 'Exactly 1 new delta row must be inserted for the locked part.');
        $this->assertEquals(0, $body1['updated'], 'The locked row itself must never be updated.');

        $originalRow->refresh();
        $this->assertEqualsWithDelta(10.0, (float) $originalRow->required_quantity, 0.001, 'Original PO-locked row must be UNTOUCHED.');

        $deltaRows = RequisitionItem::where('requisition_id', $requisition->id)
            ->where('part_item_id', $part->id)->where('source', 'resync_delta')->get();
        $this->assertCount(1, $deltaRows, 'Exactly 1 resync_delta row must exist.');
        $this->assertEqualsWithDelta(6.0, (float) $deltaRows->first()->required_quantity, 0.001, 'Delta must be exactly 16-10=6.');

        // --- IDEMPOTENCY: resync again with NO further change -> 0 new rows ---
        $req2 = Request::create('/resync-shortage-requisition', 'POST', ['business_details_id' => $proj['bdId']]);
        $resp2 = $this->storeController->resyncShortageRequisition($req2);
        $body2 = json_decode($resp2->getContent(), true);
        $this->assertEquals(0, $body2['inserted'], 'Second resync call with no further growth must insert ZERO new rows (idempotent).');

        $allRowsAfterSecondResync = RequisitionItem::where('requisition_id', $requisition->id)->where('part_item_id', $part->id)->get();
        $this->assertCount(2, $allRowsAfterSecondResync, 'Row count must remain exactly 2 (original + 1 delta) after the idempotent second call.');

        // --- SHRINK: need drops back down (mtr_for_01_nos_trolley 8 -> 3, scaled=6, less than the 16 already covered) ---
        BomMaterialItem::where('business_details_id', $proj['bdId'])->update(['mtr_for_01_nos_trolley' => 3.0]);
        $req3 = Request::create('/resync-shortage-requisition', 'POST', ['business_details_id' => $proj['bdId']]);
        $resp3 = $this->storeController->resyncShortageRequisition($req3);
        $body3 = json_decode($resp3->getContent(), true);
        $this->assertEquals(0, $body3['inserted'], 'Shrunk need must never auto-remove/insert-negative — 0 inserted.');
        $this->assertEquals(0, $body3['updated'], 'Locked rows are never updated even on shrink.');

        $rowsAfterShrink = RequisitionItem::where('requisition_id', $requisition->id)->where('part_item_id', $part->id)->get();
        $this->assertCount(2, $rowsAfterShrink, 'Nothing must be deleted/removed on a shrunk need.');
        $originalRow->refresh();
        $this->assertEqualsWithDelta(10.0, (float) $originalRow->required_quantity, 0.001, 'Original row still untouched after shrink.');
    }

    // ==================================================================
    // 9. Edge cases: null-part_item_id reachability, case-insensitive unit
    //    naming (METER), production_shortage source exclusion from legacy scaling.
    // ==================================================================
    public function test_edge_cases_null_part_item_id_meter_unit_and_production_shortage_exclusion(): void
    {
        $unitNos = UnitMaster::where('name', 'NOS')->first();
        $proj = $this->makeProject('T2026059-Edge-' . uniqid(), 3, $this->sentStatusId());

        $requisition = Requisition::create([
            'req_name' => 'Auto', 'business_id' => $proj['bizId'], 'business_details_id' => $proj['bdId'],
            'design_id' => 0, 'production_id' => 0, 'req_date' => date('Y-m-d'),
        ]);
        $bap = BusinessApplicationProcesses::where('id', $proj['bapId'])->first();
        $bap->requisition_id = $requisition->id;
        $bap->save();

        // --- (a) null-part_item_id row must remain reachable ---
        $nullPartRow = RequisitionItem::create([
            'requisition_id' => $requisition->id, 'business_details_id' => $proj['bdId'],
            'part_item_id' => null, 'product_description' => 'NULL_PART_ITEM_MARKER',
            'required_quantity' => 4, 'available_quantity' => 1, 'shortage_quantity' => 3,
            'unit_id' => $unitNos->id, 'rate' => 2, 'trolley_qty' => 1, 'is_qty_trolley_scaled' => 1,
            'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 0, 'source' => 'manual_shortage',
        ]);

        $view = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $shortage = collect($view->getData()['shortage']);
        $found = $shortage->first(fn($s) => ($s->requisition_item_id ?? null) == $nullPartRow->id);
        $this->assertNotNull($found, 'A null-part_item_id requisition_items row must remain reachable via its own PK.');
        $this->assertNull($found->part_item_id);

        // --- (b) case-insensitive / METER unit naming ---
        // partMeter's OWN unit_id is irrelevant to this check (tbl_part_item.unit_id is NOT NULL,
        // so a valid id must be supplied) — what's under test is the BOM row's free-text `unit`
        // column ('METER', not 'MTR'), which showBomInventoryCheck() reads directly.
        $partMeter = $this->makePartItem('METER-unit-naming part', $unitNos->id);
        $this->makeBomItem($proj['bdId'], $proj['bizId'], $partMeter->id, null, 'METER', 1, 4.0, 50.000, 3);
        $this->setStock($partMeter->id, 0.0);

        $view2 = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $shortage2 = collect($view2->getData()['shortage']);
        $meterRow = $shortage2->first(fn($s) => $s->part_item_id == $partMeter->id);
        $this->assertNotNull($meterRow, 'METER-unit row must be classified as a shortage.');
        // trolleyQty for this project = 3; mtr_for_01_nos_trolley=4 -> scaled = 4*3=12 (length-unit branch selected for "METER").
        $this->assertEqualsWithDelta(12.0, $meterRow->required_quantity, 0.001, '"METER" (not just "MTR") must be treated as a length unit, case-insensitively.');

        // --- (c) production_shortage source excluded from retroactive legacy-scaling ---
        $partProdShortage = $this->makePartItem('production_shortage source part', $unitNos->id);
        $prodShortageRow = RequisitionItem::create([
            'requisition_id' => $requisition->id, 'business_details_id' => $proj['bdId'],
            'part_item_id' => $partProdShortage->id, 'product_description' => 'PRODUCTION_SHORTAGE_MARKER',
            'required_quantity' => 9, 'available_quantity' => 1, 'shortage_quantity' => 8,
            'unit_id' => $unitNos->id, 'rate' => 2, 'trolley_qty' => null, 'is_qty_trolley_scaled' => 0, // legacy-shaped
            'is_active' => 1, 'is_deleted' => 0, 'is_sent_to_purchase' => 1, 'source' => 'production_shortage',
        ]);

        $view3 = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $shortage3 = collect($view3->getData()['shortage']);
        $prodRowOut = $shortage3->first(fn($s) => ($s->requisition_item_id ?? null) == $prodShortageRow->id);
        $this->assertNotNull($prodRowOut);
        // Must be shown VERBATIM (9), never retroactively multiplied by the fallback trolleyQty (3) to 27,
        // because production_shortage rows are excluded from the legacy retroactive-correction path.
        $this->assertEqualsWithDelta(9.0, $prodRowOut->required_quantity, 0.001, 'production_shortage-sourced rows must be excluded from retroactive legacy scaling, shown verbatim.');
        $this->assertEqualsWithDelta(8.0, $prodRowOut->shortage_quantity, 0.001, 'shortage must also be shown verbatim for production_shortage rows.');
    }
}
