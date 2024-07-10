<?php
namespace App\Http\Repository\Organizations\Purchase;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
  Business,
  DesignModel,
  BusinessApplicationProcesses,
  ProductionModel,
  DesignRevisionForProd
};
use Config;

class AllListRepository
{



  // public function getAllListDesignRecievedForMaterial(){
  //     try {

  //         $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];

  //         $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
  //           $join->on('business_application_processes.business_id', '=', 'production.business_id');
  //         })
  //         ->leftJoin('designs', function($join) {
  //           $join->on('business_application_processes.business_id', '=', 'designs.business_id');
  //         })
  //         ->leftJoin('businesses', function($join) {
  //           $join->on('business_application_processes.business_id', '=', 'businesses.id');
  //         })
  //         ->leftJoin('design_revision_for_prod', function($join) {
  //           $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
  //         })
  //         ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
  //         ->where('businesses.is_active',true)
  //         ->select(
  //             'businesses.id',
  //             'businesses.title',
  //             'businesses.descriptions',
  //             'businesses.remarks',
  //             'businesses.is_active',
  //             'production.business_id',
  //             'production.id as productionId',
  //             'design_revision_for_prod.reject_reason_prod',
  //             'design_revision_for_prod.id as design_revision_for_prod_id',
  //             'designs.bom_image',
  //             'designs.design_image'

  //         )
  //         ->get();
  //       return $data_output;
  //     } catch (\Exception $e) {
  //         
  //         return $e;
  //     }
  // }

  // public function getAllListMaterialSentToProduction(){
  //   try {

  //       $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];

  //       $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
  //         $join->on('business_application_processes.business_id', '=', 'production.business_id');
  //       })
  //       ->leftJoin('designs', function($join) {
  //         $join->on('business_application_processes.business_id', '=', 'designs.business_id');
  //       })
  //       ->leftJoin('businesses', function($join) {
  //         $join->on('business_application_processes.business_id', '=', 'businesses.id');
  //       })
  //       ->leftJoin('design_revision_for_prod', function($join) {
  //         $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
  //       })
  //       ->whereIn('business_application_processes.store_status_id',$array_to_be_check)
  //       ->where('businesses.is_active',true)
  //       ->select(
  //           'businesses.id',
  //           'businesses.title',
  //           'businesses.descriptions',
  //           'businesses.remarks',
  //           'businesses.is_active',
  //           'production.business_id',
  //           'production.id as productionId',
  //           'design_revision_for_prod.reject_reason_prod',
  //           'design_revision_for_prod.id as design_revision_for_prod_id',
  //           'designs.bom_image',
  //           'designs.design_image'

  //       )
  //       ->get();
  //     return $data_output;
  //   } catch (\Exception $e) {
  //       return $e;
  //   }
  // }


  public function getAllListMaterialReceivedForPurchase()
  {
    try {
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')];

      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
      })
        ->leftJoin('designs', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'designs.business_id');
        })
        ->leftJoin('requisition', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'requisition.business_id');
        })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
        })

        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
        })
        
        ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
        ->where('businesses.is_active', true)
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
          'requisition.id as requistition_id',

        )
        ->get();
      return $data_output;
    } catch (\Exception $e) {
      return $e;
    }
  }



  public function getAllListApprovedPurchaseOrder()
  {
    try {

      $array_to_be_check = [config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE')];

      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
      })
        ->leftJoin('designs', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'designs.business_id');
        })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
        })
        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
        })
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
        ->whereNull('purchase_orders.purchase_order_mail_submited_to_vendor_date')

        
        ->where('businesses.is_active', true)
        ->select(
          'business_application_processes.purchase_order_id',
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

  public function getAllListPurchaseOrderMailSentToVendor()
  {
    try {

      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY')];

      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
      })
        ->leftJoin('designs', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'designs.business_id');
        })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
        })

        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
        })


        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->distinct('business_application_processes.id')
        ->select(
          'purchase_orders.purchase_orders_id as purchase_order_id',
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

  
    
  public function getAllListPurchaseOrderTowardsOwner(){
    try {

        $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];
        $array_not_to_be_check = [config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE')];

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
          ->leftJoin('purchase_orders', function($join) {
            $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
          })
          ->whereIn('purchase_orders.purchase_status_from_purchase',$array_to_be_check)
          // ->whereIn('business_application_processes.business_status_id',$array_not_to_be_check)
          ->where('businesses.is_active',true)
          ->select(
              'business_application_processes.purchase_order_id',
              'business_application_processes.requisition_id as requistition_id',
              
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


}