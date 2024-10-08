@extends('admin.layouts.master')

@section('content')
<style>
    .error{
        color: red !important;
    }
    .form-control{
        color: black !important;
    }
    </style>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="sparkline12-list">
                                            <div class="sparkline12-hd">
                                                <div class="main-sparkline12-hd">
                                                    <center>
                                                        <h1>Add Notice</h1>
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
                                                                    <div class="row d-flex justify-content-center form-display-center">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <form class="forms-sample" action="{{ route('add-notice') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="company_id">Select Department <span class="text-danger">*</span></label>
                                            <select class="form-control custom-select-value" name="department_id" id="department_id">
                                                <ul class="dropdown-menu ">
                                                    <option value="">Select Department</option>
                                                    @foreach($dept as $datas)
                                                    <option value="{{$datas->id}}">{{ucfirst($datas->department_name)}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Title <span class="text-danger">*</span></label>
                                            <input class="form-control mb-2" name="title" id="title"
                                                placeholder="Enter the Title" name="title"
                                                value="{{ old('title') }}">
                                            @if ($errors->has('title'))
                                                <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group" id="summernote_id">
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                                <span
                                                    class="red-text">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="image">Upload <span class="text-danger">*</span></label><br>
                                            {{-- <input type="file" name="image" id="image" accept="/*"
                                                value="{{ old('image') }}" class="form-control mb-2">
                                            @if ($errors->has('image'))
                                                <span class="red-text"><?php //echo $errors->first('image', ':message'); ?></span>
                                            @endif --}}

                                            <input type="file" class="form-control" accept="application/pdf"
                                            id="image" name="image">
                                        @if ($errors->has('image'))
                                            <span class="red-text"><?php echo $errors->first('image', ':message'); ?></span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton"  >
                                            Save &amp; Submit
                                        </button>
                                        {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-notice') }}"
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
            const department_id = $('#department_id').val();
            const title = $('#title').val();
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
        $('#image').attr('accept', 'application/pdf');

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
                department_id: {
                    required: true,
                },
                title: {
                    required: true,
                },
                description: {
                    required: true,
                },
                image: {
                    required: true,
                    fileExtension: ["pdf"],
                    fileSize: [50, 1048], // Min 10KB and Max 2MB (2 * 1024 KB)
                    // imageDimensions: [200, 200, 1000, 1000], // Min width x height and Max width x height
                },
            },
            messages: {
                department_id: {
                    required: "Please slect deparment name",
                },
                title: {
                    required: "Please Select Title Name",
                },
                description: {
                    required: "Please Enter the Description",
                },
                image: {
                    required: "Please upload an pdf.",
                    fileExtension: "Only pdf are allowed.",
                    fileSize: "File size must be between 50 KB and 1048 KB.",
                    // imageDimensions: "Image dimensions must be between 200x200 and 1000x1000 pixels.",
                },
            },
        });
    });
</script>
    @endsection
