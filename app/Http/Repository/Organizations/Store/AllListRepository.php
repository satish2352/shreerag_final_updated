<?php
namespace App\Http\Repository\Organizations\Store;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    Business, 
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    PurchaseOrdersModel
    };
use Config;

class AllListRepository  {


  
  public function getAllListDesignRecievedForMaterial(){
      try {

          $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
          
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
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
          ->select(
              'businesses.id',
              'businesses.product_name',
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

  public function getAllListMaterialSentToProduction(){
    try {

        $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
        
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
        ->whereIn('business_application_processes.store_status_id',$array_to_be_check)
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


  public function getAllListMaterialSentToPurchase(){
    try {
      
      $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
      $array_not_to_be_check = [NULL];
        
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
       
        ->whereIn('business_application_processes.store_status_id',$array_to_be_check)
        // ->whereIn('purchase_orders.grn_no',$array_not_to_be_check)
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


  
  public function getAllListMaterialReceivedFromQuality(){
    try {
      
      $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
      $array_to_be_check_new = ['0'];
        
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
        ->whereIn('purchase_orders.quality_status_id',$array_to_be_check)
        // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
        ->where('businesses.is_active',true)
        ->distinct('businesses.id')
        ->select(
            'businesses.id',
            'businesses.title',
            'businesses.product_name',
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

  public function getPurchaseOrderBusinessWise($id)
{
    try {
      $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
      $array_to_be_check_new = ['0'];
        $data_output = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
        ->leftJoin('businesses', function ($join) {
          $join->on('purchase_orders.business_id', '=', 'businesses.id');
        })
        ->leftJoin('business_application_processes', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
        })
        ->leftJoin('designs', function ($join) {
          $join->on('purchase_orders.business_id', '=', 'designs.business_id');
        })
        ->select(
            'purchase_orders.id',
            'business_application_processes.business_id',
            'purchase_orders.purchase_orders_id',         
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
            'businesses.title',
            'businesses.descriptions',
            'businesses.remarks',
              'designs.bom_image',
          'designs.design_image',
            'purchase_orders.is_active'
        )
         ->whereIn('purchase_orders.quality_status_id',$array_to_be_check)
        // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
        ->where('purchase_orders.business_id', $id)
        ->get(); 
        return $data_output;
    } catch (\Exception $e) {
        return $e->getMessage(); 
    }
}


}