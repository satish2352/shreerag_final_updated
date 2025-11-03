<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductionReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;  // no collect() again
    }

    public function collection()
    {
        return $this->data->get()->map(function ($item) {
            return [
                $item->updated_at ?? '',
                $item->project_name ?? '',
                $item->customer_po_number ?? '',
                $item->product_name ?? '',
                $item->description ?? '',
                $item->quantity ?? '',
                $item->cumulative_completed_quantity ?? '',
                $item->remaining_quantity ?? '',
                $item->from_place ?? '',
                $item->to_place ?? '',
                $item->gate_entry ?? '',
                $item->dispatch_remark ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Project Name',
            'Customer PO Number',
            'Product Name',
            'Description',
            'Total Quantity',
            'Completed Quantity',
            'Remaining Quantity',
            'From Place',
            'To Place',
            'Gate Entry',
            'Dispatch Remark',
        ];
    }
}
