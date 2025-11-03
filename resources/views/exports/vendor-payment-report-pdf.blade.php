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
    <h3 style="text-align:center;">Vendor Payment Report</h3>
    <table>
        <thead>
            <tr>
            <th style="width: 4%;">Sr No.</th>
            <th data-field="updated_at" data-editable="false">Payment Release Date</th>
            <th data-field="purchase_orders_id" data-editable="false">Purchase Order No.</th>
            <th data-field="grn_status_sanction" data-editable="false">GRN No.</th>
            <th data-field="from_place" data-editable="false">Payment Status</th>
            <th data-field="vendor_name" data-editable="false">Vendor Name</th>
            <th data-field="vendor_company_name" data-editable="false">Vendor Company Name</th>
            <th data-field="" data-editable="false">Vendor Email</th>
            <th data-field="contact_no" data-editable="false">Contact No.</th>
            <th data-field="purchase_orders_id" data-editable="false">PO Number</th>
            <th data-field="invoice_date" data-editable="false">Invoice Date</th>


            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                   <td>{{ $loop->iteration }}</td>
                    <td>{{ ucwords($row['updated_at'] ?? '-') }}</td>
                    <td>{{ ucwords($row['purchase_orders_id'] ?? '-') }}</td>
                    <td>{{ $row['grn_no_generate'] ?? '-' }}</td>
                    <td>{{ $row['grn_status_sanction'] ?? '-' }}</td>
                    <td>{{ $row['vendor_name'] ?? '-' }}</td>
                    <td>{{ $row['vendor_company_name'] ?? '-' }}</td>
                    <td>{{ $row['vendor_email'] ?? '-' }}</td>
                    <td>{{ $row['contact_no'] ?? '-' }}</td>
                    <td>{{ $row['purchase_orders_id'] ?? '-' }}</td>
                    <td>{{ $row['invoice_date'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
