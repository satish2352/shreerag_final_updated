<?php
namespace App\Http\Controllers\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsumptionReport implements FromCollection, WithHeadings
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
                $item['project_name'],
                $item['customer_po_number'],
                $item['title'],
                $item['product_name'],
                $item['quantity'],
                 
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Project Name',
            'Customer PO Number',
            'title',
            'Product Name',
            'Quantity',
        ];
    }
}
