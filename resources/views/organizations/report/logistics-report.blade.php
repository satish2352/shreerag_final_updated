@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Completed Product Logistics to Fianance Report</h1>

                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">

                                {{-- <form id="filterForm" method="GET" action="{{ route('list-logistics-report') }}"> --}}
                                <form id="filterForm" method="GET" action="{{ route('logistics-ajax') }}">

                                    <input type="hidden" name="export_type" id="export_type" />

                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label>Project Name</label>
                                            <select class="form-control select2" name="project_name" id="project_name">
                                                <option value="">All Projects</option>
                                                @foreach ($getProjectName as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Product Name</label>
                                            <select class="form-control select2" name="business_details_id"
                                                id="business_details_id">
                                                <option value="">All Product Name</option>
                                                {{-- Product options will be populated via JS --}}
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
                                    </div>
                                </form>




                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>

                                                <th data-field="id">ID</th>
                                                <th data-field="updated_at" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="title" data-editable="false">customer Name</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="completed_quantity" data-editable="false">Completed
                                                    Production</th>
                                                <th data-field="remaining_quantity" data-editable="false">Balance Quantity
                                                </th>
                                                <th data-field="from_place" data-editable="false">Form Place</th>
                                                <th data-field="to_place" data-editable="false">To Place</th>
                                                <th data-field="transport_name" data-editable="false">Transport Name</th>
                                                <th data-field="vehicle_name" data-editable="false">Vehicle Type</th>
                                                <th data-field="truck_no" data-editable="false">Truck No.</th>
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

            fetch(`{{ route('logistics-ajax') }}?${params.toString()}`)
                .then(res => res.json())
                .then(res => {
                    const tbody = document.getElementById('reportBody');
                    const pagLinks = document.getElementById('paginationLinks');
                    const pagInfo = document.getElementById('paginationInfo');

                    if (res.status && Array.isArray(res.data)) {

                        const rows = res.data.map((item, i) => {
                            const poUrl =
                                `${window.APP_URL}securitydept/list-po-details/${btoa(item.purchase_id)}/${btoa(item.purchase_orders_id)}`;

                            return `
        <tr>
            <td>${((res.pagination.currentPage - 1) * pageSize) + i + 1}</td>
            <td>${item.updated_at ? new Date(item.updated_at).toLocaleDateString('en-IN') : '-'}</td>
            <td>${item.project_name ?? '-'}</td>
            <td>${item.customer_po_number ?? '-'}</td>
            <td>${item.title ?? '-'}</td>
            <td>${item.product_name ?? '-'}</td>
            <td>${item.quantity ?? '-'}</td>
            <td>${item.completed_quantity ?? '-'}</td>
            <td>${item.remaining_quantity ?? '-'}</td>
            <td>${item.from_place ?? '-'}</td>
            <td>${item.to_place ?? '-'}</td>
            <td>${item.transport_name ?? '-'}</td>
            <td>${item.vehicle_name ?? '-'}</td>
            <td>${item.truck_no ?? '-'}</td>
                    
          
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

        // document.getElementById('filterForm').addEventListener('submit', e => {
        //     e.preventDefault();
        //     fetchReport(true);
        // });
        document.getElementById('filterForm').addEventListener('submit', e => {

            // IF export button clicked → allow normal form submit
            if (document.getElementById('export_type').value) {
                return;
            }

            // Otherwise → AJAX filter
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
@endsection
