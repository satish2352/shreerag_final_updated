<?php

namespace App\Http\Controllers\Organizations\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Services\Organizations\Productions\ProductionServices;
use Session;
use Validator;
use Config;
use Carbon;
// use App\Models\ {
//     DesignModel,
//     DesignDetailsModel
//     };

class GatepassController extends Controller
{ 
    // public function __construct(){
    //     $this->service = new ProductionServices();
    // }



    public function index(){
        try {
            return view('organizations.security.gatepass.list-gatepass');
        } catch (\Exception $e) {
            return $e;
        }
    }  
    
    public function add(){
        try {
            return view('organizations.security.gatepass.add-gatepass');
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function edit(){
        try {       
            return view('organizations.security.gatepass.edit-gatepass');
        } catch (\Exception $e) {
            return $e;
        }
    }
}