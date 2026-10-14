@extends('admin.layouts.master')
@section('content')


    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-md-12">
                <div class="emp-form-card">

                    {{-- Header --}}
                    <div class="emp-form-header">
                        <h4><i class="fa fa-user-plus mr-2"></i> Add Employee Data</h4>
                        <a href="{{ route('list-employee') }}" class="emp-form-close" title="Close">&times;</a>
                    </div>

                    <div class="emp-form-body">
                        <form id="regForm" name="regForm" method="post"
                            action="{{ route('add-employee') }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ Session::token() }}" />

                            <div class="row">

                                {{-- Email --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="u_email" id="u_email"
                                            placeholder=" " value="{{ old('u_email') }}">
                                        <label for="u_email">Email ID <span class="red-text">*</span></label>
                                        @if ($errors->has('u_email'))
                                            <span class="fl-error">{{ $errors->first('u_email') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Department --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <select class="form-control {{ old('role_id') ? 'has-value' : '' }}"
                                            id="role_id" name="role_id"
                                            onchange="myFunction(this.value); this.classList.toggle('has-value', this.value !== '')">
                                            <option value=""></option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role['id'] }}" {{ old('role_id') == $role['id'] ? 'selected' : '' }}>
                                                    {{ $role['role_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="role_id">Department <span class="red-text">*</span></label>
                                        @if ($errors->has('role_id'))
                                            <span class="fl-error">{{ $errors->first('role_id') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group fl-pw-wrap">
                                        <input type="password" class="password form-control" name="u_password"
                                            id="u_password" placeholder=" " value="{{ old('u_password') }}">
                                        <label for="u_password">Password <span class="red-text">*</span></label>
                                        <span class="password-toggle" onclick="togglePasswordVisibility()">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                        @if ($errors->has('u_password'))
                                            <span class="fl-error">{{ $errors->first('u_password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Confirm Password --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group fl-pw-wrap">
                                        <input type="password" class="password_confirmation form-control"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder=" " value="{{ old('password_confirmation') }}">
                                        <label for="password_confirmation">Confirm Password <span class="red-text">*</span></label>
                                        <span class="password-toggle toggleConfirmPpassword" onclick="toggleConfirmPasswordVisibility()">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="fl-error">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- First Name --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="f_name" id="f_name"
                                            placeholder=" " value="{{ old('f_name') }}"
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
                                            placeholder=" " value="{{ old('m_name') }}"
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
                                            placeholder=" " value="{{ old('l_name') }}"
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
                                            value="{{ old('number') }}"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,''); addvalidateMobileNumber(this.value)">
                                        <label for="number">Mobile Number <span class="red-text">*</span></label>
                                        <span id="validation-message" class="fl-error"></span>
                                        @if ($errors->has('number'))
                                            <span class="fl-error">{{ $errors->first('number') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Designation --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" name="designation" id="designation"
                                            placeholder=" " value="{{ old('designation') }}"
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
                                            placeholder=" " value="{{ old('address') }}">
                                        <label for="address">Address <span class="red-text">*</span></label>
                                        @if ($errors->has('address'))
                                            <span class="fl-error">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- State --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <select class="form-control {{ old('state') ? 'has-value' : '' }}"
                                            id="state" name="state"
                                            onchange="this.classList.toggle('has-value', this.value !== '')">
                                            <option value=""></option>
                                            @foreach ($dynamic_state as $state)
                                                <option value="{{ $state['location_id'] }}" {{ old('state') == $state['location_id'] ? 'selected' : '' }}>
                                                    {{ $state['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="state">State <span class="red-text">*</span></label>
                                    </div>
                                </div>

                                {{-- City --}}
                                <div class="col-lg-6 col-md-6 col-sm-6 fl-col">
                                    <div class="fl-group">
                                        <select class="form-control" name="city" id="city"
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
                                            placeholder=" " value="{{ old('pincode') }}"
                                            oninput="addvalidatePincode(this.value)">
                                        <label for="pincode">Pincode <span class="red-text">*</span></label>
                                        <span id="validation-message-pincode" class="fl-error"></span>
                                        @if ($errors->has('pincode'))
                                            <span class="fl-error">{{ $errors->first('pincode') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 user_tbl">
                                    <div id="data_for_role"></div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input"
                                                name="is_active" id="is_active" value="y"
                                                {{ old('is_active') ? 'checked' : '' }}>
                                            Is Active
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 text-center mt-4">
                                    <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">
                                        Save &amp; Submit
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
    function addvalidateMobileNumber(n) {
        document.getElementById('validation-message').textContent =
            /^\d*$/.test(n) ? '' : 'Please enter only numbers.';
    }
    function addvalidatePincode(p) {
        document.getElementById('validation-message-pincode').textContent =
            /^\d{6}$/.test(p) ? '' : 'Please enter a valid 6-digit number.';
    }
    function togglePasswordVisibility() {
        var inp = document.querySelector('.password.form-control');
        var ico = document.querySelector('[onclick="togglePasswordVisibility()"] i');
        if (inp.type === 'password') { inp.type = 'text'; ico && ico.classList.replace('fa-eye-slash','fa-eye'); }
        else { inp.type = 'password'; ico && ico.classList.replace('fa-eye','fa-eye-slash'); }
    }
    function toggleConfirmPasswordVisibility() {
        var inp = document.querySelector('.password_confirmation.form-control');
        var ico = document.querySelector('.toggleConfirmPpassword i');
        if (inp.type === 'password') { inp.type = 'text'; ico && ico.classList.replace('fa-eye-slash','fa-eye'); }
        else { inp.type = 'password'; ico && ico.classList.replace('fa-eye','fa-eye-slash'); }
    }

    $(document).ready(function() {
        $('#state').change(function() {
            var stateId = $(this).val();
            $('#city').html('<option value=""></option>');
            if (!stateId) return;
            $.ajax({
                url: '{{ route('cities') }}', type: 'GET', data: { stateId: stateId },
                success: function(r) {
                    if (r.city && r.city.length) {
                        $.each(r.city, function(i, c) {
                            $('#city').append('<option value="'+c.location_id+'">'+c.name+'</option>');
                        });
                    }
                }
            });
        });

        $("#regForm").validate({
            rules: {
                u_email: { required: true, email: true },
                role_id: { required: true },
                u_password: { required: true },
                password_confirmation: { required: true },
                f_name: { required: true }, m_name: { required: true }, l_name: { required: true },
                number: { required: true, minlength: 10, maxlength: 10, digits: true },
                designation: { required: true }, address: { required: true },
                state: { required: true }, city: { required: true }, pincode: { required: true },
            },
            errorClass: 'fl-error',
            errorElement: 'span',
            errorPlacement: function(error, element) {
                /* append error inside the fl-group, after the input */
                var group = element.closest('.fl-group');
                if (group.length) { error.appendTo(group); }
                else { error.insertAfter(element); }
            },
            highlight: function(element) {
                $(element).css('border-bottom-color', '#e53935');
            },
            unhighlight: function(element) {
                $(element).css('border-bottom-color', '#ced4da');
            }
        });
    });
</script>
@endpush
@endsection
