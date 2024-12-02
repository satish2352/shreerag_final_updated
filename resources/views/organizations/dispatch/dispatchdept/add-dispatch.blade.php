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
                            <h1>Add Logistics Data</h1>
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
                                        <form  action="{{ route('store-dispatch', ['id' => $editData->id, 'business_details_id' => $editData->business_details_id]) }}" method="POST"
                                       
                                          id="editDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" id=""
                                            class="form-control" value="{{ $editData->id }}"
                                            placeholder="">
                                            {{-- <input type="hidden" name="id" id=""
                                            class="form-control" value="{{ $editData->business_details_id }}"
                                            placeholder=""> --}}
                                            <input type="hidden" name="business_details_id" value="{{ $editData->business_details_id }}">

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="customer_po_number">PO  Number :  <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="customer_po_number" id="customer_po_number"
                                                        placeholder="Enter the customer po number"
                                                        value=" @if (old('customer_po_number')) {{ old('customer_po_number') }}@else{{ $editData->customer_po_number }} @endif" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="product_name">product Name :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="product_name"
                                                     value=" @if (old('product_name')) {{ old('product_name') }}@else{{ $editData->product_name }} @endif"
                                                        name="product_name" placeholder="Enter Product Name" readonly>
                                                        @if ($errors->has('product_name'))
                                                        <span class="red-text"><?php echo $errors->first('product_name', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="quantity">Total Quantity :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="quantity"
                                                     value=" @if (old('quantity')) {{ old('quantity') }}@else{{ $editData->quantity }} @endif"
                                                        name="quantity" placeholder="Enter Product Name" readonly>
                                                        @if ($errors->has('quantity'))
                                                        <span class="red-text"><?php echo $errors->first('quantity', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="completed_quantity">Actual Production Quantity :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="completed_quantity"
                                                     value=" @if (old('completed_quantity')) {{ old('completed_quantity') }}@else{{ $editData->completed_quantity }} @endif"
                                                        name="completed_quantity" placeholder="Enter completed quantity " readonly>
                                                        @if ($errors->has('completed_quantity'))
                                                        <span class="red-text"><?php echo $errors->first('completed_quantity', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="title">Vendor Name :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="title"
                                                     value=" @if (old('title')) {{ old('title') }}@else{{ $editData->title }} @endif"
                                                        name="title" placeholder="Enter Vendor Name" readonly>
                                                        @if ($errors->has('title'))
                                                        <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="truck_no">Truck Number:  <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="truck_no" id="truck_no"
                                                        placeholder="Enter the customer po number"
                                                        value=" @if (old('truck_no')) {{ old('truck_no') }}@else{{ $editData->truck_no }} @endif" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="truck_no">Truck Number:  <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="truck_no" id="truck_no"
                                                        placeholder="Enter the customer po number"
                                                        value=" @if (old('truck_no')) {{ old('truck_no') }}@else{{ $editData->truck_no }} @endif" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="vehicle_type">Vehicle Type: <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="vehicle_type" id="vehicle_type"
                                                           placeholder="Enter Vehicle Type"
                                                           value="{{ $editData->vehicle_type ?? '' }}" readonly>
                                                </div>
                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="transport_name">Transport Name: <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="transport_name" id="transport_name"
                                                           placeholder="Enter Transport Name"
                                                           value="{{ $editData->transport_name ?? '' }}" readonly>
                                                </div>
                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="outdoor_no">Outdoor No. :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="outdoor_no"
                                                     value=" @if (old('outdoor_no')) {{ old('outdoor_no') }}@else{{ $editData->outdoor_no }} @endif"
                                                        name="outdoor_no" placeholder="Enter Product Name">
                                                        @if ($errors->has('outdoor_no'))
                                                        <span class="red-text"><?php echo $errors->first('outdoor_no', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="gate_entry">Gate Entry :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="gate_entry"
                                                     value=" "
                                                        name="gate_entry" placeholder="Enter Product Name">
                                                        @if ($errors->has('gate_entry'))
                                                        <span class="red-text"><?php echo $errors->first('gate_entry', ':message'); ?></span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="sparkline12-graph">
                                                        <div id="pwd-container1">
                                                            <div class="form-group">
                                                                <label for="remark">Remark</label> (optional) 
                                                                <textarea class="form-control" rows="3" type="text" class="form-control" id="remark" name="remark"
                                                                    placeholder="Enter Remark">{{ old('remark') }}</textarea>
                                                                   
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="pwstrength_viewport_progress"></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                                                           </div>

                                            <div class="container-fluid">
                                                
                                            
                                            
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-products') }}"
                                                                    class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    type="submit" style="margin-bottom:50px">Save Data</button>
                                                               
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
 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
 

@endsection
