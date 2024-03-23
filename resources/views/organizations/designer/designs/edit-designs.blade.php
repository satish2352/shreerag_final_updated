@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
    }
    label.error {
        color: red; /* Change 'red' to your desired text color */
        font-size: 12px; /* Adjust font size if needed */
        /* Add any other styling as per your design */
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Edit Design Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        @if(session('msg'))
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
                                    <form action="{{ route('update-designs', $editData->id) }}" method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                        @csrf
                                       <input type="hidden" name="id" id="id" class="form-control"
                                    value="{{ $editData->id }}" placeholder="">
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="design_name">Design Name:</label>
                                                    <input type="text" class="form-control" id="design_name" name="design_name" value="{{ $editData->design_name }}" placeholder="Enter Employee name">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="design_page">Design Page:</label>
                                                    <input type="text" class="form-control" id="design_page" name="design_page" value="{{ $editData->design_page }}" placeholder="Enter design_page">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="project_name">Project Name:</label>
                                                    <input type="text" class="form-control" id="project_name" name="project_name" value="{{ $editData->project_name }}" placeholder="Enter Aadhar number">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="time_allocation">Time Allocated For Design:</label>
                                                    <input type="text" class="form-control" id="time_allocation" name="time_allocation" value="{{ $editData->time_allocation }}" placeholder="Enter Pancard number">
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="image">Image:</label>
                                                    <input type="file" class="form-control" accept="image/*" id="image" name="image">
                                                    <div id="oldImageDisplay">
                                                        @if (old('image') || isset($editData))
                                                            <b>Image Preview: </b> 
                                                            <img src="@if (old('image')) {{ old('image') }} @elseif(isset($editData)) {{ Config::get('DocumentConstant.DESIGNS_VIEW') . $editData->image }} @endif" alt="Old Image" style="max-width: 100px;">
                                                        @endif
                                                    </div>
                                                    <div id="selectedImageDisplay" style="display: none;">
                                                        <b>Image Preview: </b>
                                                        <img src="" alt="Selected Image" style="max-width: 100px;">
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-designs') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                            <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Update Data</button>
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
<script>
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $("#editDesignsForm").validate({
            rules: {
                design_name: {
                    required: true,
                },
                design_page: {
                    required: true,
                    // Add your custom validation rule if needed
                },
                project_name: {
                    required: true,
                },
                time_allocation: {
                    required: true,
                },
                image: {
                    accept: "image/*",
                },
            },
            messages: {
                design_name: {
                    required: "Please enter design name.",
                },
                design_page: {
                    required: "Please enter a valid design page.",
                },
                project_name: {
                    required: "Please enter project name.",
                },
                time_allocation: {
                    required: "Please enter time allocated for design.",
                },
                image: {
                    accept: "Please select an image file.",
                },
            },
        });

 $("#image").change(function () {
        readURL(this);
    });

    function readURL(input) {
        var oldImageName = "@if (isset($editData)) {{ $editData->image }} @endif";
        $("#image").val(oldImageName);
        $("#oldImageDisplay img").show(); // Show the old image
        $("#selectedImageDisplay").hide(); // Hide the selected image display

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#selectedImageDisplay img").attr('src', e.target.result);
                $("#oldImageDisplay img").hide(); // Hide the old image
                $("#selectedImageDisplay").show(); // Show the selected image
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});
</script>

@endsection
