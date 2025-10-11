<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemStockReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        $rows = $this->data->map(function ($item) {
            return [
                $item['updated_at'] ?? '',
                $item['description'] ?? '',
                $item['received_quantity'] ?? 0,
                $item['used_quantity'] ?? 0,
                $item['balance_quantity'] ?? 0,
            ];
        });

        // ✅ Calculate totals
        $totalReceived = $this->data->sum('received_quantity');
        $totalIssue = $this->data->sum('used_quantity');
        $totalBalance = $this->data->sum('balance_quantity');

        // ✅ Add total row at bottom
        $rows->push([
            '', // Date
            'Total', // Label
            $totalReceived,
            $totalIssue,
            $totalBalance,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Item Name',
            'Received Quantity',
            'Issue Quantity',
            'Balance Quantity',
        ];
    }
}
