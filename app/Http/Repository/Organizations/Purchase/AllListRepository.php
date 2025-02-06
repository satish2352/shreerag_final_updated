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
  DesignRevisionForProd,
  PurchaseOrdersModel};
use Config;

class AllListRepository
{
  public function getAllListMaterialReceivedForPurchase()
  {
      try {
          $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')];
          $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
              $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
          })
              ->leftJoin('designs', function ($join) {
                  $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
              })
              ->leftJoin('requisition', function ($join) {
                  $join->on('business_application_processes.business_details_id', '=', 'requisition.business_details_id');
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
              ->leftJoin('requisition as req2', function($join) {
                  $join->on('business_application_processes.business_details_id', '=', 'req2.business_details_id');
              })
              ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
              ->where('businesses_details.is_active', true)
              ->where('businesses_details.is_deleted', 0)
              ->groupBy(
                  // 'businesses.id',
                  'production.business_details_id',
                  'businesses_details.id',
                  'businesses.customer_po_number',
                  'businesses_details.product_name',
                  'businesses_details.description',
                  'businesses_details.quantity',
                  'businesses_details.is_active',
                  'production.business_id',
                  'production.id',
                  'designs.bom_image',
                  'designs.design_image',
                  'req2.id',   
                  'req2.bom_file',
                  'req2.updated_at'
              )
              ->select(
                  // 'businesses.id',
                  'production.business_details_id',
                  'businesses_details.id',
                  'businesses.customer_po_number',
                  'businesses_details.product_name',
                  'businesses_details.description',
                  'businesses_details.quantity',
                  'businesses_details.is_active',
                  'production.id',
                  'production.id as productionId',
                  'designs.bom_image',
                  'designs.design_image',
                  'req2.id as requistition_id',
                  'req2.bom_file',
                  'req2.updated_at'
              ) ->distinct('businesses_details.id')->orderBy('req2.updated_at', 'desc')
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

      $data_output = BusinessApplicationProcesses::leftJoin('purchase_orders', function ($join) {
        $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
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
      ->leftJoin('production', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })
        // ->leftJoin('purchase_orders', function($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        // })
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
        ->whereNull('purchase_orders.purchase_order_mail_submited_to_vendor_date')        
        // ->where('businesses.is_active', true)
        // ->distinct('businesses.id')
        ->groupBy(
          'businesses_details.id',            // Unique identifier
          'businesses_details.product_name', // Relevant for grouping
          'businesses_details.description',  // Relevant for grouping
          'businesses.title'                 // Relevant for grouping
      )
      ->select(
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses.title',
          DB::raw('MAX(purchase_orders.updated_at) as latest_update') // Aggregate function
      )
      ->orderBy('latest_update', 'desc')
      ->get();
      return $data_output;
    } catch (\Exception $e) {

      return $e;
    }
  }
  
  public function getPurchaseOrderSentToOwnerForApprovalBusinesWise($id)
  {
    try {
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];
      $data_output = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
      ->join('businesses_details', 'businesses_details.id', '=', 'purchase_orders.business_details_id')
      ->select(
          'purchase_orders.id',
          'purchase_orders.purchase_orders_id',         
          'vendors.vendor_name', 
          'vendors.vendor_company_name', 
          'vendors.vendor_email', 
          'vendors.vendor_address', 
          'vendors.contact_no', 
          'vendors.gst_no', 
          'purchase_orders.business_details_id',
          'purchase_orders.is_active'
      )
      ->where('purchase_orders.business_details_id', $id)
      // ->get(); 

      // ->where('business_id', $id)
      // ->whereNull('purchase_status_from_owner')
      ->whereNull('purchase_status_from_owner')
      ->whereIn('purchase_status_from_purchase', $array_to_be_check)

      ->get(); // Added to execute the query and get results
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
          $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
      })
        // ->distinct('businesses.id')
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->groupBy(
          'businesses_details.id',            // Unique identifier
          'businesses_details.product_name', // Relevant for grouping
          'businesses_details.description',  // Relevant for grouping
          'businesses.title'                 // Relevant for grouping
      )
      ->select(
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses.title',
          DB::raw('MAX(purchase_orders.updated_at) as latest_update') // Aggregate function
      )
      ->orderBy('latest_update', 'desc')
        ->get();
 
      return $data_output;
    } catch (\Exception $e) {

      return $e;
    }
  }


  public function getAllListPurchaseOrderMailSentToVendorBusinessWise($id)
  {
      try {
        $decoded_business_id = base64_encode($id);
    
        $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY')];

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
          // ->distinct('business_application_processes.id')
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
            'purchase_orders.updated_at',
            )->distinct()->orderBy('purchase_orders.updated_at', 'desc')
          ->get();
          
        
          return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage(); // Changed to return the error message string
      }
  }
  
  public function getAllListSubmitedPurchaeOrderByVendor()
  {
    try {

      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
    
      $array_to_be_check_owner = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
      })
        ->leftJoin('businesses', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('businesses_details', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
      })
      ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })

         // ->distinct('businesses_details.id')
         ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check_owner)
         ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
         // ->distinct('businesses.id')
         ->where('businesses.is_active', true)
         ->where('businesses.is_deleted', 0)
         // ->groupBy( 'businesses_details.id',
         // 'businesses_details.product_name','businesses_details.description',
         // 'purchase_orders.updated_at')
         ->groupBy(
          'businesses_details.id',            // Unique identifier
          'businesses_details.product_name', // Relevant for grouping
          'businesses_details.description',  // Relevant for grouping
          'businesses.title'                 // Relevant for grouping
      )
      ->select(
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses.title',
          DB::raw('MAX(purchase_orders.updated_at) as latest_update') // Aggregate function
      )
      ->orderBy('latest_update', 'desc')
         ->get();

      return $data_output;
    } catch (\Exception $e) {

      return $e;
    }
  }


  public function getAllListSubmitedPurchaeOrderByVendorBusinessWise($id)
  {
      try {
         
        $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
        $array_to_be_check_owner = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
          ->leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
          ->leftJoin('businesses_details', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('purchase_orders', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
          })
          ->leftJoin('vendors', function($join) {
            $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
          })
          ->where('businesses_details.id', $id)
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
            'purchase_orders.vendor_id',
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
            'purchase_orders.updated_at',
              )->orderBy('purchase_orders.updated_at', 'desc')
          ->get();
          
        
          return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage(); // Changed to return the error message string
      }
  }
public function getAllListPurchaseOrderTowardsOwner(){
  try {
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];

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
  ->leftJoin('purchase_orders', function ($join) {
      $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
  })
  ->leftJoin('businesses_details', function ($join) {
      $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
  })
  ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
  ->whereNull('purchase_orders.purchase_status_from_owner')
  ->where('businesses.is_active', true)
  ->where('businesses.is_deleted', 0)
  ->groupBy(
      'businesses_details.id',            // Unique identifier
      'businesses_details.product_name', // Relevant for grouping
      'businesses_details.description',  // Relevant for grouping
      'businesses.title'                 // Relevant for grouping
  )
  ->select(
      'businesses_details.id',
      'businesses_details.product_name',
      'businesses_details.description',
      'businesses.title',
      DB::raw('MAX(purchase_orders.updated_at) as latest_update') // Aggregate function
  )
  ->orderBy('latest_update', 'desc')
  ->get();


      return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
}