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
  DesignRevisionForProd,
  PurchaseOrdersModel
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
          'businesses.product_name',
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
      return $data_output;
    } catch (\Exception $e) {
      
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
          'businesses.customer_po_number',
          'businesses.product_name',
          'businesses.title',
          'businesses.descriptions',
          'businesses.quantity',
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
        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
        })
        ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
        // ->whereIn('business_application_processes.grn_no', $array_not_to_be_check)
        ->where('business_application_processes.purchase_order_id', '0')
        ->where('businesses.is_active', true)
        ->distinct('businesses.id')
        ->select(
          'businesses.id',
          'businesses.customer_po_number',
          'businesses.product_name',
          'businesses.title',
          'businesses.descriptions',
          'businesses.quantity',
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

        // dd($data_output);
        // die();
      return $data_output;
    } catch (\Exception $e) {
      return $e;
    }
  }




  public function getAllListPurchaseOrder()
  {
    try {

      // $array_not_to_be_check = [
      //   config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE'),
      //   config('constants.HIGHER_AUTHORITY.HALF_APPROVED_PO_FROM_PURCHASE')
      
      // ];
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];

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

        ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
        // ->orWhereNotIn('business_application_processes.business_status_id', $array_not_to_be_check)
        ->whereNull('purchase_orders.grn_no')
        ->whereNull('purchase_orders.store_receipt_no')
        ->distinct('businesses.id')
        ->where('businesses.is_active', true)
        ->select(
          'business_application_processes.purchase_order_id',
          'businesses.id',
          'businesses.product_name',
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
        ->orWhereIn('business_application_processes.purchase_status_from_purchase', $array_to_be_check_purchase)
        ->where('businesses.is_active', true)

        ->select(
          'business_application_processes.purchase_order_id',
          'businesses.id',
          'businesses.product_name',
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

  public function getPurchaseOrderBusinessWise($id)
  {
      try {
          // $data_output = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
          // ->select(
          //     'purchase_orders.id',
          //     'purchase_orders.purchase_orders_id',         
          //     'vendors.vendor_name', 
          //     'vendors.vendor_company_name', 
          //     'vendors.vendor_email', 
          //     'vendors.vendor_address', 
          //     'vendors.contact_no', 
          //     'vendors.gst_no', 
          //     'purchase_orders.is_active'
          // )
          // ->where('purchase_orders.business_id', $id)
          // // ->get(); 
  
          // // ->where('business_id', $id)
          // ->whereNull('purchase_status_from_owner')
          // ->orWhere('purchase_status_from_owner', config('constants.HIGHER_AUTHORITY.HALF_APPROVED_PO_FROM_PURCHASE'))
  
          // ->get(); // Added to execute the query and get results
        //  dd($data_output);
        //  die();




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
          ->leftJoin('vendors', function($join) {
            $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
          })
  
          ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
          ->where('businesses.is_active', true)
          ->distinct('business_application_processes.id')
          ->select(
            'purchase_orders.purchase_orders_id as purchase_order_id',
            'businesses.id',
            'businesses.product_name',
            'businesses.title',
            'businesses.descriptions',
            'businesses.remarks',
            'businesses.is_active',
            'production.business_id',
            'design_revision_for_prod.reject_reason_prod',
            'designs.bom_image',
            'designs.design_image',
            'purchase_orders.vendor_id',
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
  
          )->get();
          
          // dd($data_output);
          // die();
          return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage(); // Changed to return the error message string
      }
  }
  
  public function listPOReceivedForApprovaTowardsOwner()
  {
    try {

      // $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
      $array_to_be_check = [config('constants.HIGHER_AUTHORITY.INVOICE_RECEIVED_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY'),
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
        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
        })
        ->whereIn('purchase_orders.finanace_store_receipt_status_id', $array_to_be_check)
        // ->whereIn('business_application_processes.business_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->select(
          'purchase_orders.purchase_orders_id',
          'business_application_processes.store_receipt_no',
          'purchase_orders.grn_no',
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
              'businesses.customer_po_number',
              'businesses.product_name',
              'businesses.title',
              'businesses.descriptions',
              'businesses.quantity',
              'businesses.remarks',
              'businesses.is_active',
              'designs.id',
              'designs.design_image',
              'designs.bom_image',
              'designs.business_id'

          )->get();
        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

                      

}