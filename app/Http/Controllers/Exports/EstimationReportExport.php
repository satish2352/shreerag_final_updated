<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EstimationReportExport implements 
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

    /** Raw data */
    public function collection()
    {
        return $this->data;
    }

    /** Map each row for proper formatting */
    public function map($item): array
    {
        static $serial = 1;

        return [
            $serial++,                                             // Sr No
            (string) ($item['updated_at'] ?? '-'),                 // Date
            ucwords($item['project_name'] ?? '-'),                 // Project Name
            "'" . ($item['customer_po_number'] ?? '-'),            // Prevent scientific notation
            $item['remark'] ?? '-',
            ucwords($item['product_name'] ?? '-'),
            $item['quantity'] ?? '-',
            $item['description'] ?? '-',
            $item['bom_image'] ?? '-',                             // Image file name or "-"
            $item['design_image'] ?? '-',                          // Image file name or "-"
            $item['total_estimation_amount'] ?? '-',               // Amount
        ];
    }

    /** Excel Headings */
    public function headings(): array
    {
        return [
            'Sr No',
            'Date',
            'Project Name',
            'Customer PO Number',
            'Remark',
            'Product Name',
            'Quantity',
            'Description',
            'Estimated BOM',
            'Design Layout',
            'Total Estimation',
        ];
    }

    /** Apply styles using your helper */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->count()
        );
    }
}
