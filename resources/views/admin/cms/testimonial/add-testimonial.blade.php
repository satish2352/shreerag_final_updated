@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="emp-form-card">
                        <div class="emp-form-header">
                            <h4><i class="fa fa-quote-left mr-2"></i> Add Testimonial</h4>
                            <a href="{{ route('list-testimonial') }}" class="emp-form-close">&times;</a>
                        </div>
                        <div class="emp-form-body">

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

                            <form class="forms-sample" action="{{ route('add-testimonial') }}"
                                method="POST" enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input class="form-control mb-2" type="text" name="title"
                                                id="title" placeholder=" "
                                                value="{{ old('title') }}">
                                            <label for="title">Title <span class="red-text">*</span></label>
                                            @if ($errors->has('title'))
                                                <span class="fl-error"><?php echo $errors->first('title', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input class="form-control mb-2" type="text" name="position"
                                                id="position" placeholder=" "
                                                value="{{ old('position') }}">
                                            <label for="position">Position <span class="red-text">*</span></label>
                                            @if ($errors->has('position'))
                                                <span class="fl-error"><?php echo $errors->first('position', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="file" name="image" id="image"
                                                accept="image/*" value="{{ old('image') }}"
                                                class="form-control mb-2">
                                            <label for="image">Image <span class="red-text">*</span></label>
                                            @if ($errors->has('image'))
                                                <span class="fl-error"><?php echo $errors->first('image', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group" id="summernote_id">
                                            <textarea class="form-control" name="description" id="description" placeholder="Enter Page Content">{{ old('description') }}</textarea>
                                            <label for="description">Description <span class="red-text">*</span></label>
                                            @if ($errors->has('description'))
                                                <span class="fl-error">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-bg-colour"
                                            id="submitButton">
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
    @push('scripts')
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
    @endpush
@endsection
