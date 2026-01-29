<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A3 landscape;
            margin: 10px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        h3 {
            text-align: center;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

    <h3>Employee Leave Report</h3>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Sr No</th>
                <th rowspan="2">Employee Name</th>
                <th rowspan="2">Year</th>
                <th colspan="3">Opening</th>
                <th colspan="3">Used</th>
                <th colspan="3">Balanced</th>
            </tr>
            <tr>
                <th>CL</th>
                <th>PL</th>
                <th>SL</th>

                <th>CL</th>
                <th>PL</th>
                <th>SL</th>

                <th>CL</th>
                <th>PL</th>
                <th>SL</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->f_name }} {{ $row->m_name }} {{ $row->l_name }}</td>
                    <td>{{ $row->year }}</td>

                    <td>{{ $row->opening_cl }}</td>
                    <td>{{ $row->opening_pl }}</td>
                    <td>{{ $row->opening_sl }}</td>

                    <td>{{ $row->used_cl }}</td>
                    <td>{{ $row->used_pl }}</td>
                    <td>{{ $row->used_sl }}</td>

                    <td>{{ $row->closed_cl }}</td>
                    <td>{{ $row->closed_pl }}</td>
                    <td>{{ $row->closed_sl }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
