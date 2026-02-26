<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsumptionReport implements FromCollection, WithHeadings
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
                $item['project_name'] ?? '-',
                $item['customer_po_number'] ?? '-',
                $item['product_name'] ?? '-',
                $item['title'] ?? '-',
                $item['quantity'] ?? '-',
                $item['updated_at'] ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Project Name',
            'PO Number',
            'Product Name',
            'Title',
            'Quantity',
            'Date',
        ];
    }
}
