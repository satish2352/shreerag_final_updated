<?php
namespace App\Http\Repository\Organizations\Finance;

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



  public function getAllListSRAndGRNGeanrated()
  {
    try {

      $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
      $array_not_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];

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
        ->whereIn('business_application_processes.finanace_store_receipt_status_id', $array_to_be_check)
        ->whereNotIn('business_application_processes.business_status_id', $array_not_to_be_check)
        ->where('businesses.is_active', true)
        ->select(
          'business_application_processes.purchase_order_id',
          'business_application_processes.store_receipt_no',
          'business_application_processes.grn_no',
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
      dd($e);
      return $e;
    }
  }


  public function listPOSentForApprovaTowardsOwner()
  {
    try {

      // $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
      $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];

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
        ->whereIn('business_application_processes.business_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->select(
          'business_application_processes.purchase_order_id',
          'business_application_processes.store_receipt_no',
          'business_application_processes.grn_no',
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
      dd($e);
      return $e;
    }
  }

  public function listAcceptedGrnSrnFinance()
  {
    try {

      $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];


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
        ->whereIn('business_application_processes.store_status_id', $array_to_be_check)

        ->where('businesses.is_active', true)
        ->select(
          'business_application_processes.purchase_order_id',
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




  public function listPOSanctionAndNeedToDoPaymentToVendor()
  {
    try {

      $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
      $array_not_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_PAID_AGAINST_PO'),
      config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];

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
        ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
        ->whereNotIn('business_application_processes.finanace_store_receipt_status_id', $array_not_to_be_check)

        ->where('businesses.is_active', true)
        ->select(
          'business_application_processes.purchase_order_id',
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


  // public function getAllListMaterialSentToPurchase(){
  //   try {


  //       $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];

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



  // public function getAllListMaterialReceivedFromQuality(){
  //   try {

  //       $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
  //       $array_not_to_be_check = ['',null,NULL];

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
  //       ->whereIn('business_application_processes.quality_status_id',$array_to_be_check)
  //       ->whereNotIn('business_application_processes.store_receipt_no',$array_not_to_be_check)
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


}