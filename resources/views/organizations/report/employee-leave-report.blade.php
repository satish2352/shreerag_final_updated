@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Employee Leaves Report</h1>

                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">

                                <form id="filterForm" method="GET" action="{{ route('employee-leave-ajax') }}"
                                    target="_blank">

                                    <input type="hidden" name="export_type" id="export_type" />

                                    <div class="row mb-3">

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
                                                    <option value="{{ $m }}">
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>From Date</label>
                                            <input type="date" name="from_date" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label>To Date</label>
                                            <input type="date" name="to_date" class="form-control">
                                        </div>
                                    </div>
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
                                                <th rowspan="2">Sr. No.</th>
                                                <th rowspan="2">Employee Name</th>
                                                <th rowspan="2">Year</th>
                                                <th colspan="3">Opening</th>
                                                <th colspan="3">Used</th>
                                                <th colspan="3">Balanced</th>
                                                <th rowspan="2">Action</th>
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

                                <div class="modal fade" id="leaveDetailsModal" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Employee Leave Details</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Date</th>
                                                            <th>Leave Type</th>
                                                            <th>Days</th>
                                                            <th>Reason</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="leaveDetailsBody"></tbody>
                                                </table>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.APP_URL = "{{ config('app.url') }}";
    </script>

    <script>
        let currentPage = 1,
            pageSize = 10;



        function fetchReport(reset = false) {
            if (reset) currentPage = 1;

            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            formData.append('pageSize', pageSize);
            formData.append('currentPage', currentPage);
            formData.append('search', document.getElementById('searchKeyword').value);

            const params = new URLSearchParams();
            formData.forEach((val, key) => params.append(key, val));

            fetch(`{{ route('employee-leave-ajax') }}?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    const tbody = document.getElementById('reportBody');
                    const pagLinks = document.getElementById('paginationLinks');
                    const pagInfo = document.getElementById('paginationInfo');

                    if (res.status && Array.isArray(res.data)) {

                        const rows = res.data.map((item, i) => {

                            return `

                                <tr>
                                <td>${((currentPage - 1) * pageSize) + i + 1}</td>
       <td>${item.f_name ?? ''} ${item.m_name ?? ''} ${item.l_name ?? ''}</td>

        <td>${item.year ?? '-'}</td>

        <td>${item.opening_cl ?? 0}</td>
        <td>${item.opening_pl ?? 0}</td>
        <td>${item.opening_sl ?? 0}</td>

        <td>${item.used_cl ?? 0}</td>
        <td>${item.used_pl ?? 0}</td>
        <td>${item.used_sl ?? 0}</td>

        <td>${item.closed_cl ?? 0}</td>
        <td>${item.closed_pl ?? 0}</td>
        <td>${item.closed_sl ?? 0}</td>
        <td>
        <button class="btn btn-sm btn-info" onclick="viewDetails(${item.employee_id}, ${item.year})">
            <i class="fa fa-eye"></i>
        </button>
    </td>
    </tr>

        
    `;
                        }).join('');



                        tbody.innerHTML = rows || '<tr><td colspan="14">No records found.</td></tr>';

                        // Pagination
                        let pagHtml = '',
                            totalPages = res.pagination.totalPages;
                        let start = Math.max(1, currentPage - 2),
                            end = Math.min(totalPages, start + 4);

                        if (start > 1) pagHtml +=
                            `<li><a class="page-link" onclick="goToPage(1)">1</a></li><li>...</li>`;
                        for (let i = start; i <= end; i++) {
                            pagHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                    <a class="page-link" onclick="goToPage(${i})">${i}</a>
                                </li>`;
                        }
                        if (end < totalPages) pagHtml +=
                            `<li>...</li><li><a class="page-link" onclick="goToPage(${totalPages})">${totalPages}</a></li>`;

                        pagLinks.innerHTML = pagHtml;
                        pagInfo.innerHTML =
                            `Showing ${res.pagination.from} to ${res.pagination.to} of ${res.pagination.totalItems}`;
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

        // Initial load
        fetchReport(true);
    </script>
    <script>
        document.getElementById('project_name').addEventListener('change', function() {
            let projectId = this.value;
            let productSelect = document.getElementById('business_details_id'); // ✅ must match the ID

            // Clear options
            productSelect.innerHTML = '<option value="">All Product Name</option>';

            if (!projectId) return;

            let url = '{{ url('designdept/get-products-by-project') }}/' + projectId;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        data.products.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.id;
                            option.textContent = product.name;
                            productSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error("Failed to load products:", error);
                });
        });
    </script>
    <script>
        function viewDetails(employeeId, year) {
            fetch(`{{ url('financedept/employee-leave-details') }}?employee_id=${employeeId}&year=${year}`)
                .then(res => res.json())
                .then(res => {
                    let rows = '';
                    let i = 1;

                    if (res.data.length > 0) {
                        res.data.forEach(item => {
                            rows += `
                        <tr>
                            <td>${i++}</td>
                            <td>${item.leave_start_date} to ${item.leave_end_date}</td>
                            <td>${item.leave_name}</td>
                            <td>${item.leave_count}</td>
                            <td>${item.reason ?? '-'}</td>
                        </tr>
                    `;
                        });
                    } else {
                        rows = `<tr>
                    <td colspan="5" class="text-center">No leave records</td>
                </tr>`;
                    }

                    document.getElementById('leaveDetailsBody').innerHTML = rows;

                    $('#leaveDetailsModal').modal('show');
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to load leave details');
                });
        }
    </script>
@endsection
