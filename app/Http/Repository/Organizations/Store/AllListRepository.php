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
          ->distinct('businesses.id')
          ->where('businesses.is_active',true)
          ->groupBy(
            'businesses.id',
            'businesses.customer_po_number',
            // 'businesses.product_name',
            // 'businesses.title',
            // 'businesses.descriptions',
            // 'businesses.quantity',
            'businesses.remarks',
            'businesses.is_active',
            'production.business_id',
             'businesses.updated_at'
              // 'production.id as productionId',
              // 'design_revision_for_prod.reject_reason_prod',
              // 'design_revision_for_prod.id as design_revision_for_prod_id'
        )
          ->select(
              'businesses.id',
              // 'businesses.product_name',
              // 'businesses.title',
              'businesses.customer_po_number',
              // 'businesses.descriptions',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
               'businesses.updated_at'
              // 'production.id as productionId',
              // 'design_revision_for_prod.reject_reason_prod',
              // 'design_revision_for_prod.id as design_revision_for_prod_id',
              // 'designs.bom_image',
              // 'designs.design_image'

              )
              ->orderBy('businesses.updated_at', 'desc')
          ->get();
        return $data_output;
      } catch (\Exception $e) {
          
          return $e;
      }
  }
  public function getAllListDesignRecievedForMaterialBusinessWise($business_id)
    {
        try {
            $decoded_business_id = base64_decode($business_id);

            $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
        
            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
            // ->leftJoin('designs', function ($join) {
            //     $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            // })
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function($join) {
              $join->on('production.business_details_id', '=', 'businesses_details.id');
          })
          ->leftJoin('designs', function($join) {
            $join->on('production.business_details_id', '=', 'designs.business_details_id');
        })
        ->leftJoin('production_details', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
      })
            ->leftJoin('design_revision_for_prod', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
            })
            ->leftJoin('purchase_orders', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
            })
            ->where('businesses_details.business_id', $decoded_business_id)
            ->distinct('businesses.id')
            ->where('production.is_approved_production', 1)
            ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
            // ->where('businesses.is_active', true)
           
           
            ->groupBy( 'production.business_details_id',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            'businesses_details.is_active',
            'production.business_id',
             'production.id',
            // 'production.id as productionId',
            'design_revision_for_prod.reject_reason_prod',
            // 'design_revision_for_prod.id as design_revision_for_prod_id',
                 'design_revision_for_prod.id',
            'designs.bom_image',
            'designs.design_image',
            'design_revision_for_prod.bom_image',
            'design_revision_for_prod.design_image',
            'production_details.material_send_production',
            'production.updated_at'
            )

            ->select(
                'production.business_details_id',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                'businesses_details.is_active',
                'production.business_id',
                'production.id as productionId',
                'production.id',
                'design_revision_for_prod.reject_reason_prod',
                  'design_revision_for_prod.id',
                // 'design_revision_for_prod.id as design_revision_for_prod_id',
                'designs.bom_image',
                'designs.design_image',
                'design_revision_for_prod.bom_image',
              'design_revision_for_prod.design_image',
              'production_details.material_send_production',
                'production.updated_at'
                )
                ->orderBy('production.updated_at', 'desc')
            ->get();

            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
  public function getAllListMaterialSentToProduction(){
    try {

        $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
        
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
          $join->on('production.business_details_id', '=', 'businesses_details.id');
      })
        ->leftJoin('design_revision_for_prod', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })
        
        ->whereIn('business_application_processes.store_status_id',$array_to_be_check)
        ->where('businesses.is_active',true)
        ->select(
            'businesses.id',
            'businesses.title',
            'businesses.customer_po_number',
            'businesses_details.product_name',
            'businesses_details.quantity',
            'businesses_details.description',
            // 'businesses.remarks',
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
          $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
        ->leftJoin('designs', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
        })
        ->leftJoin('businesses', function($join) {
          $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
        ->leftJoin('design_revision_for_prod', function($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })
        ->leftJoin('businesses_details', function($join) {
          $join->on('production.business_details_id', '=', 'businesses_details.id');
      })
      ->leftJoin('requisition', function($join) {
        $join->on('business_application_processes.business_details_id', '=', 'requisition.business_details_id');
    })
        ->whereIn('business_application_processes.store_status_id',$array_to_be_check)
        // ->whereIn('purchase_orders.grn_no',$array_not_to_be_check)
        ->where('businesses.is_active',true)
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
            'designs.bom_image',
            'designs.design_image',
            'requisition.bom_file',
            'businesses.updated_at'

        )->orderBy('businesses.updated_at', 'desc')
        ->get();
      return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
  }


  
  public function getAllListMaterialReceivedFromQuality(){
    try {
      
      $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
      $array_to_be_check_new = [null];
        
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
        ->whereIn('purchase_orders.quality_status_id',$array_to_be_check)
        ->whereNull('business_application_processes.store_material_sent_date')
        // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
        ->where('businesses.is_active',true)
        ->distinct('businesses.id')
        ->select(
            'businesses_details.id',
            'businesses.title',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses.remarks',
            'businesses.is_active',
            'production.business_id',
            'production.id as productionId',
            'design_revision_for_prod.reject_reason_prod',
            'design_revision_for_prod.id as design_revision_for_prod_id',
            'designs.bom_image',
            'designs.design_image',
            'business_application_processes.store_receipt_no',
            'businesses.updated_at'

        )->orderBy('businesses.updated_at', 'desc')
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
        ->leftJoin('businesses_details', function ($join) {
          $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('business_application_processes', function ($join) {
          $join->on('purchase_orders.business_details_id', '=', 'business_application_processes.business_details_id');
        })
       
        ->leftJoin('designs', function ($join) {
          $join->on('purchase_orders.business_details_id', '=', 'designs.business_details_id');
        })
        ->where('businesses_details.id', $id)
        ->distinct('purchase_orders.purchase_orders_id')
        ->select(
          // 'purchase_orders.id',
          'businesses_details.id',
            'business_application_processes.business_details_id',
            'purchase_orders.purchase_orders_id',         
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no',
            'businesses_details.product_name', 
            'businesses_details.description', 
          
              'designs.bom_image',
          'designs.design_image',
            'purchase_orders.is_active',
            'businesses_details.updated_at'
        )
         ->whereIn('purchase_orders.quality_status_id',$array_to_be_check)
        // ->whereIn('purchase_orders.store_receipt_no',$array_to_be_check_new)
        ->orderBy('businesses_details.updated_at', 'desc')
        ->get(); 
        // dd( $data_output);
        // die();
        return $data_output;
    } catch (\Exception $e) {
        return $e->getMessage(); 
    }
}

public function getAllInprocessProductProduction(){
  try {
     
    $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION')];
      
    $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    })
    ->leftJoin('designs', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    })
    // ->leftJoin('businesses', function($join) {
    //   $join->on('business_application_processes.business_id', '=', 'businesses.id');
    // })
    ->leftJoin('businesses_details', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
  })
    ->leftJoin('design_revision_for_prod', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    })
    ->leftJoin('purchase_orders', function($join) {
      $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
    })
    // ->where('businesses_details.id',$id)
    ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
    ->where('businesses_details.is_active',true)
    ->distinct('businesses.id')
    ->groupBy('businesses_details.id',
    'businesses_details.product_name',
    'businesses_details.quantity',
    'businesses_details.description',
    'businesses_details.is_active',
    'production.business_details_id',
    // 'design_revision_for_prod.reject_reason_prod',
    // 'design_revision_for_prod.id as design_revision_for_prod_id',
    'designs.bom_image',
    'designs.design_image',
    'business_application_processes.store_material_sent_date'
            )

    ->select(
        'businesses_details.id',
        'businesses_details.product_name',
        'businesses_details.quantity',
        'businesses_details.description',
        'businesses_details.is_active',
        'production.business_details_id',
        // 'design_revision_for_prod.reject_reason_prod',
        // 'design_revision_for_prod.id as design_revision_for_prod_id',
        'designs.bom_image',
        'designs.design_image',
        'business_application_processes.store_material_sent_date'

    )
    ->get();

      return $data_output ;
  } catch (\Exception $e) {
      return [
          'msg' => $e->getMessage(),
          'status' => 'error'
      ];
  }
}
}