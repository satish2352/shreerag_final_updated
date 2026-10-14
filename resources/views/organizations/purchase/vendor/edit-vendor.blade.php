@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Vendor</h4>
    <a href="{{ route('list-vendor') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    @if (session('msg'))
    <div class="alert alert-{{ session('status') }} mb-3">{{ session('msg') }}</div>
    @endif

    @if (Session::get('status') == 'success')
    <div class="alert alert-success alert-dismissible mb-3" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Success!</strong> {{ Session::get('msg') }}
    </div>
    @endif

    @if (Session::get('status') == 'error')
    <div class="alert alert-danger alert-dismissible mb-3" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Error!</strong> {!! session('msg') !!}
    </div>
    @endif

    <form action="{{ route('update-vendor') }}" method="POST" id="addDesignsForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{ old('id', $editData->id) }}" id="id" name="id">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input class="form-control" name="vendor_name" id="vendor_name" placeholder=" " value="{{ old('vendor_name', $editData->vendor_name) }}">
                    <label for="vendor_name">Vendor Name <span class="red-text">*</span></label>
                    @if($errors->has('vendor_name'))<span class="fl-error">{{ $errors->first('vendor_name') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input class="form-control" name="vendor_company_name" id="vendor_company_name" placeholder=" " value="{{ old('vendor_company_name', $editData->vendor_company_name) }}">
                    <label for="vendor_company_name">Company Name <span class="red-text">*</span></label>
                    @if($errors->has('vendor_company_name'))<span class="fl-error">{{ $errors->first('vendor_company_name') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="email" class="form-control" name="vendor_email" id="vendor_email" placeholder=" " value="{{ old('vendor_email', $editData->vendor_email) }}">
                    <label for="vendor_email">Email <span class="red-text">*</span></label>
                    @if($errors->has('vendor_email'))<span class="fl-error">{{ $errors->first('vendor_email') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="contact_no" id="contact_no" placeholder=" " value="{{ old('contact_no', $editData->contact_no) }}">
                    <label for="contact_no">Contact No. <span class="red-text">*</span></label>
                    @if($errors->has('contact_no'))<span class="fl-error">{{ $errors->first('contact_no') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="gst_no" id="gst_no" placeholder=" " value="{{ old('gst_no', $editData->gst_no) }}">
                    <label for="gst_no">Vendor GST No <span class="red-text">*</span></label>
                    @if($errors->has('gst_no'))<span class="fl-error">{{ $errors->first('gst_no') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="quote_no" id="quote_no" placeholder=" " value="{{ old('quote_no', $editData->quote_no) }}">
                    <label for="quote_no">Vendor Quote No <span class="red-text">*</span></label>
                    @if($errors->has('quote_no'))<span class="fl-error">{{ $errors->first('quote_no') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="payment_terms" id="payment_terms" placeholder=" " value="{{ old('payment_terms', $editData->payment_terms) }}">
                    <label for="payment_terms">Payment Terms <span class="red-text">*</span></label>
                    @if($errors->has('payment_terms'))<span class="fl-error">{{ $errors->first('payment_terms') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="vendor_address" id="vendor_address" placeholder=" " value="{{ old('vendor_address', $editData->vendor_address) }}">
                    <label for="vendor_address">Address <span class="red-text">*</span></label>
                    @if($errors->has('vendor_address'))<span class="fl-error">{{ $errors->first('vendor_address') }}</span>@endif
                </div>
            </div>
            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="is_active"
                id="is_active" value="y" data-parsley-multiple="is_active"
                {{ old('is_active') ? 'checked' : '' }}>
            Is Active
            <i class="input-helper"></i><i class="input-helper"></i></label>

            </div> --}}
        </div>
        <div class="row mt-3">
            <div class="col-12 text-right">
                <a href="{{ route('list-vendor') }}" class="btn btn-secondary mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Data</button>
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
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        }, "Please check your input.");

        $("#addDesignsForm").validate({
            rules: {
                vendor_name: {
                    required: true,
                },
                vendor_company_name: {
                    required: true,
                },
                vendor_address: {
                    required: true,
                },
                gst_no: {
                    required: true,
                    regex: /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/ // Add your GST pattern here
                },
                contact_no: {
                    required: true,
                    regex: /^[0-9]{10}$/
                },
                vendor_email: {
                    required: true,
                    email: true
                },
                quote_no: {
                    required: true,
                    regex: /^[A-Z0-9]+$/
                },
                payment_terms: {
                    required: true,
                },
            },
            messages: {
                vendor_name: {
                    required: "Please enter your name.",
                },
                vendor_company_name: {
                    required: "Please enter company name.",
                },
                vendor_address: {
                    required: "Please enter your vendor address.",
                },
                gst_no: {
                    required: "Please enter your GST No.",
                    regex: "Please enter a valid GST No."
                },
                contact_no: {
                    required: "Please enter a valid contact no.",
                    regex: "Please enter a valid 10 digit mobile number."
                },
                vendor_email: {
                    required: "Please enter your valid email.",
                    email: "Please enter a valid email address."
                },
                quote_no: {
                    required: "Please enter your quote no.",
                    regex: "Please enter a valid Quote No."
                },
                payment_terms: {
                    required: "Please enter your payment terms.",
                },

            },
            submitHandler: function(form) {
                // Use SweetAlert to show a success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Vendor added successfully.',
                }).then(function() {
                    form.submit(); // Submit the form after the user clicks OK
                });
            }
        });
    });
    </script>
@endpush

@endsection
