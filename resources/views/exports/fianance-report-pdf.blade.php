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
    <h3 style="text-align:center;">Logistics Report</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">Sr No.</th>
                <th style="width: 30px;">Date</th>
                {{-- <th style="width: 100px;">Vendor Name</th>
                <th style="width: 120px;">Purchase Order Id</th>
                <th style="width: 100px;">Gatepass Name</th>
                <th style="width: 100px;">Remark</th> --}}


            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                   <td>{{ $loop->iteration }}</td>
                    <td>{{ ucwords($row['updated_at'] ?? '-') }}</td>
                    {{-- <td>{{ ucwords($row['project_name'] ?? '-') }}</td>
                    <td>{{ $row['customer_po_number'] ?? '-' }}</td>
                    <td>{{ $row['title'] ?? '-' }}</td>
                    <td>{{ $row['product_name'] ?? '-' }}</td>
                    <td>{{ $row['quantity'] ?? '-' }}</td>
                    <td>{{ $row['completed_quantity'] ?? '-' }}</td>
                    <td>{{ $row['remaining_quantity'] ?? '-' }}</td>
                    <td>{{ $row['from_place'] ?? '-' }}</td>
                    <td>{{ $row['to_place'] ?? '-' }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
