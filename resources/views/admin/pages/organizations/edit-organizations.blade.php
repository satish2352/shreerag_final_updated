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
                                <form action="{{ route('update-organizations') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group-inner">
                                                <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id" >
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="company_name">Company Name:</label>
                                                <input type="text" class="form-control" value="@if (old('company_name')) {{ old('company_name') }}@else{{ $editData->company_name }} @endif" id="company_name" name="company_name" placeholder="Enter company name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="email">Email:</label>
                                                <input type="email" class="form-control" id="email" value="@if (old('email')) {{ old('email') }}@else{{ $editData->email }} @endif" name="email" placeholder="Enter email">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="mobile_number">Mobile Number:</label>
                                                <input type="text" class="form-control" id="mobile_number" value="@if (old('mobile_number')) {{ old('mobile_number') }}@else{{ $editData->mobile_number }} @endif" name="mobile_number" placeholder="Enter mobile number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="address">Company Address:</label>
                                                <input type="text" class="form-control" id="address" value="@if (old('address')) {{ old('address') }}@else{{ $editData->address }} @endif" name="address" placeholder="Enter company address">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="employee_count">Employee Count:</label>
                                                <input type="text" class="form-control" id="employee_count" value="@if (old('employee_count')) {{ old('employee_count') }}@else{{ $editData->employee_count }} @endif" name="employee_count" placeholder="Enter employee count">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="founding_date">Foundation Date:</label>
                                                <input type="text" class="form-control flatpickr-input" id="founding_date" value="@if (old('founding_date')) {{ old('founding_date') }}@else{{ $editData->founding_date }} @endif" name="founding_date" placeholder="Enter foundation date">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="facebook_link">Facebook Link:</label>
                                                <input type="text" class="form-control" id="facebook_link" value="@if (old('facebook_link')) {{ old('facebook_link') }}@else{{ $editData->facebook_link }} @endif" name="facebook_link" placeholder="Enter Facebook link">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="instagram_link">Instagram Link:</label>
                                                <input type="text" class="form-control" id="instagram_link" value="@if (old('instagram_link')) {{ old('instagram_link') }}@else{{ $editData->instagram_link }} @endif" name="instagram_link" placeholder="Enter Instagram link">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="twitter_link">Twitter Link:</label>
                                                <input type="text" class="form-control" id="twitter_link" value="@if (old('twitter_link')) {{ old('twitter_link') }}@else{{ $editData->twitter_link }} @endif" name="twitter_link" placeholder="Enter Twitter link">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="website_link">Website Link:</label>
                                                <input type="text" class="form-control" id="website_link" value="@if (old('website')) {{ old('website') }}@else{{ $editData->website }} @endif" name="website_link" placeholder="Enter website link">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="image">Image:</label>
                                                <input type="file" class="form-control" id="image" name="image" value="@if (old('image')) {{ old('image') }}@else{{ $editData->image }} @endif">
                                                @if (old('image') || isset($editData))
                                                    <div>
                                                        <label>Old Image:  </label>
                                                        <img src="@if (old('image')) {{ old('image') }} @elseif(isset($editData)) {{ Config::get('DocumentConstant.ORGANIZATION_VIEW') . $editData->image }} @endif" alt="Old Image" style="max-width: 100px;">
                                                    </div>
                                                @endif
                                            </div>
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
                                        </div>


                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-organizations') }}"><button class="btn btn-white" style="margin-bottom:50px">Cancel</button></a>
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
<script>
    flatpickr(".flatpickr-input", {
        dateFormat: "d/m/Y",
        allowInput: true
    });
</script>
@endsection
