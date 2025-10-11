<?php
namespace App\Http\Repository\Organizations\Report;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    Business, 
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    PurchaseOrdersModel,
    Logistics,
    Gatepass,
    CustomerProductQuantityTracking,
    GrnPOQuantityTracking,
    ItemStock,
    PartItem
    };
use Config;

class ReportRepository  {
public function getCompletedProductList(Request $request)
{
    try {
        $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

        // Base query
              $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
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
            ->leftJoin('tbl_dispatch', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
            })
             ->leftJoin('estimation', function ($join) {
                $join->on('tbl_logistics.business_details_id', '=', 'estimation.business_details_id');
            })
               ->leftJoin(DB::raw('(SELECT business_details_id, SUM(items_used_total_amount) as total_items_used_amount 
                     FROM production_details 
                     GROUP BY business_details_id) as pd'), 
           'tbl_dispatch.business_details_id', '=', 'pd.business_details_id')
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

        if ($request->filled('business_details_id')) {
            $query->where('tbl_logistics.business_details_id', $request->business_details_id);
        }

        // if ($request->filled('from_date')) {
        //     $query->whereDate('tbl_dispatch.created_at', '>=', $request->from_date);
        // }

        // if ($request->filled('to_date')) {
        //     $query->whereDate('tbl_dispatch.created_at', '<=', $request->to_date);
        // }

        // if ($request->filled('year')) {
        //     $query->whereYear('tbl_dispatch.updated_at', $request->year);
        // }

        // if ($request->filled('month')) {
        //     $query->whereMonth('tbl_dispatch.updated_at', $request->month);
        // }

        // if ($request->filled('production_status_id')) {
        //     $statusIds = explode(',', $request->production_status_id);
        //     $query->whereIn('bap1.production_status_id', $statusIds);
        // }

        // SELECT, GROUP, HAVING
        $query->select(
                'businesses_details.id as business_details_id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
                DB::raw('MAX(tbl_dispatch.updated_at) as updated_at'),
                 DB::raw('COALESCE(MAX(pd.total_items_used_amount), 0) as total_items_used_amount'),
                'estimation.total_estimation_amount',
            )
            ->groupBy(
                'businesses_details.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                'estimation.total_estimation_amount',
            )
            ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity');

        // Sort
        $query->orderBy(DB::raw('MAX(tbl_dispatch.updated_at)'), 'desc');

        // ✅ Export handling
        if ($request->filled('export_type')) {
            $data = $query->get();
            return [
                'data' => $data,
                'pagination' => null,
                'total_count' => $data->count(), // ✅ fix for missing key
            ];
        }

        // ✅ Pagination handling
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);

        // Clone the base query for count
        $countQuery = clone $query;
        $totalItems = $countQuery->get()->count(); // Grouped query, so we get and count manually

        $data = (clone $query)
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
            ],
            'total_count' => $totalItems, // ✅ added to prevent controller crash
        ];
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
// public function getCompletedProductList($request)
// {
//     try {
//         $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
//         $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

//         // Base query
//         $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function ($join) {
//                 $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
//             })
//             ->leftJoin('businesses', function ($join) {
//                 $join->on('tbl_logistics.business_id', '=', 'businesses.id');
//             })
//             ->leftJoin('business_application_processes as bap1', function ($join) {
//                 $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
//             })
//             ->leftJoin('businesses_details', function ($join) {
//                 $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
//             })
//             ->leftJoin('tbl_dispatch', function ($join) {
//                 $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
//             })
//             ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
//             ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
//             ->where('businesses.is_active', true)
//             ->where('businesses.is_deleted', 0);
      
//         if ($request->filled('from_date') && $request->filled('to_date')) {
//             $query->whereBetween('tbl_dispatch.updated_at', [$request->from_date, $request->to_date]);
//         }

//         if ($request->filled('year')) {
//             $query->whereYear('tbl_dispatch.updated_at', $request->year);
//         }

//         if ($request->filled('month')) {
//             $query->whereMonth('tbl_dispatch.updated_at', $request->month);
//         }

//         $data_output = $query->select(
//             'businesses_details.id as business_details_id',
//             'businesses.project_name',
//             'businesses.customer_po_number',
//             'businesses.title',
//             'businesses.created_at',
//             'businesses_details.product_name',
//             'businesses_details.description',
//             'businesses_details.quantity',
//             DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
//             DB::raw('MAX(tbl_dispatch.updated_at) as updated_at') 
//         )
//         ->groupBy(
//             'businesses_details.id',
//             'businesses.project_name',
//             'businesses.customer_po_number',
//             'businesses.title',
//             'businesses.created_at',
//             'businesses_details.product_name',
//             'businesses_details.description',
//             'businesses_details.quantity'
//         )
//         ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
//         ->orderBy(DB::raw('MAX(tbl_dispatch.updated_at)'), 'desc') 
//         ->get();

//         $totalCount = $query->select('businesses_details.id')
//             ->groupBy(
//                 'businesses_details.id',
//                 'businesses.customer_po_number',
//                 'businesses.title',
//                 'businesses_details.product_name',
//                 'businesses_details.description',
//                 'businesses_details.quantity'
//             )
//             ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
//             ->get()
//             ->count();

