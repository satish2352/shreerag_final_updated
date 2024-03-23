@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 10px;
        }
        .form-display-center{
        display: flex !important;
        justify-content: center !important;
        align-items: center;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Update Business Data</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
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
                            <div class="all-form-element-inner">
                                <div class="row d-flex justify-content-center form-display-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                                <form action="{{ route('update-business') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control"
                                            value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif"
                                            id="id" name="id">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="title">Title:</label>
                                                <input class="form-control" name="title" id="title"
                                                    placeholder="Enter the Title"
                                                    value=" @if (old('title')) {{ old('title') }}@else{{ $editData->title }} @endif">
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="descriptions">Description:</label>
                                                <textarea class="form-control english_description" name="descriptions" id="descriptions"
                                                    placeholder="Enter the Description">@if (old('descriptions')){{ old('descriptions') }}@else{{ $editData->descriptions }}@endif
                                            </textarea>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="remarks">Remark:</label>
                                                <textarea class="form-control english_description" name="remarks" id="remarks" placeholder="Enter the Description">@if (old('remarks')){{ old('remarks') }}@else{{ $editData->remarks }}@endif
                                            </textarea>
                                            </div>
                                        </div>
                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-business') }}"><button
                                                                class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</button></a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs"
                                                            type="submit" style="margin-bottom:50px">Update Data</button>
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
    </div>
    <script src="{{ asset('js/password-meter/pwstrength-bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/password-meter/zxcvbn.js') }}"></script>
    <script src="{{ asset('js/password-meter/password-meter-active.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    {{-- <script>
   jQuery.noConflict();
   jQuery(document).ready(function ($) {
      $("#addEmployeeForm").validate({
            rules: {
                employee_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                mobile_number: {
                    required: true,
                },
                address: {
                    required: true,
                },
                department_id: {
                    required: true,
                },
                role_id: {
                    required: true,
                },
                image: {
                    required: true,
                    accept: "image/*",
                },
                password: {
                    required: true,
                },
            },
            messages: {
                employee_name: {
                    required: "Please enter employee name.",
                },
                email: {
                    required: "Please enter a valid email address.",
                    email: "Please enter a valid email address.",
                },
                mobile_number: {
                    required: "Please enter mobile number.",
                },
                address: {
                    required: "Please enter employee address.",
                },
                department_id: {
                    required: "Please select a department.",
                },
                role_id: {
                    required: "Please select a role.",
                },
                image: {
                    required: "Please select an image.",
                    accept: "Please select a valid image file.",
                },
                password: {
                    required: "Please enter a password.",
                },
            },
        });
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
</script> --}}
@endsection
