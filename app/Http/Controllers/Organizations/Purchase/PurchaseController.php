<?php

namespace App\Http\Controllers\Organizations\Purchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\PurchaseServices;
use Session;
use Validator;
use Config;
use Carbon;

class PurchaseController extends Controller
{ 
   
    public function __construct(){
        $this->service = new PurchaseServices();
    }


    public function submitBOMToOwner($id){
        try {
            $data_output = $this->service->getDetailsForPurchase($id);
            return view('organizations.purchase.addpurchasedetails.add-purchase-orders', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

  

}
