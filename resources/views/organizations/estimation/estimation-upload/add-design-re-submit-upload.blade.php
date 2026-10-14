@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
        <div class="emp-form-header">
            <h4><i class="fa fa-redo mr-2"></i> Re-Submit Estimation</h4>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
    <script>
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
                        fileExtension: ["pdf"], // Validate for PDF extension
                        fileSize: [1, 6144], // Min 1KB and Max 2MB
                    },
                    // T-2026-003: bom_image validation disabled — field is hidden (commented out in HTML).
                    // bom_image: {
                    //     required: true,
                    //     fileExtension: ["xls", "xlsx"],
                    //     fileSize: [1, 6144],
                    // },
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
                },
                submitHandler: function(form) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Design re-submit added successfully.',
                    }).then(function() {
                        form.submit(); // Submit the form after the user clicks OK
                    });
                }
            });
        });
    </script>
@endsection
