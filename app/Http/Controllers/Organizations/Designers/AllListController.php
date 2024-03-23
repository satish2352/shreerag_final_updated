<?php

namespace App\Http\Controllers\Organizations\Designers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Designers\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListDesignRecievedForCorrection(Request $request){
        try {
            $data_output = $this->service->getAllListDesignRecievedForCorrection();
        
            return view('organizations.designer.list.list_design_received_from_production_for_correction', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

}