@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="emp-form-card">
                        <div class="emp-form-header">
                            <h4><i class="fa fa-clipboard-check mr-2"></i> Add GRN</h4>
                            <a href="{{ route('list-grn') }}" class="emp-form-close">&times;</a>
                        </div>
                        <div class="emp-form-body">

                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

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

                            <form action="{{ route('store-grn') }}" method="POST" id="addDesignsForm"
                                enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <input type="hidden" name="id" id="" class="form-control"
                                    value="{{ $gatepassId->id }}" placeholder="">

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
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                        <p>{{ Session::get('success') }}</p>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="date" class="form-control" id="grn_date"
                                                name="grn_date" placeholder=" "
                                                value="{{ date('Y-m-d') }}" readonly>
                                            <label for="grn_date">GRN Date</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="text" class="form-control" id="purchase_orders_id"
                                                name="purchase_orders_id" placeholder=" "
                                                value="{{ $purchase_order_data->purchase_orders_id }}" readonly>
                                            <label for="purchase_orders_id">PO No.</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="text" class="form-control" id="po_date"
                                                name="po_date" placeholder=" "
                                                value="{{ $purchase_order_data->created_at->format('d-m-Y') }}"
                                                readonly>
                                            <label for="po_date">PO Date</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="text" class="form-control" id="gatepass_name"
                                                name="gatepass_name" placeholder=" "
                                                value="{{ $gatepassId->gatepass_name }}" readonly>
                                            <label for="gatepass_name">Customer Name</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="text" class="form-control" id="bill_no"
                                                value="{{ old('bill_no') }}" name="bill_no"
                                                placeholder=" ">
                                            <label for="bill_no">Bill No. <span class="text-danger">*</span></label>
                                            @if ($errors->has('bill_no'))<span class="fl-error">{{ $errors->first('bill_no') }}</span>@endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="date" class="form-control" id="bill_date"
                                                value="{{ old('bill_date') }}" name="bill_date"
                                                placeholder=" ">
                                            <label for="bill_date">Bill Date <span class="text-danger">*</span></label>
                                            @if ($errors->has('bill_date'))<span class="fl-error">{{ $errors->first('bill_date') }}</span>@endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="file" class="form-control" accept="image/*"
                                                id="image" name="image">
                                            <label for="image">Signature <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-top:20px" class="table-responsive">
                                    <table class="table table-bordered" id="dynamicTable">
                                        <tr>
                                            <th style="width: 60px;">Sr. No.</th>
                                            <th style="width: 400px;">Description</th>
                                            <th style="width: 100px;">Part No.</th>
                                            <th style="width: 100px;">PO Qty</th>
                                            <th style="width: 70px;">Unit</th>
                                            {{-- <th style="width: 100px;">HSN</th> --}}
                                            <th style="width: 70px;">Rate</th>
                                            <th style="width: 80px;">Dis</th>
                                            <th style="width: 100px;">Actual Qty</th>
                                            <th style="width: 100px;">Acce Qty</th>
                                            <th style="width: 100px;">Rej Qty</th>
                                            <th style="width: 100px;">Bal Qty</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                        @foreach ($purchase_order_details_data as $index => $item)
                                            <tr>
                                                <input type="hidden"
                                                    name="addmore[{{ $index }}][edit_id]"
                                                    placeholder="Enter Description" class="form-control"
                                                    value="{{ $item->id }}" readonly />
                                                <td>{{ $index + 1 }}</td>
                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][part_description]"
                                                        placeholder="Enter Description"
                                                        class="form-control"
                                                        value="{{ $item->part_description }}" readonly />
                                                </td>
                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][description]"
                                                        placeholder="Enter description"
                                                        class="form-control"
                                                        value="{{ $item->description }}" readonly />
                                                </td>

                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][chalan_quantity]"
                                                        placeholder="Enter Chalan Qty"
                                                        class="form-control"
                                                        value="{{ $item->quantity }}" readonly />
                                                </td>
                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][unit_name]"
                                                        placeholder="Enter" class="form-control unit_name"
                                                        value="{{ $item->unit_name }}" readonly />
                                                    {{-- </td>
                                                <td><input type="text"
                                                    name="addmore[{{ $index }}][hsn_name]"
                                                    placeholder="Enter"
                                                    class="form-control hsn_name"
                                                    value="{{ $item->hsn_name }}" readonly />
                                            </td> --}}
                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][rate]"
                                                        placeholder="Enter" class="form-control rate"
                                                        value="{{ $item->rate }}" readonly />
                                                </td>
                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][discount]"
                                                        placeholder="Enter" class="form-control discount"
                                                        value="{{ $item->discount }}%" readonly />
                                                </td>


                                                <td><input type="number"
                                                        name="addmore[{{ $index }}][actual_quantity]"
                                                        placeholder="Enter Actual Qty"
                                                        class="form-control actual_quantity"
                                                        data-po-qty="{{ $item->quantity }}"
                                                        min="0" step="any" />
                                                </td>



                                                <td><input type="number"
                                                        name="addmore[{{ $index }}][accepted_quantity]"
                                                        placeholder="Enter Accepted Qty"
                                                        class="form-control accepted_quantity"
                                                        data-po-qty="{{ $item->quantity }}"
                                                        min="0" step="any" />
                                                </td>

                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][rejected_quantity]"
                                                        placeholder="Enter Rejected Qty"
                                                        class="form-control rejected_quantity" readonly />
                                                </td>

                                                <td><input type="text"
                                                        name="addmore[{{ $index }}][remaining_quantity]"
                                                        placeholder="0"
                                                        value="{{ $item->remaining_quantity }}"
                                                        class="form-control remaining_quantity" readonly />
                                                </td>
                                                {{-- <td><button type="button" name="add" id="add"
                                                        class="btn btn-success">Add More</button></td> --}}
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <textarea class="form-control" rows="3" id="remark" name="remark"
                                                placeholder=" "></textarea>
                                            <label for="remark">Remark <span class="text-danger">*</span></label>
                                            @if ($errors->has('remark'))<span class="fl-error">{{ $errors->first('remark') }}</span>@endif
                                        </div>
                                    </div>
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
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('js/vendor/jquery-1.11.3.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        var i = 0;

        $("#add").click(function() {
            ++i;

            $("#dynamicTable").append(
                '<tr><td><input type="text" name="addmore[' +
                i +
                '][part_description]" placeholder="Enter Description" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][chalan_quantity]" placeholder="Enter Chalan Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][actual_quantity]" placeholder="Enter Actual Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][accepted_quantity]" placeholder="Enter Accepted Qty" class="form-control" /></td><td><input type="text" name="addmore[' +
                i +
                '][rejected_quantity]" placeholder="Enter Rejected Qty" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
            );
        });

        $(document).on("click", ".remove-tr", function() {
            $(this).parents("tr").remove();
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('input', '.actual_quantity, .accepted_quantity', function(e) {
                var currentRow = $(this).closest("tr");
                var actual = parseFloat(currentRow.find('.actual_quantity').val()) || 0;
                var accepted = parseFloat(currentRow.find('.accepted_quantity').val()) || 0;
                var rejected = actual - accepted;
                currentRow.find('.rejected_quantity').val(actual !== 0 || accepted !== 0 ? rejected : '');

                // Re-validate accepted qty when actual changes so error clears live
                var acceptedInput = currentRow.find('.accepted_quantity');
                if (acceptedInput.val() !== '') {
                    acceptedInput.valid();
                }
            });
        });
    </script>


    <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {

            // Custom rule: value must not exceed PO qty stored in data-po-qty
            $.validator.addMethod("maxPoQty", function(value, element) {
                var poQty = parseFloat($(element).data('po-qty'));
                if (isNaN(poQty)) return true;
                return parseFloat(value) <= poQty;
            }, function(params, element) {
                var poQty = $(element).data('po-qty');
                return "Cannot exceed PO Qty (" + poQty + ").";
            });

            // Custom rule: accepted qty must not exceed actual qty in same row
            $.validator.addMethod("maxActualQty", function(value, element) {
                var actualQty = parseFloat($(element).closest('tr').find('.actual_quantity').val());
                if (isNaN(actualQty)) return true;
                return parseFloat(value) <= actualQty;
            }, "Accepted Qty cannot exceed Actual Qty.");

            // Initialize validation
            var validator = $("#addDesignsForm").validate({
                ignore: [], // do NOT ignore hidden fields inside table rows
                rules: {
                    grn_date: {
                        required: true
                    },
                    purchase_orders_id: {
                        required: true
                    },
                    po_date: {
                        required: true
                    },
                    bill_no: {
                        required: true
                    },
                    bill_date: {
                        required: true
                    },
                    remark: {
                        required: true
                    },
                    image: {
                        required: true,
                        accept: "image/*"
                    }
                },
                messages: {
                    grn_date: {
                        required: "Please Select GRN Date."
                    },
                    purchase_orders_id: {
                        required: "Please Enter PO No."
                    },
                    bill_no: {
                        required: "Please Enter Bill No."
                    },
                    bill_date: {
                        required: "Please select Bill Date."
                    },
                    po_date: {
                        required: "Please select PO Date."
                    },
                    remark: {
                        required: "Please enter Remark."
                    },
                    image: {
                        required: "Please select Signature.",
                        accept: "Only image files are allowed."
                    }
                }
            });

            // ============================
            // APPLY VALIDATION TO ALL ROWS
            // ============================

            // For Actual Qty
            $(".actual_quantity").each(function() {
                $(this).rules("add", {
                    required: true,
                    number: true,
                    min: 0,
                    maxPoQty: true,
                    messages: {
                        required: "Please enter Actual Qty.",
                        number: "Enter a valid number.",
                        min: "Actual Qty cannot be negative."
                    }
                });
            });

            // For Accepted Qty
            $(".accepted_quantity").each(function() {
                $(this).rules("add", {
                    required: true,
                    number: true,
                    min: 0,
                    maxPoQty: true,
                    maxActualQty: true,
                    messages: {
                        required: "Please enter Accepted Qty.",
                        number: "Enter a valid number.",
                        min: "Accepted Qty cannot be negative."
                    }
                });
            });

        });
    </script>




@endsection
