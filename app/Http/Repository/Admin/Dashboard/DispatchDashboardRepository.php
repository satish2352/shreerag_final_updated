<?php

namespace App\Http\Repository\Admin\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\{
    BusinessApplicationProcesses,
    Logistics,
    CustomerProductQuantityTracking
};

class DispatchDashboardRepository
{
    public function getCounts()
    {
        // Stage 1: Received from Finance, waiting to be dispatched
        $dispatch_received_from_finance = BusinessApplicationProcesses::where('logistics_status_id', 1146)
            ->where('off_canvas_status', 21)
            ->where('dispatch_status_id', 1147)
            ->where('is_active', 1)
            ->count();

        // Stage 2: Dispatch in process (quantity received from Finance, being dispatched)
        $dispatch_in_process = CustomerProductQuantityTracking::where('quantity_tracking_status', 3004)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->count();

        // Stage 3: Dispatch completed (quantity dispatched)
        $dispatch_completed = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
            $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
        })
            ->leftJoin('businesses', function ($join) {
                $join->on('tbl_logistics.business_id', '=', 'businesses.id');
            })
            ->where('tbl_customer_product_quantity_tracking.quantity_tracking_status', 3005)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();

        // Stage 4: Final product fully closed (dispatch_status_id = 1154)
        $final_product_closed = BusinessApplicationProcesses::where('dispatch_status_id', 1154)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->count();

        return [
            'dispatch_counts' => [
                'dispatch_received_from_finance' => $dispatch_received_from_finance,
                'dispatch_in_process'            => $dispatch_in_process,
                'dispatch_completed'             => $dispatch_completed,
                'final_product_closed'           => $final_product_closed,
            ],
        ];
    }
}
