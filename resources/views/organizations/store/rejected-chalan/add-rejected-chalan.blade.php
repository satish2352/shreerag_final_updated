@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="emp-form-card">
                        <div class="emp-form-header">
                            <h4><i class="fa fa-ban mr-2"></i> Add Rejected Challan</h4>
                            <a href="{{ route('list-rejected-chalan') }}" class="emp-form-close">&times;</a>
                        </div>
                        <div class="emp-form-body">

                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

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

                            <form action="{{ route('store-rejected-chalan') }}" method="POST" id="addDesignsForm"
                                enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" id=""
                                class="form-control" value="{{ $gatepass_data->id }}"
                                placeholder="">
                                <div class="form-group-inner">

                                    <div class="container-fluid">
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
                                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                            <div class="fl-group">
                                                <input type="text" class="form-control" id="purchase_orders_id"
                                                    name="purchase_orders_id" placeholder=" "
                                                    value="{{ $purchase_order_data->purchase_orders_id }}" readonly>
                                                <label for="purchase_orders_id">PO No.</label>
                                                @if($errors->has('purchase_orders_id'))<span class="fl-error">{{ $errors->first('purchase_orders_id') }}</span>@endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                            <div class="fl-group">
                                                <input type="text" class="form-control" id="po_date"
                                                    name="po_date" placeholder=" "
                                                    value="{{ $purchase_order_data->created_at->format('d-m-Y') }}"
                                                    readonly>
                                                <label for="po_date">PO Date</label>
                                                @if($errors->has('po_date'))<span class="fl-error">{{ $errors->first('po_date') }}</span>@endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                            <div class="fl-group">
                                                <input type="text" class="form-control" id="gatepass_name"
                                                    name="gatepass_name" placeholder=" "
                                                    value="{{ $gatepass_data->gatepass_name }}"
                                                    readonly>
                                                <label for="gatepass_name">Customer Name</label>
                                                @if($errors->has('gatepass_name'))<span class="fl-error">{{ $errors->first('gatepass_name') }}</span>@endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                            <div class="fl-group">
                                                <input type="date" class="form-control" id="grn_date"
                                                    name="grn_date" placeholder=" "
                                                    value="{{ $gatepass_data->grn_date }}" readonly>
                                                <label for="grn_date">GRN Date</label>
                                                @if($errors->has('grn_date'))<span class="fl-error">{{ $errors->first('grn_date') }}</span>@endif
                                            </div>
                                        </div>
                                    </div>

                                    <div style="margin-top:20px">
                                        <table class="table table-bordered" id="dynamicTable">
                                            <tr>
                                                <th>Description</th>
                                                <th>Chalan Quantity</th>
                                                <th>Actual Quantity</th>
                                                <th>Accepted Quantity</th>
                                                <th>Rejected Quantity</th>
                                            </tr>
                                            @foreach ($purchase_order_details_data as $item)
                                                <tr>
                                                    <input type="hidden" name="addmore[0][edit_id]"
                                                        placeholder="Enter Description" class="form-control"
                                                        value="{{ $item->id }}" readonly />
                                                    <td><input type="text" name="addmore[0][description]"
                                                            placeholder="Enter Description"
                                                            class="form-control"
                                                            value="{{ $item->part_description }}" readonly />
                                                    </td>
                                                    <td><input type="text"
                                                            name="addmore[0][chalan_quantity]"
                                                            placeholder="Enter Chalan Qty"
                                                            class="form-control"
                                                            value="{{ $item->quantity }}" readonly />
                                                    </td>
                                                    <td><input type="text"
                                                            name="addmore[0][actual_quantity]"
                                                            placeholder="Enter Actual Qty"

                                                            class="form-control actual_quantity"
                                                            value="{{ $item->actual_quantity }}" readonly />
                                                    </td>
                                                    <td><input type="text"
                                                            name="addmore[0][accepted_quantity]"
                                                            placeholder="Enter Accepted Qty"
                                                            class="form-control accepted_quantity"
                                                            value="{{ $item->accepted_quantity }}" readonly/>
                                                    </td>
                                                    <td><input type="text"
                                                            name="addmore[0][rejected_quantity]"
                                                            placeholder="Enter Rejected Qty"
                                                            class="form-control rejected_quantity"
                                                            value="{{ $item->rejected_quantity }}" readonly />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                            <div class="fl-group">
                                                <input type="text" class="form-control" id="chalan_no"
                                                    name="chalan_no" placeholder=" ">
                                                <label for="chalan_no">Chalan No.</label>
                                                @if($errors->has('chalan_no'))<span class="fl-error">{{ $errors->first('chalan_no') }}</span>@endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                            <div class="fl-group">
                                                <input type="text" class="form-control" id="reference_no"
                                                    name="reference_no" placeholder=" ">
                                                <label for="reference_no">Your Reference No.</label>
                                                @if($errors->has('reference_no'))<span class="fl-error">{{ $errors->first('reference_no') }}</span>@endif
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 fl-col">
                                            <div class="fl-group">
                                                <textarea class="form-control" rows="3" id="remark" name="remark"
                                                    placeholder=" "></textarea>
                                                <label for="remark">Remark</label>
                                                @if($errors->has('remark'))<span class="fl-error">{{ $errors->first('remark') }}</span>@endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="login-btn-inner">
                                        <div class="row">
                                            <div class="col-lg-5"></div>
                                            <div class="col-lg-7">
                                                <div class="login-horizental cancel-wp pull-left">
                                                    <a href="{{ route('list-rejected-chalan') }}" class="btn btn-white"
                                                        style="margin-bottom:50px">Cancel</a>
                                                    <button class="btn btn-sm btn-primary login-submit-cs"
                                                        type="submit" style="margin-bottom:50px">Save
                                                        Data</button>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        var i = 0;

        $("#add").click(function() {
            ++i;

            $("#dynamicTable").append(
                '<tr><td><input type="text" name="addmore[' +
                i +
                '][description]" placeholder="Enter Description" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][chalan_quantity]" placeholder="Enter Chalan Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][actual_quantity]" placeholder="Enter Actual Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][accepted_quantity]" placeholder="Enter Accepted Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][rejected_quantity]" placeholder="Enter Rejected Qty" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
            );
        });

        $(document).on("click", ".remove-tr", function() {
            $(this).parents("tr").remove();
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('keyup', '.actual_quantity, .accepted_quantity', function(e) {
                var currentRow = $(this).closest("tr");
                var current_row_actual_quantity = currentRow.find('.actual_quantity').val();
                var current_row_accepted_quantity = currentRow.find('.accepted_quantity').val();
                var new_rejected_quantity = '0';
                if (current_row_actual_quantity != '' && current_row_accepted_quantity != '') {
                    var new_rejected_quantity = current_row_actual_quantity - current_row_accepted_quantity;
                }

                currentRow.find('.rejected_quantity').val(new_rejected_quantity);
            });
        });
    </script>

    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $("#addDesignsForm").validate({
                rules: {
                    grn_date: {
                        required: true,
                    },
                    purchase_orders_id: {
                        required: true,
                    },
                    po_date: {
                        required: true,
                    },
                    invoice_no: {
                        required: true,
                    },
                    invoice_date: {
                        required: true,
                    },
                    remark: {
                        required: true,
                    },
                    image: {
                        required: true,
                        accept: "image/*",
                    },
                    'addmore[0][description]': {
                        required: true,
                    },
                    'addmore[0][chalan_quantity]': {
                        required: true,
                    },
                    'addmore[0][actual_quantity]': {
                        required: true,
                    },
                    'addmore[0][accepted_quantity]': {
                        required: true,
                    },
                    'addmore[0][rejected_quantity]': {
                        required: true,
                    },
                },
                messages: {
                    grn_date: {
                        required: "Please Select GRN Date.",
                    },
                    purchase_orders_id: {
                        required: "Please Enter PO No",
                    },
                    po_date: {
                        required: "Please select PO Date.",
                    },
                    invoice_no: {
                        required: "Please Enter Invoice No.",
                    },
                    invoice_date: {
                        required: "Please Select Invoice Date.",
                    },
                    remark: {
                        required: "Please enter Remark.",
                    },

                    'addmore[0][description]': {
                        required: "Please enter Description.",
                    },
                    'addmore[0][chalan_quantity]': {
                        required: "Please enter Chalan Qty.",
                    },
                    'addmore[0][actual_quantity]': {
                        required: "Please enter Actual Qty.",
                    },
                    'addmore[0][accepted_quantity]': {
                        required: "Please enter Accepted Qty.",
                    },
                    'addmore[0][rejected_quantity]': {
                        required: "Please enter Rejected Qty.",
                    },
                },
            });
        });
    </script>

@endsection
