@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 10px;
        }

        label.error {
            color: red;
            font-size: 12px;
        }

        .form-display-center {
            display: flex !important;
            justify-content: center !important;
            align-items: center;
        }

        .red-text {
            color: red;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <center>
                                <h1>Add Delivery Challan</h1>
                            </center>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if (Session::get('status') == 'success')
                                        <div class="col-md-12">
                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <strong>Success!</strong> {{ Session::get('msg') }}
                                            </div>
                                        </div>
                                    @endif

                                    @if (Session::get('status') == 'error')
                                        <div class="col-md-12">
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <strong>Error!</strong> {!! session('msg') !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="all-form-element-inner">
                                            <div class="row d-flex justify-content-center form-display-center">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                    <form action="{{ route('store-delivery-chalan') }}" method="POST"
                                                        id="addEmployeeForm" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group-inner">
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="vendor_id">Vendor Company Name <span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-control mb-2" name="vendor_id"
                                                                            id="vendor_id">
                                                                            <option value="" default>Vendor Company
                                                                                Name</option>
                                                                            @foreach ($dataOutputVendor as $data)
                                                                                <option value="{{ $data['id'] }}">
                                                                                    {{ $data['vendor_company_name'] }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="business_id">PO Number
                                                                            (Optional)</label>
                                                                        <select class="form-control mb-2" name="business_id"
                                                                            id="business_id">
                                                                            <option value="" default>Select PO Number
                                                                            </option>
                                                                            @foreach ($dataOutputBusiness as $data)
                                                                                <option value="{{ $data['id'] }}">
                                                                                    {{ $data['customer_po_number'] }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="customer_po_no">Customer PO Number
                                                                            (optional)</label>
                                                                        <input type="text" class="form-control"
                                                                            id="customer_po_no"
                                                                            value="{{ old('customer_po_no') }}"
                                                                            name="customer_po_no"
                                                                            placeholder="Enter Customer PO Number">
                                                                        @if ($errors->has('customer_po_no'))
                                                                            <span
                                                                                class="red-text"><?php echo $errors->first('customer_po_no', ':message'); ?></span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="transport_id">Transport Name<span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-control mb-2"
                                                                            name="transport_id" id="transport_id">
                                                                            <option value="" default>Select Transport
                                                                                Name</option>
                                                                            @foreach ($dataOutputTransportName as $data)
                                                                                <option value="{{ $data['id'] }}">
                                                                                    {{ $data['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="vehicle_id">Vehicle Type<span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-control mb-2" name="vehicle_id"
                                                                            id="vehicle_id">
                                                                            <option value="" default>Select Vehicle
                                                                                Type</option>
                                                                            @foreach ($dataOutputVehicleType as $data)
                                                                                <option value="{{ $data['id'] }}">
                                                                                    {{ $data['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <label for="plant_id">Plant Name <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="plant_id" value="{{ old('plant_id') }}"
                                                                        name="plant_id" placeholder="Enter Plant Name">
                                                                    @if ($errors->has('plant_id'))
                                                                        <span class="red-text"><?php echo $errors->first('plant_id', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Tax Type<span
                                                                                class="text-danger">*</span></label>
                                                                        <select name="tax_type" class="form-control"
                                                                            title="select tax" id="tax_type">
                                                                            <option value="">Select Tax Type</option>
                                                                            <option value="GST">GST</option>
                                                                            <option value="SGST">SGST</option>
                                                                            <option value="CGST">CGST</option>
                                                                            <option value="SGST+CGST">SGST+CGST</option>
                                                                            <option value="IGST">IGST</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="tax_id">Tax<span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-control mb-2" name="tax_id"
                                                                            id="tax_id">
                                                                            <option value="" default>Tax</option>
                                                                            @foreach ($dataOutputTax as $data)
                                                                                <option value="{{ $data['id'] }}">
                                                                                    {{ $data['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <label for="vehicle_number">Vehicle Number <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="vehicle_number"
                                                                        value="{{ old('vehicle_number') }}"
                                                                        name="vehicle_number"
                                                                        placeholder="Enter Vehicle Number">
                                                                    @if ($errors->has('vehicle_number'))
                                                                        <span class="red-text"><?php echo $errors->first('vehicle_number', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <label for="po_date">PO Date <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="date" class="form-control"
                                                                        id="po_date" value="{{ old('po_date') }}"
                                                                        name="po_date" placeholder="Enter PO Date">
                                                                    @if ($errors->has('po_date'))
                                                                        <span class="red-text"><?php echo $errors->first('po_date', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <label for="lr_number">LR Number (Optional)</label>
                                                                    <input type="text" class="form-control"
                                                                        id="lr_number" value="{{ old('lr_number') }}"
                                                                        name="lr_number" placeholder="Enter LR Number">
                                                                    @if ($errors->has('lr_number'))
                                                                        <span class="red-text"><?php echo $errors->first('lr_number', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12">
                                                                    <div class="table-responsive">
                                                                        <table
                                                                            class="table table-hover table-white repeater"
                                                                            id="purchase_order_table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th class="col-sm-2">Product Name
                                                                                    </th>
                                                                                    <th class="col-md-1">HSN</th>
                                                                                    <th class="col-md-1">Unit</th>
                                                                                    <th class="col-md-2">process</th>
                                                                                    <th class="col-md-2">Quantity</th>
                                                                                    <th class="col-md-1">Rate</th>
                                                                                    <th class="col-md-1">Size</th>
                                                                                    <th class="col-md-1">Amount</th>
                                                                                    <th>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-success font-18 mr-1"
                                                                                            id="add_more_btn"
                                                                                            title="Add"
                                                                                            data-repeater-create>
                                                                                            <i class="fa fa-plus"></i>
                                                                                        </button>
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            name="id"
                                                                                            class="form-control"
                                                                                            style="min-width:50px" readonly
                                                                                            value="1">
                                                                                        <input type="hidden"
                                                                                            id="i_id"
                                                                                            class="form-control"
                                                                                            style="min-width:50px" readonly
                                                                                            value="0">
                                                                                    </td>
                                                                                    <td>
                                                                                        <select
                                                                                            class="form-control part_item_id mb-2"
                                                                                            name="addmore[0][part_item_id]"
                                                                                            id="part_item_id_0">
                                                                                            <option value="" default>
                                                                                                Select Part Item
                                                                                            </option>
                                                                                            @foreach ($dataOutputPartItem as $data)
                                                                                                <option
                                                                                                    value="{{ $data['id'] }}">
                                                                                                    {{ $data['description'] }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>

                                                                                        {{-- <input class="form-control part_item_id" name="addmore[0][part_item_id]" type="text" style="min-width:150px"> --}}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input
                                                                                            class="form-control hsn_name"
                                                                                            name="addmore[0][hsn_id]"
                                                                                            type="text"
                                                                                            style="min-width:100px"
                                                                                            disabled>

                                                                                        <input type="hidden"
                                                                                            class="form-control hsn_id"
                                                                                            name="addmore[0][hsn_id]"
                                                                                            type="text"
                                                                                            style="min-width:100px">
                                                                                        {{-- <select class="form-control mb-2" name="addmore[0][hsn_id]"
                                                                                            id="" style="min-width:100px">
                                                                                            <option value="" default>Select HSN</option>
                                                                                            @foreach ($dataOutputHSNMaster as $data)
                                                                                                <option value="{{ $data['id'] }}">
                                                                                                    {{ $data['name'] }}</option>
                                                                                            @endforeach
                                                                                        </select> --}}
                                                                                        {{-- <input class="form-control quantity" name="addmore[0][quantity]" type="text"> --}}
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="form-control mb-2"
                                                                                            name="addmore[0][unit_id]"
                                                                                            id="">
                                                                                            <option value="" default>
                                                                                                Select Unit
                                                                                            </option>
                                                                                            @foreach ($dataOutputUnitMaster as $data)
                                                                                                <option
                                                                                                    value="{{ $data['id'] }}">
                                                                                                    {{ $data['name'] }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        {{-- <input class="form-control description" name="addmore[0][description]" type="text" style="min-width:150px"> --}}
                                                                                    </td>
                                                                                    {{-- <td>
                                                                                        <select class="form-control mb-2"
                                                                                            name="addmore[0][hsn_id]"
                                                                                            id="">
                                                                                            <option value="" default>
                                                                                                Select HSN
                                                                                            </option>
                                                                                            @foreach ($dataOutputHSNMaster as $data)
                                                                                                <option
                                                                                                    value="{{ $data['id'] }}">
                                                                                                    {{ $data['name'] }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </td> --}}
                                                                                    {{-- <td>
                                                                                <input class="form-control unit" name="addmore[0][unit]"  type="text">
                                                                            </td> --}}
                                                                                    <td>
                                                                                        <select class="form-control mb-2"
                                                                                            name="addmore[0][process_id]"
                                                                                            id="">
                                                                                            <option value="" default>
                                                                                                Select Process
                                                                                            </option>
                                                                                            @foreach ($dataOutputProcessMaster as $data)
                                                                                                <option
                                                                                                    value="{{ $data['id'] }}">
                                                                                                    {{ $data['name'] }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        {{-- <input class="form-control rate" name="addmore[0][rate]" type="text"> --}}
                                                                                    </td>
                                                                                    <td><input
                                                                                            class="form-control quantity"
                                                                                            name="addmore[0][quantity]"
                                                                                            type="text">
                                                                                        <span
                                                                                            class="stock-available"></span>
                                                                                    </td>
                                                                                    <td><input class="form-control rate"
                                                                                            name="addmore[0][rate]"
                                                                                            type="text"> </td>
                                                                                    <td><input class="form-control rate"
                                                                                            name="addmore[0][size]"
                                                                                            type="text"> </td>
                                                                                    <td>
                                                                                        <input
                                                                                            class="form-control total_amount"
                                                                                            name="addmore[0][amount]"
                                                                                            readonly style="width:120px"
                                                                                            type="text">
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-danger font-18 ml-2 remove-row"
                                                                                            title="Delete"
                                                                                            data-repeater-delete>
                                                                                            <i class="fa fa-trash"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="sparkline12-graph">
                                                                    <div id="pwd-container1">
                                                                        <div class="form-group">
                                                                            <label for="remark">Remark <span
                                                                                    class="text-danger">*</span></label>
                                                                            <textarea class="form-control" rows="3" type="text" class="form-control" id="remark" name="remark"
                                                                                placeholder="Enter Remark">{{ old('remark') }}</textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="pwstrength_viewport_progress">
                                                                                </span></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="login-btn-inner">
                                                            <div class="row">
                                                                <div class="col-lg-12"
                                                                    style="display: flex; justify-content: center;">
                                                                    <div class="login-horizental cancel-wp pull-left">
                                                                        <a href="{{ route('list-delivery-chalan') }}"
                                                                            class="btn btn-white"
                                                                            style="margin-bottom:50px">Cancel</a>
                                                                        <button
                                                                            class="btn btn-sm btn-primary login-submit-cs"
                                                                            type="submit" style="margin-bottom:50px">Save
                                                                            Data</button>
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
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

        <script>
            $(document).ready(function() {
                // Function to set the minimum date for PO date
                function setMinDate() {
                    var today = new Date();
                    var day = String(today.getDate()).padStart(2, '0');
                    var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
                    var year = today.getFullYear();
                    var todayDate = year + '-' + month + '-' + day;
                    $('#po_date').attr('min', todayDate);
                }
                setMinDate();

                // Function to set the minimum date for DC date
                function setMinDate11() {
                    var today = new Date();
                    var day = String(today.getDate()).padStart(2, '0');
                    var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
                    var year = today.getFullYear();
                    var todayDate = year + '-' + month + '-' + day;
                    $('#dc_date').attr('min', todayDate);
                }
                setMinDate11();

                // Initialize jQuery Validation
                var validator = $("#addEmployeeForm").validate({
                    ignore: [], // Validate hidden inputs as well
                    rules: {
                        vendor_id: {
                            required: true
                        },
                        transport_id: {
                            required: true
                        },
                        vehicle_id: {
                            required: true
                        },
                        tax_type: {
                            required: true
                        },
                        tax_id: {
                            required: true
                        },
                        plant_id: {
                            required: true
                        },
                        vehicle_number: {
                            required: true
                        },
                        po_date: {
                            required: true,
                        },
                        'addmore[0][part_item_id]': {
                            required: true,
                            maxlength: 100
                        },
                        'addmore[0][unit_id]': {
                            required: true,
                            maxlength: 255
                        },
                        // 'addmore[0][hsn_id]': {
                        //     required: true,
                        //     maxlength: 255
                        // },
                        'addmore[0][process_id]': {
                            required: true,
                            maxlength: 255
                        },
                        'addmore[0][size]': {
                            required: true,
                            maxlength: 255
                        },
                        'addmore[0][quantity]': {
                            required: true,
                            digits: true,
                            min: 1
                        },
                        'addmore[0][amount]': {
                            required: true,
                        }
                    },
                    messages: {
                        vendor_id: {
                            required: "Select vendor name."
                        },
                        transport_id: {
                            required: "Select transport name."
                        },
                        vehicle_id: {
                            required: "Select vehicle type."
                        },
                        tax_type: {
                            required: "Select tax type"
                        },
                        tax_id: {
                            required: "Select tax name."
                        },
                        vehicle_number: {
                            required: "Enter vehicle number."
                        },
                        plant_id: {
                            required: "Enter plant name."
                        },
                        po_date: {
                            required: "Please select PO date.",
                            date: "Please select a valid date."
                        },
                        'addmore[0][part_item_id]': {
                            required: "Please enter the Product Name.",
                            maxlength: "Product Name must be at most 100 characters long."
                        },
                        'addmore[0][unit_id]': {
                            required: "Please enter the unit_id.",
                            maxlength: "unit_id must be at most 255 characters long."
                        },
                        // 'addmore[0][hsn_id]': {
                        //     required: "Please enter the hsn_id.",
                        //     maxlength: "hsn_id must be at most 255 characters long."
                        // },
                        'addmore[0][process_id]': {
                            required: "Please select the process."
                        },
                        'addmore[0][size]': {
                            required: "Please enter the size."
                        },
                        'addmore[0][quantity]': {
                            required: "Please enter the Quantity.",
                            digits: "Please enter only digits for Quantity.",
                            min: "Quantity must be at least 1."
                        },
                        'addmore[0][amount]': {
                            required: "Please Enter the Amount"
                        },
                    },
                    errorPlacement: function(error, element) {
                        error.addClass('text-danger');
                        if (element.closest('.form-group').length) {
                            element.closest('.form-group').append(error);
                        } else if (element.closest('td').length) {
                            element.closest('td').append(error);
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                // Function to check stock availability
                function checkStock($row) {
                    const quantity = $row.find('.quantity').val();
                    const partItemId = $row.find('select[name*="part_item_id"]').val();
                    const stockAvailableMessage = $row.find('.stock-available');

                    if (partItemId && quantity) {
                        $.ajax({
                            url: '{{ route('check-stock-quantity') }}',
                            type: 'GET',
                            data: {
                                part_item_id: partItemId,
                                quantity: quantity
                            },
                            success: function(response) {
                                if (response.status === 'error') {
                                    stockAvailableMessage.text('Insufficient stock. Available: ' + response
                                            .available_quantity)
                                        .css('color', 'red');
                                } else {
                                    stockAvailableMessage.text('Stock is sufficient').css('color', 'green');
                                }
                            },
                            error: function() {
                                stockAvailableMessage.text('Error checking stock').css('color', 'red');
                            }
                        });
                    } else {
                        stockAvailableMessage.text('');
                    }
                }

                // Add more rows when the "Add More" button is clicked
                $("#add_more_btn").click(function() {
                    var i_count = $('#i_id').val();
                    var i = parseInt(i_count) + 1;
                    $('#i_id').val(i);

                    if (i_count === "0") {
                        i = 2;
                    }

                    $('#i_id').val(i);
                    var newRow = `
                <tr>
                    <td>
                        <input type="text" name="id" class="form-control" style="min-width:50px" readonly value="${i}">
                    </td>
                    <td>
                        <select class="form-control part_item_id mb-2" name="addmore[${i}][part_item_id]">
                            <option value="" default>Select Part Item</option>
                            @foreach ($dataOutputPartItem as $data)
                                <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control hsn_name" type="text" style="min-width:150px" disabled>
                        <input type="hidden" class="form-control hsn_id" name="addmore[${i}][hsn_id]" type="text" style="min-width:150px">
                    </td>
                    <td>
                        <select class="form-control mb-2 unit_id" name="addmore[${i}][unit_id]">
                            <option value="" default>Select Unit</option>
                            @foreach ($dataOutputUnitMaster as $data)
                                <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control mb-2 process_id" name="addmore[${i}][process_id]">
                            <option value="" default>Select Process</option>
                            @foreach ($dataOutputProcessMaster as $data)
                                <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control quantity" name="addmore[${i}][quantity]" type="text">
                        <span class="stock-available"></span>
                    </td>
                    <td>
                        <input class="form-control rate" name="addmore[${i}][rate]" type="text">
                    </td>
                    <td>
                        <input class="form-control size" name="addmore[${i}][size]" type="text" required>
                    </td>
                    <td>
                        <input class="form-control total_amount" name="addmore[${i}][amount]" readonly style="width:120px" type="text" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove_row"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

                    $('#purchase_order_table tbody').append(newRow);
                    initializeValidation($('#purchase_order_table tbody tr:last-child'));
                    validator.resetForm(); // Reset the validation errors
                });

                // Remove a row when the "Remove" button is clicked
                $(document).on('click', '.remove_row', function() {
                    $(this).closest('tr').remove();
                    validator.resetForm(); // Reset the validation errors
                });

                // Recalculate the total amount when quantity or rate changes
               // Recalculate the total amount when quantity or rate changes
$(document).on('keyup', '.quantity, .rate', function() {
    var $row = $(this).closest('tr');
    var quantity = parseFloat($row.find('.quantity').val()) || 0;
    var rate = parseFloat($row.find('.rate').val()) || 0;
    var totalAmount = (quantity * rate).toFixed(2);
    $row.find('.total_amount').val(totalAmount);
    checkStock($row); // Check stock after calculating total
});


                // Initialize validation for the newly added row
                function initializeValidation(row) {
                    row.find('.part_item_id, .unit_id, .process_id, .quantity, .rate').each(function() {
                        $(this).rules('add', {
                            required: true
                        });
                    });
                }
            });
        </script>

        <script>
            $(document).ready(function() {
                // Bind the select2:select event
                $(document).on('change', '.part_item_id', function(e) {
                    var partNoId = $(this).val(); // Get the selected part_no_id
                    var currentRow = $(this).closest('tr'); // Get the current row

                    // Check if partNoId has value
                    if (partNoId) {
                        console.log("Selected partNoId: ", partNoId); // Debugging the selected ID

                        // Make an AJAX request to fetch the HSN based on the part_no_id
                        $.ajax({
                            url: '{{ route('get-hsn-for-part') }}', // Ensure this route is correct in your routes file
                            type: 'GET',
                            data: {
                                part_no_id: partNoId
                            }, // Pass the part_no_id as a query parameter
                            success: function(response) {
                                console.log("HSN response:",
                                    response); // Debug the response

                                if (response.part && response.part.length > 0) {
                                    var hsnName = response.part[0].name;
                                    var hsnId = response.part[0].id;

                                    // Update the HSN inputs for the current row only
                                    currentRow.find('.hsn_name').val(
                                        hsnName); // Set HSN name
                                    currentRow.find('.hsn_id').val(hsnId); // Set HSN ID
                                } else {
                                    alert("HSN not found for the selected part.");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("AJAX Error: ", status, error);
                                alert("Error fetching HSN. Please try again.");
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
