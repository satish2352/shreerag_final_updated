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
NotificationStatus
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
            $designRevisionForProdID = DesignRevisionForProd::where('business_details_id', $business_application->business_details_id)->first();
            $dataOutput = DesignModel::where('business_details_id', $business_application->business_details_id)->first();
    
            if (!$dataOutput) {
                return [
                    'msg' => 'Design model not found',
                    'status' => 'error',
                ];
            }
    
            // Update design model images
            $dataOutput->design_image = $designRevisionForProdID->design_image;
            $dataOutput->bom_image = $designRevisionForProdID->bom_image;
            $dataOutput->save();
    
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
            $designRevisionForProdID = DesignRevisionForProd::where('id', $production_data->id)->orderBy('id','desc')->first();
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
            AdminView::where('business_details_id', $production_data->business_details_id)
                // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
                ->update($update_data_admin);

            NotificationStatus::where('business_details_id', $production_data->business_details_id)
                // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
                ->update($update_data_admin);
            }

        } catch (\Exception $e) {
            return $e;
        }
    }     
    public function acceptProductionCompleted($id) {
        try {
            // Fetch the business application process record for the given business ID
            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
            if ($business_application) {
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN');
                $business_application->off_canvas_status = 18; 
                $business_application->save();

                // Update admin view and notification status with the new off canvas status
              $update_data_admin['off_canvas_status'] = 18;
              $update_data_admin['is_view'] = '0';
               $update_data_business['off_canvas_status'] = 18;
              AdminView::where('business_details_id', $business_application->business_details_id)
                  // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
                  ->update($update_data_admin);
  
              NotificationStatus::where('business_details_id', $business_application->business_details_id)
                  // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
                  ->update($update_data_business);
    
                $dataOutput = Logistics::where('business_details_id', $id)->first();
                if (!$dataOutput) {
                    $dataOutput = new Logistics;
                }
    
                $dataOutput->business_details_id = $id;
                $dataOutput->business_id = $business_application->business_id;
                $dataOutput->business_application_processes_id = $business_application->id;
                // $dataOutput->quantity = $request->input('quantity');
                $dataOutput->is_approve = '0';
                $dataOutput->is_active = '1';
                $dataOutput->is_deleted = '0';
                $dataOutput->save();

                $dataOutputDispatch = Dispatch::where('business_details_id', $id)->first();
                if (!$dataOutputDispatch) {
                    $dataOutputDispatch = new Dispatch;
                }
    
                $dataOutputDispatch->business_details_id = $id;
                $dataOutputDispatch->business_id = $business_application->business_id;
                $dataOutputDispatch->logistics_id =  $dataOutput->id;
                $dataOutputDispatch->business_application_processes_id = $business_application->id;
                $dataOutputDispatch->is_approve = '0';
                $dataOutputDispatch->is_active = '1';
                $dataOutputDispatch->is_deleted = '0';
                $dataOutputDispatch->save();


             
             
                return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
            } else {
                // Return an error response if the record does not exist
                return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
            }
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
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
                ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
                ->where('businesses_details.is_active', true)
                ->select(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'production_details.part_item_id',
                    'production_details.quantity',
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
public function updateProductMaterial($request) {
    try {
        // Fetch existing production details based on the business_details_id
        $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $request->business_details_id)->get();

        // Loop through the addmore array
        foreach ($request->addmore as $item) {
            // Check if part_item_id is set and not empty
            if (isset($item['part_item_id']) && !empty($item['part_item_id'])) {
                if (isset($item['id']) && $item['id'] != '') {
                    // Update existing ProductionDetails if 'id' is present
                    $dataOutput = ProductionDetails::find($item['id']);
                    if ($dataOutput) {
                        // Update fields
                        $dataOutput->part_item_id = $item['part_item_id'];
                        $dataOutput->quantity = $item['quantity'] ?? 0; // Provide a default value if not set
                        $dataOutput->unit = $item['unit'] ?? ''; // Provide a default value if not set
                        $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
                        $dataOutput->save();
                    }
                } else {
                    // Create new ProductionDetails if 'id' is not present
                    $dataOutput = new ProductionDetails();
                    $dataOutput->part_item_id = $item['part_item_id'];
                    $dataOutput->quantity = $item['quantity'] ?? 0; // Provide a default value if not set
                    $dataOutput->unit = $item['unit'] ?? ''; // Provide a default value if not set
                    $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;

                    // Set the necessary relationships
                    $dataOutput->business_id = $dataOutput_ProductionDetails->first()->business_id;
                    $dataOutput->design_id = $dataOutput_ProductionDetails->first()->design_id;
                    $dataOutput->business_details_id = $dataOutput_ProductionDetails->first()->business_details_id;
                    $dataOutput->production_id = $dataOutput_ProductionDetails->first()->production_id;
                    $dataOutput->save();
                }
            } else {
                // Optionally handle the case where part_item_id is not set
                // You can log an error or throw an exception
            }
        }

        // Update the BusinessApplicationProcesses status
        $businessOutput = BusinessApplicationProcesses::where('business_details_id', $dataOutput_ProductionDetails->first()->business_details_id)
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
            'message' => 'Failed to update production materials.',
            'error' => $e->getMessage()
        ];
    }
}

    
}