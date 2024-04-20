@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 10px;
        }
        label.error {
            color: red;
            font-size: 12px;
        }
        .form-display-center{
        display: flex !important;
        justify-content: center !important;
        align-items: center;
        }
    </style>
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add New Business</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">


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
                                        <div class="row d-flex justify-content-center form-display-center">
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                                            <form action="{{ route('store-business') }}" method="POST" id="addEmployeeForm"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group-inner">
                                                <div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label for="title">Title:</label> 
                                                        <input type="text" class="form-control" id="title" value="{{ old('title') }}"
                                                            name="title" placeholder="Enter Title">
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="sparkline12-graph">
                                                            <div id="pwd-container1">
                                                                <div class="form-group">
                                                                    <label for="descriptions">Description</label>
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="descriptions" name="descriptions"
                                                                        placeholder="Enter Description">{{ old('descriptions') }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="pwstrength_viewport_progress"></span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="sparkline12-graph">
                                                            <div id="pwd-container1">
                                                                <div class="form-group">
                                                                    <label for="password1">Remark</label>
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="remarks" name="remarks"
                                                                        placeholder="Enter Remark">{{ old('remarks') }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="pwstrength_viewport_progress"></span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-business') }}" class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    type="submit" style="margin-bottom:50px">Save Data</button>
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
            $("#addEmployeeForm").validate({
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
                // Use SweetAlert to show a confirmation dialog
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    text: 'You want to submit this business to Design Department ?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // If user clicks "Yes", submit the form
                        form.submit();
                    }
                });
            }
        });
    });
    </script>
@endsection
