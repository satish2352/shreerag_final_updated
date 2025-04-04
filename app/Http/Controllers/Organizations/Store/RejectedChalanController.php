<?php

namespace App\Http\Controllers\Organizations\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\RejectedChalanServices;
use DB;
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
    GRNModel,
    GrnPOQuantityTracking
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
                ->where('tbl_rejected_chalan.purchase_orders_id', '=', $purchase_orders_id)->where('tbl_rejected_chalan.grn_id', '=', $rejected_id)
                ->select(
                    'tbl_rejected_chalan.id',
                    'gatepass.gatepass_name',
                    'grn_tbl.grn_date'
                )
                ->first();
                $po_id = $purchase_order_data->id;              
                     $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item', function ($join) {
                $join->on('tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id');
              })
              ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
              ->where('tbl_grn_po_quantity_tracking.grn_id', $rejected_id)
                ->select(
                'tbl_grn_po_quantity_tracking.*',
                'tbl_part_item.description as part_description',
                'tbl_grn_po_quantity_tracking.updated_at',
            )
          
            ->get();
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
            $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id')
            ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
            ->leftJoin('tbl_unit', 'tbl_grn_po_quantity_tracking.unit', '=', 'tbl_unit.id')
            ->leftJoin('tbl_rejected_chalan', 'tbl_grn_po_quantity_tracking.grn_id', '=', 'tbl_rejected_chalan.grn_id')
            ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
            // ->where('tbl_grn_po_quantity_tracking.grn_id', $id)
            ->where('tbl_rejected_chalan.id', $id)
            ->select(
                'tbl_grn_po_quantity_tracking.purchase_order_id', 
                'tbl_grn_po_quantity_tracking.part_no_id',
                'tbl_grn_po_quantity_tracking.purchase_order_details_id',
                DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
                DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
                DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
                DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),
                
                DB::raw('(SELECT SUM(t2.actual_quantity) 
                          FROM tbl_grn_po_quantity_tracking AS t2 
                          WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
                          AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
                          AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
                          AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at) AS sum_grn_actual_quantity'),
                
                DB::raw('(
                    purchase_order_details.quantity - 
                    (SELECT SUM(t2.actual_quantity) 
                     FROM tbl_grn_po_quantity_tracking AS t2 
                     WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
                     AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
                     AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
                     AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at)
                ) AS remaining_quantity'),
        
                'tbl_part_item.description as part_description',
                'tbl_part_item.part_number',
                'tbl_unit.name as unit_name',
                DB::raw('MAX(purchase_order_details.description) as po_description'),
                DB::raw('MAX(purchase_order_details.rate) as po_rate'),
                DB::raw('MAX(purchase_order_details.discount) as po_discount'),
                'tbl_grn_po_quantity_tracking.created_at' // Add created_at to SELECT
            )
            ->groupBy(
                'tbl_grn_po_quantity_tracking.purchase_order_id',
                'tbl_grn_po_quantity_tracking.part_no_id',
                'tbl_grn_po_quantity_tracking.purchase_order_details_id',
                'tbl_part_item.id',
                'tbl_part_item.description',
                'tbl_part_item.part_number',
                'tbl_unit.name',
                'purchase_order_details.quantity',
                'tbl_grn_po_quantity_tracking.created_at' // Add created_at to GROUP BY
            )
            ->get();
                
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();

            return view('organizations.store.rejected-chalan.particular-rejected-chalan', compact('all_gatepass','getOrganizationData','purchase_order_data', 'purchase_order_details_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}