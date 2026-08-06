<?php

namespace App\Http\Controllers\Organizations\Store;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\StoreServices;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Exceptions\StoreIssueTransactionAborted;
use App\Models\{
    PartItem,
    User,
    UnitMaster,
    ItemStock,
    GRNModel,
    BomMaterialItem,
    BusinessDetails,
    BusinessApplicationProcesses,
    Requisition,
    RequisitionItem,
    ProductionModel,
    ProductionDetails,
    EstimationModel,
    AdminView,
    NotificationStatus
};
use Illuminate\Support\Facades\DB;


class StoreController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new StoreServices();
    }

    /**
     * Resolve trolley_qty for a business_details_id from the designs table.
     * Shared by every place that must compute a unit-aware, trolley-scaled
     * requisition quantity (T-2026-059) so the lookup rule lives in ONE place.
     * Defaults to 1 when no active/non-deleted design row is found.
     */
    private function resolveTrolleyQty($businessDetailsId): int
    {
        return (int) (\App\Models\DesignModel::where('business_details_id', $businessDetailsId)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->value('trolley_qty') ?: 1);
    }

    /**
     * Resolve a human-readable unit name (for BomTotalCalculator's unit-aware
     * check) from a tbl_unit.id. Returns '' when not found — BomTotalCalculator::
     * isPieceUnit('') is false, so an unresolved unit is safely treated as
     * length-based (matches the "anything not in PIECE_UNITS is length-based" rule).
     */
    private function resolveUnitNameFromId($unitId): string
    {
        if (empty($unitId)) {
            return '';
        }
        $unit = UnitMaster::find($unitId);
        if (!$unit) {
            return '';
        }

        return (string) ($unit->name ?? $unit->unit_name ?? '');
    }

    /**
     * Normalise a length value (bom_material_items.length / requisition_items.length)
     * into a stable string key for map lookups, so that DB-decimal string formatting
     * differences (e.g. "500.000" vs "500.0") never cause a false mismatch, and NULL
     * (piece-unit BOM rows with no length) always maps to a single, distinct sentinel.
     */
    private static function normalizeLengthKey($length): string
    {
        return ($length === null || $length === '')
            ? 'NULL'
            : number_format((float) $length, 3, '.', '');
    }

    /**
     * Normalise a product_description value into a stable string key for map
     * lookups (trimmed + case-folded so incidental whitespace/casing differences
     * never cause a false mismatch). NULL/'' always maps to a single, distinct
     * sentinel — distinct from any real (even empty-after-trim) description.
     *
     * T-2026-059 iteration 3 (Gap 6 fix): used ONLY as the secondary matching key
     * for requisition_items rows whose part_item_id is NULL (a BOM Excel row the
     * fuzzy part-matcher, T-2026-052, could not resolve — see
     * bom_material_items.part_item_id nullability). part_item_id itself can never
     * be used for these rows (it's null on both the BOM side and the persisted
     * side), so product_description + length is the next-best stable identity.
     */
    private static function normalizeDescriptionKey($description): string
    {
        $trimmed = trim((string) ($description ?? ''));

        return $trimmed === '' ? 'NULL' : mb_strtolower($trimmed);
    }

    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {
            $acceptdesign = $id;
            $update_data = $this->service->orderAcceptedAndMaterialForwareded($acceptdesign);
            return redirect('storedept/list-accepted-design-from-prod');
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function createRequesition($createRequesition)
    {
        try {
            return view('organizations.store.requistion.add-requistion', compact('createRequesition'));
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function storeRequesition(Request $request)
    {
        try {
            $add_record = $this->service->storeRequesition($request);
            if ($add_record) {
                $msg = $add_record['msg'];
                $status = $add_record['status'];

                if ($status == 'success') {
                    return redirect('storedept/list-material-sent-to-purchase')->with(compact('msg', 'status'));
                } else {
                    return redirect('storedept/add-requistion')->withInput()->with(compact('msg', 'status'));
                }
            }
            // }
        } catch (Exception $e) {
            return redirect('storedept/add-requistion')->withInput()->with(['msg' => 'Something went wrong. Please try again.', 'status' => 'error']);
        }
    }



    public function genrateStoreReciptAndForwardMaterialToTheProduction($purchase_orders_id, $business_id)
    {
        try {
            $acceptdesign = base64_decode($purchase_orders_id);
            $acceptbusinessId = base64_decode($business_id);
            $update_data = $this->service->genrateStoreReciptAndForwardMaterialToTheProduction($acceptdesign, $acceptbusinessId);
            return redirect('storedept/list-accepted-design-from-prod');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function editProductMaterialWiseAddNewReq($id)
    { //checked
        try {
            $id = $id;
            $editData = $this->service->editProductMaterialWiseAddNewReq($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->orderByRaw('LOWER(description) ASC')->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.store.list.edit-material-bom-wise-add-new-req', [
                'productDetails' => $editData['productDetails'],
                // 'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputByid' => $editData['dataOutputByid'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster' => $dataOutputUnitMaster,

                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }
    public function checkStockQuantity(Request $request)
    {
        try {
            $partItemId = $request->input('part_item_id');
            $quantity = $request->input('quantity');
            $materialSendProduction = $request->input('material_send_production');
            $quantityMinusStatus = $request->input('quantity_minus_status');
            $isInsertOrUpdate = $request->input('is_insert_or_update', false); // Add a flag to check if it's a new submission

            Log::info('Checking stock quantity', [
                'part_item_id' => $partItemId,
                'quantity' => $quantity,
                'material_send_production' => $materialSendProduction,
                'quantity_minus_status' => $quantityMinusStatus,
                'is_insert_or_update' => $isInsertOrUpdate
            ]);

            // **Bypass stock validation if it's a new insert or update request**
            if ($isInsertOrUpdate) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Stock check skipped (Insert/Update Mode)',
                ]);
            }

            // If already processed, SKIP checking stock
            if ($materialSendProduction == 1 && $quantityMinusStatus == 'done') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Stock check skipped (already processed)',
                ]);
            }

            // Validate inputs
            if (!$partItemId || !$quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid inputs. Please provide both part_item_id and quantity.',
                ], 400);
            }

            // Fetch the part item stock
            // $partItem = ItemStock::find($partItemId);
            $partItem = ItemStock::where('part_item_id', $partItemId)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->first();

            if (!$partItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Part Item not found',
                ], 404);
            }

            // Get the available quantity
            $availableQuantity = $partItem->quantity ?? 0;

            if ($quantity > $availableQuantity) {
                return response()->json([
                    'status' => 'error',
                    'available_quantity' => $availableQuantity,
                    'message' => 'Insufficient stock',
                ]);
            }

            // Sufficient stock
            return response()->json([
                'status' => 'success',
                'available_quantity' => $availableQuantity,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in checkStockQuantity:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
            ], 500);
        }
    }
    public function updateProductMaterialWiseAddNewReq(Request $request)
    {
        try {

            $updateData = $this->service->updateProductMaterialWiseAddNewReq($request);

            if ($updateData['status'] === 'error') {

                return redirect()->back()
                    ->withInput()
                    ->with([
                        'status' => 'error',
                        'msg' => $updateData['message'] ?? implode(', ', $updateData['errors'] ?? [])
                    ]);
            }

            return redirect()->back()->with([
                'status' => 'success',
                'msg' => $updateData['message']
            ]);
        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with([
                'status' => 'error',
                'msg' => 'Something went wrong. Please try again.'
            ]);
        }
    }
    // public function updateProductMaterialWiseAddNewReq(Request $request)
    // {
    //     $rules = [];

    //     $messages = [];

    //     $validation = Validator::make($request->all(), $rules, $messages);

    //     if ($validation->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validation);
    //     }

    //     try {
    //         $updateData = $this->service->updateProductMaterialWiseAddNewReq($request);

    //         if ($updateData['status'] == 'success') {

    //             // return redirect('storedept/list-accepted-design-from-prod')->with(['status' => 'success', 'msg' => $updateData['message']]);
    //             return redirect()->back()
    //                 ->with(['status' => 'success', 'msg' => $updateData['message']]);
    //         } else {
    //             return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
    //     }
    // }
    public function getPartItemRate(Request $request)
    {
        $partItem = PartItem::find($request->part_item_id);

        if ($partItem) {
            return response()->json([
                'status' => 'success',
                'basic_rate' => $partItem->basic_rate // adjust field name if different
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Part item not found']);
        }
    }

    public function editProductMaterialWiseAdd($purchase_orders_id, $business_id)
    {
        try {
            $purchase_orders_id = base64_decode($purchase_orders_id);

            $business_id = base64_decode($business_id);
            $editData = $this->service->editProductMaterialWiseAdd($purchase_orders_id, $business_id);
            $dataOutputPartItem = PartItem::where('is_active', true)->orderByRaw('LOWER(description) ASC')->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.store.list.edit-material-bom-wise-add', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster' => $dataOutputUnitMaster,
                'purchase_orders_id' => $purchase_orders_id,
                'business_id' => $business_id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }
    public function updateProductMaterialWiseAdd(Request $request)
    {
        $rules = [];

        $messages = [];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        try {
            $updateData = $this->service->updateProductMaterialWiseAdd($request);

            if ($updateData['status'] == 'success') {
                return redirect('storedept/list-accepted-design-from-prod')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }
    public function generateSRstoreDept(Request $request)
    {
        try {
            if (empty($request->id)) {
                return redirect()->back()->with('error', 'GRN ID is required.');
            }
            $gatepass = GRNModel::where('id', $request->id)->first();

            if (!$gatepass) {
                return redirect()->back()->with('error', 'GRN not found.');
            }
            $store_receipt_no_generate = str_replace(['-', ':', ' '], '', date('YmdHis'));
            $gatepass->store_remark = $request->store_remark;
            $gatepass->store_receipt_no_generate = $store_receipt_no_generate;
            $gatepass->grn_status_sanction = config('constants.STORE_DEPARTMENT.STORE_RECIEPT_GENRATED_SENT_TO_FINANCE_GRN_WISE');

            if ($gatepass->save()) {
                return redirect('storedept/list-material-received-from-quality')
                    ->with('success', 'GRN updated successfully.');
            }
            return redirect()->back()->with('error', 'Failed to update GRN.');
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error in storeGRN: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while updating GRN.');
        }
    }


    public function destroyAddmoreStoreItem(Request $request)
    {
        try {
            $delete_record = $this->service->destroyAddmoreStoreItem($request->delete_id);

            return response()->json([
                'status' => $delete_record['status'],
                'msg'    => $delete_record['msg']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    /**
     * Show the BOM Inventory Check page for a given business_details_id.
     * Splits BOM items into "available" and "shortage" based on tbl_item_stock.
     *
     * @param  string  $business_details_id  base64-encoded businesses_details.id
     */
    public function showBomInventoryCheck($business_details_id)
    {
        try {
            $decoded_id = base64_decode($business_details_id);

            // Fetch product details
            $productDetails = BusinessDetails::find($decoded_id);
            if (!$productDetails) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'Product details not found.']);
            }

            // Fetch estimation amount for info box
            $estimationAmount = EstimationModel::where('business_details_id', $decoded_id)
                ->value('total_estimation_amount');

            // NOTE: No cleanup/mutation runs here. GET handlers must be idempotent.
            // Pending production_details rows survive page reloads unchanged.

            // Fetch trolley_qty for this order from designs table (default 1 if not set)
            $trolleyQty = (int) (\App\Models\DesignModel::where('business_details_id', $decoded_id)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->value('trolley_qty') ?: 1);

            // Fetch BOM items for this business_details_id
            $bomItems = BomMaterialItem::where('business_details_id', $decoded_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->with(['partItem', 'unitMaster'])
                ->get();

            // Pre-fetch ALL issued records (BOM + non-BOM) with part item relation
            $issuedRecords = ProductionDetails::where('business_details_id', $decoded_id)
                ->where('material_send_production', 1)
                ->where('quantity_minus_status', 'done')
                ->where('is_deleted', 0)
                ->whereNotNull('part_item_id')
                ->where('part_item_id', '!=', '')
                ->with(['partItemRelation', 'unitMasterRelation'])
                ->get();

            $alreadyIssuedIds = $issuedRecords->pluck('part_item_id')->toArray();
            $issuedDateMap    = $issuedRecords->pluck('updated_at', 'part_item_id');

            $available               = [];
            $shortage                = [];
            $alreadyIssued           = [];
            $availableFromProduction = [];

            // BOM part_item_ids for dedup when adding non-BOM issued items later
            $bomPartItemIds = $bomItems->pluck('part_item_id')->filter()->toArray();

            foreach ($bomItems as $item) {
                if ($item->part_item_id) {
                    $availableStock = (float) ItemStock::where('part_item_id', $item->part_item_id)
                        ->where('is_deleted', 0)
                        ->where('is_active', 1)
                        ->sum('quantity');
                } else {
                    $availableStock = 0.0;
                }

                // T-2026-059: unit-aware, trolley-scaled required quantity — the actual
                // total needed across ALL trolleys for this order (piece units use
                // `quantity`, length/raw units use `mtr_for_01_nos_trolley`, both scaled
                // by trolleyQty). This is the figure that must be compared against real
                // stock to decide availability, and it is what gets sent to Purchase for
                // genuinely-short items.
                $unitName          = (string) ($item->unit ?: (optional($item->unitMaster)->name ?? optional($item->unitMaster)->unit_name ?? ''));
                $scaledRequiredQty = \App\Support\BomTotalCalculator::scaledQuantity(
                    $item->quantity,
                    $item->mtr_for_01_nos_trolley,
                    $unitName,
                    $trolleyQty
                );
                // Raw (unscaled, non-unit-aware) BOM quantity — kept for the Already
                // Issued / Available Materials tables + "Additional Items to Issue" grid,
                // which have their OWN independent trolley-scaling logic in the blade
                // ($computeMtrN/$computeIssueQty, T-2026-058) that already assumes
                // required_quantity is this raw figure. Re-using $scaledRequiredQty there
                // would double-apply the trolley factor.
                $rawRequiredQty = (float) $item->quantity;

                $item->available_stock = $availableStock;

                if ($item->part_item_id && in_array($item->part_item_id, $alreadyIssuedIds)) {
                    $item->required_quantity = $rawRequiredQty;
                    $item->shortage_quantity = max(0, $rawRequiredQty - $availableStock);
                    $item->issued_at = $issuedDateMap->get($item->part_item_id);
                    $alreadyIssued[] = $item;
                } elseif ($availableStock >= $scaledRequiredQty) {
                    $item->required_quantity = $rawRequiredQty;
                    $item->shortage_quantity = 0.0;
                    $available[] = $item;
                } else {
                    $item->required_quantity = $scaledRequiredQty;
                    $item->shortage_quantity = \App\Support\BomTotalCalculator::shortage($scaledRequiredQty, $availableStock);
                    $shortage[]  = $item;
                }
            }

            // Add non-BOM issued items (production-requested items already issued by store)
            foreach ($issuedRecords as $issued) {
                if (in_array($issued->part_item_id, $bomPartItemIds)) {
                    continue; // already handled in BOM loop above
                }
                $norm = new \stdClass();
                $norm->part_item_id        = $issued->part_item_id;
                $norm->product_description = null; // blade falls back to $item->partItem->description
                $norm->required_quantity   = (float) $issued->quantity;
                $norm->rate                = $issued->basic_rate ?? 0;
                $norm->unit_id             = $issued->unit;
                $norm->partItem            = $issued->partItemRelation;
                $norm->unitMaster          = $issued->unitMasterRelation;
                $norm->issued_at           = $issued->updated_at;
                // T-2026-058: non-BOM issued row — no BOM context, blade renders em-dash for these.
                $norm->length              = null;
                $norm->total_in_mm         = null;
                $alreadyIssued[]           = $norm;
            }

            // Fetch pending material requests added manually by Production department.
            // quantity_minus_status='pending' is the authoritative signal — do NOT filter
            // on material_send_production here because stale rows may have it set to 1.
            $pendingRows = ProductionDetails::where('business_details_id', $decoded_id)
                ->where('quantity_minus_status', 'pending')
                ->where('is_deleted', 0)
                ->whereNotNull('part_item_id')
                ->where('part_item_id', '!=', '')
                ->where('quantity', '>', 0)
                ->with(['partItemRelation', 'unitMasterRelation'])
                ->get();

            // Running per-part issuable balance. Several pending rows can reference the SAME
            // part_item_id and they all draw down one physical stock balance, so the issuable
            // figure must be allocated from a shared pool in row order. Without this the page
            // would offer the same physical unit on two rows and the second issuance would fail
            // issueAvailableMaterials()'s stock check, rolling the whole submission back.
            // NOTE: only the ISSUABLE figure is pool-allocated. shortage_quantity below stays on
            // the per-row (req - full stock) basis so it continues to agree with the
            // production_shortage draft written by ProductionRepository::upsert(), which computes
            // it the same way.
            $prodStockPool = [];

            foreach ($pendingRows as $pitem) {
                // Each pending production_details row stands alone — show ALL pending
                // requests regardless of whether the same part_item_id already appears in
                // BOM or was previously issued. Production dept intentionally added new
                // requests for more material; silently hiding them is incorrect.
                // (pendingRows query already guards quantity_minus_status='pending' +
                //  is_deleted=0 + quantity>0, so $alreadyIssuedIds can never overlap.)

                $stockQty = (float) ItemStock::where('part_item_id', $pitem->part_item_id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->sum('quantity');

                $reqQty      = (float) $pitem->quantity;
                $shortageQty = max(0, $reqQty - $stockQty);

                $partKey = (string) $pitem->part_item_id;
                if (!array_key_exists($partKey, $prodStockPool)) {
                    $prodStockPool[$partKey] = $stockQty;
                }
                $issuableQty = min($reqQty, max(0.0, $prodStockPool[$partKey]));

                // Normalise to same shape as BOM items so blade template works unchanged
                $norm = new \stdClass();
                $norm->pd_id              = $pitem->id;     // unique identifier for this request row
                $norm->part_item_id       = $pitem->part_item_id;
                $norm->product_description = optional($pitem->partItemRelation)->description ?? '';
                $norm->required_quantity   = $reqQty;
                $norm->available_stock     = $stockQty;
                $norm->shortage_quantity   = $shortageQty;
                $norm->unit_id             = $pitem->unit;  // production_details.unit stores the unit ID
                $norm->rate                = $pitem->basic_rate ?? optional($pitem->partItemRelation)->basic_rate ?? 0;
                $norm->partItem            = $pitem->partItemRelation;
                $norm->unitMaster          = $pitem->unitMasterRelation;
                $norm->source              = 'production';  // display badge in blade
                $norm->design_id           = null;
                $norm->created_at          = $pitem->created_at;
                // T-2026-058: production-request row — no BOM context, blade renders em-dash for these.
                $norm->length              = null;
                $norm->total_in_mm         = null;

                // T-2026-060: a request that stock can only PARTLY cover is split rather than
                // dumped wholesale into the shortage list. Production asking for 3 when 1 is on
                // the shelf must let Store issue that 1 now and raise a requisition for the
                // outstanding 2 — previously the whole 3 went to purchase and the unit already
                // in stock sat idle.
                if ($issuableQty >= $reqQty) {
                    // Fully covered — issue the lot, nothing to purchase.
                    $prodStockPool[$partKey] -= $reqQty;
                    $availableFromProduction[] = $norm;  // pre-filled in "Additional Items to Issue" rows
                } elseif ($issuableQty > 0) {
                    // PARTIAL: issuable slice now, remainder to purchase. Both halves point at the
                    // same pd_id — issueAvailableMaterials() closes only the issued portion and
                    // re-opens a pending row for the balance, which keeps this row's
                    // production_shortage draft alive until purchase delivers.
                    $prodStockPool[$partKey] -= $issuableQty;

                    $issuableRow = clone $norm;   // shallow clone is safe: only scalars are reassigned
                    $issuableRow->required_quantity  = $issuableQty;
                    $issuableRow->shortage_quantity  = 0.0;
                    $issuableRow->is_partial_issue   = true;
                    $issuableRow->requested_quantity = $reqQty;                 // what Production asked for
                    $issuableRow->pending_quantity   = $reqQty - $issuableQty;  // what purchase must cover
                    $availableFromProduction[] = $issuableRow;

                    $norm->is_partial_issue   = true;
                    $norm->issuable_quantity  = $issuableQty;
                    $shortage[] = $norm;                 // needs purchase requisition for the balance
                } else {
                    $shortage[] = $norm;                 // nothing on the shelf — all of it to purchase
                }
            }

            $partItems   = PartItem::where('is_active', true)->orderByRaw('LOWER(description) ASC')->get();
            $unitMasters = UnitMaster::where('is_active', true)->get();

            // Check if shortage requisition was already sent to purchase
            $bap = \App\Models\BusinessApplicationProcesses::where('business_details_id', $decoded_id)->first();
            $requisitionSent = $bap && $bap->store_status_id == config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');

            // When the production/dispatch is CLOSED (dispatch_status_id == 1154),
            // the page becomes preview-only — no Add row, no "Issue to Production",
            // no "Send Shortage List as Requisition to Purchase".
            $isClosed = $bap && (int) $bap->dispatch_status_id === 1154;

            // Build lookup maps for all requisition_items belonging to this business_details_id's
            // requisition, keyed by the requisition_items PRIMARY KEY (not part_item_id — T-2026-059
            // fix for Defect 2(ii): BOM legitimately holds multiple rows per part_item_id at
            // different lengths, and keying by part_item_id alone collapsed them onto one entry,
            // hiding/mis-assigning distinct rows). Three secondary composite-key indexes are built
            // alongside for O(1) matching:
            //   - $reqItemsByPartLength: "partItemId|lengthKey" => requisition_item_id, for BOM-
            //     derived shortage rows that DO have a resolved part_item_id (matched by
            //     part_item_id + BOM length).
            //   - $reqItemsByPartForProd: partItemId => requisition_item_id, for production-shortage
            //     draft rows (ProductionRepository::upsert, source='production_shortage') which never
            //     carry a BOM length and are matched by part_item_id alone — kept in a SEPARATE index
            //     so a piece-unit BOM row (length=NULL) can never collide with an unrelated
            //     production-shortage draft for the same part (both would otherwise share the same
            //     "partId|NULL" key).
            //   - $reqItemsByNullPartKey: "lengthKey|descriptionKey" => requisition_item_id, for BOM-
            //     derived shortage rows whose part_item_id itself is NULL (T-2026-052 fuzzy
            //     part-matching miss on a BOM Excel row, submitted anyway — Gap 6 fix, T-2026-059
            //     iteration 3). part_item_id can never be the matching key here since it's null on
            //     both sides, so product_description + length is used instead. production_shortage
            //     rows are excluded from this index (ProductionRepository::upsert only ever writes
            //     rows with a non-null part_item_id — see its whereNotNull('part_item_id') guard —
            //     so this branch is reachable only for bom_shortage/manual_shortage-sourced rows,
            //     kept as an explicit guard for defense in depth rather than an assumption).
            $sentPartIds  = [];  // kept for backward compat with BOM-derived shortage badge logic below
            $reqItemsMap           = [];  // requisition_item_id => RequisitionItem Eloquent model
            $reqItemsByPartLength  = [];  // "partItemId|lengthKey" => requisition_item_id
            $reqItemsByPartForProd = [];  // partItemId => requisition_item_id (source=production_shortage only)
            $reqItemsByNullPartKey = [];  // "lengthKey|descriptionKey" => requisition_item_id (part_item_id IS NULL only)
            $hasDraftRows = false; // true when any requisition_items row has is_sent_to_purchase = 0
            $requisitionId = $bap ? $bap->requisition_id : null;

            if ($requisitionId) {
                $allReqItems = RequisitionItem::where('requisition_id', $requisitionId)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->with(['partItem', 'unitMaster'])
                    ->get();

                foreach ($allReqItems as $ri) {
                    $reqItemsMap[$ri->id] = $ri;

                    if ($ri->part_item_id !== null) {
                        if ($ri->source === 'production_shortage') {
                            if (!isset($reqItemsByPartForProd[$ri->part_item_id]) || (int) $ri->is_sent_to_purchase === 1) {
                                $reqItemsByPartForProd[$ri->part_item_id] = $ri->id;
                            }
                        } else {
                            $lengthKey    = self::normalizeLengthKey($ri->length);
                            $compositeKey = $ri->part_item_id . '|' . $lengthKey;
                            // Prefer a sent (=1) row over a draft (=0) row when more than one row
                            // happens to share the same part_item_id+length.
                            if (!isset($reqItemsByPartLength[$compositeKey]) || (int) $ri->is_sent_to_purchase === 1) {
                                $reqItemsByPartLength[$compositeKey] = $ri->id;
                            }
                        }

                        if ((int) $ri->is_sent_to_purchase === 1) {
                            $sentPartIds[] = (string) $ri->part_item_id; // used by BOM-derived shortage rows for badge
                        }
                    } elseif (($ri->source ?? null) !== 'production_shortage') {
                        // Gap 6 fix: null-part_item_id row — match by product_description + length
                        // instead, so it can be re-matched to itself on the next page load (see
                        // the matching loop below).
                        $nullKey = self::normalizeLengthKey($ri->length) . '|' . self::normalizeDescriptionKey($ri->product_description);
                        if (!isset($reqItemsByNullPartKey[$nullKey]) || (int) $ri->is_sent_to_purchase === 1) {
                            $reqItemsByNullPartKey[$nullKey] = $ri->id;
                        }
                    }

                    if ((int) $ri->is_sent_to_purchase === 0) {
                        $hasDraftRows = true;
                    }
                }
                $sentPartIds = array_unique($sentPartIds);
            }

            // Mark BOM-derived + production-request shortage rows with their per-row
            // is_sent_to_purchase value, matched via the appropriate composite key.
            $matchedReqItemIds = []; // requisition_item_id => true, tracks rows already surfaced above
            foreach ($shortage as $sItem) {
                $ri = null;

                if (!empty($sItem->part_item_id)) {
                    if (($sItem->source ?? null) === 'production') {
                        // Production-request row (from production_details, quantity_minus_status=pending) —
                        // match against the production_shortage draft index (part_item_id only, no length).
                        if (isset($reqItemsByPartForProd[$sItem->part_item_id])) {
                            $ri = $reqItemsMap[$reqItemsByPartForProd[$sItem->part_item_id]] ?? null;
                        }
                    } else {
                        // Genuine BOM row — match by part_item_id + BOM length.
                        $lengthKey    = self::normalizeLengthKey($sItem->length ?? null);
                        $compositeKey = $sItem->part_item_id . '|' . $lengthKey;
                        if (isset($reqItemsByPartLength[$compositeKey])) {
                            $ri = $reqItemsMap[$reqItemsByPartLength[$compositeKey]] ?? null;
                        }
                    }
                } elseif (($sItem->source ?? null) !== 'production') {
                    // Gap 6 fix (T-2026-059 iteration 3): genuine BOM row whose part_item_id is
                    // NULL (unmatched during BOM Excel fuzzy-matching, T-2026-052) — match by
                    // product_description + length against $reqItemsByNullPartKey instead, so a
                    // previously-sent null-part_item_id row is correctly recognised as already
                    // sent, instead of falling through as "not yet sent" and being displayed a
                    // second time by the leftover-row surfacing loop below.
                    $nullKey = self::normalizeLengthKey($sItem->length ?? null) . '|' . self::normalizeDescriptionKey($sItem->product_description ?? null);
                    if (isset($reqItemsByNullPartKey[$nullKey])) {
                        $ri = $reqItemsMap[$reqItemsByNullPartKey[$nullKey]] ?? null;
                    }
                }

                if ($ri) {
                    $sItem->is_sent_to_purchase = (int) $ri->is_sent_to_purchase;
                    $sItem->requisition_item_id  = $ri->id;
                    $matchedReqItemIds[$ri->id]  = true;
                } else {
                    $sItem->is_sent_to_purchase = null; // not in requisition at all
                    $sItem->requisition_item_id  = null;
                }
            }

            // Surface leftover requisition_items rows (added via +Add More, or a
            // production_shortage draft whose production_details row was itself removed/
            // resolved between page loads, or any row with a NULL part_item_id) in the
            // shortage display list — i.e. every row NOT already matched above. Now they
            // carry is_sent_to_purchase per row: 0 = draft (orange badge), 1 = sent (green badge).
            foreach ($reqItemsMap as $reqItemId => $mri) {
                if (isset($matchedReqItemIds[$reqItemId])) {
                    continue; // already surfaced via the BOM/production match above
                }

                $isSentRow  = (int) $mri->is_sent_to_purchase === 1;
                $mriUnitName = optional($mri->unitMaster)->name ?? optional($mri->unitMaster)->unit_name ?? '';
                $mriIsScaled = (int) ($mri->is_qty_trolley_scaled ?? 0) === 1;
                // production_shortage rows (ProductionRepository.php — out of this task's scope)
                // are NEVER "per 1 trolley" figures to begin with (production_details.quantity is
                // already the final quantity a concrete production run needs) — the trolley-scaling
                // retroactive-correction concept simply does not apply to them, so they are always
                // displayed verbatim regardless of is_qty_trolley_scaled. This only matters for the
                // rare orphaned case where the underlying production_details pending row was itself
                // resolved/removed between page loads (normally these rows are matched+displayed
                // from the live pendingRows computation above, never reaching this branch at all).
                $isProductionShortage = ($mri->source ?? null) === 'production_shortage';

                $norm = new \stdClass();
                $norm->requisition_item_id = $mri->id;
                $norm->part_item_id        = $mri->part_item_id;
                $norm->product_description = $mri->product_description ?? (optional($mri->partItem)->description ?? '');
                if ($isSentRow && !$isProductionShortage) {
                    // Immutable (sent rows can never be edited again — see updateDraftShortageItem's
                    // 403 guard), so it is SAFE to retroactively correct legacy pre-T-2026-059 rows
                    // for display: this can never desync from what gets saved, because nothing will
                    // ever save over it again.
                    $norm->required_quantity = \App\Support\BomTotalCalculator::effectiveRequiredQuantity(
                        $mri->required_quantity, $mri->mtr_for_01_nos_trolley, $mriUnitName,
                        $mri->trolley_qty, $trolleyQty, $mriIsScaled
                    );
                    $norm->shortage_quantity = \App\Support\BomTotalCalculator::effectiveShortageQuantity(
                        $mri->available_quantity, $mri->required_quantity, $mri->mtr_for_01_nos_trolley, $mriUnitName,
                        $mri->trolley_qty, $trolleyQty, $mriIsScaled
                    );
                } else {
                    // Still-editable draft — keep the stored raw value verbatim. It remains
                    // user-editable and the server always recomputes the correct scaled figure
                    // on next save (storeAdditionalShortageRequisition / updateDraftShortageItem),
                    // regardless of what is shown here meanwhile.
                    $norm->required_quantity = (float) $mri->required_quantity;
                    $norm->shortage_quantity = (float) $mri->shortage_quantity;
                }
                $norm->available_stock     = (float) $mri->available_quantity;
                $norm->unit_id             = $mri->unit_id;
                $norm->rate                = $mri->rate;
                $norm->partItem            = $mri->partItem;
                $norm->unitMaster          = $mri->unitMaster;
                $norm->created_at          = $mri->created_at;
                // T-2026-059 iteration 2 (reviewer-flagged, LOW severity): as of this fix, every
                // NEW row written by storeShortageRequisition() is explicitly tagged
                // ('bom_shortage' or 'manual_shortage' — see both create() calls above) and
                // storeAdditionalShortageRequisition() already tags its own rows
                // ('manual_shortage'), so `$mri->source` should no longer be null for rows
                // created after this fix ships. The `?? 'manual_shortage'` fallback is kept,
                // narrowly, only for rows that predate this fix (source was never stamped before)
                // — such a row could, in a narrow window, be a genuinely BOM-derived row that
                // this fallback would mislabel as 'manual_shortage' for source/badge display
                // purposes if its part_item_id+length later stopped matching any current BOM row
                // (e.g. after a BOM re-upload changed the length). This is cosmetic (this
                // codebase never renders `source` as a user-facing label anywhere on this page)
                // and self-heals: running "Resync with Current BOM/Stock" does not relabel this
                // field, but any pre-fix row eventually becomes indistinguishable from a genuine
                // manual row in practice once no current BOM row references its old length.
                $norm->source              = $mri->source ?? 'manual_shortage';
                $norm->is_sent_to_purchase = (int) $mri->is_sent_to_purchase;
                // requisition_items DOES carry mtr_for_01_nos_trolley — the draft-row grid
                // renders it as an editable input plus a derived "for N trolleys" cell, so it
                // must always be present on the normalised row (null when never captured).
                $norm->mtr_for_01_nos_trolley = $mri->mtr_for_01_nos_trolley ?? null;
                // T-2026-058: requisition_items has no total_in_mm column — no BOM context
                // for manually-added shortage rows, blade renders em-dash for these. `length`
                // IS now carried through (T-2026-059) purely for matching provenance; these
                // rows are never BOM-length rows so it is null in practice, kept explicit here.
                $norm->length              = $mri->length ?? null;
                $norm->total_in_mm         = null;
                $shortage[]                = $norm;
                $matchedReqItemIds[$reqItemId] = true;
            }

            // Split $shortage into sent (=1 or null — formal requisition rows) and draft (=0 — manually added, not yet sent).
            // BOM/production-derived rows have is_sent_to_purchase=null (not in requisition) or =1 (sent).
            // Manual +Add More rows have is_sent_to_purchase=0 (draft) or =1 (sent).
            // Only is_sent_to_purchase=0 rows go into $shortageDraft; everything else stays in $shortageSent.
            $shortageSent  = array_values(array_filter($shortage, function ($r) {
                return !isset($r->is_sent_to_purchase) || (int) $r->is_sent_to_purchase !== 0;
            }));
            $shortageDraft = array_values(array_filter($shortage, function ($r) {
                return isset($r->is_sent_to_purchase) && (int) $r->is_sent_to_purchase === 0;
            }));
            $hasDraftRows = count($shortageDraft) > 0;

            return view('organizations.store.list.bom-inventory-check', compact(
                'available',
                'shortage',
                'shortageSent',
                'shortageDraft',
                'alreadyIssued',
                'availableFromProduction',
                'productDetails',
                'estimationAmount',
                'business_details_id',
                'partItems',
                'unitMasters',
                'requisitionSent',
                'sentPartIds',
                'isClosed',
                'hasDraftRows',
                'requisitionId',
                'trolleyQty'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }

    /**
     * Create a requisition from the shortage items identified on the BOM Inventory Check page.
     * Creates a Requisition record, RequisitionItem records for each shortage item,
     * and updates the BAP store_status_id to "sent to purchase".
     */
    public function storeShortageRequisition(Request $request)
    {
        try {
            $business_details_id = $request->input('business_details_id');
            $business_id         = $request->input('business_id');
            $design_id           = $request->input('design_id');

            // Server-side guard — block re-submission if already sent
            $alreadySent = \App\Models\BusinessApplicationProcesses::where('business_details_id', $business_details_id)
                ->where('store_status_id', config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE'))
                ->exists();
            if ($alreadySent) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'Requisition already sent to Purchase department.']);
            }
            $items           = $request->input('items', []);
            $manual_shortage = $request->input('manual_shortage', []);

            if (empty($items) && empty($manual_shortage)) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'No shortage items to submit.']);
            }

            // T-2026-059: resolve trolleyQty server-side (never trust a client-posted
            // trolley count) — used to stamp every new row's audit-trail trolley_qty
            // column and to unit-aware + trolley-scale genuinely-manual rows.
            $trolleyQty = $this->resolveTrolleyQty($business_details_id);

            DB::transaction(function () use ($business_details_id, $business_id, $design_id, $items, $manual_shortage, $trolleyQty) {

                // 3 first: fetch BAP to get authoritative design_id and production_id
                $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();

                // Resolve design_id and production_id from BAP (NOT NULL columns in requisition table)
                $resolved_design_id    = ($business_application && $business_application->design_id)    ? $business_application->design_id    : ($design_id ?: 0);
                $resolved_production_id = ($business_application && $business_application->production_id) ? $business_application->production_id : 0;
                $resolved_business_id  = ($business_application && $business_application->business_id)  ? $business_application->business_id  : $business_id;

                // 1. Check if requisition already exists for this business_details_id
                $requisition = Requisition::where('business_details_id', $business_details_id)->first();

                if (!$requisition) {
                    $requisition = new Requisition();
                    $requisition->req_name            = 'Auto-Requisition';
                    $requisition->business_id         = $resolved_business_id;
                    $requisition->business_details_id = $business_details_id;
                    $requisition->design_id           = $resolved_design_id;
                    $requisition->production_id       = $resolved_production_id;
                    $requisition->req_date            = date('Y-m-d');
                    $requisition->bom_file            = null;
                    $requisition->save();
                }

                // 2. Create requisition_items for each BOM shortage item
                // Remove old items for idempotency (re-submit scenario)
                RequisitionItem::where('requisition_id', $requisition->id)->delete();

                foreach ($items as $itemData) {
                    RequisitionItem::create([
                        'requisition_id'         => $requisition->id,
                        'business_details_id'    => $business_details_id,
                        'part_item_id'           => ($itemData['part_item_id'] ?? '') !== '' ? $itemData['part_item_id'] : null,
                        'product_description'    => $itemData['product_description'] ?? null,
                        // T-2026-059: this value is already the unit-aware, trolley-scaled TOTAL —
                        // computed server-side in showBomInventoryCheck() and merely echoed back
                        // through the hidden form, never recomputed from raw client input here.
                        'required_quantity'      => (float) ($itemData['required_quantity'] ?? 0),
                        'available_quantity'     => (float) ($itemData['available_quantity'] ?? 0),
                        'shortage_quantity'      => (float) ($itemData['shortage_quantity'] ?? 0),
                        'unit_id'                => ($itemData['unit_id'] ?? '') !== '' ? $itemData['unit_id'] : null,
                        'rate'                   => isset($itemData['rate']) && $itemData['rate'] !== '' ? (float) $itemData['rate'] : null,
                        'mtr_for_01_nos_trolley' => isset($itemData['mtr_for_01_nos_trolley']) && $itemData['mtr_for_01_nos_trolley'] !== '' ? (float) $itemData['mtr_for_01_nos_trolley'] : null,
                        'trolley_qty'            => $trolleyQty,
                        'length'                 => isset($itemData['length']) && $itemData['length'] !== '' ? (float) $itemData['length'] : null,
                        'is_qty_trolley_scaled'  => 1,
                        'is_active'              => 1,
                        'is_deleted'             => 0,
                        'is_sent_to_purchase'    => 1, // BOM-derived shortage: part of the official requisition send
                        // T-2026-059 iteration 2: explicitly tagged (was left unset/null) so this
                        // row's true provenance is never ambiguous — see the leftover-row surfacing
                        // comment in showBomInventoryCheck() below for why this matters.
                        'source'                 => 'bom_shortage',
                    ]);
                }

                // 2b. Create requisition_items for each manually-added shortage row.
                // These are submitted together with the initial BOM requisition, so they ARE
                // part of the official send → is_sent_to_purchase = 1.
                foreach ($manual_shortage as $ms) {
                    $msPartItemId = ($ms['part_item_id'] ?? '') !== '' ? (int) $ms['part_item_id'] : null;
                    if (!$msPartItemId) continue; // skip rows with no part selected
                    $msQty = (float) ($ms['required_quantity'] ?? 0);
                    if ($msQty <= 0) continue;   // skip rows with no quantity
                    $msUnitId = ($ms['unit_id'] ?? '') !== '' ? (int) $ms['unit_id'] : null;
                    if (!$msUnitId) continue;     // skip rows with no unit

                    $msAvailQty    = (float) ($ms['available_quantity'] ?? 0);
                    $msRate        = isset($ms['rate']) && $ms['rate'] !== '' ? (float) $ms['rate'] : null;
                    $msDesc        = $ms['product_description'] ?? null;
                    $msMtr1        = isset($ms['mtr_for_01_nos_trolley']) && $ms['mtr_for_01_nos_trolley'] !== '' ? (float) $ms['mtr_for_01_nos_trolley'] : null;
                    $msLength      = isset($ms['length']) && $ms['length'] !== '' ? (float) $ms['length'] : null;

                    // Dedup: skip only if this EXACT part_item_id + length combo is already in
                    // the requisition. T-2026-059 iteration 2: made length-aware (was
                    // part_item_id-only) — a part_item_id-only check would silently drop a
                    // legitimate same-part-different-length shortage row, reproducing the exact
                    // "rows silently dropped" defect class Defect 2(ii) fixed on the read side
                    // ($reqItemsMap/$reqItemsByPartLength above). Reuses normalizeLengthKey() (the
                    // same DB-decimal-formatting-safe identity used throughout this file) rather
                    // than comparing raw length values, which could false-negative on formatting
                    // differences like "500.000" vs "500.0".
                    $msLengthKey  = self::normalizeLengthKey($msLength);
                    // T-2026-059 iteration 3 (defensive, code_reviewer Issue 3): scope the dedup
                    // read to is_active=1/is_deleted=0 rows only. Currently unreachable in
                    // practice (grepped the whole app — nothing ever sets
                    // requisition_items.is_deleted=1 today), but a future soft-delete feature
                    // must not silently resurrect this same "legitimate row silently dropped"
                    // defect class by matching a soft-deleted row and skipping the new one.
                    $msIsDuplicate = RequisitionItem::where('requisition_id', $requisition->id)
                        ->where('part_item_id', $msPartItemId)
                        ->where('is_active', 1)
                        ->where('is_deleted', 0)
                        ->get(['length'])
                        ->contains(fn ($existing) => self::normalizeLengthKey($existing->length) === $msLengthKey);
                    if ($msIsDuplicate) {
                        continue;
                    }

                    // T-2026-059: `already_scaled` distinguishes a BOM-not-yet-sent row (already
                    // final/scaled, echoed back from the server-computed value — recomputing it
                    // here would double-apply the trolley factor for piece units) from a genuinely
                    // free-typed manual row (qty/mtr1 are raw per-1-trolley bases that MUST be
                    // unit-aware + trolley-scaled server-side, never trusting client-side math).
                    $msAlreadyScaled = (string) ($ms['already_scaled'] ?? '0') === '1';
                    $msUnitName      = $this->resolveUnitNameFromId($msUnitId);
                    $msFinalQty      = $msAlreadyScaled
                        ? $msQty
                        : \App\Support\BomTotalCalculator::scaledQuantity($msQty, $msMtr1, $msUnitName, $trolleyQty);
                    $msShortageQty   = \App\Support\BomTotalCalculator::shortage($msFinalQty, $msAvailQty);

                    RequisitionItem::create([
                        'requisition_id'         => $requisition->id,
                        'business_details_id'    => $business_details_id,
                        'part_item_id'           => $msPartItemId,
                        'product_description'    => $msDesc,
                        'required_quantity'      => $msFinalQty,
                        'available_quantity'     => $msAvailQty,
                        'shortage_quantity'      => $msShortageQty,
                        'unit_id'                => $msUnitId,
                        'rate'                   => $msRate,
                        'mtr_for_01_nos_trolley' => $msMtr1,
                        'trolley_qty'            => $trolleyQty,
                        'length'                 => $msLength,
                        'is_qty_trolley_scaled'  => 1,
                        'is_active'              => 1,
                        'is_deleted'             => 0,
                        'is_sent_to_purchase'    => 1, // submitted together with initial requisition → already sent
                        // T-2026-059 iteration 2: explicitly tagged (was left unset/null) — see the
                        // leftover-row surfacing comment in showBomInventoryCheck() below.
                        'source'                 => 'manual_shortage',
                    ]);
                }

                // 3. Update BAP
                if ($business_application) {
                    $business_application->store_status_id    = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');
                    $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE');
                    $business_application->requisition_id     = $requisition->id;
                    $business_application->off_canvas_status  = 16;
                    $business_application->save();

                    // 4. Update AdminView + NotificationStatus (same pattern as storeRequesition in StoreRepository)
                    AdminView::where('business_details_id', $business_details_id)
                        ->update(['off_canvas_status' => 16, 'is_view' => 0]);

                    NotificationStatus::where('business_details_id', $business_details_id)
                        ->update(['off_canvas_status' => 16, 'purchase_is_view' => 0]);
                }
            });

            return redirect('storedept/list-material-sent-to-purchase')
                ->with(['status' => 'success', 'msg' => 'Shortage requisition submitted to Purchase department successfully.']);

        } catch (\Exception $e) {
            Log::error('storeShortageRequisition error: ' . $e->getMessage());
            return redirect()->back()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }

    /**
     * Append new shortage items to the existing requisition already sent to purchase.
     * Only adds items — does NOT delete previously sent items and does NOT change BAP status.
     */
    public function storeAdditionalShortageRequisition(Request $request)
    {
        try {
            $business_details_id = $request->input('business_details_id');
            // Accept either items[] (from BOM not-sent form) or manual_shortage[] (from +Add More form), or both
            $items = array_merge(
                $request->input('items', []),
                $request->input('manual_shortage', [])
            );

            if (empty($items)) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'No additional items to submit.']);
            }

            // Requisition must already exist for this business_details_id
            $requisition = Requisition::where('business_details_id', $business_details_id)->first();
            if (!$requisition) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'No existing requisition found. Please send the main requisition first.']);
            }

            // T-2026-059: resolve trolleyQty server-side (never trust a client-posted
            // trolley count) — these draft rows must ALREADY carry the correct final
            // quantity when created, because sendPendingShortageToPurchase() later just
            // flips is_sent_to_purchase without recomputing anything.
            $trolleyQty = $this->resolveTrolleyQty($business_details_id);

            DB::transaction(function () use ($requisition, $business_details_id, $items, $trolleyQty) {
                // T-2026-059 iteration 4 (code_reviewer iteration-3 re-review MEDIUM finding —
                // resync-vs-storeAdditionalShortageRequisition race, reproduced with a real
                // 2-process test): re-fetch and lock the OWNING requisition row FIRST, inside
                // THIS transaction, before this method's own part_item_id+length dedup SELECT
                // below. The `$requisition` captured by the pre-transaction lookup above (used
                // only for its existence check and to know the id) is NOT locked — that read
                // happened before this transaction/lock scope even began, so it cannot serialize
                // against anything. Only a lock acquired freshly inside the transaction actually
                // contends with a concurrent resyncShortageRequisition() call for the SAME
                // requisition_id, which takes this exact same `Requisition::where('id',
                // $id)->lockForUpdate()->first()` as the FIRST statement inside its own
                // transaction (see the comment there). Without this, the reviewer's own
                // real 2-connection test demonstrated: this method's dedup SELECT below could run
                // and decide "not a duplicate" WHILE a concurrent resync's transaction was still
                // open (holding the requisition-row lock), and this method's subsequent INSERT
                // would merely queue behind an incidental InnoDB gap-lock on the requisition_items
                // composite index (not a deliberate mechanism, and not guaranteed to survive a
                // future index/query-shape change) and still proceed once resync committed —
                // producing 2 rows for the identical part_item_id+length. Re-locking here makes
                // the dedup decision happen only AFTER mutual exclusion with resync is genuinely
                // established, closing the window by direct, deliberate synchronization instead
                // of incidental index-locking behavior.
                $lockedRequisition = Requisition::where('id', $requisition->id)->lockForUpdate()->first();
                if (!$lockedRequisition) {
                    return; // requisition vanished between the pre-check and here — nothing to append to
                }

                foreach ($items as $itemData) {
                    $partItemId = ($itemData['part_item_id'] ?? '') !== '' ? (int) $itemData['part_item_id'] : null;
                    if (!$partItemId) continue; // skip rows without a part selected
                    $qty = (float) ($itemData['required_quantity'] ?? $itemData['quantity'] ?? 0);
                    if ($qty <= 0) continue;    // skip rows with no quantity
                    $unitId = ($itemData['unit_id'] ?? '') !== '' ? (int) $itemData['unit_id'] : null;

                    $availQty = (float) ($itemData['available_quantity'] ?? 0);
                    $mtr1     = isset($itemData['mtr_for_01_nos_trolley']) && $itemData['mtr_for_01_nos_trolley'] !== '' ? (float) $itemData['mtr_for_01_nos_trolley'] : null;
                    $length   = isset($itemData['length']) && $itemData['length'] !== '' ? (float) $itemData['length'] : null;

                    // Skip only if this EXACT part_item_id + length combo is already in the
                    // requisition (as any row, sent or draft). T-2026-059 iteration 2: made
                    // length-aware (was part_item_id-only) — this endpoint is reachable via the
                    // unified "Send to Purchase" button's State-2 AJAX chain, where
                    // bomNotSentItems (carrying a real, non-null BOM length) get concatenated
                    // into manual_shortage[] and posted here; a part_item_id-only dedup would
                    // silently drop a legitimate same-part-different-length shortage row whenever
                    // the requisition already has ANY row for that part at a DIFFERENT length —
                    // reproducing the exact "rows silently dropped" defect class Defect 2(ii) fixed
                    // on the read side. Reuses normalizeLengthKey() (same DB-decimal-formatting-safe
                    // identity used throughout this file) rather than comparing raw length values.
                    // T-2026-059 iteration 4: this SELECT now genuinely runs only after the
                    // requisition-row lock above is held, so its result can no longer be
                    // invalidated by a concurrent resync's still-in-flight insert.
                    $lengthKey  = self::normalizeLengthKey($length);
                    // T-2026-059 iteration 3 (defensive, code_reviewer Issue 3): scope the dedup
                    // read to is_active=1/is_deleted=0 rows only — see the identical comment in
                    // storeShortageRequisition()'s manual_shortage loop for the full rationale.
                    $isDuplicate = RequisitionItem::where('requisition_id', $lockedRequisition->id)
                        ->where('part_item_id', $partItemId)
                        ->where('is_active', 1)
                        ->where('is_deleted', 0)
                        ->get(['length'])
                        ->contains(fn ($existing) => self::normalizeLengthKey($existing->length) === $lengthKey);
                    if ($isDuplicate) {
                        continue;
                    }

                    // T-2026-059: `already_scaled` distinguishes a BOM-not-yet-sent row (already
                    // final/scaled, echoed back from the server-computed value) from a genuinely
                    // free-typed manual row (qty/mtr1 are raw per-1-trolley bases that MUST be
                    // unit-aware + trolley-scaled server-side).
                    $alreadyScaled = (string) ($itemData['already_scaled'] ?? '0') === '1';
                    $unitName      = $this->resolveUnitNameFromId($unitId);
                    $finalQty      = $alreadyScaled
                        ? $qty
                        : \App\Support\BomTotalCalculator::scaledQuantity($qty, $mtr1, $unitName, $trolleyQty);
                    // Prefer a client-posted shortage_quantity for the already-scaled BOM path
                    // (it was computed against a possibly-different available_quantity snapshot
                    // at render time); otherwise derive it from the just-scaled quantity.
                    $shortageQty = isset($itemData['shortage_quantity']) && $itemData['shortage_quantity'] !== ''
                        ? (float) $itemData['shortage_quantity']
                        : \App\Support\BomTotalCalculator::shortage($finalQty, $availQty);

                    RequisitionItem::create([
                        'requisition_id'         => $lockedRequisition->id,
                        'business_details_id'    => $business_details_id,
                        'part_item_id'           => $partItemId,
                        'product_description'    => $itemData['product_description'] ?? null,
                        'required_quantity'      => $finalQty,
                        'available_quantity'     => $availQty,
                        'shortage_quantity'      => $shortageQty,
                        'unit_id'                => $unitId,
                        'rate'                   => isset($itemData['rate']) && $itemData['rate'] !== '' ? (float) $itemData['rate'] : null,
                        'mtr_for_01_nos_trolley' => $mtr1,
                        'trolley_qty'            => $trolleyQty,
                        'length'                 => $length,
                        'is_qty_trolley_scaled'  => 1,
                        'is_active'              => 1,
                        'is_deleted'             => 0,
                        'is_sent_to_purchase'    => 0,      // draft — user must explicitly send via "Send Pending to Purchase"
                        'source'                 => 'manual_shortage', // explicitly added via +Add More by Store user
                    ]);
                }

                // NOTE: Do NOT notify purchase yet — rows are drafts until user clicks "Send Pending to Purchase".
            });

            $successMsg = 'New shortage items saved as draft. Click "Send Pending to Purchase" to formally send them to the Purchase department.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'msg' => $successMsg]);
            }
            return redirect()->back()->with(['status' => 'success', 'msg' => $successMsg]);

        } catch (\Exception $e) {
            Log::error('storeAdditionalShortageRequisition error: ' . $e->getMessage());
            $errMsg = 'Something went wrong. Please try again.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'msg' => $errMsg], 500);
            }
            return redirect()->back()->with(['status' => 'error', 'msg' => $errMsg]);
        }
    }

    /**
     * Update an existing draft requisition_item (is_sent_to_purchase=0).
     * Sent rows (is_sent_to_purchase=1) are immutable — returns 403.
     *
     * POST /update-draft-shortage-item
     * Body JSON: { requisition_item_id, required_quantity, unit_id, rate, mtr_for_01_nos_trolley? }
     */
    public function updateDraftShortageItem(Request $request)
    {
        try {
            $reqItemId   = $request->input('requisition_item_id');
            $requiredQty = $request->input('required_quantity');
            $unitId      = $request->input('unit_id');
            $rate        = $request->input('rate');
            $mtr1        = $request->input('mtr_for_01_nos_trolley');

            if (!$reqItemId || !is_numeric($requiredQty) || (float) $requiredQty <= 0) {
                return response()->json(['status' => 'error', 'msg' => 'Invalid input: requisition_item_id and required_quantity > 0 are required.'], 422);
            }

            $item = RequisitionItem::find($reqItemId);
            if (!$item) {
                return response()->json(['status' => 'error', 'msg' => 'Item not found.'], 404);
            }

            if ((int) $item->is_sent_to_purchase === 1) {
                return response()->json(['status' => 'error', 'msg' => 'Cannot update an item that has already been sent to Purchase.'], 403);
            }

            // T-2026-059: this endpoint only ever edits genuinely-manual/draft rows (BOM-not-yet-
            // sent rows are handled by storeAdditionalShortageRequisition's insert path instead —
            // see bomNotSentItems in bom-inventory-check.blade.php). $requiredQty/$mtr1 here are
            // therefore ALWAYS raw, per-1-trolley bases (mirroring bom_material_items.quantity /
            // mtr_for_01_nos_trolley semantics) and must be unit-aware + trolley-scaled server-side
            // before being persisted — never trust client-side math for this.
            $rawRequiredQty  = (float) $requiredQty;
            $mtr1Float       = ($mtr1 !== null && $mtr1 !== '') ? (float) $mtr1 : null;
            $effectiveUnitId = ($unitId !== null && $unitId !== '') ? (int) $unitId : $item->unit_id;
            $unitName        = $this->resolveUnitNameFromId($effectiveUnitId);
            $trolleyQty      = $this->resolveTrolleyQty($item->business_details_id);
            $requiredQty     = \App\Support\BomTotalCalculator::scaledQuantity($rawRequiredQty, $mtr1Float, $unitName, $trolleyQty);

            // Recompute available_quantity and shortage_quantity from live stock
            $availableQty = (float) ItemStock::where('part_item_id', $item->part_item_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->sum('quantity');

            $shortageQty = \App\Support\BomTotalCalculator::shortage($requiredQty, $availableQty);

            DB::transaction(function () use ($item, $requiredQty, $unitId, $rate, $mtr1Float, $availableQty, $shortageQty, $trolleyQty) {
                $item->required_quantity     = $requiredQty;
                $item->available_quantity    = $availableQty;
                $item->shortage_quantity     = $shortageQty;
                $item->trolley_qty           = $trolleyQty;
                $item->is_qty_trolley_scaled = 1;
                if ($unitId !== null && $unitId !== '') {
                    $item->unit_id = (int) $unitId;
                }
                if ($rate !== null && $rate !== '') {
                    $item->rate = (float) $rate;
                }
                if ($mtr1Float !== null) {
                    $item->mtr_for_01_nos_trolley = $mtr1Float;
                }
                $item->save();
            });

            return response()->json([
                'status'        => 'success',
                'msg'           => 'Draft item updated successfully.',
                'available_qty' => $availableQty,
                'shortage_qty'  => $shortageQty,
            ]);

        } catch (\Exception $e) {
            Log::error('updateDraftShortageItem error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'msg' => 'Something went wrong. Please try again.'], 500);
        }
    }

    /**
     * Issue available BOM materials directly to production from the BOM Inventory Check page.
     * Creates production_details records, deducts stock, updates BAP.
     */
    public function issueAvailableMaterials(Request $request)
    {
        try {
            $business_details_id = $request->input('business_details_id');
            $items               = array_merge(
                $request->input('items', []),
                $request->input('extra_items', [])   // manually added rows
            );

            if (empty($items)) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'Please select/add at least one material item before Issue to Production.']);
            }

            $errors = [];
            $validItems = collect($items)->filter(function ($item) {
                $partItemId = $item['part_item_id'] ?? null;
                $quantity   = (float) ($item['quantity'] ?? 0);
                $unitId     = $item['unit_id'] ?? null;

                return !empty($partItemId) && $quantity > 0 && !empty($unitId);
            })->values()->all();

            if (empty($validItems)) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'Please select/add at least one material item before Issue to Production.']);
            }

            try {
                DB::transaction(function () use ($business_details_id, $validItems, &$errors) {

                    $production = ProductionModel::where('business_details_id', $business_details_id)->first();
                    if (!$production) {
                        $errors[] = 'Production record not found for this product.';
                        throw new StoreIssueTransactionAborted();
                    }

                    $bap = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();

                    foreach ($validItems as $item) {
                        $partItemId  = $item['part_item_id']  ?? null;
                        $quantity    = (float) ($item['quantity']    ?? 0);
                        $unitId      = $item['unit_id']       ?? null;
                        $rate        = (float) ($item['rate']        ?? 0);
                        $description = $item['product_description'] ?? '';
                        // Set only on rows pre-filled from a pending Production request
                        // (production_details.id). BOM rows and JS-added manual rows have none.
                        $pdId        = $item['pd_id'] ?? null;

                        if (!$partItemId || $quantity <= 0) {
                            continue;
                        }

                        // Check live stock — skip row with error if insufficient.
                        // NOTE: No idempotency/dedup check by part_item_id here. The BOM legitimately
                        // lists the same part_item_id on multiple rows (different quantities / usages),
                        // and each submitted grid row must become its own production_details row.
                        $stock = ItemStock::where('part_item_id', $partItemId)
                            ->where('is_active', 1)
                            ->where('is_deleted', 0)
                            ->first();

                        $availableQty = $stock ? (float) $stock->quantity : 0;

                        if ($availableQty < $quantity) {
                            $errors[] = "Insufficient stock for: {$description} (available: {$availableQty}, required: {$quantity})";
                            continue;
                        }

                        // A row pre-filled from a pending Production request must CONSUME that
                        // request row rather than spawn a second one. Inserting a fresh row and
                        // leaving the original at quantity_minus_status='pending' left an orphan
                        // that showBomInventoryCheck()'s $pendingRows query re-rendered under
                        // "Additional Items to Issue" on every reload — the item looked un-issued
                        // even though it had been issued and stock had been deducted.
                        //
                        // The lookup is scoped to this business_details_id and to still-pending
                        // rows, so a tampered/stale pd_id can neither close another order's
                        // request nor re-close an already-issued one; it simply falls through to
                        // the INSERT path below.
                        $pendingRequest = null;
                        if (!empty($pdId)) {
                            $pendingRequest = ProductionDetails::where('id', $pdId)
                                ->where('business_details_id', $business_details_id)
                                ->where('quantity_minus_status', 'pending')
                                ->where('is_deleted', 0)
                                ->lockForUpdate()
                                ->first();
                        }

                        // T-2026-060 partial fulfilment: stock covered only part of what
                        // Production asked for. The consumed row records what was actually
                        // issued, and the un-issued balance is carried on a FRESH pending row so
                        // it stays visible to Production, keeps its production_shortage
                        // requisition draft alive, and can be issued later once purchase delivers.
                        // Captured before the row is mutated below.
                        $outstandingQty = 0.0;
                        if ($pendingRequest) {
                            $outstandingQty = max(0.0, (float) $pendingRequest->quantity - $quantity);
                        }

                        // Otherwise INSERT a fresh row — each submitted grid row is an independent
                        // issuance event. Never upsert or find-or-create by part_item_id because
                        // doing so would collapse duplicate part_item_id rows into one DB record.
                        $detail = $pendingRequest ?: new ProductionDetails();
                        $detail->business_id              = $production->business_id;
                        $detail->business_details_id      = $business_details_id;
                        $detail->design_id                = $bap->design_id ?? null;
                        $detail->production_id            = $production->id;
                        $detail->part_item_id             = $partItemId;
                        // The store operator may edit the pre-filled quantity before issuing;
                        // the issued figure is authoritative and the request is closed in full.
                        $detail->quantity                 = $quantity;
                        $detail->unit                     = $unitId;
                        $detail->basic_rate               = $rate;
                        $detail->items_used_total_amount  = $rate * $quantity;
                        $detail->quantity_minus_status    = 'done';
                        $detail->material_send_production = 1;
                        $detail->save();

                        // Carry the un-issued balance of a partially-fulfilled request forward.
                        if ($outstandingQty > 0) {
                            $balance = new ProductionDetails();
                            $balance->business_id              = $detail->business_id;
                            $balance->business_details_id      = $business_details_id;
                            $balance->design_id                = $detail->design_id;
                            $balance->production_id            = $detail->production_id;
                            $balance->part_item_id             = $partItemId;
                            $balance->quantity                 = $outstandingQty;
                            $balance->unit                     = $unitId;
                            $balance->basic_rate               = $rate;
                            $balance->items_used_total_amount  = $rate * $outstandingQty;
                            $balance->quantity_minus_status    = 'pending';
                            $balance->material_send_production = 0;
                            $balance->save();
                        }

                        // Deduct stock
                        $stock->quantity -= $quantity;
                        $stock->save();
                    }

                    if (!empty($errors)) {
                        throw new StoreIssueTransactionAborted();
                    }

                    // Mark production tracking as incomplete (in-progress)
                    $production->production_status_quantity_tracking = 'incomplete';
                    $production->save();

                    if ($bap) {
                        $bap->off_canvas_status = 17;
                        $bap->product_production_inprocess_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_INPROCESS_FOR_PRODUCTION');
                        $bap->save();

                        AdminView::where('business_details_id', $business_details_id)
                            ->update(['off_canvas_status' => 17, 'is_view' => '0']);
                        NotificationStatus::where('business_details_id', $business_details_id)
                            ->update(['off_canvas_status' => 17, 'material_received_from_store' => '0']);
                    }
                });
            } catch (StoreIssueTransactionAborted $e) {
                // Intentionally swallowed. `$errors` (captured by reference into the closure
                // above) already holds the human-readable message(s) accumulated before this
                // exception was thrown. This exception's only purpose is to force
                // DB::transaction() to genuinely roll back — a bare `return` inside a
                // transaction closure does NOT roll back in Laravel, only a thrown exception
                // does. It must be caught here (not left to propagate into the generic
                // `catch (\Exception $e)` below), otherwise the specific `$errors` message
                // would be overwritten by the generic "Something went wrong" message.
            }

            if (!empty($errors)) {
                return redirect()->back()->with(['status' => 'error', 'msg' => implode('; ', $errors)]);
            }

            return redirect(route('list-accepted-design-from-prod'))
                ->with(['status' => 'success', 'msg' => 'Available materials issued to Production successfully.']);

        } catch (\Exception $e) {
            Log::error('issueAvailableMaterials error: ' . $e->getMessage());
            return redirect()->back()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }

    /**
     * Flip all draft requisition_items (is_sent_to_purchase = 0) for the given business_details_id
     * to is_sent_to_purchase = 1, then notify the Purchase department.
     *
     * POST /send-pending-shortage-to-purchase
     */
    public function sendPendingShortageToPurchase(Request $request)
    {
        try {
            $business_details_id = $request->input('business_details_id');
            if (!$business_details_id) {
                return response()->json(['status' => 'error', 'msg' => 'Invalid request.'], 422);
            }

            $bap = \App\Models\BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
            if (!$bap || !$bap->requisition_id) {
                return response()->json(['status' => 'error', 'msg' => 'No requisition found for this project.'], 422);
            }

            $updated = 0;
            DB::transaction(function () use ($bap, $business_details_id, &$updated) {
                $updated = RequisitionItem::where('requisition_id', $bap->requisition_id)
                    ->where('is_sent_to_purchase', 0)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->update(['is_sent_to_purchase' => 1]);

                if ($updated > 0) {
                    // Touch the requisition's updated_at so downstream timestamps refresh
                    Requisition::where('id', $bap->requisition_id)->touch();

                    // Notify purchase department
                    NotificationStatus::where('business_details_id', $business_details_id)
                        ->update(['purchase_is_view' => 0]);
                }
            });

            if ($updated === 0) {
                return response()->json(['status' => 'info', 'msg' => 'No pending rows to send.', 'updated' => 0]);
            }

            return response()->json([
                'status'  => 'success',
                'msg'     => $updated . ' pending item(s) sent to Purchase department successfully.',
                'updated' => $updated,
            ]);

        } catch (\Exception $e) {
            Log::error('sendPendingShortageToPurchase error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'msg' => 'Something went wrong. Please try again.'], 500);
        }
    }

    /**
     * T-2026-059 — Requisition drift/resync.
     *
     * An already-sent requisition is otherwise a FROZEN SNAPSHOT: storeShortageRequisition()'s
     * delete/re-insert only ever runs on the very first send (guarded by the BAP store_status_id
     * check), and every later mutation path (storeAdditionalShortageRequisition,
     * ProductionRepository's production_shortage upsert) only ever APPENDS new draft rows —
     * nothing ever refreshes a row that was already marked is_sent_to_purchase=1, even though the
     * BOM can legitimately be re-uploaded (smart-merge sums quantities into bom_material_items)
     * and stock levels change continuously. This action lets Store deliberately reconcile an
     * already-sent requisition against the CURRENT BOM + stock snapshot, without ever losing
     * state Purchase has already acted on:
     *
     *   - For each part_item_id that ALREADY has a Purchase Order against this requisition
     *     (same part_item_id-based "already purchased" check PurchaseOrderController itself uses
     *     — this codebase has no requisition_item-level FK to a PO, only a part_item_id match),
     *     the existing sent row(s) are NEVER edited in place. If the CURRENT need has grown
     *     beyond what was already sent, a NEW supplemental row (source=resync_delta) is appended
     *     for exactly the delta so Purchase can see/act on the extra quantity. If the need has
     *     shrunk, nothing is touched (we never silently "unbuy" material Purchase may already
     *     have ordered) — this is intentionally a human-in-the-loop concern, not auto-resolved.
     *   - For a part_item_id with NO existing PO against this requisition, the matching sent row
     *     (by part_item_id + BOM length) is safely refreshed in place to the current computed
     *     values (nothing external depends on the old numbers yet).
     *   - A CURRENT BOM shortage with no requisition_items row at all yet (a brand-new shortage
     *     that appeared after the original send) is appended as a new sent row.
     *   - Nothing is ever DELETED.
     *
     * POST /resync-shortage-requisition
     * Body: { business_details_id }
     */
    public function resyncShortageRequisition(Request $request)
    {
        try {
            $business_details_id = $request->input('business_details_id');
            if (!$business_details_id) {
                return response()->json(['status' => 'error', 'msg' => 'Invalid request.'], 422);
            }

            $bap = \App\Models\BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
            $sentStatusId = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');
            if (!$bap || !$bap->requisition_id || (int) $bap->store_status_id !== (int) $sentStatusId) {
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'No sent requisition found for this project. Use "Send Shortage List to Purchase" for the first submission.',
                ], 422);
            }
            $requisitionId = $bap->requisition_id;

            // part_item_ids already referenced by an existing Purchase Order for THIS requisition —
            // mirrors PurchaseOrderController::createPurchaseOrder()'s own "already purchased" check
            // (part_item_id match, since there is no requisition_item-level FK to a PO in this schema).
            $purchasedPartIds = \App\Models\PurchaseOrderDetailsModel::join(
                    'purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id'
                )
                ->where('purchase_orders.requisition_id', $requisitionId)
                ->pluck('purchase_order_details.part_no_id')
                ->map(fn($id) => (string) $id)
                ->unique()
                ->toArray();

            $decoded_id = $business_details_id;
            $trolleyQty = $this->resolveTrolleyQty($decoded_id);

            // Recompute the CURRENT BOM shortage snapshot directly from bom_material_items + live
            // stock (same unit-aware + trolley-scaled formula as showBomInventoryCheck(), via
            // BomTotalCalculator — never duplicated here).
            $bomItems = BomMaterialItem::where('business_details_id', $decoded_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->with(['partItem', 'unitMaster'])
                ->get();

            $currentShortageRows = [];
            foreach ($bomItems as $item) {
                $availableStock = $item->part_item_id
                    ? (float) ItemStock::where('part_item_id', $item->part_item_id)
                        ->where('is_deleted', 0)->where('is_active', 1)->sum('quantity')
                    : 0.0;

                $unitName = (string) ($item->unit ?: (optional($item->unitMaster)->name ?? optional($item->unitMaster)->unit_name ?? ''));
                $scaledRequiredQty = \App\Support\BomTotalCalculator::scaledQuantity(
                    $item->quantity, $item->mtr_for_01_nos_trolley, $unitName, $trolleyQty
                );
                $shortageQty = \App\Support\BomTotalCalculator::shortage($scaledRequiredQty, $availableStock);

                if (!$item->part_item_id || $shortageQty <= 0) {
                    continue; // fully available (or no master part linked) — nothing to resync here
                }

                $currentShortageRows[] = [
                    'part_item_id'           => $item->part_item_id,
                    'length'                 => $item->length,
                    'product_description'    => $item->product_description,
                    'required_quantity'      => $scaledRequiredQty,
                    'available_quantity'     => $availableStock,
                    'shortage_quantity'      => $shortageQty,
                    'unit_id'                => $item->unit_id,
                    'rate'                   => $item->rate,
                    'mtr_for_01_nos_trolley' => $item->mtr_for_01_nos_trolley,
                ];
            }

            $insertedCount = 0;
            $updatedCount  = 0;
            $skippedLocked = 0;

            DB::transaction(function () use (
                $currentShortageRows, $requisitionId, $business_details_id, $purchasedPartIds,
                $trolleyQty, &$insertedCount, &$updatedCount, &$skippedLocked
            ) {
                // T-2026-059 iteration 3 (Gap 3 fix — concurrency/TOCTOU race): the read
                // ($existingGroup->sum(...)) below and the conditional INSERT of a resync_delta/
                // resync_new row further down form a classic check-then-act sequence. Two
                // concurrent calls to this method for the SAME requisition (double-click, or 2
                // browser tabs) could otherwise both read the pre-insert state and both insert a
                // duplicate delta/new row — reproduced concretely with 2 real interleaved DB
                // connections during the coverage audit (module_tester, T-2026-059 iteration-2
                // critique gate). Fix: take an exclusive row lock on the OWNING requisition row
                // FIRST, inside this same transaction, before reading or writing any
                // requisition_items row for it. A second, concurrent call for the SAME
                // requisition_id will block on this SELECT ... FOR UPDATE until the first call's
                // transaction commits (or rolls back) — serializing the two calls instead of
                // letting them race — while resyncs for DIFFERENT requisitions never contend with
                // each other (locked row is scoped to this one requisition_id only). This is a
                // pessimistic DB-row lock (not an application-level Cache::lock()) so it correctly
                // serializes concurrent requests across ANY number of horizontally-scaled app
                // instances, not just within one process.
                $lockedRequisition = Requisition::where('id', $requisitionId)->lockForUpdate()->first();
                if (!$lockedRequisition) {
                    return; // requisition vanished between the pre-check and here — nothing to resync
                }

                // Pre-load all active, non-deleted, part-item-linked rows for this requisition
                // once, then match in-memory by part_item_id + length (same composite-key
                // approach as showBomInventoryCheck(), for the same Defect 2(ii) reason: a part
                // can legitimately appear at multiple distinct BOM lengths). Also taken under
                // FOR UPDATE (belt-and-braces alongside the requisition-row lock above — the
                // requisition-row lock is what actually serializes concurrent callers, since a
                // brand-new-row insert has no existing row to lock).
                $existingRows = RequisitionItem::where('requisition_id', $requisitionId)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->whereNotNull('part_item_id')
                    ->lockForUpdate()
                    ->get()
                    ->groupBy(fn($ri) => $ri->part_item_id . '|' . self::normalizeLengthKey($ri->length));

                foreach ($currentShortageRows as $row) {
                    $compositeKey  = $row['part_item_id'] . '|' . self::normalizeLengthKey($row['length']);
                    $existingGroup = $existingRows->get($compositeKey) ?? collect();
                    $existing      = $existingGroup->first();
                    $isLocked      = in_array((string) $row['part_item_id'], $purchasedPartIds, true);

                    if ($existing && !$isLocked) {
                        // Safe to refresh in place — nothing external depends on the old numbers yet.
                        $existing->update([
                            'required_quantity'      => $row['required_quantity'],
                            'available_quantity'     => $row['available_quantity'],
                            'shortage_quantity'      => $row['shortage_quantity'],
                            'rate'                   => $row['rate'],
                            'mtr_for_01_nos_trolley' => $row['mtr_for_01_nos_trolley'],
                            'trolley_qty'            => $trolleyQty,
                            'length'                 => $row['length'],
                            'is_qty_trolley_scaled'  => 1,
                            'is_sent_to_purchase'    => 1,
                        ]);
                        $updatedCount++;
                    } elseif ($existing && $isLocked) {
                        // Already acted on by Purchase — never overwrite. If the current need has
                        // grown beyond what was already sent, append a supplemental delta row.
                        // IMPORTANT (idempotency): compare against the SUM of every existing row for
                        // this exact part_item_id+length — not just the single original row — so a
                        // delta appended by a PREVIOUS resync is counted as already-covered need.
                        // Comparing only against $existing->required_quantity here would re-detect the
                        // very same growth on every subsequent resync call and insert a fresh duplicate
                        // delta row each time.
                        $alreadyCovered = (float) $existingGroup->sum('required_quantity');
                        $delta = $row['required_quantity'] - $alreadyCovered;
                        if ($delta > 0.0005) {
                            RequisitionItem::create([
                                'requisition_id'         => $requisitionId,
                                'business_details_id'    => $business_details_id,
                                'part_item_id'           => $row['part_item_id'],
                                'product_description'    => $row['product_description'],
                                'required_quantity'      => $delta,
                                'available_quantity'     => $row['available_quantity'],
                                'shortage_quantity'      => min($delta, $row['shortage_quantity']),
                                'unit_id'                => $row['unit_id'],
                                'rate'                   => $row['rate'],
                                'mtr_for_01_nos_trolley' => $row['mtr_for_01_nos_trolley'],
                                'trolley_qty'            => $trolleyQty,
                                'length'                 => $row['length'],
                                'is_qty_trolley_scaled'  => 1,
                                'is_active'              => 1,
                                'is_deleted'             => 0,
                                'is_sent_to_purchase'    => 1,
                                'source'                 => 'resync_delta',
                            ]);
                            $insertedCount++;
                        } else {
                            $skippedLocked++;
                        }
                    } else {
                        // Brand-new shortage that appeared after the original send.
                        RequisitionItem::create([
                            'requisition_id'         => $requisitionId,
                            'business_details_id'    => $business_details_id,
                            'part_item_id'           => $row['part_item_id'],
                            'product_description'    => $row['product_description'],
                            'required_quantity'      => $row['required_quantity'],
                            'available_quantity'     => $row['available_quantity'],
                            'shortage_quantity'      => $row['shortage_quantity'],
                            'unit_id'                => $row['unit_id'],
                            'rate'                   => $row['rate'],
                            'mtr_for_01_nos_trolley' => $row['mtr_for_01_nos_trolley'],
                            'trolley_qty'            => $trolleyQty,
                            'length'                 => $row['length'],
                            'is_qty_trolley_scaled'  => 1,
                            'is_active'              => 1,
                            'is_deleted'             => 0,
                            'is_sent_to_purchase'    => 1,
                            'source'                 => 'resync_new',
                        ]);
                        $insertedCount++;
                    }
                }

                if ($insertedCount > 0 || $updatedCount > 0) {
                    Requisition::where('id', $requisitionId)->touch();
                    NotificationStatus::where('business_details_id', $business_details_id)
                        ->update(['purchase_is_view' => 0]);
                }
            });

            $msg = "Resync complete: {$updatedCount} row(s) refreshed, {$insertedCount} new row(s) added"
                . ($skippedLocked ? ", {$skippedLocked} already-purchased row(s) left untouched (no growth in need)" : '')
                . '.';

            return response()->json([
                'status'         => 'success',
                'msg'            => $msg,
                'updated'        => $updatedCount,
                'inserted'       => $insertedCount,
                'skipped_locked' => $skippedLocked,
            ]);

        } catch (\Exception $e) {
            Log::error('resyncShortageRequisition error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'msg' => 'Something went wrong. Please try again.'], 500);
        }
    }

    /**
     * Delete a draft requisition_item (is_sent_to_purchase = 0 only).
     * Sent rows (is_sent_to_purchase = 1) are immutable and cannot be deleted.
     *
     * POST /delete-draft-shortage-item
     */
    public function deleteDraftShortageItem(Request $request)
    {
        try {
            $requisition_item_id = $request->input('requisition_item_id');
            if (!$requisition_item_id) {
                return response()->json(['status' => 'error', 'msg' => 'Invalid request.'], 422);
            }

            $item = RequisitionItem::find($requisition_item_id);
            if (!$item) {
                return response()->json(['status' => 'error', 'msg' => 'Item not found.'], 404);
            }

            if ((int) $item->is_sent_to_purchase === 1) {
                return response()->json(['status' => 'error', 'msg' => 'Cannot delete an item that has already been sent to Purchase.'], 403);
            }

            // Soft-delete: consistent with the is_deleted pattern used throughout the codebase
            $item->is_deleted = 1;
            $item->is_active  = 0;
            $item->save();

            return response()->json(['status' => 'success', 'msg' => 'Draft item deleted successfully.']);

        } catch (\Exception $e) {
            Log::error('deleteDraftShortageItem error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'msg' => 'Something went wrong. Please try again.'], 500);
        }
    }
}
