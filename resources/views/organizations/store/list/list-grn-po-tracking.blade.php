@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1> GRN Details</h1>
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
                                                        <input type="text" class="form-control" id="po_date"
                                                            name="po_date" placeholder="Enter PO Date"
                                                            value="{{ $purchase_order_data->created_at->format('Y-m-d') }}"
                                                            readonly>
                                                    </div>   
                                                    
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="bill_no">Bill No. :</label>
                                                        <input type="text" class="form-control" id="bill_no"
                                                            name="bill_no" placeholder=""
                                                            value="{{ $grn_data->bill_no}}"
                                                            readonly>
                                                    </div> 
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="bill_date">Bill Date :</label>
                                                        <input type="date" class="form-control" id="bill_date"
                                                            name="bill_date" placeholder=""
                                                            value="{{ $grn_data->bill_date }}"
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
