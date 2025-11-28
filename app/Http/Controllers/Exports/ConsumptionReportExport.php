<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConsumptionReportExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    /**
     * Raw data to be exported
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            (string) ($item['date'] ?? '-'),
            ucwords($item['project_name'] ?? '-'),
            "'" . ($item['customer_po_number'] ?? '-'),   // Avoid scientific notation
            ucwords($item['title'] ?? '-'),
            ucwords($item['product_name'] ?? '-'),
            $item['quantity'] ?? '-',
        ];
    }

    /**
     * Headings
     */
    public function headings(): array
    {
        return [
            'Sr. No.',
            'Date',
            'Project Name',
            'PO Number',
            'Title',
            'Product Name',
            'Quantity',
        ];
    }

    /**
     * Apply styles (using your helper)
     */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet, 
            $this->headings(), 
            $this->data->count()
        );
    }
}
