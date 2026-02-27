<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    PurchaseOrderModel,
    Vendors,
    Tax,
    PartItem
};

class PurchaseDashboardRepository
{
    public function getCounts()
    {
        $BOM_recived_for_purchase = PurchaseOrderModel::where('is_active', 1)->count();
        $vendor_list = Vendors::where('is_active', 1)->count();
        $tax = Tax::where('is_active', 1)->count();
        $part_item = PartItem::where('is_active', 1)->count();
        $purchase_order_approved = PurchaseOrderModel::where('purchase_status_from_owner', 1127)->where('purchase_status_from_purchase', 1126)
            ->where('is_active', 1)->count();
        $purchase_order_submited_by_vendor = PurchaseOrderModel::where('purchase_status_from_owner', 1129)->where('purchase_status_from_purchase', 1129)
            ->where('is_active', 1)->count();
        return [


            'purchase_dept_counts' =>  [
                'BOM_recived_for_purchase' => $BOM_recived_for_purchase,
                'vendor_list' => $vendor_list,
                'tax' => $tax,
                'part_item' => $part_item,
                'purchase_order_approved' => $purchase_order_approved,
                'purchase_order_submited_by_vendor' => $purchase_order_submited_by_vendor,
            ]
        ];
    }
}
