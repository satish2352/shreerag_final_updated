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
                    <center><h1>Add Employee Data</h1></center>
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
                                <form action="{{ route('store-employees') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="company_name">Employee Name:</label>
                                            <input type="text" class="form-control" id="employee_name" name="employee_name" placeholder="Enter Employee name">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-select-list">
                                                <label for="company_id">Select Company:</label>
                                                <select class="form-control custom-select-value" name="company_id">
                                                    <ul class="dropdown-menu ">
                                                        <option value="">Select Company</option>
                                                        @foreach($data as $datas)
                                                        <option value="{{$datas->id}}">{{ucfirst($datas->company_name)}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="email">Employee Email:</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="mobile_number">Mobile Number:</label>
                                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter mobile number">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="address">Employee Address:</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter company address">
                                        </div>
                                           <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="image">Image:</label>
                                            <input type="file" class="form-control" accept="image*" id="image" name="image">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="sparkline12-graph">
                                                <div id="pwd-container1">
                                                    <div class="form-group">
                                                        <label for="password1">Password</label>
                                                        <input type="password" class="form-control example1" name="password" id="password1" placeholder="Password" value="Passwo">
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
                                                        <a href="{{ route('list-employees') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
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
  
@endsection
