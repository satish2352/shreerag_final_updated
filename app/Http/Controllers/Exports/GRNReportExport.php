<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GRNReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data); // Ensure it's a collection
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                $item['updated_at'],
                $item['purchase_orders_id'],
                $item['grn_no_generate'],
                $item['vendor_name'],
                $item['vendor_company_name'],

            ];
        });
    }

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
}
