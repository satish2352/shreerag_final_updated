<?php

namespace App\Http\Controllers\Organizations\Productions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Productions\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

use App\Models\{
    BusinessApplicationProcesses
};

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

            $update_data['prod_is_view'] = '1';
            BusinessApplicationProcesses::where('production_status_id', 1113)
                                          ->where('design_status_id', 1113)
                                          ->where('prod_is_view', '0')
                                          ->update($update_data);
          
            return view('organizations.productions.product.list_design_received_for_production', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllNewRequirementBusinessWise($business_id){
        try {
            $data_output = $this->service->getAllNewRequirementBusinessWise($business_id);
            return view('organizations.productions.product.list_design_received_for_production_business_wise', compact('data_output'));
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
    public function acceptdesignlistBusinessWise($business_id){
        try {
            $data_output = $this->service->acceptdesignlistBusinessWise($business_id);
            return view('organizations.productions.product.list-design-accepted-business-wise', compact('data_output'));
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

            $update_data['prod_is_view_revised'] = '1';
            BusinessApplicationProcesses::where('production_status_id', 1116)
                                          ->where('design_status_id', 1116)
                                          ->where('prod_is_view_revised', '0')
                                          ->update($update_data);

            return view('organizations.productions.product.list-design-revised', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    
    public function getAllListMaterialRecievedToProduction(){
        try {
            $data_output = $this->service->getAllListMaterialRecievedToProduction();

            $update_data['prod_is_view_material_received'] = '1';
            BusinessApplicationProcesses::where('production_status_id', 1119)
                                          ->where('store_status_id', 1118)
                                          ->where('prod_is_view_material_received', '0')
                                          ->update($update_data);

            return view('organizations.productions.product.list-recived-material', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    
    public function getAllListMaterialRecievedToProductionBusinessWise($id){
        try {
            $data_output = $this->service->getAllListMaterialRecievedToProductionBusinessWise($id);
           
            return view('organizations.productions.product.list-recived-bussinesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllCompletedProduction(){
        try {
            $data_output = $this->service->getAllCompletedProduction();
            return view('organizations.productions.product.list-production-completed', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
}