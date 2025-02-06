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
        // Apply filters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('tbl_dispatch.updated_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('year')) {
            $query->whereYear('tbl_dispatch.updated_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tbl_dispatch.updated_at', $request->month);
        }

        // Clone query for data_output with proper grouping
        $data_output = $query->select(
            'businesses_details.id as business_details_id',
            'businesses.customer_po_number',
            'businesses.title',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses_details.quantity',
            DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
            DB::raw('MAX(tbl_dispatch.updated_at) as updated_at') // Use alias for clarity
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
        ->orderBy(DB::raw('MAX(tbl_dispatch.updated_at)'), 'desc') // Use aggregate function in ORDER BY
        ->get();

        // Calculate total count using a subquery to match data_output
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

}