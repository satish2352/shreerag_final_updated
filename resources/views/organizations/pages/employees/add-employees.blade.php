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
.red-text{
    color: red !important;
}
.password-toggle {
    cursor: pointer;
    position: absolute;
    top: 65%;
    right: 20px;
    transform: translateY(-50%);
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center>
                        <h1>Add Employee Data</h1>
                    </center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">


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
                                    <form action="{{ route('organizations-store-employees') }}" method="POST"
                                        id="addEmployeeForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="f_name">First Name</label></label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" name="f_name"
                                                        placeholder="First Name" value="{{ old('f_name') }}"
                                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        @if ($errors->has('f_name'))
                                                            <span class="red-text"><?php echo $errors->first('f_name', ':message'); ?></span>
                                                        @endif
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="m_name">Middle Name</label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" name="m_name" id="m_name"
                                                        placeholder="Middle Name" value="{{ old('m_name') }}"
                                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        @if ($errors->has('m_name'))
                                                            <span class="red-text"><?php echo $errors->first('m_name', ':message'); ?></span>
                                                        @endif
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="l_name">Last Name</label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" name="l_name"
                                                        placeholder="Last Name" value="{{ old('l_name') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    @if ($errors->has('l_name'))
                                                        <span class="red-text"><?php echo $errors->first('l_name', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="u_email">Employee Email:</label>&nbsp<span class="red-text">*</span>
                                                    <input type="email" class="form-control" id="u_email" name="u_email"
                                                        placeholder="Enter email" value="{{ old('u_email') }}">
                                                    @if ($errors->has('u_email'))
                                                        <span class="red-text"><?php echo $errors->first('u_email', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="number">Mobile Number:</label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" id="number"
                                                        name="number" placeholder="Enter mobile number" pattern="[789]{1}[0-9]{9}"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                        maxlength="10" minlength="10" 
                                                        value="{{ old('number') }}"
                                                        onkeyup="addvalidateMobileNumber(this.value)">
                                                    <span id="validation-message" class="red-text"></span>
                                                    @if ($errors->has('number'))
                                                        <span class="red-text"><?php echo $errors->first('number', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="state">State</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" id="state" name="state">
                                                        <option>Select State</option>
                                                        @foreach ($dynamic_state as $state)
                                                            @if (old('state') == $state['location_id'])
                                                                <option value="{{ $state['location_id'] }}" selected>
                                                                    {{ $state['name'] }}</option>
                                                            @else
                                                                <option value="{{ $state['location_id'] }}">
                                                                    {{ $state['name'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="city">City</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="city" id="city">
                                                        <option value="">Select City</option>
                                                    </select>
                                                    @if ($errors->has('city'))
                                                        <span class="red-text"><?php echo $errors->first('city', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="pincode">Pincode</label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" name="pincode" id="pincode"
                                                        placeholder="Enter Pincode" value="{{ old('pincode') }}"
                                                        onkeyup="addvalidatePincode(this.value)">
                                                    <span id="validation-message-pincode" class="red-text"></span>
                                                    @if ($errors->has('pincode'))
                                                        <span class="red-text"><?php echo $errors->first('pincode', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="address">Address</label>&nbsp<span class="red-text">*</span>
                                                    <input type="text" class="form-control" name="address" id="address"
                                                        placeholder="Enter Address" value="{{ old('address') }}">
                                                    @if ($errors->has('address'))
                                                        <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="designation">Designation</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <input type="text" class="form-control" name="designation"
                                                        id="designation" placeholder="Enter Designation" value="{{ old('designation') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    @if ($errors->has('designation'))
                                                        <span class="red-text"><?php echo $errors->first('designation', ':message'); ?></span>
                                                    @endif
                                                </div>

                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="company_name">Employee Name:</label>
                                                    <input type="text" class="form-control" id="employee_name"
                                                        name="employee_name" placeholder="Enter Employee name">
                                                </div> -->

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="company_id">Select Department:</label>&nbsp<span
                                                    class="red-text">*</span>
                                                    <select class="form-control custom-select-value"
                                                        name="department_id">
                                                        <ul class="dropdown-menu ">
                                                            <option value="">Select Company</option>
                                                            @foreach($dept as $datas)
                                                            <option value="{{$datas->id}}">
                                                                {{ucfirst($datas->department_name)}}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <label for="u_password">Password</label>&nbsp<span class="red-text">*</span>
                                                    <input type="password" class="password form-control" name="u_password"
                                                        id="u_password" placeholder="" value="{{ old('u_password') }}">
                                                    <span id="togglePassword" class="togglePpassword password-toggle"
                                                        onclick="togglePasswordVisibility()">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </span>
                                                    @if ($errors->has('u_password'))
                                                        <span class="red-text"><?php echo $errors->first('u_password', ':message'); ?></span>
                                                    @endif
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="sparkline12-graph">
                                                        <div id="pwd-container1">
                                                            <div class="form-group">
                                                                 <label for="password_confirmation">Confirm Password</label>&nbsp<span
                                                                    class="red-text">*</span>
                                                                <input type="password" class="password_confirmation form-control"
                                                                    id="password_confirmation" name="password_confirmation"
                                                                    value="{{ old('password_confirmation') }}">
                                                                <span id="toggleConfirmPassword" class=" toggleConfirmPpassword password-toggle"
                                                                    onclick="toggleConfirmPasswordVisibility()">
                                                                    <i class="fa fa-eye-slash"></i>
                                                                </span>
                                                                <span id="password-error" class="error-message red-text"></span>
                                                                @if ($errors->has('password_confirmation'))
                                                                    <span class="red-text"><?php echo $errors->first('password_confirmation', ':message'); ?></span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="pwstrength_viewport_progress"></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('organizations-list-employees') }}"
                                                            class="btn btn-white" style="margin-bottom:50px">Cancel</a>
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
<script src="{{asset('js/password-meter/pwstrength-bootstrap.min.js')}}"></script>
<script src="{{asset('js/password-meter/zxcvbn.js')}}"></script>
<script src="{{asset('js/password-meter/password-meter-active.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
<script>
    $(document).ready(function() {
        // Function to check if all input fields are filled with valid data
        function checkFormValidity() {
            const f_name = $('#f_name').val();
            const m_name = $('#m_name').val();
            const l_name = $('#l_name').val();
            const u_email = $('#u_email').val();
            const number = $('#number').val();
            const state = $('#state').val();
            const city = $('#city').val();
            const pincode = $('#pincode').val();
            const address = $('#address').val();
            const designation = $('#designation').val();
            const u_password = $('#u_password').val();
            const password_confirmation = $('#password_confirmation').val();
            // Enable the submit button if all fields are valid
            if (f_name && m_name && l_name && u_email && number && state && city && pincode && address && designation && u_password && password_confirmation) 
            {
                $('#submitButton').prop('disabled', false);
            } else {
                $('#submitButton').prop('disabled', true);
            }
        }

        $.validator.addMethod("number", function(value, element) {
            return this.optional(element) || /^[0-9]{10}$/.test(value);
        }, "Please enter a valid 10-digit number.");

        $.validator.addMethod("u_email", function(value, element) {
            // Regular expression for email validation
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return this.optional(element) || emailRegex.test(value);
        }, "Please enter a valid email address.");

        // Initialize the form validation
        $("#addEmployeeForm").validate({
            rules: {
                f_name: {
                    required: true,
                },
                m_name: {
                    required: true,
                },
                l_name: {
                    required: true,
                },
                u_email: {
                    required: true,
                    u_email:true,
                },
                number: {
                    required: true,
                    number:true,
                },
                state: {
                    required: true,
                },
                city: {
                    required: true,
                },
                pincode: {
                    required: true,
                },
                address: {
                    required: true,
                },
                designation: {
                    required: true,
                },
                u_password: {
                    required: true,
                },
                password_confirmation: {
                    required: true,
                },
            },
            messages: {
                f_name: {
                    required: "Please Enter the First Name",
                },
                m_name: {
                    required: "Please Enter the Middle Name",
                },
                l_name: {
                    required: "Please Enter the Last Name",
                },
                u_email: {
                    required: "Please Enter the Eamil",
                },
                number: {
                    required: "Please Enter the Number",
                },
                state: {
                    required: "Please Select State",
                },
                city: {
                    required: "Please Select City",
                },
                address: {
                    required: "Please Enter the Address",
                },
                pincode: {
                    required: "Please Enter the Pincode",
                },
                designation: {
                    required: "Please Enter the Designation",
                },
                u_password: {
                    required: "Please Enter the Password",
                },
                password_confirmation: {
                    required: "Please Enter the Confirmation Password",
                },
            },
        });
    });
</script>

<script>
    function addvalidateMobileNumber(number) {
        var mobileNumberPattern = /^\d*$/;
        var validationMessage = document.getElementById("validation-message");

        if (mobileNumberPattern.test(number)) {
            validationMessage.textContent = "";
        } else {
            validationMessage.textContent = "Please enter only numbers.";
        }
    }
</script>

<script>
    function addvalidatePincode(number) {
        var pincodePattern = /^\d*$/;
        var validationMessage = document.getElementById("validation-message-pincode");

        if (pincodePattern.test(number)) {
            validationMessage.textContent = "";
        } else {
            validationMessage.textContent = "Please enter only numbers.";
        }
    }
</script>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementsByClassName("password")[0];
        var toggleIcon = document.querySelector(".togglePpassword i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        }
    }
</script>

<script>
    function toggleConfirmPasswordVisibility() {
        var passwordInput = document.getElementsByClassName("password_confirmation")[0];

        var toggleIcon = document.querySelector(".toggleConfirmPpassword i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        }
    }
</script>

<script>
    $(document).ready(function() {

        $('#state').change(function(e) {
            e.preventDefault();
            var stateId = $('#state').val();
            // console.log(stateId);
            $('#city').html('<option value="">Select City</option>');

            if (stateId !== '') {
                $.ajax({
                    url: '{{ route('cities') }}',
                    type: 'GET',
                    data: {
                        stateId: stateId
                    },
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function(response) {
                        console.log(response);
                        if (response.city.length > 0) {
                            $.each(response.city, function(index, city) {
                                $('#city').append('<option value="' + city
                                    .location_id +
                                    '">' + city.name + '</option>');
                            });
                        }
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {

        $('#state').change(function(e) {
            e.preventDefault();
            var stateId = $('#state').val();
            // console.log(stateId);
            $('#city').html('<option value="">Select City</option>');

            if (stateId !== '') {
                $.ajax({
                    url: '{{ route('cities') }}',
                    type: 'GET',
                    data: {
                        stateId: stateId
                    },
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function(response) {
                        console.log(response);
                        if (response.city.length > 0) {
                            $.each(response.city, function(index, city) {
                                $('#city').append('<option value="' + city
                                    .location_id +
                                    '">' + city.name + '</option>');
                            });
                        }
                    }
                });
            }
        });
    });
</script>

@endsection