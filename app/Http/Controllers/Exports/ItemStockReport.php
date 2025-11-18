<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemStockReport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        $serial = 1; // Start serial number

        return $this->data->map(function ($item) use (&$serial) {
            return [
                'serial_no'   => $serial++,
                'updated_at'  => isset($item['updated_at'])
                    ? date('d-m-Y H:i:s', strtotime($item['updated_at']))
                    : '-',
                'description' => $item['description'] ?? '-',
                'quantity'    => $item['quantity'] ?? '-',
                'unit_name'   => $item['unit_name'] ?? '-',
                'hsn_name'    => $item['hsn_name'] ?? '-',
                'group_name'  => $item['group_name'] ?? '-',
                'rack_name'   => $item['rack_name'] ?? '-',

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr No',
            'Date At',
            'Item Name',
            'Stock',
            'Unit Name',
            'HSN Name',
            'Group Name',
            'Rack Name',

        ];
    }
}
