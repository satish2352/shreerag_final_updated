@extends('admin.layouts.master')

@section('content')
    <style>
        .error {
            color: red !important;
        }

        .red-text {
            color: red !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <center>
                                <h1>Add Testimonial</h1>
                            </center>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if (Session::get('status') == 'success')
                                        <div class="col-md-12">
                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <strong>Success!</strong> {{ Session::get('msg') }}
                                            </div>
                                        </div>
                                    @endif

                                    @if (Session::get('status') == 'error')
                                        <div class="col-md-12">
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <strong>Error!</strong> {!! session('msg') !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="all-form-element-inner">
                                            <div class="row d-flex justify-content-center form-display-center">
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                                                    <form class="forms-sample" action="{{ route('add-testimonial') }}"
                                                        method="POST" enctype="multipart/form-data" id="regForm">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="title">Title</label>&nbsp<span
                                                                        class="red-text">*</span>
                                                                    <input class="form-control mb-2" name="title"
                                                                        id="title" placeholder="Enter the Title"
                                                                        name="title" value="{{ old('title') }}">
                                                                    @if ($errors->has('title'))
                                                                        <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="position">position</label>&nbsp<span
                                                                        class="red-text">*</span>
                                                                    <input class="form-control mb-2" name="position"
                                                                        id="position" placeholder="Enter the position"
                                                                        name="position" value="{{ old('position') }}">
                                                                    @if ($errors->has('position'))
                                                                        <span class="red-text"><?php echo $errors->first('position', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="image">Image </label>&nbsp<span
                                                                        class="red-text">*</span><br>
                                                                    <input type="file" name="image" id="image"
                                                                        accept="image/*" value="{{ old('image') }}"
                                                                        class="form-control mb-2">
                                                                    @if ($errors->has('image'))
                                                                        <span class="red-text"><?php echo $errors->first('image', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                <div class="form-group" id="summernote_id">
                                                                    <label for="description">Description <span
                                                                            class="red-text">*</span></label>
                                                                    <textarea class="form-control" name="description" id="description" placeholder="Enter Page Content">{{ old('description') }}</textarea>
                                                                    @if ($errors->has('description'))
                                                                        <span
                                                                            class="red-text">{{ $errors->first('description') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 text-center">
                                                                <button type="submit" class="btn btn-sm btn-success"
                                                                    id="submitButton" {{-- disabled --}}>
                                                                    Save &amp; Submit
                                                                </button>
                                                                {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                                                <span><a href="{{ route('list-testimonial') }}"
                                                                        class="btn btn-sm btn-primary ">Back</a></span>
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
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
        <script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const title = $('#title').val();
                    const description = $('#description textarea').val();
                    const image = $('#image').val();
                }
                // Custom validation method to check file extension
                $.validator.addMethod("fileExtension", function(value, element, param) {
                    // Get the file extension
                    const extension = value.split('.').pop().toLowerCase();
                    return $.inArray(extension, param) !== -1;
                }, "Invalid file extension.");

                // Custom validation method to check file size
                $.validator.addMethod("fileSize", function(value, element, param) {
                    // Convert bytes to KB
                    const fileSizeKB = element.files[0].size / 1024;
                    return fileSizeKB >= param[0] && fileSizeKB <= param[1];
                }, "File size must be between {0} KB and {1} KB.");

                // Update the accept attribute to validate based on file extension
                $('#image').attr('accept', 'image/jpeg, image/png');

                // Call the checkFormValidity function on input change
                $('input, textarea, #image').on('input change', checkFormValidity);

                // Initialize the form validation
                $("#regForm").validate({
                    rules: {
                        title: {
                            required: true,
                        },
                        position: {
                            required: true,
                        },
                        description: {
                            required: true,
                        },
                        image: {
                            required: true,
                            fileExtension: ["jpg", "jpeg", "png"],
                            fileSize: [1, 1024], // Min 1KB and Max 2MB (2 * 1024 KB)
                            imageDimensions: [50, 50, 800, 800], // Min width x height and Max width x height
                        },
                    },
                    messages: {
                        title: {
                            required: "Please enter the Title.",
                        },
                        position: {
                            required: "Please Enter the Position",
                        },
                        description: {
                            required: "Please Enter the Description",
                        },
                        image: {
                            required: "Please upload an Image (jpg, jpeg, png).",
                            fileExtension: "Only JPG, JPEG, and PNG images are allowed.",
                            fileSize: "File size must be between 1 KB and 1024 KB.",
                            imageDimensions: "Image dimensions must be between 50x50 and 800x800 pixels.",
                        },
                    },
                });
            });
        </script>
    @endsection
