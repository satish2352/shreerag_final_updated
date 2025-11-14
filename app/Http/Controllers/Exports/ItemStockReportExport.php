<?php

namespace App\Http\Controllers\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemStockReportExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;
    protected $totals;

    public function __construct($data, $totals)
    {
        $this->data   = collect($data);
        $this->totals = $totals;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->data as $index => $item) {

            // Proper date format
            $formattedDate = $item->date
                ? Carbon::parse($item->date)->format('d/m/Y h:i:s A')
                : '-';

            // Proper minus balance
            $balance = isset($item->balance)
                ? ($item->balance < 0 ? '-' . abs($item->balance) : $item->balance)
                : 0;

            $rows->push([
                $index + 1,
                $formattedDate,
                ucwords($item->part_name),
                $item->received_qty,
                $item->issue_qty,
                $balance,
            ]);
        }

        // Add total row
        $rows->push([
            '',
            '',
            'TOTAL',
            $this->totals['received'],
            $this->totals['issue'],
            $this->totals['balance'],
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Sr.No',
            'Transaction Date',
            'Entry No / Particulars',
            'Received Qty',
            'Issue Qty',
            'Balance Qty',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);  // header bold
        $sheet->getStyle('A' . ($this->data->count() + 2) . ':F' . ($this->data->count() + 2))->getFont()->setBold(true); // total row bold

        return [];
    }
}
