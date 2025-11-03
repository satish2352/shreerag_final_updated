<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompleteProductReportExport implements FromCollection, WithHeadings
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
                // Match these to the headings exactly
                // Format date to a readable string if needed
                isset($item['updated_at']) ? $item['updated_at'] : '',
                isset($item['project_name']) ? $item['project_name'] : '',
                // PO Number (if you have both project PO and customer PO, map correctly)
                isset($item['customer_po_number']) ? $item['customer_po_number'] : '',
                // If you have a separate PO field, put it here instead
                // Customer name (title)
                isset($item['title']) ? $item['title'] : '',
                // Product Name
                isset($item['product_name']) ? $item['product_name'] : '',
                // Total Product Quantity
                isset($item['quantity']) ? $item['quantity'] : '',
                // Total Production Done Quantity
                isset($item['total_completed_quantity']) ? $item['total_completed_quantity'] : '',
                // Estimated Amount
                isset($item['total_estimation_amount']) ? $item['total_estimation_amount'] : '',
                // Actual Amount (items used amount)
                isset($item['total_items_used_amount']) ? $item['total_items_used_amount'] : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Project Name',
            'Customer PO Number',  // mapped to customer_po_number above
            'Customer Name',       // title
            'Product Name',
            'Total Product Quantity',
            'Total Production Done Quantity',
            'Estimated Amount',
            'Actual Amount',
        ];
    }
}
