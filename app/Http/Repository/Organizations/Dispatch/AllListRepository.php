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
    PurchaseOrdersModel,
    Logistics,
    CustomerProductQuantityTracking
    };
use Config;

class AllListRepository  {
public function getAllReceivedFromFianance(){
  try {

      $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_RECEIVED_FROM_FINANCE_ACCORDING_TO_LOGISTICS')];
      $array_to_be_quantity_tracking = [ config('constants.DISPATCH_DEPARTMENT.RECEIVED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

      $array_to_be_check_new = [NULL];
      $data_output = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function($join) {
        $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
    })
      ->leftJoin('businesses', function($join) {
              $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
          })
          ->leftJoin('business_application_processes as bap1', function($join) {
              $join->on('tbl_customer_product_quantity_tracking.business_application_processes_id', '=', 'bap1.id');
          })
          ->leftJoin('businesses_details', function($join) {
            $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'businesses_details.id');
        })
    ->leftJoin('tbl_transport_name', function($join) {
        $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
    })
    ->leftJoin('tbl_vehicle_type', function($join) {
        $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
    })
    //   $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //   })
    //   ->leftJoin('designs', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    //   })
    //   ->leftJoin('businesses', function($join) {
    //     $join->on('business_application_processes.business_id', '=', 'businesses.id');
    //   })
    //   ->leftJoin('businesses_details', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    // })
    //   ->leftJoin('design_revision_for_prod', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    //   })
    //   ->leftJoin('purchase_orders', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
    //   })
    //   ->leftJoin('tbl_logistics', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
    //   })
    //   ->leftJoin('tbl_transport_name', function($join) {
    //     $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
    // })
    // ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
    //   $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
    // })
    // ->leftJoin('tbl_vehicle_type', function($join) {
    //     $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
    // })
    ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

      // ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
      ->where('businesses.is_active',true)
      // ->distinct('businesses_details.id')
      ->groupBy(
      'tbl_customer_product_quantity_tracking.id','tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
        'businesses.customer_po_number',
        'businesses.title',
        // 'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'bap1.id',
        'tbl_logistics.truck_no',
        'tbl_transport_name.name',
        'tbl_vehicle_type.name',
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
        'tbl_customer_product_quantity_tracking.completed_quantity'
    )
      ->select(
  'tbl_customer_product_quantity_tracking.id','tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
        'businesses.customer_po_number',
        'businesses.title',
        // 'businesses_details.id',
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
    $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function($join) {
      $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
  })
  ->leftJoin('businesses', function($join) {
      $join->on('tbl_logistics.business_id', '=', 'businesses.id');
  })
  ->leftJoin('business_application_processes as bap1', function($join) {
      $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
  })
  ->leftJoin('businesses_details', function($join) {
      $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
  })
  ->leftJoin('tbl_transport_name', function($join) {
      $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
  })
  ->leftJoin('tbl_vehicle_type', function($join) {
      $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
  })
  ->leftJoin('tbl_dispatch', function($join) {
  $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
})
      ->whereIn('tcqt1.quantity_tracking_status',$array_to_be_quantity_tracking)
      // ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
      ->where('businesses.is_active',true)
      ->groupBy(
        'tcqt1.id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'tbl_logistics.truck_no',
        'tbl_dispatch.outdoor_no',
        'tbl_dispatch.gate_entry',
        'tbl_dispatch.remark',
        'tbl_dispatch.updated_at',
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
        'tcqt1.completed_quantity'
    )
      ->select(
        'tcqt1.id',
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
          'tcqt1.completed_quantity'
      )->orderBy('tbl_dispatch.updated_at', 'desc')
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
// public function getAllDispatchClosedProduct() {
//   try {
//       $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
//       $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];
      
//       $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function($join) {
//               $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
//           })
//           ->leftJoin('businesses', function($join) {
//               $join->on('tbl_logistics.business_id', '=', 'businesses.id');
//           })
//           ->leftJoin('business_application_processes as bap1', function($join) {
//               $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
//           })
//           ->leftJoin('businesses_details', function($join) {
//               $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
//           })
//           ->leftJoin('tbl_dispatch', function($join) {
//               $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
//           })
//           ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
//           ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
//           ->where('businesses.is_active', true)
          
//           // Group by business_details_id and other selected fields
//           ->groupBy(
//               'businesses_details.id',
//               'businesses.customer_po_number',
//               'businesses.title',
//               'businesses_details.product_name',
//               'businesses_details.description',
//               'businesses_details.quantity',
//               'tbl_dispatch.updated_at'
//           )
          
//           // Select the fields, including sum of quantities
//           ->select(
//               'businesses_details.id as business_details_id',
//               'businesses.customer_po_number',
//               'businesses.title',
//               'businesses_details.product_name',
//               'businesses_details.description',
//               'businesses_details.quantity',
//                'tbl_dispatch.updated_at',
//               DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity')
//           )
          
//           // Filter records where the sum of completed_quantity matches the quantity
//           ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
          
//           ->orderBy('businesses_details.id', 'asc')
//           ->get();

//       return $data_output;
//   } catch (\Exception $e) {
//       return $e;
//   }
// }
public function getAllDispatchClosedProduct()
{
    try {
        // Define constants for checks
        $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

        // Base query
        $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
            })
            ->leftJoin('businesses', function ($join) {
                $join->on('tbl_logistics.business_id', '=', 'businesses.id');
            })
            ->leftJoin('business_application_processes as bap1', function ($join) {
                $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('tbl_dispatch', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
            })
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)

            // Select fields
            ->select(
                'businesses_details.id as business_details_id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
                DB::raw('MAX(tbl_dispatch.updated_at) as last_updated_at') // Get the last updated_at value
            )

            // Group by necessary fields only
            ->groupBy(
                'businesses_details.id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity'
            )

            // Ensure completed quantity matches the required quantity
            ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')

            // Order by ID for consistent results
            ->orderBy('businesses_details.id', 'asc')
            ->get()
            ->map(function($data) {
                // Convert last_updated_at to Carbon instance if it's not already
                $data->last_updated_at = Carbon::parse($data->last_updated_at);
                return $data;
            });
// dd($data_output);
// die();
        return $data_output;
    } catch (\Exception $e) {
        // Return exception for debugging purposes
        return $e;
    }
}

// public function getAllDispatchClosedProduct() {
//   try {
//       $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
//       $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];
      
//       $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function($join) {
//               $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
//           })
//           ->leftJoin('businesses', function($join) {
//               $join->on('tbl_logistics.business_id', '=', 'businesses.id');
//           })
//           ->leftJoin('business_application_processes as bap1', function($join) {
//               $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
//           })
//           ->leftJoin('businesses_details', function($join) {
//               $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
//           })
//           ->leftJoin('tbl_dispatch', function($join) {
//               $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
//           })
//           ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
//           ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
//           ->where('businesses.is_active', true)
          
//           // Group by business_details_id and other selected fields
//           ->groupBy(
//             'businesses_details.id',
//             'businesses.customer_po_number',
//             'businesses.title',
//             'businesses_details.product_name',
//             'businesses_details.description',
//             'businesses_details.quantity' // Include this field
//         )
        
//           // Select the fields, including sum of quantities
//           ->select(
//             'businesses_details.id as business_details_id',
//             'businesses.customer_po_number',
//             'businesses.title',
//             'businesses_details.product_name',
//             'businesses_details.description',
//              'businesses_details.quantity',
//             // DB::raw('SUM(businesses_details.quantity) as total_quantity'), // Use SUM or another function
//             DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity')
//         )
        
//           ->orderBy('businesses_details.id', 'asc')
//           ->get();

//       return $data_output;
//   } catch (\Exception $e) {
//       return $e;
//   }
// }

}