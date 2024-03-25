<?php

namespace App\Http\Controllers\Organizations\Productions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Productions\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    // public function getAllListForwardedToDesign(Request $request){
    //     try {
    //         $data_output = $this->service->getAllListForwardedToDesign();
        
    //         return view('organizations.business.list.list-business', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 
    
    // public function getAllListCorrectionToDesignFromProduction(Request $request){
    //     try {
    //         $data_output = $this->service->getAllListCorrectionToDesignFromProduction();
        
    //         return view('organizations.business.list.list-business-design-correction-from-prod', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 



    
    public function getAllNewRequirement(Request $request){
        try {
            $data_output = $this->service->getAllNewRequirement();
            return view('organizations.productions.product.list_design_received_for_production', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 


    public function acceptdesignlist(){
        try {
            $data_output = $this->service->getAllacceptdesign();
            return view('organizations.productions.product.list-design-accepted', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 


    
    public function rejectdesignlist(){
        try {
            $data_output = $this->service->getAllrejectdesign();

            return view('organizations.productions.product.list-design-rejected', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function reviseddesignlist(){
        try {
            $data_output = $this->service->getAllreviseddesign();

            return view('organizations.productions.product.list-design-revised', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    
    public function getAllListMaterialRecievedToProduction(){
        try {
            $data_output = $this->service->getAllListMaterialRecievedToProduction();

            return view('organizations.productions.product.list-recived-material', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

}