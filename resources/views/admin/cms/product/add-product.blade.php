@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <center>
                                <h1>Add Product</h1>
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
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                    <form class="forms-sample" action="{{ route('add-product') }}"
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
                                                                <div class="form-group" id="summernote_id">
                                                                    <label for="description">Description <span
                                                                            class="red-text">*</span></label>
                                                                    <textarea class="form-control" name="description" id="description" placeholder="Enter Page Description">{{ old('description') }}</textarea>
                                                                    @if ($errors->has('description'))
                                                                        <span
                                                                            class="red-text">{{ $errors->first('description') }}</span>
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
                                                            <div class="col-md-12 col-sm-12 text-center">
                                                                <button type="submit" class="btn btn-sm btn-bg-colour"
                                                                    id="submitButton">
                                                                    Save &amp; Submit
                                                                </button>
                                                                {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                                                <span><a href="{{ route('list-product') }}"
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
    @push('scripts')
        <script>
            $(document).ready(function() {
                $.validator.addMethod("fileExtension", function(value, element, param) {
                    const extension = value.split('.').pop().toLowerCase();
                    return $.inArray(extension, param) !== -1;
                }, "Invalid file extension.");

                $.validator.addMethod("fileSize", function(value, element, param) {
                    const fileSizeKB = element.files[0].size / 1024;
                    return fileSizeKB >= param[0] && fileSizeKB <= param[1];
                }, "File size must be between {0} KB and {1} KB.");

                $.validator.addMethod("imageDimensions", function(value, element, param) {
                    return new Promise((resolve, reject) => {
                        const file = element.files[0];
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            const img = new Image();
                            img.onload = function() {
                                if (img.width >= param[0] && img.height >= param[1] &&
                                    img.width <= param[2] && img.height <= param[3]) {
                                    resolve(true);
                                } else {
                                    reject("Image dimensions must be between " + param[0] +
                                        "x" + param[1] +
                                        " and " + param[2] + "x" + param[3] + " pixels.");
                                }
                            };
                            img.onerror = function() {
                                reject("Error loading image.");
                            };
                            img.src = event.target.result;
                        };

                        reader.readAsDataURL(file);
                    });
                });

                $('#image').attr('accept', 'image/jpeg, image/png');

                $("#regForm").validate({
                    rules: {
                        title: {
                            required: true,
                            spcenotallow: true,
                        },
                        description: {
                            required: true,
                        },
                        image: {
                            required: true,
                            fileExtension: ["jpg", "jpeg", "png"],
                            fileSize: [
                                {{ Config::get('AllFileValidation.PRODUCT_IMAGE_MIN_SIZE') }},
                                {{ Config::get('AllFileValidation.PRODUCT_IMAGE_MAX_SIZE') }}
                            ],
                            imageDimensions: [200, 200, 500, 500],
                        },
                    },
                    messages: {
                        title: {
                            required: "Please enter the Title.",
                            spcenotallow: "Enter Some Title",
                        },
                        description: {
                            required: "Please enter the Description.",
                        },
                        image: {
                            required: "Please upload an Image (jpg, jpeg, png).",
                            fileExtension: "Only JPG, JPEG, and PNG images are allowed.",
                            fileSize: "File size must be between " +
                                {{ Config::get('AllFileValidation.PRODUCT_IMAGE_MIN_SIZE') }} +
                                " KB and " +
                                {{ Config::get('AllFileValidation.PRODUCT_IMAGE_MAX_SIZE') }} +
                                " KB.",
                        },
                    }
                });
            });
        </script>
          @endpush
    @endsection
