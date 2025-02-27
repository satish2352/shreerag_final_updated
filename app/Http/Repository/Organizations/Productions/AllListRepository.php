<?php
namespace App\Http\Repository\Organizations\Productions;
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
    ProductionDetails
    };
use Config;

class AllListRepository  {
  public function getAllNewRequirement(){
    try {
        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION')];
        $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
            $join->on('business_application_processes.business_id', '=', 'production.business_id');
          })
          ->leftJoin('businesses', function($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
          ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
          ->where('businesses.is_active', true)
          ->where('businesses.is_deleted', 0)
          ->groupBy('businesses.id', 'businesses.customer_po_number', 'businesses.title', 'businesses.remarks', 'businesses.is_active', 'production.business_id', 'businesses.updated_at')
          ->select(
              'businesses.id',
              'businesses.customer_po_number',
              'businesses.title',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
              'businesses.updated_at'
          )->orderBy('businesses.updated_at', 'desc')
          ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllNewRequirementBusinessWise($business_id) {
  try {
    $decoded_business_id = base64_decode($business_id);

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION')];

      $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
          })
          ->leftJoin('businesses_details', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
          })
          ->leftJoin('designs', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
          })
          ->where('businesses_details.business_id', $decoded_business_id)
          ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
          // ->whereNull('business_application_processes.business_details_status_id')
          ->whereNull('production.is_approved_production')
          ->where('businesses_details.is_active', true)
          ->where('businesses_details.is_deleted', 0)
          ->distinct('businesses_details.id')
          ->select(
            'production.business_details_id',
              'businesses_details.product_name',
              'businesses_details.description',
              'businesses_details.quantity',
              'production.business_id',
              'production.id as productionId',
              'designs.bom_image',
              'designs.design_image',
              'business_application_processes.production_status_id',
              'business_application_processes.business_status_id',
              'business_application_processes.design_status_id',
              'production.updated_at'
          )->orderBy('production.updated_at', 'desc')
          ->get();

      return $data_output;
  } catch (\Exception $e) {
      return $e->getMessage(); // or return response()->json(['error' => $e->getMessage()], 500);
  }
}

  
  public function getAllacceptdesign(){
      try {
          $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
          $array_to_be_check_store = [
            config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE'),
            config('constants.STORE_DEPARTMENT.LIST_PO_RECEIVED_FROM_QUALITY_DEPARTMENT'),
            config('constants.STORE_DEPARTMENT.STORE_RECIEPT_GENRATED'),
          ];
          $array_to_be_check_purchase = [
            config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE'),
            config('constants.PUCHASE_DEPARTMENT.REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE_VENDOR_FINAL'),
            config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL'),
            config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY'),
            config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR'),
            config('constants.PUCHASE_DEPARTMENT.LIST_NOT_APPROVED_PO_FROM_HIGHER_AUTHORITY'),
            config('constants.PUCHASE_DEPARTMENT.NOT_APPROVED_PO_AGAIN_SENT_FOR_APPROVAL_TO_HIGHER_AUTHORITY'),
          ];


          $array_to_be_check_owner = [
            config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE'),
            config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE'),
            config('constants.HIGHER_AUTHORITY.NOT_APPROVED_PO_FROM_PURCHASE'),
            config('constants.HIGHER_AUTHORITY.LIST_APPROVED_PO_SENT_TO_VENDOR_BY_PURCHASE'),
            config('constants.HIGHER_AUTHORITY.LIST_INVOICE_TO_BE_APPROVE_FINANCE'),
            config('constants.HIGHER_AUTHORITY.APPROVED_INVOICE_FINANCE'),
            config('constants.HIGHER_AUTHORITY.NOT_APPROVED_INVOICE_FINANCE'),
          
          ];

          $array_to_be_check_quality = [
            config('constants.QUALITY_DEPARTMENT.LIST_PO_RECEIVED_FROM_SECURITY'),
            config('constants.QUALITY_DEPARTMENT.PO_CHECKED'),
            config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE'),
            config('constants.QUALITY_DEPARTMENT.PO_CHECKED_NOT_OK_RETURN_TO_VENDOR'),

          ];
          
          $data_output= BusinessApplicationProcesses::leftJoin('businesses', function($join) {
              $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('production', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
            ->leftJoin('designs', function($join) {
              $join->on('business_application_processes.business_id', '=', 'designs.business_id');
            })

            ->leftJoin('businesses_details', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
          })
            ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
            ->where('businesses.is_active',true)
            ->where('businesses.is_deleted', 0)
            ->groupBy([
              'businesses.id', 
              'businesses.customer_po_number', 
              'businesses.remarks', 
              'businesses.is_active', 
              'production.business_id', 
              'businesses.updated_at'
          ])
          ->select(
              'businesses.id',
              'businesses.customer_po_number',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
              'businesses.updated_at'
          )
          
        ->orderBy('businesses.updated_at', 'desc')->get();
          return $data_output;
       
      } catch (\Exception $e) {
          return $e;
      }
  }
  public function acceptdesignlistBusinessWise($business_id){
    try {
      $decoded_business_id = base64_decode($business_id);
        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
       
      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
        ->leftJoin('designs', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        })

        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('businesses_details', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
      })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })
         ->where('production.business_id', $decoded_business_id)
         ->where('production.is_approved_production', 1)
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses_details.is_active', true)
          ->where('businesses_details.is_deleted', 0)
          ->distinct('businesses_details.id')
          // ->where('businesses.is_active',true)
          ->select(
              'businesses_details.id',
              // 'businesses.customer_po_number',
            'businesses_details.product_name',
            // 'businesses.title',
            'businesses_details.description',
            'businesses_details.quantity',
              // 'businesses.remarks',
              'businesses_details.is_active',
              'production.business_id',
              'designs.bom_image',
              'designs.design_image',
              'design_revision_for_prod.reject_reason_prod',
              'design_revision_for_prod.bom_image as re_bom_image',
              'design_revision_for_prod.design_image as re_design_image',
              'production.updated_at',
              )->orderBy('production.updated_at', 'desc')
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
          'businesses.customer_po_number',
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
          'businesses.customer_po_number',
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
                'businesses.customer_po_number',
                'businesses.title',
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
                'businesses.customer_po_number',
                'businesses.title',
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
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.id',
            'businesses_details.product_name',
            'businesses_details.description'
        )
        ->select(
            'businesses_details.id',
            'businesses.customer_po_number',
            'businesses_details.product_name',
            'businesses.title',
            DB::raw('MAX(production.updated_at) as last_updated_at') // Use MAX aggregate function
        )
        ->orderBy('last_updated_at', 'desc')
        ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}
