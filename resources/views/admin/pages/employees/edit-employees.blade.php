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
                        @if(session('msg'))
                            <div class="alert alert-{{ session('status') }}">
                                {{ session('msg') }}
                            </div>
                        @endif

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if (Session::get('status') == 'success')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-success " id="success-alert">
                                        <button type="button"  data-bs-dismiss="alert"></button>
                                        <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                </div>
                            @endif

                            @if (Session::get('status') == 'error')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-danger " id="error-alert">
                                        <button type="button"  data-bs-dismiss="alert"></button>
                                        <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                    </div>
                                </div>
                            @endif

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
                                <form action="{{ route('update-employees') }}" method="POST" enctype="multipart/form-data" id="editEmployeeForm" autocomplete="off">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="employee_name">Employee Name:</label>
                                                <input type="text" class="form-control" value="@if (old('employee_name')) {{ old('employee_name') }}@else{{ $editData->employee_name }} @endif" id="employee_name" name="employee_name" placeholder="Enter Employees name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="company_name">Select Company:</label>
                                                <select class="form-control" id="organization_id" name="organization_id">
                                                    @foreach($data as $datas)
                                                        <option value="{{$datas->id}}" @if (old('company_name') == $datas->id || $editData->company_id == $datas->id) selected @endif>{{ ucfirst($datas->company_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="mobile_number">Mobile Number:</label>
                                                <input type="text" class="form-control" id="mobile_number" value="@if (old('mobile_number')) {{ old('mobile_number') }}@else{{ $editData->mobile_number }} @endif" name="mobile_number" placeholder="Enter mobile number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="email">Email:</label>
                                                <input type="email" class="form-control" id="email" value="@if (old('email')) {{ old('email') }}@else{{ $editData->email }} @endif" name="email" placeholder="Enter email">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="address">Employee Address:</label>
                                                <input type="text" class="form-control" id="address" value="@if (old('address')) {{ old('address') }}@else{{ $editData->address }} @endif" name="address" placeholder="Enter company address">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="image">Image:</label>
                                                <input type="file" class="form-control" id="image" name="image" value="@if (old('image')) {{ old('image') }}@else{{ $editData->image }} @endif">
                                                @if (old('image') || isset($editData))
                                                    <div>
                                                        <label>Old Image: </label>
                                                        <img src="@if (old('image')) {{ old('emp_image') }} @elseif(isset($editData->emp_image)) {{ Config::get('DocumentConstant.EMPLOYEES_VIEW') . $editData->emp_image }} @endif" alt="Old Image" style="max-width: 100px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                       <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="sparkline12-graph">
                                                <div id="pwd-container1">
                                                    <div class="form-group">
                                                        <label for="password1">Password</label>
                                                        <input type="password" class="form-control example1" required name="password" id="password1" placeholder="Password" value="Passwo">
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="pwstrength_viewport_progress"></span></div>
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
                                                    <a href="{{ route('list-employees') }}">
                                                        <button class="btn btn-white" style="margin-bottom:50px">Cancel</button>
                                                    </a>
                                                    <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
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
  <script src="{{asset('js/vendor/jquery-1.11.3.min.js')}}"></script>
    <script src="{{asset('js/password-meter/pwstrength-bootstrap.min.js')}}"></script>
    <script src="{{asset('js/password-meter/zxcvbn.js')}}"></script>
    <script src="{{asset('js/password-meter/password-meter-active.js')}}"></script>
  <!-- Include jQuery -->


@endsection
