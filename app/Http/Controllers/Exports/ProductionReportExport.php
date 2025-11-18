<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductionReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;  // only query builder
    }

    public function collection()
    {
        $counter = 1;

        return $this->data->get()->map(function ($item) use (&$counter) {

            // Safe date formatting
            $updatedAt = $item->updated_at
                ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y h:i:s A')
                : '';

            $dispatchDate = $item->dispatch_updated_at
                ? \Carbon\Carbon::parse($item->dispatch_updated_at)->format('d/m/Y h:i:s A')
                : '';

            return [
                $counter++,  // 🔥 Serial Number
                $updatedAt,
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
                $dispatchDate,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr No.',        // 🔥 Serial number heading
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
            'Dispatch Date',
        ];
    }
}
