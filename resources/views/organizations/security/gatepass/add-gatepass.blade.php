@extends('admin.layouts.master')
@section('content')
<style>
label {
    margin-top: 20px;
}

label.error {
    color: red;
    /* Change 'red' to your desired text color */
    font-size: 12px;
    /* Adjust font size if needed */
    /* Add any other styling as per your design */
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center>
                        <h1>Add Gatepass Data</h1>
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
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Success!</strong> {{ Session::get('msg') }}
                                </div>
                            </div>
                            @endif

                            @if (Session::get('status') == 'error')
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Error!</strong> {!! session('msg') !!}
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    <form action="" method="POST" id="addDesignsForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group-inner">

                                            {{-- ========================== --}}
                                            <div class="container-fluid">
                                                {{-- <form 
                                                action="{{ route('addmorePost') }}"
                                                method="POST"> --}}

                                                {{-- @csrf --}}

                                                @if ($errors->any())
                                                <div class="alert alert-danger">

                                                    <ul>

                                                        @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                        @endforeach

                                                    </ul>

                                                </div>
                                                @endif

                                                @if (Session::has('success'))
                                                <div class="alert alert-success text-center">

                                                    <a href="#" class="close" data-dismiss="alert"
                                                        aria-label="close">×</a>

                                                    <p>{{ Session::get('success') }}</p>

                                                </div>
                                                @endif
                                            
                                                {{-- <button type="submit" class="btn btn-success">Save</button> --}}

                                                {{-- </form> --}}

                                            </div>

                                            {{-- =================== --}}

                                            <div class="row">                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="purchase_id">PO:</label>
                                                    <input type="text" class="form-control" id="purchase_id"
                                                        name="purchase_id" placeholder="Enter PO No">
                                                </div>                                               

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="gatepass_name">Name:</label>
                                                    <input type="text" class="form-control" id="gatepass_name"
                                                        name="gatepass_name" placeholder="Enter Name">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="gatepass_date">Date:</label>
                                                    <input type="date" class="form-control" id="gatepass_date"
                                                        name="gatepass_date" placeholder="Select Date">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="gatepass_time">Time:</label>
                                                    <input type="time" class="form-control" id="gatepass_time"
                                                        name="gatepass_time" placeholder="Select Time">
                                                </div>
                                                   
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="remark">Remark:</label>
                                                    <input type="text" class="form-control" id="remark"
                                                        name="remark" placeholder="Enter Remark">
                                                </div>  

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="status">Status:</label>
                                                    <input type="text" class="form-control" id="status"
                                                        name="status" placeholder="Enter Status">
                                               </div>                                                                                                                                                                                          
                                            </div>

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-gatepass') }}" class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</a>
                                                            <button class="btn btn-sm btn-primary login-submit-cs"
                                                                type="submit" style="margin-bottom:50px">Save
                                                                Data</button>
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
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
var i = 0;



</script>
<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $("#addDesignsForm").validate({
        rules: {          
            purchase_id: {
                required: true
            },
            gatepass_name : {
                required : true
            },     
            gatepass_date : {
                required : true
            },    
            gatepass_time : {
                required : true
            },
            remark :{
                required : true,
            },           
            status :{
                required : true,
            },            
        },
        messages: {
            purchase_id: {
                required: "Please Enter Po Number.",
            },              
            gatepass_name : {
                required: "Please Enter Gatepass Name.",
            },
            gatepass_date : {
                required: "Please Select Gatepass Date.",
            },
            gatepass_time : {
                required: "Please Select Gatepass Time.",
            },
            remark : {
                required: "Please enter Remark.",
            },                      
            status : {
                required: "Please enter Status.",
            },            
        },
    });
});
</script>

@endsection