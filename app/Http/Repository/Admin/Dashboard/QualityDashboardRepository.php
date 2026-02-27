<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    Gatepass,
    BusinessApplicationProcesses,
    RejectedChalan
};

class QualityDashboardRepository
{
    public function getCounts()
    {
        $GRN_genration = Gatepass::leftJoin('purchase_orders', function ($join) {
            $join->on('gatepass.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id');
        })
            ->where('gatepass.po_tracking_status', 4001)->where('gatepass.is_active', 1)->where('gatepass.is_deleted', 0)->count();
        $material_need_to_sent_to_store = BusinessApplicationProcesses::leftJoin('purchase_orders', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
        })
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->where('purchase_orders.quality_status_id', 1134)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();
        $rejected_chalan_po_wise = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
            ->leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
            ->where('tbl_rejected_chalan.is_active', true)
            ->where('tbl_rejected_chalan.is_deleted', 0)
            ->where('tbl_rejected_chalan.chalan_no', '<>', '')
            ->count();

        return [

            'quality_dept_counts' => [
                'GRN_genration' => $GRN_genration,
                'material_need_to_sent_to_store' => $material_need_to_sent_to_store,
                'rejected_chalan_po_wise' => $rejected_chalan_po_wise,
            ]

        ];
    }
}
