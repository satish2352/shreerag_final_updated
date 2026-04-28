<?php

namespace App\Http\Repository\Admin\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\{
    PurchaseOrderModel,
    Vendors,
    Tax,
    PartItem,
    BusinessApplicationProcesses
};

class PurchaseDashboardRepository
{
    public function getCounts()
    {
        // --- Pipeline counts ---

        // Requisitions from store waiting for PO creation
        $requisitions_pending = BusinessApplicationProcesses::where('store_status_id', 1123)
            ->where('is_active', 1)->where('is_deleted', 0)->count();

        // POs sent to owner for approval (not yet decided)
        $po_pending_owner = PurchaseOrderModel::where('purchase_status_from_purchase', 1126)
            ->where('is_active', 1)
            ->whereNotIn('purchase_status_from_owner', [1127, 1201])
            ->count();

        // POs approved by owner but not yet sent to vendor
        $po_approved_pending_send = PurchaseOrderModel::where('purchase_status_from_owner', 1127)
            ->where('purchase_status_from_purchase', 1126)
            ->where('is_active', 1)->count();

        // POs sent to vendor (email dispatched)
        $po_sent_to_vendor = PurchaseOrderModel::where('purchase_status_from_purchase', 1129)
            ->where('is_active', 1)->count();

        // POs rejected by owner
        $po_rejected = PurchaseOrderModel::where('purchase_status_from_owner', 1201)
            ->where('is_active', 1)->count();

        // Total POs ever created
        $total_po = PurchaseOrderModel::where('is_active', 1)->count();

        // Gatepasses generated (material arrived at gate)
        $gatepasses_count = DB::table('gatepass')->where('is_deleted', 0)->count();

        // GRNs generated (quality checked)
        $grn_count = DB::table('grn_tbl')->where('is_deleted', 0)->count();

        // Total PO committed value
        $total_po_value = (float) DB::table('purchase_order_details')
            ->join('purchase_orders', 'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
            ->where('purchase_orders.is_deleted', 0)
            ->selectRaw('SUM(purchase_order_details.quantity * purchase_order_details.rate) as total')
            ->value('total');

        // Total GRN accepted value
        $total_grn_value = (float) DB::table('tbl_grn_po_quantity_tracking')
            ->join('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
            ->where('tbl_grn_po_quantity_tracking.is_deleted', 0)
            ->selectRaw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity * purchase_order_details.rate) as total')
            ->value('total');

        // Master data
        $vendor_list = Vendors::where('is_active', 1)->count();
        $part_item   = PartItem::where('is_active', 1)->count();

        return [
            'purchase_dept_counts' => [
                // pipeline
                'requisitions_pending'       => $requisitions_pending,
                'po_pending_owner'           => $po_pending_owner,
                'po_approved_pending_send'   => $po_approved_pending_send,
                'po_sent_to_vendor'          => $po_sent_to_vendor,
                'po_rejected'                => $po_rejected,
                'total_po'                   => $total_po,
                'gatepasses_count'           => $gatepasses_count,
                'grn_count'                  => $grn_count,
                // financial
                'total_po_value'             => $total_po_value,
                'total_grn_value'            => $total_grn_value,
                // master data (kept for reference)
                'vendor_list'                => $vendor_list,
                'part_item'                  => $part_item,
                // keep old keys so nothing else breaks
                'BOM_recived_for_purchase'   => $total_po,
                'tax'                        => Tax::where('is_active', 1)->count(),
                'purchase_order_approved'    => $po_approved_pending_send,
                'purchase_order_submited_by_vendor' => $po_sent_to_vendor,
            ]
        ];
    }
}
