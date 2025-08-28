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
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="date" data-editable="false"> Sent Date</th>
                                                    <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                 <th data-field="grand_total_amount" data-editable="false">Grand Total Amount</th>
                                                <th data-field="po_validity" data-editable="false">PO Validity</th>
                                                <th data-field="customer_payment_terms" data-editable="false">Payment Terms
                                                </th>
                                                <th data-field="customer_terms_condition" data-editable="false">Terms
                                                    Condition</th>
                                                <th data-field="remarks" data-editable="false">Remark</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td> {{ $data->created_at ? $data->created_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                     <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td><strong>{{ucwords($data->grand_total_amount)}}</strong></td>
                                                    <td>{{ ucwords($data->po_validity) }}</td>
                                                    <td>{{ ucwords($data->customer_payment_terms) }}</td>
                                                    <td>{{ ucwords($data->customer_terms_condition) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    <td>
                                                        @if ($data->is_approved_production == 1)
                                                            <button data-toggle="tooltip" title="Edit"
                                                                class="pd-setting-ed" disabled>
                                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            </button>
                                                            <button data-toggle="tooltip" title="Trash"
                                                                class="pd-setting-ed" disabled>
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        @else
                                                            <div>
                                                                <a
                                                                    href="{{ route('edit-business', base64_encode($data->id)) }}">
                                                                    <button data-toggle="tooltip" title="Edit"
                                                                        class="pd-setting-ed">
                                                                        <i class="fa fa-pencil-square-o"
                                                                            aria-hidden="true"></i>
                                                                    </button>
                                                                </a>
                                                                <button data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed delete-button"
                                                                    data-url="{{ route('delete-business', base64_encode($data->id)) }}">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('js/password-meter/pwstrength-bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/password-meter/zxcvbn.js') }}"></script>
    <script src="{{ asset('js/password-meter/password-meter-active.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
    <script>
        $.noConflict();
        jQuery(document).ready(function($) {
            $(".delete-button").click(function(event) {
                event.preventDefault(); // Prevent default button behavior
                var deleteUrl = $(this).data('url'); // Get the delete URL from data attribute

                // Show SweetAlert confirmation
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    text: 'Do you want to delete this Business Entry?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete it',
                    cancelButtonText: 'No, Keep it',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // If confirmed, navigate to the delete URL
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
@endsection
