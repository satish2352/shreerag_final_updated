@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1> Item Stock Report</h1>

                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">

                                <form id="filterForm" method="GET"
                                    action="{{ route('list-itemwise-vendor-rate-report') }}">

                                    <input type="hidden" name="export_type" id="export_type" />
                                    <div class="row mb-5">
                                        <div class="col-md-2">
                                            <label>Part Item</label>
                                            <select class="form-control select2" name="description">
                                                <option value="">All Part Item</option>
                                                @foreach ($getPartItemName as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
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
                                                    <option value="{{ $m }}">
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
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
                                            <label>Total Records</label>
                                            <div> <span id="totalCount">0</span></div>
                                        </div>
                                    </div>

                                    {{-- 🔹 Search and Export --}}
                                    <div class="row mb-2">
                                        <div class="col-md-6 d-flex justify-content-center">

                                            <button type="submit" class="btn btn-primary filterbg">Filter</button>
                                            <button type="button" class="btn btn-secondary ms-2" id="resetFilters"
                                                style="margin-left: 10px;">
                                                Reset
                                            </button>
                                        </div>
                                        <div class="col-md-6 text-end d-flex">
                                            <input type="text" class="form-control d-flex align-self-center"
                                                id="searchKeyword" style="margin-right: 23px;" placeholder="Search...">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    id="exportDropdown" data-toggle="dropdown" aria-expanded="false"
                                                    style="float: right;">
                                                    <i class="fa fa-download"></i> Export
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                                    <li><a class="dropdown-item" href="#" id="exportExcel">Export to
                                                            Excel</a></li>
                                                    <li><a class="dropdown-item" href="#" id="exportPdf">Export to
                                                            PDF</a></li>
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
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>

                                                <th data-field="id">Sr.No.</th>
                                                <!-- <th data-field="received_updated_at" data-editable="false">Received Date
                                                                                                            </th> -->
                                                <th data-field="issue_updated_at" data-editable="false">Date
                                                </th>
                                                <th data-field="balance_quantity" data-editable="false">Item Name</th>
                                                <th data-field="description" data-editable="false">Vendor Name</th>
                                                <th data-field="received_quantity" data-editable="false">Vendor Company Name
                                                </th>

                                                <th data-field="issue_quantity" data-editable="false">Rate</th>


                                            </tr>
                                        </thead>
                                        <tbody id="reportBody">
                                            <tr>
                                                <td colspan="6">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="pagination-wrapper">
                                        <div id="paginationInfo"></div>
                                        <ul class="pagination" id="paginationLinks"></ul>
                                    </div>

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
    </div>
  @push('scripts')
  <script>
$(document).ready(function () {

    // ==========================  
    // Initialize Select2  
    // ==========================
    $('.select2').select2({
        width: '100%',
        placeholder: 'All Part Item',
        allowClear: true
    });

    // Pagination values
    let currentPage = 1;
    let pageSize = 10;

    // ==========================
    // Fetch Report (AJAX)
    // ==========================
    function fetchReport(reset = false) {

        if (reset) currentPage = 1;

        const formData = new FormData(document.getElementById('filterForm'));
        formData.append('pageSize', pageSize);
        formData.append('currentPage', currentPage);
        formData.append('search', $('#searchKeyword').val());

        const params = new URLSearchParams();
        formData.forEach((v, k) => params.append(k, v));

        fetch(`{{ route('list-itemwise-vendor-rate-report-ajax') }}?${params.toString()}`)
            .then(res => res.json())
            .then(res => {

                const tbody = $('#reportBody');
                const pagLinks = $('#paginationLinks');
                const pagInfo = $('#paginationInfo');

                if (!res.status) {
                    tbody.html('<tr><td colspan="7">Failed to fetch data.</td></tr>');
                    return;
                }

                $('#totalCount').text(res.pagination.totalItems || 0);

                // ==========================
                // Table rows
                // ==========================
                if (res.data.length > 0) {
                    let rows = '';

                    res.data.forEach((item, i) => {
                        rows += `
                            <tr>
                                <td>${((res.pagination.currentPage - 1) * pageSize) + i + 1}</td>
                                <td>${item.updated_at ? new Date(item.updated_at).toLocaleDateString('en-IN') : '-'}</td>
                                <td>${item.description || '-'}</td>
                                <td>${item.vendor_name || '-'}</td>
                                <td>${item.vendor_company_name || '-'}</td>
                                <td>${item.rate || '-'}</td>
                            </tr>
                        `;
                    });

                    tbody.html(rows);

                } else {
                    tbody.html('<tr><td colspan="7">No records found.</td></tr>');
                }

                // ==========================
                // Pagination
                // ==========================
                let totalPages = res.pagination.totalPages;
                let html = '';
                let start = Math.max(1, currentPage - 2);
                let end = Math.min(totalPages, start + 4);

                if (start > 1) {
                    html += `<li><a class="page-link" onclick="goToPage(1)">1</a></li><li>...</li>`;
                }

                for (let i = start; i <= end; i++) {
                    html += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" onclick="goToPage(${i})">${i}</a>
                        </li>`;
                }

                if (end < totalPages) {
                    html += `<li>...</li><li><a class="page-link" onclick="goToPage(${totalPages})">${totalPages}</a></li>`;
                }

                pagLinks.html(html);

                pagInfo.html(`Showing ${res.pagination.from} to ${res.pagination.to} of ${res.pagination.totalItems}`);
            });
    }

    // ==========================
    // Go to Page
    // ==========================
    window.goToPage = function (page) {
        currentPage = page;
        fetchReport();
    };

    // ==========================
    // Events
    // ==========================
    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        fetchReport(true);
    });

    $('#searchKeyword').on('input', function () {
        fetchReport(true);
    });

    $('#exportPdf').on('click', function () {
        $('#export_type').val(1);
        $('#filterForm').submit();
    });

    $('#exportExcel').on('click', function () {
        $('#export_type').val(2);
        $('#filterForm').submit();
    });

    // First Load
    fetchReport(true);

});
</script>

 
      @endpush
@endsection
