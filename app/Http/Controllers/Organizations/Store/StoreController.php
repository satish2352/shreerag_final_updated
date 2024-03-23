<?php

namespace App\Http\Controllers\Organizations\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\ProductionServices;
use Session;
use Validator;
use Config;
use Carbon;


class StoreController extends Controller
{ 
    public function __construct(){
        $this->service = new ProductionServices();
    }



    public function index(){
        try {
          
          
            return view('organizations.store.requistion.list-requistion');
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
