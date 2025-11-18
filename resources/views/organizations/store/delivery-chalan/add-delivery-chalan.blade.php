@extends('admin.layouts.master-add-more')
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

        .custom-dropdown {
            position: relative;
        }

        .custom-dropdown .dropdown-options {
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            width: 615px !important;
        }

        .custom-dropdown .option {
            padding: 5px 10px;
            cursor: pointer;
        }

        .custom-dropdown .option:hover {
            background: #f0f0f0;
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
                                                                        <label for="transport_id">Transport Name
                                                                            (Optional)</label>
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
                                                                        <label for="vehicle_id">Vehicle Type <span
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
                                                                    <label for="vehicle_number">Vehicle Number
                                                                        (Optional)</label>
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
                                                                    <label for="po_date">Delivery Chalan Date <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="date" class="form-control"
                                                                        id="po_date" value="{{ old('po_date') }}"
                                                                        name="po_date"
                                                                        placeholder="Enter Delivery Chalan Date">
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
                                                                                            class="btn btn-sm btn-bg-colour font-18 mr-1"
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
                                                                                    {{-- <td>
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

                                                                                    </td> --}}
                                                                                    <td>
                                                                                        <div class="custom-dropdown"
                                                                                            data-index="0">
                                                                                            <input type="hidden"
                                                                                                name="addmore[0][part_item_id]"
                                                                                                class="part_no"
                                                                                                value="">
                                                                                            <input type="text"
                                                                                                class="dropdown-input form-control part-no"
                                                                                                placeholder="Select Part Item..."
                                                                                                readonly>

                                                                                            <div class="dropdown-options dropdown-height"
                                                                                                style="display: none; position: absolute; background: white; border: 1px solid #ccc; z-index: 1000; width: 100%;">
                                                                                                <input type="text"
                                                                                                    class="search-box form-control"
                                                                                                    placeholder="Search..."
                                                                                                    style="margin-bottom: 5px;">
                                                                                                <div class="options-list"
                                                                                                    style="max-height: 200px; overflow-y: auto;">
                                                                                                    @foreach ($dataOutputPartItem as $data)
                                                                                                        <div class="option"
                                                                                                            data-id="{{ $data['id'] }}">
                                                                                                            {{ $data['description'] }}
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
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


                                                        <div class="col-lg-12 mb-4"
                                                            style="display: flex; justify-content: center;">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-delivery-chalan') }}"
                                                                    class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary btn-bg-colour"
                                                                    type="submit" style="margin-bottom:50px">Save
                                                                    Data</button>
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
        <script>
            $(document).ready(function() {

                /* -----------------------------------------
                   1. Custom Dropdown with Search
                ----------------------------------------- */
                $(document).on('click', '.dropdown-input', function() {
                    $('.dropdown-options').hide(); // close other dropdowns
                    $(this).siblings('.dropdown-options').toggle();
                    $(this).siblings('.dropdown-options').find('.search-box').val('').trigger(
                        'keyup'); // reset search
                });

                $(document).on('keyup', '.search-box', function() {
                    var searchValue = $(this).val().toLowerCase();
                    var optionsList = $(this).siblings('.options-list').find('.option');
                    optionsList.each(function() {
                        var text = $(this).text().toLowerCase();
                        $(this).toggle(text.indexOf(searchValue) > -1);
                    });
                });

                $(document).on('click', '.option', function() {
                    var selectedText = $(this).text();
                    var selectedId = $(this).data('id');
                    var dropdown = $(this).closest('.custom-dropdown');
                    var currentRow = $(this).closest('tr');

                    // Set value to hidden input & visible input
                    dropdown.find('.part_no').val(selectedId);
                    dropdown.find('.dropdown-input').val(selectedText);
                    dropdown.find('.dropdown-options').hide();

                    // Fetch HSN after selection
                    fetchHSN(selectedId, currentRow);
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.custom-dropdown').length) {
                        $('.dropdown-options').hide();
                    }
                });

                /* -----------------------------------------
                   2. Normal <select> dropdown change event
                ----------------------------------------- */
                $(document).on('change', '.part_item_id', function() {
                    var partNoId = $(this).val();
                    var currentRow = $(this).closest('tr');

                    // Store in hidden input (for stock check)
                    currentRow.find('.part_no').val(partNoId);

                    fetchHSN(partNoId, currentRow);
                });

                /* -----------------------------------------
                   3. Fetch HSN Function
                ----------------------------------------- */
                function fetchHSN(partNoId, row) {
                    if (!partNoId) return;

                    $.ajax({
                        url: '{{ route('get-hsn-for-part') }}',
                        type: 'GET',
                        data: {
                            part_no_id: partNoId
                        },
                        success: function(response) {
                            if (response.part && response.part.length > 0) {
                                var hsnName = response.part[0].name;
                                var hsnId = response.part[0].id;
                                row.find('.hsn_name').val(hsnName);
                                row.find('.hsn_id').val(hsnId);
                            } else {
                                row.find('.hsn_name').val('');
                                row.find('.hsn_id').val('');
                                Swal.fire("HSN not found for selected part.");
                            }
                        },
                        error: function() {
                            Swal.fire("Error fetching HSN. Please try again.");
                        }
                    });
                }

                // Initialize jQuery Validation
                var validator = $("#addEmployeeForm").validate({
                    ignore: [], // Validate hidden inputs as well
                    rules: {
                        vendor_id: {
                            required: true
                        },
                        // transport_id: {
                        //     required: true
                        // },
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
                        // vehicle_number: {
                        //     required: true
                        // },
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
                        // transport_id: {
                        //     required: "Select transport name."
                        // },
                        vehicle_id: {
                            required: "Select vehicle type."
                        },
                        tax_type: {
                            required: "Select tax type"
                        },
                        tax_id: {
                            required: "Select tax name."
                        },
                        // vehicle_number: {
                        //     required: "Enter vehicle number."
                        // },
                        plant_id: {
                            required: "Enter plant name."
                        },
                        po_date: {
                            required: "Please select Delivery Chalan Date.",
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
                /* -----------------------------------------
                   4. Stock Check Function
                ----------------------------------------- */
                function checkStock($row) {
                    const quantity = $row.find('.quantity').val();
                    const partItemId = $row.find('.part_no').val();
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
                                    stockAvailableMessage
                                        .text('Insufficient stock. Available: ' + response
                                            .available_quantity)
                                        .css('color', 'red');
                                } else {
                                    stockAvailableMessage
                                        .text('Stock is sufficient')
                                        .css('color', 'green');
                                }
                            },
                            error: function() {
                                stockAvailableMessage
                                    .text('Error checking stock')
                                    .css('color', 'red');
                            }
                        });
                    } else {
                        stockAvailableMessage.text('');
                    }
                }

                /* -----------------------------------------
                   5. Quantity/Rate Change
                ----------------------------------------- */
                $(document).on('keyup', '.quantity, .rate', function() {
                    var $row = $(this).closest('tr');
                    var quantity = parseFloat($row.find('.quantity').val()) || 0;
                    var rate = parseFloat($row.find('.rate').val()) || 0;
                    var totalAmount = (quantity * rate).toFixed(2);

                    $row.find('.total_amount').val(totalAmount);

                    // Stock check after amount calculation
                    checkStock($row);
                });

                /* -----------------------------------------
                   6. Add More Rows Button
                ----------------------------------------- */
                $("#add_more_btn").click(function() {
                    var i_count = $('#i_id').val();
                    var i = parseInt(i_count) + 1;
                    $('#i_id').val(i);

                    var newRow = `
            <tr>
                <td>${i}</td>
                <td>
                    <!-- Custom dropdown example -->
                    <div class="custom-dropdown">
                        <input type="text" class="form-control dropdown-input" placeholder="Search Part Item">
                        <input type="hidden" name="addmore[${i}][part_item_id]" class="part_no">
                        <div class="dropdown-options" style="display:none;">
                            <input type="text" class="form-control search-box" placeholder="Search...">
                            <div class="options-list" style="max-height:150px; overflow:auto;">
                                @foreach ($dataOutputPartItem as $data)
                                    <div class="option" data-id="{{ $data['id'] }}">{{ $data['description'] }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <input class="form-control hsn_name" type="text" disabled>
                    <input type="hidden" class="form-control hsn_id" name="addmore[${i}][hsn_id]">
                </td>
                <td>
                    <select class="form-control unit_id" name="addmore[${i}][unit_id]">
                        <option value="">Select Unit</option>
                        @foreach ($dataOutputUnitMaster as $data)
                            <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control process_id" name="addmore[${i}][process_id]">
                        <option value="">Select Process</option>
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
                    <input class="form-control size" name="addmore[${i}][size]" type="text">
                </td>
                <td>
                    <input class="form-control total_amount" name="addmore[${i}][amount]" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove_row" title="Delete" data-repeater-delete>
    <i class="fa fa-trash"></i>
</button>
                </td>
            </tr>`;

                    $('#purchase_order_table tbody').append(newRow);

                    // Apply validation rules to new row
                    // Apply validation rules to new row (target actual elements)
                    $(`input[name='addmore[${i}][part_item_id]']`).rules("add", {
                        required: true,
                        maxlength: 100,
                        messages: {
                            required: "Please enter the Product Name."
                        }
                    });

                    $(`select[name='addmore[${i}][unit_id]']`).rules("add", {
                        required: true,
                        maxlength: 255,
                        messages: {
                            required: "Please enter the unit_id."
                        }
                    });

                    $(`select[name='addmore[${i}][process_id]']`).rules("add", {
                        required: true,
                        maxlength: 255,
                        messages: {
                            required: "Please select the process."
                        }
                    });

                    $(`input[name='addmore[${i}][size]']`).rules("add", {
                        required: true,
                        maxlength: 255,
                        messages: {
                            required: "Please enter the size."
                        }
                    });

                    $(`input[name='addmore[${i}][quantity]']`).rules("add", {
                        required: true,
                        digits: true,
                        min: 1,
                        messages: {
                            required: "Please enter the Quantity.",
                            digits: "Please enter only digits.",
                            min: "Quantity must be at least 1."
                        }
                    });

                    $(`input[name='addmore[${i}][amount]']`).rules("add", {
                        required: true,
                        messages: {
                            required: "Please Enter the Amount."
                        }
                    });
                });

                /* -----------------------------------------
                   7. Remove Row
                ----------------------------------------- */
                $(document).on('click', '.remove_row', function() {
                    $(this).closest('tr').remove();
                });

            });
        </script>
    @endsection
