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
    </style>
</head>

<body>
    <h3 style="text-align:center; margin-bottom:4px;">Item List</h3>

    <div class="meta">
        Generated on: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
        @if (!empty($search))
            &nbsp;|&nbsp; Search: <strong>{{ $search }}</strong>
        @endif
        &nbsp;|&nbsp; Total Records: <strong>{{ count($data) }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">Sr No.</th>
                <th style="width: 11%;">Part Number</th>
                <th style="width: 21%;">Description</th>
                <th style="width: 16%;">Extra Description</th>
                <th style="width: 8%;">Unit</th>
                <th style="width: 9%;">HSN</th>
                <th style="width: 9%;">Group</th>
                <th style="width: 8%;">Rack No.</th>
                <th style="width: 7%;" class="text-right">Basic Rate</th>
                <th style="width: 7%;" class="text-right">Opening Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->part_number ?: '-' }}</td>
                    <td>{{ $row->description ?: '-' }}</td>
                    <td>{{ $row->extra_description ?: '-' }}</td>
                    <td>{{ $row->name ?: '-' }}</td>
                    <td>{{ $row->hsn_name ?: '-' }}</td>
                    <td>{{ $row->group_name ?: '-' }}</td>
                    <td>{{ $row->rack_name ?: '-' }}</td>
                    <td class="text-right">
                        {{ is_numeric($row->basic_rate) ? number_format((float) $row->basic_rate, 2) : '-' }}
                    </td>
                    <td class="text-right">
                        {{ is_numeric($row->opening_stock) ? number_format((float) $row->opening_stock, 2) : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
