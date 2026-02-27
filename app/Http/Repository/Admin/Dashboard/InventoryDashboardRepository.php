<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses,
    PartItem
};

class InventoryDashboardRepository
{
    public function getCounts()
    {
        $material_need_to_sent_to_production_inventory = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
            ->where('production_status_id', 1114)->where('off_canvas_status', 15)
            ->where('is_active', 1)->count();
        $part_item_inventory = PartItem::where('is_active', 1)->where('is_deleted', 0)->count();


        return [
            'inventory_dept_counts' => [

                'material_need_to_sent_to_production_inventory' => $material_need_to_sent_to_production_inventory,
                'part_item_inventory' => $part_item_inventory
            ]


        ];
    }
}
