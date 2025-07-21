@extends('admin.layouts.master')
@section('content')
 <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
<div class="container-fluid mt-4">
    <h4 class="mb-3">Purchase Orders</h4>

    {{-- 🔹 Filters --}}
   <form id="filterForm" method="GET" action="{{ route('purchase-report') }}">
    <input type="hidden" name="export_type" id="export_type" />
      
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Project Name</label>
                <select class="form-control select2" name="project_name">
                    <option value="">All Projects</option>
                    @foreach($getProjectName as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control">
            </div>
            <div class="col-md-2">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Year</label>
                <select name="year" class="form-control">
                    <option value="">All</option>
                    @for ($i = now()->year; $i >= 2010; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
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

        {{-- 🔹 Search and Export --}}
        <div class="row mb-2">
          <div class="col-md-6 d-flex justify-content-center">
    <button type="submit" class="btn btn-primary filterbg me-2">Filter</button>
    <button type="button" class="btn btn-secondary" id="resetFilters">Reset</button>
</div>

            <div class="col-md-6 text-end d-flex" >
                <input type="text" class="form-control d-flex align-self-center" id="searchKeyword" style="margin-right: 23px;" placeholder="Search...">
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="float: right;">
            <i class="fa fa-download"></i> Export
        </button>
        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
            <li><a class="dropdown-item" href="#" id="exportExcel">Export to Excel</a></li>
            <li><a class="dropdown-item" href="#" id="exportPdf">Export to PDF</a></li>
        </ul>
    </div>
</div>
            {{-- <div class="col-md-8 text-end">
                <button type="button" class="btn btn-success" id="exportExcel">Export Excel</button>
                <button type="button" class="btn btn-danger" id="exportPdf">Export PDF</button>
            </div> --}}
        </div>
    </form>

    {{-- 🔹 Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-light">
             <tr>
                                                <th data-field="id">Sr.No.</th>
                                                {{-- <th data-field="po_number" data-editable="false">PO Number</th> --}}
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">Customer PO Number</th>
                                                 <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                              <th data-field="purchase_orders_id" data-editable="false">Purchase Order ID</th>
                                                <th data-field="client_name" data-editable="false">Client Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Client Company Name</th>
                                                <th data-field="email" data-editable="false">Email</th>
                                                <th data-field="contact_no" data-editable="false">Phone Number</th>
                                                
                                            </tr>
            </thead>
            <tbody id="reportBody">
                <tr><td colspan="10">Loading...</td></tr>
            </tbody>
        </table>
    </div>

    {{-- 🔹 Pagination --}}
    <div class="pagination-wrapper">
        <div id="paginationInfo"></div>
        <ul class="pagination" id="paginationLinks"></ul>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
 </div>
<script>
let currentPage = 1, totalPages = 1, pageSize = 10;

function fetchReport(reset = false) {
    if (reset) currentPage = 1;
    const formData = new FormData(document.getElementById('filterForm'));
    formData.append('pageSize', pageSize);
    formData.append('currentPage', currentPage);
    formData.append('search', document.getElementById('searchKeyword').value);

    fetch(`{{ route('ajax') }}?${new URLSearchParams(formData).toString()}`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('reportBody');
            const pagLinks = document.getElementById('paginationLinks');
            const pagInfo = document.getElementById('paginationInfo');

            if (res.status) {
                const rows = res.data.map((item, index) => `
                    <tr>
                        <td>${((res.pagination.currentPage - 1) * pageSize) + index + 1}</td>
                        <td>${item.project_name ?? '-'}</td>
                        <td>${item.customer_po_number ?? '-'}</td>
                        <td>${item.product_name ?? '-'}</td>
                        <td>${item.description ?? '-'}</td>
                        <td>${item.purchase_order_id ?? '-'}</td>
                        <td>${item.vendor_name ?? '-'}</td>
                        <td>${item.vendor_company_name ?? '-'}</td>
                        <td>${item.vendor_email ?? '-'}</td>
                        <td>${item.contact_no ?? '-'}</td>
                    </tr>
                `).join('');

                tbody.innerHTML = rows || '<tr><td colspan="10">No records found.</td></tr>';
                totalPages = res.pagination.totalPages;

                // Pagination UI
                let pagHtml = '';
                let maxPages = 5;
                let start = Math.max(1, currentPage - 2);
                let end = Math.min(totalPages, start + maxPages - 1);

                if (start > 1) pagHtml += `<li><span class="page-link" onclick="goToPage(1)">1</span></li><li><span class="page-link">...</span></li>`;
                for (let i = start; i <= end; i++) {
                    pagHtml += `<li class="${i === currentPage ? 'active' : ''}"><span class="page-link" onclick="goToPage(${i})">${i}</span></li>`;
                }
                if (end < totalPages) pagHtml += `<li><span class="page-link">...</span></li><li><span class="page-link" onclick="goToPage(${totalPages})">${totalPages}</span></li>`;

                pagLinks.innerHTML = pagHtml;
                pagInfo.innerHTML = `Showing ${(res.pagination.from)} to ${(res.pagination.to)} of ${res.pagination.totalItems} rows`;
            } else {
                tbody.innerHTML = `<tr><td colspan="10">Failed to fetch data.</td></tr>`;
            }
        });
}

function goToPage(p) {
    currentPage = p;
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

// Initial load
fetchReport(true);
</script>
<script>
    document.getElementById('resetFilters').addEventListener('click', function () {
    // Clear all form fields
    const form = document.getElementById('filterForm');
    form.reset();

    // Clear search input
    document.getElementById('searchKeyword').value = '';

    // If using select2, reset it
    if ($('.select2').length) {
        $('.select2').val('').trigger('change');
    }

    // Fetch full report again
    fetchReport(true);
});

</script>
@endsection
