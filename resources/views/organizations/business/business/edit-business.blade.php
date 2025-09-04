@extends('admin.layouts.master')
@section('content')
    <div class=".business-form">
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
                                            method="POST" enctype="multipart/form-data" id="updateBusiness">
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
                                                            <input type="date" class="form-control" name="po_validity"
                                                                id="po_validity"
                                                                value="{{ old('po_validity', $editDataNew->po_validity) }}">
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
                                                            <th>Sr. No.</th>
                                                            <th>Product Name</th>
                                                            <th>Description</th>
                                                            <th>Quantity</th>
                                                            <th>Rate</th>
                                                            <th>Total Amount</th>
                                                            <th>
                                                                <button type="button"
                                                                    class="btn btn-sm font-18 mr-1 btn-bg-colour"
                                                                    id="add_more_btn" title="Add" data-repeater-create>
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </th>
                                                        </tr>

                                                        @foreach ($editData as $key => $editDataNew)
                                                      
                                                        <input type="hidden" name="delete_id" id="delete_id" value="{{ $editDataNew->id }}">
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
                                                                        @if (!($editDataNew->business_status_id == 1112 && $editDataNew->design_status_id == 1111)) disabled @endif
                                                                         />
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        name="description_{{ $key }}"
                                                                        value="{{ $editDataNew->description }}"
                                                                        class="form-control"
                                                                        @if (!($editDataNew->business_status_id == 1112 && $editDataNew->design_status_id == 1111)) disabled @endif
                                                                         />
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        name="quantity_{{ $key }}"
                                                                        value="{{ $editDataNew->quantity }}"
                                                                        class="form-control quantity"
                                                                        @if (!($editDataNew->business_status_id == 1112 && $editDataNew->design_status_id == 1111)) disabled @endif 
                                                                        />
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="rate_{{ $key }}"
                                                                        value="{{ $editDataNew->rate }}"
                                                                        class="form-control rate"
                                                                        @if (!($editDataNew->business_status_id == 1112 && $editDataNew->design_status_id == 1111)) disabled @endif 
                                                                        />
                                                                </td>
                                                                  <td><input type="text" name="total_{{ $key }}" class="form-control total" readonly value="{{ $editDataNew->quantity * $editDataNew->rate }}"></td>
                                                                <td>
                                                                    <a data-id="{{ $editDataNew->id }}"
                                                                        class="btn btn-sm btn-danger font-18 ml-2 remove-row"
                                                                        title="Delete"
                                                                        @if (!($editDataNew->business_status_id == 1112 && $editDataNew->design_status_id == 1111)) disabled @endif
                                                                        >
                                                                        <i class="fas fa-archive"></i>
                                                                    </a>
                                                                    
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                         <tfoot>
                                                            <tr>
                                                                <td colspan="5" class="text-right font-weight-bold">Grand Total</td>
                                                                <td colspan="2">
                                                                    <input type="text" id="grandTotal" name="grand_total_amount" class="form-control" readonly>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                @foreach ($editData as $key => $editDataNew)
                                                    @if ($key == 0)
                                                    <div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="remarks">Remark: <span
                                                                        class="text-danger">*</span> </label>
                                                            <textarea class="form-control remarks" name="remarks" id="remarks" placeholder="Enter the Description">@if (old('remarks')){{ trim(old('remarks')) }}@else{{ trim($editDataNew->remarks) }}@endif</textarea>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="customer_payment_terms">Payment Terms:</label>
                                                            (optional)
                                                            <textarea class="form-control customer_payment_terms" name="customer_payment_terms" id="customer_payment_terms" placeholder="Enter the Description">@if (old('customer_payment_terms')){{ trim(old('customer_payment_terms')) }}@else{{ trim($editDataNew->customer_payment_terms) }}@endif</textarea>

                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
                                                            <label for="customer_terms_condition">Terms
                                                                Condition:</label> (optional)
                                                                <textarea class="form-control customer_terms_condition" name="customer_terms_condition" id="customer_terms_condition" placeholder="Enter the Description">@if (old('customer_terms_condition')){{ trim(old('customer_terms_condition')) }}@else{{ trim($editDataNew->customer_terms_condition) }}@endif</textarea>
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
                                                <button class="btn btn-sm btn-primary login-submit-cs edit-buinsess-btn-center" type="submit">
                                                    Update Data
                                                </button>                                               
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
    $('.total').each(function () {
        const val = parseFloat($(this).val());
        if (!isNaN(val)) grandTotal += val;
    });
    $('#grandTotal').val(grandTotal.toFixed(2));
    }
    $(document).ready(function() {
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
                    digits: true
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
                    digits: "PO number can only contain digits."
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

        $(document).on('input', '.quantity, .rate', function () {
            const row = $(this).closest('tr');
              calculateTotalAmount(row);
        });

        var rowCount = $("#purchase_order_table tbody tr").length;

        // $("#add_more_btn").click(function() {
        //     rowCount++;
        //     const newRow = `
        //         <tr>
        //             <td><input type="text" name="addmore[${rowCount}][business_id]" class="form-control" value="${rowCount}"></td>
        //             <td>
        //                 <input type="hidden" name="design_id_${rowCount}" value="0">
        //                 <input type="text" name="addmore[${rowCount}][product_name]" class="form-control product_name" placeholder="Enter product name" />
        //             </td>
        //             <td><input type="text" name="addmore[${rowCount}][description]" class="form-control description" placeholder="Enter description" /></td>
        //             <td><input type="text" name="addmore[${rowCount}][quantity]" class="form-control quantity" placeholder="Enter quantity" /></td>
        //             <td><input type="text" name="addmore[${rowCount}][rate]" class="form-control rate" placeholder="Enter rate" /></td>
        //             <td><input type="text" name="addmore[${rowCount}][total]" class="form-control total" readonly></td>
        //             <td>
        //                 <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete">
        //                     <i class="fa fa-trash"></i>
        //                 </button>
        //             </td>
        //         </tr>`;
        //     $("#purchase_order_table tbody").append(newRow);
        //     const $lastRow = $("#purchase_order_table tbody tr:last-child");
        //     initializeValidation($lastRow);
        // });
function updateSerialNumbers() {
    $("#purchase_order_table tbody tr").each(function(index) {
        $(this).find('td:first').text(index + 1);
    });
}

// After adding a new row
$("#add_more_btn").click(function() {
    rowCount++;
    const newRow = `
        <tr>
            <td></td> <!-- Serial number will be updated -->
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
    updateSerialNumbers(); // Update serial numbers
});
        // $(document).on("click", ".remove-row", function() {
        //     $(this).closest("tr").remove();
        //        calculateGrandTotal();
        // });
//     $(document).on("click", ".remove-row", function(e) {
//     const id = $(this).data("id");

//     // If no data-id, it's a new row => just remove
//     if (!id || id === 0) {
//         $(this).closest("tr").remove();
//         calculateGrandTotal();
//         return;
//     }

//     // Else it's an old row => confirm and submit delete form
//     e.preventDefault();

//     Swal.fire({
//         title: 'Are you sure?',
//         text: "This will permanently delete this product row!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: 'Yes, delete it!',
//         cancelButtonText: 'Cancel'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $('#delete_id').val(id);
//             $('#deleteform').submit();
//         }
//     });
// });
// After deleting a row
$(document).on("click", ".remove-row", function(e) {
    const id = $(this).data("id");
    if (!id || id === 0) {
        $(this).closest("tr").remove();
        calculateGrandTotal();
        updateSerialNumbers(); // Update serial numbers
        return;
    }
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this product row!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#delete_id').val(id);
            $('#deleteform').submit();
        }
    });
});
        $("#purchase_order_table tbody tr").each(function() {
            initializeValidation($(this));
            calculateTotalAmount($(this));
        });
    });
</script>
@endsection
