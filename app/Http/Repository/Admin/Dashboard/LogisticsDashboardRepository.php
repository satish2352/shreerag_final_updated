<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    PurchaseOrdersModel,
    BusinessApplicationProcesses,
    Logistics,
    VehicleType,
    TransportName
};

class LogisticsDashboardRepository
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
        $production_completed_prod_dept_logisitics = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
            $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
        })
            ->where('grn_tbl.grn_status_sanction', 6001)
            ->whereNotNull('grn_tbl.grn_no_generate')
            ->whereNotNull('grn_tbl.store_receipt_no_generate')
            ->whereNotNull('grn_tbl.store_remark')
            ->where('grn_tbl.is_active', 1)
            ->where('grn_tbl.is_deleted', 0)
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
        $logistics_list_count = BusinessApplicationProcesses::where('logistics_status_id', 1145)->where('off_canvas_status', 19)
            ->where('is_active', 1)->count();
        $logistics_send_by_finance_count = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
            $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
        })
            ->leftJoin('businesses', function ($join) {
                $join->on('tbl_logistics.business_id', '=', 'businesses.id');
            })
            ->where('tbl_customer_product_quantity_tracking.logistics_list_status', 'Send_Fianance')
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();

        $vehicle_type_count = VehicleType::where('is_active', 1)->count();
        $transport_name_count = TransportName::where('is_active', 1)->count();
        return [

            'logistics_counts' => [
                'need_to_check_for_payment' => $need_to_check_for_payment,
                'production_completed_prod_dept_logisitics' => $production_completed_prod_dept_logisitics,
                'po_pyament_need_to_release' => $po_pyament_need_to_release,
                'logistics_list_count' => $logistics_list_count,
                'logistics_send_by_finance_count' => $logistics_send_by_finance_count,
                'vehicle_type_count' => $vehicle_type_count,
                'transport_name_count' => $transport_name_count,
            ]
        ];
    }
}
