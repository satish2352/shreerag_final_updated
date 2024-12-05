@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
    }
    label.error {
        color: red;
        font-size: 12px;
    }
    .disabled-row {
        background-color: #f0f0f0;
        opacity: 0.6;
    }
    .disabled-row input,
    .disabled-row select,
    .disabled-row button {
        pointer-events: none;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 200px;">
        <div class="sparkline12-list" >
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Received Product Production Material Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if (Session::get('status') == 'success')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-success" id="success-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                </div>
                            @endif

                            @if (Session::get('status') == 'error')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-danger" id="error-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                     <form action="{{ route('update-production', $id) }}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">
                                        <input type="hidden" name="part_item_id" id="part_item_id" value="{{ $id }}">
                                        <div class="row">
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
                                            <table class="table table-hover table-white" id="purchase_order_table">
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
                                                <tbody style="overflow: scroll;">
                                                    @foreach ($dataGroupedById as $key => $items)
                                                        @foreach ($items as $index => $item)
                                                            <tr @if($item->material_send_production) class="disabled-row" @endif>
                                                                <td>
                                                                    <input type="hidden" name="addmore[{{ $index }}][id]" class="form-control" value="{{ $item->id }}">
                                                                    <input type="text" class="form-control" readonly value="{{ $index + 1 }}">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control part-no" name="addmore[{{ $index }}][part_item_id]" @if($item->material_send_production) disabled @endif>
                                                                        <option value="">Select Part Item</option>
                                                                        @foreach ($dataOutputPartItem as $partItem)
                                                                            <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                                {{ $partItem->description }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control val-quantity" name="addmore[{{ $index }}][quantity]" type="text" value="{{ $item->quantity }}" @if($item->material_send_production) readonly @endif>
                                                                </td>
                                                                {{-- <td>
                                                                    <input class="form-control val-unit" name="addmore[{{ $index }}][unit]" type="text" value="{{ $item->unit_name }}" @if($item->material_send_production) readonly @endif>
                                                                </td> --}}
                                                                <td>
                                                                    <select class="form-control part-no" name="addmore[{{ $index }}][unit]" @if($item->material_send_production) disabled @endif>
                                                                        <option value="">Select Part Item</option>
                                                                        @foreach ($dataOutputUnitMaster as $unit_data)
                                                                            <option value="{{ $unit_data->id }}" {{ $unit_data->id == $item->unit ? 'selected' : '' }}>
                                                                                {{ $unit_data->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <span class="material-send-production-status">
                                                                        @if($item->material_send_production)
                                                                            <i class="fa fa-check" style="color: green;"></i>
                                                                        @else
                                                                            <i class="fa fa-times" style="color: red;"></i>
                                                                        @endif
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if(!$item->material_send_production)
                                                                        <button type="button" class="btn btn-sm btn-danger remove-row">
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
                                            <button class="btn btn-sm btn-primary" type="submit">Save Data</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    let rowCounter = @json($dataGroupedById->count());

    function initializeValidation(row) {
        row.find('.part-no').rules("add", {
            required: true,
            messages: {
                required: "Please enter the Part Item."
            }
        });
        row.find('.val-quantity').rules("add", {
            required: true,
            digits: true,
            min: 1,
            messages: {
                required: "Please enter the Quantity.",
                digits: "Please enter only digits for Quantity.",
                min: "Quantity must be at least 1."
            }
        });
        row.find('.val-unit').rules("add", {
            required: true,
            messages: {
                required: "Please enter the unit."
            }
        });
    }

    function updateSerialNumbers() {
        $("#purchase_order_table tbody tr").each(function(index) {
            $(this).find("td:first input[type='text']").val(index + 1);
        });
    }

    $("#add_more_btn").click(function() {
        rowCounter++;
        var newRow = `
            <tr>
                <td>
                    <input type="hidden" name="addmore[${rowCounter}][id]" class="form-control" value="">
                    <input type="text" class="form-control" readonly value="${rowCounter + 1}">
                </td>
                <td>
                    <select class="form-control part-no" name="addmore[${rowCounter}][part_item_id]">
                        <option value="">Select Part Item</option>
                        @foreach ($dataOutputPartItem as $partItem)
                            <option value="{{ $partItem->id }}">{{ $partItem->description }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-control val-quantity" name="addmore[${rowCounter}][quantity]" type="text">
                </td>
                <td>
                                                                    <select class="form-control mb-2" name="addmore[${rowCounter}][unit]" id="">
                                                                        <option value="" default>Select Unit</option>
                                                                        @foreach ($dataOutputUnitMaster as $data)
                                                                                <option value="{{ $data['id'] }}" >
                                                                                    {{ $data['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                <td>
                    <span class="material-send-production-status">
                        <i class="fa fa-question" style="color: gray;"></i>
                    </span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        
        var row = $(newRow).appendTo("#purchase_order_table tbody");
        initializeValidation(row);
        updateSerialNumbers();
    });

    $(document).on("click", ".remove-row", function() {
        $(this).closest("tr").remove();
        updateSerialNumbers();
    });

    $("#addProductForm").on("submit", function() {
        // Optionally remove material_send_production fields or handle accordingly
        $(this).find('input[name*="[material_send_production]"]').remove();
    });

    // Initialize validation on document ready
    $(document).ready(function() {
        $("#addProductForm").validate();
    });
</script>
@endsection
