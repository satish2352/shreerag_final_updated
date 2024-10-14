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
  Gatepass
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
                  'production.updated_at' // Added here
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
                  DB::raw('MAX(production.updated_at) as latest_updated_at') // Use the latest update time
              )
              ->orderBy('latest_updated_at', 'desc') // Order by the latest update time
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
          // 'design_revision_for_prod.reject_reason_prod',
          'design_revision_for_prod.reject_reason_prod',
          // 'design_revision_for_prod.id as design_revision_for_prod_id',
          // 'design_revision_for_prod.design_image',
          // 'design_revision_for_prod.bom_image',
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



  // public function getAllStoreDeptSentForPurchaseMaterials()
  // {
  //   try {

      
  //     $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
  //     $array_to_be_check_business = [config('constants.HIGHER_AUTHORITY.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')];
  //     $array_not_to_be_check = ['0'];

  //     $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
  //       $join->on('business_application_processes.business_id', '=', 'production.business_id');
  //     })
  //       ->leftJoin('designs', function ($join) {
  //         $join->on('business_application_processes.business_id', '=', 'designs.business_id');
  //       })
  //       ->leftJoin('businesses', function ($join) {
  //         $join->on('business_application_processes.business_id', '=', 'businesses.id');
  //       })
        

  //       ->leftJoin('design_revision_for_prod', function ($join) {
  //         $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
  //       })
  //       ->leftJoin('purchase_orders', function($join) {
  //         $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
  //       })
  //       ->leftJoin('businesses_details', function($join) {
  //         $join->on('businesses.id', '=', 'businesses_details.business_id');
  //     })
  //       ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
  //       ->whereIn('business_application_processes.business_status_id', $array_to_be_check_business)
        
  //       // ->whereIn('business_application_processes.grn_no', $array_not_to_be_check)
  //       ->where('business_application_processes.purchase_order_id', '0')
  //       ->where('businesses.is_active', true)
  //       // ->distinct('businesses.id')
  //       // ->groupBy('businesses.id', 'businesses.customer_po_number','businesses.title',  'businesses.remarks', 'businesses.is_active', 'production.business_id')

  //       ->select(
  //         'businesses.id',
  //         'businesses.customer_po_number',
  //         // 'businesses.product_name',
  //         'businesses.title',
  //         // 'businesses.descriptions',
  //         // 'businesses.quantity',
  //         'businesses.remarks',
  //         'businesses.is_active',
  //         'production.business_id',
  //         'production.id as productionId',
  //         'design_revision_for_prod.reject_reason_prod',
  //         'design_revision_for_prod.id as design_revision_for_prod_id',
  //         'designs.bom_image',
  //         'designs.design_image',
  //         'businesses_details.product_name',
  //           'businesses_details.description',
  //           'businesses_details.quantity',
  //           'businesses_details.rate'

  //       )
  //       ->get();
  //     return $data_output;
  //   } catch (\Exception $e) {
  //     return $e;
  //   }
  // }
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
        ->leftJoin('purchase_orders', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->leftJoin('businesses_details', function($join) {
          $join->on('production.business_details_id', '=', 'businesses_details.id');
      })
        ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
        // ->orWhereNotIn('business_application_processes.business_status_id', $array_not_to_be_check)
        ->whereNull('purchase_orders.grn_no')
        ->whereNull('purchase_orders.store_receipt_no')
        ->distinct('businesses_details.id')
        ->where('businesses.is_active', true)
        ->groupBy('businesses.id','businesses_details.id','businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
        'businesses_details.rate',
        'purchase_orders.updated_at'
        )
        ->select(
          // 'business_application_processes.purchase_order_id',
          'businesses.id',
          'businesses_details.id',
          // 'businesses.product_name',
          // 'businesses.title',
          // 'businesses.descriptions',
          // 'businesses.remarks',
          // 'businesses.is_active',
          // 'production.business_id',
          // 'design_revision_for_prod.reject_reason_prod',
          // 'designs.bom_image',
          // 'designs.design_image',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.rate',
          'purchase_orders.updated_at'
        )->orderBy('purchase_orders.updated_at', 'desc')->get();

      return $data_output;
    } catch (\Exception $e) {

      return $e;
    }
  }



  public function getAllListApprovedPurchaseOrderOwnerlogin()
  {
    try {

      $array_to_be_check_business = [config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE')];
      $array_to_be_check_purchase = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

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
       
        ->whereIn('business_application_processes.business_status_id', $array_to_be_check_business)
        ->orWhereIn('business_application_processes.purchase_status_from_purchase', $array_to_be_check_purchase)
        ->where('businesses.is_active', true)

        ->select(
          'business_application_processes.purchase_order_id',
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
       
        $array_to_be_check = [config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE')];
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
  
          ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check)
          ->where('businesses.is_active', true)
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
      $array_to_be_check = [config('constants.HIGHER_AUTHORITY.INVOICE_RECEIVED_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY'),
      config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];

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
        ->whereIn('purchase_orders.finanace_store_receipt_status_id', $array_to_be_check)
        // ->whereIn('business_application_processes.business_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->select(
          'purchase_orders.purchase_orders_id',
          'business_application_processes.store_receipt_no',
          'purchase_orders.grn_no',
          'businesses.id',
          'businesses.title',
          // 'businesses.description',
          // 'businesses.remarks',
          'businesses.is_active',
          'production.business_id',
          'business_application_processes.business_details_id',
          // 'production.id as productionId',
          // 'design_revision_for_prod.reject_reason_prod',
          // 'design_revision_for_prod.id as design_revision_for_prod_id',
          // 'designs.bom_image',
          // 'designs.design_image'

          'purchase_orders.updated_at'
          )->orderBy('purchase_orders.updated_at', 'desc')
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
          // ->leftJoin('designs', function($join) {
          //   $join->on('production.business_id', '=', 'designs.business_id');
          // })

          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
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
public function loadDesignSubmittedForProductionBusinessWise($business_id){
  try {

      $decoded_business_id = base64_decode($business_id);

      $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),
      config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION'),
      config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED'),
  
      ];
      // $data_output= ProductionModel::leftJoin('businesses', function($join) {
      //     $join->on('production.business_id', '=', 'businesses.id');
      //   })
      //   ->leftJoin('businesses_details', function($join) {
      //     $join->on('production.business_details_id', '=', 'businesses_details.id');
      // })
      //   ->leftJoin('business_application_processes', function($join) {
      //     $join->on('production.business_id', '=', 'business_application_processes.business_id');
      //   })
      //   ->leftJoin('designs', function($join) {
      //     $join->on('production.business_id', '=', 'designs.business_id');
      //   })
      $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    })
    // ->leftJoin('business_application_processes', function($join) {
    //     $join->on('business_application_processes.business_id', '=', 'business_application_processes.business_id');
    // })
    ->leftJoin('businesses_details', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    })
    ->leftJoin('designs', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    })
    ->leftJoin('design_revision_for_prod', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    })
    ->where('businesses_details.business_id', $decoded_business_id)
        ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
        ->where('businesses_details.is_active',true)
        // ->distinct('businesses.id')
        ->distinct('businesses_details.id')
        ->select(
            'businesses_details.id',
            // 'businesses.customer_po_number',
            'businesses_details.product_name',
            // 'businesses.title',
            'businesses_details.description',
            'businesses_details.quantity',
            // 'businesses.remarks',
            'businesses_details.is_active',
            'designs.id',
            'designs.design_image',
            'designs.bom_image',
            'design_revision_for_prod.reject_reason_prod',
            'design_revision_for_prod.design_image as re_design_image',
            'design_revision_for_prod.bom_image as re_bom_image',
            'designs.business_id',
         'businesses_details.updated_at'
        )->orderBy('businesses_details.updated_at', 'desc')->get();


       
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
        $data_output = Gatepass::get();

        return $data_output;
    } catch (\Exception $e) {

        return $e;
    }
}

