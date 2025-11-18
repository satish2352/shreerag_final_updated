<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorThroughTakenMaterialReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        $serial = 1; // Start serial number

        return $this->data->map(function ($item) use (&$serial) {
            return [
                'serial_no'            => $serial++,
                'latest_update'           => isset($item['latest_update'])
                    ? date('d-m-Y H:i:s', strtotime($item['latest_update']))
                    : '-',
                'vendor_name'          => $item['vendor_name'] ?? '-',
                'vendor_company_name'  => $item['vendor_company_name'] ?? '-',
                'vendor_email'         => $item['vendor_email'] ?? '-',
                'contact_no'           => $item['contact_no'] ?? '-',

            ];
        });
    }

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
}
