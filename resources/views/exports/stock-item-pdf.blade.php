@php
    // ✅ Get totals dynamically from data
    $totalReceived = $data->sum('received_quantity');
    $totalIssue = $data->sum('issue_quantity');
    $totalBalance = $data->sum('balance_quantity');
    $totalCount = $data->count();
@endphp

<h3>Item Stock Report</h3>
<p><strong>Total Records: {{ $totalCount }}</strong></p>

<table>
    <thead>
        <tr>
            <th style="width: 4%;">Sr No.</th>
            <th style="width: 10%;">Issue Date</th>
            <th style="width: 10%;">Received Date</th>
            <th style="width: 25%;">Entry No / Particulars</th>
            <th style="width: 10%;">Received Qty</th>
            <th style="width: 10%;">Issue Qty</th>
            <th style="width: 10%;">Balance Qty</th>
            <th style="width: 8%;">Unit</th>
            <th style="width: 8%;">HSN</th>
            <th style="width: 8%;">Group</th>
            <th style="width: 8%;">Rack No.</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $row)
            @php
                $issueDate = $row['issue_updated_at'] 
                    ? \Carbon\Carbon::parse($row['issue_updated_at'])->format('d-m-Y H:i') 
                    : '-';
                $receivedDate = $row['received_updated_at'] 
                    ? \Carbon\Carbon::parse($row['received_updated_at'])->format('d-m-Y H:i') 
                    : '-';
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $issueDate }}</td>
                <td>{{ $receivedDate }}</td>
                <td>{{ ucwords($row['description'] ?? '-') }}</td>
                <td>{{ $row['received_quantity'] ?? 0 }}</td>
                <td>{{ $row['issue_quantity'] ?? 0 }}</td>
                <td>{{ $row['balance_quantity'] ?? 0 }}</td>
                <td>{{ $row['unit_name'] ?? '-' }}</td>
                <td>{{ $row['hsn_name'] ?? '-' }}</td>
                <td>{{ $row['group_name'] ?? '-' }}</td>
                <td>{{ $row['rack_name'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right;">Total:</td>
            <td>{{ $totalReceived }}</td>
            <td>{{ $totalIssue }}</td>
            <td>{{ $totalBalance }}</td>
            <td colspan="4"></td>
        </tr>
    </tfoot>
</table>
