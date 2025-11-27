@extends('admin.layouts.master')
@section('content')
    <style>
        .table-responsive-scroll {
    width: 100%;
    overflow-x: auto;      /* Enables horizontal scroll */
    overflow-y: hidden;
    white-space: nowrap;   /* Prevents table cells from wrapping */
}

.table-responsive-scroll table {
    width: 1500px;         /* Set fixed width if needed (optional) */
}


        .form-control {
            border: 2px solid #ced4da;
            border-radius: 4px;
        }

        .error {
            color: red;
        }

        .form-control {
            color: black;
        }

        /* .table-responsive {
            min-height: .01%;
            overflow-x: visible !important;
        } */

        /* The container of the dropdown button */
        .marginTop {
            margin-top: 200px;
        }

        .select2-container .select2-selection--single {
            height: 34px !important;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #ccc !important;
            border-radius: 0px !important;
        }

        #select2--container {
            width: 300px !important;
        }

        .
        /* Add this CSS to ensure error message visibility */

        .reverse-label {
            display: flex;
            flex-direction: row-reverse;
            /* Reverse the order of elements */
            flex-wrap: wrap-reverse;
            /* Allow elements to wrap onto the next line */
            align-items: center;
            /* Align items vertically if needed */
        }

        .reverse-label span {
            order: 2;
            /* Place span after the label content */
        }

        .reverse-label label {
            order: 1;
            /* Ensure label content appears before span */
        }
    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Purchase Order <span class="table-project-n">Form</span> </h1>
                            </div><br>

                            <form action="{{ route('store-purchase-order') }} " id="forms" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input class="form-control" type="hidden" name="business_details_id"
                                    id="business_details_id" value="{{ $business_detailsId }}">
                                <input class="form-control" type="hidden" name="requistition_id" id="requistition_id"
                                    value="{{ $requistition_id }}">
                                <input class="form-control" type="hidden" name="vendor_id" id="vendor_id">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Vendor Company Name <span class="text-danger">*</span></label>
                                            {{-- <select class="form-control"  name="vendor_id" id="vendor_id">
                          <option>Select</option> --}}

                                            <select class="form-control mb-2 select2" name="vendor_id" id="vendor_id">
                                                <option value="" default>Vendor Company Name</option>

                                                @foreach ($dataOutputVendor as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['vendor_company_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="vendor_type_id">Vendor Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control mb-2 select2" name="vendor_type_id"
                                                id="vendor_type_id">
                                                <option value="" default>Vendor Type</option>

                                                @foreach ($dataOutputVendorTyper as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Contact Person Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_person_name"
                                                value="{{ old('contact_person_name') }}" name="contact_person_name"
                                                placeholder="Enter Contact Person Name">
                                            @if ($errors->has('contact_person_name'))
                                                <span class="red-text"><?php echo $errors->first('contact_person_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Contact Person Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_person_number"
                                                value="{{ old('contact_person_number') }}" name="contact_person_number"
                                                placeholder="Enter Contact Person Number" maxlength="10" pattern="\d{10}">
                                            @if ($errors->has('contact_person_number'))
                                                <span class="red-text"><?php echo $errors->first('contact_person_number', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Tax Type<span class="text-danger">*</span></label>
                                            <select name="tax_type" class="form-control" title="select tax" id="tax_type">
                                                <option value="">Select Tax Type</option>
                                                <option value="GST">GST</option>
                                                <option value="SGST">SGST</option>
                                                <option value="CGST">CGST</option>
                                                <option value="SGST+CGST">SGST+CGST</option>
                                                <option value="IGST">IGST</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="tax_id">Tax<span class="text-danger">*</span></label>
                                            <select class="form-control mb-2" name="tax_id" id="tax_id">
                                                <option value="">Tax</option>
                                                @foreach ($dataOutputTax as $data)
                                                    <option value="{{ $data['id'] }}"
                                                        data-tax-rate="{{ $data['name'] }}">
                                                        {{ $data['name'] }}
                                                    </option>

                                                    {{-- <option value="{{ $data['id'] }}" data-tax-rate="{{ $data['value'] }}">
                                                {{ $data['name'] }}
                                            </option> --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Purchase Order Date <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                    name="invoice_date" id="invoice_date" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Payment Terms <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="payment_terms"
                                                value="{{ old('payment_terms') }}" name="payment_terms"
                                                placeholder="Enter Payment Terms">
                                            {{-- <select name="payment_terms" class="form-control" title="select tax" id="">
                      <option value="">Select Payment Terms</option>
                      <option value="30">30 Days</option>
                      <option value="60">60 Days</option>
                      <option value="90">90 Days</option>

                    </select> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Quote Number (optional)</label>
                                            <input class="form-control" type="text" name="quote_no" value=""
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>




                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-white repeater"
                                                id="purchase_order_table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="col-sm-2">Description</th>
                                                        <th class="col-md-2">HSN No.</th>
                                                        <th class="col-md-2">Part No.</th>
                                                        {{-- <th class="col-md-2">Due Date</th> --}}
                                                        <th class="col-md-2">Quantity</th>
                                                        <th class="col-md-2">Unit</th>
                                                        <th class="col-md-2">Rate</th>
                                                        <th class="col-md-2">Discount</th>

                                                        <th>Amount</th>
                                                        <th>
                                                            <button type="button"
                                                                class="btn btn-sm btn-bg-colour font-18 mr-1"
                                                                id="add_more_btn" title="Add" data-repeater-create>
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="id" class="form-control"
                                                                style="min-width:15px" readonly value="1">
                                                            <input type="hidden" id="i_id" class="form-control"
                                                                style="min-width:15px" readonly value="0">
                                                        </td>



                                                        {{-- <td>
                                                            <select class="form-control part-no mb-2"
                                                                name="addmore[0][part_no_id]" id=""
                                                                style="min-width:200px">
                                                                <option value="" default>Select Description</option>
                                                                @foreach ($dataOutputPartItem as $data)
                                                                    <option value="{{ $data['id'] }}">
                                                                        {{ $data['description'] }}</option>
                                                                @endforeach
                                                            </select>

                                                        </td> --}}

                                                        <td class="reverse-label">
                                                            <select
                                                                class="form-control mb-2 part_no_id select2"name="addmore[0][part_no_id]"
                                                                id="" style="width:100%">
                                                                <option value="" default>Select Description</option>
                                                                @foreach ($dataOutputPartItem as $data)
                                                                    <option value="{{ $data['id'] }}">
                                                                        {{ $data['description'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            {{-- <input class="form-control quantity" name="addmore[0][quantity]" type="text"> --}}
                                                        </td>
                                                        <td>
                                                            <input class="form-control hsn_name" name="addmore[0][hsn_id]"
                                                                type="text" style="min-width:100px" disabled>

                                                            <input type="hidden" class="form-control hsn_id"
                                                                name="addmore[0][hsn_id]" type="text"
                                                                style="min-width:100px">
                                                            {{-- <select class="form-control mb-2" name="addmore[0][hsn_id]"
                                                                id="" style="min-width:100px">
                                                                <option value="" default>Select HSN</option>
                                                                @foreach ($dataOutputHSNMaster as $data)
                                                                    <option value="{{ $data['id'] }}">
                                                                        {{ $data['name'] }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                            {{-- <input class="form-control quantity" name="addmore[0][quantity]" type="text"> --}}
                                                        </td>
                                                        <td>
                                                            <input class="form-control description"
                                                                name="addmore[0][description]" type="text"
                                                                style="min-width:100px">
                                                        </td>
                                                        {{-- <td>
                                        <input class="form-control due-date" placeholder="YYYY-MM-DD" name="addmore[0][due_date]" type="date"
                                            style="min-width:150px" value="">
                                    </td> --}}
                                                        <td>
                                                            <input class="form-control quantity"
                                                                name="addmore[0][quantity]" style="width:100%"
                                                                type="text">
                                                        </td>
                                                        {{-- <td>
                                        <input class="form-control unit" name="addmore[0][unit]" style="width:80px" type="text">
                                    </td> --}}
                                                        <td>
                                                            <select class="form-control mb-2 unit" name="addmore[0][unit]"
                                                                id="" style="min-width:100px">
                                                                <option value="" default>Select Unit</option>
                                                                @foreach ($dataOutputUnitMaster as $data)
                                                                    <option value="{{ $data['id'] }}">
                                                                        {{ $data['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            {{-- <input class="form-control description" name="addmore[0][description]" type="text" style="min-width:150px"> --}}
                                                        </td>
                                                        <td>
                                                            <input class="form-control rate" name="addmore[0][rate]"
                                                                style="min-width:100px" type="text">
                                                        </td>
                                                        <td>
                                                            <select class="form-control discount"
                                                                name="addmore[0][discount]" id="discount"
                                                                style="width:80px">
                                                                <option value="0">0 %</option>
                                                                <option value="1">1 %</option>
                                                                <option value="2">2 %</option>
                                                                <option value="3">3 %</option>
                                                                <option value="4">4 %</option>
                                                                <option value="5">5 %</option>
                                                                <option value="6">6 %</option>
                                                                <option value="7">7 %</option>
                                                                <option value="8">8 %</option>
                                                                <option value="9">9 %</option>
                                                                <option value="10">10 %</option>
                                                                <option value="11">11 %</option>
                                                                <option value="12">12 %</option>
                                                                <option value="13">13 %</option>
                                                                <option value="14">14 %</option>
                                                                <option value="15">15 %</option>
                                                                <option value="16">16 %</option>
                                                                <option value="17">17 %</option>
                                                                <option value="18">18 %</option>
                                                                <option value="19">19 %</option>
                                                                <option value="20">20 %</option>
                                                                <option value="21">21 %</option>
                                                                <option value="22">22 %</option>
                                                                <option value="23">23 %</option>
                                                                <option value="24">24 %</option>
                                                                <option value="25">25 %</option>
                                                                <option value="26">26 %</option>
                                                                <option value="27">27 %</option>
                                                                <option value="28">28 %</option>
                                                                <option value="29">29 %</option>
                                                                <option value="30">30 %</option>
                                                                <option value="31">31 %</option>
                                                                <option value="32">32 %</option>
                                                                <option value="33">33 %</option>
                                                                <option value="34">34 %</option>
                                                                <option value="35">35 %</option>
                                                                <option value="36">36 %</option>
                                                                <option value="37">37 %</option>
                                                                <option value="38">38 %</option>
                                                                <option value="39">39 %</option>
                                                                <option value="40">40 %</option>
                                                                <option value="41">41 %</option>
                                                                <option value="42">42 %</option>
                                                                <option value="43">43 %</option>
                                                                <option value="44">44 %</option>
                                                                <option value="45">45 %</option>
                                                                <option value="46">46 %</option>
                                                                <option value="47">47 %</option>
                                                                <option value="48">48 %</option>
                                                                <option value="49">49 %</option>
                                                                <option value="50">50 %</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control total_amount"
                                                                name="addmore[0][amount]" readonly style="width:150px"
                                                                type="text">
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger font-18 ml-2 remove-row"
                                                                title="Delete" data-repeater-delete>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <tfoot>
                                                    <tr class="grand-total-row">
                                                        <td colspan="8" class="text-end"><strong>Grand Total:</strong>
                                                        </td>
                                                        <td colspan="4"> <input type="text"
                                                                id="po_grand_total_amount" name="po_grand_total_amount"
                                                                class="form-control" readonly> </td>
                                                    </tr>
                                                </tfoot>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Transport-Dispatch <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="transport_dispatch"
                                                value="" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Remark <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="note"></textarea>
                                        </div>
                                    </div>


                                </div>
                                <div class="login-btn-inner">
                                    <div class="row">
                                        <div class="col-lg-5"></div>
                                        <div class="col-lg-7">
                                            <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('list-purchase') }}" class="btn btn-white"
                                                    style="margin-bottom:50px">Cancel</a>
                                                <button class="btn btn-sm btn-primary login-submit-cs" type="submit"
                                                    style="margin-bottom:50px">Save
                                                    Data</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

               
  @push('scripts')
                    <!-- ========== 1) TAX CALCULATION SCRIPT ========== -->
                    {{-- <script>
                        $(document).ready(function() {
 $(".select2").select2({ width: '100%' });
                            $('#tax_id').on('change', function() {

                                let taxId = $(this).val();

                                if (!taxId) {
                                    $('#po_grand_total_amount').val("0.00");
                                    return;
                                }

                                $.ajax({
                                    url: "{{ route('get-tax-value') }}",
                                    type: "GET",
                                    data: {
                                        tax_id: taxId
                                    },
                                    success: function(response) {

                                        let taxRate = parseFloat(response.tax_value) || 0;
                                        calculateGrandTotal(taxRate);
                                    }
                                });
                            });

                            $(document).on('keyup change', '.quantity, .rate, .discount', function() {

                                let taxRate = parseFloat($('#tax_id option:selected').data('tax-rate')) || 0;
                                calculateGrandTotal(taxRate);
                            });

                            function calculateGrandTotal(taxRate) {

                                let grandTotal = 0;

                                $('#purchase_order_table tbody tr').each(function() {

                                    let qty = parseFloat($(this).find('.quantity').val()) || 0;
                                    let rate = parseFloat($(this).find('.rate').val()) || 0;
                                    let disc = parseFloat($(this).find('.discount').val()) || 0;

                                    let baseAmount = qty * rate;
                                    let discountAmount = (baseAmount * disc) / 100;
                                    let afterDiscount = baseAmount - discountAmount;

                                    let taxAmount = (afterDiscount * taxRate) / 100;
                                    let rowTotal = afterDiscount + taxAmount;

                                    $(this).find('.total_amount').val(rowTotal.toFixed(2));
                                    grandTotal += rowTotal;
                                });

                                $('#po_grand_total_amount').val(grandTotal.toFixed(2));
                            }

                        });
                    </script> --}}
<script>
$(document).ready(function () {

    $(document).on('keyup change', '.quantity, .rate, .discount, #tax_id', function () {
        calculateGrandTotal();
    });

    function calculateGrandTotal() {

        let totalWithoutTax = 0;

        $('#purchase_order_table tbody tr').each(function () {

            let qty = parseFloat($(this).find('.quantity').val()) || 0;
            let rate = parseFloat($(this).find('.rate').val()) || 0;
            let discount = parseFloat($(this).find('.discount').val()) || 0;

            let baseAmount = qty * rate;
            let discountAmount = (baseAmount * discount) / 100;
            let afterDiscount = baseAmount - discountAmount;

            $(this).find('.total_amount').val(afterDiscount.toFixed(2));

            totalWithoutTax += afterDiscount;
        });

        let taxRate = parseFloat($('#tax_id option:selected').data('tax-rate')) || 0;
        let taxAmount = (totalWithoutTax * taxRate) / 100;
        let finalTotal = totalWithoutTax + taxAmount;

        $('#po_grand_total_amount').val(finalTotal.toFixed(2));
    }
});
</script>


                    <!-- ========== 2) NO CONFLICT SCRIPT ========== -->
                

                    <!-- ========== 3) FORM VALIDATION & ADD ROW SCRIPT ========== -->
                    <script>
                        $(document).ready(function() {

                            var validator = $("#forms").validate({

                                ignore: [],
                                rules: {

                                    vendor_type_id: {
                                        required: true
                                    },
                                    contact_person_name: {
                                        required: true
                                    },
                                    contact_person_number: {
                                        required: true
                                    },
                                    tax_type: {
                                        required: true
                                    },
                                    tax_id: {
                                        required: true
                                    },
                                    invoice_date: {
                                        required: true
                                    },
                                    payment_terms: {
                                        required: true
                                    },
                                    transport_dispatch: {
                                        required: true
                                    },
                                    note: {
                                        required: true
                                    },

                                    'addmore[0][part_no_id]': {
                                        required: true
                                    },
                                    'addmore[0][discount]': {
                                        required: true
                                    },
                                    'addmore[0][quantity]': {
                                        required: true
                                    },
                                    'addmore[0][hsn_id]': {
                                        required: true,
                                        number: true
                                    },
                                    'addmore[0][rate]': {
                                        required: true,
                                        number: true
                                    },
                                    'addmore[0][amount]': {
                                        required: true
                                    },
                                },

                                messages: {
                                    vendor_type_id: {
                                        required: "Please select Vendor Type"
                                    },
                                    contact_person_name: {
                                        required: "Enter Contact Person Name"
                                    },
                                    contact_person_number: {
                                        required: "Enter Contact Person Number"
                                    },
                                    tax_type: {
                                        required: "Select Tax Type"
                                    },
                                    tax_id: {
                                        required: "Select Tax"
                                    },
                                    invoice_date: {
                                        required: "Please select Purchase Order Date"
                                    },
                                    payment_terms: {
                                        required: "Enter Payment Terms"
                                    },
                                    transport_dispatch: {
                                        required: "Enter Transport/Dispatch field"
                                    },
                                    note: {
                                        required: "Enter Remark"
                                    },

                                    'addmore[0][part_no_id]': {
                                        required: "Please Enter the Part Number"
                                    },
                                    'addmore[0][discount]': {
                                        required: "Please Enter the Discount"
                                    },
                                    'addmore[0][quantity]': {
                                        required: "Please Enter the Quantity"
                                    },
                                    'addmore[0][rate]': {
                                        required: "Please Enter the Rate"
                                    },
                                    'addmore[0][amount]': {
                                        required: "Please Enter the Amount"
                                    },
                                },

                                errorPlacement: function(error, element) {

                                    if (element.hasClass("select2-hidden-accessible")) {
                                        var select2Container = element.next('.select2');
                                        error.insertAfter(select2Container);
                                    } else if (
                                        element.hasClass("part_no_id") ||
                                        element.hasClass("discount") ||
                                        element.hasClass("quantity") ||
                                        element.hasClass("unit") ||
                                        element.hasClass("rate") ||
                                        element.hasClass("total_amount")
                                    ) {
                                        error.insertAfter(element);
                                    } else {
                                        error.insertAfter(element);
                                    }
                                }

                            });

                            $(document).on('change', '.part_no_id', function() {
                                if ($(this).val()) $(this).valid();
                            });

                            function initializeValidation(context) {
                                $(context).find('.part_no_id').rules("add", {
                                    required: true
                                });
                                $(context).find('.discount').rules("add", {
                                    required: true
                                });
                                $(context).find('.quantity').rules("add", {
                                    required: true,
                                    digits: true
                                });
                                $(context).find('.unit').rules("add", {
                                    required: true
                                });
                                $(context).find('.rate').rules("add", {
                                    required: true,
                                    number: true
                                });
                                $(context).find('.total_amount').rules("add", {
                                    required: true
                                });
                            }
   $('.part_no_id').select2({
        width: '100%'
    });
                            $("#add_more_btn").click(function() {

                                var i_count = $('#i_id').val();
                                var i = parseInt(i_count) + 1;
                                $('#i_id').val(i);

                                var newRow = `
                <tr>
                    <td>
                <input type="text" name="id" class="form-control" style="min-width:15px" readonly value="${i + 1}"> <!-- This will start numbering from 2 -->
            </td>
                      <td class="reverse-label">
                    <select class="form-control part_no_id select2 mb-2" name="addmore[${i}][part_no_id]" id="" required style="width:100%">
                        <option value="" default>Select Description</option>
                        @foreach ($dataOutputPartItem as $data)
                            <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
                        @endforeach
                    </select>
                </td>
                      <td>
                        <input class="form-control hsn_name"  type="text" style="min-width:80px" disabled>
                             <input type="hidden" class="form-control hsn_id" name="addmore[${i}][hsn_id]" type="text" style="min-width:80px">
                        </td>
                    <td>
                        <input class="form-control description" name="addmore[${i}][description]" type="text" style="min-width:80px">
                    </td>
                    
                    <td>
                        <input class="form-control quantity" name="addmore[${i}][quantity]" style="width:100%" type="text" required>
                    </td>
                  
                   <td>
                             <select class="form-control mb-2 unit" name="addmore[${i}][unit]" required style="width:100%">
                                <option value="" default>Select Unit</option>
                                @foreach ($dataOutputUnitMaster as $data)
                                    <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                @endforeach
                            </select>
                        </td>
                    

                    <td>
                        <input class="form-control rate" name="addmore[${i}][rate]" style="min-width:100px" type="text" required>
                    </td>
                     <td>
                                       <select class="form-control discount" name="addmore[${i}][discount]"  style="width:80px">
                                                <option value="0">0 %</option>
                                                <option value="1">1 %</option>
                                                <option value="2">2 %</option>
                                                <option value="3">3 %</option>
                                                <option value="4">4 %</option>
                                                <option value="5">5 %</option>
                                                <option value="6">6 %</option>
                                                <option value="7">7 %</option>
                                                <option value="8">8 %</option>
                                                <option value="9">9 %</option>
                                                <option value="10">10 %</option>
                                                <option value="11">11 %</option>
                                                <option value="12">12 %</option>
                                                <option value="13">13 %</option>
                                                <option value="14">14 %</option>
                                                <option value="15">15 %</option>
                                                <option value="16">16 %</option>
                                                <option value="17">17 %</option>
                                                <option value="18">18 %</option>
                                                <option value="19">19 %</option>
                                                <option value="20">20 %</option>
                                                <option value="21">21 %</option>
                                                <option value="22">22 %</option>
                                                <option value="23">23 %</option>
                                                <option value="24">24 %</option>
                                                <option value="25">25 %</option>
                                                <option value="26">26 %</option>
                                                <option value="27">27 %</option>
                                                <option value="28">28 %</option>
                                                <option value="29">29 %</option>
                                                <option value="30">30 %</option>
                                                <option value="31">31 %</option>
                                                <option value="32">32 %</option>
                                                <option value="33">33 %</option>
                                                <option value="34">34 %</option>
                                                <option value="35">35 %</option>
                                                <option value="36">36 %</option>
                                                <option value="37">37 %</option>
                                                <option value="38">38 %</option>
                                                <option value="39">39 %</option>
                                                <option value="40">40 %</option>
                                                <option value="41">41 %</option>
                                                <option value="42">42 %</option>
                                                <option value="43">43 %</option>
                                                <option value="44">44 %</option>
                                                <option value="45">45 %</option>
                                                <option value="46">46 %</option>
                                                <option value="47">47 %</option>
                                                <option value="48">48 %</option>
                                                <option value="49">49 %</option>
                                                <option value="50">50 %</option>
                                              </select>
                                    </td>
                    <td>
                        <input class="form-control total_amount" name="addmore[${i}][amount]" readonly style="width:150px" type="text" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete" data-repeater-delete>
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

                                $("#purchase_order_table tbody").append(newRow);

                                $("#purchase_order_table tbody tr:last .select2").select2({
                                    width: '100%'
                                });

                                validator.resetForm();
                                initializeValidation($("#purchase_order_table tbody tr:last"));
                            });
 $("#purchase_order_table tbody tr:last .select2").select2({
            width: '100%'
        });
                            $(document).on("click", ".remove-row", function() {

                                var i = parseInt($('#i_id').val()) - 1;
                                $('#i_id').val(i);

                                $(this).closest("tr").remove();
                                validator.resetForm();
                                calculateGrandTotal();
                            });

                            initializeValidation($("#purchase_order_table"));

                        });
                    </script>
                    <!-- ========== 5) HSN FETCH SCRIPT ========== -->
                    <script>
                        $(document).ready(function() {

                          

                            $(document).on('change', '.part_no_id', function(e) {

                                var partNoId = $(this).val();
                                var currentRow = $(this).closest('tr');

                                if (partNoId) {

                                    $.ajax({
                                        url: '{{ route('get-hsn-for-part') }}',
                                        type: 'GET',
                                        data: {
                                            part_no_id: partNoId
                                        },
                                        success: function(response) {

                                            if (response.part && response.part.length > 0) {

                                                currentRow.find('.hsn_name').val(response.part[0].name);
                                                currentRow.find('.hsn_id').val(response.part[0].id);

                                            } else {
                                                alert("HSN not found for the selected part.");
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            alert("Error fetching HSN. Please try again.");
                                        }
                                    });

                                }
                            });

                        });
                    </script>
  @endpush

                @endsection