//         return [
//             'data' => $data_output,
//             'total_count' => $totalCount
//         ];
//     } catch (\Exception $e) {
//         return $e;
//     }
// }
public function listDesignReport(Request $request)
{
    try {
        $array_to_be_check = [
            config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')
        ];

        $query = BusinessApplicationProcesses::leftJoin('production', function ($join) {
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
            $join->on('designs.id', '=', 'design_revision_for_prod.design_id');
        })
        
        ->where('production.is_approved_production', 1)
        // ->whereIn('business_application_processes.production_status_id', $array_to_be_check)
        ->where('businesses_details.is_active', true)
        ->where('businesses_details.is_deleted', 0)
        ->distinct('businesses_details.id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%");
                   
                    
            });
        }

        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

        // if ($request->filled('from_date')) {
        //     $query->whereDate('production.created_at', '>=', $request->from_date);
        // }

        // if ($request->filled('to_date')) {
        //     $query->whereDate('production.created_at', '<=', $request->to_date);
        // }
         if ($request->filled('from_date')) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $query->where('production.updated_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $to = Carbon::parse($request->to_date)->endOfDay();
            $query->where('production.updated_at', '<=', $to);
        }
        if ($request->filled('year')) {
            $query->whereYear('production.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('production.updated_at', $request->month);
        }
        if ($request->filled('production_status_id')) {
    $statusIds = explode(',', $request->production_status_id);
    $query->whereIn('business_application_processes.production_status_id', $statusIds);
}


        $query->select(
            'businesses.id as business_id',
            'businesses.project_name',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.id as business_details_id',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            'businesses_details.rate',
            'designs.bom_image',
            'designs.design_image',
            'design_revision_for_prod.reject_reason_prod',
            'design_revision_for_prod.id as design_revision_for_prod_id',
            'design_revision_for_prod.remark_by_design as remark_by_design',
            'design_revision_for_prod.reject_reason_prod as reject_reason_prod',
            'design_revision_for_prod.design_image as re_design_image',
            'design_revision_for_prod.bom_image as re_bom_image',
            'production.updated_at',
            'business_application_processes.production_status_id'
        )
        ->orderBy('production.updated_at', 'desc');

        // ✅ Export data (no pagination)
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null,
            ];
        }

        // ✅ Paginated data
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
}

public function getProductionReport($request)
{
    try {
        $array_to_be_quantity_tracking = [
            config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')
        ];

        $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
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
            ->leftJoin('tbl_dispatch', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
            })
            ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
  ->whereRaw('tbl_customer_product_quantity_tracking.id = (
        SELECT MAX(id) 
        FROM tbl_customer_product_quantity_tracking t2 
        WHERE t2.business_details_id = businesses_details.id
    )');   // ✅ only latest entry per product
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('tbl_dispatch.created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('tbl_dispatch.created_at', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_dispatch.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_dispatch.updated_at', $request->month);
        }

        // Handle Production Status (Pending / Completed)
        if ($request->filled('production_status_id')) {
            if ($request->production_status_id === 'Completed') {
                $query->whereRaw('
                    (businesses_details.quantity - (
                        SELECT SUM(t2.completed_quantity)
                        FROM tbl_customer_product_quantity_tracking AS t2
                        WHERE t2.business_details_id = businesses_details.id
                          AND t2.id <= tbl_customer_product_quantity_tracking.id
                    )) <= 0
                ');
            } elseif ($request->production_status_id === 'Pending') {
                $query->whereRaw('
                    (businesses_details.quantity - (
                        SELECT SUM(t2.completed_quantity)
                        FROM tbl_customer_product_quantity_tracking AS t2
                        WHERE t2.business_details_id = businesses_details.id
                          AND t2.id <= tbl_customer_product_quantity_tracking.id
                    )) > 0
                ');
            }
        }

        // Select fields
        $query->select(
            'tbl_customer_product_quantity_tracking.id',
            'businesses.project_name',
            'businesses.created_at',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            'tbl_logistics.truck_no',
            'tbl_dispatch.outdoor_no',
            'tbl_dispatch.gate_entry',
            'tbl_dispatch.remark as dispatch_remark',
            'tbl_dispatch.updated_at',
            'tbl_logistics.from_place',
            'tbl_logistics.to_place',
            'tbl_customer_product_quantity_tracking.completed_quantity',
            DB::raw('(SELECT SUM(t2.completed_quantity)
                      FROM tbl_customer_product_quantity_tracking AS t2
                      WHERE t2.business_details_id = businesses_details.id
                        AND t2.id <= tbl_customer_product_quantity_tracking.id
                     ) AS cumulative_completed_quantity'),
            DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
                      FROM tbl_customer_product_quantity_tracking AS t2
                      WHERE t2.business_details_id = businesses_details.id
                        AND t2.id <= tbl_customer_product_quantity_tracking.id
                     )) AS remaining_quantity')
        );

        $query->orderBy('tbl_dispatch.updated_at', 'desc');

        // ✅ Export mode: return query builder
        if ($request->filled('export_type')) {
            return [
                'data' => $query, // ⛔ DO NOT CALL ->get() here
                'pagination' => null,
            ];
        }

        // ✅ Pagination mode
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);

        $totalItems = (clone $query)->count();

        $data = (clone $query)
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

    } catch (\Exception $e) {
        return [
            'data' => [],
            'pagination' => null,
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}
public function getSecurityReport(Request $request)
{
    try {
        $query = PurchaseOrdersModel::leftJoin('gatepass', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'gatepass.purchase_orders_id');
            })
            ->leftJoin('vendors', function ($join) {
                $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
            })
            ->where('gatepass.is_deleted', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vendors.vendor_name', 'like', "%{$search}%")
                    ->orWhere('gatepass.gatepass_name', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%")
                    ->orWhere('gatepass.remark', 'like', "%{$search}%");
            });
        }

        if ($request->filled('vendor_name')) {
            $query->where('vendors.id', $request->vendor_name);
        }

        if ($request->filled('purchase_orders_id')) {
            $query->where('purchase_orders.purchase_orders_id', $request->purchase_orders_id);
        }       
        if ($request->filled('from_date')) {
            $query->whereDate('gatepass.gatepass_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('gatepass.gatepass_date', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('gatepass.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('gatepass.updated_at', $request->month);
        }

        $query->select(
            'gatepass.id as id',
            'gatepass.gatepass_date as date',
            'vendors.vendor_name',
            'purchase_orders.id as purchase_id',
            'purchase_orders.purchase_orders_id',
            'gatepass.gatepass_name',
            'gatepass.remark'
        )->orderBy('gatepass.updated_at', 'asc');

        // ✅ Export full data
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null,
            ];
        }

        // ✅ Paginated result
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
}
// public function getGRNReport(Request $request)
// {
//     try {
//         $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
//             $query = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
//                 ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
//                 ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
//                 ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
//                 ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
//                 ->where('businesses_details.is_deleted', 0);
               
//         if ($request->filled('search')) {
//             $search = $request->search;
//             $query->where(function ($q) use ($search) {
//                 $q->Where('businesses_details.product_name', 'like', "%{$search}%")
//                 ->orWhere('vendors.vendor_name', 'like', "%{$search}%") 
//                  ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")      
//                    ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%");           
//             });
//         }

