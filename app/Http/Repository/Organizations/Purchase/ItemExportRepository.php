<?php

namespace App\Http\Repository\Organizations\Purchase;

use App\Models\PartItem;

/**
 * Unpaginated feed for the Item List (purchase/list-part-item) Excel/PDF exports.
 *
 * Deliberately a separate class from ItemRepository so the export can be
 * maintained without touching the paginated listing query.
 *
 * IMPORTANT: the joins / select / search / order below are intentionally kept
 * identical to ItemRepository::getAll(), so the download matches the screen.
 * If that listing query changes, change this one to match.
 */
class ItemExportRepository
{
    public function getAllForExport()
    {
        try {
            $search = request()->search;

            return PartItem::leftJoin('tbl_unit', function ($join) {
                $join->on('tbl_part_item.unit_id', '=', 'tbl_unit.id');
            })
                ->leftJoin('tbl_hsn', function ($join) {
                    $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
                })
                ->leftJoin('tbl_group_master', function ($join) {
                    $join->on('tbl_part_item.group_type_id', '=', 'tbl_group_master.id');
                })
                ->leftJoin('tbl_rack_master', function ($join) {
                    $join->on('tbl_part_item.rack_id', '=', 'tbl_rack_master.id');
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('tbl_part_item.description', 'LIKE', "%{$search}%")
                            ->orWhere('tbl_part_item.part_number', 'LIKE', "%{$search}%")
                            ->orWhere('tbl_part_item.opening_stock', 'LIKE', "%{$search}%");
                    });
                })
                ->select(
                    'tbl_part_item.id',
                    'tbl_part_item.part_number',
                    'tbl_part_item.basic_rate',
                    'tbl_part_item.opening_stock',
                    'tbl_part_item.image',
                    'tbl_part_item.description',
                    'tbl_part_item.extra_description',
                    'tbl_part_item.unit_id',
                    'tbl_unit.name',
                    'tbl_part_item.hsn_id',
                    'tbl_hsn.name as hsn_name',
                    'tbl_part_item.group_type_id',
                    'tbl_group_master.name as group_name',
                    'tbl_rack_master.name as rack_name'
                )
                ->orderBy('tbl_part_item.updated_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            return $e;
        }
    }
}
