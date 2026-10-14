@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-file-upload mr-2"></i> Upload Finance Document</h4>
    <a href="{{ route('list-doc-upload-fianace') }}" class="emp-form-close">&times;</a>
  </div>
  <div class="emp-form-body">

    @if (session('msg'))
        <div class="alert alert-{{ session('status') }}">
            {{ session('msg') }}
        </div>
    @endif
    @if (Session::get('status') == 'success')
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Success!</strong> {{ Session::get('msg') }}
        </div>
    @endif
    @if (Session::get('status') == 'error')
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> {!! session('msg') !!}
        </div>
    @endif

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
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif

    <form action="{{ route('store-doc-upload-fianace') }}" method="POST" id="addDesignsForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control custom-select-value" name="department_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="">Select Department</option>
                        <option value="">sample</option>
                    </select>
                    <label for="department_id">Select Department</label>
                    @if($errors->has('department_id'))<span class="fl-error">{{ $errors->first('department_id') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="file" class="form-control" accept="image/*" id="grn_image" name="grn_image">
                    <label for="grn_image">GRN Image</label>
                    @if($errors->has('grn_image'))<span class="fl-error">{{ $errors->first('grn_image') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="file" class="form-control" accept="image/*" id="sr_image" name="sr_image">
                    <label for="sr_image">SR Image</label>
                    @if($errors->has('sr_image'))<span class="fl-error">{{ $errors->first('sr_image') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" id="status" name="status" placeholder=" ">
                    <label for="status">Status</label>
                    @if($errors->has('status'))<span class="fl-error">{{ $errors->first('status') }}</span>@endif
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-auto">
                <div class="login-horizental cancel-wp">
                    <a href="{{ route('list-doc-upload-fianace') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                    <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
                </div>
            </div>
        </div>
    </form>

  </div>
</div>
</div></div></div></div>

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
