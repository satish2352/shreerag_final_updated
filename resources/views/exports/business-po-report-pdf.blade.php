<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A3 landscape;
            margin: 12px;
        }

        body {
            font-family: sans-serif;
            font-size: 9px;
        }

        h3 {
            text-align: center;
            margin: 0 0 4px 0;
            font-size: 15px;
        }

        .meta {
            font-size: 9px;
            margin-bottom: 6px;
            color: #333;
        }

        .meta span {
            margin-right: 12px;
            white-space: nowrap;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 9px;
            word-wrap: break-word;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
            text-align: left;
            vertical-align: top;
        }

        thead th {
            background: #1a3a6b;
            color: #fff;
        }

        .num {
            text-align: right;
        }

        .biz-head td {
            background: #e8f0fe;
            font-weight: bold;
        }

        .po-sub td {
            background: #fff8e1;
            font-weight: bold;
        }

        .biz-total td {
            background: #d4edda;
            font-weight: bold;
        }

        .grand td {
            background: #1a3a6b;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <h3>Business PO Report</h3>

    <div class="meta">
        <span><strong>Generated On:</strong> {{ now()->format('d-m-Y H:i') }}</span>
        @if(!empty($filters))
            @foreach($filters as $label => $value)
                <span><strong>{{ $label }}:</strong> {{ $value }}</span>
            @endforeach
        @else
            <span><strong>Filters:</strong> All records (no filter applied)</span>
        @endif
        <span><strong>Total Rows:</strong> {{ count($rows) }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%;">Sr.</th>
                <th style="width:9%;">Business / Project</th>
                <th style="width:7%;">PO Number</th>
                <th style="width:5%;">PO Date</th>
                <th style="width:8%;">Product Name</th>
                <th style="width:8%;">Vendor Name</th>
                <th style="width:8%;">Vendor Company</th>
                <th style="width:6%;">GRN No</th>
                <th style="width:5%;">GRN Date</th>
                <th style="width:12%;">Material Description</th>
                <th style="width:4%;" class="num">PO Qty</th>
                <th style="width:4%;" class="num">Actual Qty</th>
                <th style="width:4%;" class="num">Accepted Qty</th>
                <th style="width:4%;" class="num">Rejected Qty</th>
                <th style="width:4%;">Unit</th>
                <th style="width:4%;" class="num">Rate</th>
                <th style="width:5%;" class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $rows = collect($rows); @endphp

            @if($rows->isEmpty())
                <tr>
                    <td colspan="17" style="text-align:center; padding:15px;">No records found for the selected filters.</td>
                </tr>
            @else
                @php $sr = 1; @endphp
                @foreach($rows->groupBy('business_id') as $bizRows)
                    @php
                        $bizName  = $bizRows->first()->project_name;
                        $bizTotal = $bizRows->sum(fn($r) => (float) $r->line_amount);
                        $poGroups = $bizRows->groupBy('purchase_orders_id');
                    @endphp

                    <tr class="biz-head">
                        <td colspan="17">
                            {{ ucwords($bizName) }}
                            — {{ $poGroups->count() }} PO{{ $poGroups->count() > 1 ? 's' : '' }}
                        </td>
                    </tr>

                    @foreach($poGroups as $poId => $poRows)
                        @foreach($poRows as $row)
                            <tr>
                                <td>{{ $sr++ }}</td>
                                <td>{{ ucwords((string) $row->project_name) }}</td>
                                <td>{{ $row->purchase_orders_id }}</td>
                                <td>{{ $row->po_date ? \Carbon\Carbon::parse($row->po_date)->format('d-m-Y') : '—' }}</td>
                                <td>{{ ucwords((string) $row->product_name) }}</td>
                                <td>{{ ucwords((string) $row->vendor_name) }}</td>
                                <td>{{ ucwords((string) $row->vendor_company_name) }}</td>
                                <td>{{ $row->grn_no_generate ?? '—' }}</td>
                                <td>{{ $row->grn_date ? \Carbon\Carbon::parse($row->grn_date)->format('d-m-Y') : '—' }}</td>
                                <td>{{ $row->material_description ?? '—' }}</td>
                                <td class="num">{{ $row->po_quantity }}</td>
                                <td class="num">{{ is_null($row->actual_quantity) ? '—' : $row->actual_quantity }}</td>
                                <td class="num">{{ is_null($row->accepted_quantity) ? '—' : $row->accepted_quantity }}</td>
                                <td class="num">{{ is_null($row->rejected_quantity) ? '—' : $row->rejected_quantity }}</td>
                                <td>{{ $row->unit_name ?? '—' }}</td>
                                <td class="num">{{ number_format((float) $row->rate, 2) }}</td>
                                <td class="num">{{ number_format((float) $row->line_amount, 2) }}</td>
                            </tr>
                        @endforeach

                        <tr class="po-sub">
                            <td colspan="16" class="num">PO {{ $poId }} — Subtotal</td>
                            <td class="num">{{ number_format($poRows->sum(fn($r) => (float) $r->line_amount), 2) }}</td>
                        </tr>
                    @endforeach

                    <tr class="biz-total">
                        <td colspan="16" class="num">{{ ucwords($bizName) }} — Total</td>
                        <td class="num">{{ number_format($bizTotal, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="grand">
                <td colspan="16" class="num">Grand Total</td>
                <td class="num">{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
