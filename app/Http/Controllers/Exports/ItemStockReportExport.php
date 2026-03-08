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

            // Date Format
            $formattedDate = $item->date
                ? Carbon::parse($item->date)->format('d/m/Y')
                : '-';

            // Particulars logic (same as Blade)
            $particulars = '-';

            if ($item->received_qty > 0) {

                if ($item->grn_no === 'Opening Stock') {
                    $particulars = "Opening Stock | " . $item->part_name;
                } else {
                    $particulars = "Supplier GRN No." . $item->grn_no .
                        " | " . $item->vendor_name .
                        " | " . $item->part_name;
                }
            } elseif ($item->issue_qty > 0) {

                if ($item->product_name === 'Delivery Challan No.') {
                    $particulars = "DELIVERY CHALLAN ISSUE | " . $item->part_name;
                } else {
                    $particulars = "FOR PRODUCTION ISSUE " .
                        $item->product_name . " " .
                        $item->part_name;
                }
            }

            $rows->push([
                $index + 1,
                $formattedDate,
                $particulars,
                number_format($item->received_qty, 2),
                number_format($item->issue_qty, 2),
                number_format($item->balance, 2),
            ]);
        }

        // TOTAL ROW
        $rows->push([
            '',
            '',
            'TOTAL',
            number_format($this->totals['received'], 2),
            number_format($this->totals['issue'], 2),
            number_format($this->totals['balance'], 2),
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
        // Apply your helper style
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->count() + 1
        );

        // TOTAL row bold
        $totalRow = $this->data->count() + 2;

        $sheet->getStyle("A{$totalRow}:F{$totalRow}")
            ->getFont()
            ->setBold(true);

        return [];
    }
}
