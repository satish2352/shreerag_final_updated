<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class VendorThroughTakenMaterialListDetailsReport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        $rows = $this->data->values()->map(function ($item, $index) {
            return [
                $index + 1,
                isset($item->updated_at) ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y') : '-',
                $item->purchase_order_id             ?? '-',
                $item->part_number                   ?? '-',
                $item->part_description              ?? '-',
                number_format((float)($item->max_quantity ?? 0), 2),
                number_format((float)($item->sum_actual_quantity ?? 0), 2),
                number_format((float)($item->tracking_accepted_quantity ?? 0), 2),
                number_format((float)($item->tracking_rejected_quantity ?? 0), 2),
                number_format((float)($item->remaining_quantity ?? 0), 2),
                $item->unit_name                     ?? '-',
                number_format((float)($item->po_rate ?? 0), 2),
                number_format((float)($item->po_discount ?? 0), 2) . '%',
            ];
        });

        // Total row
        $rows->push([
            '', 'Total', '', '', '',
            number_format((float) $this->data->sum('max_quantity'), 2),
            number_format((float) $this->data->sum('sum_actual_quantity'), 2),
            number_format((float) $this->data->sum('tracking_accepted_quantity'), 2),
            number_format((float) $this->data->sum('tracking_rejected_quantity'), 2),
            number_format((float) $this->data->sum('remaining_quantity'), 2),
            '', '', '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Date',
            'PO ID',
            'Part Number',
            'Item Name',
            'PO Quantity',
            'Actual Quantity (Sum)',
            'Accepted Quantity',
            'Rejected Quantity',
            'Remaining Quantity',
            'Unit',
            'Rate',
            'Discount',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $totalRow = $this->data->count() + 2; // +1 heading +1 for 1-based index
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle("A{$totalRow}:M{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'],
                    ],
                ]);
            },
        ];
    }
}
