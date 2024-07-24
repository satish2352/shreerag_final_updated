<?php

namespace App\Http\Controllers\Organizations\Logistics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Logistics\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllCompletedProduction(){
        try {
            $data_output = $this->service->getAllCompletedProduction();
            return view('organizations.logistics.logisticsdept.list-production-completed', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllLogistics(){
        try {
            $data_output = $this->service->getAllLogistics();
            return view('organizations.logistics.logisticsdept.list-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}