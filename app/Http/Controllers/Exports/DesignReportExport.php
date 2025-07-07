<?php
namespace App\Http\Controllers\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseReportExport implements FromCollection, WithHeadings
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
                $item['project_name'],
                $item['customer_po_number'],
                $item['product_name'],
                $item['description'],
                $item['purchase_order_id'],
                $item['vendor_name'],
                $item['vendor_company_name'],
                $item['vendor_email'],
                $item['contact_no'],
            ];
        });
    }

    public function headings(): array
    {
        return [
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
}
