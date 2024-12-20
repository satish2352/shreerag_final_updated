@extends('admin.layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Vision Mission</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                           
                            <form class="forms-sample" action="{{ route('add-vision-mission') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group" id="summernote_id">
                                            <label for="vision_description">Vision Description <span class="red-text">*</span></label>
                                            <textarea class="form-control" name="vision_description" id="description" placeholder="Enter Page Content">{{ old('vision_description') }}</textarea>
                                            @if ($errors->has('vision_description'))
                                                <span class="red-text">{{ $errors->first('vision_description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group" id="summernote_id1">
                                            <label for="mission_description">Mission Description <span class="red-text">*</span></label>
                                            <textarea class="form-control" name="mission_description" id="description1" placeholder="Enter Page Content">{{ old('mission_description') }}</textarea>
                                            @if ($errors->has('mission_description'))
                                                <span class="red-text">{{ $errors->first('mission_description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="vision_image">Vision Image </label>&nbsp<span class="red-text">*</span><br>
                                            <input type="file" name="vision_image" id="english_image" accept="image/*"
                                                value="{{ old('vision_image') }}" class="form-control mb-2">
                                            @if ($errors->has('vision_image'))
                                                <span class="red-text"><?php echo $errors->first('vision_image', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="mission_image"> Mission Image </label>&nbsp<span class="red-text">*</span><br>
                                            <input type="file" name="mission_image" id="english_image_new" accept="image/*"
                                                value="{{ old('mission_image') }}" class="form-control mb-2">
                                            @if ($errors->has('mission_image'))
                                                <span class="red-text"><?php echo $errors->first('mission_image', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton"  >
                                            Save &amp; Submit
                                        </button>
                                        {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-vision-mission') }}"
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
        <script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const description = $('#description textarea').val();
                    const description1 = $('#description1 textarea').val();
                    const english_image = $('#english_image').val();  
                    const english_image_new = $('#english_image_new').val();                       
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
                $('#english_image').attr('accept', 'image/jpeg, image/png');
                $('#english_image_new').attr('accept', 'image/jpeg, image/png');


                // Call the checkFormValidity function on input change
                $('input, textarea, #english_image', '#english_image_new').on('input change', checkFormValidity);
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
                        },
                        description1: {
                            required: true,
                        },
                        english_image: {
                            required: true,
                            fileExtension: ["jpg", "jpeg", "png"],
                            fileSize: [150, 1048], // Min 1KB and Max 2MB (2 * 1024 KB)
                            imageDimensions: [300, 300, 1000, 1000], // Min width x height and Max width x height
                        },
                        english_image_new: {
                            required: true,
                            fileExtension: ["jpg", "jpeg", "png"],
                            fileSize: [150, 1048], // Min 1KB and Max 2MB (2 * 1024 KB)
                            imageDimensions: [300, 300, 1000, 1000], // Min width x height and Max width x height
                        },
                    },
                    messages: {
                        description: {
                            required: "Please Enter the Vision Description",
                        },
                        description1: {
                            required: "Please Enter the Mission Description",
                        },
                        english_image: {
                            required: "Please upload an Image (jpg, jpeg, png).",
                            fileExtension: "Only JPG, JPEG, and PNG images are allowed.",
                            fileSize: "File size must be between 150 KB and 1048 KB.",
                            imageDimensions: "Image dimensions must be between 300x300 and 1000x1000 pixels.",
                        },
                        english_image_new: {
                            required: "Please upload an Image (jpg, jpeg, png).",
                            fileExtension: "Only JPG, JPEG, and PNG images are allowed.",
                            fileSize: "File size must be between 150 KB and 1048 KB.",
                            imageDimensions: "Image dimensions must be between 300x300 and 1000x1000 pixels.",
                        },
                    },
                });
            });
        </script>
    @endsection
