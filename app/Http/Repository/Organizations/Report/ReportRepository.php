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
    Gatepass
    };
use Config;

class ReportRepository  {

public function getCompletedProductList($request)
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
      
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tbl_dispatch.updated_at', [$request->from_date, $request->to_date]);
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

        $totalCount = $query->select('businesses_details.id')
            ->groupBy(
                'businesses_details.id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity'
            )
            ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
            ->get()
            ->count();

        return [
            'data' => $data_output,
            'total_count' => $totalCount
        ];
    } catch (\Exception $e) {
        return $e;
    }
}
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

        if ($request->filled('from_date')) {
            $query->whereDate('production.created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('production.created_at', '<=', $request->to_date);
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
            ->where('businesses.is_deleted', 0);

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
            'tbl_dispatch.remark',
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
public function getGRNReport(Request $request)
{
    try {
        $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            $query = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
                ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
                ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
                ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
                ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
                // ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                // ->where('businesses_details.id', $id)
                ->where('businesses_details.is_deleted', 0);
               
              


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->Where('businesses_details.product_name', 'like', "%{$search}%");
                   
                    
            });
        }

        if ($request->filled('vendor_name')) {
            $query->where('vendors.id', $request->vendor_name);
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
                    
                    'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id' // GRN ID from tracking table
                )->groupBy(
                    'purchase_orders.purchase_orders_id',
                    'tbl_grn_po_quantity_tracking.grn_id',
                    'purchase_orders.business_details_id',
                    'businesses_details.product_name',
                    'businesses_details.description',
                   'grn_tbl.updated_at',
                    'grn_tbl.grn_no_generate',
                    'vendors.vendor_name',
                    'vendors.vendor_company_name',
                )->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc')
                ->get(); 

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
    throw $e; // Let controller handle it
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

  public function getConsumptionMaterialList($id) {
        try {
            // $id = base64_decode($id); 
// dd($id);
// die();
            
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
       $array_to_be_quantity_tracking = [ config('constants.LOGISTICS_DEPARTMENT.UPDATED_COMPLETED_QUANLTITY_LOGISTICS_DEPT_SEND_TO_FIANANCE_DEPT')];

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
      ->where('tbl_customer_product_quantity_tracking.logistics_list_status','Send_Fianance')      
      ->where('businesses.is_active',true)
      ->where('businesses.is_deleted', 0);
      
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('businesses.project_name', 'like', "%{$search}%")
                    ->orWhere('businesses.title', 'like', "%{$search}%")
                    ->orWhere('businesses_details.product_name', 'like', "%{$search}%")
                    ->orWhere('businesses.customer_po_number', 'like', "%{$search}%");
            });
        }

       if ($request->filled('project_name')) {
            $query->where('businesses.id', $request->project_name);
        }
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
     
    ) 
    ->orderBy('tbl_logistics.updated_at', 'desc')
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
}