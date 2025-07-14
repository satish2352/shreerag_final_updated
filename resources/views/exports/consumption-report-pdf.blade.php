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
    <h3 style="text-align:center;">Design Report</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">Sr No.</th>
                <th style="width: 30px;">Date</th>
                <th style="width: 100px;">Vendor Name</th>
                <th style="width: 120px;">Purchase Order Id</th>
                <th style="width: 100px;">Gatepass Name</th>
                <th style="width: 100px;">Remark</th>


            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>

                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucwords($row['date'] ?? '-') }}</td>
                    <td>{{ ucwords($row['vendor_name'] ?? '-') }}</td>
                    <td>{{ $row['purchase_orders_id'] ?? '-' }}</td>
                    <td>{{ $row['gatepass_name'] ?? '-' }}</td>
                    <td>{{ $row['remark'] ?? '-' }}</td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
