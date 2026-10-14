@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Organization</h4>
    <a href="{{ route('list-organizations') }}" class="emp-form-close" title="Close">&times;</a>
  </div>

  <div class="emp-form-body">

    <form action="{{ route('update-organizations') }}" method="POST"
        enctype="multipart/form-data" id="addOrgForm">
      @csrf
      <input type="hidden" class="form-control"
          value="@if (old('id')){{ old('id') }}@else{{ $editData->id }}@endif"
          id="id" name="id">

      <div class="row">

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="company_name" name="company_name"
                placeholder=" "
                value="@if (old('company_name')){{ old('company_name') }}@else{{ $editData->company_name }}@endif">
            <label for="company_name">Company Name</label>
            @if ($errors->has('company_name'))
              <span class="fl-error">{{ $errors->first('company_name') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="email" class="form-control" id="email" name="email"
                placeholder=" "
                value="@if (old('email')){{ old('email') }}@else{{ $editData->email }}@endif">
            <label for="email">Email</label>
            @if ($errors->has('email'))
              <span class="fl-error">{{ $errors->first('email') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="gst_no" name="gst_no"
                placeholder=" "
                value="@if (old('gst_no')){{ old('gst_no') }}@else{{ $editData->gst_no }}@endif">
            <label for="gst_no">GST No <span class="red-text">*</span></label>
            @if ($errors->has('gst_no'))
              <span class="fl-error">{{ $errors->first('gst_no') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="cin_number" name="cin_number"
                placeholder=" "
                value="@if (old('cin_number')){{ old('cin_number') }}@else{{ $editData->cin_number }}@endif">
            <label for="cin_number">CIN No</label>
            @if ($errors->has('cin_number'))
              <span class="fl-error">{{ $errors->first('cin_number') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="mobile_number" name="mobile_number"
                placeholder=" "
                value="@if (old('mobile_number')){{ old('mobile_number') }}@else{{ $editData->mobile_number }}@endif">
            <label for="mobile_number">Contact No</label>
            @if ($errors->has('mobile_number'))
              <span class="fl-error">{{ $errors->first('mobile_number') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="address" name="address"
                placeholder=" "
                value="@if (old('address')){{ old('address') }}@else{{ $editData->address }}@endif">
            <label for="address">Company Address</label>
            @if ($errors->has('address'))
              <span class="fl-error">{{ $errors->first('address') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="employee_count" name="employee_count"
                placeholder=" "
                value="@if (old('employee_count')){{ old('employee_count') }}@else{{ $editData->employee_count }}@endif">
            <label for="employee_count">Employee Count</label>
            @if ($errors->has('employee_count'))
              <span class="fl-error">{{ $errors->first('employee_count') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="date" class="form-control" id="founding_date" name="founding_date"
                placeholder=" "
                value="{{ old('founding_date', $editData->founding_date) }}">
            <label for="founding_date">Founding Date</label>
            @if ($errors->has('founding_date'))
              <span class="fl-error">{{ $errors->first('founding_date') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="facebook_link" name="facebook_link"
                placeholder=" "
                value="@if (old('facebook_link')){{ old('facebook_link') }}@else{{ $editData->facebook_link }}@endif">
            <label for="facebook_link">Facebook Link</label>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="instagram_link" name="instagram_link"
                placeholder=" "
                value="@if (old('instagram_link')){{ old('instagram_link') }}@else{{ $editData->instagram_link }}@endif">
            <label for="instagram_link">Instagram Link</label>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="twitter_link" name="twitter_link"
                placeholder=" "
                value="@if (old('twitter_link')){{ old('twitter_link') }}@else{{ $editData->twitter_link }}@endif">
            <label for="twitter_link">Twitter Link</label>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="website_link" name="website_link"
                placeholder=" "
                value="@if (old('website')){{ old('website') }}@else{{ $editData->website }}@endif">
            <label for="website_link">Website Link</label>
            @if ($errors->has('website_link'))
              <span class="fl-error">{{ $errors->first('website_link') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="file" class="form-control" id="image" name="image"
                value="@if (old('image')){{ old('image') }}@else{{ $editData->image }}@endif">
            <label for="image">Image</label>
            @if ($errors->has('image'))
              <span class="fl-error">{{ $errors->first('image') }}</span>
            @endif
          </div>
          @if (old('image') || isset($editData))
            <div>
              <label>Old Image: </label>
              <img src="@if (old('image')){{ old('image') }} @elseif(isset($editData)){{ Config::get('DocumentConstant.ORGANIZATION_VIEW') . $editData->image }}@endif"
                  alt="Old Image" style="max-width: 100px;">
            </div>
          @endif
        </div>

        <div class="col-md-12 col-sm-12 text-center mt-3">
          <a href="{{ route('list-organizations') }}" class="btn btn-white">Cancel</a>
          <button class="btn btn-sm btn-primary ml-2" type="submit">Save Data</button>
        </div>

      </div>
    </form>

  </div>
</div>
</div></div></div></div>

@push('scripts')
<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $.validator.addMethod("regex", function(value, element, regexp) {
            if (regexp.constructor != RegExp) regexp = new RegExp(regexp);
            else if (regexp.global) regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        }, "Please check your input.");

        $(document).ready(function() {
            $('.datetimepicker').datetimepicker({ format: 'YYYY-MM-DD' });
            const foundingDate = $('#founding_date').val();
            if (foundingDate) { $('#founding_date').data("DateTimePicker").date(foundingDate); }
        });

        $("#addOrgForm").validate({
            rules: {
                company_name: { required: true, minlength: 3 },
                email: { required: true, email: true },
                gst_no: { required: true, minlength: 15 },
                cin_number: { required: true },
                mobile_number: { required: true, digits: true, minlength: 10, maxlength: 15 },
                address: { required: true },
                founding_date: { required: true },
                website_link: { required: true },
            },
            messages: {
                company_name: {
                    required: "Please enter the company name",
                    minlength: "Company name should be at least 3 characters long"
                },
                email: { required: "Please enter an email address", email: "Please enter a valid email address" },
                gst_no: { required: "Please enter the GST number", minlength: "GST number should be at least 15 characters" },
                mobile_number: {
                    required: "Please enter a mobile number",
                    digits: "Please enter only digits",
                    minlength: "Mobile number should be at least 10 digits long",
                    maxlength: "Mobile number should be no more than 15 digits"
                },
                cin_number: { required: "Please enter the CIN number" },
                address: { required: "Please enter the company address" },
                employee_count: { required: "Please enter the employee count", digits: "Please enter only digits" },
                founding_date: { required: "Please enter the foundation date" },
                website_link: { required: "Please enter a valid website link" },
            },
            errorPlacement: function(error, element) { error.appendTo(element.parent()); }
        });
    });
</script>
<script>
    flatpickr(".flatpickr-input", { dateFormat: "d/m/Y", allowInput: true });
</script>
@endpush
@endsection
