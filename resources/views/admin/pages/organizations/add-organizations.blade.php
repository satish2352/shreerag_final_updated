@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Organization</h4>
    <a href="{{ route('list-organizations') }}" class="emp-form-close" title="Close">&times;</a>
  </div>

  <div class="emp-form-body">

    <form action="{{ route('store-organizations') }}" method="POST"
        enctype="multipart/form-data" id="addOrgForm">
      @csrf
      <div class="row">

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="company_name" name="company_name"
                placeholder=" " value="{{ old('company_name') }}">
            <label for="company_name">Company Name <span class="red-text">*</span></label>
            @if ($errors->has('company_name'))
              <span class="fl-error">{{ $errors->first('company_name') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="email" name="email"
                placeholder=" " value="{{ old('email') }}">
            <label for="email">Email <span class="red-text">*</span></label>
            @if ($errors->has('email'))
              <span class="fl-error">{{ $errors->first('email') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="gst_no" name="gst_no"
                placeholder=" " value="{{ old('gst_no') }}">
            <label for="gst_no">GST No <span class="red-text">*</span></label>
            @if ($errors->has('gst_no'))
              <span class="fl-error">{{ $errors->first('gst_no') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="mobile_number" name="mobile_number"
                placeholder=" " value="{{ old('mobile_number') }}"
                maxlength="10" pattern="\d{10}" title="Please enter a 10-digit mobile number" required>
            <label for="mobile_number">Mobile Number <span class="red-text">*</span></label>
            @if ($errors->has('mobile_number'))
              <span class="fl-error">{{ $errors->first('mobile_number') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="cin_number" name="cin_number"
                placeholder=" " value="{{ old('cin_number') }}">
            <label for="cin_number">CIN No <span class="red-text">*</span></label>
            @if ($errors->has('cin_number'))
              <span class="fl-error">{{ $errors->first('cin_number') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="address" name="address"
                placeholder=" " value="{{ old('address') }}">
            <label for="address">Company Address <span class="red-text">*</span></label>
            @if ($errors->has('address'))
              <span class="fl-error">{{ $errors->first('address') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="employee_count" name="employee_count"
                placeholder=" " value="{{ old('employee_count') }}">
            <label for="employee_count">Employee Count <span class="red-text">*</span></label>
            @if ($errors->has('employee_count'))
              <span class="fl-error">{{ $errors->first('employee_count') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="date" class="form-control" id="founding_date" name="founding_date"
                placeholder=" " value="{{ old('founding_date') }}">
            <label for="founding_date">Foundation Date <span class="red-text">*</span></label>
            @if ($errors->has('founding_date'))
              <span class="fl-error">{{ $errors->first('founding_date') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="facebook_link" name="facebook_link"
                placeholder=" " value="{{ old('facebook_link') }}">
            <label for="facebook_link">Facebook Link (optional)</label>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="instagram_link" name="instagram_link"
                placeholder=" " value="{{ old('instagram_link') }}">
            <label for="instagram_link">Instagram Link (optional)</label>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="twitter_link" name="twitter_link"
                placeholder=" " value="{{ old('twitter_link') }}">
            <label for="twitter_link">Twitter Link (optional)</label>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="website_link" name="website_link"
                placeholder=" " value="{{ old('website_link') }}">
            <label for="website_link">Website Link <span class="red-text">*</span></label>
            @if ($errors->has('website_link'))
              <span class="fl-error">{{ $errors->first('website_link') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="file" class="form-control" accept="image*" id="image" name="image">
            <label for="image">Image <span class="red-text">*</span></label>
          </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/additional-methods/1.19.3/additional-methods.min.js"></script>
<script>
    $(document).ready(function() {
        $("#addOrgForm").validate({
            rules: {
                company_name: { required: true, minlength: 3 },
                email: { required: true, email: true },
                gst_no: { required: true, minlength: 15 },
                cin_number: { required: true },
                mobile_number: { required: true, digits: true, minlength: 10, maxlength: 15 },
                address: { required: true, minlength: 5 },
                employee_count: { required: true, digits: true },
                founding_date: { required: true },
                website_link: { required: true },
                image: { required: true, extension: "jpg|jpeg|png|gif" }
            },
            messages: {
                company_name: {
                    required: "Please enter the company name",
                    minlength: "Company name should be at least 3 characters long"
                },
                email: {
                    required: "Please enter an email address",
                    email: "Please enter a valid email address"
                },
                gst_no: {
                    required: "Please enter the GST number",
                    minlength: "GST number should be at least 15 characters"
                },
                cin_number: { required: "Please enter the CIN number" },
                mobile_number: {
                    required: "Please enter a mobile number",
                    digits: "Please enter only digits",
                    minlength: "Mobile number should be at least 10 digits long",
                    maxlength: "Mobile number should be no more than 15 digits"
                },
                address: {
                    required: "Please enter the company address",
                    minlength: "Company address should be at least 5 characters long"
                },
                employee_count: {
                    required: "Please enter the employee count",
                    digits: "Please enter only digits"
                },
                founding_date: { required: "Please enter the foundation date" },
                website_link: { required: "Please enter a valid website link" },
                image: { required: "Please upload an image" }
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            }
        });
    });
</script>
@endsection
