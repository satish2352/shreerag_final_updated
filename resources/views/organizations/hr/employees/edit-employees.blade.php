@extends('admin.layouts.master')
@section('content')
@php
    $isHR = session('role_name') === 'HR';
@endphp

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list" style="padding-bottom: 100px">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Edit Employee Data</h1>
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
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-success " id="success-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-danger " id="error-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                        </div>
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
                                <div class="all-form-element-inner">
                                    <form class="forms-sample" id="regForm" name="frm_register" method="post"
                                        role="form" action="{{ route('update-employee') }}" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" id="csrf-token"
                                            value="{{ Session::token() }}" />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="u_email">Email ID</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <input type="text" class="form-control" name="u_email" id="u_email"
                                                        placeholder="" value="{{ $user_data['data_users']['u_email'] }}">
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
                                                        onchange="myFunction(this.value)" disabled>
                                                        <option>Select</option>
                                                        @foreach ($user_data['roles'] as $role)
                                                            <option value="{{ $role['id'] }}"
                                                                @if ($role['id'] == $user_data['data_users']['role_id']) <?php echo 'selected'; ?> @endif>
                                                                {{ $role['role_name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('role_id'))
                                                        <span class="red-text"><?php echo $errors->first('role_id', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="f_name">First Name</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <input type="text" class="form-control mb-2" name="f_name"
                                                        id="f_name" placeholder=""
                                                        value="{{ $user_data['data_users']['f_name'] }}"
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
                                                    <input type="text" class="form-control mb-2" name="m_name"
                                                        id="m_name" placeholder=""
                                                        value="{{ $user_data['data_users']['m_name'] }}"
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
                                                    <input type="text" class="form-control mb-2" name="l_name"
                                                        id="l_name" placeholder=""
                                                        value="{{ $user_data['data_users']['l_name'] }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    @if ($errors->has('l_name'))
                                                        <span class="red-text"><?php echo $errors->first('l_name', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            {{-- Mobile Number --}}

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="number">Mobile Number</label>&nbsp;<span class="red-text">*</span>
                                                    <input type="text" class="form-control mb-2" name="number" id="number"
                                                        value="{{ $user_data['data_users']['number'] }}"
                                                        onkeyup="editvalidateMobileNumber(this.value)"
                                                        pattern="[789]{1}[0-9]{9}"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        maxlength="10" minlength="10"
                                                        @if(session('role_name') == 'HR') disabled @endif>
                                                    <span id="edit-message" class="red-text"></span>
                                                    @if ($errors->has('number'))
                                                        <span class="red-text">{{ $errors->first('number') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                                @if (session('role_id') == 9)
                                                {{-- Change Password --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="u_password">Change Password</label>                                                    
                                                        <input type="password" class="password form-control mb-2" name="u_password" id="u_password"
                                                            value="{{ old('u_password') }}" disabled>
                                                            <span class="text-danger">Please contact the owner to change the password.</span>
                                                        @if ($errors->has('u_password'))
                                                            <span class="red-text">{{ $errors->first('u_password') }}</span>
                                                        @endif
                                                        <span id="togglePassword" class="togglePpassword password-toggle-disable" onclick="togglePasswordVisibility()">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Confirm Password --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="password_confirmation">Confirm Password</label>
                                                        <input type="password" class="password_confirmation form-control mb-2" id="password_confirmation"
                                                            name="password_confirmation"
                                                            value="{{ old('password_confirmation') }}" disabled>
                                                        <span id="password-error" class="error-message red-text"></span>
                                                        @if ($errors->has('password_confirmation'))
                                                            <span class="red-text">{{ $errors->first('password_confirmation') }}</span>
                                                        @endif
                                                        <span id="toggleConfirmPassword" class="toggleConfirmPpassword password-toggle-disable"
                                                            onclick="toggleConfirmPasswordVisibility()">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @else
                                                       {{-- Change Password --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="u_password">Change Password</label>
                                                        <input type="password" class="password form-control mb-2" name="u_password" id="u_password"
                                                            value="{{ old('u_password') }}">
                                                        @if ($errors->has('u_password'))
                                                            <span class="red-text">{{ $errors->first('u_password') }}</span>
                                                        @endif
                                                        <span id="togglePassword" class="togglePpassword password-toggle" onclick="togglePasswordVisibility()">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Confirm Password --}}
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="password_confirmation">Confirm Password</label>
                                                        <input type="password" class="password_confirmation form-control mb-2" id="password_confirmation"
                                                            name="password_confirmation"
                                                            value="{{ old('password_confirmation') }}">
                                                        <span id="password-error" class="error-message red-text"></span>
                                                        @if ($errors->has('password_confirmation'))
                                                            <span class="red-text">{{ $errors->first('password_confirmation') }}</span>
                                                        @endif
                                                        <span id="toggleConfirmPassword" class="toggleConfirmPpassword password-toggle"
                                                            onclick="toggleConfirmPasswordVisibility()">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @endif


                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="designation">Designation</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <input type="text" class="form-control mb-2" name="designation"
                                                        id="designation" placeholder=""
                                                        value="{{ $user_data['data_users']['designation'] }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    @if ($errors->has('designation'))
                                                        <span class="red-text"><?php echo $errors->first('designation', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="address">Address</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <input type="text" class="form-control mb-2" name="address"
                                                        id="address" placeholder=""
                                                        value="{{ $user_data['data_users']['address'] }}">
                                                    @if ($errors->has('address'))
                                                        <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="state">State</label>&nbsp;<span
                                                        class="red-text">*</span>
                                                    <select class="form-control mb-2" name="state" id="state">
                                                        <option value="">Select State</option>
                                                    </select>
                                                    @if ($errors->has('state'))
                                                        <span class="red-text"><?php echo $errors->first('state', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="city">City</label>&nbsp;<span
                                                        class="red-text">*</span>
                                                    <select class="form-control mb-2" name="city" id="city">
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
                                                    <input type="text" class="form-control mb-2" name="pincode"
                                                        id="pincode" placeholder=""
                                                        value="{{ $user_data['data_users']['pincode'] }}"
                                                        onkeyup="editvalidatePincode(this.value)">
                                                    <span id="edit-message-pincode" class="red-text"></span>
                                                    @if ($errors->has('pincode'))
                                                        <span class="red-text"><?php //echo $errors->first('pincode', ':message');
                                                        ?></span>
                                                    @endif
                                                </div>
                                            </div>



                                            <br>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group form-check form-check-flat form-check-primary">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" name="is_active"
                                                            id="is_active" value="y"
                                                            data-parsley-multiple="is_active"
                                                            @if ($user_data['data_users']['is_active']) <?php echo 'checked'; ?> @endif>
                                                        Is Active
                                                        <i class="input-helper"></i><i class="input-helper"></i></label>
                                                </div>
                                            </div>

                                            {{-- <div class="col-lg-12 col-md-12 col-sm-12 user_tbl">
                                        <div id="data_for_role">
                                        </div>
                                    </div> --}}

                                            <div class="col-md-12 col-sm-12 text-center mt-3">
                                                <input type="hidden" class="form-check-input" name="edit_id"
                                                    id="edit_id" value="{{ $user_data['data_users']['id'] }}">
                                                <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">
                                                    Save &amp; Update
                                                </button>
                                                {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
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
    @push('scripts')  
    <script>
        function getStateCity(stateId, city_id) {
            $('#city').html('<option value="">Select City</option>');
            if (stateId !== '') {
                $.ajax({
                    url: '{{ route('cities') }}',
                    type: 'GET',
                    data: {
                        stateId: stateId
                    },
                    success: function(response) {
                        if (response.city.length > 0) {
                            $.each(response.city, function(index, city) {
                                $('#city').append('<option value="' + city.location_id + '">' + city
                                    .name + '</option>');
                            });
                            if (city_id != null) {
                                $('#city').val(city_id);
                            }
                        }
                    }
                });
            }
        }

        function getState(stateId) {
            $('#state').html('<option value="">Select State</option>');
            $.ajax({
                url: '{{ route('states') }}',
                type: 'GET',
                success: function(response) {
                    if (response.state.length > 0) {
                        $.each(response.state, function(index, state) {
                            $('#state').append('<option value="' + state.location_id + '">' + state
                                .name + '</option>');
                        });
                        $('#state').val(stateId);
                    }
                }
            });
        }

        $(document).ready(function() {
            getState('{{ $user_data['data_users']['state'] }}');
            getStateCity('{{ $user_data['data_users']['state'] }}', '{{ $user_data['data_users']['city'] }}');

            $("#state").on('change', function() {
                getStateCity($(this).val(), '');
            });
        });
    </script>

    <script type="text/javascript">
        function submitRegister() {
            document.getElementById("frm_register").submit();
        }
    </script>
    <script>
        function editvalidateMobileNumber(number) {
            var mobileNumberPattern = /^\d*$/;
            var validationMessage = document.getElementById("edit-message");

            if (mobileNumberPattern.test(number)) {
                validationMessage.textContent = "";
            } else {
                validationMessage.textContent = "Only numbers are allowed.";
            }
        }
    </script>
    <script>
        function editvalidatePincode(number) {
            var pincodePattern = /^\d*$/;
            var validationMessage = document.getElementById("edit-message-pincode");

            if (pincodePattern.test(number)) {
                validationMessage.textContent = "";
            } else {
                validationMessage.textContent = "Only numbers are allowed.";
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            myFunction($("#role_id").val());
            getStateCity('{{ $user_data['data_users']['state'] }}', '{{ $user_data['data_users']['city'] }}');
            getState('{{ $user_data['data_users']['state'] }}');

            $("#state").on('change', function() {
                getStateCity($("#state").val(), '');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $.validator.addMethod('mypassword', function(value, element) {
                    return this.optional(element) || (value.match(/[a-z]/) && value.match(/[A-Z]/) && value
                        .match(/[0-9]/));
                },
                'Password must contain at least one uppercase, lowercase and numeric');

            $("#frm_register1").validate({
                rules: {

                    u_password: {
                        //required: true,
                        minlength: 6,
                        mypassword: true

                    },
                    password_confirmation: {
                        //required: true,
                        equalTo: "#u_password"
                    },
                },
                messages: {
                    u_password: {
                        required: "Please enter your new password",
                        minlength: "Password should be minimum 8 characters"
                    },
                    password_confirmation: {
                        required: "Please Enter Password Same as New Password for Confirmation",
                        equalTo: "Password does not Match! Please check the Password"
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to check if all input fields are filled with valid data
            function checkFormValidity() {
                // const role_id = $('#role_id').val();
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

            }

            // Call the checkFormValidity function on file input change
            $('input, #english_image, #marathi_image').on('change', function() {
                checkFormValidity();
                validator.element(this); // Revalidate the file input
            });
            // Initialize the form validation
            var form = $("#regForm");
            var validator = form.validate({
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
                    number: {
                        required: true,
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
                        required: "Please Select State",
                    },
                    // user_profile: {
                    //     required: "Upload Media File",
                    //     accept: "Only png, jpeg, and jpg image files are allowed.", // Update the error message for the accept rule
                    // },
                    pincode: {
                        required: "Please Enter the Pincode",
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            // Submit the form when the "Update" button is clicked
            $("#submitButton").click(function() {
                // Validate the form
                if (form.valid()) {
                    form.submit();
                }
            });
        });
    </script>
<script>
    $(document).ready(function() {
        $('.password_confirmation').on('input', function() {
            var password = $('.u_password').val();
            var confirmPassword = $(this).val();
            var errorSpan = $('.password-error');

            if (password !== confirmPassword) {
                errorSpan.text('Password does not match.');
            } else {
                errorSpan.text('');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#password_confirmation').on('input', function() {
            var password = $('#u_password').val();
            var confirmPassword = $(this).val();
            var errorSpan = $('#password-error');

            if (password !== confirmPassword) {
                errorSpan.text('Password does not match.');
            } else {
                errorSpan.text('');
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
@endpush
@endsection
