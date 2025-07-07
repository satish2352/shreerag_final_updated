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
    Logistics
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
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
            DB::raw('MAX(tbl_dispatch.updated_at) as updated_at') 
        )
        ->groupBy(
            'businesses_details.id',
            'businesses.customer_po_number',
            'businesses.title',
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

// public function listDesignReport(){
//   try {
//       $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
    
//     $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
//       $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
//     })
//       ->leftJoin('designs', function ($join) {
//         $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
//       })

//       ->leftJoin('businesses', function ($join) {
//         $join->on('business_application_processes.business_id', '=', 'businesses.id');
//       })
//       ->leftJoin('businesses_details', function($join) {
//         $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
//     })
//       ->leftJoin('design_revision_for_prod', function ($join) {
//         $join->on('designs.id', '=', 'design_revision_for_prod.design_id');
//       })
     
//         ->where('production.is_approved_production', 1)
//         // ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
       
//         ->where('businesses_details.is_active', true)
//         ->where('businesses_details.is_deleted', 0)
//         ->distinct('businesses_details.id');



















        
//         // ->where('businesses.is_active',true)
//        ->groupBy(
//             'businesses.id',
//             'businesses.customer_po_number',
//             'businesses.title',
//             'businesses_details.id',
//             'businesses_details.product_name',
//             'businesses_details.description',
//             'businesses_details.quantity',
//             'businesses_details.rate',
//             'designs.bom_image',
//             'designs.design_image',
//             'design_revision_for_prod.id',
//             'design_revision_for_prod.design_image',
//             'design_revision_for_prod.bom_image',
//             'design_revision_for_prod.reject_reason_prod',
//             'production.updated_at'
//         )
//         ->select(
//             'businesses.id',
//             'businesses_details.id',
//             'businesses.title',
//             'businesses.customer_po_number',
//             'businesses_details.product_name',
//             'businesses_details.description',
//             'businesses_details.quantity',
//             'designs.bom_image',
//             'designs.design_image',
//             'design_revision_for_prod.reject_reason_prod',
//             'design_revision_for_prod.id as design_revision_for_prod_id',
//             'design_revision_for_prod.design_image as re_design_image',
//             'design_revision_for_prod.bom_image as re_bom_image',
//             'production.updated_at'
//         )->orderBy('production.updated_at', 'desc')->get();
//       return $data_output;
    
//   } catch (\Exception $e) {
//       return $e;
//   }
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

        $query->select(
            'businesses.id as business_id',
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
            'design_revision_for_prod.design_image as re_design_image',
            'design_revision_for_prod.bom_image as re_bom_image',
            'production.updated_at'
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

}