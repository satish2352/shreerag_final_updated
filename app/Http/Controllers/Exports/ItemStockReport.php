<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemStockReport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($item): array
    {
        static $serial = 1;

        return [
            $serial++,
            isset($item['updated_at'])
                ? date('d-m-Y H:i:s', strtotime($item['updated_at']))
                : '-',

            $item['description'] ?? '-',   // Item Name (Wrap this)

            (string) ($item['quantity'] ?? '0'),
            $item['unit_name'] ?? '-',
            $item['hsn_name'] ?? '-',
            $item['group_name'] ?? '-',
            $item['rack_name'] ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Sr No',
            'Date At',
            'Item Name',
            'Stock',
            'Unit Name',
            'HSN Name',
            'Group Name',
            'Rack Name',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold heading
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Wrap text for Item Name column (Column C)
        $sheet->getStyle('C')->getAlignment()->setWrapText(true);

        // Auto row height for wrapping
        foreach ($sheet->getRowDimensions() as $row) {
            $row->setRowHeight(-1);
        }
    }
}
