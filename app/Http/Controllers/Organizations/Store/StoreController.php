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
            return redirect('storedept/add-requistion')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
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
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
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
                'msg' => $e->getMessage()
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
    //         return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
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
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
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
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
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
                'msg'    => $e->getMessage()
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

            // One-time cleanup: soft-delete stale pending rows where a 'done' row already exists
            $donePartIds = ProductionDetails::where('business_details_id', $decoded_id)
                ->where('material_send_production', 1)
                ->where('quantity_minus_status', 'done')
                ->where('is_deleted', 0)
                ->pluck('part_item_id')
                ->toArray();

            if (!empty($donePartIds)) {
                ProductionDetails::where('business_details_id', $decoded_id)
                    ->where('quantity_minus_status', 'pending')
                    ->where('is_deleted', 0)
                    ->whereIn('part_item_id', $donePartIds)
                    ->update(['is_deleted' => 1]);
            }

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
                $alreadyIssued[]           = $norm;
            }

            // Collect all part_item_ids already placed from BOM to avoid duplicates
            $bomPlacedIds = array_unique(array_merge(
                array_map(fn($i) => $i->part_item_id, $available),
                array_map(fn($i) => $i->part_item_id, $shortage),
                array_map(fn($i) => $i->part_item_id, $alreadyIssued),
            ));

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
                // Skip if already issued or already covered by BOM
                if (in_array($pitem->part_item_id, $alreadyIssuedIds) ||
                    in_array($pitem->part_item_id, $bomPlacedIds)) {
                    continue;
                }

                $stockQty = (float) ItemStock::where('part_item_id', $pitem->part_item_id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->sum('quantity');

                $reqQty      = (float) $pitem->quantity;
                $shortageQty = max(0, $reqQty - $stockQty);

                // Normalise to same shape as BOM items so blade template works unchanged
                $norm = new \stdClass();
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

            // Fetch part_item_ids that were included in the sent requisition (for per-row badge in shortage table)
            $sentPartIds = [];
            if ($requisitionSent && $bap && $bap->requisition_id) {
                $sentPartIds = RequisitionItem::where('requisition_id', $bap->requisition_id)
                    ->whereNotNull('part_item_id')
                    ->pluck('part_item_id')
                    ->map(fn($id) => (string) $id)
                    ->toArray();
            }

            return view('organizations.store.list.bom-inventory-check', compact(
                'available',
                'shortage',
                'alreadyIssued',
                'availableFromProduction',
                'productDetails',
                'estimationAmount',
                'business_details_id',
                'partItems',
                'unitMasters',
                'requisitionSent',
                'sentPartIds'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
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
            $items               = $request->input('items', []);

            if (empty($items)) {
                return redirect()->back()->with(['status' => 'error', 'msg' => 'No shortage items to submit.']);
            }

            DB::transaction(function () use ($business_details_id, $business_id, $design_id, $items) {

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

                // 2. Create requisition_items for each shortage item
                // Remove old items for idempotency (re-submit scenario)
                RequisitionItem::where('requisition_id', $requisition->id)->delete();

                foreach ($items as $itemData) {
                    RequisitionItem::create([
                        'requisition_id'      => $requisition->id,
                        'business_details_id' => $business_details_id,
                        'part_item_id'        => ($itemData['part_item_id'] ?? '') !== '' ? $itemData['part_item_id'] : null,
                        'product_description' => $itemData['product_description'] ?? null,
                        'required_quantity'   => (float) ($itemData['required_quantity'] ?? 0),
                        'available_quantity'  => (float) ($itemData['available_quantity'] ?? 0),
                        'shortage_quantity'   => (float) ($itemData['shortage_quantity'] ?? 0),
                        'unit_id'             => ($itemData['unit_id'] ?? '') !== '' ? $itemData['unit_id'] : null,
                        'rate'                => isset($itemData['rate']) && $itemData['rate'] !== '' ? (float) $itemData['rate'] : null,
                        'is_active'           => 1,
                        'is_deleted'          => 0,
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
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
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
            $items               = $request->input('items', []);

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
                    $partItemId = ($itemData['part_item_id'] ?? '') !== '' ? $itemData['part_item_id'] : null;

                    // Skip if this part_item is already in the requisition
                    if ($partItemId && RequisitionItem::where('requisition_id', $requisition->id)
                            ->where('part_item_id', $partItemId)->exists()) {
                        continue;
                    }

                    RequisitionItem::create([
                        'requisition_id'      => $requisition->id,
                        'business_details_id' => $business_details_id,
                        'part_item_id'        => $partItemId,
                        'product_description' => $itemData['product_description'] ?? null,
                        'required_quantity'   => (float) ($itemData['required_quantity'] ?? 0),
                        'available_quantity'  => (float) ($itemData['available_quantity'] ?? 0),
                        'shortage_quantity'   => (float) ($itemData['shortage_quantity'] ?? 0),
                        'unit_id'             => ($itemData['unit_id'] ?? '') !== '' ? $itemData['unit_id'] : null,
                        'rate'                => isset($itemData['rate']) && $itemData['rate'] !== '' ? (float) $itemData['rate'] : null,
                        'is_active'           => 1,
                        'is_deleted'          => 0,
                    ]);
                }

                // Notify purchase department of the updated requisition
                NotificationStatus::where('business_details_id', $business_details_id)
                    ->update(['purchase_is_view' => 0]);
            });

            return redirect()->back()
                ->with(['status' => 'success', 'msg' => 'Additional shortage items appended to the existing requisition and sent to Purchase department.']);

        } catch (\Exception $e) {
            Log::error('storeAdditionalShortageRequisition error: ' . $e->getMessage());
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
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
                return redirect()->back()->with(['status' => 'error', 'msg' => 'No items to issue.']);
            }

            $errors = [];

            DB::transaction(function () use ($business_details_id, $items, &$errors) {

                $production = ProductionModel::where('business_details_id', $business_details_id)->first();
                if (!$production) {
                    $errors[] = 'Production record not found for this product.';
                    return;
                }

                $bap = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();

                foreach ($items as $item) {
                    $partItemId  = $item['part_item_id']  ?? null;
                    $quantity    = (float) ($item['quantity']    ?? 0);
                    $unitId      = $item['unit_id']       ?? null;
                    $rate        = (float) ($item['rate']        ?? 0);
                    $description = $item['product_description'] ?? '';

                    if (!$partItemId || $quantity <= 0) {
                        continue;
                    }

                    // Idempotency: skip if already issued for this part+business_details (prevents double-submit)
                    $alreadyIssued = ProductionDetails::where('business_details_id', $business_details_id)
                        ->where('part_item_id', $partItemId)
                        ->where('material_send_production', 1)
                        ->where('quantity_minus_status', 'done')
                        ->where('is_deleted', 0)
                        ->exists();

                    if ($alreadyIssued) {
                        continue; // already sent — skip silently
                    }

                    // Check live stock
                    $stock = ItemStock::where('part_item_id', $partItemId)
                        ->where('is_active', 1)
                        ->where('is_deleted', 0)
                        ->first();

                    $availableQty = $stock ? (float) $stock->quantity : 0;

                    if ($availableQty < $quantity) {
                        $errors[] = "Insufficient stock for: {$description} (available: {$availableQty}, required: {$quantity})";
                        continue;
                    }

                    // Find pending row (any pending — including stale send=1 rows) or create new
                    $detail = ProductionDetails::where('business_details_id', $business_details_id)
                        ->where('part_item_id', $partItemId)
                        ->where('quantity_minus_status', 'pending')
                        ->where('is_deleted', 0)
                        ->first();

                    if (!$detail) {
                        $detail = new ProductionDetails();
                        $detail->business_id         = $production->business_id;
                        $detail->business_details_id = $business_details_id;
                        $detail->design_id           = $bap->design_id ?? null;
                        $detail->production_id       = $production->id;
                    }

                    $detail->part_item_id             = $partItemId;
                    $detail->quantity                 = $quantity;
                    $detail->unit                     = $unitId;
                    $detail->basic_rate               = $rate;
                    $detail->items_used_total_amount  = $rate * $quantity;
                    $detail->quantity_minus_status    = 'done';
                    $detail->material_send_production = 1;
                    $detail->save();

                    // Soft-delete any remaining stale pending rows for same item (duplicates from old buggy saves)
                    ProductionDetails::where('business_details_id', $business_details_id)
                        ->where('part_item_id', $partItemId)
                        ->where('quantity_minus_status', 'pending')
                        ->where('is_deleted', 0)
                        ->where('id', '!=', $detail->id)
                        ->update(['is_deleted' => 1]);

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
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
}