//         if ($request->filled('vendor_name')) {
//             $query->where('vendors.id', $request->vendor_name);
//         }
//         if ($request->filled('purchase_orders_id')) {
//             $query->where('purchase_orders.purchase_orders_id', $request->purchase_orders_id);
//         }
//         if ($request->filled('from_date')) {
//             $query->whereDate('grn_tbl.created_at', '>=', $request->from_date);
//         }

//         if ($request->filled('to_date')) {
//             $query->whereDate('grn_tbl.created_at', '<=', $request->to_date);
//         }

//         if ($request->filled('year')) {
//             $query->whereYear('grn_tbl.updated_at', $request->year);
//         }

//         if ($request->filled('month')) {
//             $query->whereMonth('grn_tbl.updated_at', $request->month);
//         }
//         $query->select(
//                     'purchase_orders.business_details_id',
//                     'purchase_orders.purchase_orders_id',
//                     'tbl_grn_po_quantity_tracking.grn_id', 
//                     'businesses_details.product_name', 
//                     'businesses_details.description',
//                     'grn_tbl.updated_at',
//                     'grn_tbl.grn_no_generate',
//                     'vendors.vendor_name',
//                     'vendors.vendor_company_name',
                    
//                     'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id' // GRN ID from tracking table
//                 )->groupBy(
//                     'purchase_orders.purchase_orders_id',
//                     'tbl_grn_po_quantity_tracking.grn_id',
//                     'purchase_orders.business_details_id',
//                     'businesses_details.product_name',
//                     'businesses_details.description',
//                    'grn_tbl.updated_at',
//                     'grn_tbl.grn_no_generate',
//                     'vendors.vendor_name',
//                     'vendors.vendor_company_name',
//                 )->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc')
//                 ->get(); 

//         // ✅ Export data (no pagination)
//         if ($request->filled('export_type')) {
//             return [
//                 'data' => $query->get(),
//                 'pagination' => null,
//             ];
//         }

//         // ✅ Paginated data
//         $perPage = $request->input('pageSize', 10);
//         $currentPage = $request->input('currentPage', 1);
//         $totalItems = (clone $query)->count();

//         $data = (clone $query)
//             ->skip(($currentPage - 1) * $perPage)
//             ->take($perPage)
//             ->get();

//         return [
//             'data' => $data,
//             'pagination' => [
//                 'currentPage' => $currentPage,
//                 'pageSize' => $perPage,
//                 'totalItems' => $totalItems,
//                 'totalPages' => ceil($totalItems / $perPage),
//                 'from' => ($currentPage - 1) * $perPage + 1,
//                 'to' => (($currentPage - 1) * $perPage) + count($data),
//             ]
//         ];
//     } catch (\Exception $e) {
//     throw $e; // Let controller handle it
// }
// }
public function getGRNReport(Request $request)
{
    try {
        $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

        $query = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
            ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
            ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
            ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
            ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
            ->where('businesses_details.is_deleted', 0);

        // 🔹 Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses_details.product_name', 'like', "%{$search}%")
                  ->orWhere('vendors.vendor_name', 'like', "%{$search}%")
                  ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")
                  ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('vendor_name')) {
            $query->where('vendors.id', $request->vendor_name);
        }

        if ($request->filled('purchase_orders_id')) {
            $query->where('purchase_orders.purchase_orders_id', $request->purchase_orders_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('grn_tbl.created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('grn_tbl.created_at', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('grn_tbl.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('grn_tbl.updated_at', $request->month);
        }

        // 🔹 Select & GroupBy
        $query->select(
            'purchase_orders.business_details_id',
            'purchase_orders.purchase_orders_id',
            'tbl_grn_po_quantity_tracking.grn_id',
            'businesses_details.product_name',
            'businesses_details.description',
            'grn_tbl.updated_at',
            'grn_tbl.grn_no_generate',
            'vendors.vendor_name',
            'vendors.vendor_company_name',
            'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id'
        )
        ->groupBy(
            'purchase_orders.purchase_orders_id',
            'tbl_grn_po_quantity_tracking.grn_id',
            'purchase_orders.business_details_id',
            'businesses_details.product_name',
            'businesses_details.description',
            'grn_tbl.updated_at',
            'grn_tbl.grn_no_generate',
            'vendors.vendor_name',
            'vendors.vendor_company_name',
        )
        ->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc');

        // 🔹 Export
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null,
            ];
        }

        // 🔹 Pagination
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = $query->count();

        $data = $query->skip(($currentPage - 1) * $perPage)
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

    } catch (\Exception $e) {
        throw $e;
    }
}

public function getConsumptionReport(Request $request)
{
    try {
    $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

        // Base query
        $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
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
            ->leftJoin('tbl_dispatch', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
            })
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.title', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%");
            });
        }
 if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }
        if ($request->filled('business_details_id')) {
            $query->where('tbl_dispatch.business_details_id', $request->business_details_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('tbl_dispatch.updated_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('tbl_dispatch.updated_at', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_dispatch.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_dispatch.updated_at', $request->month);
        }

        $data_output = $query->select(
            'businesses_details.id as business_details_id',
            'businesses.project_name',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses.created_at',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
            DB::raw('MAX(tbl_dispatch.updated_at) as updated_at') 
        )
        ->groupBy(
            'businesses_details.id',
            'businesses.project_name',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses.created_at',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity'
        )
        ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
        ->orderBy(DB::raw('MAX(tbl_dispatch.updated_at)'), 'desc') 
        ->get();

        // ✅ Export full data
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null,
            ];
        }

        // ✅ Paginated result
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
}

//   public function getConsumptionMaterialList($id) {
//         try {
//             // $id = base64_decode($id); 

            
//             $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function($join) {
//                     $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
//                 })
//                 ->leftJoin('businesses_details', function($join) {
//                     $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
//                 })
//                 ->leftJoin('production_details', function($join) {
//                     $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
//                 })
//                 ->where('businesses_details.id', $id)
//                 ->where('businesses_details.is_active', true)
//                 ->where('production_details.is_deleted', 0)
//                 ->select(
//                     'businesses_details.id',
//                     // 'gatepass.id',
//                     'production_details.id',
//                     'businesses_details.product_name',
//                     'businesses_details.quantity',
//                     'businesses_details.description',
//                     'production_details.part_item_id',
//                     'production_details.quantity',
//                     'production_details.unit',
//                     'production_details.quantity_minus_status',
//                     'production_details.material_send_production',
//                     'production_details.basic_rate',
//                     'production_details.items_used_total_amount',
//                         DB::raw('COALESCE(MAX(production_details.total_items_used_amount), 0) as total_items_used_amount'),
//                     'business_application_processes.store_material_sent_date'
//                 )
//                 ->get(); 
//             $productDetails = $dataOutputByid->first(); // Assuming the first entry contains the product details
//             $dataGroupedById = $dataOutputByid->groupBy('business_details_id');
    
