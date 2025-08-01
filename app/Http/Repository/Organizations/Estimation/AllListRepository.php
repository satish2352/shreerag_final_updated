<?php
namespace App\Http\Repository\Organizations\Estimation;
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
    BusinessDetails,
    CustomerProductQuantityTracking,
    ProductionDetails,
    EstimationModel
    };
use Config;

class AllListRepository  {
  public function getAllNewRequirement(){
    try {
         $send_estimation =config('constants.ESTIMATION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_ESTIMATION');
         
        $data_output = BusinessApplicationProcesses::leftJoin('estimation', function($join) {
            $join->on('business_application_processes.business_id', '=', 'estimation.business_id');
          })
          ->leftJoin('businesses', function($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
           ->whereNull('business_application_processes.bom_estimation_send_to_owner')
           ->where('business_application_processes.design_send_to_estimation',$send_estimation)
          ->where('businesses.is_active', true)
          ->where('businesses.is_deleted', 0)
          ->select(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.remarks',
                'businesses.grand_total_amount',
                'businesses.is_active',
                'businesses.created_at',
                'estimation.business_id',
                DB::raw('MAX(estimation.updated_at) as updated_at')
                
            )
            ->groupBy(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.remarks',
                'businesses.grand_total_amount',
                'businesses.is_active',
                'businesses.created_at',
                'estimation.business_id'
              
            )
            ->orderBy('updated_at', 'desc')

          ->orderBy('estimation.updated_at', 'desc')
          ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllNewRequirementBusinessWise($business_id) {
  try {
    $decoded_business_id = base64_decode($business_id);

      $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_ESTIMATION_DEPT_FIRST_TIME')];

      $data_output = BusinessApplicationProcesses::leftJoin('estimation', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
          })
          ->leftJoin('businesses_details', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
          })
          ->leftJoin('designs', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
          })
          ->where('businesses_details.business_id', $decoded_business_id)
           ->whereNull('business_application_processes.bom_estimation_send_to_owner')
          ->whereIn('business_application_processes.design_send_to_estimation',$array_to_be_check)
          ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
          ->whereNull('estimation.is_approved_estimation')
          ->where('businesses_details.is_active', true)
          ->where('businesses_details.is_deleted', 0)
          ->distinct('businesses_details.id')
          ->select(
            'estimation.business_details_id',
              'businesses_details.product_name',
              'businesses_details.description',
              'businesses_details.quantity',
               'businesses_details.total_amount',
              'estimation.business_id',
              'estimation.id as estimationId',
              'designs.bom_image',
              'designs.design_image',
              'business_application_processes.business_status_id',
              'business_application_processes.design_status_id',
              'estimation.updated_at'
          )->orderBy('estimation.updated_at', 'desc')
          ->get();

      return $data_output;
  } catch (\Exception $e) {
      return $e->getMessage(); 
  }
}  

public function getAllEstimationSendToOwnerForApproval()
{
    try {
        $array_to_be_check = config('constants.HIGHER_AUTHORITY.ESTIMATION_DEPT_THROUGH_RECEIVED_BOM');

        $data_output = EstimationModel::leftJoin('businesses', function($join) {
                $join->on('estimation.business_id', '=', 'businesses.id');
            })
            ->leftJoin('business_application_processes', function($join) {
                $join->on('estimation.business_id', '=', 'business_application_processes.business_id');
            })
            ->where('business_application_processes.bom_estimation_send_to_owner', $array_to_be_check)
            ->whereNull('business_application_processes.owner_bom_accepted')
            ->whereNull('business_application_processes.owner_bom_rejected')
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.remarks',
                'businesses.grand_total_amount',
                'businesses.updated_at',
                 'businesses.created_at',
                \DB::raw('MAX(estimation.business_id) as business_id'),
                \DB::raw('MAX(estimation.business_details_id) as business_details_id')
            )
            ->groupBy(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.remarks',
                  'businesses.grand_total_amount',
                'businesses.updated_at',
                 'businesses.created_at'
            )
            ->orderBy('businesses.updated_at', 'desc')
            ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllEstimationSendToOwnerForApprovalBusinessWise($business_id) {
   try {
        $decoded_business_id = base64_decode($business_id);

         $array_to_be_check = config('constants.HIGHER_AUTHORITY.ESTIMATION_DEPT_THROUGH_RECEIVED_BOM');    

        $data_output = BusinessApplicationProcesses::leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
          
            ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
              ->leftJoin('estimation', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
            })
          
            ->where('business_application_processes.business_id', $decoded_business_id)
            ->where('business_application_processes.bom_estimation_send_to_owner', $array_to_be_check)
            ->where('business_application_processes.is_active', true)
            ->where('business_application_processes.is_deleted', 0)
            ->select(
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                 'businesses_details.total_amount',
                 'estimation.total_estimation_amount',
                DB::raw('MAX(designs.bom_image) as bom_image'),
                DB::raw('MAX(designs.design_image) as design_image'),
                'estimation.total_estimation_amount',
            )
            ->groupBy(
                'businesses_details.id',
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                 'businesses_details.total_amount',
                'estimation.total_estimation_amount',
                'businesses_details.description',
                 'estimation.total_estimation_amount',
            )
            ->get();
      return $data_output;
  } catch (\Exception $e) {
      return $e->getMessage(); // or return response()->json(['error' => $e->getMessage()], 500);
  }
} 
  public function acceptBOMlist(){
   
    try {
         $array_to_be_check = config('constants.HIGHER_AUTHORITY.OWNER_BOM_ESTIMATION_ACCEPTED');    
        $data_output = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->where('business_application_processes.owner_bom_accepted', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                 'businesses.remarks',
                 'businesses.updated_at'
             
            )
            ->groupBy(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                 'businesses.remarks',
                  'businesses.updated_at'
            )
            ->get();
        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}
  public function acceptBOMlistBusinessWise($business_id){
    
    try {
       $decoded_business_id = base64_decode($id);
      
         $accepted = config('constants.HIGHER_AUTHORITY.OWNER_BOM_ESTIMATION_ACCEPTED'); 
          $received = config('constants.HIGHER_AUTHORITY.ESTIMATION_DEPT_THROUGH_RECEIVED_BOM');   
        $data_output = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
               ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
             ->leftJoin('estimation', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
            })
            ->where('business_application_processes.bom_estimation_send_to_owner', $received)
            ->where('business_application_processes.owner_bom_accepted', $accepted)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
               'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                DB::raw('MAX(designs.bom_image) as bom_image'),
                DB::raw('MAX(designs.design_image) as design_image'),
                'estimation.total_estimation_amount',
             
            )
            ->groupBy(
                'businesses_details.id',
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                 'estimation.total_estimation_amount',
            )
            ->get();
        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}
  public function getAllrejectdesign(){
    try {

        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];
        $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
      ->leftJoin('businesses', function($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
      })
      ->leftJoin('businesses_details', function($join) {
          $join->on('production.business_details_id', '=', 'businesses_details.id');
      })
      ->leftJoin('designs', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
      })
      ->leftJoin('design_revision_for_prod', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
      })
      ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
      ->where('businesses.is_active', true)
      ->where('businesses.is_deleted', 0)
      ->groupBy(
          'businesses.id',
          'businesses_details.id',
          'businesses.project_name',
          'businesses.customer_po_number',
          'businesses.created_at',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.is_active',
          'production.business_id',
          'businesses.updated_at',
          'designs.bom_image',
          'designs.design_image'
      )
      ->select(
          'businesses.id',
          'businesses_details.id',
          'businesses.project_name',
          'businesses.customer_po_number',
          'businesses.created_at',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.is_active',
          'production.business_id',
          DB::raw('MAX(COALESCE(design_revision_for_prod.reject_reason_prod, "")) as reject_reason_prod'),
          'businesses.updated_at',
          'designs.bom_image',
          'designs.design_image'
      )
      ->orderBy('businesses.updated_at', 'desc')
      ->get();
        return $data_output;
    } catch (\Exception $e) {
        
        return $e;
    }
  }
  
