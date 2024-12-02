<?php
namespace App\Http\Repository\Organizations\Security;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    BusinessApplicationProcesses,
    PurchaseOrdersModel,
    AdminView,
    NotificationStatus,
    Gatepass,
    PurchaseOrderModel
};
use Config;

class GatepassRepository
{

    // public function getAll()
    // {
    //     try {
    //         $data_output = Gatepass::get();

    //         return $data_output;
    //     } catch (\Exception $e) {

    //         return $e;
    //     }
    // }

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

            ->orderBy('gatepass.updated_at', 'desc') // Sorting by gatepass table's updated_at

            ->get();

    

            return $data_output;

        } catch (\Exception $e) {

            return $e;

        }

    }
   
    public function addAll($request)
    {
        try {
            // Fetch purchase order details
            $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
    
            if (!$purchase_orders_details) {
                return [
                    'msg' => 'Purchase Order not found.',
                    'status' => 'error'
                ];
            }
    
            // Create a new Gatepass entry
            $dataOutput = new Gatepass();
            $dataOutput->purchase_orders_id = $request->purchase_orders_id;
            $dataOutput->gatepass_name = $request->gatepass_name;
            $dataOutput->gatepass_date = $request->gatepass_date;
            $dataOutput->gatepass_time = $request->gatepass_time;
            $dataOutput->remark = $request->remark;
            $dataOutput->po_tracking_status = 4001;
    
            // Determine the next tracking_id
            $lastTrackingStatus = Gatepass::where('purchase_orders_id', $request->purchase_orders_id)
                ->max('tracking_id'); // Get the maximum tracking_id
            $dataOutput->tracking_id = $lastTrackingStatus ? $lastTrackingStatus + 1 : 1; // Increment or start from 1
    
            $dataOutput->business_details_id = $purchase_orders_details->business_details_id;
    
            // Save the Gatepass record
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            $po_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
           
            $po_details->security_material_recived_date = date('Y-m-d');
            $po_details->security_status_id = config('constants.QUALITY_DEPARTMENT.LIST_PO_RECEIVED_FROM_SECURITY');
            
           $business_application = BusinessApplicationProcesses::where('business_details_id', $purchase_orders_details->business_details_id)->first();
        
           if ($business_application) {
                $business_application->off_canvas_status = 26;
                $business_application->save();

            }
    
            // Prepare data for AdminView and NotificationStatus tables
            $update_data_admin = [
                'off_canvas_status' => 26,
                'is_view' => '0'
            ];
    
            $update_data_business = [
                'off_canvas_status' => 26,
                'po_send_to_vendor_visible_security' => '0'
            ];
    
            // Update AdminView table
            AdminView::where('business_details_id', $purchase_orders_details->business_details_id)
                ->update($update_data_admin);
    
            // Update NotificationStatus table
            NotificationStatus::where('business_details_id', $purchase_orders_details->business_details_id)
                ->update($update_data_business);
    
            // Returning success message
            return [
                'msg' => 'Data Added Successfully',
                'status' => 'success'
            ];
    
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    
    public function getById($id){
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

            // Update main design data
            $dataOutput = Gatepass::findOrFail($request->id);
            $dataOutput->purchase_id = $request->purchase_id;
            $dataOutput->gatepass_name = $request->gatepass_name;
            $dataOutput->gatepass_date = $request->gatepass_date;
            $dataOutput->gatepass_time = $request->gatepass_time;
            $dataOutput->remark = $request->remark;
            $dataOutput->save();

            

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
            // Returning success message
            return [
                'msg' => 'Data updated successfully.',
                'status' => 'success',
                'designDetails' => $request->all()
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