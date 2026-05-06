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
                                {{ count($shortageSent) + count($shortageDraft) }} shortage,
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

                    {{-- Closed-product banner: page becomes preview-only (no Add / Issue / Send Requisition). --}}
                    @if($isClosed ?? false)
                    <div style="background:#fff3cd; color:#856404; border:1px solid #ffeeba; border-left:5px solid #e6a817; border-radius:4px; padding:12px 16px; margin-bottom:16px;">
                        <strong><i class="fa fa-eye"></i> Preview Only</strong> &mdash;
                        This product is <strong>CLOSED</strong>. Materials and shortage list are shown for reference;
                        adding/editing items, issuing to production, and sending purchase requisitions are disabled.
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
                                            @if(!($isClosed ?? false))
                                                <button type="button" class="btn btn-sm btn-light" id="addExtraRow">
                                                    <i class="fa fa-plus"></i> Add
                                                </button>
                                            @endif
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
                            @if($isClosed ?? false)
                                {{-- Production CLOSED: preview only — no Issue/Save action --}}
                            @elseif($issuedExceedsEstimation)
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
                    <div class="bom-check-section-title" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                        <span>
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                            Shortage Materials
                            @if($requisitionSent)
                                <span style="background:#28a745;color:#fff;padding:3px 10px;border-radius:4px;font-size:12px;">
                                    <i class="fa fa-check"></i> Requisition Sent to Purchase
                                </span>
                            @else
                                <span class="badge-shortage">Need to purchase</span>
                            @endif
                        </span>
                        @if(!($isClosed ?? false))
                            <button type="button" class="btn btn-sm btn-danger" id="addShortageManualRow" style="white-space:nowrap;">
                                <i class="fa fa-plus"></i> Add More
                            </button>
                        @endif
                    </div>

                    @if (count($shortageSent) > 0)
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
                                    @foreach ($shortageSent as $i => $item)
                                        @php
                                            // Rows in $shortageSent: is_sent_to_purchase=1 (sent) or null (BOM-derived, not yet in requisition)
                                            $isSent   = isset($item->is_sent_to_purchase) && (int) $item->is_sent_to_purchase === 1;
                                            $rowClass = $isSent ? 'shortage-sent-row' : 'shortage-row';
                                            $reqItemId = $item->requisition_item_id ?? null;
                                        @endphp
                                        <tr class="{{ $rowClass }}" id="shortage-row-{{ $reqItemId ?? 'bom-' . $i }}">
                                            <td style="width:45px;">{{ $i + 1 }}</td>
                                            <td style="white-space:nowrap; font-size:12px; color:#555;">
                                                {{ ($item->created_at ?? null) ? \Carbon\Carbon::parse($item->created_at)->format('d M Y, h:i A') : '—' }}
                                            </td>
                                            <td>
                                                {{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}
                                                @if($isSent)
                                                    <span class="badge-sent-purchase"><i class="fa fa-check"></i> Sent to Purchase</span>
                                                @elseif($requisitionSent)
                                                    <span class="badge-not-sent"><i class="fa fa-exclamation"></i> Not in Requisition</span>
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
                             Additional Storage Items (manually added + production requests)
                             — draft rows from +Add More AND production-derived shortage drafts
                             ======================== --}}
                        @php $showManualSection = count($shortageDraft) > 0; @endphp
                        @if(!($isClosed ?? false))
                        <div id="manualShortageSection" style="margin-top:14px;{{ $showManualSection ? '' : ' display:none;' }}">
                            <div style="font-weight:600; font-size:14px; color:#dc3545; margin-bottom:6px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span><i class="fa fa-plus-circle"></i> Additional Storage Items (manually added)</span>
                                @if(count($shortageDraft) > 0)
                                    <span style="background:#fd7e14;color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;">
                                        <span id="pendingDraftCount">{{ count($shortageDraft) }}</span> pending
                                    </span>
                                @else
                                    <span id="pendingDraftCountBadge" style="background:#fd7e14;color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;display:none;">
                                        <span id="pendingDraftCount">0</span> pending
                                    </span>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="manualShortageTable">
                                    <thead style="background:#dc3545; color:#fff;">
                                        <tr>
                                            <th style="width:40px;">Sr.</th>
                                            <th style="min-width:260px;">Part Item</th>
                                            <th style="width:120px;">Required Qty</th>
                                            <th style="width:120px;">Available Stock</th>
                                            <th style="width:120px;">Shortage Qty</th>
                                            <th style="width:120px;">Unit</th>
                                            <th style="width:110px;">Rate</th>
                                            <th style="width:40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="manualShortageBody">
                                        {{-- Pre-rendered draft rows (is_sent_to_purchase=0) — editable --}}
                                        @foreach ($shortageDraft as $di => $ditem)
                                        <tr id="draft_row_{{ $ditem->requisition_item_id }}"
                                            class="shortage-new-row"
                                            data-req-item-id="{{ $ditem->requisition_item_id }}">
                                            <td style="vertical-align:middle;">{{ $di + 1 }}</td>
                                            <td style="vertical-align:middle; min-width:260px;">
                                                <input type="text" class="form-control"
                                                       value="{{ $ditem->product_description ?? (optional($ditem->partItem)->description ?? '—') }}"
                                                       readonly style="background:#f8f9fa;">
                                                <input type="hidden" class="sm-part-id" value="{{ $ditem->part_item_id }}">
                                                <input type="hidden" class="sm-desc" value="{{ $ditem->product_description ?? (optional($ditem->partItem)->description ?? '') }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-qty-input"
                                                       step="0.001" min="0.001" style="width:110px;"
                                                       value="{{ $ditem->required_quantity }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-avail-stock"
                                                       step="0.001" readonly style="width:110px; background:#f8f9fa;"
                                                       value="{{ $ditem->available_stock }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-shortage-qty"
                                                       step="0.001" readonly style="width:110px; background:#f8f9fa;"
                                                       value="{{ $ditem->shortage_quantity }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <select class="form-control sm-unit-select" style="min-width:100px;">
                                                    <option value="">Select Unit</option>
                                                    @foreach ($unitMasters as $u)
                                                        <option value="{{ $u->id }}" {{ $u->id == $ditem->unit_id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-rate-input"
                                                       step="0.001" min="0" style="width:100px;"
                                                       value="{{ $ditem->rate ?? 0 }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <button type="button" class="btn btn-danger btn-sm delete-draft-btn"
                                                        data-id="{{ $ditem->requisition_item_id }}"
                                                        title="Delete this draft item">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        {{-- JS-added new rows appended here --}}
                                    </tbody>
                                </table>
                            </div>
                            <div id="shortageManualValidationMsg" class="issue-validation-msg"></div>
                            <div style="margin-top:8px;">
                                <button type="button" class="btn btn-danger" id="sendManualShortageBtn">
                                    <i class="fa fa-paper-plane"></i>
                                    Send <span id="sendPendingCountLabel">{{ count($shortageDraft) }}</span> New Item(s) to Purchase
                                </button>
                            </div>
                        </div>
                        @endif

                        {{-- ========================
                             Shortage Requisition Form / Sent Notice
                             ======================== --}}
                        @if($requisitionSent)
                            {{-- Main requisition already sent. Show status + "Send Pending" button for draft rows. --}}
                            @php
                                // notSentItems: BOM/production shortage rows in $shortageSent with NO requisition_items record at all
                                // (is_sent_to_purchase === null means not in any requisition yet)
                                $notSentItems = collect($shortageSent)->filter(function($item) use ($sentPartIds) {
                                    return !in_array((string)($item->part_item_id ?? ''), $sentPartIds)
                                        && !(isset($item->is_sent_to_purchase) && (int)$item->is_sent_to_purchase === 1);
                                })->values();
                            @endphp

                            <div style="margin-top:12px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                                <a href="{{ route('list-accepted-design-from-prod') }}" class="btn btn-white">Cancel</a>
                                <button type="button" class="btn btn-success" disabled style="cursor:not-allowed; opacity:0.85;">
                                    <i class="fa fa-check-circle"></i>
                                    Requisition Already Sent to Purchase
                                </button>
                                @if(($hasDraftRows ?? false) && !($isClosed ?? false))
                                    <button type="button" class="btn btn-warning" id="sendPendingBtn"
                                            data-bd="{{ $productDetails->id }}"
                                            style="font-weight:600;">
                                        <i class="fa fa-paper-plane"></i>
                                        Send Pending to Purchase
                                    </button>
                                @endif
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

                            {{-- Hidden form for manual shortage rows when requisition already sent --}}
                            @if(!($isClosed ?? false))
                            <form action="{{ route('store-additional-shortage-requisition') }}" method="POST" id="manualAddMoreForm" style="display:none;">
                                @csrf
                                <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">
                                {{-- manual_shortage[] hidden inputs injected by JS before submit --}}
                            </form>
                            @endif
                        @else
                            @php $design_id_for_form = $shortageSent[0]->design_id ?? ($shortage[0]->design_id ?? null); @endphp
                            <form action="{{ route('store-shortage-requisition') }}" method="POST" id="shortageReqForm">
                                @csrf
                                <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">
                                <input type="hidden" name="business_id"         value="{{ $productDetails->business_id }}">
                                <input type="hidden" name="design_id"           value="{{ $design_id_for_form }}">

                                @foreach ($shortageSent as $i => $item)
                                    <input type="hidden" name="items[{{ $i }}][part_item_id]"        value="{{ $item->part_item_id }}">
                                    <input type="hidden" name="items[{{ $i }}][product_description]" value="{{ $item->product_description ?? (optional($item->partItem)->description ?? '') }}">
                                    <input type="hidden" name="items[{{ $i }}][required_quantity]"   value="{{ $item->required_quantity }}">
                                    <input type="hidden" name="items[{{ $i }}][available_quantity]"  value="{{ $item->available_stock }}">
                                    <input type="hidden" name="items[{{ $i }}][shortage_quantity]"   value="{{ $item->shortage_quantity }}">
                                    <input type="hidden" name="items[{{ $i }}][unit_id]"             value="{{ $item->unit_id }}">
                                    <input type="hidden" name="items[{{ $i }}][rate]"                value="{{ $item->rate }}">
                                @endforeach
                                {{-- manual_shortage[] hidden inputs injected by JS before shortageReqForm submit --}}

                                <div class="login-btn-inner" style="margin-top:10px;">
                                    <div class="login-horizental cancel-wp">
                                        <a href="{{ route('list-accepted-design-from-prod') }}"
                                           class="btn btn-white" style="margin-right:10px;">
                                            @if($isClosed ?? false) Back @else Cancel @endif
                                        </a>
                                        @if(!($isClosed ?? false))
                                            <button type="submit" class="btn btn-danger shortage-confirm-btn"
                                                    data-confirm-title="Send Shortage Requisition?"
                                                    data-confirm-text="Submit {{ count($shortageSent) }} shortage item(s) as a requisition to Purchase department?"
                                                    data-confirm-button="Yes, Send Requisition">
                                                <i class="fa fa-paper-plane"></i>
                                                Send Shortage List as Requisition to Purchase
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        @endif

                    @else
                        {{-- No BOM-derived shortage items, but user can still add manual shortage items / has drafts --}}
                        @php $showManualSectionNoSent = count($shortageDraft) > 0; @endphp
                        @if(!($isClosed ?? false))
                        <div id="manualShortageSection" style="margin-top:14px;{{ $showManualSectionNoSent ? '' : ' display:none;' }}">
                            <div style="font-weight:600; font-size:14px; color:#dc3545; margin-bottom:6px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span><i class="fa fa-plus-circle"></i> Additional Storage Items (manually added)</span>
                                @if(count($shortageDraft) > 0)
                                    <span style="background:#fd7e14;color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;">
                                        <span id="pendingDraftCount">{{ count($shortageDraft) }}</span> pending
                                    </span>
                                @else
                                    <span id="pendingDraftCountBadge" style="background:#fd7e14;color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;display:none;">
                                        <span id="pendingDraftCount">0</span> pending
                                    </span>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="manualShortageTable">
                                    <thead style="background:#dc3545; color:#fff;">
                                        <tr>
                                            <th style="width:40px;">Sr.</th>
                                            <th style="min-width:260px;">Part Item</th>
                                            <th style="width:120px;">Required Qty</th>
                                            <th style="width:120px;">Available Stock</th>
                                            <th style="width:120px;">Shortage Qty</th>
                                            <th style="width:120px;">Unit</th>
                                            <th style="width:110px;">Rate</th>
                                            <th style="width:40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="manualShortageBody">
                                        {{-- Pre-rendered draft rows (is_sent_to_purchase=0) --}}
                                        @foreach ($shortageDraft as $di => $ditem)
                                        <tr id="draft_row_{{ $ditem->requisition_item_id }}"
                                            class="shortage-new-row"
                                            data-req-item-id="{{ $ditem->requisition_item_id }}">
                                            <td style="vertical-align:middle;">{{ $di + 1 }}</td>
                                            <td style="vertical-align:middle; min-width:260px;">
                                                <input type="text" class="form-control"
                                                       value="{{ $ditem->product_description ?? (optional($ditem->partItem)->description ?? '—') }}"
                                                       readonly style="background:#f8f9fa;">
                                                <input type="hidden" class="sm-part-id" value="{{ $ditem->part_item_id }}">
                                                <input type="hidden" class="sm-desc" value="{{ $ditem->product_description ?? (optional($ditem->partItem)->description ?? '') }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-qty-input"
                                                       step="0.001" min="0.001" style="width:110px;"
                                                       value="{{ $ditem->required_quantity }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-avail-stock"
                                                       step="0.001" readonly style="width:110px; background:#f8f9fa;"
                                                       value="{{ $ditem->available_stock }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-shortage-qty"
                                                       step="0.001" readonly style="width:110px; background:#f8f9fa;"
                                                       value="{{ $ditem->shortage_quantity }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <select class="form-control sm-unit-select" style="min-width:100px;">
                                                    <option value="">Select Unit</option>
                                                    @foreach ($unitMasters as $u)
                                                        <option value="{{ $u->id }}" {{ $u->id == $ditem->unit_id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <input type="number" class="form-control sm-rate-input"
                                                       step="0.001" min="0" style="width:100px;"
                                                       value="{{ $ditem->rate ?? 0 }}">
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <button type="button" class="btn btn-danger btn-sm delete-draft-btn"
                                                        data-id="{{ $ditem->requisition_item_id }}"
                                                        title="Delete this draft item">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        {{-- JS-added new rows appended here --}}
                                    </tbody>
                                </table>
                            </div>
                            <div id="shortageManualValidationMsg" class="issue-validation-msg"></div>
                            <div style="margin-top:8px;">
                                <button type="button" class="btn btn-danger" id="sendManualShortageBtn">
                                    <i class="fa fa-paper-plane"></i>
                                    Send <span id="sendPendingCountLabel">{{ count($shortageDraft) }}</span> New Item(s) to Purchase
                                </button>
                            </div>
                        </div>

                        {{-- Form for manual shortage when no BOM shortage exists yet --}}
                        @if($requisitionSent)
                            <form action="{{ route('store-additional-shortage-requisition') }}" method="POST" id="manualAddMoreForm" style="display:none;">
                                @csrf
                                <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">
                            </form>
                        @else
                            <form action="{{ route('store-shortage-requisition') }}" method="POST" id="shortageReqForm" style="display:none;">
                                @csrf
                                <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">
                                <input type="hidden" name="business_id"         value="{{ $productDetails->business_id }}">
                                <input type="hidden" name="design_id"           value="{{ null }}">
                                {{-- manual_shortage[] hidden inputs injected by JS before submit --}}
                            </form>
                        @endif
                        @endif

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

    // ── Manual Shortage Rows (Add More) ──────────────────────────────────
    var shortageManualRowCount = 0;
    var requisitionSentFlag = {{ $requisitionSent ? 'true' : 'false' }};

    // Count of pre-rendered draft rows (from server — is_sent_to_purchase=0)
    var preRenderedDraftCount = {{ count($shortageDraft) }};

    // Update the pending count badge in the section header and send button label
    function updateDraftCount() {
        var total = document.querySelectorAll('#manualShortageBody tr').length;
        var countEl = document.getElementById('pendingDraftCount');
        if (countEl) countEl.textContent = total;
        var sendLabelEl = document.getElementById('sendPendingCountLabel');
        if (sendLabelEl) sendLabelEl.textContent = total;
        // Show/hide the badge for the no-draft initial state
        var badge = document.getElementById('pendingDraftCountBadge');
        if (badge) badge.style.display = total > 0 ? '' : 'none';
    }

    // Wire qty-change on pre-rendered draft rows (recompute shortage qty on input)
    document.querySelectorAll('#manualShortageBody tr[data-req-item-id]').forEach(function (row) {
        var qtyInput   = row.querySelector('.sm-qty-input');
        var availInput = row.querySelector('.sm-avail-stock');
        var shortInput = row.querySelector('.sm-shortage-qty');
        if (qtyInput) {
            qtyInput.addEventListener('input', function () {
                var qty   = parseFloat(this.value) || 0;
                var avail = parseFloat(availInput ? availInput.value : 0) || 0;
                if (shortInput) shortInput.value = Math.max(0, qty - avail).toFixed(3);
            });
        }
    });

    function buildShortageMenu(rowIndex) {
        var menu = document.createElement('div');
        menu.className = 'ei-dropdown-menu';
        menu.setAttribute('data-srow', rowIndex);
        menu.innerHTML =
            '<div class="ei-search-box"><input type="text" class="form-control form-control-sm ei-search-input" placeholder="Search part..."></div>' +
            '<div class="ei-options-list"></div>';

        var list = menu.querySelector('.ei-options-list');
        partData.forEach(function (p) {
            var opt = document.createElement('div');
            opt.className   = 'ei-option';
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
            var rowEl = document.getElementById('shortage_manual_row_' + rowIndex);
            if (!rowEl) return;
            rowEl.querySelector('.sm-trigger').textContent  = opt.textContent;
            rowEl.querySelector('.sm-part-id').value        = opt.getAttribute('data-id');
            rowEl.querySelector('.sm-rate-input').value     = opt.getAttribute('data-rate');
            rowEl.querySelector('.sm-desc').value           = opt.textContent;
            closeActiveMenu();
            checkShortageStock(rowIndex);
        });

        document.body.appendChild(menu);
        return menu;
    }

    function checkShortageStock(rowIndex) {
        var rowEl = document.getElementById('shortage_manual_row_' + rowIndex);
        if (!rowEl) return;
        var partItemId = rowEl.querySelector('.sm-part-id').value;
        var quantity   = rowEl.querySelector('.sm-qty-input').value;
        var availInput = rowEl.querySelector('.sm-avail-stock');
        var shortInput = rowEl.querySelector('.sm-shortage-qty');

        if (!partItemId) {
            if (availInput) availInput.value = '0';
            if (shortInput) shortInput.value = '0';
            return;
        }

        var qty = parseFloat(quantity) || 0;

        fetch(checkStockUrl + '?part_item_id=' + encodeURIComponent(partItemId) +
              '&quantity=1&material_send_production=0&quantity_minus_status=pending')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var avail = (data.available_quantity !== undefined) ? parseFloat(data.available_quantity) : 0;
                if (availInput) availInput.value = avail.toFixed(3);
                var shortage = Math.max(0, qty - avail);
                if (shortInput) shortInput.value = shortage.toFixed(3);
            })
            .catch(function () {
                if (availInput) availInput.value = '0';
                var shortage = Math.max(0, parseFloat(quantity) || 0);
                if (shortInput) shortInput.value = shortage.toFixed(3);
            });
    }

    function addShortageManualRow() {
        var i = shortageManualRowCount++;
        var row =
            '<tr id="shortage_manual_row_' + i + '">' +
            '<td style="vertical-align:middle;">' + (i + 1) + '</td>' +
            '<td style="vertical-align:middle; min-width:260px;">' +
                '<div class="ei-dropdown">' +
                    '<button type="button" class="btn btn-default form-control sm-trigger" style="text-align:left;">-- Select Part Item --</button>' +
                '</div>' +
                '<input type="hidden" class="sm-part-id" value="">' +
                '<input type="hidden" class="sm-desc" value="">' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<input type="number" class="form-control sm-qty-input" step="0.001" min="0.001" style="width:110px;" placeholder="0">' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<input type="number" class="form-control sm-avail-stock" step="0.001" min="0" value="0" readonly style="width:110px; background:#f8f9fa;">' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<input type="number" class="form-control sm-shortage-qty" step="0.001" min="0" value="0" readonly style="width:110px; background:#f8f9fa;">' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<select class="form-control sm-unit-select" style="min-width:100px;">' + unitOptions + '</select>' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<input type="number" class="form-control sm-rate-input" step="0.001" min="0" value="0" style="width:100px;">' +
            '</td>' +
            '<td style="vertical-align:middle;">' +
                '<button type="button" class="btn btn-danger btn-sm" onclick="removeShortageManualRow(' + i + ')"><i class="fa fa-trash"></i></button>' +
            '</td>' +
            '</tr>';

        document.getElementById('manualShortageBody').insertAdjacentHTML('beforeend', row);

        // Show the section
        var section = document.getElementById('manualShortageSection');
        if (section) section.style.display = '';

        updateDraftCount();

        var rowEl = document.getElementById('shortage_manual_row_' + i);
        var menu  = buildShortageMenu(i);

        rowEl.querySelector('.sm-trigger').addEventListener('click', function (e) {
            e.stopPropagation();
            openMenu(this, menu);
        });

        rowEl.querySelector('.sm-qty-input').addEventListener('input', function () {
            var availInput = rowEl.querySelector('.sm-avail-stock');
            var shortInput = rowEl.querySelector('.sm-shortage-qty');
            var qty = parseFloat(this.value) || 0;
            var avail = parseFloat(availInput ? availInput.value : 0) || 0;
            if (shortInput) shortInput.value = Math.max(0, qty - avail).toFixed(3);
            checkShortageStock(i);
        });
    }

    window.removeShortageManualRow = function (i) {
        var menu = document.querySelector('.ei-dropdown-menu[data-srow="' + i + '"]');
        if (menu) menu.remove();
        var row = document.getElementById('shortage_manual_row_' + i);
        if (row) row.remove();
        updateDraftCount();
        var body = document.getElementById('manualShortageBody');
        var section = document.getElementById('manualShortageSection');
        if (body && section && body.children.length === 0 && preRenderedDraftCount === 0) {
            section.style.display = 'none';
        }
    };

    function hasIncompleteShortageRow() {
        var incomplete = false;
        document.querySelectorAll('#manualShortageBody tr').forEach(function (row) {
            var partId = row.querySelector('.sm-part-id') ? row.querySelector('.sm-part-id').value.trim() : '';
            var qty    = parseFloat(row.querySelector('.sm-qty-input') ? row.querySelector('.sm-qty-input').value : 0) || 0;
            var unitId = row.querySelector('.sm-unit-select') ? row.querySelector('.sm-unit-select').value.trim() : '';
            if (!partId || qty <= 0 || !unitId) { incomplete = true; }
        });
        return incomplete;
    }

    function prepareManualShortageInputs(formEl) {
        // Remove any previously injected manual_shortage inputs
        formEl.querySelectorAll('input[name^="manual_shortage"]').forEach(function (el) { el.remove(); });
        var rows = document.querySelectorAll('#manualShortageBody tr');
        rows.forEach(function (row, idx) {
            var partId      = row.querySelector('.sm-part-id').value;
            var desc        = row.querySelector('.sm-desc').value;
            var qty         = row.querySelector('.sm-qty-input').value;
            var availStock  = row.querySelector('.sm-avail-stock').value || '0';
            var shortageQty = row.querySelector('.sm-shortage-qty').value || '0';
            var unitId      = row.querySelector('.sm-unit-select').value;
            var rate        = row.querySelector('.sm-rate-input').value || '0';

            function addHidden(name, val) {
                var inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = name; inp.value = val;
                formEl.appendChild(inp);
            }
            addHidden('manual_shortage[' + idx + '][part_item_id]',        partId);
            addHidden('manual_shortage[' + idx + '][product_description]',  desc);
            addHidden('manual_shortage[' + idx + '][required_quantity]',    qty);
            addHidden('manual_shortage[' + idx + '][available_quantity]',   availStock);
            addHidden('manual_shortage[' + idx + '][shortage_quantity]',    shortageQty);
            addHidden('manual_shortage[' + idx + '][unit_id]',              unitId);
            addHidden('manual_shortage[' + idx + '][rate]',                 rate);
        });
    }

    // Wire +Add More button for shortage
    var addShortageBtn = document.getElementById('addShortageManualRow');
    if (addShortageBtn) {
        addShortageBtn.addEventListener('click', addShortageManualRow);
    }

    // Helper to get CSRF token
    function getCsrfToken() {
        var metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) return metaTag.getAttribute('content');
        var tokenInput = document.querySelector('input[name="_token"]');
        return tokenInput ? tokenInput.value : '';
    }

    // Wire Send Manual Shortage button — combined update-existing-drafts + insert-new-rows + sendPending chain
    var sendManualBtn = document.getElementById('sendManualShortageBtn');
    if (sendManualBtn) {
        sendManualBtn.addEventListener('click', function () {
            var allRows = document.querySelectorAll('#manualShortageBody tr');
            var msgEl   = document.getElementById('shortageManualValidationMsg');
            if (msgEl) { msgEl.style.display = 'none'; }

            if (allRows.length === 0) {
                showAlert('Validation', 'Please add at least one shortage item row before sending.', 'warning');
                return;
            }
            if (hasIncompleteShortageRow()) {
                if (msgEl) {
                    msgEl.textContent = 'Please complete Part Item, Required Qty (> 0), and Unit for all rows.';
                    msgEl.style.display = 'block';
                    msgEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    showAlert('Validation', 'Please complete Part Item, Required Qty, and Unit for all rows.', 'warning');
                }
                return;
            }

            // State 1: No requisition sent yet — delegate to shortageReqForm which handles BOM + manual rows together
            if (!requisitionSentFlag) {
                var reqForm = document.getElementById('shortageReqForm');
                if (reqForm) {
                    reqForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                } else {
                    showAlert('Error', 'Form not found. Please reload the page.', 'error');
                }
                return;
            }

            // State 2: Requisition already sent — AJAX chain: update existing drafts + insert new rows + sendPending
            // Separate existing draft rows (have data-req-item-id) from new rows (do not)
            var existingDraftRows = [];
            var newRows           = [];

            allRows.forEach(function (row) {
                var reqItemId   = row.getAttribute('data-req-item-id') || '';
                var partId      = row.querySelector('.sm-part-id')     ? row.querySelector('.sm-part-id').value.trim()     : '';
                var qty         = parseFloat(row.querySelector('.sm-qty-input')    ? row.querySelector('.sm-qty-input').value    : 0) || 0;
                var unitId      = row.querySelector('.sm-unit-select') ? row.querySelector('.sm-unit-select').value.trim() : '';
                var rate        = parseFloat(row.querySelector('.sm-rate-input')   ? row.querySelector('.sm-rate-input').value   : 0) || 0;
                var avail       = parseFloat(row.querySelector('.sm-avail-stock')  ? row.querySelector('.sm-avail-stock').value  : 0) || 0;
                var shortage    = parseFloat(row.querySelector('.sm-shortage-qty') ? row.querySelector('.sm-shortage-qty').value : 0) || 0;
                var desc        = row.querySelector('.sm-desc')        ? row.querySelector('.sm-desc').value                : '';

                if (!partId || qty <= 0 || !unitId) return; // skip incomplete (already validated above)

                if (reqItemId) {
                    existingDraftRows.push({ requisition_item_id: reqItemId, required_quantity: qty, unit_id: unitId, rate: rate });
                } else {
                    newRows.push({ part_item_id: partId, product_description: desc, required_quantity: qty,
                                   available_quantity: avail, shortage_quantity: shortage, unit_id: unitId, rate: rate });
                }
            });

            var totalCount = existingDraftRows.length + newRows.length;
            var bdId       = '{{ $productDetails->id }}';
            var csrfToken  = getCsrfToken();

            confirmWithPrompt({
                title: 'Send to Purchase?',
                text:  'Send ' + totalCount + ' shortage item(s) to the Purchase department?',
                icon:  'question',
                confirmButtonText: 'Yes, Send'
            }, function () {
                sendManualBtn.disabled = true;
                sendManualBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

                // Step 1: Update existing draft rows via updateDraftShortageItem
                var updatePromises = existingDraftRows.map(function (row) {
                    return fetch('{{ route("update-draft-shortage-item") }}', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body:    JSON.stringify(row)
                    }).then(function (r) { return r.json(); });
                });

                Promise.all(updatePromises).then(function (updateResults) {
                    var updateFailed = updateResults.some(function (r) { return r && r.status !== 'success'; });
                    if (updateFailed) {
                        var failMsg = updateResults.filter(function (r) { return r && r.status !== 'success'; })
                                                   .map(function (r) { return r.msg || 'Update failed'; }).join('; ');
                        showAlert('Error', 'Could not update draft items: ' + failMsg, 'error');
                        sendManualBtn.disabled = false;
                        sendManualBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send <span id="sendPendingCountLabel">' + totalCount + '</span> New Item(s) to Purchase';
                        return Promise.reject('update_failed');
                    }

                    // Step 2: Insert new rows via storeAdditionalShortageRequisition (AJAX)
                    if (newRows.length === 0) {
                        return Promise.resolve({ status: 'success' });
                    }

                    var formData = new FormData();
                    formData.append('_token', csrfToken);
                    formData.append('business_details_id', bdId);
                    newRows.forEach(function (row, idx) {
                        Object.keys(row).forEach(function (key) {
                            formData.append('manual_shortage[' + idx + '][' + key + ']', row[key]);
                        });
                    });

                    return fetch('{{ route("store-additional-shortage-requisition") }}', {
                        method:  'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body:    formData
                    }).then(function (r) { return r.json(); });

                }).then(function (insertResult) {
                    if (!insertResult || insertResult.status !== 'success') {
                        var errMsg = insertResult ? (insertResult.msg || 'Failed to save new items.') : 'No response from server.';
                        showAlert('Error', errMsg, 'error');
                        sendManualBtn.disabled = false;
                        sendManualBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send <span id="sendPendingCountLabel">' + totalCount + '</span> New Item(s) to Purchase';
                        return Promise.reject('insert_failed');
                    }

                    // Step 3: Flip all drafts to sent
                    return fetch('{{ route("send-pending-shortage-to-purchase") }}', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body:    JSON.stringify({ business_details_id: bdId })
                    }).then(function (r) { return r.json(); });

                }).then(function (sendResult) {
                    if (!sendResult) return;
                    if (sendResult.status === 'success' || sendResult.status === 'info') {
                        showAlert('Success', sendResult.msg || 'Items sent to Purchase.', 'success');
                        setTimeout(function () { location.reload(); }, 1500);
                    } else {
                        showAlert('Error', sendResult.msg || 'Something went wrong.', 'error');
                        sendManualBtn.disabled = false;
                        sendManualBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send <span id="sendPendingCountLabel">' + totalCount + '</span> New Item(s) to Purchase';
                    }
                }).catch(function (reason) {
                    if (reason !== 'update_failed' && reason !== 'insert_failed') {
                        showAlert('Error', 'Network error. Please try again.', 'error');
                        sendManualBtn.disabled = false;
                        sendManualBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send <span id="sendPendingCountLabel">' + totalCount + '</span> New Item(s) to Purchase';
                    }
                });
            });
        });
    }

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

            // For shortageReqForm: validate and include any manually-added shortage rows
            if (form.id === 'shortageReqForm') {
                if (typeof hasIncompleteShortageRow === 'function' && hasIncompleteShortageRow()) {
                    var smMsgEl = document.getElementById('shortageManualValidationMsg');
                    if (smMsgEl) {
                        smMsgEl.textContent = 'Please complete Part Item, Required Qty (> 0), and Unit for all manually-added rows, or remove them.';
                        smMsgEl.style.display = 'block';
                        smMsgEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        showAlert('Validation', 'Please complete or remove the incomplete manually-added shortage rows.', 'warning');
                    }
                    return;
                }
                if (typeof prepareManualShortageInputs === 'function') {
                    prepareManualShortageInputs(form);
                }
            }

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

    // ── Send Pending to Purchase ──────────────────────────────────────────
    var sendPendingBtn = document.getElementById('sendPendingBtn');
    if (sendPendingBtn) {
        sendPendingBtn.addEventListener('click', function () {
            var bd = sendPendingBtn.getAttribute('data-bd');
            confirmWithPrompt({
                title: 'Send Pending Items?',
                text: 'Send all pending (draft) shortage items to the Purchase department?',
                icon: 'question',
                confirmButtonText: 'Yes, Send'
            }, function () {
                sendPendingBtn.disabled = true;
                sendPendingBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

                var csrfToken = document.querySelector('meta[name="csrf-token"]')
                    ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    : (document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '');

                fetch('{{ route("send-pending-shortage-to-purchase") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ business_details_id: bd })
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.status === 'success') {
                        showAlert('Success', data.msg, 'success');
                        setTimeout(function () { location.reload(); }, 1500);
                    } else if (data.status === 'info') {
                        showAlert('Nothing to Send', data.msg, 'info');
                        sendPendingBtn.disabled = false;
                        sendPendingBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Pending to Purchase';
                    } else {
                        showAlert('Error', data.msg || 'Something went wrong.', 'error');
                        sendPendingBtn.disabled = false;
                        sendPendingBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Pending to Purchase';
                    }
                })
                .catch(function () {
                    showAlert('Error', 'Network error. Please try again.', 'error');
                    sendPendingBtn.disabled = false;
                    sendPendingBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Pending to Purchase';
                });
            });
        });
    }

    // ── Delete Draft Shortage Item ────────────────────────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.delete-draft-btn');
        if (!btn) return;
        var reqItemId = btn.getAttribute('data-id');
        // Row may be in the main shortage table (shortage-row-N) OR in the draft section (draft_row_N)
        var rowEl = document.getElementById('shortage-row-' + reqItemId) ||
                    document.getElementById('draft_row_' + reqItemId);

        confirmWithPrompt({
            title: 'Delete Draft Item?',
            text: 'Remove this pending shortage item? It has not been sent to Purchase yet.',
            icon: 'warning',
            confirmButtonText: 'Yes, Delete'
        }, function () {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            var csrfToken = getCsrfToken();

            fetch('{{ route("delete-draft-shortage-item") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ requisition_item_id: reqItemId })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'success') {
                    if (rowEl) rowEl.remove();
                    // Decrement preRenderedDraftCount if this was a server-side draft row
                    if (preRenderedDraftCount > 0) preRenderedDraftCount--;
                    updateDraftCount();
                    // If no draft rows remain, hide section (only if no pre-rendered either)
                    var remainingDraft = document.querySelectorAll('#manualShortageBody tr');
                    if (remainingDraft.length === 0 && preRenderedDraftCount === 0) {
                        var section = document.getElementById('manualShortageSection');
                        if (section) section.style.display = 'none';
                    }
                    // Hide the legacy "Send Pending" button if it exists and no drafts left
                    var remainingBtns = document.querySelectorAll('.delete-draft-btn');
                    if (remainingBtns.length === 0) {
                        var spBtn = document.getElementById('sendPendingBtn');
                        if (spBtn) spBtn.style.display = 'none';
                    }
                } else if (data.status === 'error' && data.msg && data.msg.indexOf('already been sent') !== -1) {
                    showAlert('Cannot Delete', data.msg, 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-trash"></i>';
                } else {
                    showAlert('Error', data.msg || 'Something went wrong.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-trash"></i>';
                }
            })
            .catch(function () {
                showAlert('Error', 'Network error. Please try again.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-trash"></i>';
            });
        });
    });
})();
</script>
@endpush
@endsection
