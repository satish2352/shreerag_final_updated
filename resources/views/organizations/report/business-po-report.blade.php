@extends('admin.layouts.master')
@section('content')
<style>
    .business-po-filter-actions {
        gap: 4px;
        justify-content: flex-end;
        white-space: nowrap;
    }
</style>
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Business <span class="table-project-n">PO Report</span></h1>
                        </div>
                    </div>

                    <div class="sparkline13-graph">

                        {{-- Filter Form --}}
                        <form method="GET" action="{{ route('business-po-report') }}" id="filterForm">
                            <input type="hidden" name="per_page" value="{{ (int) request('per_page', 100) }}">
                            <div class="row mb-3" style="background:#f8f9fa; padding:15px 15px 5px 15px; border-radius:6px; margin:0 0 15px 0; border:1px solid #dee2e6;">

                                <div class="col-md-3 mb-2">
                                    <label style="font-size:12px; font-weight:600; margin-bottom:3px;">Business / Project</label>
                                    <select name="business_id" id="businessSelect" class="form-control form-control-sm">
                                        <option value="">-- All Business --</option>
                                        @foreach($businesses as $id => $name)
                                            <option value="{{ $id }}" {{ request('business_id') == $id ? 'selected' : '' }}>
                                                {{ ucwords($name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <label style="font-size:12px; font-weight:600; margin-bottom:3px;">Product</label>
                                    <select name="product_id" id="productSelect" class="form-control form-control-sm">
                                        <option value="">-- All Products --</option>
                                        @foreach($products as $id => $name)
                                            <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>
                                                {{ ucwords($name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-1 mb-2">
                                    <label style="font-size:12px; font-weight:600; margin-bottom:3px;">Year</label>
                                    <select name="year" class="form-control form-control-sm">
                                        <option value="">-- All --</option>
                                        @for($y = date('Y'); $y >= 2024; $y--)
                                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <label style="font-size:12px; font-weight:600; margin-bottom:3px;">From Date (GRN)</label>
                                    <input type="date" name="from_date" class="form-control form-control-sm"
                                        value="{{ request('from_date') }}">
                                </div>

                                <div class="col-md-2 mb-2">
                                    <label style="font-size:12px; font-weight:600; margin-bottom:3px;">To Date (GRN)</label>
                                    <input type="date" name="to_date" class="form-control form-control-sm"
                                        value="{{ request('to_date') }}">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label style="font-size:12px; font-weight:600; margin-bottom:3px;">Search (PO No / GRN No / Business / Product / Vendor)</label>
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="PO Number, GRN Number, Business name, Product, Vendor..."
                                        value="{{ request('search') }}">
                                </div>

                                <div class="col-md-8 mb-2 d-flex align-items-end business-po-filter-actions">
                                    <button type="submit" class="btn btn-primary btn-sm filterbg">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                    <a href="{{ route('business-po-report') }}" class="btn btn-secondary btn-sm">Reset</a>
                                </div>

                            </div>
                        </form>

                        {{-- Results --}}
                        @if(!$result['status'])
                            <div class="alert alert-danger">{{ $result['message'] }}</div>
                        @else
                            @php
                                $paginator  = $result['data'];
                                $rows       = collect($paginator->items());
                                $grandTotal = $result['grandTotal'];
                                $grouped    = $rows->groupBy('business_id');
                            @endphp

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    Showing {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }}
                                    of {{ $paginator->total() }} rows
                                </small>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-striped" style="font-size:12px;">
                                    <thead style="background:#1a3a6b; color:#fff;">
                                        <tr>
                                            <th style="width:35px; text-align:center;">Sr.</th>
                                            <th>Business / Project</th>
                                            <th style="white-space:nowrap;">PO Number</th>
                                            <th style="white-space:nowrap;">PO Date</th>
                                            <th>Product Name</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Company</th>
                                            <th style="white-space:nowrap;">GRN No</th>
                                            <th style="white-space:nowrap;">GRN Date</th>
                                            <th>Material Description</th>
                                            <th style="text-align:right; white-space:nowrap;">PO Qty</th>
                                            <th style="text-align:right; white-space:nowrap;">Actual Qty</th>
                                            <th style="text-align:right; white-space:nowrap;">Accepted Qty</th>
                                            <th style="text-align:right; white-space:nowrap;">Rejected Qty</th>
                                            <th>Unit</th>
                                            <th style="text-align:right;">Rate</th>
                                            <th style="text-align:right;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($rows->isEmpty())
                                            <tr>
                                                <td colspan="17" class="text-center text-muted" style="padding:20px;">
                                                    No records found.
                                                </td>
                                            </tr>
                                        @else
                                            @php $sr = ($paginator->currentPage() - 1) * $paginator->perPage() + 1; @endphp
                                            @foreach($grouped as $businessId => $bizRows)
                                                @php
                                                    $bizTotal  = $bizRows->sum(fn($r) => (float) $r->line_amount);
                                                    $bizName   = $bizRows->first()->project_name;
                                                    $poGroups  = $bizRows->groupBy('purchase_orders_id');
                                                    $poCount   = $poGroups->count();
                                                @endphp

                                                {{-- Business header --}}
                                                <tr style="background:#e8f0fe;">
                                                    <td colspan="17" style="font-weight:700; padding:6px 10px;">
                                                        <i class="fa fa-briefcase"></i>
                                                        {{ ucwords($bizName) }}
                                                        <span style="font-weight:400; font-size:12px; margin-left:8px; color:#555;">
                                                            — {{ $poCount }} PO{{ $poCount > 1 ? 's' : '' }} on this page
                                                        </span>
                                                    </td>
                                                </tr>

                                                @foreach($poGroups as $poId => $poRows)
                                                    @php
                                                        $poTotal    = $poRows->sum(fn($r) => (float) $r->line_amount);
                                                        $totalRows  = $poRows->count();
                                                    @endphp
                                                    @foreach($poRows as $ri => $row)
                                                        <tr>
                                                            @if($ri === 0)
                                                                <td rowspan="{{ $totalRows }}" style="text-align:center; vertical-align:middle;">{{ $sr++ }}</td>
                                                                <td rowspan="{{ $totalRows }}" style="vertical-align:middle;">{{ ucwords($row->project_name) }}</td>
                                                                <td rowspan="{{ $totalRows }}" style="vertical-align:middle; font-weight:600; white-space:nowrap;">{{ $row->purchase_orders_id }}</td>
                                                                <td rowspan="{{ $totalRows }}" style="vertical-align:middle; white-space:nowrap;">
                                                                    {{ $row->po_date ? \Carbon\Carbon::parse($row->po_date)->format('d-m-Y') : '—' }}
                                                                </td>
                                                                <td rowspan="{{ $totalRows }}" style="vertical-align:middle;">{{ ucwords($row->product_name) }}</td>
                                                                <td rowspan="{{ $totalRows }}" style="vertical-align:middle;">{{ ucwords($row->vendor_name) }}</td>
                                                                <td rowspan="{{ $totalRows }}" style="vertical-align:middle;">{{ ucwords($row->vendor_company_name) }}</td>
                                                            @endif
                                                            <td style="white-space:nowrap;">{{ $row->grn_no_generate ?? '—' }}</td>
                                                            <td style="white-space:nowrap;">
                                                                {{ $row->grn_date ? \Carbon\Carbon::parse($row->grn_date)->format('d-m-Y') : '—' }}
                                                            </td>
                                                            <td>{{ $row->material_description ?? '—' }}</td>
                                                            <td style="text-align:right;">{{ $row->po_quantity }}</td>
                                                            <td style="text-align:right;">{{ $row->actual_quantity }}</td>
                                                            <td style="text-align:right; color:#155724; font-weight:600;">{{ $row->accepted_quantity }}</td>
                                                            <td style="text-align:right; color:#721c24;">{{ $row->rejected_quantity }}</td>
                                                            <td>{{ $row->unit_name ?? '—' }}</td>
                                                            <td style="text-align:right;">{{ number_format((float)$row->rate, 2) }}</td>
                                                            <td style="text-align:right;"><strong>{{ number_format((float)$row->line_amount, 2) }}</strong></td>
                                                        </tr>
                                                    @endforeach

                                                    {{-- PO subtotal --}}
                                                    <tr style="background:#fff8e1;">
                                                        <td colspan="16" style="text-align:right; font-weight:600; padding:4px 8px; font-size:12px;">
                                                            PO {{ $poId }} — Subtotal
                                                        </td>
                                                        <td style="text-align:right; font-weight:700;">{{ number_format($poTotal, 2) }}</td>
                                                    </tr>
                                                @endforeach

                                                {{-- Business subtotal --}}
                                                <tr style="background:#d4edda;">
                                                    <td colspan="16" style="text-align:right; font-weight:700; padding:5px 8px;">
                                                        {{ ucwords($bizName) }} — Page Total
                                                    </td>
                                                    <td style="text-align:right; font-weight:700; color:#155724;">{{ number_format($bizTotal, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr style="background:#1a3a6b; color:#fff;">
                                            <td colspan="16" style="text-align:right; font-weight:700; font-size:14px; padding:8px;">
                                                Grand Total (All Pages)
                                            </td>
                                            <td style="text-align:right; font-weight:700; font-size:14px;">
                                                {{ number_format($grandTotal, 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                                <div class="d-flex align-items-center mb-2">
                                    <label for="perPageSelect" style="font-size:12px; font-weight:600; margin:0 8px 0 0; white-space:nowrap;">
                                        Show
                                    </label>
                                    <select id="perPageSelect" class="form-control form-control-sm" style="width:auto;"
                                        onchange="changePerPage(this.value)">
                                        @foreach([5, 10, 20, 50, 100] as $size)
                                            <option value="{{ $size }}" {{ (int) request('per_page', 100) === $size ? 'selected' : '' }}>
                                                {{ $size }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span style="font-size:12px; color:#6c757d; margin-left:8px; white-space:nowrap;">per page</span>
                                </div>
                                <div>
                                    {{ $paginator->links() }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changePerPage(size) {
    var url = new URL(window.location.href);
    url.searchParams.set('per_page', size);
    url.searchParams.set('page', 1); // jump back to first page when page size changes
    window.location.href = url.toString();
}

$(document).ready(function() {
    $('#businessSelect').on('change', function() {
        var businessId = $(this).val();
        var $productSelect = $('#productSelect');
        $productSelect.html('<option value="">-- All Products --</option>');
        if (!businessId) return;
        $.get('{{ route('get-products-by-business') }}', { business_id: businessId }, function(data) {
            $.each(data, function(i, item) {
                $productSelect.append('<option value="' + item.id + '">' + item.product_name + '</option>');
            });
        });
    });
});
</script>
@endsection