//             return [
//                 'productDetails' => $productDetails,
//                 'dataGroupedById' => $dataGroupedById
//             ]; 
//             // return  $dataOutputByid;
//         } catch (\Exception $e) {
//             return [
//                 'status' => 'error',
//                 'msg' => $e->getMessage()
//             ];
//         }
//     }
public function getConsumptionMaterialList($id) {
    try {
        $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
            ->leftJoin('businesses_details', function($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('production_details', function($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
            })
            ->where('businesses_details.id', $id)
            ->where('businesses_details.is_active', true)
            ->where('production_details.is_deleted', 0)
            ->select(
                'businesses_details.id as business_details_id',
                'production_details.id as production_details_id',
                'businesses_details.product_name',
                'businesses_details.quantity as business_quantity',
                'businesses_details.description',
                'production_details.part_item_id',
                'production_details.quantity as production_quantity',
                'production_details.unit',
                'production_details.quantity_minus_status',
                'production_details.material_send_production',
                'production_details.basic_rate',
                'production_details.items_used_total_amount',
                DB::raw('COALESCE(SUM(production_details.items_used_total_amount), 0) as total_items_used_amount'),
                'business_application_processes.store_material_sent_date'
            )
            ->groupBy(
                'businesses_details.id',
                'production_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'production_details.part_item_id',
                'production_details.quantity',
                'production_details.unit',
                'production_details.quantity_minus_status',
                'production_details.material_send_production',
                'production_details.basic_rate',
                'production_details.items_used_total_amount',
                'business_application_processes.store_material_sent_date'
            )
            ->get();

        $productDetails = $dataOutputByid->first();
        $dataGroupedById = $dataOutputByid->groupBy('business_details_id');

        $totalAmount = $dataOutputByid->sum('items_used_total_amount'); // collection sum

        return [
            'productDetails' => $productDetails,
            'dataGroupedById' => $dataGroupedById,
            'total_items_used_amount' => $totalAmount
        ]; 
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'msg' => $e->getMessage()
        ];
    }
}

     public function listItemStockReport() {
        try {            
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
                })
                ->leftJoin('businesses_details', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('production_details', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
                })
                // ->where('businesses_details.id', $id)
                ->where('businesses_details.is_active', true)
                ->where('production_details.is_deleted', 0)
                ->select(
                    'businesses_details.id',
                    // 'gatepass.id',
                    'production_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'production_details.part_item_id',
                    'production_details.quantity',
                    'production_details.unit',
                    'production_details.quantity_minus_status',
                    'production_details.material_send_production',
                    'business_application_processes.store_material_sent_date'
                )
                ->get(); 
            $productDetails = $dataOutputByid->first(); // Assuming the first entry contains the product details
            $dataGroupedById = $dataOutputByid->groupBy('business_details_id');
    
            return [
                'productDetails' => $productDetails,
                'dataGroupedById' => $dataGroupedById
            ]; 
            // return  $dataOutputByid;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
        }
    }
