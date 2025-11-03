<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product Completed Report</title>
    <style>
        @page {
            size: A3 landscape;
            margin: 10px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h3>Product Completed Report</h3>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">Sr No.</th>
                <th style="width: 10%;">Sent Date</th>
                <th style="width: 12%;">Project Name</th>
                <th style="width: 10%;">PO Number</th>
                <th style="width: 12%;">Customer Name</th>
                <th style="width: 12%;">Product Name</th>
                <th style="width: 10%;">Total Quantity</th>
                <th style="width: 12%;">Completed Quantity</th>
                <th style="width: 10%;">Estimated Amount</th>
                <th style="width: 10%;">Actual Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ !empty($row['updated_at']) ? \Carbon\Carbon::parse($row['updated_at'])->format('d-m-Y') : '-' }}</td>
                    <td>{{ $row['project_name'] ?? '-' }}</td>
                    <td>{{ $row['customer_po_number'] ?? '-' }}</td>
                    <td>{{ $row['title'] ?? '-' }}</td>
                    <td>{{ $row['product_name'] ?? '-' }}</td>
                    <td class="text-center">{{ $row['quantity'] ?? '-' }}</td>
                    <td class="text-center">{{ $row['total_completed_quantity'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($row['total_estimation_amount'] ?? 0, 2) }}</td>
                    <td class="text-right">{{ number_format($row['total_items_used_amount'] ?? 0, 2) }}</td>
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
