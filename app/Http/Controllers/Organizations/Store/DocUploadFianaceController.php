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

class DocUploadFianaceController extends Controller
{ 
    // public function __construct(){
    //     $this->service = new ProductionServices();
    // }



    public function index(){
        try {
          
          
            return view('organizations.store.docuploadfinance.list-docuploadfinance');
        } catch (\Exception $e) {
            return $e;
        }
    }  
    
    public function add(){
        try {
          
          
            return view('organizations.store.docuploadfinance.add-docuploadfinance');
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function edit(){
        try {
          
          
            return view('organizations.store.docuploadfinance.edit-docuploadfinance');
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

  
}
