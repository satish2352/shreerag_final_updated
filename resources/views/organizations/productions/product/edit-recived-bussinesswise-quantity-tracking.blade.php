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
    .disabled-row {
        background-color: #f0f0f0;
        opacity: 0.6;
    }
    .disabled-row input,
    .disabled-row select,
    .disabled-row button {
        pointer-events: none;
    }
    .marg-top{
        margin-top: 30px !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Product Competed Quantity</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if (Session::get('status') == 'success')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-success" id="success-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                </div>
                            @endif

                            @if (Session::get('status') == 'error')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-danger" id="error-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                     <form action="{{route('update-final-production-completed-status', $id)}}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">
                                        <input type="hidden" name="part_item_id" id="part_item_id" value="{{ $id }}">
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="product_name">Name:</label>
                                                    <input type="text" class="form-control" id="name" name="product_name" value="{{ $productDetails->product_name }}" placeholder="Enter Product Name" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="description">Description :</label>
                                                    <input type="text" class="form-control" id="description" name="description" value="{{ $productDetails->description }}" placeholder="Enter Description" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="quantity">Actual Quantity :</label>
                                                    <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $productDetails->quantity }}" placeholder="Enter Quantity" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="completed_quantity">Completed Quantity :</label>
                                                    <input type="text" class="form-control" id="completed_quantity" name="completed_quantity" value="" placeholder="Enter Completed Quantity" >
                                                </div>
                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="description">Description:</label>
                                                <input type="text" class="form-control" id="description" name="description" value="{{ $productDetails->description }}" placeholder="Enter Description" readonly>
                                            </div> --}}
                                        </div>
                                  
                                        
                                        <div class="login-btn-inner marg-top">
                                            <button class="btn btn-sm btn-primary" type="submit">Submit Completed Quantity</button>
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
@endsection