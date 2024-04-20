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

        .form-display-center {
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
                            <h1>Rejected Design List Sent For Corection</h1>
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
                                                <form action="{{ route('reject-design') }}" method="POST"
                                                    id="addEmployeeForm" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group-inner">
                                                        <div>

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="sparkline12-graph">
                                                                    <div id="pwd-container1">
                                                                        <div class="form-group">
                                                                            <input type="hidden" name="business_id"
                                                                                id="business_id"
                                                                                value="{{ $idtoedit }}">
                                                                            <label for="remarks">Remark</label>
                                                                            <textarea class="form-control" rows="3" type="text" class="form-control" id="reject_reason_prod"
                                                                                name="reject_reason_prod" placeholder="Enter Remark" required>{{ old('reject_reason_prod') }}</textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="pwstrength_viewport_progress">
                                                                                </span></div>
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
                                                                    <a href="{{ route('list-business') }}"
                                                                        class="btn btn-white"
                                                                        style="margin-bottom:50px">Cancel</a>
                                                                        <button class="btn btn-sm btn-primary login-submit-cs" id="saveButton" type="button" style="margin-bottom:50px">Save Data</button>
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
                $("#addEmployeeForm").validate({
                    rules: {
                        title: {
                            required: true,
                        },
                        descriptions: {
                            required: true,
                        },
                        reject_reason_prod: {
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
                        reject_reason_prod: {
                            required: "Please enter Remark.",
                        },

                    },
                });
            });
        </script>

<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $("#saveButton").click(function() {
            // Use SweetAlert to show a confirmation dialog
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to reject this design and resend to Design Department ?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then(function(result) {
                if (result.isConfirmed) {
                    // If user clicks "Yes", submit the form
                    $("#addEmployeeForm").submit();
                }
            });
        });
    });
</script>
    @endsection
