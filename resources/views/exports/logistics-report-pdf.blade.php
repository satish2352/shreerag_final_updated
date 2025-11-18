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
    <h3 style="text-align:center;">Logistics Report</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">Sr No.</th>
                <th style="width: 30px;">Date</th>
                <th data-field="project_name" data-editable="false">Project Name</th>
                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                <th data-field="product_name" data-editable="false">Product Name</th>
                <th data-field="quantity" data-editable="false">Quantity</th>
                <th data-field="completed_quantity" data-editable="false">Completed Production</th>
                <th data-field="remaining_quantity" data-editable="false">Balance Quantity</th>
                <th data-field="from_place" data-editable="false">Form Place</th>
                <th data-field="to_place" data-editable="false">To Place</th>
                <th data-field="transport_name" data-editable="false">Transport Name</th>
                <th data-field="vehicle_name" data-editable="false">Vehicle Type</th>
                <th data-field="truck_no" data-editable="false">Truck No.</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($row->updated_at)->format('d/m/Y h:i:s A') }}
                    </td>
                    <td>{{ ucwords($row['project_name'] ?? '-') }}</td>
                    <td>{{ $row['customer_po_number'] ?? '-' }}</td>
                    <td>{{ $row['product_name'] ?? '-' }}</td>
                    <td>{{ $row['quantity'] ?? '-' }}</td>
                    <td>{{ $row['completed_quantity'] ?? '-' }}</td>
                    <td>{{ $row['remaining_quantity'] ?? '-' }}</td>
                    <td>{{ $row['from_place'] ?? '-' }}</td>
                    <td>{{ $row['to_place'] ?? '-' }}</td>
                    <td>{{ $row['transport_name'] ?? '-' }}</td>
                    <td>{{ $row['vehicle_name'] ?? '-' }}</td>
                    <td>{{ $row['truck_no'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
