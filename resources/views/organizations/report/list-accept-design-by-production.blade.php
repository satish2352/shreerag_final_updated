@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Design Report</h1>

                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">

                                <form id="filterForm" method="GET" action="{{ route('design-ajax') }}" target="_blank">

                                    <input type="hidden" name="export_type" id="export_type" />

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>Project Name</label>
                                            <select class="form-control select2" name="project_name">
                                                <option value="">All Projects</option>
                                                @foreach ($getProjectName as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Status</label>
                                            <select name="production_status_id" class="form-control"
                                                id="production_status_id">
                                                <option value="">Select Status</option>
                                                <option value="1114,1121,1117">Accepted</option>
                                                <option value="1115">Rejected</option>
                                            </select>
                                        </div>
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
                                                    id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false"
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

                                                <th data-field="id">ID</th>
                                                <th data-field="updated_at" data-editable="false">Date</th>
                                                <th data-field="status" data-editable="false">Status</th>
                                                <th data-field="po_number" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                {{-- <th data-field="grn_date" data-editable="false">Description</th> --}}
                                                <th data-field="remark" data-editable="false">Remark</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                {{-- <th data-field="title" data-editable="false">Name</th> --}}
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                {{-- <th data-field="purchase_id" data-editable="false">Remark</th>                                          --}}
                                                <th data-field="reject_reason_prod" data-editable="false">Rejected Reason
                                                </th>
                                                <th data-field="remark_by_design" data-editable="false">Design Remark</th>
                                                <th data-field="bom_image" data-editable="false">BOM</th>
                                                <th data-field="re_design_image" data-editable="false">Design Layout
                                                </th>
                                                <th data-field="re_bom_image" data-editable="false">Revised Design Layout
                                                </th>
                                                <th data-field="reject_reason_prod" data-editable="false">Rejected BOM
                                                </th>

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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        let currentPage = 1,
            pageSize = 10;

        function getStatusLabel(id) {
            if (id == 1115) return 'Rejected';
            if ([1114, 1121, 1117].includes(id)) return 'Accepted';
            return '-';
        }

        function fetchReport(reset = false) {
            if (reset) currentPage = 1;

            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            formData.append('pageSize', pageSize);
            formData.append('currentPage', currentPage);
            formData.append('search', document.getElementById('searchKeyword').value);

            const params = new URLSearchParams();
            formData.forEach((val, key) => params.append(key, val));

            fetch(`{{ route('design-ajax') }}?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    const tbody = document.getElementById('reportBody');
                    const pagLinks = document.getElementById('paginationLinks');
                    const pagInfo = document.getElementById('paginationInfo');

                    if (res.status) {
                        document.getElementById('totalCount').innerText = res.pagination.totalItems || 0;
                        const rows = res.data.map((item, i) => {
                            const designImage = item.design_image ?
                                `<a class="img-size" target="_blank" href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}${item.design_image}">Click to view</a>` :
                                '-';

                            const bomImage = item.bom_image ?
                                `<a class="img-size" target="_blank" href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}${item.bom_image}">Click to download</a>` :
                                '-';

                            const reDesignImage = item.reject_reason_prod && item.re_design_image ?
                                `<a class="img-size" target="_blank" href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}${item.re_design_image}">Click to view</a>` :
                                '-';

                            const reBomImage = item.reject_reason_prod && item.re_bom_image ?
                                `<a class="img-size" target="_blank" href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}${item.re_bom_image}">Click to download</a>` :
                                '-';

                            return `
        <tr>
            <td>${((res.pagination.currentPage - 1) * pageSize) + i + 1}</td>
            <td>${item.updated_at ? new Date(item.updated_at).toLocaleDateString('en-IN') : '-'}</td>
            <td>${getStatusLabel(item.production_status_id)}</td>
            <td>${item.project_name || '-'}</td>
            <td>${item.customer_po_number || '-'}</td>
            <td>${item.remark || '-'}</td>
            <td>${item.product_name || '-'}</td>
            <td>${item.quantity || '-'}</td>
            <td>${item.description || '-'}</td>
            <td>${item.reject_reason_prod || '-'}</td>
            <td>${item.remark_by_design || '-'}</td>
            
            <td>${designImage}</td>
            <td>${bomImage}</td>
            <td>${reDesignImage}</td>
            <td>${reBomImage}</td>
        </tr>
    `;
                        }).join('');
                        tbody.innerHTML = rows || '<tr><td colspan="6">No records found.</td></tr>';

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
@endsection
