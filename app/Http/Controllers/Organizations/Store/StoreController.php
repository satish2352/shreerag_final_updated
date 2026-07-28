<?php

namespace App\Http\Controllers\Organizations\Store;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\StoreServices;
use Illuminate\Support\Facades\Validator;
use Exception;
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

                $requiredQty  = (float) $item->quantity;
                $shortageQty  = max(0, $requiredQty - $availableStock);

                $item->available_stock   = $availableStock;
                $item->required_quantity = $requiredQty;
                $item->shortage_quantity = $shortageQty;

                if ($item->part_item_id && in_array($item->part_item_id, $alreadyIssuedIds)) {
                    $item->issued_at = $issuedDateMap->get($item->part_item_id);
                    $alreadyIssued[] = $item;
                } elseif ($availableStock >= $requiredQty) {
                    $available[] = $item;
                } else {
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

                if ($stockQty >= $reqQty) {
                    $availableFromProduction[] = $norm;  // pre-filled in "Additional Items to Issue" rows
                } else {
                    $shortage[] = $norm;                 // needs purchase requisition
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

            // Build a row-level map: requisition_item_id => is_sent_to_purchase (and part_item_id => row record)
            // for all requisition_items belonging to this business_details_id's requisition.
            // This replaces the old flat $sentPartIds array so each shortage row carries its own sent/draft flag.
            $sentPartIds  = [];  // kept for backward compat with BOM-derived shortage badge logic below
            $reqItemsMap  = [];  // part_item_id (string) => RequisitionItem Eloquent model
            $hasDraftRows = false; // true when any requisition_items row has is_sent_to_purchase = 0
            $requisitionId = $bap ? $bap->requisition_id : null;

            if ($requisitionId) {
                $allReqItems = RequisitionItem::where('requisition_id', $requisitionId)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->whereNotNull('part_item_id')
                    ->with(['partItem', 'unitMaster'])
                    ->get();

                foreach ($allReqItems as $ri) {
                    $key = (string) $ri->part_item_id;
                    // Keep the first occurrence; if multiple rows exist for same part, prefer sent (=1) over draft (=0)
                    if (!isset($reqItemsMap[$key]) || (int) $ri->is_sent_to_purchase === 1) {
                        $reqItemsMap[$key] = $ri;
                    }
                    if ((int) $ri->is_sent_to_purchase === 1) {
                        $sentPartIds[] = $key; // used by BOM-derived shortage rows for badge
                    }
                    if ((int) $ri->is_sent_to_purchase === 0) {
                        $hasDraftRows = true;
                    }
                }
                $sentPartIds = array_unique($sentPartIds);
            }

            // Mark BOM-derived shortage rows with their per-row is_sent_to_purchase value
            foreach ($shortage as $sItem) {
                $key = (string) ($sItem->part_item_id ?? '');
                if (isset($reqItemsMap[$key])) {
                    $ri = $reqItemsMap[$key];
                    $sItem->is_sent_to_purchase = (int) $ri->is_sent_to_purchase;
                    $sItem->requisition_item_id  = $ri->id;
                } else {
                    $sItem->is_sent_to_purchase = null; // not in requisition at all
                    $sItem->requisition_item_id  = null;
                }
            }

            // Surface manual-shortage rows (added via +Add More) in the shortage display list.
            // These rows exist in requisition_items but are NOT derived from BOM or production_details.
            // Now they carry is_sent_to_purchase per row: 0 = draft (orange badge), 1 = sent (green badge).
            if ($requisitionId) {
                // Collect part_item_ids already covered by BOM/production-derived shortage + available + issued
                // Use collect()->pluck() because $shortage/$available/$alreadyIssued may contain Eloquent
                // models (from BOM loop) or stdClass objects (from production_details loop). Both support
                // property access, and collect() handles both for pluck().
                $coveredPartIds = collect(array_merge($shortage, $available, $alreadyIssued))
                    ->pluck('part_item_id')
                    ->filter()
                    ->map(fn($id) => (string) $id)
                    ->toArray();

                foreach ($reqItemsMap as $key => $mri) {
                    // Only add rows whose part_item_id is NOT already in BOM/production-derived lists
                    if (in_array($key, $coveredPartIds)) {
                        continue;
                    }
                    $norm = new \stdClass();
                    $norm->requisition_item_id = $mri->id;
                    $norm->part_item_id        = $mri->part_item_id;
                    $norm->product_description = $mri->product_description ?? (optional($mri->partItem)->description ?? '');
                    $norm->required_quantity   = (float) $mri->required_quantity;
                    $norm->available_stock     = (float) $mri->available_quantity;
                    $norm->shortage_quantity   = (float) $mri->shortage_quantity;
                    $norm->unit_id             = $mri->unit_id;
                    $norm->rate                = $mri->rate;
                    $norm->partItem            = $mri->partItem;
                    $norm->unitMaster          = $mri->unitMaster;
                    $norm->created_at          = $mri->created_at;
                    $norm->source              = 'manual_shortage';
                    $norm->is_sent_to_purchase = (int) $mri->is_sent_to_purchase;
                    // requisition_items DOES carry mtr_for_01_nos_trolley — the draft-row grid
                    // renders it as an editable input plus a derived "for N trolleys" cell, so it
                    // must always be present on the normalised row (null when never captured).
                    $norm->mtr_for_01_nos_trolley = $mri->mtr_for_01_nos_trolley ?? null;
                    // T-2026-058: requisition_items has no length/total_in_mm columns — no BOM
                    // context for manually-added shortage rows, blade renders em-dash for these.
                    $norm->length              = null;
                    $norm->total_in_mm         = null;
                    $shortage[]                = $norm;
                    // Track this part_item_id as covered so duplicates in reqItemsMap are not added twice
                    $coveredPartIds[] = $key;
                }
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

            DB::transaction(function () use ($business_details_id, $business_id, $design_id, $items, $manual_shortage) {

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
                        'required_quantity'      => (float) ($itemData['required_quantity'] ?? 0),
                        'available_quantity'     => (float) ($itemData['available_quantity'] ?? 0),
                        'shortage_quantity'      => (float) ($itemData['shortage_quantity'] ?? 0),
                        'unit_id'                => ($itemData['unit_id'] ?? '') !== '' ? $itemData['unit_id'] : null,
                        'rate'                   => isset($itemData['rate']) && $itemData['rate'] !== '' ? (float) $itemData['rate'] : null,
                        'mtr_for_01_nos_trolley' => isset($itemData['mtr_for_01_nos_trolley']) && $itemData['mtr_for_01_nos_trolley'] !== '' ? (float) $itemData['mtr_for_01_nos_trolley'] : null,
                        'is_active'              => 1,
                        'is_deleted'             => 0,
                        'is_sent_to_purchase'    => 1, // BOM-derived shortage: part of the official requisition send
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
                    $msShortageQty = max(0, $msQty - $msAvailQty);
                    $msRate        = isset($ms['rate']) && $ms['rate'] !== '' ? (float) $ms['rate'] : null;
                    $msDesc        = $ms['product_description'] ?? null;

                    // Dedup: skip if this part_item is already in the requisition
                    if (RequisitionItem::where('requisition_id', $requisition->id)
                            ->where('part_item_id', $msPartItemId)->exists()) {
                        continue;
                    }

                    $msMtr1 = isset($ms['mtr_for_01_nos_trolley']) && $ms['mtr_for_01_nos_trolley'] !== '' ? (float) $ms['mtr_for_01_nos_trolley'] : null;

                    RequisitionItem::create([
                        'requisition_id'         => $requisition->id,
                        'business_details_id'    => $business_details_id,
                        'part_item_id'           => $msPartItemId,
                        'product_description'    => $msDesc,
                        'required_quantity'      => $msQty,
                        'available_quantity'     => $msAvailQty,
                        'shortage_quantity'      => $msShortageQty,
                        'unit_id'                => $msUnitId,
                        'rate'                   => $msRate,
                        'mtr_for_01_nos_trolley' => $msMtr1,
                        'is_active'              => 1,
                        'is_deleted'             => 0,
                        'is_sent_to_purchase'    => 1, // submitted together with initial requisition → already sent
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

            DB::transaction(function () use ($requisition, $business_details_id, $items) {
                foreach ($items as $itemData) {
                    $partItemId = ($itemData['part_item_id'] ?? '') !== '' ? (int) $itemData['part_item_id'] : null;
                    if (!$partItemId) continue; // skip rows without a part selected
                    $qty = (float) ($itemData['required_quantity'] ?? $itemData['quantity'] ?? 0);
                    if ($qty <= 0) continue;    // skip rows with no quantity
                    $unitId = ($itemData['unit_id'] ?? '') !== '' ? (int) $itemData['unit_id'] : null;

                    // Skip if this part_item is already in the requisition as a sent row.
                    // Allow insert if the existing row is a draft (user may be re-adding a draft with
                    // different qty — this is unusual, so we skip duplicates entirely for simplicity).
                    if (RequisitionItem::where('requisition_id', $requisition->id)
                            ->where('part_item_id', $partItemId)->exists()) {
                        continue;
                    }

                    $availQty    = (float) ($itemData['available_quantity'] ?? 0);
                    $shortageQty = (float) ($itemData['shortage_quantity'] ?? max(0, $qty - $availQty));

                    RequisitionItem::create([
                        'requisition_id'         => $requisition->id,
                        'business_details_id'    => $business_details_id,
                        'part_item_id'           => $partItemId,
                        'product_description'    => $itemData['product_description'] ?? null,
                        'required_quantity'      => $qty,
                        'available_quantity'     => $availQty,
                        'shortage_quantity'      => $shortageQty,
                        'unit_id'                => $unitId,
                        'rate'                   => isset($itemData['rate']) && $itemData['rate'] !== '' ? (float) $itemData['rate'] : null,
                        'mtr_for_01_nos_trolley' => isset($itemData['mtr_for_01_nos_trolley']) && $itemData['mtr_for_01_nos_trolley'] !== '' ? (float) $itemData['mtr_for_01_nos_trolley'] : null,
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

            $requiredQty = (float) $requiredQty;

            // Recompute available_quantity and shortage_quantity from live stock
            $availableQty = (float) ItemStock::where('part_item_id', $item->part_item_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->sum('quantity');

            $shortageQty = max(0, $requiredQty - $availableQty);

            DB::transaction(function () use ($item, $requiredQty, $unitId, $rate, $mtr1, $availableQty, $shortageQty) {
                $item->required_quantity  = $requiredQty;
                $item->available_quantity = $availableQty;
                $item->shortage_quantity  = $shortageQty;
                if ($unitId !== null && $unitId !== '') {
                    $item->unit_id = (int) $unitId;
                }
                if ($rate !== null && $rate !== '') {
                    $item->rate = (float) $rate;
                }
                if ($mtr1 !== null && $mtr1 !== '') {
                    $item->mtr_for_01_nos_trolley = (float) $mtr1;
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

            DB::transaction(function () use ($business_details_id, $validItems, &$errors) {

                $production = ProductionModel::where('business_details_id', $business_details_id)->first();
                if (!$production) {
                    $errors[] = 'Production record not found for this product.';
                    return;
                }

                $bap = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();

                foreach ($validItems as $item) {
                    $partItemId  = $item['part_item_id']  ?? null;
                    $quantity    = (float) ($item['quantity']    ?? 0);
                    $unitId      = $item['unit_id']       ?? null;
                    $rate        = (float) ($item['rate']        ?? 0);
                    $description = $item['product_description'] ?? '';

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

                    // Always INSERT a fresh row — each submitted grid row is an independent
                    // issuance event. Never upsert or find-or-create by part_item_id because
                    // doing so would collapse duplicate part_item_id rows into one DB record.
                    $detail = new ProductionDetails();
                    $detail->business_id              = $production->business_id;
                    $detail->business_details_id      = $business_details_id;
                    $detail->design_id                = $bap->design_id ?? null;
                    $detail->production_id            = $production->id;
                    $detail->part_item_id             = $partItemId;
                    $detail->quantity                 = $quantity;
                    $detail->unit                     = $unitId;
                    $detail->basic_rate               = $rate;
                    $detail->items_used_total_amount  = $rate * $quantity;
                    $detail->quantity_minus_status    = 'done';
                    $detail->material_send_production = 1;
                    $detail->save();

                    // Deduct stock
                    $stock->quantity -= $quantity;
                    $stock->save();
                }

                if (!empty($errors)) {
                    return; // transaction rolls back
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
