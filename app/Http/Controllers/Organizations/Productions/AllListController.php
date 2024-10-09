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
    BusinessApplicationProcesses,
    NotificationStatus
};

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
    public function getAllNewRequirement(Request $request){
        try {
            $data_output = $this->service->getAllNewRequirement();
           $first_business_id = optional($data_output->first())->id;
            if ($first_business_id) {
            $update_data['prod_is_view'] = '1';
            NotificationStatus::where('prod_is_view', '0')
                ->where('business_id', $first_business_id) 
                ->update($update_data);
        }
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
            $first_business_id = optional($data_output->first())->id;
            if ($first_business_id) {
            $update_data['prod_design_accepted'] = '1';
            NotificationStatus::where('prod_design_accepted', '0')
                ->where('business_id', $first_business_id) 
                ->update($update_data);
        }
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
             if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->id; 
                    if (!empty($business_details_id)) {
                        $update_data['design_is_view_resended'] = '1';
                        NotificationStatus::where('design_is_view_resended', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.designer.list.list_design_received_from_production_for_correction', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }
            return view('organizations.productions.product.list-design-revised', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    
    public function getAllListMaterialRecievedToProduction(){
        try {
            $data_output = $this->service->getAllListMaterialRecievedToProduction();
            // dd( $data_output);
            // die();
           
            if ($data_output->isNotEmpty()) {
               foreach ($data_output as $data) {
                   $business_details_id = $data->id; 
                   if (!empty($business_details_id)) {
                       $update_data['material_received_from_store'] = '1';
                       NotificationStatus::where('material_received_from_store', '0')
                           ->where('business_details_id', $business_details_id)
                           ->update($update_data);
                   }
               }
           } else {
               return view('organizations.productions.product.list-recived-material', [
                   'data_output' => [],
                   'message' => 'No data found for designs received for correction'
               ]);
           }

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