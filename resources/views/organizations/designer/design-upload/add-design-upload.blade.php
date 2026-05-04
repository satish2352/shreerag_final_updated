@extends('admin.layouts.master')
@section('content')
    <div class="">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add Design Data</h1>
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
                                        <form action="{{ route('update-design-upload') }}" method="POST"
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
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mt-4">
                                                        <label for="design_image">Upload Design Layout (PDF, 1KB - 5MB)
                                                            <span class="text-danger">*</span></label>
                                                        {{-- <input type="file" class="form-control" accept="application/pdf" name="design_image"> --}}
                                                        <input type="file" class="form-control" accept="application/pdf"
                                                            name="design_image"> <!-- 5MB -->

                                                        @if ($errors->has('design_image'))
                                                            <span
                                                                class="red-text">{{ $errors->first('design_image') }}</span>
                                                        @endif
                                                    </div>
                                                    {{-- T-2026-003: BOM Excel upload disabled — BOM is now captured via the structured BOM Material Items modal (T-2026-002). Uncomment to restore.
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mt-4">
                                                        <label for="bom_image">Upload BOM (Excel, 1KB - 5MB) <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" accept=".xls,.xlsx"
                                                            name="bom_image"> <!-- 5MB -->

                                                        @if ($errors->has('bom_image'))
                                                            <span class="red-text">{{ $errors->first('bom_image') }}</span>
                                                        @endif
                                                    </div>
                                                    --}}
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mt-4">
                                                        @if (isset($design_data) && $design_data)
                                                            @php
                                                                $_bomFetchUrl = route('design.get-bom-material-items', [base64_encode($design_data->business_details_id), base64_encode($design_data->id)]);
                                                            @endphp

                                                            {{-- ========== STEP 1: UPLOAD BOM EXCEL ========== --}}
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-3"
                                                                 style="border:1px dashed #28a745; border-radius:6px; padding:14px; background:#f6fff8;">
                                                                <label style="font-weight:600; color:#1e7e34;">
                                                                    Step 1 &mdash; Upload BOM Excel
                                                                </label>
                                                                <br>
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    id="bomExcelUploadBtn">
                                                                    <i class="fa fa-file-excel"></i> Upload BOM Excel
                                                                </button>
                                                                <input type="file" id="bomExcelFileInput"
                                                                    accept=".xls,.xlsx" style="display:none;">

                                                                {{-- Wipe all imported BOM rows for this design so the user
                                                                     can re-upload a different / corrected Excel file. --}}
                                                                <button type="button" class="btn btn-outline-danger btn-sm ml-2"
                                                                    id="bomClearAllBtn">
                                                                    <i class="fa fa-trash"></i> Clear All
                                                                </button>

                                                                <small class="text-muted d-block mt-2">
                                                                    Please upload an Excel file (.xls/.xlsx). Its rows
                                                                    will be parsed and converted into BOM line items.
                                                                    After the import finishes, click <strong>Open BOM
                                                                    Items</strong> below to preview, edit, and save.
                                                                    Use <strong>Clear All</strong> if you uploaded the
                                                                    wrong file and want to start fresh.
                                                                </small>
                                                            </div>

                                                            {{-- ========== STEP 2: OPEN BOM ITEMS MODAL ========== --}}
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4"
                                                                 style="border:1px dashed #17a2b8; border-radius:6px; padding:14px; background:#f4fbfd;">
                                                                <label style="font-weight:600; color:#117a8b;">
                                                                    Step 2 &mdash; BOM Material Items (Structured Data)
                                                                </label>
                                                                <br>
                                                                <button type="button" class="btn btn-info btn-sm"
                                                                    onclick="openBomModal_bomMaterialItemsModal('{{ $_bomFetchUrl }}')">
                                                                    <i class="fa fa-list"></i> Open BOM Items
                                                                </button>
                                                                <small class="text-muted d-block mt-2">
                                                                    The BOM rows imported from your Excel file appear
                                                                    here. Review and edit them in the modal, then click
                                                                    <strong>Save BOM Items</strong>. You can also add
                                                                    rows manually using <strong>+ Add More</strong>.
                                                                </small>
                                                            </div>
                                                            @push('scripts')
                                                                <script>
                                                                    (function ($) {
                                                                        var BOM_FETCH_URL  = @json($_bomFetchUrl);
                                                                        var BOM_IMPORT_URL = @json(route('common.import-bom-excel'));
                                                                        var BOM_CLEAR_URL  = @json(route('common.clear-bom-items'));
                                                                        var BOM_BUSINESS_ID         = {{ (int) $design_data->business_id }};
                                                                        var BOM_BUSINESS_DETAILS_ID = {{ (int) $design_data->business_details_id }};
                                                                        var BOM_DESIGN_ID           = {{ (int) $design_data->id }};
                                                                        var BOM_DEPT_ROLE_ID        = 3; // 3 = design

                                                                        $(function () {
                                                                            $('#bomExcelUploadBtn').on('click', function () {
                                                                                $('#bomExcelFileInput').val('').trigger('click');
                                                                            });

                                                                            // Clear All — soft-deletes every bom_material_items row for
                                                                            // this business_details_id + design_id. Confirms first.
                                                                            $('#bomClearAllBtn').on('click', function () {
                                                                                var doClear = function () {
                                                                                    var $btn = $('#bomClearAllBtn');
                                                                                    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Clearing...');
                                                                                    $.ajax({
                                                                                        url:  BOM_CLEAR_URL,
                                                                                        type: 'POST',
                                                                                        data: {
                                                                                            _token:              '{{ csrf_token() }}',
                                                                                            business_details_id: BOM_BUSINESS_DETAILS_ID,
                                                                                            design_id:           BOM_DESIGN_ID
                                                                                        },
                                                                                        success: function (resp) {
                                                                                            $btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Clear All');
                                                                                            if (resp && resp.status === 'success') {
                                                                                                if (typeof Swal !== 'undefined') {
                                                                                                    Swal.fire({ icon: 'success', title: 'Cleared', text: resp.message, timer: 1400, showConfirmButton: false });
                                                                                                } else {
                                                                                                    alert(resp.message);
                                                                                                }
                                                                                            } else {
                                                                                                alert((resp && resp.message) || 'Failed to clear BOM rows.');
                                                                                            }
                                                                                        },
                                                                                        error: function (xhr) {
                                                                                            $btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Clear All');
                                                                                            var msg = 'Failed to clear BOM rows.';
                                                                                            try { var r = JSON.parse(xhr.responseText); if (r && r.message) msg = r.message; } catch (e) {}
                                                                                            alert(msg);
                                                                                        }
                                                                                    });
                                                                                };

                                                                                if (typeof Swal !== 'undefined') {
                                                                                    Swal.fire({
                                                                                        icon: 'warning',
                                                                                        title: 'Clear all BOM rows?',
                                                                                        text: 'This will remove every imported and manually-added BOM line item for this design. You can re-upload an Excel afterwards.',
                                                                                        showCancelButton: true,
                                                                                        confirmButtonText: 'Yes, clear all',
                                                                                        cancelButtonText: 'Cancel',
                                                                                        confirmButtonColor: '#dc3545'
                                                                                    }).then(function (r) { if (r.isConfirmed) doClear(); });
                                                                                } else if (confirm('Clear all BOM rows for this design?')) {
                                                                                    doClear();
                                                                                }
                                                                            });

                                                                            $('#bomExcelFileInput').on('change', function () {
                                                                                var file = this.files && this.files[0];
                                                                                if (!file) return;

                                                                                var fd = new FormData();
                                                                                fd.append('excel_file',          file);
                                                                                fd.append('business_id',         BOM_BUSINESS_ID);
                                                                                fd.append('business_details_id', BOM_BUSINESS_DETAILS_ID);
                                                                                fd.append('design_id',           BOM_DESIGN_ID);
                                                                                fd.append('dept_role_id',        BOM_DEPT_ROLE_ID);
                                                                                fd.append('_token',              '{{ csrf_token() }}');

                                                                                var $btn = $('#bomExcelUploadBtn');
                                                                                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Importing...');

                                                                                $.ajax({
                                                                                    url:         BOM_IMPORT_URL,
                                                                                    type:        'POST',
                                                                                    data:        fd,
                                                                                    processData: false,
                                                                                    contentType: false,
                                                                                    success: function (resp) {
                                                                                        $btn.prop('disabled', false).html('<i class="fa fa-file-excel"></i> Upload BOM Excel');
                                                                                        if (resp && resp.status === 'success') {
                                                                                            if (typeof Swal !== 'undefined') {
                                                                                                Swal.fire({
                                                                                                    icon: 'success',
                                                                                                    title: 'Excel Imported',
                                                                                                    text: resp.message,
                                                                                                    timer: 1800,
                                                                                                    showConfirmButton: false
                                                                                                }).then(function () {
                                                                                                    if (typeof openBomModal_bomMaterialItemsModal === 'function') {
                                                                                                        openBomModal_bomMaterialItemsModal(BOM_FETCH_URL);
                                                                                                    }
                                                                                                });
                                                                                            } else {
                                                                                                alert(resp.message);
                                                                                                if (typeof openBomModal_bomMaterialItemsModal === 'function') {
                                                                                                    openBomModal_bomMaterialItemsModal(BOM_FETCH_URL);
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            alert((resp && resp.message) || 'Failed to import Excel.');
                                                                                        }
                                                                                    },
                                                                                    error: function (xhr) {
                                                                                        $btn.prop('disabled', false).html('<i class="fa fa-file-excel"></i> Upload BOM Excel');
                                                                                        var msg = 'Failed to import Excel.';
                                                                                        try { var r = JSON.parse(xhr.responseText); if (r && r.message) msg = r.message; } catch (e) {}
                                                                                        alert(msg);
                                                                                    }
                                                                                });
                                                                            });
                                                                        });
                                                                    })(jQuery);
                                                                </script>
                                                            @endpush
                                                        @endif
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
    @if (isset($design_data) && $design_data)
        @include('organizations.common.bom-material-items-modal', [
            'mode' => 'design_edit',
            'businessId' => $design_data->business_id,
            'businessDetailsId' => $design_data->business_details_id,
            'designId' => $design_data->id,
            'bomSaveUrl' => route('design.save-bom-material-items'),
        ])
    @endif
    @push('scripts')
        <script>
            // T-2026-007: Server-rendered flag — true when at least one BOM item is persisted for this design.
            var hasBomItems = {{ isset($bom_items_count) && $bom_items_count > 0 ? 'true' : 'false' }};

            jQuery.noConflict();
            jQuery(document).ready(function($) {
                // Custom validation method for file size
                $.validator.addMethod('filesize', function(value, element, param) {
                    if (element.files.length === 0) return true; // Allow if no file selected
                    var fileSize = element.files[0].size; // Get file size in bytes
                    return this.optional(element) || (fileSize >= param.min && fileSize <= param.max);
                }, 'Invalid file size.');

                // Initialize jQuery Validation
                $("#addDesignsForm").validate({
                    ignore: [], // Validate hidden inputs as well
                    rules: {
                        design_image: {
                            required: true,
                            accept: "application/pdf",
                            filesize: {
                                min: 1024,
                                max: 5242880
                            } // 1KB to 5MB
                        },
                        // T-2026-003: bom_image validation disabled — field is hidden (commented out in HTML).
                        // bom_image: {
                        //     required: true,
                        //     accept: ".xls,.xlsx",
                        //     filesize: {
                        //         min: 1024,
                        //         max: 5242880
                        //     } // 1KB to 5MB
                        // }
                    },
                    messages: {
                        design_image: {
                            required: "Please select a design layout PDF.",
                            accept: "Please select a valid design layout PDF file.",
                            filesize: "The file must be between 1KB and 5MB."
                        },
                        // T-2026-003: bom_image messages disabled — field is hidden.
                        // bom_image: {
                        //     required: "Please select a BOM Excel file.",
                        //     accept: "Please select a valid BOM Excel file.",
                        //     filesize: "The file must be between 1KB and 5MB."
                        // }
                    },
                    errorPlacement: function(error, element) {
                        error.addClass('text-danger'); // Add Bootstrap text-danger class for styling
                        error.insertAfter(element); // Insert error message after the input
                    },
                    submitHandler: function(form) {
                        // T-2026-007: Block submission if no BOM items have been saved for this design.
                        if (!hasBomItems) {
                            Swal.fire({
                                icon: 'error',
                                title: 'BOM Required',
                                text: 'Please add at least one BOM Material Item before sending the design to the Estimation Department.',
                            });
                            return false;
                        }
                        Swal.fire({
                            icon: 'question',
                            title: 'Are you sure?',
                            text: 'You want to send this design to the Estimation Department?',
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

                // T-2026-007: Update hasBomItems when the user saves BOM items via the modal
                // without requiring a page reload.
                window.addEventListener('bom-saved', function(e) {
                    if (e.detail && e.detail.itemCount > 0) {
                        hasBomItems = true;
                    }
                });
            });
        </script>
    @endpush
@endsection
