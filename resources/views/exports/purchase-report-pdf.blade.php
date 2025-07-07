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
        /* body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; } */
    </style>
</head>
<body>
    <h3 style="text-align:center;">Purchase Report</h3>
    <table>
        <thead>
            <tr>
                 <th style="width: 4%;">Sr No.</th>
            <th style="width: 100px;">Project Name</th>
            <th style="width: 60px;">Customer PO No.</th>
            <th style="width: 120px;">Product Name</th>
            <th style="width: 100px;">Description</th>
            <th style="width: 100px;">Purchase Order ID</th>
            <th style="width: 120px;">Vendor Name</th>
            <th style="width: 120px;">Company</th>
            <th style="width: 150px;">Email</th>
            <th style="width: 80px;">Contact No.</th>
            <th style="width: 30px;">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td style="width: 4%;">{{ $index + 1 }}</td>
                    <td>{{ $row->project_name ?? '-' }}</td>
                    <td>{{ $row->customer_po_number ?? '-' }}</td>
                    <td>{{ $row->product_name ?? '-' }}</td>
                    <td>{{ $row->description ?? '-' }}</td>
                    <td>{{ $row->purchase_order_id ?? '-' }}</td>
                    <td>{{ $row->vendor_name ?? '-' }}</td>
                    <td>{{ $row->vendor_company_name ?? '-' }}</td>
                    <td>{{ $row->vendor_email ?? '-' }}</td>
                    <td>{{ $row->contact_no ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->latest_update)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
