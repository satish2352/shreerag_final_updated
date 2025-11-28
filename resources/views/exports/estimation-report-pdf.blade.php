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
    <h3 style="text-align:center;">Estimation Report</h3>
    <table>
        <thead>
            <tr>
               <th data-field="id">ID</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="purchase_id" data-editable="false">Remark</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="bom_image" data-editable="false">Estimated BOM</th>
                                                <th data-field="total_estimation_amount" data-editable="false">Total
                                                    Estimation Amount</th>


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
                    <td>{{ $row['remark'] ?? '-' }}</td>
                    <td>{{ $row['product_name'] ?? '-' }}</td>
                    <td>{{ $row['quantity'] ?? '-' }}</td>
                    <td>{{ $row['description'] ?? '-' }}</td>
                    <td>{{ $row['design_image'] ?? '-' }}</td>
                    <td>{{ $row['bom_image'] ?? '-' }}</td>
                    <td>{{ $row['total_estimation_amount'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
