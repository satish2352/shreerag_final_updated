<?php

namespace App\Http\Controllers\Organizations\Store;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\Organizations\Store\ReturnableChalanServices;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\{
    BusinessApplicationProcesses,
    Vendors,
    Tax,
    PartItem,
    Business,
    VehicleType,
    TransportName,
    UnitMaster,
    HSNMaster,
    ProcessMaster,
    ReturnableChalan,
    ReturnableChalanItemDetails,
    PurchaseOrdersModel
};
use App\Http\Controllers\Organizations\CommanController;


class ReturnableChalanController extends Controller
{
    public function __construct()
    {
        $this->service = new ReturnableChalanServices();
        $this->serviceCommon = new CommanController();

    }

    public function index()
    {
        $getOutput = ReturnableChalan::leftJoin('vendors', function($join) {
            $join->on('tbl_returnable_chalan.vendor_id', '=', 'vendors.id');
          })
          ->leftJoin('purchase_orders', function($join) {
            $join->on('tbl_returnable_chalan.business_id', '=', 'purchase_orders.id');
          })
          ->leftJoin('tbl_transport_name', function($join) {
            $join->on('tbl_returnable_chalan.transport_id', '=', 'tbl_transport_name.id');
          })
          ->leftJoin('tbl_vehicle_type', function($join) {
            $join->on('tbl_returnable_chalan.vehicle_id', '=', 'tbl_vehicle_type.id');
          })
          ->where('tbl_returnable_chalan.is_deleted', 0)
        ->select('tbl_returnable_chalan.id','tbl_returnable_chalan.vendor_id','tbl_returnable_chalan.transport_id',
        'tbl_returnable_chalan.business_id','tbl_returnable_chalan.vehicle_id','vendors.vendor_name'
        ,'tbl_returnable_chalan.dc_number','tbl_returnable_chalan.updated_at','purchase_orders.purchase_orders_id','tbl_transport_name.name as transport_name','tbl_vehicle_type.name as vehicle_name','tbl_returnable_chalan.customer_po_no'
        )->orderBy('tbl_returnable_chalan.updated_at', 'desc')
        ->get();        
        return view(
            'organizations.store.returnable-chalan.list-returnable-chalan',
            compact(
                'getOutput'
            )
        );
    }

