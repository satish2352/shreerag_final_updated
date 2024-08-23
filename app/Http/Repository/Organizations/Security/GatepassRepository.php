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
            $data_output = Gatepass::orderBy('updated_at', 'desc')->get();
    
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    public function addAll($request)
    {
        $existingGatepass = Gatepass::where('purchase_orders_id', $request->purchase_orders_id)->first();
        
        if ($existingGatepass) {
            return [
                'msg' => 'Gate pass already exists for this purchase order.',
                'status' => 'error'
            ];
        }

        
        $dataOutput = new Gatepass();
        $dataOutput->purchase_orders_id = $request->purchase_orders_id;
        $dataOutput->gatepass_name = $request->gatepass_name;
        $dataOutput->gatepass_date = $request->gatepass_date;
        $dataOutput->gatepass_time = $request->gatepass_time;
        $dataOutput->remark = $request->remark;
        $dataOutput->save();
        $last_insert_id = $dataOutput->id;

        $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
        
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
            // dd($request->purchase_orders_id);
            // die();
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