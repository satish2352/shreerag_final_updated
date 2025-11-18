<?php

namespace App\Http\Controllers\Organizations\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Services\Organizations\Productions\ProductionServices;
use App\Http\Services\Organizations\Security\GatepassServices;
use App\Http\Controllers\Organizations\CommanController;
use Illuminate\Support\Facades\Validator;
use Exception;


use App\Models\{
    Gatepass,
    PurchaseOrdersModel
};

class GatepassController extends Controller
{
    protected $service;
    protected $serviceCommon;
    public function __construct()
    {
        $this->service = new GatepassServices();
        $this->serviceCommon = new CommanController();
    }

    public function searchByPONo()
    {
        try {

            return view('organizations.security.search-by-pono');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addGatePassWithPO($purchase_orders_id)
    {
        try {

            $purchase_orders_id = base64_decode($purchase_orders_id);
            return view('organizations.security.gatepass.add-gatepass-with-po-details', compact('purchase_orders_id'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseDetails($id, $purchase_order_id)
    {
        try {
            $businessDetailsId = base64_decode($id);
            $purchaseOrderId = base64_decode($purchase_order_id);

            $data = PurchaseOrdersModel::leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
                ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
                ->join('purchase_order_details', 'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
                ->join('tbl_part_item', 'tbl_part_item.id', '=', 'purchase_order_details.part_no_id')
                ->join('tbl_unit', 'tbl_unit.id', '=', 'purchase_order_details.unit')
                ->join('tbl_hsn', 'tbl_hsn.id', '=', 'purchase_order_details.hsn_id')
                ->join('tbl_tax', 'tbl_tax.id', '=', 'purchase_orders.tax_id')
                ->leftJoin('gatepass', 'purchase_orders.business_details_id', '=', 'gatepass.business_details_id')
                ->where('purchase_orders.id', $businessDetailsId)
                ->where('purchase_orders.purchase_orders_id', $purchaseOrderId)
                ->select(
                    'purchase_orders.id as purchase_order_id',
                    'purchase_orders.purchase_orders_id',
                    'purchase_orders.requisition_id',
                    'purchase_orders.business_details_id',
                    'purchase_orders.production_id',
                    'purchase_orders.po_date',
                    'purchase_orders.terms_condition',
                    'purchase_orders.transport_dispatch',
                    'purchase_orders.purchase_status_from_purchase',
                    'purchase_orders.contact_person_name',
                    'purchase_orders.contact_person_number',
                    'purchase_orders.image',
                    'purchase_orders.tax_type',
                    'purchase_orders.tax_id',
                    'purchase_orders.invoice_date',
                    'purchase_orders.payment_terms',
                    'vendors.vendor_name',
                    'vendors.vendor_company_name',
                    'vendors.vendor_email',
                    'vendors.vendor_address',
                    'vendors.contact_no',
                    'vendors.gst_no',
                    'vendors.quote_no',
                    'purchase_orders.is_active',
                    'purchase_orders.created_at',
                    'purchase_order_details.*',
                    'tbl_part_item.id', // Part number from tbl_part_item
                    'tbl_part_item.description as part_name',
                    'tbl_unit.name as unit_name',
                    'tbl_hsn.name as hsn_name',
                    'tbl_tax.name as tax_name',
                    'purchase_orders.note'
                )
                ->distinct() // Ensure unique rows
                ->get();

            $purchaseOrder = $data->first();
            $purchaseOrderDetails = $data;

            $business_id = $purchaseOrder->business_id;
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();

            return view('organizations.security.gatepass.list-particular-purchase-order-details', compact(
                'purchase_order_id',
                'purchaseOrder',
                'purchaseOrderDetails',
                'business_id',
                'getOrganizationData',
                'businessDetailsId'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPurchaseDetailsAfterGatepass($id, $purchase_order_id)
    {
        try {

            $businessDetailsId = base64_decode($id);
            $purchaseOrderId = base64_decode($purchase_order_id);
            $data = PurchaseOrdersModel::leftJoin('businesses_details', function ($join) {
                $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
            })
                ->leftJoin('vendors', function ($join) {
                    $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
                })
                ->join('purchase_order_details', 'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
                ->join('tbl_part_item', function ($join) {
                    $join->on('tbl_part_item.id', '=', 'purchase_order_details.part_no_id')
                        ->orOn('tbl_part_item.id', '=', 'purchase_order_details.part_no_id');
                })
                ->join('tbl_unit', function ($join) {
                    $join->on('tbl_unit.id', '=', 'purchase_order_details.unit')
                        ->orOn('tbl_unit.id', '=', 'purchase_order_details.unit');
                })
                ->join('tbl_hsn', function ($join) {
                    $join->on('tbl_hsn.id', '=', 'purchase_order_details.hsn_id')
                        ->orOn('tbl_hsn.id', '=', 'purchase_order_details.hsn_id');
                })
                ->join('tbl_tax', function ($join) {
                    $join->on('tbl_tax.id', '=', 'purchase_order_details.tax_id')
                        ->orOn('tbl_tax.id', '=', 'purchase_order_details.tax_id');
                })
                ->leftJoin('gatepass', function ($join) {
                    $join->on('purchase_orders.business_details_id', '=', 'gatepass.business_details_id');
                })
                //   ->leftJoin('tbl_hsn as hsn', 'hsn.id', '=', 'pod1.hsn_id')
                ->where('gatepass.id', $businessDetailsId)
                ->where('purchase_orders.purchase_orders_id', $purchaseOrderId)
                ->select(
                    'purchase_orders.id as purchase_order_id',
                    // 'purchase_orders.business_id',
                    'purchase_orders.purchase_orders_id',
                    'purchase_orders.requisition_id',
                    // 'purchase_orders.business_id', 
                    'purchase_orders.business_details_id',
                    'purchase_orders.production_id',
                    'purchase_orders.po_date',
                    'purchase_orders.terms_condition',
                    'purchase_orders.transport_dispatch',
                    'purchase_orders.purchase_status_from_purchase',
                    'purchase_orders.contact_person_name',
                    'purchase_orders.contact_person_number',
                    'purchase_orders.image',
                    'purchase_orders.tax_type',
                    'purchase_orders.tax_id',
                    'purchase_orders.invoice_date',
                    'purchase_orders.payment_terms',
                    // 'purchase_orders.discount', 
                    'vendors.vendor_name',
                    'vendors.vendor_company_name',
                    'vendors.vendor_email',
                    'vendors.vendor_address',
                    'vendors.contact_no',
                    'vendors.gst_no',
                    'vendors.quote_no',
                    'purchase_orders.is_active',
                    'purchase_orders.created_at',
                    'purchase_order_details.*',
                    'tbl_part_item.id',            // Fetch part number from the tbl_part_item table
                    'tbl_part_item.description as part_name',
                    'tbl_unit.name as unit_name',
                    'tbl_hsn.name as hsn_name',
                    'tbl_tax.name as tax_name',
                    'purchase_orders.note'

                )->get();
            $purchaseOrder = $data->first();
            $purchaseOrderDetails = $data;

            $business_id = $purchaseOrder->business_id;
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();

            return view('organizations.security.gatepass.list-particular-purchase-order-details', compact(
                'purchase_order_id',
                'purchaseOrder',
                'purchaseOrderDetails',
                'business_id',
                'getOrganizationData',
                'businessDetailsId'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $all_gatepass = $this->service->getAll();

            return view('organizations.security.gatepass.list-gatepass', compact('all_gatepass'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function add()
    {
        try {
            return view('organizations.security.gatepass.add-gatepass');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'purchase_orders_id' => 'required|string',
            'gatepass_name' => 'required|string',
            'gatepass_date' => 'required',
            'gatepass_time' => 'required',
            'remark' => 'required|string',
        ];

        $messages = [
            'purchase_orders_id.required' => 'The Purchase Number is required.',
            'purchase_orders_id.string' => 'The Purchase Number must be a valid string.',

            'gatepass_name.required' => 'The Gatepass name is required.',
            'gatepass_name.string' => 'The Gatepass Person name must be a valid string.',

            'gatepass_date.required' => 'Please enter a valid Gatepass Date.',

            'gatepass_time.required' => 'Please Enter  a valid Gatepass Time.',

            'remark.required' => 'The remark is required.',
            'remark.string' => 'The remark must be a valid string.',
        ];


        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('add-gatepass')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->addAll($request);

                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('securitydept/list-gatepass')->with(compact('msg', 'status'));
                    } else {
                        return redirect('add-gatepass')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('add-gatepass')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function edit(Request $request)
    {
        try {
            $edit_data_id = base64_decode($request->id);
            $editData = $this->service->getById($edit_data_id);
            $data = Gatepass::orderby('updated_at', 'desc')->get();

            return view('organizations.security.gatepass.edit-gatepass', compact('editData', 'data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function update(Request $request)
    {
        $id = $request->edit_id;
        $rules = [
            'purchase_orders_id' => 'required|string',
            'gatepass_name' => 'required|string',
            'gatepass_date' => 'required',
            'gatepass_time' => 'required',
            'remark' => 'required|string',
        ];

        $messages = [
            'purchase_orders_id.required' => 'The Purchase Number is required.',
            'purchase_orders_id.string' => 'The Purchase Number must be a valid string.',

            'gatepass_name.required' => 'The Gatepass name is required.',
            'gatepass_name.string' => 'The Gatepass Person name must be a valid string.',

            'gatepass_date.required' => 'Please enter a valid Gatepass Date.',

            'gatepass_time.required' => 'Please Enter  a valid Gatepass Time.',

            'remark.required' => 'The remark is required.',
            'remark.string' => 'The remark must be a valid string.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $update_data = $this->service->updateAll($request);
                if ($update_data) {
                    $msg = $update_data['msg'];
                    $status = $update_data['status'];
                    if ($status == 'success') {
                        return redirect('securitydept/list-gatepass')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
