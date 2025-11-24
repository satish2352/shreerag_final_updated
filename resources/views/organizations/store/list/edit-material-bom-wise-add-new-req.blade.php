@extends('admin.layouts.master')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            /* Change 'red' to your desired text color */
            font-size: 12px;
            /* Adjust font size if needed */
            /* Add any other styling as per your design */
        }

        .sparkline12-list {
            margin-bottom: 100px;
        }

        .table-responsive {
            overflow-x: clip !important;
        }

        .custom-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-height {
            height: 280px !important;
        }

        .dropdown-input[readonly] {
            background-color: #fff !important;
            color: #000 !important;
            opacity: 1 !important;
        }

        .custom-dropdown .dropdown-options {
            /* position: absolute; */
            width: 600px !important;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ccc;
            z-index: 999;
            /* max-height: 200px; */
            overflow-y: auto;
        }

        .custom-dropdown .option {
            padding: 6px 10px;
            cursor: pointer;
        }

        .custom-dropdown .option:hover {
            background: #f0f0f0;
        }

        .custom-dropdown .search-box {
            border-bottom: 1px solid #ccc;
            margin-bottom: 5px;
        }

        .margin-bottom {
            margin-bottom: 100px !important;
        }
    </style>

    <div class="">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list margin-bottom">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Issue Product Material</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {{-- SweetAlert Success --}}
                                @if (session('status') === 'success')
                                    <script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: '{{ session('msg') }}',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                    </script>
                                @endif

                                {{-- SweetAlert Error --}}
                                @if (session('status') === 'error')
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: '{{ session('msg') }}',
                                        });
                                    </script>
                                @endif


                                @if (isset($productDetails) && isset($dataGroupedById))
                                    <!-- Display product details -->
                                @else
                                    <div class="alert alert-warning">
                                        No product details available.
                                    </div>
                                @endif

                                <form action="{{ route('update-material-list-bom-wise-new-req', $id) }}" method="POST"
                                    id="addProductForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="business_details_id" id="business_details_id"
                                        value="{{ $id }}">
                                    <input type="hidden" name="id" id="id" value="{{ $productDetails->id }}">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="product_name">Name:</label>
                                            <input type="text" class="form-control" id="name" name="product_name"
                                                value="{{ $productDetails->product_name }}" placeholder="Enter Product Name"
                                                readonly>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="description">Description:</label>
                                            <input type="text" class="form-control" id="description" name="description"
                                                value="{{ $productDetails->description }}" placeholder="Enter Description"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-white repeater" id="purchase_order_table">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Date</th>
                                                    <th>Part Item</th>
                                                    <th>Basic Rate</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Send to Production</th>
                                                    <th>
                                                        <button type="button" class="btn btn-sm btn-bg-colour"
                                                            id="add_more_btn">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataGroupedById as $key => $items)
                                                    @foreach ($items as $index => $item)
                                                        <tr class="item-row">
                                                            <input type="hidden"
                                                                name="addmore[{{ $index }}][detail_id]"
                                                                value="{{ $item->id }}">
                                                            <td>
                                                                <input type="text"
                                                                    name="addmore[{{ $index }}][id]"
                                                                    class="form-control" readonly
                                                                    value="{{ $index + 1 }}">
                                                            </td>
                                                            {{-- <td>
                                                                <div class="custom-dropdown">
                                                                    <input type="hidden"
                                                                        name="addmore[{{ $loop->parent->index }}][part_item_id]"
                                                                        class="part_no" value="{{ $item->part_item_id }}">

                                                                    @php
                                                                        $selectedPartItem = $dataOutputPartItem->firstWhere(
                                                                            'id',
                                                                            $item->part_item_id,
                                                                        );
                                                                    @endphp

                                                                    <input type="text"
                                                                        class="dropdown-input form-control part-item-name"
                                                                        placeholder="Select Part Item..."
                                                                        value="{{ $selectedPartItem ? $selectedPartItem->description : 'Select Part Item' }}"
                                                                        readonly>

                                                                    <div class="dropdown-options dropdown-height"
                                                                        style="display:none;">
                                                                        <input type="text"
                                                                            class="search-box form-control"
                                                                            placeholder="Search...">
                                                                        <div class="options-list">
                                                                            @foreach ($dataOutputPartItem as $data)
                                                                                <div class="option"
                                                                                    data-id="{{ $data->id }}">
                                                                                    {{ $data->description }}
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td> --}}
                                                            <td>
                                                                <input class="form-control"
                                                                    name="addmore[{{ $index }}][updated_at]"
                                                                    type="text" step="any" required
                                                                    value="{{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') }}"
                                                                    readonly>
                                                                <input type="hidden" class="total_amount"
                                                                    name="addmore[{{ $index }}][total_amount]"
                                                                    value="{{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') }}">
                                                            </td>
                                                            <td>
                                                                <div class="custom-dropdown disabled-dropdown">

                                                                    <!-- Correct index -->
                                                                    <input type="hidden"
                                                                        name="addmore[{{ $index }}][part_item_id]"
                                                                        class="part_no" value="{{ $item->part_item_id }}">

                                                                    @php
                                                                        $selectedPartItem = $dataOutputPartItem->firstWhere(
                                                                            'id',
                                                                            $item->part_item_id,
                                                                        );
                                                                    @endphp

                                                                    <!-- Visible Text -->
                                                                    <input type="text"
                                                                        class="dropdown-input form-control part-item-name"
                                                                        placeholder="Select Part Item..."
                                                                        value="{{ $selectedPartItem ? $selectedPartItem->description : '' }}"
                                                                        readonly>

                                                                    <!-- Dropdown -->
                                                                    <div class="dropdown-options dropdown-height"
                                                                        style="display:none;">
                                                                        <input type="text"
                                                                            class="search-box form-control"
                                                                            placeholder="Search...">

                                                                        <div class="options-list">
                                                                            @foreach ($dataOutputPartItem as $data)
                                                                                <div class="option"
                                                                                    data-id="{{ $data->id }}">
                                                                                    {{ $data->description }}
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            {{-- <td>
                                                                <div class="custom-dropdown"
                                                                    data-index="{{ $index }}">
                                                                    <input type="hidden"
                                                                        name="addmore[{{ $index }}][part_item_id]"
                                                                        class="part_no" value="{{ $item->part_item_id }}">
                                                                    @php
                                                                        $selectedPartItem = $dataOutputPartItem->firstWhere(
                                                                            'id',
                                                                            $item->part_item_id,
                                                                        );
                                                                    @endphp
                                                                    <input type="text"
                                                                        class="dropdown-input form-control part-no"
                                                                        placeholder="Select Part Item..." readonly
                                                                        value="{{ $selectedPartItem->description ?? '' }}">

                                                                    <div class="dropdown-options dropdown-height"
                                                                        style="display: none;">
                                                                        <input type="text"
                                                                            class="search-box form-control"
                                                                            placeholder="Search...">
                                                                        <div class="options-list">
                                                                            @foreach ($dataOutputPartItem as $data)
                                                                                <div class="option"
                                                                                    data-id="{{ $data->id }}">
                                                                                    {{ $data->description }}</div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td> --}}
                                                            <td>
                                                                <input class="form-control basic_rate"
                                                                    name="addmore[{{ $index }}][basic_rate]"
                                                                    type="number" step="any" required
                                                                    value="{{ $item->basic_rate }}" readonly>
                                                                <input type="hidden" class="total_amount"
                                                                    name="addmore[{{ $index }}][total_amount]"
                                                                    value="{{ $item->basic_rate * $item->quantity }}">
                                                            </td>

                                                            {{-- @php
    $selectedPart = $dataOutputPartItem->firstWhere('id', $item->part_item_id);
@endphp

<td>
    <div class="custom-dropdown" data-index="{{ $index }}">
        <input type="hidden" name="addmore[{{ $index }}][part_item_id]" class="part_no" value="{{ $item->part_item_id }}">
        <input type="text" class="dropdown-input form-control part-no" 
               placeholder="Select Part Item..." 
               value="{{ $selectedPart ? $selectedPart->description : '' }}" readonly>

        <div class="dropdown-options dropdown-height" style="display: none;">
            <input type="text" class="search-box form-control" placeholder="Search..."> --}}


                                                            {{-- <td>
                                                                <select class="form-control part-no" name="addmore[{{ $index }}][part_item_id]" required>
                                                                    <option value="">Select Part Item</option>
                                                                    @foreach ($dataOutputPartItem as $partItem)
                                                                        <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                            {{ $partItem->description }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td> --}}
                                                            <td>
                                                                <input class="form-control quantity"
                                                                    name="addmore[{{ $index }}][quantity]"
                                                                    type="number" step="any" required
                                                                    value="{{ $item->quantity }}">
                                                                <span class="stock-available"></span>
                                                            </td>
                                                            <td>
                                                                <select class="form-control unit"
                                                                    name="addmore[{{ $index }}][unit]" required>
                                                                    <option value="">Select Unit</option>
                                                                    @foreach ($dataOutputUnitMaster as $unitName)
                                                                        <option value="{{ $unitName->id }}"
                                                                            {{ $unitName->id == $item->unit ? 'selected' : '' }}>
                                                                            {{ $unitName->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="hidden"
                                                                    name="addmore[{{ $index }}][quantity_minus_status]"
                                                                    value="{{ $item->quantity_minus_status }}">
                                                                {{-- @if ($productDetails->material_send_production == 1)
                                                                <input type="hidden" name="addmore[{{ $index }}][material_send_production]" value="1">
                                                                <input type="checkbox" class="material-send-checkbox" name="addmore[{{ $index }}][material_send_production]" value="1" {{ $item->material_send_production == 1 ? 'checked' : '' }} disabled>
                                                                
                                                            @else --}}
                                                                <input type="hidden"
                                                                    name="addmore[{{ $index }}][material_send_production]"
                                                                    value="0">
                                                                <div class="checkbox-wrapper">
                                                                    <input type="checkbox" class="material-send-checkbox"
                                                                        name="addmore[{{ $index }}][material_send_production]"
                                                                        value="1"
                                                                        {{ $item->material_send_production == 1 ? 'checked' : '' }}
                                                                        required>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if ($item->material_send_production == 0)
                                                                    <a data-id="{{ $item->id }}"
                                                                        class="btn btn-danger btn-sm ajax-delete"
                                                                        title="Delete">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @else
                                                                    <button type="button"
                                                                        class="delete-btn btn btn-sm btn-danger remove-row"
                                                                        disabled>
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

                                    <div class="d-flex justify-content-center align-items-center mt-3 mb-5">
                                        <a href="{{ route('list-accepted-design-from-prod') }}"
                                            class="btn btn-white me-3">
                                            Cancel
                                        </a>
                                        <button class="btn btn-sm btn-bg-colour" type="submit" id="saveBtn">
                                            Save Data
                                        </button>
                                    </div>
                                    {{-- <div class="login-btn-inner text-center" >
                                        <button class="btn btn-sm btn-primary" type="submit" id="saveBtn">Save Data</button>
                                    </div> --}}


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
    <form method="POST" action="{{ route('deleted-addmore-store-material-item') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>

   @push('scripts')
    <script>
        $(document).ready(function() {
            // Show/hide dropdown
            $(document).on('click', '.dropdown-input', function() {
                $('.dropdown-options').hide(); // close others
                $(this).siblings('.dropdown-options').toggle();
                $(this).siblings('.dropdown-options').find('.search-box').val('').trigger('input').focus();
            });

            // Search filter
            $(document).on('input', '.search-box', function() {
                const searchTerm = $(this).val().toLowerCase();
                const $options = $(this).siblings('.options-list').find('.option');
                $options.each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(searchTerm));
                });
            });

            // Select option
            // $(document).on('click', '.custom-dropdown .option', function () {
            //     const selectedText = $(this).text();
            //     const selectedId = $(this).data('id');
            //     const $dropdown = $(this).closest('.custom-dropdown');
            //     $dropdown.find('.dropdown-input').val(selectedText);
            //     $dropdown.find('.part_no').val(selectedId);
            //     $dropdown.find('.dropdown-options').hide();
            // });

            function updateTotalAmount($row) {
                let rate = parseFloat($row.find('.basic_rate').val()) || 0;
                let qty = parseFloat($row.find('.quantity').val()) || 0;
                let total = rate * qty;
                $row.find('.total_amount').val(total.toFixed(2)); // store in hidden input
            }

            $("#purchase_order_table").on('input', '.basic_rate, .quantity', function() {
                let $row = $(this).closest('tr');
                updateTotalAmount($row);
            });


            // Select option
            $(document).on('click', '.custom-dropdown .option', function() {
                const selectedText = $(this).text();
                const selectedId = $(this).data('id');
                const $dropdown = $(this).closest('.custom-dropdown');
                const $row = $dropdown.closest('tr');

                // Set the selected text & ID
                $dropdown.find('.dropdown-input').val(selectedText);
                $dropdown.find('.part_no').val(selectedId);
                $dropdown.find('.dropdown-options').hide();

                // Fetch the basic rate via AJAX
                $.ajax({
                    url: '{{ route('get-part-item-rate') }}',
                    type: 'GET',
                    data: {
                        part_item_id: selectedId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $row.find('.basic_rate').val(response.basic_rate);
                            updateTotalAmount($row);
                        } else {
                            $row.find('.basic_rate').val('');
                        }
                    }
                });
            });

            // Close on click outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.custom-dropdown').length) {
                    $('.dropdown-options').hide();
                }
            });
        });
    </script>

    <script>
        function removeRow(button) {
            // Find the row containing the button
            var row = button.closest('tr');
            // Remove the row from the table
            row.remove();
        }
    </script>
    <script>
        $(document).ready(function() {
            // Initialize form validation
            $("#addProductForm").validate({
                submitHandler: function(form) {
                    // Perform stock validation for each row
                    var isValid = true;
                    $('#purchase_order_table tbody tr').each(function() {
                        // var partItemId = $(this).find('select[name*="[part_item_id]"]').val();
                        // var quantity = $(this).find('input[name*="[quantity]"]').val();
                        var partItemId = $(this).find('input.part_no').val();
                        var quantity = $(this).find('.quantity').val();

                        // Add your stock validation logic here
                        // If stock is insufficient, set isValid to false
                    });
                    if (isValid) {
                        form.submit();
                    } else {
                        alert('Please ensure all items have sufficient stock.');
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const purchaseOrderTable = $("#purchase_order_table");
            const validationMessages = {
                part_no: "Please select a Part Item",
                quantity: {
                    required: "Please enter the Quantity",
                    digits: "Quantity must be a valid number",
                },
                unit: "Please select a Unit",
                materialSend: "Please check this box if materials will be sent for production",
            };

            // Initialize validation rules
            function initializeValidation(context) {
                $(context).find('.part_no').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: validationMessages.part_no
                        }
                    });
                });
                $(context).find('.quantity').each(function() {
                    $(this).rules("add", {
                        required: true,
                        digits: true,
                        messages: validationMessages.quantity
                    });
                });
                $(context).find('.unit').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: validationMessages.unit
                        }
                    });
                });
                $(context).find('.material-send-checkbox').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: validationMessages.materialSend
                        }
                    });
                });
            }

            let rowCount = purchaseOrderTable.find("tbody tr").length;

            // Function to check stock availability

            function checkStock($row) {
                const quantity = $row.find('.quantity').val();
                const partItemId = $row.find('input.part_no').val(); // ✅ FIXED
                const materialSendProduction = $row.find('.material-send-checkbox').is(':checked') ? 1 : 0;
                const quantityMinusStatus = $row.find('input[name*="quantity_minus_status"]').val();
                const stockAvailableMessage = $row.find('.stock-available');

                // Reset Save button to enabled
                $("#saveBtn").prop("disabled", false);

                if (materialSendProduction === 1 && quantityMinusStatus === 'done') {
                    stockAvailableMessage.text('Stock check skipped').css('color', 'blue');
                    return;
                }

                if (partItemId && quantity) {
                    $.ajax({
                        url: '{{ route('check-stock-quantity') }}',
                        type: 'GET',
                        data: {
                            part_item_id: partItemId,
                            quantity: quantity,
                            material_send_production: materialSendProduction,
                            quantity_minus_status: quantityMinusStatus
                        },
                        success: function(response) {
                            if (response.status === 'error') {
                                // ❌ Insufficient stock → Disable Save Button
                                $("#saveBtn").prop("disabled", true);
                                stockAvailableMessage.text('Insufficient stock. Available: ' + response
                                        .available_quantity)
                                    .css('color', 'red');
                            } else {
                                stockAvailableMessage.text('Stock is sufficient').css('color', 'green');
                            }
                        },
                        // error: function () {
                        //     stockAvailableMessage.text('Error checking stock').css('color', 'red');
                        // }
                        error: function(xhr) {
                            let msg = 'Error checking stock';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            stockAvailableMessage.text(msg).css('color', 'red');
                        }

                    });
                } else {
                    stockAvailableMessage.text('');
                }
            }




            // Add new row functionality
            $("#add_more_btn").click(function() {
                rowCount++;
                const newRow = `
            <tr class="item-row">
                  <input type="hidden" name="addmore[${rowCount}][detail_id]" value="">
                <td>
                    <input type="text" name="addmore[${rowCount}][id]" class="form-control" readonly value="${rowCount}">
                </td>
        <td class="text-center">-</td>       
<td>
    <div class="custom-dropdown">
        <input type="hidden" name="addmore[${rowCount}][part_item_id]" class="part_no" value="">
        <input type="text" class="dropdown-input form-control" placeholder="Select Part Item..." readonly>

        <div class="dropdown-options dropdown-height" style="display:none;">
            <input type="text" class="search-box form-control" placeholder="Search...">
            <div class="options-list">
                @foreach ($dataOutputPartItem as $data)
                    <div class="option" data-id="{{ $data->id }}">{{ $data->description }}</div>
                @endforeach
            </div>
        </div>
    </div>
</td>



                                                                   <td>
    <input class="form-control basic_rate" name="addmore[${rowCount}][basic_rate]" type="number" step="any" readonly required>
    <input type="hidden" class="total_amount" name="addmore[${rowCount}][items_used_total_amount]" value="0">
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
                    <button type="button" class="btn btn-danger remove-row btn-sm delete-btn">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
                purchaseOrderTable.find("tbody").append(newRow);

                const addedRow = purchaseOrderTable.find("tbody tr").last();
                initializeValidation(addedRow);
            });

            // Remove row functionality
            purchaseOrderTable.on("click", ".remove-row", function() {
                $(this).closest('tr').remove();
            });

            // Trigger stock check on quantity input change
            purchaseOrderTable.on('input', '.quantity', function() {
                const $row = $(this).closest('tr');
                checkStock($row);
            });

            // Trigger stock check on checkbox change
            purchaseOrderTable.on('change', '.material-send-checkbox', function() {
                const $row = $(this).closest('tr');
                if ($(this).is(':checked')) {
                    checkStock($row);
                } else {
                    $row.find('.stock-available').text('');
                }
            });

            // Submit form with confirmation
            $("form").validate({
                ignore: ".delete-btn", // Ignore delete buttons during validation
                errorPlacement: function(error, element) {
                    if (element.hasClass('part_no')) {
                        error.insertAfter(element.closest('.custom-dropdown'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'You want to send this material to the Production Department?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            // Initialize validation on existing rows
            initializeValidation(purchaseOrderTable.find("tbody tr"));
        });


        // Disable dropdown click for existing rows
        $(document).on('click', '.disabled-dropdown .dropdown-input', function(e) {
            e.stopImmediatePropagation();
            return false;
        });

        // Disable selecting options for disabled dropdown
        $(document).on('click', '.disabled-dropdown .option', function(e) {
            e.stopImmediatePropagation();
            return false;
        });
    </script>

    <script>
        $(document).ready(function() {

            // AJAX Delete
            $(document).on("click", ".ajax-delete", function() {
                let deleteId = $(this).data("id");
                let row = $(this).closest("tr");

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to delete this item?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Delete",
                    cancelButtonText: "No",
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ route('deleted-addmore-store-material-item') }}",
                            type: "POST",
                            data: {
                                delete_id: deleteId,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {

                                if (response.status === 'success') {
                                    Swal.fire("Deleted!", response.msg, "success");

                                    // Remove Row from UI
                                    row.remove();
                                } else {
                                    Swal.fire("Error", response.msg, "error");
                                }
                            },
                            error: function() {
                                Swal.fire("Error", "Server Error Occurred!", "error");
                            }
                        });

                    }


                });
            });
        });
    </script>
    @endpush
@endsection
