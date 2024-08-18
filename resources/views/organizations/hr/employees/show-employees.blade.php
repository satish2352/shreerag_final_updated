@extends('admin.layouts.master')
@section('content')
    <div class="show-page-position">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 " style="padding-top: 88px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-start align-items-center">
                    <h3 class="page-title">
                        Employee Details
                    </h3>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 show-btn-position">
                    <a href="{{ route('list-users') }}" class="btn btn-sm btn-primary ml-3">Back</a>
                </div>
            </div>
          
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center" >
              
                <div class="" style="background-color: #fff; padding:25px;">
                           
                               
                                <div class="col-12">
                                    @include('admin.layouts.alert')
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Name :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ $user_detail->f_name }} {{ $user_detail->m_name }}
                                                {{ $user_detail->l_name }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Role :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->role_name) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Email :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->u_email) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Mobile Number :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->number) }}</label>
                                        </div>
                                    </div>


                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Designation :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->designation) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Address :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->address) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>State :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->state) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>City :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->city) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Pincode :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($user_detail->pincode) }}</label>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Profile :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <img src="{{ Config::get('DocumentConstant.USER_PROFILE_VIEW') }}{{ $user_detail->user_profile }}"
                                                style="width:100px; height:100px; border-radius:50%" />
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                </div>
        
                <!-- content-wrapper ends -->
            @endsection
        