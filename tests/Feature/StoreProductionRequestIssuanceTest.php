<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BusinessApplicationProcesses;
use App\Models\ProductionDetails;
use App\Models\ProductionModel;
use App\Models\UnitMaster;
use App\Models\PartItem;
use App\Models\ItemStock;
use App\Models\DesignModel;
use App\Http\Controllers\Organizations\Store\StoreController;

/**
 * Regression cover for the "Additional Items to Issue" orphan-request defect.
 *
 * Reproduction (observed on business_details_id 2325): Production raised a
 * material request -> production_details row #68 (quantity_minus_status='pending').
 * Store issued it from the BOM Inventory Check page -> issueAvailableMaterials()
 * INSERTED a brand-new row #70 ('done') and left #68 untouched. On the next page
 * load showBomInventoryCheck()'s $pendingRows query still matched #68, so the item
 * rendered simultaneously under "Already Issued to Production" AND under
 * "Additional Items to Issue" — and could be issued again, double-deducting stock.
 *
 * These tests drive the REAL controller methods against real fixtures, wrapped in
 * DatabaseTransactions so every write is rolled back.
 */
class StoreProductionRequestIssuanceTest extends TestCase
{
    use DatabaseTransactions;

    private $storeController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeController = new StoreController();
    }

    private function makePartItem(string $description, int $unitId): PartItem
    {
        return PartItem::create([
            'part_number'   => 'PRISSUE-' . uniqid(),
            'description'   => $description,
            'unit_id'       => $unitId,
            'hsn_id'        => 1,
            'group_type_id' => 1,
            'basic_rate'    => '100',
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
     * Full project fixture including the `production` row that
     * issueAvailableMaterials() requires.
     *
     * @return array{bdId:int, bizId:int, productionId:int}
     */
    private function makeProject(string $productName): array
    {
        $bizId = DB::table('businesses')->insertGetId([
            'organization_id'          => 1,
            'project_name'             => $productName . ' Project',
            'customer_po_number'       => 'PO-PRISSUE-' . uniqid(),
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
            'description'  => 'production-request issuance fixture',
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
            'trolley_qty'         => 1,
            'is_approve'          => 1,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        $productionId = DB::table('production')->insertGetId([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'design_id'           => 0,
            'is_active'           => 1,
            'is_deleted'          => 0,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        BusinessApplicationProcesses::create([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'business_status_id'  => 0,
            'design_id'           => 0,
            'production_id'       => $productionId,
            'is_active'           => 1,
            'is_deleted'          => 0,
        ]);

        return ['bdId' => $bdId, 'bizId' => $bizId, 'productionId' => $productionId];
    }

    /** Create the pending Production-department material request. */
    private function makePendingRequest(array $proj, int $partItemId, int $unitId, float $qty, float $rate): ProductionDetails
    {
        return ProductionDetails::create([
            'business_id'              => $proj['bizId'],
            'business_details_id'      => $proj['bdId'],
            'design_id'                => 0,
            'production_id'            => $proj['productionId'],
            'part_item_id'             => $partItemId,
            'quantity'                 => $qty,
            'unit'                     => $unitId,
            'basic_rate'               => $rate,
            'items_used_total_amount'  => $qty * $rate,
            'quantity_minus_status'    => 'pending',
            'material_send_production' => 0,
            'is_deleted'               => 0,
        ]);
    }

    private function issue(array $proj, array $extraItems): void
    {
        $this->storeController->issueAvailableMaterials(new Request([
            'business_details_id' => $proj['bdId'],
            'items'               => [],
            'extra_items'         => $extraItems,
        ]));
    }

    private function unitNos(): UnitMaster
    {
        $unit = UnitMaster::where('name', 'NOS')->first();
        $this->assertNotNull($unit, 'Fixture precondition: NOS unit must exist.');
        return $unit;
    }

    // ==================================================================
    // 1. The reported defect: issuing a pre-filled production request must
    //    consume that request, not leave it pending.
    // ==================================================================
    public function test_issuing_a_production_request_closes_the_pending_row_and_removes_it_from_additional_items(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-Defect-' . uniqid());
        $part = $this->makePartItem('test my data', $unit->id);
        $this->setStock($part->id, 1178.0);

        $pending = $this->makePendingRequest($proj, $part->id, $unit->id, 2.0, 100.0);

        // Page renders it as a pre-filled "Additional Items to Issue" row carrying pd_id.
        $before = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']))->getData();
        $this->assertCount(1, $before['availableFromProduction']);
        $this->assertSame($pending->id, $before['availableFromProduction'][0]->pd_id);

        $this->issue($proj, [[
            'part_item_id'        => $part->id,
            'product_description' => 'test my data',
            'quantity'            => 2,
            'unit_id'             => $unit->id,
            'rate'                => 100,
            'pd_id'               => $pending->id,
        ]]);

        // The ORIGINAL request row is now closed — not an orphan.
        $pending->refresh();
        $this->assertSame('done', $pending->quantity_minus_status);
        $this->assertEquals(1, $pending->material_send_production);

        // No duplicate row was spawned for this part.
        $this->assertSame(1, ProductionDetails::where('business_details_id', $proj['bdId'])
            ->where('part_item_id', $part->id)
            ->where('is_deleted', 0)
            ->count());

        // Stock deducted exactly once.
        $this->assertEquals(1176.0, (float) ItemStock::where('part_item_id', $part->id)->sum('quantity'));

        // Re-render: gone from "Additional Items to Issue", present under "Already Issued".
        $after = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']))->getData();
        $this->assertCount(0, $after['availableFromProduction'], 'Issued request must not re-appear as pre-filled.');
        $this->assertSame(1, collect($after['alreadyIssued'])->where('part_item_id', $part->id)->count());
    }

    // ==================================================================
    // 2. A replayed / stale pd_id must not re-close anything, and must not
    //    silently vanish — it falls through to the normal INSERT path.
    // ==================================================================
    public function test_replayed_pd_id_does_not_double_close_and_still_records_the_issuance(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-Replay-' . uniqid());
        $part = $this->makePartItem('replayed request part', $unit->id);
        $this->setStock($part->id, 50.0);

        $pending = $this->makePendingRequest($proj, $part->id, $unit->id, 5.0, 10.0);

        $payload = [[
            'part_item_id'        => $part->id,
            'product_description' => 'replayed request part',
            'quantity'            => 5,
            'unit_id'             => $unit->id,
            'rate'                => 10,
            'pd_id'               => $pending->id,
        ]];

        $this->issue($proj, $payload);
        $this->issue($proj, $payload);   // stale form re-submitted

        $rows = ProductionDetails::where('business_details_id', $proj['bdId'])
            ->where('part_item_id', $part->id)
            ->where('is_deleted', 0)
            ->get();

        // First submit consumed the request; the second could not re-consume it and
        // was recorded as its own independent issuance instead.
        $this->assertCount(2, $rows);
        $this->assertSame(0, $rows->where('quantity_minus_status', 'pending')->count());
        $this->assertEquals(40.0, (float) ItemStock::where('part_item_id', $part->id)->sum('quantity'));
    }

    // ==================================================================
    // 3. A pd_id belonging to a DIFFERENT order must never be closed by this
    //    order's issuance.
    // ==================================================================
    public function test_pd_id_from_another_order_is_ignored(): void
    {
        $unit = $this->unitNos();
        $projA = $this->makeProject('PRISSUE-A-' . uniqid());
        $projB = $this->makeProject('PRISSUE-B-' . uniqid());
        $part  = $this->makePartItem('cross order part', $unit->id);
        $this->setStock($part->id, 20.0);

        $foreignPending = $this->makePendingRequest($projB, $part->id, $unit->id, 3.0, 10.0);

        $this->issue($projA, [[
            'part_item_id'        => $part->id,
            'product_description' => 'cross order part',
            'quantity'            => 3,
            'unit_id'             => $unit->id,
            'rate'                => 10,
            'pd_id'               => $foreignPending->id,
        ]]);

        $foreignPending->refresh();
        $this->assertSame('pending', $foreignPending->quantity_minus_status, "Another order's request must stay open.");
        $this->assertEquals($projB['bdId'], $foreignPending->business_details_id);

        // Order A recorded its own issuance row.
        $this->assertSame(1, ProductionDetails::where('business_details_id', $projA['bdId'])
            ->where('part_item_id', $part->id)
            ->where('quantity_minus_status', 'done')
            ->where('is_deleted', 0)
            ->count());
    }

    // ==================================================================
    // 4. Removing the pre-filled row (trash icon) posts no pd_id, so the
    //    request must remain open for later.
    // ==================================================================
    public function test_request_stays_pending_when_its_prefilled_row_is_removed_before_issuing(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-Removed-' . uniqid());
        $partReq   = $this->makePartItem('untouched request part', $unit->id);
        $partOther = $this->makePartItem('manually added part', $unit->id);
        $this->setStock($partReq->id, 10.0);
        $this->setStock($partOther->id, 10.0);

        $pending = $this->makePendingRequest($proj, $partReq->id, $unit->id, 4.0, 10.0);

        // Operator deleted the pre-filled row and issued only a manual row.
        $this->issue($proj, [[
            'part_item_id'        => $partOther->id,
            'product_description' => 'manually added part',
            'quantity'            => 1,
            'unit_id'             => $unit->id,
            'rate'                => 10,
        ]]);

        $pending->refresh();
        $this->assertSame('pending', $pending->quantity_minus_status);

        $data = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']))->getData();
        $this->assertCount(1, $data['availableFromProduction'], 'Un-issued request must still be offered.');
        $this->assertEquals(10.0, (float) ItemStock::where('part_item_id', $partReq->id)->sum('quantity'));
    }

    // ==================================================================
    // 5. The operator may edit the pre-filled quantity before issuing; the
    //    issued figure is what gets persisted, and the request still closes.
    // ==================================================================
    public function test_edited_quantity_is_persisted_on_the_consumed_request_row(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-EditQty-' . uniqid());
        $part = $this->makePartItem('edited qty part', $unit->id);
        $this->setStock($part->id, 30.0);

        $pending = $this->makePendingRequest($proj, $part->id, $unit->id, 8.0, 10.0);

        $this->issue($proj, [[
            'part_item_id'        => $part->id,
            'product_description' => 'edited qty part',
            'quantity'            => 3,          // operator reduced it
            'unit_id'             => $unit->id,
            'rate'                => 10,
            'pd_id'               => $pending->id,
        ]]);

        $pending->refresh();
        $this->assertSame('done', $pending->quantity_minus_status);
        $this->assertEquals(3.0, (float) $pending->quantity);
        $this->assertEquals(30.0, (float) $pending->items_used_total_amount);
        $this->assertEquals(27.0, (float) ItemStock::where('part_item_id', $part->id)->sum('quantity'));
    }

    // ==================================================================
    // 7. T-2026-060 — the reported scenario: Production requests 3, only 1 is
    //    on the shelf. Store must be offered the 1 for immediate issue AND a
    //    shortage of 2 for purchase — not the whole 3 dumped on purchase.
    // ==================================================================
    public function test_partially_covered_request_splits_into_issuable_and_shortage(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-Partial-' . uniqid());
        $part = $this->makePartItem('QD THINNER', $unit->id);
        $this->setStock($part->id, 1.0);

        $pending = $this->makePendingRequest($proj, $part->id, $unit->id, 3.0, 85.0);

        $data = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']))->getData();

        // Issuable half — 1 unit, pre-filled and issuable right now.
        $this->assertCount(1, $data['availableFromProduction']);
        $issuable = $data['availableFromProduction'][0];
        $this->assertEquals(1.0, (float) $issuable->required_quantity);
        $this->assertTrue($issuable->is_partial_issue);
        $this->assertEquals(3.0, (float) $issuable->requested_quantity);
        $this->assertEquals(2.0, (float) $issuable->pending_quantity);
        $this->assertSame($pending->id, $issuable->pd_id);

        // Shortage half — 2 units for purchase, still tied to the same request.
        $shortRow = collect($data['shortage'])->first(fn($s) => ($s->pd_id ?? null) === $pending->id);
        $this->assertNotNull($shortRow, 'The un-covered balance must appear in the shortage list.');
        $this->assertEquals(3.0, (float) $shortRow->required_quantity);
        $this->assertEquals(1.0, (float) $shortRow->available_stock);
        $this->assertEquals(2.0, (float) $shortRow->shortage_quantity);
        $this->assertTrue($shortRow->is_partial_issue);
        $this->assertEquals(1.0, (float) $shortRow->issuable_quantity);
    }

    // ==================================================================
    // 8. Issuing the covered slice must leave the balance outstanding —
    //    Production still sees 2 requested, and it stays purchasable.
    // ==================================================================
    public function test_issuing_the_covered_slice_carries_the_balance_forward_as_pending(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-Balance-' . uniqid());
        $part = $this->makePartItem('QD THINNER balance', $unit->id);
        $this->setStock($part->id, 1.0);

        $pending = $this->makePendingRequest($proj, $part->id, $unit->id, 3.0, 85.0);

        $this->issue($proj, [[
            'part_item_id'        => $part->id,
            'product_description' => 'QD THINNER balance',
            'quantity'            => 1,          // only what stock covers
            'unit_id'             => $unit->id,
            'rate'                => 85,
            'pd_id'               => $pending->id,
        ]]);

        // Consumed row records the 1 that was actually issued.
        $pending->refresh();
        $this->assertSame('done', $pending->quantity_minus_status);
        $this->assertEquals(1.0, (float) $pending->quantity);

        // Balance of 2 carried forward on a fresh pending row.
        $balance = ProductionDetails::where('business_details_id', $proj['bdId'])
            ->where('part_item_id', $part->id)
            ->where('quantity_minus_status', 'pending')
            ->where('is_deleted', 0)
            ->get();
        $this->assertCount(1, $balance);
        $this->assertEquals(2.0, (float) $balance->first()->quantity);
        $this->assertEquals(0, (int) $balance->first()->material_send_production);
        $this->assertEquals($unit->id, (int) $balance->first()->unit);

        // Stock fully consumed; nothing further is issuable, all 2 to purchase.
        $this->assertEquals(0.0, (float) ItemStock::where('part_item_id', $part->id)->sum('quantity'));

        $data = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']))->getData();
        $this->assertCount(0, $data['availableFromProduction'], 'Stock is exhausted — nothing left to pre-fill.');
        $shortRow = collect($data['shortage'])->first(fn($s) => ($s->pd_id ?? null) === $balance->first()->id);
        $this->assertNotNull($shortRow);
        $this->assertEquals(2.0, (float) $shortRow->shortage_quantity);
        $this->assertSame(1, collect($data['alreadyIssued'])->where('part_item_id', $part->id)->count());
    }

    // ==================================================================
    // 9. Two pending rows for the SAME part draw from one physical balance —
    //    the page must not offer the same unit twice.
    // ==================================================================
    public function test_shared_stock_is_allocated_across_pending_rows_not_double_offered(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-Pool-' . uniqid());
        $part = $this->makePartItem('shared pool part', $unit->id);
        $this->setStock($part->id, 4.0);

        $first  = $this->makePendingRequest($proj, $part->id, $unit->id, 3.0, 10.0);
        $second = $this->makePendingRequest($proj, $part->id, $unit->id, 3.0, 10.0);

        $data = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']))->getData();

        $offered = collect($data['availableFromProduction']);
        $this->assertEquals(
            4.0,
            (float) $offered->sum('required_quantity'),
            'Total offered must never exceed the 4 physically in stock.'
        );

        // First row fully covered (3), second gets the remaining 1 as a partial.
        $firstOffer  = $offered->firstWhere('pd_id', $first->id);
        $secondOffer = $offered->firstWhere('pd_id', $second->id);
        $this->assertNotNull($firstOffer);
        $this->assertEquals(3.0, (float) $firstOffer->required_quantity);
        $this->assertNotNull($secondOffer);
        $this->assertEquals(1.0, (float) $secondOffer->required_quantity);
        $this->assertTrue($secondOffer->is_partial_issue);
    }

    // ==================================================================
    // 10. Zero stock is unchanged behaviour — the whole request goes to
    //     purchase and nothing is offered for issue.
    // ==================================================================
    public function test_zero_stock_sends_the_whole_request_to_shortage(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-NoStock-' . uniqid());
        $part = $this->makePartItem('out of stock part', $unit->id);
        // deliberately no ItemStock row at all

        $pending = $this->makePendingRequest($proj, $part->id, $unit->id, 5.0, 10.0);

        $data = $this->storeController->showBomInventoryCheck(base64_encode($proj['bdId']))->getData();

        $this->assertCount(0, $data['availableFromProduction']);
        $shortRow = collect($data['shortage'])->first(fn($s) => ($s->pd_id ?? null) === $pending->id);
        $this->assertNotNull($shortRow);
        $this->assertEquals(5.0, (float) $shortRow->shortage_quantity);
        $this->assertFalse($shortRow->is_partial_issue ?? false);
    }

    // ==================================================================
    // 6. Insufficient stock rolls the whole transaction back — the request
    //    must NOT be marked done and stock must be untouched.
    // ==================================================================
    public function test_failed_issuance_leaves_the_request_pending_and_stock_intact(): void
    {
        $unit = $this->unitNos();
        $proj = $this->makeProject('PRISSUE-Rollback-' . uniqid());
        $part = $this->makePartItem('short stock part', $unit->id);
        $this->setStock($part->id, 1.0);

        $pending = $this->makePendingRequest($proj, $part->id, $unit->id, 9.0, 10.0);

        $this->issue($proj, [[
            'part_item_id'        => $part->id,
            'product_description' => 'short stock part',
            'quantity'            => 9,
            'unit_id'             => $unit->id,
            'rate'                => 10,
            'pd_id'               => $pending->id,
        ]]);

        $pending->refresh();
        $this->assertSame('pending', $pending->quantity_minus_status, 'Rolled-back issuance must not close the request.');
        $this->assertEquals(0, (int) $pending->material_send_production);
        $this->assertEquals(1.0, (float) ItemStock::where('part_item_id', $part->id)->sum('quantity'));
    }
}
