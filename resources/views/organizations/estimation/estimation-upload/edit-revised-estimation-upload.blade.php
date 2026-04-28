@extends('admin.layouts.master')
@section('content')
    <div class="">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add Revised Estimation BOM</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (Session::get('status') == 'success')
                                    <div class="col-md-12">
                                        <div class="alert alert-success alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>Success!</strong> {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>Error!</strong> {!! session('msg') !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('update-edit-revised-bom-material-estimation') }}" method="POST"
                                            id="addDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" class="form-control"
                                                value="{{ $business_details_data->id }}" id="business_id"
                                                name="business_id">

                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label for="product_name">Product Name</label>
                                                        <input type="text" class="form-control" id="product_name"
                                                            name="product_name"
                                                            value="{{ old('product_name', $business_details_data->product_name) }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label for="quantity">Quantity</label>
                                                        <input type="text" class="form-control" id="quantity"
                                                            name="quantity"
                                                            value="{{ old('quantity', $business_details_data->quantity) }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label for="description">Description</label>
                                                        <input type="text" class="form-control" id="description"
                                                            name="description"
                                                            value="{{ old('description', $business_details_data->description) }}"
                                                            readonly>
                                                    </div>

                                                    {{-- T-2026-003: BOM Excel upload disabled — BOM is now captured via the structured BOM Material Items modal (T-2026-002). Uncomment to restore.
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mt-4">
                                                        <label for="bom_image">Upload Estimation BOM (Excel, 1KB - 5MB) <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" accept=".xls,.xlsx"
                                                            name="bom_image"> <!-- 5MB -->

                                                        @if ($errors->has('bom_image'))
                                                            <span class="red-text">{{ $errors->first('bom_image') }}</span>
                                                        @endif
                                                    </div>
                                                    --}}

                                                    {{-- T-2026-011: BOM Material Items button — same pattern as edit-estimation-upload.blade.php.
                                                         Guard on design_id so the button is only shown when a design (and hence BOM data) exists.
                                                         Uses business_details_id alias (= businesses_details.id) added to SELECT in editRevisedEstimation(). --}}
                                                    @if(isset($business_details_data) && $business_details_data && $business_details_data->design_id)
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-3">
                                                        <label>BOM Material Items (Structured Data)</label><br>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            onclick="openBomModal_bomMaterialItemsModal('{{ route('estimation.get-bom-material-items', [base64_encode($business_details_data->business_details_id), base64_encode($business_details_data->design_id)]) }}')">
                                                            <i class="fa fa-list"></i> View / Edit BOM Items
                                                        </button>
                                                        <small class="text-muted ml-2">Review and edit structured BOM line items.</small>
                                                    </div>
                                                    @endif

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mt-4">
                                                        <label for="total_estimation_amount">Total Estimation Amount <span
                                                                class="text-danger">*</span></label>
                                                        {{-- T-2026-011: Field value priority (same as edit-estimation-upload.blade.php / T-2026-010):
                                                             1. BOM Final Total ($bom_final_total, server-computed SUM(rate×qty)) when > 0
                                                             2. total_estimation_amount saved on the estimation row (prior saves fallback)
                                                             3. blank
                                                             Field is readonly — auto-populated from BOM items via the modal above. --}}
                                                        <input type="text" class="form-control" id="total_estimation_amount"
                                                            name="total_estimation_amount"
                                                            readonly
                                                            style="background-color:#f8f9fa; cursor:not-allowed;"
                                                            value="{{ (isset($bom_final_total) && $bom_final_total > 0) ? number_format($bom_final_total, 2, '.', '') : (isset($business_details_data) && !is_null($business_details_data) && !is_null($business_details_data->total_estimation_amount) ? $business_details_data->total_estimation_amount : '') }}">
                                                        <small class="text-muted">
                                                            <i class="fa fa-info-circle"></i>
                                                            This amount is auto-calculated from BOM items. Use the BOM modal above to add or edit items.
                                                        </small>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mt-4">
                                                        <label for="remark_by_estimation">Remark <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="remark_by_estimation"
                                                            name="remark_by_estimation" >
                                                    </div>

                                                </div>
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-design-upload') }}"
                                                                    class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    type="submit" style="margin-bottom:50px">Save
                                                                    Data</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- T-2026-011: BOM modal partial — estimation_edit mode (same as edit-estimation-upload.blade.php).
         Guarded on design_id. business_id from estimation.business_id (added to SELECT).
         business_details_id from businesses_details.id alias added to SELECT.
         bomSaveUrl = estimation.save-bom-material-items (same endpoint as first-time estimation edit). --}}
    @if(isset($business_details_data) && $business_details_data && $business_details_data->design_id)
        @include('organizations.common.bom-material-items-modal', [
            'mode'              => 'estimation_edit',
            'businessId'        => $business_details_data->business_id ?? 0,
            'businessDetailsId' => $business_details_data->business_details_id,
            'designId'          => $business_details_data->design_id,
            'bomSaveUrl'        => route('estimation.save-bom-material-items'),
        ])
    @endif
   @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $.validator.addMethod('filesize', function(value, element, param) {
                if (element.files.length === 0) return true;
                var fileSize = element.files[0].size;
                return this.optional(element) || (fileSize >= param.min && fileSize <= param.max);
            }, 'Invalid file size.');

            // Initialize jQuery Validation
            // Note: total_estimation_amount is readonly — ignore:[] ensures jQuery Validate still
            // evaluates it (readonly fields are excluded by default; ignore:[] overrides that).
            $("#addDesignsForm").validate({
                ignore: [],
                rules: {
                    // T-2026-003: bom_image validation disabled — field is hidden (commented out in HTML).
                    // bom_image: {
                    //     required: true,
                    //     accept: ".xls,.xlsx",
                    //     filesize: {
                    //         min: 1024,
                    //         max: 5242880
                    //     } // 1KB to 5MB
                    // },
                    total_estimation_amount: {
                        required: true,
                    },
                    remark_by_estimation: {
                        required: true,
                    }
                },
                messages: {
                    // T-2026-003: bom_image messages disabled — field is hidden.
                    // bom_image: {
                    //     required: "Please select a BOM Excel file.",
                    //     accept: "Please select a valid BOM Excel file.",
                    //     filesize: "The file must be between 1KB and 5MB."
                    // },
                    total_estimation_amount: {
                        required: "Total Estimation Amount is auto-calculated from BOM items. Please save BOM items first.",
                    },
                    remark_by_estimation: {
                        required: "Please enter the remark",
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-danger');
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'Do you want to update the Revised Estimation BOM?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            // Event listener for file input changes
            $(document).on('change', 'input[type="file"]', function() {
                $(this).rules("remove"); // Remove existing rules
                $(this).rules("add", { // Re-add rules for validation
                    filesize: {
                        min: 1024,
                        max: 5242880
                    }, // 1KB to 5MB
                });
                $(this).valid(); // Trigger validation immediately
            });

            // T-2026-011: Listen for bom-saved event dispatched by the BOM modal
            // to update the readonly Total Estimation Amount field (same listener as
            // edit-estimation-upload.blade.php / T-2026-005).
            window.addEventListener('bom-saved', function(e) {
                var detail = e.detail || {};
                if (detail.bomFinalTotal !== null && detail.bomFinalTotal !== undefined && !isNaN(detail.bomFinalTotal)) {
                    $('#total_estimation_amount').val(parseFloat(detail.bomFinalTotal).toFixed(2));
                    if (typeof $('#addDesignsForm').validate === 'function') {
                        $('#addDesignsForm').validate().element('#total_estimation_amount');
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
