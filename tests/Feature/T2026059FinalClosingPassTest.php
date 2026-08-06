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
use App\Models\DesignModel;
use App\Http\Controllers\Organizations\Store\StoreController;

/**
 * T-2026-059 — FINAL CLOSING PASS (module_tester, GENUINE, independent re-verification).
 *
 * This file exists specifically to satisfy task-brief item 2(a): re-verify the Gap 6
 * null-part_item_id fix (StoreController.php, iteration 3) with a SLIGHTLY DIFFERENT
 * scenario than the one already covered by
 * StoreTrolleyRequisitionCoverageAuditTest::test_gap6_null_part_item_id_bom_row_write_then_reread
 * (which used a single PIECE-unit row, length=null) — this test instead uses THREE
 * LENGTH-unit rows (unit=MTR, non-null length), specifically to confirm the fix's
 * `normalizeLengthKey($ri->length) . '|' . normalizeDescriptionKey($ri->product_description)`
 * composite key genuinely disambiguates:
 *   (1) two null-part_item_id rows at the SAME length but DIFFERENT descriptions
 *       (must not collapse/cross-match), and
 *   (2) two null-part_item_id rows with the SAME description but DIFFERENT lengths
 *       (must not collapse/cross-match either)
 * — i.e. the fix is genuinely keyed on BOTH length AND description together, not
 * narrowly tailored to the original single-row/no-length test case.
 */
class T2026059FinalClosingPassTest extends TestCase
{
    use DatabaseTransactions;

