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

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN') ];
      $array_to_be_check_new = [NULL];
      $array_to_be_quantity_tracking = [ config('constants.PRODUCTION_DEPARTMENT.INPROCESS_COMPLETED_QUANLTITY_SEND_TO_LOGISTICS')];
     
      $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
      ->leftJoin('designs', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
      })
      ->leftJoin('businesses', function($join) {
        $join->on('business_application_processes.business_id', '=', 'businesses.id');
      })
      ->leftJoin('businesses_details', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    })
      ->leftJoin('design_revision_for_prod', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
      })
      ->leftJoin('purchase_orders', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
      })

      ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
      })
      ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
      ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
      // ->whereNull('business_application_processes.logistics_status_id')====hide quantity tracking
      // ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->distinct('businesses_details.id')
      ->select(
          'businesses_details.id',
          'businesses.title',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses.title',
          'businesses_details.quantity',
          'businesses.remarks',
          'businesses.is_active',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          'production.business_id',
          'production.id as productionId',
          'business_application_processes.store_material_sent_date',
       
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
    $array_to_be_quantity_tracking = [ config('constants.LOGISTICS_DEPARTMENT.UPDATE_INPROCESS_COMPLETED_QUANLTITY_IN_LOGISTICS_DEPT')];

    $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
  })
  ->leftJoin('designs', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
  })
  ->leftJoin('businesses', function($join) {
      $join->on('business_application_processes.business_id', '=', 'businesses.id');
  })
  ->leftJoin('businesses_details', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
  })
  ->leftJoin('design_revision_for_prod', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
  })
  ->leftJoin('purchase_orders', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
  })
  ->leftJoin('tbl_logistics', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
  })
  ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
    $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
  })
  ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

  ->whereIn('business_application_processes.logistics_status_id', $array_to_be_check)
  // ->whereNull('business_application_processes.dispatch_status_id')
  ->where('businesses.is_active', true)
  ->groupBy(
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.id',
      'businesses_details.product_name',
      'businesses_details.quantity',
      'businesses_details.description',
      'business_application_processes.id',
      'tbl_customer_product_quantity_tracking.completed_quantity',
      'tbl_logistics.updated_at',
  )
  ->select(
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.id',
      'businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'tbl_customer_product_quantity_tracking.completed_quantity',
      'tbl_logistics.updated_at',
      // Add the columns here
  )->orderBy('tbl_logistics.updated_at', 'desc')
  ->get();
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

public function getAllListSendToFiananceByLogistics(){
  try {
  
    $array_to_be_check = [config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_SEND_PRODUCTION_REQUEST_TO_FINANCE')];
    $array_to_be_quantity_tracking = [ config('constants.LOGISTICS_DEPARTMENT.UPDATED_COMPLETED_QUANLTITY_LOGISTICS_DEPT_SEND_TO_FIANANCE_DEPT')];

  
      $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
      ->leftJoin('designs', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
      })
      ->leftJoin('businesses', function($join) {
        $join->on('business_application_processes.business_id', '=', 'businesses.id');
      })
    
      ->leftJoin('businesses_details', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    })
      ->leftJoin('design_revision_for_prod', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
      })
      ->leftJoin('purchase_orders', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
      })
      ->leftJoin('tbl_logistics', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
      })
      ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
      })
      ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
      ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check)
      // ->whereNull('business_application_processes.dispatch_status_id')
      // ->whereNull('business_application_processes.dispatch_status_id')
      
      // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      // ->distinct('businesses_details.id')
      ->groupBy(
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'business_application_processes.id',
        'tbl_customer_product_quantity_tracking.completed_quantity',
        'tbl_logistics.truck_no',
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
    )
      ->select(
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
        'tbl_customer_product_quantity_tracking.completed_quantity',
          // 'production.id as productionId',
          // 'business_application_processes.store_material_sent_date',
          'tbl_logistics.truck_no',
          'tbl_logistics.from_place',
          'tbl_logistics.to_place',
      )
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

}