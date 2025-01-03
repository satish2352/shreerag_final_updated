@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 10px;
    }
    label.error {
        color: red; /* Change 'red' to your desired text color */
        font-size: 12px; /* Adjust font size if needed */
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

        .fa-eye-slash {
            /* display: none; */
        }
</style>
<div class="container-fluid">
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list" style="padding-bottom: 100px">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Employee Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
     
{{-- 
                    @if (Session::get('status') == 'error')
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error!</strong> {!! session('msg') !!}
                            </div>
                        </div>
                    @endif --}}
{{-- 
                    @if ($errors->any())
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error!</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif --}}

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">


                                    <form class="forms-sample" id="regForm" name="regForm" method="post" role="form"
                                    action="{{ route('add-users') }}" enctype="multipart/form-data">
                                    <div class="row">
                                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
    
                                        {{--        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="u_uname">User Name</label>&nbsp<span class="red-text">*</span>
                                                <input type="text" class="form-control" name="u_uname" id="u_uname"
                                                    placeholder="" value="{{ old('u_uname') }}">
                                                @if ($errors->has('u_uname'))
                                                    <span class="red-text"><?php echo $errors->first('u_uname', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>
                                        --}}
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="u_email">Email ID</label>&nbsp<span class="red-text">*</span>
                                                <input type="text" class="form-control" name="u_email" id="u_email"
                                                    placeholder="" value="{{ old('u_email') }}">
                                                @if ($errors->has('u_email'))
                                                    <span class="red-text"><?php echo $errors->first('u_email', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="role_id">Department</label>&nbsp<span class="red-text">*</span>
                                                <select class="form-control" id="role_id" name="role_id"
                                                    onchange="myFunction(this.value)">
                                                    <option value="">Select Department</option>
                                                    @foreach ($roles as $role)
                                                        @if (old('role_id') == $role['id'])
                                                            <option value="{{ $role['id'] }}" selected>
                                                                {{ $role['role_name'] }}</option>
                                                        @else
                                                            <option value="{{ $role['id'] }}">{{ $role['role_name'] }}
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
                                        <div class="col-lg-6 col-md-6 col-sm-6">
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
                                        </div>
                                        {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="company_id">Select Department:</label>
                                                <select class="form-control custom-select-value" name="department_id">
                                                    <ul class="dropdown-menu ">
                                                        <option value="">Select Company</option>
                                                        @foreach($dept as $datas)
                                                        <option value="{{$datas->id}}">{{ucfirst($datas->department_name)}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="f_name">First Name</label>&nbsp<span class="red-text">*</span>
                                                <input type="text" class="form-control" name="f_name" id="f_name"
                                                    placeholder="" value="{{ old('f_name') }}"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                @if ($errors->has('f_name'))
                                                    <span class="red-text"><?php echo $errors->first('f_name', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>
    
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="m_name">Middle Name</label>&nbsp<span class="red-text">*</span>
                                                <input type="text" class="form-control" name="m_name" id="m_name"
                                                    placeholder="" value="{{ old('m_name') }}"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                @if ($errors->has('m_name'))
                                                    <span class="red-text"><?php echo $errors->first('m_name', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>
    
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="l_name">Last Name</label>&nbsp<span class="red-text">*</span>
                                                <input type="text" class="form-control" name="l_name" id="l_name"
                                                    placeholder="" value="{{ old('l_name') }}"
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
                                                <input type="text" class="form-control" name="number" id="number"
                                                    pattern="[789]{1}[0-9]{9}"
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
                                                    id="designation" placeholder="" value="{{ old('designation') }}"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                                @if ($errors->has('number'))
                                                    <span class="red-text"><?php echo $errors->first('designation', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="address">Address</label>&nbsp<span class="red-text">*</span>
                                                <input type="text" class="form-control" name="address" id="address"
                                                    placeholder="" value="{{ old('address') }}">
                                                @if ($errors->has('address'))
                                                    <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
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
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="city">City</label>&nbsp<span class="red-text">*</span>
                                                <select class="form-control" name="city" id="city">
                                                    <option value="">Select City</option>
                                                </select>
                                                @if ($errors->has('city'))
                                                    <span class="red-text"><?php echo $errors->first('city', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="user_profile">Profile Photo</label>&nbsp<span
                                                    class="red-text">*</span><br>
                                                <input type="file" name="user_profile" id="user_profile" accept="image/*"
                                                    value="{{ old('user_profile') }}"><br>
                                                @if ($errors->has('user_profile'))
                                                    <span class="red-text"><?php //echo $errors->first('user_profile', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="pincode">Pincode</label>&nbsp<span class="red-text">*</span>
                                                {{-- <input type="text" class="form-control" name="pincode" id="pincode"
                                                    placeholder="" value="{{ old('pincode') }}"
                                                    onkeyup="addvalidatePincode(this.value)"> --}}
                                                    <input type="text" class="form-control" id="pincode" name="pincode" oninput="addvalidatePincode(this.value)">
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
                                                    <input type="checkbox" class="form-check-input" name="is_active"
                                                        id="is_active" value="y" data-parsley-multiple="is_active"
                                                        {{ old('is_active') ? 'checked' : '' }}>
                                                    Is Active
                                                    <i class="input-helper"></i><i class="input-helper"></i></label>
                                            </div>
                                        </div>
    
                                        <div class="col-md-12 col-sm-12 text-center">
                                            <button type="submit" class="btn btn-sm btn-success" id="submitButton" >
                                                Save &amp; Submit
                                            </button>
                                            {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                            <span><a href="{{ route('list-users') }}"
                                                    class="btn btn-sm btn-primary ">Back</a></span>
                                        </div>
                                    </div>
                                </form>

                                    {{-- <form action="{{ route('hr-store-employees') }}" method="POST" id="addEmployeeForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="employee_name">Employee Name:</label>
                                                    <input type="text" class="form-control" id="employee_name" name="employee_name" placeholder="Enter Employee name">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="email">Employee Email:</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="mobile_number">Mobile Number:</label>
                                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter mobile number">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="address">Employee Address:</label>
                                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter company address">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-select-list">
                                                        <label for="department_id">Select Department:</label>
                                                        <select class="form-control custom-select-value" name="department_id">
                                                            <option value="">Select Department</option>
                                                            @foreach($dept as $datas)
                                                                <option value="{{$datas->id}}">{{ucfirst($datas->department_name)}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-select-list">
                                                        <label for="role_id">Select Role:</label>
                                                        <select class="form-control custom-select-value" name="role_id">
                                                            <option value="">Select Role</option>
                                                            @foreach($roles as $datas)
                                                                <option value="{{$datas->id}}">{{ucfirst($datas->role_name)}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="aadhar_number">Aadhar Number:</label>
                                                    <input type="text" class="form-control" id="aadhar_number" name="aadhar_number" placeholder="Enter Aadhar number">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="pancard_number">Pancard Number:</label>
                                                    <input type="text" class="form-control" id="pancard_number" name="pancard_number" placeholder="Enter Pancard number">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="total_experience">Total Experience:<span><i>(in year)</i></span></label>
                                                    <input type="text" class="form-control" id="total_experience" name="total_experience" placeholder="Enter total experience">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="highest_qualification">Highest Qualification:</label>
                                                    <input type="text" class="form-control" id="highest_qualification" name="highest_qualification" placeholder="Enter highest qualification">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="gender">Gender:</label>
                                                    <select class="form-control custom-select-value" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="joining_date">Joining Date:</label>
                                                    <input type="date" class="form-control" id="joining_date" name="joining_date" placeholder="Enter foundation date">
                                                </div>
                                            </div>

                                            <div class="row">
                                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="image">Image:</label>
                                                    <input type="file" class="form-control" accept="image/*" id="image" name="image">
                                                </div>
                                            </div>

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('hr-list-employees') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                            <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form> --}}
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
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
{{-- <script>
    function myFunction(role_id) {
        // alert(role_id);
        $("#data_for_role").empty();
        $.ajax({
            url: "{{ route('list-role-wise-permission') }}",
            method: "POST",
            data: {
                "role_id": role_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $("#data_for_role").empty();
                $("#data_for_role").append(data);
            },
            error: function(data) {}
        });
    }
</script> --}}
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
                number && designation && address && state && city &&  pincode) {
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
                    u_email:true,
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
                    number:true,
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



@endsection