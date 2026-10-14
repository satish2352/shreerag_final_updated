@extends('admin.layouts.master')
@section('content')

    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
      <div class="emp-form-header">
        <h4><i class="fa fa-edit mr-2"></i> Edit Business</h4>
        <a href="{{ route('list-business') }}" class="emp-form-close" title="Close">&times;</a>
      </div>
      <div class="emp-form-body">

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

        <form action="{{ route('update-business', $editData[0]->business_main_id) }}"
            method="POST" enctype="multipart/form-data" id="updateBusiness">
            @csrf
            <input type="hidden" name="business_main_id" id=""
                class="form-control" value="{{ $editData[0]->business_main_id }}"
                placeholder="">
            <input type="hidden" name="old_business_pdf"
                value="{{ $editData[0]->business_pdf }}">
            <div class="form-group-inner">
                @foreach ($editData as $key => $editDataNew)
                    @if ($key == 0)
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label for="project_name">Project Name : <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="project_name"
                                    id="project_name"
                                    placeholder="Enter the customer po number"
                                    value="@if (old('project_name')) {{ trim(old('project_name')) }}@else{{ trim($editDataNew->project_name) }} @endif">
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label for="customer_po_number">
                                    PO Number : <span class="text-danger">*</span>
                                </label>

                                <input
                                    class="form-control @error('customer_po_number') is-invalid @enderror"
                                    name="customer_po_number" id="customer_po_number"
                                    placeholder="Enter the customer po number"
                                    value="{{ old('customer_po_number', trim($editDataNew->customer_po_number)) }}">

                                @error('customer_po_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-4">
                                <label for="title">Customer Name : <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="title" id="title"
                                    placeholder="Enter the customer po number"
                                    value=" @if (old('title')) {{ old('title') }}@else{{ $editDataNew->title }} @endif">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="po_validity">PO Validity: <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control"
                                    name="po_validity" id="po_validity"
                                    value="{{ old('po_validity', $editDataNew->po_validity) }}">
                            </div>
                        </div>
                    @endif
                @endforeach
                <th>
                </th>
                {{-- ── Add-more table styles ── --}}
                <style>
                    #purchase_order_table { border-collapse: separate; border-spacing: 0; width: 100%; }
                    #purchase_order_table thead tr { background: #1a3a6b; }
                    #purchase_order_table thead th {
                        color: #fff; font-size: 13.5px; font-weight: 600;
                        padding: 12px 14px; border: none; white-space: nowrap; vertical-align: middle;
                    }
                    #purchase_order_table thead th:first-child { border-radius: 8px 0 0 0; }
                    #purchase_order_table thead th:last-child  { border-radius: 0 8px 0 0; text-align: center; }
                    #purchase_order_table tbody tr { border-bottom: 1px solid #e8edf5; transition: background 0.15s; }
                    #purchase_order_table tbody tr:hover { background: #f5f8ff; }
                    #purchase_order_table tbody td { padding: 10px 10px; vertical-align: middle; border: none; }
                    /* Editable inputs */
                    #purchase_order_table .form-control {
                        border: 1.5px solid #d1d9e6; border-radius: 6px;
                        height: 38px; font-size: 13.5px; padding: 0 10px;
                        background: #fff; transition: border-color 0.18s;
                    }
                    #purchase_order_table .form-control:focus {
                        border-color: #1a3a6b; box-shadow: 0 0 0 3px rgba(26,58,107,0.1); outline: none;
                    }
                    /* Readonly inputs — distinct light bg */
                    #purchase_order_table .form-control[readonly] {
                        background: #f3f6fc; color: #4a5568; border-color: #e2eaf5; cursor: default;
                    }
                    /* Exceed-pending row */
                    #purchase_order_table tbody tr.row-exceed-pending {
                        background: #fffbeb; border-left: 4px solid #f59e0b;
                    }
                    #purchase_order_table tbody tr.row-exceed-pending:hover { background: #fef9e7; }
                    /* Grand Total footer */
                    #purchase_order_table tfoot tr { background: #f0f4fb; }
                    #purchase_order_table tfoot td {
                        padding: 12px 14px; border-top: 2px solid #c5d3e8;
                        font-size: 14px; font-weight: 700; color: #1a3a6b; vertical-align: middle;
                    }
                    #purchase_order_table tfoot .form-control {
                        background: #fff; border-color: #1a3a6b; font-weight: 700;
                        color: #1a3a6b; font-size: 15px;
                    }
                    /* Sr.No cell */
                    #purchase_order_table tbody td:first-child { font-weight: 600; color: #344563; text-align: center; width: 60px; }
                    /* Add-more button */
                    #add_more_btn {
                        background: #0bb8b2; border: none; color: #fff;
                        width: 32px; height: 32px; border-radius: 6px;
                        display: inline-flex; align-items: center; justify-content: center;
                        font-size: 16px; cursor: pointer; transition: background 0.18s;
                    }
                    #add_more_btn:hover { background: #09a09a; }
                    /* Delete button */
                    #purchase_order_table .remove-row {
                        background: transparent; border: 1.5px solid #dc2626;
                        color: #dc2626; border-radius: 6px; width: 32px; height: 32px;
                        display: inline-flex; align-items: center; justify-content: center;
                        font-size: 13px; cursor: pointer; transition: all 0.15s;
                    }
                    #purchase_order_table .remove-row:hover:not([disabled]) {
                        background: #dc2626; color: #fff;
                    }
                    #purchase_order_table .remove-row[disabled] {
                        border-color: #e5e7eb; color: #9ca3af; cursor: not-allowed;
                    }
                    .exceed-note { color: #d97706; font-size: 12px; margin-top: 3px; display: block; }
                </style>

                <div class="table-responsive" style="border-radius:8px; border:1px solid #e2eaf5; overflow:hidden; margin-bottom:16px;">
                    <table class="table repeater" id="purchase_order_table" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th style="width:60px;">Sr. No.</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th style="width:140px;">Quantity</th>
                                <th style="width:140px;">Rate</th>
                                <th style="width:160px;">Total Amount</th>
                                <th style="width:60px; text-align:center;">
                                    <button type="button" id="add_more_btn" title="Add Row" data-repeater-create>
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($editData as $key => $editDataNew)
                                @php
                                    $isOriginallyEditable = $editDataNew->business_status_id == 1112 && $editDataNew->design_status_id == 1111;
                                    $isExceedPending = $editDataNew->bom_estimation_send_to_owner == 1300;
                                    $isRateEditable = $isOriginallyEditable || $isExceedPending;
                                @endphp
                                <tr class="{{ $isExceedPending ? 'row-exceed-pending' : '' }}">
                                    <input type="hidden" name="design_count" value="{{ count($editData) }}">
                                    <input type="hidden" name="design_id_{{ $key }}" value="{{ $editDataNew->id }}">
                                    <td>
                                        {{ $key + 1 }}
                                        @if($isExceedPending)
                                            <br><span class="badge badge-warning" style="font-size:10px;">Exceed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="product_name_{{ $key }}"
                                            value="{{ $editDataNew->product_name }}"
                                            class="form-control"
                                            placeholder="Product name"
                                            @if (!$isOriginallyEditable) readonly @endif />
                                    </td>
                                    <td>
                                        <input type="text" name="description_{{ $key }}"
                                            value="{{ $editDataNew->description }}"
                                            class="form-control"
                                            placeholder="Description"
                                            @if (!$isOriginallyEditable) readonly @endif />
                                    </td>
                                    <td>
                                        <input type="text" name="quantity_{{ $key }}"
                                            value="{{ $editDataNew->quantity }}"
                                            class="form-control quantity"
                                            placeholder="Qty"
                                            @if (!$isRateEditable) readonly @endif />
                                    </td>
                                    <td>
                                        <input type="text" name="rate_{{ $key }}"
                                            value="{{ $editDataNew->rate }}"
                                            class="form-control rate"
                                            placeholder="Rate"
                                            @if (!$isRateEditable) readonly @endif />
                                        @if($isExceedPending)
                                            <span class="exceed-note"><i class="fas fa-info-circle"></i> Update rate to set new amount</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="total_{{ $key }}"
                                            class="form-control total" readonly
                                            value="{{ $editDataNew->quantity * $editDataNew->rate }}"
                                            placeholder="0.00" />
                                    </td>
                                    <td style="text-align:center;">
                                        <button type="button"
                                            class="btn remove-row"
                                            data-id="{{ $isOriginallyEditable ? $editDataNew->id : 0 }}"
                                            title="Remove row"
                                            @if (!$isOriginallyEditable) disabled @endif>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">
                                    <i class="fas fa-calculator mr-1" style="color:#0bb8b2;"></i> Grand Total
                                </td>
                                <td colspan="2">
                                    <input type="text" id="grandTotal" name="grand_total_amount" class="form-control" readonly>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @foreach ($editData as $key => $editDataNew)
                    @if ($key == 0)
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="remarks">Remark: <span
                                        class="text-danger">*</span> </label>
                                <textarea class="form-control remarks" name="remarks" id="remarks" placeholder="Enter the Description">
@if (old('remarks'))
{{ trim(old('remarks')) }}@else{{ trim($editDataNew->remarks) }}
@endif
</textarea>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="customer_payment_terms">Payment Terms
                                    (optional)
                                    :</label>

                                <textarea class="form-control customer_payment_terms" name="customer_payment_terms" id="customer_payment_terms"
                                    placeholder="Enter the Description">
@if (old('customer_payment_terms'))
{{ trim(old('customer_payment_terms')) }}@else{{ trim($editDataNew->customer_payment_terms) }}
@endif
</textarea>

                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
                                <label for="customer_terms_condition">Terms
                                    Condition (optional):</label>
                                <textarea class="form-control customer_terms_condition" name="customer_terms_condition" id="customer_terms_condition"
                                    placeholder="Enter the Description">
@if (old('customer_terms_condition'))
{{ trim(old('customer_terms_condition')) }}@else{{ trim($editDataNew->customer_terms_condition) }}
@endif
</textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label>Business PDF : <span class="text-danger">*</span>
                                </label>

                                <input type="file" name="business_pdf"
                                    class="form-control" accept=".pdf">

                                {{-- Show existing pdf --}}
                                @if (!empty($editData[0]->business_pdf))
                                    <a class="btn btn-sm btn-info mt-2" target="_blank"
                                        href="{{ Config::get('FileConstant.BUSINESS_PDF_VIEW') }}{{ $editData[0]->business_pdf }}"
                                        alt="Design"> Click to view File</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 text-center login-btn-inner mb-5">
                <a href="{{ route('list-business') }}">
                    <button type="button" class="btn btn-white edit-buinsess-btn-center">
                        Cancel
                    </button>
                </a>
                <button
                    class="btn btn-sm btn-primary login-submit-cs edit-buinsess-btn-center"
                    type="submit">
                    Update Data
                </button>
            </div>
        </form>

      </div>{{-- emp-form-body --}}
    </div>{{-- emp-form-card --}}
    </div></div></div></div>

    {{-- <form method="POST" action="{{ route('delete-addmore') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form> --}}

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

                function calculateTotalAmount(row) {
                    const quantity = parseFloat(row.find('.quantity').val()) || 0;
                    const rate = parseFloat(row.find('.rate').val()) || 0;
                    const total = quantity * rate;
                    row.find('.total').val(total.toFixed(2));
                    calculateGrandTotal();
                }

                function calculateGrandTotal() {
                    let grandTotal = 0;
                    $('.total').each(function() {
                        const val = parseFloat($(this).val());
                        if (!isNaN(val)) grandTotal += val;
                    });
                    $('#grandTotal').val(grandTotal.toFixed(2));
                }


                // Trim whitespace on form submission
                $("#updateBusiness").on('submit', function() {
                    var poNumberInput = $('#customer_po_number');
                    var remarksInput = $('#remarks');
                    poNumberInput.val($.trim(poNumberInput.val()));
                    remarksInput.val($.trim(remarksInput.val()));
                });

                var validator = $("#updateBusiness").validate({
                    ignore: [],
                    rules: {
                        title: {
                            required: true,
                            maxlength: 50
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
                            pattern: "PO number can only contain alphabets and numbers."
                        },
                        po_validity: {
                            required: "Please enter PO validity.",
                            date: "Please enter a valid date."
                        },
                        remarks: {
                            required: "Please enter remarks.",
                            maxlength: "Remarks must be at most 255 characters long."
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

                function initializeValidation(row) {
                    row.find('.product_name').rules("add", {
                        required: true,
                        maxlength: 100,
                        messages: {
                            required: "Please enter the Product Name.",
                            maxlength: "Product Name must be at most 100 characters long."
                        }
                    });
                    row.find('.description').rules("add", {
                        required: true,
                        maxlength: 255,
                        messages: {
                            required: "Please enter the Description.",
                            maxlength: "Description must be at most 255 characters long."
                        }
                    });
                    row.find('.quantity').rules("add", {
                        required: true,
                        digits: true,
                        min: 1,
                        messages: {
                            required: "Please enter the Quantity.",
                            digits: "Please enter only digits for Quantity.",
                            min: "Quantity must be at least 1."
                        }
                    });
                    row.find('.rate').rules("add", {
                        required: true,
                        number: true,
                        min: 0.01,
                        messages: {
                            required: "Please enter the Rate.",
                            number: "Please enter a valid number for Rate.",
                            min: "Rate must be a positive number."
                        }
                    });
                }

                $(document).on('input', '.quantity, .rate', function() {
                    const row = $(this).closest('tr');
                    calculateTotalAmount(row);
                });

                var rowCount = $("#purchase_order_table tbody tr").length;
                $("#add_more_btn").click(function() {
                    rowCount++;
                    const newRow = `
                <tr>
                    <td><input type="text" name="addmore[${rowCount}][business_id]" class="form-control" value="${rowCount}"></td>
                    <td>
                        <input type="hidden" name="design_id_${rowCount}" value="0">
                        <input type="text" name="addmore[${rowCount}][product_name]" class="form-control product_name" placeholder="Enter product name" />
                    </td>
                    <td><input type="text" name="addmore[${rowCount}][description]" class="form-control description" placeholder="Enter description" /></td>
                    <td><input type="text" name="addmore[${rowCount}][quantity]" class="form-control quantity" placeholder="Enter quantity" /></td>
                    <td><input type="text" name="addmore[${rowCount}][rate]" class="form-control rate" placeholder="Enter rate" /></td>
                    <td><input type="text" name="addmore[${rowCount}][total]" class="form-control total" readonly></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                    $("#purchase_order_table tbody").append(newRow);
                    const $lastRow = $("#purchase_order_table tbody tr:last-child");
                    initializeValidation($lastRow);
                });

                function updateSerialNumbers() {
                    $("#purchase_order_table tbody tr").each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                }


                $(document).on("click", ".remove-row", function(e) {

                    const deleteId = $(this).data("id");

                    // If row is newly added (not saved in DB)
                    if (deleteId == 0) {
                        $(this).closest("tr").remove();
                        calculateGrandTotal();
                        return;
                    }

                    // If saved row → delete from DB
                    Swal.fire({
                        title: "Are you sure?",
                        text: "This row will be deleted permanently!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#delete_id").val(deleteId); // SET ID
                            $("#deleteform").submit(); // SUBMIT THE DELETE FORM
                        }
                    });

                });


                $("#purchase_order_table tbody tr").each(function() {
                    initializeValidation($(this));
                    calculateTotalAmount($(this));
                });
            });
        </script>
    @endpush
    {{-- DELETE FORM (must be outside update form) --}}
    <form method="POST" action="{{ url('owner/delete-addmore') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id">
    </form>
@endsection
