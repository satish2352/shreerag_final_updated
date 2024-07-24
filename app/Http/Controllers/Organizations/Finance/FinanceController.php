<?php

namespace App\Http\Controllers\Organizations\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Finance\FinanceServices;
use Session;
use Validator;
use Config;
use Carbon;


class FinanceController extends Controller
{
    public function __construct()
    {
        $this->service = new FinanceServices();
    }

    public function forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id)
    {
        try {
            $update_data = $this->service->forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id);
    
            if (is_array($update_data)) {
                return redirect('financedept/list-sr-and-gr-genrated-business')->with('success', 'Purchase order forwarded successfully.');
            } else {
                return redirect('financedept/list-sr-and-gr-genrated-business')->with('error', $update_data);
            }
        } catch (\Exception $e) {
            return redirect('financedept/list-sr-and-gr-genrated-business')->with('error', $e->getMessage());
        }
    }
    

    // public function forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id)
    // {
    //     try {
    //         $update_data = $this->service->forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id);
    //         return redirect('financedept/list-sr-and-gr-genrated-business');
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }


    
    public function forwardedPurchaseOrderPaymentToTheVendor($purchase_orders_id, $business_id)
    {
        try {
            $update_data = $this->service->forwardedPurchaseOrderPaymentToTheVendor($purchase_orders_id, $business_id);
            return redirect('financedept/list-po-sanction-and-need-to-do-payment-to-vendor');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function sendToDispatch($id){
        try {
            $accepted = base64_decode($id);
            $update_data = $this->service->sendToDispatch($accepted);
            return redirect('financedept/list-business-send-to-dispatch');
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    // public function createRequesition($createRequesition)
    // {
    //     try {
    //         return view('organizations.store.requistion.add-requistion', compact('createRequesition'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }



    // public function storeRequesition(Request $request)
    // {
    //     // $rules = [
    //     //     'production_id' => 'required',
    //     //     // 'bom_file_req' => 'required|image|mimes:jpeg,png,jpg|',
    //     // ];

    //     // $rules['bom_file_req'] = 'required|image|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.REQUISITION_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.REQUISITION_IMAGE_MIN_SIZE");


    //     // $messages = [
    //     //     'production_id.required' => '',

    //     //     // 'bom_file_req.required' => 'The image is required.',
    //     //     // 'bom_file_req.mimes' => 'The image must be excel format.',
    //     //     // 'bom_file_req.max' => 'The image size must not exceed ' . Config::get("AllFileValidation.REQUISITION_IMAGE_MAX_SIZE") . 'KB .',
    //     //     // 'bom_file_req.min' => 'The image size must not be less than ' . Config::get("AllFileValidation.REQUISITION_IMAGE_MIN_SIZE") . 'KB .',
    //     // ];


    //     try {
    //         // $validation = Validator::make($request->all(), $rules, $messages);

    //         // if ($validation->fails()) {
    //         //     return redirect('add-requistion')
    //         //         ->withInput()
    //         //         ->withErrors($validation);
    //         // } else {
    //         $add_record = $this->service->addAll($request);

    //         if ($add_record) {
    //             $msg = $add_record['msg'];
    //             $status = $add_record['status'];

    //             if ($status == 'success') {
    //                 return redirect('list-material-sent-to-purchase')->with(compact('msg', 'status'));
    //             } else {
    //                 return redirect('add-requistion')->withInput()->with(compact('msg', 'status'));
    //             }
    //         }
    //         // }
    //     } catch (Exception $e) {
    //         return redirect('add-requistion')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
    //     }
    // }



    // public function genrateStoreReciptAndForwardMaterialToTheProduction($id)
    // {
    //     try {
    //         $acceptdesign = base64_decode($id);
    //         $update_data = $this->service->genrateStoreReciptAndForwardMaterialToTheProduction($acceptdesign);
    //         return redirect('list-accepted-design-from-prod');
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }




}
