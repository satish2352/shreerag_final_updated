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
            font-size: 10px;
        }

        h3 {
            text-align: center;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #d9edf7;
        }

        tfoot tr {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h3>Vendor Through Taken Material Report</h3>
    <table>
        <thead>
            <tr>
                <th style="width:4%;">Sr No.</th>
                <th style="width:8%;">Date</th>
                <th style="width:8%;">PO No.</th>
                <th style="width:10%;">Part Number</th>
                <th style="width:18%;">Item Name</th>
                <th style="width:8%;">PO Quantity</th>
                <th style="width:9%;">Actual Qty (Sum)</th>
                <th style="width:9%;">Accepted Qty</th>
                <th style="width:9%;">Rejected Qty</th>
                <th style="width:9%;">Remaining Qty</th>
                <th style="width:5%;">Unit</th>
                <th style="width:7%;">Rate</th>
                <th style="width:6%;">Discount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                @php
                    $row = is_array($item) ? $item : $item->toArray();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ isset($row['updated_at']) ? \Carbon\Carbon::parse($row['updated_at'])->format('d-m-Y') : '-' }}
                    </td>
                    <td>{{ $row['purchase_orders_id'] ?? '-' }}</td>
                    <td>{{ $row['part_number'] ?? '-' }}</td>
                    <td>{{ $row['part_description'] ?? '-' }}</td>
                    <td>{{ number_format((float) ($row['max_quantity'] ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($row['sum_actual_quantity'] ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($row['tracking_accepted_quantity'] ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($row['tracking_rejected_quantity'] ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($row['remaining_quantity'] ?? 0), 2) }}</td>
                    <td>{{ $row['unit_name'] ?? '-' }}</td>
                    <td>{{ number_format((float) ($row['po_rate'] ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($row['po_discount'] ?? 0), 2) }}%</td>
                </tr>
            @endforeach
        </tbody>
        @if (count($data) > 0)
            @php
                $dataCol = collect($data)->map(fn($i) => is_array($i) ? $i : $i->toArray());
            @endphp
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right;">Total</td>
                    <td>{{ number_format((float) $dataCol->sum('max_quantity'), 2) }}</td>
                    <td>{{ number_format((float) $dataCol->sum('sum_actual_quantity'), 2) }}</td>
                    <td>{{ number_format((float) $dataCol->sum('tracking_accepted_quantity'), 2) }}</td>
                    <td>{{ number_format((float) $dataCol->sum('tracking_rejected_quantity'), 2) }}</td>
                    <td>{{ number_format((float) $dataCol->sum('remaining_quantity'), 2) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>

</html>
