<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Models\PurchaseOrdersModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\Organizations\Purchase\PurchaseOrderServices;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\{
    BusinessApplicationProcesses,
    Vendors,
    Tax,
    PartItem,
    UnitMaster,
    HSNMaster
};
use App\Http\Controllers\Organizations\CommanController;


class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->service = new PurchaseOrderServices();
        $this->serviceCommon = new CommanController();

    }

    public function index($requistition_id)
    {
        // $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];
        // $getOutput = PurchaseOrdersModel::where('requisition_id', base64_decode($requistition_id))->get();
        $getOutput = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
        // ->where('purchase_orders.business_id')
        ->whereNull('purchase_status_from_owner')
        // ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
        ->where('requisition_id', base64_decode($requistition_id))
        ->get();
       
// dd($getOutput);
// die();
        return view(
            'organizations.purchase.addpurchasedetails.list-purchase-orders',
            compact(
                'getOutput',
                'requistition_id'
            )
        );
    }

    public function create(Request $request)
    {
        // dd($request->all());
        // die();
        // dd($request->business_details_id);
        // die();
        $requistition_id = $request->requistition_id;
        $requistitionId = base64_decode($request->requistition_id);
        $title = 'create invoice';
        $dataPurchaseOrder = PurchaseOrdersModel::where('requisition_id', $requistitionId)->first();
    //    dd($dataPurchaseOrder);
    //    die();
        $dataOutputVendor = Vendors::where('is_active', true)->get();
        $dataOutputTax = Tax::where('is_active', true)->get();
        $dataOutputPartItem = PartItem::where('is_active', true)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->get();
        return view(
            'organizations.purchase.addpurchasedetails.add-purchase-orders',
            compact(
                'title',
                'requistition_id',
                'dataOutputVendor',
                'dataOutputTax',
                'dataOutputPartItem',
                'dataOutputUnitMaster',
                'dataOutputHSNMaster',
                'dataPurchaseOrder'
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
            'tax_id' => 'required',
            'invoice_date' => 'required',
            'payment_terms' => 'required',
            // 'discount' => 'required',
            // 'quote_no' => 'required',
            // 'status' => 'required',
            'note' => 'nullable',
        ];

        $messages = [
            'vendor_id.required' => 'The select vendor comapny name is required.',
            'tax_id.required' => 'The Tax is required.',
            'invoice_date.required' => 'The Invoice Date is required.',
            'payment_terms.required' => 'The Payment Terms is required.',
            'discount.required' => 'The Discount is required.',
            'quote_no.required' => 'The quote number is required.',
            // 'status.required' => 'The Status is required.',
            'note.required' => 'The Note is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('purchase/add-purchase-order')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $requi_id = $request->requistition_id;
                $add_record = $this->service->submitBOMToOwner($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];
                    if ($status == 'success') {
                        return redirect('purchase/list-purchase-order/' . $requi_id)->with(compact('msg', 'status'));
                    } else {
                        return redirect('purchase/add-purchase-order')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('purchase/add-business')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function store_old(Request $request)
    {
        $rules = [
            // 'client_name' => 'required',
            // 'phone_number' => 'required',
            // 'email' => 'required',
            'tax_id' => 'required',
            'invoice_date' => 'required',
            // 'gst_number' => 'required',
            'payment_terms' => 'required',
            // 'client_address' => 'required',
            'discount' => 'required',
            'quote_no' => 'required',
            // 'status' => 'required',
            'note' => 'nullable',
        ];


        $amount = 0;
        foreach ($request->items as $item) {
            $amount += $item['amount'];
        }

        $itemsJson = json_encode($request->items);


        $invoice = new PurchaseOrdersModel([
            // 'client_name' => $request->client_name,
            // 'phone_number' => $request->phone_number,
            'tax_id' => $request->tax_id,
            // 'email' => $request->email,
            // 'client_address' => $request->client_address,
            // 'gst_number' => $request->gst_number,
            'invoice_date' => $request->invoice_date,
            'payment_terms' => $request->payment_terms,
            'items' => $itemsJson,
            'discount' => $request->discount,
            'total' => $amount,
            'note' => $request->note,
            'quote_no' => $request->quote_no,
            // 'status' => $request->status,
        ]);

        if ($invoice->save()) {
            $msg = 'Invoice has been created';
            $status = 'success';

            return redirect('purchase/list-purchase-order')->with(compact('msg', 'status'));
        } else {
            $msg = 'Failed to create invoice';
            $status = 'error';

            return redirect('purchase/add-purchase-order')->withInput()->with(compact('msg', 'status'));
        }
    }



    public function show(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        $title = 'view invoice';
        return view('organizations.purchase.addpurchasedetails.show-purchase-orders', compact('invoice', 'title'));
    }

    public function show21(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        $title = 'view invoice';
        return view('organizations.purchase.addpurchasedetails.show-purchase-orders21', compact('invoice', 'title'));
    }

    public function showpurchase(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        $title = 'view invoice';
        return view('organizations.purchase.addpurchasedetails.show-purchase-orders1', compact('invoice', 'title'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Invoice::findOrFail($request->id)->delete();
        $notification = notify('Invoice has been deleted successfully');
        return back()->with($notification);
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
            return redirect('purchase/list-purchase')->with(compact('msg', 'status'));
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
                'organizations.purchase.purchase.purchase-order-details',
                compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations','business_id')
            );


            // return view('organizations.business.purchase-order.purchase-order-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData'));


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

            return redirect('purchase/list-purchase-order-approved-sent-to-vendor')->with(compact('msg', 'status'));

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

            return view('organizations.purchase.addpurchasedetails.view-purchase-orders-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations','business_id'));
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function edit(Request $request){
        $edit_data_id = $request->id;
        // $edit_data_id = base64_decode($request->id);
        $editData = $this->service->getById($edit_data_id);
        $dataOutputVendor = Vendors::where('is_active', true)->get();
        $dataOutputTax = Tax::where('is_active', true)->get();
        $dataOutputPartItem = PartItem::where('is_active', true)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->get();
        return view('organizations.purchase.addpurchasedetails.edit-purchase-orders', compact('editData', 'dataOutputVendor', 'dataOutputTax', 'dataOutputPartItem',  'dataOutputUnitMaster',
        'dataOutputHSNMaster',));
    }
    
    
            public function update(Request $request){
                
               $rules = [
                    // 'design_name' => 'required|string|max:255',
                    // 'design_page' => 'required|max:255',
                    // 'project_name' => 'required|string|max:20',
                    // 'time_allocation' => 'required|string|max:255',
                    // 'image' => 'image|mimes:jpeg,png,jpg|max:10240|min:5',
                ];
    
                $messages = [
                            // 'design_name.required' => 'The design name is required.',
                            // 'design_name.string' => 'The design name must be a valid string.',
                            // 'design_name.max' => 'The design name must not exceed 255 characters.',
                            
                            // 'design_page.required' => 'The design page is required.',
                            // 'design_page.max' => 'The design page must not exceed 255 characters.',
                            
                            // 'project_name.required' => 'The project name is required.',
                            // 'project_name.string' => 'The project name must be a valid string.',
                            // 'project_name.max' => 'The project name must not exceed 20 characters.',
                            
                            // 'time_allocation.required' => 'The time allocation is required.',
                            // 'time_allocation.string' => 'The time allocation must be a valid string.',
                            // 'time_allocation.max' => 'The time allocation must not exceed 255 characters.',
                            
                            // 'image.required' => 'The image is required.',
                            // 'image.image' => 'The image must be a valid image file.',
                            // 'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
                            // 'image.max' => 'The image size must not exceed 10MB.',
                            // 'image.min' => 'The image size must not be less than 5KB.',
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
                                return redirect('purchase/list-purchase')->with(compact('msg', 'status'));
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

    public function destroyAddmore(Request $request){
        try {
            $delete_rti = $this->service->deleteByIdAddmore($request->delete_id);
         
            if ($delete_rti) {
                $msg = $delete_rti['msg'];
                $status = $delete_rti['status'];

                $id = base64_encode($request->delete_id);
                if ($status == 'success') {
                    return redirect('purchase/edit-purchase-order/{id}')->with(compact('msg', 'status'));
                    // return redirect()->route('purchase.edit-purchase-order', ['id' => $id])
                    // ->with(compact('msg', 'status'));
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
    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id, $business_id)
    {
        try {
            // Fetch purchase order details
            $purchaseOrder = $this->service->submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id, $business_id);
    
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
            $pdf = Pdf::loadView('organizations.common-pages.purchase-order-view', [
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
            // $vendorName = $purchaseOrder->vendor_name;
            // Mail::send([], [], function ($message) use ($purchaseOrder, $pdfPath, $vendorName) {
            //     $message->to($purchaseOrder->vendor_email)
            //         ->subject('Purchase Order Notification')
            //         ->attach($pdfPath)
            //         ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            
            //     // Set plain text body
            //     $message->text("Respected $vendorName, \n\n I hope this message finds you well.\n\nWe would like to place a purchase order with your company for the following items. Please find the details of the purchase order below:\n\nThank you!");
            // });
            
            return redirect('purchase/list-purchase-order-approved-sent-to-vendor')->with('status', 'success')->with('msg', 'Purchase order mail sent to vendor.');
    
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
}