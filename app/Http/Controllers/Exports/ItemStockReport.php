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
        return $this->data->map(function ($item) {
            return [
                $item['description'] ?? '-',
                $item['quantity'] ?? '-',
                $item['unit_name'] ?? '-',
                $item['hsn_name'] ?? '-',
                $item['group_name'] ?? '-',
                $item['rack_name'] ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Stock',
            'Unit Name',
            'HSN Name',
            'Group Name',
            'Rack Name',
        ];
    }
}
