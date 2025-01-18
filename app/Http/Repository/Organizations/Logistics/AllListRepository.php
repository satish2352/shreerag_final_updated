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
    PurchaseOrdersModel,
    Logistics,
    CustomerProductQuantityTracking
    };
use Config;

class AllListRepository  {
public function getAllCompletedProduction(){
  try {

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN') ];
      $array_to_be_check_new = [NULL];
      $array_to_be_quantity_tracking = [ config('constants.LOGISTICS_DEPARTMENT.INPROCESS_COMPLETED_QUANLTITY_RECEIVED_FROM_PRODUCTION')];
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
  ->leftJoin('production', function($join) {
    $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
})
      // ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
      //   $join->on('bap1.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
      // })
      ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
      ->whereIn('bap1.production_status_id',$array_to_be_check)
      // ->whereNull('business_application_processes.logistics_status_id')====hide quantity tracking
      // ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->distinct('businesses_details.id')
      ->select(
          'tbl_customer_product_quantity_tracking.id',
          'tbl_customer_product_quantity_tracking.business_details_id',
          'businesses.title',
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
          'tbl_customer_product_quantity_tracking.updated_at'
       
      ) 
      ->orderBy('tbl_customer_product_quantity_tracking.updated_at', 'desc')
      ->get();
      
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

// public function getAllLogistics(){
//   try {
  
//     $array_to_be_check = [config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_FILL_COMPLETED_PRODUCTION_FORM_IN_LOGISTICS')];
//     $array_to_be_quantity_tracking = [ config('constants.LOGISTICS_DEPARTMENT.UPDATE_INPROCESS_COMPLETED_QUANLTITY_IN_LOGISTICS_DEPT')];

//     $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
//   })
//   ->leftJoin('designs', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
//   })
//   ->leftJoin('businesses', function($join) {
//       $join->on('business_application_processes.business_id', '=', 'businesses.id');
//   })
//   ->leftJoin('businesses_details', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
//   })
//   ->leftJoin('design_revision_for_prod', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
//   })
//   ->leftJoin('purchase_orders', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
//   })
//   ->leftJoin('tbl_logistics', function($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
//   })
// //   ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
// //     $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id')
// //          ->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
// // })

//   ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
//     $join->on('business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
//   })
//   ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

//   ->whereIn('business_application_processes.logistics_status_id', $array_to_be_check)
//   // ->whereNull('business_application_processes.dispatch_status_id')
//   ->where('businesses.is_active', true)
//   ->groupBy(
//       'businesses.customer_po_number',
//       'businesses.title',
//       'businesses_details.id',
//       'businesses_details.product_name',
//       'businesses_details.quantity',
//       'businesses_details.description',
//       'business_application_processes.id',
//       'tbl_customer_product_quantity_tracking.completed_quantity',
//       'tbl_logistics.updated_at',
//   )
//   ->select(
//       'businesses.customer_po_number',
//       'businesses.title',
//       'businesses_details.id',
//       'businesses_details.product_name',
//       'businesses_details.description',
//       'businesses_details.quantity',
//       'tbl_customer_product_quantity_tracking.completed_quantity',
//       'tbl_logistics.updated_at',
//       // Add the columns here
//   )->orderBy('tbl_logistics.updated_at', 'desc')
//   ->get();
//     return $data_output;
//   } catch (\Exception $e) {
//       return $e;
//   }
// }
public function getAllLogistics() {
  try {
      $array_to_be_check = [config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_FILL_COMPLETED_PRODUCTION_FORM_IN_LOGISTICS')];
      $array_to_be_quantity_tracking = [config('constants.LOGISTICS_DEPARTMENT.UPDATE_INPROCESS_COMPLETED_QUANLTITY_IN_LOGISTICS_DEPT')];

      $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function($join) {
        $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
    })
      ->leftJoin('businesses', function($join) {
              $join->on('tbl_logistics.business_id', '=', 'businesses.id');
          })
          ->leftJoin('business_application_processes as bap1', function($join) {
              $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
          })
          ->leftJoin('production', function($join) {
            $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
        })
          ->leftJoin('businesses_details', function($join) {
              $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
          })
        
            ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
          // ->whereIn('bap1.logistics_status_id', $array_to_be_check)
          // ->whereNull('bap1.dispatch_status_id')
          ->where('businesses.is_active', true)
          // ->groupBy(
          //   'tbl_customer_product_quantity_tracking.id',
          //   'tbl_customer_product_quantity_tracking.business_details_id',
          //     'businesses.customer_po_number',
          //     'businesses.title',
          //     // 'businesses_details.id',
          //     'businesses_details.product_name',
          //     'businesses_details.quantity',
          //     'businesses_details.description',
          //     // 'bap1.id',
          //     'tbl_customer_product_quantity_tracking.completed_quantity',
          //     'tbl_logistics.updated_at'
          // )
          // ->select(
          //   'tbl_customer_product_quantity_tracking.id',
          //   'tbl_customer_product_quantity_tracking.business_details_id',
          //     'businesses.customer_po_number',
          //     'businesses.title',
          //     // 'businesses_details.id',
          //     DB::raw('(businesses_details.quantity - tbl_customer_product_quantity_tracking.completed_quantity) AS remaining_quantity'),
          //     'businesses_details.product_name',
          //     'businesses_details.description',
          //     'businesses_details.quantity',
          //     'tbl_customer_product_quantity_tracking.completed_quantity',
          //     'tbl_logistics.updated_at'
          // )
          ->select(
            'tbl_customer_product_quantity_tracking.id',
            'tbl_customer_product_quantity_tracking.business_details_id',
            'businesses.title',
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
            'tbl_customer_product_quantity_tracking.updated_at'
         
        ) 
          ->orderBy('tbl_logistics.updated_at', 'desc')
          ->get();
      
      return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}


public function getAllListSendToFiananceByLogistics(){
  try {
  
    // $array_to_be_check = [config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_SEND_PRODUCTION_REQUEST_TO_FINANCE')];
    $array_to_be_quantity_tracking = [ config('constants.LOGISTICS_DEPARTMENT.UPDATED_COMPLETED_QUANLTITY_LOGISTICS_DEPT_SEND_TO_FIANANCE_DEPT')];

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
        ->leftJoin('production', function($join) {
          $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
      })
      // ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
      // ->whereIn('bap1.logistics_status_id',$array_to_be_check)
    
      ->where('businesses.is_active',true)
      // ->distinct('businesses_details.id')
      ->select(
        'tbl_customer_product_quantity_tracking.id',
        'tbl_customer_product_quantity_tracking.business_details_id',
        'businesses.title',
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
               'tbl_logistics.truck_no',
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
     
    ) 
    ->orderBy('tbl_logistics.updated_at', 'desc')
    //   ->groupBy(
    //     'tbl_customer_product_quantity_tracking.id',
    //     'businesses.customer_po_number',
    //     'businesses.title',
    //     'businesses_details.id',
    //     'businesses_details.product_name',
    //     'businesses_details.quantity',
    //     'businesses_details.description',
    //     'bap1.id',
    //     'tbl_customer_product_quantity_tracking.completed_quantity',
    //     'tbl_logistics.truck_no',
    //     'tbl_logistics.from_place',
    //     'tbl_logistics.to_place',
    // )
    //   ->select(
    //     'tbl_customer_product_quantity_tracking.id',
    //     'businesses.customer_po_number',
    //     'businesses.title',
    //     'businesses_details.id',
    //     'businesses_details.product_name',
    //     'businesses_details.description',
    //     'businesses_details.quantity',
    //     'tbl_customer_product_quantity_tracking.completed_quantity',
    //     DB::raw('(businesses_details.quantity - tbl_customer_product_quantity_tracking.completed_quantity) AS remaining_quantity'),
    //       // 'production.id as productionId',
    //       // 'business_application_processes.store_material_sent_date',
    //       'tbl_logistics.truck_no',
    //       'tbl_logistics.from_place',
    //       'tbl_logistics.to_place',
    //   )
      ->get();
     
 
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

}