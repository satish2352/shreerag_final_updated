<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses,
    CustomerProductQuantityTracking
};

class FinanceDashboardRepository
{
    public function getCounts()
    {

        $logistics_send_by_finance_received_fianance_count = BusinessApplicationProcesses::where('logistics_status_id', 1146)->where('off_canvas_status', 20)
            ->where('is_active', 1)->count();
        $fianance_send_to_dispatch_count = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function ($join) {
            $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
        })
            ->leftJoin('businesses', function ($join) {
                $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
            })

            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->where('tbl_customer_product_quantity_tracking.fianace_list_status', 'Send_Dispatch')
            ->count();
        return [

            'fianance_counts' => [
                'logistics_send_by_finance_received_fianance_count' => $logistics_send_by_finance_received_fianance_count,
                'fianance_send_to_dispatch_count' => $fianance_send_to_dispatch_count,
            ]

        ];
    }
}
