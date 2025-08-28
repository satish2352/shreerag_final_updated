@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid business-form">
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
                                                    <form action="{{ route('store-business') }}" method="POST"
                                                        id="addEmployeeForm" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group-inner">
                                                            <div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label for="project_name">Project Name : <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="project_name" value="{{ old('project_name') }}"
                                                                        name="project_name"
                                                                        placeholder="Enter project Name">
                                                                    @if ($errors->has('project_name'))
                                                                        <span class="red-text"><?php echo $errors->first('project_name', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label for="customer_po_number">Customer PO Number :
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="customer_po_number"
                                                                        value="{{ old('customer_po_number') }}"
                                                                        name="customer_po_number"
                                                                        placeholder="Enter Customer PO Number">
                                                                    @if ($errors->has('customer_po_number'))
                                                                        <span class="red-text"><?php echo $errors->first('customer_po_number', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label for="title">Customer Name : <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="title" value="{{ old('title') }}"
                                                                        name="title" placeholder="Enter Customer Name">
                                                                    @if ($errors->has('title'))
                                                                        <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label for="po_validity">PO Validity : <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="date" class="form-control"
                                                                        id="po_validity" value="{{ old('po_validity') }}"
                                                                        name="po_validity" placeholder="Enter PO Validity">
                                                                    @if ($errors->has('po_validity'))
                                                                        <span class="red-text"><?php echo $errors->first('po_validity', ':message'); ?></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover table-white repeater"
                                                                        id="purchase_order_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Sr. No.</th>
                                                                                <th class="col-sm-3">Product Name
                                                                                </th>
                                                                                <th class="col-md-3">Description
                                                                                </th>
                                                                                <th class="col-md-2">Quantity</th>
                                                                                <th class="col-md-2">Rate</th>
                                                                                <th class="col-md-2">Total</th>
                                                                                <th>
                                                                                    <button type="button"
                                                                                        class="btn btn-sm font-18 mr-1 btn-bg-colour"
                                                                                        id="add_more_btn" title="Add"
                                                                                        data-repeater-create>
                                                                                        <i class="fa fa-plus"></i>
                                                                                    </button>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="text" name="id"
                                                                                        class="form-control"
                                                                                        style="min-width:50px" readonly
                                                                                        value="1">
                                                                                    <input type="hidden" id="i_id"
                                                                                        class="form-control"
                                                                                        style="min-width:50px" readonly
                                                                                        value="0">
                                                                                </td>
                                                                                <td>
                                                                                    <input class="form-control product_name"
                                                                                        name="addmore[0][product_name]"
                                                                                        type="text"
                                                                                        style="min-width:150px">
                                                                                </td>

                                                                                <td>
                                                                                    <input class="form-control description"
                                                                                        name="addmore[0][description]"
                                                                                        type="text"
                                                                                        style="min-width:150px">
                                                                                </td>
                                                                                <td>
                                                                                    <input class="form-control quantity"
                                                                                        name="addmore[0][quantity]"
                                                                                        type="text">
                                                                                </td>
                                                                                <td>
                                                                                    <input class="form-control rate"
                                                                                        name="addmore[0][rate]"
                                                                                        type="text">
                                                                                </td>

                                                                                <td>
                                                                                    <input
                                                                                        class="form-control total_amount"
                                                                                        name="addmore[${i}][total]"
                                                                                        type="text" readonly>
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

                                                            <div class="col-md-12 text-right" style="margin-top: 10px;">
                                                                <strong>Grand Total: ₹</strong> <span
                                                                    id="grand_total">0.00</span>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <div class="sparkline12-graph">
                                                                        <div id="pwd-container1">
                                                                            <div class="form-group">
                                                                                <label for="remarks">Remark <span
                                                                                        class="text-danger">*</span></label>
                                                                                <textarea class="form-control" rows="3" type="text" class="form-control" id="remarks" name="remarks"
                                                                                    placeholder="Enter Remark">{{ old('remarks') }}</textarea>

                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="pwstrength_viewport_progress">
                                                                                    </span></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label for="customer_payment_terms">Payment Terms
                                                                        :</label> (optional)
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="customer_payment_terms"
                                                                        name="customer_payment_terms" placeholder="Enter Payment Terms">{{ old('customer_payment_terms') }}</textarea>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label for="customer_terms_condition">Terms Condition
                                                                        :</label> (optional)
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="customer_terms_condition"
                                                                        name="customer_terms_condition" placeholder="Enter Terms and Condition">{{ old('customer_terms_condition') }}</textarea>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="login-btn-inner">
                                                            <div class="row">
                                                                <div class="col-lg-12"
                                                                    style="display: flex; justify-content: center;">
                                                                    <div class="login-horizental cancel-wp pull-left">
                                                                        <a href="{{ route('list-business') }}"
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
        <script>
            $(document).ready(function() {

                function setMinDate() {
                    var today = new Date();
                    var day = String(today.getDate()).padStart(2, '0');
                    var month = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var year = today.getFullYear();
                    var todayDate = year + '-' + month + '-' + day;

                    $('#po_validity').attr('min', todayDate);
                }
                setMinDate();

                // Initialize jQuery Validation
                var validator = $("#addEmployeeForm").validate({
                    ignore: [], // Validate hidden inputs as well
                    rules: {
                        project_name: {
                            required: true,
                            maxlength: 50,

                        },
                        title: {
                            required: true,
                            maxlength: 50,

                        },
                        customer_po_number: {
                            required: true,
                            minlength: 10,
                            maxlength: 16,
                            digits: true
                        },
                        po_validity: {
                            required: true,
                            date: true
                        },
                        remarks: {
                            required: true,
                            maxlength: 255
                        },
                        'addmore[0][product_name]': {
                            required: true,
                            maxlength: 100,

                        },
                        'addmore[0][description]': {
                            required: true,
                            maxlength: 255
                        },
                        'addmore[0][quantity]': {
                            required: true,
                            digits: true,
                            min: 1
                        },
                        'addmore[0][rate]': {
                            required: true,
                            number: true,
                            min: 0.01
                        }
                    },
                    messages: {
                        project_name: {
                            required: "Please enter Project Name.",
                        },
                        title: {
                            required: "Please enter Customer Name.",
                            maxlength: "Customer Name must be at most 50 characters long.",
                        },
                        customer_po_number: {
                            required: "Please enter PO number.",
                            minlength: "PO number must be at least 10 characters long.",
                            maxlength: "PO number must be at most 16 characters long.",
                            digits: "PO number can only contain digits."
                        },
                        po_validity: {
                            required: "Please enter PO validity.",
                            date: "Please enter a valid date."
                        },
                        remarks: {
                            required: "Please enter remark.",
                            maxlength: "Remarks must be at most 255 characters long."
                        },
                        'addmore[0][product_name]': {
                            required: "Please enter the Product Name.",
                            maxlength: "Product Name must be at most 100 characters long.",
                        },
                        'addmore[0][description]': {
                            required: "Please enter the Description.",
                            maxlength: "Description must be at most 255 characters long."
                        },
                        'addmore[0][quantity]': {
                            required: "Please enter the Quantity.",
                            digits: "Please enter only digits for Quantity.",
                            min: "Quantity must be at least 1."
                        },
                        'addmore[0][rate]': {
                            required: "Please enter the Rate.",
                            number: "Please enter a valid number for Rate.",
                            min: "Rate must be a positive number."
                        }
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

                // Attach validation to the default row
                initializeValidation($("#purchase_order_table tbody tr"));

                // Function to attach validation rules to dynamic fields
                function initializeValidation(row) {
                    row.find('.product_name').rules("add", {
                        required: true,
                        maxlength: 100, // Maximum length of 100 characters
                        // alphanumeric: true, // Alphanumeric validation
                        messages: {
                            required: "Please enter the Product Name.",
                            maxlength: "Product Name must be at most 100 characters long.",
                            // alphanumeric: "Product Name can only contain letters and numbers."
                        }
                    });
                    row.find('.description').rules("add", {
                        required: true,
                        maxlength: 255, // Maximum length of 255 characters
                        messages: {
                            required: "Please enter the Description.",
                            maxlength: "Description must be at most 255 characters long."
                        }
                    });
                    row.find('.quantity').rules("add", {
                        required: true,
                        digits: true, // Digits only
                        min: 1, // Minimum value 1
                        messages: {
                            required: "Please enter the Quantity.",
                            digits: "Please enter only digits for Quantity.",
                            min: "Quantity must be at least 1."
                        }
                    });
                    row.find('.rate').rules("add", {
                        required: true,
                        number: true, // Number validation
                        min: 0.01, // Minimum value 0.01
                        messages: {
                            required: "Please enter the Rate.",
                            number: "Please enter a valid number for Rate.",
                            min: "Rate must be a positive number."
                        }
                    });
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
                            <input class="form-control product_name" name="addmore[${i}][product_name]" type="text" style="min-width:150px">
                        </td>
                        <td>
                            <input class="form-control description" name="addmore[${i}][description]" type="text" style="min-width:150px">
                        </td>
                        <td>
                            <input class="form-control quantity" name="addmore[${i}][quantity]" type="text">
                        </td>
                        <td>
                            <input class="form-control rate" name="addmore[${i}][rate]" type="text">
                        </td>
                        <td>
                            <input class="form-control total_amount" name="addmore[${i}][total]" type="text" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete" data-repeater-delete>
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                    var row = $(newRow).appendTo("#purchase_order_table tbody");

                    // Attach validation to the new row
                    initializeValidation(row);
                    validator.resetForm(); // Reset validation state after adding a new row
                });

                // Remove a row when the "Remove" button is clicked
                $(document).on("click", ".remove-row", function() {
                    $(this).closest("tr").remove();
                    validator.resetForm(); // Reset validation state after removing a row
                });
            });
        </script>
       
        <script>
            // Function to calculate total amount
            function calculateRowTotal(row) {
                let quantity = parseFloat(row.find('.quantity').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;
                let total = (quantity * rate).toFixed(2);
                row.find('.total_amount').val(total);
            }

            // Trigger on change of quantity or rate
            $(document).on('input', '.quantity, .rate', function() {
                let row = $(this).closest('tr');
                calculateRowTotal(row);
            });
        </script>
        <script>
            function calculateGrandTotal() {
                let grandTotal = 0;
                $('.total_amount').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    grandTotal += val;
                });
                $('#grand_total').text(grandTotal.toFixed(2));
            }

            // Recalculate on rate/quantity change
            $(document).on('input', '.quantity, .rate', function() {
                let row = $(this).closest('tr');
                calculateRowTotal(row);
                calculateGrandTotal();
            });

            // Also recalculate on row remove
            $(document).on("click", ".remove-row", function() {
                $(this).closest("tr").remove();
                validator.resetForm();
                calculateGrandTotal();
            });
        </script>
    @endsection
