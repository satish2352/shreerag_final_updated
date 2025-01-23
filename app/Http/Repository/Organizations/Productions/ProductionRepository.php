<?php
namespace App\Http\Repository\Organizations\Productions;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
Business, 
DesignModel,
BusinessApplicationProcesses,
ProductionModel,
DesignRevisionForProd,
Logistics,
Dispatch,
BusinessDetails,
AdminView,
ProductionDetails,
NotificationStatus,
CustomerProductQuantityTracking
};
use Config;

class ProductionRepository  {

    public function acceptdesign($id)
    {
        try {
            // Fetch the business application process for the given business details ID
            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
    
            if ($business_application) {
                // Update business application statuses
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION');
                $business_application->off_canvas_status = 15;
                $business_application->save();
    
                // // Update admin view for the business
                // AdminView::where('business_id', $business_application->business_id)
                //     ->update([
                //         'current_department' => config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION'),
                //         'is_view' => '0'
                //     ]);
                 // Update AdminView and NotificationStatus with the correct business details
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
    
            // Fetch and update the production model for the given business details ID
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

            
    
            // Fetch the design revision and design model for the business details ID
            // $designRevisionForProdID = DesignRevisionForProd::where('business_details_id', $business_application->business_details_id)->first();
            // $dataOutput = DesignModel::where('business_details_id', $business_application->business_details_id)->first();
    
            // if (!$dataOutput) {
            //     return [
            //         'msg' => 'Design model not found',
            //         'status' => 'error',
            //     ];
            // }
    
            // // Update design model images
            // $dataOutput->design_image = $designRevisionForProdID->design_image;
            // $dataOutput->bom_image = $designRevisionForProdID->bom_image;
            // $dataOutput->save();
    
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
    public function rejectdesign($request){
        try {
             
            $idtoedit = base64_decode($request->business_id);
          
            // $idtoedit = BusinessApplicationProcesses::where('business_details_id', $id)->first();
            $production_data = ProductionModel::where('business_details_id', $idtoedit)->first();
           
            $designRevisionForProdID = DesignRevisionForProd::where('design_id', $production_data->business_details_id)->orderBy('id','desc')->first();
            
        //       dd( $designRevisionForProdID);
        //   die();
            if($designRevisionForProdID) {

                $designRevisionForProdID->business_id = $production_data->business_id;
                $designRevisionForProdID->business_details_id = $production_data->business_details_id;
                $designRevisionForProdID->design_id = $production_data->design_id;
                $designRevisionForProdID->production_id = $production_data->business_id;
                $designRevisionForProdID->production_id = $production_data->business_details_id;
                $designRevisionForProdID->reject_reason_prod = $request->reject_reason_prod;
                $designRevisionForProdID->remark_by_design = '';
                $designRevisionForProdID->save();

            } else {
                $designRevisionForProdIDInsert = new DesignRevisionForProd();
                $designRevisionForProdIDInsert->business_id = $production_data->business_id;
                $designRevisionForProdIDInsert->business_details_id = $production_data->business_details_id;
                $designRevisionForProdIDInsert->design_id = $production_data->design_id;
                $designRevisionForProdIDInsert->production_id = $production_data->business_id;
                $designRevisionForProdIDInsert->production_id = $production_data->business_details_id;
                $designRevisionForProdIDInsert->reject_reason_prod = $request->reject_reason_prod;
                $designRevisionForProdIDInsert->remark_by_design = '';
                $designRevisionForProdIDInsert->save();

            }

            $business_application = BusinessApplicationProcesses::where('business_details_id', $production_data->business_details_id)->first();
            
            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
                // $business_application->design_id = $production_data->design_id;
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
                // $business_application->production_id =  $production_data->id;
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED');
                $business_application->off_canvas_status = 13;
                $business_application->save();

                 // Update admin view and notification status with the new off canvas status
            $update_data_admin['off_canvas_status'] = 13;
            $update_data_business['off_canvas_status'] = 13;
            $update_data_admin['is_view'] = '0';
            AdminView::where('business_details_id', $production_data->business_details_id)
                ->update($update_data_admin);

            NotificationStatus::where('business_details_id', $production_data->business_details_id)
                ->update($update_data_business);
            }

        } catch (\Exception $e) {
            return $e;
        }
    }     
    // public function rejectdesign($request){
    //     try {
    //         $idtoedit = base64_decode($request->business_id);
           
    //         // $idtoedit = BusinessApplicationProcesses::where('business_details_id', $id)->first();
    //         $production_data = ProductionModel::where('business_details_id', $idtoedit)->first();
    //         $designRevisionForProdID = DesignRevisionForProd::where('id', $production_data->id)->orderBy('id','desc')->first();
    //         if($designRevisionForProdID) {

    //             $designRevisionForProdID->business_id = $production_data->business_id;
    //             $designRevisionForProdID->business_details_id = $production_data->business_details_id;
    //             $designRevisionForProdID->design_id = $production_data->design_id;
    //             $designRevisionForProdID->production_id = $production_data->business_id;
    //             $designRevisionForProdID->production_id = $production_data->business_details_id;
    //             $designRevisionForProdID->reject_reason_prod = $request->reject_reason_prod;
    //             $designRevisionForProdID->remark_by_design = '';
    //             $designRevisionForProdID->save();

    //         } else {
    //             $designRevisionForProdIDInsert = new DesignRevisionForProd();
    //             $designRevisionForProdIDInsert->business_id = $production_data->business_id;
    //             $designRevisionForProdIDInsert->business_details_id = $production_data->business_details_id;
    //             $designRevisionForProdIDInsert->design_id = $production_data->design_id;
    //             $designRevisionForProdIDInsert->production_id = $production_data->business_id;
    //             $designRevisionForProdIDInsert->production_id = $production_data->business_details_id;
    //             $designRevisionForProdIDInsert->reject_reason_prod = $request->reject_reason_prod;
    //             $designRevisionForProdIDInsert->remark_by_design = '';
    //             $designRevisionForProdIDInsert->save();

    //         }

    //         $business_application = BusinessApplicationProcesses::where('business_details_id', $production_data->business_details_id)->first();
            
    //         if ($business_application) {
    //             $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
    //             // $business_application->design_id = $production_data->design_id;
    //             $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
    //             // $business_application->production_id =  $production_data->id;
    //             $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED');
    //             $business_application->off_canvas_status = 13;
    //             $business_application->save();

    //              // Update admin view and notification status with the new off canvas status
    //         $update_data_admin['off_canvas_status'] = 13;
    //         $update_data_business['off_canvas_status'] = 13;
    //         $update_data_admin['is_view'] = '0';
    //         AdminView::where('business_details_id', $production_data->business_details_id)
    //             ->update($update_data_admin);

    //         NotificationStatus::where('business_details_id', $production_data->business_details_id)
    //             ->update($update_data_business);
    //         }

    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }     
    // public function acceptProductionCompleted($id, $completed_quantity) {
    //     try {
    //         // Fetch the business application process record for the given business ID
    //         $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
    //         if ($business_application) {
    //             $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN');
    //             $business_application->off_canvas_status = 18; 
    //             $business_application->save();

    //             // Update admin view and notification status with the new off canvas status
    //           $update_data_admin['off_canvas_status'] = 18;
    //           $update_data_admin['is_view'] = '0';
    //            $update_data_business['off_canvas_status'] = 18;
    //           AdminView::where('business_details_id', $business_application->business_details_id)
    //               // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
    //               ->update($update_data_admin);
  
    //           NotificationStatus::where('business_details_id', $business_application->business_details_id)
    //               // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
    //               ->update($update_data_business);
    
               

    //          // Track the completed quantity
    //          $quantity_tracking = new CustomerProductQuantityTracking();
    //          $quantity_tracking->business_id = $business_application->business_id;
    //          $quantity_tracking->business_details_id = $id;
    //          $quantity_tracking->production_id = $business_application->id;
    //          $quantity_tracking->completed_quantity = $completed_quantity; // Save the completed quantity
    //          $quantity_tracking->quantity_tracking_status = config('constants.PRODUCTION_DEPARTMENT.INPROCESS_COMPLETED_QUANLTITY_SEND_TO_LOGISTICS');
            
    //          $quantity_tracking->save();

    //          $dataOutput = Logistics::where('business_details_id', $id)->first();
          
    //          if (!$dataOutput) {
    //              $dataOutput = new Logistics;
    //          }
 
    //          $dataOutput->business_details_id = $id;
    //          $dataOutput->business_id = $business_application->business_id;
    //          $dataOutput->business_application_processes_id = $business_application->id;
    //          $dataOutput->quantity_tracking_id =  $quantity_tracking->id;
    //          // $dataOutput->quantity = $request->input('quantity');
    //          $dataOutput->is_approve = '0';
    //          $dataOutput->is_active = '1';
    //          $dataOutput->is_deleted = '0';
          
    //          $dataOutput->save();

    //          $dataOutputDispatch = Dispatch::where('business_details_id', $id)->first();
    //          if (!$dataOutputDispatch) {
    //              $dataOutputDispatch = new Dispatch;
    //          }
 
    //          $dataOutputDispatch->business_details_id = $id;
    //          $dataOutputDispatch->business_id = $business_application->business_id;
    //          $dataOutputDispatch->logistics_id =  $dataOutput->id;
            
             
    //          $dataOutputDispatch->business_application_processes_id = $business_application->id;
    //          $dataOutputDispatch->is_approve = '0';
    //          $dataOutputDispatch->is_active = '1';
    //          $dataOutputDispatch->is_deleted = '0';
    //          $dataOutputDispatch->save();


             
    //             return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
    //         } else {
    //             // Return an error response if the record does not exist
    //             return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
    //         }
    //     } catch (\Exception $e) {
    //         // Return an error response if an exception occurs
    //         return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }
    // }
    public function acceptProductionCompleted($id, $completed_quantity) {
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
                   'logistics_id'=>$logistics->id,
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
    
    public function editProduct($id) {
        try {
            $array_to_be_check = [
                config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')
            ];
    
            // Fetch all related data
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
                })
                // ->leftJoin('designs', function($join) {
                //     $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                // })
                ->leftJoin('businesses_details', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                // ->leftJoin('design_revision_for_prod', function($join) {
                //     $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                // })
                ->leftJoin('purchase_orders', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->leftJoin('production_details as pd', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'pd.business_details_id');
                })
                ->leftJoin('tbl_unit', 'pd.unit', '=', 'tbl_unit.id')  // Ensure this join is present
                
                // ->join('production_details', 'production_details.unit', '=', 'tbl_unit.id')
                ->where('businesses_details.id', $id)
                // ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
                ->where('businesses_details.is_active', true)
                ->select(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'pd.part_item_id',
                    'pd.quantity',
                    'pd.unit',
                    'tbl_unit.name as unit_name',  // Ensure tbl_unit.name exists in the table
                    'pd.business_details_id',
                    'pd.material_send_production',
                    // 'designs.bom_image',
                    // 'designs.design_image',
                    'business_application_processes.store_material_sent_date'
                )
                ->get(); 
    
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
    // public function editProduct($id) {
    //     try {
    //         $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //             })
    //             ->leftJoin('businesses_details', function ($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    //             })
    //             ->leftJoin('production_details as pd', function ($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'pd.business_details_id');
    //             })
    //             ->leftJoin('tbl_unit', 'pd.unit', '=', 'tbl_unit.id')
    //             ->where('businesses_details.id', $id)
    //             ->where('businesses_details.is_active', true)
    //             ->select(
    //                 'businesses_details.id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.description',
    //                 \DB::raw('MAX(pd.id) as pd_id'),
    //                 'pd.part_item_id',
    //                  'pd.quantity',
    //                 // \DB::raw('SUM(pd.quantity) as total_quantity'), // Aggregate quantity
    //                 'pd.unit',
    //                 'tbl_unit.name as unit_name',
    //                 'pd.business_details_id',
    //                 'pd.material_send_production',
    //                 'business_application_processes.store_material_sent_date'
    //             )
    //             ->groupBy(
    //                 'businesses_details.id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.description',
    //                 'pd.part_item_id',
    //                 // 'pd.id',
    //                        'pd.quantity',
    //                 'pd.unit',
    //                 'tbl_unit.name',
    //                 'pd.business_details_id',
    //                 'pd.material_send_production',
    //                 'business_application_processes.store_material_sent_date'
    //             )
    //             ->get();
    
    //         return [
    //             'productDetails' => $dataOutputByid->first(),
    //             'dataGroupedById' => $dataOutputByid->groupBy('business_details_id')
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'status' => 'error',
    //             'msg' => $e->getMessage()
    //         ];
    //     }
    // }
    
    
    public function editProductQuantityTracking($id) {
        try {
            $array_to_be_check = [
                config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')
            ];
    
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
                ->where('businesses_details.id', $id)
                // ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
                ->where('businesses_details.is_active', true)
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
                    'business_application_processes.store_material_sent_date'
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
    public function destroyAddmoreStoreItem($id){
        try {
            $deleteDataById = ProductionDetails::find($id);
            // dd($deleteDataById);
            // die();
            $deleteDataById->delete();
            return $deleteDataById;
        
        } catch (\Exception $e) {
            return $e;
        }    }
// public function updateProductMaterial($request) {
//     try {
      
//         // Fetch existing production details based on the business_details_id
//         $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $request->business_details_id)->get();

//         $dataOutput_Production = ProductionModel::where('business_details_id', $request->business_details_id)->firstOrFail();
//         $dataOutput_Production->store_status_quantity_tracking = 'incomplete-store';
//         $dataOutput_Production->save();
    
//         foreach ($request->addmore as $item) {
//             if (isset($item['part_item_id']) && !empty($item['part_item_id'])) {
//                 if (isset($item['id']) && $item['id'] != '') {
//                     $dataOutput = ProductionDetails::find($item['id']);
//                     if ($dataOutput) {
//                         $dataOutput->part_item_id = $item['part_item_id'];
//                         $dataOutput->quantity = $item['quantity'] ?? 0; // Provide a default value if not set
//                         $dataOutput->unit = $item['unit'] ?? ''; // Provide a default value if not set
//                         $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
//                         $dataOutput->save();
//                     }
//                 } else {
//                     // Create new ProductionDetails if 'id' is not present
//                     $dataOutput = new ProductionDetails();
//                     $dataOutput->part_item_id = $item['part_item_id'];
//                     $dataOutput->quantity = $item['quantity'] ?? 0; // Provide a default value if not set
//                     $dataOutput->unit = $item['unit'] ?? ''; // Provide a default value if not set
//                     $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;

//                     // Set the necessary relationships
//                     $dataOutput->business_id = $dataOutput_ProductionDetails->first()->business_id;
//                     $dataOutput->design_id = $dataOutput_ProductionDetails->first()->design_id;
//                     $dataOutput->business_details_id = $dataOutput_ProductionDetails->first()->business_details_id;
//                     $dataOutput->production_id = $dataOutput_ProductionDetails->first()->production_id;
//                     // dd($dataOutput);
//                     // die();
                    
                    
//                     $dataOutput->save();
//                     $update_data_admin['off_canvas_status'] = 18;
//                     $update_data_admin['is_view'] = '0';
//                     $update_data_business['off_canvas_status'] = 18;
                    
//                     AdminView::where('business_id', $dataOutput_ProductionDetails->business_id)
//                         ->where('business_details_id', $id)
//                         ->update($update_data_admin);
                       
    
//                     NotificationStatus::where('business_id', $dataOutput_ProductionDetails->business_id)
//                         ->where('business_details_id', $id)
//                         ->update($update_data_business);
//                 }
//             } else {
                
//             }
//         }
//             return [
//             'status' => 'success',
//             'message' => 'Production materials updated successfully.',
//             'updated_details' => $request->all()
//         ];
//     } catch (\Exception $e) {
//         return [
//             'status' => 'error',
//             'message' => 'Failed to update production materials.',
//             'error' => $e->getMessage()
//         ];
//     }
// }
public function updateProductMaterial($request) {
    try {
        // Fetch existing production details based on the business_details_id
        $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $request->business_details_id)->get();

        $dataOutput_Production = ProductionModel::where('business_details_id', $request->business_details_id)->firstOrFail();
        $dataOutput_Production->store_status_quantity_tracking = 'incomplete-store';
        $dataOutput_Production->save();

        foreach ($request->addmore as $item) {
            if (isset($item['part_item_id']) && !empty($item['part_item_id'])) {
                if (isset($item['id']) && $item['id'] != '') {
                    // Update existing record
                    $dataOutput = ProductionDetails::find($item['id']);
                    if ($dataOutput) {
                        $dataOutput->part_item_id = $item['part_item_id'];
                        $dataOutput->quantity = $item['quantity'] ?? 0;
                        $dataOutput->unit = $item['unit'] ?? '';
                        $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
                        $dataOutput->save();

                       
                    }
                } else {
                    // Create new ProductionDetails record for each row
                    $newRow = new ProductionDetails();
                    $newRow->part_item_id = $item['part_item_id'];
                    $newRow->quantity = $item['quantity'] ?? 0;
                    $newRow->unit = $item['unit'] ?? '';
                    $newRow->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
                    $newRow->quantity_minus_status = 'pending';
                    // Set the necessary relationships
                    $newRow->business_id = $dataOutput_ProductionDetails->first()->business_id ?? null;
                    $newRow->design_id = $dataOutput_ProductionDetails->first()->design_id ?? null;
                    $newRow->business_details_id = $request->business_details_id;
                    $newRow->production_id = $dataOutput_ProductionDetails->first()->production_id ?? null;

                    $newRow->save();
                }
            }
        }

        return [
            'status' => 'success',
            'message' => 'Production materials updated successfully.',
            'updated_details' => $request->all()
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Failed to update production materials.',
            'error' => $e->getMessage()
        ];
    }
}

    
}