public function listLogisticsReport(Request $request)
{
    try {
        $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
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
            ->leftJoin('production', function($join) {
                $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
            })
              ->leftJoin('tbl_vehicle_type', function($join) {
                $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
            })
              ->leftJoin('tbl_transport_name', function($join) {
                $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
            })
            ->where('tbl_customer_product_quantity_tracking.logistics_list_status', 'Send_Fianance')
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0);

        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.title', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%");
            });
        }

        // Filter by Project
        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

        // 📁 Filter by Product
        if ($request->filled('product_name')) {
            $query->where('businesses_details.id', $request->product_name);
        }

         if ($request->filled('from_date')) {
                $from = Carbon::parse($request->from_date)->startOfDay(); // 00:00:00
                $query->where('tbl_logistics.updated_at', '>=', $from);
            }

            if ($request->filled('to_date')) {
                $to = Carbon::parse($request->to_date)->endOfDay(); // 23:59:59
                $query->where('tbl_logistics.updated_at', '<=', $to);
            }

        if ($request->filled('year')) {
            $query->whereYear('tbl_logistics.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_logistics.updated_at', $request->month);
        }

        // 🔽 Select columns
        $query->select(
            'tbl_customer_product_quantity_tracking.id',
            'tbl_customer_product_quantity_tracking.business_details_id',
            'businesses.title',
            'businesses.project_name',
            'businesses.customer_po_number',
            'businesses.created_at',
            'businesses_details.product_name',
            'businesses_details.quantity',
            'tbl_customer_product_quantity_tracking.completed_quantity',
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
            'production.business_id',
            'production.id as productionId',
            'bap1.store_material_sent_date',
            'tbl_customer_product_quantity_tracking.updated_at',
            'tbl_logistics.truck_no',
            'tbl_logistics.from_place',
            'tbl_logistics.to_place',
             'tbl_logistics.vehicle_type_id',
               'tbl_logistics.transport_name_id',
                'tbl_transport_name.name as transport_name',
                 'tbl_vehicle_type.name as vehicle_name',
               
        );

        $query->orderBy('tbl_logistics.updated_at', 'desc');

        // 📤 Export full data (PDF/Excel)
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination setup
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        throw $e; // ✅ Let the controller catch and respond
    }
}
public function listFinanceReport(Request $request){
    try {
       $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
      $array_to_be_quantity_tracking = [ config('constants.FINANCE_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

      $array_to_be_check_new = ['0'];
       $query = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function($join) {
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
  ->leftJoin('production', function($join) {
    $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
    })
      ->where('businesses.is_active',true)
      ->where('businesses.is_deleted', 0)
      ->where('tbl_customer_product_quantity_tracking.fianace_list_status','Send_Dispatch');
        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.title', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%");
            });
        }

        // 📁 Filter by Project
        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

        // 📁 Filter by Product
        if ($request->filled('product_name')) {
            $query->where('businesses_details.id', $request->product_name);
        }

        // 🗓️ Date filters
        if ($request->filled('from_date')) {
            $query->whereDate('tbl_logistics.gatepass_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('tbl_logistics.gatepass_date', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_logistics.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_logistics.updated_at', $request->month);
        }

        // 🔽 Select columns
        $query->select(
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
     

        // 📤 Export full data (PDF/Excel)
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination setup
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        throw $e; // ✅ Let the controller catch and respond
    }
}
public function listVendorPaymentReport(Request $request){
    try {
    //    $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
    //   $array_to_be_quantity_tracking = [ config('constants.FINANCE_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

    //   $array_to_be_check_new = ['0'];
       $query = PurchaseOrdersModel::leftJoin('grn_tbl', function($join) {
      $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
        })
        ->leftJoin('vendors', function($join) {
            $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
        })
            ->where('purchase_orders.is_active',true)
            ->where('purchase_orders.is_deleted', 0);
    
        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vendors.vendor_name', 'like', "%{$search}%")
                    ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")
                    ->orWhere('vendors.contact_no', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%");
            });
        }

        // 📁 Filter by Project
        if ($request->filled('vendor_name')) {
            $query->where('vendors.id', $request->vendor_name);
        }

        // 📁 Filter by Product
        if ($request->filled('purchase_orders_id')) {
            $query->where('purchase_orders.purchase_orders_id', $request->purchase_orders_id);
        }

        // 🗓️ Date filters
        if ($request->filled('from_date')) {
            $query->whereDate('grn_tbl.updated_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('grn_tbl.updated_at', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('grn_tbl.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('grn_tbl.updated_at', $request->month);
        }

         if ($request->filled('grn_status_sanction')) {
    $statusIds = explode(',', $request->grn_status_sanction);
    $query->whereIn('grn_tbl.grn_status_sanction', $statusIds);
}


        // 🔽 Select columns
        $query->select(
      'purchase_orders.id',
      'purchase_orders.purchase_orders_id',
      'vendors.vendor_name',
       'vendors.vendor_company_name',
      'vendors.vendor_email',
      'vendors.contact_no',   
      'purchase_orders.invoice_date',
      'grn_tbl.grn_status_sanction',
     'grn_tbl.grn_no_generate',
      'grn_tbl.updated_at'
      
  ) 
  ->orderBy('grn_tbl.updated_at', 'desc')
      ->get();
     

        // 📤 Export full data (PDF/Excel)
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination setup
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        throw $e; // ✅ Let the controller catch and respond
    }
}
public function listDispatchReport(Request $request )
{
    try {
      $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

        $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
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
            ->leftJoin('tbl_dispatch', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
            })
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
            ->where('bap1.off_canvas_status', 22)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0);
        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.title', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%");
            });
        }

        // 📁 Filter by Project
        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

         // 📁 Filter by Product
            if ($request->filled('product_name')) {
                $query->where('businesses_details.id', $request->product_name);
            }
            if ($request->filled('from_date')) {
                $from = Carbon::parse($request->from_date)->startOfDay(); // 00:00:00
                $query->where('tbl_dispatch.updated_at', '>=', $from);
            }

            if ($request->filled('to_date')) {
                $to = Carbon::parse($request->to_date)->endOfDay(); // 23:59:59
                $query->where('tbl_dispatch.updated_at', '<=', $to);
            }

        if ($request->filled('year')) {
            $query->whereYear('tbl_dispatch.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_dispatch.updated_at', $request->month);
        }

        // 🔽 Select columns
        $query ->select(
                'businesses_details.id as business_details_id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
                DB::raw('MAX(tbl_dispatch.updated_at) as last_updated_at') // Alias for MAX(updated_at)
            )

            ->groupBy(
                'businesses_details.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.created_at',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity'
            )

            ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
            ->orderBy('last_updated_at', 'desc') // Use the alias instead of tbl_dispatch.last_updated_at
            ->get()
            ->map(function ($data) {
                $data->last_updated_at = Carbon::parse($data->last_updated_at);
                return $data;
            });
     

        // 📤 Export full data (PDF/Excel)
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination setup
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        throw $e; // ✅ Let the controller catch and respond
    }
}


public function listPendingDispatchReport(Request $request)
{
    try {
        $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_RECEIVED_FROM_FINANCE_ACCORDING_TO_LOGISTICS')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.RECEIVED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

        $query = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function ($join) {
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
            ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0);

        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.title', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%");
            });
        }

        // 📁 Filter by Project
        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

        // 📁 Filter by Product
        if ($request->filled('product_name')) {
            $query->where('businesses_details.id', $request->product_name);
        }

        // 🗓️ Date filters
        if ($request->filled('from_date')) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $query->where('bap1.updated_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $to = Carbon::parse($request->to_date)->endOfDay();
            $query->where('bap1.updated_at', '<=', $to);
        }

        if ($request->filled('year')) {
            $query->whereYear('bap1.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('bap1.updated_at', $request->month);
        }

        // 🔽 Select columns
        $query->select(
            'tbl_customer_product_quantity_tracking.id',
            'tbl_customer_product_quantity_tracking.business_details_id',
            'businesses.title',
            'businesses.project_name',
            'businesses.created_at',
            'businesses.customer_po_number',
            'businesses_details.product_name',
            'businesses_details.quantity',
            'businesses.remarks',
            'businesses.is_active',
            'tbl_customer_product_quantity_tracking.completed_quantity',
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
            'production.business_id',
            'production.id as productionId',
            'bap1.store_material_sent_date',
            'tbl_customer_product_quantity_tracking.updated_at',
            'tbl_transport_name.name as transport_name',
            'tbl_vehicle_type.name as vehicle_name',
            'tbl_logistics.truck_no',
            'tbl_logistics.from_place',
            'tbl_logistics.to_place'
        )
        ->orderBy('bap1.updated_at', 'desc');

        // 📤 Export full data
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination setup
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);
        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
    } catch (\Exception $e) {
        throw $e;
    }
}



