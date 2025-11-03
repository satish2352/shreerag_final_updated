<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorPaymentReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data->map(function ($item, $index) {
            return [
                'Sr No.' => $index + 1,
                'Payment Release Date' => $item['updated_at'] ?? '-',
                'Purchase Order No.' => $item['purchase_orders_id'] ?? '-',
                'GRN No.' => $item['grn_no_generate'] ?? '-',
                'Payment Status' => $item['grn_status_sanction'] ?? '-',
                'Vendor Name' => $item['vendor_name'] ?? '-',
                'Vendor Company Name' => $item['vendor_company_name'] ?? '-',
                'Vendor Email' => $item['vendor_email'] ?? '-',
                'Contact No.' => $item['contact_no'] ?? '-',
                'PO Number' => $item['purchase_orders_id'] ?? '-',
                'Invoice Date' => $item['invoice_date'] ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr No.',
            'Payment Release Date',
            'Purchase Order No.',
            'GRN No.',
            'Payment Status',
            'Vendor Name',
            'Vendor Company Name',
            'Vendor Email',
            'Contact No.',
            'PO Number',
            'Invoice Date',
        ];
    }
}
