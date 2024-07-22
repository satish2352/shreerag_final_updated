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
    DesignRevisionForProd
    };

class AllListController extends Controller
{ 
    
    public function getAllListMaterialRecieved(Request $request){
        try {
            $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
                $join->on('business_application_processes.business_id', '=', 'production.business_id');
              })
             
              ->leftJoin('purchase_orders', function($join) {
                $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
              })

              ->leftJoin('businesses', function($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
              })
              ->where('purchase_orders.purchase_orders_id', 'like', '%' . $request->purchase_orders_id . '%')
              ->where('businesses.is_active',true)
              ->select(
                  'businesses.id',
                  'businesses.title',
                  'businesses.descriptions',
                  'businesses.remarks',
                  'businesses.is_active',
                  'production.business_id',
                  'production.id as productionId',
                  'purchase_orders.purchase_orders_id'
                //   'design_revision_for_prod.reject_reason_prod',
                //   'design_revision_for_prod.id as design_revision_for_prod_id',
                //   'designs.bom_image',
                //   'designs.design_image'
      
              )
              ->get();
            return view('organizations.security.list.list-recived-material', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

}