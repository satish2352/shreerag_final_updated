@extends('admin.layouts.master')
@section('content')
    <style>
        a {
            color: black;
        }

        a:hover {
            color: black;
        }

        label {
            margin-top: 10px;
        }

        label.error {
            color: red;
            /* Change 'red' to your desired text color */
            font-size: 12px;
            /* Adjust font size if needed */
            /* Add any other styling as per your design */
        }

        .red-text {
            color: red;
        }

        .readonly-select {
            pointer-events: none;
            opacity: 0.6;
            /* Looks visually "readonly" */
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <center>
                                <h1>Edit Delivery Challan Data</h1>
                            </center>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="all-form-element-inner">
                                            <form
                                                action="{{ route('update-delivery-chalan', $editData[0]->purchase_main_id) }}"
                                                method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id" id="id"
                                                    value="{{ $editData[0]->id }}">
                                                <input type="hidden" name="purchase_main_id" id=""
                                                    class="form-control" value="{{ $editData[0]->purchase_main_id }}"
                                                    placeholder="">
                                                <a {{-- href="{{ route('add-more-data') }}" --}}>
                                                    <div class="container-fluid">
                                                        <!-- @if ($errors->any())
    <div class="alert alert-danger">
                                                                                <ul>
                                                                                    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
                                                                                </ul>
                                                                            </div>
    @endif -->



                                                        @foreach ($editData as $key => $editDataNew)
                                                            @if ($key == 0)
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="Service">Vendor Company
                                                                                Name: <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-control mb-2"
                                                                                name="vendor_id" id="vendor_id">
                                                                                <option value="" default>Select
                                                                                    Vendor Company Name</option>
                                                                                @foreach ($dataOutputVendor as $service)
                                                                                    <option value="{{ $service['id'] }}"
                                                                                        {{ old('vendor_id', $editDataNew->vendor_id) == $service->id ? 'selected' : '' }}>
                                                                                        {{ $service->vendor_company_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('vendor_id'))
                                                                                <span
                                                                                    class="red-text">{{ $errors->first('vendor_id') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="business_id">PO Number
                                                                                (Optional)
                                                                            </label>
                                                                            <select class="form-control mb-2"
                                                                                name="business_id" id="business_id">
                                                                                <option value="" default>Select PO
                                                                                    Number</option>
                                                                                @foreach ($dataOutputBusiness as $OutputBusiness)
                                                                                    <option
                                                                                        value="{{ $OutputBusiness['id'] }}"
                                                                                        {{ old('business_id', $editDataNew->business_id) == $OutputBusiness->id ? 'selected' : '' }}>
                                                                                        {{ $OutputBusiness->customer_po_number }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Customer PO Number (optional) <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="cal-icon">
                                                                                <input class="form-control datetimepicker"
                                                                                    type="text" name="customer_po_no"
                                                                                    id="customer_po_no"
                                                                                    value="{{ $editDataNew->customer_po_no }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="transport_id">Transport Name <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-control" id="transport_id"
                                                                                name="transport_id"
                                                                                onchange="myFunction(this.value)">
                                                                                <option value="">Select Transport Name
                                                                                </option>
                                                                                @foreach ($dataOutputTransportName as $role)
                                                                                    <option value="{{ $role['id'] }}"
                                                                                        {{ old('transport_id', $editDataNew->transport_id) == $role->id ? 'selected' : '' }}>
                                                                                        {{ $role->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('transport_id'))
                                                                                <span
                                                                                    class="red-text"><?php echo $errors->first('transport_id', ':message'); ?></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="vehicle_id">Vehicle Type <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-control" id="vehicle_id"
                                                                                name="vehicle_id"
                                                                                onchange="myFunction(this.value)">
                                                                                <option value="">Select Vehicle Type
                                                                                </option>
                                                                                @foreach ($dataOutputVehicleType as $vehicleType)
                                                                                    <option
                                                                                        value="{{ $vehicleType['id'] }}"
                                                                                        {{ old('vehicle_id', $editDataNew->vehicle_id) == $vehicleType->id ? 'selected' : '' }}>
                                                                                        {{ $vehicleType->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('vehicle_id'))
                                                                                <span
                                                                                    class="red-text"><?php echo $errors->first('vehicle_id', ':message'); ?></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Plant Name <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="cal-icon">
                                                                                <input class="form-control datetimepicker"
                                                                                    type="text" name="plant_id"
                                                                                    id="plant_id"
                                                                                    value="{{ $editDataNew->plant_id }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Tax Type <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-control mb-2"
                                                                                name="tax_type" id="tax_type">
                                                                                <option value=""
                                                                                    {{ old('tax_type') == '' ? 'selected' : '' }}>
                                                                                    Select Tax Type</option>
                                                                                <option value="GST"
                                                                                    {{ old('tax_type', $editDataNew->tax_type) == 'GST' ? 'selected' : '' }}>
                                                                                    GST</option>
                                                                                <option value="SGST"
                                                                                    {{ old('tax_type', $editDataNew->tax_type) == 'SGST' ? 'selected' : '' }}>
                                                                                    SGST</option>
                                                                                <option value="CGST"
                                                                                    {{ old('tax_type', $editDataNew->tax_type) == 'CGST' ? 'selected' : '' }}>
                                                                                    CGST</option>
                                                                                <option value="SGST+CGST"
                                                                                    {{ old('tax_type', $editDataNew->tax_type) == 'SGST+CGST' ? 'selected' : '' }}>
                                                                                    SGST+CGST</option>
                                                                                <option value="IGST"
                                                                                    {{ old('tax_type', $editDataNew->tax_type) == 'IGST' ? 'selected' : '' }}>
                                                                                    IGST</option>
                                                                            </select>
                                                                            @if ($errors->has('tax_type'))
                                                                                <span
                                                                                    class="red-text">{{ $errors->first('tax_type') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="Service">Tax : <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-control mb-2"
                                                                                name="tax_id" id="tax_id">
                                                                                <option value="" default>Select
                                                                                    Item Part</option>
                                                                                @foreach ($dataOutputTax as $taxData)
                                                                                    <option value="{{ $taxData['id'] }}"
                                                                                        {{ old('tax_id', $editDataNew->tax_id) == $taxData->id ? 'selected' : '' }}>
                                                                                        {{ $taxData->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('tax_id'))
                                                                                <span
                                                                                    class="red-text">{{ $errors->first('tax_id') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Vehicle Number <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="cal-icon">
                                                                                <input class="form-control datetimepicker"
                                                                                    type="text" name="vehicle_number"
                                                                                    id="vehicle_number"
                                                                                    value="{{ $editDataNew->vehicle_number }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>PO Date <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="cal-icon">
                                                                                <input class="form-control datetimepicker"
                                                                                    type="text" name="po_date"
                                                                                    id="po_date"
                                                                                    value="{{ $editDataNew->po_date }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label> LR Number (Optional)<span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="cal-icon">
                                                                                <input class="form-control datetimepicker"
                                                                                    type="text" name="vehicle_number"
                                                                                    id="vehicle_number"
                                                                                    value="{{ $editDataNew->vehicle_number }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        {{-- <button type="button" name="add" id="add"
                                                            class="btn btn-success">Add More</button> --}}
                                                        <div style="margin-top:10px;">
                                                            <table class="table table-bordered" id="dynamicTable">
                                                                <tr>
                                                                    <th style="width:200px">Part No</th>
                                                                    <th style="width:100px">HSN</th>
                                                                    <th style="width:100px">Process</th>
                                                                    <th style="width:100px">Quantity</th>
                                                                    <th style="width:100px">Unit</th>
                                                                    <th>Rate</th>
                                                                    <th>Size</th>
                                                                    <th>Amount</th>
                                                                    <th>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-success font-18 mr-1"
                                                                            id="add" title="Add" name="add"
                                                                            data-repeater-create>
                                                                            <i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                                @foreach ($editData as $key => $editDataNew)
                                                                    <tr>
                                                                        <input type="hidden" name="design_count"
                                                                            id="design_id_{{ $key }}"
                                                                            class="form-control"
                                                                            value="{{ $key }}" placeholder="">

                                                                        <input type="hidden"
                                                                            name="design_id_{{ $key }}"
                                                                            id="design_id_{{ $key }}"
                                                                            class="form-control"
                                                                            value="{{ $editDataNew->tbl_delivery_chalan_item_details_id }}"
                                                                            placeholder="">
                                                                        <td>
                                                                            <select
                                                                                class="form-control process_id mb-2 readonly-select"
                                                                                name="part_item_id_{{ $key }}"
                                                                                id="part_item_id_{{ $key }}"
                                                                                onchange="updateHiddenInput(this)">
                                                                                <option value="" selected>Select Item
                                                                                </option>
                                                                                @foreach ($dataOutputPartItem as $data)
                                                                                    <option value="{{ $data['id'] }}"
                                                                                        {{ old('part_item_id', $editDataNew->part_item_id) == $data->id ? 'selected' : '' }}>
                                                                                        {{ $data->description }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <input type="hidden"
                                                                                name="hidden_process_id_{{ $key }}"
                                                                                id="hidden_process_id_{{ $key }}"
                                                                                value="{{ old('process_id', $editDataNew->process_id) }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                name="hsn_id_{{ $key }}"
                                                                                value="{{ $editDataNew->hsn_name }}"
                                                                                placeholder="Enter hsn_id"
                                                                                class="form-control hsn_name"
                                                                                style="min-width:100px" disabled>

                                                                            <input type="hidden"
                                                                                name="hsn_id_{{ $key }}"
                                                                                value="{{ $editDataNew->hsn_id }}"
                                                                                placeholder="Enter hsn_id"
                                                                                class="form-control hsn_id"
                                                                                style="min-width:100px">
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control process_id mb-2 "
                                                                                name="process_id_{{ $key }}"
                                                                                id="">
                                                                                <option value="" default>Select
                                                                                    Process</option>
                                                                                @foreach ($dataOutputProcessMaster as $data)
                                                                                    <option value="{{ $data['id'] }}"
                                                                                        {{ old('process_id', $editDataNew->process_id) == $data->id ? 'selected' : '' }}>
                                                                                        {{ $data->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                name="quantity_{{ $key }}"
                                                                                value="{{ $editDataNew->quantity }}"
                                                                                placeholder="Enter Quantity"
                                                                                class="form-control quantity readonly-select" />
                                                                            <span class="stock-available"></span>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control unit_id mb-2"
                                                                                name="unit_id_{{ $key }}"
                                                                                id="">
                                                                                <option value="" default>Select Unit
                                                                                </option>
                                                                                @foreach ($dataOutputUnitMaster as $data)
                                                                                    <option value="{{ $data['id'] }}"
                                                                                        {{ old('unit_id', $editDataNew->unit_id) == $data->id ? 'selected' : '' }}>
                                                                                        {{ $data->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                name="rate_{{ $key }}"
                                                                                value="{{ $editDataNew->rate }}"
                                                                                placeholder="Enter Rate"
                                                                                class="form-control rate" />
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                name="size_{{ $key }}"
                                                                                value="{{ $editDataNew->size }}"
                                                                                placeholder="Enter size"
                                                                                class="form-control size" />
                                                                        </td>

                                                                        <td>
                                                                            <input type="text"
                                                                                name="amount_{{ $key }}"
                                                                                value="{{ $editDataNew->amount }}"
                                                                                placeholder="0"
                                                                                class="form-control amount readonly-select" />
                                                                        </td>
                                                                        <td>
                                                                            <a data-id="{{ $editDataNew->tbl_delivery_chalan_item_details_id }}"
                                                                                class="delete-btn btn btn-danger m-1"
                                                                                title="Delete"><i
                                                                                    class="fas fa-archive"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>

                                                        @foreach ($editData as $key => $editDataNew)
                                                            @if ($key == 0)
                                                                <div class="form-group-inner">
                                                                    <div class="row">
                                                                        <div class="row">
                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label for="note">Remark
                                                                                    : <span
                                                                                        class="text-danger">*</span></label>
                                                                                <textarea class="form-control" name="remark">
@if (old('remark'))
{{ old('remark') }}@else{{ $editDataNew->remark }}
@endif
</textarea>

                                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="login-btn-inner">
                                                        <div class="row">
                                                            <div class="col-lg-5"></div>
                                                            <div class="col-lg-7">
                                                                <div class="login-horizental cancel-wp pull-left">
                                                                    <a href="{{ route('list-delivery-chalan') }}"
                                                                        class="btn btn-white"
                                                                        style="margin-bottom:50px">Cancel</a>
                                                                    <button class="btn btn-sm btn-primary login-submit-cs"
                                                                        type="submit" style="margin-bottom:50px">Update
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


    <form method="POST" action="{{ route('delete-addmore-delivery') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function() {
            var validator = $("#editDesignsForm").validate({
                ignore: [],
                rules: {
                    vendor_id: {
                        required: true,
                    },
                    transport_id: {
                        required: true,
                    },
                    vehicle_id: {
                        required: true,
                    },
                    plant_id: {
                        required: true,
                    },
                    vehicle_number: {
                        required: true,
                    },
                    tax_type: {
                        required: true,
                    },
                    tax_id: {
                        required: true,
                    },
                    remark: {
                        required: true,
                    },
                    'part_item_id_0': {
                        required: true,
                    },
                    'quantity_0': {
                        required: true,
                        digits: true,
                    },
                    'unit_id_0': {
                        required: true,
                    },
                    'hsn_id_0': {
                        required: true,
                        maxlength: 255
                    },
                    'process_id_0': {
                        required: true,
                        maxlength: 255
                    },
                    'amount_0': {
                        required: true,
                    },
                },
                messages: {
                    vendor_id: {
                        required: "Please Select the Vendor Company Name",
                    },
                    transport_id: {
                        required: "Please Select the transport Name",
                    },
                    vehicle_id: {
                        required: "Please Select the vehicle Name",
                    },
                    plant_id: {
                        required: "Please Enter the plant name",
                    },
                    vehicle_number: {
                        required: "Please Enter the vehicle number",
                    },
                    tax_type: {
                        required: "Please Select the tax type",
                    },
                    tax_id: {
                        required: "Please Select the Tax",
                    },
                    remark: {
                        required: "Please Enter the remark",
                    },
                    'part_item_id_0': {
                        required: "Please enter the Part Number",
                    },
                    'quantity_0': {
                        required: "Please enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    },
                    'unit_id_0': {
                        required: "Please enter the unit_id",
                    },
                    'hsn_id_0': {
                        required: "Please enter the hsn_id.",
                        maxlength: "hsn must be at most 255 characters long."
                    },
                    'process_id_0': {
                        required: "Please enter the process.",
                        maxlength: "process must be at most 255 characters long."
                    },
                    'amount_0': {
                        required: "Please enter the Amount",
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("part_item_id") || element.hasClass("hsn_id") || element
                        .hasClass("process_id") ||
                        element.hasClass("quantity") || element.hasClass("unit_id") ||
                        element.hasClass("amount")) {
                        error.insertAfter(element);
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            var i = {!! count($editData) !!}; // Initialize i with the number of existing rows

            $("#add").click(function() {
                ++i;

                var newRow = $(`
                <tr>
                    <input type="hidden" name="addmore[${i}][design_count]" class="form-control" value="${i}" placeholder="">
                    <input type="hidden" name="addmore[${i}][purchase_id]" class="form-control" value="${i}" placeholder="">
                    <td>
                        <select class="form-control part_item_id mb-2" name="addmore[${i}][part_item_id]" id="">
                            <option value="" default>Select Part Item</option>
                            @foreach ($dataOutputPartItem as $data)
                                <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
                            @endforeach
                        </select>
                    </td> 
                    <td><input type="text" class="form-control hsn_name" placeholder=" " readonly />
                        <input type="hidden" name="addmore[${i}][hsn_id]" class="form-control hsn_id" placeholder=" Amount" readonly />
                    </td>
                    <td>
                        <select class="form-control process_id mb-2" name="addmore[${i}][process_id]" id="">
                            <option value="" default>Select Process</option>
                            @foreach ($dataOutputProcessMaster as $data)
                                <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control quantity" name="addmore[${i}][quantity]" placeholder=" Quantity" />
                         <span class="stock-available"></span></td>
                    <td>
                        <select class="form-control unit_id mb-2" name="addmore[${i}][unit_id]" id="">
                            <option value="" default>Select Unit</option>
                            @foreach ($dataOutputUnitMaster as $data)
                                <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control rate" name="addmore[${i}][rate]" placeholder="rate" /></td>
                    <td><input type="text" class="form-control size" name="addmore[${i}][size]" placeholder="size" /></td>
                    <td><input type="text" class="form-control amount" name="addmore[${i}][amount]" placeholder=" Amount" readonly /></td>
                    <td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete Tender" data-id="{{ $editDataNew->id }}"><i class="fas fa-archive"></i></a></td>
                </tr>
            `);

                $("#dynamicTable").append(newRow);

                // Reinitialize validation for the new row
                $('select[name="addmore[' + i + '][part_item_id]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please select the Part Number",
                    }
                });

                $('select[name="addmore[' + i + '][hsn_id]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please select the HSN",
                    }
                });

                $('select[name="addmore[' + i + '][process_id]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please select the process",
                    }
                });

                $('input[name="addmore[' + i + '][quantity]"]').rules("add", {
                    required: true,
                    digits: true,
                    messages: {
                        required: "Please enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    }
                });

                $('input[name="addmore[' + i + '][unit_id]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the unit",
                    }
                });

                $('select[name="addmore[' + i + '][size]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the size",
                    }
                });

                $('input[name="addmore[' + i + '][amount]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Amount",
                    }
                });

                // Call checkStock function whenever quantity is entered
                $('input[name="addmore[' + i + '][quantity]"]').on('keyup', function() {
                    var currentRow = $(this).closest("tr");
                    checkStock(currentRow); // Check stock when quantity changes
                });
            });
            $(document).on('keyup', '.quantity, .rate', function(e) {
                var currentRow = $(this).closest("tr");
                var quantity = currentRow.find('.quantity').val();
                var rate = currentRow.find('.rate').val();
                var amount = quantity * rate;
                currentRow.find('.amount').val(amount);
            });
            $(document).on("click", ".remove-tr", function() {
                $(this).parents("tr").remove();
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

            // Custom validation method for minimum date
            $.validator.addMethod("minDate", function(value, element) {
                var today = new Date();
                var inputDate = new Date(value);
                return inputDate >= today;
            }, "The date must be today or later.");
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('change', '.part_item_id', function() {
                var partNoId = $(this).val(); // Get the selected part_item_id
                var currentRow = $(this).closest('tr'); // Get the current row

                if (partNoId) {
                    $.ajax({
                        url: '{{ route('get-hsn-for-part-item-store') }}', // Use the Laravel route helper
                        type: 'GET',
                        data: {
                            part_item_id: partNoId
                        },
                        success: function(response) {
                            console.log("HSN response:", response);

                            if (response.part && response.part.length > 0) {
                                var hsnName = response.part[0].name;
                                var hsnId = response.part[0].id;
                                currentRow.find('.hsn_name').val(hsnName); // Set HSN name
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
    <script>
        function updateHiddenInput(selectElement) {
            const hiddenInput = document.getElementById('hidden_' + selectElement.id);
            hiddenInput.value = selectElement.value;
        }
    </script>
@endsection
