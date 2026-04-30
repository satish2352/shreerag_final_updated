@extends('admin.layouts.master')
@section('content')
<style>
    .bom-check-section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        margin-top: 20px;
    }
    .bom-product-info-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 14px 18px;
        margin-bottom: 20px;
    }
    .bom-product-info-box .info-label {
        font-weight: 600;
        color: #495057;
    }
    .table-available thead {
        background-color: #28a745;
        color: #fff;
    }
    .table-shortage thead {
        background-color: #dc3545;
        color: #fff;
    }
    .badge-available {
        background-color: #28a745;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .badge-shortage {
        background-color: #dc3545;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .shortage-row td {
        vertical-align: middle;
    }
    .qty-highlight {
        font-weight: 600;
        color: #dc3545;
    }
    .table-issued thead {
        background-color: #6c757d;
        color: #fff;
    }
    .badge-issued {
        background-color: #6c757d;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .table-pending-prod thead {
        background-color: #fd7e14;
        color: #fff;
    }
    .badge-pending-prod {
        background-color: #fd7e14;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .accordion-chevron-issued {
        transition: transform 0.25s ease;
    }
    .bom-check-section-title[aria-expanded="true"] .accordion-chevron-issued {
        transform: rotate(180deg);
    }
    .badge-sent-purchase {
        background-color: #28a745;
        color: #fff;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
    }
    .badge-not-sent {
        background-color: #fd7e14;
        color: #fff;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
    }
    tr.shortage-sent-row td { background-color: #f0fff4 !important; }
    tr.shortage-new-row  td { background-color: #fff8f0 !important; }
    .issue-validation-msg {
        display: none;
        margin-bottom: 10px;
        padding: 8px 12px;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        background: #f8d7da;
        color: #721c24;
        font-weight: 600;
    }
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>BOM Inventory Check</h1>
                        </div>
                    </div>

                    {{-- Flash messages --}}
                    @if (Session::get('status') == 'success')
                        <div class="alert alert-success alert-success-style1">
                            <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                            </button>
                            <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
                        </div>
                    @endif
                    @if (Session::get('status') == 'error')
                        <div class="alert alert-danger alert-mg-b alert-success-style4">
                            <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                            </button>
                            <i class="fa fa-times adminpro-danger-error admin-check-pro" aria-hidden="true"></i>
                            <p><strong>Error!</strong> {{ Session::get('msg') }}</p>
                        </div>
                    @endif

                    {{-- Product Info --}}
                    <div class="bom-product-info-box">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="info-label">Product Name:</span>
                                {{ ucwords($productDetails->product_name ?? '—') }}
                            </div>
                            <div class="col-md-3">
                                <span class="info-label">Description:</span>
                                {{ ucwords($productDetails->description ?? '—') }}
                            </div>
                            <div class="col-md-2">
                                <span class="info-label">Quantity:</span>
                                {{ $productDetails->quantity ?? '—' }}
                            </div>
                            <div class="col-md-2">
                                <span class="info-label">Estimation Amount:</span>
                                <strong>{{ $estimationAmount ? number_format((float)$estimationAmount, 2) : '—' }}</strong>
                            </div>
                            <div class="col-md-2">
                                <span class="info-label">Items:</span>
                                {{ count($available) }} available,
                                {{ count($shortage) }} shortage,
                                {{ count($alreadyIssued) }} issued
                            </div>
                        </div>
                    </div>

                    {{-- Pre-calculate issued grand total for budget check --}}
                    @php
                        $issuedGrandTotal    = 0;
                        foreach ($alreadyIssued as $_item) {
                            $issuedGrandTotal += (float)($_item->required_quantity ?? 0) * (float)($_item->rate ?? 0);
                        }
                        $estimationAmt          = (float)($estimationAmount ?? 0);
                        $issuedExceedsEstimation = $estimationAmt > 0 && $issuedGrandTotal >= $estimationAmt;
                    @endphp

                    {{-- ========================
                         TABLE 0 — Already Issued
                         ======================== --}}
                    @if (count($alreadyIssued) > 0)
                    <div class="bom-check-section-title" style="cursor:pointer;" data-toggle="collapse" data-target="#alreadyIssuedCollapse" aria-expanded="false" aria-controls="alreadyIssuedCollapse">
                        <i class="fa fa-check-double text-secondary"></i>
                        Already Issued to Production
                        <span class="badge-issued">Sent previously</span>
                        <span style="margin-left:8px;font-size:13px;color:#6c757d;">
                            <i class="fa fa-chevron-down accordion-chevron-issued"></i>
                        </span>
                    </div>
                    <div class="collapse" id="alreadyIssuedCollapse">
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <table class="table table-bordered table-hover table-issued">
                                <thead>
                                    <tr>
                                        <th style="width:45px;">Sr.</th>
                                        <th style="white-space:nowrap;">Date &amp; Time</th>
                                        <th>Product Description</th>
                                        <th>Issued Qty</th>
                                        <th>Unit</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $innerIssuedTotal = 0; @endphp
                                    @foreach ($alreadyIssued as $i => $item)
                                        @php
                                            $rowTotal         = (float)($item->required_quantity ?? 0) * (float)($item->rate ?? 0);
                                            $innerIssuedTotal += $rowTotal;
                                            $issuedAt = $item->issued_at ?? null;
                                            $issuedAtStr = $issuedAt ? \Carbon\Carbon::parse($issuedAt)->format('d M Y, h:i A') : '—';
                                        @endphp
                                        <tr>
                                            <td style="width:45px;">{{ $i + 1 }}</td>
                                            <td style="white-space:nowrap; font-size:12px; color:#555;">{{ $issuedAtStr }}</td>
                                            <td>{{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}</td>
                                            <td>{{ number_format($item->required_quantity, 3) }}</td>
                                            <td>{{ optional($item->unitMaster)->name ?? ($item->unit_id ?? '—') }}</td>
                                            <td>{{ isset($item->rate) && $item->rate !== null ? number_format((float)$item->rate, 3) : '—' }}</td>
                                            <td><strong>{{ number_format($rowTotal, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background:#e9ecef; font-weight:700;">
                                        <td colspan="6" style="text-align:right; padding-right:12px;">Grand Total</td>
                                        <td>{{ number_format($issuedGrandTotal, 2) }}</td>
                                    </tr>
                                    @if($issuedExceedsEstimation)
                                    <tr>
                                        <td colspan="7" style="padding:0;">
                                            <div style="background:#dc3545;color:#fff;padding:6px 12px;font-size:13px;font-weight:600;">
                                                <i class="fa fa-exclamation-triangle"></i>
                                                Issued Total ({{ number_format($issuedGrandTotal, 2) }}) already exceeds Estimation Amount ({{ number_format($estimationAmt, 2) }}).
                                                Issuing more materials is blocked.
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Budget exceeded banner --}}
                    @if($issuedExceedsEstimation)
                    <div class="alert alert-danger" style="border-left:5px solid #a71d2a; border-radius:4px; margin-bottom:16px;">
                        <strong><i class="fa fa-ban"></i> Budget Limit Exceeded — Issue to Production is Blocked</strong><br>
                        Already Issued: <strong>{{ number_format($issuedGrandTotal, 2) }}</strong> &nbsp;|&nbsp;
                        Estimation Amount: <strong>{{ number_format($estimationAmt, 2) }}</strong> &nbsp;|&nbsp;
                        Exceeded by: <strong style="color:#a71d2a;">{{ number_format($issuedGrandTotal - $estimationAmt, 2) }}</strong>
                    </div>
                    @endif

                    {{-- ========================
                         TABLE 1 — Available Items
                         ======================== --}}
                    <div class="bom-check-section-title">
                        <i class="fa fa-check-circle text-success"></i>
                        Available Materials
                        <span class="badge-available">Can be issued from stock</span>
                    </div>

                    <form action="{{ route('issue-available-materials') }}" method="POST" id="issueAvailableForm">
                        @csrf
                        <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">

                        {{-- Hidden inputs for BOM available items --}}
                        @foreach ($available as $i => $item)
                            <input type="hidden" name="items[{{ $i }}][part_item_id]"        value="{{ $item->part_item_id }}">
                            <input type="hidden" name="items[{{ $i }}][product_description]" value="{{ $item->product_description ?? (optional($item->partItem)->description ?? '') }}">
                            <input type="hidden" name="items[{{ $i }}][quantity]"            value="{{ $item->required_quantity }}">
                            <input type="hidden" name="items[{{ $i }}][unit_id]"             value="{{ $item->unit_id }}">
                            <input type="hidden" name="items[{{ $i }}][rate]"                value="{{ $item->rate ?? 0 }}">
                        @endforeach

                        {{-- BOM Available items (read-only display) --}}
                        @if (count($available) > 0)
                            <div class="table-responsive" style="margin-bottom: 10px;">
                                <table class="table table-bordered table-hover table-available">
                                    <thead>
                                        <tr>
                                            <th style="width:45px;">Sr.</th>
                                            <th>Product Description</th>
                                            <th>Required Qty</th>
                                            <th>Available Stock</th>
                                            <th>Unit</th>
                                            <th>Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($available as $i => $item)
                                            <tr>
                                                <td style="width:45px;">{{ $i + 1 }}</td>
                                                <td>{{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}</td>
                                                <td>{{ number_format($item->required_quantity, 3) }}</td>
                                                <td>{{ number_format($item->available_stock, 3) }}</td>
                                                <td>{{ optional($item->unitMaster)->name ?? ($item->unit_id ?? '—') }}</td>
                                                <td>{{ isset($item->rate) && $item->rate !== null ? number_format((float)$item->rate, 3) : '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info" style="margin-bottom: 10px;">
                                No BOM items are fully available in stock.
                            </div>
                        @endif

                        {{-- Additional Items to Issue (pre-filled with production requests + manual add) --}}
                        <div class="bom-check-section-title" style="margin-top: 10px;">
                            <i class="fa fa-plus-circle text-primary"></i>
                            Additional Items to Issue
                            @if(count($availableFromProduction) > 0)
                                <small class="text-muted">({{ count($availableFromProduction) }} production request(s) pre-filled — add more if needed)</small>
                            @else
                                <small class="text-muted">(optional — add items not in BOM)</small>
                            @endif
                        </div>
                        <div class="table-responsive" style="margin-bottom: 10px;">
                            <table class="table table-bordered" id="extraItemsTable">
                                <thead style="background:#007bff; color:#fff;">
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Part Item</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Rate</th>
                                        <th>
                                            <button type="button" class="btn btn-sm btn-light" id="addExtraRow">
                                                <i class="fa fa-plus"></i> Add
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="extraItemsBody">
                                    {{-- Pre-filled rows for production-requested available items --}}
                                    @foreach ($availableFromProduction as $pi => $pitem)
                                        <tr id="extra_row_{{ $pi }}" style="background:#fff8f0;">
                                            <td style="vertical-align:middle;">{{ $pi + 1 }}</td>
                                            <td style="vertical-align:middle; min-width:300px;">
                                                <input type="text" class="form-control" value="{{ $pitem->product_description }}" readonly style="background:#f8f9fa;">
                                                <input type="hidden" name="extra_items[{{ $pi }}][part_item_id]"        value="{{ $pitem->part_item_id }}">
                                                <input type="hidden" name="extra_items[{{ $pi }}][product_description]" value="{{ $pitem->product_description }}">
                                                <input type="hidden" name="extra_items[{{ $pi }}][rate]"                value="{{ $pitem->rate ?? 0 }}">
                                                <small style="color:#fd7e14;font-size:11px;"><i class="fa fa-industry"></i> Production Request</small>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" name="extra_items[{{ $pi }}][quantity]" class="form-control" step="0.001" min="0.001" value="{{ $pitem->required_quantity }}" style="width:110px;">
                                                <small style="color:green;font-size:11px;">&#10004; Stock: {{ number_format($pitem->available_stock, 3) }}</small>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <select name="extra_items[{{ $pi }}][unit_id]" class="form-control" style="min-width:100px;">
                                                    <option value="">Select Unit</option>
                                                    @foreach ($unitMasters as $u)
                                                        <option value="{{ $u->id }}" {{ $u->id == $pitem->unit_id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" name="extra_items[{{ $pi }}][rate]" class="form-control" step="0.001" min="0" value="{{ $pitem->rate ?? 0 }}" style="width:100px;" readonly>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeExtraRow({{ $pi }})"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- JS-added rows appended here --}}
                                </tbody>
                            </table>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <div id="issueValidationMsg" class="issue-validation-msg"></div>
                            @if($issuedExceedsEstimation)
                                <button type="button" class="btn btn-danger" disabled style="cursor:not-allowed; opacity:0.85;">
                                    <i class="fa fa-ban"></i>
                                    Issue Blocked — Estimation Amount Exceeded
                                </button>
                            @else
                                <button type="submit" class="btn btn-success" id="issueBtn">
                                    <i class="fa fa-check-circle"></i>
                                    Issue to Production
                                </button>
                            @endif
                        </div>
                    </form>

                    {{-- ========================
                         TABLE 2 — Shortage Items
                         ======================== --}}
                    <div class="bom-check-section-title">
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                        Shortage Materials
                        @if($requisitionSent)
                            <span style="background:#28a745;color:#fff;padding:3px 10px;border-radius:4px;font-size:12px;">
                                <i class="fa fa-check"></i> Requisition Sent to Purchase
                            </span>
                        @else
                            <span class="badge-shortage">Need to purchase</span>
                        @endif
                    </div>

                    @if (count($shortage) > 0)
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <table class="table table-bordered table-hover table-shortage">
                                <thead>
                                    <tr>
                                        <th style="width:45px;">Sr.</th>
                                        <th style="white-space:nowrap;">Date &amp; Time</th>
                                        <th>Product Description</th>
                                        <th>Required Qty</th>
                                        <th>Available Stock</th>
                                        <th class="qty-highlight" style="color:#fff;">Shortage Qty</th>
                                        <th>Unit</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shortage as $i => $item)
                                        @php
                                            $isSentToPurchase = $requisitionSent && in_array((string)($item->part_item_id ?? ''), $sentPartIds);
                                            $rowClass = $requisitionSent ? ($isSentToPurchase ? 'shortage-sent-row' : 'shortage-new-row') : 'shortage-row';
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td style="width:45px;">{{ $i + 1 }}</td>
                                            <td style="white-space:nowrap; font-size:12px; color:#555;">
                                                {{ ($item->created_at ?? null) ? \Carbon\Carbon::parse($item->created_at)->format('d M Y, h:i A') : '—' }}
                                            </td>
                                            <td>
                                                {{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}
                                                @if($requisitionSent)
                                                    @if($isSentToPurchase)
                                                        <span class="badge-sent-purchase"><i class="fa fa-check"></i> Sent to Purchase</span>
                                                    @else
                                                        <span class="badge-not-sent"><i class="fa fa-exclamation"></i> Not in Requisition</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->required_quantity, 3) }}</td>
                                            <td>{{ number_format($item->available_stock, 3) }}</td>
                                            <td><strong class="qty-highlight">{{ number_format($item->shortage_quantity, 3) }}</strong></td>
                                            <td>{{ optional($item->unitMaster)->name ?? ($item->unit_id ?? '—') }}</td>
                                            <td>{{ isset($item->rate) && $item->rate !== null ? number_format((float)$item->rate, 3) : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- ========================
                             Shortage Requisition Form / Sent Notice
                             ======================== --}}
                        @if($requisitionSent)
                            {{-- Main requisition already sent. Show status + option to send newly-added items. --}}
                            @php
                                $notSentItems = collect($shortage)->filter(function($item) use ($sentPartIds) {
                                    return !in_array((string)($item->part_item_id ?? ''), $sentPartIds);
                                })->values();
                            @endphp

                            <div style="margin-top:12px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                                <a href="{{ route('list-accepted-design-from-prod') }}" class="btn btn-white">Cancel</a>
                                <button type="button" class="btn btn-success" disabled style="cursor:not-allowed; opacity:0.85;">
                                    <i class="fa fa-check-circle"></i>
                                    Requisition Already Sent to Purchase
                                </button>
                            </div>

                            @if($notSentItems->count() > 0)
                                <div style="margin-top:16px; padding:12px 16px; border:1px solid #fd7e14; border-radius:6px; background:#fff8f0;">
                                    <p style="margin:0 0 10px; font-weight:600; color:#fd7e14;">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        {{ $notSentItems->count() }} new shortage item(s) not yet sent to Purchase:
                                    </p>
                                    <form action="{{ route('store-additional-shortage-requisition') }}" method="POST" id="additionalReqForm">
                                        @csrf
                                        <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">

                                        @foreach ($notSentItems as $ni => $item)
                                            <input type="hidden" name="items[{{ $ni }}][part_item_id]"        value="{{ $item->part_item_id }}">
                                            <input type="hidden" name="items[{{ $ni }}][product_description]" value="{{ $item->product_description ?? (optional($item->partItem)->description ?? '') }}">
                                            <input type="hidden" name="items[{{ $ni }}][required_quantity]"   value="{{ $item->required_quantity }}">
                                            <input type="hidden" name="items[{{ $ni }}][available_quantity]"  value="{{ $item->available_stock }}">
                                            <input type="hidden" name="items[{{ $ni }}][shortage_quantity]"   value="{{ $item->shortage_quantity }}">
                                            <input type="hidden" name="items[{{ $ni }}][unit_id]"             value="{{ $item->unit_id }}">
                                            <input type="hidden" name="items[{{ $ni }}][rate]"                value="{{ $item->rate }}">
                                        @endforeach

                                        <button type="submit" class="btn btn-warning shortage-confirm-btn"
                                                data-confirm-title="Send New Shortage Items?"
                                                data-confirm-text="Send {{ $notSentItems->count() }} new shortage item(s) to Purchase department?"
                                                data-confirm-button="Yes, Send">
                                            <i class="fa fa-paper-plane"></i>
                                            Send {{ $notSentItems->count() }} New Item(s) to Purchase
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            @php $design_id_for_form = $shortage[0]->design_id ?? null; @endphp
                            <form action="{{ route('store-shortage-requisition') }}" method="POST" id="shortageReqForm">
                                @csrf
                                <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">
                                <input type="hidden" name="business_id"         value="{{ $productDetails->business_id }}">
                                <input type="hidden" name="design_id"           value="{{ $design_id_for_form }}">

                                @foreach ($shortage as $i => $item)
                                    <input type="hidden" name="items[{{ $i }}][part_item_id]"        value="{{ $item->part_item_id }}">
                                    <input type="hidden" name="items[{{ $i }}][product_description]" value="{{ $item->product_description ?? (optional($item->partItem)->description ?? '') }}">
                                    <input type="hidden" name="items[{{ $i }}][required_quantity]"   value="{{ $item->required_quantity }}">
                                    <input type="hidden" name="items[{{ $i }}][available_quantity]"  value="{{ $item->available_stock }}">
                                    <input type="hidden" name="items[{{ $i }}][shortage_quantity]"   value="{{ $item->shortage_quantity }}">
                                    <input type="hidden" name="items[{{ $i }}][unit_id]"             value="{{ $item->unit_id }}">
                                    <input type="hidden" name="items[{{ $i }}][rate]"                value="{{ $item->rate }}">
                                @endforeach

                                <div class="login-btn-inner" style="margin-top:10px;">
                                    <div class="login-horizental cancel-wp">
                                        <a href="{{ route('list-accepted-design-from-prod') }}"
                                           class="btn btn-white" style="margin-right:10px;">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-danger shortage-confirm-btn"
                                                data-confirm-title="Send Shortage Requisition?"
                                                data-confirm-text="Submit {{ count($shortage) }} shortage item(s) as a requisition to Purchase department?"
                                                data-confirm-button="Yes, Send Requisition">
                                            <i class="fa fa-paper-plane"></i>
                                            Send Shortage List as Requisition to Purchase
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    @else
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i>
                            All BOM items are available in stock. No purchase requisition is needed.
                        </div>

                        <div class="login-btn-inner" style="margin-top: 20px;">
                            <a href="{{ route('list-accepted-design-from-prod') }}" class="btn btn-white">
                                Back to List
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<style>
    .ei-dropdown { position: relative; }
    .ei-dropdown-menu {
        display: none;
        position: fixed;
        z-index: 9999;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 6px 18px rgba(0,0,0,.15);
        width: 420px;
        max-height: 300px;
        overflow: hidden;
    }
    .ei-dropdown-menu.ei-open { display: flex; flex-direction: column; }
    .ei-search-box {
        padding: 6px 8px;
        border-bottom: 1px solid #eee;
        flex-shrink: 0;
    }
    .ei-search-box input { width:100%; box-sizing:border-box; }
    .ei-options-list { overflow-y: auto; flex: 1; }
    .ei-option {
        padding: 6px 10px;
        cursor: pointer;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ei-option:hover { background: #f0f4ff; }
    .ei-trigger {
        cursor: pointer;
        background: #fff;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<script>
(function () {
    // Start after Blade-pre-rendered production-request rows
    var extraRowCount = {{ count($availableFromProduction) }};

    // Build part items data array for the custom dropdown
    var partData = [];
    @foreach($partItems as $p)
    partData.push({ id: {{ $p->id }}, label: {!! json_encode($p->description) !!}, rate: '{{ $p->basic_rate }}' });
    @endforeach

    var unitOptions = '<option value="">Select Unit</option>';
    @foreach($unitMasters as $u)
        unitOptions += '<option value="{{ $u->id }}">{{ $u->name }}</option>';
    @endforeach

    var checkStockUrl = '{{ route("check-stock-quantity") }}';

    // ── Custom dropdown logic ──────────────────────────────────────────
    var activeMenu = null;

    function closeActiveMenu() {
        if (activeMenu) {
            activeMenu.classList.remove('ei-open');
            activeMenu = null;
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.ei-dropdown') && !e.target.closest('.ei-dropdown-menu')) {
            closeActiveMenu();
        }
    });

    function openMenu(trigger, menu) {
        closeActiveMenu();
        var rect = trigger.getBoundingClientRect();
        menu.style.top  = rect.bottom + 'px';
        menu.style.left = rect.left   + 'px';
        menu.classList.add('ei-open');
        activeMenu = menu;
        var searchInput = menu.querySelector('.ei-search-input');
        searchInput.value = '';
        filterOptions(menu, '');
        searchInput.focus();
    }

    function filterOptions(menu, term) {
        term = term.toLowerCase();
        menu.querySelectorAll('.ei-option').forEach(function (opt) {
            opt.style.display = opt.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    }

    function buildMenu(rowIndex) {
        var menu = document.createElement('div');
        menu.className = 'ei-dropdown-menu';
        menu.setAttribute('data-row', rowIndex);
        menu.innerHTML =
            '<div class="ei-search-box"><input type="text" class="form-control form-control-sm ei-search-input" placeholder="Search..."></div>' +
            '<div class="ei-options-list"></div>';

        var list = menu.querySelector('.ei-options-list');
        partData.forEach(function (p) {
            var opt = document.createElement('div');
            opt.className  = 'ei-option';
            opt.textContent = p.label;
            opt.setAttribute('data-id',   p.id);
            opt.setAttribute('data-rate', p.rate);
            list.appendChild(opt);
        });

        menu.querySelector('.ei-search-input').addEventListener('input', function () {
            filterOptions(menu, this.value);
        });

        menu.addEventListener('click', function (e) {
            var opt = e.target.closest('.ei-option');
            if (!opt) return;
            var rowEl = document.getElementById('extra_row_' + rowIndex);
            rowEl.querySelector('.ei-trigger').textContent  = opt.textContent;
            rowEl.querySelector('.extra-part-id').value     = opt.getAttribute('data-id');
            rowEl.querySelector('.extra-rate-hidden').value = opt.getAttribute('data-rate');
            rowEl.querySelector('.extra-rate-input').value  = opt.getAttribute('data-rate');
            rowEl.querySelector('.extra-desc').value        = opt.textContent;
            closeActiveMenu();
            checkStock(rowIndex);
        });

        document.body.appendChild(menu);
        return menu;
    }

    // ── Stock check ───────────────────────────────────────────────────
    function checkStock(rowIndex) {
        var rowEl      = document.getElementById('extra_row_' + rowIndex);
        if (!rowEl) return;
        var partItemId = rowEl.querySelector('.extra-part-id').value;
        var quantity   = rowEl.querySelector('.extra-qty-input').value;
        var msgEl      = rowEl.querySelector('.extra-stock-msg');

        if (!partItemId || !quantity || parseFloat(quantity) <= 0) {
            msgEl.textContent = ''; return;
        }

        fetch(checkStockUrl + '?part_item_id=' + encodeURIComponent(partItemId) +
              '&quantity=' + encodeURIComponent(quantity) +
              '&material_send_production=0&quantity_minus_status=pending')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'success') {
                    msgEl.textContent = '✔ Available: ' + data.available_quantity;
                    msgEl.style.color = 'green';
                } else {
                    var avail = data.available_quantity !== undefined ? data.available_quantity : 0;
                    msgEl.textContent = '✘ Not available (Stock: ' + avail + ')';
                    msgEl.style.color = 'red';
                }
            })
            .catch(function () { msgEl.textContent = ''; });
    }

    // ── Add row ───────────────────────────────────────────────────────
    function addExtraRow() {
        var i = extraRowCount++;
        var row =
            '<tr id="extra_row_' + i + '">' +
            '<td style="vertical-align:middle;">' + (i + 1) + '</td>' +
            '<td style="vertical-align:middle; min-width:300px;">' +
                '<div class="ei-dropdown">' +
                    '<button type="button" class="btn btn-default form-control ei-trigger" style="text-align:left;">-- Select Part Item --</button>' +
                '</div>' +
                '<input type="hidden" name="extra_items[' + i + '][part_item_id]" class="extra-part-id" value="">' +
                '<input type="hidden" name="extra_items[' + i + '][product_description]" class="extra-desc" value="">' +
                '<input type="hidden" name="extra_items[' + i + '][rate]" class="extra-rate-hidden" value="0">' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<input type="number" name="extra_items[' + i + '][quantity]" class="form-control extra-qty-input" step="0.001" min="0.001" required style="width:110px;">' +
                '<small class="extra-stock-msg d-block mt-1" style="font-size:11px;"></small>' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<select name="extra_items[' + i + '][unit_id]" class="form-control" required style="min-width:100px;">' + unitOptions + '</select>' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<input type="number" name="extra_items[' + i + '][rate]" class="form-control extra-rate-input" step="0.001" min="0" style="width:100px;" value="0" readonly>' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<button type="button" class="btn btn-danger btn-sm" onclick="removeExtraRow(' + i + ')"><i class="fa fa-trash"></i></button>' +
            '</td>' +
            '</tr>';

        document.getElementById('extraItemsBody').insertAdjacentHTML('beforeend', row);

        var rowEl = document.getElementById('extra_row_' + i);
        var menu  = buildMenu(i);

        rowEl.querySelector('.ei-trigger').addEventListener('click', function (e) {
            e.stopPropagation();
            openMenu(this, menu);
        });

        rowEl.querySelector('.extra-qty-input').addEventListener('input', function () {
            checkStock(i);
        });
    }

    window.removeExtraRow = function (i) {
        // also remove the floating menu if present
        var menu = document.querySelector('.ei-dropdown-menu[data-row="' + i + '"]');
        if (menu) menu.remove();
        var row = document.getElementById('extra_row_' + i);
        if (row) row.remove();
    };

    document.getElementById('addExtraRow').addEventListener('click', addExtraRow);

    // Budget validation + double-submit guard
    var estimationAmt  = {{ (float)($estimationAmount ?? 0) }};
    var issuedTotal    = {{ $issuedGrandTotal }};
    var budgetExceeded = {{ $issuedExceedsEstimation ? 'true' : 'false' }};

    function calcNewItemsTotal() {
        var total = 0;
        // BOM available items (hidden inputs carry rate)
        @foreach ($available as $i => $item)
            total += {{ (float)($item->required_quantity ?? 0) }} * {{ (float)($item->rate ?? 0) }};
        @endforeach
        // Extra rows (production requests + manual add)
        document.querySelectorAll('#extraItemsBody tr').forEach(function (row) {
            var qtyEl  = row.querySelector('input[name*="[quantity]"]');
            var rateEl = row.querySelector('input[name*="[rate]"].extra-rate-hidden') ||
                         row.querySelector('input[name*="[rate]"]');
            var qty    = qtyEl  ? parseFloat(qtyEl.value)  || 0 : 0;
            var rate   = rateEl ? parseFloat(rateEl.value) || 0 : 0;
            total += qty * rate;
        });
        return total;
    }

    function getIssueSelectionError() {
        var hasBomItems = {{ count($available) > 0 ? 'true' : 'false' }};
        var hasValidExtraItem = false;
        var hasIncompleteExtraItem = false;

        document.querySelectorAll('#extraItemsBody tr').forEach(function (row) {
            var partEl = row.querySelector('input[name*="[part_item_id]"]');
            var qtyEl  = row.querySelector('input[name*="[quantity]"]');
            var unitEl = row.querySelector('select[name*="[unit_id]"], input[name*="[unit_id]"]');
            var partId = partEl ? String(partEl.value || '').trim() : '';
            var qty    = qtyEl ? parseFloat(qtyEl.value) || 0 : 0;
            var unitId = unitEl ? String(unitEl.value || '').trim() : '';
            var touched = partId !== '' || qty > 0 || unitId !== '';

            if (partId !== '' && qty > 0 && unitId !== '') {
                hasValidExtraItem = true;
            } else if (touched) {
                hasIncompleteExtraItem = true;
            }
        });

        if (!hasBomItems && !hasValidExtraItem) {
            return 'Please select/add at least one material item before Issue to Production.';
        }

        if (hasIncompleteExtraItem) {
            return 'Please complete Part Item, Quantity and Unit for additional material rows, or remove incomplete rows.';
        }

        return '';
    }

    function showIssueValidation(message) {
        var msgEl = document.getElementById('issueValidationMsg');
        if (!msgEl) {
            showAlert('Validation Required', message, 'warning');
            return;
        }
        msgEl.textContent = message;
        msgEl.style.display = 'block';
        msgEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function showAlert(title, text, icon) {
        if (window.Swal) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon || 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#25385F'
            });
        }
    }

    function confirmWithPrompt(options, onConfirm) {
        if (window.Swal) {
            Swal.fire({
                title: options.title,
                text: options.text,
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonText: options.confirmButtonText || 'Yes',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#25385F',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    onConfirm();
                }
            });
        }
    }

    document.querySelectorAll('#additionalReqForm, #shortageReqForm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (form.dataset.confirmed === '1') return;

            e.preventDefault();
            var btn = form.querySelector('.shortage-confirm-btn');
            confirmWithPrompt({
                title: btn ? btn.getAttribute('data-confirm-title') : 'Send Requisition?',
                text: btn ? btn.getAttribute('data-confirm-text') : 'Do you want to send this requisition to Purchase department?',
                confirmButtonText: btn ? btn.getAttribute('data-confirm-button') : 'Yes, Send'
            }, function () {
                form.dataset.confirmed = '1';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
                }
                form.submit();
            });
        });
    });

    var issueForm = document.getElementById('issueAvailableForm');
    if (issueForm) {
        issueForm.addEventListener('submit', function (e) {
            var btn = document.getElementById('issueBtn');
            if (!btn || btn.disabled) { e.preventDefault(); return false; }

            if (budgetExceeded) {
                e.preventDefault();
                showAlert('Issue Blocked', 'Already issued amount exceeds the estimation amount.', 'error');
                return false;
            }

            var selectionError = getIssueSelectionError();
            if (selectionError) {
                e.preventDefault();
                showIssueValidation(selectionError);
                return false;
            }

            if (estimationAmt > 0) {
                var newTotal      = calcNewItemsTotal();
                var combinedTotal = issuedTotal + newTotal;
                if (combinedTotal > estimationAmt) {
                    e.preventDefault();
                    var msg = 'Total to be issued (' + combinedTotal.toFixed(2) + ') will exceed the Estimation Amount (' + estimationAmt.toFixed(2) + ').\n\nDo you want to proceed anyway?';
                    confirmWithPrompt({
                        title: 'Estimation Amount Exceeded',
                        text: msg,
                        icon: 'warning',
                        confirmButtonText: 'Yes, Proceed'
                    }, function () {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
                        issueForm.submit();
                    });
                    return false;
                }
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
        });
    }

    // Accordion chevron for "Already Issued" section
    var issuedCollapse = document.getElementById('alreadyIssuedCollapse');
    if (issuedCollapse) {
        $(issuedCollapse).on('show.bs.collapse', function () {
            var toggle = document.querySelector('[data-target="#alreadyIssuedCollapse"]');
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
        });
        $(issuedCollapse).on('hide.bs.collapse', function () {
            var toggle = document.querySelector('[data-target="#alreadyIssuedCollapse"]');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        });
    }
})();
</script>
@endpush
@endsection
