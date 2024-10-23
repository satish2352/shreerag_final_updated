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
      $array_to_be_quantity_tracking = [ config('constants.DISPATCH_DEPARTMENT.RECEIVED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

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
      ->leftJoin('tbl_transport_name', function($join) {
        $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
    })
    ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
    })
    ->leftJoin('tbl_vehicle_type', function($join) {
        $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
    })
    ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

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
        'tbl_transport_name.name',
        'tbl_vehicle_type.name',
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
        'tbl_customer_product_quantity_tracking.completed_quantity'
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
          'tbl_transport_name.name as transport_name',
          'tbl_vehicle_type.name as vehicle_name',
          'tbl_vehicle_type.name',
          'tbl_logistics.from_place',
          'tbl_logistics.to_place',
          'tbl_customer_product_quantity_tracking.completed_quantity'
          // 'tbl_logistics.vendor_id',
      )
      ->get();
        
      
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getAllDispatch(){
  try {
  
    $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
    $array_to_be_quantity_tracking = [ config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

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
      ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
      })
      ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

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
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
        'tbl_customer_product_quantity_tracking.completed_quantity'
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
          'tbl_logistics.from_place',
          'tbl_logistics.to_place',
          'tbl_customer_product_quantity_tracking.completed_quantity'
      )->orderBy('tbl_dispatch.updated_at', 'desc')
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
// public function getAllDispatchClosedProduct(){
//   try {
  
//     $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
//     $array_to_be_quantity_tracking = [ config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

//     $array_to_be_check_new = ['0'];
  
//     $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
//     })
//     ->leftJoin('designs', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
//     })
//     ->leftJoin('businesses', function($join) {
//       $join->on('business_application_processes.business_id', '=', 'businesses.id');
//     })
//     ->leftJoin('businesses_details', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
//   })
//     ->leftJoin('design_revision_for_prod', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
//     })
//     ->leftJoin('purchase_orders', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
//     })
//     ->leftJoin('tbl_logistics', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
//     })
//       ->leftJoin('tbl_dispatch', function($join) {
//         $join->on('business_application_processes.business_details_id', '=', 'tbl_dispatch.business_details_id');
//       })
//       ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
//         $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
//       })
//       ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

//       ->whereIn('business_application_processes.dispatch_status_id',$array_to_be_check)
//       // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
//       ->where('businesses.is_active',true)
//       ->distinct('businesses_details.id')
//       ->groupBy(
//         'businesses_details.id',
//         // 'businesses.id',
//         'businesses.customer_po_number',
//         'businesses.title',
//         'businesses_details.product_name',
//         'businesses_details.quantity',
//         'businesses_details.description',
//         // 'business_application_processes.id',
//         // 'tbl_logistics.business_details_id',
//         'tbl_logistics.truck_no',
//         'tbl_dispatch.outdoor_no',
//         'tbl_dispatch.gate_entry',
//         'tbl_dispatch.remark',
//         'tbl_dispatch.updated_at',
//         'tbl_logistics.from_place',
//         'tbl_logistics.to_place',
//         'tbl_customer_product_quantity_tracking.completed_quantity'
//     )
//       ->select(
//         'businesses_details.id',
//         // 'businesses.id',
//         'businesses.customer_po_number',
//         'businesses.title',
//         'businesses_details.product_name',
//         'businesses_details.description',
//         'businesses_details.quantity',
//           // 'production.id as productionId',
//           // 'business_application_processes.store_material_sent_date',
//           // 'tbl_logistics.business_details_id',
//           'tbl_logistics.truck_no',
//           // 'tbl_logistics.vendor_id',
//           'tbl_dispatch.outdoor_no',
//           'tbl_dispatch.gate_entry',
//           'tbl_dispatch.remark',
//           'tbl_dispatch.updated_at',
//           'tbl_logistics.from_place',
//           'tbl_logistics.to_place',
//           'tbl_customer_product_quantity_tracking.completed_quantity'
//       )->orderBy('tbl_dispatch.updated_at', 'desc')
//       ->get();
     
 
//     return $data_output;
//   } catch (\Exception $e) {
//       return $e;
//   }
// }
// public function getAllDispatchClosedProduct(){
//   try {
//     $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
//     $array_to_be_quantity_tracking = [ config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];
    
