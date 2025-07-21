<?php
namespace App\Http\Controllers\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogisticsReportExport implements FromCollection, WithHeadings
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
                $item['updated_at'],
                 $item['project_name'],
                $item['customer_po_number'],
                $item['title'],
                $item['product_name'],
                $item['quantity'],
                $item['completed_quantity'],
                $item['remaining_quantity'],
                $item['from_place'],
                $item['to_place'],
                $item['transport_name'],
                $item['vehicle_name'],
                $item['truck_no'], 
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            '>Project Name',
            'Customer PO Number',
            'customer Name',
            'Product Name',
            'Quantity',
            'Completed Production',
            'Balance Quantity',
            'Form Place',
            'To Place',
            'Transport Name',
            'Vehicle Type',
            'Truck',
        ];
    }
}
