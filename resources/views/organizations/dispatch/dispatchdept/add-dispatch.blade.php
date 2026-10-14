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
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
      <div class="emp-form-header">
        <h4><i class="fa fa-truck mr-2"></i> Add Dispatch</h4>
        <a href="{{ route('list-final-production-completed-received-from-fianance') }}" class="emp-form-close">&times;</a>
      </div>
      <div class="emp-form-body">

            @if (session('msg'))
                <div class="alert alert-{{ session('status') }}">
                    {{ session('msg') }}
                </div>
            @endif

            @if (Session::has('status'))
                <div class="col-md-12">
                    <div class="alert alert-{{ Session::get('status') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>{{ ucfirst(Session::get('status')) }}!</strong>
                        {{ Session::get('msg') }}
                    </div>
                </div>
            @endif

            <div class="all-form-element-inner">
                <form
                    action="{{ route('store-dispatch', ['id' => $editData->id, 'business_details_id' => $editData->business_details_id]) }}"
                    id="addProductForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="" class="form-control"
                        value="{{ $editData->id }}" placeholder="">
                    <input type="hidden" name="business_details_id"
                        value="{{ $editData->business_details_id }}">

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="customer_po_number"
                                    name="customer_po_number" placeholder=" "
                                    value=" @if (old('customer_po_number')) {{ old('customer_po_number') }}@else{{ $editData->customer_po_number }} @endif"
                                    readonly>
                                <label for="customer_po_number">PO Number</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="product_name"
                                    name="product_name" placeholder=" "
                                    value=" @if (old('product_name')) {{ old('product_name') }}@else{{ $editData->product_name }} @endif"
                                    readonly>
                                <label for="product_name">Product Name</label>
                                @if ($errors->has('product_name'))
                                    <span class="fl-error"><?php echo $errors->first('product_name', ':message'); ?></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="quantity"
                                    name="quantity" placeholder=" "
                                    value=" @if (old('quantity')) {{ old('quantity') }}@else{{ $editData->quantity }} @endif"
                                    readonly>
                                <label for="quantity">Total Quantity</label>
                                @if ($errors->has('quantity'))
                                    <span class="fl-error"><?php echo $errors->first('quantity', ':message'); ?></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="completed_quantity"
                                    name="completed_quantity" placeholder=" "
                                    value=" @if (old('completed_quantity')) {{ old('completed_quantity') }}@else{{ $editData->completed_quantity }} @endif"
                                    readonly>
                                <label for="completed_quantity">Actual Production Quantity</label>
                                @if ($errors->has('completed_quantity'))
                                    <span class="fl-error"><?php echo $errors->first('completed_quantity', ':message'); ?></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="title"
                                    name="title" placeholder=" "
                                    value=" @if (old('title')) {{ old('title') }}@else{{ $editData->title }} @endif"
                                    readonly>
                                <label for="title">Vendor Name</label>
                                @if ($errors->has('title'))
                                    <span class="fl-error"><?php echo $errors->first('title', ':message'); ?></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="truck_no"
                                    name="truck_no" placeholder=" "
                                    value=" @if (old('truck_no')) {{ old('truck_no') }}@else{{ $editData->truck_no }} @endif"
                                    readonly>
                                <label for="truck_no">Truck Number</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="vehicle_type"
                                    name="vehicle_type" placeholder=" "
                                    value="{{ $editData->vehicle_type ?? '' }}" readonly>
                                <label for="vehicle_type">Vehicle Type</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="transport_name"
                                    name="transport_name" placeholder=" "
                                    value="{{ $editData->transport_name ?? '' }}" readonly>
                                <label for="transport_name">Transport Name</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="outdoor_no"
                                    name="outdoor_no" placeholder=" " value="">
                                <label for="outdoor_no">Outdoor No. (Optional)</label>
                                @if ($errors->has('outdoor_no'))
                                    <span class="fl-error"><?php echo $errors->first('outdoor_no', ':message'); ?></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="text" class="form-control" id="gate_entry"
                                    name="gate_entry" placeholder=" " value="">
                                <label for="gate_entry">Gate Entry <span class="text-danger">*</span></label>
                                @if ($errors->has('gate_entry'))
                                    <span class="fl-error"><?php echo $errors->first('gate_entry', ':message'); ?></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <textarea class="form-control" rows="3" id="remark" name="remark">{{ old('remark') }}</textarea>
                                <label for="remark">Remark (optional)</label>
                            </div>
                        </div>
                    </div>

                    <div class="login-btn-inner">
                        <div class="row">
                            <div class="col-lg-5"></div>
                            <div class="col-lg-7">
                                <div class="login-horizental cancel-wp pull-left">
                                    <a href="{{ route('list-final-production-completed-received-from-fianance') }}"
                                        class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                    <button class="btn btn-sm btn-primary login-submit-cs" type="submit"
                                        style="margin-bottom:50px" id="submitButton">Save Data</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

      </div>
    </div>
    </div></div></div></div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            // Validate the form
            $("#addProductForm").validate({
                rules: {
                    // outdoor_no:{
                    //     required: true,
                    // },
                    gate_entry: {
                        required: true,

                    }
                },
                messages: {
                    gate_entry: {
                        required: "Please enter the gate entry."

                    },
                    // outdoor_no: {
                    //     required: "Please enter the outdoor no."

                    // }
                },
                submitHandler: function(form) {
                    // Confirmation dialog before submitting the form
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you want to mark this quantity as completed for dispatch?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, submit it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
