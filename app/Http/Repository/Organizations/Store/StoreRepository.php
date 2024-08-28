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
    
};
use Config;

class StoreRepository
{

    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {
            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
            if ($business_application) {
                //  $business_application->business_details_id = $id;

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
           
            $business_application = BusinessApplicationProcesses::where('production_id', $production_id)->first();
           
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
            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_bom_reisition_for_purchase_image.' . $request->bom_file_req->getClientOriginalExtension();
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


                $update_data_admin['current_department'] = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');
                $update_data_admin['is_view'] = '0';
            AdminView::where('business_id', $business_application->business_id)
                        ->update($update_data_admin);

            }
            // PurchaseOrderModel::where('business_id', $business_application->business_id)->update(['purchase_status_from_purchase', config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')]);
        
            return [
                'ImageName' => $imageName,
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }



    // public function genrateStoreReciptAndForwardMaterialToTheProduction($id)
    // {
    //     try {

    //         $purchase_order = PurchaseOrderModel::where('production_id', $id)->first();
    //         $business_application = BusinessApplicationProcesses::where('production_id', $id)->first();
    //         $store_receipt_no  =  str_replace(array("-", ":"), "", date('Y-m-d') . time());
    //         if ($business_application) {
    //             // $business_application->business_id = $id;

    //             $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
    //             $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
    //             $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION');
    //             $business_application->store_material_sent_date = date('Y-m-d');
    //             $purchase_order->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');


    //             $purchase_order->store_receipt_no = $store_receipt_no;
    //             $purchase_order->finanace_store_receipt_generate_date = date('Y-m-d');
    //             $purchase_order->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT');


    //             $business_application->save();

    //         }

    //         return "ok";
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
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
    
    // public function editProduct($id){
    //     try {
    //         $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')];
    
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

    //             ->leftJoin('production_details', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
    //             })
    //             ->where('businesses_details.id', $id)
    //             ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
    //             ->where('businesses_details.is_active', true)
    //             ->distinct('businesses_details.id')
    //             ->select(
    //                 'businesses_details.id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.quantity',
    //                 'businesses_details.description',
    //                 'production_details.part_item_id',
    //                 'production_details.quantity',
    //                 'production_details.unit',
    //                 'businesses_details.is_active',
    //                 'production.business_details_id',
    //                 // 'design_revision_for_prod.reject_reason_prod',
    //                 // 'design_revision_for_prod.id as design_revision_for_prod_id',
    //                 'designs.bom_image',
    //                 'designs.design_image',
    //                 'business_application_processes.store_material_sent_date'
    //             )
    //             ->first();
    // dd($dataOutputByid);
    // die();
    //         return $dataOutputByid ?: null;
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => $e->getMessage(),
    //             'status' => 'error'
    //         ];
    //     }
    // }

    public function editProduct($id){
        try {
            $array_to_be_check = [
                config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')
            ];
    
            // Modify the query to get all matching records
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
                ->groupBy( 'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                   'production_details.business_details_id',
                'production_details.part_item_id',
                'production_details.quantity',
                'production_details.unit',
                'businesses_details.is_active',
                'production.business_details_id',
                'designs.bom_image',
                'designs.design_image',
                'business_application_processes.store_material_sent_date'
                        )
                ->select(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'production_details.business_details_id',
                    'production_details.part_item_id',
                    'production_details.quantity',
                    'production_details.unit',
                    'businesses_details.is_active',
                    'production.business_details_id',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date'
                )
                ->get(); // Use get() to fetch all results
    dd($dataOutputByid);
    die();
            return $dataOutputByid ?: null;
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    
    public function updateProductMaterial($request) {
        try {
            $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $request->business_details_id)->firstOrFail();
            
            // Remove existing records related to the business_details_id before saving new ones
            ProductionDetails::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)->delete();
    
            // Loop through the addmore array and update or create new ProductionDetails
            foreach ($request->addmore as $item) {
                $dataOutput = new ProductionDetails();
                $dataOutput->part_item_id = $item['part_no_id'];
                $dataOutput->quantity = $item['quantity'];
                $dataOutput->unit = $item['unit'];
                $dataOutput->business_id = $dataOutput_ProductionDetails->business_id;
                $dataOutput->design_id = $dataOutput_ProductionDetails->design_id;
                $dataOutput->business_details_id = $dataOutput_ProductionDetails->business_details_id;
                $dataOutput->production_id = $dataOutput_ProductionDetails->production_id;
                $dataOutput->save();
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
            return [
                'status' => 'error',
                'message' => 'Failed to update production materials.',
                'error' => $e->getMessage()
            ];
        }
    }
}