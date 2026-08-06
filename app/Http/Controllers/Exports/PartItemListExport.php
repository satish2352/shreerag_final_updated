<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PartItemListExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->data as $index => $item) {
            $rows->push([
                $index + 1,
                $item->part_number ?: '-',
                $item->description ?: '-',
                $item->extra_description ?: '-',
                $item->name ?: '-',
                $item->hsn_name ?: '-',
                $item->group_name ?: '-',
                $item->rack_name ?: '-',
                is_numeric($item->basic_rate) ? number_format((float) $item->basic_rate, 2) : '-',
                is_numeric($item->opening_stock) ? number_format((float) $item->opening_stock, 2) : '-',
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Sr.No',
            'Part Number',
            'Description',
            'Extra Description',
            'Unit',
            'HSN',
            'Group',
            'Rack No.',
            'Basic Rate',
            'Opening Stock',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->count()
        );

        return [];
    }
}
