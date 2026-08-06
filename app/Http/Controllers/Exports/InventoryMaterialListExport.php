<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryMaterialListExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        $rows = collect();
        $totalQty = 0;

        foreach ($this->data as $index => $item) {
            // Mirrors the screen: a part with no tbl_item_stock row yet falls
            // back to its opening stock (see list-part-item.blade.php).
            $qty = is_null($item->quantity) ? $item->opening_stock : $item->quantity;
            $qty = is_numeric($qty) ? (float) $qty : 0;
            $totalQty += $qty;

            $rows->push([
                $index + 1,
                $item->description ?: '-',
                number_format($qty, 2),
                $item->name ?: '-',
                $item->hsn_name ?: '-',
                $item->group_name ?: '-',
                $item->rack_name ?: '-',
            ]);
        }

        // TOTAL ROW
        $rows->push([
            '',
            'TOTAL',
            number_format($totalQty, 2),
            '',
            '',
            '',
            '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Sr.No',
            'Description',
            'Quantity',
            'Unit',
            'HSN',
            'Group',
            'Rack No.',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->count() + 1
        );

        // TOTAL row bold
        $totalRow = $this->data->count() + 2;

        $sheet->getStyle("A{$totalRow}:G{$totalRow}")
            ->getFont()
            ->setBold(true);

        return [];
    }
}
