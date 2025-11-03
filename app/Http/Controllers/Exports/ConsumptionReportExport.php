<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsumptionReportExport implements FromCollection, WithHeadings
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
                $index + 1,  
                $item['project_name'] ?? '-',
                $item['customer_po_number'] ?? '-',
                $item['title'] ?? '-',
                $item['product_name'] ?? '-',
                $item['quantity'] ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
             'Sr. No.',
            'Date',
            'Project Name',
            'PO Number',
            'Title',
            'Product Name',
            'Quantity',
        ];
    }
}
