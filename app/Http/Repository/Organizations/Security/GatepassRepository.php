<?php
namespace App\Http\Repository\Organizations\Security;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    Gatepass,
    PurchaseOrderModel,
    BusinessApplicationProcesses
};
use Config;

class GatepassRepository
{

    public function getAll()
    {
        try {
            $data_output = Gatepass::get();

            return $data_output;
        } catch (\Exception $e) {

            return $e;
        }
    }


    public function addAll($request)
    {
        try {

            $dataOutput = new Gatepass();
            $dataOutput->purchase_orders_id = $request->purchase_orders_id;
            $dataOutput->gatepass_name = $request->gatepass_name;
            $dataOutput->gatepass_date = $request->gatepass_date;
            $dataOutput->gatepass_time = $request->gatepass_time;
            $dataOutput->remark = $request->remark;
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;


            $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
            // $business_application = BusinessApplicationProcesses::where('business_id', $purchase_orders_details->business_id)->first();
            if ($purchase_orders_details) {
                $purchase_orders_details->store_material_recived_for_grn_date = date('Y-m-d');
                $purchase_orders_details->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $purchase_orders_details->security_material_recived_date = date('Y-m-d');
                $purchase_orders_details->security_status_id = config('constants.QUALITY_DEPARTMENT.LIST_PO_RECEIVED_FROM_SECURITY');
                $purchase_orders_details->save();
            }

          
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
            // Update main design data
            $dataOutput = Gatepass::findOrFail($request->id);
            // Updating fields
            $dataOutput->purchase_id = $request->purchase_id;
            $dataOutput->gatepass_name = $request->gatepass_name;
            $dataOutput->gatepass_date = $request->gatepass_date;
            $dataOutput->gatepass_time = $request->gatepass_time;
            $dataOutput->remark = $request->remark;
    
            $dataOutput->save();
    
            // Returning success message
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