//     $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
//     })
//     ->leftJoin('designs', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
//     })
//     ->leftJoin('businesses', function($join) {
//       $join->on('business_application_processes.business_id', '=', 'businesses.id');
//     })
//     ->leftJoin('businesses_details', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
//     })
//     ->leftJoin('design_revision_for_prod', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
//     })
//     ->leftJoin('purchase_orders', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
//     })
//     ->leftJoin('tbl_logistics', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
//     })
//     ->leftJoin('tbl_dispatch', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'tbl_dispatch.business_details_id');
//     })
//     ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
//     })
//     ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
//     ->whereIn('business_application_processes.dispatch_status_id', $array_to_be_check)
//     ->where('businesses.is_active', true)
    
//     // Group by business_details_id and calculate sum of completed_quantity
//     ->groupBy('businesses_details.id', 'businesses.customer_po_number', 'businesses.title', 'businesses_details.product_name', 'businesses_details.quantity', 'businesses_details.description', 'tbl_logistics.truck_no', 'tbl_dispatch.outdoor_no', 'tbl_dispatch.gate_entry', 'tbl_dispatch.remark', 'tbl_dispatch.updated_at', 'tbl_logistics.from_place', 'tbl_logistics.to_place')
    
//     // Select needed fields and sum completed_quantity
//     ->select('businesses_details.id', 'businesses.customer_po_number', 'businesses.title', 'businesses_details.product_name', 'businesses_details.description', 'businesses_details.quantity', 'tbl_logistics.truck_no', 'tbl_dispatch.outdoor_no', 'tbl_dispatch.gate_entry', 'tbl_dispatch.remark', 'tbl_dispatch.updated_at', 'tbl_logistics.from_place', 'tbl_logistics.to_place', DB::raw('SUM(tbl_customer_product_quantity_tracking.completed_quantity) as total_completed_quantity'))

//     // Having clause to filter records where the sum of completed_quantity is less than the quantity in businesses_details
//     ->havingRaw('SUM(tbl_customer_product_quantity_tracking.completed_quantity) > businesses_details.quantity')

//     // Order the results by the dispatch updated date
//     ->orderBy('tbl_dispatch.updated_at', 'desc')
//     ->get();
//     dd($data_output);
//     die();
//     return $data_output;
//   } catch (\Exception $e) {
//     return $e;
//   }
// }
public function getAllDispatchClosedProduct(){
  try {
    $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
    $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];
    
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
    ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
    })
    ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
    ->whereIn('business_application_processes.dispatch_status_id', $array_to_be_check)
    ->where('businesses.is_active', true)
    
    // Group by business_details_id to calculate the sum of completed_quantity per business_details_id
    ->groupBy('businesses_details.id', 'businesses.customer_po_number', 'businesses.title', 'businesses_details.product_name', 'businesses_details.quantity', 'businesses_details.description', 'tbl_logistics.truck_no', 'tbl_dispatch.outdoor_no', 'tbl_dispatch.gate_entry', 'tbl_dispatch.remark', 'tbl_dispatch.updated_at', 'tbl_logistics.from_place', 'tbl_logistics.to_place')
    
    // Select the fields, including the sum of completed_quantity
    ->select(
        'businesses_details.id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity', 
        'tbl_logistics.truck_no',
        'tbl_dispatch.outdoor_no',
        'tbl_dispatch.gate_entry',
        'tbl_dispatch.remark',
        'tbl_dispatch.updated_at',
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
        DB::raw('SUM(tbl_customer_product_quantity_tracking.completed_quantity) as total_completed_quantity') // Sum completed_quantity per business_details_id
    )

    // Having clause to filter out records where the total completed_quantity is greater than the quantity from businesses_details
    ->havingRaw('SUM(tbl_customer_product_quantity_tracking.completed_quantity) > businesses_details.quantity')

    // Order by dispatch updated date
    ->orderBy('tbl_dispatch.updated_at', 'desc')
    ->get();
    
    return $data_output;
  } catch (\Exception $e) {
    return $e;
  }
}

}