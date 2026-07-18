@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Purchase Order<span class="table-project-n">Sent To Vendor</span></h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <form method="GET" action="{{ url()->current() }}">
                                        <input type="hidden" name="per_page" value="{{ (int) request('per_page', 10) }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-3">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control" placeholder="Search Product Name">
                                            </div>
                                            <div class="col-md-3 d-flex flex-wrap align-items-center" style="gap:6px;">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                                <a id="downloadAllPoBtn"
                                                    href="{{ route('download-all-po-businesswise', request()->route('id')) }}"
                                                    class="btn btn-success"><i class="fa fa-download"></i> Download All PO</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar"> --}}
                                        <thead>
                                            <tr>
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="updated_at" data-editable="false">Sent To Vendor Date</th>
                                                <th data-field="purchase_orders_id" data-editable="false">Purchase Order ID
                                                </th>
                                                <th data-field="client_name" data-editable="false">Client Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Client Company
                                                    Name</th>
                                                <th data-field="email" data-editable="false">Email</th>
                                                <th data-field="contact_no" data-editable="false">Phone Number</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>
                                                    <td>{{ ($data_output->currentPage() - 1) * $data_output->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{ $data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') : 'N/A' }}</td>
                                                    <td>{{ $data->purchase_order_id }}</td>
                                                    <td>{{ $data->vendor_name }}</td>
                                                    <td>{{ $data->vendor_company_name }}</td>
                                                    <td>{{ $data->vendor_email }}</td>
                                                    <td>{{ $data->contact_no }}</td>
                                                    <td>
                                                        <div style="display: inline-block; align-items: center;">
                                                            <a
                                                                href="{{ route('check-details-of-po-before-send-vendor', $data->purchase_order_id) }}"><button
                                                                    data-toggle="tooltip" title="View Details"
                                                                    class="btn btn-sm btn-bg-colour"> View
                                                                    Details</button></a> &nbsp;
                                                            &nbsp; &nbsp;
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                                        <div class="d-flex align-items-center mb-2">
                                            <label for="perPageSelect" style="font-size:12px; font-weight:600; margin:0 8px 0 0; white-space:nowrap;">
                                                Show
                                            </label>
                                            <select id="perPageSelect" class="form-control form-control-sm" style="width:auto;"
                                                onchange="changePerPage(this.value)">
                                                @foreach([5, 10, 20, 100] as $size)
                                                    <option value="{{ $size }}" {{ (int) request('per_page', 10) === $size ? 'selected' : '' }}>
                                                        {{ $size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span style="font-size:12px; color:#6c757d; margin-left:8px; white-space:nowrap;">per page</span>
                                        </div>
                                        <div>
                                            {{ $data_output->links() }}
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
        function changePerPage(size) {
            var url = new URL(window.location.href);
            url.searchParams.set('per_page', size);
            url.searchParams.set('page', 1); // jump back to first page when page size changes
            window.location.href = url.toString();
        }

        (function () {
            var downloadBtn = document.getElementById('downloadAllPoBtn');
            if (!downloadBtn) {
                return;
            }

            var originalHtml = downloadBtn.innerHTML;
            var pollInterval = null;
            var safetyTimeout = null;

            function getCookie(name) {
                var match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
                return match ? decodeURIComponent(match[1]) : null;
            }

            function deleteCookie(name) {
                document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            }

            function resetButton() {
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
                if (safetyTimeout) {
                    clearTimeout(safetyTimeout);
                    safetyTimeout = null;
                }
                downloadBtn.innerHTML = originalHtml;
                downloadBtn.classList.remove('disabled');
                downloadBtn.style.pointerEvents = '';
                downloadBtn.removeAttribute('aria-disabled');
                deleteCookie('downloadToken');
            }

            downloadBtn.addEventListener('click', function (e) {
                e.preventDefault();

                if (downloadBtn.classList.contains('disabled')) {
                    return;
                }

                var token = Date.now() + '-' + Math.floor(Math.random() * 1e6);

                downloadBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Preparing...';
                downloadBtn.classList.add('disabled');
                downloadBtn.style.pointerEvents = 'none';
                downloadBtn.setAttribute('aria-disabled', 'true');

                var baseUrl = downloadBtn.getAttribute('href');
                var separator = baseUrl.indexOf('?') === -1 ? '?' : '&';
                var url = baseUrl + separator + 'download_token=' + encodeURIComponent(token);

                pollInterval = setInterval(function () {
                    var cookieValue = getCookie('downloadToken');
                    if (cookieValue === token) {
                        resetButton();
                    }
                }, 500);

                safetyTimeout = setTimeout(function () {
                    resetButton();
                }, 120000);

                window.location.href = url;
            });
        })();
    </script>
@endsection
