<?php

namespace App\Http\Controllers\Organizations\Dispatch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Dispatch\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllReceivedFromFianance(){
        try {
            $data_output = $this->service->getAllReceivedFromFianance();
            return view('organizations.dispatch.dispatchdept.list-business-received-from-fianance', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatch(){
        try {
            $data_output = $this->service->getAllDispatch();
            return view('organizations.dispatch.dispatchdept.list-dispatch', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}