<?php
namespace App\Http\Repository\Organizations\Business;

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

class AllListRepositor
{


  public function getAllListForwardedToDesign()
  {
    try {

      $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
      $data_output = DesignModel::leftJoin('businesses', function ($join) {
        $join->on('designs.business_id', '=', 'businesses.id');
      })
        ->leftJoin('business_application_processes', function ($join) {
          $join->on('designs.business_id', '=', 'business_application_processes.business_id');
        })
        ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->select(
          'businesses.id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses.descriptions',
          'businesses.quantity',
          'businesses.po_validity',
          'businesses.hsn_number',
          'businesses.customer_payment_terms',
          'businesses.customer_terms_condition',
          'businesses.remarks',
          'businesses.is_active',
          'designs.business_id',
          'designs.created_at'

        )->get();

      return $data_output;
    } catch (\Exception $e) {
      return $e;
    }
  }



  public function getAllListCorrectionToDesignFromProduction()
  {
    try {

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];

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
        ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
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
      //   dd($data_output);
      return $data_output;
    } catch (\Exception $e) {
      dd($e);
      return $e;
    }
  }


  public function materialAskByProdToStore()
  {
    try {

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];

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
        ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
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
          'designs.design_image'

        )
        ->get();
      return $data_output;
    } catch (\Exception $e) {
      dd($e);
      return $e;
    }
  }



  public function getAllStoreDeptSentForPurchaseMaterials()
  {
    try {

      
      $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
      $array_not_to_be_check = ['0'];

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
        ->whereIn('business_application_processes.grn_no', $array_not_to_be_check)
        ->where('business_application_processes.purchase_order_id', '0')
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
          'designs.design_image'

        )
        ->get();
      return $data_output;
    } catch (\Exception $e) {
      return $e;
    }
  }




  public function getAllListPurchaseOrder()
  {
    try {

      $array_not_to_be_check = [config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE')];
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];
      $array_to_be_check_grn_no = ['0'];

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
        ->whereIn('business_application_processes.purchase_status_id', $array_to_be_check)
        ->whereIn('business_application_processes.business_status_id', $array_not_to_be_check)
        ->whereIn('business_application_processes.grn_no', $array_to_be_check_grn_no)
        ->whereIn('business_application_processes.store_receipt_no', $array_to_be_check_grn_no)

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



  public function getAllListApprovedPurchaseOrderOwnerlogin()
  {
    try {

      $array_to_be_check_business = [config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE')];
      $array_to_be_check_purchase = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

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
        ->whereIn('business_application_processes.business_status_id', $array_to_be_check_business)
        ->orWhereIn('business_application_processes.purchase_status_id', $array_to_be_check_purchase)
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

  public function listPOReceivedForApprovaTowardsOwner()
  {
    try {

      // $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
      $array_to_be_check = [config('constants.HIGHER_AUTHORITY.INVOICE_RECEIVED_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];

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

  public function loadDesignSubmittedForProduction(){
    try {

        $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),
        config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION'),
        config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED'),
    
        ];
        $data_output= ProductionModel::leftJoin('businesses', function($join) {
            $join->on('production.business_id', '=', 'businesses.id');
          })
          ->leftJoin('business_application_processes', function($join) {
            $join->on('production.business_id', '=', 'business_application_processes.business_id');
          })
          ->leftJoin('designs', function($join) {
            $join->on('production.business_id', '=', 'designs.business_id');
          })
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
          ->select(
              'businesses.id',
              'businesses.title',
              'businesses.descriptions',
              'businesses.remarks',
              'businesses.is_active',
              'designs.id',
              'designs.design_image',
              'designs.bom_image',
              'designs.business_id'

          )->get();
        //   dd($data_output);
        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}


}