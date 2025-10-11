<?php
namespace App\Http\Controllers\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SecurityReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data); // Ensure it's a collection
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
               $item['date'],
                 $item['vendor_name'],
                $item['purchase_orders_id'],
                $item['gatepass_name'],
                $item['remark'],
            ];
        });
    }

    public function headings(): array
    {
        return [
             'Date',
            'Vendor Name',
            'PO Number',
            'Gatepass Name',
            'Remarks',
        ];
    }
}
