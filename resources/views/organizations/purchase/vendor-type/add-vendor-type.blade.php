@extends('admin.layouts.master')
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Vendor Type Data</h1></center>
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
                                <form action="{{ route('store-vendor-type') }}" method="POST" enctype="multipart/form-data" id="regForm">
                                    @csrf
                                    <div class="form-group-inner">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="name">Name:</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                        </div>
                                    </div>
                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-vendor-type') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
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
@push('scripts') 
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
                    },
                    // You can add more fields here as needed
                },
                messages: {
                    name: {
                        required: "Please enter the name.",
                    },
                    // Custom error messages for other fields can go here
                },
            });
        });
        </script>
        @endpush
  
@endsection
