@extends('admin.layouts.master')
@section('content')
@php
    // Trolley column helpers — mirrors bom-inventory-check.blade.php pattern (T-2026-043/T-2026-044).
    $PIECE_UNITS = ['NOS', 'PCS', 'SET', 'EACH'];
    $computeMtrN = function ($mtr, $qty, $unitName, $trolleyQty) use ($PIECE_UNITS) {
        $t = (int) ($trolleyQty ?: 1);
        $isPiece = in_array(strtoupper(trim((string) $unitName)), $PIECE_UNITS, true);
        if ($isPiece) {
            return (float) ($qty ?? 0) * $t;
        }
        if ($mtr === null || $mtr === '') return null;
        return (float) $mtr * $t;
    };
    $fmt = fn($n) => ($n === null || $n === '') ? '—' : rtrim(rtrim(number_format((float) $n, 3, '.', ''), '0'), '.');
@endphp
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            font-size: 12px;
        }

        .disabled-btn {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Row source highlighting */
        tr.row-store-issued {
            background-color: #e8f8ea !important;
        }

        tr.row-prod-request {
            background-color: #fff3e0 !important;
        }

        .src-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 3px;
            margin-top: 2px;
        }

        .src-badge-store {
            background: #28a745;
            color: #fff;
        }

        .src-badge-prod {
            background: #fd7e14;
            color: #fff;
        }

        .custom-dropdown .dropdown-options {
            position: absolute;
            width: 600px !important;
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

        .margin-bottom {
            margin-bottom: 100px !important;
        }
    </style>
    <div class="">
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
                                                <div class="col-lg-4">
                                                    <label for="product_name">Product Name :</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="product_name" value="{{ $productDetails->product_name }}"
                                                        placeholder="Enter Product Name" readonly>
                                                </div>
                                                <div class="col-lg-5">
                                                    <label for="description">Description :</label>
                                                    <input type="text" class="form-control" id="description"
                                                        name="description" value="{{ $productDetails->description }}"
                                                        placeholder="Enter Description" readonly>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="total_estimation_amount">Estimation Amount :</label>
                                                    <input type="text" class="form-control" id="total_estimation_amount"
                                                        name="total_estimation_amount"
                                                        value="{{ $productDetails->total_estimation_amount }}"
                                                        placeholder="Enter Description" readonly>
                                                </div>
                                            </div>

                                            {{-- Design image links --}}
                                            @if (!empty($productDetails->design_image) || !empty($productDetails->bom_image))
                                                <div class="row" style="margin-top:10px;">
                                                    @if (!empty($productDetails->design_image))
                                                        <div class="col-lg-3">
                                                            <a href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $productDetails->design_image }}"
                                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-image"></i> View Design Layout
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @if (!empty($productDetails->bom_image))
                                                        <div class="col-lg-3">
                                                            <a href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $productDetails->bom_image }}"
                                                                target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-file-alt"></i> View BOM
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Legend --}}
                                            <div style="margin-top:10px; margin-bottom:4px; font-size:12px;">
                                                <span class="src-badge src-badge-store">&#9632; Received from Store</span>
                                                &nbsp;
                                                <span class="src-badge src-badge-prod">&#9632; Production Request</span>
                                            </div>

                                            {{-- T-2026-038: Removed teal read-only reference panel (T-2026-031/T-2026-032).
                                                 Store-issued items now appear as editable rows (green badge) directly in the
                                                 addmore[] grid. Production-request rows follow (orange badge).
                                                 JS rowCount starts at count($storeRows) + count($prodRows). --}}
                                            @php
                                                $storeCount = count($storeRows);
                                                $prodCount  = count($prodRows);
                                                $totalPrefilled = $storeCount + $prodCount;
                                            @endphp

                                            <div class="table-responsive" style="margin-top:20px;">
                                                <table class="table table-hover table-white repeater"
                                                    id="purchase_order_table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date</th>
                                                            <th>Part Item</th>
                                                            <th>Basic Rate</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Mtr for 01 Nos Trolley</th>
                                                            <th>Mtr/Nos for {{ $trolleyQty }} Trolley(s)</th>
                                                            <th>Status</th>
                                                            <th>Received</th>
                                                            <th>
                                                                <button type="button" class="btn btn-sm btn-bg-colour"
                                                                    id="add_more_btn">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        {{-- Pre-filled rows: store-issued items (green badge, indices 0..storeCount-1) --}}
                                                        @foreach ($storeRows as $si => $sitem)
                                                            @php
                                                                try {
                                                                    $sDate = $sitem->updated_at
                                                                        ? \Carbon\Carbon::parse($sitem->updated_at)->format('d-m-Y')
                                                                        : '';
                                                                } catch (\Exception $e) {
                                                                    $sDate = '';
                                                                }
                                                            @endphp
                                                            <tr class="row-store-issued">
                                                                <td><span class="form-control" style="min-width:50px">{{ $si + 1 }}</span></td>
                                                                <td>
                                                                    <input class="form-control" name="addmore[{{ $si }}][updated_at]"
                                                                        value="{{ $sDate }}" readonly placeholder="—">
                                                                </td>
                                                                <td>
                                                                    <div class="custom-dropdown">
                                                                        <input type="hidden" name="addmore[{{ $si }}][item_id]" value="{{ $sitem->pd_id }}">
                                                                        <input type="hidden" name="addmore[{{ $si }}][part_item_id]"
                                                                            class="part_no" value="{{ $sitem->part_item_id ?? '' }}">
                                                                        <input type="text" class="dropdown-input form-control"
                                                                            placeholder="Select Part Item..."
                                                                            value="{{ $sitem->part_description ?? '' }}" readonly>
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
                                                                    <input class="form-control basic_rate"
                                                                        name="addmore[{{ $si }}][basic_rate]"
                                                                        type="number" step="any"
                                                                        value="{{ $sitem->basic_rate ?? 0 }}" readonly>
                                                                    <input type="hidden" class="total_amount"
                                                                        name="addmore[{{ $si }}][items_used_total_amount]"
                                                                        value="{{ ($sitem->basic_rate ?? 0) * ($sitem->quantity ?? 0) }}">
                                                                </td>
                                                                <td>
                                                                    {{-- Store-received rows: Quantity is set by Store and not editable here. --}}
                                                                    <input class="form-control quantity"
                                                                        name="addmore[{{ $si }}][quantity]"
                                                                        type="number" step="any"
                                                                        value="{{ $sitem->quantity ?? '' }}"
                                                                        readonly
                                                                        style="background:#e9ecef;cursor:not-allowed;">
                                                                </td>
                                                                <td>
                                                                    {{-- Store-received rows: Unit is set by Store. Disable the select but
                                                                         keep the value posted via a hidden input under the same name. --}}
                                                                    <select class="form-control" disabled
                                                                            style="background:#e9ecef;cursor:not-allowed;">
                                                                        <option value="">Select Unit</option>
                                                                        @foreach ($dataOutputUnitMaster as $unit)
                                                                            <option value="{{ $unit->id }}"
                                                                                {{ $unit->id == ($sitem->unit ?? '') ? 'selected' : '' }}>
                                                                                {{ $unit->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="hidden"
                                                                        name="addmore[{{ $si }}][unit]"
                                                                        value="{{ $sitem->unit ?? '' }}">
                                                                </td>
                                                                @php
                                                                    $mtr1S = $sitem->mtr_for_01_nos_trolley ?? null;
                                                                    $mtrNS = $computeMtrN($mtr1S, $sitem->quantity ?? null, $sitem->unit_name ?? null, $trolleyQty);
                                                                @endphp
                                                                <td style="vertical-align:middle; white-space:nowrap;">{{ $fmt($mtr1S) }}</td>
                                                                <td style="vertical-align:middle; white-space:nowrap;">{{ $fmt($mtrNS) }}</td>
                                                                <td style="vertical-align:middle;">
                                                                    <span class="src-badge src-badge-store">Received from Store</span>
                                                                    <input type="hidden"
                                                                        name="addmore[{{ $si }}][material_send_production]" value="1">
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <span class="text-muted">&#10003;</span>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-row">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        {{-- Pre-filled rows: production-request items (orange badge, indices storeCount..total-1) --}}
                                                        @foreach ($prodRows as $pi => $pitem)
                                                            @php
                                                                $pIdx = $storeCount + $pi;
                                                                try {
                                                                    $pDate = $pitem->updated_at
                                                                        ? \Carbon\Carbon::parse($pitem->updated_at)->format('d-m-Y')
                                                                        : '';
                                                                } catch (\Exception $e) {
                                                                    $pDate = '';
                                                                }
                                                            @endphp
                                                            <tr class="row-prod-request">
                                                                <td><span class="form-control" style="min-width:50px">{{ $pIdx + 1 }}</span></td>
                                                                <td>
                                                                    <input class="form-control" name="addmore[{{ $pIdx }}][updated_at]"
                                                                        value="{{ $pDate }}" readonly placeholder="—">
                                                                </td>
                                                                <td>
                                                                    <div class="custom-dropdown">
                                                                        <input type="hidden" name="addmore[{{ $pIdx }}][item_id]" value="{{ $pitem->pd_id }}">
                                                                        <input type="hidden" name="addmore[{{ $pIdx }}][part_item_id]"
                                                                            class="part_no" value="{{ $pitem->part_item_id ?? '' }}">
                                                                        <input type="text" class="dropdown-input form-control"
                                                                            placeholder="Select Part Item..."
                                                                            value="{{ $pitem->part_description ?? '' }}" readonly>
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
                                                                    <input class="form-control basic_rate"
                                                                        name="addmore[{{ $pIdx }}][basic_rate]"
                                                                        type="number" step="any"
                                                                        value="{{ $pitem->basic_rate ?? 0 }}" readonly>
                                                                    <input type="hidden" class="total_amount"
                                                                        name="addmore[{{ $pIdx }}][items_used_total_amount]"
                                                                        value="{{ ($pitem->basic_rate ?? 0) * ($pitem->quantity ?? 0) }}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control quantity"
                                                                        name="addmore[{{ $pIdx }}][quantity]"
                                                                        type="number" step="any"
                                                                        value="{{ $pitem->quantity ?? '' }}">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="addmore[{{ $pIdx }}][unit]">
                                                                        <option value="">Select Unit</option>
                                                                        @foreach ($dataOutputUnitMaster as $unit)
                                                                            <option value="{{ $unit->id }}"
                                                                                {{ $unit->id == ($pitem->unit ?? '') ? 'selected' : '' }}>
                                                                                {{ $unit->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                @php
                                                                    $mtr1P = $pitem->mtr_for_01_nos_trolley ?? null;
                                                                    $mtrNP = $computeMtrN($mtr1P, $pitem->quantity ?? null, $pitem->unit_name ?? null, $trolleyQty);
                                                                @endphp
                                                                <td style="vertical-align:middle;">
                                                                    <input type="number" class="form-control prod-mtr1"
                                                                        name="addmore[{{ $pIdx }}][mtr_for_01_nos_trolley]"
                                                                        value="{{ $mtr1P !== null ? (float) $mtr1P : '' }}"
                                                                        step="any" min="0" placeholder="0.000"
                                                                        data-trolley-qty="{{ (int) $trolleyQty }}"
                                                                        style="min-width:90px;">
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <input type="text" class="form-control prod-mtrN"
                                                                        value="{{ $mtrNP !== null ? rtrim(rtrim(number_format((float) $mtrNP, 3, '.', ''), '0'), '.') : '' }}"
                                                                        readonly tabindex="-1"
                                                                        style="background:#f3f4f6;cursor:not-allowed;min-width:90px;">
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <span class="src-badge src-badge-prod">Production Request</span>
                                                                    <input type="hidden"
                                                                        name="addmore[{{ $pIdx }}][material_send_production]" value="0">
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <span class="text-muted">—</span>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-row">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        {{-- If no rows at all, show one blank row so the grid is never completely empty --}}
                                                        @if ($totalPrefilled === 0)
                                                            <tr class="row-prod-request">
                                                                <td><span class="form-control" style="min-width:50px">1</span></td>
                                                                <td>
                                                                    <input class="form-control" name="addmore[0][updated_at]"
                                                                        value="" readonly placeholder="—">
                                                                </td>
                                                                <td>
                                                                    <div class="custom-dropdown">
                                                                        <input type="hidden" name="addmore[0][item_id]" value="">
                                                                        <input type="hidden" name="addmore[0][part_item_id]"
                                                                            class="part_no" value="">
                                                                        <input type="text" class="dropdown-input form-control"
                                                                            placeholder="Select Part Item..." readonly>
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
                                                                    <input class="form-control basic_rate"
                                                                        name="addmore[0][basic_rate]" type="number" step="any"
                                                                        value="" readonly>
                                                                    <input type="hidden" class="total_amount"
                                                                        name="addmore[0][items_used_total_amount]" value="0">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control quantity"
                                                                        name="addmore[0][quantity]" type="number" step="any"
                                                                        value="">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="addmore[0][unit]">
                                                                        <option value="">Select Unit</option>
                                                                        @foreach ($dataOutputUnitMaster as $unit)
                                                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td style="vertical-align:middle;">—</td>
                                                                <td style="vertical-align:middle;">—</td>
                                                                <td style="vertical-align:middle;">
                                                                    <span class="src-badge src-badge-prod">Production Request</span>
                                                                    <input type="hidden"
                                                                        name="addmore[0][material_send_production]" value="0">
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <span class="text-muted">—</span>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-row">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endif

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" class="text-end"><strong>Total Amount
                                                                    :</strong></td>
                                                            <td colspan="2">
                                                                <input type="text" id="grand_total"
                                                                    class="form-control" readonly value="0">
                                                            </td>
                                                            <td colspan="5"></td>
                                                        </tr>
                                                    </tfoot>

                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center mt-3 mb-5">
                                                <a href="{{ route('list-material-received') }}"
                                                    class="btn btn-white me-3">
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

    {{-- <form method="POST" action="{{ route('delete-addmore-production-material-item') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id">
        <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">
    </form> --}}

    @push('scripts')
        <script>
            $(document).ready(function() {
                calculateGrandTotal(); //  THIS LINE IS REQUIRED
                const table = $("#purchase_order_table");

                // ========================
                //  DROPDOWN FUNCTIONALITY
                // ========================
                table.on('click', '.dropdown-input', function() {
                    $('.dropdown-options').hide(); // close all others
                    $(this).siblings('.dropdown-options').show();
                    $(this).siblings('.dropdown-options').find('.search-box').val('').trigger('input').focus();
                });

                table.on('input', '.search-box', function() {
                    const term = $(this).val().toLowerCase();
                    $(this).siblings('.options-list').find('.option').each(function() {
                        $(this).toggle($(this).text().toLowerCase().includes(term));
                    });
                });

                table.on('click', '.custom-dropdown .option', function() {
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
                        url: '{{ route('get-part-item-rate') }}',
                        type: 'GET',
                        data: {
                            part_item_id: id
                        },
                        success: function(res) {
                            if (res.status === 'success') {
                                $row.find('.basic_rate').val(res.basic_rate);
                                updateTotalAmount($row);
                            } else {
                                $row.find('.basic_rate').val('');
                            }
                        },
                        error: function() {
                            $row.find('.basic_rate').val('');
                        }
                    });
                });

                $(document).click(function(e) {
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
                    calculateGrandTotal();
                }

                function calculateGrandTotal() {
                    let grandTotal = 0;
                    $('.total_amount').each(function() {
                        grandTotal += parseFloat($(this).val()) || 0;
                    });
                    $('#grand_total').val(grandTotal.toFixed(2));
                }

                // on change
                $("#purchase_order_table").on('input', '.basic_rate, .quantity', function() {
                    updateTotalAmount($(this).closest('tr'));
                });


                table.on('input', '.basic_rate, .quantity', function() {
                    updateTotalAmount($(this).closest('tr'));
                });

                // ========================
                //  ADD MORE ROW
                // ========================
                $("#add_more_btn").click(function() {
                    let rowCount = table.find("tbody tr").length;

                    let newRow = `
            <tr class="row-prod-request">
                <td><span class="form-control" style="min-width:50px">${rowCount + 1}</span></td>
                <td><input class="form-control" value="—" readonly></td>
                <td>
                    <div class="custom-dropdown">
                        <input type="hidden" name="addmore[${rowCount}][part_item_id]" class="part_no" value="">
                        <input type="text" class="dropdown-input form-control" placeholder="Select Part Item..." readonly required>
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
                <td style="vertical-align:middle;">
                    <input type="number" class="form-control prod-mtr1"
                        name="addmore[${rowCount}][mtr_for_01_nos_trolley]"
                        value="" step="any" min="0" placeholder="0.000"
                        data-trolley-qty="{{ (int) $trolleyQty }}"
                        style="min-width:90px;">
                </td>
                <td style="vertical-align:middle;">
                    <input type="text" class="form-control prod-mtrN" value=""
                        readonly tabindex="-1"
                        style="background:#f3f4f6;cursor:not-allowed;min-width:90px;">
                </td>
                <td style="vertical-align:middle;">
                    <span class="src-badge src-badge-prod">Production Request</span>
                    <input type="hidden" name="addmore[${rowCount}][material_send_production]" value="0">
                </td>
                <td style="vertical-align:middle;">
                    <span class="text-muted">—</span>
                </td>
                <td style="vertical-align:middle;">
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
                    table.find("tbody").append(newRow);
                });

                // Remove row
                table.on("click", ".remove-row", function() {
                    $(this).closest("tr").remove();
                });

                // ========================
                //  Live recompute: Mtr/Nos for N Trolleys = Mtr for 01 Nos Trolley × trolley_qty.
                //  Fires for both Blade-prefilled production-request rows AND JS-added rows.
                // ========================
                table.on("input change", ".prod-mtr1", function() {
                    var $input = $(this);
                    var $row   = $input.closest("tr");
                    var mtr1   = parseFloat($input.val()) || 0;
                    var tQty   = parseInt($input.data("trolley-qty"), 10) || 1;
                    var mtrN   = mtr1 * tQty;
                    var formatted = mtrN === 0 ? "" : mtrN.toFixed(3).replace(/\.?0+$/, "");
                    $row.find(".prod-mtrN").val(formatted);
                });

                // ========================
                //  VALIDATION
                // ========================
                $("#addProductForm").validate({
                    ignore: [],
                    rules: {
                        "product_name": {
                            required: true
                        },
                        "description": {
                            required: true
                        }
                    },
                    messages: {
                        "product_name": "Product name is required",
                        "description": "Description is required"
                    },
                    errorPlacement: function(error, element) {
                        if (element.hasClass('part_no')) {
                            error.insertAfter(element.closest('.custom-dropdown'));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    // submitHandler: function(form) {
                    //     Swal.fire({
                    //         icon: 'question',
                    //         title: 'Are you sure?',
                    //         text: 'Send this material to Production?',
                    //         showCancelButton: true,
                    //         confirmButtonText: 'Yes',
                    //         cancelButtonText: 'No',
                    //     }).then(function(result) {
                    //         if (result.isConfirmed) {
                    //             form.submit();
                    //         }
                    //     });
                    // }
                });
            });
        </script>
        <script>
            // ========================================
            // AJAX SAVE Product Material
            // ========================================
            $("#addProductForm").on("submit", function(e) {
                e.preventDefault();


                let total = parseFloat($('#grand_total').val()) || 0;
                let estimation = parseFloat($('#total_estimation_amount').val()) || 0;

                if (total > estimation) {
                    Swal.fire("Error", "Total amount exceeds estimation amount", "error");
                    return false;
                }

                // Validate: every row with a quantity must have a part item selected
                let hasIncompleteRow = false;
                $('#purchase_order_table tbody tr').each(function() {
                    let partId = $(this).find('.part_no').val();
                    let qty = parseFloat($(this).find('.quantity').val()) || 0;
                    if (qty > 0 && (!partId || partId === '')) {
                        hasIncompleteRow = true;
                        return false; // break $.each
                    }
                });
                if (hasIncompleteRow) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Part Item Not Selected',
                        html: 'One or more rows have a quantity but no part item selected.<br><br>'
                            + '<strong>How to select a part item:</strong><br>'
                            + '1. Click the "Select Part Item..." box to open the dropdown.<br>'
                            + '2. Type to search, then <strong>click</strong> the item name in the list.<br>'
                            + '3. The box will show the selected item name — then save.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                let form = $(this);
                let formData = new FormData(form[0]);

                Swal.fire({
                    icon: "question",
                    title: "Are you sure?",
                    text: "Do you want to save the updated material?",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No"
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: form.attr("action"),
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function(res) {

                            if (res.status === "success") {

                                Swal.fire({
                                    icon: "success",
                                    title: "Saved!",
                                    text: res.msg,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });

                            } else {
                                Swal.fire("Error!", res.msg, "error");
                            }
                        },

                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });

                });
            });

            function reloadTable() {
                let businessId = $("#business_details_id").val();

                $.ajax({
                    url: "/proddept/edit-received-inprocess-production-material/" + businessId,
                    type: "GET",
                    success: function(html) {

                        // Extract only table HTML from page
                        let newTable = $(html).find("#purchase_order_table").html();

                        $("#purchase_order_table").html(newTable);
                    }
                });
            }
        </script>
        <script>
            // ================================
            //   AJAX DELETE ROW (ENHANCED)
            // ================================
            $(document).on("click", ".ajax-delete", function(e) {
                e.preventDefault(); // stop form submit

                let deleteId = $(this).data("id");
                let businessId = $(this).data("business-id");
                let row = $(this).closest("tr");

                Swal.fire({
                    title: "Delete Item?",
                    text: "This material item will be permanently removed. Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete",
                    cancelButtonText: "Cancel"
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('delete-addmore-production-material-item') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            delete_id: deleteId,
                            business_details_id: businessId
                        },
                        success: function(response) {

                            if (response.status === "success") {

                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted Successfully!",
                                    text: response.msg,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                row.fadeOut(300, function() {
                                    $(this).remove();
                                });

                            } else {
                                Swal.fire("Error!", response.msg, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });

                });
            });
        </script>
    @endpush
@endsection
