@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
    }
    label.error {
        color: red; /* Change 'red' to your desired text color */
        font-size: 12px; /* Adjust font size if needed */
        /* Add any other styling as per your design */
    }
    .sparkline12-list{
        margin-bottom: 100px;
    }
    
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Issue Product Material</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{-- @if (Session::get('status') == 'success')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-success " id="success-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                </div>
                            @endif
                            @if (Session::get('status') == 'error')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-danger " id="error-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                    </div>
                                </div>
                            @endif --}}
                            {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    @if (Session::get('status') == 'error')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-danger " id="error-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                        </div>
                                    </div>
                                @endif --}}
                                @if(session('status') === 'error')
    <div class="alert alert-danger">
        {{ session('msg') }}
    </div>
@endif

@if(isset($productDetails) && isset($dataGroupedById))
    <!-- Display product details -->
@else
    <div class="alert alert-warning">
        No product details available.
    </div>
@endif

                                <form action="{{ route('update-material-list-bom-wise-new-req', $id) }}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">
                                    <input type="hidden" name="id" id="id" value="{{ $productDetails->id }}">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="product_name">Name:</label>
                                            <input type="text" class="form-control" id="name" name="product_name" value="{{ $productDetails->product_name }}" placeholder="Enter Product Name" readonly>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="description">Description:</label>
                                            <input type="text" class="form-control" id="description" name="description" value="{{ $productDetails->description }}" placeholder="Enter Description" readonly>
                                        </div>
                                    </div>
                                
                                    <div class="table-responsive">
                                        <table class="table table-hover table-white repeater" id="purchase_order_table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Part Item</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Send to Production</th>
                                                    <th>
                                                        <button type="button" class="btn btn-sm btn-success" id="add_more_btn">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataGroupedById as $key => $items)
                                                    @foreach ($items as $index => $item)
                                                        <tr class="item-row">
                                                            <td>
                                                                <input type="text" name="addmore[{{ $index }}][id]" class="form-control" readonly value="{{ $index + 1 }}">
                                                            </td>
                                                            <td>
                                                                <select class="form-control part-no" name="addmore[{{ $index }}][part_item_id]" required>
                                                                    <option value="">Select Part Item</option>
                                                                    @foreach ($dataOutputPartItem as $partItem)
                                                                        <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                            {{ $partItem->description }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input class="form-control quantity" name="addmore[{{ $index }}][quantity]" type="number" step="any" required value="{{ $item->quantity }}">
                                                                <span class="stock-available"></span>
                                                            </td>
                                                            <td>
                                                                <select class="form-control unit" name="addmore[{{ $index }}][unit]" required>
                                                                    <option value="">Select Unit</option>
                                                                    @foreach ($dataOutputUnitMaster as $unitName)
                                                                        <option value="{{ $unitName->id }}" {{ $unitName->id == $item->unit ? 'selected' : '' }}>
                                                                            {{ $unitName->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="addmore[{{ $index }}][quantity_minus_status]" value="{{ $item->quantity_minus_status }}" >
                                                                {{-- @if($productDetails->material_send_production == 1)
                                                                <input type="hidden" name="addmore[{{ $index }}][material_send_production]" value="1">
                                                                <input type="checkbox" class="material-send-checkbox" name="addmore[{{ $index }}][material_send_production]" value="1" {{ $item->material_send_production == 1 ? 'checked' : '' }} disabled>
                                                                
                                                            @else --}}
                                                            <input type="hidden" name="addmore[{{ $index }}][material_send_production]" value="0">
                                                            <input type="checkbox" class="material-send-checkbox" name="addmore[{{ $index }}][material_send_production]" value="1" {{ $item->material_send_production == 1 ? 'checked' : '' }} required>
                                                                                                                        {{-- @endif --}}




                                                                
                                                            </td>
                                                            <td>
                                                                @if($productDetails->quantity_minus_status == 'pending')
                                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                                @else
                                                                <button type="button" class="btn btn-sm btn-danger remove-row" disabled>
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                
                                    <div class="login-btn-inner">
                                        <button class="btn btn-sm btn-primary" type="submit" id="saveBtn">Save Data</button>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

{{-- <script>
    $(document).ready(function() {
        // Initialize jQuery Validation
        var validator = $("#addProductForm").validate({
            ignore: [],
            rules: {
                'addmore[0][part_item_id]': {
                    required: true,
                },
                'addmore[0][quantity]': {
                    required: true,
                    digits: true,
                },
                'addmore[0][unit]': {
                    required: true,
                },
                'addmore[0][material_send_production]': {
                    required: true // Add required rule for the checkbox
                },
            },
            messages: {
                'addmore[0][part_item_id]': {
                    required: "Please select a Part Item",
                },
                'addmore[0][quantity]': {
                    required: "Please enter the Quantity",
                    digits: "Quantity must be a valid number",
                },
                'addmore[0][unit]': {
                    required: "Please select a Unit",
                },
                'addmore[0][material_send_production]': {
                    required: "Please check this box if materials will be sent for production" // Custom message for the checkbox
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("type") == "checkbox") {
                    // Insert error after the checkbox
                    error.insertAfter(element.closest('td'));
                } else {
                    // Default placement for other elements
                    error.insertAfter(element);
                }
            }
        });
        // Add more rows dynamically
        var i_count = $("#purchase_order_table tbody tr").length;
        $(document).ready(function() {
    var i_count = $("#purchase_order_table tbody tr").length;

    // Add new row
    $("#add_more_btn").click(function() {
        i_count++;
        var newRow = `
            <tr>
                <td>
                    <input type="text" name="addmore[${i_count}][id]" class="form-control" readonly value="${i_count}">
                </td>
                <td>
                    <select class="form-control part-no" name="addmore[${i_count}][part_item_id]" required>
                        <option value="">Select Part Item</option>
                        @foreach ($dataOutputPartItem as $data)
                            <option value="{{ $data->id }}">{{ $data->description }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-control quantity" name="addmore[${i_count}][quantity]" type="number" step="any" required>
                </td>
                <td>
                    <select class="form-control unit" name="addmore[${i_count}][unit]" required>
                        <option value="">Select Unit</option>
                        @foreach ($dataOutputUnitMaster as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="addmore[${i_count}][material_send_production]" value="1">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        $("#purchase_order_table tbody").append(newRow);
    });

    // Remove row
    $("#purchase_order_table").on("click", ".remove-row", function() {
        $(this).closest('tr').remove();
    });
});
        // Remove a row
        $("#purchase_order_table").on("click", ".remove-row", function() {
            $(this).closest('tr').remove();
            validator.resetForm(); // Reset validation state
        });
    
        // Initialize validation for dynamically added fields
        function initializeValidation(context) {
            $(context).find('.part-no').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please select a Part Item",
                    }
                });
            });
            $(context).find('.quantity').each(function() {
                $(this).rules("add", {
                    required: true,
                    digits: true,
                    messages: {
                        required: "Please enter the Quantity",
                        digits: "Quantity must be a valid number",
                    }
                });
            });
            $(context).find('.unit').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please select a Unit",
                    }
                });
            });
            $(context).find('.material_send_production').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please check this box if materials will be sent for production",
                    }
                });
            });
        }
    
        initializeValidation($("#purchase_order_table"));
    });
    </script> --}}
    
    <script>

// $(document).ready(function () {
//     $(document).on('input', '.quantity, .part-no', function () {
//         var $row = $(this).closest('tr'); 
//         var quantity = $row.find('.quantity').val(); 
//         var partItemId = $row.find('select[name*="part_item_id"]').val(); 
//         var stockAvailableMessage = $row.find('.stock-available'); 
//         if (partItemId && quantity) {
//             $.ajax({
//                 url: '{{ route('check-stock-quantity') }}',
//                 type: 'GET',
//                 data: {
//                     part_item_id: partItemId,
//                     quantity: quantity
//                 },
                
//                 success: function (response) {
//                     console.log(response, "responseresponseresponseresponse");
                    
//                     if (response.status === 'error') {
//                         stockAvailableMessage.text('Insufficient stock. Available: ' + response.available_quantity);
//                         stockAvailableMessage.css('color', 'red');
//                         $row.find('.quantity').val(''); 
//                     } else {
//                         stockAvailableMessage.text('Stock is sufficient');
//                         stockAvailableMessage.css('color', 'green');
//                     }
//                 },
//                 error: function () {
//                     stockAvailableMessage.text('Error checking stock');
//                     stockAvailableMessage.css('color', 'red');
//                 }
//             });
//         } else {
//             stockAvailableMessage.text(''); 
//         }
//     });

//     var i_count = $("#purchase_order_table tbody tr").length;
//     $("#add_more_btn").click(function () {
//         i_count++;
//         var newRow = `
//             <tr class="item-row">
//                 <td>
//                     <input type="text" name="addmore[${i_count}][id]" class="form-control" readonly value="${i_count + 1}">
//                 </td>
//                 <td>
//                     <select class="form-control part-no" name="addmore[${i_count}][part_item_id]" required>
//                         <option value="">Select Part Item</option>
//                         @foreach ($dataOutputPartItem as $data)
//                             <option value="{{ $data->id }}">{{ $data->description }}</option>
//                         @endforeach
//                     </select>
//                 </td>
//                 <td>
//                     <input class="form-control quantity" name="addmore[${i_count}][quantity]" type="number" step="any" required>
//                     <span class="stock-available"></span>
//                 </td>
//                 <td>
//                     <select class="form-control unit" name="addmore[${i_count}][unit]" required>
//                         <option value="">Select Unit</option>
//                         @foreach ($dataOutputUnitMaster as $data)
//                             <option value="{{ $data->id }}">{{ $data->name }}</option>
//                         @endforeach
//                     </select>
//                 </td>
//                 <td>
//                     <input type="checkbox" name="addmore[${i_count}][material_send_production]" value="1">
//                 </td>
//                 <td>
//                     <button type="button" class="btn btn-sm btn-danger remove-row">
//                         <i class="fa fa-trash"></i>
//                     </button>
//                 </td>
//             </tr>`;
//         $("#purchase_order_table tbody").append(newRow);
//     });

//     $("#purchase_order_table").on("click", ".remove-row", function () {
//         $(this).closest('tr').remove();
//     });
// });
// $(document).ready(function () {
//     // On change of part item or quantity, check stock
//     $(document).on('input', '.quantity, .part-no', function () {
//         var $row = $(this).closest('tr'); // Get the current row
//         var quantity = $row.find('.quantity').val(); // Get quantity value
//         var partItemId = $row.find('select[name*="part_item_id"]').val(); // Get selected part item ID
//         var stockAvailableMessage = $row.find('.stock-available'); // Target the stock message span

//         if (partItemId && quantity) {
//             // AJAX request to check stock
//             $.ajax({
//                 url: '{{ route('check-stock-quantity') }}',
//                 type: 'GET',
//                 data: {
//                     part_item_id: partItemId,
//                     quantity: quantity
//                 },
//                 success: function (response) {
//                     if (response.status === 'error') {
//                         stockAvailableMessage.text('Insufficient stock. Available: ' + response.available_quantity);
//                         stockAvailableMessage.css('color', 'red');
//                         $row.find('.quantity').val(''); // Clear the input if stock is insufficient
//                     } else {
//                         stockAvailableMessage.text('Stock is sufficient');
//                         stockAvailableMessage.css('color', 'green');
//                     }
//                 },
//                 error: function () {
//                     stockAvailableMessage.text('Error checking stock');
//                     stockAvailableMessage.css('color', 'red');
//                 }
//             });
//         } else {
//             stockAvailableMessage.text(''); // Clear message if inputs are incomplete
//         }
//     });

//     // Add new row functionality
//     var i_count = $("#purchase_order_table tbody tr").length;
//     $("#add_more_btn").click(function () {
//         i_count++;
//         var newRow = `
//             <tr class="item-row">
//                 <td>
//                     <input type="text" name="addmore[${i_count}][id]" class="form-control" readonly value="${i_count + 1}">
//                 </td>
//                 <td>
//                     <select class="form-control part-no" name="addmore[${i_count}][part_item_id]" required>
//                         <option value="">Select Part Item</option>
//                         @foreach ($dataOutputPartItem as $data)
//                             <option value="{{ $data->id }}">{{ $data->description }}</option>
//                         @endforeach
//                     </select>
//                 </td>
//                 <td>
//                     <input class="form-control quantity" name="addmore[${i_count}][quantity]" type="number" step="any" required>
//                     <span class="stock-available"></span>
//                 </td>
//                 <td>
//                     <select class="form-control unit" name="addmore[${i_count}][unit]" required>
//                         <option value="">Select Unit</option>
//                         @foreach ($dataOutputUnitMaster as $data)
//                             <option value="{{ $data->id }}">{{ $data->name }}</option>
//                         @endforeach
//                     </select>
//                 </td>
//                 <td>
//             <input type="hidden" name="addmore[${i_count}][material_send_production]" value="1">
//             <input type="checkbox" class="material-send-checkbox" name="addmore[${i_count}][material_send_production]" value="1">
//         </td>
//                 <td>
//                     <button type="button" class="btn btn-sm btn-danger remove-row">
//                         <i class="fa fa-trash"></i>
//                     </button>
//                 </td>
//             </tr>`;
//         $("#purchase_order_table tbody").append(newRow);
//     });

//     // Remove row functionality
//     $("#purchase_order_table").on("click", ".remove-row", function () {
//         $(this).closest('tr').remove();
//     });

//     // Disable checkbox when ticked
//     $(document).on('change', '.material-send-checkbox', function () {
//         if ($(this).is(':checked')) {
//             // $(this).prop('disabled', true); // Disable the checkbox
//         }
//     });
// });
// Update stock availability message on checkbox tick
// On change of quantity input, check stock
// Function to check stock availability
// =========
// function checkStock($row) {
//     var quantity = $row.find('.quantity').val(); // Get quantity value
//     var partItemId = $row.find('select[name*="part_item_id"]').val(); // Get selected part item ID
//     var stockAvailableMessage = $row.find('.stock-available'); // Target the stock message span

//     if (partItemId && quantity) {
//         // AJAX request to check stock
//         $.ajax({
//             url: '{{ route('check-stock-quantity') }}',
//             type: 'GET',
//             data: {
//                 part_item_id: partItemId,
//                 quantity: quantity
//             },
//             success: function (response) {
//                 if (response.status === 'error') {
//                     stockAvailableMessage.text('Insufficient stock. Available: ' + response.available_quantity);
//                     stockAvailableMessage.css('color', 'red');
//                 } else {
//                     stockAvailableMessage.text('Stock is sufficient');
//                     stockAvailableMessage.css('color', 'green');
//                 }
//             },
//             error: function () {
//                 stockAvailableMessage.text('Error checking stock');
//                 stockAvailableMessage.css('color', 'red');
//             }
//         });
//     } else {
//         stockAvailableMessage.text(''); // Clear message if inputs are incomplete
//     }
// }

// // Trigger stock check on quantity input change
// $(document).on('input', '.quantity', function () {
//     var $row = $(this).closest('tr'); // Get the current row
//     checkStock($row); // Call the stock check function
// });

// // Trigger stock check on checkbox tick
// $(document).on('change', '.material-send-checkbox', function () {
//     var $row = $(this).closest('tr'); // Get the current row
//     if ($(this).is(':checked')) {
//         checkStock($row); // Call the stock check function when checkbox is ticked
//     } else {
//         $row.find('.stock-available').text(''); // Clear the message when checkbox is unticked
//     }
// });
// ===============
// $(document).ready(function () {
//     var i_count = $("#purchase_order_table tbody tr").length; // Initial row count

//     // Function to check stock
//     function checkStock($row) {
//         var quantity = $row.find('.quantity').val(); // Get quantity value
//         var partItemId = $row.find('select[name*="part_item_id"]').val(); // Get selected part item ID
//         var stockAvailableMessage = $row.find('.stock-available'); // Target the stock message span

//         if (partItemId && quantity) {
//             // AJAX request to check stock
//             $.ajax({
//                 url: '{{ route('check-stock-quantity') }}',
//                 type: 'GET',
//                 data: {
//                     part_item_id: partItemId,
//                     quantity: quantity
//                 },
//                 success: function (response) {
//                     if (response.status === 'error') {
//                         stockAvailableMessage.text('Insufficient stock. Available: ' + response.available_quantity);
//                         stockAvailableMessage.css('color', 'red');
//                     } else {
//                         stockAvailableMessage.text('Stock is sufficient');
//                         stockAvailableMessage.css('color', 'green');
//                     }
//                 },
//                 error: function () {
//                     stockAvailableMessage.text('Error checking stock');
//                     stockAvailableMessage.css('color', 'red');
//                 }
//             });
//         } else {
//             stockAvailableMessage.text(''); // Clear message if inputs are incomplete
//         }
//     }

//     // Add new row functionality
//     $("#add_more_btn").click(function () {
//         i_count++;
//         var newRow = `
//             <tr class="item-row">
//                 <td>
//                     <input type="text" name="addmore[${i_count}][id]" class="form-control" readonly value="${i_count + 1}">
//                 </td>
//                 <td>
//                     <select class="form-control part-no" name="addmore[${i_count}][part_item_id]" required>
//                         <option value="">Select Part Item</option>
//                         @foreach ($dataOutputPartItem as $data)
//                             <option value="{{ $data->id }}">{{ $data->description }}</option>
//                         @endforeach
//                     </select>
//                 </td>
//                 <td>
//                     <input class="form-control quantity" name="addmore[${i_count}][quantity]" type="number" step="any" required>
//                     <span class="stock-available"></span>
//                 </td>
//                 <td>
//                     <select class="form-control unit" name="addmore[${i_count}][unit]" required>
//                         <option value="">Select Unit</option>
//                         @foreach ($dataOutputUnitMaster as $data)
//                             <option value="{{ $data->id }}">{{ $data->name }}</option>
//                         @endforeach
//                     </select>
//                 </td>
//                 <td>
//                     <input type="hidden" name="addmore[${i_count}][material_send_production]" value="0">
//                     <input type="checkbox" class="material-send-checkbox" name="addmore[${i_count}][material_send_production]" value="1">
//                 </td>
//                 <td>
//                     <button type="button" class="btn btn-sm btn-danger remove-row">
//                         <i class="fa fa-trash"></i>
//                     </button>
//                 </td>
//             </tr>`;
//         $("#purchase_order_table tbody").append(newRow); // Append new row
//     });

//     // Remove row functionality
//     $("#purchase_order_table").on("click", ".remove-row", function () {
//         $(this).closest('tr').remove(); // Remove the row
//     });

//     // Trigger stock check on quantity input change
//     $(document).on('input', '.quantity', function () {
//         var $row = $(this).closest('tr'); // Get the current row
//         checkStock($row); // Call the stock check function
//     });

//     // Trigger stock check on checkbox tick
//     $(document).on('change', '.material-send-checkbox', function () {
//         var $row = $(this).closest('tr'); // Get the current row
//         if ($(this).is(':checked')) {
//             checkStock($row); // Call the stock check function when checkbox is ticked
//         } else {
//             $row.find('.stock-available').text(''); // Clear the message when checkbox is unticked
//         }
//     });
// });
$(document).ready(function () {
    const purchaseOrderTable = $("#purchase_order_table");
    const validationMessages = {
        partNo: "Please select a Part Item",
        quantity: {
            required: "Please enter the Quantity",
            digits: "Quantity must be a valid number",
        },
        unit: "Please select a Unit",
        materialSend: "Please check this box if materials will be sent for production",
    };

    // Initialize validation rules
    function initializeValidation(context) {
        $(context).find('.part-no').each(function () {
            $(this).rules("add", {
                required: true,
                messages: { required: validationMessages.partNo }
            });
        });
        $(context).find('.quantity').each(function () {
            $(this).rules("add", {
                required: true,
                digits: true,
                messages: validationMessages.quantity
            });
        });
        $(context).find('.unit').each(function () {
            $(this).rules("add", {
                required: true,
                messages: { required: validationMessages.unit }
            });
        });
        $(context).find('.material-send-checkbox').each(function () {
            $(this).rules("add", {
                required: true,
                messages: { required: validationMessages.materialSend }
            });
        });
    }

    let rowCount = purchaseOrderTable.find("tbody tr").length;

    // Function to check stock availability
    function checkStock($row) {
        const quantity = $row.find('.quantity').val();
        const partItemId = $row.find('select[name*="part_item_id"]').val();
        const stockAvailableMessage = $row.find('.stock-available');

        if (partItemId && quantity) {
            $.ajax({
                url: '{{ route("check-stock-quantity") }}',
                type: 'GET',
                data: { part_item_id: partItemId, quantity: quantity },
                success: function (response) {
                    if (response.status === 'error') {
                        stockAvailableMessage.text('Insufficient stock. Available: ' + response.available_quantity)
                            .css('color', 'red');
                    } else {
                        stockAvailableMessage.text('Stock is sufficient').css('color', 'green');
                    }
                },
                error: function () {
                    stockAvailableMessage.text('Error checking stock').css('color', 'red');
                }
            });
        } else {
            stockAvailableMessage.text('');
        }
    }

    // Add new row functionality
    $("#add_more_btn").click(function () {
        rowCount++;
        const newRow = `
            <tr class="item-row">
                <td>
                    <input type="text" name="addmore[${rowCount}][id]" class="form-control" readonly value="${rowCount}">
                </td>
                <td>
                    <select class="form-control part-no" name="addmore[${rowCount}][part_item_id]" required>
                        <option value="">Select Part Item</option>
                        @foreach ($dataOutputPartItem as $data)
                            <option value="{{ $data->id }}">{{ $data->description }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-control quantity" name="addmore[${rowCount}][quantity]" type="number" step="any" required>
                    <span class="stock-available"></span>
                </td>
                <td>
                    <select class="form-control unit" name="addmore[${rowCount}][unit]" required>
                        <option value="">Select Unit</option>
                        @foreach ($dataOutputUnitMaster as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="hidden" name="addmore[${rowCount}][material_send_production]" value="0">
                    <input type="checkbox" class="material-send-checkbox" name="addmore[${rowCount}][material_send_production]" value="1">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        purchaseOrderTable.find("tbody").append(newRow);

        const addedRow = purchaseOrderTable.find("tbody tr").last();
        initializeValidation(addedRow);
    });

    // Remove row functionality
    purchaseOrderTable.on("click", ".remove-row", function () {
        $(this).closest('tr').remove();
    });

    // Trigger stock check on quantity input change
    purchaseOrderTable.on('input', '.quantity', function () {
        const $row = $(this).closest('tr');
        checkStock($row);
    });

    // Trigger stock check on checkbox change
    purchaseOrderTable.on('change', '.material-send-checkbox', function () {
        const $row = $(this).closest('tr');
        if ($(this).is(':checked')) {
            checkStock($row);
        } else {
            $row.find('.stock-available').text('');
        }
    });

    // Submit form with confirmation
    $("form").validate({
        submitHandler: function (form) {
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'You want to send this material to the Production Department?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    // Initialize validation on existing rows
    initializeValidation(purchaseOrderTable.find("tbody tr"));
});



//         $(document).ready(function () {
//             // On change of part item or quantity, check stock
//             $(document).on('input', '.quantity, .part-no', function () {
//     var $row = $(this).closest('tr');
//     var quantity = $row.find('.quantity').val();
//     var partItemId = $row.find('.part-no').val();
//     var stockAvailableMessage = $row.find('.stock-available');

//     if (partItemId && quantity) {
//         $.ajax({
//             url: '{{ route('check-stock-quantity') }}',
//             type: 'GET',
//             data: {
//                 part_item_id: partItemId,
//                 quantity: quantity
//             },
//             success: function (response) {
//                 if (response.status === 'error') {
//                     stockAvailableMessage.text('Insufficient stock. Available: ' + response.available_quantity);
//                     stockAvailableMessage.css('color', 'red');
//                     $row.find('.quantity').val(''); // Clear the input if stock is insufficient
//                 } else {
//                     stockAvailableMessage.text('Stock is sufficient');
//                     stockAvailableMessage.css('color', 'green');
//                 }
//             },
//             error: function () {
//                 stockAvailableMessage.text('Error checking stock');
//                 stockAvailableMessage.css('color', 'red');
//             }
//         });
//     }
// });

            // $(document).on('input', '.quantity, .part-no', function () {
            //     var quantity = $(this).val();
              
                
            //     var partItemId = $(this).closest('tr').find('select[name*="part_item_id"]').val();
            //     var stockAvailableMessage = $(this).closest('td').find('.stock-available');
            //     console.log(stockAvailableMessage, "quantityquantity");
            //     if (partItemId && quantity) {
            //         // AJAX request to check stock
            //         $.ajax({

            //             url: '{{ route('check-stock-quantity') }}',
            //         type: 'GET',
            //         data: {
            //             part_item_id: partItemId,
            //             quantity: quantity
            //         },
                  
            //             success: function (response) {
            //                 if (response.status === 'error') {
            //                     stockAvailableMessage.text('Insufficient stock. Available: ' + response.available_quantity);
            //                     stockAvailableMessage.css('color', 'red');
            //                     $(this).val(''); // Clear the input if stock is insufficient
            //                 } else {
            //                     stockAvailableMessage.text('Stock is sufficient');
            //                     stockAvailableMessage.css('color', 'green');
            //                 }
            //             },
            //             error: function () {
            //                 stockAvailableMessage.text('Error checking stock');
            //                 stockAvailableMessage.css('color', 'red');
            //             }
            //         });
            //     }
            // });
    
            // Add new row functionality
//             var i_count = $("#purchase_order_table tbody tr").length;
//             $("#add_more_btn").click(function () {
//                 i_count++;
//                 var newRow = `
//                     <tr class="item-row">
//                         <td>
//                             <input type="text" name="addmore[${i_count}][id]" class="form-control" readonly value="${i_count + 1}">
//                         </td>
//                         <td>
//                             <select class="form-control part-no" name="addmore[${i_count}][part_item_id]" required>
//                                 <option value="">Select Part Item</option>
//                                 @foreach ($dataOutputPartItem as $data)
//                                     <option value="{{ $data->id }}">{{ $data->description }}</option>
//                                 @endforeach
//                             </select>
//                         </td>
//                         <td>
//                             <input class="form-control quantity" name="addmore[${i_count}][quantity]" type="number" step="any" required>
//                             <span class="stock-available"></span>
//                         </td>
//                         <td>
//                             <select class="form-control unit" name="addmore[${i_count}][unit]" required>
//                                 <option value="">Select Unit</option>
//                                 @foreach ($dataOutputUnitMaster as $data)
//                                     <option value="{{ $data->id }}">{{ $data->name }}</option>
//                                 @endforeach
//                             </select>
//                         </td>
//                         <td>
//                             <input type="checkbox" name="addmore[${i_count}][material_send_production]" value="1">
//                         </td>
//                         <td>
//                             <button type="button" class="btn btn-sm btn-danger remove-row">
//                                 <i class="fa fa-trash"></i>
//                             </button>
//                         </td>
//                     </tr>`;
//                 $("#purchase_order_table tbody").append(newRow);
//             });
    
//             // Remove row functionality
//             var i_count = $("#purchase_order_table tbody tr").length;
// $("#add_more_btn").click(function () {
//     i_count++;
//     var newRow = `
//         <tr class="item-row">
//             <td>
//                 <input type="text" name="addmore[${i_count}][id]" class="form-control" readonly value="${i_count}">
//             </td>
//             <td>
//                 <select class="form-control part-no" name="addmore[${i_count}][part_item_id]" required>
//                     <option value="">Select Part Item</option>
//                     @foreach ($dataOutputPartItem as $data)
//                         <option value="{{ $data->id }}">{{ $data->description }}</option>
//                     @endforeach
//                 </select>
//             </td>
//             <td>
//                 <input class="form-control quantity" name="addmore[${i_count}][quantity]" type="number" step="any" required>
//                 <span class="stock-available"></span>
//             </td>
//             <td>
//                 <select class="form-control unit" name="addmore[${i_count}][unit]" required>
//                     <option value="">Select Unit</option>
//                     @foreach ($dataOutputUnitMaster as $data)
//                         <option value="{{ $data->id }}">{{ $data->name }}</option>
//                     @endforeach
//                 </select>
//             </td>
//             <td>
//                 <input type="checkbox" name="addmore[${i_count}][material_send_production]" value="1">
//             </td>
//             <td>
//                 <button type="button" class="btn btn-sm btn-danger remove-row">
//                     <i class="fa fa-trash"></i>
//                 </button>
//             </td>
//         </tr>`;
//     $("#purchase_order_table tbody").append(newRow);
// });

            // $("#purchase_order_table").on("click", ".remove-row", function () {
            //     $(this).closest('tr').remove();
            // });
        // });
    </script>

@endsection
