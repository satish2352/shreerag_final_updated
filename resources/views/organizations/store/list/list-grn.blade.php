@extends('admin.layouts.master')
@section('content')
<style>
     .sparkline13-list-new {
        background-color: #fff;
        padding: 22px;
        margin-top: 72px;
        margin-bottom: 80px;
    }
    .error{
        color: red;
    }
</style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline13-list-new">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1> GRN Details</h1><br>
                            <div class="d-flex justify-content-center align-items-center">
                                <h4 style="display: flex; justify-content: left; color: green;padding-left: 16px;">Note: First You will Add This Accepted Quantity In Inventory Department, Then Only You Can Issue Material to Production Department.</h4> 
                                    
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
                                        <form action="{{ route('store-grn') }}" method="POST" id="addDesignsForm"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group-inner">

                                                {{-- ========================== --}}
                                                <div class="container-fluid">
                                                    {{-- <form 
                                                action="{{ route('addmorePost') }}"
                                                method="POST"> --}}

                                                    {{-- @csrf --}}

                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">

                                                            <ul>

                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach

                                                            </ul>

                                                        </div>
                                                    @endif

                                                    @if (Session::has('success'))
                                                        <div class="alert alert-success text-center">

                                                            <a href="#" class="close" data-dismiss="alert"
                                                                aria-label="close">×</a>

                                                            <p>{{ Session::get('success') }}</p>

                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="po_date">GRN No. :</label>
                                                        <input type="text" class="form-control" id="grn_no"
                                                            name="grn_no" placeholder=""
                                                            value="{{ $grn_data->grn_no_generate }}"
                                                            readonly>
                                                    </div> 

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="grn_date">GRN Date:</label>
                                                        <input type="date" class="form-control" id="grn_date"
                                                            name="grn_date" placeholder="Enter GRN Date"
                                                            value="{{ $grn_data->grn_date }}" readonly>

                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="purchase_orders_id">PO No.:</label>
                                                        <input type="text" class="form-control" id="purchase_orders_id"
                                                            name="purchase_orders_id" placeholder="Enter Purchase No."
                                                            value="{{ $purchase_order_data->purchase_orders_id }}" readonly>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="po_date">PO Date :</label>
                                                        <input type="date" class="form-control" id="po_date"
                                                            name="po_date" placeholder="Enter PO Date"
                                                            value="{{ $purchase_order_data->created_at->format('Y-m-d') }}"
                                                            readonly>
                                                    </div> 
                                                    
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="gatepass_name">Customer Name :</label>
                                                        <input type="text" class="form-control" id="gatepass_name"
                                                            name="gatepass_name" placeholder="Enter PO Date"
                                                            value="{{ $grn_data->gatepass_name }}"
                                                            readonly>

                                                    </div>                                              
                                                </div>

                                                <div style="margin-top:20px">
                                                    <table class="table table-bordered" id="dynamicTable">
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>PO Quantity</th>
                                                            <th>Actual Quantity</th>
                                                            <th>Accepted Quantity</th>
                                                            <th>Rejected Quantity</th>
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                        @foreach ($purchase_order_details_data as $item)
                                                            <tr>
                                                                <input type="hidden" name="addmore[0][edit_id]"
                                                                    placeholder="Enter Description" class="form-control"
                                                                    value="{{ $item->id }}" readonly />
                                                                <td><input type="text" name="addmore[0][description]"
                                                                        placeholder="Enter Description" class="form-control"
                                                                        value="{{ $item->description }}" readonly />
                                                                </td>
                                                                <td><input type="text" name="addmore[0][chalan_quantity]"
                                                                        placeholder="Enter Chalan Qty" class="form-control"
                                                                        value="{{ $item->quantity }}" readonly />
                                                                </td>
                                                                <td><input type="text" name="addmore[0][actual_quantity]"
                                                                        placeholder="Enter Actual Qty"
                                                                        class="form-control actual_quantity"
                                                                        value="{{ $item->actual_quantity }}" readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[0][accepted_quantity]"
                                                                        placeholder="Enter Accepted Qty"
                                                                        class="form-control accepted_quantity"
                                                                        value="{{ $item->accepted_quantity }}" readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[0][rejected_quantity]"
                                                                        placeholder="Enter Rejected Qty"
                                                                        class="form-control rejected_quantity"
                                                                        value="{{ $item->rejected_quantity }}" readonly />
                                                                </td>
                                                                {{-- <td><button type="button" name="add" id="add"
                                                                        class="btn btn-success">Add More</button></td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>

                                                <div class="row">
                                                    <div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="remark">Remark:</label>
                                                        <textarea class="form-control" rows="3" type="text" class="form-control" id="remark" name="remark"
                                                            placeholder="Enter Remark" readonly>{{ $grn_data->remark }}</textarea>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="image">Signature:</label><br>
                                                            <img src="{{ Config::get('DocumentConstant.GRN_VIEW') }}{{ $grn_data->image }}"
                                                                style="width:150px; height:150px; background-color: aliceblue;"
                                                                alt=" No Signature" />
                                                    </div>



                                                </div>


                                            </div>
                                        </form>
                                        @if($grn_data->store_receipt_no_generate === NULL && $grn_data->store_remark === NULL)
                                        <form action="{{ route('generate-sr-store-dept') }}" method="POST" id="addStoreRemark" enctype="multipart/form-data">
                                            @csrf
                                            <!-- Hidden Input for GRN ID -->
                                            <input type="hidden" name="id" id="id" value="{{ $grn_id }}">
                                            
                                            <!-- Store Remark Input -->
                                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="store_remark">Store Remark:</label>
                                                <textarea
                                                    class="form-control"
                                                    rows="3"
                                                    id="store_remark"
                                                    name="store_remark"
                                                    placeholder="Enter store remark">{{ $grn_data->store_remark }}</textarea>
                                            </div>
                                            
                                            <!-- Submit Button -->
                                            <div class="form-group col-lg-12">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </form>
                                    @else
                                        <form action="{{ route('generate-sr-store-dept') }}" method="POST" id="addStoreRemark" enctype="multipart/form-data">
                                            @csrf
                                            <!-- Hidden Input for GRN ID -->
                                            <input type="hidden" name="id" id="id" value="{{ $grn_id }}">
                                            
                                            <!-- Store Remark Input -->
                                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="store_remark">Store Remark:</label>
                                                <textarea
                                                    class="form-control"
                                                    rows="3"
                                                    id="store_remark"
                                                    name="store_remark"
                                                    placeholder="Enter store remark" disabled>{{ $grn_data->store_remark }}</textarea>
                                            </div>
                                            
                                            <!-- Submit Button -->
                                            <div class="form-group col-lg-12">
                                                <button type="submit" class="btn btn-primary" disabled>Submit</button>
                                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </form>
                                    @endif
                                    
                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery Validation Script -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        var i = 0;
    </script>
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $("#addStoreRemark").validate({
                rules: {
                    store_remark: {
                        required: true,
                    },
                },
                messages: {
                    store_remark: {
                        required: "Please enter Remark.",
                    },
                },
            });
        });
    </script>
    
@endsection
