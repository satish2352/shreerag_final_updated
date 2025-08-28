@extends('admin.layouts.master')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center>
                        <h1>Rejected Design List Sent For Correction</h1>
                    </center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{-- Success message --}}
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

                            {{-- Error message --}}
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
                                                    <input type="hidden" name="business_id" id="business_id"
                                                        value="{{ $idtoedit }}">
                                                    
                                                    <div class="form-group">
                                                        <label for="reject_reason_prod">Remark</label>
                                                        <textarea class="form-control" rows="3" id="reject_reason_prod"
                                                            name="reject_reason_prod" placeholder="Enter Remark"
                                                            required>{{ old('reject_reason_prod') }}</textarea>
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
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    id="saveButton" type="button"
                                                                    style="margin-bottom:50px">Save Data</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div> <!-- col -->
                                    </div> <!-- row -->
                                </div>
                            </div>
                        </div>
                    </div> <!-- row -->
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS dependencies --}}
 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        // ✅ jQuery Validation setup
        $("#addEmployeeForm").validate({
            rules: {
                reject_reason_prod: {
                    required: true,
                    minlength: 3 // optional: at least 3 characters
                }
            },
            messages: {
                reject_reason_prod: {
                    required: "Please enter a remark.",
                    minlength: "Remark must be at least 3 characters."
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element); // show error below the field
            },
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            }
        });

        // ✅ Save button click handler
        $("#saveButton").click(function() {
            // Validate first
            if (!$("#addEmployeeForm").valid()) {
                $("#addEmployeeForm").find(".error:first").focus();
                return; // Stop if invalid
            }

            // SweetAlert confirmation
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to reject this design and resend to Design Department?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then(function(result) {
                if (result.isConfirmed) {
                    $("#addEmployeeForm").submit();
                }
            });
        });
    });
</script>
@endsection
