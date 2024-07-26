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
                            <h1>Add Dispatch Data</h1>
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
                                        <form action="{{ route('store-dispatch', 
                                       ) }}"
                                            method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                           
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="customer_po_number">PO  Number :  <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="customer_po_number" id="customer_po_number"
                                                        placeholder="Enter the customer po number"
                                                        value=" @if (old('customer_po_number')) {{ old('customer_po_number') }}@else{{ $editData->customer_po_number }} @endif" readonly>
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
                                                                <label for="remarks">Remark</label> (optional) 
                                                                <textarea class="form-control" rows="3" type="text" class="form-control" id="remarks" name="remarks"
                                                                    placeholder="Enter Remark">{{ old('remarks') }}</textarea>
                                                                   
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
