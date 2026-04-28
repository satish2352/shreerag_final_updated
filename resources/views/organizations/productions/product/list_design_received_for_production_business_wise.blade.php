@extends('admin.layouts.master')
@section('content')
    <style>
        .fixed-table-loading {
            display: none;
        }

        #table thead th {
            white-space: nowrap;
        }

        #table thead th {
            width: 300px !important;
            padding-right: 49px !important;
            padding-left: 20px !important;
        }

        .custom-datatable-overright table tbody tr td {
            padding-left: 19px !important;
            padding-right: 5px !important;
            font-size: 14px;
            text-align: left;
        }

        .mb-4 {
            margin-bottom: 4%;
        }

        button.pd-setting-ed {
            border: 1px solid rgb(0 0 0 / 39%) !important;
        }
    </style>

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>New Design and BOM Received For Production <span class="table-project-n"></span></h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                    </div>
                                    <div class="col-lg-10">

                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (Session::get('status') == 'success')
                            <div class="alert alert-success alert-success-style1">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
                            </div>
                        @endif
                        @if (Session::get('status') == 'error')
                            <div class="alert alert-danger alert-mg-b alert-success-style4">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <i class="fa fa-times adminpro-danger-error admin-check-pro" aria-hidden="true"></i>
                                <p><strong>Danger!</strong> {{ Session::get('msg') }}</p>
                            </div>
                        @endif

                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control" placeholder="Search Project Name / Project Name ">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th data-field="id">ID</th>
                                                <th>Project Name</th>
                                                <th>Grand Total Amount</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="total_estimation_amount" data-editable="false">Estimated
                                                    Amount</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="bom_items" data-editable="false">BOM Items</th>
                                                <th data-field="action">Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @forelse($data_output as $data)
                                                <tr>

                                                    <td> {{ ($data_output->currentPage() - 1) * $data_output->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td><b>{{ ucwords($data->grand_total_amount) }}</b></td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td><b>{{ ucwords($data->total_estimation_amount) }}</b></td>
                                                    <td><a class="img-size" target="_blank"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                            alt="Design"> Click to view</a>
                                                    </td>
                                                    <td>
                                                        @if(!empty($data->business_details_id) && !empty($data->design_id))
                                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                                onclick="prodNewReqOpenBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                <i class="fa fa-list"></i> View BOM
                                                            </button>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a
                                                                href="{{ route('accept-design', base64_encode($data->business_details_id)) }}"><button
                                                                    data-toggle="tooltip" title="Accept BOM Estimation"
                                                                    class="accept-btn">Accept</button></a> &nbsp;
                                                            &nbsp; &nbsp;

                                                            <a
                                                                href="{{ route('reject-design-edit', base64_encode($data->business_details_id)) }}"><button
                                                                    data-toggle="tooltip" title="Rejected BOM Estimation"
                                                                    class="reject-btn">Reject</button></a> &nbsp;
                                                            &nbsp; &nbsp;
                                                        </div>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p>
                                                Showing {{ $data_output->firstItem() }} to
                                                {{ $data_output->lastItem() }}
                                                of {{ $data_output->total() }} rows
                                            </p>
                                        </div>

                                        <div class="col-md-6 d-flex justify-content-end mt-3">
                                            {{ $data_output->onEachSide(1)->links() }}
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

{{-- BOM Material Items Modal (production view-only — new requirements received) --}}
@include('organizations.common.bom-material-items-modal', [
    'mode'              => 'view_only',
    'businessId'        => 0,
    'businessDetailsId' => 0,
    'designId'          => 0,
    'bomModalId'        => 'prodNewReqBomModal',
])

    @push('scripts')
        <script>
            function confirmAccept(acceptUrl) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to accept this design and send for production ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, accept it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, redirect to the accept URL
                        window.location.href = acceptUrl;
                    }
                });
                // Prevent the default link action until the user confirms
                return false;
            }

            function prodNewReqOpenBomModal(businessDetailsId, designId) {
                var bdEncoded = btoa(businessDetailsId);
                var dEncoded  = btoa(designId);
                var fetchUrl  = '{{ url("production/get-bom-material-items") }}/' + bdEncoded + '/' + dEncoded;
                openBomModal_prodNewReqBomModal(fetchUrl);
            }
        </script>
    @endpush
@endsection
