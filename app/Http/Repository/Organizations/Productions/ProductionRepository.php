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
    PartItem
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
                dd('No business_application found for ID: ' . $business_details_id);
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
            dd('Caught Exception: ' . $e->getMessage());
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
                ->leftJoin('tbl_unit', 'pd.unit', '=', 'tbl_unit.id')
                ->leftJoin('tbl_part_item', 'pd.part_item_id', '=', 'tbl_part_item.id')
                ->where('businesses_details.id', $id)
                ->where('businesses_details.is_active', true)
                ->where('pd.is_deleted', 0)
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
                    'pd.basic_rate',
                    'tbl_part_item.description as part_description',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date'
                )
                ->distinct()  // Ensure only distinct rows
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

    //  public function editProduct($id){
    //         try {
    //             if (!$id) {
    //                 return [
    //                     'status' => 'error',
    //                     'msg' => 'Invalid product ID.'
    //                 ];
    //             }
    //             $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
    //                     $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //                 })
    //                 ->leftJoin('designs', function ($join) {
    //                     $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    //                 })
    //                 ->leftJoin('businesses_details', function ($join) {
    //                     $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    //                 })
    //                 ->leftJoin('design_revision_for_prod', function ($join) {
    //                     $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    //                 })
    //                 ->leftJoin('purchase_orders', function ($join) {
    //                     $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
    //                 })
    //                 ->leftJoin('production_details as pd', function ($join) {
    //                     $join->on('business_application_processes.business_details_id', '=', 'pd.business_details_id');
    //                 })
    //                 ->leftJoin('tbl_unit', 'pd.unit', '=', 'tbl_unit.id')
    //                 ->where('businesses_details.id', $id)
    //                 ->where('businesses_details.is_active', true)
    //                 ->where('pd.is_deleted', 0)
    //                 ->select(
    //                     'businesses_details.id',
    //                     'pd.id as pd_id',
    //                     'businesses_details.product_name',
    //                     'businesses_details.description',
    //                     'pd.part_item_id',
    //                     'pd.quantity',
    //                     'pd.unit',
    //                     'tbl_unit.name as unit_name',
    //                     'pd.business_details_id',
    //                     'pd.material_send_production',
    //                     'designs.bom_image',
    //                     'designs.design_image',
    //                     'business_application_processes.store_material_sent_date'
    //                 )
    //                 ->get();

    //             // Extract product details
    //             $productDetails = $dataOutputByid->first();

    //             // Group data by business_details_id
    //             $dataGroupedById = $dataOutputByid->groupBy('business_details_id');

    //             return [
    //                 'status' => 'success',
    //                 'productDetails' => $productDetails,
    //                 'dataGroupedById' => $dataGroupedById
    //             ];
    //         } catch (\Exception $e) {
    //             // Log the error for debugging
    //             \Log::error('Error in editProduct: ' . $e->getMessage(), [
    //                 'id' => $id,
    //                 'trace' => $e->getTraceAsString()
    //             ]);

    //             return [
    //                 'status' => 'error',
    //                 'msg' => 'Failed to fetch product details. Please try again.'
    //             ];
    //     }
    // }
    // public function updateProductMaterial($request) {
    //     try {
    //         $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $request->business_details_id)->firstOrFail();

    //         $errorMessages = []; // Array to hold error messages



    //         // Loop through the addmore array and update or create new ProductionDetails
    //         foreach ($request->addmore as $item) {

    //             $partItemData = PartItem::where('id', $item['part_item_id'])->first();
    //               $basicRate = $partItemData ? $partItemData->basic_rate : 0; // default 0 if not found

    //         // Calculate total amount
    //         $totalAmount = isset($item['items_used_total_amount'])
    //             ? $item['items_used_total_amount']
    //             : ($basicRate * $item['quantity']);

    //             // First, check if part_item_id already exists with material_send_production == 0
    //             $existingDetail = ProductionDetails::where('business_details_id', $request->business_details_id)
    //                 ->where('part_item_id', $item['part_no_id'])
    //                 ->where('material_send_production', 0)
    //                 ->where('quantity_minus_status', 'pending')
    //                 ->first();

    //                 if ($existingDetail) {

    //                 $existingDetail->part_item_id = $item['part_no_id'];
    //                 $existingDetail->quantity = $item['quantity'];
    //                 $existingDetail->unit = $item['unit'];
    //                 $existingDetail->basic_rate = $basicRate; // <-- auto insert basic_rate
    //                 $existingDetail->items_used_total_amount = $totalAmount;
    //                 $existingDetail->material_send_production = '0';
    //                 $existingDetail->quantity_minus_status = 'pending';
    //                 $existingDetail->save();
    //             } else {
    //                 $dataOutput = new ProductionDetails();
    //             $dataOutput->part_item_id = $item['part_no_id'];
    //             $dataOutput->quantity = $item['quantity'];
    //             $dataOutput->unit = $item['unit'];
    //             $dataOutput->basic_rate = $basicRate; 
    //             $dataOutput->items_used_total_amount = $totalAmount;
    //             $dataOutput->quantity_minus_status = 'pending';
    //             $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
    //             $dataOutput->business_id = $dataOutput_ProductionDetails->business_id;
    //             $dataOutput->design_id = $dataOutput_ProductionDetails->design_id;
    //             $dataOutput->business_details_id = $dataOutput_ProductionDetails->business_details_id;
    //             $dataOutput->production_id = $dataOutput_ProductionDetails->production_id;

    //             $dataOutput->save();
    //             // if ($dataOutput->material_send_production == 1) {
    //             //     $itemStock = ItemStock::where('part_item_id', $item['part_no_id'])->first();
    //             //     if ($itemStock) {
    //             //         if ($itemStock->quantity >= $item['quantity']) {
    //             //             $itemStock->quantity -= $item['quantity'];
    //             //             $itemStock->save();
    //             //         } else {
    //             //             $errorMessages[] = "Not enough stock for part item ID: " . $item['part_no_id'];
    //             //         }
    //             //     } else {
    //             //         $errorMessages[] = "Item stock not found for part item ID: " . $item['part_no_id'];
    //             //     }
    //             // }
    //         }
    //     }
    //         if (!empty($errorMessages)) {
    //             return [
    //                 'status' => 'error',
    //                 'errors' => $errorMessages 
    //             ];
    //         }
    //         $businessOutput = BusinessApplicationProcesses::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)
    //             ->firstOrFail();
    //         $businessOutput->product_production_inprocess_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_INPROCESS_FOR_PRODUCTION');
    //         $businessOutput->save();

    //         return [
    //             'status' => 'success',
    //             'message' => 'Production materials updated successfully.',
    //             'updated_details' => $request->all()
    //         ];

    //     } catch (\Exception $e) {
    //         return [
    //             'status' => 'error',
    //             'error' => $e->getMessage() 
    //         ];
    //     }
    // }
    public function updateProductMaterial($request)
    {
        try {
            $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $request->business_details_id)
                ->firstOrFail();
            // dd($dataOutput_ProductionDetails, "dataOutput_ProductionDetails=====");

            $errorMessages = [];

            foreach ($request->addmore as $item) {
                // dd($item, "item==========");
                // Ensure default values to avoid undefined index
                $partItemId = $item['part_item_id'] ?? null;
                $quantity   = $item['quantity'] ?? 0;
                $unit       = $item['unit'] ?? null;

                if (!$partItemId) {
                    $errorMessages[] = "Part Item ID is missing for one of the rows.";
                    continue;
                }

                // Get basic rate
                $partItemData = PartItem::where('id', $partItemId)->first();
                // dd($partItemData, "partItemData==========");
                $basicRate    = $partItemData ? $partItemData->basic_rate : 0;
                // dd($basicRate, "basicRate==========");
                // Calculate total amount
                $totalAmount = isset($item['items_used_total_amount'])
                    ? $item['items_used_total_amount']
                    : ($basicRate * $quantity);
                dd($totalAmount, "totalAmount==========");
                // Check if part_item_id already exists with material_send_production == 0
                $existingDetail = ProductionDetails::where('business_details_id', $request->business_details_id)
                    ->where('part_item_id', $partItemId)
                    ->where('material_send_production', 0)
                    ->where('quantity_minus_status', 'pending')
                    ->first();
                // dd($existingDetail, "existingDetail==========");
                if ($existingDetail) {

                    dd($existingDetail, "existingDetailexistingDetail==========");
                    $existingDetail->part_item_id              = $partItemId;
                    $existingDetail->quantity                  = $quantity;
                    $existingDetail->unit                      = $unit;
                    $existingDetail->basic_rate                = $basicRate;
                    $existingDetail->items_used_total_amount   = $totalAmount;
                    $existingDetail->material_send_production  = 0;
                    $existingDetail->quantity_minus_status     = 'pending';
                    $existingDetail->save();
                } else {
                    // dd("hii savita==========");
                    $dataOutput = new ProductionDetails();
                    $dataOutput->part_item_id               = $partItemId;
                    $dataOutput->quantity                   = $quantity;
                    $dataOutput->unit                       = $unit;
                    $dataOutput->basic_rate                 = $basicRate;
                    $dataOutput->items_used_total_amount    = $totalAmount;
                    $dataOutput->quantity_minus_status      = 'pending';
                    $dataOutput->material_send_production   = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
                    $dataOutput->business_id                = $dataOutput_ProductionDetails->business_id;
                    $dataOutput->design_id                  = $dataOutput_ProductionDetails->design_id;
                    $dataOutput->business_details_id        = $dataOutput_ProductionDetails->business_details_id;
                    $dataOutput->production_id              = $dataOutput_ProductionDetails->production_id;


                    dd($dataOutput, "dataOutput==========");
                    $dataOutput->save();
                }
            }


            if (!empty($errorMessages)) {
                return [
                    'status' => 'error',
                    'errors' => $errorMessages
                ];
            }

            // Update status
            $businessOutput = BusinessApplicationProcesses::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)
                ->firstOrFail();
            $businessOutput->product_production_inprocess_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_INPROCESS_FOR_PRODUCTION');
            $businessOutput->save();

            return [
                'status' => 'success',
                'message' => 'Production materials updated successfully.',
                'updated_details' => $request->all()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
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
