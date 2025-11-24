@extends('admin.layouts.master')
@section('content')
    <!-- Styles -->
    <style>
        label {
            margin-top: 10px;
        }

        .month {
            display: grid !important;
        }

        .year {
            display: grid !important;
        }

        label.error {
            color: red;
            /* Change 'red' to your desired text color */
            font-size: 12px;
            /* Adjust font size if needed */
            /* Add any other styling as per your design */
        }

         /* FIX: Calendar icon must stay fixed even when error message appears */
.calendar-icon {
    position: relative;
    display: block;
}

.calendar-icon::after {
    content: "\1F4C5";
    position: absolute;
    top: 50%;
    transform: translateY(-50%);   /* prevents icon from moving */
    right: 10px;
    pointer-events: none;
    z-index: 10;
}

/* Ensure error message does NOT push icon down */
.calendar-icon input {
    position: relative;
    z-index: 5;
}

/* Prevent DIV expanding too much when error label appears */
.calendar-icon + label.error {
    margin-top: 2px !important;
    display: block;
}


        /* ----- FIX Full jQuery UI Datepicker Calendar Layout ----- */

        /* Fix Datepicker width (small size) */
        .ui-datepicker {
            width: 260px !important;
            /* SMALL WIDTH */
            padding: 10px !important;
            z-index: 99999 !important;
        }

        /* Table should not stretch */
        .ui-datepicker table {
            width: 100% !important;
            table-layout: fixed !important;
        }

        /* Center calendar */
        .ui-datepicker {
            margin: 0 auto !important;
        }

        /* Prevent full page expansion */
        .ui-datepicker-calendar {
            width: 100% !important;
        }

        /* Day cell size */
        .ui-datepicker-calendar td {
            padding: 6px !important;
            width: 34px !important;
            height: 34px !important;
        }

        /* Calendar header days */
        .ui-datepicker-calendar th {
            text-align: center !important;
            padding: 6px !important;
            font-weight: bold !important;
            color: #333 !important;
        }

        /* Calendar date cells */
        .ui-datepicker-calendar td {
            text-align: center !important;
            padding: 6px !important;
            width: 30px !important;
            height: 32px !important;
        }

        /* Default Date Box */
        .ui-state-default {
            background: #f7f7f7 !important;
            border-radius: 4px !important;
            padding: 5px !important;
            display: inline-block !important;
            width: 28px !important;
            height: 28px !important;
            line-height: 28px !important;
        }

        /* Hover */
        .ui-state-hover {
            background: #e1efff !important;
            color: #000 !important;
        }

        /* Selected Date */
        .ui-state-active {
            background: #175CA2 !important;
            color: #fff !important;
        }

        /* Remove unwanted long rows */
        .ui-datepicker-calendar tr {
            height: auto !important;
        }

        /* Disable template styling */
        .ui-datepicker table tr td,
        .ui-datepicker table tr th {
            border: none !important;
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
                                    <form action="{{ route('update-leaves') }}" id="editEmployeeForm" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $editData['leave_details']->id }}">

                                        <div class="row">
                                            <!-- First Name -->
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="other_employee_name">First Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control mb-2"
                                                        name="other_employee_name" id="other_employee_name"
                                                        value="{{ $editData['leave_details']->other_employee_name }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    @if ($errors->has('other_employee_name'))
                                                        <span
                                                            class="text-danger">{{ $errors->first('other_employee_name') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Leave Type -->
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="leave_type_id">Select Leave Type <span
                                                            class="text-danger">*</span></label>
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
                                                        <span
                                                            class="text-danger">{{ $errors->first('leave_type_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Leave Day -->
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="leave_day">Select Leave Day <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="leave_day" id="leave_day">
                                                        <option value="">Select Leave Day</option>
                                                        <option value="full_day"
                                                            {{ $editData['leave_details']->leave_day == 'full_day' ? 'selected' : '' }}>
                                                            Full Day</option>
                                                        <option value="half_day"
                                                            {{ $editData['leave_details']->leave_day == 'half_day' ? 'selected' : '' }}>
                                                            Half Day</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Start Date -->
                                            <div class="col-lg-6">
                                                <label for="leave_start_date">Start Date <span
                                                        class="text-danger">*</span></label>
                                                <div class="calendar-icon">
                                                    <input type="text" class="form-control" id="leave_start_date"
                                                        name="leave_start_date"
                                                        value="{{ $editData['leave_details']->leave_start_date }}"
                                                        placeholder="Select Start Date">
                                                </div>
                                            </div>

                                            <!-- End Date -->
                                            <div class="col-lg-6">
                                                <label for="leave_end_date">End Date <span
                                                        class="text-danger">*</span></label>
                                                <div class="calendar-icon">
                                                    <input type="text" class="form-control" id="leave_end_date"
                                                        name="leave_end_date"
                                                        value="{{ $editData['leave_details']->leave_end_date }}"
                                                        placeholder="Select End Date">
                                                </div>
                                            </div>

                                            <!-- Reason -->
                                            <div class="col-lg-12">
                                                <label for="reason">Reason <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="reason" name="reason"
                                                    value="{{ $editData['leave_details']->reason }}"
                                                    placeholder="Enter reason">
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="row mt-4">
                                            <div class="col-lg-12  text-center">
                                                <a href="{{ route('list-leaves') }}"
                                                    class="btn btn-sm btn-secondary">Cancel</a>

                                                <button type="submit" class="btn btn-sm btn-bg-colour">Save Data</button>
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

    <link rel="stylesheet" 
href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        $(function() {

            // Remove previous datepicker if any
            $("#leave_start_date, #leave_end_date").datepicker("destroy");

            // Apply NEW datepicker without image icon
            $("#leave_start_date, #leave_end_date").datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0,
                changeMonth: true,
                changeYear: true,
                showAnim: "slideDown"
            });

        });



        // jQuery Validation
        $("#editEmployeeForm").validate({
            rules: {
                other_employee_name: {
                    required: true
                },
                leave_type_id: {
                    required: true
                },
                leave_day: {
                    required: true
                },
                leave_start_date: {
                    required: true
                },
                leave_end_date: {
                    required: true
                },
                reason: {
                    required: true
                }
            },
            messages: {
                other_employee_name: {
                    required: "Please enter full name"
                },
                leave_type_id: {
                    required: "Please select leave type"
                },
                leave_day: {
                    required: "Please select leave day"
                },
                leave_start_date: {
                    required: "Please select start date"
                },
                leave_end_date: {
                    required: "Please select end date"
                },
                reason: {
                    required: "Please enter reason"
                }
            }
        });
    </script>
      @endpush

@endsection