// public function getAllListMaterialRecievedToProductionBusinessWise($id)
// {
//     try {
//         $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')];

//         $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
//             $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
//         })
//             ->leftJoin('businesses', function ($join) {
//                 $join->on('business_application_processes.business_id', '=', 'businesses.id');
//             })
//             ->leftJoin('businesses_details', function ($join) {
//                 $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
//             })
//             ->leftJoin('tbl_customer_product_quantity_tracking', 'business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id')
//             ->leftJoin('purchase_orders', function ($join) {
//                 $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
//             })
//             ->where('businesses_details.id', $id)
//             ->where('businesses_details.is_active', true)
//             ->where('businesses.is_deleted', 0)
//             ->distinct('businesses.id')
//             ->select(
//                 'business_application_processes.id',
//                 'businesses.id as business_id',
//                 'businesses.customer_po_number',
//                 'businesses.title',
//                 'businesses_details.id as business_details_id',
//                 'businesses_details.product_name',
//                 'businesses_details.quantity',
//                 'businesses_details.description',
//                 'businesses.remarks',
//                 DB::raw('(SELECT SUM(t2.completed_quantity)
//                           FROM tbl_customer_product_quantity_tracking AS t2
//                           WHERE t2.business_details_id = businesses_details.id
//                             AND t2.id <= tbl_customer_product_quantity_tracking.id
//                          ) AS cumulative_completed_quantity'),
//                 DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
//                           FROM tbl_customer_product_quantity_tracking AS t2
//                           WHERE t2.business_details_id = businesses_details.id
//                             AND t2.id <= tbl_customer_product_quantity_tracking.id
//                          )) AS remaining_quantity'),
//                 'production.updated_at'
//             )
//             ->groupBy(
//                 'business_application_processes.id',
//                 'businesses.id',
//                 'businesses.customer_po_number',
//                 'businesses.title',
//                 'businesses_details.id',
//                 'businesses_details.product_name',
//                 'businesses_details.quantity',
//                 'businesses_details.description',
//                 'businesses.remarks',
//                 'tbl_customer_product_quantity_tracking.id', // Added to resolve the issue
//             'production.updated_at'
//                 )
//             ->get();

//         return $data_output;
//     } catch (\Exception $e) {
//         return $e;
//     }
// }
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
                DB::raw('tbl_customer_product_quantity_tracking.updated_at AS tracking_updated_at')
            )
            ->orderBy('updated_at', 'desc')
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
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.rate',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          'production.updated_at'
      )
      ->select(
        'tbl_customer_product_quantity_tracking.id',
          'businesses.customer_po_number',
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