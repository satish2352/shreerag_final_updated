<?php

namespace App\Http\Controllers\Organizations\Business;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Business\BusinessServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Http\Controllers\Organizations\CommanController;

class BusinessController extends Controller
{ 
    public function __construct(){
        $this->service = new BusinessServices();
        $this->serviceCommon = new CommanController();
    }
    public function index()
    {
        try {
            $data_output = $this->service->getAll();
            return view('organizations.business.business.list-business', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function add(){
        try {
            return view('organizations.business.business.add-business');
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function store(Request $request){
        $rules = [
                'title' => 'required|string|max:255',
                'customer_po_number' => 'required',
                'quantity' => 'required',
                'po_validity' => 'required',
                'hsn_number' => 'required',
                'customer_payment_terms' => 'required',
                'customer_terms_condition' => 'required',
                'remarks' => 'required',
            ];

            $messages = [
                        'title.required' => 'The design customer name is required.',
                        'title.string' => 'The design customer name must be a valid string.',
                        'title.max' => 'The design customer name must not exceed 255 characters.',
                        'customer_po_number.required' => 'The customer po number is required.',
                        'quantity.required' => 'The customer quantity is required.',
                        'po_validity.required' => 'The po validity is required.',
                        'hsn_number.required' => 'The hsn number is required.',
                        'customer_payment_terms.required' => 'The customer payment terms is required.',
                        'customer_terms_condition.required' => 'The customer terms condition is required.',
                        'remarks.required' => 'The remarks is required.',
                                            ];
  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('owner/add-business')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);

                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('owner/list-forwarded-to-design')->with(compact('msg', 'status'));
                      } else {
                          return redirect('owner/add-business')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('owner/add-business')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }
      public function edit(Request $request){
        try {     

            $edit_data_id = base64_decode($request->id);
            $editData = $this->service->getById($edit_data_id);
            // dd($editData);
            return view('organizations.business.business.edit-business', compact('editData'));
        } catch (\Exception $e) {
            return $e;
        }
    }
       public function update(Request $request){
        $rules = [
            'customer_po_number' => 'required',
            'quantity' => 'required',
            'po_validity' => 'required',
            'hsn_number' => 'required',
            'customer_payment_terms' => 'required',
            'customer_terms_condition' => 'required',
            'remarks' => 'required',
            ];       
        $messages = [
            'title.required' => 'The design customer name is required.',
            'title.string' => 'The design customer name must be a valid string.',
            'title.max' => 'The design customer name must not exceed 255 characters.',
            'customer_po_number.required' => 'The customer po number is required.',
            'quantity.required' => 'The customer quantity is required.',
            'po_validity.required' => 'The po validity is required.',
            'hsn_number.required' => 'The hsn number is required.',
            'customer_payment_terms.required' => 'The customer payment terms is required.',
            'customer_terms_condition.required' => 'The customer terms condition is required.',
            'remarks.required' => 'The remarks is required.',
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
                        return redirect('owner/list-business')->with(compact('msg', 'status'));
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
                    return redirect('owner/list-business')->with(compact('msg', 'status'));
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
    public function submitFinalPurchaseOrder($id){
        try {
            $data_output = $this->service->getPurchaseOrderBusinessWise($id);
            // dd($data_output);
            // die();
            return view('organizations.business.list.list-purchase-order-particular-po', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    public function submitFinalPurchaseOrderBusinessWise($purchase_order_id){

        try {
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();

            $data = $this->serviceCommon->getPurchaseOrderDetails($purchase_order_id);
            // dd($data);
            // die();
            $getAllRulesAndRegulations = $this->serviceCommon->getAllRulesAndRegulations();

            $purchaseOrder = $data['purchaseOrder'];
            $purchaseOrderDetails = $data['purchaseOrderDetails'];

            return view('organizations.business.purchase-order.purchase-order-details', compact('purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'getAllRulesAndRegulations'));

        } catch (\Exception $e) {
            return $e;
        }
    } 


    public function acceptPurchaseOrder($purchase_order_id)
    {
        try {
            
            $delete = $this->service->acceptPurchaseOrder($purchase_order_id);
            if ($delete) {
                $status = 'success';
                $msg ='Purchase order accepted.';
            } else {
                $status = 'success';
                $msg ='Purchase order accepted.';
            }  

            return redirect('owner/list-purchase-orders')->with(compact('msg', 'status'));
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }

}