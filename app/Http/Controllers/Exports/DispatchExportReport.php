<?php

namespace App\Http\Controllers\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DispatchExportReport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
     * Mapping For Each Row (Formatting Applied)
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        // Format date
        $formattedDate = '-';
        if (!empty($item['last_updated_at'])) {
            try {
                $formattedDate = Carbon::parse($item['last_updated_at'])->format('d-m-Y h:i:s A');
            } catch (\Exception $e) {
                $formattedDate = $item['last_updated_at'];
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
            $item['total_completed_quantity'] ?? '-',
        ];
    }

    /**
     * Excel Sheet Headings
     */
    public function headings(): array
    {
        return [
            'Sr. No.',
            'Dispatch Completed Date',
            'Project Name',
            'Customer PO Number',
            'Customer Name',
            'Product Name',
            'Quantity',
            'Completed Quantity',
        ];
    }

    /**
     * Apply Global Styling (Helper)
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
