@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-11 col-md-12">
<div class="emp-form-card">

    <div class="emp-form-header">
        <h4><i class="fa fa-edit mr-2"></i> Edit Leave Type</h4>
        <a href="{{ route('list-yearly-leave-management') }}" class="emp-form-close" title="Close">&times;</a>
    </div>

    <div class="emp-form-body">
        @if(session('msg'))
            <div class="alert alert-{{ session('status') }}">
                {{ session('msg') }}
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

        <form action="{{ route('update-yearly-leave-management') }}" id="editEmployeeForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" value="{{ $editData->id }}" id="id" name="id">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                    <div class="fl-group">
                        <select class="form-control has-value" id="dYear" name="leave_year"
                            onchange="this.classList.toggle('has-value', this.value !== '')">
                            <option selected value="">Select Year</option>
                            @for ($year = date('Y'); $year >= 1950; $year--)
                                <option value="{{ $year }}"
                                    @if (old('leave_year', $editData->leave_year) == $year) selected @endif>
                                    {{ $year }}</option>
                            @endfor
                        </select>
                        <label for="dYear">Year <span class="text-danger">*</span></label>
                        @if ($errors->has('leave_year'))
                            <span class="fl-error">{{ $errors->first('leave_year') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                    <div class="fl-group">
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder=" "
                            value="@if (old('name')) {{ old('name') }}@else{{ $editData->name }} @endif">
                        <label for="name">Leave Type Name <span class="text-danger">*</span></label>
                        @if ($errors->has('name'))
                            <span class="fl-error">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                    <div class="fl-group">
                        <input type="text" class="form-control" id="leave_count" name="leave_count"
                            placeholder=" "
                            value="@if (old('leave_count')) {{ old('leave_count') }}@else{{ $editData->leave_count }} @endif">
                        <label for="leave_count">Leave Count <span class="text-danger">*</span></label>
                        @if ($errors->has('leave_count'))
                            <span class="fl-error">{{ $errors->first('leave_count') }}</span>
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
                    required: "Please enter leave count.",
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
