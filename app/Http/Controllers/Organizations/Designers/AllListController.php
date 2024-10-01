<?php

namespace App\Http\Controllers\Organizations\Designers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Designers\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    BusinessApplicationProcesses,
    AdminView
};

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListDesignRecievedForCorrection(Request $request){
        try {
            $data_output = $this->service->getAllListDesignRecievedForCorrection();

            $update_data['design_is_view_rejected'] = '1';
            BusinessApplicationProcesses::where('business_status_id', 1115)
                                          ->where('design_status_id', 1115)
                                          ->where('design_is_view_rejected', '0')
                                          ->update($update_data);
                                          
        
            return view('organizations.designer.list.list_design_received_from_production_for_correction', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

}