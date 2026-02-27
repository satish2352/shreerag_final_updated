<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses,
    Logistics
};

class DispatchDashboardRepository
{
    public function getCounts()
    {

        $dispatch_received_from_finance = BusinessApplicationProcesses::where('logistics_status_id', 1146)->where('off_canvas_status', 21)
            ->where('dispatch_status_id', 1147)
            ->where('is_active', 1)->count();
        // $dispatch_completed = BusinessApplicationProcesses::where('logistics_status_id', 1146)->where('off_canvas_status',22)
        // ->where('dispatch_status_id', 1148)
        // ->where('is_active',1)->count();

        $dispatch_completed = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
            $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
        })
            ->leftJoin('businesses', function ($join) {
                $join->on('tbl_logistics.business_id', '=', 'businesses.id');
            })
            ->where('tbl_customer_product_quantity_tracking.quantity_tracking_status', 3005)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)->count();

        return [

            'dispatch_counts' => [
                'dispatch_received_from_finance' => $dispatch_received_from_finance,
                'dispatch_completed' => $dispatch_completed,

            ]

        ];
    }
}
