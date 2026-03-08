<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A3 landscape;
            margin: 10px;
        }

        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            word-wrap: break-word;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h3>Item Stock Report</h3>

    <table>
        <thead>
            <tr>
                <th style="width:40px">Sr.No</th>
                <th style="width:120px">Transaction Date</th>
                <th>Entry No / Particulars</th>
                <th style="width:90px">Received Qty</th>
                <th style="width:90px">Issue Qty</th>
                <th style="width:90px">Balance Qty</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($data as $index => $row)
                @php
                    $particulars = '-';

                    if ($row->received_qty > 0) {
                        if ($row->grn_no === 'Opening Stock') {
                            $particulars = 'Opening Stock | ' . $row->part_name;
                        } else {
                            $particulars =
                                'Supplier GRN No.' . $row->grn_no . ' | ' . $row->vendor_name . ' | ' . $row->part_name;
                        }
                    } elseif ($row->issue_qty > 0) {
                        if ($row->product_name === 'Delivery Challan No.') {
                            $particulars = 'DELIVERY CHALLAN ISSUE | ' . $row->part_name;
                        } else {
                            $particulars = 'FOR PRODUCTION ISSUE ' . $row->product_name . ' ' . $row->part_name;
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}
                    </td>

                    <td>{{ $particulars }}</td>

                    <td class="text-right">
                        {{ number_format($row->received_qty, 2) }}
                    </td>

                    <td class="text-right">
                        {{ number_format($row->issue_qty, 2) }}
                    </td>

                    <td class="text-right">
                        @if (isset($row->balance))
                            {{ number_format($row->balance, 2) }}
                        @else
                            -
                        @endif
                    </td>

                </tr>
            @endforeach

            {{-- TOTAL ROW --}}

            <tr style="font-weight:bold;background:#f2f2f2;">
                <td colspan="3" class="text-right">Total</td>
                <td class="text-right">{{ number_format($totals['received'], 2) }}</td>
                <td class="text-right">{{ number_format($totals['issue'], 2) }}</td>
                <td class="text-right">{{ number_format($totals['balance'], 2) }}</td>
            </tr>

        </tbody>
    </table>

</body>

</html>
