<?php

namespace App\Http\Repository\Organizations\Productions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\{
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    Logistics,
    Dispatch,
    AdminView,
    ProductionDetails,
    NotificationStatus,
    CustomerProductQuantityTracking,
    PartItem,
    Requisition,
    RequisitionItem,
    ItemStock,
};

class ProductionRepository
{

    public function acceptdesign($id)
    {
        try {
            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();

            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION');
                $business_application->off_canvas_status = 15;
                $business_application->save();
                $update_data_admin['off_canvas_status'] = 15;
                $update_data_admin['is_view'] = '0';
                $update_data_business['off_canvas_status'] = 15;
                AdminView::where('business_id', $business_application->business_id)
                    ->where('business_details_id', $id)
                    ->update($update_data_admin);

                NotificationStatus::where('business_id', $business_application->business_id)
                    ->where('business_details_id', $id)
                    ->update($update_data_business);
            } else {
                return [
                    'msg' => 'Business application process not found',
                    'status' => 'error',
                ];
            }

            $business = ProductionModel::where('business_details_id', $id)->first();

            if ($business) {
                $business->is_approved_production = '1';
                $business->save();
            } else {
                return [
                    'msg' => 'Production model not found',
                    'status' => 'error',
                ];
            }
            return [
                'msg' => 'Design accepted and updated successfully',
                'status' => 'success',
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'An error occurred: ' . $e->getMessage(),
                'status' => 'error',
            ];
        }
    }
    public function rejectdesign($request)
    { //checked
        try {
            //  Step 2: Decode business ID from request
            $idtoedit = base64_decode($request->business_id);
            //  Step 3: Get production data for this business_details_id
            $production_data = ProductionModel::where('business_details_id', $idtoedit)->first();
            //  Step 4: Check if business_details_id exists inside production_data
            $business_details_id = $production_data->business_details_id;
            //  Step 5: Get design revision record
            $designRevisionForProdID = DesignRevisionForProd::where('business_details_id', $business_details_id)
                ->orderBy('id', 'desc')
                ->first();
            //  Step 6: Insert or update design revision
            if ($designRevisionForProdID) {
                $designRevisionForProdID->business_id = $production_data->business_id;
                $designRevisionForProdID->business_details_id = $business_details_id;
                $designRevisionForProdID->design_id = $production_data->design_id;
                $designRevisionForProdID->production_id = $production_data->id;
                $designRevisionForProdID->reject_reason_prod = $request->reject_reason_prod;
                $designRevisionForProdID->remark_by_design = '';
                $designRevisionForProdID->save();
            } else {
                $designRevisionForProdIDInsert = new DesignRevisionForProd();
                $designRevisionForProdIDInsert->business_id = $production_data->business_id;
                $designRevisionForProdIDInsert->business_details_id = $business_details_id;
                $designRevisionForProdIDInsert->design_id = $production_data->design_id;
                $designRevisionForProdIDInsert->production_id = $production_data->id;
                $designRevisionForProdIDInsert->reject_reason_prod = $request->reject_reason_prod;
                $designRevisionForProdIDInsert->remark_by_design = '';
                $designRevisionForProdIDInsert->save();
            }
            //  Step 7: Get Business Application Process record
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();

            if (!$business_application) {
                // dd('No business_application found for ID: ' . $business_details_id);
            }
            //  Step 8: Update statuses if record is found
            $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
            $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
            $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED');
            $business_application->off_canvas_status = 13;
            $business_application->save();
            //  Step 9: Update admin and notification views
            $update_data_admin['off_canvas_status'] = 13;
            $update_data_admin['is_view'] = '0';
            $update_data_business['off_canvas_status'] = 13;

            AdminView::where('business_details_id', $business_details_id)->update($update_data_admin);
            NotificationStatus::where('business_details_id', $business_details_id)->update($update_data_business);

            //  Optional: return success message
            return response()->json(['status' => 'success', 'message' => 'Design rejected and updates completed.']);
        } catch (\Exception $e) {
            //  Step 10: Catch and debug any exception
            // dd('Caught Exception: ' . $e->getMessage());
        }
    }

    public function acceptProductionCompleted($id, $completed_quantity)
    {
        try {
            // Fetch the business application process record for the given business ID
            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();

            if ($business_application) {
                // Update business application process status
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN');
                $business_application->off_canvas_status = 18;
                $business_application->save();

                // Update admin view and notification status
                $update_data_admin = ['off_canvas_status' => 18, 'is_view' => '0'];
                $update_data_business = ['off_canvas_status' => 18, 'production_completed' => '0'];

                AdminView::where('business_details_id', $business_application->business_details_id)->update($update_data_admin);
                NotificationStatus::where('business_details_id', $business_application->business_details_id)->update($update_data_business);

                // Track the completed quantity
                $quantity_tracking = new CustomerProductQuantityTracking();
                $quantity_tracking->business_id = $business_application->business_id;
                $quantity_tracking->business_details_id = $id;
                $quantity_tracking->production_id = $business_application->id;
                $quantity_tracking->business_application_processes_id = $business_application->id;
                $quantity_tracking->completed_quantity = $completed_quantity;  // Save the completed quantity
                $quantity_tracking->quantity_tracking_status = config('constants.PRODUCTION_DEPARTMENT.INPROCESS_COMPLETED_QUANLTITY_SEND_TO_LOGISTICS');
                $quantity_tracking->save();

                // Debug: Check if quantity_tracking was saved successfully
                if (!$quantity_tracking->id) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to save quantity tracking data'], 500);
                }

                // Create a new logistics record with tracking ID
                $logistics = Logistics::create([
                    'business_details_id' => $id,
                    'business_id' => $business_application->business_id,
                    'business_application_processes_id' => $business_application->id,
                    'quantity_tracking_id' => $quantity_tracking->id,  // Correctly pass the quantity_tracking_id from quantity_tracking
                    'is_approve' => '0',
                    'is_active' => '1',
                    'is_deleted' => '0',
                ]);


                // Debug: Check if logistics data was created successfully
                if (!$logistics) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to save logistics data'], 500);
                }

                // Create a new dispatch record with the logistics ID
                $dispatch = Dispatch::create([
                    'logistics_id' => $logistics->id,
                    'business_details_id' => $id,
                    'business_id' => $business_application->business_id,
                    'business_application_processes_id' => $business_application->id,
                    'quantity_tracking_id' => $logistics->quantity_tracking_id,  // Correctly pass quantity_tracking_id from logistics
                    'is_approve' => '0',
                    'is_active' => '1',
                    'is_deleted' => '0',
                ]);
                if (!$dispatch) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to save dispatch data'], 500);
                }

                return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function editProductQuantityTracking($id)
    {
        try {
            $array_to_be_check = [
                config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')
            ];

            // Fetch all related data
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->leftJoin('production_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
                })
                ->leftJoin('tbl_customer_product_quantity_tracking', 'business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id')
                ->where('businesses_details.id', $id)
                // ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
                ->where('businesses_details.is_active', true)
                ->where('businesses_details.is_deleted', 0)

                ->select(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'production_details.part_item_id',
                    // DB::raw('(businesses_details.quantity - tbl_customer_product_quantity_tracking.completed_quantity) AS remaining_quantity'),
                    // 'production_details.quantity',
                    'production_details.unit',
                    'production_details.business_details_id',
                    'production_details.material_send_production',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date',
                    DB::raw('(SELECT SUM(t2.completed_quantity)
                    FROM tbl_customer_product_quantity_tracking AS t2
                    WHERE t2.business_details_id = businesses_details.id) 
                    AS completed_quantity'),
                    DB::raw('(businesses_details.quantity - 
                    (SELECT SUM(t2.completed_quantity)
                    FROM tbl_customer_product_quantity_tracking AS t2
                    WHERE t2.business_details_id = businesses_details.id)) 
                    AS remaining_quantity'),
                    'production.updated_at'
                )
                ->get();

            // Extract product details and data for table
            $productDetails = $dataOutputByid->first(); // Assuming the first entry contains the product details
            $dataGroupedById = $dataOutputByid->groupBy('business_details_id');

            return [
                'productDetails' => $productDetails,
                'dataGroupedById' => $dataGroupedById
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
        }
    }


    public function getAllListMaterialRecievedToProductionBusinessWise($id)
    {
        try {
            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('tbl_customer_product_quantity_tracking', 'business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id')
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->where('businesses_details.id', $id)
                ->where('businesses_details.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->select(
                    'business_application_processes.id',
                    'businesses.id as business_id',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses_details.id as business_details_id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'businesses.remarks',
                    DB::raw('(SELECT SUM(t2.completed_quantity)
                          FROM tbl_customer_product_quantity_tracking AS t2
                          WHERE t2.business_details_id = businesses_details.id) 
                          AS completed_quantity'),
                    DB::raw('(businesses_details.quantity - 
                          (SELECT SUM(t2.completed_quantity)
                          FROM tbl_customer_product_quantity_tracking AS t2
                          WHERE t2.business_details_id = businesses_details.id)) 
                          AS remaining_quantity'),
                    'production.updated_at'
                )
                ->groupBy(
                    'business_application_processes.id',
                    'businesses.id',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'businesses.remarks',
                    'production.updated_at'
                )
                ->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function editProduct($id)
    {
        try {
            if (!$id) {
                return [
                    'status' => 'error',
                    'msg' => 'Invalid product ID.'
                ];
            }

            // Using distinct to avoid duplicates
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->leftJoin('production_details as pd', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'pd.business_details_id');
                })
                ->leftJoin('estimation', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
                })
                ->leftJoin('tbl_unit', 'pd.unit', '=', 'tbl_unit.id')
                ->leftJoin('tbl_part_item', 'pd.part_item_id', '=', 'tbl_part_item.id')
                ->where('businesses_details.id', $id)
                ->where('businesses_details.is_active', true)
                ->where('pd.is_deleted', 0)
                ->whereNotNull('pd.part_item_id')
                ->where('pd.part_item_id', '!=', '')
                ->where('pd.quantity', '>', 0)
                ->select(
                    'businesses_details.id',
                    'pd.id as pd_id',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'pd.part_item_id',
                    'pd.quantity',
                    'pd.unit',
                    'tbl_unit.name as unit_name',
                    'pd.business_details_id',
                    'pd.material_send_production',
                    'pd.quantity_minus_status',
                    'pd.basic_rate',
                    'pd.updated_at',
                    'tbl_part_item.description as part_description',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date',
                    'estimation.total_estimation_amount'
                )
                ->distinct()
                ->get();

            // Extract product details
            $productDetails = $dataOutputByid->first();

            // Group data by business_details_id
            $dataGroupedById = $dataOutputByid->groupBy('business_details_id');

            return [
                'status' => 'success',
                'productDetails' => $productDetails,
                'dataGroupedById' => $dataGroupedById
            ];
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in editProduct: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'status' => 'error',
                'msg' => 'Failed to fetch product details. Please try again.'
            ];
        }
    }
    public function updateProductMaterial($request)
    {
        try {
            // ============================================
            // 0️⃣ PRE-VALIDATION (before any DB writes)
            //    Detect rows where the user entered a quantity
            //    but did NOT select a part item from the dropdown.
            //    We check here (outside the transaction) so that
            //    if validation fails, zero DB writes have occurred.
            // ============================================
            $missingPartRows = [];
            foreach ($request->addmore as $index => $item) {
                $hasQty = isset($item['quantity'])
                    && $item['quantity'] !== null
                    && $item['quantity'] !== ''
                    && floatval($item['quantity']) > 0;
                $hasPart = !empty($item['part_item_id'])
                    && $item['part_item_id'] !== null
                    && $item['part_item_id'] !== '';
                if ($hasQty && !$hasPart) {
                    $missingPartRows[] = (int)$index + 1; // 1-based row number for UI display
                }
            }
            if (!empty($missingPartRows)) {
                $rowList = implode(', ', $missingPartRows);
                return [
                    'status'  => 'error',
                    'message' => 'Row(s) ' . $rowList . ': a quantity was entered but no Part Item was selected. '
                        . 'Please click a part item from the dropdown list for each new row, then save again.'
                ];
            }

            return DB::transaction(function () use ($request) {

                // ============================================
                // 1️⃣ FETCH BASE DETAILS
                // ============================================
                $baseDetail = ProductionDetails::where('business_details_id', $request->business_details_id)
                    ->firstOrFail();

                // Remove any stale pending rows that have no part item (from previously empty form rows)
                ProductionDetails::where('business_details_id', $request->business_details_id)
                    ->where('quantity_minus_status', 'pending')
                    ->where('material_send_production', 0)
                    ->where('is_deleted', 0)
                    ->where(function ($q) {
                        $q->whereNull('part_item_id')
                          ->orWhere('part_item_id', '');
                    })
                    ->update(['is_deleted' => 1]);

                // Fix stale rows saved by old buggy form (material_send_production=1 but still pending)
                // Reset to 0 so store can see them as pending requests
                ProductionDetails::where('business_details_id', $request->business_details_id)
                    ->where('material_send_production', 1)
                    ->where('quantity_minus_status', 'pending')
                    ->where('is_deleted', 0)
                    ->whereNotNull('part_item_id')
                    ->where('part_item_id', '!=', '')
                    ->update(['material_send_production' => 0]);

                // ============================================
                // 2️⃣ LOOP THROUGH addmore ROWS
                // ============================================
                $savedCount = 0;

                foreach ($request->addmore as $index => $item) {

                    // --------------------------------------------------
                    // ⭐ FIRST: SKIP DISABLED ROWS (QUANTITY NOT SENT)
                    //    These are "Received from Store" rows whose quantity
                    //    input is disabled — they produce no quantity key.
                    // --------------------------------------------------
                    if (
                        !isset($item['quantity']) ||                // not present (disabled input)
                        $item['quantity'] === null ||              // null
                        $item['quantity'] === '' ||                // empty string
                        floatval($item['quantity']) <= 0           // 0 or negative
                    ) {
                        continue;  // SKIP this row completely
                    }

                    // --------------------------------------------------
                    // ⭐ SECOND: SKIP ROWS WITHOUT PART ITEM
                    //    (Pre-validation above already returned an error for
                    //    any row with qty>0 but no part_item_id, so this
                    //    continue is just a safety guard — should never fire.)
                    // --------------------------------------------------
                    if (
                        empty($item['part_item_id']) ||
                        $item['part_item_id'] == null ||
                        $item['part_item_id'] == ""
                    ) {
                        continue;
                    }

                    // Clean variables
                    $partItemId = $item['part_item_id'];
                    $quantity   = (float) $item['quantity'];
                    $unit       = $item['unit'] ?? null;

                    // --------------------------------------------------
                    // 3️⃣ FETCH PART ITEM BASIC RATE
                    // --------------------------------------------------
                    $partItemData = PartItem::find($partItemId);
                    $basicRate    = $partItemData ? $partItemData->basic_rate : 0;

                    $totalAmount  = $item['items_used_total_amount'] ?? ($basicRate * $quantity);

                    // --------------------------------------------------
                    // 4️⃣ ROUTE BY ROW IDENTITY (item_id present = UPDATE, absent = INSERT)
                    //
                    //    When the blade sends a non-empty item_id (the PK of an existing
                    //    ProductionDetails row), we update THAT specific row by its primary key.
                    //    No dedup-by-part_item_id is performed — each row is its own identity.
                    //
                    //    When item_id is absent/empty (a newly-added row), we always INSERT a
                    //    new row regardless of whether another row with the same part_item_id
                    //    already exists.  The same part can legitimately appear on multiple rows
                    //    with different units or quantities.
                    // --------------------------------------------------
                    $itemId = isset($item['item_id']) && $item['item_id'] !== '' && $item['item_id'] !== null
                        ? (int) $item['item_id']
                        : null;

                    if ($itemId !== null) {
                        // EXISTING ROW — update by primary key only
                        $existingRow = ProductionDetails::where('id', $itemId)
                            ->where('is_deleted', 0)
                            ->first();

                        if ($existingRow) {
                            $existingRow->quantity                = $quantity;
                            $existingRow->unit                    = $unit;
                            $existingRow->basic_rate              = $basicRate;
                            $existingRow->items_used_total_amount = $totalAmount;
                            $existingRow->save();
                            $savedCount++;
                        }
                        continue; // move to next row — never INSERT for an existing row
                    }

                    // --------------------------------------------------
                    // 5️⃣ INSERT NEW PENDING ROW (material_send_production=0 so store can see it)
                    //    No dedup checks — the same part_item_id can appear on multiple rows.
                    // --------------------------------------------------
                    $new = new ProductionDetails();
                    $new->business_id              = $baseDetail->business_id;
                    $new->design_id                = $baseDetail->design_id;
                    $new->business_details_id      = $baseDetail->business_details_id;
                    $new->production_id            = $baseDetail->production_id;

                    $new->part_item_id             = $partItemId;
                    $new->quantity                 = $quantity;
                    $new->unit                     = $unit;
                    $new->basic_rate               = $basicRate;
                    $new->items_used_total_amount  = $totalAmount;
                    $new->material_send_production = 0;    // store issues it — keeps it visible as pending
                    $new->quantity_minus_status    = 'pending';
                    $new->save();

                    $savedCount++;
                }

                // --------------------------------------------------
                // 6️⃣ UPSERT production_shortage DRAFT ROWS
                //    For every pending production_details row (quantity > available stock),
                //    ensure a draft requisition_items row exists with source='production_shortage'
                //    and is_sent_to_purchase=0 so the Store user can review/edit before sending.
                //    If shortage has resolved (qty <= stock), soft-delete any stale draft.
                //    Only runs when a Requisition already exists for this business_details_id.
                //    If no Requisition exists yet, skip (Store will create one when sending BOM).
                // --------------------------------------------------
                $requisitionForProd = Requisition::where('business_details_id', $baseDetail->business_details_id)->first();
                if ($requisitionForProd) {
                    // Re-fetch all current pending rows (includes rows just saved above)
                    $allPendingRows = ProductionDetails::where('business_details_id', $baseDetail->business_details_id)
                        ->where('quantity_minus_status', 'pending')
                        ->where('is_deleted', 0)
                        ->whereNotNull('part_item_id')
                        ->where('part_item_id', '!=', '')
                        ->where('quantity', '>', 0)
                        ->get();

                    // Track which part_item_ids have an active shortage so we can clean up stale drafts
                    $partIdsWithShortage = [];

                    foreach ($allPendingRows as $pendingRow) {
                        $stockQty = (float) ItemStock::where('part_item_id', $pendingRow->part_item_id)
                            ->where('is_active', 1)
                            ->where('is_deleted', 0)
                            ->sum('quantity');

                        $reqQty      = (float) $pendingRow->quantity;
                        $shortageQty = max(0, $reqQty - $stockQty);

                        if ($shortageQty > 0) {
                            // Shortage exists — upsert the draft row
                            $partIdsWithShortage[] = (int) $pendingRow->part_item_id;

                            $existingDraft = RequisitionItem::where('requisition_id', $requisitionForProd->id)
                                ->where('part_item_id', $pendingRow->part_item_id)
                                ->where('source', 'production_shortage')
                                ->where('is_sent_to_purchase', 0)
                                ->where('is_deleted', 0)
                                ->first();

                            if ($existingDraft) {
                                // UPDATE: refresh qty in case Production changed their request
                                $existingDraft->required_quantity  = $reqQty;
                                $existingDraft->available_quantity = $stockQty;
                                $existingDraft->shortage_quantity  = $shortageQty;
                                $existingDraft->save();
                            } else {
                                // INSERT: new draft row
                                $partItemData = PartItem::find($pendingRow->part_item_id);
                                RequisitionItem::create([
                                    'requisition_id'      => $requisitionForProd->id,
                                    'business_details_id' => $baseDetail->business_details_id,
                                    'part_item_id'        => (int) $pendingRow->part_item_id,
                                    'product_description' => $partItemData ? $partItemData->description : null,
                                    'required_quantity'   => $reqQty,
                                    'available_quantity'  => $stockQty,
                                    'shortage_quantity'   => $shortageQty,
                                    'unit_id'             => $pendingRow->unit ? (int) $pendingRow->unit : null,
                                    'rate'                => $partItemData ? $partItemData->basic_rate : null,
                                    'is_active'           => 1,
                                    'is_deleted'          => 0,
                                    'is_sent_to_purchase' => 0,
                                    'source'              => 'production_shortage',
                                ]);
                            }
                        } else {
                            // No shortage — soft-delete any stale production_shortage draft
                            RequisitionItem::where('requisition_id', $requisitionForProd->id)
                                ->where('part_item_id', $pendingRow->part_item_id)
                                ->where('source', 'production_shortage')
                                ->where('is_sent_to_purchase', 0)
                                ->where('is_deleted', 0)
                                ->update(['is_deleted' => 1, 'is_active' => 0]);
                        }
                    }

                    // Also soft-delete drafts for parts that no longer have ANY pending row
                    // (e.g. the Production row was itself soft-deleted or cancelled)
                    if (!empty($partIdsWithShortage)) {
                        RequisitionItem::where('requisition_id', $requisitionForProd->id)
                            ->where('source', 'production_shortage')
                            ->where('is_sent_to_purchase', 0)
                            ->where('is_deleted', 0)
                            ->whereNotIn('part_item_id', $partIdsWithShortage)
                            ->update(['is_deleted' => 1, 'is_active' => 0]);
                    } else {
                        // No pending rows with shortage at all — clean up all stale production_shortage drafts
                        RequisitionItem::where('requisition_id', $requisitionForProd->id)
                            ->where('source', 'production_shortage')
                            ->where('is_sent_to_purchase', 0)
                            ->where('is_deleted', 0)
                            ->update(['is_deleted' => 1, 'is_active' => 0]);
                    }
                }
                // end production_shortage upsert

                // --------------------------------------------------
                // 7️⃣ UPDATE PRODUCTION STATUS
                // --------------------------------------------------
                $businessOutput = BusinessApplicationProcesses::where('business_details_id', $baseDetail->business_details_id)
                    ->firstOrFail();

                $businessOutput->product_production_inprocess_status_id =
                    config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_INPROCESS_FOR_PRODUCTION');

                $businessOutput->save();

                // Mark production record so Store tracking list can see pending material requests
                ProductionModel::where('business_details_id', $baseDetail->business_details_id)
                    ->update(['store_status_quantity_tracking' => 'incomplete-store']);

                return [
                    'status'  => 'success',
                    'message' => 'Production materials updated successfully. Items saved: ' . $savedCount . '.'
                ];

            }); // end DB::transaction

        } catch (\Exception $e) {

            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }




    public function destroyAddmoreStoreItem($id)
    {
        try {
            $deleteDataById = ProductionDetails::find($id);

            if ($deleteDataById) {
                // Perform a soft delete by setting is_deleted = 1
                $deleteDataById->is_deleted = 1;
                $deleteDataById->save();

                return [
                    'status' => 'success',
                    'message' => 'Record marked as deleted successfully.',
                    'data' => $deleteDataById
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Record not found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
