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
    DesignRevisionForProd
    };
use Config;

class AllListRepository  {


//   public function getAllListDesignRecievedForCorrection(){
//     try {

//         $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];

//         $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
//             $join->on('business_application_processes.business_id', '=', 'production.business_id');
//           })
//           ->leftJoin('designs', function($join) {
//             $join->on('business_application_processes.business_id', '=', 'designs.business_id');
//           })
//           ->leftJoin('businesses', function($join) {
//             $join->on('business_application_processes.business_id', '=', 'businesses.id');
//           })
//           ->leftJoin('design_revision_for_prod', function($join) {
//             $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
//           })
//           ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
//           ->where('businesses.is_active',true)
//           ->select(
//               'businesses.id',
//               'businesses.title',
//               'businesses.descriptions',
//               'businesses.remarks',
//               'businesses.is_active',
//               'production.business_id',
//               'design_revision_for_prod.reject_reason_prod',
//               'design_revision_for_prod.id as design_revision_for_prod_id',
//               'designs.bom_image',
//               'designs.design_image'

//           )
//           ->distinct('design_revision_for_prod.id')
//           ->get();
//         return $data_output;
//     } catch (\Exception $e) {
//         
//         return $e;
//     }
// }



  public function getAllNewRequirement(){
    try {

        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION')];
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
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
          ->select(
              'businesses.id',
              'businesses.title',
              'businesses.descriptions',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
              'production.id as productionId',
              'design_revision_for_prod.reject_reason_prod',
              'design_revision_for_prod.id as design_revision_for_prod_id',
              'designs.bom_image',
              'designs.design_image'

          )
          ->get();
        return $data_output;
    } catch (\Exception $e) {
        return $e;
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
          
          $data_output= ProductionModel::leftJoin('businesses', function($join) {
              $join->on('production.business_id', '=', 'businesses.id');
            })
            ->leftJoin('business_application_processes', function($join) {
              $join->on('production.business_id', '=', 'business_application_processes.business_id');
            })
            ->leftJoin('designs', function($join) {
              $join->on('business_application_processes.business_id', '=', 'designs.business_id');
            })

            ->leftJoin('purchase_orders', function($join) {
              $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
            })

            ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
            ->orWhereIn('business_application_processes.store_status_id',$array_to_be_check_store)
            ->orWhereIn('purchase_orders.purchase_status_from_purchase',$array_to_be_check_purchase)
            ->orWhereIn('business_application_processes.business_status_id',$array_to_be_check_owner)
            ->orWhereIn('purchase_orders.quality_status_id',$array_to_be_check_quality)
            
            
            ->where('businesses.is_active',true)
            ->select(
                'businesses.id',
                'businesses.title',
                'businesses.descriptions',
                'businesses.remarks',
                'businesses.is_active',
                'production.business_id',
                'designs.bom_image',
                'designs.design_image',

            )->get();
          return $data_output;
      } catch (\Exception $e) {
          return $e;
      }
  }

  public function getAllrejectdesign(){
    try {

        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];

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
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
          ->select(
              'businesses.id',
              'businesses.title',
              'businesses.descriptions',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
              'design_revision_for_prod.reject_reason_prod',
              'designs.bom_image',
              'designs.design_image'

          )->get();
        return $data_output;
    } catch (\Exception $e) {
        
        return $e;
    }
  }


  
  public function getAllreviseddesign(){
    try {

        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED')];

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
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
          ->select(
              'businesses.id',
              'businesses.title',
              'businesses.descriptions',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
              'design_revision_for_prod.reject_reason_prod',
              'design_revision_for_prod.bom_image as re_bom_image',
              'design_revision_for_prod.design_image as re_design_image',
              'design_revision_for_prod.remark_by_design',
              'designs.bom_image',
              'designs.design_image',
              'production.id as productionId',
              

          )->get();

        return $data_output;
    } catch (\Exception $e) {
        
        return $e;
    }
  }




  public function getAllListMaterialRecievedToProduction(){
    try {

        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')];
        
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
        ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
        ->where('businesses.is_active',true)
        ->select(
            'businesses.id',
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


}