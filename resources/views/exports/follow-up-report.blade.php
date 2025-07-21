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
    <h3 style="text-align:center;">Follow Up Report</h3>
    <table>
        <thead>
            <tr>
            <th style="width: 4%;">Sr No.</th>
            <th style="width: 30px;">Date</th>
            <th style="width: 100px;">Purchase Order ID</th>
            <th style="width: 120px;">Vendor Name</th>
            <th style="width: 120px;">Company</th>
            <th style="width: 150px;">Email</th>
            <th style="width: 80px;">Contact No.</th>          
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td style="width: 4%;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->latest_update)->format('d-m-Y') }}</td>
                     <td>{{ $row->purchase_order_id ?? '-' }}</td>                   
                    <td>{{ $row->vendor_name ?? '-' }}</td>
                    <td>{{ $row->vendor_company_name ?? '-' }}</td>
                    <td>{{ $row->vendor_email ?? '-' }}</td>
                    <td>{{ $row->contact_no ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
