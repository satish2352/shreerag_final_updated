<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FiananceExportReport implements FromCollection, WithHeadings
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
                $item['updated_at'] ?? '-',   // use last_updated_at from repo
                $item['project_name'] ?? '-',
                $item['customer_po_number'] ?? '-',
                $item['title'] ?? '-',
                $item['product_name'] ?? '-',
                $item['quantity'] ?? '-',
                $item['cumulative_completed_quantity'] ?? '-',
                $item['remaining_quantity'] ?? '-',
                $item['from_place'] ?? '-',
                $item['to_place'] ?? '-',
                $item['transport_name'] ?? '-',
                $item['vehicle_name'] ?? '-',
                 $item['truck_no'] ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Date',
            'Project Name',
            'Customer PO Number',
            'Customer Name',
            'Product Name',
            'Quantity',
            'Completed Quantity',
            'Balance Quantity',
            'Form Place',
            'To Place',
            'Transport Name',
            'Vehicle Type',
            'Truck No.',

        ];
    }
}
