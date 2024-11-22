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
        
        $query = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
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
            ->leftJoin('tbl_dispatch', function($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_dispatch.quantity_tracking_id');
            })
            ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
            ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
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
        $data_output = $query->distinct('businesses_details.id')
        // Select distinct values and group by
        ->groupBy(
            'businesses_details.id',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity' // Include this field
        )
        
          // Select the fields, including sum of quantities
          ->select(
            'businesses_details.id as business_details_id',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.product_name',
            'businesses_details.description',
             'businesses_details.quantity',
             DB::raw('COUNT(DISTINCT businesses_details.id) as total_business_details_count'), // Count distinct business_details_id
            DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity')
        )
            ->orderBy('tbl_dispatch.updated_at', 'desc')
            ->get();
$totalCount = $query->distinct('businesses_details.id')->count('businesses_details.id');
        return [
            'data' => $data_output,
            'total_count' => $totalCount
        ];
    } catch (\Exception $e) {
        return $e;
    }
}

}