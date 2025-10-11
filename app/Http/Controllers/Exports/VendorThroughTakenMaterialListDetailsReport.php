<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorThroughTakenMaterialListDetailsReport implements FromCollection, WithHeadings
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
                $item['purchase_order_id'] ?? '-',
                $item['part_number'] ?? '-',
                $item['part_description'] ?? '-',
                $item['max_quantity'] ?? '-',
                 $item['sum_actual_quantity'] ?? '-',
                  $item['tracking_accepted_quantity'] ?? '-',
                   $item['tracking_rejected_quantity'] ?? '-',
                    $item['remaining_quantity'] ?? '-',
                 $item['unit_name'] ?? '-',
                  $item['po_rate'] ?? '-',
                   $item['po_discount'] ?? '-',
            ];
        });
    }
 
    public function headings(): array
    {
        return [
            'Purchase Order No.',
            'Vendor Company Name',
            'Vendor Email',
            'Vendor Contact Number',
            
        ];
    }
}
