<?php

namespace App\Http\Controllers\Organizations\Quality;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Quality\GRNServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel
    };

class GRNController extends Controller
{ 
    public function __construct(){
        $this->service = new GRNServices();
    }



    public function index(){
        try {
            return view('organizations.quality.grn.list-grn');
        } catch (\Exception $e) {
            return $e;
        }
    }  
    
    public function add(){
        try {
            $purchase_order_data=PurchaseOrdersModel::where('id', '=', '1')->first();
            $po_id=$purchase_order_data->id;

            $purchase_order_details_data = PurchaseOrderDetailsModel::where('purchase_id', $po_id)
                        ->get();

            return view('organizations.quality.grn.add-grn', compact('purchase_order_data','purchase_order_details_data'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function store(Request $request){
        $rules = [
            'grn_date' => 'required',
            'purchase_id' => 'required',
            'po_date' => 'required',
            ];

            $messages = [
                        'grn_date.required' => 'The Client Name is required.',
                        'purchase_id.required' => 'The Phone Number is required.',
                        'po_date.required' => 'The Email is required.',
                        // 'tax.required' => 'The Tax is required.',
                        // 'invoice_date.required' => 'The Invoice Date is required.',
                        // 'gst_number.required' => 'The GST Number is required.',
                        // 'payment_terms.required' => 'The Payment Terms is required.',
                        // 'client_address.required' => 'The Client Address is required.',
                        // 'discount.required' => 'The Discount is required.',
                        // 'status.required' => 'The Status is required.',
                        // 'note.required' => 'The Note is required.',
                                            ];
  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('add-grn')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->storeGRN($request);
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('list-grn')->with(compact('msg', 'status'));
                      } else {
                          return redirect('add-grn')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('add-business')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }

    public function edit(){
        try {       
            return view('organizations.quality.grn.edit-grn');
        } catch (\Exception $e) {
            return $e;
        }
    }
}