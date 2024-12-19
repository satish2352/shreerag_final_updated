@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
    }
    .error{
        color: red !important;
    }
    .red-text{
        color: red !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Organization Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form action="{{ route('store-organizations') }}" method="POST" enctype="multipart/form-data" id="addOrgForm">
                                    @csrf
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="company_name">Company Name: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="company_name" value="{{ old('company_name') }}" name="company_name" placeholder="Enter company name">
                                                @if ($errors->has('company_name'))
                                                <span class="red-text"><?php echo $errors->first('company_name', ':message'); ?></span>
                                            @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="email">Email: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="email" value="{{ old('email') }}" name="email" placeholder="Enter email">
                                                @if ($errors->has('email'))
                                                <span class="red-text"><?php echo $errors->first('email', ':message'); ?></span>
                                            @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="gst_no"> GST No: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="gst_no" value="{{ old('gst_no') }}"
                                                    name="gst_no" placeholder="Enter GST number">
                                                    @if ($errors->has('gst_no'))
                                                    <span class="red-text"><?php echo $errors->first('gst_no', ':message'); ?></span>
                                                @endif
                                                </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="mobile_number">Mobile Number: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="mobile_number" value="{{ old('mobile_number') }}" name="mobile_number" placeholder="Enter mobile number" maxlength="10" pattern="\d{10}" title="Please enter a 10-digit mobile number" required>
                                                @if ($errors->has('mobile_number'))
                                                <span class="red-text"><?php echo $errors->first('mobile_number', ':message'); ?></span>
                                            @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="cin_number">CIN No. : <span class="text-danger">*</span></label>
                                                <input type="cin_number" class="form-control" id="cin_number" value="{{ old('cin_number') }}" name="cin_number" placeholder="Enter cin number">
                                                @if ($errors->has('cin_number'))
                                                <span class="red-text"><?php echo $errors->first('cin_number', ':message'); ?></span>
                                            @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="address">Company Address: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="address" value="{{ old('address') }}" name="address" placeholder="Enter company address">
                                                @if ($errors->has('address'))
                                                <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                            @endif
                                            </div>
                                       

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="employee_count">Employee Count: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="address" value="{{ old('employee_count') }}" name="employee_count" placeholder="Enter employee count">
                                                @if ($errors->has('employee_count'))
                                                <span class="red-text"><?php echo $errors->first('employee_count', ':message'); ?></span>
                                            @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="founding_date">Foundation Date: <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="founding_date" value="{{ old('founding_date') }}" name="founding_date" placeholder="Enter foundation date">
                                                @if ($errors->has('founding_date'))
                                                <span class="red-text"><?php echo $errors->first('founding_date', ':message'); ?></span>
                                            @endif
                                            </div>
                                        
                                       
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="facebook_link">Facebook Link (optional):</label>
                                                <input type="text" class="form-control" id="facebook_link" value="{{ old('facebook_link') }}" name="facebook_link" placeholder="Enter Facebook link">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="instagram_link">Instagram Link (optional):</label>
                                                <input type="text" class="form-control" id="instagram_link" value="{{ old('instagram_link') }}" name="instagram_link" placeholder="Enter Instagram link">
                                            </div>
                                      
                                       
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="twitter_link">Twitter Link (optional):</label>
                                                <input type="text" class="form-control" id="twitter_link" name="twitter_link" value="{{ old('twitter_link') }}" placeholder="Enter Twitter link">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="website_link">Website Link: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="website_link"  value="{{ old('website_link') }}" name="website_link" placeholder="Enter website link">
                                            </div>
                                      
                                        
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="image">Image: <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" accept="image*" id="image" name="image">
                                            </div>
                                            
                                        </div>

                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-organizations') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
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
    <script src="js/vendor/jquery-1.11.3.min.js"></script>
    <script src="js/password-meter/pwstrength-bootstrap.min.js"></script>
    <script src="js/password-meter/zxcvbn.js"></script>
    <script src="js/password-meter/password-meter-active.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/additional-methods/1.19.3/additional-methods.min.js"></script>
    <script>
    $(document).ready(function() {
        // Validate the form
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
                    minlength: 5
                },
                employee_count: {
                    required: true,
                    digits: true
                },
                founding_date: {
                    required: true
                },
                // facebook_link: {
                //     url: true
                // },
                // instagram_link: {
                //     url: true
                // },
                // twitter_link: {
                //     url: true
                // },
                website_link: {
                    required: true,
                },
                image: {
                    required: true,
                    // extension: "jpg|jpeg|png|gif"
                }
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
                cin_number: {
                    required: "Please enter the CIN number",
                },
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
                founding_date: {
                    required: "Please enter the foundation date"
                },
                // facebook_link: {
                //     url: "Please enter a valid Facebook link"
                // },
                // instagram_link: {
                //     url: "Please enter a valid Instagram link"
                // },
                // twitter_link: {
                //     url: "Please enter a valid Twitter link"
                // },
                website_link: {
                    required: "Please enter a valid website link"
                },
                image: {
                    required: "Please upload an image",
                    // extension: "Only image files (jpg, jpeg, png, gif) are allowed"
                }
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            }
        });
    });
</script>

@endsection
