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
  DesignRevisionForProd,
  PurchaseOrdersModel,
  PurchaseOrderModel,
  Logistics,
  CustomerProductQuantityTracking
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
        ->whereNotIn('business_application_processes.business_status_id', $array_not_to_be_check)
        ->where('businesses.is_active', true)
        ->distinct('businesses_details.id')
        ->groupBy(
          'businesses_details.id',
          // 'businesses.id',
          // 'purchase_orders.purchase_orders_id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.quantity',
          'businesses_details.description',
          // 'business_application_processes.id',
          // 'tbl_logistics.business_details_id',
         
      )
        ->select(
          'businesses_details.id',
          // 'businesses.id',
          // 'purchase_orders.purchase_orders_id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
            // 'production.id as productionId',
            // 'business_application_processes.store_material_sent_date',
            // 'tbl_logistics.business_details_id',
           
        )
        ->get();


       
       
        // ->select(
        //   'purchase_orders.purchase_orders_id',
        //   'purchase_orders.store_receipt_no',
        //   'purchase_orders.grn_no',
        //   'businesses.id',
        //   'businesses.product_name',
        //   'businesses.title',
        //   'businesses.descriptions',
        //   'businesses.remarks',
        //   'businesses.is_active',
        //   'production.business_id',
        //   'production.id as productionId',
        //   'design_revision_for_prod.reject_reason_prod',
        //   'design_revision_for_prod.id as design_revision_for_prod_id',
        //   'designs.bom_image',
        //   'designs.design_image'

        // )
        // ->get();
      return $data_output;
    } catch (\Exception $e) {
      
      return $e;
    }
  }


  public function getAllListSRAndGRNGeanratedBusinessWise($id)
  {
      try {
      
         
        $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
        $array_not_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];

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
          ->where('businesses_details.id', $id)
          ->whereIn('purchase_orders.finanace_store_receipt_status_id', $array_to_be_check)
        ->whereNotIn('business_application_processes.business_status_id', $array_not_to_be_check)
        ->where('businesses.is_active', true)

        ->groupBy(
          'purchase_orders.purchase_orders_id',
          'purchase_orders.store_receipt_no',
          'purchase_orders.grn_no',
          'businesses_details.id',
          // 'businesses.id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.quantity',
          'businesses_details.description',
          // 'business_application_processes.id',
          // 'tbl_logistics.business_details_id',
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
         
      )
        ->select(
          'purchase_orders.purchase_orders_id',
          'purchase_orders.store_receipt_no',
          'purchase_orders.grn_no',
          'businesses_details.id',
          // 'businesses.id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
            // 'production.id as productionId',
            // 'business_application_processes.store_material_sent_date',
            // 'tbl_logistics.business_details_id',
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
           
        )
        ->get();





          // ->select(
          //   'purchase_orders.purchase_orders_id',
          // 'purchase_orders.store_receipt_no',
          // 'purchase_orders.grn_no',
          //   'businesses.id',
          //   'businesses.product_name',
          //   'businesses.title',
          //   'businesses.descriptions',
          //   'businesses.remarks',
          //   'businesses.is_active',
          //   'production.business_id',
          //   'design_revision_for_prod.reject_reason_prod',
          //   'designs.bom_image',
          //   'designs.design_image',
          //   'purchase_orders.vendor_id',
          //   'vendors.vendor_name', 
          //   'vendors.vendor_company_name', 
          //   'vendors.vendor_email', 
          //   'vendors.vendor_address', 
          //   'vendors.contact_no', 
          //   'vendors.gst_no', 
  
          // )->get();
          
        
          return $data_output;
      } catch (\Exception $e) {
          return $e->getMessage(); // Changed to return the error message string
      }
  }

  public function listPOSentForApprovaTowardsOwner()
  {
    try {

      // $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
      $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY'),
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
        ->leftJoin('vendors', function($join) {
          $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
        })
        ->whereIn('purchase_orders.finanace_store_receipt_status_id', $array_to_be_check)
        // ->whereIn('business_application_processes.business_status_id', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->groupBy(
          'purchase_orders.purchase_orders_id',
          'purchase_orders.store_receipt_no',
          'purchase_orders.grn_no',
          'businesses_details.id',
          // 'businesses.id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.quantity',
          'businesses_details.description',
          // 'business_application_processes.id',
          // 'tbl_logistics.business_details_id',
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
         'purchase_orders.updated_at',
      )
        ->select(
          'purchase_orders.purchase_orders_id',
          'purchase_orders.store_receipt_no',
          'purchase_orders.grn_no',
          'businesses_details.id',
          // 'businesses.id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
            // 'production.id as productionId',
            // 'business_application_processes.store_material_sent_date',
            // 'tbl_logistics.business_details_id',
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
            'purchase_orders.updated_at',
           
        )->orderBy('purchase_orders.updated_at', 'desc')
        ->get();

        // ->select(
        //   'purchase_orders.purchase_orders_id',
        //   'purchase_orders.store_receipt_no',
        //   'purchase_orders.grn_no',
        //   'businesses.id',
        //   'businesses.title',
        //   'businesses.description',
        //   'businesses.remarks',
        //   'businesses.is_active',
        //   'production.business_id',
        //   'production.id as productionId',
        //   'design_revision_for_prod.reject_reason_prod',
        //   'design_revision_for_prod.id as design_revision_for_prod_id',
        //   'designs.bom_image',
        //   'designs.design_image'

        // )
        // ->get();
      return $data_output;
    } catch (\Exception $e) {
      
      return $e;
    }
  }

  public function listAcceptedGrnSrnFinance($purchase_orders_id)
  {
    try {

      $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
      $purchase_order = PurchaseOrderModel::where('purchase_orders_id', $purchase_orders_id)->first();


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
        ->whereIn('business_application_processes.store_status_id', $array_to_be_check)

        ->where('businesses.is_active', true)
        ->select(
          'purchase_orders.purchase_orders_id',
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
      
        ->whereIn('purchase_orders.store_status_id', $array_to_be_check)
        ->whereNotIn('purchase_orders.finanace_store_receipt_status_id', $array_not_to_be_check)
     
        ->where('businesses.is_active', true)
        ->groupBy(
          'purchase_orders.purchase_orders_id',
          'purchase_orders.store_receipt_no',
          'purchase_orders.grn_no',
          'businesses_details.id',
          // 'businesses.id',
          'business_application_processes.business_details_id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.quantity',
          'businesses_details.description',
          // 'business_application_processes.id',
          // 'tbl_logistics.business_details_id',
          'production.business_id',
          // 'design_revision_for_prod.reject_reason_prod',
          'designs.bom_image',
          'designs.design_image',
          'purchase_orders.vendor_id',
          'vendors.vendor_name', 
          'vendors.vendor_company_name', 
          'vendors.vendor_email', 
          'vendors.vendor_address', 
          'vendors.contact_no', 
          'vendors.gst_no', 
         
      )
        ->select(
          'purchase_orders.purchase_orders_id',
          'purchase_orders.store_receipt_no',
          'purchase_orders.grn_no',
          'businesses_details.id',
          // 'businesses.id',
          'business_application_processes.business_details_id',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
            // 'production.id as productionId',
            // 'business_application_processes.store_material_sent_date',
            // 'tbl_logistics.business_details_id',
            'production.business_id',
            // 'design_revision_for_prod.reject_reason_prod',
            'designs.bom_image',
            'designs.design_image',
            'purchase_orders.vendor_id',
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
           
        )
        ->get();

        // ->select(
        //   'purchase_orders.purchase_orders_id',
        //   'businesses.id',
        //   'businesses.title',
        //   'businesses.descriptions',
        //   'businesses.remarks',
        //   'businesses.is_active',
        //   'production.business_id',
        //   'production.id as productionId',
        //   'design_revision_for_prod.reject_reason_prod',
        //   'design_revision_for_prod.id as design_revision_for_prod_id',
        //   'designs.bom_image',
        //   'designs.design_image'

        // )
        // ->get();
      return $data_output;
    } catch (\Exception $e) {
      return $e;
    }
  }

  // public function getAllListBusinessReceivedFromLogistics(){
  //   try {
    
  //     $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_RECEIVED_FROM_LOGISTICS')];
  //     $array_to_be_quantity_tracking = [ config('constants.FINANCE_DEPARTMENT.RECEVIDE_COMPLETED_QUANLTITY_FROM_LOGISTICS_DEPT_TO_FIANANCE_DEPT')];
  //     $array_to_be_check_new = ['0'];     
  //     $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function($join) {
  //       $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
  //   })
  //     ->leftJoin('businesses', function($join) {
  //             $join->on('tbl_logistics.business_id', '=', 'businesses.id');
  //         })
  //         ->leftJoin('business_application_processes as bap1', function($join) {
  //             $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
  //         })
  //         ->leftJoin('businesses_details', function($join) {
  //             $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
  //         }) 
  //     ->leftJoin('tbl_transport_name', function($join) {
  //         $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
  //     })
  //     ->leftJoin('tbl_vehicle_type', function($join) {
  //         $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
  //     })
  //     ->leftJoin('tbl_customer_product_quantity_tracking', function($join) {
  //       $join->on('bap1.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id');
  //     })
  //     ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status',$array_to_be_quantity_tracking)

  //       // ->whereNull('business_application_processes.dispatch_status_id')
  //       ->whereIn('bap1.logistics_status_id',$array_to_be_check)

  //       // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
  //       ->where('businesses.is_active',true)
  //       // ->distinct('businesses_details.id')
  //       ->groupBy(
  //         'tbl_customer_product_quantity_tracking.id',
  //         'businesses.customer_po_number',
  //         'businesses.title',
  //         'businesses_details.id',
  //         'businesses_details.product_name',
  //         'businesses_details.quantity',
  //         'businesses_details.description',
  //         'bap1.id',
  //         'tbl_transport_name.name',
  //         'tbl_vehicle_type.name',
  //         'tbl_logistics.truck_no',
  //         'tbl_logistics.from_place',
  //         'tbl_logistics.to_place',
  //         'tbl_customer_product_quantity_tracking.completed_quantity'
  //     )
  //       ->select(
  //         'tbl_customer_product_quantity_tracking.id',
  //         'businesses.customer_po_number',
  //         'businesses.title',
  //         'businesses_details.id',
  //         'businesses_details.product_name',
  //         'businesses_details.description',
  //         'businesses_details.quantity',
  //         'tbl_transport_name.name as transport_name',
  //           'tbl_vehicle_type.name as vehicle_name',
  //           'tbl_logistics.truck_no',
  //           'tbl_logistics.from_place',
  //           'tbl_logistics.to_place',
  //           'tbl_customer_product_quantity_tracking.completed_quantity',
            
            
  //       )
  //       ->get();
      
       
     
  //     return $data_output;
  //   } catch (\Exception $e) {
  //       return $e;
  //   }
  // }
  public function getAllListBusinessReceivedFromLogistics(){
    try {
        $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_RECEIVED_FROM_LOGISTICS')];
        $array_to_be_quantity_tracking = [config('constants.FINANCE_DEPARTMENT.RECEVIDE_COMPLETED_QUANLTITY_FROM_LOGISTICS_DEPT_TO_FIANANCE_DEPT')];
        
        $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
            })
            ->leftJoin('businesses', function($join) {
                $join->on('tbl_logistics.business_id', '=', 'businesses.id');
            })
            ->leftJoin('business_application_processes as bap1', function($join) {
                $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
            })
            ->leftJoin('businesses_details', function($join) {
                $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('tbl_transport_name', function($join) {
                $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
            })
            ->leftJoin('tbl_vehicle_type', function($join) {
                $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
            })
            // ->leftJoin('tbl_customer_product_quantity_tracking as tcqt2', function($join) {
            //     $join->on('bap1.business_details_id', '=', 'tcqt2.business_details_id');
            // })
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            // ->whereIn('bap1.logistics_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->groupBy(
                'tcqt1.id',
                'tcqt1.business_details_id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'bap1.id',
                'tbl_transport_name.name',
                'tbl_vehicle_type.name',
                'tbl_logistics.truck_no',
                'tbl_logistics.from_place',
                'tbl_logistics.to_place',
                'tcqt1.completed_quantity'
            )
            ->select(
                'tcqt1.id',
                'tcqt1.business_details_id',
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
                'tcqt1.completed_quantity'
            )
            ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

  public function getAllListBusinessFianaceSendToDispatch(){
    try {
    
      $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
      $array_to_be_quantity_tracking = [ config('constants.FINANCE_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

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
      // ->distinct('businesses_details.id')
      ->groupBy(
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'bap1.id',
        'tbl_logistics.truck_no',
        'tbl_logistics.from_place',
        'tbl_logistics.to_place',
        'tbl_transport_name.name',
        'tbl_vehicle_type.name',
        'tbl_customer_product_quantity_tracking.completed_quantity',
    )
      ->select(
        'businesses.customer_po_number',
        'businesses.title',
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses_details.quantity',
          'tbl_logistics.truck_no',
          'tbl_logistics.from_place',
          'tbl_logistics.to_place',
          'tbl_transport_name.name as transport_name',
          'tbl_vehicle_type.name as vehicle_name',
          'tbl_customer_product_quantity_tracking.completed_quantity',
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