public function listDispatchBarChartProductWise(Request $request)
{
    try {
        $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

        $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', 'tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id')
            ->leftJoin('business_application_processes as bap1', 'tbl_logistics.business_application_processes_id', '=', 'bap1.id')
            ->leftJoin('businesses', 'tbl_logistics.business_id', '=', 'businesses.id') // ✅ needed for project filter
            ->leftJoin('businesses_details', 'tbl_logistics.business_details_id', '=', 'businesses_details.id')
            ->leftJoin('tbl_dispatch', 'tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id')
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check);

        // 📁 Filter by Project
        if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }

        // 📁 Filter by Product
        if ($request->filled('product_name')) {
            $query->where('businesses_details.id', $request->product_name);
        }

        $data = $query->select(
                'businesses_details.id as business_details_id',
                'businesses_details.product_name',
                'businesses_details.quantity',
                DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
                DB::raw('MAX(tbl_dispatch.updated_at) as last_updated_at')
            )
            ->groupBy(
                'businesses_details.id',
                'businesses_details.product_name',
                'businesses_details.quantity'
            )
            ->orderBy('last_updated_at', 'desc')
            ->get()
            ->map(function ($row) {
                return [
                    'business_details_id' => $row->business_details_id,
                    'product_name' => $row->product_name,
                    'quantity' => (int) $row->quantity,
                    'total_completed_quantity' => (int) $row->total_completed_quantity,
                    'last_updated_at' => $row->last_updated_at ? Carbon::parse($row->last_updated_at)->format('Y-m-d H:i:s') : null
                ];
            });

        return ['data' => $data];

    } catch (\Exception $e) {
        throw $e;
    }
}

public function listDispatchBarChart(Request $request)
{
    try {
        $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
        $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

        $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
            })
            ->leftJoin('businesses', 'tbl_logistics.business_id', '=', 'businesses.id')
            ->leftJoin('business_application_processes as bap1', 'tbl_logistics.business_application_processes_id', '=', 'bap1.id')
            ->leftJoin('businesses_details', 'tbl_logistics.business_details_id', '=', 'businesses_details.id')
            ->leftJoin('tbl_dispatch', 'tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id')
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->whereNotNull('tbl_dispatch.updated_at');

        $data = $query
            ->select(
                DB::raw("DATE_FORMAT(tbl_dispatch.updated_at, '%Y-%m') as month"),
                DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
                DB::raw('SUM(businesses_details.quantity) as total_quantity')
            )
            ->groupBy(DB::raw("DATE_FORMAT(tbl_dispatch.updated_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(tbl_dispatch.updated_at, '%Y-%m')"), 'asc')
            ->get()
            ->map(function ($row) {
                $row->month_label = \Carbon\Carbon::parse($row->month . '-01')->format('M Y');
                $row->pending_quantity = max(0, $row->total_quantity - $row->total_completed_quantity);
                return $row;
            });

        return ['data' => $data];

    } catch (\Exception $e) {
        throw $e;
    }
}

// public function listVendorWise(Request $request)
// {
//     try {
//         $data = \DB::table('purchase_orders')
//             ->leftJoin('vendors', function ($join) {
//                 $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
//             })
//             ->leftJoin('purchase_order_details', function ($join) {
//                 $join->on('purchase_order_details.purchase_id', '=', 'purchase_orders.id');
//             })
//             ->leftJoin('tbl_part_item', function ($join) {
//                 $join->on('purchase_order_details.part_no_id', '=', 'tbl_part_item.id');
//             })
//             ->select(
//                 'purchase_orders.id as purchase_order_id',
//                 'vendors.vendor_name',
//                 'purchase_order_details.quantity',
//                 'tbl_part_item.description',
//                 'tbl_part_item.part_number'
//             )
//             ->get();

