<?php

namespace App\Http\Repository\Organizations\Finance;

use Illuminate\Support\Facades\DB;
use App\Models\{
  BusinessApplicationProcesses,
  PurchaseOrdersModel,
  PurchaseOrderModel,
  Logistics,
  CustomerProductQuantityTracking
};

class AllListRepository
{
  public function getAllListBusinessDetails()
  {
    try {
      $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];

      $data_output = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
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
          'businesses.project_name',
          'businesses.customer_po_number',
          'businesses.title',
          'businesses.business_pdf',
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses.remarks',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.rate',
          'businesses_details.total_amount',
          'businesses.created_at',
          'businesses.updated_at'
        )
        ->select(
          'businesses.id',
          'businesses.project_name',
          'businesses_details.id',
          'businesses.title',
          'businesses.business_pdf',
          'businesses.customer_po_number',
          'businesses.remarks',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'businesses_details.rate',
          'businesses_details.total_amount',
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


  public function getAllListSRAndGRNGeanrated()
  {
    try {
      $array_to_be_check_status_sanction = [config('constants.STORE_DEPARTMENT.STORE_RECIEPT_GENRATED_SENT_TO_FINANCE_GRN_WISE')];

      $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
      $array_not_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];

      $data_output = PurchaseOrdersModel::leftJoin('gatepass', function ($join) {
        $join->on('purchase_orders.purchase_orders_id', '=', 'gatepass.purchase_orders_id');
      })
        ->leftJoin('businesses_details', function ($join) {
          $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('vendors', function ($join) {
          $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
        })
        ->leftJoin('grn_tbl', function ($join) {
          $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
        })
        ->where('grn_tbl.grn_status_sanction', $array_to_be_check_status_sanction)
        ->whereNotNull('grn_tbl.grn_no_generate')
        ->whereNotNull('grn_tbl.store_receipt_no_generate')
        ->whereNotNull('grn_tbl.store_remark')
        ->groupBy(
          'purchase_orders.purchase_orders_id',
          'grn_tbl.id',
          'purchase_orders.business_details_id',
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
          'grn_tbl.updated_at'
        )
        ->select(
          'purchase_orders.purchase_orders_id',
          'grn_tbl.id',
          'purchase_orders.business_details_id',
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
          'grn_tbl.updated_at'
        )
        ->orderBy('grn_tbl.updated_at', 'desc')
        ->get();

      return $data_output;
    } catch (\Exception $e) {
      return $e->getMessage();
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
        ->leftJoin('businesses_details', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })

        ->leftJoin('purchase_orders', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->leftJoin('vendors', function ($join) {
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
      return $data_output;
    } catch (\Exception $e) {
      return $e->getMessage(); // Changed to return the error message string
    }
  }
  public function listPOSentForApprovaTowardsOwner()
  {
    try {
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
        ->leftJoin('businesses_details', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('design_revision_for_prod', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        })
        ->leftJoin('purchase_orders', function ($join) {
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
      // $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT')];
      // $array_not_to_be_check = [config('constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY')];
      $array_to_be_check = [config('constants.HIGHER_AUTHORITY.INVOICE_RECEIVED_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY_GRN_WISE')];

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
  public function getAllListBusinessReceivedFromLogistics()
  {
    try {
      $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_RECEIVED_FROM_LOGISTICS')];
      $array_to_be_quantity_tracking = [config('constants.FINANCE_DEPARTMENT.RECEVIDE_COMPLETED_QUANLTITY_FROM_LOGISTICS_DEPT_TO_FIANANCE_DEPT')];

      $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
        $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
      })
        ->leftJoin('businesses', function ($join) {
          $join->on('tbl_logistics.business_id', '=', 'businesses.id');
        })
        ->leftJoin('business_application_processes as bap1', function ($join) {
          $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
        })
        ->leftJoin('businesses_details', function ($join) {
          $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('tbl_transport_name', function ($join) {
          $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
        })
        ->leftJoin('tbl_vehicle_type', function ($join) {
          $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
        })
        ->leftJoin('production', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
        })

        ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
        ->where('businesses.is_active', true)
        ->select(
          'tbl_customer_product_quantity_tracking.id',
          'tbl_customer_product_quantity_tracking.business_details_id',
          'businesses.title',
          'businesses.project_name',
          'businesses.created_at',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses.title',
          'businesses_details.quantity',
          'businesses.remarks',
          'businesses.is_active',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          // DB::raw('(businesses_details.quantity - tbl_customer_product_quantity_tracking.completed_quantity) AS remaining_quantity'),
          DB::raw('(SELECT SUM(t2.completed_quantity)
              FROM tbl_customer_product_quantity_tracking AS t2
              WHERE t2.business_details_id = businesses_details.id
                AND t2.id <= tbl_customer_product_quantity_tracking.id
             ) AS cumulative_completed_quantity'),
          DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
      FROM tbl_customer_product_quantity_tracking AS t2
      WHERE t2.business_details_id = businesses_details.id
        AND t2.id <= tbl_customer_product_quantity_tracking.id
     )) AS remaining_quantity'),
          DB::raw('production.updated_at AS updated_at'),
          DB::raw('tbl_customer_product_quantity_tracking.updated_at AS tracking_updated_at'),
          DB::raw('tbl_customer_product_quantity_tracking.completed_quantity AS completed_quantity'),
          'production.business_id',
          'production.id as productionId',
          'bap1.store_material_sent_date',
          'tbl_customer_product_quantity_tracking.updated_at',
          'tbl_transport_name.name as transport_name',
          'tbl_vehicle_type.name as vehicle_name',
          'tbl_logistics.truck_no',
          'tbl_logistics.from_place',
          'tbl_logistics.to_place',

        )
        ->orderBy('tbl_logistics.updated_at', 'desc')
        ->get();

      return $data_output;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function getAllListBusinessFianaceSendToDispatch()
  {
    try {

      $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
      $array_to_be_quantity_tracking = [config('constants.FINANCE_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

      $array_to_be_check_new = ['0'];
      $data_output = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function ($join) {
        $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
      })
        ->leftJoin('businesses', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
        })
        ->leftJoin('business_application_processes as bap1', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_application_processes_id', '=', 'bap1.id');
        })
        ->leftJoin('businesses_details', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('tbl_transport_name', function ($join) {
          $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
        })
        ->leftJoin('tbl_vehicle_type', function ($join) {
          $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
        })
        ->leftJoin('production', function ($join) {
          $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
        })
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->where('tbl_customer_product_quantity_tracking.fianace_list_status', 'Send_Dispatch')
        // ->distinct('businesses_details.id')
        ->select(
          'tbl_customer_product_quantity_tracking.id',
          'tbl_customer_product_quantity_tracking.business_details_id',
          'businesses.title',
          'businesses.project_name',
          'businesses.created_at',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses.title',
          'businesses_details.quantity',
          'businesses.remarks',
          'businesses.is_active',
          'tbl_customer_product_quantity_tracking.completed_quantity',
          // DB::raw('(businesses_details.quantity - tbl_customer_product_quantity_tracking.completed_quantity) AS remaining_quantity'),
          DB::raw('(SELECT SUM(t2.completed_quantity)
      FROM tbl_customer_product_quantity_tracking AS t2
      WHERE t2.business_details_id = businesses_details.id
        AND t2.id <= tbl_customer_product_quantity_tracking.id
     ) AS cumulative_completed_quantity'),
          DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
      FROM tbl_customer_product_quantity_tracking AS t2
      WHERE t2.business_details_id = businesses_details.id
        AND t2.id <= tbl_customer_product_quantity_tracking.id
     )) AS remaining_quantity'),
          // DB::raw('production.updated_at AS updated_at'),
          'production.business_id',
          'production.id as productionId',
          'bap1.store_material_sent_date',
          'tbl_customer_product_quantity_tracking.updated_at',
          'tbl_transport_name.name as transport_name',
          'tbl_vehicle_type.name as vehicle_name',
          'tbl_logistics.truck_no',
          'tbl_logistics.from_place',
          'tbl_logistics.to_place',

        )
        ->orderBy('tbl_logistics.updated_at', 'desc')
        ->get();

      return $data_output;
    } catch (\Exception $e) {
      return $e;
    }
  }
}
