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
                    <center><h1>Edit Leaves Data</h1></center>
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
                                <form action="{{ route('update-yearly-leave-management') }}" id="editEmployeeForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control" value="{{ $editData->id }}" id="id" name="id" >
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="leave_year">Year : </label>&nbsp;<span class="red-text">*</span>
                                                    <select class="form-control" id="dYear" name="leave_year">
                                                        <option selected value="">Select Year</option>
                                                        @for ($year = date('Y'); $year >= 1950; $year--)
                                                            <option value="{{ $year }}"
                                                                @if (old('leave_year', $editData->leave_year) == $year) selected @endif>
                                                                {{ $year }}</option>
                                                        @endfor
                                                    </select>
                                                    <label class="error py-2" for="leave_year" id="dYear_error"></label>
                                                    @if ($errors->has('leave_year'))
                                                        <span class="red-text">{{ $errors->first('leave_year') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <label for="name">Name :</label>&nbsp<span class="red-text">*</span>
                                                <div class="calendar-icon">
                                                    <input type="text" class="form-control custom-select-value" value="@if (old('name')) {{ old('name') }}@else{{ $editData->name }} @endif" id="name" name="name" placeholder="Enter foundation date">
                                                    @if ($errors->has('name'))
                                                    <span class="red-text"><?php echo $errors->first('name', ':message'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <label for="leave_count">Leave Count :</label>&nbsp<span class="red-text">*</span>
                                                <div class="calendar-icon">
                                                    <input type="text" class="form-control" id="leave_count"value="@if (old('leave_count')) {{ old('leave_count') }}@else{{ $editData->leave_count }} @endif" name="leave_count" placeholder="Enter foundation date">
                                                    @if ($errors->has('leave_count'))
                                                    <span class="red-text"><?php echo $errors->first('leave_count', ':message'); ?></span>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>

                                    <div class="login-btn-inner">
                                        <div class="row">
                                            <div class="col-lg-5"></div>
                                            <div class="col-lg-7">
                                                <div class="login-horizental cancel-wp pull-left">
                                                    <a href="{{ route('list-yearly-leave-management') }}"><button class="btn btn-white" style="margin-bottom:50px">Cancel</button></a>
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

    $("#addForm").validate({
        rules: {
                leave_year: {
                    required: true,
                },
                name: {
                    required: true,
                },
                leave_count: {
                    required: true,
                },
                
            },
            messages: {
                leave_year: {
                    required: "Please select leave year.",
                },
                name: {
                    required: "Please enter leave name.",
                },
                leave_count: {
                    required: "Please leave count.",
                },
               
            },
        // submitHandler: function(form) {
        //     // Use SweetAlert to show a success message
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Success!',
        //         text: 'Employee added successfully.',
        //     }).then(function() {
        //         form.submit(); // Submit the form after the user clicks OK
        //     });
        // }
    });
});
</script>



@endsection
