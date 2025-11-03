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
         .custom-dropdown .dropdown-options {
        position: absolute;
        width:600px !important;
        /* top: 700px; */
        left: 193px;
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
    .margin-bottom{
        margin-bottom: 100px !important;
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
                                        <form action="{{ route('update-received-inprocess-production-material', $id) }}"
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
                                                             <th>Basic Rate</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Received Material for Production</th>
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
                                                                <tr>
                                                                    <td><span class="form-control"
                                                                            style="min-width:50px">{{ $loop->iteration }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->material_send_production == 0)
                                                                           <div class="custom-dropdown">
        <input type="hidden" name="addmore[{{ $index }}][part_item_id]" class="part_no" value="{{ $item->part_item_id ?? '' }}">
        <input type="text" class="dropdown-input form-control" placeholder="Select Part Item..." value="{{ $item->part_description ?? '' }}" readonly required>

       
        <div class="dropdown-options dropdown-height" style="display: none;">
            <input type="text" class="search-box form-control" placeholder="Search...">
            <div class="options-list">
                @foreach ($dataOutputPartItem as $data)
                    <div class="option" data-id="{{ $data->id }}">{{ $data->part_description }}</div>
                @endforeach
            </div>
        </div>
    </div>

                                                                            {{-- <select class="form-control part-no"
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
                                                                            </select> --}}
                                                                        @else
                                                                               <div class="custom-dropdown">
        <input type="hidden" name="addmore[{{ $index }}][part_item_id]" class="part_no" value="{{ $item->part_item_id ?? '' }}">
        <input type="text" class="dropdown-input form-control" placeholder="Select Part Item..." value="{{ $item->part_description ?? '' }}" readonly required>

        <div class="dropdown-options dropdown-height" style="display: none;">
            <input type="text" class="search-box form-control" placeholder="Search...">
            <div class="options-list">
                @foreach ($dataOutputPartItem as $data)
                    <div class="option" data-id="{{ $data->id }}">{{ $data->part_description }}</div>
                @endforeach
            </div>
        </div>
    </div>

                                                                        @endif
                                                                    </td>
                                                                      <td>
                                                                <input class="form-control basic_rate" name="addmore[{{ $index }}][basic_rate]" type="number" step="any" required value="{{ $item->basic_rate }}" readonly>
                                                                <input type="hidden" class="total_amount" name="addmore[{{ $index }}][total_amount]" value="{{ $item->basic_rate * $item->quantity }}">
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
                                                                                class="delete-btn btn btn-danger btn-sm remove-row "
                                                                                title="Delete Tender"><i
                                                                                    class="fas fa-archive"></i></a>
                                                                        @else
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm remove-row disabled-btn"
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
                                            <a href="{{ route('list-material-received') }}" class="btn btn-white me-3">
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
$(document).ready(function () {
    const table = $("#purchase_order_table");

    // ========================
    //  DROPDOWN FUNCTIONALITY
    // ========================
    table.on('click', '.dropdown-input', function () {
        $('.dropdown-options').hide(); // close all others
        $(this).siblings('.dropdown-options').show();
        $(this).siblings('.dropdown-options').find('.search-box').val('').trigger('input').focus();
    });

    table.on('input', '.search-box', function () {
        const term = $(this).val().toLowerCase();
        $(this).siblings('.options-list').find('.option').each(function () {
            $(this).toggle($(this).text().toLowerCase().includes(term));
        });
    });

    table.on('click', '.custom-dropdown .option', function () {
        const text = $(this).text();
        const id = $(this).data('id');
        const $dropdown = $(this).closest('.custom-dropdown');
        const $row = $dropdown.closest('tr');

        // Set hidden value + visible text
        $dropdown.find('.dropdown-input').val(text);
        $dropdown.find('.part_no').val(id);
        $dropdown.find('.dropdown-options').hide();

        // Fetch basic rate
        $.ajax({
            url: '{{ route("get-part-item-rate") }}',
            type: 'GET',
            data: { part_item_id: id },
            success: function (res) {
                if (res.status === 'success') {
                    $row.find('.basic_rate').val(res.basic_rate);
                    updateTotalAmount($row);
                } else {
                    $row.find('.basic_rate').val('');
                }
            },
            error: function () {
                $row.find('.basic_rate').val('');
            }
        });
    });

    $(document).click(function (e) {
        if (!$(e.target).closest('.custom-dropdown').length) {
            $('.dropdown-options').hide();
        }
    });

    // ========================
    //  TOTAL AMOUNT CALCULATION
    // ========================
    function updateTotalAmount($row) {
        let rate = parseFloat($row.find('.basic_rate').val()) || 0;
        let qty = parseFloat($row.find('.quantity').val()) || 0;
        $row.find('.total_amount').val((rate * qty).toFixed(2));
    }

    table.on('input', '.basic_rate, .quantity', function () {
        updateTotalAmount($(this).closest('tr'));
    });

    // ========================
    //  ADD MORE ROW
    // ========================
    let rowCount = table.find("tbody tr").length;
    $("#add_more_btn").click(function () {
        rowCount++;
        let newRow = `
            <tr>
                <td><input type="text" class="form-control" value="${rowCount}" readonly></td>
                <td>
                    <div class="custom-dropdown">
                        <input type="hidden" name="addmore[${rowCount}][part_item_id]" class="part_no" value="">
                        <input type="text" class="dropdown-input form-control" placeholder="Select Part Item..." readonly required>
                        <div class="dropdown-options dropdown-height" style="display: none;">
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
                    <input class="form-control basic_rate" name="addmore[${rowCount}][basic_rate]" type="number" step="any" required>
                    <input type="hidden" class="total_amount" name="addmore[${rowCount}][items_used_total_amount]" value="0">
                </td>
                <td>
                    <input class="form-control quantity" name="addmore[${rowCount}][quantity]" type="number" step="any" required>
                </td>
                <td>
                    <select class="form-control unit" name="addmore[${rowCount}][unit]" required>
                        <option value="">Select Unit</option>
                        @foreach ($dataOutputUnitMaster as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                 -
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
        table.find("tbody").append(newRow);

        $(`input[name="addmore[${rowCount}][part_item_id]"]`).rules("add", {
    required: true,
    messages: { required: "Please select a Part Item" }
});
    });

    // Remove row
    table.on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
    });

    // ========================
    //  VALIDATION
    // ========================
    $("#addProductForm").validate({
        ignore: [],
        rules: {
            "product_name": { required: true },
            "description": { required: true }
        },
        messages: {
            "product_name": "Product name is required",
            "description": "Description is required"
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('part_no')) {
                error.insertAfter(element.closest('.custom-dropdown'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Send this material to Production?',
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
});
</script>

@endsection
