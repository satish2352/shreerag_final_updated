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
    </style>
</head>

<body>
    <h3 style="text-align:center;">Rejected GRN Report</h3>
    <table>
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Date</th>
                <th>PO Number</th>
                <th>GRN Number</th>
                <th>Vendor Name</th>
                <th>Vendor Company Name</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['updated_at'] ?? '-' }}</td>
                    <td>{{ $row['purchase_orders_id'] ?? '-' }}</td>
                    <td>{{ ucwords($row['grn_no_generate'] ?? '-') }}</td>
                    <td>{{ $row['vendor_name'] ?? '-' }}</td>
                    <td>{{ $row['vendor_company_name'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
