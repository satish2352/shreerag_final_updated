<?php

namespace App\Http\Controllers\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SecurityReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    /**
     * Return raw data
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Format each row
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        // Format date
        $formattedDate = '-';
        if (!empty($item['date'])) {
            try {
                $formattedDate = Carbon::parse($item['date'])->format('d-m-Y h:i:s A');
            } catch (\Exception $e) {
                $formattedDate = $item['date'];
            }
        }

        return [
            $index,
            $formattedDate,
            ucwords($item['vendor_name'] ?? '-'),
            $item['purchase_orders_id'] ?? '-',
            $item['gatepass_name'] ?? '-',
            $item['remark'] ?? '-',
        ];
    }

    /**
     * Excel headings
     */
    public function headings(): array
    {
        return [
            'Sr. No.',
            'Date',
            'Vendor Name',
            'PO Number',
            'Gatepass Name',
            'Remarks',
        ];
    }

    /**
     * Apply global Excel styling
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
