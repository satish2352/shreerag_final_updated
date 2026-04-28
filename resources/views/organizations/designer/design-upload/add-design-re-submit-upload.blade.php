@extends('admin.layouts.master')
@section('content')
<style>
    .error{
        color: red !important;
    }
    </style>
    <div class="">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add Revised Design Data</h1>
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
                                        <form action="{{ route('update-re-design-upload') }}" method="POST"
                                            id="addDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group-inner">
                                                <div class="container-fluid">
                                                    @if (Session::has('success'))
                                                        <div class="alert alert-success text-center">
                                                            <a href="#" class="close" data-dismiss="alert"
                                                                aria-label="close">×</a>
                                                            <p>{{ Session::get('success') }}</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <input type="hidden" class="form-control"
                                                    value="{{ $design_revision_for_prod_id }}"
                                                    id="design_revision_for_prod_id" name="design_revision_for_prod_id">


                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="design_image">Upload Design Layout (upload pdf file
                                                            min:1KB to max:5MB) :</label>
                                                        <input type="file" class="form-control" accept="application/pdf"
                                                            id="design_image" name="design_image">
                                                        @if ($errors->has('design_image'))
                                                            <span class="red-text"><?php echo $errors->first('design_image', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    {{-- T-2026-003: BOM Excel upload disabled — BOM is now captured via the structured BOM Material Items modal (T-2026-002). Uncomment to restore.
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="bom_image">Upload BOM (upload excel file min : 1KB to
                                                            max : 5MB) :</label>
                                                        <input type="file" class="form-control" accept=".xls, .xlsx"
                                                            id="bom_image" name="bom_image">
                                                        @if ($errors->has('bom_image'))
                                                            <span class="red-text"><?php echo $errors->first('bom_image', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    --}}


                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="sparkline12-graph">
                                                            <div id="pwd-container1">
                                                                <div class="form-group">
                                                                    <label for="remark_by_design">Remark</label>
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="remark_by_design"
                                                                        name="remark_by_design" placeholder="Enter Remark">{{ old('remark_by_design') }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="pwstrength_viewport_progress"></span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- T-2026-006: BOM Material Items section (design_edit mode) --}}
                                                {{-- Designer can revise BOM items alongside the revised PDF. --}}
                                                {{-- After submission, revised design goes to estimation (not production directly). --}}
                                                @if(!empty($business_details_id) && !empty($design_id))
                                                <div class="row" style="margin-top:16px;">
                                                    <div class="col-lg-12">
                                                        <div class="alert alert-info" style="padding:10px 15px;">
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

                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-reject-design-from-prod') }}"
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
