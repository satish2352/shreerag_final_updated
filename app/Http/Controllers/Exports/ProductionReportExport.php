<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductionReportExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping,
    WithStyles, 
    ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data; // Query builder
    }

    /** Raw Data */
    public function collection()
    {
        return $this->data->get();
    }

    /** Format each row */
    public function map($item): array
    {
        static $serial = 1;

        $updatedAt = $item->updated_at
            ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y h:i:s A')
            : '-';

        $dispatchDate = $item->dispatch_updated_at
            ? \Carbon\Carbon::parse($item->dispatch_updated_at)->format('d/m/Y h:i:s A')
            : '-';

        return [
            $serial++,                                      // Sr No
            $updatedAt,
            ucwords($item->project_name ?? '-'),
            "'" . ($item->customer_po_number ?? '-'),       // avoid scientific notation
            ucwords($item->product_name ?? '-'),
            $item->description ?? '-',
            $item->quantity ?? '-',
            $item->cumulative_completed_quantity ?? '-',
            $item->remaining_quantity ?? '-',
            ucwords($item->from_place ?? '-'),
            ucwords($item->to_place ?? '-'),
            $item->gate_entry ?? '-',
            $item->dispatch_remark ?? '-',
            $dispatchDate,
        ];
    }

    /** Excel Headings */
    public function headings(): array
    {
        return [
            'Sr No.',
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

    /** Apply global styles */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->get()->count()
        );
    }
}
