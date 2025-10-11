@extends('admin.layouts.master')

@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>GRN Report</h1>
                        </div>
                    </div>

                    <div class="sparkline13-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">

                            {{-- 🔹 Filter Form --}}
                            <form id="filterForm" method="GET" action="{{ route('grn-report') }}" target="_blank">
                                <input type="hidden" name="export_type" id="export_type" />

                                <div class="row mb-3">

                                    <div class="col-md-3">
    <label>Vendor Name</label>
    <select class="form-control select2" name="vendor_name" id="vendorSelect">
        <option value="">All Vendors</option>
        @foreach($getProjectName as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label>PO Number</label>
    <select class="form-control select2" name="purchase_orders_id" id="poSelect">
        <option value="">All PO Numbers</option>
        @foreach($getPurchaseOrder as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>

                                    {{-- <div class="col-md-3">
                                        <label>Vendor Name</label>
                                        <select class="form-control select2" name="vendor_name">
                                            <option value="">All Vendors</option>
                                            @foreach($getProjectName as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    {{-- <div class="col-md-3">
                                        <label>PO Number</label>
                                        <select class="form-control select2" name="purchase_orders_id">
                                            <option value="">All PO Numbers</option>
                                            @foreach($getPurchaseOrder as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="col-md-3">
                                        <label>Year</label>
                                        <select name="year" class="form-control">
                                            <option value="">All</option>
                                            @foreach (yearOptions() as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Month</label>
                                        <select name="month" class="form-control">
                                            <option value="">All</option>
                                            @foreach (range(1, 12) as $m)
                                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total Records</label>
                                        <div><span id="totalCount">0</span></div>
                                    </div>
                                </div>

                                {{-- 🔹 Buttons --}}
                                <div class="row mb-3">
                                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                                        <button type="submit" class="btn btn-primary filterbg">Filter</button>
                                        <button type="button" class="btn btn-secondary ms-2" id="resetFilters" style="margin-left: 10px;">Reset</button>
                                    </div>

                                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                                        <input type="text" class="form-control me-2" id="searchKeyword" placeholder="Search...">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-download"></i> Export
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                                <li><a class="dropdown-item" href="#" id="exportExcel">Export to Excel</a></li>
                                                <li><a class="dropdown-item" href="#" id="exportPdf">Export to PDF</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            {{-- 🔹 Data Table --}}
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>PO Number</th>
                                            <th>GRN Number</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Company Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportBody">
                                        <tr><td colspan="6" class="text-center">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- 🔹 Pagination --}}
                            <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-3">
                                <div id="paginationInfo"></div>
                                <ul class="pagination" id="paginationLinks"></ul>
                            </div>

                        </div> {{-- .datatable --}}
                    </div> {{-- .graph --}}
                </div> {{-- .list --}}
            </div>
        </div>
    </div>
</div>

{{-- 🔹 JavaScript --}}
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
        if (val) params.append(key, val);
    });

    fetch(`{{ route('grn-report-ajax') }}?${params.toString()}`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('reportBody');
            const pagLinks = document.getElementById('paginationLinks');
            const pagInfo = document.getElementById('paginationInfo');

            if (res.status) {
                document.getElementById('totalCount').innerText = res.pagination.totalItems || 0;

                const rows = res.data.length > 0
                    ? res.data.map((item, i) => `
                        <tr>
                            <td>${((res.pagination.currentPage - 1) * pageSize) + i + 1}</td>
                            <td>${item.updated_at ? new Date(item.updated_at).toLocaleDateString('en-IN') : '-'}</td>
                            <td>${item.purchase_orders_id ?? '-'}</td>
                            <td>${item.grn_no_generate ?? '-'}</td>
                            <td>${item.vendor_name ?? '-'}</td>
                            <td>${item.vendor_company_name ?? '-'}</td>
                        </tr>
                    `).join('')
                    : '<tr><td colspan="6" class="text-center">No records found.</td></tr>';

                tbody.innerHTML = rows;

                // Pagination
                let pagHtml = '';
                const totalPages = res.pagination.totalPages;
                const start = Math.max(1, currentPage - 2);
                const end = Math.min(totalPages, start + 4);

                if (start > 1)
                    pagHtml += `<li class="page-item"><a class="page-link" href="javascript:goToPage(1)">1</a></li><li class="page-item disabled"><span class="page-link">...</span></li>`;

                for (let i = start; i <= end; i++) {
                    pagHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="javascript:goToPage(${i})">${i}</a>
                    </li>`;
                }

                if (end < totalPages)
                    pagHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>
                                <li class="page-item"><a class="page-link" href="javascript:goToPage(${totalPages})">${totalPages}</a></li>`;

                pagLinks.innerHTML = pagHtml;
                pagInfo.innerHTML = `Showing ${res.pagination.from} to ${res.pagination.to} of ${res.pagination.totalItems}`;
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to fetch data.</td></tr>';
            }
        })
        .catch(() => {
            document.getElementById('reportBody').innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error fetching data.</td></tr>';
        });
}

function goToPage(page) {
    currentPage = page;
    fetchReport();
}

// Filter submit
document.getElementById('filterForm').addEventListener('submit', e => {
    e.preventDefault();
    fetchReport(true);
});

// Live search
document.getElementById('searchKeyword').addEventListener('input', () => fetchReport(true));

// Export buttons
document.getElementById('exportPdf').addEventListener('click', () => {
    document.getElementById('export_type').value = 1;
    document.getElementById('filterForm').submit();
});

document.getElementById('exportExcel').addEventListener('click', () => {
    document.getElementById('export_type').value = 2;
    document.getElementById('filterForm').submit();
});

// Reset filters
document.getElementById('resetFilters').addEventListener('click', () => {
    document.getElementById('filterForm').reset();
    document.getElementById('searchKeyword').value = '';
    fetchReport(true);
});

// Initial load
fetchReport(true);




document.getElementById('vendorSelect').addEventListener('change', function () {
    const vendorId = this.value;
    const poSelect = document.getElementById('poSelect');

    poSelect.innerHTML = '<option value="">All PO Numbers</option>';

    if (vendorId) {
        let url = '{{ route("get-vendor-by-purchase_order", ":id") }}'.replace(':id', vendorId);

        fetch(url)
            .then(res => res.json())
            .then(res => {
                if (res.status && res.purchaseOrders.length > 0) {
                    res.purchaseOrders.forEach(po => {
                        const opt = document.createElement('option');
                        opt.value = po.name; // ✅ important
                        opt.textContent = po.name;
                        poSelect.appendChild(opt);
                    });
                }

                // Refresh select2 if used
                if ($('.select2').length) $('#poSelect').trigger('change.select2');

                fetchReport(true); // ✅ refresh table immediately
            });
    } else {
        fetchReport(true); // refresh if no vendor selected
    }
});


</script>
@endsection
