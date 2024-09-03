<?php

namespace App\Http\Controllers\Organizations\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    Business,
    BusinessApplicationProcesses,
    AdminView,
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    GRNModel

};

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListDesignRecievedForMaterial(Request $request){
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterial();

            $update_data['store_is_view'] = '1';
            BusinessApplicationProcesses::where('production_status_id',1114)
                                          ->where('design_status_id',1114)
                                          ->where('store_is_view', '0')
                                          ->update($update_data);
        
            return view('organizations.store.list.list-accepted-design', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllListDesignRecievedForMaterialBusinessWise($business_id, Request $request)
    {
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterialBusinessWise($business_id);
            // dd($data_output); // Uncomment for debugging
            // die(); // Uncomment for debugging
            return view('organizations.store.list.list-accepted-design-business-wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    public function getAllListMaterialSentToProduction(Request $request){
        try {
            $data_output = $this->service->getAllListMaterialSentToProduction();
        
            return view('organizations.store.list.list-material-sent-to-prod', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 


    
    public function getAllListMaterialSentToPurchase(){

        try {
            $data_output = $this->service->getAllListMaterialSentToPurchase();
            return view('organizations.store.list.list-material-sent-to-purchase', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    
    
    public function getAllListMaterialReceivedFromQuality(){

        try {
            $data_output = $this->service->getAllListMaterialReceivedFromQuality();
            return view('organizations.store.list.list-material-received-from-quality', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function submitFinalPurchaseOrder($id){
        try {
            $data_output = $this->service->getPurchaseOrderBusinessWise($id);
           
            return view('organizations.store.list.list-material-received-from-quality-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getGRNDetails($purchase_orders_id)
    {
        try {
            $idtoedit = base64_decode($purchase_orders_id);
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $idtoedit)->first();
            $grn_data = GRNModel::where('purchase_orders_id', '=', $idtoedit)->first();
            $po_id = $purchase_order_data->id;

            $purchase_order_details_data = PurchaseOrderDetailsModel::where('purchase_id', $po_id)
                ->get();
              
            return view('organizations.store.list.list-grn', compact('purchase_order_data', 'purchase_order_details_data', 'grn_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllInprocessProductProduction(){
        try {
            $data_output = $this->service->getAllInprocessProductProduction();
           
            return view('organizations.store.list.list-material-received-from-production-inprocess', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

}