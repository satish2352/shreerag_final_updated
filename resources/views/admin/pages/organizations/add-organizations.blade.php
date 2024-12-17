@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
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
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('store-organizations') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="company_name">Company Name:</label>
                                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter company name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="email">Email:</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="gst_no"> GST No:</label>
                                                <input type="text" class="form-control" id="gst_no"
                                                    name="gst_no" placeholder="Enter GST number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="mobile_number">Mobile Number:</label>
                                                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter mobile number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="address">Company Address:</label>
                                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter company address">
                                            </div>
                                       

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="employee_count">Employee Count:</label>
                                                <input type="text" class="form-control" id="employee_count" name="employee_count" placeholder="Enter employee count">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="founding_date">Foundation Date:</label>
                                                <input type="date" class="form-control" id="founding_date" name="founding_date" placeholder="Enter foundation date">
                                            </div>
                                        
                                       
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="facebook_link">Facebook Link:</label>
                                                <input type="text" class="form-control" id="facebook_link" name="facebook_link" placeholder="Enter Facebook link">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="instagram_link">Instagram Link:</label>
                                                <input type="text" class="form-control" id="instagram_link" name="instagram_link" placeholder="Enter Instagram link">
                                            </div>
                                      
                                       
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="twitter_link">Twitter Link:</label>
                                                <input type="text" class="form-control" id="twitter_link" name="twitter_link" placeholder="Enter Twitter link">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="website_link">Website Link:</label>
                                                <input type="text" class="form-control" id="website_link" name="website_link" placeholder="Enter website link">
                                            </div>
                                      
                                        
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="image">Image:</label>
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
                facebook_link: {
                    url: true
                },
                instagram_link: {
                    url: true
                },
                twitter_link: {
                    url: true
                },
                website_link: {
                    url: true
                },
                image: {
                    required: true,
                    extension: "jpg|jpeg|png|gif"
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
                facebook_link: {
                    url: "Please enter a valid Facebook link"
                },
                instagram_link: {
                    url: "Please enter a valid Instagram link"
                },
                twitter_link: {
                    url: "Please enter a valid Twitter link"
                },
                website_link: {
                    url: "Please enter a valid website link"
                },
                image: {
                    required: "Please upload an image",
                    extension: "Only image files (jpg, jpeg, png, gif) are allowed"
                }
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            }
        });
    });
</script>

@endsection
