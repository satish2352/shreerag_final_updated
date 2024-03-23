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
                        <h1>Add Production Data</h1>
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
                                    <form action="{{ route('store-purchase') }}" method="POST" id="addDesignsForm"
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

                                                <table class="table table-bordered" id="dynamicTable">

                                                    <tr>

                                                        <th>Product Name</th>

                                                        <th>Product Qty</th>

                                                        <th>Product Price</th>
                                                        <th>Product Unit</th>
                                                        <th>Action</th>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" name="addmore[0][design_name]" id="design_name"
                                                                placeholder="Enter your Name" class="form-control" />
                                                        </td>

                                                        <td><input type="text" name="addmore[0][product_quantity]"
                                                                placeholder="Enter your Qty" class="form-control" />
                                                        </td>

                                                        <td><input type="text" name="addmore[0][product_size]"
                                                                placeholder="Enter your Price" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][product_unit]"
                                                                placeholder="Enter your Product Unit" class="form-control" />
                                                        </td>

                                                        <td><button type="button" name="add" id="add"
                                                                class="btn btn-success">Add More</button></td>

                                                    </tr>

                                                </table>

                                                {{-- <button type="submit" class="btn btn-success">Save</button> --}}

                                                {{-- </form> --}}

                                            </div>

                                            {{-- =================== --}}

                                            <div class="row">
                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="design_name">Product Name:</label>
                                                        <input type="text" class="form-control" id="design_name"
                                                            name="design_name" placeholder="Enter Employee name">
                                                    </div> -->
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="design_page">Product Page:</label>
                                                    <input type="text" class="form-control" id="design_page"
                                                        name="design_page" placeholder="Enter design_page">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="project_name">Project Name:</label>
                                                    <input type="text" class="form-control" id="project_name"
                                                        name="project_name" placeholder="Enter Aadhar number">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="time_allocation">Time Allocated For Product:</label>
                                                    <input type="text" class="form-control" id="time_allocation"
                                                        name="time_allocation" placeholder="Enter Pancard number">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="image">Image:</label>
                                                    <input type="file" class="form-control" accept="image/*" id="image"
                                                        name="image">
                                                </div>
                                            </div>

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-purchase') }}" class="btn btn-white"
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

$("#add").click(function() {
    ++i;

    $("#dynamicTable").append(
        '<tr><td><input type="text" name="addmore[' +
        i +
        '][design_name]" placeholder="Enter your Name" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][product_quantity]" placeholder="Enter your Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][product_size]" placeholder="Enter your Price" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][product_unit]" placeholder="Enter your Unit" class="form-control" /></td><td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete"><i class="fas fa-archive"></i></a></td></tr>'
    );
});

$(document).on("click", ".remove-tr", function() {
    $(this).parents("tr").remove();
});
</script>
<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $("#addDesignsForm").validate({
        rules: {
            // design_name: {
            //     required: true,
            // },
            design_page: {
                required: true,
                // Add your custom validation rule if needed
            },
            project_name: {
                required: true,
            },
            time_allocation: {
                required: true,
            },
            image: {
                required: true,
                accept: "image/*",
            },
            design_name: {
                required: true,
            },
            // Add validation rules for other fields
            'addmore[0][design_name]': {
                required: true,
            },
            'addmore[0][product_quantity]': {
                required: true,
            },
            'addmore[0][product_size]': {
                required: true,
            },
            'addmore[0][product_unit]': {
                required: true,
            },

        },
        messages: {
            // design_name: {
            //     required: "Please enter design name.",
            // },
            design_page: {
                required: "Please enter a valid design page.",
            },
            project_name: {
                required: "Please enter project name.",
            },
            time_allocation: {
                required: "Please enter time allocated for design.",
            },
            image: {
                required: "Please select an image.",
                accept: "Please select an image file.",
            },
            design_name: {
                required: "Please enter Design Name.",
            },
             // Add error messages for other fields
             'addmore[0][design_name]': {
                required: "Please enter Design Name.",
            },
            'addmore[0][product_quantity]': {
                required: "Please enter Product Quantity.",
            },
            'addmore[0][product_size]': {
                required: "Please enter Product Size.",
            },
            'addmore[0][product_unit]': {
                required: "Please enter Product Unit.",
            },
        },
    });
});
</script>



@endsection