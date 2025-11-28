@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Rejected Estimation List Sent For Correction</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (Session::get('status') == 'success')
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Error!</strong> {!! session('msg') !!}
                                    </div>
                                @endif
                                <div class="all-form-element-inner">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                                            <form action="{{ route('add-rejected-bom-estimation') }}" method="POST" id="addEmployeeForm" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="business_id" id="business_id" value="{{ $idtoedit }}">

                                                <div class="form-group">
                                                    <label for="rejected_remark_by_owner">Remark</label>
                                                    <textarea class="form-control" rows="3" id="rejected_remark_by_owner" name="rejected_remark_by_owner" placeholder="Enter Remark" required>{{ old('rejected_remark_by_owner') }}</textarea>
                                                </div>

                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-business') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs" id="saveButton" type="button" style="margin-bottom:50px">Save Data</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div> <!-- col-9 -->
                                    </div> <!-- row -->
                                </div> <!-- all-form-element-inner -->
                            </div> <!-- col-12 -->
                        </div> <!-- row -->
                    </div> <!-- basic-login-form-ad -->
                </div> <!-- sparkline12-graph -->
            </div> <!-- sparkline12-list -->
        </div> <!-- col-12 -->
    </div> <!-- row -->
@push('scripts')  
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // jQuery Validation
            $("#addEmployeeForm").validate({
                rules: {
                    rejected_remark_by_owner: {
                        required: true,
                    },
                },
                messages: {
                    rejected_remark_by_owner: {
                        required: "Please enter Remark.",
                    },
                },
                errorElement: 'label',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                }
            });

            // Save button with SweetAlert confirmation
            $("#saveButton").click(function() {
                if ($("#addEmployeeForm").valid()) {
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'Do you want to reject this estimation and resend to Estimation Department?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $("#addEmployeeForm").submit();
                        }
                    });
                }
            });
        });
    </script>
    @endpush
@endsection
