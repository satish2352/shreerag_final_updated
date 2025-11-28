<?php

namespace App\Http\Controllers\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VendorPaymentReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    /**
     * Return raw data
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Format each row
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        // Format payment release date
        $paymentDate = '-';
        if (!empty($item['updated_at'])) {
            try {
                $paymentDate = Carbon::parse($item['updated_at'])->format('d-m-Y h:i:s A');
            } catch (\Exception $e) {
                $paymentDate = $item['updated_at'];
            }
        }

        // Format invoice date
        $invoiceDate = '-';
        if (!empty($item['invoice_date'])) {
            try {
                $invoiceDate = Carbon::parse($item['invoice_date'])->format('d-m-Y');
            } catch (\Exception $e) {
                $invoiceDate = $item['invoice_date'];
            }
        }

        return [
            $index,
            $paymentDate,
            $item['purchase_orders_id'] ?? '-',
            $item['grn_no_generate'] ?? '-',
            $item['grn_status_sanction'] ?? '-',
            ucwords($item['vendor_name'] ?? '-'),
            ucwords($item['vendor_company_name'] ?? '-'),
            $item['vendor_email'] ?? '-',
            $item['contact_no'] ?? '-',
            $item['purchase_orders_id'] ?? '-',
            $invoiceDate,
        ];
    }

    /**
     * Excel headings
     */
    public function headings(): array
    {
        return [
            'Sr No.',
            'Payment Release Date',
            'Purchase Order No.',
            'GRN No.',
            'Payment Status',
            'Vendor Name',
            'Vendor Company Name',
            'Vendor Email',
            'Contact No.',
            'PO Number',
            'Invoice Date',
        ];
    }

    /**
     * Apply Excel styling using helper
     */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->data->count()
        );

        return [];
    }
}
