@extends('admin.layouts.master')
@section('content')

    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
      <div class="emp-form-header">
        <h4><i class="fa fa-plus mr-2"></i> Upload Estimation Design</h4>
        <a href="{{ route('list-design-upload') }}" class="emp-form-close" title="Close">&times;</a>
      </div>
      <div class="emp-form-body">

        @if (session('msg'))
            <div class="alert alert-{{ session('status') }}">
                {{ session('msg') }}
            </div>
        @endif
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

        <form action="{{ route('update-design-upload') }}" method="POST"
            id="addDesignsForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control"
                value="{{ $business_details_data->id }}" id="business_id"
                name="business_id">

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 fl-col">
                    <div class="fl-group">
                        <input type="text" class="form-control" id="product_name"
                            name="product_name"
                            value="{{ old('product_name', $business_details_data->product_name) }}"
                            placeholder=" " readonly>
                        <label for="product_name">Product Name</label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 fl-col">
                    <div class="fl-group">
                        <input type="text" class="form-control" id="quantity"
                            name="quantity"
                            value="{{ old('quantity', $business_details_data->quantity) }}"
                            placeholder=" " readonly>
                        <label for="quantity">Quantity</label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 fl-col">
                    <div class="fl-group">
                        <input type="text" class="form-control" id="description"
                            name="description"
                            value="{{ old('description', $business_details_data->description) }}"
                            placeholder=" " readonly>
                        <label for="description">Description</label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                    <div class="fl-group">
                        <input type="file" class="form-control" accept="application/pdf"
                            name="design_image">
                        <label for="design_image">Upload Design Layout (PDF, 1KB - 5MB) <span class="red-text">*</span></label>
                        {{-- <input type="file" class="form-control" accept="application/pdf" name="design_image"> --}}
                        {{-- T-2026-003: BOM Excel upload disabled — BOM is now captured via the structured BOM Material Items modal (T-2026-002). Uncomment to restore. --}}
                        @if ($errors->has('design_image'))
                            <span class="fl-error">{{ $errors->first('design_image') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-center mt-3 mb-4">
                <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Save Data</button>
            </div>
        </form>

      </div>{{-- emp-form-body --}}
    </div>{{-- emp-form-card --}}
    </div></div></div></div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
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
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'You want to send this design to the Production Department?',
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
        });
    </script>
@endsection
