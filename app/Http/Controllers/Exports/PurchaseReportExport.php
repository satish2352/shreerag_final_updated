<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    /**
     * Return raw data (no formatting here)
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Apply formatting and mapping for each row
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $item['project_name']              ?? '-',
            $item['customer_po_number']        ?? '-',
            ucwords($item['product_name']      ?? '-'),
            $item['description']               ?? '-',
            $item['purchase_order_id']         ?? '-',
            ucwords($item['vendor_name']       ?? '-'),
            ucwords($item['vendor_company_name'] ?? '-'),
            $item['vendor_email']              ?? '-',
            $item['contact_no']                ?? '-',
        ];
    }

    /**
     * Excel column headings
     */
    public function headings(): array
    {
        return [
            'Sr. No.',
            'Project Name',
            'Customer PO Number',
            'Product Name',
            'Description',
            'Purchase Order ID',
            'Vendor Name',
            'Company Name',
            'Email',
            'Phone',
        ];
    }

    /**
     * Apply common Excel styling from helper
     */
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
