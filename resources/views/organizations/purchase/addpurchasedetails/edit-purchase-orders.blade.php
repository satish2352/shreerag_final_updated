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
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <center>
                                <h1>Edit Purchase Order Data</h1>
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
                                    @if (Session::has('status'))
                                        <div class="col-md-12">
                                            <div class="alert alert-{{ Session::get('status') }} alert-dismissible"
                                                role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <strong>{{ ucfirst(Session::get('status')) }}!</strong>
                                                {{ Session::get('msg') }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="all-form-element-inner">
                                            <form
                                                action="{{ route('update-purchase-order', $editData[0]->purchase_main_id) }}"
                                                method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                                @csrf
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
                                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="Service">Vendor Company
                                                                                Name:</label> &nbsp;<span
                                                                                class="red-text">*</span>
                                                                            <select class="form-control mb-2"
                                                                                name="vendor_id" id="vendor_id">
                                                                                <option value="" default>Select
                                                                                    Category</option>
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

                                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Tax</label>
                                                                            <select class="form-control mb-2" name="tax"
                                                                                id="tax">
                                                                                <option value="" default>Select Tax
                                                                                </option>

                                                                                <option value="9"
                                                                                    {{ $editDataNew->tax == 9 ? 'selected' : '' }}>
                                                                                    C-GST</option>
                                                                                <option value="9"
                                                                                    {{ $editDataNew->tax == 9 ? 'selected' : '' }}>
                                                                                    S-GST</option>
                                                                                <option value="18"
                                                                                    {{ $editDataNew->tax == 18 ? 'selected' : '' }}>
                                                                                    C-GST + S-GST</option>
                                                                            </select>
                                                                            @if ($errors->has('tax'))
                                                                                <span
                                                                                    class="red-text">{{ $errors->first('tax') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Invoice date <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="cal-icon">
                                                                                <input class="form-control datetimepicker"
                                                                                    type="text" name="invoice_date"
                                                                                    id="invoice_date"
                                                                                    value="{{ $editDataNew->invoice_date }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Payment Terms</label>
                                                                            <select name="payment_terms"
                                                                                class="form-control"
                                                                                title="select payment terms"
                                                                                id="payment_terms">
                                                                                <option value="">Select Payment Terms
                                                                                </option>
                                                                                <option value="30"
                                                                                    {{ $editDataNew->payment_terms == 30 ? 'selected' : '' }}>
                                                                                    30 Days</option>
                                                                                <option value="60"
                                                                                    {{ $editDataNew->payment_terms == 60 ? 'selected' : '' }}>
                                                                                    60 Days</option>
                                                                                <option value="90"
                                                                                    {{ $editDataNew->payment_terms == 90 ? 'selected' : '' }}>
                                                                                    90 Days</option>
                                                                            </select>
                                                                            @if ($errors->has('payment_terms'))
                                                                                <span
                                                                                    class="red-text">{{ $errors->first('payment_terms') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        <button type="button" name="add" id="add"
                                                            class="btn btn-success">Add More</button>
                                                        <div style="margin-top:10px;">
                                                            <table class="table table-bordered" id="dynamicTable">
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
                                                                @foreach ($editData as $key => $editDataNew)
                                                                    <?php
                                                                    // dd($editDataNew);
                                                                    // die();
                                                                    ?>
                                                                    <tr>
                                                                        <input type="hidden" name="design_count"
                                                                            id="design_id_{{ $key }}"
                                                                            class="form-control"
                                                                            value="{{ $key }}" placeholder="">

                                                                        <input type="hidden"
                                                                            name="design_id_{{ $key }}"
                                                                            id="design_id_{{ $key }}"
                                                                            class="form-control"
                                                                            value="{{ $editDataNew->purchase_order_details_id }}"
                                                                            placeholder="">

                                                                        <td>
                                                                            <input type="text"
                                                                                name="part_no_{{ $key }}"
                                                                                value="{{ $editDataNew->part_no }}"
                                                                                placeholder="Enter Part No"
                                                                                class="form-control part_no" />
                                                                        </td>

                                                                        <td>
                                                                            <input type="text"
                                                                                name="description_{{ $key }}"
                                                                                value="{{ $editDataNew->description }}"
                                                                                placeholder="Enter Description"
                                                                                class="form-control description" />
                                                                        </td>

                                                                        <td>
                                                                            <input type="date"
                                                                                name="due_date_{{ $key }}"
                                                                                value="{{ $editDataNew->due_date }}"
                                                                                placeholder="Enter Due Date"
                                                                                class="form-control due_date" />
                                                                        </td>

                                                                        <td>
                                                                            <input type="text"
                                                                                name="hsn_no_{{ $key }}"
                                                                                value="{{ $editDataNew->hsn_no }}"
                                                                                placeholder="Enter HSN No"
                                                                                class="form-control hsn_no" />
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
                                                                            <input type="text"
                                                                                name="amount_{{ $key }}"
                                                                                value="{{ $editDataNew->amount }}"
                                                                                placeholder="Enter Amount"
                                                                                class="form-control total_amount" />
                                                                        </td>

                                                                        <td>
                                                                            <a data-id="{{ $editDataNew->id }}"
                                                                                class="delete-btn btn btn-danger m-1"
                                                                                title="Delete Tender"><i
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
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                            <label for="quote_no">Quote No:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="quote_no" name="quote_no"
                                                                                value="{{ $editDataNew->quote_no }}"
                                                                                placeholder="Enter Terms & Condition">
                                                                        </div>

                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                            <label for="discount">Discount:</label>
                                                                            <input type="text" class="form-control"
                                                                                id="discount" name="discount"
                                                                                value="{{ $editDataNew->discount }}"
                                                                                placeholder="Enter discount">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                            <label for="note">Other Information
                                                                                :</label>
                                                                            <textarea class="form-control" name="note">
@if (old('note'))
{{ old('note') }}@else{{ $editDataNew->note }}
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
                                                                    <a href="{{ route('list-purchase') }}"
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
                    vendor_id: {
                        required: true,
                    },
                    tax: {
                        required: true,
                    },
                    invoice_date: {
                        required: true,
                    },
                    payment_terms: {
                        required: true,
                    },
                    discount: {
                        required: true,
                        number: true,
                    },
                    quote_no: {
                        required: true,
                        number: true,
                    },
                    note: {
                        required: true,
                    },
                    'part_no_0': {
                        required: true,
                    },
                    'description_0': {
                        required: true,
                    },
                    'due_date_0': {
                        required: true,
                    },
                    'hsn_no_0': {
                        required: true,
                    },
                    'quantity_0': {
                        required: true,
                        digits: true,
                    },
                    'rate_0': {
                        required: true,
                        number: true,
                    },
                    'amount_0': {
                        required: true,
                    },
                },
                messages: {
                    vendor: {
                        required: "Please Select the Vendor Company Name",
                    },
                    tax_id: {
                        required: "Please Enter the Tax",
                    },
                    invoice_date: {
                        required: "Please Enter the Invoice Date",
                    },
                    payment_terms: {
                        required: "Please Enter the Payment Terms",
                    },
                    discount: {
                        required: "Please Enter the Discount",
                        number: "Please enter a valid number.",
                    },
                    quote_no: {
                        required: "Please Enter the quote number",
                        number: "Please enter a valid number.",

                    },
                    note: {
                        required: "Please Enter the Other Information",
                    },
                    'part_no_0': {
                        required: "Please enter the Part Number",
                    },
                    'description_0': {
                        required: "Please enter the Description",
                    },
                    'due_date_0': {
                        required: "Please enter the Due Date",
                    },
                    'hsn_no_0': {
                        required: "Please enter the HSN",
                    },
                    'quantity_0': {
                        required: "Please enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    },
                    'rate_0': {
                        required: "Please enter the Rate",
                        number: "Please enter a valid number for Rate",
                    },
                    'amount_0': {
                        required: "Please enter the Amount",
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("part_no") || element.hasClass("description") ||
                        element.hasClass("due_date") || element.hasClass("hsn_no") ||
                        element.hasClass("quantity") || element.hasClass("rate") ||
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

                var newRow = $(
                    '<tr>' +
                    '<input type="hidden" name="addmore[' + i +
                    '][design_count]" class="form-control" value="' + i +
                    '" placeholder=""> <input type="hidden" name="addmore[' + i +
                    '][purchase_id]" class="form-control" value="' + i + '" placeholder="">' +
                    '<td><input type="text" class="form-control part_no" name="addmore[' + i +
                    '][part_no]" placeholder=" Part No" /></td>' +
                    '<td><input type="text" class="form-control description" name="addmore[' + i +
                    '][description]" placeholder=" Description" /></td>' +
                    '<td><input type="date" class="form-control due_date" name="addmore[' + i +
                    '][due_date]" placeholder=" Due Date" /></td>' +
                    '<td><input type="text" class="form-control hsn_no" name="addmore[' + i +
                    '][hsn_no]" placeholder=" HSN No" /></td>' +
                    '<td><input type="text" class="form-control quantity" name="addmore[' + i +
                    '][quantity]" placeholder=" Quantity" /></td>' +
                    '<td><input type="text" class="form-control rate" name="addmore[' + i +
                    '][rate]" placeholder=" Rate" /></td>' +
                    '<td><input type="text" class="form-control amount" name="addmore[' + i +
                    '][amount]" placeholder=" Amount" readonly /></td>' +
                    '<td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete Tender"><i class="fas fa-archive"></i></a></td>' +
                    '</tr>'
                );

                $("#dynamicTable").append(newRow);

                // Reinitialize validation for the new row
                $('input[name="addmore[' + i + '][part_no]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Part Number",
                    }
                });
                $('input[name="addmore[' + i + '][description]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Description",
                    }
                });
                $('input[name="addmore[' + i + '][due_date]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Due Date",
                    }
                });
                $('input[name="addmore[' + i + '][hsn_no]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the HSN",
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
                    number: true,
                    messages: {
                        required: "Please enter the Rate",
                        number: "Please enter a valid number for Rate",
                    }
                });
                $('input[name="addmore[' + i + '][amount]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Amount",
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
                $('.due_date').attr('min', today);
            }
            setMinDateForDueDates();

            $(document).on('focus', '.due_date', function() {
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
