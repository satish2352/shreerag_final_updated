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
                                        <form action="{{ route('store-logistics', $editData->id
                                       ) }}"
                                            method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="business_id" id=""
                                                    class="form-control" value="{{ $editData->id }}"
                                                    placeholder="">
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
                                                    <label for="quantity">Quantity :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="quantity"
                                                     value=" @if (old('quantity')) {{ old('quantity') }}@else{{ $editData->quantity }} @endif"
                                                        name="quantity" placeholder="Enter Product Name" readonly>
                                                        @if ($errors->has('quantity'))
                                                        <span class="red-text"><?php echo $errors->first('quantity', ':message'); ?></span>
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
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="vehicle_type_id">Vehicle Type</label>&nbsp<span class="red-text">*</span>
                                                        <select class="form-control" id="vehicle_type_id" name="vehicle_type_id"
                                                            onchange="myFunction(this.value)">
                                                            <option value="">Select Vehicle Type</option>
                                                            @foreach ($dataOutputVehicleType as $role)
                                                                @if (old('vehicle_type_id') == $role['id'])
                                                                    <option value="{{ $role['id'] }}" selected>
                                                                        {{ $role['name'] }}</option>
                                                                @else
                                                                    <option value="{{ $role['id'] }}">{{ $role['name'] }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('vehicle_type_id'))
                                                            <span class="red-text"><?php echo $errors->first('vehicle_type_id', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="transport_name_id">Transport Name</label>&nbsp<span class="red-text">*</span>
                                                        <select class="form-control" id="transport_name_id" name="transport_name_id"
                                                            onchange="myFunction(this.value)">
                                                            <option value="">Select Transport Name</option>
                                                            @foreach ($dataOutputTransportName as $role)
                                                                @if (old('transport_name_id') == $role['id'])
                                                                    <option value="{{ $role['id'] }}" selected>
                                                                        {{ $role['name'] }}</option>
                                                                @else
                                                                    <option value="{{ $role['id'] }}">{{ $role['name'] }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('transport_name_id'))
                                                            <span class="red-text"><?php echo $errors->first('transport_name_id', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="truck_no">Truck Number :  <span class="text-danger">*</span></label> 
                                                    <input type="text" class="form-control" id="truck_no"
                                                     value=" "
                                                        name="truck_no" placeholder="Enter Product Name">
                                                        @if ($errors->has('truck_no'))
                                                        <span class="red-text"><?php echo $errors->first('truck_no', ':message'); ?></span>
                                                    @endif
                                                </div>
                                               
                                                {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="vendor_id">Vendor Company Name <span class="text-danger">*</span></label>
                                                            <select class="form-control mb-2" name="vendor_id" id="vendor_id">
                                                            <option value="" default>Vendor Company Name</option>
                                                            @foreach ($dataOutputVendor as $data)
                                                                    <option value="{{ $data['id'] }}" >
                                                                        {{ $data['vendor_company_name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div> --}}
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
