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
    <h3 style="text-align:center;">Item Stock Report</h3>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Issue Date</th>
                 <th>Received Date</th>
                <th>Project Name</th>
                <th>PO Number</th>
                <th>Customer Name</th>
                <th>Product Name</th>
            
            </tr>
        </thead>
        <tbody>
          
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['issue_updated_at'] ?? '-' }}</td>
                     <td>{{ $row['received_updated_at'] ?? '-' }}</td>
                    <td>{{ ucwords($row['description'] ?? '-') }}</td>
                    <td>{{ $row['received_quantity'] ?? '-' }}</td>
                    <td>{{ $row['balance_quantity'] ?? '-' }}</td>
                    <td>{{ $row['used_quantity'] ?? '-' }}</td>
                  
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
