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
    RejectedChalan
};
use Config;

class RejectedChalanRepository
{

    public function getAll()
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
            ->get();                
            return $dataOutputCategory;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getDetailsForPurchase($id)
    {
        $data_output = PurchaseOrdersModel::where('id', '=', $id)->first();
       
        return $data_output;
    }
    public function storeRejectedChalan($request)
    {
        try {
            // Find existing record
            $dataOutput = RejectedChalan::first();
        
            $dataOutput->purchase_orders_id = $request->purchase_orders_id;
            $dataOutput->grn_id = $dataOutput->grn_id;
            $dataOutput->chalan_no = $request->chalan_no;
            $dataOutput->reference_no = $request->reference_no;
            $dataOutput->remark = $request->remark;
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
            $dataOutput->save();
    
            return [
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    
}