    private $storeController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeController = new StoreController();
    }

    private function makeProject(string $productName, int $trolleyQty): array
    {
        $bizId = DB::table('businesses')->insertGetId([
            'organization_id'          => 1,
            'project_name'             => $productName . ' Project',
            'customer_po_number'       => 'PO-T2026059FINAL-' . uniqid(),
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
            'description'  => 'T-2026-059 final-closing-pass fixture',
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
            'store_status_id'     => null,
            'business_status_id'  => 0,
            'design_id'           => 0,
            'production_id'       => 0,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        return ['bdId' => $bdId, 'bizId' => $bizId, 'bapId' => $bap->id];
    }

    public function test_gap6_variant_length_unit_multiple_null_part_rows_disambiguated_by_length_and_description(): void
    {
        $unitMtr = UnitMaster::where('name', 'MTR')->first();
        $this->assertNotNull($unitMtr, 'MTR unit master must exist for this test.');
        $trolleyQty = 3;
        $proj = $this->makeProject('T2026059-Gap6v2-' . uniqid(), $trolleyQty);

        $descA = 'Gap6v2 unmatched length-BOM row A';
        $descB = 'Gap6v2 unmatched length-BOM row B';

        // Row 1: descA, length=250
        // Row 2: descB, length=250 (SAME length, DIFFERENT description as row 1)
        // Row 3: descA, length=400 (SAME description as row 1, DIFFERENT length)
        $rows = [
            ['description' => $descA, 'length' => 250, 'mtr1' => 6],
            ['description' => $descB, 'length' => 250, 'mtr1' => 4],
            ['description' => $descA, 'length' => 400, 'mtr1' => 9],
        ];

        foreach ($rows as $r) {
            \App\Models\BomMaterialItem::create([
                'business_id'            => $proj['bizId'],
                'business_details_id'    => $proj['bdId'],
                'design_id'              => 0,
                'part_item_id'           => null,
                'serial_no'              => 1,
                'product_description'    => $r['description'],
                'length'                 => $r['length'],
                'quantity'               => 0,
                'mtr_for_01_nos_trolley' => $r['mtr1'],
                'rate'                   => 15,
                'unit'                   => 'MTR',
                'unit_id'                => $unitMtr->id,
                'created_by'             => 1,
                'created_dept_role_id'   => 3,
                'is_active'              => 1,
                'is_deleted'             => 0,
            ]);
        }

        // --- Pre-send: confirm all 3 surface as distinct shortage candidates ---
        $view0 = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $shortage0 = collect($view0->getData()['shortage']);
        foreach ($rows as $idx => $r) {
            $match = $shortage0->filter(fn($s) => empty($s->part_item_id)
                && ($s->product_description ?? '') === $r['description']
                && (float) ($s->length ?? -1) === (float) $r['length']);
            $this->assertCount(1, $match, "Pre-send: row #{$idx} ({$r['description']}, length={$r['length']}) must surface exactly once.");
        }

        // --- Submit all 3 through the REAL storeShortageRequisition() write path ---
        $items = [];
        foreach ($rows as $idx => $r) {
            $srcRow = $shortage0->first(fn($s) => empty($s->part_item_id)
                && ($s->product_description ?? '') === $r['description']
                && (float) ($s->length ?? -1) === (float) $r['length']);
            $items[$idx] = [
                'part_item_id'           => '',
                'product_description'    => $r['description'],
                'required_quantity'      => $srcRow->required_quantity,
                'available_quantity'     => $srcRow->available_stock,
                'shortage_quantity'      => $srcRow->shortage_quantity,
                'unit_id'                => $unitMtr->id,
                'rate'                   => 15,
                'mtr_for_01_nos_trolley' => $r['mtr1'],
                'length'                 => $r['length'],
            ];
        }
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
        $this->assertCount(3, $persisted, 'All 3 null-part_item_id rows (2 distinct lengths x mixed descriptions) must be persisted as 3 distinct rows, not collapsed.');
        foreach ($persisted as $p) {
            $this->assertNull($p->part_item_id);
            $this->assertEquals(1, (int) $p->is_sent_to_purchase);
        }

        // --- Re-render: each of the 3 rows must appear EXACTLY ONCE, correctly matched ---
        $view1 = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']));
        $shortage1 = collect($view1->getData()['shortage']);

        foreach ($rows as $idx => $r) {
            $matches = $shortage1->filter(fn($s) => empty($s->part_item_id)
                && ($s->product_description ?? '') === $r['description']
                && (float) ($s->length ?? -1) === (float) $r['length']);

            if ($matches->count() !== 1) {
                $this->fail(
                    "Gap6 regression (length-unit variant): row #{$idx} ({$r['description']}, length={$r['length']}) " .
                    "appears {$matches->count()} times after being sent (expected exactly 1). This would indicate the " .
                    "length+description composite key is not correctly disambiguating same-length/different-description " .
                    "or same-description/different-length null-part_item_id rows."
                );
            }

            $only = $matches->first();
            $this->assertEquals(1, (int) $only->is_sent_to_purchase, "Row #{$idx} must be correctly recognised as already sent.");
            $this->assertNotNull($only->requisition_item_id ?? null, "Row #{$idx} must be matched to a persisted requisition_item_id, not left null.");

            // Confirm it is matched to the CORRECT persisted row (not a sibling's) —
            // find the persisted row with this exact description+length and compare ids.
            $expectedPersisted = $persisted->first(fn($p) => (float) $p->length === (float) $r['length']
                && trim(mb_strtolower((string) $p->product_description)) === trim(mb_strtolower($r['description'])));
            $this->assertNotNull($expectedPersisted, "Sanity: persisted row for #{$idx} must exist.");
            $this->assertEquals($expectedPersisted->id, $only->requisition_item_id, "Row #{$idx} must be matched to ITS OWN persisted row, not a sibling's (cross-match check).");
        }

        // Cross-check: total distinct requisition_item_ids surfaced across all 3 matches must be 3 (no accidental sharing).
        $allSurfacedIds = collect($rows)->map(function ($r) use ($shortage1) {
            $m = $shortage1->first(fn($s) => empty($s->part_item_id)
                && ($s->product_description ?? '') === $r['description']
                && (float) ($s->length ?? -1) === (float) $r['length']);
            return $m->requisition_item_id ?? null;
        });
        $this->assertCount(3, $allSurfacedIds->unique()->filter(), 'All 3 surfaced rows must map to 3 DISTINCT requisition_item_id values (no cross-matching between siblings).');
    }
}
