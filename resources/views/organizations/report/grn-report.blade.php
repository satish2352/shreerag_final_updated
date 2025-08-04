<!-- Static Table Start -->
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

       <form id="filterForm" method="GET" action="{{ route('grn-report') }}" target="_blank">

    <input type="hidden" name="export_type" id="export_type" />
      
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Vendor Name</label>
                <select class="form-control select2" name="vendor_name">
                    <option value="">All Vendors</option>
                    @foreach($getProjectName as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
                <div class="col-md-2">
                <label>PO Number</label>
                <select class="form-control select2" name="purchase_orders_id">
                    <option value="">All PO Numbers</option>
                   @foreach($getPurchaseOrder as $id => $name)
    <option value="{{ $id }}">{{ $name }}</option>
@endforeach

                </select>
            </div>
         <div class="col-md-3">
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
        <div>   <span id="totalCount">0</span></div>
        </div>
        </div>

        {{-- 🔹 Search and Export --}}
        <div class="row mb-2">
            <div class="col-md-6 d-flex justify-content-center">
               
                <button type="submit" class="btn btn-primary filterbg">Filter</button>
             <button type="button" class="btn btn-secondary ms-2" id="resetFilters" style="margin-left: 10px;">
        Reset
    </button>
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
  <table class="table table-bordered">
    <thead>
      <tr>

                                                 <th data-field="id">ID</th>
                                                 <th data-field="updated_at" data-editable="false">Date</th>
                                                <th data-field="purchase_orders_id" data-editable="false">PO Number</th>
                                                <th data-field="grn_no_generate" data-editable="false">GRN Number</th>
                                                <th data-field="vendor_name" data-editable="false">Vendor Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Vendor Company Name</th>
                                                {{-- <th data-field="grn" data-editable="false">GRN</th> --}}
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

    <script>
let currentPage = 1, pageSize = 10;

function getStatusLabel(id) {
    if (id == 1114) return 'Rejected';
    if ([1115, 1121, 1117].includes(id)) return 'Accepted';
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

    fetch(`{{ route('grn-report-ajax') }}?${params.toString()}`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('reportBody');
            const pagLinks = document.getElementById('paginationLinks');
            const pagInfo = document.getElementById('paginationInfo');

            if (res.status) {
                console.log(res,"resresresresres");
                
                document.getElementById('totalCount').innerText = res.pagination.totalItems || 0;
               const rows = res.data.map((item, i) => {
   
    return `
        <tr>
            <td>${((res.pagination.currentPage - 1) * pageSize) + i + 1}</td>
            <td>${item.updated_at ? new Date(item.updated_at).toLocaleDateString('en-IN') : '-'}</td>
            <td>${item.purchase_orders_id}</td>
            <td>${item.grn_no_generate || '-'}</td>
            <td>${item.vendor_name || '-'}</td>
             <td>${item.vendor_company_name || '-'}</td>
           
        </tr>
    `;
}).join('');
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

// Initial load
fetchReport(true);
</script>
@endsection

