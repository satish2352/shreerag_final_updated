<?php

namespace App\Http\Controllers\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemWiseVendorRateReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;  // can be Query, Collection, Array
    }

    /**
     * Normalize data to collection
     */
    public function collection()
    {
        // Query Builder → get()
        if (is_object($this->data) && method_exists($this->data, 'get') && !($this->data instanceof Collection)) {
            return $this->data->get();
        }

        // Array or already Collection
        return collect($this->data);
    }

    /**
     * Row Mapping (Formatting)
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        // Convert array → object
        if (is_array($item)) {
            $item = (object)$item;
        }

        // Format Date
        $date = '';
        if (!empty($item->updated_at)) {
            try {
                $date = Carbon::parse($item->updated_at)->format('d-m-Y h:i:s A');
            } catch (\Exception $e) {
                $date = $item->updated_at;
            }
        }

        return [
            $index,
            $date,
            $item->purchase_orders_id ?? '-',
            $item->description ?? '-',
            ucwords($item->vendor_name ?? '-'),
            ucwords($item->vendor_company_name ?? '-'),
            $item->rate ?? '-',
        ];
    }

    /**
     * Headings
     */
    public function headings(): array
    {
        return [
            'Sr. No.',
            'Date',
            'PO Number',
            'Entry No / Particulars',
            'Vendor Name',
            'Vendor Company Name',
            'Rate'
        ];
    }

    /**
     * Apply Common Styles
     */
    public function styles(Worksheet $sheet)
    {
        applyExcelCommonStyles(
            $sheet,
            $this->headings(),
            $this->collection()->count()
        );

        return [];
    }
}
