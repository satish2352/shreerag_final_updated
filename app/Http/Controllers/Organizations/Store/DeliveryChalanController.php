<?php

namespace App\Http\Controllers\Organizations\Store;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\Organizations\Store\DeliveryChalanServices;
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
    DeliveryChalan,
    DeliveryChalanItemDetails,
    PurchaseOrdersModel
    
};
use App\Http\Controllers\Organizations\CommanController;


class DeliveryChalanController extends Controller
{
    public function __construct()
    {
        $this->service = new DeliveryChalanServices();
        $this->serviceCommon = new CommanController();

    }
    public function index()
    {
        $getOutput = DeliveryChalan::leftJoin('vendors', function ($join) {
            $join->on('tbl_delivery_chalan.vendor_id', '=', 'vendors.id');
        })
        ->leftJoin('businesses', function ($join) {
            $join->on('tbl_delivery_chalan.business_id', '=', 'businesses.id');
        })
        ->leftJoin('tbl_transport_name', function ($join) {
            $join->on('tbl_delivery_chalan.transport_id', '=', 'tbl_transport_name.id');
        })
        ->leftJoin('tbl_vehicle_type', function ($join) {
            $join->on('tbl_delivery_chalan.vehicle_id', '=', 'tbl_vehicle_type.id');
        })
        ->where('tbl_delivery_chalan.is_deleted', 0)
        ->select('tbl_delivery_chalan.id','tbl_delivery_chalan.vendor_id','tbl_delivery_chalan.transport_id',
        'tbl_delivery_chalan.business_id','tbl_delivery_chalan.vehicle_id','vendors.vendor_name', 'tbl_delivery_chalan.customer_po_no'
        ,'businesses.customer_po_number','tbl_transport_name.name as transport_name','tbl_vehicle_type.name as vehicle_name','tbl_delivery_chalan.remark','tbl_delivery_chalan.dc_number','tbl_delivery_chalan.updated_at'
        )->orderBy('tbl_delivery_chalan.updated_at', 'desc')->get();
        return view(
            'organizations.store.delivery-chalan.list-delivery-chalan',
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
        $dataOutputVehicleType = VehicleType::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputTransportName = TransportName::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputProcessMaster = ProcessMaster::where('is_active', true)->where('is_deleted', 0)->get();
        return view(
            'organizations.store.delivery-chalan.add-delivery-chalan',
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
                'dataOutputProcessMaster'
            )
        );
    }
    public function store(Request $request)
    {
        $rules = [
            'vendor_id' => 'required',
            // 'transport_id' => 'required',
            'vehicle_id' => 'required',
            // 'business_id' => 'nullable|exists:businesses,id',
            'customer_po_no' => 'nullable|string|max:255',
            'plant_id' => 'required|string|max:255',
            'tax_type' => 'required',
            'tax_id' => 'required',
            // 'vehicle_number' => 'required|string',
            'po_date' => 'required',
            'lr_number' => 'nullable|string|max:255',
            'remark' => 'required|string',
            'addmore.*.part_item_id' => 'required',
            'addmore.*.unit_id' => 'required',
            // 'addmore.*.hsn_id' => 'required',
            'addmore.*.process_id' => 'required',
            'addmore.*.quantity' => 'required|numeric',
            // 'addmore.*.rate' => 'required',
            'addmore.*.size' => 'required|string',
            'addmore.*.amount' => 'required',
        ];
        $messages = [
            'vendor_id.required' => 'The vendor company name is required.',
            // 'transport_id.required' => 'The transport name is required.',
            'vehicle_id.required' => 'The vehicle type is required.',
            // 'business_id.exists' => 'The selected PO number is invalid.',
            'plant_id.required' => 'The plant name is required.',
            'tax_type.required' => 'The tax type is required.',
            'tax_id.required' => 'The tax field is required.',
            // 'vehicle_number.required' => 'The vehicle number is required.',
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
                return redirect('storedept/add-delivery-chalan')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->submitBOMToOwner($request);
            
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];
                    if ($status == 'success') {
                        return redirect('storedept/list-delivery-chalan')->with(compact('msg', 'status'));
                    } else {
                        return redirect('storedept/add-delivery-chalan')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('storedept/add-business')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function show(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $showData = $this->service->getPurchaseOrderDetails($show_data_id);
        $getOrganizationData = $this->serviceCommon->getAllOrganizationData();
        $getAllRulesAndRegulations = $this->serviceCommon->getAllRulesAndRegulations();

        return view('organizations.store.delivery-chalan.show-delivery-chalan', compact('showData', 'getOrganizationData', 'getAllRulesAndRegulations'));
    }
    public function show21(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        $title = 'view invoice';
        return view('organizations.store.delivery-chalan.show-delivery-chalan21', compact('invoice', 'title'));
    }
    public function showpurchase(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        $title = 'view invoice';
        return view('organizations.store.delivery-chalan.show-delivery-chalan1', compact('invoice', 'title'));
    }
    public function submitPurchaseOrderToOwnerForReview(Request $request)
    {
        try {
            $requistition_id = base64_decode($request->requistition_id);
            
            $data_purchase_orders_id = PurchaseOrdersModel::where('requisition_id', $requistition_id)->pluck('purchase_orders_id');
            
            $data_purchase_orders_update = PurchaseOrdersModel::where('requisition_id', $requistition_id)->first();
            $data_purchase_orders_update->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL');
            $data_purchase_orders_update->save();

            $business_application = BusinessApplicationProcesses::where('requisition_id', $requistition_id)->first();

            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE');
                $business_application->purchase_order_id = $data_purchase_orders_id;
                $business_application->purchase_order_submited_to_owner_date = date('Y-m-d');

                $business_application->save();

            }

            $msg = 'Purchase order submitted successfully';
            $status = 'success';
            return redirect('storedept/list-purchase')->with(compact('msg', 'status'));
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
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

            return view('organizations.store.delivery-chalan.view-delivery-chalan-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations','business_id'));
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
        $dataOutputVehicleType = VehicleType::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputTransportName = TransportName::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->where('is_deleted', 0)->get();
        $dataOutputProcessMaster = ProcessMaster::where('is_active', true)->where('is_deleted', 0)->get();
        return view('organizations.store.delivery-chalan.edit-delivery-chalan', compact('editData', 
       'dataOutputVendor',
        'dataOutputTax',
        'dataOutputPartItem',
        'dataOutputBusiness',
        'dataOutputVehicleType',
        'dataOutputTransportName',
        'dataOutputUnitMaster',
        'dataOutputHSNMaster',
        'dataOutputProcessMaster'));
    }
     public function update(Request $request){
                
                $rules = [
             'vendor_id' => 'required',
            // 'transport_id' => 'required',
            'vehicle_id' => 'required',
            // 'business_id' => 'nullable|exists:businesses,id',
            'customer_po_no' => 'nullable|string|max:255',
            'plant_id' => 'required|string|max:255',
            'tax_type' => 'required',
            'tax_id' => 'required',
            // 'vehicle_number' => 'required|string',
            'po_date' => 'required',
            'lr_number' => 'nullable|string|max:255',
            'remark' => 'required|string',
            'addmore.*.part_item_id' => 'required',
            'addmore.*.unit_id' => 'required',
            // 'addmore.*.hsn_id' => 'required',
            'addmore.*.process_id' => 'required',
            'addmore.*.quantity' => 'required|numeric',
            // 'addmore.*.rate' => 'required',
            'addmore.*.size' => 'required|string',
            'addmore.*.amount' => 'required',

                ];
                $messages = [
                    'vendor_id.required' => 'The vendor company name is required.',
                    // 'transport_id.required' => 'The transport name is required.',
                    // 'vehicle_id.required' => 'The vehicle type is required.',
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
                        if ($update_data) {
                            $msg = $update_data['msg'];
                            $status = $update_data['status'];
                            if ($status == 'success') {
                                return redirect('storedept/list-delivery-chalan')->with(compact('msg', 'status'));
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
                            return redirect('storedept/list-delivery-chalan')->with(compact('msg', 'status'));
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
            $delete_data_id = $request->delete_id;
            try {
                $delete_record = $this->service->deleteByIdAddmore($delete_data_id);
                if ($delete_record) {
                    $msg = $delete_record['msg'];
                    $status = $delete_record['status'];
                    if ($status == 'success') {
                        return redirect('storedept/list-delivery-chalan')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()->withInput()->with(compact('msg', 'status'));
                    }
                }
            } catch (\Exception $e) {
                return $e;
            }
        }
    public function getHsnForPartItemInDelivery(Request $request)
        {
        try{

            $partNoId = $request->part_item_id;
            $part = PartItem::leftJoin('tbl_hsn', function($join) {
                $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
            })
            ->where('tbl_part_item.id', $partNoId)
                                ->where('tbl_hsn.is_active', true)
                                ->get(['tbl_hsn.id', 'name']); 
        
            return response()->json(['part' => $part]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}