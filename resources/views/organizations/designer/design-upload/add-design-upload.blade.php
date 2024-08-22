@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            font-size: 12px;
        }

        .red-text {
            color: red;
        }
    </style>
    <div class="row">
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

                                                <input type="hidden" class="form-control" value="{{ $addData }}"
                                                    id="business_id" name="business_id">

                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="customer_po_number">PO No.:</label>
                                                        <input type="text" class="form-control" id="customer_po_number"
                                                            name="customer_po_number" placeholder="Enter Purchase No."
                                                            value="{{ $business_data->customer_po_number }}" readonly>
                                                    </div>

                                                </div>
                                                <div style="margin-top:20px">
                                                    <table class="table table-bordered" id="dynamicTable">
                                                        <tr>
                                                            <th class="col-sm-2">Product Name</th>
                                                            <th class="col-md-2">Description</th>
                                                            <th class="col-md-1">Quantity</th>
                                                            <th class="col-md-1">Rate</th>
                                                            <th class="col-sm-3">Upload Design Layout (upload pdf file min:10KB to max:2MB)</th>
                                                            <th class="col-sm-3">Upload BOM (upload excel file min:10KB to max:2MB)</th>
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                        @foreach ($business_details_data as $index => $item)
                                                            <tr>
                                                                <input type="hidden"
                                                                    name="addmore[{{ $index }}][edit_id]"
                                                                    class="form-control" value="{{ $item->id }}"
                                                                    readonly />

                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][product_name]"
                                                                        class="form-control"
                                                                        value="{{ $item->product_name }}" readonly /></td>
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][description]"
                                                                        class="form-control"
                                                                        value="{{ $item->description }}" readonly /></td>
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][quantity]"
                                                                        class="form-control" value="{{ $item->quantity }}"
                                                                        readonly /></td>
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][rate]"
                                                                        class="form-control" value="{{ $item->rate }}"
                                                                        readonly /></td>

                                                                <td>
                                                                    <input type="file" class="form-control"
                                                                        accept="application/pdf"
                                                                        name="addmore[{{ $index }}][design_image]">
                                                                    @if ($errors->has("addmore.{$index}.design_image"))
                                                                        <span
                                                                            class="red-text">{{ $errors->first("addmore.{$index}.design_image") }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input type="file" class="form-control"
                                                                        accept=".xls, .xlsx"
                                                                        name="addmore[{{ $index }}][bom_image]">
                                                                    @if ($errors->has("addmore.{$index}.bom_image"))
                                                                        <span
                                                                            class="red-text">{{ $errors->first("addmore.{$index}.bom_image") }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-design-upload') }}"
                                                                class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                            <button class="btn btn-sm btn-primary login-submit-cs"
                                                                type="submit" style="margin-bottom:50px">Save
                                                                Data</button>
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
    jQuery.noConflict();
jQuery(document).ready(function($) {
    // Custom validation method for file size
    $.validator.addMethod('filesize', function (value, element, param) {
        var fileSize = element.files[0].size; // Get file size in bytes
        return this.optional(element) || (fileSize >= param.min && fileSize <= param.max);
    }, 'Invalid file size.');

    // Initialize jQuery Validation
    $("#addDesignsForm").validate({
        ignore: [], // Validate hidden inputs as well
        rules: {
            // Validation rules for the first set of fields (index 0)
            'addmore[0][design_image]': {
                required: true,
                accept: "application/pdf",
                filesize: { min: 10 * 1024, max: 2 * 1024 * 1024 } // 10KB to 2MB
            },
            'addmore[0][bom_image]': {
                required: true,
                accept: ".xls,.xlsx",
                filesize: { min: 10 * 1024, max: 2 * 1024 * 1024 } // 10KB to 2MB
            }
        },
        messages: {
            'addmore[0][design_image]': {
                required: "Please select design layout pdf.",
                accept: "Please select a valid design layout pdf file.",
                filesize: "The file must be between 10KB and 2MB."
            },
            'addmore[0][bom_image]': {
                required: "Please select BOM excel file.",
                accept: "Please select a valid BOM excel file.",
                filesize: "The file must be between 10KB and 2MB."
            }
        },
        errorPlacement: function(error, element) {
            error.addClass('text-danger'); // Add Bootstrap text-danger class for styling
            if (element.closest('td').length) {
                element.closest('td').append(error);
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            // Use SweetAlert to show a confirmation dialog
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

    // Function to initialize validation for dynamically added fields
    function initializeValidation(index) {
        var design_image_field = 'addmore[' + index + '][design_image]';
        var bom_image_field = 'addmore[' + index + '][bom_image]';
        
        $('input[name="' + design_image_field + '"]').rules("add", {
            required: true,
            accept: "application/pdf",
            filesize: { min: 10 * 1024, max: 2 * 1024 * 1024 }, // 10KB to 2MB
            messages: {
                required: "Please select design layout pdf.",
                accept: "Please select a valid design layout pdf file.",
                filesize: "The file must be between 10KB and 2MB."
            }
        });

        $('input[name="' + bom_image_field + '"]').rules("add", {
            required: true,
            accept: ".xls,.xlsx",
            filesize: { min: 10 * 1024, max: 2 * 1024 * 1024 }, // 10KB to 2MB
            messages: {
                required: "Please select BOM excel file.",
                accept: "Please select a valid BOM excel file.",
                filesize: "The file must be between 10KB and 2MB."
            }
        });
    }

    // Attach validation for each row that is dynamically added
    $("#add_more_btn").click(function() {
        var rowCount = $('#dynamicTable tr').length; // Get the current number of rows
        var newRow = '<tr>...' // Your code to generate new row
        $('#dynamicTable').append(newRow);

        initializeValidation(rowCount); // Apply validation to the new row
    });

    // Apply validation to the existing rows
    $('#dynamicTable tr').each(function(index) {
        initializeValidation(index);
    });

     // Event listener for file input changes
     $(document).on('change', 'input[type="file"]', function() {
        // Remove validation rules for the file input
        $(this).rules("remove");
    });
});
        // jQuery.noConflict();
        // jQuery(document).ready(function($) {
        //     $("#addDesignsForm").validate({
        //         rules: {
        //             design_image: {
        //                 required: true,
        //                 accept: "application/pdf", // Specify PDF MIME type
        //             },
        //             bom_image: {
        //                 required: true,
        //                 accept: ".xls,.xlsx",
        //             },
        //         },
        //         messages: {
        //             design_image: {
        //                 required: "Please select design layout pdf .",
        //                 accept: "Please select an  design layout pdf file.",
        //             },
        //             bom_image: {
        //                 required: "Please select bom excel .",
        //                 accept: "Please select an bom excel file.",
        //             },
        //         },
        //         submitHandler: function(form) {
        //             // Use SweetAlert to show a confirmation dialog
        //             Swal.fire({
        //                 icon: 'question',
        //                 title: 'Are you sure?',
        //                 text: 'You want to send this design to Production Department ?',
        //                 showCancelButton: true,
        //                 confirmButtonText: 'Yes',
        //                 cancelButtonText: 'No',
        //             }).then(function(result) {
        //                 if (result.isConfirmed) {
        //                     // If user clicks "Yes", submit the form
        //                     form.submit();
        //                 }
        //             });
        //         }
        //     });
        // });
    </script>
@endsection
