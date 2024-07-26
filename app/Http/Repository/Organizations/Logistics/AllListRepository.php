<?php
namespace App\Http\Repository\Organizations\Logistics;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    Business, 
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    PurchaseOrdersModel
    };
use Config;

class AllListRepository  {
public function getAllCompletedProduction(){
  try {

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
      $array_to_be_check_new = [NULL];
      $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
      })
      ->leftJoin('designs', function($join) {
        $join->on('business_application_processes.business_id', '=', 'designs.business_id');
      })
      ->leftJoin('businesses', function($join) {
        $join->on('business_application_processes.business_id', '=', 'businesses.id');
      })
      ->leftJoin('design_revision_for_prod', function($join) {
        $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
      })
      ->leftJoin('purchase_orders', function($join) {
        $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
      })
      ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
      ->whereNull('business_application_processes.logistics_status_id')
      // ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->distinct('businesses.id')
      ->select(
          'businesses.id',
          'businesses.product_name',
          'businesses.title',
          'businesses.descriptions',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          'production.id as productionId',
          'design_revision_for_prod.reject_reason_prod',
          'design_revision_for_prod.id as design_revision_for_prod_id',
          'designs.bom_image',
          'designs.design_image',
          'business_application_processes.store_material_sent_date'

      )
      ->get();
      
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getAllLogistics(){
  try {
  
    $array_to_be_check = [config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_FILL_COMPLETED_PRODUCTION_FORM_IN_LOGISTICS')];
    $array_to_be_check_new = ['0'];
  
      $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
      })
      ->leftJoin('designs', function($join) {
        $join->on('business_application_processes.business_id', '=', 'designs.business_id');
      })
      ->leftJoin('businesses', function($join) {
        $join->on('business_application_processes.business_id', '=', 'businesses.id');
      })
      ->leftJoin('design_revision_for_prod', function($join) {
        $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
      })
      ->leftJoin('purchase_orders', function($join) {
        $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
      })
      ->leftJoin('tbl_logistics', function($join) {
        $join->on('business_application_processes.business_id', '=', 'tbl_logistics.business_id');
      })
      ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check)
      ->whereNull('business_application_processes.dispatch_status_id')
      
      // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->distinct('businesses.id')
      ->select(
          'businesses.id',
          'businesses.title',
          'businesses.product_name',
          'businesses.descriptions',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          'production.id as productionId',
          'design_revision_for_prod.reject_reason_prod',
          'design_revision_for_prod.id as design_revision_for_prod_id',
          'designs.bom_image',
          'designs.design_image',
          'business_application_processes.logistics_status_id',
          'tbl_logistics.truck_no',
          // 'tbl_logistics.vendor_id',
      )
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

public function getAllListSendToFiananceByLogistics(){
  try {
  
    $array_to_be_check = [config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_SEND_PRODUCTION_REQUEST_TO_FINANCE')];
    // $array_to_be_check_new = ['0'];
  
      $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
      })
      ->leftJoin('designs', function($join) {
        $join->on('business_application_processes.business_id', '=', 'designs.business_id');
      })
      ->leftJoin('businesses', function($join) {
        $join->on('business_application_processes.business_id', '=', 'businesses.id');
      })
      ->leftJoin('design_revision_for_prod', function($join) {
        $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
      })
      ->leftJoin('purchase_orders', function($join) {
        $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
      })
      ->leftJoin('tbl_logistics', function($join) {
        $join->on('business_application_processes.business_id', '=', 'tbl_logistics.business_id');
      })
      ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check)
      ->whereNull('business_application_processes.dispatch_status_id')
      // ->whereNull('business_application_processes.dispatch_status_id')
      
      // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->distinct('businesses.id')
      ->select(
          'businesses.id',
          'businesses.title',
          'businesses.product_name',
          'businesses.descriptions',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          'production.id as productionId',
          'design_revision_for_prod.reject_reason_prod',
          'design_revision_for_prod.id as design_revision_for_prod_id',
          'designs.bom_image',
          'designs.design_image',
          'business_application_processes.logistics_status_id',
          'tbl_logistics.truck_no',
          // 'tbl_logistics.vendor_id',
      )
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

}