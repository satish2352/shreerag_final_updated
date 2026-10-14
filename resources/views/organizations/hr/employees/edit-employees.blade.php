@extends('admin.layouts.master')
@section('content')
@php $isHR = session('role_name') === 'HR'; @endphp

    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-md-12">
                <div class="emp-form-card">

                    {{-- Header --}}
                    <div class="emp-form-header">
                        <h4><i class="fa fa-user-edit mr-2"></i> Edit Employee Data</h4>
                        <a href="{{ route('list-employee') }}" class="emp-form-close" title="Close">&times;</a>
                    </div>

                    <div class="emp-form-body">

                        @if (session('status') == 'success')
                            <div class="alert alert-success alert-success-style1">
                                <strong>Success!</strong> {{ session('msg') }}
                            </div>
                        @endif
                        @if (session('status') == 'error')
                            <div class="alert alert-danger">
                                <strong>Error!</strong> {!! session('msg') !!}
                            </div>
                        @endif

                        <form id="regForm" name="regForm" method="post"
                            action="{{ route('update-employee') }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ Session::token() }}" />
                            <input type="hidden" name="edit_id" value="{{ $user_data['data_users']['id'] }}">

                            <div class="row">

                                {{-- Email --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="u_email" id="u_email"
                                            placeholder=" " value="{{ $user_data['data_users']['u_email'] }}">
                                        <label for="u_email">Email ID <span class="red-text">*</span></label>
                                        @if ($errors->has('u_email'))
                                            <span class="fl-error">{{ $errors->first('u_email') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Department (disabled) --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <select class="form-control has-value" id="role_id" name="role_id"
                                            onchange="myFunction(this.value)" disabled>
                                            @foreach ($user_data['roles'] as $role)
                                                <option value="{{ $role['id'] }}"
                                                    {{ $role['id'] == $user_data['data_users']['role_id'] ? 'selected' : '' }}>
                                                    {{ $role['role_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="role_id">Department <span class="red-text">*</span></label>
                                    </div>
                                </div>

                                {{-- Password fields --}}
                                @if (session('role_id') == 9)
                                    {{-- Role 9: disabled password --}}
                                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                        <div class="fl-group fl-pw-wrap">
                                            <input type="password" class="password form-control" name="u_password"
                                                id="u_password" placeholder=" " value="{{ old('u_password') }}" disabled>
                                            <label for="u_password">Change Password</label>
                                            <span class="password-toggle togglePpassword" onclick="togglePasswordVisibility()">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                        </div>
                                        <small class="text-danger">Please contact the owner to change the password.</small>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                        <div class="fl-group fl-pw-wrap">
                                            <input type="password" class="password_confirmation form-control"
                                                id="password_confirmation" name="password_confirmation"
                                                placeholder=" " value="{{ old('password_confirmation') }}" disabled>
                                            <label for="password_confirmation">Confirm Password</label>
                                            <span class="password-toggle toggleConfirmPpassword" onclick="toggleConfirmPasswordVisibility()">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                        <div class="fl-group fl-pw-wrap">
                                            <input type="password" class="password form-control" name="u_password"
                                                id="u_password" placeholder=" " value="{{ old('u_password') }}">
                                            <label for="u_password">Change Password</label>
                                            <span class="password-toggle togglePpassword" onclick="togglePasswordVisibility()">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                            @if ($errors->has('u_password'))
                                                <span class="fl-error">{{ $errors->first('u_password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                        <div class="fl-group fl-pw-wrap">
                                            <input type="password" class="password_confirmation form-control"
                                                id="password_confirmation" name="password_confirmation"
                                                placeholder=" " value="{{ old('password_confirmation') }}">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <span class="password-toggle toggleConfirmPpassword" onclick="toggleConfirmPasswordVisibility()">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                            <span id="password-error" class="fl-error"></span>
                                            @if ($errors->has('password_confirmation'))
                                                <span class="fl-error">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- First Name --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="f_name" id="f_name"
                                            placeholder=" " value="{{ $user_data['data_users']['f_name'] }}"
                                            oninput="this.value=this.value.replace(/[^a-zA-Z\s.]/g,'')">
                                        <label for="f_name">First Name <span class="red-text">*</span></label>
                                        @if ($errors->has('f_name'))
                                            <span class="fl-error">{{ $errors->first('f_name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Middle Name --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="m_name" id="m_name"
                                            placeholder=" " value="{{ $user_data['data_users']['m_name'] }}"
                                            oninput="this.value=this.value.replace(/[^a-zA-Z\s.]/g,'')">
                                        <label for="m_name">Middle Name <span class="red-text">*</span></label>
                                        @if ($errors->has('m_name'))
                                            <span class="fl-error">{{ $errors->first('m_name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Last Name --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="l_name" id="l_name"
                                            placeholder=" " value="{{ $user_data['data_users']['l_name'] }}"
                                            oninput="this.value=this.value.replace(/[^a-zA-Z\s.]/g,'')">
                                        <label for="l_name">Last Name <span class="red-text">*</span></label>
                                        @if ($errors->has('l_name'))
                                            <span class="fl-error">{{ $errors->first('l_name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Mobile --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="number" id="number"
                                            placeholder=" " maxlength="10" minlength="10"
                                            value="{{ $user_data['data_users']['number'] }}"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,''); editvalidateMobileNumber(this.value)"
                                            @if ($isHR) disabled @endif>
                                        <label for="number">Mobile Number <span class="red-text">*</span></label>
                                        <span id="edit-message" class="fl-error"></span>
                                        @if ($errors->has('number'))
                                            <span class="fl-error">{{ $errors->first('number') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Designation --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="designation" id="designation"
                                            placeholder=" " value="{{ $user_data['data_users']['designation'] }}"
                                            oninput="this.value=this.value.replace(/[^a-zA-Z\s.]/g,'')">
                                        <label for="designation">Designation <span class="red-text">*</span></label>
                                        @if ($errors->has('designation'))
                                            <span class="fl-error">{{ $errors->first('designation') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="address" id="address"
                                            placeholder=" " value="{{ $user_data['data_users']['address'] }}">
                                        <label for="address">Address <span class="red-text">*</span></label>
                                        @if ($errors->has('address'))
                                            <span class="fl-error">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- State --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <select class="form-control" id="state" name="state"
                                            onchange="this.classList.toggle('has-value', this.value !== '')">
                                            <option value=""></option>
                                        </select>
                                        <label for="state">State <span class="red-text">*</span></label>
                                        @if ($errors->has('state'))
                                            <span class="fl-error">{{ $errors->first('state') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- City --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <select class="form-control" id="city" name="city"
                                            onchange="this.classList.toggle('has-value', this.value !== '')">
                                            <option value=""></option>
                                        </select>
                                        <label for="city">City <span class="red-text">*</span></label>
                                        @if ($errors->has('city'))
                                            <span class="fl-error">{{ $errors->first('city') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Pincode --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" id="pincode" name="pincode"
                                            placeholder=" " value="{{ $user_data['data_users']['pincode'] }}"
                                            oninput="editvalidatePincode(this.value)">
                                        <label for="pincode">Pincode <span class="red-text">*</span></label>
                                        <span id="edit-message-pincode" class="fl-error"></span>
                                    </div>
                                </div>

                                {{-- Is Active --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input"
                                                name="is_active" id="is_active" value="y"
                                                @if ($user_data['data_users']['is_active']) checked @endif>
                                            Is Active
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 text-center mt-4">
                                    <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">
                                        Save &amp; Update
                                    </button>
                                    <a href="{{ route('list-employee') }}" class="btn btn-sm btn-primary ml-2">Back</a>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

@push('scripts')
<script>
    function editvalidateMobileNumber(n) {
        document.getElementById('edit-message').textContent =
            /^\d*$/.test(n) ? '' : 'Only numbers are allowed.';
    }
    function editvalidatePincode(p) {
        document.getElementById('edit-message-pincode').textContent =
            /^\d{6}$/.test(p) ? '' : 'Please enter a valid 6-digit pincode.';
    }
    function togglePasswordVisibility() {
        var inp = document.querySelector('.password.form-control');
        var ico = document.querySelector('.togglePpassword i');
        if (!inp || inp.disabled) return;
        if (inp.type === 'password') { inp.type = 'text'; ico && ico.classList.replace('fa-eye-slash','fa-eye'); }
        else { inp.type = 'password'; ico && ico.classList.replace('fa-eye','fa-eye-slash'); }
    }
    function toggleConfirmPasswordVisibility() {
        var inp = document.querySelector('.password_confirmation.form-control');
        var ico = document.querySelector('.toggleConfirmPpassword i');
        if (!inp || inp.disabled) return;
        if (inp.type === 'password') { inp.type = 'text'; ico && ico.classList.replace('fa-eye-slash','fa-eye'); }
        else { inp.type = 'password'; ico && ico.classList.replace('fa-eye','fa-eye-slash'); }
    }

    function getStateCity(stateId, city_id) {
        $('#city').html('<option value=""></option>');
        if (!stateId) return;
        $.ajax({
            url: '{{ route('cities') }}', type: 'GET', data: { stateId: stateId },
            success: function(r) {
                if (r.city && r.city.length) {
                    $.each(r.city, function(i, c) {
                        $('#city').append('<option value="'+c.location_id+'">'+c.name+'</option>');
                    });
                    if (city_id) { $('#city').val(city_id); }
                    if ($('#city').val()) { $('#city').addClass('has-value'); }
                }
            }
        });
    }
    function getState(stateId) {
        $('#state').html('<option value=""></option>');
        $.ajax({
            url: '{{ route('states') }}', type: 'GET',
            success: function(r) {
                if (r.state && r.state.length) {
                    $.each(r.state, function(i, s) {
                        $('#state').append('<option value="'+s.location_id+'">'+s.name+'</option>');
                    });
                    $('#state').val(stateId);
                    if ($('#state').val()) { $('#state').addClass('has-value'); }
                }
            }
        });
    }

    $(document).ready(function() {
        myFunction($("#role_id").val());
        getState('{{ $user_data['data_users']['state'] }}');
        getStateCity('{{ $user_data['data_users']['state'] }}', '{{ $user_data['data_users']['city'] }}');

        $('#state').on('change', function() { getStateCity($(this).val(), ''); });

        /* Password match check */
        $('#password_confirmation').on('input', function() {
            var match = $('#u_password').val() === $(this).val();
            $('#password-error').text(match ? '' : 'Password does not match.');
        });

        /* jQuery Validate */
        $("#regForm").validate({
            rules: {
                f_name: { required: true }, m_name: { required: true }, l_name: { required: true },
                number: { required: true, minlength: 10, maxlength: 10, digits: true },
                designation: { required: true }, address: { required: true },
                state: { required: true }, city: { required: true }, pincode: { required: true },
            },
            errorClass: 'fl-error',
            errorElement: 'span',
            errorPlacement: function(error, element) {
                var group = element.closest('.fl-group');
                if (group.length) { error.appendTo(group); }
                else { error.insertAfter(element); }
            },
            highlight: function(el) { $(el).css('border-bottom-color','#e53935'); },
            unhighlight: function(el) { $(el).css('border-bottom-color','#ced4da'); }
        });
    });
</script>
@endpush
@endsection