public function getSendToProductionList()
{
    try {
        $array_to_be_check = config('constants.ESTIMATION_DEPARTMENT.UPDATED_ACCEPTED_BOM_SEND_TO_PRODUCTION');

        $data_output = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
            ->leftJoin('estimation', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
            })
            ->where('business_application_processes.estimation_send_to_production', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.remarks',
                'estimation.updated_at',

                // These fields are not in groupBy, so we wrap them in MAX()
                DB::raw('MAX(businesses_details.product_name) as product_name'),
                DB::raw('MAX(businesses_details.quantity) as quantity'),
                DB::raw('MAX(businesses_details.description) as description'),

                DB::raw('MAX(designs.bom_image) as bom_image'),
                DB::raw('MAX(designs.design_image) as design_image'),

                'estimation.total_estimation_amount'
            )
            ->groupBy(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.remarks',
                'estimation.updated_at',
                'estimation.total_estimation_amount'
            )
            ->orderBy('estimation.updated_at', 'desc')
            ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}


  public function getAllRevisedDesign() {
    try {
        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED')];

        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
            ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('production.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('design_revision_for_prod', function ($join) {
                $join->on('designs.id', '=', 'design_revision_for_prod.design_id');
            })
            ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
                'business_application_processes.id',
                'businesses.id as business_id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.id as business_details_id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'businesses.remarks',
                DB::raw('MAX(design_revision_for_prod.reject_reason_prod) as reject_reason_prod'), // Aggregated
                DB::raw('MAX(designs.bom_image) as bom_image'), // Aggregated
                DB::raw('MAX(designs.design_image) as design_image'), // Aggregated
                DB::raw('MAX(design_revision_for_prod.bom_image) as re_bom_image'), // Aggregated
                DB::raw('MAX(design_revision_for_prod.design_image) as re_design_image'), // Aggregated
                DB::raw('MAX(design_revision_for_prod.remark_by_design) as remark_by_design'), // Aggregated    
                DB::raw('MAX(design_revision_for_prod.updated_at) as updated_at'), // Aggregated                
                )
            ->groupBy(
                'business_application_processes.id',
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'businesses.remarks',
                'design_revision_for_prod.updated_at',
            )
            ->orderBy('design_revision_for_prod.updated_at', 'desc')
            ->get();

        return $data_output;

    } catch (\Exception $e) {
        // Log the exception for debugging
        \Log::error('Error in getAllRevisedDesign: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function getAllListMaterialRecievedToProduction()
{
    try {
        $array_to_be_check = [
            config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')
        ];

        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
        ->leftJoin('designs', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        })
        ->leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('businesses_details', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('design_revision_for_prod', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })
        ->leftJoin('purchase_orders', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->where('production.production_status_quantity_tracking', 'incomplete')
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->distinct('businesses.id')
        ->groupBy(
            'businesses.project_name',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses.created_at',
            'businesses_details.id',
            'businesses_details.product_name',
            'businesses_details.description'
        )
        ->select(
            'businesses_details.id',
            'businesses.project_name',
            'businesses.customer_po_number',
            'businesses_details.product_name',
            'businesses.title',
            'businesses.created_at',
            DB::raw('MAX(production.updated_at) as last_updated_at') // Use MAX aggregate function
        )
        ->orderBy('last_updated_at', 'desc')
        ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllListMaterialRecievedToProductionBusinessWise($id)
{
    try {
        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('tbl_customer_product_quantity_tracking', 'business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id')
            ->leftJoin('purchase_orders', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
            })
            ->where('businesses_details.id', $id)
            ->where('businesses_details.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
                'business_application_processes.id',
                'businesses.id as business_id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.id as business_details_id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'businesses.remarks',
                DB::raw('(SELECT SUM(t2.completed_quantity)
                          FROM tbl_customer_product_quantity_tracking AS t2
                          WHERE t2.business_details_id = businesses_details.id) 
                          AS completed_quantity'),
                DB::raw('(businesses_details.quantity - 
                          (SELECT SUM(t2.completed_quantity)
                          FROM tbl_customer_product_quantity_tracking AS t2
                          WHERE t2.business_details_id = businesses_details.id)) 
                          AS remaining_quantity'),
                'production.updated_at'
            )
            ->groupBy(
                'business_application_processes.id',
                'businesses.id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'businesses.remarks',
                'production.updated_at'
            )
            ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllCompletedProduction() {
    try {
        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
        $array_to_be_quantity_tracking = [config('constants.PRODUCTION_DEPARTMENT.INPROCESS_COMPLETED_QUANLTITY_SEND_TO_LOGISTICS')];

        $data_output = DB::table('business_application_processes')
            ->leftJoin('production', 'business_application_processes.business_details_id', '=', 'production.business_details_id')
            ->leftJoin('businesses', 'business_application_processes.business_id', '=', 'businesses.id')
            ->leftJoin('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
            ->leftJoin('tbl_customer_product_quantity_tracking', 'business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id')
            // ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->select(
                'tbl_customer_product_quantity_tracking.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses_details.id as business_details_id',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
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
                DB::raw('production.updated_at AS updated_at'),
                DB::raw('tbl_customer_product_quantity_tracking.updated_at AS tracking_updated_at'),
                DB::raw('tbl_customer_product_quantity_tracking.completed_quantity AS completed_quantity')
            )
            ->orderBy('tbl_customer_product_quantity_tracking.updated_at', 'desc')
            ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}
public function getAllCompletedProductionSendToLogistics()
{
    try {
        $array_to_be_check = [
            config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')
        ];

        $array_to_be_quantity_tracking = [
            config('constants.PRODUCTION_DEPARTMENT.INPROCESS_COMPLETED_QUANLTITY_SEND_TO_LOGISTICS')
        ];



        $data_output = CustomerProductQuantityTracking::leftJoin('production', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'production.business_details_id');
      })
      ->leftJoin('designs', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'designs.business_details_id');
      })
      ->leftJoin('businesses', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
      })
      ->leftJoin('businesses_details', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'businesses_details.id');
      })
      ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'design_revision_for_prod.business_details_id');
      })
      ->leftJoin('purchase_orders', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'purchase_orders.business_details_id');
      })
      ->where(function ($query) {
          $query->whereNotNull('tbl_customer_product_quantity_tracking.completed_quantity')
              ->WhereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', [3001, 3002, 3003, 3004, 3005]);
      })
      ->where('businesses.is_active', true)
      ->where('businesses.is_deleted', 0)
      ->distinct('businesses.id')
      ->groupBy(
          'tbl_customer_product_quantity_tracking.id',
          'businesses_details.id',
          'businesses.project_name',
          'businesses.customer_po_number',
          'businesses.created_at',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.rate',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          'production.updated_at'
      )
      ->select(
        'tbl_customer_product_quantity_tracking.id',
          'businesses.project_name',
          'businesses.customer_po_number',
          'businesses.created_at',
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          'production.updated_at'
      )
      ->orderBy('production.updated_at', 'desc')
      ->get();

        return $data_output;

    } catch (\Exception $e) {
        // Return the exception message for debugging
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getAllCompletedProductionSendToLogisticsProductWise($id) {
  try {
      $array_to_be_check = [
          config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')
      ];
      $dataOutputByid = ProductionDetails::leftJoin('production', function ($join) {
        $join->on('production_details.production_id', '=', 'production.id');
    })
   
    ->leftJoin('businesses', function ($join) {
        $join->on('production_details.business_id', '=', 'businesses.id');
    })
    ->leftJoin('businesses_details', function ($join) {
        $join->on('production_details.business_details_id', '=', 'businesses_details.id');
    })  
          ->leftJoin('tbl_unit', 'production_details.unit', '=', 'tbl_unit.id')  
          ->where('businesses_details.id', $id)
          ->where('businesses_details.is_active', true)
          ->where('businesses_details.is_deleted', 0)
          ->select(
              'businesses_details.id',
              'businesses_details.product_name',
              'businesses_details.description',
              'production_details.part_item_id',
              'production_details.quantity',
              'production_details.unit',
              'tbl_unit.name as unit_name', 
              'production_details.business_details_id',
              'production_details.material_send_production',
          )
          ->get(); 

      $productDetails = $dataOutputByid->first(); 
      $dataGroupedById = $dataOutputByid->groupBy('business_details_id');

      return [
          'productDetails' => $productDetails,
          'dataGroupedById' => $dataGroupedById
      ];
  } catch (\Exception $e) {
      return [
          'status' => 'error',
          'msg' => $e->getMessage()
      ];
  }
}
}