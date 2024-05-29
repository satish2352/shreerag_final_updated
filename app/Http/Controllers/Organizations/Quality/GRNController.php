<?php

namespace App\Http\Controllers\Organizations\Quality;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Quality\GRNServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel
};

class GRNController extends Controller
{
    public function __construct()
    {
        $this->service = new GRNServices();
    }



    public function index()
    {
        try {
            $all_gatepass = $this->service->getAll();
            return view('organizations.quality.grn.list-grn', compact('all_gatepass'));
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

            return view('organizations.quality.grn.add-grn', compact('purchase_order_data', 'purchase_order_details_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'grn_date' => 'required',
            'purchase_orders_id' => 'required',
            'po_date' => 'required',
        ];

        $messages = [
            'grn_date.required' => 'The Client Name is required.',
            'purchase_orders_id.required' => 'The purchase orders no is required.',
            'po_date.required' => 'The Email is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('quality/add-grn')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->storeGRN($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('quality/list-grn')->with(compact('msg', 'status'));
                    } else {
                        return redirect('quality/add-grn')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('quality/add-grn')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function edit()
    {
        try {
            return view('organizations.quality.grn.edit-grn');
        } catch (\Exception $e) {
            return $e;
        }
    }
}