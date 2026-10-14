@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
        <div class="emp-form-header">
            <h4><i class="fa fa-redo mr-2"></i> Re-Submit Design</h4>
            <a href="{{ route('list-reject-design-from-prod') }}" class="emp-form-close">&times;</a>
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

            <form action="{{ route('update-re-design-upload') }}" method="POST"
                id="addDesignsForm" enctype="multipart/form-data">
                @csrf
                @if (Session::has('success'))
                    <div class="alert alert-success text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif

                <input type="hidden" value="{{ $design_revision_for_prod_id }}"
                    id="design_revision_for_prod_id" name="design_revision_for_prod_id">

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="file" class="form-control" accept="application/pdf"
                                id="design_image" name="design_image">
                            <label for="design_image">Upload Design Layout (PDF, min:1KB to max:5MB)</label>
                            @if ($errors->has('design_image'))<span class="fl-error">{{ $errors->first('design_image') }}</span>@endif
                        </div>
                    </div>
                    {{-- T-2026-003: BOM Excel upload disabled — BOM is now captured via the structured BOM Material Items modal (T-2026-002). Uncomment to restore.
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="file" class="form-control" accept=".xls, .xlsx" id="bom_image" name="bom_image">
                            <label for="bom_image">Upload BOM (Excel, min:1KB to max:5MB)</label>
                            @if ($errors->has('bom_image'))<span class="fl-error">{{ $errors->first('bom_image') }}</span>@endif
                        </div>
                    </div>
                    --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <textarea class="form-control" rows="3" id="remark_by_design"
                                name="remark_by_design" placeholder=" ">{{ old('remark_by_design') }}</textarea>
                            <label for="remark_by_design">Remark</label>
                            @if($errors->has('remark_by_design'))<span class="fl-error">{{ $errors->first('remark_by_design') }}</span>@endif
                        </div>
                    </div>
                </div>

                {{-- T-2026-006: BOM Material Items section (design_edit mode) --}}
                {{-- Designer can revise BOM items alongside the revised PDF. --}}
                {{-- After submission, revised design goes to estimation (not production directly). --}}
                @if(!empty($business_details_id) && !empty($design_id))
                <div class="row mt-3">
                    <div class="col-lg-12">
                        {{-- Use a custom class instead of Bootstrap's .alert so the global
                             footer timer ($(".alert").alert('close') after 1s) does NOT
                             dismiss this informational note before the user can read it. --}}
                        <div class="bom-update-items-note" style="padding:10px 15px; background-color:#d1ecf1; color:#0c5460; border:1px solid #bee5eb; border-radius:4px; margin-bottom:16px;">
                            <strong>Update BOM Items:</strong>
                            If your revised design changes the bill of materials, please update the BOM items before saving. After submission, this design will be sent to the <strong>Estimation Department</strong> for review.
                        </div>
                        <button type="button" class="btn btn-warning"
                            onclick="openReSubmitBomModal()">
                            <i class="fa fa-list"></i> Edit BOM Items
                        </button>
                    </div>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('list-reject-design-from-prod') }}" class="btn btn-white mr-2">Cancel</a>
                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Save Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div></div></div></div>

    {{-- T-2026-006: BOM modal partial (design_edit mode, editable) --}}
    {{-- Only include if we have the required IDs from the controller --}}
    @if(!empty($business_details_id) && !empty($design_id))
        @include('organizations.common.bom-material-items-modal', [
            'mode'               => 'design_edit',
            'businessId'         => $business_id ?? 0,
            'businessDetailsId'  => $business_details_id,
            'designId'           => $design_id,
            'bomSaveUrl'         => route('design.save-bom-material-items'),
            'bomModalId'         => 'designReSubmitBomModal',
        ])
    @endif

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
    <script>
        // T-2026-007: Server-rendered flag — true when at least one BOM item is persisted for this design.
        var hasBomItems = {{ isset($bom_items_count) && $bom_items_count > 0 ? 'true' : 'false' }};

        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Custom validation method to check file extension
            $.validator.addMethod("fileExtension", function(value, element, param) {
                const extension = value.split('.').pop().toLowerCase();
                return $.inArray(extension, param) !== -1;
            }, "Invalid file extension.");

            // Custom validation method to check file size
            $.validator.addMethod("fileSize", function(value, element, param) {
                const fileSizeKB = element.files[0].size / 1024;
                return fileSizeKB >= param[0] && fileSizeKB <= param[1];
            }, "File size must be between {0} KB and {1} KB.");

            $("#addDesignsForm").validate({
                rules: {
                    design_image: {
                        required: true,
                        fileExtension: ["pdf"],
                        fileSize: [1, 6144],
                    },
                    // T-2026-003: bom_image validation disabled — field is hidden (commented out in HTML).
                    // bom_image: {
                    //     required: true,
                    //     fileExtension: ["xls", "xlsx"],
                    //     fileSize: [1, 6144],
                    // },
                    remark_by_design: {
                         required: true,
                    }
                },
                messages: {
                    design_image: {
                        required: "Please select design layout PDF.",
                        fileExtension: "Only PDF files are allowed.",
                        fileSize: "File size must be between 1 KB and 5MB.",
                    },
                    // T-2026-003: bom_image messages disabled — field is hidden.
                    // bom_image: {
                    //     required: "Please select BOM Excel file.",
                    //     fileExtension: "Only Excel files (.xls, .xlsx) are allowed.",
                    //     fileSize: "File size must be between 1 KB and 5MB.",
                    // },
                     remark_by_design: {
                        required: "Please enter remark",
                    }
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
                        icon: 'success',
                        title: 'Success!',
                        text: 'Design re-submit added successfully. It will be sent to Estimation for review.',
                    }).then(function() {
                        form.submit(); // Submit the form after the user clicks OK
                    });
                }
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

    @push('scripts')
    {{-- T-2026-006: JS to open the BOM modal for the re-submit form --}}
    @if(!empty($business_details_id) && !empty($design_id))
    <script>
    function openReSubmitBomModal() {
        var bdEncoded = btoa({{ $business_details_id }});
        var dEncoded  = btoa({{ $design_id }});
        var fetchUrl  = '{{ url("designdept/get-bom-material-items") }}/' + bdEncoded + '/' + dEncoded;
        openBomModal_designReSubmitBomModal(fetchUrl);
    }
    </script>
    @endif
    @endpush
@endsection
