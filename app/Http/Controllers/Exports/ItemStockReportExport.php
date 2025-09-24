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
        return $this->data->map(function ($item) {
            return [
                $item['updated_at'] ?? '',
                $item['description'] ?? '',
                $item['received_quantity'] ?? '',
                $item['balance_quantity'] ?? '',
                $item['used_quantity'] ?? '',
               
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Item Name',
            'Received Qantity',
          'Issue Qantity',
          'Balance Qantity',
        ];
    }
}
