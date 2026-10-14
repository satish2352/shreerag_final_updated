@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-11 col-md-12">
<div class="emp-form-card">

    <div class="emp-form-header">
        <h4><i class="fa fa-plus mr-2"></i> Add Leave Type</h4>
        <a href="{{ route('list-yearly-leave-management') }}" class="emp-form-close" title="Close">&times;</a>
    </div>

    <div class="emp-form-body">
        <form action="{{ route('store-yearly-leave-management') }}" method="POST" id="addForm" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                    <div class="fl-group">
                        <select class="form-control {{ old('leave_year') ? 'has-value' : '' }}" id="dYear" name="leave_year"
                            onchange="this.classList.toggle('has-value', this.value !== '')">
                            <option value="{{ old('leave_year') }}">Select Year</option>
                        </select>
                        <label for="dYear">Year <span class="text-danger">*</span></label>
                        @if ($errors->has('leave_year'))
                            <span class="fl-error"><?php echo $errors->first('leave_year', ':message'); ?></span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                    <div class="fl-group">
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}" placeholder=" ">
                        <label for="name">Leave Type <span class="text-danger">*</span></label>
                        @if ($errors->has('name'))
                            <span class="fl-error"><?php echo $errors->first('name', ':message'); ?></span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                    <div class="fl-group">
                        <input type="text" class="form-control" id="leave_count" name="leave_count"
                            value="{{ old('leave_count') }}" placeholder=" ">
                        <label for="leave_count">Count <span class="text-danger">*</span></label>
                        @if ($errors->has('leave_count'))
                            <span class="fl-error"><?php echo $errors->first('leave_count', ':message'); ?></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12 text-center">
                    <a href="{{ route('list-yearly-leave-management') }}" class="btn btn-secondary mr-2">Cancel</a>
                    <button class="btn btn-sm btn-primary" type="submit">Save Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div></div></div></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(function() {
        // Clear any existing options first
        $("#dYear").empty();

        var currentYear = new Date().getFullYear();
        var years = [currentYear - 1, currentYear]; // Previous year and current year only

        years.forEach(function(year) {
            var option = $("<option>").val(year).text(year);
            $("#dYear").append(option);
        });

        // Optional: set current year as selected by default
        $("#dYear").val(currentYear);
    });
</script>

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