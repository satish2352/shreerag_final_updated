<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorThroughTakenMaterialReport implements FromCollection, WithHeadings
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
                $item['vendor_name'] ?? '-',
                $item['vendor_company_name'] ?? '-',
                $item['vendor_email'] ?? '-',
                $item['contact_no'] ?? '-',
            ];
        });
    }
 
    public function headings(): array
    {
        return [
            'Vendor Name',
            'Vendor Company Name',
            'Vendor Email',
            'Vendor Contact Number',
            
        ];
    }
}
