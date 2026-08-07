<?php

namespace App\Http\Repository\Organizations\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\{
    BusinessApplicationProcesses,
    PurchaseOrdersModel,
    Logistics,
    CustomerProductQuantityTracking,
    GrnPOQuantityTracking,
    ItemStock,
    RejectedChalan,
    LeaveManagement,
    Leaves
};

class ReportRepository
{
    public function getCompletedProductList(Request $request)
    {
        try {
            $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.FINAL_PRODUCT_DISPATCH')];
            // $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
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
                ->leftJoin(
                    DB::raw('(SELECT business_details_id, SUM(items_used_total_amount) as total_items_used_amount 
                     FROM production_details 
                     GROUP BY business_details_id) as pd'),
                    'tbl_dispatch.business_details_id',
                    '=',
                    'pd.business_details_id'
                )
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

            if ($request->filled('production_status_id')) {
                $statusIds = explode(',', $request->production_status_id);
                $query->whereIn('bap1.production_status_id', $statusIds);
            }

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

    public function getEstimationReport(Request $request)
    {
        try {
            $array_to_be_check = config('constants.ESTIMATION_DEPARTMENT.UPDATED_ACCEPTED_BOM_SEND_TO_PRODUCTION');

            $data_output = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('estimation', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->where('business_application_processes.estimation_send_to_production', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0);

            // 🔍 Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $data_output->where(function ($q) use ($search) {
                    $q->where('businesses.project_name', 'like', "%{$search}%")
                        ->orWhere('businesses.customer_po_number', 'like', "%{$search}%")
                        ->orWhere('businesses_details.product_name', 'like', "%{$search}%");
                });
            }

            // 🔍 Project name filter
            if ($request->filled('project_name')) {
                $data_output->where('businesses.id', $request->project_name);
            }

            if ($request->filled('business_details_id')) {
                $data_output->where('business_application_processes.business_details_id', $request->business_details_id);
            }

            // 🔍 Date filters
            if ($request->filled('from_date')) {
                $from = Carbon::parse($request->from_date)->startOfDay();
                $data_output->where('production.updated_at', '>=', $from);
            }

            if ($request->filled('to_date')) {
                $to = Carbon::parse($request->to_date)->endOfDay();
                $data_output->where('production.updated_at', '<=', $to);
            }

            if ($request->filled('year')) {
                $data_output->whereYear('production.updated_at', $request->year);
            }

            if ($request->filled('month')) {
                $data_output->whereMonth('production.updated_at', $request->month);
            }

            // 🔍 Production status filter
            if ($request->filled('production_status_id')) {
                $statusIds = explode(',', $request->production_status_id);
                $data_output->whereIn('business_application_processes.production_status_id', $statusIds);
            }

            // 🎯 Final Select
            $data_output->select(
                'businesses.id',
                'businesses.project_name',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses.remarks',
                'estimation.updated_at',

                DB::raw('MAX(businesses_details.product_name) as product_name'),
                DB::raw('MAX(businesses_details.quantity) as quantity'),
                DB::raw('MAX(businesses_details.description) as description'),

                DB::raw('MAX(design_revision_for_prod.bom_image) as bom_image'),
                DB::raw('MAX(designs.design_image) as design_image'),

                'estimation.total_estimation_amount'
            )
                ->groupBy(
                    'businesses.id',
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses.remarks',
                    'estimation.updated_at',
                    'estimation.total_estimation_amount'
                )
                ->orderBy('estimation.updated_at', 'desc');

            // ⬇ EXPORT (no pagination)
            if ($request->filled('export_type')) {
                return [
                    'data' => $data_output->get(),
                    'pagination' => null,
                ];
            }

            // ⬇ PAGINATION
            $perPage = $request->input('pageSize', 10);
            $currentPage = $request->input('currentPage', 1);

            $totalItems = (clone $data_output)->count();

            $data = (clone $data_output)
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
                // ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking) //3001
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->whereRaw('tbl_customer_product_quantity_tracking.id = (
        SELECT MAX(id) 
        FROM tbl_customer_product_quantity_tracking t2 
        WHERE t2.business_details_id = businesses_details.id
    )');
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

            if ($request->filled('production_status_id')) {

                if ($request->production_status_id == 'Completed') {
                    $query->whereRaw('
                    (businesses_details.quantity - (
                        SELECT SUM(t2.completed_quantity)
                        FROM tbl_customer_product_quantity_tracking AS t2
                        WHERE t2.business_details_id = businesses_details.id
                        AND t2.id <= tbl_customer_product_quantity_tracking.id
                    )) <= 0
                ');
                }

                if ($request->production_status_id == 'Inprocess') {
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


            if ($request->filled('from_date')) {
                $query->whereDate('tbl_logistics.updated_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('tbl_logistics.updated_at', '<=', $request->to_date);
            }

            if ($request->filled('year')) {
                $query->whereYear('tbl_logistics.updated_at', $request->year);
            }

            if ($request->filled('month')) {
                $query->whereMonth('tbl_logistics.updated_at', $request->month);
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
                'tbl_logistics.updated_at',
                'tbl_dispatch.remark as dispatch_remark',
                'tbl_dispatch.updated_at as dispatch_updated_at',
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
    // public function getGRNReport(Request $request)
    // {
    //     try {
    //         $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

    //         $query = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
    //             ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
    //             ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
    //             ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
    //             ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
    //             // ->leftJoin('gatepass ', 'grn_tbl.gatepass_id', '=', 'gatepass.id')
    //             ->where('businesses_details.is_deleted', 0);

    //         // 🔹 Filters
    //         if ($request->filled('search')) {
    //             $search = $request->search;
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('businesses_details.product_name', 'like', "%{$search}%")
    //                     ->orWhere('vendors.vendor_name', 'like', "%{$search}%")
    //                     ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")
    //                     ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%");
    //             });
    //         }

    //         if ($request->filled('vendor_name')) {
    //             $query->where('vendors.id', $request->vendor_name);
    //         }

    //         if ($request->filled('purchase_orders_id')) {
    //             $query->where('purchase_orders.purchase_orders_id', $request->purchase_orders_id);
    //         }

    //         if ($request->filled('from_date')) {
    //             $query->whereDate('grn_tbl.updated_at', '>=', $request->from_date);
    //         }

    //         if ($request->filled('to_date')) {
    //             $query->whereDate('grn_tbl.updated_at', '<=', $request->to_date);
    //         }


    //         if ($request->filled('year')) {
    //             $query->whereYear('grn_tbl.updated_at', $request->year);
    //         }

    //         if ($request->filled('month')) {
    //             $query->whereMonth('grn_tbl.updated_at', $request->month);
    //         }

    //         // 🔹 Select & GroupBy
    //         $query->select(
    //             'purchase_orders.business_details_id',
    //             'purchase_orders.purchase_orders_id',
    //             'tbl_grn_po_quantity_tracking.grn_id',
    //             'businesses_details.product_name',
    //             'businesses_details.description',
    //             'grn_tbl.updated_at',
    //             'grn_tbl.grn_no_generate',
    //             'vendors.vendor_name',
    //             'vendors.vendor_company_name',
    //             'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id'
    //         )
    //             ->groupBy(
    //                 'purchase_orders.purchase_orders_id',
    //                 'tbl_grn_po_quantity_tracking.grn_id',
    //                 'purchase_orders.business_details_id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.description',
    //                 'grn_tbl.updated_at',
    //                 'grn_tbl.grn_no_generate',
    //                 'vendors.vendor_name',
    //                 'vendors.vendor_company_name',
    //             )
    //             ->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc');

    //         // 🔹 Export
    //         if ($request->filled('export_type')) {
    //             return [
    //                 'data' => $query->get(),
    //                 'pagination' => null,
    //             ];
    //         }

    //         // 🔹 Pagination
    //         $perPage = $request->input('pageSize', 10);
    //         $currentPage = $request->input('currentPage', 1);
    //         $totalItems = $query->count();
    //         $data = $query->skip(($currentPage - 1) * $perPage)
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
    //         throw $e;
    //     }
    // }
    public function getGRNReport(Request $request)
    {
        try {

            // -----------------------------------------------
            //  BASE QUERY
            // -----------------------------------------------
            $query = PurchaseOrdersModel::leftJoin(
                'grn_tbl',
                'purchase_orders.purchase_orders_id',
                '=',
                'grn_tbl.purchase_orders_id'
            )
                ->leftJoin(
                    'businesses_details',
                    'purchase_orders.business_details_id',
                    '=',
                    'businesses_details.id'
                )
                ->leftJoin(
                    'tbl_grn_po_quantity_tracking',
                    'grn_tbl.id',
                    '=',
                    'tbl_grn_po_quantity_tracking.grn_id'
                )
                ->leftJoin(
                    'vendors',
                    'purchase_orders.vendor_id',
                    '=',
                    'vendors.id'
                )
                ->whereNotNull('grn_tbl.grn_no_generate')
                ->where('businesses_details.is_deleted', 0);


            // -----------------------------------------------
            //  SEARCH FILTER
            // -----------------------------------------------
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('businesses_details.product_name', 'like', "%{$search}%")
                        ->orWhere('vendors.vendor_name', 'like', "%{$search}%")
                        ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")
                        ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%");
                });
            }


            // -----------------------------------------------
            //  VENDOR FILTER
            // -----------------------------------------------
            if ($request->filled('vendor_name')) {
                $query->where('vendors.id', $request->vendor_name);
            }


            // -----------------------------------------------
            //  PO NUMBER FILTER
            // -----------------------------------------------
            if ($request->filled('purchase_orders_id')) {
                $query->where('purchase_orders.purchase_orders_id', $request->purchase_orders_id);
            }


            // -----------------------------------------------
            //  DATE FILTERS
            // -----------------------------------------------
            if ($request->filled('from_date')) {
                $query->whereDate('grn_tbl.updated_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('grn_tbl.updated_at', '<=', $request->to_date);
            }


            // -----------------------------------------------
            //  YEAR & MONTH FILTER
            // -----------------------------------------------
            if ($request->filled('year')) {
                $query->whereYear('grn_tbl.updated_at', $request->year);
            }

            if ($request->filled('month')) {
                $query->whereMonth('grn_tbl.updated_at', $request->month);
            }


            // -----------------------------------------------
            //  SELECT (DISTINCT → remove duplicates)
            // -----------------------------------------------
            $query->select(
                'purchase_orders.purchase_orders_id',
                'businesses_details.product_name',
                'businesses_details.description',
                'vendors.vendor_name',
                'vendors.vendor_company_name',
                'grn_tbl.updated_at',
                'grn_tbl.grn_no_generate',
                'businesses_details.id as business_details_id',
                'grn_tbl.id as grn_id',
                'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id'
            )->distinct();



            // -----------------------------------------------
            //  EXPORT REQUEST
            // -----------------------------------------------
            if ($request->filled('export_type')) {

                return [
                    'data' => $query->orderBy('grn_tbl.id', 'desc')->get(),
                    'pagination' => null,
                ];
            }


            // -----------------------------------------------
            //  PAGINATION - FIXED
            //  (Count DISTINCT only!)
            // -----------------------------------------------
            $perPage = $request->input('pageSize', 10);
            $currentPage = $request->input('currentPage', 1);

            // FIX: count only unique GRN rows
            $totalItems = $query->distinct()->count('grn_tbl.id');


            $data = $query->orderBy('grn_tbl.id', 'desc')
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
    public function getRejectedGRNReport(Request $request)
    {
        try {

            // -----------------------------------------------
            //  BASE QUERY
            // -----------------------------------------------
            $query = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
                ->leftJoin('gatepass', 'grn_tbl.gatepass_id', '=', 'gatepass.id')

                // 🔥 FIXED JOIN → Use purchase_orders.purchase_orders_id instead of id
                ->leftJoin(
                    'purchase_orders',
                    'tbl_rejected_chalan.purchase_orders_id',
                    '=',
                    'purchase_orders.purchase_orders_id'
                )

                ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
                ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
                ->leftJoin('tbl_grn_po_quantity_tracking', 'grn_tbl.id', '=', 'tbl_grn_po_quantity_tracking.grn_id')
                ->where('tbl_rejected_chalan.is_deleted', 0)
                ->where('tbl_rejected_chalan.chalan_no', '<>', '');

            // -----------------------------------------------
            // SEARCH FILTER
            // -----------------------------------------------
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('businesses_details.product_name', 'like', "%{$search}%")
                        ->orWhere('vendors.vendor_name', 'like', "%{$search}%")
                        ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")
                        ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%");
                });
            }

            // -----------------------------------------------
            // VENDOR FILTER
            // -----------------------------------------------
            if ($request->filled('vendor_name')) {
                $query->where('vendors.id', $request->vendor_name);
            }

            // -----------------------------------------------
            // PO NUMBER FILTER
            // -----------------------------------------------
            if ($request->filled('purchase_orders_id')) {
                $query->where('purchase_orders.purchase_orders_id', $request->purchase_orders_id);
            }

            // -----------------------------------------------
            // DATE FILTER
            // -----------------------------------------------
            if ($request->filled('from_date')) {
                $query->whereDate('grn_tbl.updated_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('grn_tbl.updated_at', '<=', $request->to_date);
            }

            // -----------------------------------------------
            // YEAR / MONTH FILTER
            // -----------------------------------------------
            if ($request->filled('year')) {
                $query->whereYear('grn_tbl.updated_at', $request->year);
            }

            if ($request->filled('month')) {
                $query->whereMonth('grn_tbl.updated_at', $request->month);
            }

            // -----------------------------------------------
            // SELECT FIELDS
            // -----------------------------------------------
            $query->select(
                'tbl_rejected_chalan.id',
                'tbl_rejected_chalan.purchase_orders_id',
                'grn_tbl.po_date',
                'grn_tbl.grn_date',
                'grn_tbl.remark',
                'gatepass.gatepass_name',
                'tbl_rejected_chalan.is_active',
                'grn_tbl.updated_at',
                'purchase_orders.purchase_orders_id as po_number',
                'businesses_details.product_name',
                'businesses_details.description',
                'vendors.vendor_name',
                'vendors.vendor_company_name',
                'grn_tbl.grn_no_generate',
                'businesses_details.id as business_details_id',
                'grn_tbl.id as grn_id',
                'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id'
            )->distinct();

            // -----------------------------------------------
            // EXPORT REQUEST
            // -----------------------------------------------
            if ($request->filled('export_type')) {
                return [
                    'data' => $query->orderBy('grn_tbl.id', 'desc')->get(),
                    'pagination' => null,
                ];
            }

            // -----------------------------------------------
            // PAGINATION
            // -----------------------------------------------
            $perPage = $request->input('pageSize', 10);
            $currentPage = $request->input('currentPage', 1);

            $totalItems = $query->distinct()->count('grn_tbl.id');

            $data = $query->orderBy('grn_tbl.id', 'desc')
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
                // ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
                ->where('bap1.dispatch_status_id', 1154)

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
    public function getConsumptionMaterialList($id)
    {
        try {
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('production_details', function ($join) {
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

    public function listItemStockReport()
    {
        try {
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('production_details', function ($join) {
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
                ->leftJoin('production', function ($join) {
                    $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
                })
                ->leftJoin('tbl_vehicle_type', function ($join) {
                    $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
                })
                ->leftJoin('tbl_transport_name', function ($join) {
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

            if ($request->filled('business_details_id')) {
                $query->where('tbl_logistics.business_details_id', $request->business_details_id);
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
    public function listFinanceReport(Request $request)
    {
        try {
            $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
            $array_to_be_quantity_tracking = [config('constants.FINANCE_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

            $array_to_be_check_new = ['0'];
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
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->where('tbl_customer_product_quantity_tracking.fianace_list_status', 'Send_Dispatch');
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

            if ($request->filled('business_details_id')) {
                $query->where('tbl_logistics.business_details_id', $request->business_details_id);
            }

            // 🗓️ Date filters
            if ($request->filled('from_date')) {
                $query->whereDate('tbl_logistics.updated_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('tbl_logistics.updated_at', '<=', $request->to_date);
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
    public function listVendorPaymentReport(Request $request)
    {
        try {
            //    $array_to_be_check = [config('constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT')];
            //   $array_to_be_quantity_tracking = [ config('constants.FINANCE_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

            //   $array_to_be_check_new = ['0'];
            $query = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->leftJoin('vendors', function ($join) {
                    $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
                })
                ->where('purchase_orders.is_active', true)
                ->where('purchase_orders.is_deleted', 0)
                ->whereNotNull('grn_tbl.grn_no_generate');

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
                'grn_tbl.*'
                //   'grn_tbl.grn_status_sanction',
                //  'grn_tbl.grn_no_generate',
                //   'grn_tbl.updated_at'

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
    public function listDispatchReport(Request $request)
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
            if ($request->filled('business_details_id')) {
                $query->where('tbl_dispatch.business_details_id', $request->business_details_id);
            }

            if ($request->filled('from_date')) {
                $from = Carbon::parse($request->from_date)->startOfDay(); // 00:00:00
                $query->where('tbl_dispatch.updated_at', '>=', $from);
            }

            if ($request->filled('to_date')) {
                $to = Carbon::parse($request->to_date)->endOfDay(); // 23:59:59
                $query->where('tbl_dispatch.updated_at', '<=', $to);
            }
            // Year Filter (Use HAVING after selecting MAX Date)
            if ($request->filled('year')) {
                $query->havingRaw("YEAR(MAX(tbl_dispatch.updated_at)) = ?", [$request->year]);
            }

            // Month Filter (Use HAVING)
            if ($request->filled('month')) {
                $query->havingRaw("MONTH(MAX(tbl_dispatch.updated_at)) = ?", [$request->month]);
            }

            // if ($request->filled('year')) {
            //     $query->whereYear('tbl_dispatch.updated_at', $request->year);
            // }

            // if ($request->filled('month')) {
            //     $query->whereMonth('tbl_dispatch.updated_at', $request->month);
            // }

            // 🔽 Select columns
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

            if ($request->filled('business_details_id')) {
                $query->where('bap1.business_details_id', $request->business_details_id);
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
            $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.FINAL_PRODUCT_DISPATCH')];
            // $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
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

            if ($request->filled('business_details_id')) {
                $query->where('tbl_dispatch.business_details_id', $request->business_details_id);
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
            $query = DB::table('purchase_orders')
                ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
                ->leftJoin('purchase_order_details', 'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
                ->select(
                    'vendors.vendor_name',
                    DB::raw('SUM(purchase_order_details.quantity) as total_quantity')
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
            $query->whereDate('purchase_orders.updated_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('purchase_orders.updated_at', '<=', $request->to_date);
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
                DB::raw('COUNT(purchase_orders.id) as total_pos'),

            )
            ->groupBy(
                'vendors.id',
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
                'purchase_orders.purchase_orders_id',
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
                    'purchase_orders.purchase_orders_id',
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

            // ⚡ FAST EXPORT (Chunking Data)
            if ($request->filled('export_type')) {

                $exportData = [];

                $query->chunk(300, function ($rows) use (&$exportData) {
                    foreach ($rows as $row) {
                        $exportData[] = $row;
                    }
                });

                return [
                    'status' => true,
                    'data' => $exportData,  // <-- only chunked full data
                    'pagination' => null
                ];
            }

            // 📄 Pagination
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
    // public function listStockDailyReport($request)
    // {
    //     try {

    //         // PART ITEM FILTER (Required for ledger)
    //         $partId = $request->description ?? null;

    //         // ------------------------------------------------------
    //         // 1. RECEIVED TRANSACTIONS (GRN)
    //         // ------------------------------------------------------
    //         $received = DB::table('tbl_grn_po_quantity_tracking')
    //             ->join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_grn_po_quantity_tracking.part_no_id')
    //             ->selectRaw("
    //                 tbl_grn_po_quantity_tracking.updated_at AS date,
    //                 tbl_part_item.description AS part_name,
    //                 tbl_grn_po_quantity_tracking.quantity AS received_qty,
    //                 0 AS issue_qty
    //             ");

    //         if ($partId) {
    //             $received->where('tbl_part_item.id', $partId);
    //         }

    //         // Filters
    //         if ($request->filled('from_date')) {
    //             $received->whereDate('tbl_grn_po_quantity_tracking.updated_at', '>=', $request->from_date);
    //         }
    //         if ($request->filled('to_date')) {
    //             $received->whereDate('tbl_grn_po_quantity_tracking.updated_at', '<=', $request->to_date);
    //         }
    //         if ($request->filled('year')) {
    //             $received->whereYear('tbl_grn_po_quantity_tracking.updated_at', $request->year);
    //         }
    //         if ($request->filled('month')) {
    //             $received->whereMonth('tbl_grn_po_quantity_tracking.updated_at', $request->month);
    //         }

    //         // ------------------------------------------------------
    //         // 2. ISSUE TRANSACTIONS (PRODUCTION)
    //         // ------------------------------------------------------
    //         $issued = DB::table('production_details')
    //             ->join('tbl_part_item', 'tbl_part_item.id', '=', 'production_details.part_item_id')
    //             ->selectRaw("
    //                 production_details.updated_at AS date,
    //                 tbl_part_item.description AS part_name,
    //                 0 AS received_qty,
    //                 production_details.quantity AS issue_qty
    //             ");

    //         if ($partId) {
    //             $issued->where('tbl_part_item.id', $partId);
    //         }

    //         // Filters
    //         if ($request->filled('from_date')) {
    //             $issued->whereDate('production_details.updated_at', '>=', $request->from_date);
    //         }
    //         if ($request->filled('to_date')) {
    //             $issued->whereDate('production_details.updated_at', '<=', $request->to_date);
    //         }
    //         if ($request->filled('year')) {
    //             $issued->whereYear('production_details.updated_at', $request->year);
    //         }
    //         if ($request->filled('month')) {
    //             $issued->whereMonth('production_details.updated_at', $request->month);
    //         }

    //         // ------------------------------------------------------
    //         // 3. UNION BOTH (RECEIVED + ISSUED)
    //         // ------------------------------------------------------
    //         $ledger = $received->unionAll($issued)
    //             ->orderBy('date', 'asc')
    //             ->get();

    //         // ------------------------------------------------------
    //         // 4. RUNNING BALANCE LOGIC
    //         // ------------------------------------------------------
    //         $runningBalance = 0;
    //         foreach ($ledger as $row) {
    //             $runningBalance = $runningBalance + $row->received_qty - $row->issue_qty;
    //             $row->balance = $runningBalance;
    //         }

    //         // ------------------------------------------------------
    //         // 5. PAGINATION (MANUAL)
    //         // ------------------------------------------------------
    //         $currentPage = $request->input('currentPage', 1);
    //         $pageSize = $request->input('pageSize', 10);

    //         $totalItems = count($ledger);
    //         $pagedData = array_slice($ledger->toArray(), ($currentPage - 1) * $pageSize, $pageSize);

    //         return [
    //             'status' => true,
    //             'data' => $pagedData,
    //             'pagination' => [
    //                 'currentPage' => $currentPage,
    //                 'pageSize' => $pageSize,
    //                 'totalItems' => $totalItems,
    //                 'totalPages' => ceil($totalItems / $pageSize),
    //                 'from' => ($currentPage - 1) * $pageSize + 1,
    //                 'to' => min($currentPage * $pageSize, $totalItems),
    //             ],
    //             'totals' => [
    //                 'received' => $ledger->sum('received_qty'),
    //                 'issue' => $ledger->sum('issue_qty'),
    //                 'balance' => $runningBalance,
    //             ]
    //         ];

    //     } catch (\Exception $e) {
    //         return [
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }
    /**
     * T-2026-060 — Stock Daily Report ledger.
     *
     * Ledger legs (all UNION ALL'd, each selecting the SAME 8 columns in the
     * same order: date, part_name, received_qty, issue_qty, grn_no,
     * vendor_name, product_name, sort_order):
     *   0. Opening Stock          — tbl_part_item.opening_stock (sort_order=0,
     *                               always the first row for the selected part;
     *                               window-aware when a date/year/month filter
     *                               narrows the report — see
     *                               resolveStockReportWindowStart()).
     *   1. GRN Received           — tbl_grn_po_quantity_tracking, dated by the
     *                               real grn_tbl.grn_date (falls back to
     *                               updated_at only when grn_date is null).
     *   2. Production Issue       — production_details, restricted to rows
     *                               that ACTUALLY moved stock
     *                               (quantity_minus_status='done' AND
     *                               material_send_production=1 — see
     *                               StoreRepository::updateAddmoreStoreItem()),
     *                               dated by the dedicated issued_at column
     *                               (falls back to updated_at for legacy rows
     *                               written before that column existed).
     *   3. Delivery Challan Issue — tbl_delivery_chalan_item_details, dated by
     *                               the real tbl_delivery_chalan.dc_date
     *                               (set once at creation, never touched by
     *                               later edits).
     *   4. Returnable Challan Issue — tbl_returnable_chalan_item_details (was
     *                               entirely missing from the ledger before
     *                               this fix), same dating convention as
     *                               Delivery.
     *   5. Manual Stock Movement  — tbl_item_stock_history, movement_type IN
     *                               (manual_addition, manual_adjustment_set,
     *                               opening_reconciliation) — this is what
     *                               makes "Add Stock" / "Edit Stock" receipts
     *                               (previously invisible to this report)
     *                               show up, and where the one-time
     *                               reconciliation entries seeded by
     *                               `php artisan stock:reconcile-opening-
     *                               balance` land.
     *
     * Cross-part accumulation (root cause #6): a running Balance Qty is only
     * meaningful for ONE part at a time. When no `description` (part item)
     * filter is applied, `balance` is returned as `null` for every row and in
     * `totals.balance` — never a meaningless cross-part running total. This
     * was chosen over partitioning the balance per part within one flat,
     * chronologically-interleaved list, which would make the single visible
     * "Balance Qty" column jump around nonsensically row to row between
     * unrelated parts.
     *
     * `search` (root cause #8) is applied as a POST-filter on the already
     * balance-computed ledger collection, never as a SQL WHERE before the
     * running balance is computed — this guarantees the true final Balance
     * Qty (and `totals.balance`) always matches tbl_item_stock.quantity
     * regardless of what the user has typed into the search box.
     */
    public function listStockDailyReport($request)
    {
        try {

            $partId = $request->description ?? null;
            $search = $request->filled('search') ? trim((string) $request->search) : null;

            $windowStart = $this->resolveStockReportWindowStart($request);

            /* -----------------------------------------
           0. OPENING STOCK
        ------------------------------------------*/
            if ($partId) {
                $partRow = DB::table('tbl_part_item')
                    ->where('id', $partId)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->first(['id', 'description', 'opening_stock', 'created_at']);

                if ($partRow) {
                    $openingValue = (float) ($partRow->opening_stock ?? 0);
                    $openingDate = $partRow->created_at;

                    if ($windowStart) {
                        // Root cause #5: when a date/year/month window narrows the
                        // report, "opening balance" must mean everything that
                        // happened BEFORE the window, not just the static
                        // opening_stock column.
                        $openingValue += $this->sumLedgerNetForPart($partId, $windowStart);
                        $openingDate = $windowStart->copy()->subSecond();
                    }

                    $opening = DB::table('tbl_part_item')
                        ->where('id', $partId)
                        ->selectRaw('? as date, ? as part_name, ? as received_qty, 0 as issue_qty, ? as grn_no, ? as vendor_name, ? as product_name, 0 as sort_order', [
                            $openingDate, $partRow->description, $openingValue, 'Opening Stock', '', ''
                        ])
                        ->limit(1);
                } else {
                    // Selected part id is invalid / inactive / deleted — keep the
                    // union structurally valid with an empty opening leg.
                    $opening = DB::table('tbl_part_item')
                        ->selectRaw("created_at as date, description as part_name, 0 as received_qty, 0 as issue_qty, 'Opening Stock' as grn_no, '' as vendor_name, '' as product_name, 0 as sort_order")
                        ->whereRaw('1 = 0');
                }
            } else {
                $opening = DB::table('tbl_part_item')
                    ->selectRaw("
                        created_at as date,
                        description as part_name,
                        COALESCE(opening_stock, 0) as received_qty,
                        0 as issue_qty,
                        'Opening Stock' as grn_no,
                        '' as vendor_name,
                        '' as product_name,
                        0 as sort_order
                    ")
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    // Only parts that actually HAVE opening stock. Without this
                    // the unfiltered report emitted one row per part item
                    // regardless — and since opening_stock is NULL or 0 for
                    // almost every part (1,335 of 1,336 on live data), page 1
                    // was nothing but blank rows with the real transactions
                    // buried pages deep. Excluding them changes no figure:
                    // a NULL/0 opening stock contributes nothing to any total.
                    ->whereNotNull('opening_stock')
                    ->where('opening_stock', '<>', 0);
            }

            /* -----------------------------------------
           1. RECEIVED TRANSACTIONS (GRN)
        ------------------------------------------*/
            $received = DB::table('tbl_grn_po_quantity_tracking')
                ->join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_grn_po_quantity_tracking.part_no_id')
                ->join('grn_tbl', 'grn_tbl.id', '=', 'tbl_grn_po_quantity_tracking.grn_id')
                ->leftJoin('purchase_orders', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
                ->leftJoin('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
                ->selectRaw("
        COALESCE(grn_tbl.grn_date, tbl_grn_po_quantity_tracking.updated_at) as date,
        tbl_part_item.description as part_name,
        IFNULL(tbl_grn_po_quantity_tracking.accepted_quantity,0) as received_qty,
        0 as issue_qty,
        grn_tbl.grn_no_generate as grn_no,
        vendors.vendor_name as vendor_name,
        '' as product_name,
        1 as sort_order
    ")
                ->where('tbl_grn_po_quantity_tracking.is_deleted', 0)
                ->where('tbl_grn_po_quantity_tracking.is_active', 1);

            if ($partId) {
                $received->where('tbl_part_item.id', $partId);
            }

            $this->applyStockReportDateFilters($received, $request, DB::raw('COALESCE(grn_tbl.grn_date, tbl_grn_po_quantity_tracking.updated_at)'));

            /* -----------------------------------------
           2. ISSUE TRANSACTIONS (PRODUCTION)
           Root cause #3: only count rows that ACTUALLY moved stock.
        ------------------------------------------*/
            $issued = DB::table('production_details')
                ->leftJoin('tbl_part_item', 'tbl_part_item.id', '=', 'production_details.part_item_id')
                ->leftJoin('businesses_details', 'businesses_details.id', '=', 'production_details.business_details_id')
                ->selectRaw("
        COALESCE(production_details.issued_at, production_details.updated_at) as date,
        tbl_part_item.description as part_name,
        0 as received_qty,
        production_details.quantity as issue_qty,
        '' as grn_no,
        '' as vendor_name,
        businesses_details.product_name as product_name,
        1 as sort_order
    ")
                ->where('production_details.is_deleted', 0)
                ->where('production_details.is_active', 1)
                ->where('production_details.quantity_minus_status', 'done')
                ->where('production_details.material_send_production', 1);

            if ($partId) {
                $issued->where('tbl_part_item.id', $partId);
            }

            $this->applyStockReportDateFilters($issued, $request, DB::raw('COALESCE(production_details.issued_at, production_details.updated_at)'));

            /* -----------------------------------------
           3. DELIVERY CHALLAN ISSUE
        ------------------------------------------*/
            $delivery = DB::table('tbl_delivery_chalan_item_details')
                ->leftJoin('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_delivery_chalan_item_details.part_item_id')
                ->leftJoin('tbl_delivery_chalan', 'tbl_delivery_chalan.id', '=', 'tbl_delivery_chalan_item_details.delivery_chalan_id')
                ->leftJoin('vendors', 'vendors.id', '=', 'tbl_delivery_chalan.vendor_id')
                ->selectRaw("
        COALESCE(tbl_delivery_chalan.dc_date, tbl_delivery_chalan_item_details.created_at) as date,
        tbl_part_item.description as part_name,
        0 as received_qty,
        tbl_delivery_chalan_item_details.quantity as issue_qty,
        '' as grn_no,
        vendors.vendor_company_name as vendor_name,
        'Delivery Challan No.' as product_name,
        1 as sort_order
    ")
                ->where('tbl_delivery_chalan_item_details.is_deleted', 0)
                ->where('tbl_delivery_chalan_item_details.is_active', 1);

            if ($partId) {
                $delivery->where('tbl_part_item.id', $partId);
            }

            $this->applyStockReportDateFilters($delivery, $request, DB::raw('COALESCE(tbl_delivery_chalan.dc_date, tbl_delivery_chalan_item_details.created_at)'));

            /* -----------------------------------------
           4. RETURNABLE CHALLAN ISSUE (root cause #4 — was entirely missing)

           KNOWN LIMITATION (T-2026-060, deferred, not fixed here): this leg
           reads the CURRENT `tbl_returnable_chalan_item_details.quantity`,
           which is what was actually deducted from ItemStock only at the
           moment the issue was first created. `ReturnableChalanRepository::
           updateAll()` (~lines 209-222) allows editing an existing
           returnable-chalan item's quantity WITHOUT adjusting ItemStock, so
           for any part whose returnable-chalan issue was later edited that
           way, this leg's figure can silently diverge from the amount truly
           deducted from stock. This is a pre-existing bug in
           ReturnableChalanRepository, not introduced by this task;
           ReturnableChalanRepository is an explicitly off-limits/read-only
           reference file for T-2026-060 and must not be modified here. See
           system_architect memory (T-2026-060, iteration 2) for the full
           writeup — candidate for a future dedicated task.
        ------------------------------------------*/
            $returnableIssue = DB::table('tbl_returnable_chalan_item_details')
                ->leftJoin('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_returnable_chalan_item_details.part_item_id')
                ->leftJoin('tbl_returnable_chalan', 'tbl_returnable_chalan.id', '=', 'tbl_returnable_chalan_item_details.returnable_chalan_id')
                ->leftJoin('vendors', 'vendors.id', '=', 'tbl_returnable_chalan.vendor_id')
                ->selectRaw("
        COALESCE(tbl_returnable_chalan.dc_date, tbl_returnable_chalan_item_details.created_at) as date,
        tbl_part_item.description as part_name,
        0 as received_qty,
        tbl_returnable_chalan_item_details.quantity as issue_qty,
        '' as grn_no,
        vendors.vendor_company_name as vendor_name,
        'Returnable Challan No.' as product_name,
        1 as sort_order
    ")
                ->where('tbl_returnable_chalan_item_details.is_deleted', 0)
                ->where('tbl_returnable_chalan_item_details.is_active', 1);

            if ($partId) {
                $returnableIssue->where('tbl_part_item.id', $partId);
            }

            $this->applyStockReportDateFilters($returnableIssue, $request, DB::raw('COALESCE(tbl_returnable_chalan.dc_date, tbl_returnable_chalan_item_details.created_at)'));

            /* -----------------------------------------
           5. MANUAL STOCK MOVEMENT (root cause #1 — was entirely missing)
        ------------------------------------------*/
            $manual = DB::table('tbl_item_stock_history')
                ->join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_item_stock_history.part_item_id')
                ->selectRaw("
        tbl_item_stock_history.created_at as date,
        tbl_part_item.description as part_name,
        CASE WHEN tbl_item_stock_history.quantity_delta >= 0 THEN tbl_item_stock_history.quantity_delta ELSE 0 END as received_qty,
        CASE WHEN tbl_item_stock_history.quantity_delta < 0 THEN ABS(tbl_item_stock_history.quantity_delta) ELSE 0 END as issue_qty,
        CASE
            WHEN tbl_item_stock_history.quantity_delta >= 0 AND tbl_item_stock_history.movement_type = 'opening_reconciliation' THEN 'Opening Reconciliation'
            WHEN tbl_item_stock_history.quantity_delta >= 0 THEN 'Manual Entry'
            ELSE ''
        END as grn_no,
        '' as vendor_name,
        CASE
            WHEN tbl_item_stock_history.quantity_delta < 0 AND tbl_item_stock_history.movement_type = 'opening_reconciliation' THEN 'Opening Reconciliation'
            WHEN tbl_item_stock_history.quantity_delta < 0 THEN 'Manual Entry'
            ELSE ''
        END as product_name,
        1 as sort_order
    ")
                ->where('tbl_item_stock_history.is_deleted', 0)
                ->where('tbl_item_stock_history.is_active', 1)
                ->whereIn('tbl_item_stock_history.movement_type', ['manual_addition', 'manual_adjustment_set', 'opening_reconciliation'])
                ->whereNotNull('tbl_item_stock_history.quantity_delta');

            if ($partId) {
                $manual->where('tbl_part_item.id', $partId);
            }

            $this->applyStockReportDateFilters($manual, $request, DB::raw('tbl_item_stock_history.created_at'));

            /* -----------------------------------------
           6. MERGE ALL LEGS (UNION ALL)
        ------------------------------------------*/
            $ledgerQuery = $opening
                ->unionAll($received)
                ->unionAll($issued)
                ->unionAll($delivery)
                ->unionAll($returnableIssue)
                ->unionAll($manual);

            // sort_order guarantees Opening Stock is always the FIRST row for
            // the selected part regardless of any date-formatting/timezone
            // edge case in the underlying columns (root cause #5).
            $ledger = DB::query()
                ->fromSub($ledgerQuery, 'ledger')
                ->orderBy('sort_order', 'asc')
                ->orderBy('date', 'asc')
                ->get();

            /* -----------------------------------------
           7. RUNNING BALANCE LOGIC
           Root cause #6: only computed when a single part is selected.
        ------------------------------------------*/
            $computeBalance = (bool) $partId;
            $runningBalance = 0.0;

            foreach ($ledger as $row) {
                if ($computeBalance) {
                    $runningBalance = $runningBalance + (float) $row->received_qty - (float) $row->issue_qty;
                    $row->balance = $runningBalance;
                } else {
                    $row->balance = null;
                }
            }

            $finalBalance = $computeBalance ? $runningBalance : null;

            // Root cause #8: search is a display-only post-filter, applied
            // AFTER the true running balance has already been computed and
            // stamped onto every row — it can never desync the reported
            // balance from tbl_item_stock.quantity.
            if ($search !== null && $search !== '') {
                $needle = mb_strtolower($search);
                $ledger = $ledger->filter(function ($row) use ($needle) {
                    return str_contains(mb_strtolower((string) $row->part_name), $needle)
                        || str_contains(mb_strtolower((string) $row->grn_no), $needle)
                        || str_contains(mb_strtolower((string) $row->vendor_name), $needle)
                        || str_contains(mb_strtolower((string) $row->product_name), $needle);
                })->values();
            }

            // Prepare totals. received/issue reflect the currently-visible
            // (possibly searched) rows; balance is always the TRUE final
            // balance across the FULL ledger, never affected by search.
            $totals = [
                'received' => (float) $ledger->sum('received_qty'),
                'issue'    => (float) $ledger->sum('issue_qty'),
                'balance'  => $finalBalance,
            ];

            /* -----------------------------------------
           8. EXPORT PDF / EXCEL
        ------------------------------------------*/
            if ($request->filled('export_type')) {

                // PDF EXPORT
                if ($request->export_type == 1) {
                    return [
                        'export_pdf' => true,
                        'data'       => $ledger,
                        'totals'     => $totals
                    ];
                }

                // EXCEL EXPORT → implement as needed
                if ($request->export_type == 2) {
                    return [
                        'export_excel' => true,
                        'data'         => $ledger,
                        'totals'       => $totals
                    ];
                }
            }

            /* -----------------------------------------
           9. PAGINATION
        ------------------------------------------*/
            $currentPage = $request->input('currentPage', 1);
            $pageSize    = $request->input('pageSize', 10);

            $totalItems = $ledger->count();
            $pagedData  = array_slice($ledger->values()->toArray(), ($currentPage - 1) * $pageSize, $pageSize);

            return [
                'status' => true,
                'data' => $pagedData,
                'pagination' => [
                    'currentPage' => $currentPage,
                    'pageSize' => $pageSize,
                    'totalItems' => $totalItems,
                    'totalPages' => ceil($totalItems / $pageSize),
                    'from' => ($currentPage - 1) * $pageSize + 1,
                    'to' => min($currentPage * $pageSize, $totalItems),
                ],
                'totals' => $totals
            ];
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Resolves the lower-bound date of the currently-applied filter window,
     * used only to compute a correct "opening balance as of window start"
     * (root cause #5). Returns null when there is no clean, contiguous lower
     * bound to compute against (no filter at all, or a `month`-only filter
     * with no `year` — that combination matches the given calendar month
     * across every year, which is not a contiguous window and has no single
     * meaningful "before" boundary; in that case the report falls back to
     * the static tbl_part_item.opening_stock, same as when unfiltered).
     */
    private function resolveStockReportWindowStart($request): ?Carbon
    {
        if ($request->filled('from_date')) {
            return Carbon::parse($request->from_date)->startOfDay();
        }

        if ($request->filled('year')) {
            $year = (int) $request->year;
            if ($request->filled('month')) {
                return Carbon::createFromDate($year, (int) $request->month, 1)->startOfDay();
            }
            return Carbon::createFromDate($year, 1, 1)->startOfDay();
        }

        return null;
    }

    /**
     * Applies the report's from_date/to_date/year/month filters to a leg's
     * query builder against the given date expression (a real column or a
     * DB::raw() COALESCE expression — never `updated_at` alone, per root
     * cause #7).
     */
    private function applyStockReportDateFilters($query, $request, $dateExpression): void
    {
        if ($request->filled('from_date')) {
            $query->whereRaw('DATE(' . $dateExpression->getValue(DB::connection()->getQueryGrammar()) . ') >= ?', [$request->from_date]);
        }
        if ($request->filled('to_date')) {
            $query->whereRaw('DATE(' . $dateExpression->getValue(DB::connection()->getQueryGrammar()) . ') <= ?', [$request->to_date]);
        }
        if ($request->filled('year')) {
            $query->whereRaw('YEAR(' . $dateExpression->getValue(DB::connection()->getQueryGrammar()) . ') = ?', [$request->year]);
        }
        if ($request->filled('month')) {
            $query->whereRaw('MONTH(' . $dateExpression->getValue(DB::connection()->getQueryGrammar()) . ') = ?', [$request->month]);
        }
    }

    /**
     * Sums the signed net (received - issued) of every ledger leg EXCEPT
     * Opening Stock for a single part, optionally bounded to movements
     * strictly before $beforeDate. Shared by:
     *   - listStockDailyReport()'s windowed opening-balance calculation
     *     (root cause #5), where $beforeDate = the window start.
     *   - computeFullStockLedgerBalance() (used by the
     *     stock:reconcile-opening-balance artisan command), where
     *     $beforeDate = null (sums the entire history).
     */
    private function sumLedgerNetForPart($partId, ?Carbon $beforeDate = null): float
    {
        $received = DB::table('tbl_grn_po_quantity_tracking')
            ->join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_grn_po_quantity_tracking.part_no_id')
            ->join('grn_tbl', 'grn_tbl.id', '=', 'tbl_grn_po_quantity_tracking.grn_id')
            ->where('tbl_grn_po_quantity_tracking.is_deleted', 0)
            ->where('tbl_grn_po_quantity_tracking.is_active', 1)
            ->where('tbl_part_item.id', $partId);
        if ($beforeDate) {
            $received->whereRaw('COALESCE(grn_tbl.grn_date, tbl_grn_po_quantity_tracking.updated_at) < ?', [$beforeDate]);
        }
        $receivedSum = (float) $received->sum(DB::raw('IFNULL(tbl_grn_po_quantity_tracking.accepted_quantity,0)'));

        $issuedProd = DB::table('production_details')
            ->join('tbl_part_item', 'tbl_part_item.id', '=', 'production_details.part_item_id')
            ->where('production_details.is_deleted', 0)
            ->where('production_details.is_active', 1)
            ->where('production_details.quantity_minus_status', 'done')
            ->where('production_details.material_send_production', 1)
            ->where('tbl_part_item.id', $partId);
        if ($beforeDate) {
            $issuedProd->whereRaw('COALESCE(production_details.issued_at, production_details.updated_at) < ?', [$beforeDate]);
        }
        $issuedProdSum = (float) $issuedProd->sum('production_details.quantity');

        $issuedDelivery = DB::table('tbl_delivery_chalan_item_details')
            ->join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_delivery_chalan_item_details.part_item_id')
            ->leftJoin('tbl_delivery_chalan', 'tbl_delivery_chalan.id', '=', 'tbl_delivery_chalan_item_details.delivery_chalan_id')
            ->where('tbl_delivery_chalan_item_details.is_deleted', 0)
            ->where('tbl_delivery_chalan_item_details.is_active', 1)
            ->where('tbl_part_item.id', $partId);
        if ($beforeDate) {
            $issuedDelivery->whereRaw('COALESCE(tbl_delivery_chalan.dc_date, tbl_delivery_chalan_item_details.created_at) < ?', [$beforeDate]);
        }
        $issuedDeliverySum = (float) $issuedDelivery->sum('tbl_delivery_chalan_item_details.quantity');

        // KNOWN LIMITATION: see the identical comment on the main Returnable
        // Challan Issue ledger leg above (~line 3182) — this sum has the same
        // pre-existing ReturnableChalanRepository::updateAll() divergence risk.
        $issuedReturnable = DB::table('tbl_returnable_chalan_item_details')
            ->join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_returnable_chalan_item_details.part_item_id')
            ->leftJoin('tbl_returnable_chalan', 'tbl_returnable_chalan.id', '=', 'tbl_returnable_chalan_item_details.returnable_chalan_id')
            ->where('tbl_returnable_chalan_item_details.is_deleted', 0)
            ->where('tbl_returnable_chalan_item_details.is_active', 1)
            ->where('tbl_part_item.id', $partId);
        if ($beforeDate) {
            $issuedReturnable->whereRaw('COALESCE(tbl_returnable_chalan.dc_date, tbl_returnable_chalan_item_details.created_at) < ?', [$beforeDate]);
        }
        $issuedReturnableSum = (float) $issuedReturnable->sum('tbl_returnable_chalan_item_details.quantity');

        $manual = DB::table('tbl_item_stock_history')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->whereIn('movement_type', ['manual_addition', 'manual_adjustment_set', 'opening_reconciliation'])
            ->whereNotNull('quantity_delta')
            ->where('part_item_id', $partId);
        if ($beforeDate) {
            $manual->where('created_at', '<', $beforeDate);
        }
        $manualSum = (float) $manual->sum('quantity_delta');

        return $receivedSum - $issuedProdSum - $issuedDeliverySum - $issuedReturnableSum + $manualSum;
    }

    /**
     * Computes the FULL (unfiltered, entire-history) ledger balance for a
     * single part item and compares it against the authoritative
     * tbl_item_stock.quantity — used by the
     * `php artisan stock:reconcile-opening-balance` command.
     *
     * Returns null if the part item does not exist.
     */
    public function computeFullStockLedgerBalance($partId): ?array
    {
        $partRow = DB::table('tbl_part_item')
            ->where('id', $partId)
            ->first(['id', 'description', 'opening_stock', 'is_active', 'is_deleted']);

        if (!$partRow) {
            return null;
        }

        $openingStock = (float) ($partRow->opening_stock ?? 0);
        $net = $this->sumLedgerNetForPart($partId, null);
        $computedBalance = $openingStock + $net;

        $itemStock = DB::table('tbl_item_stock')->where('part_item_id', $partId)->first(['quantity']);
        $actualQuantity = ($itemStock && $itemStock->quantity !== null) ? (float) $itemStock->quantity : null;

        return [
            'part_id'          => $partRow->id,
            'description'      => $partRow->description,
            'is_active'        => (bool) $partRow->is_active,
            'is_deleted'       => (bool) $partRow->is_deleted,
            'computed_balance' => round($computedBalance, 3),
            'actual_quantity'  => $actualQuantity !== null ? round($actualQuantity, 3) : null,
        ];
    }

    /**
     * True if this part already has an active (non-deleted)
     * 'opening_reconciliation' history entry — the reconciliation command's
     * idempotency guard (never seed more than one per part).
     */
    public function hasActiveOpeningReconciliation($partId): bool
    {
        return DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)
            ->where('movement_type', 'opening_reconciliation')
            ->where('is_deleted', 0)
            ->exists();
    }

    /**
     * Seeds the one-time "Stock Adjustment (opening reconciliation)" ledger
     * entry for a part whose computed ledger total does not match
     * tbl_item_stock.quantity (unrecoverable historical manual stock
     * movements, per the task's acceptance criteria). Dated as early as
     * possible in that part's own ledger so it structurally sorts as the very
     * first real transaction, immediately after the true Opening Stock row.
     */
    public function insertOpeningReconciliationEntry($partId, float $delta, float $balanceAfter, string $remark): void
    {
        $earliestDate = $this->findEarliestLedgerDateForPart($partId);

        DB::table('tbl_item_stock_history')->insert([
            'part_item_id'   => $partId,
            'movement_type'  => 'opening_reconciliation',
            'quantity'       => $delta,
            'quantity_delta' => $delta,
            'balance_after'  => $balanceAfter,
            'remark'         => $remark,
            'is_active'      => 1,
            'is_deleted'     => 0,
            'created_at'     => $earliestDate,
            'updated_at'     => now(),
        ]);
    }

    /**
     * Earliest known date across a part's own ledger (part creation, or any
     * existing GRN/production/delivery/returnable/manual movement, whichever
     * is earliest), minus one second — used purely so a newly-inserted
     * opening_reconciliation row sorts before every other real row.
     */
    private function findEarliestLedgerDateForPart($partId): Carbon
    {
        $dates = [];

        $partCreatedAt = DB::table('tbl_part_item')->where('id', $partId)->value('created_at');
        if ($partCreatedAt) {
            $dates[] = Carbon::parse($partCreatedAt);
        }

        $grnMin = DB::table('tbl_grn_po_quantity_tracking')
            ->join('grn_tbl', 'grn_tbl.id', '=', 'tbl_grn_po_quantity_tracking.grn_id')
            ->where('tbl_grn_po_quantity_tracking.part_no_id', $partId)
            ->where('tbl_grn_po_quantity_tracking.is_deleted', 0)
            ->min(DB::raw('COALESCE(grn_tbl.grn_date, tbl_grn_po_quantity_tracking.updated_at)'));
        if ($grnMin) {
            $dates[] = Carbon::parse($grnMin);
        }

        $prodMin = DB::table('production_details')
            ->where('part_item_id', $partId)
            ->where('is_deleted', 0)
            ->where('quantity_minus_status', 'done')
            ->where('material_send_production', 1)
            ->min(DB::raw('COALESCE(issued_at, updated_at)'));
        if ($prodMin) {
            $dates[] = Carbon::parse($prodMin);
        }

        $delivMin = DB::table('tbl_delivery_chalan_item_details')
            ->leftJoin('tbl_delivery_chalan', 'tbl_delivery_chalan.id', '=', 'tbl_delivery_chalan_item_details.delivery_chalan_id')
            ->where('tbl_delivery_chalan_item_details.part_item_id', $partId)
            ->where('tbl_delivery_chalan_item_details.is_deleted', 0)
            ->min(DB::raw('COALESCE(tbl_delivery_chalan.dc_date, tbl_delivery_chalan_item_details.created_at)'));
        if ($delivMin) {
            $dates[] = Carbon::parse($delivMin);
        }

        $retMin = DB::table('tbl_returnable_chalan_item_details')
            ->leftJoin('tbl_returnable_chalan', 'tbl_returnable_chalan.id', '=', 'tbl_returnable_chalan_item_details.returnable_chalan_id')
            ->where('tbl_returnable_chalan_item_details.part_item_id', $partId)
            ->where('tbl_returnable_chalan_item_details.is_deleted', 0)
            ->min(DB::raw('COALESCE(tbl_returnable_chalan.dc_date, tbl_returnable_chalan_item_details.created_at)'));
        if ($retMin) {
            $dates[] = Carbon::parse($retMin);
        }

        $manualMin = DB::table('tbl_item_stock_history')
            ->where('part_item_id', $partId)
            ->where('is_deleted', 0)
            ->whereIn('movement_type', ['manual_addition', 'manual_adjustment_set'])
            ->min('created_at');
        if ($manualMin) {
            $dates[] = Carbon::parse($manualMin);
        }

        if (empty($dates)) {
            return Carbon::now()->subSecond();
        }

        /** @var Carbon $earliest */
        $earliest = collect($dates)->sort()->first();

        return $earliest->copy()->subSecond();
    }

    /**
     * All active, non-deleted part item ids — used by
     * stock:reconcile-opening-balance to iterate the whole catalogue (or a
     * single part when --part=ID is given).
     */
    public function getReconcilablePartItemIds(?int $onlyPartId = null): \Illuminate\Support\Collection
    {
        $query = DB::table('tbl_part_item')
            ->where('is_active', 1)
            ->where('is_deleted', 0);

        if ($onlyPartId) {
            $query->where('id', $onlyPartId);
        }

        return $query->orderBy('id')->pluck('id');
    }

    // public function listItemWiseVendorRateReport(Request $request)
    // {
    //     try {

    //         $query = PurchaseOrdersModel::leftJoin('purchase_order_details', function ($join) {
    //             $join->on('purchase_order_details.purchase_id', '=', 'purchase_orders.id');
    //         })
    //             ->leftJoin('grn_tbl', function ($join) {
    //                 $join->on('grn_tbl.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
    //             })
    //             ->leftJoin('tbl_part_item', function ($join) {
    //                 $join->on('purchase_order_details.part_no_id', '=', 'tbl_part_item.id');
    //             })
    //             ->leftJoin('vendors', function ($join) {
    //                 $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
    //             })
    //             ->where('purchase_orders.is_active', true)
    //             ->where('purchase_orders.is_deleted', 0);

    //         if ($request->filled('search')) {
    //             $search = $request->search;
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('purchase_orders.purchase_orders_id', 'like', "%{$search}%")
    //                     ->orWhere('vendors.name', 'like', "%{$search}%")
    //                      ->orWhere('tbl_part_item.description', 'like', "%{$search}%")
    //                     ->orWhere('purchase_order_details.rate', 'like', "%{$search}%");
    //             });
    //         }

    //         // 📁 Filter by Project
    //         if ($request->filled('description')) {
    //             $query->where('tbl_part_item.id', $request->description);
    //         }

    //         // 🗓️ Date filters
    //         if ($request->filled('from_date')) {
    //             $query->whereDate('purchase_order_details.updated_at', '>=', $request->from_date);
    //         }

    //         if ($request->filled('to_date')) {
    //             $query->whereDate('purchase_order_details.updated_at', '<=', $request->to_date);
    //         }

    //         if ($request->filled('year')) {
    //             $query->whereYear('purchase_order_details.updated_at', $request->year);
    //         }

    //         if ($request->filled('month')) {
    //             $query->whereMonth('purchase_order_details.updated_at', $request->month);
    //         }

    //         // 🔽 Select columns
    //         $query->select(
    //             'purchase_orders.purchase_orders_id',
    //             'tbl_part_item.description',
    //             'vendors.vendor_name',
    //             'vendors.vendor_company_name',
    //             'purchase_order_details.rate',
    //             'grn_tbl.updated_at',

    //         )
    //             ->orderBy('purchase_order_details.updated_at', 'desc')
    //             ->get();


    //         // 📤 Export full data (PDF/Excel)
    //         if ($request->filled('export_type')) {
    //             return [
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
    //         throw $e; // ✅ Let the controller catch and respond
    //     }
    // }
    public function listItemWiseVendorRateReport(Request $request)
    {
        try {

            $query = PurchaseOrdersModel::leftJoin('purchase_order_details', function ($join) {
                $join->on('purchase_order_details.purchase_id', '=', 'purchase_orders.id');
            })
                ->leftJoin('grn_tbl', function ($join) {
                    $join->on('grn_tbl.purchase_orders_id', '=', 'purchase_orders.id');
                })
                ->leftJoin('tbl_part_item', function ($join) {
                    $join->on('purchase_order_details.part_no_id', '=', 'tbl_part_item.id');
                })
                ->leftJoin('vendors', function ($join) {
                    $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
                })
                ->where('purchase_orders.is_active', true)
                ->where('purchase_orders.is_deleted', 0);

            // 🔍 Search
            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('tbl_part_item.description', 'like', "%{$search}%")   // Item Name
                        ->orWhere('tbl_part_item.part_number', 'like', "%{$search}%")
                        ->orWhere('vendors.vendor_name', 'like', "%{$search}%")
                        ->orWhere('vendors.vendor_company_name', 'like', "%{$search}%")
                        ->orWhere('purchase_order_details.rate', 'like', "%{$search}%");
                });
            }

            if ($request->filled('description')) {
                $query->where('tbl_part_item.id', $request->description);
            }



            // 📅 Date Filters
            if ($request->filled('from_date')) {
                $query->whereDate('purchase_order_details.updated_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('purchase_order_details.updated_at', '<=', $request->to_date);
            }

            if ($request->filled('year')) {
                $query->whereYear('purchase_order_details.updated_at', $request->year);
            }

            if ($request->filled('month')) {
                $query->whereMonth('purchase_order_details.updated_at', $request->month);
            }

            // 🔽 Select Fields
            $query->select(
                'grn_tbl.updated_at',
                'tbl_part_item.description',
                'vendors.vendor_name',
                'vendors.vendor_company_name',
                'purchase_order_details.rate'
            )
                ->orderBy('grn_tbl.updated_at', 'desc');

            // Export
            if ($request->filled('export_type')) {
                return [
                    'data' => $query->get(),
                    'pagination' => null
                ];
            }

            // Pagination
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
    public function listEmployeeLeaveReport(Request $request)
    {
        try {
            $year = $request->year ?? date('Y');

            $pageSize = $request->pageSize ?? 10;
            $currentPage = $request->currentPage ?? 1;
            $offset = ($currentPage - 1) * $pageSize;

            $baseQuery = DB::table('users')
                ->leftJoin('tbl_leave_management as lm', function ($join) use ($year) {
                    $join->on('lm.leave_year', '=', DB::raw($year))
                        ->where('lm.is_active', 1)
                        ->where('lm.is_deleted', 0);
                })
                ->leftJoin('tbl_leaves as l', function ($join) use ($year) {
                    $join->on('users.id', '=', 'l.employee_id')
                        ->on('lm.id', '=', 'l.leave_type_id')
                        ->where('l.is_approved', 2)
                        ->whereYear('l.leave_start_date', $year);
                });

            /* ---------------- FILTERS ---------------- */

            if ($request->filled('year')) {
                $baseQuery->whereYear('l.leave_start_date', $request->year);
            }

            if ($request->filled('month')) {
                $baseQuery->whereMonth('l.leave_start_date', $request->month);
            }

            if ($request->filled('from_date')) {
                $baseQuery->whereDate('l.leave_start_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $baseQuery->whereDate('l.leave_start_date', '<=', $request->to_date);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $baseQuery->where(function ($q) use ($search) {
                    $q->where('users.f_name', 'like', "%{$search}%")
                        ->orWhere('users.m_name', 'like', "%{$search}%")
                        ->orWhere('users.l_name', 'like', "%{$search}%");
                });
            }

            /* -------- TOTAL COUNT -------- */
            $totalItems = (clone $baseQuery)
                ->select('users.id')
                ->groupBy('users.id')
                ->get()
                ->count();

            /* -------- DATA -------- */
            $data = $baseQuery->select(
                'users.id as employee_id',
                'users.f_name',
                'users.m_name',
                'users.l_name',
                DB::raw("$year as year"),

                DB::raw("SUM(CASE WHEN lm.name = 'CASUAL LEAVE' THEN lm.leave_count ELSE 0 END) as opening_cl"),
                DB::raw("SUM(CASE WHEN lm.name = 'PL' THEN lm.leave_count ELSE 0 END) as opening_pl"),
                DB::raw("SUM(CASE WHEN lm.name = 'SL' THEN lm.leave_count ELSE 0 END) as opening_sl"),

                DB::raw("SUM(CASE WHEN lm.name = 'CASUAL LEAVE' THEN l.leave_count ELSE 0 END) as used_cl"),
                DB::raw("SUM(CASE WHEN lm.name = 'PL' THEN l.leave_count ELSE 0 END) as used_pl"),
                DB::raw("SUM(CASE WHEN lm.name = 'SL' THEN l.leave_count ELSE 0 END) as used_sl")
            )
                ->groupBy('users.id', 'users.f_name', 'users.m_name', 'users.l_name')
                ->offset($offset)
                ->limit($pageSize)
                ->get();

            foreach ($data as $row) {
                $row->closed_cl = $row->opening_cl - $row->used_cl;
                $row->closed_pl = $row->opening_pl - $row->used_pl;
                $row->closed_sl = $row->opening_sl - $row->used_sl;
            }

            return [
                'data' => $data,
                'pagination' => [
                    'totalItems' => $totalItems,
                    'totalPages' => ceil($totalItems / $pageSize),
                    'from' => $offset + 1,
                    'to' => min($offset + $pageSize, $totalItems),
                ]
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }


    // public function listEmployeeLeaveReport(Request $request)
    // {
    //     try {
    //         $year = $request->year ?? date('Y');

    //         $pageSize = $request->pageSize ?? 10;
    //         $currentPage = $request->currentPage ?? 1;
    //         $offset = ($currentPage - 1) * $pageSize;

    //         $baseQuery = DB::table('users')
    //             ->leftJoin('tbl_leave_management as lm', function ($join) use ($year) {
    //                 $join->where('lm.leave_year', $year)
    //                     ->where('lm.is_active', 1)
    //                     ->where('lm.is_deleted', 0);
    //             })
    //             ->leftJoin('tbl_leaves as l', function ($join) use ($year) {
    //                 $join->on('users.id', '=', 'l.employee_id')
    //                     ->on('lm.id', '=', 'l.leave_type_id')
    //                     ->where('l.is_approved', 2)
    //                     ->whereYear('l.leave_start_date', $year);
    //             });

    //         /* ---------------- FILTERS ---------------- */

    //         if ($request->filled('month')) {
    //             $baseQuery->whereMonth('l.leave_start_date', $request->month);
    //         }

    //         if ($request->filled('from_date')) {
    //             $baseQuery->whereDate('l.leave_start_date', '>=', $request->from_date);
    //         }

    //         if ($request->filled('to_date')) {
    //             $baseQuery->whereDate('l.leave_start_date', '<=', $request->to_date);
    //         }

    //         if ($request->filled('search')) {
    //             $search = $request->search;
    //             $baseQuery->where(function ($q) use ($search) {
    //                 $q->where('users.f_name', 'like', "%{$search}%")
    //                     ->orWhere('users.m_name', 'like', "%{$search}%")
    //                     ->orWhere('users.l_name', 'like', "%{$search}%");
    //             });
    //         }

    //         /* -------- TOTAL COUNT (before limit) -------- */

    //         $countQuery = clone $baseQuery;
    //         $totalItems = $countQuery
    //             ->select('users.id')
    //             ->groupBy('users.id')
    //             ->get()
    //             ->count();

    //         /* -------- DATA QUERY -------- */

    //         $dataQuery = $baseQuery->select(
    //             'users.id as employee_id',
    //             'users.f_name',
    //             'users.m_name',
    //             'users.l_name',
    //             DB::raw("$year as year"),

    //             DB::raw("SUM(CASE WHEN lm.name = 'CASUAL LEAVE' THEN lm.leave_count ELSE 0 END) as opening_cl"),
    //             DB::raw("SUM(CASE WHEN lm.name = 'PL' THEN lm.leave_count ELSE 0 END) as opening_pl"),
    //             DB::raw("SUM(CASE WHEN lm.name = 'SL' THEN lm.leave_count ELSE 0 END) as opening_sl"),

    //             DB::raw("SUM(CASE WHEN lm.name = 'CASUAL LEAVE' THEN l.leave_count ELSE 0 END) as used_cl"),
    //             DB::raw("SUM(CASE WHEN lm.name = 'PL' THEN l.leave_count ELSE 0 END) as used_pl"),
    //             DB::raw("SUM(CASE WHEN lm.name = 'SL' THEN l.leave_count ELSE 0 END) as used_sl")
    //         )
    //             ->groupBy('users.id', 'users.f_name', 'users.m_name', 'users.l_name');
    //         /* -------- EXPORT (NO LIMIT) -------- */
    //         if ($request->filled('export_type')) {
    //             $data = $dataQuery->get();
    //         } else {
    //             $data = $dataQuery->offset($offset)->limit($pageSize)->get();
    //         }

    //         /* -------- CLOSED -------- */
    //         foreach ($data as $row) {
    //             $row->closed_cl = $row->opening_cl - $row->used_cl;
    //             $row->closed_pl = $row->opening_pl - $row->used_pl;
    //             $row->closed_sl = $row->opening_sl - $row->used_sl;
    //         }

    //         $totalPages = ceil($totalItems / $pageSize);

    //         return [
    //             'data' => $data,
    //             'pagination' => $request->filled('export_type') ? null : [
    //                 'totalItems' => $totalItems,
    //                 'totalPages' => $totalPages,
    //                 'from' => $offset + 1,
    //                 'to' => min($offset + $pageSize, $totalItems),
    //             ]
    //         ];
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    // }
}
