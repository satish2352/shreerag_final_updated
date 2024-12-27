<?php
namespace App\Http\Repository\Organizations\Store;

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
};
use Config;

class RejectedChalanRepository
{

    public function getAll()
    {
        try {
            // $dataOutputCategory = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
            $dataOutputCategory = GRNModel::join('purchase_orders', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')

            
            ->leftJoin('tbl_rejected_chalan', function ($join) {
                $join->on('grn_tbl.id', '=', 'tbl_rejected_chalan.grn_id');
            })
            ->leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
            ->where('tbl_rejected_chalan.chalan_no','') 
            ->select(
                'grn_tbl.id',
                'tbl_rejected_chalan.purchase_orders_id',
                'grn_tbl.po_date', 
                'grn_tbl.grn_date', 
                'grn_tbl.remark', 
                'tbl_rejected_chalan.grn_id',
                'gatepass.gatepass_name',
                'tbl_rejected_chalan.is_active'
            )
            ->groupBy( 'grn_tbl.id','tbl_rejected_chalan.purchase_orders_id', 'grn_tbl.po_date', 'grn_tbl.grn_date', 'grn_tbl.remark',
            'tbl_rejected_chalan.grn_id','gatepass.gatepass_name', 'tbl_rejected_chalan.is_active')
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
           $grn_id = $request->id;
        //    dd($grn_id);
        //    die();
            // Find existing record
            $dataOutput = RejectedChalan::where('tbl_rejected_chalan.id', $grn_id)->first();
        
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

    public function getAllRejectedChalanList()
    {
        try {

            $dataOutputCategory = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
            ->leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
            ->where('tbl_rejected_chalan.chalan_no', '<>', '') 
            ->select(
                'tbl_rejected_chalan.id',
                'tbl_rejected_chalan.purchase_orders_id',
                'grn_tbl.po_date', 
                'grn_tbl.grn_date', 
                'grn_tbl.remark', 
                'gatepass.gatepass_name',
                'tbl_rejected_chalan.is_active'
            )
            ->groupBy( 'tbl_rejected_chalan.id','tbl_rejected_chalan.purchase_orders_id', 'grn_tbl.po_date', 'grn_tbl.grn_date', 'grn_tbl.remark',
            'gatepass.gatepass_name', 'tbl_rejected_chalan.is_active')
            ->orderBy('tbl_rejected_chalan.purchase_orders_id', 'desc')

            ->get();                
            return $dataOutputCategory;
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    public function getAllRejectedChalanDetailsList($purchase_orders_id, $id)
    {
        try {
            $dataOutputCategory = GRNModel::join('purchase_orders', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
            ->leftJoin('tbl_rejected_chalan', function ($join) {
                $join->on('grn_tbl.id', '=', 'tbl_rejected_chalan.grn_id');
            })
            ->leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
            ->where('tbl_rejected_chalan.id', $id)
            ->select(
                'grn_tbl.id',
                'tbl_rejected_chalan.purchase_orders_id',
                'grn_tbl.po_date', 
                'grn_tbl.grn_date', 
                'grn_tbl.remark', 
                'tbl_rejected_chalan.grn_id',
                'tbl_rejected_chalan.chalan_no',
                'tbl_rejected_chalan.reference_no',
                'tbl_rejected_chalan.remark',
                'gatepass.gatepass_name',
                'purchase_orders.grn_no',
                'tbl_rejected_chalan.is_active'
            )
            ->groupBy( 'grn_tbl.id','tbl_rejected_chalan.purchase_orders_id', 'grn_tbl.po_date', 'grn_tbl.grn_date', 'grn_tbl.remark',
            'tbl_rejected_chalan.chalan_no',
            'tbl_rejected_chalan.reference_no',
            'purchase_orders.grn_no',
            'tbl_rejected_chalan.remark', 'tbl_rejected_chalan.grn_id','gatepass.gatepass_name', 'tbl_rejected_chalan.is_active')
            ->orderBy('tbl_rejected_chalan.purchase_orders_id', 'desc')
            ->get();  



            // $dataOutputCategory = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
            // ->select(
            //     'tbl_rejected_chalan.id',
            //     'tbl_rejected_chalan.purchase_orders_id',
            //     'grn_tbl.po_date', 
            //     'grn_tbl.grn_date', 
            //     'grn_tbl.remark', 
            //     'tbl_rejected_chalan.is_active'
            // )
            // ->groupBy( 'tbl_rejected_chalan.id','tbl_rejected_chalan.purchase_orders_id', 'grn_tbl.po_date', 'grn_tbl.grn_date', 'grn_tbl.remark', 'tbl_rejected_chalan.is_active')
            // ->orderBy('tbl_rejected_chalan.purchase_orders_id', 'desc')
            // ->where('tbl_rejected_chalan.id', $id) 
            // ->where('tbl_rejected_chalan.chalan_no', '<>', '') 
            // ->get();                
            return $dataOutputCategory;
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}