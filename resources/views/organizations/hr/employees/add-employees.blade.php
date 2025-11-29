@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list" style="padding-bottom: 100px">
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
                                    <div class="all-form-element-inner">
                                        <form class="forms-sample" id="regForm" name="regForm" method="post"
                                            role="form" action="{{ route('add-employee') }}" enctype="multipart/form-data">
                                            <div class="row">
                                                <input type="hidden" name="_token" id="csrf-token"
                                                    value="{{ Session::token() }}" />
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="u_email">Email ID</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" name="u_email"
                                                            id="u_email" placeholder="" value="{{ old('u_email') }}">
                                                        @if ($errors->has('u_email'))
                                                            <span class="red-text"><?php echo $errors->first('u_email', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="role_id">Department</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <select class="form-control" id="role_id" name="role_id"
                                                            onchange="myFunction(this.value)">
                                                            <option value="">Select Department</option>
                                                            @foreach ($roles as $role)
                                                                @if (old('role_id') == $role['id'])
                                                                    <option value="{{ $role['id'] }}" selected>
                                                                        {{ $role['role_name'] }}</option>
                                                                @else
                                                                    <option value="{{ $role['id'] }}">
                                                                        {{ $role['role_name'] }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('role_id'))
                                                            <span class="red-text"><?php echo $errors->first('role_id', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="u_password">Password</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="password" class="password form-control"
                                                            name="u_password" id="u_password" placeholder=""
                                                            value="{{ old('u_password') }}">
                                                        <span id="togglePassword" class="togglePpassword password-toggle"
                                                            onclick="togglePasswordVisibility()">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </span>
                                                        @if ($errors->has('u_password'))
                                                            <span class="red-text"><?php echo $errors->first('u_password', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="password_confirmation">Confirm
                                                            Password</label>&nbsp<span class="red-text">*</span>
                                                        <input type="password" class="password_confirmation form-control"
                                                            id="password_confirmation" name="password_confirmation"
                                                            value="{{ old('password_confirmation') }}">
                                                        <span id="toggleConfirmPassword"
                                                            class=" toggleConfirmPpassword password-toggle-confirm"
                                                            onclick="toggleConfirmPasswordVisibility()">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </span>
                                                        <span id="password-error" class="error-message red-text"></span>
                                                        @if ($errors->has('password_confirmation'))
                                                            <span class="red-text"><?php echo $errors->first('password_confirmation', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="f_name">First Name</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" name="f_name"
                                                            id="f_name" placeholder="" value="{{ old('f_name') }}"
                                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        @if ($errors->has('f_name'))
                                                            <span class="red-text"><?php echo $errors->first('f_name', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="m_name">Middle Name</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" name="m_name"
                                                            id="m_name" placeholder="" value="{{ old('m_name') }}"
                                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        @if ($errors->has('m_name'))
                                                            <span class="red-text"><?php echo $errors->first('m_name', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="l_name">Last Name</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" name="l_name"
                                                            id="l_name" placeholder="" value="{{ old('l_name') }}"
                                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        @if ($errors->has('l_name'))
                                                            <span class="red-text"><?php echo $errors->first('l_name', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="number">Mobile Number</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" name="number"
                                                            id="number" pattern="[789]{1}[0-9]{9}"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                            maxlength="10" minlength="10" placeholder=""
                                                            value="{{ old('number') }}"
                                                            onkeyup="addvalidateMobileNumber(this.value)">
                                                        <span id="validation-message" class="red-text"></span>
                                                        @if ($errors->has('number'))
                                                            <span class="red-text"><?php echo $errors->first('number', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="designation">Designation</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" name="designation"
                                                            id="designation" placeholder=""
                                                            value="{{ old('designation') }}"
                                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        @if ($errors->has('number'))
                                                            <span class="red-text"><?php echo $errors->first('designation', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="address">Address</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" name="address"
                                                            id="address" placeholder="" value="{{ old('address') }}">
                                                        @if ($errors->has('address'))
                                                            <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="state">State</label>&nbsp<span
                                                            class="red-text">*</span>
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
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="city">City</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <select class="form-control" name="city" id="city">
                                                            <option value="">Select City</option>
                                                        </select>
                                                        @if ($errors->has('city'))
                                                            <span class="red-text"><?php echo $errors->first('city', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="pincode">Pincode</label>&nbsp<span
                                                            class="red-text">*</span>
                                                        <input type="text" class="form-control" id="pincode"
                                                            name="pincode" oninput="addvalidatePincode(this.value)">
                                                        <span id="validation-message-pincode" style="color: red;"></span>
                                                        <span id="validation-message-pincode" class="red-text"></span>
                                                        @if ($errors->has('pincode'))
                                                            <span class="red-text"><?php echo $errors->first('pincode', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 user_tbl">
                                                    <div id="data_for_role">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                                                    <div class="form-group form-check form-check-flat form-check-primary">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_active" id="is_active" value="y"
                                                                data-parsley-multiple="is_active"
                                                                {{ old('is_active') ? 'checked' : '' }}>
                                                            Is Active
                                                            <i class="input-helper"></i><i
                                                                class="input-helper"></i></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12 text-center">
                                                    <button type="submit" class="btn btn-sm btn-bg-colour"
                                                        id="submitButton">
                                                        Save &amp; Submit
                                                    </button>
                                                    <span><a href="{{ route('list-employee') }}"
                                                            class="btn btn-sm btn-primary ">Back</a></span>
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
    </div>
   @push('scripts')
    <script type="text/javascript">
        function submitRegister() {
            document.getElementById("frm_register").submit();
        }
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
            // Function to check if all input fields are filled with valid data
            function checkFormValidity() {
                const u_email = $('#u_email').val();
                const role_id = $('#role_id').val();
                const u_password = $('#u_password').val();
                const password_confirmation = $('#password_confirmation').val();
                const f_name = $('#f_name').val();
                const m_name = $('#m_name').val();
                const l_name = $('#l_name').val();
                const number = $('#number').val();
                const designation = $('#designation').val();
                const address = $('#address').val();
                const state = $('#state').val();
                const city = $('#city').val();
                // const user_profile = $('#user_profile').val();
                const pincode = $('#pincode').val();

                // Enable the submit button if all fields are valid
                if (u_email && role_id && u_password && password_confirmation && f_name && m_name && l_name &&
                    number && designation && address && state && city && pincode) {
                    $('#submitButton').prop('disabled', false);
                } else {
                    $('#submitButton').prop('disabled', true);
                }
            }

            // Call the checkFormValidity function on input change
            // $('input,textarea, select, #user_profile').on('input change',
            // checkFormValidity);

            $.validator.addMethod("number", function(value, element) {
                return this.optional(element) || /^[0-9]{10}$/.test(value);
            }, "Please enter a valid 10-digit number.");

            $.validator.addMethod("u_email", function(value, element) {
                // Regular expression for email validation
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return this.optional(element) || emailRegex.test(value);
            }, "Please enter a valid email address.");

            // Initialize the form validation
            $("#regForm").validate({
                rules: {
                    u_email: {
                        required: true,
                        //     remote: {
                        //     url: '/web/check-email-exists',
                        //     type: 'post',
                        //     data: {
                        //         u_email: function() {
                        //             return $('#u_email').val();
                        //         }
                        //     }
                        // },
                        u_email: true,
                    },
                    role_id: {
                        required: true,
                    },
                    u_password: {
                        required: true,
                    },
                    password_confirmation: {
                        required: true,
                    },
                    f_name: {
                        required: true,
                    },
                    m_name: {
                        required: true,
                    },
                    l_name: {
                        required: true,
                    },
                    number: {
                        required: true,
                        number: true,
                    },
                    designation: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    // user_profile: {
                    //     required: true,
                    // },
                    pincode: {
                        required: true,
                    },

                },
                messages: {
                    u_email: {
                        required: "Please Enter the Eamil",
                        // remote: "This Email already exists."
                    },
                    role_id: {
                        required: "Please Select Role Name",
                    },
                    u_password: {
                        required: "Please Enter the Password",
                    },
                    password_confirmation: {
                        required: "Please Enter the Confirmation Password",
                    },
                    f_name: {
                        required: "Please Enter the First Name",
                    },
                    m_name: {
                        required: "Please Enter the Middle Name",
                    },
                    l_name: {
                        required: "Please Enter the Last Name",
                    },
                    number: {
                        required: "Please Enter the Number",
                    },
                    designation: {
                        required: "Please Enter the Designation",
                    },
                    address: {
                        required: "Please Enter the Address",
                    },

                    state: {
                        required: "Please Select State",
                    },
                    city: {
                        required: "Please Select City",
                    },
                    // user_profile: {
                    //     required: "Upload Media File",
                    //     accept: "Only png, jpeg, and jpg image files are allowed.", // Update the error message for the accept rule
                    // },
                    pincode: {
                        required: "Please Enter the Pincode",
                    },
                },

            });
        });
    </script>
    <script>
        function addvalidatePincode(pincode) {
            // Regular expression for exactly 6 digits and numbers only
            var pincodePattern = /^\d{6}$/;
            var validationMessage = document.getElementById("validation-message-pincode");

            if (pincodePattern.test(pincode)) {
                validationMessage.textContent = ""; // Clear error message
            } else {
                validationMessage.textContent = "Please enter a valid 6-digit number."; // Show error
            }
        }
    </script>
    @endpush
@endsection