//         return ['data' => $data];
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }
public function listVendorWise(Request $request)
{
    try {
        $query = \DB::table('purchase_orders')
            ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
            ->leftJoin('purchase_order_details', 'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
            ->select(
                'vendors.vendor_name',
                \DB::raw('SUM(purchase_order_details.quantity) as total_quantity')
            )
            // ->whereNull('purchase_orders.is_deleted')
            // ->whereNull('vendors.is_deleted')
            ->groupBy('vendors.vendor_name');

        // Optional filter for month
        if ($request->has('month')) {
            $query->whereMonth('purchase_orders.created_at', $request->month);
        }

        $data = $query->get();

        // Calculate percentage
        $totalQuantity = $data->sum('total_quantity');
        foreach ($data as $row) {
            $row->percentage = $totalQuantity > 0 ? round(($row->total_quantity / $totalQuantity) * 100, 2) : 0;
        }

        return ['data' => $data];
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function listVendorThroughTakenMaterial($request)
{
    $statuses = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

    $query = PurchaseOrdersModel::leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
        ->whereIn('purchase_orders.purchase_status_from_owner', $statuses)
        ->whereIn('purchase_orders.purchase_status_from_purchase', $statuses);

  

    // Search filter
    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('vendors.vendor_name', 'like', "%{$s}%")
                ->orWhere('vendors.vendor_company_name', 'like', "%{$s}%")
                ->orWhere('vendors.vendor_email', 'like', "%{$s}%")
                ->orWhere('vendors.contact_no', 'like', "%{$s}%");
        });
    }
  // Filter by vendor
    if ($request->filled('vendor_id')) {
        $query->where('vendors.id', $request->vendor_id);
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

    // Group by vendor only
    $queryForData = (clone $query)
        ->select(
            'vendors.id as vendor_id',
            'vendors.vendor_name',
            'vendors.vendor_company_name',
            'vendors.vendor_email',
            'vendors.contact_no',
            DB::raw('MAX(purchase_orders.updated_at) as latest_update'),
            DB::raw('COUNT(purchase_orders.id) as total_pos')
        )
        ->groupBy(
            'vendors.id',
            'vendors.vendor_name',
            'vendors.vendor_company_name',
            'vendors.vendor_email',
            'vendors.contact_no'
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

public function listVendorThroughTakenMaterialVendorId(Request $request, $id)
{
    try {
        $query = GrnPOQuantityTracking::leftJoin('tbl_part_item', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id')
            ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
            ->leftJoin('tbl_unit', 'tbl_grn_po_quantity_tracking.unit', '=', 'tbl_unit.id')
            ->leftJoin('purchase_orders', 'tbl_grn_po_quantity_tracking.purchase_order_id', '=', 'purchase_orders.id')
            ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
            ->where('vendors.id', $id);

        // 🔍 Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('tbl_part_item.description', 'like', "%{$s}%")
                    ->orWhere('tbl_part_item.part_number', 'like', "%{$s}%")
                    ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$s}%");
            });
        }

        // 📅 Filters
        if ($request->filled('from_date')) {
            $query->whereDate('tbl_grn_po_quantity_tracking.created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('tbl_grn_po_quantity_tracking.created_at', '<=', $request->to_date);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_grn_po_quantity_tracking.updated_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_grn_po_quantity_tracking.updated_at', $request->year);
        }

        // 📊 Select & Group
        $query->select(
            'tbl_grn_po_quantity_tracking.purchase_order_id',
            'tbl_grn_po_quantity_tracking.part_no_id',
            'tbl_grn_po_quantity_tracking.purchase_order_details_id',
            DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
            DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
            DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
            DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),

            DB::raw('(SELECT SUM(t2.actual_quantity) 
                      FROM tbl_grn_po_quantity_tracking AS t2 
                      WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
                      AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
                      AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
                      AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at) AS sum_grn_actual_quantity'),

            DB::raw('(
                purchase_order_details.quantity - 
                (SELECT SUM(t2.actual_quantity) 
                 FROM tbl_grn_po_quantity_tracking AS t2 
                 WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
                 AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
                 AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
                 AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at)
            ) AS remaining_quantity'),

            'tbl_part_item.description as part_description',
            'tbl_part_item.part_number',
            'tbl_unit.name as unit_name',
            DB::raw('MAX(purchase_order_details.description) as po_description'),
            DB::raw('MAX(purchase_order_details.rate) as po_rate'),
            DB::raw('MAX(purchase_order_details.discount) as po_discount'),
            'tbl_grn_po_quantity_tracking.created_at',
            'tbl_grn_po_quantity_tracking.updated_at'
        )
        ->groupBy(
            'tbl_grn_po_quantity_tracking.purchase_order_id',
            'tbl_grn_po_quantity_tracking.part_no_id',
            'tbl_grn_po_quantity_tracking.purchase_order_details_id',
            'tbl_part_item.id',
            'tbl_part_item.description',
            'tbl_part_item.part_number',
            'tbl_unit.name',
            'purchase_order_details.quantity',
            'tbl_grn_po_quantity_tracking.created_at',
            'tbl_grn_po_quantity_tracking.updated_at'
        )
        ->orderByDesc('tbl_grn_po_quantity_tracking.updated_at');

        // 📤 Export logic
        if ($request->filled('export_type')) {
            return [
                'data' => $query->get(),
                'pagination' => null,
            ];
        }

        // 👇 No Pagination
        return [
            'data' => $query->get()
        ];

    } catch (\Exception $e) {
        throw $e;
    }
}
public function getStockItem($request)
{
    try {
        $query = ItemStock::leftJoin('tbl_part_item', function ($join) {
                $join->on('tbl_item_stock.part_item_id', '=', 'tbl_part_item.id');
            })
            ->leftJoin('tbl_unit', function ($join) {
                $join->on('tbl_part_item.unit_id', '=', 'tbl_unit.id');
            })
            ->leftJoin('tbl_hsn', function ($join) {
                $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
            })
            ->leftJoin('tbl_group_master', function ($join) {
                $join->on('tbl_part_item.group_type_id', '=', 'tbl_group_master.id');
            })
            ->leftJoin('tbl_rack_master', function ($join) {
                $join->on('tbl_part_item.rack_id', '=', 'tbl_rack_master.id');
            });

           // Search filter
            if ($request->filled('search')) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('tbl_part_item.description', 'like', "%{$s}%")
                        ->orWhere('tbl_item_stock.quantity', 'like', "%{$s}%")
                        ->orWhere('tbl_hsn.name', 'like', "%{$s}%");
                });
            }
        //  Date filters — make sure to use correct table columns, not tbl_dispatch
        if ($request->filled('from_date')) {
            $query->whereDate('tbl_item_stock.updated_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('tbl_item_stock.updated_at', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_item_stock.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_item_stock.updated_at', $request->month);
        }
        $query->orderBy('tbl_item_stock.quantity', 'desc');
        $query->select(
            'tbl_item_stock.id',
            'tbl_part_item.part_number',
            'tbl_part_item.basic_rate',
            'tbl_part_item.opening_stock',
            'tbl_part_item.description',
            'tbl_part_item.extra_description',
            'tbl_part_item.unit_id',
            'tbl_item_stock.quantity',
            'tbl_unit.name as unit_name',
            'tbl_part_item.hsn_id',
            'tbl_hsn.name as hsn_name',
            'tbl_part_item.group_type_id',
            'tbl_part_item.rack_id',
            'tbl_rack_master.name as rack_name',
            'tbl_group_master.name as group_name',
            'tbl_item_stock.updated_at'
        );

        // 📤 Export full data
        if ($request->filled('export_type')) {
            return [
                'status' => true,
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination setup
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);

        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
                'to' => min($currentPage * $perPage, $totalItems),
            ]
        ];
    } catch (\Exception $e) {
        return [
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}
public function getStoreItemStockList($request)
{
    try {
        $query = ItemStock::leftJoin('tbl_part_item', function ($join) {
                $join->on('tbl_item_stock.part_item_id', '=', 'tbl_part_item.id');
            })
            ->leftJoin('tbl_unit', function ($join) {
                $join->on('tbl_part_item.unit_id', '=', 'tbl_unit.id');
            })
            ->leftJoin('tbl_hsn', function ($join) {
                $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
            })
            ->leftJoin('tbl_group_master', function ($join) {
                $join->on('tbl_part_item.group_type_id', '=', 'tbl_group_master.id');
            })
            ->leftJoin('tbl_rack_master', function ($join) {
                $join->on('tbl_part_item.rack_id', '=', 'tbl_rack_master.id');
            });

        // 🗓️ Date filters — make sure to use correct table columns, not tbl_dispatch
        if ($request->filled('from_date')) {
            $query->whereDate('tbl_item_stock.updated_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('tbl_item_stock.updated_at', '<=', $request->to_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_item_stock.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_item_stock.updated_at', $request->month);
        }
        $query->orderBy('tbl_item_stock.quantity', 'desc');
        $query->select(
            'tbl_item_stock.id',
            'tbl_part_item.part_number',
            'tbl_part_item.basic_rate',
            'tbl_part_item.opening_stock',
            'tbl_part_item.description',
            'tbl_part_item.extra_description',
            'tbl_part_item.unit_id',
            'tbl_item_stock.quantity',
            'tbl_unit.name as unit_name',
            'tbl_part_item.hsn_id',
            'tbl_hsn.name as hsn_name',
            'tbl_part_item.group_type_id',
            'tbl_part_item.rack_id',
            'tbl_rack_master.name as rack_name',
            'tbl_group_master.name as group_name',
            'tbl_item_stock.updated_at'
        );

        // 📤 Export full data
        if ($request->filled('export_type')) {
            return [
                'status' => true,
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination setup
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);

        $totalItems = (clone $query)->count();

        $data = (clone $query)
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
                'to' => min($currentPage * $perPage, $totalItems),
            ]
        ];
    } catch (\Exception $e) {
        return [
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}
// public function listStockDailyReport($request)
// {
//     try {
//        $query = PartItem::leftJoin('production_details', 'tbl_part_item.id', '=', 'production_details.part_item_id');
//     // ->leftJoin('production_details', 'tbl_part_item.id', '=', 'production_details.part_item_id')
//     // ->leftJoin('tbl_grn_po_quantity_tracking', 'tbl_part_item.id', '=', 'tbl_grn_po_quantity_tracking.part_no_id');

//         // 🗓️ Date filters — make sure to use correct table columns, not tbl_dispatch
//         if ($request->filled('from_date')) {
//             $query->whereDate('tbl_item_stock.updated_at', '>=', $request->from_date);
//         }

//         if ($request->filled('to_date')) {
//             $query->whereDate('tbl_item_stock.updated_at', '<=', $request->to_date);
//         }

//         if ($request->filled('year')) {
//             $query->whereYear('tbl_item_stock.updated_at', $request->year);
//         }

//         if ($request->filled('month')) {
//             $query->whereMonth('tbl_item_stock.updated_at', $request->month);
//         }
//         // $query->orderBy('tbl_item_stock.quantity', 'desc');
//         // $query->select(
//         //      'tbl_part_item.id',
//         //      'tbl_part_item.description',
//         //      'tbl_grn_po_quantity_tracking.part_no_id',
//         //      'tbl_grn_po_quantity_tracking.quantity',
//         //       'tbl_item_stock.quantity as balance_quantity',
//         //      'tbl_item_stock.part_item_id',
//         //        'production_details.quantity as used_quantity',
//         //     // 'tbl_item_stock.updated_at'
//         // );

//         $query->select(
//     'tbl_part_item.id',
//     'tbl_part_item.description',
//     DB::raw('COALESCE(SUM(production_details.quantity), 0) as issue_quantity')
//         )
// ->groupBy(
//     'tbl_part_item.id',
//     'tbl_part_item.description'
// );

//     // ->orderBy('tbl_item_stock.quantity', 'desc');
//         // 📤 Export full data
//         if ($request->filled('export_type')) {
//             return [
//                 'status' => true,
//                 'data' => $query->get(),
//                 'pagination' => null
//             ];
//         }

//         // 📄 Pagination setup
//         $perPage = $request->input('pageSize', 10);
//         $currentPage = $request->input('currentPage', 1);

//         $totalItems = (clone $query)->count();

//         $data = (clone $query)
//             ->skip(($currentPage - 1) * $perPage)
//             ->take($perPage)
//             ->get();

//         return [
//             'status' => true,
//             'data' => $data,
//             'pagination' => [
//                 'currentPage' => $currentPage,
//                 'pageSize' => $perPage,
//                 'totalItems' => $totalItems,
//                 'totalPages' => ceil($totalItems / $perPage),
//                 'from' => ($currentPage - 1) * $perPage + 1,
//                 'to' => min($currentPage * $perPage, $totalItems),
//             ]
//         ];
//     } catch (\Exception $e) {
//         return [
//             'status' => false,
//             'message' => $e->getMessage()
//         ];
//     }
// }

public function listStockDailyReport($request)
{
    try {
        $query = PartItem::leftJoin('tbl_item_stock', 'tbl_part_item.id', '=', 'tbl_item_stock.part_item_id')
            ->leftJoin('production_details', 'tbl_part_item.id', '=', 'production_details.part_item_id')
              ->leftJoin('tbl_grn_po_quantity_tracking', 'tbl_part_item.id', '=', 'tbl_grn_po_quantity_tracking.part_no_id');

               // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tbl_part_item.description', 'like', "%{$search}%")
                    ->orWhere('production_details.quantity', 'like', "%{$search}%")
                    ->orWhere('tbl_item_stock.quantity', 'like', "%{$search}%")
                    ->orWhere('tbl_grn_po_quantity_tracking.quantity', 'like', "%{$search}%");
            });
        }


        if ($request->filled('description')) {
            $query->where('tbl_part_item.id', $request->description);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('tbl_item_stock.updated_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('tbl_item_stock.updated_at', '<=', $request->to_date);
        }
        if ($request->filled('year')) {
            $query->whereYear('tbl_item_stock.updated_at', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('tbl_item_stock.updated_at', $request->month);
        }

        $query->select(
    'tbl_part_item.id',
    'tbl_part_item.description',
      'production_details.updated_at as issue_updated_at',
    'production_details.quantity as issue_quantity',
    'tbl_item_stock.quantity as balance_quantity',
    'tbl_grn_po_quantity_tracking.quantity as received_quantity',
    'tbl_grn_po_quantity_tracking.updated_at as received_updated_at'
);

   // 📄 Calculate totals for filtered data
        $totalReceived = (clone $query)->sum('tbl_grn_po_quantity_tracking.quantity');
        $totalIssue = (clone $query)->sum('production_details.quantity');
        $totalBalance = (clone $query)->sum('tbl_item_stock.quantity');

        if ($request->filled('export_type')) {
            return [
                'status' => true,
                'data' => $query->get(),
                'pagination' => null
            ];
        }

        // 📄 Pagination
        $perPage = $request->input('pageSize', 10);
        $currentPage = $request->input('currentPage', 1);

        $totalItems = (clone $query)->get()->count();

        $data = (clone $query)
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
                'to' => min($currentPage * $perPage, $totalItems),
            ],
            'totals' => [
                'received' => $totalReceived,
                'issue' => $totalIssue,
                'balance' => $totalBalance,
            ]

           
        ];
      
    } catch (\Exception $e) {
        return [
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}

}