<?php
namespace App\Http\Repository\Organizations\Store;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    Business,
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    Requisition,
    PurchaseOrderModel,
    AdminView,
    BusinessDetails,
    ProductionDetails,
    ItemStock,
    NotificationStatus,
    CustomerProductQuantityTracking,
    Gatepass
    
};
use Config;

class StoreRepository
{

    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {
            $id = base64_decode($id); // Assuming $encodedId is base64 encoded
            
            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION');
                $business_application->store_material_sent_date = date('Y-m-d');
                $business_application->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $business_application->save();
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function storeRequesition($request)
    {
        try {
            
            $production_id = base64_decode($request->production_id);
            $businessDetails = BusinessDetails::where('id', $production_id)->first();
            if (!$businessDetails) {
                return [
                    'msg' => 'Business details not found.',
                    'status' => 'error',
                ];
            }
    
            $productName = $businessDetails->product_name;
            // $business_application = BusinessApplicationProcesses::where('design_id', $production_id)->first();
            $business_application = BusinessApplicationProcesses::where('business_details_id', $businessDetails->id)->first();
            $dataOutput = new Requisition();
            $dataOutput->business_id = $business_application->business_id;
            $dataOutput->business_details_id = $business_application->business_details_id;
            $dataOutput->design_id = $business_application->design_id;
            $dataOutput->production_id = $business_application->production_id;
            $dataOutput->req_name = "";
            $dataOutput->req_date = date('Y-m-d');
            $dataOutput->bom_file = 'null';
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            // Updating image name in requisition
            $imageName = $last_insert_id . '_'. $productName.'_' . rand(100000, 999999) . '_requisition_bom.' . $request->bom_file_req->getClientOriginalExtension();
            $finalOutput = Requisition::find($last_insert_id);
            $finalOutput->bom_file = $imageName;
            $finalOutput->save();
            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.BOM_SENT_TO_STORE_DEPT_FOR_CHECKING');
                $business_application->store_status_id = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');
                $business_application->requisition_id = $last_insert_id;
                $business_application->purchase_order_id = '0';
                $dataOutput->purchase_dept_req_sent_date = date('Y-m-d');
                $business_application->save();

           $update_data_admin['off_canvas_status'] = 16;
           $update_data_admin['is_view'] = '0';
           $update_data_business['off_canvas_status'] = 16;
           $update_data_business['purchase_is_view'] = 0;
           AdminView::where('business_details_id', $business_application->business_details_id)
               // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
               ->update($update_data_admin);

           NotificationStatus::where('business_details_id', $business_application->business_details_id)
               // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
               ->update($update_data_business);

            }
          // Updating off_canvas_status for the business application
        $business_application->off_canvas_status = 16;
        $business_application->save();
           
            // PurchaseOrderModel::where('business_id', $business_application->business_id)->update(['purchase_status_from_purchase', config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')]);
        
            return [
                'ImageName' => $imageName,
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    public function genrateStoreReciptAndForwardMaterialToTheProduction($purchase_orders_id, $business_id)
    {
        try {
      
         
            // Fetch the purchase order and business application based on the production ID
            $purchase_order = PurchaseOrderModel::where('purchase_orders_id', $purchase_orders_id)->first();
          
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();

            // Generate a store receipt number
            $store_receipt_no = str_replace(array("-", ":"), "", date('Y-m-d') . time());
    
            // Check if the business application exists
            if ( $business_application  &&  $purchase_order) {
                // Update the business application statuses and dates
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION');
                $business_application->store_material_sent_date = date('Y-m-d');
                $business_application->off_canvas_status = 17; 
                // Update the purchase order statuses, receipt number, and dates
                $purchase_order->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $purchase_order->store_receipt_no = $store_receipt_no;
                $purchase_order->finanace_store_receipt_generate_date = date('Y-m-d');
                $purchase_order->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT');
    
                // Save the updated business application and purchase order
                $business_application->save();
                $purchase_order->save();
            }
          
    
            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    // public function editProductMaterialWiseAddNewReq($id) {
    //     try {
    //         $id = base64_decode($id); 
    //         // $purchase_orders_id = $purchase_orders_id;
    //         // dd($purchase_orders_id);
    //         // die();
    //         // $business_id = $business_id;
    //         // Fetch all related data
    //         $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //             })
    //             ->leftJoin('designs', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    //             })
    //             ->leftJoin('businesses_details', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    //             })
    //             ->leftJoin('design_revision_for_prod', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    //             })
    //             // ->leftJoin('purchase_orders', function($join) {
    //             //     $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
    //             // })
    //             ->leftJoin('production_details', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
    //             })
    //             // ->leftJoin('grn_tbl', function($join) {
    //             //     $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
    //             // })
    //             // ->leftJoin('gatepass', function($join) {
    //             //     $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
    //             // })
    //             ->where('businesses_details.id', $id)
    //             // ->where('purchase_orders.purchase_orders_id', $purchase_orders_id)
    //             // ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
    //             ->where('businesses_details.is_active', true)
    //             ->select(
    //                 'businesses_details.id',
    //                 // 'gatepass.id',
    //                 'production_details.id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.quantity',
    //                 'businesses_details.description',
    //                 'production_details.part_item_id',
    //                 'production_details.quantity',
    //                 'production_details.unit',
    //                 'production_details.quantity_minus_status',
    //                 'production_details.material_send_production',
    //                 'designs.bom_image',
    //                 'designs.design_image',
    //                 'business_application_processes.store_material_sent_date'
    //             )
    //             ->get(); 
    //             // dd($dataOutputByid);
    //             // die();
    //         // Extract product details and data for table
    //         $productDetails = $dataOutputByid->first(); // Assuming the first entry contains the product details
    //         $dataGroupedById = $dataOutputByid->groupBy('business_details_id');
    
    //         return [
    //             'productDetails' => $productDetails,
    //             'dataGroupedById' => $dataGroupedById
    //         ]; 
    //         // return  $dataOutputByid;
    //     } catch (\Exception $e) {
    //         return [
    //             'status' => 'error',
    //             'msg' => $e->getMessage()
    //         ];
    //     }
    // }

    public function editProductMaterialWiseAddNewReq($id) {
        try {
            $id = base64_decode($id);
    
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', 'business_application_processes.business_details_id', '=', 'production.business_details_id')
                ->leftJoin('designs', 'business_application_processes.business_details_id', '=', 'designs.business_details_id')
                ->leftJoin('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
                ->leftJoin('design_revision_for_prod', 'business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id')
                ->leftJoin('production_details', 'business_application_processes.business_details_id', '=', 'production_details.business_details_id')
                ->where('businesses_details.id', $id)
                ->where('businesses_details.is_active', true)
                ->select(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    // 'businesses_details.quantity as total_quantity',
                    'businesses_details.description',
                    'production_details.part_item_id',
                    'production_details.quantity',
                    'production_details.unit',
                    'production_details.material_send_production',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date'
                )
                ->distinct()
                ->get();
    
            $productDetails = $dataOutputByid->first(); // Fetch the first entry
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
    
    // public function updateProductMaterialWiseAddNewReq($request) {
    //     try {
    //         $business_details_id = base64_decode($request->business_details_id);
        
    //         $dataOutput_Production = ProductionModel::where('business_details_id', $business_details_id)->firstOrFail();
    //         $dataOutput_Production->production_status_quantity_tracking = 'incomplete';
    //         $dataOutput_Production->save();
    //         $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $dataOutput_Production->business_details_id)->firstOrFail();
           
    //         $business_details_id = $dataOutput_ProductionDetails->business_details_id;
    //         $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
    //         if (!$business_application) {
    //             return [
    //                 'msg' => 'Business Application not found.',
    //                 'status' => 'error'
    //             ];
    //         }
    //         // Remove existing records related to the business_details_id before saving new ones
    //         ProductionDetails::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)->delete();
    //         $errorMessages = []; // Array to hold error messages
    //         foreach ($request->addmore as $item) {
    //             $dataOutput = new ProductionDetails();
    //             $dataOutput->part_item_id = $item['part_item_id'];
    //             $dataOutput->quantity = $item['quantity'];
    //             $dataOutput->unit = $item['unit'];
    //             $dataOutput->quantity_minus_status = 'pending';
    //             $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
    //             $dataOutput->business_id = $dataOutput_ProductionDetails->business_id;
    //             $dataOutput->design_id = $dataOutput_ProductionDetails->design_id;
    //             $dataOutput->business_details_id = $dataOutput_ProductionDetails->business_details_id;
    //             $dataOutput->production_id = $dataOutput_ProductionDetails->production_id;
    //             $dataOutput->save();

    //         $existingEntry = ProductionDetails::find($dataOutput->id);
    //         // Ensure $existingEntry exists and has a part_item_id
    //         if ($existingEntry && isset($existingEntry->part_item_id)) {

    //             $partItemId = $existingEntry->part_item_id; // Get the part_item_id from the existing entry
    //             $itemStock = ItemStock::where('part_item_id', $partItemId)->first();

    //             if ($itemStock) {
    //                 if ($itemStock->quantity >= $dataOutput->quantity) {
    //                     // Deduct stock from available quantity
    //                     $itemStock->quantity -= $dataOutput->quantity;
    //                     $itemStock->save();
    
    //                     // Update the production detail status
    //                     $dataOutput->material_send_production = 1;
    //                     $dataOutput->quantity_minus_status = 'done';
    //                     $dataOutput->save();
    //                 } else {
    //                     $errorMessages[] = "Not enough stock for part item ID: " . $dataOutput->part_item_id;
    //                 }
    //             } else {
    //                 $errorMessages[] = "Item stock not found for part item ID: " . $dataOutput->part_item_id;
    //             }
           
    //     } else {
    //         $errorMessages[] = "Production detail not found or part_item_id is missing.";
    //     }
    //         }

    //            // Update the business application process's off_canvas_status
    //         $business_application->off_canvas_status = 17;
    //         $business_application->save();
    //          // Update admin view and notification status with the new off canvas status
    //          $update_data_admin['off_canvas_status'] = 17;
    //          $update_data_admin['is_view'] = '0';
    //           $update_data_business['off_canvas_status'] = 17;
    //          AdminView::where('business_details_id', $business_application->business_details_id)
    //              // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
    //              ->update($update_data_admin);
 
    //          NotificationStatus::where('business_details_id', $business_application->business_details_id)
    //              // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
    //              ->update($update_data_business);
           
    //             // If there are error messages, return them without a generic error message
    //         if (!empty($errorMessages)) {
    //             return [
    //                 'status' => 'error',
    //                 'errors' => $errorMessages // Only return specific error messages
    //             ];
    //         }
    
    //         // Update the BusinessApplicationProcesses status
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
    //         // Optionally, you can log the exception message if needed
    //         return [
    //             'status' => 'error',
    //             'error' => $e->getMessage() // Return the exception message only if necessary
    //         ];
    //     }
    // }

public function updateProductMaterialWiseAddNewReq($request)
{
    try {
        $business_details_id = base64_decode($request->business_details_id);
        // Fetch production details
        $dataOutput_Production = ProductionModel::where('business_details_id', $business_details_id)->firstOrFail();
        $dataOutput_Production->production_status_quantity_tracking = 'incomplete';
        $dataOutput_Production->save();
      
        $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $dataOutput_Production->business_details_id)->firstOrFail();
        $business_details_id = $dataOutput_ProductionDetails->business_details_id;
        $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
      
        if (!$business_application) {
            return [
                'msg' => 'Business Application not found.',
                'status' => 'error'
            ];
        }

        $errorMessages = []; // To hold errors for stock validation
     
        foreach ($request->addmore as $item) {
            $existingEntry = ProductionDetails::where('business_details_id', $business_application->business_details_id)
            ->where('quantity_minus_status','pending')
            ->where('material_send_production',0)  
                ->first();
               
            if ($existingEntry) {
             if($item['quantity_minus_status'] =='pending'){
                if ($existingEntry->quantity_minus_status == 'pending' && $existingEntry->material_send_production == 0) {
                    $existingEntry->part_item_id = $item['part_item_id'];
                    $existingEntry->quantity = $item['quantity'];  // Set the new quantity (replacing the existing one)
                    $existingEntry->unit = $item['unit'];  // Update the unit if needed
                    $existingEntry->quantity_minus_status = 'pending';  // Ensure it's 'pending' for new request
                    $existingEntry->material_send_production = 0;  // Reset material_send_production
                    $existingEntry->save();
           
                    $partItemId = $existingEntry->part_item_id;
                    $itemStock = ItemStock::where('part_item_id', $partItemId)->first();

            if ($itemStock) {
                if ($itemStock->quantity >= $existingEntry->quantity && 
                $existingEntry->material_send_production == 0 &&
                $existingEntry->quantity_minus_status == 'pending') {
                    // Deduct stock and update statuses
                    $itemStock->quantity -= $existingEntry->quantity ;
                    $itemStock->save();

                    // Mark the entry as done and material sent to production
                    if ($existingEntry) {
                        $existingEntry->material_send_production = 1;
                        $existingEntry->quantity_minus_status = 'done';  // Set to done
                    } else {
                        // For newly created entry, mark as 'done' as well
                        $newEntry->material_send_production = 1;
                        $newEntry->quantity_minus_status = 'done';  // Set to done
                    }
                    // Save updated entry (after stock deduction)
                    $existingEntry ? $existingEntry->save() : $newEntry->save();
                } else {
                    // Log error if not enough stock
                    $errorMessages[] = "Not enough stock for part item ID: " . $item['part_item_id'];
                }
            } else {
                // Log error if stock record not found
                $errorMessages[] = "Item stock not found for part item ID: " . $item['part_item_id'];
            }
        }
        else{
            echo "hii";
        }

        }
            } else {
                // If no matching record exists, create a new entry
                $newEntry = new ProductionDetails();
                $newEntry->part_item_id = $item['part_item_id'];
                $newEntry->quantity = $item['quantity'];
                $newEntry->unit = $item['unit'];
                $newEntry->quantity_minus_status = 'pending';  // Status as 'pending'
                $newEntry->material_send_production = 0;  // Not yet sent for production
                $newEntry->business_id = $dataOutput_ProductionDetails->business_id;
                $newEntry->design_id = $dataOutput_ProductionDetails->design_id;
                $newEntry->business_details_id = $dataOutput_ProductionDetails->business_details_id;
                $newEntry->production_id = $dataOutput_ProductionDetails->production_id;
                // dd($newEntry);
                // die();
                $newEntry->save();
                    // Handle stock and quantity deduction
                    $partItemId = $newEntry->part_item_id;
            $itemStock = ItemStock::where('part_item_id', $partItemId)->first();

            if ($itemStock) {
                // if ($itemStock->quantity >= $newEntry->quantity) {
                    if ($itemStock->quantity >= $newEntry->quantity && 
            $newEntry->material_send_production == 0 &&
            $newEntry->quantity_minus_status == 'pending') {
                    // Deduct stock and update statuses
                    $itemStock->quantity -= $newEntry->quantity ;
                    $itemStock->save();

                    // Mark the entry as done and material sent to production
                    if ($newEntry) {
                        $newEntry->material_send_production = 1;
                        $newEntry->quantity_minus_status = 'done';  // Set to done
                    } else {
                        // For newly created entry, mark as 'done' as well
                        $newEntry->material_send_production = 1;
                        $newEntry->quantity_minus_status = 'done';  // Set to done
                    }
                    // Save updated entry (after stock deduction)
                    $newEntry ? $newEntry->save() : $newEntry->save();
                } else {
                    // Log error if not enough stock
                    $errorMessages[] = "Not enough stock for part item ID: " . $item['part_item_id'];
                }
            } else {
                // Log error if stock record not found
                $errorMessages[] = "Item stock not found for part item ID: " . $item['part_item_id'];
            }
            }

        
        }

        // Update BusinessApplicationProcesses and related statuses
        $business_application->off_canvas_status = 17;
        $business_application->save();

        AdminView::where('business_details_id', $business_application->business_details_id)
            ->update(['off_canvas_status' => 17, 'is_view' => '0']);
        NotificationStatus::where('business_details_id', $business_application->business_details_id)
            ->update(['off_canvas_status' => 17]);

        // If errors occurred, return them
        if (!empty($errorMessages)) {
            return [
                'status' => 'error',
                'errors' => $errorMessages
            ];
        }

        // Update production status
        $businessOutput = BusinessApplicationProcesses::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)->firstOrFail();
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

//     public function updateProductMaterialWiseAddNewReq($request) {
//     try {
//         $business_details_id = base64_decode($request->business_details_id);
    
//         $dataOutput_Production = ProductionModel::where('business_details_id', $business_details_id)->firstOrFail();
//         $dataOutput_Production->production_status_quantity_tracking = 'incomplete';
//         $dataOutput_Production->save();
        
//         $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $dataOutput_Production->business_details_id)->firstOrFail();
        
//         $business_details_id = $dataOutput_ProductionDetails->business_details_id;
//         $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
//         if (!$business_application) {
//             return [
//                 'msg' => 'Business Application not found.',
//                 'status' => 'error'
//             ];
//         }

//         // Remove existing records related to the business_details_id before saving new ones
//         ProductionDetails::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)->delete();
        
//         $errorMessages = []; // Array to hold error messages
// // dd($request);
// // die();
//         foreach ($request->addmore as $item) {
//             $dataOutput = new ProductionDetails();
//             $dataOutput->part_item_id = $item['part_item_id'];
//             $dataOutput->quantity = $item['quantity'];
//             $dataOutput->unit = $item['unit'];
//              // Update material_send_production only if provided
//     if (isset($item['material_send_production']) && $item['material_send_production'] == 1) {
//         $dataOutput->material_send_production = 1;
//         $dataOutput->quantity_minus_status = 'done';
//     }
//             // $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
//             $dataOutput->business_id = $dataOutput_ProductionDetails->business_id;
//             $dataOutput->design_id = $dataOutput_ProductionDetails->design_id;
//             $dataOutput->business_details_id = $dataOutput_ProductionDetails->business_details_id;
//             $dataOutput->production_id = $dataOutput_ProductionDetails->production_id;
//             $dataOutput->save();

//             $existingEntry = ProductionDetails::find($dataOutput->id);

//             if ($existingEntry && isset($existingEntry->part_item_id)) {
//                 $partItemId = $existingEntry->part_item_id;
                
//                 $itemStock = ItemStock::where('part_item_id', $partItemId)->first();
//                 if ($dataOutput->material_send_production == 1 && $dataOutput->material_send_production == 'done') {
//                     if ($itemStock) {
// //                         dd($itemStock);
// // die();
//                         if ($itemStock->quantity >= $item['quantity']) {
//                             $itemStock->quantity -= $item['quantity'];
//                             $itemStock->save();
//                         } else {
//                             $errorMessages[] = "Not enough stock for part item ID: " . $dataOutput->part_item_id;
//                         }
//                     } else {
//                         $errorMessages[] = "Item stock not found for part item ID: " . $dataOutput->part_item_id;
//                     }
//                 }
//             } else {
//                 $errorMessages[] = "Production detail not found or part_item_id is missing.";
//             }
//         }

//         // Update the business application process's off_canvas_status
//         $business_application->off_canvas_status = 17;
//         $business_application->save();
        
//         $update_data_admin['off_canvas_status'] = 17;
//         $update_data_admin['is_view'] = '0';
//         $update_data_business['off_canvas_status'] = 17;

//         AdminView::where('business_details_id', $business_application->business_details_id)
//             ->update($update_data_admin);

//         NotificationStatus::where('business_details_id', $business_application->business_details_id)
//             ->update($update_data_business);

//         // Return error messages if there are any
//         if (!empty($errorMessages)) {
//             return [
//                 'status' => 'error',
//                 'errors' => $errorMessages // Return specific error messages
//             ];
//         }

        public function editProductMaterialWiseAdd($purchase_orders_id, $business_id) {
        try {
            // $id = base64_decode($id); 
            // $purchase_orders_id = $purchase_orders_id;
            // dd($purchase_orders_id);
            // die();
            // $business_id = $business_id;
            // Fetch all related data
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
                })
                ->leftJoin('designs', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses_details', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('design_revision_for_prod', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->leftJoin('purchase_orders', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->leftJoin('production_details', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
                })
                ->leftJoin('grn_tbl', function($join) {
                    $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
                })
                ->leftJoin('gatepass', function($join) {
                    $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
                })
                // ->where('businesses_details.id', $business_id)
                ->where('purchase_orders.purchase_orders_id', $purchase_orders_id)
                // ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
                ->where('businesses_details.is_active', true)
                ->select(
                    'gatepass.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'production_details.part_item_id',
                    'production_details.quantity',
                    'production_details.unit',
                    'production_details.material_send_production',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date'
                )
                ->get(); 
                // dd($dataOutputByid);
                // die();
            // Extract product details and data for table
            $productDetails = $dataOutputByid->first(); // Assuming the first entry contains the product details
            $dataGroupedById = $dataOutputByid->groupBy('business_details_id');
    
            return [
                'productDetails' => $productDetails,
                'dataGroupedById' => $dataGroupedById
            ]; 
            // return  $dataOutputByid;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
        }
    }
    public function updateProductMaterialWiseAdd($request) {
        try {
           
            $gatepassId = $request->id;
          


            $business_details_id = $request->business_details_id;
            
            $dataOutput_Production = ProductionModel::where('business_details_id', $business_details_id)->firstOrFail();
            $dataOutput_Production->production_status_quantity_tracking = 'incomplete';
            $dataOutput_Production->save();
         
        
            
            $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $dataOutput_Production->business_details_id)->firstOrFail();
           
           
               // Fetch the business details ID from the purchase order
     $business_details_id = $dataOutput_ProductionDetails->business_details_id;
    
     // Fetch the business application process using business_details_id
     $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
   
     if (!$business_application) {
         return [
             'msg' => 'Business Application not found.',
             'status' => 'error'
         ];
     }
    
      // This should be the 'id' of the gatepass you want to update

     // Check if the Gatepass record exists
   
     
            // Remove existing records related to the business_details_id before saving new ones
            ProductionDetails::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)->delete();
    
            $errorMessages = []; // Array to hold error messages
            foreach ($request->addmore as $item) {
             
                $dataOutput = new ProductionDetails();
                $dataOutput->part_item_id = $item['id'];
                $dataOutput->quantity = $item['quantity'];
                $dataOutput->unit = $item['unit'];
                $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
                $dataOutput->business_id = $dataOutput_ProductionDetails->business_id;
                $dataOutput->design_id = $dataOutput_ProductionDetails->design_id;
                $dataOutput->business_details_id = $dataOutput_ProductionDetails->business_details_id;
                $dataOutput->production_id = $dataOutput_ProductionDetails->production_id;
                $dataOutput->save();
            $existingEntry = ProductionDetails::find($dataOutput->id);
// Ensure $existingEntry exists and has a part_item_id
if ($existingEntry && isset($existingEntry->part_item_id)) {

    $partItemId = $existingEntry->part_item_id; // Get the part_item_id from the existing entry
    $itemStock = ItemStock::where('part_item_id', $partItemId)->first();
} else {
    $errorMessages[] = "Production detail not found or part_item_id is missing.";
}

            }


               // Update the business application process's off_canvas_status
    $business_application->off_canvas_status = 17;
    $business_application->save();
             // Update admin view and notification status with the new off canvas status
             $update_data_admin['off_canvas_status'] = 17;
             $update_data_admin['is_view'] = '0';
              $update_data_business['off_canvas_status'] = 17;
             AdminView::where('business_details_id', $business_application->business_details_id)
                 // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
                 ->update($update_data_admin);
 
             NotificationStatus::where('business_details_id', $business_application->business_details_id)
                 // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
                 ->update($update_data_business);
           

            //              $gatepass = Gatepass::where('id', $request->id)->first();
          
            //   Gatepass::where('id', $gatepass->id)
            //   ->update([
            //       'po_tracking_status' => 4003, // Update the tracking status
            //   ]);

                // If there are error messages, return them without a generic error message
            if (!empty($errorMessages)) {
                return [
                    'status' => 'error',
                    'errors' => $errorMessages // Only return specific error messages
                ];
            }
    
            // Update the BusinessApplicationProcesses status
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
            // Optionally, you can log the exception message if needed
            return [
                'status' => 'error',
                'error' => $e->getMessage() // Return the exception message only if necessary
            ];
        }
    }
    public function editProduct($id)
{
    try {
        // Validate the ID
        if (!$id) {
            return [
                'status' => 'error',
                'msg' => 'Invalid product ID.'
            ];
        }

        // Build the query to fetch product details
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
            ->where('businesses_details.id', $id)
            ->where('businesses_details.is_active', true)
            ->select(
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.description',
                'pd.part_item_id',
                'pd.quantity',
                'pd.unit',
                'tbl_unit.name as unit_name',
                'pd.business_details_id',
                'pd.material_send_production',
                'designs.bom_image',
                'designs.design_image',
                'business_application_processes.store_material_sent_date'
            )
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
        \Log::error('Error in editProduct: ' . $e->getMessage(), [
            'id' => $id,
            'trace' => $e->getTraceAsString()
        ]);

        return [
            'status' => 'error',
            'msg' => 'Failed to fetch product details. Please try again.'
        ];
    }
}

    // public function editProduct($id) {
    //     try {
    //         $array_to_be_check = [
    //             config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')
    //         ];
    
    //         // Fetch all related data
    //         $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //             })
    //             ->leftJoin('designs', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    //             })
    //             ->leftJoin('businesses_details', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    //             })
    //             ->leftJoin('design_revision_for_prod', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    //             })
    //             ->leftJoin('purchase_orders', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
    //             })
    //             // ->leftJoin('production_details', function($join) {
    //             //     $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
    //             // })
    //             ->leftJoin('production_details as pd', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'pd.business_details_id');
    //             })
    //             ->leftJoin('tbl_unit', 'pd.unit', '=', 'tbl_unit.id')
    //             ->where('businesses_details.id', $id)
    //             // ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
    //             ->where('businesses_details.is_active', true)
    //             ->select(
    //                 'businesses_details.id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.quantity',
    //                 'businesses_details.description',
    //                 'pd.part_item_id',
    //                 'pd.quantity',
    //                 'pd.unit',
    //                 'tbl_unit.name as unit_name',  
    //                 'pd.business_details_id',
    //                 'pd.material_send_production',
    //                 'designs.bom_image',
    //                 'designs.design_image',
    //                 'business_application_processes.store_material_sent_date'
    //             )
    //             ->get(); 
    
    //         // Extract product details and data for table
    //         $productDetails = $dataOutputByid->first(); // Assuming the first entry contains the product details
    //         $dataGroupedById = $dataOutputByid->groupBy('business_details_id');
    
    //         return [
    //             'productDetails' => $productDetails,
    //             'dataGroupedById' => $dataGroupedById
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'status' => 'error',
    //             'msg' => $e->getMessage()
    //         ];
    //     }
    // }
        public function updateProductMaterial($request) {
        try {
            $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $request->business_details_id)->firstOrFail();
            
            $errorMessages = []; // Array to hold error messages
    
            // Loop through the addmore array and update or create new ProductionDetails
            foreach ($request->addmore as $item) {
                // First, check if part_item_id already exists with material_send_production == 0
                $existingDetail = ProductionDetails::where('business_details_id', $request->business_details_id)
                    ->where('part_item_id', $item['part_no_id'])
                    ->where('material_send_production', 0)
                    ->first();
    
                // If found, update the existing record, otherwise create a new one
                if ($existingDetail) {
                    $dataOutput = $existingDetail; // Update the existing record
                } else {
                    $dataOutput = new ProductionDetails(); // Create a new record
                }
    
                // Update fields with request data
                $dataOutput->part_item_id = $item['part_no_id'];
                $dataOutput->quantity = $item['quantity'];
                $dataOutput->unit = $item['unit'];
                $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
                $dataOutput->business_id = $dataOutput_ProductionDetails->business_id;
                $dataOutput->design_id = $dataOutput_ProductionDetails->design_id;
                $dataOutput->business_details_id = $dataOutput_ProductionDetails->business_details_id;
                $dataOutput->production_id = $dataOutput_ProductionDetails->production_id;
                $dataOutput->save();
    
                // Now handle stock decrement logic if material_send_production is 0
                if ($dataOutput->material_send_production == 1) {
                    $itemStock = ItemStock::where('part_item_id', $item['part_no_id'])->first();
                    if ($itemStock) {
                        // Check if enough stock is available
                        if ($itemStock->quantity >= $item['quantity']) {
                            // Decrement the stock quantity
                            $itemStock->quantity -= $item['quantity'];
                            $itemStock->save();
                        } else {
                            $errorMessages[] = "Not enough stock for part item ID: " . $item['part_no_id'];
                        }
                    } else {
                        $errorMessages[] = "Item stock not found for part item ID: " . $item['part_no_id'];
                    }
                }
            }
    
            // If there are error messages, return them without a generic error message
            if (!empty($errorMessages)) {
                return [
                    'status' => 'error',
                    'errors' => $errorMessages // Only return specific error messages
                ];
            }
    
            // Update the BusinessApplicationProcesses status
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
            // Optionally, you can log the exception message if needed
            return [
                'status' => 'error',
                'error' => $e->getMessage() // Return the exception message only if necessary
            ];
        }
    }
    
    

    
}