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
    PurchaseOrderModel
};
use Config;

class StoreRepository
{

    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {

            $business_application = BusinessApplicationProcesses::where('business_id', $id)->first();
            if ($business_application) {
                // $business_application->business_id = $id;

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



    public function genrateStoreReciptAndForwardMaterialToTheProduction($id)
    {
        try {

            $business_application = BusinessApplicationProcesses::where('production_id', $id)->first();
            $store_receipt_no  =  str_replace(array("-", ":"), "", date('Y-m-d') . time());
            if ($business_application) {
                // $business_application->business_id = $id;

                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION');
                $business_application->store_material_sent_date = date('Y-m-d');
                $business_application->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');


                $business_application->store_receipt_no = $store_receipt_no;
                $business_application->finanace_store_receipt_generate_date = date('Y-m-d');
                $business_application->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT');


                $business_application->save();

            }

            return "ok";
        } catch (\Exception $e) {
            return $e;
        }
    }

}