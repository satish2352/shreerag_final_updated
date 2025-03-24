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

                                <form action="{{ route('update-material-list-bom-wise-new-req', $id) }}" method="POST" id="addProductForm" enctype="multipart/form-data" >
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
                                                                
                                                            </td>
                                                            <td>
                                                                @if($item->material_send_production == 0)
                                                                <a data-id="{{ $item->id }}"
                                                                    class="delete-btn btn btn-danger m-1"
                                                                    title="Delete"><i
                                                                        class="fas fa-archive"></i></a>
                                                                @else
                                                                <button type="button" class="delete-btn btn btn-sm btn-danger remove-row" disabled>
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
<form method="POST" action="{{ route('deleted-addmore-store-material-item') }}" id="deleteform">
    @csrf
    <input type="hidden" name="delete_id" id="delete_id" value="">
</form>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
<script>
    function removeRow(button) {
        // Find the row containing the button
        var row = button.closest('tr');
        // Remove the row from the table
        row.remove();
    }
</script>
<script>
    $(document).ready(function () {
        // Initialize form validation
        $("#addProductForm").validate({
            submitHandler: function (form) {
                // Perform stock validation for each row
                var isValid = true;
                $('#purchase_order_table tbody tr').each(function () {
                    var partItemId = $(this).find('select[name*="[part_item_id]"]').val();
                    var quantity = $(this).find('input[name*="[quantity]"]').val();
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
    const materialSendProduction = $row.find('input[name*="material_send_production"]').is(':checked') ? 1 : 0;
    const quantityMinusStatus = $row.find('input[name*="quantity_minus_status"]').val(); // Ensure this is captured correctly

    const stockAvailableMessage = $row.find('.stock-available');

    console.log("Checking stock for:", {
        part_item_id: partItemId,
        quantity: quantity,
        material_send_production: materialSendProduction,
        quantity_minus_status: quantityMinusStatus
    });

    if (materialSendProduction === 1 && quantityMinusStatus === 'done') {
        console.log("Stock check skipped (Already processed)");
        stockAvailableMessage.text('Stock check skipped').css('color', 'blue');
        return;
    }

    if (partItemId && quantity) {
        $.ajax({
            url: '{{ route("check-stock-quantity") }}',
            type: 'GET',
            data: { 
                part_item_id: partItemId, 
                quantity: quantity,
                material_send_production: materialSendProduction,
                quantity_minus_status: quantityMinusStatus
            },
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
                    <button type="button" class="btn btn-sm btn-danger remove-row delete-btn">
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
        ignore: ".delete-btn", // Ignore delete buttons during validation
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

</script>
@endsection