public function getOwnerGRN()
{
    try {
        $data_output = Gatepass::where('is_checked_by_quality',false)->get();

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

            ->distinct('businesses.id')
            ->select(
                'businesses.id',
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
        ->select(
            'purchase_orders.id',
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
      ->distinct('businesses.id')
      ->groupBy('businesses.id','businesses.customer_po_number','businesses_details.id','businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
      'businesses_details.rate',
      'purchase_orders.updated_at'
      )
      ->select(
          'businesses.customer_po_number',
          // 'businesses.title',
          // 'businesses.remarks',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'purchase_orders.updated_at'
          // 'production.business_id',
          // 'production.id as productionId',
          // 'design_revision_for_prod.reject_reason_prod',
          // 'design_revision_for_prod.id as design_revision_for_prod_id',
          // 'designs.bom_image',
          // 'designs.design_image',
          // 'business_application_processes.store_material_sent_date'

          )->orderBy('purchase_orders.updated_at', 'desc')->get();
      
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getOwnerFinalAllCompletedProductionLogistics(){
  try {

    //   $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
    //   $array_to_be_check_new = [NULL];
    //   $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //   })
    //   ->leftJoin('designs', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    //   })
    //   ->leftJoin('businesses', function($join) {
    //     $join->on('business_application_processes.business_id', '=', 'businesses.id');
    //   })
    //   ->leftJoin('businesses_details', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    // })
    //   ->leftJoin('design_revision_for_prod', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    //   })
    //   ->leftJoin('purchase_orders', function($join) {
    //     $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
    //   })
    //   ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
    //   ->whereNull('business_application_processes.logistics_status_id')
    //   // ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check_new)
    //   ->where('businesses.is_active',true)
    //   ->distinct('businesses_details.id')
    //   ->select(
    //       'businesses_details.id',
    //       'businesses.title',
    //       'businesses.customer_po_number',
    //       'businesses_details.product_name',
    //       'businesses.title',
    //       'businesses_details.quantity',
    //       'businesses.remarks',
    //       'businesses.is_active',
    //       'production.business_id',
    //       'production.id as productionId',
    //       'business_application_processes.store_material_sent_date',
    //       'purchase_orders.updated_at'
      
    //   )->orderBy('purchase_orders.updated_at', 'desc')->get();

    $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
      
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
    ->distinct('businesses.id')
    ->groupBy('businesses.id','businesses.customer_po_number','businesses_details.id','businesses_details.product_name',
    'businesses_details.description',
    'businesses_details.quantity',
    'businesses_details.rate',
    'purchase_orders.updated_at'
    )
    ->select(
      'businesses.id',
        'businesses.customer_po_number',
        // 'businesses.title',
        // 'businesses.remarks',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
        'purchase_orders.updated_at'
        // 'production.business_id',
        // 'production.id as productionId',
        // 'design_revision_for_prod.reject_reason_prod',
        // 'design_revision_for_prod.id as design_revision_for_prod_id',
        // 'designs.bom_image',
        // 'designs.design_image',
        // 'business_application_processes.store_material_sent_date'

        )->orderBy('purchase_orders.updated_at', 'desc')->get();
  
    return $data_output;
  } catch (\Exception $e) {
      
      return $e;
  }
}

public function getOwnerAllListBusinessReceivedFromLogistics(){
  try {
  
    $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_RECEIVED_FROM_LOGISTICS')];
    $array_to_be_check_new = ['0'];
 
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
      ->leftJoin('tbl_logistics', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
      })
      ->whereNull('business_application_processes.dispatch_status_id')
      ->whereIn('business_application_processes.logistics_status_id',$array_to_be_check)

      // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      // ->distinct('businesses_details.id')
      ->groupBy(
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'business_application_processes.id',
        'tbl_logistics.truck_no',
        'purchase_orders.updated_at'
    )
      ->select(
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
          // 'production.id as productionId',
          // 'business_application_processes.store_material_sent_date',
          'tbl_logistics.truck_no',
          'purchase_orders.updated_at'
          // 'tbl_logistics.vendor_id',
      )->orderBy('purchase_orders.updated_at', 'desc')->get();
      // ->select(
      //     'businesses.id',
      //     'businesses_details.id',
      //     'businesses.title',
      //     'businesses.customer_po_number',
      //     'businesses_details.product_name',
      //     'businesses.title',
      //     'businesses_details.quantity',
      //     'businesses.remarks',
      //     'businesses.is_active',
      //     'production.business_id',
      //     'production.id as productionId',
      //     'business_application_processes.store_material_sent_date',
      //     'tbl_logistics.truck_no',
      //     // 'tbl_logistics.vendor_id',
      // );
     
   
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}

public function getOwnerAllListBusinessFianaceSendToDispatch(){
  try {
  
    $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
    $array_to_be_check_new = ['0'];
 


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
    ->leftJoin('tbl_logistics', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
    })
    ->whereIn('business_application_processes.dispatch_status_id',$array_to_be_check)
    // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
    ->where('businesses.is_active',true)
    // ->distinct('businesses_details.id')
    ->groupBy(
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.id',
      'businesses_details.product_name',
      'businesses_details.quantity',
      'businesses_details.description',
      'business_application_processes.id',
      'tbl_logistics.truck_no',
        'tbl_logistics.updated_at'
  )
    ->select(
      'businesses.customer_po_number',
      'businesses.title',
      'businesses_details.id',
      'businesses_details.product_name',
      'businesses_details.description',
      'businesses_details.quantity',
        'tbl_logistics.truck_no',
          'tbl_logistics.updated_at'
    )->orderBy('tbl_logistics.updated_at', 'desc')->get();
    return $data_output;
  } catch (\Exception $e) {
      return $e;
  }
}
public function listProductDispatchCompletedFromDispatch(){
  try {
  
    $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
    $array_to_be_check_new = ['0'];
  
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
    ->leftJoin('tbl_logistics', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
    })
      ->leftJoin('tbl_dispatch', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'tbl_dispatch.business_details_id');
      })
      ->whereIn('business_application_processes.dispatch_status_id',$array_to_be_check)
      // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
      ->where('businesses.is_active',true)
      ->distinct('businesses_details.id')
      ->groupBy(
        'businesses_details.id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'tbl_logistics.truck_no',
        'tbl_dispatch.outdoor_no',
        'tbl_dispatch.gate_entry',
        'tbl_dispatch.remark',
        'tbl_dispatch.updated_at',
          'tbl_dispatch.updated_at'
    )
      ->select(
        'businesses_details.id',
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
          'tbl_logistics.truck_no',
          'tbl_dispatch.outdoor_no',
          'tbl_dispatch.gate_entry',
          'tbl_dispatch.remark',
          'tbl_dispatch.updated_at',
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