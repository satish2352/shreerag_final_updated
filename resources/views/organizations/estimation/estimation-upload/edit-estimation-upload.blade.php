@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
        <div class="emp-form-header">
            <h4><i class="fa fa-edit mr-2"></i> Edit Estimation Upload</h4>
            <a href="{{ route('list-design-upload') }}" class="emp-form-close">&times;</a>
        </div>
        <div class="emp-form-body">
            @if (session('msg'))
                <div class="alert alert-{{ session('status') }}">
                    {{ session('msg') }}
                </div>
            @endif
            @if (Session::get('status') == 'success')
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Success!</strong> {{ Session::get('msg') }}
                </div>
            @endif
            @if (Session::get('status') == 'error')
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Error!</strong> {!! session('msg') !!}
                </div>
            @endif

            @if(isset($estimation_data) && !is_null($estimation_data) && !is_null($estimation_data->owner_suggested_amount))
                {{-- Use a custom class instead of Bootstrap's .alert so the global
                     footer timer ($(".alert").alert('close') after 1s) does NOT
                     dismiss this informational note before the user can read it. --}}
                <div class="bom-owner-revised-note" role="alert"
                     style="border-left: 4px solid #e6a817; background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 4px; padding: 12px 16px; margin-bottom: 16px;">
                    <strong>Owner Has Suggested a Revised Amount</strong><br>
                    The owner has reviewed your exceeded estimation and suggested a revised amount of
                    <strong>&#8377;{{ number_format($estimation_data->owner_suggested_amount, 2) }}</strong>
                    @if($estimation_data->owner_suggested_at)
                        (on {{ \Carbon\Carbon::parse($estimation_data->owner_suggested_at)->format('d-m-Y') }})
                    @endif.<br>
                    The estimation amount field below shows the BOM-derived total.
                    Update the BOM items if needed to match or revise this suggestion before resubmitting for owner approval.
                    @if(!is_null($estimation_data->owner_suggestion_remark))
                        <br><strong>Owner Remark:</strong> {{ $estimation_data->owner_suggestion_remark }}
                    @endif
                </div>
            @endif

            {{-- T-2026-057: Owner rejected the exceed-amount request. Unlike the
                 "suggested amount" banner above, there is no revised amount to show —
                 the estimator must reduce/revise the BOM items themselves and resubmit. --}}
            @if(isset($estimation_data) && !is_null($estimation_data) && !empty($estimation_data->is_exceed_rejected))
                <div class="bom-owner-rejected-note" role="alert"
                     style="border-left: 4px solid #c0392b; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 12px 16px; margin-bottom: 16px;">
                    <strong>Rejected by Owner</strong><br>
                    The owner has rejected your exceeded estimation
                    @if($estimation_data->exceed_rejected_at)
                        (on {{ \Carbon\Carbon::parse($estimation_data->exceed_rejected_at)->format('d-m-Y') }})
                    @endif.<br>
                    Please reduce/revise the estimation amount to be within the business limit (or provide a
                    stronger justification) before resubmitting.
                    @if(!is_null($estimation_data->exceed_rejected_remark))
                        <br><strong>Owner Remark:</strong> {{ $estimation_data->exceed_rejected_remark }}
                    @endif
                </div>
            @endif

            <form action="{{ route('update-estimation') }}" method="POST"
                id="addDesignsForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $business_details_data->id }}" id="business_id" name="business_id">

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                value="{{ old('product_name', $business_details_data->product_name) }}"
                                placeholder=" " readonly>
                            <label for="product_name">Product Name</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="text" class="form-control" id="quantity" name="quantity"
                                value="{{ old('quantity', $business_details_data->quantity) }}"
                                placeholder=" " readonly>
                            <label for="quantity">Quantity</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="text" class="form-control" id="description" name="description"
                                value="{{ old('description', $business_details_data->description) }}"
                                placeholder=" " readonly>
                            <label for="description">Description</label>
                        </div>
                    </div>
                </div>

                {{-- T-2026-003: BOM Excel upload disabled — BOM is now captured via the structured BOM Material Items modal (T-2026-002). Uncomment to restore.
                <div class="col-lg-6 col-md-6 col-sm-12 fl-col mt-4">
                    <label for="bom_image">Upload Estimation BOM (Excel, 1KB - 5MB) <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" accept=".xls,.xlsx" name="bom_image">
                    @if ($errors->has('bom_image'))
                        <span class="red-text">{{ $errors->first('bom_image') }}</span>
                    @endif
                </div>
                --}}

                @if(isset($estimation_data) && $estimation_data && $estimation_data->design_id)
                <div class="row mt-3">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label>BOM Material Items (Structured Data)</label><br>
                        <button type="button" class="btn btn-info btn-sm"
                            onclick="openBomModal_bomMaterialItemsModal('{{ route('estimation.get-bom-material-items', [base64_encode($estimation_data->business_details_id), base64_encode($estimation_data->design_id)]) }}')">
                            <i class="fa fa-list"></i> View / Edit BOM Items
                        </button>
                        <small class="text-muted ml-2">Review and edit structured BOM line items from the design department.</small>
                    </div>
                    {{-- T-2026-035: Yellow exceed warning shown on main form (alert only — textarea is inside the BOM modal).
                         The alert is shown/hidden by the modal's updateExceedUI() JS and pre-rendered
                         server-side when $bom_final_total > business total_amount.
                         The "Reason for Excess Amount" textarea lives exclusively inside the BOM modal. --}}
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                        @php
                            $bomExceedsOnLoad = isset($bom_final_total) && $bom_final_total > 0
                                && isset($business_details_data) && $business_details_data->total_amount > 0
                                && $bom_final_total > $business_details_data->total_amount;
                            $bomTotalFmt   = number_format($bom_final_total ?? 0, 2);
                            $bizLimitFmt   = number_format($business_details_data->total_amount ?? 0, 2);
                            $availableFmt  = number_format(max(0, ($business_details_data->total_amount ?? 0) - ($bom_final_total ?? 0)), 2);
                            $differenceFmt = number_format(max(0, ($bom_final_total ?? 0) - ($business_details_data->total_amount ?? 0)), 2);
                        @endphp
                        <div id="bomMaterialItemsModalExceedWarning"
                             class="bom-modal-warning-msg"
                             style="{{ $bomExceedsOnLoad ? '' : 'display:none;' }}">
                            <strong><i class="fa fa-exclamation-triangle"></i> Amount Exceeds Business Limit</strong><br>
                            <span id="bomMaterialItemsModalExceedWarningText">@if($bomExceedsOnLoad)BOM Total &#8377;{{ $bomTotalFmt }} exceeds Business Limit &#8377;{{ $bizLimitFmt }}. Difference Amount: &#8377;{{ $differenceFmt }}. Available limit: &#8377;{{ $availableFmt }}.@endif</span><br>
                            Saving will automatically send an approval request to the Owner.
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mt-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            {{-- T-2026-010: Field value priority:
                                 1. BOM Final Total ($bom_final_total, server-computed SUM(rate×qty)) when > 0
                                 2. total_estimation_amount saved on the estimation row (prior saves fallback)
                                 3. blank
                                 owner_suggested_amount is intentionally excluded — it is shown in the
                                 informational banner above this field only. --}}
                            <input type="text" class="form-control" id="total_estimation_amount"
                                name="total_estimation_amount"
                                readonly
                                style="background-color:#f8f9fa; cursor:not-allowed;"
                                placeholder=" "
                                value="{{ (isset($bom_final_total) && $bom_final_total > 0) ? number_format($bom_final_total, 2, '.', '') : (isset($estimation_data) && !is_null($estimation_data) && !is_null($estimation_data->total_estimation_amount) ? $estimation_data->total_estimation_amount : '') }}">
                            <label for="total_estimation_amount">Total Estimation Amount <span class="text-danger">*</span></label>
                            @if($errors->has('total_estimation_amount'))<span class="fl-error">{{ $errors->first('total_estimation_amount') }}</span>@endif
                        </div>
                        <small class="text-muted">
                            <i class="fa fa-info-circle"></i>
                            This amount is auto-calculated from BOM line items. Use the BOM modal above to add or edit items.
                        </small>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="text" class="form-control" id="remark_by_estimation"
                                name="remark_by_estimation" placeholder=" ">
                            <label for="remark_by_estimation">Remark <span class="text-danger">*</span></label>
                            @if($errors->has('remark_by_estimation'))<span class="fl-error">{{ $errors->first('remark_by_estimation') }}</span>@endif
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('list-design-upload') }}" class="btn btn-white mr-2">Cancel</a>
                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Save Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div></div></div></div>

    @if(isset($estimation_data) && $estimation_data && $estimation_data->design_id)
        @include('organizations.common.bom-material-items-modal', [
            'mode'              => 'estimation_edit',
            'businessId'        => $estimation_data->business_id,
            'businessDetailsId' => $estimation_data->business_details_id,
            'designId'          => $estimation_data->design_id,
            'bomSaveUrl'        => route('estimation.save-bom-material-items'),
        ])
    @endif
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
    <script>
        // T-2026-005: Exceed-amount flow is now triggered automatically from the BOM modal.
        // The estimationExceeds flag and the blur-check AJAX call have been removed.
        // The total_estimation_amount field is readonly and auto-populated from BOM Final Total.

        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Custom validation method for file size
            $.validator.addMethod('filesize', function(value, element, param) {
                if (element.files.length === 0) return true;
                var fileSize = element.files[0].size;
                return this.optional(element) || (fileSize >= param.min && fileSize <= param.max);
            }, 'Invalid file size.');

            // Initialize jQuery Validation
            // Note: total_estimation_amount is readonly so it won't be validated by jQuery Validate
            // by default (ignore: [] ensures hidden fields are validated but readonly fields
            // still work). We keep the required rule so the server rejects empty submissions.
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
                    //     }
                    // },
                    total_estimation_amount: {
                        required: true,
                    },
                    remark_by_estimation: {
                        required: true,
                    },
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
                    },
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-danger');
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    // T-2026-005: Exceed flow is triggered at BOM-save time (not form-submit time).
                    // Form submit only saves remark and other metadata.
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'Do you want to update the Estimation data?',
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
                $(this).rules("remove");
                $(this).rules("add", {
                    filesize: {
                        min: 1024,
                        max: 5242880
                    },
                });
                $(this).valid();
            });

            // T-2026-005: Listen for bom-saved event dispatched by the BOM modal
            // to update the readonly Total Estimation Amount field.
            window.addEventListener('bom-saved', function(e) {
                var detail = e.detail || {};
                if (detail.bomFinalTotal !== null && detail.bomFinalTotal !== undefined && !isNaN(detail.bomFinalTotal)) {
                    $('#total_estimation_amount').val(parseFloat(detail.bomFinalTotal).toFixed(2));
                    // Trigger jQuery Validate to re-validate the field now it has a value
                    if (typeof $('#addDesignsForm').validate === 'function') {
                        $('#addDesignsForm').validate().element('#total_estimation_amount');
                    }
                }
            });
        });
    </script>


    @endpush
@endsection
