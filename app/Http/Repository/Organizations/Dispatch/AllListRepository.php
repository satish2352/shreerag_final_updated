<?php
namespace App\Http\Repository\Organizations\Dispatch;
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
public function getAllReceivedFromFianance(){
  try {

      $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_RECEIVED_FROM_FINANCE_ACCORDING_TO_LOGISTICS')];
      $array_to_be_check_new = [NULL];
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
      ->whereIn('business_application_processes.dispatch_status_id',$array_to_be_check)
      // ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check_new)
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
        'tbl_logistics.truck_no',
    )
      ->select(
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
          // 'production.id as productionId',
          // 'business_application_processes.store_material_sent_date',
          'tbl_logistics.truck_no',
          // 'tbl_logistics.vendor_id',
      )
      ->get();
        // ->select(
        //     'businesses.id',
        //     'businesses_details.id',
        //     'businesses.title',
        //     'businesses.customer_po_number',
        //     'businesses_details.product_name',
        //     'businesses.title',
        //     'businesses_details.quantity',
        //     'businesses.remarks',
        //     'businesses.is_active',
        //     'production.business_id',
        //     'production.id as productionId',
        //     'business_application_processes.store_material_sent_date',
        //     'tbl_logistics.truck_no',
        //     // 'tbl_logistics.vendor_id',
        // );
      
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getAllDispatch(){
  try {
  
    $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
    $array_to_be_check_new = ['0'];
  
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
      ->leftJoin('tbl_dispatch', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'tbl_dispatch.business_details_id');
      })
      ->whereIn('business_application_processes.dispatch_status_id',$array_to_be_check)
      // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->distinct('businesses_details.id')
      ->groupBy(
        'businesses_details.id',
        // 'businesses.id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        // 'business_application_processes.id',
        // 'tbl_logistics.business_details_id',
        'tbl_logistics.truck_no',
        'tbl_dispatch.outdoor_no',
        'tbl_dispatch.gate_entry',
        'tbl_dispatch.remark',
        'tbl_dispatch.updated_at',
    )
      ->select(
        'businesses_details.id',
        // 'businesses.id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
          // 'production.id as productionId',
          // 'business_application_processes.store_material_sent_date',
          // 'tbl_logistics.business_details_id',
          'tbl_logistics.truck_no',
          // 'tbl_logistics.vendor_id',
          'tbl_dispatch.outdoor_no',
          'tbl_dispatch.gate_entry',
          'tbl_dispatch.remark',
          'tbl_dispatch.updated_at',
      )
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
}