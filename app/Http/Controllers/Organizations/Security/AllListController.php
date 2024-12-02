<?php

namespace App\Http\Controllers\Organizations\Security;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    Business, 
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    NotificationStatus,
    
    };

class AllListController extends Controller
{ 
    
    public function getAllListMaterialRecieved(Request $request){
        try {
            $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
              })
             
              ->leftJoin('purchase_orders', function($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
              })

              ->leftJoin('businesses', function($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
              })
              ->leftJoin('businesses_details', function($join) {
                $join->on('production.business_details_id', '=', 'businesses_details.id');
            })
              ->where('purchase_orders.purchase_orders_id', 'like', '%' . $request->purchase_orders_id . '%')
              ->where('businesses.is_active',true)
              ->select(
                  'businesses_details.id',
                  'businesses.title',
                  'businesses_details.product_name',
                  'businesses_details.description',
                  'businesses.remarks',
                  'businesses.is_active',
                  'production.business_id',
                  'production.id as productionId',
                  'purchase_orders.purchase_orders_id'
                            )
              ->get();
              if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->id; 
                    if (!empty($business_id)) {
                        $update_data['po_send_to_vendor_visible_security'] = '1';
                        NotificationStatus::where('po_send_to_vendor_visible_security', '0')
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
            return view('organizations.security.list.list-recived-material', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

}