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
    BusinessApplicationProcesses
};

class RejectedChalanController extends Controller
{
    public function __construct()
    {
        $this->service = new RejectedChalanServices();
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

    public function add($purchase_orders_id)
    {
        try {
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $purchase_orders_id)->first();
            $po_id = $purchase_order_data->id;

            $purchase_order_details_data = PurchaseOrderDetailsModel::where('purchase_id', $po_id)
                ->get();
            return view('organizations.store.rejected-chalan.add-rejected-chalan', compact('purchase_order_data', 'purchase_order_details_data'));
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
}