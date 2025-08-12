@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }
        label.error {
            color: red;
            /* Error text color */
            font-size: 12px;
        }
        .disabled-btn {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list" style="margin-bottom: 100px;">
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
                                @if (Session::get('status') == 'success')
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
                                @endif

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('update-recived-inprocess-production-material', $id) }}"
                                            method="POST" id="addProductForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="business_details_id" id="business_details_id"
                                                value="{{ $id }}">
                                            <input type="hidden" name="part_item_id" id="part_item_id"
                                                value="{{ $id }}">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label for="product_name">Product Name :</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="product_name" value="{{ $productDetails->product_name }}"
                                                        placeholder="Enter Product Name" readonly>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="description">Description :</label>
                                                    <input type="text" class="form-control" id="description"
                                                        name="description" value="{{ $productDetails->description }}"
                                                        placeholder="Enter Description" readonly>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="margin-top:20px;">
                                                <table class="table table-hover table-white repeater"
                                                    id="purchase_order_table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Part Item</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Received Material for Production</th>
                                                            <th>
                                                                <button type="button" class="btn btn-sm btn-success"
                                                                    id="add_more_btn">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dataGroupedById as $key => $items)
                                                            @foreach ($items as $index => $item)
                                                                <tr>
                                                                    <td><span class="form-control"
                                                                            style="min-width:50px">{{ $loop->iteration }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->material_send_production == 0)
                                                                            <select class="form-control part-no"
                                                                                name="addmore[{{ $index }}][part_no_id]"
                                                                                required>
                                                                                <option value="">Select Part Item
                                                                                </option>
                                                                                @foreach ($dataOutputPartItem as $partItem)
                                                                                    <option value="{{ $partItem->id }}"
                                                                                        {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                                        {{ $partItem->description }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <select
                                                                                class="form-control part-no disabled-btn"
                                                                                name="addmore[{{ $index }}][part_no_id]"
                                                                                disabled>
                                                                                <option value="">Select Part Item
                                                                                </option>
                                                                                @foreach ($dataOutputPartItem as $partItem)
                                                                                    <option value="{{ $partItem->id }}"
                                                                                        {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                                        {{ $partItem->description }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->material_send_production == 0)
                                                                            <input class="form-control quantity"
                                                                                name="addmore[{{ $index }}][quantity]"
                                                                                type="text"
                                                                                value="{{ $item->quantity }}" required>
                                                                        @else
                                                                            <input
                                                                                class="form-control quantity disabled-btn"
                                                                                name="addmore[{{ $index }}][quantity]"
                                                                                type="text"
                                                                                value="{{ $item->quantity }}" disabled>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->material_send_production == 0)
                                                                            <select class="form-control"
                                                                                name="addmore[{{ $index }}][unit]"
                                                                                required>
                                                                                <option value="">Select Unit</option>
                                                                                @foreach ($dataOutputUnitMaster as $unit_data)
                                                                                    <option value="{{ $unit_data->id }}"
                                                                                        {{ $unit_data->id == $item->unit ? 'selected' : '' }}>
                                                                                        {{ $unit_data->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <select class="form-control"
                                                                                name="addmore[{{ $index }}][unit]"
                                                                                disabled>
                                                                                <option value="">Select Unit</option>
                                                                                @foreach ($dataOutputUnitMaster as $unit_data)
                                                                                    <option value="{{ $unit_data->id }}"
                                                                                        {{ $unit_data->id == $item->unit ? 'selected' : '' }}>
                                                                                        {{ $unit_data->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->material_send_production == 0)
                                                                            <span>-</span>
                                                                            <input type="hidden"
                                                                                name="addmore[{{ $index }}][material_send_production]"
                                                                                value="1">
                                                                        @else
                                                                            <input type="checkbox"
                                                                                name="addmore[{{ $index }}][material_send_production]"
                                                                                class="disabled-btn" value="1" checked
                                                                                disabled>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->material_send_production == 0)
                                                                            <a data-id="{{ $item->pd_id }}"
                                                                                class="delete-btn btn btn-danger m-1"
                                                                                title="Delete Tender"><i
                                                                                    class="fas fa-archive"></i></a>
                                                                        @else
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-row disabled-btn"
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
                                            <a href="{{ route('list-material-recived') }}" class="btn btn-white me-3">
                                                Cancel
                                            </a>
                                            <button class="btn btn-sm btn-bg-colour" type="submit">
                                                Save Data
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

    <form method="POST" action="{{ route('delete-addmore-production-material-item') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>
    <!-- Include jQuery, jQuery Validate (updated version for better compatibility), and SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            // 1. Initialize jQuery Validate on the form
            var validator = $("#addProductForm").validate({
                rules: {
                    product_name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // Dynamic fields can be validated via added rules.
                },
                messages: {
                    product_name: {
                        required: "Product name is required."
                    },
                    description: {
                        required: "Description is required."
                    }
                },
                errorClass: "error",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                }
            });

            // 2. Set row counter based on current rows
            var i_count = $("#purchase_order_table tbody tr").length;

            // 3. Event handler for "Add More" button
            $("#add_more_btn").click(function() {
                i_count++;
                var newRow = `
            <tr>
                <td>
                    <input type="text" name="addmore[${i_count}][id]" class="form-control" style="min-width:20px" value="${i_count}" readonly>
                </td>
                <td>
                    <select class="form-control part-no mb-2" name="addmore[${i_count}][part_no_id]" required>
                        <option value="">Select Part Item</option>
                        @foreach ($dataOutputPartItem as $data)
                            <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-control quantity" name="addmore[${i_count}][quantity]" type="text" required>
                </td>
                <td>
                    <select class="form-control mb-2 unit" name="addmore[${i_count}][unit]" required>
                        <option value="">Select Unit</option>
                        @foreach ($dataOutputUnitMaster as $data)
                            <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                           <span>-</span>
                    <input type="hidden" name="addmore[${i_count}][material_send_production]" value="0">
                    <input type="hidden" name="addmore[${i_count}][quantity_minus_status]" value="pending">
                </td>
                    <td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete Tender" data-id="{{ $item->pd_id }}"><i class="fas fa-archive"></i></a></td>
                
            </tr>
        `;
                // Append the new row to the table
                $("#purchase_order_table tbody").append(newRow);

                // 4. Add validation rules to the newly added "quantity" input
                $("#purchase_order_table tbody tr:last .quantity").rules("add", {
                    required: true,
                    digits: true,
                    messages: {
                        required: "Quantity is required.",
                        digits: "Please enter a valid number."
                    }
                });
            });

            // 5. Delegate click event for removing rows
            $(document).on("click", ".remove-row", function() {
                $(this).closest("tr").remove();
                updateSerialNumbers();
            });

            // Helper function to update serial numbers after a row removal
            function updateSerialNumbers() {
                $("#purchase_order_table tbody tr").each(function(index) {
                    $(this).find('td:first input[type="text"]').val(index + 1);
                });
            }
        });
    </script>
@endsection
