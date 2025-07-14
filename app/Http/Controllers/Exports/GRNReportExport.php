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
                //  $item['production_status_id'],
                // $item['project_name'],
                // $item['customer_po_number'],
                // $item['product_name'],
                // $item['description'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            // 'Status',
            // 'Project Name',
            // 'Customer PO Number',
            // 'Product Name',
            // 'Description',
        ];
    }
}
