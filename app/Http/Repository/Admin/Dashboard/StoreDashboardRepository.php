<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses,
    RejectedChalan,
    DeliveryChalan,
    ReturnableChalan
};

class StoreDashboardRepository
{
    public function getCounts()
    {

        $material_need_to_sent_to_production = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
            ->where('production_status_id', 1114)->where('off_canvas_status', 15)
            ->where('is_deleted', 0)
            ->where('is_active', 1)->count();
        $material_for_purchase = BusinessApplicationProcesses::where('store_status_id', 1123)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->count();
        $material_received_from_quality = BusinessApplicationProcesses::leftJoin('purchase_orders', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->where('purchase_orders.quality_status_id', 1134)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();

        $rejected_chalan = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
            ->leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
            ->where('tbl_rejected_chalan.is_active', true)
            ->where('tbl_rejected_chalan.is_deleted', 0)
            ->where('tbl_rejected_chalan.chalan_no', '<>', '')
            ->count();
        $delivery_chalan = DeliveryChalan::where('is_active', 1)->where('is_deleted', 0)->count();
        $returnable_chalan = ReturnableChalan::where('is_active', 1)->where('is_deleted', 0)->count();

        return [

            'store_dept_counts' => [
                'material_need_to_sent_to_production' => $material_need_to_sent_to_production,
                // 'material_sent_to_production' => $material_sent_to_production,
                'material_for_purchase' => $material_for_purchase,
                'material_received_from_quality' => $material_received_from_quality,
                'rejected_chalan' => $rejected_chalan,
                'delivery_chalan' => $delivery_chalan,
                'returnable_chalan' => $returnable_chalan,
            ]
        ];
    }
}
