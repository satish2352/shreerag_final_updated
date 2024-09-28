<?php
namespace App\Http\Repository\Organizations\Report;
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

class ReportRepository  {

// public function getCompletedProductList($request)
// {
//     try {
//         // dd($request);
//         // die();
//         $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];

//         $query = BusinessApplicationProcesses::leftJoin('production', 'business_application_processes.business_details_id', '=', 'production.business_details_id')
//             ->leftJoin('designs', 'business_application_processes.business_details_id', '=', 'designs.business_details_id')
//             ->leftJoin('businesses', 'business_application_processes.business_id', '=', 'businesses.id')
//             ->leftJoin('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
//             ->leftJoin('tbl_dispatch', 'business_application_processes.business_details_id', '=', 'tbl_dispatch.business_details_id')
//             ->whereIn('business_application_processes.dispatch_status_id', $array_to_be_check)
//             ->where('businesses.is_active', true);

//         // Apply filters based on request parameters
//         if ($request->filled('from_date') && $request->filled('to_date')) {
//             $query->whereBetween('tbl_dispatch.updated_at', [$request->from_date, $request->to_date]);
//         }

//         if ($request->filled('year')) {
//             $query->whereYear('tbl_dispatch.updated_at', $request->year);
//         }

//         if ($request->filled('month')) {
//             $query->whereMonth('tbl_dispatch.updated_at', $request->month);
//         }

//         // Select distinct values and group by
//         $data_output = $query->distinct('businesses_details.id')
//             ->groupBy(
//                 'businesses_details.id',
//                 'businesses.customer_po_number',
//                 'businesses.title',
//                 'businesses_details.product_name',
//                 'businesses_details.quantity',
//                 'tbl_dispatch.outdoor_no',
//                 'tbl_dispatch.updated_at'
//             )
//             ->select(
//                 'businesses_details.id',
//                 'businesses.customer_po_number',
//                 'businesses.title',
//                 'businesses_details.product_name',
//                 'businesses_details.quantity',
//                 'tbl_dispatch.outdoor_no',
//                 'tbl_dispatch.updated_at'
//             )
//             ->orderBy('tbl_dispatch.updated_at', 'desc')
//             ->get();

//         return $data_output;
//     } catch (\Exception $e) {
//         return $e;
//     }
// }
public function getCompletedProductList($request)
{
    try {
        $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];

        $query = BusinessApplicationProcesses::leftJoin('production', 'business_application_processes.business_details_id', '=', 'production.business_details_id')
            ->leftJoin('designs', 'business_application_processes.business_details_id', '=', 'designs.business_details_id')
            ->leftJoin('businesses', 'business_application_processes.business_id', '=', 'businesses.id')
            ->leftJoin('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
            ->leftJoin('tbl_dispatch', 'business_application_processes.business_details_id', '=', 'tbl_dispatch.business_details_id')
            ->whereIn('business_application_processes.dispatch_status_id', $array_to_be_check)
            ->where('businesses.is_active', true);

        // Apply filters based on request parameters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tbl_dispatch.updated_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_dispatch.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_dispatch.updated_at', $request->month);
        }

        // Clone the query to get total count without pagination
        $totalCount = $query->count();

        // Select distinct values and group by
        $data_output = $query->distinct('businesses_details.id')
            ->groupBy(
                'businesses_details.id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'tbl_dispatch.outdoor_no',
                'tbl_dispatch.updated_at'
            )
            ->select(
                'businesses_details.id',
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.product_name',
                'businesses_details.quantity',
                'tbl_dispatch.outdoor_no',
                'tbl_dispatch.updated_at'
            )
            ->orderBy('tbl_dispatch.updated_at', 'desc')
            ->get();

        return [
            'data' => $data_output,
            'total_count' => $totalCount
        ];
    } catch (\Exception $e) {
        return $e;
    }
}

}