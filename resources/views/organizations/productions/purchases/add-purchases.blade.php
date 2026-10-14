@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="emp-form-card">
                    <div class="emp-form-header">
                        <h4><i class="fa fa-shopping-cart mr-2"></i> Add Production Purchase</h4>
                        <a href="{{ route('list-purchases') }}" class="emp-form-close">&times;</a>
                    </div>
                    <div class="emp-form-body">

                        @if(session('msg'))
                            <div class="alert alert-{{ session('status') }}">
                                {{ session('msg') }}
                            </div>
                        @endif

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

                        <form action="{{ route('store-purchases') }}" method="POST" id="addDesignsForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" id="name" name="name" placeholder=" ">
                                        <label for="name">Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" id="email" name="email" placeholder=" ">
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" id="price" name="price" placeholder=" ">
                                        <label for="price">Price</label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" id="contact" name="contact" placeholder=" ">
                                        <label for="contact">Contact</label>
                                    </div>
                                </div>
                            </div>
                           {{-- <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                    <div class="fl-group">
                                        <input type="file" class="form-control" accept="image/*" id="image" name="image">
                                        <label for="image">Image</label>
                                    </div>
                                </div>
                            </div>--}}

                            <div class="login-btn-inner">
                                <div class="row">
                                    <div class="col-lg-5"></div>
                                    <div class="col-lg-7">
                                        <div class="login-horizental cancel-wp pull-left">
                                            <a href="{{ route('list-purchases') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                            <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

<script>
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $("#addDesignsForm").validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    // Add your custom validation rule if needed
                },
                price: {
                    required: true,
                },
                contact: {
                    required: true,
                },

            },
            messages: {
                name: {
                    required: "Please enter design name.",
                },
                email: {
                    required: "Please enter a valid design page.",
                },
                price: {
                    required: "Please enter project name.",
                },
                contact: {
                    required: "Please enter time allocated for design.",
                },

            },
            submitHandler: function(form) {
                // Use SweetAlert to show a success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Purchases order add successfully.',
                }).then(function() {
                    form.submit(); // Submit the form after the user clicks OK
                });
            }
        });
    });
</script>



@endsection
