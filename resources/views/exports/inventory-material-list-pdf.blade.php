<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A4 landscape;
            margin: 10px;
        }

        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 10px;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .meta {
            font-size: 10px;
            margin-bottom: 6px;
        }

        tfoot td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center; margin-bottom:4px;">Inventory Material List</h3>

    <div class="meta">
        Generated on: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
        @if (!empty($search))
            &nbsp;|&nbsp; Search: <strong>{{ $search }}</strong>
        @endif
        &nbsp;|&nbsp; Total Records: <strong>{{ count($data) }}</strong>
    </div>

    @php
        $totalQty = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">Sr No.</th>
                <th style="width: 37%;">Description</th>
                <th style="width: 12%;" class="text-right">Quantity</th>
                <th style="width: 11%;">Unit</th>
                <th style="width: 12%;">HSN</th>
                <th style="width: 13%;">Group</th>
                <th style="width: 10%;">Rack No.</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                @php
                    // Mirrors the screen: fall back to opening stock when no
                    // tbl_item_stock row exists yet.
                    $qty = is_null($row->quantity) ? $row->opening_stock : $row->quantity;
                    $qty = is_numeric($qty) ? (float) $qty : 0;
                    $totalQty += $qty;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->description ?: '-' }}</td>
                    <td class="text-right">{{ number_format($qty, 2) }}</td>
                    <td>{{ $row->name ?: '-' }}</td>
                    <td>{{ $row->hsn_name ?: '-' }}</td>
                    <td>{{ $row->group_name ?: '-' }}</td>
                    <td>{{ $row->rack_name ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
        @if (count($data))
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($totalQty, 2) }}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>

</html>
