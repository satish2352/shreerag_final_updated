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
    <h3 style="text-align:center;">Production Report</h3>
   <table>
    <thead>
        <tr>
            <th>Sr</th>
            <th>Date</th>
            <th>Project Name</th>
            <th>Customer PO No.</th>
            <th>Product</th>
            <th>Description</th>
            <th>Total Quantity</th>
            <th>Completed</th>
            <th>Remaining</th>
            <th>From</th>
            <th>To</th>
            <th>Gate Entry</th>
            <th>Dispatch Remark</th>
             <th>Dispatch Date</th>
        </tr>
    </thead>
    <tbody>
      
        @foreach ($data as $index => $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row['updated_at'] ?? '-' }}</td>
                <td>{{ $row['project_name'] ?? '-' }}</td>
                <td>{{ $row['customer_po_number'] ?? '-' }}</td>
                <td>{{ $row['product_name'] ?? '-' }}</td>
                <td>{{ $row['description'] ?? '-' }}</td>
                <td>{{ $row['quantity'] ?? '-' }}</td>
                <td>{{ $row['cumulative_completed_quantity'] ?? '-' }}</td>
                <td>{{ $row['remaining_quantity'] ?? '-' }}</td>
                <td>{{ $row['from_place'] ?? '-' }}</td>
                <td>{{ $row['to_place'] ?? '-' }}</td>
                <td>{{ $row['gate_entry'] ?? '-' }}</td>
                <td>{{ $row['dispatch_remark'] ?? '-' }}</td>
                 <td>{{ $row['updated_at'] ?? '-' }}</td>
                
            </tr>
        @endforeach
    </tbody>
</table>

</body>

</html>
