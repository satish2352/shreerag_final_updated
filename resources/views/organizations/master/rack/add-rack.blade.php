@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Rack</h4>
    <a href="{{ route('list-rack') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    <form action="{{ route('store-rack') }}" method="POST" enctype="multipart/form-data" id="regForm">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder=" ">
                    <label for="name">Name <span class="red-text">*</span></label>
                    @if($errors->has('name'))<span class="fl-error">{{ $errors->first('name') }}</span>@endif
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-right">
                <a href="{{ route('list-rack') }}" class="btn btn-secondary mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </div>
    </form>
  </div>
</div>
</div></div></div></div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
