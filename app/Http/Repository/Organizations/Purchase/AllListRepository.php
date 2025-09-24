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
              // ->leftJoin('designs', function ($join) {
              //     $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
              // })
              ->leftJoin('requisition', function ($join) {
                  $join->on('business_application_processes.business_details_id', '=', 'requisition.business_details_id');
              })
              ->leftJoin('businesses', function ($join) {
                  $join->on('business_application_processes.business_id', '=', 'businesses.id');
              })
              ->leftJoin('businesses_details', function($join) {
                  $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
              })
              // ->leftJoin('design_revision_for_prod', function ($join) {
              //     $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
              // })
              ->leftJoin('purchase_orders', function($join) {
                  $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
              })
                ->leftJoin('estimation', function ($join) {
                  $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
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
                  'businesses.project_name',
                  'businesses.customer_po_number',
                  'businesses.created_at',
                  'businesses_details.product_name',
                  'businesses_details.description',
                  'businesses_details.quantity',
                  'estimation.total_estimation_amount',
                  'businesses_details.is_active',
                  'production.business_id',
                  'production.id',
                  // 'designs.bom_image',
                  // 'designs.design_image',
                  'req2.id',   
                  'req2.bom_file',
                  'req2.updated_at'
              )
              ->select(
                  // 'businesses.id',
                  'production.business_details_id',
                  'businesses_details.id',
                  'businesses.project_name',
                  'businesses.created_at',
                  'businesses.customer_po_number',
                  'businesses_details.product_name',
                  'businesses_details.description',
                  'businesses_details.quantity',
                  'estimation.total_estimation_amount',
                  'businesses_details.is_active',
                  'production.id',
                  'production.id as productionId',
                  // 'designs.bom_image',
                  // 'designs.design_image',
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
          ->leftJoin('businesses_details', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('purchase_orders', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
          })
          ->leftJoin('vendors', function($join) {
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

public function getFollowUpReport($request)
{
    $statuses = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

    $query = PurchaseOrdersModel::leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
        ->whereIn('purchase_orders.purchase_status_from_owner', $statuses)
        ->whereIn('purchase_orders.purchase_status_from_purchase', $statuses);

    // 🔹 Filter by Vendor
    if ($request->filled('vendor_name')) {
        $query->where('purchase_orders.vendor_id', $request->vendor_name);
    }

    // 🔹 Filter by PO Status (your custom logic)
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



    // 🔹 Search keyword
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

    // 🔹 Date Filters
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

    // 🔹 Select and group
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
        ->groupBy(
            'purchase_orders.purchase_orders_id',
            'vendors.vendor_name',
            'vendors.vendor_company_name',
            'vendors.vendor_email',
            'vendors.contact_no',
            'purchase_orders.purchase_status_from_owner',
            'purchase_orders.purchase_status_from_purchase',
            'purchase_orders.purchase_order_mail_submited_to_vendor_date'
        )
        ->orderByDesc('purchase_orders.updated_at');

    // 🔹 For export (Excel / PDF)
    if ($request->filled('export_type')) {
        return [
            'data' => $queryForData->get(),
            'pagination' => null,
        ];
    }

    // 🔹 For AJAX pagination
    $perPage = $request->input('pageSize', 10);
    $currentPage = $request->input('currentPage', 1);

    $totalItems = (clone $queryForData)->count();

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


}