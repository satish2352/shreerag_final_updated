@extends('admin.layouts.master')
@section('content')
    <style>
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: hidden;
            width: 100%;
            display: block;
        }

        .form-display-center .col-lg-6,
        .form-display-center .col-md-6,
        .form-display-center textarea {
            width: 100% !important;
            max-width: 100% !important;
            display: block;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 90px;
            margin-bottom: 15px;
        }

        #purchase_order_table {
            table-layout: fixed;
            width: 100%;
        }

        #purchase_order_table th,
        #purchase_order_table td {
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        #purchase_order_table .col-srno   { width: 5%; }
        #purchase_order_table .col-pname  { width: 22%; }
        #purchase_order_table .col-desc   { width: 25%; }
        #purchase_order_table .col-qty    { width: 11%; }
        #purchase_order_table .col-rate   { width: 12%; }
        #purchase_order_table .col-total  { width: 14%; }
        #purchase_order_table .col-action { width: 11%; text-align: center; }

        #purchase_order_table .form-control {
            min-width: 0 !important;
            width: 100%;
            padding: 4px 6px;
            font-size: 13px;
        }

        #purchase_order_table label.error {
            font-size: 11px;
            display: block;
            white-space: normal;
        }

        /* jQuery Validate error labels — force red across the whole form */
        #addEmployeeForm label.error,
        #addEmployeeForm label.error.text-danger {
            color: #dc3545 !important;
            font-weight: 500;
            margin-top: 4px;
            display: block;
        }
    </style>
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
                                                            <div class="row">
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
                                                            </div>
                                                            <div class="row mt-2">
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
                                                            <div class="col-md-12 col-sm-12 mt-2">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover table-white repeater"
                                                                        id="purchase_order_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-srno">Sr. No.</th>
                                                                                <th class="col-pname">Product Name</th>
                                                                                <th class="col-desc">Description</th>
                                                                                <th class="col-qty">Quantity</th>
                                                                                <th class="col-rate">Rate</th>
                                                                                <th class="col-total">Total</th>
                                                                                <th class="col-action">
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
                                                                            @php
                                                                                $oldRows = old('addmore', [['product_name' => '', 'description' => '', 'quantity' => '', 'rate' => '', 'total' => '']]);
                                                                            @endphp
                                                                            @foreach ($oldRows as $idx => $row)
                                                                                <tr>
                                                                                    <td class="col-srno">
                                                                                        <input type="text" name="id"
                                                                                            class="form-control" readonly
                                                                                            value="{{ $idx + 1 }}">
                                                                                        @if ($idx === 0)
                                                                                            <input type="hidden" id="i_id"
                                                                                                class="form-control" readonly
                                                                                                value="0">
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="col-pname">
                                                                                        <input
                                                                                            class="form-control product_name"
                                                                                            name="addmore[{{ $idx }}][product_name]"
                                                                                            type="text"
                                                                                            value="{{ $row['product_name'] ?? '' }}">
                                                                                    </td>

                                                                                    <td class="col-desc">
                                                                                        <input class="form-control description"
                                                                                            name="addmore[{{ $idx }}][description]"
                                                                                            type="text"
                                                                                            value="{{ $row['description'] ?? '' }}">
                                                                                    </td>
                                                                                    <td class="col-qty">
                                                                                        <input class="form-control quantity"
                                                                                            name="addmore[{{ $idx }}][quantity]"
                                                                                            type="text"
                                                                                            value="{{ $row['quantity'] ?? '' }}">
                                                                                    </td>
                                                                                    <td class="col-rate">
                                                                                        <input class="form-control rate"
                                                                                            name="addmore[{{ $idx }}][rate]"
                                                                                            type="text"
                                                                                            value="{{ $row['rate'] ?? '' }}">
                                                                                    </td>

                                                                                    <td class="col-total">
                                                                                        <input
                                                                                            class="form-control total_amount"
                                                                                            name="addmore[{{ $idx }}][total]"
                                                                                            type="text" readonly
                                                                                            value="{{ $row['total'] ?? '' }}">
                                                                                    </td>
                                                                                    <td class="col-action">
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-danger font-18 remove-row"
                                                                                            title="Delete"
                                                                                            data-repeater-delete>
                                                                                            <i class="fa fa-trash"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach

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
                                                                        (optional)
                                                                        :</label>
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="customer_payment_terms"
                                                                        name="customer_payment_terms" placeholder="Enter Payment Terms">{{ old('customer_payment_terms') }}</textarea>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label for="customer_terms_condition">Terms Condition
                                                                        (optional)
                                                                        :</label>
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="customer_terms_condition"
                                                                        name="customer_terms_condition" placeholder="Enter Terms and Condition">{{ old('customer_terms_condition') }}</textarea>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="business_pdf">Upload File :<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="file" class="form-control"
                                                                            accept="application/pdf" id="business_pdf"
                                                                            name="business_pdf">
                                                                        @if ($errors->has('business_pdf'))
                                                                            <span class="red-text"><?php echo $errors->first('business_pdf', ':message'); ?></span>
                                                                        @endif
                                                                    </div>
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
        @push('scripts')
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
                    // `extension` and `pattern` live in jquery-validate's additional-methods.js
                    // which is NOT loaded globally. Without these, jQuery Validate silently
                    // breaks and the form submits unchecked to the server.
                    $.validator.addMethod("extension", function(value, element, param) {
                        param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpe?g|gif";
                        return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
                    }, $.validator.format("Please enter a value with a valid extension."));

                    $.validator.addMethod("pattern", function(value, element, param) {
                        if (this.optional(element)) return true;
                        if (typeof param === "string") {
                            param = new RegExp("^(?:" + param + ")$");
                        }
                        return param.test(value);
                    }, "Invalid format.");

                    $.validator.addMethod("filesize", function(value, element, param) {
                        if (element.files.length === 0) return true;
                        return element.files[0].size <= param;
                    }, "File size is too large");
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
                                // digits: true,
                                pattern: /^[A-Za-z0-9]+$/
                            },
                            po_validity: {
                                required: true,
                                date: true
                            },
                            remarks: {
                                required: true,
                                maxlength: 255
                            },
                            business_pdf: {
                                required: true,
                                extension: "pdf",
                                filesize: 1024 * 1024 // 1MB
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
                                pattern: "PO number can only contain alphabets and numbers."
                            },
                            po_validity: {
                                required: "Please enter PO validity.",
                                date: "Please enter a valid date."
                            },
                            remarks: {
                                required: "Please enter remark.",
                                maxlength: "Remarks must be at most 255 characters long."
                            },
                            business_pdf: {
                                required: "Please upload PDF file.",
                                extension: "Only PDF file allowed.",
                                filesize: "PDF must be less than 1MB."
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

                    // File inputs don't fire `keyup`/`focusout`, so re-validate on `change`
                    // to give immediate feedback when the user picks a file.
                    $('#business_pdf').on('change', function() {
                        $(this).valid();
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
                        var idx = $("#purchase_order_table tbody tr").length; // 0-based array index
                        var srno = idx + 1;                                    // 1-based display
                        var newRow = `
                    <tr>
                        <td class="col-srno">
                            <input type="text" name="id" class="form-control" readonly value="${srno}">
                        </td>
                        <td class="col-pname">
                            <input class="form-control product_name" name="addmore[${idx}][product_name]" type="text">
                        </td>
                        <td class="col-desc">
                            <input class="form-control description" name="addmore[${idx}][description]" type="text">
                        </td>
                        <td class="col-qty">
                            <input class="form-control quantity" name="addmore[${idx}][quantity]" type="text">
                        </td>
                        <td class="col-rate">
                            <input class="form-control rate" name="addmore[${idx}][rate]" type="text">
                        </td>
                        <td class="col-total">
                            <input class="form-control total_amount" name="addmore[${idx}][total]" type="text" readonly>
                        </td>
                        <td class="col-action">
                            <button type="button" class="btn btn-sm btn-danger font-18 remove-row" title="Delete" data-repeater-delete>
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
                    // $(document).on("click", ".remove-row", function() {
                    //     $(this).closest("tr").remove();
                    //     validator.resetForm(); // Reset validation state after removing a row
                    // });

                    $(document).on("click", ".remove-row", function(e) {

                        e.preventDefault();

                        let row = $(this).closest("tr");

                        Swal.fire({
                            title: "Are you sure?",
                            text: "This row will be removed!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes, delete it!",
                            cancelButtonText: "Cancel"
                        }).then((result) => {

                            if (result.isConfirmed) {

                                row.remove();

                                $("#purchase_order_table tbody tr").each(function(index) {
                                    $(this).find("td:first input").val(index + 1);
                                });

                                calculateGrandTotal();
                                validator.resetForm();

                            }

                        });

                    });

                    // After server-side validation error, rows are restored from old() —
                    // recompute each row's total and the grand total so the UI matches.
                    $("#purchase_order_table tbody tr").each(function() {
                        calculateRowTotal($(this));
                    });
                    calculateGrandTotal();
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
                // $(document).on("click", ".remove-row", function() {
                //     $(this).closest("tr").remove();
                //     validator.resetForm();
                //     calculateGrandTotal();
                // });
            </script>
        @endpush
    @endsection
