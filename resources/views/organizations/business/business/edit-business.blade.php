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
                                        <form action="{{ route('update-business', $editData[0]->business_main_id) }}"
                                            method="POST" enctype="multipart/form-data" id="addEmployeeForm">
                                            @csrf
                                            <input type="hidden" name="business_main_id" id=""
                                                class="form-control" value="{{ $editData[0]->business_main_id }}"
                                                placeholder="">
                                            <div class="form-group-inner">
                                                @foreach ($editData as $key => $editDataNew)
                                                    @if ($key == 0)
                                                        <div class="row">
                                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <label for="project_name">Project Name : <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" name="project_name"
                                                                    id="project_name"
                                                                    placeholder="Enter the customer po number"
                                                                    value="@if (old('project_name')) {{ trim(old('project_name')) }}@else{{ trim($editDataNew->project_name) }} @endif">
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <label for="customer_po_number">PO Number : <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" name="customer_po_number"
                                                                    id="customer_po_number"
                                                                    placeholder="Enter the customer po number"
                                                                    value="@if (old('customer_po_number')) {{ trim(old('customer_po_number')) }}@else{{ trim($editDataNew->customer_po_number) }} @endif">
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <label for="title">Customer Name : <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" name="title" id="title"
                                                                    placeholder="Enter the customer po number"
                                                                    value=" @if (old('title')) {{ old('title') }}@else{{ $editDataNew->title }} @endif">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <th>
                                                </th>
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-white repeater"
                                                        id="purchase_order_table">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Product Name</th>
                                                            <th>Description</th>
                                                            <th>Quantity</th>
                                                            <th>Rate</th>
                                                              <th>Total</th>
                                                            <th>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-success font-18 mr-1"
                                                                    id="add_more_btn" title="Add" data-repeater-create>
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </th>
                                                        </tr>

                                                        @foreach ($editData as $key => $editDataNew)
                                                            <tr>
                                                                <input type="hidden" name="design_count"
                                                                    value="{{ count($editData) }}">
                                                                <input type="hidden" name="design_id_{{ $key }}"
                                                                    value="{{ $editDataNew->id }}">
                                                                <td>{{ $key + 1 }}</td>

                                                                <td>
                                                                    <input type="text"
                                                                        name="product_name_{{ $key }}"
                                                                        value="{{ $editDataNew->product_name }}"
                                                                        class="form-control"
                                                                        @if (!($editDataNew->business_status_id === 1112 && $editDataNew->design_status_id === 1111)) disabled @endif />
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        name="description_{{ $key }}"
                                                                        value="{{ $editDataNew->description }}"
                                                                        class="form-control"
                                                                        @if (!($editDataNew->business_status_id === 1112 && $editDataNew->design_status_id === 1111)) disabled @endif />
                                                                </td>
                                                                {{-- <td>
                                                                    <input type="text"
                                                                        name="quantity_{{ $key }}"
                                                                        value="{{ $editDataNew->quantity }}"
                                                                        class="form-control"
                                                                        @if (!($editDataNew->business_status_id === 1112 && $editDataNew->design_status_id === 1111)) disabled @endif />
                                                                </td> --}}
                                                                <td>
                                                                <input type="text"
                                                                    name="quantity_{{ $key }}"
                                                                    value="{{ $editDataNew->quantity }}"
                                                                    class="form-control quantity"
                                                                    ... />
                                                                </td>
                                                                <td>
                                                                <input type="text"
                                                                    name="rate_{{ $key }}"
                                                                    value="{{ $editDataNew->rate }}"
                                                                    class="form-control rate"
                                                                    ... />
                                                                </td>
                                                                {{-- <td>
                                                                    <input type="text" name="rate_{{ $key }}"
                                                                        value="{{ $editDataNew->rate }}"
                                                                        class="form-control"
                                                                        @if (!($editDataNew->business_status_id === 1112 && $editDataNew->design_status_id === 1111)) disabled @endif />
                                                                </td> --}}
                                                               <td>
        <input type="number" class="form-control total_amount" value="{{ $item->total_amount ?? 0 }}" readonly>
    </td>
                                                                <td>
                                                                    <a data-id="{{ $editDataNew->id }}"
                                                                        class="btn btn-sm btn-danger font-18 ml-2 remove-row"
                                                                        title="Delete"
                                                                        @if (!($editDataNew->business_status_id === 1112 && $editDataNew->design_status_id === 1111)) disabled @endif>
                                                                        <i class="fas fa-archive"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach


                                                    </table>
                                                </div>
                                               
                                                @foreach ($editData as $key => $editDataNew)
                                                    @if ($key == 0)
                                                    <input type="hidden" name="grand_total_amount" id="grand_total_input" value="{{ old('grand_total_amount', $editDataNew->grand_total_amount) }}">

                                                    <strong>Grand Total: ₹ <span id="grand_total_amount">{{ $businessDetails->grand_total_amount ?? 0 }}</span></strong>


                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="po_validity">PO Validity: <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="po_validity"
                                                                id="po_validity"
                                                                value="{{ old('po_validity', $editDataNew->po_validity) }}">
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="customer_payment_terms">Payment Terms:</label>
                                                            (optional)
                                                            <input class="form-control" name="customer_payment_terms"
                                                                id="customer_payment_terms"
                                                                placeholder="Enter the customer po number"
                                                                value=" @if (old('customer_payment_terms')) {{ old('customer_payment_terms') }}@else{{ $editDataNew->customer_payment_terms }} @endif">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="customer_terms_condition">Terms
                                                                Condition:</label> (optional)
                                                            <input class="form-control" name="customer_terms_condition"
                                                                id="customer_terms_condition"
                                                                placeholder="Enter the customer po number"
                                                                value=" @if (old('customer_terms_condition')) {{ old('customer_terms_condition') }}@else{{ $editDataNew->customer_terms_condition }} @endif">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="remarks">Remark:</label> (optional)
                                                            <textarea class="form-control english_description" name="remarks" id="remarks"
                                                                placeholder="Enter the Description">
                                                                   @if (old('remarks'))
{{ trim(old('remarks')) }}@else{{ trim($editDataNew->remarks) }}
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
                                                            <button class="btn btn-sm btn-primary login-submit-cs"
                                                                type="submit" style="margin-bottom:50px">Update
                                                                Data</button>

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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

    <script>
        $(document).ready(function() {
            // Trim whitespace on form submission
            $("#addEmployeeForm").on('submit', function() {
                // Trim the input value before submitting the form
                var poNumberInput = $('#customer_po_number');
                var remarksInput = $('#remarks');
                poNumberInput.val($.trim(poNumberInput.val()));
                remarksInput.val($.trim(remarksInput.val()));
            });


            var validator = $("#addEmployeeForm").validate({
                ignore: [], // Validate hidden inputs as well
                rules: {
                    title: {
                        required: true,
                        maxlength: 50 // Maximum length of 50 characters
                    },
                    customer_po_number: {
                        required: true,
                        minlength: 10,
                        maxlength: 16,
                        digits: true // Digits only
                    },
                    po_validity: {
                        required: true,
                        date: true // Date validation
                    },
                    remarks: {
                        required: true,
                        maxlength: 255 // Maximum length of 255 characters
                    },
                    'addmore[0][product_name]': {
                        required: true,
                        maxlength: 100 // Maximum length of 100 characters
                    },
                    'addmore[0][description]': {
                        required: true,
                        maxlength: 255 // Maximum length of 255 characters
                    },
                    'addmore[0][quantity]': {
                        required: true,
                        digits: true, // Digits only
                        min: 1 // Minimum value 1
                    },
                    'addmore[0][rate]': {
                        required: true,
                        number: true, // Number validation
                        min: 0.01 // Minimum value 0.01
                    }
                },
                messages: {
                    title: {
                        required: "Please enter Customer Name.",
                        maxlength: "Customer Name must be at most 50 characters long."
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
                        required: "Please enter remarks.",
                        maxlength: "Remarks must be at most 255 characters long."
                    },
                    'addmore[0][product_name]': {
                        required: "Please enter the Product Name.",
                        maxlength: "Product Name must be at most 100 characters long."
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
                    error.addClass('text-danger'); // Add Bootstrap text-danger class for styling
                    if (element.closest('.form-group').length) {
                        element.closest('.form-group').append(error);
                    } else if (element.closest('td').length) {
                        element.closest('td').append(error);
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            initializeValidation($("#purchase_order_table tbody tr"));

            function initializeValidation(row) {
                row.find('.product_name').rules("add", {
                    required: true,
                    maxlength: 100, // Maximum length of 100 characters
                    messages: {
                        required: "Please enter the Product Name.",
                        maxlength: "Product Name must be at most 100 characters long."
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

            var rowCount = {{ count($editData) }};

            $("#add_more_btn").click(function() {
                rowCount++;
                var newRow = `
                    <tr>
                        <td><input type="text" name="addmore[${rowCount}][business_id]" class="form-control" value="${rowCount}"></td>
                        <td>
                            <input type="hidden" name="design_id_${rowCount}" value="0">
                            <input type="text" name="addmore[${rowCount}][product_name]" class="form-control product_name" placeholder="Enter product name" />
                        </td>
                        <td><input type="text" name="addmore[${rowCount}][description]" class="form-control description" placeholder="Enter description" /></td>
                        <td><input type="text" name="addmore[${rowCount}][quantity]" class="form-control quantity" placeholder="Enter quantity" /></td>
                        <td><input type="text" name="addmore[${rowCount}][rate]" class="form-control rate" placeholder="Enter rate" /></td>
                       <td><input type="text"  name="addmore[${rowCount}][total_amount]"  class="form-control total_amount"  > </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                $("#purchase_order_table tbody").append(newRow);
                initializeValidation($("#purchase_order_table tbody tr:last-child"));
            });

            $(document).on("click", ".remove-row", function() {
                $(this).closest("tr").remove();
                rowCount--; // Decrease the row count
                validator.resetForm(); // Reset validation state after removing a row
            });

            $("#purchase_order_table tbody tr").each(function() {
                initializeValidation($(this));
            });
        });
        // 👉 Add this block to calculate total & grand total on page load
$("#purchase_order_table tbody tr").each(function() {
    calculateRowTotal($(this));
});
calculateGrandTotal();
    </script>
 <script>
    function calculateRowTotal(row) {
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let rate = parseFloat(row.find('.rate').val()) || 0;
        let total = quantity * rate;
        row.find('.total_amount').val(total.toFixed(2));
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.total_amount').each(function() {
            let val = parseFloat($(this).val()) || 0;
            grandTotal += val;
        });
        $('#grand_total_amount').text(grandTotal.toFixed(2));
    }

    $(document).ready(function() {
        // Initial calculation
        $("#purchase_order_table tbody tr").each(function() {
            calculateRowTotal($(this));
        });
        calculateGrandTotal();
    });

    // On quantity or rate input
    $(document).on('input', '.quantity, .rate', function() {
        let row = $(this).closest('tr');
        calculateRowTotal(row);
        calculateGrandTotal();
    });

    $(document).on("click", ".remove-row", function() {
        $(this).closest("tr").remove();
        calculateGrandTotal();
    });
</script>

        {{-- <script>
            function calculateGrandTotal() {
    let grandTotal = 0;
    $('.total_amount').each(function() {
        let val = parseFloat($(this).val()) || 0;
        grandTotal += val;
    });
    $('#grand_total_amount').text(grandTotal.toFixed(2));
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

        </script> --}}
        <script>
    function calculateRowTotal(row) {
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let rate = parseFloat(row.find('.rate').val()) || 0;
        let total = quantity * rate;
        row.find('.total_amount').val(total.toFixed(2));
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.total_amount').each(function () {
            let val = parseFloat($(this).val()) || 0;
            grandTotal += val;
        });

        // Update display and hidden input
        $('#grand_total_amount').text(grandTotal.toFixed(2));
        $('#grand_total_input').val(grandTotal.toFixed(2));
    }

    // Recalculate on rate/quantity change
    $(document).on('input', '.quantity, .rate', function () {
        let row = $(this).closest('tr');
        calculateRowTotal(row);
        calculateGrandTotal();
    });

    // Recalculate on row remove
    // $(document).on("click", ".remove-row", function () {
    //     $(this).closest("tr").remove();
    //     if (typeof validator !== 'undefined') {
    //         validator.resetForm();
    //     }
    //     calculateGrandTotal();
    // });
$(document).on("click", ".remove-row", function () {
    let button = $(this);
    let row = button.closest("tr");
    let deleteId = button.data('id'); // Only present for existing DB records

    // Case: new (unsaved) row
    if (!deleteId || deleteId == 0) {
        row.remove();
        calculateGrandTotal();
        return;
    }

    // Case: saved row, confirm before delete
    Swal.fire({
        title: "Are you sure?",
        text: "This record will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $("#delete_id").val(deleteId);
            $("#deleteform").submit();
        }
    });
});

    // Optional: trigger total calculation on page load
    $(document).ready(function () {
        $('#purchase_order_table tbody tr').each(function () {
            calculateRowTotal($(this));
        });
        calculateGrandTotal();
    });
</script>

@endsection
