@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Leave</h4>
    <a href="{{ route('list-leaves') }}" class="emp-form-close" title="Close">&times;</a>
  </div>

  <div class="emp-form-body">

    @if (session('msg'))
      <div class="alert alert-{{ session('status') }}">{{ session('msg') }}</div>
    @endif

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

    <form action="{{ route('update-leaves') }}" id="editEmployeeForm" method="POST"
        enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" value="{{ $editData['leave_details']->id }}">

      <div class="row">

        {{-- Full Name --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" name="other_employee_name" id="other_employee_name"
                placeholder=" "
                value="{{ $editData['leave_details']->other_employee_name }}"
                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
            <label for="other_employee_name">Full Name <span class="text-danger">*</span></label>
            @if ($errors->has('other_employee_name'))
              <span class="fl-error">{{ $errors->first('other_employee_name') }}</span>
            @endif
          </div>
        </div>

        {{-- Leave Type --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <select class="form-control has-value" id="leave_type_id" name="leave_type_id"
                onchange="this.classList.toggle('has-value', this.value !== '')">
              <option value="">Select</option>
              @foreach ($leaveManagment as $leaveType)
                <option value="{{ $leaveType['id'] }}"
                  @if ($editData['leave_details']->leave_type_id == $leaveType['id']) selected @endif>
                  {{ $leaveType['name'] }}
                </option>
              @endforeach
            </select>
            <label for="leave_type_id">Select Leave Type <span class="text-danger">*</span></label>
            @if ($errors->has('leave_type_id'))
              <span class="fl-error">{{ $errors->first('leave_type_id') }}</span>
            @endif
          </div>
        </div>

        {{-- Leave Day --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <select class="form-control has-value" name="leave_day" id="leave_day"
                onchange="this.classList.toggle('has-value', this.value !== '')">
              <option value="">Select Leave Day</option>
              <option value="full_day"
                  {{ $editData['leave_details']->leave_day == 'full_day' ? 'selected' : '' }}>Full Day</option>
              <option value="first_half_day"
                  {{ $editData['leave_details']->leave_day == 'first_half_day' ? 'selected' : '' }}>First Half Day</option>
              <option value="second_half_day"
                  {{ $editData['leave_details']->leave_day == 'second_half_day' ? 'selected' : '' }}>Second Half Day</option>
            </select>
            <label for="leave_day">Select Leave Day <span class="text-danger">*</span></label>
          </div>
        </div>

        {{-- From Date --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="form-group">
            <label for="leave_start_date">From Date <span class="text-danger">*</span></label>
            <div class="calendar-icon">
              <input type="text" class="form-control" id="leave_start_date" name="leave_start_date"
                  value="{{ $editData['leave_details']->leave_start_date }}"
                  placeholder="Select From Date">
            </div>
          </div>
        </div>

        {{-- To Date --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="form-group">
            <label for="leave_end_date">To Date <span class="text-danger">*</span></label>
            <div class="calendar-icon">
              <input type="text" class="form-control" id="leave_end_date" name="leave_end_date"
                  value="{{ $editData['leave_details']->leave_end_date }}"
                  placeholder="Select To Date">
            </div>
          </div>
        </div>

        {{-- Reason --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="reason" name="reason"
                placeholder=" "
                value="{{ $editData['leave_details']->reason }}">
            <label for="reason">Reason <span class="text-danger">*</span></label>
          </div>
        </div>

      </div>

      <div class="row mt-4">
        <div class="col-lg-12 text-center">
          <a href="{{ route('list-leaves') }}" class="btn btn-sm btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-sm btn-bg-colour ml-2">Save Data</button>
        </div>
      </div>

    </form>

  </div>
</div>
</div></div></div></div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
function lockHalfDayToDate() {
    let dayType = $("#leave_day").val();
    let startDate = $("#leave_start_date").val();
    let endDateInput = $("#leave_end_date");

    if (dayType !== "first_half_day" && dayType !== "second_half_day") {
        endDateInput.prop("readonly", false);
        return;
    }

    if (startDate !== "") {
        endDateInput.val(startDate).prop("readonly", true);
    }
}

function initDatePickers() {
    $("#leave_start_date, #leave_end_date").datepicker("destroy");

    $("#leave_start_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        onSelect: function () {
            lockHalfDayToDate();
        }
    });

    $("#leave_end_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        beforeShow: function () {
            let dayType = $("#leave_day").val();
            let startDate = $("#leave_start_date").val();

            if (dayType === "first_half_day" || dayType === "second_half_day") {
                $(this).datepicker("option", "beforeShowDay", function (date) {
                    let d = $.datepicker.formatDate("yy-mm-dd", date);
                    return [d === startDate];
                });
            } else {
                $(this).datepicker("option", "beforeShowDay", null);
            }
        }
    });
}

$("#leave_day").on("change", function () { lockHalfDayToDate(); });
$("#leave_start_date").on("change", function () { lockHalfDayToDate(); });

$(document).ready(function () {
    initDatePickers();
    lockHalfDayToDate();
});

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
@endpush

@endsection
