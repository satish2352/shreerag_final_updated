<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses,
    CustomerProductQuantityTracking,
    PurchaseOrdersModel
};

class FinanceDashboardRepository
{
    public function getCounts()
    {
        $need_to_check_for_payment = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
            $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
        })
            ->where('grn_tbl.grn_status_sanction', 6000)
            ->whereNotNull('grn_tbl.grn_no_generate')
            ->whereNotNull('grn_tbl.store_receipt_no_generate')
            ->whereNotNull('grn_tbl.store_remark')
            ->where('grn_tbl.is_active', 1)
            ->where('grn_tbl.is_deleted', 0)
            ->count();

        $production_completed_prod_dept_logisitics = CustomerProductQuantityTracking::where('quantity_tracking_status', 3001)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->count();

        $po_pyament_need_to_release = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
            $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
        })
            ->where('grn_tbl.grn_status_sanction', 6003)
            ->whereNotNull('grn_tbl.grn_no_generate')
            ->whereNotNull('grn_tbl.store_receipt_no_generate')
            ->whereNotNull('grn_tbl.store_remark')
            ->where('grn_tbl.is_active', 1)
            ->where('grn_tbl.is_deleted', 0)
            ->count();

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
            'logistics_counts' => [
                'need_to_check_for_payment' => $need_to_check_for_payment,
                'production_completed_prod_dept_logisitics' => $production_completed_prod_dept_logisitics,
                'po_pyament_need_to_release' => $po_pyament_need_to_release,
            ],

            'fianance_counts' => [
                'logistics_send_by_finance_received_fianance_count' => $logistics_send_by_finance_received_fianance_count,
                'fianance_send_to_dispatch_count' => $fianance_send_to_dispatch_count,
            ]

        ];
    }
}
