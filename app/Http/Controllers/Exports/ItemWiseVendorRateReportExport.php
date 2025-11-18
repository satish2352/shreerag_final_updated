<?php

namespace App\Http\Controllers\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemWiseVendorRateReportExport implements FromCollection, WithHeadings
{
    protected $data;

    /**
     * $data can be:
     *  - an Eloquent\Builder (query) => call ->get()
     *  - an Eloquent/Support Collection => use as-is
     *  - an array => collect()
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Return an \Illuminate\Support\Collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // normalize to a Collection
        if (is_object($this->data) && method_exists($this->data, 'get') && !($this->data instanceof Collection)) {
            // probably a query builder / eloquent builder
            $rows = $this->data->get();
        } else {
            // already a collection or array
            $rows = collect($this->data);
        }

        // map to export rows
        $exportRows = $rows->map(function ($item) {
            // if $item is array, convert to object-like access
            if (is_array($item)) {
                $it = (object) $item;
            } else {
                $it = $item;
            }

            // format date (if present)
            $date = '';
            if (!empty($it->updated_at)) {
                try {
                    $date = Carbon::parse($it->updated_at)->format('d-m-Y h:i:s A');
                } catch (\Exception $e) {
                    $date = $it->updated_at;
                }
            }

            return [
                $date,
                $it->purchase_orders_id ?? '',
                $it->description ?? '',
                $it->vendor_name ?? '',
                $it->vendor_company_name ?? '',
                $it->rate ?? '',
            ];
        });

        // ensure it's a simple Collection of arrays
        return collect($exportRows->values()->all());
    }

    public function headings(): array
    {
        return [
            'Date',
            'PO Number',
            'Entry No / Particulars',
            'Vendor Name',
            'Vendor Company Name',
            'Rate'
        ];
    }
}
