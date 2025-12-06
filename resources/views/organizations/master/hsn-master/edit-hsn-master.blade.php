@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
    }
</style>
<div class="">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Update HSN Data</h1></center>
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
                               
                                <form action="{{ route('update-hsn') }}" method="POST" enctype="multipart/form-data" id="regForm" autocomplete="off">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="name">Name:</label>
                                                <input type="text" class="form-control" value="@if (old('name')) {{ old('name') }}@else{{ $editData->name }} @endif" id="name" name="name" placeholder="Enter part item name">
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
            // Custom validation rule to check if the input does not contain only spaces
            $.validator.addMethod("spcenotallow", function(value, element) {
                return this.optional(element) || value.trim().length > 0;
            }, "Enter some valid text");
        
            // Initialize the form validation
            $("#regForm").validate({
                rules: {
                    name: {
                        required: true,
                        spcenotallow: true,
                    },
                    // You can add more fields here as needed
                },
                messages: {
                    name: {
                        required: "Please enter the name.",
                        spcenotallow: "Name cannot contain only spaces.",
                    },
                    // Custom error messages for other fields can go here
                },
            });
        });
        </script>
        

@endsection
