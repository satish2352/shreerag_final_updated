<?php
namespace App\Http\Repository\Organizations\Quality;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    GRNModel,
    PurchaseOrderDetailsModel,
    Gatepass,
    PurchaseOrderModel,
    BusinessApplicationProcesses,
    RejectedChalan,
    PurchaseOrdersModel
    
};
use Config;

class GRNRepository
{

    public function getAll()
    {
        try {
            $data_output = Gatepass::where('is_checked_by_quality',false)->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getDetailsForPurchase($id)
    {
        return PurchaseOrdersModel::where('id', '=', $id)->first();
    }
    // repository
    public function storeGRN($request)
    {
        try {
            $grn_no = str_replace(array("-", ":"), "", date('Y-m-d') . time());
            $dataOutput = new GRNModel();
            $dataOutput->purchase_orders_id = $request->purchase_orders_id;
            // $dataOutput->grn_no = $grn_no;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->grn_date = $request->grn_date;
            $dataOutput->remark = $request->remark;
            $dataOutput->image = 'null';
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
     
            foreach ($request->addmore as $index => $item) {
                $user_data = PurchaseOrderDetailsModel::where('id', $item['edit_id'])
                    ->update([
                        // 'qc_check_remark' => $item['qc_check_remark'],
                        'actual_quantity' => $item['actual_quantity'],
                        'accepted_quantity' => $item['accepted_quantity'],
                        'rejected_quantity' => $item['rejected_quantity'],
                    ]);
            }
            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
            $finalOutput = GRNModel::find($last_insert_id);
            $finalOutput->image = $imageName;
            $finalOutput->save();

         
            
            $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
          
           
            $business_application = PurchaseOrderModel::where('purchase_orders_id', $purchase_orders_details->purchase_orders_id)->first();
          

            if ($business_application) {
                $business_application->grn_no = $grn_no;
                $business_application->quality_material_sent_to_store_date = date('Y-m-d');
                $business_application->quality_status_id = config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE');
                $business_application->save();               
            }

          
            $updateGatepassTable = Gatepass::where('purchase_orders_id',$request->purchase_orders_id)->first();
            $updateGatepassTable->is_checked_by_quality = true;
            $updateGatepassTable->save();
            $rejected_chalan_data = new RejectedChalan();
            $rejected_chalan_data->purchase_orders_id = $request->purchase_orders_id;
            $rejected_chalan_data->grn_id = $dataOutput->id;
            $rejected_chalan_data->chalan_no = '';
            $rejected_chalan_data->reference_no = '';
            $rejected_chalan_data->remark = '';
            $rejected_chalan_data->save();

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

    public function getAllListMaterialSentFromQualityBusinessWise($id)
{
    try {
        $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

        $data_output = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
        ->leftJoin('businesses_details', function($join) {
            $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
        })
        ->distinct('businesses_details.id')  
        ->select(
            'purchase_orders.id',
            'purchase_orders.purchase_orders_id',         
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
            'purchase_orders.is_active'
        )
        ->where('purchase_orders.business_details_id', $id)
        ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
        ->get(); // Added to execute the query and get results
       
        return $data_output;
    } catch (\Exception $e) {
        return $e->getMessage(); // Changed to return the error message string
    }
}


public function getAllRejectedChalanList()
{
    try {
        
        $dataOutputCategory = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
        ->select(
            'tbl_rejected_chalan.purchase_orders_id',
            'grn_tbl.po_date', 
            'grn_tbl.grn_date', 
            'grn_tbl.remark', 
            'tbl_rejected_chalan.is_active'
        )
        ->groupBy('tbl_rejected_chalan.purchase_orders_id', 'grn_tbl.po_date', 'grn_tbl.grn_date', 'grn_tbl.remark', 'tbl_rejected_chalan.is_active')
        ->orderBy('tbl_rejected_chalan.purchase_orders_id', 'desc')
        ->whereNotNull('tbl_rejected_chalan.chalan_no')
        ->get();                
        return $dataOutputCategory;
    } catch (\Exception $e) {
        return $e;
    }
}
}