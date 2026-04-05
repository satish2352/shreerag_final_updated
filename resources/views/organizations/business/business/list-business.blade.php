@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Business <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        <div class="login-horizental cancel-wp pull-left">
                                            <a href="{{ route('add-business') }}"><button
                                                    class="btn btn-sm btn-primary login-submit-cs" type="submit">Add
                                                    Business</button></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10"></div>
                                </div>
                            </div>
                        </div>
                        @if (Session::get('status') == 'success')
                            <div class="alert alert-success alert-success-style1">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <i class="fa fa-check adminpro-checked-pro admin-check-pro" aria-hidden="true"></i>
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
                                                    class="form-control"
                                                    placeholder="Search Project Name / Product Name  / PO No.">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
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
                                                <th data-field="date" data-editable="false"> Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="grand_total_amount" data-editable="false">Grand Total Amount
                                                </th>
                                                <th data-field="po_validity" data-editable="false">PO Validity</th>
                                                <th data-field="customer_payment_terms" data-editable="false">Payment Terms
                                                </th>
                                                <th data-field="customer_terms_condition" data-editable="false">Terms
                                                    Condition</th>
                                                <th data-field="remarks" data-editable="false">Remark</th>
                                                <th data-field="business_pdf" data-editable="false">File</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>
                                                    <td>{{ $data_output->firstItem() + $loop->index }}</td>

                                                    <td>
                                                        {{ optional($data->created_at)->format('d-m-Y') ?? 'N/A' }}
                                                    </td>

                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td><strong>{{ ucwords($data->grand_total_amount) }}</strong></td>
                                                    <td>{{ ucwords($data->po_validity) }}</td>
                                                    <td>{{ ucwords($data->customer_payment_terms) }}</td>
                                                    <td>{{ ucwords($data->customer_terms_condition) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    <td>
                                                        @if (!empty($data->business_pdf))
                                                            <a href="{{ Config::get('FileConstant.BUSINESS_PDF_VIEW') }}{{ $data->business_pdf }}"
                                                                target="_blank" class="btn btn-sm btn-info"
                                                                style="color: #fff">
                                                                <i class="fa fa-file-pdf-o"></i> View PDF
                                                            </a>
                                                        @else
                                                            <span class="text-muted">No File</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div>
                                                            @if ($data->is_approved_production == '1')
                                                                <button data-toggle="tooltip" title="Cannot Edit"
                                                                    class="pd-setting-ed btn-disabled-action" disabled>
                                                                    <i class="fas fa-pen-square" aria-hidden="true"></i>
                                                                </button>
                                                            @else
                                                                <a href="{{ route('edit-business', base64_encode($data->id)) }}">
                                                                    <button data-toggle="tooltip" title="Edit"
                                                                        class="pd-setting-ed">
                                                                        <i class="fas fa-pen-square" aria-hidden="true"></i>
                                                                    </button>
                                                                </a>
                                                            @endif

                                                            @if ($data->is_approved_production == '1' || $data->is_sent_to_estimation > 0)
                                                                <button data-toggle="tooltip" title="Cannot Delete — Already Forwarded"
                                                                    class="pd-setting-ed btn-disabled-action" disabled>
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            @else
                                                                <button data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed delete-button"
                                                                    data-url="{{ route('delete-business', base64_encode($data->id)) }}">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p>
                                                Showing {{ $data_output->firstItem() }} to {{ $data_output->lastItem() }}
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
    <style>
        .btn-disabled-action {
            opacity: 0.4;
            cursor: not-allowed !important;
            color: #999 !important;
            pointer-events: none;
        }
        .btn-disabled-action .fa-trash {
            color: #cc0000 !important;
            opacity: 0.4;
        }
    </style>
    @push('scripts')
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', '.delete-button', function(e) {
                    e.preventDefault();

                    var deleteUrl = $(this).data('url');

                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'Do you want to delete this Business Entry?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete it',
                        cancelButtonText: 'No, Keep it',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            // If your route is GET based (not recommended):
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
