<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessApplicationProcesses;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\UnitMaster;
use App\Models\PartItem;
use App\Http\Controllers\Organizations\Store\AllListController;

/**
 * T-2026-059 — REQUIRED regression test (coordinator's "ADDED SCOPE" instruction).
 *
 * Guards against the header/body misattribution defect that existed before the
 * AllListRepository::getAllListMaterialSentToPurchase() rewrite: the listing
 * query used to groupBy(['businesses_details.product_name', 'businesses_details
 * .description']) and pick MAX(requisition.id) inside that group. Two DIFFERENT
 * projects that happened to share an identical product_name+description (or,
 * separately, any code regression that stops keying strictly per-project) could
 * collapse into ONE listing row, whose modal HEADER (product_name/customer_
 * project_name) came from the aggregated group while its BODY
 * ($requisitionItemsMap[$data->requistition_id]) could show a different
 * project's items than the ones implied by the header — i.e. a modal's title
 * and its rendered item rows could belong to two different projects.
 *
 * This test asserts, end-to-end through the REAL controller
 * (AllListController::getAllListMaterialSentToPurchase()) and the REAL blade
 * view (list-material-sent-to-purchase.blade.php), that:
 *   (a) two projects with DISTINCT product names, each with its own
 *       requisition + distinct items, are never collapsed and never leak
 *       items into each other's modal; and
 *   (b) two projects that SHARE an identical product_name+description (the
 *       actual old collapsing key) are still never collapsed and never leak
 *       items into each other's modal.
 *
 * Runs against this project's real configured database connection (phpunit.xml
 * has no sqlite override) — wrapped in DatabaseTransactions so every write
 * this test performs is rolled back when the test finishes, regardless of
 * pass/fail. No destructive commands, no permanent writes.
 */
class StoreBomRequisitionModalTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create one full "project" fixture: businesses + businesses_details +
     * requisition + a single distinctly-named requisition_item + a
     * business_application_processes row marked "sent to purchase" (the
     * store_status_id this listing query filters on).
     *
     * Business/BusinessDetails models carry $fillable lists that don't cover
     * every column this fixture needs, so — matching this task's own
     * iteration-1 verification convention — businesses/businesses_details rows
     * are inserted via DB::table() directly; Requisition/BusinessApplication
     * Processes/RequisitionItem all declare `$guarded = ['id']` (or an
     * equivalent explicit $fillable), so their Eloquent ::create() is safe.
     *
     * @return int the created requisition's id
     */
    private function makeSentProject(
        string $productName,
        string $description,
        string $itemDescription,
        int $partItemId,
        int $unitId
    ): int {
        $sentStatusId = (int) config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');

        $bizId = DB::table('businesses')->insertGetId([
            'organization_id'           => 1,
            'project_name'              => $productName . ' Project',
            'customer_po_number'        => 'PO-TEST-' . uniqid(),
            'title'                     => $productName,
            'po_validity'               => date('Y-m-d'),
            'customer_payment_terms'    => 'NA',
            'customer_terms_condition'  => 'NA',
            'is_active'                 => 1,
            'is_deleted'                => 0,
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);

        $bdId = DB::table('businesses_details')->insertGetId([
            'business_id' => $bizId,
            'product_name' => $productName,
            'description'  => $description,
            'quantity'     => 1,
            'rate'         => 0,
            'is_active'    => 1,
            'is_deleted'   => 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $req = Requisition::create([
            'req_name'            => 'Test-' . $productName,
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'design_id'           => 0,
            'production_id'       => 0,
            'req_date'            => date('Y-m-d'),
        ]);

        RequisitionItem::create([
            'requisition_id'        => $req->id,
            'business_details_id'   => $bdId,
            'part_item_id'          => $partItemId,
            'product_description'   => $itemDescription,
            'required_quantity'     => 10,
            'available_quantity'    => 2,
            'shortage_quantity'     => 8,
            'unit_id'               => $unitId,
            'rate'                  => 5,
            'trolley_qty'           => 1,
            'is_qty_trolley_scaled' => 1,
            'is_active'             => 1,
            'is_deleted'            => 0,
            'is_sent_to_purchase'   => 1,
        ]);

        BusinessApplicationProcesses::create([
            'business_id'         => $bizId,
            'business_details_id' => $bdId,
            'store_status_id'     => $sentStatusId,
            'business_status_id'  => 0,
            'requisition_id'      => $req->id,
            'design_id'           => 0,
            'production_id'       => 0,
        ]);

        return $req->id;
    }

    /**
     * Call the REAL controller, render the REAL blade view, and assert that
     * the modal for $requisitionId contains ONLY its own item text and none
     * of $foreignItemDescriptions.
     */
    private function assertModalIsolated(
        int $requisitionId,
        string $ownItemDescription,
        array $foreignItemDescriptions
    ): void {
        $controller = new AllListController();
        $view = $controller->getAllListMaterialSentToPurchase();
        $data = $view->getData();

        $this->assertArrayHasKey('data_output', $data);
        $this->assertArrayHasKey('requisitionItemsMap', $data);

        $dataOutput          = $data['data_output'];
        $requisitionItemsMap = $data['requisitionItemsMap'];

        $row = collect($dataOutput->items())->first(fn ($r) => (int) $r->requistition_id === $requisitionId);
        $this->assertNotNull(
            $row,
            "Listing row for requisition_id={$requisitionId} was not returned by getAllListMaterialSentToPurchase() (project missing or collapsed into another row)."
        );

        $items = $requisitionItemsMap->get($row->requistition_id) ?? collect();
        $this->assertCount(
            1,
            $items,
            "Modal body for requisition_id={$requisitionId} does not contain exactly its own 1 item; got: "
                . json_encode($items->pluck('product_description'))
        );
        $this->assertSame($ownItemDescription, $items->first()->product_description);

        $html = view('organizations.store.list.list-material-sent-to-purchase', [
            'data_output'          => $dataOutput,
            'requisitionItemsMap'  => $requisitionItemsMap,
        ])->render();

        $modalStart = strpos($html, 'id="storeBomModal' . $row->requistition_id . '"');
        $this->assertNotFalse($modalStart, "Modal markup for requisition_id={$requisitionId} was not rendered.");

        // Extract this modal's markup, bounded to end at the START of the NEXT
        // sibling modal (or end of html) — a fixed-size window (e.g. 6000 chars)
        // can spill INTO an adjacent modal when 2 modals are rendered back-to-back
        // with nothing in between (each modal is only ~2000 chars), which would
        // cause a false "foreign item leaked" failure unrelated to any actual
        // production defect (found during T-2026-059's coverage self-audit).
        $nextModalPos = strpos($html, 'class="modal fade" id="storeBomModal', $modalStart + 10);
        $chunkEnd = $nextModalPos !== false ? $nextModalPos : strlen($html);
        $chunk = substr($html, $modalStart, $chunkEnd - $modalStart);

        $this->assertStringContainsString(
            $ownItemDescription,
            $chunk,
            "Modal for requisition_id={$requisitionId} does not contain its own item description."
        );

        foreach ($foreignItemDescriptions as $foreign) {
            $this->assertStringNotContainsString(
                $foreign,
                $chunk,
                "Modal for requisition_id={$requisitionId} LEAKED a foreign project's item text: \"{$foreign}\"."
            );
        }
    }

    /**
     * Scenario (a) from the coordinator's ADDED SCOPE instruction: two
     * projects with DISTINCT product names, each with its own requisition and
     * distinct items — every modal's title/subtitle must match the project
     * whose items are in its body, neither leaking into the other's.
     */
    public function test_distinct_product_names_never_leak_items_between_modals(): void
    {
        $unit  = UnitMaster::first();
        $partA = PartItem::first();
        $partB = PartItem::skip(1)->first();

        $this->assertNotNull($unit, 'Fixture precondition failed: tbl_unit has no rows.');
        $this->assertNotNull($partA, 'Fixture precondition failed: part_items has fewer than 2 rows.');
        $this->assertNotNull($partB, 'Fixture precondition failed: part_items has fewer than 2 rows.');

        $suffix = uniqid();
        $item1  = "OwnItemForDistinctProjectOne-{$suffix}";
        $item2  = "OwnItemForDistinctProjectTwo-{$suffix}";

        $req1 = $this->makeSentProject("DistinctProductOne-{$suffix}", 'Description One', $item1, $partA->id, $unit->id);
        $req2 = $this->makeSentProject("DistinctProductTwo-{$suffix}", 'Description Two', $item2, $partB->id, $unit->id);

        $this->assertNotSame($req1, $req2, 'Fixture precondition failed: expected 2 distinct requisitions.');

        $this->assertModalIsolated($req1, $item1, [$item2]);
        $this->assertModalIsolated($req2, $item2, [$item1]);
    }

    /**
     * Scenario (b) from the coordinator's ADDED SCOPE instruction: two
     * projects that SHARE an identical product_name+description — the actual
     * old collapsing key (groupBy(['businesses_details.product_name',
     * 'businesses_details.description'])) — must still never collapse into
     * one listing row and must still never leak items into each other's modal.
     */
    public function test_identical_product_name_and_description_never_collapse_or_leak(): void
    {
        $unit  = UnitMaster::first();
        $partA = PartItem::first();
        $partB = PartItem::skip(1)->first();

        $this->assertNotNull($unit, 'Fixture precondition failed: tbl_unit has no rows.');
        $this->assertNotNull($partA, 'Fixture precondition failed: part_items has fewer than 2 rows.');
        $this->assertNotNull($partB, 'Fixture precondition failed: part_items has fewer than 2 rows.');

        $suffix            = uniqid();
        $sharedProductName = "SharedProductName-{$suffix}";
        $sharedDescription = 'Shared description text — the actual old collapsing key';
        $item1             = "OwnItemForSharedProjectOne-{$suffix}";
        $item2             = "OwnItemForSharedProjectTwo-{$suffix}";

        $req1 = $this->makeSentProject($sharedProductName, $sharedDescription, $item1, $partA->id, $unit->id);
        $req2 = $this->makeSentProject($sharedProductName, $sharedDescription, $item2, $partB->id, $unit->id);

        $this->assertNotSame(
            $req1,
            $req2,
            'Fixture precondition failed: expected 2 distinct requisitions despite identical product_name+description.'
        );

        $this->assertModalIsolated($req1, $item1, [$item2]);
        $this->assertModalIsolated($req2, $item2, [$item1]);
    }
}
