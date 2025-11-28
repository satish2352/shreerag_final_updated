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
            font-size: 10px;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }

        body {
            font-family: sans-serif;
        }

        /* body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; } */
    </style>
</head>

<body>
    <h3 style="text-align:center;">Item List Report</h3>
    <table>
        <thead>
            <tr>
            <th style="width: 4%;">Sr No.</th>
                <th style="width: 10%;">Date</th>
                <th style="width: 25%;">Item Name</th>
                <th style="width: 10%;" class="text-right">Stock Qty</th>
                <th style="width: 8%;">Unit</th>
                <th style="width: 8%;">HSN</th>
                <th style="width: 8%;">Group</th>
                <th style="width: 8%;">Rack No.</th>


            </tr>
        </thead>
        <tbody>
           @forelse ($data as $index => $row)
                @php
                    $issueDate = $row['updated_at'] 
                        ? \Carbon\Carbon::parse($row['updated_at'])->format('d-m-Y H:i') 
                        : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $issueDate }}</td>
                   
                    <td>{{ ucwords($row['description'] ?? '-') }}</td>
                  @php
                    $qty = is_numeric($row['quantity']) ? (float)$row['quantity'] : 0;
                    @endphp
                    <td class="text-right">{{ number_format($qty, 2) }}</td>

                    <td>{{ $row['unit_name'] ?? '-' }}</td>
                    <td>{{ $row['hsn_name'] ?? '-' }}</td>
                    <td>{{ $row['group_name'] ?? '-' }}</td>
                    <td>{{ $row['rack_name'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
