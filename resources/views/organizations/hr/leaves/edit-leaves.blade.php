@extends('admin.layouts.master')
@section('content')
    <!-- Styles -->
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            font-size: 12px;
        }

        .calendar-icon {
            position: relative;
        }

        .calendar-icon img {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center><h1>Edit Leave Data</h1></center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            <!-- Alert Messages -->
                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (Session::get('status') == 'success')
                                    <div class="alert alert-success" id="success-alert">
                                        <strong>Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="alert alert-danger" id="error-alert">
                                        <strong>Error!</strong> {!! session('msg') !!}
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

                                <!-- Form -->
                                <div class="all-form-element-inner">
                                    <form action="{{ route('update-leaves') }}" id="editEmployeeForm" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $editData['leave_details']->id }}">

                                        <div class="row">
                                            <!-- First Name -->
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="other_employee_name">First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control mb-2" name="other_employee_name" id="other_employee_name"
                                                        value="{{ $editData['leave_details']->other_employee_name }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    @if ($errors->has('other_employee_name'))
                                                        <span class="text-danger">{{ $errors->first('other_employee_name') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Leave Type -->
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="leave_type_id">Select Leave Type <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="leave_type_id" name="leave_type_id">
                                                        <option value="">Select</option>
                                                        @foreach ($leaveManagment as $leaveType)
                                                            <option value="{{ $leaveType['id'] }}"
                                                                @if ($editData['leave_details']->leave_type_id == $leaveType['id']) selected @endif>
                                                                {{ $leaveType['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('leave_type_id'))
                                                        <span class="text-danger">{{ $errors->first('leave_type_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Leave Day -->
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="leave_day">Select Leave Day <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="leave_day" id="leave_day">
                                                        <option value="">Select Leave Day</option>
                                                        <option value="full_day" {{ $editData['leave_details']->leave_day == 'full_day' ? 'selected' : '' }}>Full Day</option>
                                                        <option value="half_day" {{ $editData['leave_details']->leave_day == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Start Date -->
                                            <div class="col-lg-6">
                                                <label for="leave_start_date">Start Date <span class="text-danger">*</span></label>
                                                <div class="calendar-icon">
                                                    <input type="text" class="form-control" id="leave_start_date" name="leave_start_date"
                                                        value="{{ $editData['leave_details']->leave_start_date }}" placeholder="Select Start Date">
                                                </div>
                                            </div>

                                            <!-- End Date -->
                                            <div class="col-lg-6">
                                                <label for="leave_end_date">End Date <span class="text-danger">*</span></label>
                                                <div class="calendar-icon">
                                                    <input type="text" class="form-control" id="leave_end_date" name="leave_end_date"
                                                        value="{{ $editData['leave_details']->leave_end_date }}" placeholder="Select End Date">
                                                </div>
                                            </div>

                                            <!-- Reason -->
                                            <div class="col-lg-12">
                                                <label for="reason">Reason <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="reason" name="reason"
                                                    value="{{ $editData['leave_details']->reason }}" placeholder="Enter reason">
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="row mt-4">
                                            <div class="col-lg-6">
                                                <a href="{{ route('list-leaves') }}" class="btn btn-secondary">Cancel</a>
                                            </div>
                                            <div class="col-lg-6 text-end">
                                                <button type="submit" class="btn btn-primary">Save Data</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- End Form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script>
        $(function() {
            // Initialize Datepicker with calendar icon
            $('#leave_start_date, #leave_end_date').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0,
                showOn: "both",
                buttonImage: "{{ asset('images/calendar.png') }}", // Add a local calendar icon
                buttonImageOnly: true,
                buttonText: "Select date"
            });
        });

        // jQuery Validation
        $("#editEmployeeForm").validate({
            rules: {
                other_employee_name: { required: true },
                leave_type_id: { required: true },
                leave_day: { required: true },
                leave_start_date: { required: true },
                leave_end_date: { required: true },
                reason: { required: true }
            },
            messages: {
                other_employee_name: { required: "Please enter full name" },
                leave_type_id: { required: "Please select leave type" },
                leave_day: { required: "Please select leave day" },
                leave_start_date: { required: "Please select start date" },
                leave_end_date: { required: "Please select end date" },
                reason: { required: "Please enter reason" }
            }
        });
    </script>
@endsection
