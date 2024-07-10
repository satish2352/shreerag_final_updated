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
                        <h1>Vendor Registration Form</h1>
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
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Success!</strong> {{ Session::get('msg') }}
                                </div>
                            </div>
                            @endif

                            @if (Session::get('status') == 'error')
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Error!</strong> {!! session('msg') !!}
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    <form action="{{ route('update-vendor') }}" method="POST" id="addDesignsForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group-inner">
                                        <input type="hidden" class="form-control"
                                            value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif"
                                            id="id" name="id">

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="vendor_name">Vendor Name:</label>
                                                        <input class="form-control" name="vendor_name" id="vendor_name"
                                                    placeholder="Enter the company name"
                                                    value=" @if (old('vendor_name')) {{ old('vendor_name') }}@else{{ $editData->vendor_name }} @endif">
                                                </div>   
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="vendor_company_name">Company Name:</label>
                                                    <input class="form-control" name="vendor_company_name" id="vendor_company_name"
                                                placeholder="Enter the Vendor Name"
                                                value=" @if (old('vendor_company_name')) {{ old('vendor_company_name') }}@else{{ $editData->vendor_company_name }} @endif">
                                            </div>  
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="vendor_email">Email:</label>
                                                    <input type="email" class="form-control" id="vendor_email"
                                                        name="vendor_email" placeholder="Enter your vendor_email" 
                                                        value=" @if (old('vendor_email')) {{ old('vendor_email') }}@else{{ $editData->vendor_email }} @endif">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="contact_no">Contact No. :</label>
                                                    <input type="text" class="form-control" id="contact_no"
                                                        name="contact_no" placeholder="Enter your contact No."
                                                        value=" @if (old('contact_no')) {{ old('contact_no') }}@else{{ $editData->contact_no }} @endif">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="gst_no">GST No:</label>
                                                    <input type="text" class="form-control" id="gst_no"
                                                        name="gst_no" placeholder="Enter GST number"
                                                        value=" @if (old('gst_no')) {{ old('gst_no') }}@else{{ $editData->gst_no }} @endif">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="quote_no">Quote No:</label>
                                                    <input type="text" class="form-control" id="quote_no"
                                                        name="quote_no" placeholder="Enter your quote no"
                                                        value=" @if (old('quote_no')) {{ old('quote_no') }}@else{{ $editData->quote_no }} @endif">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="payment_terms">Payment Terms:</label>
                                                    <input type="text" class="form-control" id="payment_terms"
                                                        name="payment_terms" placeholder="Enter your payment terms"
                                                        value=" @if (old('payment_terms')) {{ old('payment_terms') }}@else{{ $editData->payment_terms }} @endif">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="vendor_address">Address :</label>
                                                    <input type="text" class="form-control" id="vendor_address"
                                                        name="vendor_address" placeholder="Enter your vendor_address"
                                                        value=" @if (old('vendor_address')) {{ old('vendor_address') }}@else{{ $editData->vendor_address }} @endif">
                                                </div>    
                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="is_active"
                                                    id="is_active" value="y" data-parsley-multiple="is_active"
                                                    {{ old('is_active') ? 'checked' : '' }}>
                                                Is Active
                                                <i class="input-helper"></i><i class="input-helper"></i></label>
                                                   <!-- <label for="status">Status :</label>
                                                    <input type="text" class="form-control" id="status"
                                                        name="status" placeholder="Enter status here"> -->
                                                </div>
                                               
                                            </div>
                                                
                                      

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-vendor') }}" class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</a>
                                                            <button class="btn btn-sm btn-primary login-submit-cs"
                                                                type="submit" style="margin-bottom:50px">Save
                                                                Data</button>
                                                        </div>
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
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
<script>
var i = 0;

$("#add").click(function() {
    ++i;

    $("#dynamicTable").append(
        '<tr><td><input type="text" name="addmore[' +
        i +
        '][quantity]" placeholder="Enter your quantity" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][description]" placeholder="Enter your description" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][price]" placeholder="Enter your Price" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][amount]" placeholder="Enter your amount" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][total]" placeholder="Enter your total" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
    );
});

$(document).on("click", ".remove-tr", function() {
    $(this).parents("tr").remove();
});
</script>
<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $("#addDesignsForm").validate({
        rules: {
            vendor_name: {
            required: true,
            },
            vendor_address: {
                required: true,
                // Add your custom validation rule if needed
            },
            gst_no: {
                required: true,
            },
            contact_no: {
                required: true,
            },
            vendor_email: {
                required: true,
            },
            quote_no: {
                required: true,
            },
            payment_terms: {
                required: true,
            },
            status: {
                required: true,
            },
        },
        messages: {
            vendor_name: {
                required: "Please enter your name.",
            },
            vendor_address: {
                required: "Please enter your vendor_address.",
            },
            gst_no: {
                required: "Please enter your GST No.",
            },
            contact_no: {
                required: "Please enter a valid contact no.",
            },
            vendor_email: {
                required: "Please enter your valid email.",
            },
            quote_no: {
                required: "Please enter your quote no.",
            },
            payment_terms: {
                required: "Please enter your payment terms.",
            },  
            status: {
                required: "Please enter status",
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



@endsection