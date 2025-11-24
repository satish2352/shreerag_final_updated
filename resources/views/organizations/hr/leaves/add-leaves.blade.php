@extends('admin.layouts.master')

@section('content')

    {{-- ======================= PAGE-LEVEL STYLES ======================= --}}
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

        /* ===== Calendar icon wrapper ==== */
        .calendar-icon {
            position: relative;
            display: block;
        }

        /* Little calendar emoji at right side of input */
        .calendar-icon::after {
            content: "\1F4C5";
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 10;
        }

        /* Make sure input stays above background */
        .calendar-icon input {
            position: relative;
            z-index: 5;
        }

        /* Error label after calendar icon */
        .calendar-icon + label.error {
            margin-top: 2px !important;
            display: block;
        }

        /* ===== jQuery UI datepicker compact style ==== */
        .ui-datepicker {
            width: 260px !important;
            padding: 10px !important;
            z-index: 99999 !important;
            margin: 0 auto !important;
        }

        .ui-datepicker table {
            width: 100% !important;
            table-layout: fixed !important;
        }

        .ui-datepicker-calendar {
            width: 100% !important;
        }

        .ui-datepicker-calendar td {
            padding: 6px !important;
            width: 34px !important;
            height: 34px !important;
            text-align: center !important;
        }

        .ui-datepicker-calendar th {
            text-align: center !important;
            padding: 6px !important;
            font-weight: bold !important;
            color: #333 !important;
        }

        .ui-state-default {
            background: #f7f7f7 !important;
            border-radius: 4px !important;
            padding: 5px !important;
            display: inline-block !important;
            width: 28px !important;
            height: 28px !important;
            line-height: 28px !important;
        }

        .ui-state-hover {
            background: #e1efff !important;
            color: #000 !important;
        }

        .ui-state-active {
            background: #175CA2 !important;
            color: #fff !important;
        }

        .ui-datepicker-calendar tr {
            height: auto !important;
        }

        .ui-datepicker table tr td,
        .ui-datepicker table tr th {
            border: none !important;
        }
    </style>

    {{-- ======================= PAGE CONTENT START ======================= --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add Leaves Data</h1>
                        </center>
                    </div>
                </div>

                <div class="sparkline12-graph">

                    {{-- ========== FLASH MESSAGES FROM CONTROLLER (store()) ========== --}}
                    @if (session('status') == 'error')
                        <div class="alert alert-danger alert-dismissible">
                            <strong>Error:</strong> {{ session('msg') }}
                        </div>
                    @endif

                    @if (session('status') == 'success')
                        <div class="alert alert-success alert-dismissible">
                            <strong>Success:</strong> {{ session('msg') }}
                        </div>
                    @endif

                    {{-- ======================== FORM CARD ======================== --}}
                    <div class="basic-login-form-ad">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">

                                    {{-- ========================== FORM START ========================== --}}
                                    <form action="{{ route('store-leaves') }}" method="POST" id="addForm"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group-inner">
                                            <div class="row">

                                                {{-- 1) FULL NAME --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="other_employee_name">
                                                            Full Name <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="text"
                                                               class="form-control"
                                                               name="other_employee_name"
                                                               id="other_employee_name"
                                                               placeholder=""
                                                               value="{{ old('other_employee_name') }}"
                                                               oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">

                                                        @if ($errors->has('other_employee_name'))
                                                            <span class="red-text">
                                                                {{ $errors->first('other_employee_name') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- 2) LEAVE TYPE (SL / PL / CL etc.) --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="form-select-list">
                                                            <label for="leave_type_id">
                                                                Select Leave Type <span class="text-danger">*</span>
                                                            </label>

                                                            <select class="form-control"
                                                                    id="leave_type_id"
                                                                    name="leave_type_id">
                                                                <option value="">Select Leave Type</option>

                                                                @foreach ($leaveManagment as $leaveType)
                                                                    <option value="{{ $leaveType['id'] }}"
                                                                        {{ old('leave_type_id') == $leaveType['id'] ? 'selected' : '' }}>
                                                                        {{ $leaveType['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            {{-- HERE WE SHOW DYNAMIC MESSAGE:
                                                                 - "You have 0 SL remaining."
                                                                 - "Available PL : 5 day(s)."
                                                                 - etc.
                                                            --}}
                                                            <span id="leave_balance_error"
                                                                  class="text-danger"
                                                                  style="display:none;"></span>

                                                            @if ($errors->has('leave_type_id'))
                                                                <span class="red-text">
                                                                    {{ $errors->first('leave_type_id') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- 3) LEAVE DAY (FULL / HALF) --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="form-select-list">
                                                            <label for="leave_day">
                                                                Select Leave Day <span class="text-danger">*</span>
                                                            </label>

                                                            <select class="form-control custom-select-value"
                                                                    name="leave_day"
                                                                    id="leave_day">
                                                                <option value="">Select Leave Day</option>
                                                                <option value="full_day">Full Day</option>
                                                                <option value="half_day">Half Day</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- 4) START DATE --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="leave_start_date">
                                                            Start Date <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="calendar-icon">
                                                            <input type="text"
                                                                   class="form-control custom-select-value"
                                                                   id="leave_start_date"
                                                                   name="leave_start_date"
                                                                   placeholder="Enter Start Date"
                                                                   autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- 5) END DATE --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="leave_end_date">
                                                            End Date <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="calendar-icon">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   id="leave_end_date"
                                                                   name="leave_end_date"
                                                                   placeholder="Enter End Date"
                                                                   autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- 6) REASON --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <label for="reason">
                                                        Reason <span class="text-danger">*</span>
                                                    </label>

                                                    <input type="text"
                                                           class="form-control"
                                                           id="reason"
                                                           name="reason"
                                                           placeholder="Enter reason">
                                                </div>

                                            </div> {{-- /row --}}

                                            {{-- ====== BUTTON ROW ====== --}}
                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center align-items-center">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-leaves') }}"
                                                               class="btn btn-white"
                                                               style="margin-bottom:50px">
                                                                Cancel
                                                            </a>

                                                            <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    type="submit"
                                                                    style="margin-bottom:50px">
                                                                Save Data
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div> {{-- /form-group-inner --}}
                                    </form>
                                    {{-- ========================== FORM END ========================== --}}

                                </div>
                            </div>
                        </div>
                    </div> {{-- /basic-login-form-ad --}}
                </div>
            </div>
        </div>
    </div>
    {{-- ======================= PAGE CONTENT END ======================= --}}

    {{-- jQuery UI CSS (datepicker theme) --}}
    <link rel="stylesheet"
          href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    @push('scripts')

        {{-- jQuery UI JS (master footer मध्ये jQuery आधीच आहे) --}}
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

        {{-- ========== Datepicker init ========== --}}
        <script>
            $(function () {
                $('#leave_start_date, #leave_end_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0 // past dates not allowed
                });
            });
        </script>

        {{-- ========== Custom validation for overlapping dates (existing logic) ========== --}}
        <script>
            jQuery(document).ready(function ($) {

                // Custom rule: dates must not overlap existing pending leaves
                $.validator.addMethod("datesNotExist", function (value, element, params) {
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
                        async: false, // sync because validate method must return true/false
                        success: function (response) {
                            valid = !response.exists; // if exists = true ⇒ invalid
                        }
                    });

                    return valid;

                }, "These dates are already taken.");

                // jQuery Validate rules
                $("#addForm").validate({
                    rules: {
                        other_employee_name: { required: true },
                        leave_type_id: { required: true },
                        leave_day: { required: true },
                        leave_start_date: {
                            required: true,
                            datesNotExist: true
                        },
                        leave_end_date: {
                            required: true,
                            datesNotExist: true
                        },
                        reason: { required: true },
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

        {{-- ========== OPTION C: LEAVE BALANCE + DATE RANGE CHECK ========== --}}
        <script>
            $(document).ready(function () {

                // This will hold current available leaves for selected type
                let availableLeaves = 0;

                // This will store selected leave type name, e.g. "SL", "PL", "Casual Leave"
                let currentLeaveTypeName = "";

                /**
                 * Utility: calculate how many days user has selected
                 * - Full day: inclusive difference between start & end
                 * - Half day: always 0.5
                 */
                function calculateSelectedDays() {

                    const start = $("#leave_start_date").val();
                    const end   = $("#leave_end_date").val();
                    const dayType = $("#leave_day").val();

                    // If any required value missing ⇒ cannot calculate
                    if (!start || !end || !dayType) {
                        return null;
                    }

                    const s = new Date(start);
                    const e = new Date(end);

                    if (e < s) {
                        // End date before start date ⇒ invalid
                        $("#leave_balance_error")
                            .text("End date cannot be before start date.")
                            .css("color", "red")
                            .show();
                        return null;
                    }

                    // Full day = inclusive count, Half day = 0.5
                    if (dayType === "half_day") {
                        return 0.5;
                    }

                    const diffDays = Math.floor((e - s) / (1000 * 60 * 60 * 24)) + 1;
                    return diffDays;
                }

                /**
                 * Main function: check if selected days > availableLeaves
                 * Show proper message (Option C) and return true/false
                 */
                function validateSelectedDaysAgainstBalance() {

                    // If no leave type selected or no balance data yet, do nothing
                    if (!currentLeaveTypeName || availableLeaves <= 0) {
                        return true; // let backend handle
                    }

                    const totalLeaves = calculateSelectedDays();

                    if (totalLeaves === null) {
                        // invalid date / missing fields already handled in calculateSelectedDays
                        return false;
                    }

                    console.log("Selected Days:", totalLeaves, "Available:", availableLeaves);

                    if (totalLeaves > availableLeaves) {
                        // Example: "You cannot take 6 days of SL. Only 3 day(s) available."
                        $("#leave_balance_error")
                            .text("You cannot take " + totalLeaves + " day(s) of " +
                                  currentLeaveTypeName + ". Only " + availableLeaves + " day(s) available.")
                            .css("color", "red")
                            .show();
                        return false;
                    }

                    // OK
                    $("#leave_balance_error").hide();
                    return true;
                }

                // ===== 1) When Leave Type changes, fetch balance from backend =====
                $("#leave_type_id").on("change", function () {

                    const leaveTypeId = $(this).val();

                    // Get text of selected option for Option-C message
                    currentLeaveTypeName = $("#leave_type_id option:selected").text().trim();

                    $("#leave_balance_error").hide().text("");
                    availableLeaves = 0; // reset

                    // If user cleared dropdown, nothing to do
                    if (leaveTypeId === "") {
                        return;
                    }

                    $.ajax({
                        url: "{{ route('check-leave-balance') }}",
                        type: "POST",
                        data: {
                            leave_type_id: leaveTypeId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {

                            availableLeaves = parseFloat(res.available) || 0;

                            console.log("Available Leaves for", currentLeaveTypeName, "=", availableLeaves);

                            // Case 1: NO balance left for this type
                            if (availableLeaves <= 0) {

                                $("#leave_balance_error")
                                    .text("You have 0 " + currentLeaveTypeName + " remaining. You cannot apply more.")
                                    .css("color", "red")
                                    .show();

                                // Reset dropdown so user must choose something else (e.g. PL)
                                $("#leave_type_id").val("");

                                // Also clear local state
                                availableLeaves = 0;
                                currentLeaveTypeName = "";

                                return;
                            }

                            // Case 2: Some balance is available
                            $("#leave_balance_error")
                                .text("Available " + currentLeaveTypeName + " : " + availableLeaves + " day(s).")
                                .css("color", "green")
                                .show();

                            // After showing available, if dates already selected,
                            // we can immediately validate range
                            validateSelectedDaysAgainstBalance();
                        },
                        error: function (xhr) {
                            console.log("Error in check-leave-balance:", xhr.responseText);
                        }
                    });
                });

                // ===== 2) When dates or day type change, re-validate range vs balance =====
                $("#leave_start_date, #leave_end_date, #leave_day").on("change", function () {
                    validateSelectedDaysAgainstBalance();
                });

                // ===== 3) Final check on form submit =====
                $("#addForm").on("submit", function (e) {

                    // First let jQuery Validate check required fields
                    if (!$(this).valid()) {
                        return;
                    }

                    // Then perform our balance vs selected days check
                    const ok = validateSelectedDaysAgainstBalance();

                    if (!ok) {
                        // Prevent submit if user is exceeding balance
                        e.preventDefault();
                    }
                });

            });
        </script>

    @endpush

@endsection
