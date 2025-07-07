<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Report PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Purchase Orders Submitted by Vendor</h2>
    <table>
        <thead>
            <tr>
                <th>Sr.No.</th>
                <th>Project Name</th>
                <th>Customer PO Number</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Purchase Order ID</th>
                <th>Client Name</th>
                <th>Client Company Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->project_name }}</td>
                    <td>{{ $item->customer_po_number }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->purchase_order_id }}</td>
                    <td>{{ $item->vendor_name }}</td>
                    <td>{{ $item->vendor_company_name }}</td>
                    <td>{{ $item->vendor_email }}</td>
                    <td>{{ $item->contact_no }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
