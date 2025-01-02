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
                    <center><h1>Update Gatepass Data</h1></center>
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
                            <div class="all-form-element-inner">
                              
                                <form action="{{ route('update-gatepass') }}" method="POST" enctype="multipart/form-data" id="regForm" autocomplete="off">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id">
                                        <input type="hidden" class="form-control" value="@if (old('business_details_id')) {{ old('business_details_id') }}@else{{ $editData->business_details_id }} @endif" id="business_details_id" name="business_details_id">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="purchase_orders_id">PO:</label>
                                                <input type="text" class="form-control" id="purchase_orders_id"
                                                    name="purchase_orders_id" placeholder="Enter PO No" value="{{$editData->purchase_orders_id}}" readonly>
                                            </div>                                               

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="gatepass_name">Name:</label>
                                                <input type="text" class="form-control" id="gatepass_name"
                                                    name="gatepass_name" placeholder="Enter Name"  value="{{$editData->gatepass_name}}">
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="gatepass_date">Date :</label>
                                                <input type="date" class="form-control" id="gatepass_date"
                                                    name="gatepass_date" placeholder="Select Date"  value="{{$editData->gatepass_date}}">
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="gatepass_time">Time:</label>
                                                <input type="time" class="form-control" id="gatepass_time"
                                                    name="gatepass_time" placeholder="Select Time"  value="{{$editData->gatepass_time}}">
                                            </div>
                                               
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="remark">Remark:</label>
                                                <input type="text" class="form-control" id="remark"
                                                    name="remark" placeholder="Enter Remark"  value="{{$editData->remark}}">
                                            </div>  
                                           
                                        </div>

                                      
                                    <div class="login-btn-inner">
                                        <div class="row">
                                            <div class="col-lg-5"></div>
                                            <div class="col-lg-7">
                                                <div class="login-horizental cancel-wp pull-left">
                                                    <a href="{{ route('list-hsn') }}">
                                                        <button class="btn btn-white" style="margin-bottom:50px">Cancel</button>
                                                    </a>
                                                    <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Update Data</button>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
<script>
    $(document).ready(function() {
        // Custom validation rule for spaces
        $.validator.addMethod("spcenotallow", function(value, element) {
            return this.optional(element) || value.trim().length > 0;
        }, "Enter some valid text");

        // Initialize form validation
        $("#regForm").validate({
            rules: {
                gatepass_name: {
                    required: true,
                    spcenotallow: true,
                },
                gatepass_date: {
                    required: true,
                    date: true,
                },
                gatepass_time: {
                    required: true,
                },
                remark: {
                    spcenotallow: true,
                },
            },
            messages: {
                gatepass_name: {
                    required: "Please enter the name.",
                    spcenotallow: "Name cannot contain only spaces.",
                },
                gatepass_date: {
                    required: "Please select a date.",
                    date: "Enter a valid date.",
                },
                gatepass_time: {
                    required: "Please select a time.",
                },
                remark: {
                    spcenotallow: "Remark cannot contain only spaces.",
                },
            },
            submitHandler: function(form) {
                // Confirmation dialog before submitting the form
                Swal.fire({
                    title: "Are you sure?",
                    text: "Gatepass Updated successfully?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, submit it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
        

@endsection
