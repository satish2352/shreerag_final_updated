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
                    <center><h1>Add Employee Data</h1></center>
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
                                <form action="{{ route('organizations-store-employees') }}" method="POST" id="addEmployeeForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="company_name">Employee Name:</label>
                                                <input type="text" class="form-control" id="employee_name" name="employee_name" placeholder="Enter Employee name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="email">Employee Email:</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                            </div>
                                        </div>
                                       

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="mobile_number">Mobile Number:</label>
                                                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter mobile number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="address">Employee Address:</label>
                                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter company address">
                                            </div>
                                        </div>
                                       
                                      <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-select-list">
                                                <label for="company_id">Select Department:</label>
                                                <select class="form-control custom-select-value" name="department_id">
                                                    <ul class="dropdown-menu ">
                                                        <option value="">Select Company</option>
                                                        @foreach($dept as $datas)
                                                        <option value="{{$datas->id}}">{{ucfirst($datas->department_name)}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-select-list">
                                                <label for="company_id">Select Role:</label>
                                                <select class="form-control custom-select-value" name="role_id">
                                                    <ul class="dropdown-menu ">
                                                        <option value="">Select Role</option>
                                                        @foreach($roles as $datas)
                                                        <option value="{{$datas->id}}">{{ucfirst($datas->role_name)}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        </div>
                                      
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="image">Image:</label>
                                                <input type="file" class="form-control" accept="image*" id="image" name="image">
                                            </div>
                                     
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="sparkline12-graph">
                                                <div id="pwd-container1">
                                                    <div class="form-group">
                                                        <label for="password1">Password</label>
                                                        <input type="password" class="form-control example1" name="password" id="password" placeholder="Password" value="Password">
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="pwstrength_viewport_progress"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        </div>

                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('organizations-list-employees') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
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
            submitHandler: function(form) {
                // Use SweetAlert to show a success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Form submitted successfully.',
                }).then(function() {
                    form.submit(); // Submit the form after the user clicks OK
                });
            }
        });
    });
</script>
@endsection
