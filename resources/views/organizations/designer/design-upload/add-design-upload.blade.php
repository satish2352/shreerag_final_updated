@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            font-size: 12px;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add Design Data</h1>
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
                                        <form action="{{ route('update-design-upload') }}" method="POST"
                                            id="addDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group-inner">
                                                <div class="container-fluid">
                                                    @if (Session::has('success'))
                                                        <div class="alert alert-success text-center">
                                                            <a href="#" class="close" data-dismiss="alert"
                                                                aria-label="close">×</a>
                                                            <p>{{ Session::get('success') }}</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <input type="hidden" class="form-control" value="{{ $addData }}"
                                                    id="business_id" name="business_id">

                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="design_image">Upload Design Layout:</label>
                                                        <input type="file" class="form-control" accept="application/pdf"
                                                            id="design_image" name="design_image">
                                                        @if ($errors->has('design_image'))
                                                            <span class="red-text"><?php echo $errors->first('design_image', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="bom_image">Upload BOM:</label>
                                                        <input type="file" class="form-control" accept=".xls, .xlsx"
                                                            id="bom_image" name="bom_image">
                                                        @if ($errors->has('bom_image'))
                                                            <span class="red-text"><?php echo $errors->first('bom_image', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-design-upload') }}"
                                                                    class="btn btn-white"
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $("#addDesignsForm").validate({
                rules: {
                    design_image: {
                        required: true,
                        accept: "application/pdf", // Specify PDF MIME type
                    },
                    bom_image: {
                        required: true,
                        accept: ".xls,.xlsx",
                    },
                },
                messages: {
                    design_image: {
                        required: "Please select design layout pdf .",
                        accept: "Please select an  design layout pdf file.",
                    },
                    bom_image: {
                        required: "Please select bom excel .",
                        accept: "Please select an bom excel file.",
                    },
                },
                submitHandler: function(form) {
                    // Use SweetAlert to show a confirmation dialog
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'You want to send this design to Production Department ?',
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
