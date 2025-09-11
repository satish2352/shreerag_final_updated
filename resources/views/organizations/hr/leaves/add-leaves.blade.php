@extends('admin.layouts.master')
@section('content')
<style>
    label { margin-top: 10px; }
    .month { display: grid !important; }
    .year { display: grid !important; }
    label.error { color: red; font-size: 12px; }

    /* Calendar wrapper */
    .calendar-icon {
        position: relative;
        display: inline-block;
        width: -webkit-fill-available;
    }
    .calendar-icon input {
        padding-right: 35px; /* space for icon */
    }
    .calendar-icon i {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: #007bff;
        cursor: pointer;
        font-size: 18px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd text-center">
                <h1>Add Leaves Data</h1>
            </div>
            <div class="sparkline12-graph">
                <form id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="other_employee_name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="other_employee_name"
                                    id="other_employee_name" value="{{ old('other_employee_name') }}">
                            </div>
                        </div>

                        <!-- Leave Type -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="leave_type_id">Select Leave Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="leave_type_id" name="leave_type_id">
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leaveManagment as $leaveType)
                                        <option value="{{ $leaveType['id'] }}" {{ old('leave_type_id') == $leaveType['id'] ? 'selected' : '' }}>
                                            {{ $leaveType['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Leave Day -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="leave_day">Select Leave Day <span class="text-danger">*</span></label>
                                <select class="form-control" name="leave_day" id="leave_day">
                                    <option value="">Select Leave Day</option>
                                    <option value="full_day">Full Day</option>
                                    <option value="half_day">Half Day</option>
                                </select>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="leave_start_date">Start Date <span class="text-danger">*</span></label>
                                <div class="calendar-icon">
                                    <input type="text" class="form-control" id="leave_start_date" name="leave_start_date">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="leave_end_date">End Date <span class="text-danger">*</span></label>
                                <div class="calendar-icon">
                                    <input type="text" class="form-control" id="leave_end_date" name="leave_end_date">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="reason">Reason <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="reason" name="reason">
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="login-btn-inner mt-4 text-end">
                        <a href="{{ route('list-leaves') }}" class="btn btn-white">Cancel</a>
                        <button class="btn btn-primary" type="submit">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
$(function () {
    // Init datepickers
    $('#leave_start_date, #leave_end_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0
    });

    // Open datepicker on icon click
    $('.calendar-icon i').on('click', function () {
        $(this).siblings('input').datepicker('show');
    });

    // Custom validation to check existing dates
    $.validator.addMethod("datesNotExist", function(value, element) {
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

    // Form validation
    $("#addForm").validate({
        rules: {
            other_employee_name: { required: true },
            leave_type_id: { required: true },
            leave_day: { required: true },
            leave_start_date: { required: true, datesNotExist: true },
            leave_end_date: { required: true, datesNotExist: true },
            reason: { required: true }
        },
        messages: {
            other_employee_name: { required: "Please enter full name" },
            leave_type_id: { required: "Please select leave type" },
            leave_day: { required: "Please select leave day" },
            leave_start_date: { required: "Please select leave start date" },
            leave_end_date: { required: "Please select leave end date" },
            reason: { required: "Please enter reason" }
        },
        submitHandler: function(form) {
            $.ajax({
                url: "{{ route('store-leaves') }}",
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Leave added successfully'
                    }).then(() => {
                        window.location.href = "{{ route('list-leaves') }}";
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                }
            });
            return false;
        }
    });
});
</script>
@endsection
