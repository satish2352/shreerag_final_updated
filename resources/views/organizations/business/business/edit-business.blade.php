@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 10px;
        }
        .form-display-center{
        display: flex !important;
        justify-content: center !important;
        align-items: center;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Update Business Data</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
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
                            <div class="all-form-element-inner">
                                <div class="row d-flex justify-content-center form-display-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                                <form action="{{ route('update-business') }}" method="POST" enctype="multipart/form-data" id="editEmployeeForm">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control"
                                            value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif"
                                            id="id" name="id">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="title">Title:</label>
                                                <input class="form-control" name="title" id="title"
                                                    placeholder="Enter the Title"
                                                    value=" @if (old('title')) {{ old('title') }}@else{{ $editData->title }} @endif">
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="descriptions">Description:</label>
                                                <textarea class="form-control english_description" name="descriptions" id="descriptions"
                                                    placeholder="Enter the Description">@if (old('descriptions')){{ old('descriptions') }}@else{{ $editData->descriptions }}@endif
                                            </textarea>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="remarks">Remark:</label>
                                                <textarea class="form-control english_description" name="remarks" id="remarks" placeholder="Enter the Description">@if (old('remarks')){{ old('remarks') }}@else{{ $editData->remarks }}@endif
                                            </textarea>
                                            </div>
                                        </div>
                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-business') }}"><button
                                                                class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</button></a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs"
                                                            type="submit" style="margin-bottom:50px">Update Data</button>
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
    </div>
    <script src="{{ asset('js/password-meter/pwstrength-bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/password-meter/zxcvbn.js') }}"></script>
    <script src="{{ asset('js/password-meter/password-meter-active.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $("#editEmployeeForm").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    descriptions: {
                        required: true,
                    },
                    remarks: {
                        required: true,
                    },

                },
                messages: {
                    title: {
                        required: "Please enter Title.",
                    },
                    descriptions: {
                        required: "Please enter Description.",
                    },
                    remarks: {
                        required: "Please enter Remark.",
                    },

                },
                submitHandler: function(form) {
                // Use SweetAlert to show a success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Form submitted successfully.',
                }).then(function() {
                    form.submit(); // Submit the form after the user clicks OK
                });
            }
        });
    });
    </script>
@endsection
