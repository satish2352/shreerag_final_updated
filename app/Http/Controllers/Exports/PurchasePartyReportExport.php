<?php
namespace App\Http\Controllers\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasePartyReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                $item->purchase_order_id,             
                $item->vendor_name,
                $item->vendor_company_name,
                $item->vendor_email,
                $item->contact_no,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Purchase Order ID',
            'Vendor Name',
            'Company Name',
            'Email',
            'Phone',
        ];
    }
}
