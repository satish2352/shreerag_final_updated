@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Exceed Amount Requests — Pending Owner Review</h1>
                            </div>
                        </div>
                        @if (session('status') == 'success')
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Success!</strong> {{ session('msg') }}
                            </div>
                        @endif
                        @if (session('status') == 'error')
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error!</strong> {{ session('msg') }}
                            </div>
                        @endif
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    @if(isset($message) || (isset($data_output) && (is_array($data_output) ? count($data_output) === 0 : $data_output->isEmpty())))
                                        <div class="alert alert-info mt-3">No exceed amount requests pending.</div>
                                    @else
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">#</th>
                                                <th data-field="date" data-editable="false">Date Requested</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="po_number" data-editable="false">PO Number</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="business_limit" data-editable="false">Business Limit (&#8377;)</th>
                                                <th data-field="estimator_amount" data-editable="false">Estimator Amount (&#8377;)</th>
                                                <th data-field="difference" data-editable="false">Difference (&#8377;)</th>
                                                <th data-field="reason" data-editable="false">Estimator Reason</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') : 'N/A' }}</td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ $data->customer_po_number }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ number_format($data->total_amount, 2) }}</td>
                                                    <td>
                                                        <span style="color:#c0392b;font-weight:bold;">
                                                            {{ number_format($data->total_estimation_amount, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span style="color:#e67e22;font-weight:bold;">
                                                            +{{ number_format($data->exceed_difference, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $data->exceed_remark ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('edit-business', base64_encode($data->business_id)) }}">
                                                            <button class="btn btn-sm btn-warning" type="button"
                                                                style="color:#fff;">Update Amount</button>
                                                        </a>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-toggle="modal"
                                                            data-target="#rejectExceedModal{{ $data->business_details_id }}">
                                                            Reject
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject modals — one per row (matches the per-row modal pattern used elsewhere
         in this project, e.g. resources/views/organizations/store/list/list-material-sent-to-purchase.blade.php) --}}
    @if(!(isset($message) || (isset($data_output) && (is_array($data_output) ? count($data_output) === 0 : $data_output->isEmpty()))))
        @foreach ($data_output as $data)
            <div class="modal fade" id="rejectExceedModal{{ $data->business_details_id }}" tabindex="-1" role="dialog"
                aria-labelledby="rejectExceedModalLabel{{ $data->business_details_id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('reject-exceed-amount-request') }}" method="POST"
                            class="reject-exceed-form" id="rejectExceedForm{{ $data->business_details_id }}">
                            @csrf
                            <input type="hidden" name="business_id"
                                value="{{ base64_encode($data->business_details_id) }}">
                            <div class="modal-header" style="background:#c0392b; color:#fff;">
                                <h5 class="modal-title" id="rejectExceedModalLabel{{ $data->business_details_id }}">
                                    Reject Exceed Amount Request
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    style="color:#fff;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    <strong>{{ ucwords($data->product_name) }}</strong>
                                    ({{ ucwords($data->project_name) }} — {{ $data->customer_po_number }})
                                </p>
                                <div class="form-group">
                                    <label for="reject_remark{{ $data->business_details_id }}">Why are you
                                        rejecting this request? <span class="text-danger">*</span></label>
                                    <textarea class="form-control reject-remark-input" rows="3"
                                        id="reject_remark{{ $data->business_details_id }}" name="reject_remark"
                                        placeholder="Enter reject remark" required></textarea>
                                    <div class="text-danger reject-remark-error" style="display:none; margin-top:5px;">
                                        Reject remark is required.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger reject-exceed-submit-btn">Confirm
                                    Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $(document).on('click', '.reject-exceed-submit-btn', function(e) {
                e.preventDefault();
                var $form = $(this).closest('form.reject-exceed-form');
                var $textarea = $form.find('.reject-remark-input');
                var $error = $form.find('.reject-remark-error');
                var remark = $.trim($textarea.val());

                if (!remark) {
                    $error.show();
                    $textarea.addClass('is-invalid').focus();
                    return;
                }
                $error.hide();
                $textarea.removeClass('is-invalid');

                Swal.fire({
                    icon: 'warning',
                    title: 'Reject this request?',
                    text: 'The estimation department will be notified with your remark and asked to revise the amount.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Reject',
                    cancelButtonText: 'Cancel',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $form.trigger('submit');
                    }
                });
            });

            // Clear the inline error as soon as the user starts typing.
            $(document).on('input', '.reject-remark-input', function() {
                var $textarea = $(this);
                if ($.trim($textarea.val())) {
                    $textarea.closest('.form-group').find('.reject-remark-error').hide();
                    $textarea.removeClass('is-invalid');
                }
            });
        });
    </script>
    @endpush
@endsection
