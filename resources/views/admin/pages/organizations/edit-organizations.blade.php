@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }

        .error {
            color: red !important;
        }

        .red-text {
            color: red !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add Organization Data</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    <form action="{{ route('update-organizations') }}" method="POST"
                                        enctype="multipart/form-data" id="addOrgForm">
                                        @csrf
                                        <div class="form-group-inner">
                                            <input type="hidden" class="form-control"
                                                value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif"
                                                id="id" name="id">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="company_name">Company Name:</label>
                                                    <input type="text" class="form-control"
                                                        value="@if (old('company_name')) {{ old('company_name') }}@else{{ $editData->company_name }} @endif"
                                                        id="company_name" name="company_name"
                                                        placeholder="Enter company name">
                                                    @if ($errors->has('company_name'))
                                                        <span class="red-text"><?php echo $errors->first('company_name', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="email">Email:</label>
                                                    <input type="email" class="form-control" id="email"
                                                        value="@if (old('email')) {{ old('email') }}@else{{ $editData->email }} @endif"
                                                        name="email" placeholder="Enter email">
                                                    @if ($errors->has('email'))
                                                        <span class="red-text"><?php echo $errors->first('email', ':message'); ?></span>
                                                    @endif
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="gst_no"> GST No: <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="gst_no"
                                                        value="@if (old('gst_no')) {{ old('gst_no') }}@else{{ $editData->gst_no }} @endif"
                                                        name="gst_no" placeholder="Enter GST number">
                                                    @if ($errors->has('gst_no'))
                                                        <span class="red-text"><?php echo $errors->first('gst_no', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="cin_number">CIN No. :</label>
                                                    <input type="cin_number" class="form-control" id="cin_number"
                                                        value="@if (old('cin_number')) {{ old('cin_number') }}@else{{ $editData->cin_number }} @endif"
                                                        name="cin_number" placeholder="Enter cin number">
                                                    @if ($errors->has('cin_number'))
                                                        <span class="red-text"><?php echo $errors->first('cin_number', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="mobile_number">Contact No. :</label>
                                                    <input type="text" class="form-control" id="mobile_number"
                                                        name="mobile_number" placeholder="Enter your contact No."
                                                        value="@if (old('mobile_number')) {{ old('mobile_number') }}@else{{ $editData->mobile_number }} @endif">
                                                    @if ($errors->has('mobile_number'))
                                                        <span class="red-text"><?php echo $errors->first('mobile_number', ':message'); ?></span>
                                                    @endif
                                                </div>


                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="address">Company Address:</label>
                                                    <input type="text" class="form-control" id="address"
                                                        value="@if (old('address')) {{ old('address') }}@else{{ $editData->address }} @endif"
                                                        name="address" placeholder="Enter company address">
                                                    @if ($errors->has('address'))
                                                        <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                                    @endif
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="employee_count">Employee Count:</label>
                                                    <input type="text" class="form-control" id="employee_count"
                                                        name="employee_count" placeholder="Enter your contact No."
                                                        value="@if (old('employee_count')) {{ old('employee_count') }}@else{{ $editData->employee_count }} @endif">
                                                    @if ($errors->has('employee_count'))
                                                        <span class="red-text"><?php echo $errors->first('employee_count', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="founding_date">Founding Date:</label>
                                                    <input type="date" class="form-control" id="founding_date"
                                                        name="founding_date"
                                                        value="{{ old('founding_date', $editData->founding_date) }}"
                                                        placeholder="Enter company founding date">
                                                    @if ($errors->has('founding_date'))
                                                        <span class="red-text">{{ $errors->first('founding_date') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="facebook_link">Facebook Link:</label>
                                                    <input type="text" class="form-control" id="facebook_link"
                                                        value="@if (old('facebook_link')) {{ old('facebook_link') }}@else{{ $editData->facebook_link }} @endif"
                                                        name="facebook_link" placeholder="Enter Facebook link">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="instagram_link">Instagram Link:</label>
                                                    <input type="text" class="form-control" id="instagram_link"
                                                        value="@if (old('instagram_link')) {{ old('instagram_link') }}@else{{ $editData->instagram_link }} @endif"
                                                        name="instagram_link" placeholder="Enter Instagram link">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="twitter_link">Twitter Link:</label>
                                                    <input type="text" class="form-control" id="twitter_link"
                                                        value="@if (old('twitter_link')) {{ old('twitter_link') }}@else{{ $editData->twitter_link }} @endif"
                                                        name="twitter_link" placeholder="Enter Twitter link">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="website_link">Website Link:</label>
                                                    <input type="text" class="form-control" id="website_link"
                                                        value="@if (old('website')) {{ old('website') }}@else{{ $editData->website }} @endif"
                                                        name="website_link" placeholder="Enter website link">
                                                    @if ($errors->has('website_link'))
                                                        <span class="red-text"><?php echo $errors->first('website_link', ':message'); ?></span>
                                                    @endif
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="image">Image:</label>
                                                    <input type="file" class="form-control" id="image"
                                                        name="image"
                                                        value="@if (old('image')) {{ old('image') }}@else{{ $editData->image }} @endif">
                                                    @if (old('image') || isset($editData))
                                                        <div>
                                                            <label>Old Image: </label>
                                                            <img src="@if (old('image')) {{ old('image') }} @elseif(isset($editData)) {{ Config::get('DocumentConstant.ORGANIZATION_VIEW') . $editData->image }} @endif"
                                                                alt="Old Image" style="max-width: 100px;">
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-organizations') }}"><button
                                                                class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</button></a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs"
                                                            type="submit" style="margin-bottom:50px">Save Data</button>
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
 @push('scripts')  
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Validate the form
            $.validator.addMethod("regex", function(value, element, regexp) {
                if (regexp.constructor != RegExp)
                    regexp = new RegExp(regexp);
                else if (regexp.global)
                    regexp.lastIndex = 0;
                return this.optional(element) || regexp.test(value);
            }, "Please check your input.");

            $(document).ready(function() {
                $('.datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD', // Ensure format matches your date value
                });

                // Prepopulate the datepicker with the value if it exists
                const foundingDate = $('#founding_date').val();
                if (foundingDate) {
                    $('#founding_date').data("DateTimePicker").date(foundingDate);
                }
            });


            $("#addOrgForm").validate({
                rules: {
                    company_name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    gst_no: {
                        required: true,
                        minlength: 15
                    },
                    cin_number: {
                        required: true,
                    },
                    mobile_number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    address: {
                        required: true,

                    },
                    founding_date: {
                        required: true,
                        digits: true
                    },
                    founding_date: {
                        required: true
                    },
                    website_link: {
                        required: true,
                    },
                    // image: {
                    //     required: true,
                    // }
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
                    mobile_number: {
                        required: "Please enter a mobile number",
                        digits: "Please enter only digits",
                        minlength: "Mobile number should be at least 10 digits long",
                        maxlength: "Mobile number should be no more than 15 digits"
                    },
                    cin_number: {
                        required: "Please enter the CIN number",
                    },
                    address: {
                        required: "Please enter the company address",

                    },
                    employee_count: {
                        required: "Please enter the employee count",
                        digits: "Please enter only digits"
                    },
                    founding_date: {
                        required: "Please enter the foundation date"
                    },
                    website_link: {
                        required: "Please enter a valid website link"
                    },
                    // image: {
                    //     required: "Please upload an image",

                    // }
                },
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                }
            });
        });
    </script>
    <script>
        flatpickr(".flatpickr-input", {
            dateFormat: "d/m/Y",
            allowInput: true
        });
    </script>
    @endpush
@endsection
