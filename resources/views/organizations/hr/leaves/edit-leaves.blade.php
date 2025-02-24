@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            /* Change 'red' to your desired text color */
            font-size: 12px;
            /* Adjust font size if needed */
            /* Add any other styling as per your design */
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Edit Leave Data</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (Session::get('status') == 'success')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-success " id="success-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-danger " id="error-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
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
                                    <form action="{{ route('update-leaves') }}" id="editEmployeeForm" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group-inner">
                                            <input type="hidden" class="form-control" value="{{ $editData->id }}"
                                                id="id" name="id">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="other_employee_name">First Name <span
                                                            class="text-danger">*</span></label>
                                                        <input type="text" class="form-control mb-2" name="other_employee_name"
                                                            id="other_employee_name" placeholder=""
                                                            value="{{ $editData->other_employee_name }}"
                                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        @if ($errors->has('other_employee_name'))
                                                            <span class="red-text"><?php echo $errors->first('other_employee_name', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="leave_type_id">Select Leave Type <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="leave_type_id" name="leave_type_id"
                                                            onchange="myFunction(this.value)">
                                                            <option>Select</option>
                                                            @foreach ($leaveManagment as $leaveType)
                                                                <option value="{{ $leaveType['id'] }}"
                                                                    @if (isset($editData['leave_type_id']) && $leaveType['id'] == $editData['leave_type_id']) selected @endif>
                                                                    {{ $leaveType['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('leave_type_id'))
                                                            <span
                                                                class="red-text">{{ $errors->first('leave_type_id') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-select-list">
                                                        <label for="leave_day">Select Leave Day <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control custom-select-value" name="leave_day"
                                                            id="leave_day">
                                                            <option value="">Select Leave Day</option>
                                                            <option value="full_day"
                                                                {{ $editData->leave_day == 'full_day' ? 'selected' : '' }}>
                                                                Full Day</option>
                                                            <option value="half_day"
                                                                {{ $editData->leave_day == 'half_day' ? 'selected' : '' }}>
                                                                Half Day</option>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <label for="leave_start_date">Start Date <span
                                                            class="text-danger">*</span></label>
                                                    <div class="calendar-icon">
                                                        <input type="text" class="form-control custom-select-value"
                                                            value="{{ $editData->leave_start_date }}" id="leave_start_date"
                                                            name="leave_start_date" placeholder="Enter foundation date">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <label for="leave_end_date">End Date <span
                                                            class="text-danger">*</span></label>
                                                    <div class="calendar-icon">
                                                        <input type="text" class="form-control" id="leave_end_date"
                                                            value="{{ $editData->leave_end_date }}" name="leave_end_date"
                                                            placeholder="Enter foundation date">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="name">Reason <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="reason" name="reason"
                                                        value="{{ $editData->reason }}" placeholder="Enter reason">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-leaves') }}"><button class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</button></a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs"
                                                            type="submit" style="margin-bottom:50px">Save Data</button>
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
        $(function() {
            // Function to disable past dates
            function disablePastDates(date) {
                var currentDate = new Date();
                // Set hours, minutes, seconds, and milliseconds to 0 to compare only dates
                currentDate.setHours(0, 0, 0, 0);

                return date.valueOf() < currentDate.valueOf() ? false : true;
            }

            // Apply the function to your date picker inputs
            $('#leave_start_date, #leave_end_date').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0, // Disable past dates from today
                beforeShowDay: disablePastDates
            });
        });
    </script>
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {

            $.validator.addMethod("datesNotExist", function(value, element, params) {
                var valid = false;
                $.ajax({
                    type: "POST",
                    url: "{{ route('check-dates') }}",
                    data: {
                        leave_start_date: $("#leave_start_date").val(),
                        leave_end_date: $("#leave_end_date").val(),
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    async: false,
                    success: function(response) {
                        valid = !response.exists;
                    }
                });
                return valid;
            }, "These dates are already taken.");


            $("#addForm").validate({
                rules: {
                    other_employee_name: {
                        required: true,
                    },
                    leave_type_id: {
                        required: true,
                    },
                    department_id: {
                        required: true,
                    },
                    leave_day: {
                        required: true,
                    },
                    leave_start_date: {
                        required: true,
                        datesNotExist: true,
                    },
                    leave_end_date: {
                        required: true,
                        datesNotExist: true,
                    },
                    reason: {
                        required: true,
                    },
                },
                messages: {
                    other_employee_name: {
                        required: "Please enter full name",
                    },
                    leave_type_id: {
                        required: "Please select leave type.",
                    },
                    leave_day: {
                        required: "Please select leave day.",
                    },
                    leave_start_date: {
                        required: "Please select leave start date.",
                    },
                    leave_end_date: {
                        required: "Please select leave end date.",
                    },
                    reason: {
                        required: "Please enter reason.",
                    },
                },

            });
        });
    </script>

@endsection
