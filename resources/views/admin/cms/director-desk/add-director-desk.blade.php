@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
        <div class="emp-form-header">
            <h4><i class="fa fa-user-tie mr-2"></i> Add Director Desk</h4>
            <a href="{{ route('list-director-desk') }}" class="emp-form-close">&times;</a>
        </div>
        <div class="emp-form-body">

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

            <form class="forms-sample" action="{{ route('add-director-desk') }}" method="POST" enctype="multipart/form-data" id="regForm">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group" id="summernote_id">
                            <textarea class="form-control" name="description" id="description" placeholder="Enter Page Content">{{ old('description') }}</textarea>
                            <label for="description">Description <span class="red-text">*</span></label>
                            @if ($errors->has('description'))
                                <span class="fl-error">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="file" name="image" id="image" accept="image/*" value="{{ old('image') }}" class="form-control mb-2">
                            <label for="image">Image <span class="red-text">*</span></label>
                            @if ($errors->has('image'))
                                <span class="fl-error"><?php echo $errors->first('image', ':message'); ?></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 text-center">
                        <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">
                            Save &amp; Submit
                        </button>
                        {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                        <span><a href="{{ route('list-director-desk') }}" class="btn btn-sm btn-primary ">Back</a></span>
                    </div>
                </div>
            </form>

        </div>
    </div>
    </div></div></div></div>
    <script>
        $(document).ready(function() {
            // Function to check if all input fields are filled with valid data
            function checkFormValidity() {
                const description = $('#description').val();
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
            $.validator.addMethod("spcenotallow", function(value, element) {
                if ("select" === element.nodeName.toLowerCase()) {
                    var e = $(element).val();
                    return e && e.length > 0;
                }
                return this.checkable(element) ? this.getLength(value, element) > 0 : value.trim().length >
                    0;
            }, "Enter Some Text");

            // Initialize the form validation
            $("#regForm").validate({
                rules: {
                    description: {
                        required: true,
                        spcenotallow: true,
                    },
                    image: {
                        required: true,
                        fileExtension: ["jpg", "jpeg", "png"],
                        fileSize: [1, 1048], // Min 1KB and Max 2MB (2 * 1024 KB)
                        imageDimensions: [200, 200, 1000,
                            1000
                        ], // Min width x height and Max width x height
                    },
                },
                messages: {
                    description: {
                        required: "Please enter the description.",
                        spcenotallow: "Enter Some description",
                    },
                    image: {
                        required: "Please upload an Image (jpg, jpeg, png).",
                        fileExtension: "Only JPG, JPEG, and PNG images are allowed.",
                        fileSize: "File size must be between 1 KB and 1048 KB.",
                        imageDimensions: "Image dimensions must be between 200x200 and 1000x1000 pixels.",
                    },
                },
            });
        });
    </script>
@endsection
