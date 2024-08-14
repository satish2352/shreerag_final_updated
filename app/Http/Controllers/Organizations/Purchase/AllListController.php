<?php

namespace App\Http\Controllers\Organizations\Purchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    // public function getAllListDesignRecievedForMaterial(Request $request){
    //     try {
    //         $data_output = $this->service->getAllListDesignRecievedForMaterial();
        
    //         return view('organizations.store.list.list-accepted-design', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 

    // public function getAllListMaterialSentToProduction(Request $request){
    //     try {
    //         $data_output = $this->service->getAllListMaterialSentToProduction();
        
    //         return view('organizations.store.list.list-material-sent-to-prod', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 


    
    public function getAllListMaterialReceivedForPurchase(){

        try {
            $data_output = $this->service->getAllListMaterialReceivedForPurchase();
            // dd($data_output);
            // die();
            return view('organizations.purchase.list.list-bom-material-recived-for-purchase', compact('data_output'));
            // return view('organizations.purchase.forms.send-vendor-details-for-purchase', compact('data_output'));
        } catch (\Exception $e) {
            
            return $e;
        }
    }


    
    public function getAllListApprovedPurchaseOrder(Request $request){
        try {
            $data_output = $this->service->getAllListApprovedPurchaseOrder();
            return view('organizations.purchase.list.list-purchase-order-approved-need-to-check', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseOrderSentToOwnerForApprovalBusinesWise($id){
        try {
            $data_output = $this->service->getPurchaseOrderSentToOwnerForApprovalBusinesWise($id);
           
            return view('organizations.purchase.list.list-purchase-order-approved-need-to-check-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    

    public function getAllListPurchaseOrderMailSentToVendor(Request $request){
        try {

            $data_output = $this->service->getAllListPurchaseOrderMailSentToVendor();
            return view('organizations.purchase.list.list-purchase-order-approved-sent-to-vendor', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListPurchaseOrderMailSentToVendorBusinessWise($id){
        try {
            $data_output = $this->service->getAllListPurchaseOrderMailSentToVendorBusinessWise($id);
           
            return view('organizations.purchase.list.list-purchase-order-approved-sent-to-vendor-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSubmitedPurchaeOrderByVendor(Request $request){
        try {

            $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendor();
            return view('organizations.purchase.list.list-all-po-sent-to-vendor', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListSubmitedPurchaeOrderByVendorBusinessWise($id){
        try {
            $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendorBusinessWise($id);
           
            return view('organizations.purchase.list.list-all-po-sent-to-vendor-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    public function getAllListPurchaseOrderTowardsOwner(Request $request){
        try {

            $data_output = $this->service->getAllListPurchaseOrderTowardsOwner();
            return view('organizations.purchase.list.list-purchase-order-need-to-check', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    

}