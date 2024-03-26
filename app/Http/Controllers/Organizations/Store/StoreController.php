<?php

namespace App\Http\Controllers\Organizations\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\StoreServices;
use Session;
use Validator;
use Config;
use Carbon;


class StoreController extends Controller
{ 
    public function __construct(){
        $this->service = new StoreServices();
    }



    public function orderAcceptedAndMaterialForwareded($id){
        try {
            $acceptdesign = base64_decode($id);
            $update_data = $this->service->orderAcceptedAndMaterialForwareded($acceptdesign);
            return redirect('list-accepted-design-from-prod');
        } catch (\Exception $e) {
            return $e;
        }
    } 


    public function createRequesition($id){
        try {
            $acceptdesign = base64_decode($id);
            $update_data = $this->service->orderAcceptedAndMaterialForwareded($acceptdesign);
            return redirect('list-accepted-design-from-prod');
        } catch (\Exception $e) {
            return $e;
        }
    } 


    
    
 


}
