<?php

namespace App\Http\Controllers\Organizations\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\RejectedChalanServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    BusinessApplicationProcesses,
    Gatepass,
    RejectedChalan,
    GRNModel
};
use App\Http\Controllers\Organizations\CommanController;
class RejectedChalanController extends Controller
{
    public function __construct()
    {
        $this->service = new RejectedChalanServices();
        $this->serviceCommon = new CommanController();
    }



    public function index()
    {
        try {
            $all_gatepass = $this->service->getAll();
            // dd($all_gatepass);
            // die();
            return view('organizations.store.rejected-chalan.list-rejected-chalan', compact('all_gatepass'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function add($purchase_orders_id, $id)
    {
        try {
            $purchase_orders_id = base64_decode($purchase_orders_id);
       

            $rejected_id = base64_decode($id);
       
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $purchase_orders_id)->first();
            $po_id = $purchase_order_data->id;
            $purchase_order_details_data = PurchaseOrderDetailsModel::where('purchase_id', $po_id)
                ->get();
              
                $gatepass_data = GRNModel::leftJoin('tbl_rejected_chalan', function ($join) {
                    $join->on('grn_tbl.id', '=','tbl_rejected_chalan.grn_id');
                })
                ->leftJoin('gatepass', function ($join) {
                    $join->on('grn_tbl.gatepass_id', '=','gatepass.id');
                })
                ->where('tbl_rejected_chalan.purchase_orders_id', '=', $purchase_orders_id)->where('tbl_rejected_chalan.id', '=', $rejected_id)
                ->select(
                    'tbl_rejected_chalan.id',
                    'gatepass.gatepass_name',
                    'grn_tbl.grn_date'
                )
                ->first();
                // dd($gatepass_data);
                // die();
            return view('organizations.store.rejected-chalan.add-rejected-chalan', compact('purchase_order_data', 'purchase_order_details_data', 'gatepass_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'chalan_no' => 'required',
            'reference_no' => 'required',
            'remark' => 'required',
        ];

        $messages = [
            'chalan_no.required' => 'The chalan number is required.',
            'reference_no.required' => 'The reference number is required.',
            'remark.required' => 'The remark is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('store/add-rejected-chalan')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->storeRejectedChalan($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('storedept/list-rejected-chalan')->with(compact('msg', 'status'));
                    } else {
                        return redirect('storedept/add-rejected-chalan')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('storedept/add-rejected-chalan')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function getAllRejectedChalanList()
    {
        try {
            $all_gatepass = $this->service->getAllRejectedChalanList();
            return view('organizations.store.rejected-chalan.list-rejected-chalan-updated', compact('all_gatepass'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllRejectedChalanDetailsList($purchase_orders_id, $id)
    {
        try {
            $id = base64_decode($id);
            $purchase_orders_id = base64_decode($purchase_orders_id);
            $all_gatepass = $this->service->getAllRejectedChalanDetailsList($purchase_orders_id, $id);

            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $purchase_orders_id)->first();
            $po_id = $purchase_order_data->id;
            $purchase_order_details_data = PurchaseOrderDetailsModel::leftJoin('tbl_part_item', function($join) {
                $join->on('purchase_order_details.part_no_id', '=', 'tbl_part_item.id');
              })
            ->where('purchase_id', $po_id)
                ->get();
// dd($purchase_order_data);
// die();
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();

            return view('organizations.store.rejected-chalan.particular-rejected-chalan', compact('all_gatepass','getOrganizationData','purchase_order_data', 'purchase_order_details_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}