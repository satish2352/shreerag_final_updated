<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeLeaveExportReport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($item): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            trim(($item->f_name ?? '') . ' ' . ($item->m_name ?? '') . ' ' . ($item->l_name ?? '')),
            $item->year ?? '-',

            $item->opening_cl ?? 0,
            $item->opening_pl ?? 0,
            $item->opening_sl ?? 0,

            $item->used_cl ?? 0,
            $item->used_pl ?? 0,
            $item->used_sl ?? 0,

            $item->closed_cl ?? 0,
            $item->closed_pl ?? 0,
            $item->closed_sl ?? 0,
        ];
    }


    public function headings(): array
    {
        return [
            'Sr No',
            'Employee Name',
            'Year',

            'Opening CL',
            'Opening PL',
            'Opening SL',

            'Used CL',
            'Used PL',
            'Used SL',

            'Closed CL',
            'Closed PL',
            'Closed SL',
        ];
    }

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
