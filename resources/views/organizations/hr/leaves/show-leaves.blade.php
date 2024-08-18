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
                    <a href="{{ route('list-leaves-acceptedby-hr') }}" class="btn btn-sm btn-primary ml-3">Back</a>
                </div>
            </div>
          
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center" >
              
                <div class="" style="background-color: #fff; padding:25px;">
                           
                               
                                <div class="col-12">
                                    @include('admin.layouts.alert')
                                    <div class="row ">
                                        {{-- <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Name :</label>
                                        </div> --}}
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <h4>{{ $user_detail->f_name }} {{ $user_detail->m_name }}
                                                {{ $user_detail->l_name }}</h4>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        {{-- <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Email :</label>
                                        </div> --}}
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label>{{ strip_tags($user_detail->u_email) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label>From Date :</label>
                                            <label>{{ strip_tags($user_detail->leave_start_date) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label>To Date :</label>
                                            <label>{{ strip_tags($user_detail->leave_end_date) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label>Leave Day :</label>
                                            <label>{{ strip_tags($user_detail->leave_day) }}</label>
                                        </div>
                                    </div>


                                    <div class="row ">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label>Leave Count :</label>
                                            <label>{{ strip_tags($user_detail->leave_count) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label>Leave Type Name :</label>
                                            <label>{{ strip_tags($user_detail->leave_type_name) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label>Reason :</label>
                                            <label>{{ strip_tags($user_detail->reason) }}</label>
                                        </div>
                                    </div>
                                    <a ><button data-toggle="tooltip" onclick="printInvoice()"
                                        title="Leave Request" class="pd-setting-ed" style="margin-top: 20px;">Print</button></a>
                                </div>
                            
                        </div>
                    </div>
                </div>
                <script>
                    function printInvoice() {
                        window.print();
                    }
                </script>
                <!-- content-wrapper ends -->
            @endsection
        