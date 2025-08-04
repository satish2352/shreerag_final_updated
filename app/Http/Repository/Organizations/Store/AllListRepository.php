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
    PurchaseOrdersModel,
    GrnPOQuantityTracking,
    GRNModel
    };
use Config;

class AllListRepository  {
    public function getAllListDesignRecievedForMaterial(){
        try {
            $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
            $array_to_be_check_store = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
            $array_to_be_check_store_after_quality = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
            $array_to_be_check_production = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];

            $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
                $join->on('business_application_processes.business_id', '=', 'production.business_id');
            })
            ->leftJoin('businesses', function($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->where(function ($query) use ($array_to_be_check, $array_to_be_check_store, $array_to_be_check_store_after_quality, $array_to_be_check_production) {
                $query->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store)
                    ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check)
                    ->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store_after_quality)
                    ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check_production);
            })
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->distinct('businesses.id')
            ->groupBy(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.remarks',
                'businesses.is_active',
                'production.business_id',
                'businesses.updated_at',
                'businesses.created_at'
            )
            ->select(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.remarks',
                'businesses.is_active',
                'production.business_id',
                'businesses.updated_at',
                'businesses.created_at'
            )
            ->orderBy('businesses.updated_at', 'desc')
            ->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListDesignRecievedForMaterialBusinessWise($business_id){
        try {
            $decoded_business_id = base64_decode($business_id);
            $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
            $array_to_be_check_store = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
            $array_to_be_check_store_after_quality = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
            $array_to_be_check_production = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];

            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
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
            ->where('businesses_details.is_deleted', 0)
            ->distinct('businesses.id')
            ->where('production.is_approved_production', 1)
            ->where(function ($query) use ($array_to_be_check, $array_to_be_check_store, $array_to_be_check_store_after_quality, $array_to_be_check_production) {
                $query->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store)
                    ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check)
                    ->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store_after_quality)
                    ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check_production);
            })
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
                DB::raw('MAX(design_revision_for_prod.design_image) as re_design_image'), 
                DB::raw('MAX(design_revision_for_prod.remark_by_design) as remark_by_design'),
                'production.updated_at',              
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
                'businesses.remarks',
                'production.updated_at', 
            )
            ->orderBy('production.updated_at', 'desc')
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
            ->where('businesses.is_deleted', 0)
            ->select(
                'businesses.id',
                'businesses.title',
                'businesses.customer_po_number',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
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
    public function getAllListMaterialSentToPurchase()
    {
        try {
            $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
            $data_output = BusinessApplicationProcesses::leftJoin('production', function($join) {
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
                ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->groupBy([
                    'businesses_details.product_name',
                    'businesses_details.description',
                ])
                ->selectRaw("
                    MAX(businesses.id) as business_id,
                    MAX(businesses.customer_po_number) as customer_po_number,
                    MAX(businesses.project_name) as customer_project_name,
                    businesses_details.product_name,
                    MAX(businesses.title) as title,
                    businesses_details.description,
                    SUM(businesses_details.quantity) as quantity,
                    MAX(businesses.remarks) as remarks,
                    MAX(production.business_id) as production_business_id,
                    MAX(production.id) as productionId,
                    MAX(design_revision_for_prod.reject_reason_prod) as reject_reason_prod,
                    MAX(design_revision_for_prod.id) as design_revision_for_prod_id,
                    MAX(designs.bom_image) as bom_image,
                    MAX(designs.design_image) as design_image,
                    MAX(requisition.bom_file) as bom_file,
                    MAX(businesses.updated_at) as updated_at,
                    MAX(businesses.created_at) as created_at
                ")
                ->orderBy('updated_at', 'desc')
                ->get();
    
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQuality()
    {
        try {
            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->select(
                    'businesses_details.id',
                    'businesses.title',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses.remarks',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id as productionId',
                    'business_application_processes.store_receipt_no',
                    'businesses.updated_at'
                )
                ->distinct() 
                ->orderBy('businesses.updated_at', 'desc')
                ->get();

            if ($data_output->isNotEmpty()) {
                return $data_output;
            } else {
                return []; 
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPurchaseOrderBusinessWise($id)
    {
        try {
            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

            $data_output = GRNModel::leftJoin('purchase_orders', function ($join) {
                $join->on('grn_tbl.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id');
            })
            ->leftJoin('vendors', function ($join) {
                $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('gatepass as gatepass_one', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass_one.id');
            })
            ->leftJoin('business_application_processes', function ($join) {
                $join->on('purchase_orders.business_details_id', '=', 'business_application_processes.business_details_id');
            })
            ->leftJoin('designs', function ($join) {
                $join->on('purchase_orders.business_details_id', '=', 'designs.business_details_id');
            })
            ->leftJoin('production_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
            })
            ->where('businesses_details.id', $id)
            ->where('businesses_details.is_deleted', 0)
            ->select(
                'grn_tbl.id',
                'grn_tbl.grn_date',
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
                DB::raw('MAX(production_details.material_send_production) as material_send_production'),
                DB::raw('GROUP_CONCAT(DISTINCT designs.bom_image) as bom_image'),
                DB::raw('GROUP_CONCAT(DISTINCT designs.design_image) as design_image'),
                'purchase_orders.is_active',
                'grn_tbl.id'
            )
            ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
            ->groupBy(
                'grn_tbl.id',
                'grn_tbl.grn_date',
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
                'purchase_orders.is_active',
                'grn_tbl.id'
            )
            ->orderBy('grn_tbl.id', 'desc')
            ->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTracking()
    {
        try {
            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->select(
                    'businesses_details.id',
                    'businesses.title',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses.remarks',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id as productionId',
                    'business_application_processes.store_receipt_no',
                    'businesses.updated_at'
                )
                ->distinct() 
                ->orderBy('businesses.updated_at', 'desc')
                ->get();

            if ($data_output->isNotEmpty()) {
                return $data_output;
            } else {
                return []; 
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTrackingBusinessWise()
    {
        try {
            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            $data_output = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
                ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
                ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
                ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
                ->select(
                    'purchase_orders.business_details_id',
                    'purchase_orders.purchase_orders_id',
                    'tbl_grn_po_quantity_tracking.grn_id', 
                    'businesses_details.product_name', 
                    'businesses_details.description',
                    'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id' // GRN ID from tracking table
                )
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                // ->where('businesses_details.id', $id)
                ->where('businesses_details.is_deleted', 0)
                ->groupBy( 'purchase_orders.purchase_orders_id','tbl_grn_po_quantity_tracking.grn_id',   'purchase_orders.business_details_id','businesses_details.product_name', 
                'businesses_details.description',)
                ->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc')
                ->get(); 
            return $data_output;
        } catch (\Exception $e) {
            return $e->getMessage(); 
        }
    }
    public function getAllInprocessProductProduction(){
        try {
            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
            ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('design_revision_for_prod', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
            })
            ->leftJoin('purchase_orders', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
            })
            ->where('production.store_status_quantity_tracking', 'incomplete-store')
            ->where('businesses_details.is_active', true)
            ->where('businesses_details.is_deleted', 0)
            ->groupBy(
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'businesses_details.is_active',
                'production.business_details_id',
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
                'designs.bom_image',
                'designs.design_image',
                'business_application_processes.store_material_sent_date',
                DB::raw('MAX(production.updated_at) as updated_at')
            )
            ->orderBy('updated_at', 'desc')
            ->get();

            return $data_output;
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
}