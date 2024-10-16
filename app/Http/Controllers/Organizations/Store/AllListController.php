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
    GRNModel,
    NotificationStatus

};

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListDesignRecievedForMaterial(Request $request){
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterial();
// dd($data_output);
// die();
        //     $first_business_id = optional($data_output->first())->id;

        //     if ($first_business_id) {
        //     $update_data['store_is_view'] = '1';
        //     NotificationStatus::where('store_is_view', '0')
        //         ->where('business_id', $first_business_id) 
        //         ->update($update_data);
        // }
            return view('organizations.store.list.list-accepted-design', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllListDesignRecievedForMaterialBusinessWise($business_id, Request $request)
    {
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterialBusinessWise($business_id);
        //   
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->business_details_id; 
                    if (!empty($business_details_id)) {
                        $update_data['store_is_view'] = '1';
                        NotificationStatus::where('store_is_view', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.store.list.list-accepted-design-business-wise', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }
           
            return view('organizations.store.list.list-accepted-design-business-wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    public function getAllListMaterialSentToProduction(Request $request){
        try {
            $data_output = $this->service->getAllListMaterialSentToProduction();
        
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->id; 
                    if (!empty($business_details_id)) {
                        $update_data['material_received_from_store'] = '1';
                        NotificationStatus::where('material_received_from_store', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.store.list.list-material-sent-to-prod', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }
        
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
          
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->id; 
                    if (!empty($business_id)) {
                        $update_data['received_material_to quality'] = '1';
                        NotificationStatus::where('received_material_to quality', '0')
                            ->where('id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.security.list.list-recived-material', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
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