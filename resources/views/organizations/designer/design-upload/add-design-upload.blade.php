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
                                        <form action="{{ route('update-design-upload') }}" method="POST" id="addDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" class="form-control" value="{{ $business_details_data->id }}" id="business_id" name="business_id">
                                        
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label for="product_name">Product Name</label>
                                                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $business_details_data->product_name) }}" readonly>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label for="quantity">Quantity</label>
                                                        <input type="text" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $business_details_data->quantity) }}" readonly>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label for="description">Description</label>
                                                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $business_details_data->description) }}" readonly>
                                                    </div>
                                                    <div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                                                        <label for="design_image">Upload Design Layout (PDF, 10KB - 5MB)   <span class="text-danger">*</span></label>  
                                                        {{-- <input type="file" class="form-control" accept="application/pdf" name="design_image"> --}}
                                                        <input type="file" class="form-control" accept="application/pdf" name="design_image" data-maxsize="5242880"> <!-- 5MB -->
                                                        
                                                        @if ($errors->has("design_image"))
                                                            <span class="red-text">{{ $errors->first("design_image") }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="bom_image">Upload BOM (Excel, 10KB - 5MB)  <span class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" accept=".xls,.xlsx" name="bom_image" data-maxsize="5242880"> <!-- 5MB -->
                                                    
                                                        @if ($errors->has("bom_image"))
                                                            <span class="red-text">{{ $errors->first("bom_image") }}</span>
                                                        @endif
                                                    </div>
                                                    
                                                </div>
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-design-upload') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Custom validation method for file size
            $.validator.addMethod('filesize', function(value, element, param) {
                // Check if a file is selected
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
                        filesize: { min: 10 * 1024, max: 5 * 1024 * 1024 } 
                    },
                    bom_image: {
                        required: true,
                        accept: ".xls,.xlsx",
                        filesize: { min: 10 * 1024, max: 5 * 1024 * 1024 }
                    }
                },
                messages: {
                    design_image: {
                        required: "Please select a design layout PDF.",
                        accept: "Please select a valid design layout PDF file.",
                        filesize: "The file must be between 10KB and 5MB."
                    },
                    bom_image: {
                        required: "Please select a BOM Excel file.",
                        accept: "Please select a valid BOM Excel file.",
                        filesize: "The file must be between 10KB and 5MB."
                    }
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
                    filesize: { min: 10 * 1024, max: $(this).data('maxsize') },
                });
                $(this).valid(); // Trigger validation immediately
            });
        });
    </script>
    
@endsection
