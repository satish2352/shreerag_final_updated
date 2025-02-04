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
        ->select('tbl_delivery_chalan.id','tbl_delivery_chalan.vendor_id','tbl_delivery_chalan.transport_id',
        'tbl_delivery_chalan.business_id','tbl_delivery_chalan.vehicle_id','vendors.vendor_name', 'tbl_delivery_chalan.customer_po_no'
        ,'businesses.customer_po_number','tbl_transport_name.name as transport_name','tbl_vehicle_type.name as vehicle_name','tbl_delivery_chalan.remark'
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
        $dataOutputVendor = Vendors::where('is_active', true)->get();
        $dataOutputTax = Tax::where('is_active', true)->get();
        $dataOutputPartItem = PartItem::where('is_active', true)->get();
        $dataOutputBusiness = Business::where('is_active', true)->get();
        $dataOutputVehicleType = VehicleType::where('is_active', true)->get();
        $dataOutputTransportName = TransportName::where('is_active', true)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->get();
        $dataOutputProcessMaster = ProcessMaster::where('is_active', true)->get();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $rules = [
            // 'vendor_id' => 'required|exists:vendors,id',
            // 'transport_id' => 'required|exists:tbl_transport_name,id',
            // 'vehicle_id' => 'required|exists:tbl_vehicle_type,id',
            // // 'business_id' => 'nullable|exists:businesses,id',
            // 'customer_po_no' => 'nullable|string|max:255',
            // 'plant_id' => 'required|string|max:255',
            // 'tax_type' => 'required|string|in:GST',
            // 'tax_id' => 'required|exists:tbl_tax,id',
            // 'vehicle_number' => 'required|string|max:50',
            // 'po_date' => 'required|date',
            // 'lr_number' => 'nullable|string|max:255',
            // 'remark' => 'required|string',
            // 'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            // 'addmore.*.part_item_id' => 'required|exists:tbl_part_item,id',
            // 'addmore.*.unit_id' => 'required|exists:tbl_unit,id',
            // 'addmore.*.hsn_id' => 'required|exists:tbl_hsn,id',
            // 'addmore.*.process_id' => 'required|exists:tbl_process_master,id',
            // 'addmore.*.quantity' => 'required|numeric|min:1',
            // // 'addmore.*.rate' => 'required|numeric|min:0',
            // 'addmore.*.size' => 'required|string|max:255',
            // 'addmore.*.amount' => 'required|numeric|min:0',
            // 'image' => 'required|image|mimes:jpeg,png,jpg|max:1024|min:1',
        ];
        $messages = [
            // 'vendor_id.required' => 'The vendor company name is required.',
            // 'transport_id.required' => 'The transport name is required.',
            // 'vehicle_id.required' => 'The vehicle type is required.',
            // // 'business_id.exists' => 'The selected PO number is invalid.',
            // 'plant_id.required' => 'The plant name is required.',
            // 'tax_type.required' => 'The tax type is required.',
            // 'tax_id.required' => 'The tax field is required.',
            // 'vehicle_number.required' => 'The vehicle number is required.',
            // 'po_date.required' => 'The PO date is required.',
            // 'remark.required' => 'The remark field is required.',
            // 'image.required' => 'The signature upload is required.',
            // 'image.image' => 'The uploaded file must be an image.',
            // 'image.mimes' => 'The uploaded file must be a JPEG, PNG, or JPG.',
            // 'addmore.*.part_item_id.required' => 'The part item is required.',
            // 'addmore.*.unit_id.required' => 'The unit field is required.',
            // 'addmore.*.hsn_id.required' => 'The HSN code is required.',
            // 'addmore.*.process_id.required' => 'The process is required.',
            // 'addmore.*.quantity.required' => 'The quantity is required.',
            // // 'addmore.*.rate.required' => 'The rate is required.',
            // 'addmore.*.size.required' => 'The size is required.',
            // 'addmore.*.amount.required' => 'The amount is required.',
            // 'image.required' => 'The logo is required.',
            // 'image.image' => 'The logo must be a valid logo file.',
            // 'image.mimes' => 'The logo must be in JPEG, PNG, JPG format.',
            // 'image.max' => 'The logo size must not exceed 1MB.',
            // 'image.min' => 'The logo size must not be less than 1KB.',
            
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
        // Decode the id
        $show_data_id = base64_decode($request->id);
    
        // Call service to fetch purchase order details
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

                // $business_application->grn_no = '0';
                // $business_application->store_receipt_no = '0';
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
                'organizations.purchase.purchase.delivery-chalan-details',
                compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations','business_id')
            );


            // return view('organizations.business.delivery-chalan.delivery-chalan-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData'));


        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }



    public function listAllApprovedPOToBeChecked($purchase_order_id)
    {
        try {
            $delete = $this->service->listAllApprovedPOToBeChecked($purchase_order_id);
            if ($delete) {
                $status = 'success';
                $msg = 'Purchase order mail sent to vendor.';
            } else {
                $status = 'success';
                $msg = 'Purchase order mail sent to vendor.';
            }

            return redirect('purchase/list-delivery-chalan-approved-sent-to-vendor')->with(compact('msg', 'status'));

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

            return view('organizations.store.delivery-chalan.view-delivery-chalan-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations','business_id'));
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function edit(Request $request){

        $edit_data_id = base64_decode($request->id);
        $editData = $this->service->getById($edit_data_id);
        $dataOutputVendor = Vendors::where('is_active', true)->get();
        $dataOutputTax = Tax::where('is_active', true)->get();
        $dataOutputPartItem = PartItem::where('is_active', true)->get();
        $dataOutputBusiness = Business::where('is_active', true)->get();
        $dataOutputVehicleType = VehicleType::where('is_active', true)->get();
        $dataOutputTransportName = TransportName::where('is_active', true)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->get();
        $dataOutputProcessMaster = ProcessMaster::where('is_active', true)->get();
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
                    // 'vendor_id' => 'required|exists:vendors,id',
                    // 'transport_id' => 'required|exists:tbl_transport_name,id',
                    // 'vehicle_id' => 'required|exists:tbl_vehicle_type,id',
                    // // 'business_id' => 'nullable|exists:businesses,id',
                    // 'customer_po_no' => 'nullable|string|max:255',
                    // 'plant_id' => 'required|string|max:255',
                    // 'tax_type' => 'required|string|in:GST',
                    // 'tax_id' => 'required|exists:tbl_tax,id',
                    // 'vehicle_number' => 'required|string|max:50',
                    // 'po_date' => 'required|date',
                    // 'lr_number' => 'nullable|string|max:255',
                    // 'remark' => 'required|string',
                    // 'addmore.*.part_item_id' => 'required|exists:tbl_part_item,id',
                    // 'addmore.*.unit_id' => 'required|exists:tbl_unit,id',
                    // 'addmore.*.hsn_id' => 'required|exists:tbl_hsn,id',
                    // 'addmore.*.process_id' => 'required|exists:tbl_process_master,id',
                    // 'addmore.*.quantity' => 'required|numeric|min:1',
                    // // 'addmore.*.rate' => 'required|numeric|min:0',
                    // 'addmore.*.size' => 'required|string|max:255',
                    // 'addmore.*.amount' => 'required|numeric|min:0',

                ];
                $messages = [
                    // 'vendor_id.required' => 'The vendor company name is required.',
                    // 'transport_id.required' => 'The transport name is required.',
                    // 'vehicle_id.required' => 'The vehicle type is required.',
                    // // 'business_id.exists' => 'The selected PO number is invalid.',
                    // 'plant_id.required' => 'The plant name is required.',
                    // 'tax_type.required' => 'The tax type is required.',
                    // 'tax_id.required' => 'The tax field is required.',
                    // 'vehicle_number.required' => 'The vehicle number is required.',
                    // 'po_date.required' => 'The PO date is required.',
                    // 'remark.required' => 'The remark field is required.',
                    // 'addmore.*.part_item_id.required' => 'The part item is required.',
                    // 'addmore.*.unit_id.required' => 'The unit field is required.',
                    // 'addmore.*.hsn_id.required' => 'The HSN code is required.',
                    // 'addmore.*.process_id.required' => 'The process is required.',
                    // 'addmore.*.quantity.required' => 'The quantity is required.',
                    // // 'addmore.*.rate.required' => 'The rate is required.',
                    // 'addmore.*.size.required' => 'The size is required.',
                    // 'addmore.*.amount.required' => 'The amount is required.',
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

           

            public function destroyAddmore(Request $request)
{
    $delete_data_id = $request->delete_id; // Get the delete ID from the request
    // dd($delete_data_id);
    // die();
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

    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id)
    {
        try {
            $purchaseOrder = $this->service->submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id);
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();
            $data = $this->serviceCommon->getPurchaseOrderDetails($purchase_order_id);
            $getAllRulesAndRegulations = $this->serviceCommon->getAllRulesAndRegulations();
            $business_id = $data['purchaseOrder']->business_id;
            $purchaseOrder = $data['purchaseOrder'];
            $purchaseOrderDetails = $data['purchaseOrderDetails'];
            if (!$purchaseOrder) {
                return response()->json(['status' => 'error', 'message' => 'Purchase order not found'], 404);
            }
            // Generate PDF with specific settings
            $pdf = Pdf::loadView('organizations.common-pages.delivery-chalan-view', [
                'purchase_order_id' => $purchase_order_id,
                'purchaseOrder' => $purchaseOrder,
                'purchaseOrderDetails' => $purchaseOrderDetails,
                'getOrganizationData' => $getOrganizationData,
                'getAllRulesAndRegulations' => $getAllRulesAndRegulations,
                'business_id' => $business_id,
            ])
            ->setPaper('a4', 'portrait') // You can change to 'landscape' if needed
            ->setWarnings(false) // Disable warnings (optional)
            ->setOptions([
                'margin-top' => 10,     // Adjust top margin
                'margin-right' => 400,   // Adjust right margin
                'margin-bottom' => 10,  // Adjust bottom margin
                'margin-left' => 10,    // Adjust left margin
            ])
            ->save(storage_path('app/public/purchase_order_' . $purchase_order_id . '.pdf')); // Save the PDF
    
            $pdfPath = storage_path('app/public/purchase_order_' . $purchase_order_id . '.pdf');
    
            // Send email with PDF attachment
            Mail::send([], [], function ($message) use ($purchaseOrder, $pdfPath) {
                $message->to($purchaseOrder->vendor_email)
                    ->subject('Purchase Order Notification')
                    ->attach($pdfPath);
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
    
            return redirect('purchase/list-delivery-chalan-approved-sent-to-vendor')->with('status', 'success')->with('msg', 'Purchase order mail sent to vendor.');
    
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    public function getHsnForPartItemInDelivery(Request $request)
    {
    
        // $partNoId = $request->input('part-no'); // Get the part_no from the request
        // $partNoId = $request->id;
     
        // // Fetch HSN details based on the part_no_id
        // $part = PartItem::where('description', $partNoId)->first(['hsn_id', 'hsn_id']);
    try{

        $partNoId = $request->part_item_id;
    
        // Fetch PO numbers based on the selected vendor
        $part = PartItem::leftJoin('tbl_hsn', function($join) {
            $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
        })
        ->where('tbl_part_item.id', $partNoId)
                              ->where('tbl_hsn.is_active', true)
                              ->get(['tbl_hsn.id', 'name']); // Adjust column names as needed
                             

        // if ($part) {
        //     return response()->json(['hsn_id' => $part->hsn_id, 'hsn_id' => $part->hsn_id]);
        // }
    
        return response()->json(['part' => $part]);
    }
    catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
    }
}