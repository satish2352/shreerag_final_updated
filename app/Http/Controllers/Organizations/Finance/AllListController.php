<?php

namespace App\Http\Controllers\Organizations\Finance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Finance\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListSRAndGRNGeanrated(Request $request){
        try {
            $data_output = $this->service->getAllListSRAndGRNGeanrated();
        
            return view('organizations.finance.list.list-sr-and-gr-genrated-business', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function listAcceptedGrnSrnFinance($purchase_orders_id){
        try {
            // $data_output = $this->service->listAcceptedGrnSrnFinance();
        // , compact('data_output')
            return view('organizations.finance.list.list-material-details-sr-and-gr-genrated-business',compact('purchase_orders_id'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function listPOSentForApprovaTowardsOwner(Request $request){
        try {
            $data_output = $this->service->listPOSentForApprovaTowardsOwner();
        
            return view('organizations.finance.list.list-po-submited-for-sanction-towards-owner', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    

    
    public function listPOSanctionAndNeedToDoPaymentToVendor(Request $request){
        try {
            $data_output = $this->service->listPOSanctionAndNeedToDoPaymentToVendor();
        
            return view('organizations.finance.list.list-po-sanction-and-need-to-do-payment-to-vendor', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    


    
    // public function getAllListMaterialSentToPurchase(){

    //     try {
    //         $data_output = $this->service->getAllListMaterialSentToPurchase();
        
    //         return view('organizations.finance.list.list-material-sent-to-purchase', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }


    
    
    // public function getAllListMaterialReceivedFromQuality(){

    //     try {
    //         $data_output = $this->service->getAllListMaterialReceivedFromQuality();
        
    //         return view('organizations.finance.list.list-material-received-from-quality', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    

}