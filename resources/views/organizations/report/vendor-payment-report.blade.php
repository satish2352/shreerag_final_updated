
@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Vendor Payment Report</h1>
                              
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">

       <form id="filterForm" method="GET" action="{{ route('list-vendor-payment-report') }}" target="_blank">

    <input type="hidden" name="export_type" id="export_type" />
      
        <div class="row mb-3">
             <div class="col-md-3">
                <label>Vendor Name</label>
                <select class="form-control select2" name="vendor_name">
                    <option value="">All Vendors</option>
                    @foreach($getVendorName as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
                <div class="col-md-3">
                <label>PO Number</label>
                <select class="form-control select2" name="purchase_orders_id">
                    <option value="">All PO Numbers</option>
                   @foreach($getPurchaseOrder as $id => $name)
    <option value="{{ $id }}">{{ $name }}</option>
@endforeach

                </select>
            </div>
             <div class="col-md-3">
              <label>Status</label>
            <select name="grn_status_sanction" class="form-control" id="grn_status_sanction">
                <option value="">Select Status</option>
                <option value="6003">Paid</option>
                <option value="6000,6001,6002">Pending</option>
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
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
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
                                               <th data-field="updated_at" data-editable="false">Payment Release Date</th>
                                                 <th data-field="purchase_orders_id" data-editable="false">Purchase Order No.</th>
                                                   <th data-field="grn_status_sanction" data-editable="false">GRN No.</th>
                                                    <th data-field="from_place" data-editable="false">Payment Status</th>
                                                <th data-field="vendor_name" data-editable="false">Vendor Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Vendor Company Name</th>
                                                <th data-field="" data-editable="false">Vendor Email</th>
                                                <th data-field="contact_no" data-editable="false">Contact No.</th>
                                                <th data-field="purchase_orders_id" data-editable="false">PO Number</th>
                                                <th data-field="invoice_date" data-editable="false">Invoice Date</th>
                                                {{-- <th data-field="invoice_date" data-editable="false">Invoice Amount</th> --}}
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
<?php
// dd($data);
// die();
?>
<script>
    window.APP_URL = "{{ config('app.url') }}";
</script>

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
    formData.forEach((val, key) => params.append(key, val));

    fetch(`{{ route('vendor-payment-ajax') }}?${params.toString()}`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('reportBody');
            const pagLinks = document.getElementById('paginationLinks');
            const pagInfo = document.getElementById('paginationInfo');

           if (res.status && Array.isArray(res.data)) {
          
 const rows = res.data.map((item, i) => {
    // const poUrl = `${window.APP_URL}securitydept/list-po-details/${btoa(item.purchase_id)}/${btoa(item.purchase_orders_id)}`;

    return `
        <tr>
            <td>${((res.pagination.currentPage - 1) * pageSize) + i + 1}</td>
            <td>${item.updated_at ? new Date(item.updated_at).toLocaleDateString('en-IN') : '-'}</td>
             <td>${item.purchase_orders_id ?? '-'}</td>
              <td>${item.grn_no_generate ?? '-'}</td>
                <td>
            ${
                item.grn_status_sanction == '6003'
                    ? 'Paid'
                    : ['6000', '6001', '6002'].includes(item.grn_status_sanction)
                    ? 'Pending'
                    : '-'
            }
        </td>
            <td>${item.vendor_name ?? '-'}</td>
            <td>${item.vendor_company_name ?? '-'}</td>
            <td>${item.vendor_email ?? '-'}</td>
            <td>${item.contact_no ?? '-'}</td>
             <td>${item.purchase_orders_id ?? '-'}</td>
              <td>${item.invoice_date ?? '-'}</td>
                 
             
          
          
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
<script>
    document.getElementById('project_name').addEventListener('change', function () {
        let projectId = this.value;
        let productSelect = document.getElementById('business_details_id'); // ✅ must match the ID

        // Clear options
        productSelect.innerHTML = '<option value="">All Product Name</option>';

        if (!projectId) return;

        let url = '{{ url("designdept/get-products-by-project") }}/' + projectId;

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

