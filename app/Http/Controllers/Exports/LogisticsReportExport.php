<?php

namespace App\Http\Controllers\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LogisticsReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    /**
     * Raw data
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Map each row with formatting
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        // Format date
        $formattedDate = '-';
        if (!empty($item['updated_at'])) {
            try {
                $formattedDate = Carbon::parse($item['updated_at'])->format('d-m-Y h:i:s A');
            } catch (\Exception $e) {
                $formattedDate = $item['updated_at'];
            }
        }

        return [
            $index,
            $formattedDate,
            ucwords($item['project_name'] ?? '-'),
            $item['customer_po_number'] ?? '-',
            ucwords($item['title'] ?? '-'),
            ucwords($item['product_name'] ?? '-'),
            $item['quantity'] ?? '-',
            $item['completed_quantity'] ?? '-',
            $item['remaining_quantity'] ?? '-',
            ucwords($item['from_place'] ?? '-'),
            ucwords($item['to_place'] ?? '-'),
            ucwords($item['transport_name'] ?? '-'),
            ucwords($item['vehicle_name'] ?? '-'),
            $item['truck_no'] ?? '-',
        ];
    }

    /**
     * Excel column headings
     */
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
            'Completed Production',
            'Balance Quantity',
            'From Place',
            'To Place',
            'Transport Name',
            'Vehicle Type',
            'Truck No',
        ];
    }

    /**
     * Apply Excel styles using your helper
     */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->count()
        );

        return [];
    }
}
