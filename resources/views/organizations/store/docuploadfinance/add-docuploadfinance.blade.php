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
                        <h1>Add Store Finance Data</h1>
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
                                    <form action="{{ route('store-doc-upload-fianace') }}" method="POST" id="addDesignsForm"
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


                                            </div>

                                            {{-- =================== --}}

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-select-list">        
                                                        <label for="department_id">Select Department :</label>
                                                        <select class="form-control custom-select-value" name="department_id">
                                                            <option value="">Select Department</option>
                                                            <option value="">sample</option>
                                                        </select>
                                                </div>     

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                   <label for="grn_image">GRN Image :</label>
                                                    <input type="file" class="form-control" accept="image/*" id="grn_image"
                                                        name="grn_image">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                   <label for="sr_image">GRN Image :</label>
                                                    <input type="file" class="form-control" accept="image/*" id="sr_image"
                                                        name="sr_image" >
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                   <label for="status">Status :</label>
                                                    <input type="text" class="form-control" id="status"
                                                        name="status" placeholder="Enter your status ">
                                                </div>

                                                

                                                </div>
                                                
                                                                                  

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-doc-upload-fianace') }}" class="btn btn-white"
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

<script>
var i = 0;

$("#add").click(function() {
    ++i;

    $("#dynamicTable").append(
        '<tr><td><input type="text" name="addmore[' +
        i +
        '][quantity]" placeholder="Enter your quantity" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][description]" placeholder="Enter your description" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][price]" placeholder="Enter your Price" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][amount]" placeholder="Enter your amount" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][total]" placeholder="Enter your total" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
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
          
            department_id: {
                required: true,
                // Add your custom validation rule if needed
            },
            grn_image: {
                required: true,
            },
            sr_image: {
                required: true,
            },
           
            status: {
                required: true,
            },
        },
        messages: {
          
            department_id: {
                required: "Please enter a valid department id.",
            },
            grn_image: {
                required: "Please select an image.",
                accept: "Please select an image file.",
            },
          
            sr_image: {
                required: "Please select an image.",
                accept: "Please select an image file.",
            },
        
            status: {
                required: "Please enter status",
            },

        },
        submitHandler: function(form) {
                // Use SweetAlert to show a success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Document upload successfully.',
                }).then(function() {
                    form.submit(); // Submit the form after the user clicks OK
                });
            }
        });
    });
</script>



@endsection