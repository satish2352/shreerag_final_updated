<?php

namespace App\Http\Controllers\Organizations\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Services\Organizations\Productions\ProductionServices;
use App\Http\Services\Organizations\Security\GatepassServices;
use App\Http\Controllers\Organizations\CommanController;
use Session;
use Validator;
use Config;
use Carbon;

use App\Models\ {
    DesignModel,
    PurchaseOrdersModel
    };

class GatepassController extends Controller
{
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

    public function index()
    {
        try {
            $all_gatepass = $this->service->getAll();

            return view('organizations.security.gatepass.list-gatepass', compact('all_gatepass'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseDetails($purchase_order_id)
    {
        try {
            // Fetch the purchase order along with vendor and related purchase order details
            $data = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
                ->join('gatepass', 'gatepass.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id')
                ->join('purchase_order_details', 'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
                // ->where('purchase_orders.id', $purchase_order_id) // Filter by purchase_order_id
                ->select(
                    'purchase_orders.id as purchase_order_id',
                    'purchase_orders.purchase_orders_id',
                    'purchase_orders.requisition_id', 
                    'purchase_orders.business_id', 
                    'purchase_orders.business_details_id', 
                    'purchase_orders.production_id', 
                    'purchase_orders.po_date', 
                    'purchase_orders.terms_condition', 
                    'purchase_orders.transport_dispatch', 
                    'purchase_orders.purchase_status_from_purchase',
                    'purchase_orders.image', 
                    'purchase_orders.tax_type', 
                    'purchase_orders.tax_id', 
                    'purchase_orders.invoice_date', 
                    'purchase_orders.payment_terms', 
                    'purchase_orders.discount', 
                    'vendors.vendor_name', 
                    'vendors.vendor_company_name', 
                    'vendors.vendor_email', 
                    'vendors.vendor_address', 
                    'vendors.gst_no', 
                    'vendors.quote_no', 
                    'purchase_orders.is_active',
                    'purchase_orders.created_at',
                    'purchase_order_details.*' // Fetch all columns from purchase_order_details
                )->get();
    
            // Separate the purchase order data and details
            $purchaseOrder = $data->first();
            $purchaseOrderDetails = $data;
    
            // Extract business_id from the fetched purchase order
            $business_id = $purchaseOrder->business_id;
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();
           
            // Pass all necessary data to the view
            return view('organizations.security.gatepass.list-particular-purchase-order-details', compact(
                'purchase_order_id',
                'purchaseOrder',
                'purchaseOrderDetails',
                'business_id',
                'getOrganizationData'
            ));
        } catch (\Exception $e) {
            // Handle exceptions and errors
            return response()->json(['error' => $e->getMessage()], 500);
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
                return redirect('securitydept/add-gatepass')
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
                        return redirect('securitydept/add-gatepass')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('securitydept/add-gatepass')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function edit(Request $request)
    {
        try {
            $edit_data_id = base64_decode($request->id);
            $editData = $this->service->getById($edit_data_id);
          
            return view('organizations.security.gatepass.edit-gatepass', compact('editData'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(Request $request)
    {
        $rules = [
            // 'id' => 'required|integer|exists:gatepasses,id',
            // 'purchase_orders_id' => 'required|string',
            // 'gatepass_name' => 'required|string',
            // 'gatepass_date' => 'required|date',
            // 'gatepass_time' => 'required|date_format:H:i',
            // 'remark' => 'required|string',
        ];
    
        $messages = [
            // 'id.required' => 'The ID is required.',
            // 'id.integer' => 'The ID must be a valid integer.',
            // 'id.exists' => 'The ID does not exist.',
            // 'purchase_orders_id.required' => 'The Purchase Number is required.',
            // 'purchase_orders_id.string' => 'The Purchase Number must be a valid string.',
            // 'gatepass_name.required' => 'The Gatepass name is required.',
            // 'gatepass_name.string' => 'The Gatepass Person name must be a valid string.',
            // 'gatepass_date.required' => 'Please enter a valid Gatepass Date.',
            // 'gatepass_date.date' => 'The Gatepass Date must be a valid date.',
            // 'gatepass_time.required' => 'Please Enter a valid Gatepass Time.',
            // 'gatepass_time.date_format' => 'The Gatepass Time must be in the format HH:MM.',
            // 'remark.required' => 'The remark is required.',
            // 'remark.string' => 'The remark must be a valid string.',
        ];
    
        try {
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $update_data = $this->service->updateAll($request);
            
                if ($update_data['status'] == 'success') {
                    return redirect('securitydept/list-gatepass')->with('msg', $update_data['msg'])->with('status', 'success');
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('msg', $update_data['msg'])
                        ->with('status', 'error');
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    


}