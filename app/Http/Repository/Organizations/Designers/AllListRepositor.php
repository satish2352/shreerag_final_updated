<?php
namespace App\Http\Repository\Organizations\Designers;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    Business, 
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd
    };
use Config;

class AllListRepositor  {

  public function acceptdesignbyProduct(){
    try {
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
       
         ->where('production.is_approved_production', 1)
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
         
          ->where('businesses_details.is_active', true)
          ->distinct('businesses_details.id')
          // ->where('businesses.is_active',true)
          ->groupBy(
            'businesses.id',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.id',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            'businesses_details.rate',
            'designs.bom_image',
            'designs.design_image',
            'design_revision_for_prod.id',
            'design_revision_for_prod.design_image',
            'design_revision_for_prod.bom_image',
            'design_revision_for_prod.reject_reason_prod',
            'production.updated_at'
        )
        ->select(
            'businesses.id',
            'businesses_details.id',
            'businesses.title',
            'businesses.customer_po_number',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            'designs.bom_image',
            'designs.design_image',
            'design_revision_for_prod.reject_reason_prod',
            'design_revision_for_prod.id as design_revision_for_prod_id',
            'design_revision_for_prod.design_image as re_design_image',
            'design_revision_for_prod.bom_image as re_bom_image',
            'production.updated_at'
        )->orderBy('production.updated_at', 'desc')->get();
   
        return $data_output;
      
    } catch (\Exception $e) {
        return $e;
    }
}



public function listDesignReport(){
  try {
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
     
      //  ->where('production.is_approved_production', 1)
        // ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
       
        ->where('businesses_details.is_active', true)
        ->distinct('businesses_details.id')
        // ->where('businesses.is_active',true)
        ->groupBy(
          'businesses.id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.rate',
          'designs.bom_image',
          'designs.design_image',
          'design_revision_for_prod.id',
          'design_revision_for_prod.design_image',
          'design_revision_for_prod.bom_image',
          'design_revision_for_prod.reject_reason_prod',
          'production.updated_at'
      )
      ->select(
          'businesses.id',
          'businesses_details.id',
          'businesses.title',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'designs.bom_image',
          'designs.design_image',
          'design_revision_for_prod.reject_reason_prod',
          'design_revision_for_prod.id as design_revision_for_prod_id',
          'design_revision_for_prod.design_image as re_design_image',
          'design_revision_for_prod.bom_image as re_bom_image',
          'production.updated_at'
      )->orderBy('production.updated_at', 'desc')->get();
 
      return $data_output;
    
  } catch (\Exception $e) {
      return $e;
  }
}

//   public function getAllListDesignRecievedForCorrection(){
//     try {

//         $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];

//         $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
//             $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
//           })
//           ->leftJoin('designs', function($join) {
//             $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
//           })
//           ->leftJoin('businesses_details', function($join) {
//             $join->on('production.business_details_id', '=', 'businesses_details.id');
//         })
//           ->leftJoin('businesses', function($join) {
//             $join->on('business_application_processes.business_id', '=', 'businesses.id');
//           })
//           ->leftJoin('design_revision_for_prod', function($join) {
//             $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
//           })
//           ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
//           ->where('businesses.is_active',true)
//           ->select(
//               'businesses.id',
//               'businesses_details.id',
//               'businesses.title',
//               'businesses.customer_po_number',
//               'businesses_details.product_name',
//               'businesses_details.quantity',
//               'businesses_details.description',
//               'businesses.remarks',
//               'businesses.is_active',
//               'production.business_id',
//               'designs.bom_image',
//               'designs.design_image',
//               'design_revision_for_prod.reject_reason_prod',
//               'design_revision_for_prod.id as design_revision_for_prod_id',
//               'design_revision_for_prod.bom_image as re_bom_image',
//               'design_revision_for_prod.design_image as re_design_image')
//           ->distinct('design_revision_for_prod.id')
//           // ->orderBy('design_revision_for_prod.updated_at', 'desc')
//           ->get();
//         return $data_output;
//     } catch (\Exception $e) {
        
//         return $e;
//     }
// }
public function getAllListDesignRecievedForCorrection() {
  try {
      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];

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
              $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
          })
          ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
          ->where('businesses.is_active', true)
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
          ->orderBy('businesses.updated_at', 'desc')
          ->get();

      return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

}