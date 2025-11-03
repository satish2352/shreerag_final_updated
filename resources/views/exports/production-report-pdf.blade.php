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
            <th data-field="id">Sr.No.</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="title" data-editable="false">customer Name</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="completed_quantity" data-editable="false">Completed
                                                    Production</th>
                                                <th data-field="remaining_quantity" data-editable="false">Balance Quantity
                                                </th>
                                                <th data-field="from_place" data-editable="false">From Place</th>
                                                <th data-field="to_place" data-editable="false">To Place</th>
                                                <th data-field="truck_no" data-editable="false">Truck Number</th>
                                                <th data-field="outdoor_no" data-editable="false">Outdoor Number</th>
                                                <th data-field="gate_entry" data-editable="false">Gate Entry</th>
                                                <th data-field="remark" data-editable="false">Dispatch Remark</th>
                                                <th data-field="updated_at" data-editable="false">Dispatch Date</th>
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
