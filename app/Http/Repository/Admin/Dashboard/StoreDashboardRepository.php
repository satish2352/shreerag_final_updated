<?php

namespace App\Http\Repository\Admin\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\{
    BusinessApplicationProcesses,
    RejectedChalan,
    DeliveryChalan,
    ReturnableChalan,
    ItemStock
};

class StoreDashboardRepository
{
    public function getCounts()
    {
        // New requirements: production accepted design, store must issue material
        $material_need_to_sent_to_production = BusinessApplicationProcesses::where('off_canvas_status', 15)
            ->where('is_active', 1)->where('is_deleted', 0)->count();

        // Material actually issued/sent to production by store
        $material_sent_to_production = BusinessApplicationProcesses::where('off_canvas_status', 17)
            ->where('is_active', 1)->where('is_deleted', 0)->count();

        // Requisitions store sent to purchase (stock not available)
        $material_for_purchase = BusinessApplicationProcesses::where('store_status_id', 1123)
            ->where('is_active', 1)->where('is_deleted', 0)->count();

        // Material received from quality (GRN approved, now in store)
        $material_received_from_quality = BusinessApplicationProcesses::leftJoin('purchase_orders',
                'business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id')
            ->leftJoin('businesses',
                'business_application_processes.business_id', '=', 'businesses.id')
            ->where('purchase_orders.quality_status_id', 1134)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();

        // Chalans
        $rejected_chalan = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
            ->leftJoin('gatepass', 'grn_tbl.gatepass_id', '=', 'gatepass.id')
            ->where('tbl_rejected_chalan.is_active', true)
            ->where('tbl_rejected_chalan.is_deleted', 0)
            ->where('tbl_rejected_chalan.chalan_no', '<>', '')
            ->count();
        $delivery_chalan  = DeliveryChalan::where('is_active', 1)->where('is_deleted', 0)->count();
        $returnable_chalan = ReturnableChalan::where('is_active', 1)->where('is_deleted', 0)->count();

        // Inventory: distinct part items with stock > 0
        $stock_items_count = ItemStock::where('is_active', 1)->where('is_deleted', 0)
            ->where('quantity', '>', 0)->distinct('part_item_id')->count('part_item_id');

        // Total stock quantity across all items
        $total_stock_qty = (float) ItemStock::where('is_active', 1)->where('is_deleted', 0)->sum('quantity');

        return [
            'store_dept_counts' => [
                'material_need_to_sent_to_production' => $material_need_to_sent_to_production,
                'material_sent_to_production'          => $material_sent_to_production,
                'material_for_purchase'                => $material_for_purchase,
                'material_received_from_quality'       => $material_received_from_quality,
                'rejected_chalan'                      => $rejected_chalan,
                'delivery_chalan'                      => $delivery_chalan,
                'returnable_chalan'                    => $returnable_chalan,
                'stock_items_count'                    => $stock_items_count,
                'total_stock_qty'                      => $total_stock_qty,
            ]
        ];
    }
}
