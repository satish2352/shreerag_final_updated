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
        th, td {
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
    <h3 style="text-align:center;">Dispatch Report</h3>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Sent Date</th>
                <th>Project Name</th>
                <th>PO Number</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Completed Production</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['last_updated_at'] ?? '-' }}</td>
                    <td>{{ ucwords($row['project_name'] ?? '-') }}</td>
                    <td>{{ $row['customer_po_number'] ?? '-' }}</td>
                    <td>{{ $row['title'] ?? '-' }}</td>
                    <td>{{ $row['product_name'] ?? '-' }}</td>
                    <td>{{ $row['quantity'] ?? '-' }}</td>
                    <td>{{ $row['total_completed_quantity'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
