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
        .form-display-center{
        display: flex !important;
        justify-content: center !important;
        align-items: center;
        }
    </style>
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add New Business</h1>
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
                                        <div class="row d-flex justify-content-center form-display-center">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            <form action="{{ route('store-business') }}" method="POST" id="addEmployeeForm"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group-inner">
                                                <div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="customer_po_number">Customer PO Number :  <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="customer_po_number" value="{{ old('customer_po_number') }}"
                                                            name="customer_po_number" placeholder="Enter Customer PO Number">
                                                            @if ($errors->has('customer_po_number'))
                                                            <span class="red-text"><?php echo $errors->first('customer_po_number', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="product_name">Product Name :  <span class="text-danger">*</span></label> 
                                                        <input type="text" class="form-control" id="product_name" value="{{ old('product_name') }}"
                                                            name="product_name" placeholder="Enter Product Name">
                                                            @if ($errors->has('product_name'))
                                                            <span class="red-text"><?php echo $errors->first('product_name', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="title">Customer Name :  <span class="text-danger">*</span></label> 
                                                        <input type="text" class="form-control" id="title" value="{{ old('title') }}"
                                                            name="title" placeholder="Enter Customer Name">
                                                            @if ($errors->has('title'))
                                                            <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="quantity">Quantity :  <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="quantity" value="{{ old('quantity') }}"
                                                            name="quantity" placeholder="Enter Quantity">
                                                            @if ($errors->has('quantity'))
                                                            <span class="red-text"><?php echo $errors->first('quantity', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="rate">Rate :  <span class="text-danger">*</span></label> 
                                                        <input type="text" class="form-control" id="rate" value="{{ old('rate') }}"
                                                            name="rate" placeholder="Enter rate">
                                                            @if ($errors->has('rate'))
                                                            <span class="red-text"><?php echo $errors->first('rate', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="po_validity">PO Validity :  <span class="text-danger">*</span></label> 
                                                        <input type="date" class="form-control" id="po_validity" value="{{ old('po_validity') }}"
                                                            name="po_validity" placeholder="Enter PO Validity">
                                                            @if ($errors->has('po_validity'))
                                                            <span class="red-text"><?php echo $errors->first('po_validity', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="hsn_number">HSN number :  <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="hsn_number" value="{{ old('hsn_number') }}"
                                                            name="hsn_number" placeholder="Enter HSN number">
                                                            @if ($errors->has('hsn_number'))
                                                            <span class="red-text"><?php echo $errors->first('hsn_number', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="customer_payment_terms">Payment Terms :</label> (optional) 
                                                        <input type="text" class="form-control" id="customer_payment_terms" value="{{ old('customer_payment_terms') }}"
                                                            name="customer_payment_terms" placeholder="Enter Payment Terms">
                                                            
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="customer_terms_condition">Terms Condition :</label> (optional) 
                                                        <input type="text" class="form-control" id="customer_terms_condition" value="{{ old('customer_terms_condition') }}"
                                                            name="customer_terms_condition" placeholder="Enter Terms Condition ">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="sparkline12-graph">
                                                            <div id="pwd-container1">
                                                                <div class="form-group">
                                                                    <label for="descriptions">Description</label> (optional) 
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="descriptions" name="descriptions"
                                                                        placeholder="Enter Description">{{ old('descriptions') }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="pwstrength_viewport_progress"></span></div>
                                                                </div>
                                                            </div>
                                                        </div>
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
                                                </div>
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-business') }}" class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    type="submit" style="margin-bottom:50px">Save Data</button>
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
    <script src="{{ asset('js/password-meter/pwstrength-bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/password-meter/zxcvbn.js') }}"></script>
    <script src="{{ asset('js/password-meter/password-meter-active.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Function to set minimum date for the po_validity field
            function setMinDate() {
                var today = new Date();
                var day = String(today.getDate()).padStart(2, '0');
                var month = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var year = today.getFullYear();
                var todayDate = year + '-' + month + '-' + day;
    
                $('#po_validity').attr('min', todayDate);
            }
    
            // Call the function to set the minimum date
            setMinDate();
    
            $("#addEmployeeForm").validate({
                rules: {
                    product_name: {
                        required: true,
                    },
                    title: {
                        required: true,
                    },
                    // descriptions: {
                    //     required: true,
                    // },
                    customer_po_number: {
                        required: true,
                    },
                    quantity: {
                        required: true,
                        number: true,
                    },
                    rate: {
                        required: true,
                        number: true,
                    },
                    po_validity: {
                        required: true,
                    },
                    hsn_number: {
                        required: true,
                        number: true,
                    },
                    // customer_payment_terms: {
                    //     required: true,
                    // },
                    // customer_terms_condition: {
                    //     required: true,
                    // },
                    // remarks: {
                    //     required: true,
                    // },
                },
                messages: {
                    product_name: {
                        required: "Please enter Product Name.",
                    },
                    title: {
                        required: "Please enter Customer Name.",
                    },
                    // descriptions: {
                    //     required: "Please enter Description.",
                    // },
                    customer_po_number: {
                        required: "Please enter po number.",
                    }, 
                    quantity: {
                        required: "Please enter quantity.",
                        number: "Please enter a valid number.",
                    },
                    rate: {
                        required: "Please enter rate.",
                        number: "Please enter a valid number.",
                    },
                    po_validity: {
                        required: "Please enter po validity.",
                    },
                    hsn_number: {
                        required: "Please enter hsn number.",
                        number: "Please enter a valid number.",
                    },
                    // customer_payment_terms: {
                    //     required: "Please enter customer payment terms.",
                    // },
                    // customer_terms_condition: {
                    //     required: "Please enter customer terms condition.",
                    // },
                    // remarks: {
                    //     required: "Please enter Remark.",
                    // },
                },
                submitHandler: function(form) {
                    // Use SweetAlert to show a confirmation dialog
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'You want to submit this business to Design Department ?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            // If user clicks "Yes", submit the form
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
    
@endsection
