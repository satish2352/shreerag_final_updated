<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VendorThroughTakenMaterialReport implements 
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

    /** Map each row */
    public function map($item): array
    {
        static $serial = 1;

        $formattedDate = '-';
        if (!empty($item['latest_update'])) {
            $formattedDate = date('d-m-Y H:i:s', strtotime($item['latest_update']));
        }

        return [
            $serial++,                                  // Sr No
            $formattedDate,                             // Date
            ucwords($item['vendor_name'] ?? '-'),       // Vendor Name
            ucwords($item['vendor_company_name'] ?? '-'),
            $item['vendor_email'] ?? '-',
            "'" . ($item['contact_no'] ?? '-'),         // Prevent scientific notation
        ];
    }

    /** Excel Headings */
    public function headings(): array
    {
        return [
            'Sr No',
            'Date',
            'Vendor Name',
            'Vendor Company Name',
            'Vendor Email',
            'Vendor Contact Number',
        ];
    }

    /** Apply helper styling */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->count()
        );
    }
}
