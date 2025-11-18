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
            font-size: 11px;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        body {
            font-family: sans-serif;
        }

        h3 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h3 style="text-align:center;">Item-wise Vendor Rate Report</h3>

    <table>
        <thead>
            <tr>
                <th style="width:40px">Sr.No</th>
                <th style="width:100px">Date</th>
                <th style="width:100px">PO Number</th>
                <th>Entry No / Particulars</th>
                <th style="width:80px">Vendor Name</th>
                <th style="width:80px">Vendor Company Name</th>
                <th style="width:80px">Rate</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($row->updated_at)->format('d/m/Y h:i:s A') }}
                    </td>
                    <td>{{ ucwords($row->purchase_orders_id) }}</td>
                    <td>{{ $row->description }}</td>
                    <td>{{ $row->vendor_name }}</td>
                    <td>{{ $row->vendor_company_name }}</td>
                    <td>{{ $row->rate }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
