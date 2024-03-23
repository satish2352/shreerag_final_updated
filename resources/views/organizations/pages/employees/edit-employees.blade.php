@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Organization Data</h1></center>
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
                                <form action="{{ route('organizations-update-employees') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                                <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id" >
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="company_name">Employee Name:</label>
                                                <input type="text" class="form-control" value="@if (old('employee_name')) {{ old('employee_name') }}@else{{ $editData->employee_name }} @endif" id="employee_name" name="employee_name" placeholder="Enter Employees name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="email">Email:</label>
                                                <input type="email" class="form-control" id="email" value="@if (old('email')) {{ old('email') }}@else{{ $editData->email }} @endif" name="email" placeholder="Enter email">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="mobile_number">Mobile Number:</label>
                                                <input type="text" class="form-control" id="mobile_number" value="@if (old('mobile_number')) {{ old('mobile_number') }}@else{{ $editData->mobile_number }} @endif" name="mobile_number" placeholder="Enter mobile number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="address">Employee Address:</label>
                                                <input type="text" class="form-control" id="address" value="@if (old('address')) {{ old('address') }}@else{{ $editData->address }} @endif" name="address" placeholder="Enter company address">
                                            </div>
                                        </div>
                                         <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-select-list">
                                                    <label for="department_id">Select Department:</label>
                                                    <select class="form-control custom-select-value" name="department_id">
                                                        <option value="">Select Department</option>
                                                        @foreach($dept as $data)
                                                            <option value="{{ $data->id }}" {{ $data->id == $editData->department_id ? 'selected' : '' }}>
                                                                {{ ucfirst($data->department_name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-select-list">
                                                <label for="role_id">Select Role:</label>
                                                <select class="form-control custom-select-value" name="role_id">
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $data)
                                                        <option value="{{ $data->id }}" {{ $data->id == $editData->role_id ? 'selected' : '' }}>
                                                            {{ ucfirst($data->role_name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="image">Image:</label>
                                                <input type="file" class="form-control" id="image" name="image" value="@if (old('image')) {{ old('image') }}@else{{ $editData->image }} @endif">
                                                @if (old('image') || isset($editData))
                                                    <div>
                                                        <label>Old Image:  </label>
                                                        <img src="@if (old('image')) {{ old('image') }} @elseif(isset($editData)) {{ Config::get('DocumentConstant.EMPLOYEES_VIEW') . $editData->emp_image }} @endif" alt="Old Image" style="max-width: 100px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>  
                                        </div>
                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-employees') }}"><button class="btn btn-white" style="margin-bottom:50px">Cancel</button></a>
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
  <script src="{{asset('js/password-meter/pwstrength-bootstrap.min.js')}}"></script>
    <script src="{{asset('js/password-meter/zxcvbn.js')}}"></script>
    <script src="{{asset('js/password-meter/password-meter-active.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
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
</script>

@endsection
