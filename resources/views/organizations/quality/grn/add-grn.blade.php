@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }
.table-responsive {
    width: 100%;
    overflow-x: auto;
    white-space: nowrap;
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
                            <h1>Add GRN Data</h1>
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
                                            enctype="multipart/form-data" autocomplete="off">
                                            @csrf
                                            <input type="hidden" name="id" id="" class="form-control"
                                                value="{{ $gatepassId->id }}" placeholder="">


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

                                                    {{-- <button type="submit" class="btn btn-success">Save</button> --}}

                                                    {{-- </form> --}}

                                                </div>

                                                {{-- =================== --}}
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="grn_date">GRN Date:</label>
                                                        <input type="date" class="form-control" id="grn_date"
                                                            name="grn_date" placeholder="Enter GRN Date"
                                                            value="{{ date('Y-m-d') }}" readonly>

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
                                                            value="{{ $purchase_order_data->created_at->format('d-m-Y') }}"
                                                            readonly>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="gatepass_name">Customer Name :</label>
                                                        <input type="text" class="form-control" id="gatepass_name"
                                                            name="gatepass_name" placeholder="Enter PO Date"
                                                            value="{{ $gatepassId->gatepass_name }}" readonly>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Bill No. <span class="text-danger">*</span> :</label>
                                                            <input type="text" class="form-control" id="bill_no"
                                                                value="{{ old('bill_no') }}" name="bill_no"
                                                                placeholder="Enter Bill Number">
                                                            @if ($errors->has('bill_no'))
                                                                <span class="red-text"><?php echo $errors->first('bill_no', ':message'); ?></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Bill Date. <span class="text-danger">*</span> : </label>
                                                            <input type="date" class="form-control" id="bill_date"
                                                                value="{{ old('bill_date') }}" name="bill_date"
                                                                placeholder="Enter Bill Number">
                                                            @if ($errors->has('bill_date'))
                                                                <span class="red-text"><?php echo $errors->first('bill_date', ':message'); ?></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="image">Signature <span class="text-danger">*</span>
                                                            :</label>
                                                        <input type="file" class="form-control" accept="image/*"
                                                            id="image" name="image">
                                                    </div>
                                                </div>
                                                <div style="margin-top:20px" class="table-responsive">
                                                    <table class="table table-bordered" id="dynamicTable">
                                                        <tr>
                                                            <th style="width: 60px;">Sr. No.</th>
                                                            <th style="width: 400px;">Description</th>
                                                            <th style="width: 100px;">Part No.</th>
                                                            <th style="width: 100px;">PO Quantity</th>
                                                            <th style="width: 70px;">Unit</th>
                                                            {{-- <th style="width: 100px;">HSN</th> --}}
                                                            <th style="width: 70px;">Rate</th>
                                                            <th style="width: 80px;">Discount</th>
                                                            <th style="width: 100px;">Actual Quantity</th>
                                                            <th style="width: 100px;">Accepted Quantity</th>
                                                            <th style="width: 100px;">Rejected Quantity</th>
                                                            <th style="width: 100px;">Balance Quantity</th>
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                        @foreach ($purchase_order_details_data as $index => $item)
                                                            <tr>
                                                                <input type="hidden"
                                                                    name="addmore[{{ $index }}][edit_id]"
                                                                    placeholder="Enter Description" class="form-control"
                                                                    value="{{ $item->id }}" readonly />
                                                                     <td>{{ $index + 1 }}</td>
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][part_description]"
                                                                        placeholder="Enter Description"
                                                                        class="form-control"
                                                                        value="{{ $item->part_description }}" readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][description]"
                                                                        placeholder="Enter description"
                                                                        class="form-control"
                                                                        value="{{ $item->description }}" readonly />
                                                                </td>

                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][chalan_quantity]"
                                                                        placeholder="Enter Chalan Qty"
                                                                        class="form-control"
                                                                        value="{{ $item->quantity }}" readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][unit_name]"
                                                                        placeholder="Enter" class="form-control unit_name"
                                                                        value="{{ $item->unit_name }}" readonly />
                                                                    {{-- </td>
                                                                <td><input type="text"
                                                                    name="addmore[{{ $index }}][hsn_name]"
                                                                    placeholder="Enter"
                                                                    class="form-control hsn_name" 
                                                                    value="{{ $item->hsn_name }}" readonly />
                                                            </td> --}}
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][rate]"
                                                                        placeholder="Enter" class="form-control rate"
                                                                        value="{{ $item->rate }}" readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][discount]"
                                                                        placeholder="Enter" class="form-control discount"
                                                                        value="{{ $item->discount }}%" readonly />
                                                                </td>


                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][actual_quantity]"
                                                                        placeholder="Enter Actual Qty"
                                                                        class="form-control actual_quantity" />
                                                                </td>



                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][accepted_quantity]"
                                                                        placeholder="Enter Accepted Qty"
                                                                        class="form-control accepted_quantity" />
                                                                </td>

                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][rejected_quantity]"
                                                                        placeholder="Enter Rejected Qty"
                                                                        class="form-control rejected_quantity" readonly />
                                                                </td>

                                                                <td><input type="text"
                                                                        name="addmore[{{ $index }}][remaining_quantity]"
                                                                        placeholder="0"
                                                                        value="{{ $item->remaining_quantity }}"
                                                                        class="form-control remaining_quantity" readonly />
                                                                </td>
                                                                {{-- <td><button type="button" name="add" id="add"
                                                                        class="btn btn-success">Add More</button></td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label for="remark">Remark <span
                                                                class="text-danger">*</span>:</label>
                                                        <textarea class="form-control" rows="3" type="text" class="form-control" id="remark" name="remark"
                                                            placeholder="Enter Remark"></textarea>
                                                    </div>

                                                </div>

                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-grn') }}" class="btn btn-white"
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
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('js/vendor/jquery-1.11.3.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        var i = 0;

        $("#add").click(function() {
            ++i;

            $("#dynamicTable").append(
                '<tr><td><input type="text" name="addmore[' +
                i +
                '][part_description]" placeholder="Enter Description" class="form-control" /></td><td><input type="text" name="addmore[' +
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
    {{-- <script>
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
                    bill_no: {
                        required: true,
                    },
                    bill_date: {
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
                    // 'addmore[0][qc_check_remark]': {
                    //     required: true,
                    // },
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
                    bill_no: {
                        required: "Please Enter Bill No.",
                    },
                    bill_date: {
                        required: "Please select bill Date.",
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
                    image: {
                        required: "Please select Signature .",
                        accept: "Please select an Signature file.",
                    },
                    'addmore[0][description]': {
                        required: "Please enter Description.",
                    },
                    // 'addmore[0][qc_check_remark]': {
                    //     required: "Please enter QC Check.",
                    // },
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
    </script> --}}

<script>
jQuery.noConflict();
jQuery(document).ready(function($) {

    // Initialize validation
    var validator = $("#addDesignsForm").validate({
        ignore: [], // do NOT ignore hidden fields inside table rows
        rules: {
            grn_date: { required: true },
            purchase_orders_id: { required: true },
            po_date: { required: true },
            bill_no: { required: true },
            bill_date: { required: true },
            remark: { required: true },
            image: {
                required: true,
                accept: "image/*"
            }
        },
        messages: {
            grn_date: { required: "Please Select GRN Date." },
            purchase_orders_id: { required: "Please Enter PO No." },
            bill_no: { required: "Please Enter Bill No." },
            bill_date: { required: "Please select Bill Date." },
            po_date: { required: "Please select PO Date." },
            remark: { required: "Please enter Remark." },
            image: {
                required: "Please select Signature.",
                accept: "Only image files are allowed."
            }
        }
    });

    // ============================
    // APPLY VALIDATION TO ALL ROWS
    // ============================

    // For Actual Qty
    $(".actual_quantity").each(function() {
        $(this).rules("add", {
            required: true,
            number: true,
            messages: {
                required: "Please enter Actual Qty.",
                number: "Enter a valid number."
            }
        });
    });

    // For Accepted Qty
    $(".accepted_quantity").each(function() {
        $(this).rules("add", {
            required: true,
            number: true,
            messages: {
                required: "Please enter Accepted Qty.",
                number: "Enter a valid number."
            }
        });
    });

});
</script>




@endsection
