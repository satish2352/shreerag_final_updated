<?php

namespace App\Http\Controllers\Organizations\Business;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Business\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListForwardedToDesign(Request $request){
        try {
            $data_output = $this->service->getAllListForwardedToDesign();
        
            return view('organizations.business.list.list-business', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    
    public function getAllListCorrectionToDesignFromProduction(Request $request){
        try {
            $data_output = $this->service->getAllListCorrectionToDesignFromProduction();
        
            return view('organizations.business.list.list-business-design-correction-from-prod', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    public function materialAskByProdToStore(Request $request){
        try {
            $data_output = $this->service->materialAskByProdToStore();
        
            return view('organizations.business.list.list-material-ask-by-prod-to-store', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllStoreDeptSentForPurchaseMaterials(Request $request){
        try {
            $data_output = $this->service->getAllStoreDeptSentForPurchaseMaterials();
        // dd( $data_output);
        // die();
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
            return view('organizations.designer.design-upload.list-design-upload', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }     



    
}