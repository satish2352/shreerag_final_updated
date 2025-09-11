@extends('admin.layouts.master-add-more')
@section('content')
<style>
    label {
        margin-top: 10px;
    }
    label.error {
        color: red; /* Change 'red' to your desired text color */
        font-size: 12px; /* Adjust font size if needed */
        /* Add any other styling as per your design */
    }
.red-text{
    color: red !important;
}
    /* Style for the calendar icon */
    .calendar-icon {
        position: relative;
        display: inline-block;
        width: -webkit-fill-available;
    }

    .calendar-icon input[type="date"] {
        padding-right: 25px; /* Adjust as needed */
    }

    .calendar-icon::after {
        content: '\1F4C5'; /* Unicode for calendar icon, you can use an image instead */
        position: absolute;
        top: 50%;
        right: 5px;
        transform: translateY(-50%);
        pointer-events: none; /* Ensures that clicking the icon does not trigger input */
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Leaves Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
     

                    {{-- @if (Session::get('status') == 'error')
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error!</strong> {!! session('msg') !!}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error!</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif --}}

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    <form action="{{ route('store-yearly-leave-management') }}" method="POST" id="addForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="leave_year">Year</label>&nbsp<span class="red-text">*</span>
                                                        <select class="form-control" id="dYear" name="leave_year">
                                                            <option value="{{ old('leave_year') }}">Select Year</option>
                                                        </select>
                                                        @if ($errors->has('leave_year'))
                                                            <span class="red-text"><?php echo $errors->first('leave_year', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="name">Leave Type:</label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Name">
                                                    @if ($errors->has('name'))
                                                    <span class="red-text"><?php echo $errors->first('name', ':message'); ?></span>
                                                @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="name">Count:</label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" id="leave_count" name="leave_count" value="{{ old('leave_count') }}" placeholder="Enter Count">
                                                    @if ($errors->has('leave_count'))
                                                    <span class="red-text"><?php echo $errors->first('leave_count', ':message'); ?></span>
                                                @endif
                                                </div>
                                                </div>
                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-yearly-leave-management') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
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
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(function() {
        var currentYear = new Date().getFullYear();
        var startYear = 1980;
        var endYear = currentYear + 10; // Change this to the desired number of future years

        for (var year = startYear; year <= endYear; year++) {
            var option = $("<option>").val(year).text(year);
            if (year < currentYear) {
                option.prop("disabled", true);
            }
            $("#dYear").append(option);
        }
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