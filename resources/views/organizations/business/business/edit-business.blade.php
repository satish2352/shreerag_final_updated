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
    <div class="">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Update Business Data</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
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
                            <?php

                            ?>
                            <div class="all-form-element-inner">
                                <div class="row d-flex justify-content-center form-display-center">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form action="{{ route('update-business', $editData[0]->business_main_id) }}" method="POST" enctype="multipart/form-data" id="editEmployeeForm">
                                    @csrf
                                    <input type="hidden" name="business_main_id" id=""
                                    class="form-control" value="{{ $editData[0]->business_main_id }}"
                                    placeholder="">
                                    <div class="form-group-inner">
                                            @foreach ($editData as $key => $editDataNew)
                                            @if ($key == 0)
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="customer_po_number">PO  Number :  <span class="text-danger">*</span></label>
                                                <input class="form-control" name="customer_po_number" id="customer_po_number"
                                                    placeholder="Enter the customer po number"
                                                    value=" @if (old('customer_po_number')) {{ old('customer_po_number') }}@else{{ $editDataNew->customer_po_number }} @endif">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="title">Customer Name :  <span class="text-danger">*</span></label>
                                                <input class="form-control" name="title" id="title"
                                                    placeholder="Enter the customer po number"
                                                    value=" @if (old('title')) {{ old('title') }}@else{{ $editDataNew->title }} @endif">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="po_validity">PO Validity: <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="po_validity" id="po_validity"
                                                    value="{{ old('po_validity', $editDataNew->po_validity) }}">
                                            </div>
                                        </div>
                                            @endif
                                            @endforeach
                                           <div class="mt-2 d-flex justify-content-end" style="display: flex; justify-content: end;">
                                            <button type="button" name="add" id="add"
                                            class="btn btn-success ">Add More</button>
                                           </div>
                                       
                                            <div style="margin-top:10px;">
                                            <table class="table table-bordered" id="dynamicTable">
                                                <tr>
                                                    <th>Product Nmae</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Action</th>
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
                                                            value="{{ $editDataNew->business_id }}"
                                                            placeholder="">
                                                        <td>
                                                            <input type="text"
                                                            name="product_name_{{ $key }}"
                                                            value="{{ $editDataNew->product_name }}"
                                                            placeholder="Enter product_name"
                                                            class="form-control product_name" />
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="description_{{ $key }}"
                                                                value="{{ $editDataNew->description }}"
                                                                placeholder="Enter Description"
                                                                class="form-control description" />
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="quantity_{{ $key }}"
                                                                value="{{ $editDataNew->quantity }}"
                                                                placeholder="Enter Quantity"
                                                                class="form-control quantity" />
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="rate_{{ $key }}"
                                                                value="{{ $editDataNew->rate }}"
                                                                placeholder="Enter Rate"
                                                                class="form-control rate" />
                                                        </td>
                                                        <td>
                                                            <a data-id="{{ $editDataNew->id }}"
                                                                class="delete-btn btn btn-danger m-1"
                                                                title="Delete"><i
                                                                    class="fas fa-archive"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                            @foreach ($editData as $key => $editDataNew)
                                            @if ($key == 0)
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="customer_payment_terms">Payment Terms:</label> (optional)
                                                <input class="form-control" name="customer_payment_terms" id="customer_payment_terms"
                                                    placeholder="Enter the customer po number"
                                                    value=" @if (old('customer_payment_terms')) {{ old('customer_payment_terms') }}@else{{ $editDataNew->customer_payment_terms }} @endif">
                                            </div>
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="customer_terms_condition">Terms Condition:</label> (optional)
                                                <input class="form-control" name="customer_terms_condition" id="customer_terms_condition"
                                                    placeholder="Enter the customer po number"
                                                    value=" @if (old('customer_terms_condition')) {{ old('customer_terms_condition') }}@else{{ $editDataNew->customer_terms_condition }} @endif">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="remarks">Remark:</label> (optional)
                                                <textarea class="form-control english_description" name="remarks" id="remarks" placeholder="Enter the Description">@if (old('remarks')){{ old('remarks') }}@else{{ $editDataNew->remarks }}@endif
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
                                                        <button class="btn btn-sm btn-primary login-submit-cs"
                                                            type="submit" style="margin-bottom:50px">Update Data</button>

                                                            <a href="{{ route('list-business') }}"><button
                                                                class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</button></a>
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
    </div>
    <form method="POST" action="{{ route('delete-addmore') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            var validator = $("#editDesignsForm").validate({
                ignore: [],
                rules: {
                    customer_po_number: {
                        required: true,
                    },
                    title: {
                        required: true,
                    },
                    po_validity: {
                        required: true,
                    },
                    'product_name_0': {
                        required: true,
                    },
                   
                    'quantity_0': {
                        required: true,
                        digits: true,
                    },
                    'rate_0': {
                        required: true,
                    },
                },
                messages: {
                    customer_po_number: {
                        required: "Please Enter PO Number",
                    },
                    title: {
                        required: "Please Enter the Tax",
                    },
                    po_validity: {
                        required: "Please Enter the Invoice Date",
                    },
                    
                    'product_name_0': {
                        required: "Please enter the Part Number",
                    },
                    // 'description_0': {
                    //     required: "Please enter the Description",
                    // },
                    'quantity_0': {
                        required: "Please enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    },
                    'rate_0': {
                        required: "Please enter the Rate",
                        number: "Please enter a valid number for Rate",
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("product_name") ||
                        element.hasClass("quantity") || 
                         element.hasClass("rate")) {
                        error.insertAfter(element);
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            var i = {!! count($editData) !!}; // Initialize i with the number of existing rows

            $("#add").click(function() {
                ++i;

                var newRow = $(
                    '<tr>' +
                    '<input type="hidden" name="addmore[' + i +
                    '][design_count]" class="form-control" value="' + i +
                    '" placeholder=""> <input type="hidden" name="addmore[' + i +
                    '][purchase_id]" class="form-control" value="' + i + '" placeholder="">' +
                    '<td><input type="text" class="form-control product_name" name="addmore[' + i +
                        '][product_name]" placeholder="Product Name" /></td>' +
                    '<td><input type="text" class="form-control description" name="addmore[' + i +
                    '][description]" placeholder=" Description" /></td>' +
                    '<td><input type="text" class="form-control quantity" name="addmore[' + i +
                    '][quantity]" placeholder="Quantity" /></td>' +
                    '<td><input type="text" class="form-control rate" name="addmore[' + i +
                    '][rate]" placeholder="rate" /></td>' +
                    '<td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete"><i class="fas fa-archive"></i></a></td>' +
                    '</tr>'
                );

                $("#dynamicTable").append(newRow);

                // Reinitialize validation for the new row
                $('select[name="addmore[' + i + '][product_name]"]').rules("add", {
            required: true,
            messages: {
                required: "Please select the Product name",
            }
        });
                // $('input[name="addmore[' + i + '][description]"]').rules("add", {
                //     required: true,
                //     messages: {
                //         required: "Please enter the Description",
                //     }
                // });
                $('input[name="addmore[' + i + '][quantity]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Due Date",
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
                $('input[name="addmore[' + i + '][rate]"]').rules("add", {
                    required: true,
                    digits: true,
                    messages: {
                        required: "Please enter the rate",
                        
                    }
                });
            });

            $(document).on("click", ".remove-tr", function() {
                $(this).parents("tr").remove();
            });

            // Custom validation method for minimum date
            $.validator.addMethod("minDate", function(value, element) {
                var today = new Date();
                var inputDate = new Date(value);
                return inputDate >= today;
            }, "The date must be today or later.");

            // Initialize date pickers with min date set to today
            function setMinDateForDueDates() {
                var today = new Date().toISOString().split('T')[0];
                $('.quantity').attr('min', today);
            }
            setMinDateForDueDates();

            $(document).on('focus', '.quantity', function() {
                setMinDateForDueDates();
            });

            $(document).on('keyup', '.quantity, .rate', function(e) {
                var currentRow = $(this).closest("tr");
                var quantity = currentRow.find('.quantity').val();
                var rate = currentRow.find('.rate').val();
                var amount = quantity * rate;
                currentRow.find('.amount').val(amount);
            });

            $('.delete-btn').click(function(e) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#delete_id").val($(this).attr("data-id"));
                        $("#deleteform").submit();
                    }
                });
            });
        });
    </script>
@endsection
