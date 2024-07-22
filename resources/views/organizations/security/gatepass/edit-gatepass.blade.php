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
                            <h1>Edit Production Data</h1>
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
                                @if (Session::has('status'))
                                    <div class="col-md-12">
                                        <div class="alert alert-{{ Session::get('status') }} alert-dismissible"
                                            role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>{{ ucfirst(Session::get('status')) }}!</strong>
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form
                                            action="{{ route('update-gatepass') }}"
                                            method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="container-fluid">

                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="purchase_orders_id">PO :</label>
                                                            <input type="text" class="form-control"
                                                                id="purchase_orders_id" name="purchase_orders_id"
                                                                value=" @if (old('purchase_orders_id')) {{ old('purchase_orders_id') }}@else{{ $editData->purchase_orders_id }} @endif"
                                                                placeholder="" readonly >
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="gatepass_name">Name :</label>
                                                            <input type="text" class="form-control" id="gatepass_name"
                                                                name="gatepass_name"
                                                                value=" @if (old('gatepass_name')) {{ old('gatepass_name') }}@else{{ $editData->gatepass_name }} @endif"
                                                                placeholder="Enter the Name">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="gatepass_date">Date: <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="gatepass_date"
                                                                id="gatepass_date" placeholder="Select Date"
                                                                value="{{ old('gatepass_date', $editData->gatepass_date) }}">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="gatepass_time">Time :</label>
                                                            <input type="time" class="form-control" id="gatepass_time"
                                                                name="gatepass_time"
                                                                value="{{ old('gatepass_time', $editData->gatepass_time) }}"
                                                                placeholder="Select Time">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="remark">Remark :</label>
                                                            <input type="text" class="form-control" id="remark"
                                                                name="remark"
                                                                value=" @if (old('remark')) {{ old('remark') }}@else{{ $editData->remark }} @endif"
                                                                placeholder="Enter  Remark">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-products') }}"
                                                                    class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    type="submit" style="margin-bottom:50px">Update
                                                                    Data</button>

                                                            </div>
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
    <form method="POST" action="{{ route('delete-addmore') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
@endsection
