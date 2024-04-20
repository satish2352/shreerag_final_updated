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
                        <h1>Add Production Data</h1>
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
                                    <form action="{{ route('store-purchase') }}" method="POST" id="addDesignsForm"
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

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="po_date">PO Date:</label>
                                                    <input type="date" class="form-control" id="po_date"
                                                        name="po_date" placeholder="Select PO Date">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-select-list">
                                                        <label for="vendor_id">Select Vendor:</label>
                                                        <select class="form-control custom-select-value" name="vendor_id" id="vendor_id">
                                                            <ul class="dropdown-menu ">
                                                            <option value="">Select Vendor</option>  
                                                            <option value="vendor1">Vendor 1</option>
                                                            <option value="vendor2">Vendor 2</option>
                                                            <option value="vendor3">Vendor 3</option>                                                              
                                                        </select>
                                                    </div>                                                                                                   
                                                </div>
                                            </div>

                                                <table class="table table-bordered" id="dynamicTable" style=" margin-top:15px">
                                                    <tr>
                                                        <th>Part No</th>
                                                        <th>Description</th>
                                                        <th>Due Date</th>
                                                        <th>HSN</th>
                                                        <th>Quantity</th>
                                                        <th>Rate</th>
                                                        <th>Amount</th>
                                                        <th>Action</th>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" name="addmore[0][part_no]"
                                                                placeholder="Enter Part No" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][description]"
                                                                placeholder="Enter Description" class="form-control" />
                                                        </td>
                                                        <td><input type="date" name="addmore[0][due_date]"
                                                                placeholder="Select Due Date" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][hsn_no]"
                                                                placeholder="Enter HSN No" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][quantity]"
                                                                placeholder="Enter Quantity" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][rate]"
                                                                placeholder="Enter Rate" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][amount]"
                                                                placeholder="Enter Amount" class="form-control" />
                                                        </td>
                                                        <td><button type="button" name="add" id="add"
                                                                class="btn btn-success">Add More</button></td>
                                                    </tr>
                                                </table>

                                                {{-- <button type="submit" class="btn btn-success">Save</button> --}}

                                                {{-- </form> --}}

                                            </div>

                                            {{-- =================== --}}

                                            <div class="row">                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="terms_condition">Terms & Condition:</label>
                                                    <input type="text" class="form-control" id="terms_condition"
                                                        name="terms_condition" placeholder="Enter Terms & Condition">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="remark">Remark:</label>
                                                    <input type="text" class="form-control" id="remark"
                                                        name="remark" placeholder="Enter Remark">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="transport_dispatch">Transport/Dispatch:</label>
                                                    <input type="text" class="form-control" id="transport_dispatch"
                                                        name="transport_dispatch" placeholder="Enter Transport/Dispatch">
                                                </div> 
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="image">Image:</label>
                                                    <input type="file" class="form-control" accept="image/*" id="image"
                                                        name="image">
                                                </div>
                                                
                                            </div>

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-purchase') }}" class="btn btn-white"
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

<script>
var i = 0;

$("#add").click(function() {
    ++i;

    $("#dynamicTable").append(
        '<tr><td><input type="text" name="addmore[' +
        i +
        '][part_no]" placeholder="Enter Part No" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][description]" placeholder="Enter Description" class="form-control" /></td><td><input type="date" name="addmore[' +
        i +
        '][due_date]" placeholder="Select Due Date" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][hsn_no]" placeholder="Enter HSN No" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][quantity]" placeholder="Enter Quantity" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][rate]" placeholder="Enter Rate" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][amount]" placeholder="Enter Amount" class="form-control" /></td><td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete"><i class="fas fa-archive"></i></a></td></tr>'
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
            po_date: {
                required: true,
            },
            vendor_id: {
                required: true,
            },
            terms_condition: {
                required: true,
            },            
            remark: {
                required: true,
            },
            transport_dispatch : {
                required: true,
            },
            // Add validation rules for other fields
            'addmore[0][part_no]': {
                required: true,
            },
            'addmore[0][description]': {
                required: true,
            },
            'addmore[0][due_date]': {
                required: true,
            },
            'addmore[0][hsn_no]': {
                required: true,
            },
            'addmore[0][quantity]': {
                required: true,
            },
            'addmore[0][rate]': {
                required: true,
            },
            'addmore[0][amount]': {
                required: true,
            },
        },

        messages: {
            po_date: {
                required: "Please select PO Date.",
            },
            vendor_id: {
                required: "Please enter Vendor Id.",
            },
            terms_condition: {
                required: "Please enter Terms And Condition.",
            },
            remark: {
                required: "Please enter Remark.",
            },            
            transport_dispatch: {
                required: "Please enter Transport/Dispatch.",
            },

             // Add error messages for other fields
             'addmore[0][part_no]': {
                required: "Please Enter Part No",
            },
            'addmore[0][description]': {
                required: "Please enter Description.",
            },
            'addmore[0][due_date]': {
                required: "Please Select Due Date",
            },
            'addmore[0][hsn_no]': {
                required: "Please Enter HSN No",
            },
            'addmore[0][quantity]': {
                required: "Please Enter Quantity",
            },
            'addmore[0][rate]': {
                required: "Please Enter Rate",
            },
            'addmore[0][amount]': {
                required: "Please Enter Amount",
            },            
        },
        submitHandler: function(form) {
                // Use SweetAlert to show a success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Purchase added successfully.',
                }).then(function() {
                    form.submit(); // Submit the form after the user clicks OK
                });
            }
        });
    });
</script>



@endsection