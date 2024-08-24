<?php

namespace App\Http\Controllers\Organizations\Business;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Business\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    AdminView
};
class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListForwardedToDesign(Request $request){
        try {
            $data_output = $this->service->getAllListForwardedToDesign();

            $update_data_admin['is_view'] = '1';
            AdminView::where('current_department', 1112)
                        ->where('is_view', '0')
                        ->update($update_data_admin);
            return view('organizations.business.list.list-business', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    
    public function getAllListCorrectionToDesignFromProduction(Request $request){
        try {
            $data_output = $this->service->getAllListCorrectionToDesignFromProduction();

            $update_data_admin['is_view'] = '1';
            AdminView::where('current_department', 1115)
                        ->where('is_view', '0')
                        ->update($update_data_admin);
        
            return view('organizations.business.list.list-business-design-correction-from-prod', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    public function materialAskByProdToStore(Request $request){
        try {
            $data_output = $this->service->materialAskByProdToStore();
        
            $update_data_admin['is_view'] = '1';
            AdminView::where('current_department', 1114)
                        ->where('is_view', '0')
                        ->update($update_data_admin);

            return view('organizations.business.list.list-material-ask-by-prod-to-store', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllStoreDeptSentForPurchaseMaterials(Request $request){
        try {
            $data_output = $this->service->getAllStoreDeptSentForPurchaseMaterials();

            return view('organizations.business.list.list-material-list-from-store-to-purchase', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    
    public function getAllListPurchaseOrder(Request $request){
        try {
            $data_output = $this->service->getAllListPurchaseOrder();
        
            return view('organizations.business.list.list-purchase-order-need-to-check', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllListApprovedPurchaseOrderOwnerlogin(Request $request){
        try {
            $data_output = $this->service->getAllListApprovedPurchaseOrderOwnerlogin();
            return view('organizations.business.list.list-purchase-order-approved', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function submitFinalPurchaseOrder($id){
        try {
            $data_output = $this->service->getPurchaseOrderBusinessWise($id);
        //    dd($data_output);
        //    die();
            return view('organizations.business.list.list-purchase-order-approved-bussinesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listPOReceivedForApprovaTowardsOwner(Request $request){
        try {
            $data_output = $this->service->listPOReceivedForApprovaTowardsOwner();
            return view('organizations.business.list.list-po-received-for-sanction-towards-owner', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function loadDesignSubmittedForProduction(){
        try {
            $data_output = $this->service->loadDesignSubmittedForProduction();

            $update_data_admin['is_view'] = '1';
            AdminView::where('current_department', 1113)
                        ->where('is_view', '0')
                        ->update($update_data_admin);

            return view('organizations.business.design.list-design-upload', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }  
    public function loadDesignSubmittedForProductionBusinessWise($business_id){
        try {
            $data_output = $this->service->loadDesignSubmittedForProductionBusinessWise($business_id);
            return view('organizations.business.design.list-design-uploaded-owner-business-wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }  
    
    public function listProductDispatchCompletedFromDispatch(){
        try {
            $data_output = $this->service->listProductDispatchCompletedFromDispatch();
            return view('organizations.business.list.list-dispatch-completed', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }  

    

    
}