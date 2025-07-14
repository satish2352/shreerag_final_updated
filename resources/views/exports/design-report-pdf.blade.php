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

        /* body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; } */
    </style>
</head>

<body>
    <h3 style="text-align:center;">Design Report</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">Sr No.</th>
                <th style="width: 30px;">Date</th>
                <th style="width: 30px;">Status</th>
                <th style="width: 100px;">Project Name</th>
                <th style="width: 60px;">Customer PO No.</th>
                <th style="width: 120px;">Product Name</th>
                <th style="width: 100px;">Description</th>
                <th style="width: 100px;">Quantity</th>


            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>

                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucwords($row['updated_at'] ?? '-') }}</td>
                    <td>
    @switch($row['production_status_id'])
        @case(1115)
        @case(1117)
        @case(1121)
            Accepted
            @break

        @case(1114)
            Rejected
            @break

        @default
            {{ $row['production_status_id'] ?? '-' }}
    @endswitch
</td>

                    {{-- <td>{{ ucwords($row['production_status_id'] ?? '-') }}</td> --}}
                    <td>{{ ucwords($row['project_name'] ?? '-') }}</td>
                    <td>{{ $row['customer_po_number'] ?? '-' }}</td>
                    <td>{{ $row['product_name'] ?? '-' }}</td>
                    <td>{{ $row['description'] ?? '-' }}</td>
                    <td>{{ $row['quantity'] ?? '-' }}</td>


                    {{-- <td> <a class="img-size" target="_blank"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                            alt="Design"> Click to view</a>
                                                    </td>
                                                    <td> <a class="img-size"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                            alt="bill of material">Click to download</a>
                                                    </td> --}}
                    {{-- @if ($data->reject_reason_prod == '')
                                                        <td>-</td>
                                                        <td>-</td>
                                                    @else --}}
                    {{-- <td> <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_design_image'] }}"
                                                                alt="Design"> Click to view</a>
                                                        </td>
                                                        <td> <a class="img-size"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_bom_image'] }}"
                                                                alt="bill of material">Click to download</a>
                                                        </td> --}}
                    {{-- @endif --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
