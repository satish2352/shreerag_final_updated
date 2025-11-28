<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GRNReportExport implements 
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
     * Return raw data
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Map each row (fix scientific notation + formatting)
     */
    public function map($item): array
    {
        return [
            (string) $item['updated_at'],
            "'" . $item['purchase_orders_id'],   // Prevent scientific notation
            "'" . $item['grn_no_generate'],      // Prevent scientific notation
            ucwords($item['vendor_name']),
            ucwords($item['vendor_company_name']),
        ];
    }

    /**
     * Excel headings
     */
    public function headings(): array
    {
        return [
            'Date',
            'PO Number',
            'GRN No.',
            'Vendor Name',
            'Vendor Company Name',
        ];
    }

    /**
     * Apply common styles using helper function
     */
    public function styles(Worksheet $sheet)
    {
        // Calls the helper function you added earlier
        applyExcelCommonStyles($sheet, $this->headings(), $this->data->count());
    }
}
