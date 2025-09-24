<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DispatchExportReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data->values()->map(function ($item, $index) {
            return [
                $index + 1,  // ✅ Sr. No.
                $item['last_updated_at'] ?? '-',   // use last_updated_at from repo
                $item['project_name'] ?? '-',
                $item['customer_po_number'] ?? '-',
                $item['title'] ?? '-',
                $item['product_name'] ?? '-',
                $item['quantity'] ?? '-',
                $item['total_completed_quantity'] ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Dispatch Completed Date',
            'Project Name',
            'Customer PO Number',
            'Customer Name',
            'Product Name',
            'Quantity',
            'Completed Quantity',
        ];
    }
}
