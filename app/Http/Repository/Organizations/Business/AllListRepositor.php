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
  PurchaseOrdersModel,
  BusinessDetails,
  Gatepass,
  CustomerProductQuantityTracking
};
use Config;

class AllListRepositor
{


  public function getAllListForwardedToDesign()
  {
      try {
          $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
  
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
              ->where('businesses_details.is_active', true)
              ->where('businesses_details.is_deleted', 0)
              ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
              ->groupBy(
                  'businesses.id',
                  'businesses.customer_po_number',
                  'businesses.title',
                  'businesses_details.id',
                  'businesses_details.product_name',
                  'businesses.remarks',
                  'businesses_details.description',
                  'businesses_details.quantity',
                  'businesses_details.rate',
                  'businesses.created_at',
                  'businesses.updated_at' 
              )
              ->select(
                  'businesses.id',
                  'businesses_details.id',
                  'businesses.title',
                  'businesses.customer_po_number',
                  'businesses.remarks',
                  'businesses_details.product_name',
                  'businesses_details.description',
                  'businesses_details.quantity',
                  'businesses_details.rate',
                  'businesses.created_at',
                  'businesses.updated_at',
              )
              ->orderBy('updated_at', 'desc')
              ->distinct()
              ->get();
  
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
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
        ->leftJoin('designs', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('businesses_details', function($join) {
          $join->on('production.business_details_id', '=', 'businesses_details.id');
      })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })
        ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->select(
          'businesses.id',
          'businesses.title',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses_details.quantity',
          'businesses_details.description',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          'design_revision_for_prod.reject_reason_prod',
          'designs.bom_image',
          'designs.design_image',
          'designs.updated_at'
        )->orderBy('designs.updated_at', 'desc')->get();
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
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
        ->leftJoin('designs', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        })

        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })

         ->leftJoin('businesses_details', function($join) {
              $join->on('production.business_details_id', '=', 'businesses_details.id');
          })
          // ->where('production.is_approved_production', NULL)
        ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->select(
          'businesses.id',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses.title',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          'production.id as productionId',
          'design_revision_for_prod.reject_reason_prod',
          'design_revision_for_prod.id as design_revision_for_prod_id',
          'design_revision_for_prod.design_image as re_design_image',
          'design_revision_for_prod.bom_image as re_bom_image',
          'designs.bom_image',
          'designs.design_image',
          'production.updated_at'
        )
        ->orderBy('production.updated_at', 'desc')->get();
      return $data_output;
    } catch (\Exception $e) {
      
      return $e;
    }
  }
  public function getAllStoreDeptSentForPurchaseMaterials()
  {
      try {
          $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
          $array_to_be_check_business = [config('constants.HIGHER_AUTHORITY.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')];
          $array_not_to_be_check = ['0'];
  
          $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
              $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
          })
          ->leftJoin('designs', function ($join) {
              $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
          })
          ->leftJoin('businesses', function ($join) {
              $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
          ->leftJoin('design_revision_for_prod', function ($join) {
              $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
          })
          ->leftJoin('purchase_orders', function($join) {
              $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
          })
          ->leftJoin('businesses_details', function($join) {
              $join->on('production.business_details_id', '=', 'businesses_details.id');
          })
          ->leftJoin('requisition as req2', function($join) {  // Second requisition join with alias `req2`
            $join->on('business_application_processes.business_details_id', '=', 'req2.business_details_id');
        })
          ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
          ->whereIn('business_application_processes.business_status_id', $array_to_be_check_business)
          ->where('business_application_processes.purchase_order_id', '0')
          ->where('businesses.is_active', true)
          ->where('businesses.is_deleted', 0)
          ->distinct('business_application_processes.business_details_id')
          
          ->select(
              'businesses.id',
              'businesses.customer_po_number',
              'businesses.title',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
              'production.id as productionId',
              'design_revision_for_prod.reject_reason_prod',
              'design_revision_for_prod.id as design_revision_for_prod_id',
              'designs.bom_image',
              'designs.design_image',
              'businesses_details.product_name',
              'businesses_details.description',
              'businesses_details.quantity',
              'businesses_details.rate',
              'production.updated_at',
              'req2.bom_file',  // Use alias `req2`
              'req2.updated_at' 
          )->orderBy('req2.updated_at', 'desc')->get();
  
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
      // $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];
      $array_not_to_be_check = [config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE')];

      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
      
        // ->leftJoin('designs', function ($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        // })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        // ->leftJoin('design_revision_for_prod', function ($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        // })
        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->leftJoin('businesses_details', function($join) {
          $join->on('production.business_details_id', '=', 'businesses_details.id');
      })
      // ->where('purchase_orders.po_send_owner_status', 'send_owner')
      ->whereIn('purchase_orders.purchase_status_from_purchase',$array_to_be_check)
      ->whereNull('purchase_orders.purchase_status_from_owner')
        // ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
        // ->orWhereNotIn('business_application_processes.business_status_id', $array_not_to_be_check)
        ->whereNull('purchase_orders.grn_no')
        ->whereNull('purchase_orders.store_receipt_no')
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->select(
          'business_application_processes.purchase_order_id',
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses.title',
          'businesses_details.description',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          // 'design_revision_for_prod.reject_reason_prod',
          // 'designs.bom_image',
          // 'designs.design_image',
          'businesses_details.updated_at'
          )
        ->distinct()->orderBy('businesses_details.updated_at', 'desc')->get();

      return $data_output;
    } catch (\Exception $e) {

      return $e;
    }
  }
  public function getAllListApprovedPurchaseOrderOwnerlogin()
  {
    try {

      // $array_to_be_check_business = [config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE')];
      // $array_to_be_check_purchase = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
      $array_to_be_check = [config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE')];

      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
        // ->leftJoin('designs', function ($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        // })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
            ->leftJoin('businesses_details', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
      })
        // ->leftJoin('design_revision_for_prod', function ($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        // })
        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
        ->whereNull('purchase_orders.purchase_order_mail_submited_to_vendor_date')      

        // ->whereIn('business_application_processes.business_status_id', $array_to_be_check_business)
        // ->orWhereIn('business_application_processes.purchase_status_from_purchase', $array_to_be_check_purchase)
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->select(
          'business_application_processes.purchase_order_id',
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses.title',
          'businesses_details.description',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          // 'design_revision_for_prod.reject_reason_prod',
          // 'designs.bom_image',
          // 'designs.design_image',
          'businesses_details.updated_at'
          )->distinct()->orderBy('businesses_details.updated_at', 'desc')
          
        ->get();
      return $data_output;
    } catch (\Exception $e) {

      return $e;
    }
  }

  public function getPurchaseOrderBusinessWise($id)
  {
      try {
        $array_to_be_check = [config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE')];
        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
          // ->leftJoin('designs', function ($join) {
          //   $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
          // })
          ->leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
        
              ->leftJoin('businesses_details', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
          // ->leftJoin('design_revision_for_prod', function ($join) {
          //   $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
          // })
  
          ->leftJoin('purchase_orders', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
          })
          ->leftJoin('vendors', function($join) {
            $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
          })
          ->where('businesses_details.id', $id)
          ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
          ->where('businesses.is_active', true)
          ->where('businesses.is_deleted', 0)
          ->distinct('business_application_processes.id')
          ->select(
            'purchase_orders.purchase_orders_id as purchase_order_id',
            'businesses.id',
            'businesses_details.product_name',
            'businesses.title',
            'businesses_details.description',
            'businesses.remarks',
            'businesses.is_active',
            'production.business_id',
            'production.business_details_id',
            // 'design_revision_for_prod.reject_reason_prod',
            // 'designs.bom_image',
            // 'designs.design_image',
            'purchase_orders.vendor_id',
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
            'purchase_orders.updated_at'
            )->orderBy('purchase_orders.updated_at', 'desc')
          
          ->get();
         return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage(); // Changed to return the error message string
      }
  }
  public function getAllListRejectedPurchaseOrderOwnerlogin()
  {
    try {

      // $array_to_be_check_business = [config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE')];
      // $array_to_be_check_purchase = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
      $array_to_be_check = [config('constants.HIGHER_AUTHORITY.REJECTED_PO_FROM_OWNER')];

      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
        // ->leftJoin('designs', function ($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        // })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
            ->leftJoin('businesses_details', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
      })
        // ->leftJoin('design_revision_for_prod', function ($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        // })
        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->where('business_application_processes.off_canvas_status', 23)
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
        // ->whereNull('purchase_orders.purchase_order_mail_submited_to_vendor_date')      

        // ->whereIn('business_application_processes.business_status_id', $array_to_be_check_business)
        // ->orWhereIn('business_application_processes.purchase_status_from_purchase', $array_to_be_check_purchase)
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->select(
          'business_application_processes.purchase_order_id',
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses.title',
          'businesses_details.description',
          'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          // 'design_revision_for_prod.reject_reason_prod',
          // 'designs.bom_image',
          // 'designs.design_image',
          'purchase_orders.purchase_status_from_owner',
          'businesses_details.updated_at'
          )->distinct()->orderBy('businesses_details.updated_at', 'desc')
          
        ->get();
      return $data_output;
    } catch (\Exception $e) {

      return $e;
    }
  }

  public function getPurchaseOrderRejectedBusinessWise($id)
  {
      try {
        $array_to_be_check = [config('constants.HIGHER_AUTHORITY.REJECTED_PO_FROM_OWNER')];
        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
          // ->leftJoin('designs', function ($join) {
          //   $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
          // })
          ->leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
        
              ->leftJoin('businesses_details', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
          // ->leftJoin('design_revision_for_prod', function ($join) {
          //   $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
          // })
  
          ->leftJoin('purchase_orders', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
          })
          ->leftJoin('vendors', function($join) {
            $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
          })
          ->where('purchase_orders.purchase_status_from_owner', $array_to_be_check)
          ->where('businesses_details.id', $id)
          // ->where('business_application_processes.off_canvas_status', 23)
         
          ->where('businesses.is_active', true)
          ->where('businesses.is_deleted', 0)
          ->distinct('purchase_orders.purchase_orders_id')
          ->select(
            'purchase_orders.purchase_orders_id as purchase_order_id',
            'businesses.id',
            'businesses_details.product_name',
            'businesses.title',
            'businesses_details.description',
            'businesses.remarks',
            'businesses.is_active',
            'production.business_id',
            // 'design_revision_for_prod.reject_reason_prod',
            // 'designs.bom_image',
            // 'designs.design_image',
            'purchase_orders.vendor_id',
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
            'purchase_orders.updated_at'
            )->orderBy('purchase_orders.updated_at', 'desc')
          
          ->get();
         return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage(); // Changed to return the error message string
      }
  }
  public function listPOReceivedForApprovaTowardsOwner()
  {
      try {
          // $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
          // $array_not_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];
             $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY_PARTICULAR_GRN_WISE')];

          $data_output = PurchaseOrdersModel::leftJoin('gatepass', function ($join) {
              $join->on('purchase_orders.purchase_orders_id', '=', 'gatepass.purchase_orders_id');
          })
          ->leftJoin('vendors', function ($join) {
              $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
          })
          ->leftJoin('grn_tbl', function ($join) {
              $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
          })
          ->where('grn_tbl.grn_status_sanction', $array_to_be_check)
          ->whereNotNull('grn_tbl.grn_no_generate')
          ->whereNotNull('grn_tbl.store_receipt_no_generate')
          ->whereNotNull('grn_tbl.store_remark')
          ->groupBy(
              'purchase_orders.purchase_orders_id',
              'grn_tbl.id',
              'grn_tbl.grn_no_generate',
              'grn_tbl.store_receipt_no_generate',
              'grn_tbl.store_remark',
              'vendors.vendor_name',
              'vendors.vendor_company_name',
              'vendors.vendor_email',
              'vendors.contact_no',
              'vendors.vendor_address',
              'vendors.gst_no',
              'vendors.quote_no',
              'purchase_orders.updated_at'
          )
          ->select(
              'purchase_orders.purchase_orders_id',
              'grn_tbl.id',
              'grn_tbl.grn_no_generate',
              'grn_tbl.store_receipt_no_generate',
              'grn_tbl.store_remark',
              'vendors.vendor_name',
              'vendors.vendor_company_name',
              'vendors.vendor_email',
              'vendors.contact_no',
              'vendors.vendor_address',
              'vendors.gst_no',
              'vendors.quote_no',
              'purchase_orders.updated_at'
          )
          ->orderBy('purchase_orders.updated_at', 'desc')
          ->get();
  
          return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage();
      }
  }
  public function listPOPaymentReleaseByVendor()
  {
      try {
          // $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
          // $array_not_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];
             $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.FORWARDED_PURCHASE_ORDER_PAYMENT_TO_THE_VENDOR_GRN_WISE')];

          $data_output = PurchaseOrdersModel::leftJoin('gatepass', function ($join) {
              $join->on('purchase_orders.purchase_orders_id', '=', 'gatepass.purchase_orders_id');
          })
          ->leftJoin('vendors', function ($join) {
              $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
          })
          ->leftJoin('grn_tbl', function ($join) {
              $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
          })
          ->where('grn_tbl.grn_status_sanction', $array_to_be_check)
          ->whereNotNull('grn_tbl.grn_no_generate')
          ->whereNotNull('grn_tbl.store_receipt_no_generate')
          ->whereNotNull('grn_tbl.store_remark')
          ->groupBy(
              'purchase_orders.purchase_orders_id',
              'grn_tbl.id',
              'grn_tbl.grn_no_generate',
              'grn_tbl.store_receipt_no_generate',
              'grn_tbl.store_remark',
              'vendors.vendor_name',
              'vendors.vendor_company_name',
              'vendors.vendor_email',
              'vendors.contact_no',
              'vendors.vendor_address',
              'vendors.gst_no',
              'vendors.quote_no'
          )
          ->select(
              'purchase_orders.purchase_orders_id',
              'grn_tbl.id',
              'grn_tbl.grn_no_generate',
              'grn_tbl.store_receipt_no_generate',
              'grn_tbl.store_remark',
              'vendors.vendor_name',
              'vendors.vendor_company_name',
              'vendors.vendor_email',
              'vendors.contact_no',
              'vendors.vendor_address',
              'vendors.gst_no',
              'vendors.quote_no'
          )

          ->get();
  
          return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage();
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
          // ->leftJoin('designs', function($join) {
          //   $join->on('production.business_id', '=', 'designs.business_id');
          // })

          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
          ->where('businesses.is_deleted', 0)
          ->distinct('businesses.id')
          ->groupBy('businesses.id', 'businesses.customer_po_number', 'businesses.title',
           'businesses.remarks', 'businesses.is_active', 'production.business_id', 'businesses.updated_at'
           )
           
          ->select(
              'businesses.id',
              'businesses.customer_po_number',
              // 'businesses.product_name',
              // 'businesses.title',
              // 'businesses.descriptions',
              // 'businesses.quantity',
              'businesses.remarks',
              'businesses.is_active',
              // 'designs.id',
              // 'designs.design_image',
              // 'designs.bom_image',
              // 'designs.business_id',
              'production.business_id',
              'businesses.updated_at',
             'businesses.updated_at'
          )->orderBy('businesses.updated_at', 'desc')->get();


         
        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}
public function loadDesignSubmittedForProductionBusinessWise($business_id)
{
    try {
        $decoded_business_id = base64_decode($business_id);

        $array_to_be_check = [
            config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED'),
        ];

        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
            ->leftJoin('design_revision_for_prod', function ($join) {
                $join->on('designs.id', '=', 'design_revision_for_prod.design_id');
            })
            ->leftJoin('businesses', function ($join) {
                $join->on('businesses_details.business_id', '=', 'businesses.id');
            }) // Added this join
            ->where('businesses_details.business_id', $decoded_business_id)
            ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
            ->where('businesses_details.is_active', true)
            ->where('businesses_details.is_deleted', 0)
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
                DB::raw('MAX(design_revision_for_prod.reject_reason_prod) as reject_reason_prod'),
                DB::raw('MAX(designs.bom_image) as bom_image'),
                DB::raw('MAX(designs.design_image) as design_image'),
                DB::raw('MAX(design_revision_for_prod.bom_image) as re_bom_image'),
                DB::raw('MAX(design_revision_for_prod.design_image) as re_design_image')
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
                'businesses.remarks'
            )
            ->get();
        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllListSubmitedPurchaeOrderByVendorOwnerside(){
  try {
  
    $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
        $array_to_be_check_owner = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

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
  
          ->leftJoin('purchase_orders', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
          })
          ->leftJoin('vendors', function($join) {
            $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
          })
          // ->where('businesses_details.id', $id)
          ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check_owner)
          ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)

          ->where('businesses.is_active', true)
          ->where('businesses.is_deleted', 0)
          // ->distinct('business_application_processes.id')
          ->select(
            'purchase_orders.purchase_orders_id as purchase_order_id',
            'businesses_details.id',
            'businesses_details.product_name',
            'businesses.title',
            'businesses_details.description',
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
            'purchase_orders.updated_at'
            )->distinct()->orderBy('purchase_orders.updated_at', 'desc')->get();
     
   
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
public function getOwnerReceivedGatePass()
{
    try {
        $data_output = Gatepass::where('po_tracking_status',4001)->orderBy('updated_at', 'desc')->get();
        return $data_output;
    } catch (\Exception $e) {

        return $e;
    }
}

public function getOwnerGRN()
{
    try {
        $data_output = Gatepass::where('po_tracking_status',4001)->where('is_checked_by_quality',false)->orderBy('updated_at', 'desc')->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllListMaterialSentFromQualityToStoreGeneratedGRN()
{
    try {

        $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
        // $array_to_be_check_new = ['0'];

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
            ->leftJoin('purchase_orders', function($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
              })
            ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
            // ->whereIn('purchase_orders.store_receipt_no', $array_to_be_check_new)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->distinct('businesses.id')
            ->select(
                // 'businesses.id',
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses.title',
                'businesses_details.description',
                'businesses.remarks',
                'businesses.is_active',
                'production.business_id',
                'production.id as productionId',
                'design_revision_for_prod.reject_reason_prod',
                'design_revision_for_prod.id as design_revision_for_prod_id',
                'designs.bom_image',
                'designs.design_image',
                'purchase_orders.updated_at'
                )->orderBy('purchase_orders.updated_at', 'desc')->get();
           
        // return $data_output;
        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

public function getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise($id)
{
    try {
        $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

        $data_output = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
        ->leftJoin('businesses_details', function($join) {
            $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
        })
        ->distinct('businesses_details.id')  
        ->where('businesses_details.is_deleted', 0)
        ->select(
            'purchase_orders.id',
            'purchase_orders.business_details_id',
            'purchase_orders.purchase_orders_id',         
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
            'purchase_orders.is_active',
             'purchase_orders.updated_at'
        )
        ->where('purchase_orders.business_details_id', $id)
        ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
        ->orderBy('purchase_orders.updated_at', 'desc')->get();
   
       
        return $data_output;
    } catch (\Exception $e) {
        return $e->getMessage(); // Changed to return the error message string
    }
}
public function getOwnerAllListMaterialRecievedToProduction(){
  try {

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')];
      
      $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
      ->leftJoin('designs', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
      })
      ->leftJoin('businesses', function($join) {
        $join->on('business_application_processes.business_id', '=', 'businesses.id');
      })
      ->leftJoin('businesses_details', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    })
      ->leftJoin('design_revision_for_prod', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
      })
      ->leftJoin('purchase_orders', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
      })
      ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
      ->where('businesses.is_active',true)
      ->where('businesses.is_deleted', 0)
      ->distinct('businesses.id')
      ->groupBy('businesses.id','businesses.customer_po_number','businesses.title','businesses_details.id','businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'businesses_details.rate',
       'purchase_orders.updated_at'
      )
      ->select(
           'businesses.id',
          'businesses_details.id',
          'businesses.title',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'purchase_orders.updated_at'
          )->orderBy('purchase_orders.updated_at', 'desc')->get();
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getOwnerAllCompletedProduction(){
  try {

      $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
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
      ->whereIn('bap1.production_status_id',$array_to_be_check)
      ->where('businesses.is_active',true)
      ->where('businesses.is_deleted', 0)
      ->distinct('businesses.id')
      ->groupBy('businesses.id','businesses.customer_po_number','businesses_details.id','businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'businesses_details.rate',
      'tbl_customer_product_quantity_tracking.completed_quantity',
      'tbl_customer_product_quantity_tracking.updated_at'
      )
      ->select(
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          'tbl_customer_product_quantity_tracking.updated_at'

          )->orderBy('tbl_customer_product_quantity_tracking.updated_at', 'desc')->get();
      
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getOwnerFinalAllCompletedProductionLogistics(){
  try {
    $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
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
    ->whereIn('bap1.production_status_id',$array_to_be_check)
    ->where('businesses.is_active',true)
    ->where('businesses.is_deleted', 0)
    ->distinct('businesses.id')
    ->groupBy('tbl_customer_product_quantity_tracking.id','tbl_customer_product_quantity_tracking.business_id','tbl_customer_product_quantity_tracking.business_details_id','businesses.customer_po_number','businesses_details.id','businesses_details.product_name',
    'businesses_details.description',
    'businesses_details.quantity',
    'businesses_details.rate',
    'tbl_customer_product_quantity_tracking.completed_quantity',
    'tbl_customer_product_quantity_tracking.updated_at'
    )
    ->select(
      'tbl_customer_product_quantity_tracking.id','tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
        'businesses.customer_po_number',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
        'tbl_customer_product_quantity_tracking.completed_quantity',
        'tbl_customer_product_quantity_tracking.updated_at'

        )->orderBy('tbl_customer_product_quantity_tracking.updated_at', 'desc')->get();
  
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getOwnerAllListBusinessReceivedFromLogistics(){
  try {
    $array_to_be_quantity_tracking = [ config('constants.HIGHER_AUTHORITY.UPDATED_COMPLETED_QUANLTITY_LOGISTICS_DEPT_SEND_TO_FIANANCE_DEPT')];
    $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_RECEIVED_FROM_LOGISTICS')];
    $array_to_be_check_new = ['0'];
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
  ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', [
      config('constants.HIGHER_AUTHORITY.UPDATED_COMPLETED_QUANLTITY_LOGISTICS_DEPT_SEND_TO_FIANANCE_DEPT')
  ])
  ->whereIn('bap1.logistics_status_id', [
      config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_RECEIVED_FROM_LOGISTICS')
  ])
  ->where('businesses.is_active', true)
  ->where('businesses.is_deleted', 0)
  ->groupBy(
      'tbl_customer_product_quantity_tracking.id',
      'tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'bap1.id',
      'tbl_transport_name.name',
      'tbl_vehicle_type.name',
      'tbl_logistics.truck_no',
      'tbl_logistics.from_place',
      'tbl_logistics.to_place',
      'tbl_customer_product_quantity_tracking.completed_quantity',
      'tbl_customer_product_quantity_tracking.updated_at'
  )
  ->select(
      'tbl_customer_product_quantity_tracking.id',
      'tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'tbl_transport_name.name as transport_name',
      'tbl_vehicle_type.name as vehicle_name',
      'tbl_logistics.truck_no',
      'tbl_logistics.from_place',
      'tbl_logistics.to_place',
      'tbl_customer_product_quantity_tracking.completed_quantity',
      'tbl_customer_product_quantity_tracking.updated_at'
  )
  ->orderBy('tbl_customer_product_quantity_tracking.updated_at', 'desc')
  ->get();

   
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

public function getOwnerAllListBusinessFianaceSendToDispatch(){
  try {
  
    $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
    $array_to_be_quantity_tracking = [ config('constants.DISPATCH_DEPARTMENT.RECEIVED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];
    $array_to_be_check_new = ['0'];


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


    ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
    ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
    // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
    ->where('businesses.is_active',true)
    ->where('businesses.is_deleted', 0)
    // ->distinct('businesses_details.id')
    ->groupBy(
      'tbl_customer_product_quantity_tracking.id',
      'tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'bap1.id',
      'tbl_transport_name.name',
      'tbl_vehicle_type.name',
      'tbl_logistics.truck_no',
      'tbl_logistics.from_place',
      'tbl_logistics.to_place',
      'tbl_customer_product_quantity_tracking.completed_quantity',
      'tbl_customer_product_quantity_tracking.updated_at'
  )
  ->select(
      'tbl_customer_product_quantity_tracking.id',
      'tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'tbl_transport_name.name as transport_name',
      'tbl_vehicle_type.name as vehicle_name',
      'tbl_logistics.truck_no',
      'tbl_logistics.from_place',
      'tbl_logistics.to_place',
      'tbl_customer_product_quantity_tracking.completed_quantity',
      'tbl_customer_product_quantity_tracking.updated_at'
  )->orderBy('tbl_customer_product_quantity_tracking.updated_at', 'desc')->get();
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
public function listProductDispatchCompletedFromDispatch(){
  try {
  
    $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
    $array_to_be_quantity_tracking = [ config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];
    $array_to_be_check_new = ['0'];
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
      ->leftJoin('tbl_dispatch', function($join) {
        $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_dispatch.quantity_tracking_id');
      })
      ->leftJoin('tbl_transport_name', function($join) {
        $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
    })
    ->leftJoin('tbl_vehicle_type', function($join) {
        $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
    })
      ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)
      ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
      // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->where('businesses.is_deleted', 0)
      ->distinct('businesses_details.id')
      ->groupBy(
        'tbl_customer_product_quantity_tracking.id','tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'tbl_logistics.truck_no',
        'tbl_transport_name.name',
        'tbl_vehicle_type.name',
        'tbl_dispatch.outdoor_no',
        'tbl_dispatch.gate_entry',
        'tbl_dispatch.remark',
        'tbl_dispatch.updated_at',
        'tbl_customer_product_quantity_tracking.completed_quantity',
        'tbl_dispatch.updated_at'
    )
      ->select(
       'tbl_customer_product_quantity_tracking.id','tbl_customer_product_quantity_tracking.business_id',
      'tbl_customer_product_quantity_tracking.business_details_id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
        'tbl_transport_name.name as transport_name',
        'tbl_vehicle_type.name as vehicle_name',
          'tbl_logistics.truck_no',
          'tbl_dispatch.outdoor_no',
          'tbl_dispatch.gate_entry',
          'tbl_dispatch.remark',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          'tbl_dispatch.updated_at'
      )
      ->orderBy('tbl_dispatch.updated_at', 'desc')
      ->get();
   
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
                      

}