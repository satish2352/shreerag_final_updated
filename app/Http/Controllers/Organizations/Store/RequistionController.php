<?php

namespace App\Http\Controllers\Organizations\Store;

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

class RequistionController extends Controller
{ 
    // public function __construct(){
    //     $this->service = new ProductionServices();
    // }



    public function index(){
        try {
          
            $data_output = $this->service->getDataOutput();
            return view('organizations.store.requistion.list-requistion',compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }  
    
    public function add(){
        try {
          
          
            return view('organizations.store.requistion.add-requistion');
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function edit(){
        try {
          
          
            return view('organizations.store.requistion.edit-requistion');
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

    // public function add(){
    //     return view('organizations.productions.products.add-products');
    // }



}
