@extends('admin.layouts.master')
@section('content')

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Follow Up Report</h1>
                              
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">

    <form id="filterForm" method="GET" action="{{ route('follow-up-report') }}">
        <input type="hidden" name="export_type" id="export_type" />

        <div class="row mb-3">
            <div class="col-md-2">
                <label>Vendor Name</label>
                <select class="form-control" name="vendor_name" id="vendor_name">
                    <option value="">All Vendors</option>
                    @foreach($getVendorName as $id => $vendor_name)
                        <option value="{{ $id }}" {{ request('vendor_name') == $id ? 'selected' : '' }}>{{ $vendor_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>PO Status</label>
                <select class="form-control" name="po_status" id="po_status">
                    <option value="">All PO Status</option>
                    <option value="open" {{ request('po_status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="partially_received" {{ request('po_status') == 'partially_received' ? 'selected' : '' }}>Partially Received</option>
                    <option value="pending_gate_pass" {{ request('po_status') == 'pending_gate_pass' ? 'selected' : '' }}>Pending Gate Pass</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-2">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-md-2">
                <label>Year</label>
                <select name="year" class="form-control">
                    <option value="">All</option>
                     @foreach (yearOptions() as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Month</label>
                <select name="month" class="form-control">
                    <option value="">All</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary filterbg">Filter</button>
                 <button type="button" class="btn btn-secondary ms-2" id="resetFilters" style="margin-left: 10px;">
        Reset
    </button>
            </div>
            <div class="col-md-6 text-end d-flex">
                <input type="text" class="form-control me-2" id="searchKeyword" placeholder="Search...">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa fa-download"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="exportExcel">Export to Excel</a></li>
                        <li><a class="dropdown-item" href="#" id="exportPdf">Export to PDF</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </form>

    {{-- 🔹 Table --}}
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr.No.</th>
                    <th>Purchase Order ID</th>
                    <th>Vendor Name</th>
                    <th>Vendor Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody id="reportBody">
                <tr><td colspan="6">Loading...</td></tr>
            </tbody>
        </table>

        <div class="pagination-wrapper">
            <div id="paginationInfo"></div>
            <ul class="pagination" id="paginationLinks"></ul>
        </div>
    </div>
</div>
                        </div></div></div></div></div></div>
<script>
let currentPage = 1, pageSize = 10;

function fetchReport(reset = false) {
    if (reset) currentPage = 1;

    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    formData.append('pageSize', pageSize);
    formData.append('currentPage', currentPage);
    formData.append('search', document.getElementById('searchKeyword').value);

    const params = new URLSearchParams();
    formData.forEach((val, key) => {
        params.append(key, val);
    });

    fetch(`{{ route('follow-up-report-ajax') }}?${params.toString()}`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('reportBody');
            const pagLinks = document.getElementById('paginationLinks');
            const pagInfo = document.getElementById('paginationInfo');

            if (res.status) {
                const rows = res.data.map((item, i) => `
                    <tr>
                        <td>${((res.pagination.currentPage - 1) * pageSize) + i + 1}</td>
                        <td>${item.purchase_order_id}</td>
                        <td>${item.vendor_name}</td>
                        <td>${item.vendor_company_name}</td>
                        <td>${item.vendor_email}</td>
                        <td>${item.contact_no}</td>
                    </tr>
                `).join('');
                tbody.innerHTML = rows || '<tr><td colspan="6">No records found.</td></tr>';

                // Pagination
                let pagHtml = '', totalPages = res.pagination.totalPages;
                let start = Math.max(1, currentPage - 2), end = Math.min(totalPages, start + 4);

                if (start > 1) pagHtml += `<li><a class="page-link" onclick="goToPage(1)">1</a></li><li>...</li>`;
                for (let i = start; i <= end; i++) {
                    pagHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                    <a class="page-link" onclick="goToPage(${i})">${i}</a>
                                </li>`;
                }
                if (end < totalPages) pagHtml += `<li>...</li><li><a class="page-link" onclick="goToPage(${totalPages})">${totalPages}</a></li>`;

                pagLinks.innerHTML = pagHtml;
                pagInfo.innerHTML = `Showing ${res.pagination.from} to ${res.pagination.to} of ${res.pagination.totalItems}`;
            } else {
                tbody.innerHTML = '<tr><td colspan="6">Failed to fetch data.</td></tr>';
            }
        });
}

function goToPage(page) {
    currentPage = page;
    fetchReport();
}

document.getElementById('filterForm').addEventListener('submit', e => {
    e.preventDefault();
    fetchReport(true);
});

document.getElementById('searchKeyword').addEventListener('input', () => fetchReport(true));

document.getElementById('exportPdf').addEventListener('click', () => {
    document.getElementById('export_type').value = 1;
    document.getElementById('filterForm').submit();
});

document.getElementById('exportExcel').addEventListener('click', () => {
    document.getElementById('export_type').value = 2;
    document.getElementById('filterForm').submit();
});

fetchReport(true);
</script>

@endsection
