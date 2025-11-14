<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: A3 landscape;
            margin: 10px;
        }
        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 11px;
            word-wrap: break-word;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        body {
            font-family: sans-serif;
        }
        h3 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<h3 style="text-align:center;">Item Stock Report</h3>

<table>
    <thead>
        <tr>
            <th style="width:40px">Sr.No</th>
            <th style="width:100px">Transaction Date</th>
            <th>Entry No / Particulars</th>
            <th style="width:80px">Received Qty</th>
            <th style="width:80px">Issue Qty</th>
            <th style="width:80px">Balance Qty</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($data as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
           <td>
                {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y h:i:s A') }}
            </td>

            <td>{{ ucwords($row->part_name) }}</td>
            <td>{{ $row->received_qty }}</td>
            <td>{{ $row->issue_qty }}</td>
            <td>
    @if(isset($row->balance))
        {{ $row->balance < 0 ? '-' . abs($row->balance) : $row->balance }}
    @else
        -
    @endif
</td>

        </tr>
        @endforeach

        {{-- TOTAL ROW --}}
        <tr style="font-weight:bold; background:#f2f2f2;">
            <td colspan="3" style="text-align:right;">Total:</td>
            <td>{{ $totals['received'] }}</td>
            <td>{{ $totals['issue'] }}</td>
            <td>{{ $totals['balance'] }}</td>
        </tr>

    </tbody>
</table>

</body>
</html>
