<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CompleteProductReportExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    /**
     * Raw data
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
        return [
            (string) ($item['updated_at'] ?? ''),
            ucwords($item['project_name'] ?? ''),
           (string) ($item['customer_po_number'] ?? ''),     // NO SINGLE QUOTE
            ucwords($item['title'] ?? ''),         // Customer Name
            ucwords($item['product_name'] ?? ''),
            (string) ($item['quantity'] ?? '0'),
            (string) ($item['total_completed_quantity'] ?? '0'),
            (string) ($item['total_estimation_amount'] ?? '0'),
            (string) ($item['total_items_used_amount'] ?? '0'),
        ];
    }

    /**
     * Excel headings
     */
    public function headings(): array
    {
        return [
            'Date',
            'Project Name',
            'Customer PO Number',
            'Customer Name',
            'Product Name',
            'Total Product Quantity',
            'Total Production Done Quantity',
            'Estimated Amount',
            'Actual Amount',
        ];
    }

    /**
     * Prevent Excel from converting long numbers to scientific format
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,  // Customer PO Number column
        ];
    }

    /**
     * Apply common Excel styles
     */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles($sheet, $this->headings(), $this->data->count());
    }
}
