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
}