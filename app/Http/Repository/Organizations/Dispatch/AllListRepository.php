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
    ->leftJoin('production', function($join) {
        $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
    })
    ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

      // ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
      ->where('businesses.is_active',true)
      ->where('businesses.is_deleted', 0)
      // ->distinct('businesses_details.id')
->select(
    'tbl_customer_product_quantity_tracking.id',
    'tbl_customer_product_quantity_tracking.business_details_id',
    'businesses.title',
    'businesses.project_name',
    'businesses.created_at',
    'businesses.customer_po_number',
    'businesses_details.product_name',
    'businesses.title',
    'businesses_details.quantity',
    'businesses.remarks',
    'businesses.is_active',
    'tbl_customer_product_quantity_tracking.completed_quantity',
    // DB::raw('(businesses_details.quantity - tbl_customer_product_quantity_tracking.completed_quantity) AS remaining_quantity'),
    DB::raw('(SELECT SUM(t2.completed_quantity)
    FROM tbl_customer_product_quantity_tracking AS t2
    WHERE t2.business_details_id = businesses_details.id
      AND t2.id <= tbl_customer_product_quantity_tracking.id
   ) AS cumulative_completed_quantity'),
DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
    FROM tbl_customer_product_quantity_tracking AS t2
    WHERE t2.business_details_id = businesses_details.id
      AND t2.id <= tbl_customer_product_quantity_tracking.id
   )) AS remaining_quantity'),
// DB::raw('production.updated_at AS updated_at'),
    'production.business_id',
    'production.id as productionId',
    'bap1.store_material_sent_date',
    'tbl_customer_product_quantity_tracking.updated_at',
          'tbl_transport_name.name as transport_name',
      'tbl_vehicle_type.name as vehicle_name',
           'tbl_logistics.truck_no',
    'tbl_logistics.from_place',
    'tbl_logistics.to_place',
 
) 
->orderBy('tbl_logistics.updated_at', 'desc')
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

    
    $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function($join) {
      $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
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
      ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
      // ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
      ->where('businesses.is_active',true)
      ->where('businesses.is_deleted', 0)
      ->select(
        'tbl_customer_product_quantity_tracking.id',
        'businesses.project_name',
        'businesses.created_at',
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
          'tbl_customer_product_quantity_tracking.completed_quantity',
          DB::raw('(SELECT SUM(t2.completed_quantity)
          FROM tbl_customer_product_quantity_tracking AS t2
          WHERE t2.business_details_id = businesses_details.id
            AND t2.id <= tbl_customer_product_quantity_tracking.id
         ) AS cumulative_completed_quantity'),
      DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
          FROM tbl_customer_product_quantity_tracking AS t2
          WHERE t2.business_details_id = businesses_details.id
            AND t2.id <= tbl_customer_product_quantity_tracking.id
         )) AS remaining_quantity'),
       

      )
      ->orderBy('tbl_dispatch.updated_at', 'desc')
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

public function getAllDispatchClosedProduct()
{
    try {
        $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

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
              ->leftJoin('estimation', function ($join) {
                $join->on('tbl_dispatch.business_details_id', '=', 'estimation.business_details_id');
            })
            // ->leftJoin('production_details', function ($join) {
            //     $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
            // })
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
                'businesses_details.id as business_details_id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                'estimation.total_estimation_amount',
                DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
//    DB::raw('COALESCE(SUM(production_details.items_used_total_amount), 0) as total_items_used_amount'),

                DB::raw('MAX(tbl_dispatch.updated_at) as last_updated_at') // Alias for MAX(updated_at)
            )

            ->groupBy(
                'businesses_details.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                'estimation.total_estimation_amount',
            )

            ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
            ->orderBy('last_updated_at', 'desc') // Use the alias instead of tbl_dispatch.last_updated_at
            ->get()
            ->map(function ($data) {
                $data->last_updated_at = Carbon::parse($data->last_updated_at);
                return $data;
            });

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}


}