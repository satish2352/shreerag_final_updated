@extends('admin.layouts.master')

@section('content')

    {{-- ======================= PAGE CONTENT START ======================= --}}
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-11 col-md-12">
    <div class="emp-form-card">

        <div class="emp-form-header">
            <h4><i class="fa fa-plus mr-2"></i> Add Leave</h4>
            <a href="{{ route('list-leaves') }}" class="emp-form-close" title="Close">&times;</a>
        </div>

        <div class="emp-form-body">

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

            {{-- ========================== FORM START ========================== --}}
            <form action="{{ route('store-leaves') }}" method="POST" id="addForm"
                  enctype="multipart/form-data">
                @csrf

                <div class="row">

                    {{-- 1) FULL NAME --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                        <div class="fl-group">
                            <input type="text"
                                   class="form-control"
                                   name="other_employee_name"
                                   id="other_employee_name"
                                   placeholder=" "
                                   value="{{ old('other_employee_name') }}"
                                   oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                            <label for="other_employee_name">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            @if ($errors->has('other_employee_name'))
                                <span class="fl-error">{{ $errors->first('other_employee_name') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- 2) LEAVE TYPE (SL / PL / CL etc.) --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                        <div class="fl-group">
                            <select class="form-control {{ old('leave_type_id') ? 'has-value' : '' }}"
                                    id="leave_type_id"
                                    name="leave_type_id"
                                    onchange="this.classList.toggle('has-value', this.value !== '')">
                                <option value="">Select Leave Type</option>

                                @foreach ($leaveManagment as $leaveType)
                                    <option value="{{ $leaveType['id'] }}"
                                        {{ old('leave_type_id') == $leaveType['id'] ? 'selected' : '' }}>
                                        {{ $leaveType['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="leave_type_id">
                                Select Leave Type <span class="text-danger">*</span>
                            </label>
                            <span id="leave_balance_error"
                                  class="text-danger"
                                  style="display:none;"></span>
                            @if ($errors->has('leave_type_id'))
                                <span class="fl-error">{{ $errors->first('leave_type_id') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- 3) LEAVE DAY (FULL / HALF) --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                        <div class="fl-group">
                            <select class="form-control custom-select-value {{ old('leave_day') ? 'has-value' : '' }}"
                                    name="leave_day"
                                    id="leave_day"
                                    onchange="this.classList.toggle('has-value', this.value !== '')">
                                <option value="">Select Leave Day</option>
                                <option value="full_day">Full Day</option>
                                <option value="first_half_day">First Half Day</option>
                                <option value="second_half_day">Second Half Day</option>
                            </select>
                            <label for="leave_day">
                                Select Leave Day <span class="text-danger">*</span>
                            </label>
                        </div>
                    </div>

                    {{-- 4) START DATE --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                        <div class="form-group">
                            <label for="leave_start_date">
                                From Date <span class="text-danger">*</span>
                            </label>
                            <div class="calendar-icon">
                                <input type="text"
                                       class="form-control custom-select-value"
                                       id="leave_start_date"
                                       name="leave_start_date"
                                       placeholder="Enter From Date"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>

                    {{-- 5) END DATE --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                        <div class="form-group">
                            <label for="leave_end_date">
                                To Date <span class="text-danger">*</span>
                            </label>
                            <div class="calendar-icon">
                                <input type="text"
                                       class="form-control"
                                       id="leave_end_date"
                                       name="leave_end_date"
                                       placeholder="Enter To Date"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>

                    {{-- 6) REASON --}}
                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                        <div class="fl-group">
                            <input type="text"
                                   class="form-control"
                                   id="reason"
                                   name="reason"
                                   placeholder=" ">
                            <label for="reason">Reason <span class="text-danger">*</span></label>
                        </div>
                    </div>

                </div> {{-- /row --}}

                {{-- ====== BUTTON ROW ====== --}}
                <div class="row mt-3">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <a href="{{ route('list-leaves') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button class="btn btn-sm btn-primary" type="submit">Save Data</button>
                    </div>
                </div>

            </form>
            {{-- ========================== FORM END ========================== --}}

        </div>
    </div>
    </div></div></div></div>
    {{-- ======================= PAGE CONTENT END ======================= --}}

    {{-- jQuery UI CSS (datepicker theme) --}}
    <link rel="stylesheet"
          href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

   @push('scripts')

<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function () {
    // Datepicker init
    // $('#leave_start_date, #leave_end_date').datepicker({
    //     dateFormat: 'yy-mm-dd',
    //     minDate: 0
    // });
});

/*******************************************************************
    HALF DAY LOGIC → LOCK TO-DATE = FROM-DATE
*******************************************************************/
function lockHalfDayToDate() {
    let dayType = $("#leave_day").val();
    let startDate = $("#leave_start_date").val();

    // If NOT half day → enable To Date normally
    if (dayType !== "first_half_day" && dayType !== "second_half_day") {
        $("#leave_end_date").prop("readonly", false).val("");
        return;
    }

    // If half day & start date selected → lock To Date
    if (startDate !== "") {
        $("#leave_end_date")
            .val(startDate)
            .prop("readonly", true);   // Prevent typing
    }
}

/*******************************************************************
    DATEPICKER INITIALIZATION WITH HALF-DAY RESTRICTION
*******************************************************************/
$("#leave_start_date").datepicker({
    dateFormat: "yy-mm-dd",
    // minDate: 0,
    onSelect: function () {
        lockHalfDayToDate();
    }
});

$("#leave_end_date").datepicker({
    dateFormat: "yy-mm-dd",
    // minDate: 0,
    beforeShow: function () {
        let dayType = $("#leave_day").val();
        let startDate = $("#leave_start_date").val();

        // If half day → disable all dates except start date
        if (dayType === "first_half_day" || dayType === "second_half_day") {
            $(this).datepicker("option", "beforeShowDay", function (date) {
                let d = $.datepicker.formatDate("yy-mm-dd", date);
                return [d === startDate]; // Only one date enabled
            });
        } else {
            $(this).datepicker("option", "beforeShowDay", null);
        }
    }
});

/*******************************************************************
    APPLY LOGIC ON CHANGE EVENTS
*******************************************************************/
$("#leave_day").on("change", function () {
    lockHalfDayToDate();
});

$("#leave_start_date").on("change", function () {
    lockHalfDayToDate();
});

</script>

<script>
jQuery(document).ready(function ($) {

    /* ------------------------------------------------------
       CUSTOM RULE → DATES MUST NOT OVERLAP WITH EXISTING LEAVES
    ---------------------------------------------------------*/
    $.validator.addMethod("datesNotExist", function (value, element) {
        let valid = false;

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
            success: function (response) {
                valid = !response.exists;
            }
        });

        return valid;

    }, "These dates are already taken.");



    /* ------------------------------------------------------
       MAIN FORM VALIDATION (FINAL FIX WITH PROPER ALIGNMENT)
    ---------------------------------------------------------*/
    $("#addForm").validate({

        // FIX ERROR MESSAGE ALIGNMENT
        errorPlacement: function (error, element) {

            // if field is inside calendar icon wrapper
            if (element.parent().hasClass('calendar-icon')) {
                error.insertAfter(element.parent());  // place below wrapper
            } 
            else {
                error.insertAfter(element);  // normal placement
            }
        },

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
            reason: { required: true }
        },

        messages: {
            other_employee_name: { required: "Please enter full name" },
            leave_type_id: { required: "Please select leave type." },
            leave_day: { required: "Please select leave day." },
            leave_start_date: { required: "Please select leave start date." },
            leave_end_date: { required: "Please select leave end date." },
            reason: { required: "Please enter reason." }
        }
    });


    /* ------------------------------------------------------
       LEAVE BALANCE CHECK (SL / PL / CL VALIDATION)
    ---------------------------------------------------------*/

    let availableLeaves = 0;
    let currentLeaveTypeName = "";

    function calculateSelectedDays() {
        const start = $("#leave_start_date").val();
        const end = $("#leave_end_date").val();
        const dayType = $("#leave_day").val();

        if (!start || !end || !dayType) return null;

        const s = new Date(start);
        const e = new Date(end);

        if (e < s) {
            $("#leave_balance_error")
                .text("To date cannot be before from date.")
                .css("color", "red")
                .show();
            return null;
        }

        if (dayType === "first_half_day" || dayType === "second_half_day") return 0.5;

        return Math.floor((e - s) / (1000 * 60 * 60 * 24)) + 1;
    }

    function validateSelectedDaysAgainstBalance() {

        if (!currentLeaveTypeName || availableLeaves <= 0) {
            return true;
        }

        const total = calculateSelectedDays();

        if (total === null) return false;

        if (total > availableLeaves) {

            $("#leave_balance_error")
                .text("You cannot take " + total + " day(s) of " +
                      currentLeaveTypeName + ". Only " + availableLeaves + " day(s) available.")
                .css("color", "red")
                .show();

            return false;
        }

        $("#leave_balance_error").hide();
        return true;
    }


    // When Leave Type changes: fetch leave balance
    $("#leave_type_id").on("change", function () {

        const leaveTypeId = $(this).val();
        currentLeaveTypeName = $("#leave_type_id option:selected").text().trim();
        $("#leave_balance_error").hide().text("");
        availableLeaves = 0;

        if (leaveTypeId === "") return;

        $.ajax({
            url: "{{ route('check-leave-balance') }}",
            type: "POST",
            data: {
                leave_type_id: leaveTypeId,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {

                availableLeaves = parseFloat(res.available) || 0;

                if (availableLeaves <= 0) {
                    $("#leave_balance_error")
                        .text("You have 0 " + currentLeaveTypeName + " remaining. You cannot apply more.")
                        .css("color", "red")
                        .show();

                    $("#leave_type_id").val("");
                    availableLeaves = 0;
                    currentLeaveTypeName = "";
                    return;
                }

                $("#leave_balance_error")
                    .text("Available " + currentLeaveTypeName + ": " + availableLeaves + " day(s).")
                    .css("color", "green")
                    .show();

                validateSelectedDaysAgainstBalance();
            }
        });
    });

    // Re-check when dates or day type changes
    $("#leave_start_date, #leave_end_date, #leave_day").on("change", function () {
        validateSelectedDaysAgainstBalance();
    });


    /* ------------------------------------------------------
       PREVENT SUBMISSION IF EXCEEDS BALANCE
    ---------------------------------------------------------*/
    $("#addForm").on("submit", function (e) {

        if (!$(this).valid()) return;

        if (!validateSelectedDaysAgainstBalance()) {
            e.preventDefault();
        }
    });

});
</script>

@endpush

@endsection
