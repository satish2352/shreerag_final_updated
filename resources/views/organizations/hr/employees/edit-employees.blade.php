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
                    <center><h1>Edit Employee Data</h1></center>
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
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-success " id="success-alert">
                                        <button type="button"  data-bs-dismiss="alert"></button>
                                        <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                </div>
                            @endif

                            @if (Session::get('status') == 'error')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-danger " id="error-alert">
                                        <button type="button"  data-bs-dismiss="alert"></button>
                                        <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                    </div>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="all-form-element-inner">
                                <form action="{{ route('hr-update-employees') }}" id="editEmployeeForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control" value="{{ $editData->id }}" id="id" name="id" >
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="employee_name">Employee Name:</label>
                                                <input type="text" class="form-control" value="{{ $editData->employee_name }}" id="employee_name" name="employee_name" placeholder="Enter Employee name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="email">Employee Email:</label>
                                                <input type="email" class="form-control" id="email" value="{{ $editData->email }}" name="email" placeholder="Enter email">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="mobile_number">Mobile Number:</label>
                                                <input type="text" class="form-control" id="mobile_number" value="{{ $editData->mobile_number }}" name="mobile_number" placeholder="Enter mobile number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="address">Employee Address:</label>
                                                <input type="text" class="form-control" id="address" value="{{ $editData->address }}" name="address" placeholder="Enter company address">
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
                                                <label for="aadhar_number">Aadhar Number:</label>
                                                <input type="text" class="form-control" id="aadhar_number" value="{{ $editData->aadhar_number }}" name="aadhar_number" placeholder="Enter Aadhar number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="pan_card">PAN Card:</label>
                                                <input type="text" class="form-control" id="pancard_number" value="{{ $editData->pancard_number }}" name="pancard_number" placeholder="Enter PAN Card number">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="form-select-list">
                                                    <label for="gender">Gender:</label>
                                                    <select class="form-control custom-select-value" name="gender">
                                                        <option value="male" {{ $editData->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ $editData->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                        <option value="other" {{ $editData->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="joining_date">Joining Date:</label>
                                                <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ old('joining_date') ?? $editData->joining_date }}" placeholder="Select joining date">
                                            </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="highest_qualification">Highest Qualification:</label>
                                                <input type="text" class="form-control" id="highest_qualification" name="highest_qualification" value="{{ old('highest_qualification') ?? $editData->highest_qualification }}" placeholder="Enter highest qualification">
                                            </div>
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="total_experience">Total Experience:<span><i>(in year)</i></span></label>
                                                <input type="text" class="form-control" id="total_experience" name="total_experience" placeholder="Enter total experience" value="{{ old('total_experience') ?? $editData->total_experience }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                             
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="image">Image:</label>
                                                <input type="file" class="form-control" id="image" name="image" value="@if (old('emp_image')) {{ old('image') }}@else{{ $editData->emp_image }} @endif">
                                                @if (old('image') || isset($editData))
                                                    <div>
                                                        <label>Old Image:  </label>
                                                        <img src="@if (old('image')) {{ old('emp_image') }} @elseif(isset($editData)) {{ Config::get('DocumentConstant.EMPLOYEES_VIEW') . $editData->emp_image }} @endif" alt="Old Image" style="max-width: 100px;">
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
                                </form>
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
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $("#editEmployeeForm").validate({
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
                aadhar_number: {
                    required: true,
                },
                pancard_number: {
                    required: true,
                },
                total_experience: {
                    required: true,
                },
                highest_qualification: {
                    required: true,
                },
                gender: {
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
                aadhar_number: {
                    required: "Please enter Aadhar number.",
                },
                pancard_number: {
                    required: "Please enter Pancard number.",
                },
                total_experience: {
                    required: "Please enter total experience.",
                },
                joining_date: {
                    required: "Please enter joining date.",
                },
                highest_qualification: {
                    required: "Please enter highest qualification.",
                },
                gender: {
                    required: "Please select gender.",
                },
                image: {
                    required: "Please select an image.",
                    accept: "Please select an image file.", 
                },
            },
            submitHandler: function(form) {
                // Use SweetAlert to show a success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Employee update successfully.',
                }).then(function() {
                    form.submit(); // Submit the form after the user clicks OK
                });
            }
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
