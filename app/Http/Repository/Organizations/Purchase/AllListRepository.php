<?php

namespace App\Http\Repository\Organizations\Purchase;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\{
  BusinessApplicationProcesses,
  PurchaseOrdersModel
};

class AllListRepository
{
  public function getAllListMaterialReceivedForPurchase()
  {
    try {
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')];
      $search = request()->search;
      $perPage = Config::get('AllFileValidation.PAGINATION');
      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
      })
        ->leftJoin('requisition', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'requisition.business_details_id');
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
        ->leftJoin('estimation', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
        })
        ->leftJoin('requisition as req2', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'req2.business_details_id');
        })
        ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
        ->where('businesses_details.is_active', true)
        ->where('businesses_details.is_deleted', 0)
        ->when($search, function ($query) use ($search) {
          $query->where(function ($q) use ($search) {
            $q->where('businesses.project_name', 'LIKE', "%{$search}%")
              ->orWhere('businesses_details.product_name', 'LIKE', "%{$search}%")
              ->orWhere('businesses.grand_total_amount', 'LIKE', "%{$search}%");
          });
        })
        ->groupBy(
          // 'businesses.id',
          'production.business_details_id',
          'businesses_details.id',
          'businesses.project_name',
          'businesses.grand_total_amount',
          'businesses.customer_po_number',
          'businesses.created_at',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'estimation.total_estimation_amount',
          'businesses_details.is_active',
          'production.business_id',
          // 'production.id',
          'req2.id',
          'req2.bom_file',
          'req2.updated_at'
        )
        ->select(
          // 'businesses.id',
          'production.business_details_id',
          'businesses_details.id',
          'businesses.project_name',
          'businesses.grand_total_amount',
          'businesses.created_at',
          'businesses.customer_po_number',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses_details.quantity',
          'estimation.total_estimation_amount',
          'businesses_details.is_active',
          // 'production.id',
          // 'production.id as productionId',
          'req2.id as requistition_id',
          'req2.bom_file',
          'req2.updated_at'
        )->distinct('businesses_details.id')->orderBy('req2.updated_at', 'desc')
        ->paginate($perPage)
        ->withQueryString();

      return $data_output;
    } catch (\Exception $e) {
      throw $e;
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
        ->leftJoin('businesses_details', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('production', function ($join) {
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
          'purchase_orders.is_active',
          'purchase_orders.updated_at'
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

        ->leftJoin('purchase_orders', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->leftJoin('businesses_details', function ($join) {
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
        ->leftJoin('businesses_details', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
        // ->leftJoin('design_revision_for_prod', function ($join) {
        //   $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
        // })

        ->leftJoin('purchase_orders', function ($join) {
          $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
        ->leftJoin('vendors', function ($join) {
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
          'purchase_orders.owner_po_action_date',
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
      $search = request()->search;
      $perPage = Config::get('AllFileValidation.PAGINATION');

      $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
        $join->on('business_application_processes.business_id', '=', 'production.business_id');
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

        // ->distinct('businesses_details.id')
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check_owner)
        ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
        // ->distinct('businesses.id')
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->when($search, function ($query) use ($search) {
          $query->where(function ($q) use ($search) {
            $q->where('businesses_details.product_name', 'LIKE', "%{$search}%");
          });
        })
        ->groupBy(
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses.title'
        )
        ->select(
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses.title',
          DB::raw('MAX(purchase_orders.updated_at) as latest_update') // Aggregate function
        )
        ->orderBy('latest_update', 'desc')
        ->paginate($perPage)
        ->withQueryString();

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
      $search = request()->search;
      $fromDate = request('from_date');
      $toDate = request('to_date');
      $allowedPerPage = [5, 10, 20, 100];
      $perPage = (int) request('per_page', 10);
      if (!in_array($perPage, $allowedPerPage, true)) {
          $perPage = 10;
      }
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
        ->leftJoin('vendors', function ($join) {
          $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
        })
        ->where('businesses_details.id', $id)
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check_owner)
        ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)

        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->when($search, function ($query) use ($search) {
          $query->where(function ($q) use ($search) {
            $q->where('businesses_details.product_name', 'LIKE', "%{$search}%")
              ->orWhere('vendors.vendor_company_name', 'LIKE', "%{$search}%");
          });
        })
        ->when($fromDate, function ($query) use ($fromDate) {
          $d = trim((string) $fromDate);
          if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            $query->whereDate('purchase_orders.updated_at', '>=', $d);
          }
        })
        ->when($toDate, function ($query) use ($toDate) {
          $d = trim((string) $toDate);
          if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            $query->whereDate('purchase_orders.updated_at', '<=', $d);
          }
        })
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
        ->paginate($perPage)
        ->withQueryString();


      return $data_output;
    } catch (\Exception $e) {
      return $e->getMessage(); // Changed to return the error message string
    }
  }

  /**
   * Returns a de-duplicated, ordered list of purchase_orders.purchase_orders_id
   * for ALL purchase orders sent to vendor for this business_details id — no
   * pagination, no search filter (used by the "Download All PO" combined PDF).
   * Mirrors the same filter criteria as getAllListSubmitedPurchaeOrderByVendorBusinessWise().
   */
  public function getAllSubmitedPoIdsBusinessWise($id, $fromDate = null, $toDate = null)
  {
    try {
      $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
      $array_to_be_check_owner = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

      $rows = BusinessApplicationProcesses::leftJoin('production', function ($join) {
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
        ->leftJoin('vendors', function ($join) {
          $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
        })
        ->where('businesses_details.id', $id)
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check_owner)
        ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
        ->where('businesses.is_active', true)
        ->where('businesses.is_deleted', 0)
        ->when($fromDate, function ($query) use ($fromDate) {
          $d = trim((string) $fromDate);
          if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            $query->whereDate('purchase_orders.updated_at', '>=', $d);
          }
        })
        ->when($toDate, function ($query) use ($toDate) {
          $d = trim((string) $toDate);
          if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            $query->whereDate('purchase_orders.updated_at', '<=', $d);
          }
        })
        ->select(
          'purchase_orders.purchase_orders_id as purchase_order_id',
          'purchase_orders.updated_at',
        )
        ->orderBy('purchase_orders.updated_at', 'desc')
        ->get();

      // De-duplicate in PHP (rather than SQL DISTINCT + ORDER BY on a
      // non-selected column, which MySQL rejects) while preserving order.
      return $rows->pluck('purchase_order_id')->filter()->unique()->values();
    } catch (\Exception $e) {
      return collect();
    }
  }

  public function getAllListPurchaseOrderTowardsOwner()
  {
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
          'businesses_details.id',
          'businesses_details.product_name',
          'businesses_details.description',
          'businesses.title'
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
  public function getPurchaseReport($request)
  {
    // Build base query
    $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];
    $array_to_be_check_owner = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

    $query = BusinessApplicationProcesses::leftJoin('production', function ($join) {
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
      ->leftJoin('vendors', function ($join) {
        $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
      })
      ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check_owner)
      ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)

      ->where('businesses.is_active', true)
      ->where('businesses.is_deleted', 0);
    // $query = BusinessApplicationProcesses::leftJoin('production', 'business_application_processes.business_id', '=', 'production.business_id')
    //     ->leftJoin('businesses', 'business_application_processes.business_id', '=', 'businesses.id')
    //     ->leftJoin('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
    //     ->leftJoin('purchase_orders', 'business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id')
    //     ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
    //     ->where('businesses.is_active', true)
    //     ->where('businesses.is_deleted', 0);

    // Apply filters
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('businesses.project_name', 'like', "%{$search}%")
          ->orWhere('businesses.customer_po_number', 'like', "%{$search}%")
          ->orWhere('vendors.vendor_name', 'like', "%{$search}%")
          ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")
          ->orWhere('vendors.vendor_email', 'like', "%{$search}%")
          ->orWhere('vendors.contact_no', 'like', "%{$search}%")
          ->orWhere('businesses_details.product_name', 'like', "%{$search}%")
          ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%");
      });
    }

    if ($request->filled('project_name')) {
      $query->where('businesses.id', $request->project_name);
    }

    if ($request->filled('from_date')) {
      $query->whereDate('purchase_orders.created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
      $query->whereDate('purchase_orders.created_at', '<=', $request->to_date);
    }

    if ($request->filled('year')) {
      $query->whereYear('purchase_orders.updated_at', $request->year);
    }

    if ($request->filled('month')) {
      $query->whereMonth('purchase_orders.updated_at', $request->month);
    }

    // Clone query to apply select, group, order
    $queryForData = (clone $query)
      ->select(
        'purchase_orders.purchase_orders_id as purchase_order_id',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses.project_name',
        'businesses.customer_po_number',
        'vendors.vendor_name',
        'vendors.vendor_company_name',
        'vendors.vendor_email',
        'vendors.contact_no',
        DB::raw('MAX(purchase_orders.created_at) as created_update'),
        DB::raw('MAX(purchase_orders.updated_at) as latest_update')
      )
      ->groupBy(
        'purchase_orders.purchase_orders_id',
        'businesses_details.product_name',
        'businesses_details.description',
        'businesses.project_name',
        'businesses.customer_po_number',
        'vendors.vendor_name',
        'vendors.vendor_company_name',
        'vendors.vendor_email',
        'vendors.contact_no'
      )
      ->orderByDesc('latest_update');

    // ✅ If export requested
    if ($request->filled('export_type')) {
      return [
        'data' => $queryForData->get(),
        'pagination' => null,
      ];
    }

    // ✅ Otherwise return paginated
    $perPage = $request->input('pageSize', 10);
    $currentPage = $request->input('currentPage', 1);

    $totalItems = (clone $queryForData)->get()->count();

    $data = $queryForData
      ->skip(($currentPage - 1) * $perPage)
      ->take($perPage)
      ->get();

    return [
      'data' => $data,
      'pagination' => [
        'currentPage' => $currentPage,
        'pageSize' => $perPage,
        'totalItems' => $totalItems,
        'totalPages' => ceil($totalItems / $perPage),
        'from' => ($currentPage - 1) * $perPage + 1,
        'to' => (($currentPage - 1) * $perPage) + count($data),
      ]
    ];
  }
  public function getPurchasePartyReport($request)
  {
    $statuses = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

    $query = PurchaseOrdersModel::leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
      ->whereIn('purchase_orders.purchase_status_from_owner', $statuses)
      ->whereIn('purchase_orders.purchase_status_from_purchase', $statuses);

    if ($request->filled('vendor_name')) {
      $query->where('purchase_orders.vendor_id', $request->vendor_name);
    }

    if ($request->filled('search')) {
      $s = $request->search;
      $query->where(function ($q) use ($s) {
        $q->where('vendors.vendor_name', 'like', "%{$s}%")
          ->orWhere('vendors.vendor_company_name', 'like', "%{$s}%")
          ->orWhere('vendors.vendor_email', 'like', "%{$s}%")
          ->orWhere('vendors.contact_no', 'like', "%{$s}%")
          ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$s}%");
      });
    }

    if ($request->filled('from_date')) {
      $query->whereDate('purchase_orders.created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
      $query->whereDate('purchase_orders.created_at', '<=', $request->to_date);
    }
    if ($request->filled('year')) {
      $query->whereYear('purchase_orders.updated_at', $request->year);
    }
    if ($request->filled('month')) {
      $query->whereMonth('purchase_orders.updated_at', $request->month);
    }

    $queryForData = (clone $query)
      ->select(
        'purchase_orders.purchase_orders_id as purchase_order_id',
        'vendors.vendor_name',
        'vendors.vendor_company_name',
        'vendors.vendor_email',
        'vendors.contact_no',
        DB::raw('MAX(purchase_orders.updated_at) as latest_update')
      )
      ->groupBy(
        'purchase_orders.purchase_orders_id',
        'vendors.vendor_name',
        'vendors.vendor_company_name',
        'vendors.vendor_email',
        'vendors.contact_no',
      )
      ->orderByDesc('latest_update');

    if ($request->filled('export_type')) {
      return [
        'data' => $queryForData->get(),
        'pagination' => null,
      ];
    }

    $perPage = $request->input('pageSize', 10);
    $currentPage = $request->input('currentPage', 1);

    $totalItems = (clone $queryForData)->get()->count();

    $data = $queryForData
      ->skip(($currentPage - 1) * $perPage)
      ->take($perPage)
      ->get();

    return [
      'data' => $data,
      'pagination' => [
        'currentPage' => $currentPage,
        'pageSize' => $perPage,
        'totalItems' => $totalItems,
        'totalPages' => ceil($totalItems / $perPage),
        'from' => ($currentPage - 1) * $perPage + 1,
        'to' => (($currentPage - 1) * $perPage) + count($data),
      ]
    ];
  }

  //   public function getFollowUpReport($request)
  //   {
  //     $statuses = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

  //     $query = PurchaseOrdersModel::leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
  //       ->whereIn('purchase_orders.purchase_status_from_owner', $statuses)
  //       ->whereIn('purchase_orders.purchase_status_from_purchase', $statuses);

  //     // 🔹 Filter by Vendor
  //     if ($request->filled('vendor_name')) {
  //       $query->where('purchase_orders.vendor_id', $request->vendor_name);
  //     }

  //     // 🔹 Filter by PO Status (your custom logic)
  //     if ($request->filled('po_status')) {
  //       $status = $request->po_status;

  //       $query->where(function ($q) use ($status) {
  //         if ($status === 'open') {
  //           $q->whereNull('purchase_orders.purchase_status_from_owner')
  //             ->where('purchase_orders.purchase_status_from_purchase', 1126);
  //         } elseif ($status === 'partially_received') {
  //           $q->where('purchase_orders.purchase_status_from_owner', 1127)
  //             ->where('purchase_orders.purchase_status_from_purchase', 1126);
  //         } elseif ($status === 'pending_gate_pass') {
  //           $q->where('purchase_orders.purchase_status_from_owner', 1129)
  //             ->where('purchase_orders.purchase_status_from_purchase', 1129);
  //         }
  //       });
  //     }



  //     // 🔹 Search keyword
  //     if ($request->filled('search')) {
  //       $s = $request->search;
  //       $query->where(function ($q) use ($s) {
  //         $q->where('vendors.vendor_name', 'like', "%{$s}%")
  //           ->orWhere('vendors.vendor_company_name', 'like', "%{$s}%")
  //           ->orWhere('vendors.vendor_email', 'like', "%{$s}%")
  //           ->orWhere('vendors.contact_no', 'like', "%{$s}%")
  //           ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$s}%");
  //       });
  //     }

  //     // 🔹 Date Filters
  //     if ($request->filled('from_date')) {
  //       $query->whereDate('purchase_orders.created_at', '>=', $request->from_date);
  //     }

  //     if ($request->filled('to_date')) {
  //       $query->whereDate('purchase_orders.created_at', '<=', $request->to_date);
  //     }

  //     if ($request->filled('year')) {
  //       $query->whereYear('purchase_orders.updated_at', $request->year);
  //     }

  //     if ($request->filled('month')) {
  //       $query->whereMonth('purchase_orders.updated_at', $request->month);
  //     }

  //     // 🔹 Select and group
  //     $queryForData = (clone $query)
  //       ->select(
  //         'purchase_orders.purchase_orders_id as purchase_order_id',
  //         'vendors.vendor_name',
  //         'vendors.vendor_company_name',
  //         'vendors.vendor_email',
  //         'vendors.contact_no',
  //         'purchase_orders.purchase_status_from_owner',
  //         'purchase_orders.purchase_status_from_purchase',
  //         'purchase_orders.purchase_order_mail_submited_to_vendor_date',
  //         DB::raw('MAX(purchase_orders.updated_at) as latest_update')
  //       )
  //       ->groupBy(
  //         'purchase_orders.purchase_orders_id',
  //         'vendors.vendor_name',
  //         'vendors.vendor_company_name',
  //         'vendors.vendor_email',
  //         'vendors.contact_no',
  //         'purchase_orders.purchase_status_from_owner',
  //         'purchase_orders.purchase_status_from_purchase',
  //         'purchase_orders.purchase_order_mail_submited_to_vendor_date'
  //       )
  //       ->orderByDesc('purchase_orders.updated_at');

  //     // 🔹 For export (Excel / PDF)
  //     if ($request->filled('export_type')) {
  //       return [
  //         'data' => $queryForData->get(),
  //         'pagination' => null,
  //       ];
  //     }

  //     // 🔹 For AJAX pagination
  //     $perPage = $request->input('pageSize', 10);
  //     $currentPage = $request->input('currentPage', 1);

  //     $totalItems = (clone $queryForData)->count();

  //     $data = $queryForData
  //       ->skip(($currentPage - 1) * $perPage)
  //       ->take($perPage)
  //       ->get();

  //     return [
  //       'data' => $data,
  //       'pagination' => [
  //         'currentPage' => $currentPage,
  //         'pageSize' => $perPage,
  //         'totalItems' => $totalItems,
  //         'totalPages' => ceil($totalItems / $perPage),
  //         'from' => ($currentPage - 1) * $perPage + 1,
  //         'to' => (($currentPage - 1) * $perPage) + count($data),
  //       ]
  //     ];
  //   }
  // }
  public function getFollowUpReport($request)
  {
    try {

      $statuses = [
        config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')
      ];

      // Filter POs that have been sent to vendor (regardless of status field values)
      // A PO is considered "sent to vendor" if purchase_order_mail_submited_to_vendor_date is NOT NULL
      $query = PurchaseOrdersModel::leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
        ->whereNotNull('purchase_orders.purchase_order_mail_submited_to_vendor_date');

      /* ----------------------------------
           🔹 FILTERS
        ---------------------------------- */

      // Filter: Vendor
      if ($request->filled('vendor_name')) {
        $query->where('purchase_orders.vendor_id', $request->vendor_name);
      }

      // Filter: PO Status
      if ($request->filled('po_status')) {
        $status = $request->po_status;
        $query->where(function ($q) use ($status) {
          if ($status === 'open') {
            $q->whereNull('purchase_orders.purchase_status_from_owner')
              ->where('purchase_orders.purchase_status_from_purchase', 1126);
          } elseif ($status === 'partially_received') {
            $q->where('purchase_orders.purchase_status_from_owner', 1127)
              ->where('purchase_orders.purchase_status_from_purchase', 1126);
          } elseif ($status === 'pending_gate_pass') {
            $q->where('purchase_orders.purchase_status_from_owner', 1129)
              ->where('purchase_orders.purchase_status_from_purchase', 1129);
          }
        });
      }

      // Search filter
      if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
          $q->where('vendors.vendor_name', 'like', "%{$s}%")
            ->orWhere('vendors.vendor_company_name', 'like', "%{$s}%")
            ->orWhere('vendors.vendor_email', 'like', "%{$s}%")
            ->orWhere('vendors.contact_no', 'like', "%{$s}%")
            ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$s}%");
        });
      }

      // Date filters
      if ($request->filled('from_date')) {
        $query->whereDate('purchase_orders.created_at', '>=', $request->from_date);
      }

      if ($request->filled('to_date')) {
        $query->whereDate('purchase_orders.created_at', '<=', $request->to_date);
      }

      if ($request->filled('year')) {
        $query->whereYear('purchase_orders.updated_at', $request->year);
      }

      if ($request->filled('month')) {
        $query->whereMonth('purchase_orders.updated_at', $request->month);
      }

      /* ----------------------------------
           🔹 SELECT + FIX STRICT MODE
        ---------------------------------- */

      $queryForData = (clone $query)
        ->select(
          'purchase_orders.purchase_orders_id as purchase_order_id',
          'vendors.vendor_name',
          'vendors.vendor_company_name',
          'vendors.vendor_email',
          'vendors.contact_no',
          'purchase_orders.purchase_status_from_owner',
          'purchase_orders.purchase_status_from_purchase',
          'purchase_orders.purchase_order_mail_submited_to_vendor_date',
          DB::raw('MAX(purchase_orders.updated_at) as latest_update')
        )
        ->groupByRaw('
                purchase_orders.purchase_orders_id,
                vendors.vendor_name,
                vendors.vendor_company_name,
                vendors.vendor_email,
                vendors.contact_no,
                purchase_orders.purchase_status_from_owner,
                purchase_orders.purchase_status_from_purchase,
                purchase_orders.purchase_order_mail_submited_to_vendor_date
            ')
        ->orderByDesc('latest_update');

      /* ----------------------------------
           🔹 EXPORT (NO PAGINATION)
        ---------------------------------- */
      if ($request->filled('export_type')) {
        return [
          'data' => $queryForData->get(),
          'pagination' => null
        ];
      }

      /* ----------------------------------
           🔹 AJAX PAGINATION
        ---------------------------------- */

      $perPage     = $request->input('pageSize', 10);
      $currentPage = $request->input('currentPage', 1);

      $totalItems = (clone $queryForData)->get()->count();

      $data = $queryForData
        ->skip(($currentPage - 1) * $perPage)
        ->take($perPage)
        ->get();

      return [
        'status' => true,
        'data' => $data,
        'pagination' => [
          'currentPage' => $currentPage,
          'pageSize' => $perPage,
          'totalItems' => $totalItems,
          'totalPages' => ceil($totalItems / $perPage),
          'from' => ($currentPage - 1) * $perPage + 1,
          'to' => (($currentPage - 1) * $perPage) + count($data),
        ]
      ];
    } catch (\Exception $e) {

      return [
        'status' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  /**
   * Shared query builder for the Business PO report.
   *
   * Every filter the screen exposes lives here so the paginated view
   * (getBusinessPoReport) and the PDF/CSV exports (getBusinessPoReportAll)
   * can never drift apart — an export always reflects exactly the filters
   * the user has applied on screen.
   */
  private function businessPoReportQuery($request)
  {
    // Base: ALL POs (created / approved / sent-to-vendor / GRN-received) —
    // purchase_orders is now the driving table so a PO shows up as soon as it
    // exists, and GRN data (tracking + grn_tbl) is attached via LEFT JOIN so
    // it is optional. A PO with no GRN yet renders with '—' / 0.00 in the
    // GRN-related columns instead of being invisible/unsearchable.
    $query = DB::table('purchase_orders')
      ->join('purchase_order_details',
             'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
      ->join('businesses',
             'businesses.id', '=', 'purchase_orders.business_id')
      ->join('businesses_details',
             'businesses_details.id', '=', 'purchase_orders.business_details_id')
      ->join('vendors',
             'vendors.id', '=', 'purchase_orders.vendor_id')
      // Optional GRN data: is_deleted guards live in the ON clause, not a
      // WHERE clause, otherwise the LEFT JOIN is silently turned back into
      // an INNER JOIN and pending (no-GRN) POs disappear again.
      ->leftJoin('tbl_grn_po_quantity_tracking', function ($join) {
        $join->on('tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
             ->where('tbl_grn_po_quantity_tracking.is_deleted', '=', 0);
      })
      ->leftJoin('grn_tbl', function ($join) {
        $join->on('grn_tbl.id', '=', 'tbl_grn_po_quantity_tracking.grn_id')
             ->where('grn_tbl.is_deleted', '=', 0);
      })
      ->leftJoin('tbl_unit as u',
             'u.id', '=', 'purchase_order_details.unit')
      // Join the part-item master so we can fall back to its description when
      // purchase_order_details.description was entered as a bad value (e.g. "1")
      ->leftJoin('tbl_part_item as pi',
             'pi.id', '=', 'purchase_order_details.part_no_id')
      ->where('purchase_orders.is_deleted', 0)
      ->where('purchase_orders.is_active',  1)
      ->where('businesses.is_active',  1)
      ->where('businesses.is_deleted', 0);

    if ($request->filled('business_id')) {
      $query->where('purchase_orders.business_id', $request->business_id);
    }
    if ($request->filled('product_id')) {
      $query->where('purchase_orders.business_details_id', $request->product_id);
    }
    // Date/year filters must not silently drop pending (no-GRN) POs: grn_tbl.grn_date
    // is NULL until a GRN exists, so match on COALESCE(grn_tbl.grn_date, purchase_orders.created_at).
    if ($request->filled('year')) {
      $query->whereRaw('YEAR(COALESCE(grn_tbl.grn_date, purchase_orders.created_at)) = ?', [$request->year]);
    }
    if ($request->filled('from_date')) {
      $query->whereRaw('DATE(COALESCE(grn_tbl.grn_date, purchase_orders.created_at)) >= ?', [$request->from_date]);
    }
    if ($request->filled('to_date')) {
      $query->whereRaw('DATE(COALESCE(grn_tbl.grn_date, purchase_orders.created_at)) <= ?', [$request->to_date]);
    }
    if ($request->filled('grn_status')) {
      if ($request->grn_status === 'received') {
        $query->whereNotNull('grn_tbl.id');
      } elseif ($request->grn_status === 'pending') {
        $query->whereNull('grn_tbl.id');
      }
    }
    if ($request->filled('search')) {
      $s = $request->search;
      $query->where(function ($q) use ($s) {
        $q->where('purchase_orders.purchase_orders_id',    'like', "%{$s}%")
          ->orWhere('grn_tbl.grn_no_generate',             'like', "%{$s}%")
          ->orWhere('businesses.project_name',             'like', "%{$s}%")
          ->orWhere('businesses_details.product_name',     'like', "%{$s}%")
          ->orWhere('vendors.vendor_name',                 'like', "%{$s}%")
          ->orWhere('vendors.vendor_company_name',         'like', "%{$s}%")
          ->orWhere('purchase_order_details.description',  'like', "%{$s}%");
      });
    }

    return $query;
  }

  /** Column list + ordering shared by the paginated view and the exports. */
  private function businessPoReportSelect($query)
  {
    return $query->select(
      'businesses.id                                    as business_id',
      'businesses.project_name',
      'businesses_details.id                           as business_details_id',
      'businesses_details.product_name',
      'purchase_orders.id                              as po_id',
      'purchase_orders.purchase_orders_id',
      'purchase_orders.created_at                      as po_date',
      'vendors.vendor_name',
      'vendors.vendor_company_name',
      'grn_tbl.id                                      as grn_row_id',
      'grn_tbl.grn_no_generate',
      'grn_tbl.grn_date',
      // Prefer the part-item master description; fall back to the PO line
      // description only when there is no master row (legacy / free-text rows).
      // This fixes rows where someone typed a stray value like "1" into the
      // description but the part_no_id correctly points at the master.
      DB::raw("COALESCE(NULLIF(TRIM(pi.description), ''), purchase_order_details.description) as material_description"),
      'purchase_order_details.quantity                 as po_quantity',
      'u.name                                          as unit_name',
      'purchase_order_details.rate',
      'tbl_grn_po_quantity_tracking.actual_quantity',
      'tbl_grn_po_quantity_tracking.accepted_quantity',
      'tbl_grn_po_quantity_tracking.rejected_quantity',
      DB::raw('(COALESCE(tbl_grn_po_quantity_tracking.accepted_quantity, 0) * purchase_order_details.rate) as line_amount')
      )
      ->orderBy('businesses.project_name')
      ->orderBy('purchase_orders.purchase_orders_id')
      ->orderBy('grn_tbl.grn_date')
      ->orderBy('purchase_order_details.id');
  }

  /** Grand total of (accepted_quantity x rate) across ALL matching rows. */
  private function businessPoReportGrandTotal($query)
  {
    // NULL-safe: pending (no-GRN) rows have no tracking.accepted_quantity, so they
    // must contribute 0 to the sum rather than turning the whole SUM() into NULL.
    return (float) (clone $query)->selectRaw(
      'SUM(COALESCE(tbl_grn_po_quantity_tracking.accepted_quantity, 0) * purchase_order_details.rate) as total'
    )->value('total');
  }

  public function getBusinessPoReport($request)
  {
    try {
      // Page size: default 100, selectable via the left-side dropdown (5/10/20/50/100)
      $allowedPerPage = [5, 10, 20, 50, 100];
      $perPage = (int) $request->input('per_page', 100);
      if (!in_array($perPage, $allowedPerPage, true)) {
        $perPage = 100;
      }

      $query      = $this->businessPoReportQuery($request);
      $grandTotal = $this->businessPoReportGrandTotal($query);

      $paginator = $this->businessPoReportSelect($query)
        ->paginate($perPage)
        ->withQueryString();

      return [
        'status'     => true,
        'data'       => $paginator,
        'grandTotal' => $grandTotal,
      ];
    } catch (\Exception $e) {
      return ['status' => false, 'message' => $e->getMessage()];
    }
  }

  /**
   * Same filtered result set as getBusinessPoReport() but WITHOUT pagination —
   * used by the PDF / CSV exports so the file contains every matching row,
   * not just the page the user happens to be looking at.
   */
  public function getBusinessPoReportAll($request)
  {
    try {
      $query      = $this->businessPoReportQuery($request);
      $grandTotal = $this->businessPoReportGrandTotal($query);
      $rows       = $this->businessPoReportSelect($query)->get();

      return [
        'status'     => true,
        'data'       => $rows,
        'grandTotal' => $grandTotal,
      ];
    } catch (\Exception $e) {
      return ['status' => false, 'message' => $e->getMessage()];
    }
  }
}
