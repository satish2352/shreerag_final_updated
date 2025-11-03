<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DesignReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data); // Ensure it's a collection
    }

    public function collection()
    {
        return $this->data->map(function ($item, $index) {
            // ✅ Convert production_status_id into readable text
            $status = '-';
            switch ($item['production_status_id']) {
                case 1115:
                case 1117:
                case 1121:
                    $status = 'Accepted';
                    break;
                case 1114:
                    $status = 'Rejected';
                    break;
                default:
                    $status = $item['production_status_id'] ?? '-';
                    break;
            }

            return [
                $index + 1, // Sr. No.
                $item['updated_at'] ?? '-',
                $status,
                $item['project_name'] ?? '-',
                $item['customer_po_number'] ?? '-',
                $item['product_name'] ?? '-',
                $item['description'] ?? '-',
                $item['quantity'] ?? '-', // fixed spelling: was 'descripquantitytion'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Date',
            'Status',
            'Project Name',
            'Customer PO Number',
            'Product Name',
            'Description',
            'Quantity',
        ];
    }
}
