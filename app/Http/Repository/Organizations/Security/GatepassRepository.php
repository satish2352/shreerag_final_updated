<?php
namespace App\Http\Repository\Organizations\Security;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    Gatepass,
    PurchaseOrderModel,
    BusinessApplicationProcesses,
    PurchaseOrdersModel,
    AdminView,
    NotificationStatus
};
use Config;

class GatepassRepository
{

    public function getAll()
    {
        try {
            $data_output = Gatepass::leftJoin('purchase_orders', function ($join) {
                $join->on('gatepass.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id');
              })
              ->select(
                'gatepass.*',     
                'purchase_orders.quality_status_id', // Replace with the fields you need from purchase_orders
            )
            // ->orderBy('gatepass.updated_at', 'desc') // Sorting by gatepass table's updated_at
            ->get();
    
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function addAll($request)
    {
        try {
            // Fetch the purchase order using purchase_orders_id
            $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
    
            // If no purchase order found, return an error
            if (!$purchase_orders_details) {
                return [
                    'msg' => 'Purchase order not found.',
                    'status' => 'error'
                ];
            }
     // Fetch the business details ID from the purchase order
     $business_details_id = $purchase_orders_details->business_details_id;

     // Fetch the business application process using business_details_id
     $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();

     if (!$business_application) {
         return [
             'msg' => 'Business Application not found.',
             'status' => 'error'
         ];
     }
            // Check if a gatepass already exists for the given purchase order
            $existingGatepass = Gatepass::where('purchase_orders_id', $request->purchase_orders_id)->first();
    
            if ($existingGatepass) {
                return [
                    'msg' => 'Gate pass already exists for this purchase order.',
                    'status' => 'error'
                ];
            }
    
            
            // Create a new gatepass entry
            $dataOutput = new Gatepass();
            $dataOutput->purchase_orders_id = $request->purchase_orders_id;
            $dataOutput->gatepass_name = $request->gatepass_name;
            $dataOutput->gatepass_date = $request->gatepass_date;
            $dataOutput->gatepass_time = $request->gatepass_time;
            $dataOutput->remark = $request->remark;
            $dataOutput->save();
    
            // Update purchase order details
            $purchase_orders_details->store_material_recived_for_grn_date = date('Y-m-d');
            $purchase_orders_details->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
            $purchase_orders_details->security_material_recived_date = date('Y-m-d');
            $purchase_orders_details->security_status_id = config('constants.QUALITY_DEPARTMENT.LIST_PO_RECEIVED_FROM_SECURITY');
            $purchase_orders_details->save();
    
            // Update the business application process's off_canvas_status
            $business_application->off_canvas_status = 26;
            $business_application->save();
    
            // Prepare data to update admin and notification statuses
            $update_data_admin['off_canvas_status'] = 26;
            $update_data_admin['is_view'] = '0';
            $update_data_business['off_canvas_status'] = 26;
            // Update AdminView table
            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
    
            // Update NotificationStatus table
            NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);
    
            return [
                'msg' => 'Data Added Successfully',
                'status' => 'success'
            ];
    
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return [
                'msg' => 'An error occurred: ' . $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    
    
   
    public function getById($id)
    {
        try {
            $dataOutputByid = Gatepass::find($id);
            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    
    public function updateAll($request)
    {
        try {
            // Find the Gatepass by ID
            $dataOutput = Gatepass::findOrFail($request->purchase_orders_id);
         
            // Update fields
            $dataOutput->purchase_orders_id = $request->purchase_orders_id;
            $dataOutput->gatepass_name = $request->gatepass_name;
            $dataOutput->gatepass_date = $request->gatepass_date;
            $dataOutput->gatepass_time = $request->gatepass_time;
            $dataOutput->remark = $request->remark;
          
            $dataOutput->save();
      
            // Return success message
            return [
                'msg' => 'Data updated successfully.',
                'status' => 'success',
                'designDetails' => $dataOutput
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update data.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    
    

    public function deleteById($id)
    {
        try {
            $deleteDataById = Gatepass::find($id);

            if ($deleteDataById) {
                // if (file_exists_view(Config::get('FileConstant.STORE_RECEIPT_DELETE') . $deleteDataById->image)){
                //     removeImage(Config::get('FileConstant.STORE_RECEIPT_DELETE') . $deleteDataById->image);
                // }
                $deleteDataById->delete();

                return $deleteDataById;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}