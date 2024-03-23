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
                                    <form action="" method="POST" id="addDesignsForm"
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
                                            
                                                {{-- <button type="submit" class="btn btn-success">Save</button> --}}

                                                {{-- </form> --}}

                                            </div>

                                            {{-- =================== --}}

                                            <div class="row">                                                
                                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="grn_number">GRN No:</label>
                                                    <input type="text" class="form-control" id="grn_number"
                                                        name="grn_number" placeholder="Enter GRN Number">
                                                </div> --}}

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="grn_date">GRN Date:</label>
                                                    <input type="date" class="form-control" id="grn_date"
                                                        name="grn_date" placeholder="Enter GRN Date">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="purchase_id">PO No.:</label>
                                                    <input type="text" class="form-control" id="purchase_id"
                                                        name="purchase_id" placeholder="Enter Purchase No.">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="po_date">PO Date :</label>
                                                    <input type="date" class="form-control" id="po_date"
                                                        name="po_date" placeholder="Enter PO Date">
                                                </div>                                               

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="invoice_no">Invoice No.:</label>
                                                    <input type="text" class="form-control" id="invoice_no"
                                                        name="invoice_no" placeholder="Enter Invoice No">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="invoice_date">Invoice Date:</label>
                                                    <input type="date" class="form-control" id="invoice_date"
                                                        name="invoice_date" placeholder="Enter Invoice Date">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="image">Signature:</label>
                                                    <input type="file" class="form-control" accept="image/*" id="image"
                                                        name="image">
                                                </div>
                                            </div>
                                            
                                            <div style="margin-top:20px">
                                                <table class="table table-bordered" id="dynamicTable">
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>QC Check</th>
                                                        <th>Chalan Quantity</th>
                                                        <th>Actual Quantity</th>
                                                        <th>Accepted Quantity</th>
                                                        <th>Rejected Quantity</th>
                                                        <th>Action</th>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" name="addmore[0][description]"
                                                                placeholder="Enter Description" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][qc_check_remark]"
                                                                placeholder="Enter QC Check" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][chalan_quantity]"
                                                                placeholder="Enter Chalan Qty" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][actual_quantity]"
                                                                placeholder="Enter Actual Qty" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][accepted_quantity]"
                                                                placeholder="Enter Accepted Qty" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][rejected_quantity]"
                                                                placeholder="Enter Rejected Qty" class="form-control" />
                                                        </td>
                                                        <td><button type="button" name="add" id="add"
                                                                class="btn btn-success">Add More</button></td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label for="remark">Remark:</label>
                                                        <textarea class="form-control" rows="3" type="text" class="form-control" id="remark"
                                                        name="remark" placeholder="Enter Remark"></textarea>
                                                </div>
                                                
                                             

                                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="status">Status:</label>
                                                    <input type="text" class="form-control" id="status"
                                                        name="status" placeholder="Enter Status">
                                               </div>                                             --}}
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
        '][qc_check_remark]" placeholder="Enter QC Check" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][chalan_quantity]" placeholder="Enter Chalan Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][actual_quantity]" placeholder="Enter Actual Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
        i+
        '][accepted_quantity]" placeholder="Enter Accepted Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
        i+
        '][rejected_quantity]" placeholder="Enter Rejected Qty" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
    );
});

$(document).on("click", ".remove-tr", function() {
    $(this).parents("tr").remove();
});
</script>
<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $("#addDesignsForm").validate({
        rules: {           
            // grn_number: {
            //     required: true,
            // },
            grn_date: {
                required: true,
            },
            purchase_id: {
                required: true,
            },
            po_date:{
                required : true,
            },
            invoice_no: {
                required : true,
            },
            invoice_date : {
                required : true,
            },
            remark :{
                required : true,
            },
            image: {
                required: true,
                accept: "image/*",
            },
            'addmore[0][description]': {
                required : true,
            },
            'addmore[0][qc_check_remark]': {
                required : true,
            },
            'addmore[0][chalan_quantity]': {
                required : true,
            },
            'addmore[0][actual_quantity]': {
                required : true,
            },
            'addmore[0][accepted_quantity]': {
                required : true,
            },
            'addmore[0][rejected_quantity]': {
                required : true,
            },  
        },
        messages: {
            // grn_number: {
            //     required: "Please Enter GRN Number.",
            // },
            grn_date: {
                required: "Please Select GRN Date.",
            },            
            purchase_id : {
                required: "Please Enter PO No",
            },
            po_date : {
                required: "Please select PO Date.",
            },            
            invoice_no: {
                required: "Please Enter Invoice No.",
            },
            invoice_date : {
                required: "Please Select Invoice Date.",
            },
            remark : {
                required: "Please enter Remark.",
            },
            image: {
                required: "Please select Signature .",
                accept: "Please select an Signature file.",
            },            
            'addmore[0][description]': {
                required: "Please enter Description.",
            },
            'addmore[0][qc_check_remark]': {
                required: "Please enter QC Check.",
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