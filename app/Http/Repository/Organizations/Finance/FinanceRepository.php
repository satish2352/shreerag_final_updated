<?php
namespace App\Http\Repository\Organizations\Finance;

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
    NotificationStatus
};
use Config;

class FinanceRepository
{

    // public function forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id)
    // {
    //     try {
    //           $purchase_order = PurchaseOrderModel::where('purchase_orders_id', $purchase_orders_id)->first();
    //           $business_application = BusinessApplicationProcesses::where('business_id', $business_id)->first();
            
           
    //           if ( $business_application  &&  $purchase_order) {
    //             // Update the business application statuses and dates
    //             $business_application->business_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY');
                
    //             // Update the purchase order statuses, receipt number, and dates
    //             $purchase_order->purchase_orders_id = $purchase_orders_id;
    //             // Save the updated business application and purchase order
    //             $business_application->save();
    //             $purchase_order->save();

    //           }
    //        return "ok";
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // }

    public function forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id)
    {
        try {
           
            $purchase_order = PurchaseOrderModel::where('purchase_orders_id', $purchase_orders_id)->first();
            
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();
        
            if ($business_application && $purchase_order) {
                // Update the business application statuses and dates
                $business_application->business_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY');
                $purchase_order->purchase_orders_id = $purchase_orders_id;
                // Save the updated business application and purchase order
                $business_application->save();
                $purchase_order->save();
                return ['business_application' => $business_application, 'purchase_order' => $purchase_order];
            }
    
            return "No matching records found.";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function forwardedPurchaseOrderPaymentToTheVendor($purchase_order_id, $business_id)
    {
        try {
            $purchase_order = PurchaseOrderModel::where('purchase_orders_id', $purchase_order_id)->first();
           
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();
            if ( $business_application  &&  $purchase_order) {
                $business_application->purchase_order_id = $purchase_order_id;
                $business_application->business_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_PAID_AGAINST_PO');
                // $business_application->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_PAID_AGAINST_PO');
               
                $purchase_order->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_PAID_AGAINST_PO');
                $business_application->save();
                $purchase_order->save();               
            }
            return "ok";
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function sendToDispatch($id) {
        try {
          
            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
            if ($business_application) {
                $business_application->dispatch_status_id = config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT');
                $business_application->off_canvas_status = 21;

                $business_application->save();

            // $update_data_admin['current_department'] = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_FIRST_TIME');
            $update_data_admin['off_canvas_status'] = 21;
            $update_data_business['off_canvas_status'] = 21;
            $update_data_admin['is_view'] = '0';
            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
                NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);

    
                return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    // public function addAll($request)
    // {
    //     try {
    //         $production_id = base64_decode($request->production_id);
    //         $business_application = BusinessApplicationProcesses::where('production_id', $production_id)->first();
    //         $dataOutput = new Requisition();
    //         $dataOutput->business_id = $business_application->business_id;
    //         $dataOutput->design_id = $business_application->design_id;
    //         $dataOutput->production_id = $business_application->production_id;
    //         $dataOutput->req_name = "";
    //         $dataOutput->req_date = date('Y-m-d');
    //         $dataOutput->bom_file = 'null';
    //         $dataOutput->save();
    //         $last_insert_id = $dataOutput->id;

    //         // Updating image name in requisition
    //         $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_bom_reisition_for_purchase_image.' . $request->bom_file_req->getClientOriginalExtension();
    //         $finalOutput = Requisition::find($last_insert_id);
    //         $finalOutput->bom_file = $imageName;
    //         $finalOutput->save();

    //         if ($business_application) {
    //             $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE');
    //             // $business_application->design_id = $dataOutput->id;
    //             $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
    //             // $business_application->production_id = $production_data->id;
    //             $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.BOM_SENT_TO_STORE_DEPT_FOR_CHECKING');
    //             $business_application->store_status_id = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');
    //             $business_application->requisition_id = $last_insert_id;
    //             $dataOutput->purchase_dept_req_sent_date = date('Y-m-d');
    //             $business_application->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE');
    //             $business_application->save();

    //         }

    //         return [
    //             'ImageName' => $imageName,
    //             'status' => 'success'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => $e->getMessage(),
    //             'status' => 'error'
    //         ];
    //     }
    // }



    // public function genrateStoreReciptAndForwardMaterialToTheProduction($id)
    // {
    //     try {

    //         $business_application = BusinessApplicationProcesses::where('business_id', $id)->first();
    //         $store_receipt_no  =  str_replace(array("-", ":"), "", date('Y-m-d') . time());
    //         if ($business_application) {
    //             $business_application->business_id = $id;

    //             $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
    //             $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
    //             $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION');
    //             $business_application->store_material_sent_date = date('Y-m-d');
    //             $business_application->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');


    //             $business_application->store_receipt_no = $store_receipt_no;
    //             $business_application->finanace_store_receipt_generate_date = date('Y-m-d');
    //             $business_application->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT');


    //             $business_application->save();

    //         }

    //         return "ok";
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

}