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
                        <h1>Add Store Data</h1>
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
                                    <form action="{{ route('store-products') }}" method="POST" id="addDesignsForm"
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

                                                <!-- <table class="table table-bordered" id="dynamicTable">

                                                    <tr>

                                                        <th>Product Name</th>

                                                        <th>Product Qty</th>

                                                        <th>Product Price</th>
                                                        <th>Product Unit</th>
                                                        <th>Action</th>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" name="addmore[0][design_name]"
                                                                placeholder="Enter your Name" class="form-control" />
                                                        </td>

                                                        <td><input type="text" name="addmore[0][product_quantity]"
                                                                placeholder="Enter your Qty" class="form-control" />
                                                        </td>

                                                        <td><input type="text" name="addmore[0][product_size]"
                                                                placeholder="Enter your Price" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][product_unit]"
                                                                placeholder="Enter your Product Unit" class="form-control" />
                                                        </td>

                                                        <td><button type="button" name="add" id="add"
                                                                class="btn btn-success">Add More</button></td>

                                                    </tr>

                                                </table> -->

                                                {{-- <button type="submit" class="btn btn-success">Save</button> --}}

                                                {{-- </form> --}}

                                            </div>

                                            {{-- =================== --}}

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="store_date">Date:</label>
                                                        <input type="date" class="form-control" id="store_date"
                                                            name="store_date">
                                                    </div>                                           
<!-- 
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="receipt_no">Receipt No. :</label>
                                                    <input type="text" class="form-control" id="receipt_no"
                                                        name="receipt_no">
                                                </div>     -->
                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="name">Name of store person :</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" placeholder="Enter Name of store person">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="contact_number">Contact No. :</label>
                                                    <input type="text" class="form-control" id="contact_number"
                                                        name="contact_number" placeholder="Enter Contact No.">
                                                </div>

                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="address">Address:</label>
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" placeholder="Enter Address">
                                                </div> -->
                                                    
                                                </div>
                                                
                                            <div style="margin-top:30px;" >
                                                <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " > -->
                                              
                                                <table class="table table-bordered" id="dynamicTable">

                                                    <tr>

                                                        <th>Quantity</th>

                                                        <th>Description</th>

                                                        <th>Price</th>
                                                        <th>Amount</th>
                                                        <th>Total</th>
                                                        <th>Action</th>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" name="addmore[0][quantity]"
                                                                placeholder="Enter your Quantity" class="form-control" />
                                                        </td>

                                                        <td><input type="text" name="addmore[0][description]"
                                                                placeholder="Enter your description" class="form-control" />
                                                        </td>

                                                        <td><input type="text" name="addmore[0][price]"
                                                                placeholder="Enter your Price" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][amount]"
                                                                placeholder="Enter your amount" class="form-control" />
                                                        </td>
                                                        <td><input type="text" name="addmore[0][total]"
                                                                placeholder="Enter your total" class="form-control" />
                                                        </td>

                                                        <td><button type="button" name="add" id="add"
                                                                class="btn btn-success">Add More</button></td>

                                                    </tr>

                                                </table>
                                                <!-- </div> -->
                                                </div>

                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="quantity">Quantity :</label>
                                                    <input type="number" class="form-control" id="quantity"
                                                        name="quantity" placeholder="Enter Quantity">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="description">Description:</label>
                                                    <input type="text" class="form-control" id="description"
                                                        name="description" placeholder="Enter Description">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="price">Price :</label>
                                                    <input type="number" class="form-control" id="price"
                                                        name="price" placeholder="Enter Price">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="amount">Amount :</label>
                                                    <input type="number" class="form-control" id="amount"
                                                        name="amount" placeholder="Enter Amount">
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="total">Total :</label>
                                                    <input type="number" class="form-control" id="total"
                                                        name="total">
                                                </div> -->

                                                <div class='row'>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="remark">Remark :</label>
                                                    <input type="text" class="form-control" id="remark"
                                                        name="remark" placeholder="Enter Remark here">
                                                </div>
                                    <!-- signature -->
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                   <label for="signature">Signature :</label>
                                                    <input type="file" class="form-control" accept="image/*" id="signature"
                                                        name="signature" placeholder="Enter signature">
                                                </div>
                                                                                                  
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                   <label for="status">Status :</label>
                                                    <input type="text" class="form-control" id="status"
                                                        name="status" placeholder="Enter Remark here">
                                                </div>
                                               
                                                </div>
                                                
                                      

                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-store-receipt') }}" class="btn btn-white"
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
        '][quantity]" placeholder="Enter your quantity" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][description]" placeholder="Enter your description" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][price]" placeholder="Enter your Price" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][amount]" placeholder="Enter your amount" class="form-control" /></td><td><input type="text" name="addmore[' +
        i +
        '][total]" placeholder="Enter your total" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
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
            // design_name: {
            //     required: true,
            // },
            store_date: {
                required: true,
                // Add your custom validation rule if needed
            },
            receipt_no: {
                required: true,
            },
            name: {
                required: true,
            },
            contact_number: {
                required: true,
            },
            address: {
                required: true,
            },
            quantity: {
                required: true,
            },
            description: {
                required: true,
            },
            price: {
                required: true,
            },
            amount: {
                required: true,
            },
            total: {
                required: true,
            },
            remark: {
                required: true,
            },
            signature: {
                required: true,
            },
            status: {
                required: true,
            },
        },
        messages: {
            // design_name: {
            //     required: "Please enter design name.",
            // },
            receipt_date: {
                required: "Please enter a valid date.",
            },
            // receipt_no: {
            //     required: "Please enter receipt No.",
            // },
            name: {
                required: "Please enter  name.",
            },
            contact_number: {
                required: "Please enter a valid contact no.",
            },
            // address: {
            //     required: "Please enter a valid address.",
            // },
            quantity: {
                required: "Please enter quantity.",
            },
            description: {
                required: "Please enter  a valid description.",
            },
            price: {
                required: "Please enter  price.",
            },
            amount: {
                required: "Please enter amount",
            },
            total: {
                required: "Please enter  total",
            },
            remark: {
                required: "Please write remark",
            },
            signature: {
                required: "Please select an image.",
                accept: "Please select an image file.",
            },
        
            status: {
                required: "Please enter status",
            },

        },
    });
});
</script>



@endsection