    public function create(Request $request)
    {
        $requistition_id = $request->requistition_id;
        $title = 'create invoice';
        $dataOutputVendor = Vendors::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputTax = Tax::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputPartItem = PartItem::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputBusiness = Business::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputPurchaseOrdersModel = PurchaseOrdersModel::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputVehicleType = VehicleType::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputTransportName = TransportName::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputProcessMaster = ProcessMaster::where('is_active', true)->where('is_deleted', 0)->get();
        return view(
            'organizations.store.returnable-chalan.add-returnable-chalan',
            compact(
                'title',
                'requistition_id',
                'dataOutputVendor',
                'dataOutputTax',
                'dataOutputPartItem',
                'dataOutputBusiness',
                'dataOutputVehicleType',
                'dataOutputTransportName',
                'dataOutputUnitMaster',
                'dataOutputHSNMaster',
                'dataOutputProcessMaster',
                'dataOutputPurchaseOrdersModel'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $rules = [
            'vendor_id' => 'required',
            'transport_id' => 'required',
            'vehicle_id' => 'required',
            // 'business_id' => 'nullable',
            // 'customer_po_no' => 'nullable|string|max:255',
            'plant_id' => 'required|string|max:255',
            'tax_type' => 'required|string',
            'tax_id' => 'required',
            'vehicle_number' => 'required',
            'po_date' => 'required',
            'lr_number' => 'required',
            'remark' => 'required|string',
            'addmore.*.part_item_id' => 'required',
            'addmore.*.unit_id' => 'required',
            // 'addmore.*.hsn_id' => 'required',
            'addmore.*.process_id' => 'required',
            'addmore.*.quantity' => 'required',
            // 'addmore.*.rate' => 'required|numeric|min:0',
            'addmore.*.size' => 'required|string',
            'addmore.*.amount' => 'required|numeric',
        ];
        $messages = [
            'vendor_id.required' => 'The vendor company name is required.',
            'transport_id.required' => 'The transport name is required.',
            'vehicle_id.required' => 'The vehicle type is required.',
            // 'business_id.exists' => 'The selected PO number is invalid.',
            'plant_id.required' => 'The plant name is required.',
            'tax_type.required' => 'The tax type is required.',
            'tax_id.required' => 'The tax field is required.',
            'vehicle_number.required' => 'The vehicle number is required.',
            'po_date.required' => 'The PO date is required.',
            'remark.required' => 'The remark field is required.',
            'addmore.*.part_item_id.required' => 'The part item is required.',
            'addmore.*.unit_id.required' => 'The unit field is required.',
            // 'addmore.*.hsn_id.required' => 'The HSN code is required.',
            'addmore.*.process_id.required' => 'The process is required.',
            'addmore.*.quantity.required' => 'The quantity is required.',
            // 'addmore.*.rate.required' => 'The rate is required.',
            'addmore.*.size.required' => 'The size is required.',
            'addmore.*.amount.required' => 'The amount is required.',
            
        ];
        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('storedept/add-returnable-chalan')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->submitBOMToOwner($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];
                    if ($status == 'success') {
                        return redirect('storedept/list-returnable-chalan')->with(compact('msg', 'status'));
                    } else {
                        return redirect('storedept/add-returnable-chalan')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('storedept/add-business')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function fetchPONumbers(Request $request)
    {
        try {
            // Get the vendor_id from the request
            $vendorId = $request->vendor_id;
    
            // Fetch PO numbers based on the selected vendor
            $poNumbers = PurchaseOrdersModel::where('vendor_id', $vendorId)
                                  ->where('is_active', true)
                                  ->get(['id', 'purchase_orders_id']); // Adjust column names as needed
    
            // Return PO numbers as a JSON response
            return response()->json(['poNumbers' => $poNumbers]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPONumbers($vendor_id)
    {
        try {
            $poNumbers = PurchaseOrdersModel::where('vendor_id', $vendor_id)->where('is_active', true)->get(['id', 'purchase_orders_id']);
            return response()->json(['status' => 'success', 'data' => $poNumbers]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function show(Request $request)
    {
        // Decode the id
        $show_data_id = base64_decode($request->id);
    
        // Call service to fetch purchase order details
        $showData = $this->service->getPurchaseOrderDetails($show_data_id);
       
        $getOrganizationData = $this->serviceCommon->getAllOrganizationData();
        $getAllRulesAndRegulations = $this->serviceCommon->getAllRulesAndRegulations();
        return view('organizations.store.returnable-chalan.show-returnable-chalan', compact('showData', 'getOrganizationData', 'getAllRulesAndRegulations'));
    }
    

    public function show21(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        $title = 'view invoice';
        return view('organizations.store.returnable-chalan.show-returnable-chalan21', compact('invoice', 'title'));
    }

    public function showpurchase(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        $title = 'view invoice';
        return view('organizations.store.returnable-chalan.show-returnable-chalan1', compact('invoice', 'title'));
    }
   


    public function checkDetailsBeforeSendPOToVendor($purchase_order_id)
    {
        try {
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();

            $data = $this->serviceCommon->getPurchaseOrderDetails($purchase_order_id);
            $getAllRulesAndRegulations = $this->serviceCommon->getAllRulesAndRegulations();
            $business_id = $data['purchaseOrder']->business_id;
            $purchaseOrder = $data['purchaseOrder'];
            $purchaseOrderDetails = $data['purchaseOrderDetails'];

            return view(
                'organizations.purchase.purchase.returnable-chalan-details',
                compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations','business_id')
            );


            // return view('organizations.business.returnable-chalan.returnable-chalan-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData'));


        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getAllListPurchaseOrderTowardsOwnerDetails($purchase_order_id)
    {
        try {
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();
            $data = $this->serviceCommon->getPurchaseOrderDetails($purchase_order_id);
            $getAllRulesAndRegulations = $this->serviceCommon->getAllRulesAndRegulations();
            $business_id = $data['purchaseOrder']->business_id;
            $purchaseOrder = $data['purchaseOrder'];
            $purchaseOrderDetails = $data['purchaseOrderDetails'];

            return view('organizations.store.returnable-chalan.view-returnable-chalan-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations','business_id'));
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function edit(Request $request){

        $edit_data_id = base64_decode($request->id);
       
        $editData = $this->service->getById($edit_data_id);
        $dataOutputVendor = Vendors::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputTax = Tax::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputPartItem = PartItem::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputBusiness = Business::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputPurchaseOrdersModel = PurchaseOrdersModel::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputVehicleType = VehicleType::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputTransportName = TransportName::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputProcessMaster = ProcessMaster::where('is_active', true)->where('is_deleted', 0)->get();
        return view('organizations.store.returnable-chalan.edit-returnable-chalan', compact('editData', 
       'dataOutputVendor',
        'dataOutputTax',
        'dataOutputPartItem',
        'dataOutputBusiness',
        'dataOutputPurchaseOrdersModel',
        'dataOutputVehicleType',
        'dataOutputTransportName',
        'dataOutputUnitMaster',
        'dataOutputHSNMaster',
        'dataOutputProcessMaster'));
    }
    
    
            public function update(Request $request){
                
                $rules = [
                    'vendor_id' => 'required',
                    'transport_id' => 'required',
                    'vehicle_id' => 'required|exists:tbl_vehicle_type,id',
                    // 'business_id' => 'nullable|exists:purchase_orders,id',
                    // 'customer_po_no' => 'nullable|string|max:255',
                    'plant_id' => 'required|string|max:255',
                    'tax_type' => 'required|string|in:GST',
                    'tax_id' => 'required|exists:tbl_tax,id',
                    'vehicle_number' => 'required|string',
                    'po_date' => 'required',
                    'lr_number' => 'nullable|string|max:255',
                    'remark' => 'required|string',
                    'addmore.*.part_item_id' => 'required',
                    'addmore.*.unit_id' => 'required',
                    // 'addmore.*.hsn_id' => 'required',
                    'addmore.*.process_id' => 'required',
                    'addmore.*.quantity' => 'required|numeric',
                    // 'addmore.*.rate' => 'required|numeric|min:0',
                    'addmore.*.size' => 'required|string|max:255',
                    'addmore.*.amount' => 'required',

                ];
                $messages = [
                    'vendor_id.required' => 'The vendor company name is required.',
                    'transport_id.required' => 'The transport name is required.',
                    'vehicle_id.required' => 'The vehicle type is required.',
                    // 'business_id.exists' => 'The selected PO number is invalid.',
                    'plant_id.required' => 'The plant name is required.',
                    'tax_type.required' => 'The tax type is required.',
                    'tax_id.required' => 'The tax field is required.',
                    'vehicle_number.required' => 'The vehicle number is required.',
                    'po_date.required' => 'The PO date is required.',
                    'remark.required' => 'The remark field is required.',
                    'addmore.*.part_item_id.required' => 'The part item is required.',
                    'addmore.*.unit_id.required' => 'The unit field is required.',
                    // 'addmore.*.hsn_id.required' => 'The HSN code is required.',
                    'addmore.*.process_id.required' => 'The process is required.',
                    'addmore.*.quantity.required' => 'The quantity is required.',
                    // 'addmore.*.rate.required' => 'The rate is required.',
                    'addmore.*.size.required' => 'The size is required.',
                    'addmore.*.amount.required' => 'The amount is required.',
                ];
        
                try {
                    $validation = Validator::make($request->all(),$rules, $messages);
                    if ($validation->fails()) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors($validation);
                    } else {
                        
                        $update_data = $this->service->updateAll($request);
                        // $requisition_id = $request->input('requisition_id');
                       
                        if ($update_data) {
                            $msg = $update_data['msg'];
                            $status = $update_data['status'];
                            if ($status == 'success') {
                                return redirect('storedept/list-returnable-chalan')->with(compact('msg', 'status'));
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

            public function destroy(Request $request){
                $delete_data_id = base64_decode($request->id);
                try {
                    $delete_record = $this->service->deleteById($delete_data_id);
                    if ($delete_record) {
                        $msg = $delete_record['msg'];
                        $status = $delete_record['status'];
                        if ($status == 'success') {
                            return redirect('storedept/list-returnable-chalan')->with(compact('msg', 'status'));
                        } else {
                            return redirect()->back()
                                ->withInput()
                                ->with(compact('msg', 'status'));
                        }
                    }
                } catch (\Exception $e) {
                    return $e;
                }
            } 
            
            public function destroyAddmore(Request $request)
{
    // dd($request); // Inspect the request data to see if delete_id is being passed
    $delete_data_id = $request->delete_id; // Get the delete ID from the request
    try {
        $delete_record = $this->service->deleteByIdAddmore($delete_data_id);
        if ($delete_record) {
            $msg = $delete_record['msg'];
            $status = $delete_record['status'];
            if ($status == 'success') {
                return redirect('storedept/list-returnable-chalan')->with(compact('msg', 'status'));
            } else {
                return redirect()->back()->withInput()->with(compact('msg', 'status'));
            }
        }
    } catch (\Exception $e) {
        return $e;
    }
}

    
}