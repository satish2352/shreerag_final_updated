@extends('admin.layouts.master')
@section('content')
    @php
        // Trolley column helpers — used across all four BOM tables on this page.
        $PIECE_UNITS = ['NOS', 'PCS', 'SET', 'EACH'];
        $computeMtrN = function ($mtr, $qty, $unitName, $trolleyQty) use ($PIECE_UNITS) {
            $t = (int) ($trolleyQty ?: 1);
            $isPiece = in_array(strtoupper(trim((string) $unitName)), $PIECE_UNITS, true);
            if ($isPiece) {
                // Piece-unit: scale quantity by trolley count
                $base = (float) ($qty ?? 0);
            } else {
                // Length-unit: scale mtr_for_01_nos_trolley; null means no data → return null
                if ($mtr === null || $mtr === '') {
                    return null;
                }
                $base = (float) $mtr;
            }
            return $base * $t;
        };
        $fmt = fn($n) => $n === null || $n === ''
            ? '&mdash;'
            : rtrim(rtrim(number_format((float) $n, 3, '.', ''), '0'), '.');
        // T-2026-058: unit-aware issue quantity for BOM-prefilled rows in the
        // "Additional Items to Issue" grid — piece units use required_quantity*trolleyQty
        // (already what $computeMtrN returns for piece units), length/raw units use
        // mtr_for_01_nos_trolley*trolleyQty ($computeMtrN's non-piece branch). When
        // $computeMtrN returns null (length-unit row with no mtr data), fall back to
        // required_quantity*trolleyQty (T-2026-058 iteration 2 fix — the fallback must
        // stay trolley-scaled like every other branch, otherwise multi-trolley orders
        // silently under-issue by a factor of 1/trolleyQty) so the row is still issuable.
        $computeIssueQty = fn($mtrN, $reqQty, $trolleyQty) => $mtrN !== null
            ? $mtrN
            : ((float) ($reqQty ?? 0)) * ((float) ($trolleyQty ?? 1));
        // T-2026-061: the controller now allocates one shared physical stock balance
        // across every BOM row that names the same part_item_id, and publishes each
        // row's slice as `issue_quantity`. When present it is AUTHORITATIVE and must
        // win over the local recomputation above — on a partially-covered row it is
        // deliberately smaller than $computeIssueQty's full requirement, which is what
        // stops the grid from offering stock a previous row has already claimed (and
        // stops issueAvailableMaterials() rejecting the row with "Insufficient stock").
        $resolveIssueQty = fn($item, $mtrN, $trolleyQty) => isset($item->issue_quantity)
            ? (float) $item->issue_quantity
            : $computeIssueQty($mtrN, $item->required_quantity ?? 0, $trolleyQty);
    @endphp
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

        /* Wrap long Product Description text in the Shortage Materials table
           so a single long row doesn't force the whole table wide.
           Bootstrap's default table-layout: auto IGNORES max-width on cells —
           it expands the column to fit the longest token. So we set explicit
           width + !important to override the framework rule.
           T-2026-058: switched from :nth-child(3) positional targeting to a
           dedicated .shortage-desc-col class — the Length/Total in mm columns
           inserted by this task change column indices across the row types
           (BOM rows vs draft rows), so a class is the robust choice.         */
        .table-shortage th.shortage-desc-col,
        .table-shortage td.shortage-desc-col {
            width: 320px !important;
            min-width: 220px !important;
            max-width: 360px !important;
            white-space: normal !important;
            word-break: break-word !important;
            overflow-wrap: anywhere !important;
        }
        /* The product-description cell wraps its text in a <span>. Force the
           <span> (and any nested inline-block) to respect the column width
           so long unbroken tokens like SKU codes also wrap.                  */
        .table-shortage td.shortage-desc-col span,
        .table-shortage td.shortage-desc-col > * {
            max-width: 100%;
            white-space: normal !important;
            word-break: break-word !important;
            overflow-wrap: anywhere !important;
        }

        /* Manually-added rows have a subtle orange tint to distinguish from BOM rows */
        tr.shortage-row-manual td {
            background-color: #fff8f0 !important;
        }

        /* Trolley input columns in manual rows: allow wrap on narrow viewports */
        .table-shortage .short-mtr1,
        .table-shortage .short-mtrN {
            min-width: 90px;
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

        .accordion-chevron-avail {
            transition: transform 0.25s ease;
        }

        /* Default (closed): chevron points down. When open, rotate to up. */
        #availMatHeading[aria-expanded="true"] .accordion-chevron-avail {
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

        tr.shortage-sent-row td {
            background-color: #f0fff4 !important;
        }

        tr.shortage-new-row td {
            background-color: #fff8f0 !important;
        }

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
                                    <strong>{{ $estimationAmount ? number_format((float) $estimationAmount, 2) : '—' }}</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="info-label">Items:</span>
                                    {{ count($available) }} available,
                                    <span id="itemsShortageCount">{{ count($shortageSent) + count($shortageDraft) }}</span> shortage,
                                    {{ count($alreadyIssued) }} issued
                                </div>
                            </div>
                        </div>

                        {{-- Pre-calculate issued grand total for budget check --}}
                        @php
                            $issuedGrandTotal = 0;
                            foreach ($alreadyIssued as $_item) {
                                $issuedGrandTotal +=
                                    (float) ($_item->required_quantity ?? 0) * (float) ($_item->rate ?? 0);
                            }
                            $estimationAmt = (float) ($estimationAmount ?? 0);
                            $issuedExceedsEstimation = $estimationAmt > 0 && $issuedGrandTotal >= $estimationAmt;
                        @endphp

                        {{-- ========================
                         TABLE 0 — Already Issued
                         ======================== --}}
                        @if (count($alreadyIssued) > 0)
                            <div class="bom-check-section-title" style="cursor:pointer;" data-toggle="collapse"
                                data-target="#alreadyIssuedCollapse" aria-expanded="false"
                                aria-controls="alreadyIssuedCollapse">
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
                                                <th style="width:110px;">Length</th>
                                                <th>Issued Qty</th>
                                                <th style="width:130px;">Total in mm</th>
                                                <th>Unit</th>
                                                <th>Rate</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $innerIssuedTotal = 0; @endphp
                                            @foreach ($alreadyIssued as $i => $item)
                                                @php
                                                    $rowTotal =
                                                        (float) ($item->required_quantity ?? 0) *
                                                        (float) ($item->rate ?? 0);
                                                    $innerIssuedTotal += $rowTotal;
                                                    $issuedAt = $item->issued_at ?? null;
                                                    $issuedAtStr = $issuedAt
                                                        ? \Carbon\Carbon::parse($issuedAt)->format('d M Y, h:i A')
                                                        : '—';
                                                @endphp
                                                <tr>
                                                    <td style="width:45px;">{{ $i + 1 }}</td>
                                                    <td style="white-space:nowrap; font-size:12px; color:#555;">
                                                        {{ $issuedAtStr }}</td>
                                                    <td>{{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}
                                                    </td>
                                                    <td>{!! $fmt($item->length ?? null) !!}</td>
                                                    <td>{{ number_format($item->required_quantity, 3) }}</td>
                                                    <td>{!! $fmt($item->total_in_mm ?? null) !!}</td>
                                                    <td>{{ optional($item->unitMaster)->name ?? ($item->unit_id ?? '—') }}
                                                    </td>
                                                    <td>{{ isset($item->rate) && $item->rate !== null ? number_format((float) $item->rate, 3) : '—' }}
                                                    </td>
                                                    <td><strong>{{ number_format($rowTotal, 2) }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background:#e9ecef; font-weight:700;">
                                                <td colspan="8" style="text-align:right; padding-right:12px;">Grand Total
                                                </td>
                                                <td>{{ number_format($issuedGrandTotal, 2) }}</td>
                                            </tr>
                                            @if ($issuedExceedsEstimation)
                                                <tr>
                                                    <td colspan="9" style="padding:0;">
                                                        <div
                                                            style="background:#dc3545;color:#fff;padding:6px 12px;font-size:13px;font-weight:600;">
                                                            <i class="fa fa-exclamation-triangle"></i>
                                                            Issued Total ({{ number_format($issuedGrandTotal, 2) }})
                                                            already exceeds Estimation Amount
                                                            ({{ number_format($estimationAmt, 2) }}).
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
                        @if ($isClosed ?? false)
                            <div
                                style="background:#fff3cd; color:#856404; border:1px solid #ffeeba; border-left:5px solid #e6a817; border-radius:4px; padding:12px 16px; margin-bottom:16px;">
                                <strong><i class="fa fa-eye"></i> Preview Only</strong> &mdash;
                                This product is <strong>CLOSED</strong>. Materials and shortage list are shown for
                                reference;
                                adding/editing items, issuing to production, and sending purchase requisitions are disabled.
                            </div>
                        @endif

                        {{-- Budget exceeded banner --}}
                        @if ($issuedExceedsEstimation)
                            <div class="alert alert-danger"
                                style="border-left:5px solid #a71d2a; border-radius:4px; margin-bottom:16px;">
                                <strong><i class="fa fa-ban"></i> Budget Limit Exceeded — Issue to Production is
                                    Blocked</strong><br>
                                Already Issued: <strong>{{ number_format($issuedGrandTotal, 2) }}</strong> &nbsp;|&nbsp;
                                Estimation Amount: <strong>{{ number_format($estimationAmt, 2) }}</strong> &nbsp;|&nbsp;
                                Exceeded by: <strong
                                    style="color:#a71d2a;">{{ number_format($issuedGrandTotal - $estimationAmt, 2) }}</strong>
                            </div>
                        @endif

                        {{-- ========================
                         TABLE 1 — Available Items (collapsible accordion, default CLOSED)
                         T-2026-037: wrapped in Bootstrap collapse; starts closed on every page load.
                         ======================== --}}

                        {{-- Accordion header — clickable, starts closed (aria-expanded="false") --}}
                        <div class="bom-check-section-title" id="availMatHeading"
                            style="cursor:pointer; display:flex; align-items:center; justify-content:space-between;"
                            data-toggle="collapse" data-target="#availMatBody" aria-expanded="false"
                            aria-controls="availMatBody">
                            <span>
                                <i class="fa fa-check-circle text-success"></i>
                                Available Materials
                                <span class="badge-available">Can be issued from stock</span>
                            </span>
                            <span style="font-size:13px; color:#28a745;">
                                <i class="fa fa-chevron-down accordion-chevron-avail"></i>
                            </span>
                        </div>

                        {{-- Accordion body — default CLOSED (no "show" class) --}}
                        <div class="collapse" id="availMatBody">

                            {{-- T-2026-036 note: BOM available items are pre-filled as editable rows in the blue grid below.
                             This table is read-only display only — summary reference. --}}

                            {{-- BOM Available items (read-only display — summary reference) --}}
                            @if (count($available) > 0)
                                <div class="table-responsive" style="margin-bottom: 10px; margin-top: 10px;">
                                    <table class="table table-bordered table-hover table-available">
                                        <thead>
                                            <tr>
                                                <th style="width:45px;">Sr.</th>
                                                <th>Product Description</th>
                                                <th style="width:110px;">Length</th>
                                                <th>Required Qty</th>
                                                <th style="width:130px;">Total in mm</th>
                                                {{-- T-2026-061: this column is the slice of the part's physical
                                                     balance ALLOCATED to this row, not the whole balance — several
                                                     BOM rows can name the same part and they share one balance. --}}
                                                <th>Allocated Stock</th>
                                                <th>Unit</th>
                                                <th>Mtr for 01 Nos Trolley</th>
                                                <th>Mtr/Nos for {{ $trolleyQty }} Trolley(s)</th>
                                                <th>Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($available as $i => $item)
                                                @php
                                                    $unitNameAvail = optional($item->unitMaster)->name ?? null;
                                                    $mtrNAvail = $computeMtrN(
                                                        $item->mtr_for_01_nos_trolley ?? null,
                                                        $item->required_quantity ?? null,
                                                        $unitNameAvail,
                                                        $trolleyQty,
                                                    );
                                                @endphp
                                                <tr>
                                                    <td style="width:45px;">{{ $i + 1 }}</td>
                                                    <td>{{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}
                                                        @if ($item->is_partial_issue ?? false)
                                                            {{-- Stock covers only part of this row; the balance is
                                                                 listed under Shortage Materials for purchase. Both
                                                                 figures are quoted on the ISSUE basis (the trolley-
                                                                 scaled quantity actually taken off the shelf), which
                                                                 is what "Allocated Stock" opposite counts — the
                                                                 "Required Qty" column is the raw per-trolley BOM
                                                                 figure and is not comparable to either. --}}
                                                            <br>
                                                            <small style="color:#b8860b;font-size:11px;">
                                                                <i class="fa fa-adjust"></i>
                                                                Partly covered — {{ number_format($item->issue_quantity, 3) }}
                                                                issuable now of
                                                                {{ number_format($item->requested_quantity, 3) }} needed;
                                                                {{ number_format($item->pending_quantity, 3) }} moved to
                                                                Shortage
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>{!! $fmt($item->length ?? null) !!}</td>
                                                    <td>{{ number_format($item->required_quantity, 3) }}</td>
                                                    <td>{!! $fmt($item->total_in_mm ?? null) !!}</td>
                                                    <td>{{ number_format($item->available_stock, 3) }}
                                                        @if (isset($item->total_part_stock))
                                                            <br><small class="text-muted"
                                                                style="font-size:11px;">of
                                                                {{ number_format($item->total_part_stock, 3) }} in
                                                                store</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $unitNameAvail ?? ($item->unit_id ?? '—') }}</td>
                                                    <td>{!! $fmt($item->mtr_for_01_nos_trolley ?? null) !!}</td>
                                                    <td>{!! $fmt($mtrNAvail) !!}</td>
                                                    <td>{{ isset($item->rate) && $item->rate !== null ? number_format((float) $item->rate, 3) : '—' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info" style="margin-bottom: 10px; margin-top: 10px;">
                                    No BOM items are fully available in stock.
                                </div>
                            @endif

                        </div>{{-- /#availMatBody (accordion body closes here — editable grid and form are OUTSIDE) --}}

                        <form action="{{ route('issue-available-materials') }}" method="POST" id="issueAvailableForm">
                            @csrf
                            <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">

                            {{-- Additional Items to Issue (pre-filled with BOM available + production requests + manual add) --}}
                            {{-- T-2026-036: $available BOM items now appear as editable rows (indices 0..N-1).
                             $availableFromProduction rows follow (indices N..N+M-1). JS-added rows start at N+M. --}}
                            @php $totalPrefilled = count($available) + count($availableFromProduction); @endphp
                            <div class="bom-check-section-title" style="margin-top: 10px;">
                                <i class="fa fa-plus-circle text-primary"></i>
                                Additional Items to Issue
                                @if ($totalPrefilled > 0)
                                    <small class="text-muted">({{ $totalPrefilled }} item(s) pre-filled — adjust quantities
                                        or remove items as needed)</small>
                                @else
                                    <small class="text-muted">(optional — add items not in BOM)</small>
                                @endif
                            </div>
                            <div class="table-responsive" style="margin-bottom: 10px;">
                                @php
                                    // Grand Total = sum of (Rate × unit-aware issue quantity) across BOM-prefilled rows.
                                    // Unit-aware issue quantity = $mtrN for the row when available, else falls back
                                    // to required_quantity (via $computeIssueQty) — mirrors the quantity now
                                    // pre-filled into each row's editable Quantity input below (T-2026-058).
                                    // Production-request and manual rows have no BOM Mtr context and contribute 0.
                                    $extraGrandTotal = 0;
                                    foreach ($available as $gtItem) {
                                        $gtUnitName = optional($gtItem->unitMaster)->name ?? null;
                                        $gtMtrN = $computeMtrN(
                                            $gtItem->mtr_for_01_nos_trolley ?? null,
                                            $gtItem->required_quantity ?? null,
                                            $gtUnitName,
                                            $trolleyQty,
                                        );
                                        $gtIssueQty = $resolveIssueQty($gtItem, $gtMtrN, $trolleyQty);
                                        $extraGrandTotal += $gtIssueQty * ((float) ($gtItem->rate ?? 0));
                                    }
                                @endphp
                                <table class="table table-bordered" id="extraItemsTable">
                                    <thead style="background:#007bff; color:#fff;">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Part Item</th>
                                            <th style="width:110px;">Length</th>
                                            <th>Quantity</th>
                                            <th style="width:130px;">Total in mm</th>
                                            <th>Unit</th>
                                            <th>Mtr for 01 Nos Trolley</th>
                                            <th>Mtr/Nos for {{ $trolleyQty }} Trolley(s)</th>
                                            <th>Rate</th>
                                            <th>Total</th>
                                            <th>
                                                @if (!($isClosed ?? false))
                                                    <button type="button" class="btn btn-sm btn-light" id="addExtraRow">
                                                        <i class="fa fa-plus"></i> Add
                                                    </button>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="extraItemsBody">
                                        {{-- Pre-filled rows for BOM available items (T-2026-036) --}}
                                        @foreach ($available as $ai => $item)
                                            @php
                                                $unitNameAi = optional($item->unitMaster)->name ?? null;
                                                $mtrNAi = $computeMtrN(
                                                    $item->mtr_for_01_nos_trolley ?? null,
                                                    $item->required_quantity ?? null,
                                                    $unitNameAi,
                                                    $trolleyQty,
                                                );
                                                // T-2026-058: unit-aware issue quantity — meters for raw/length
                                                // material (mtr_for_01_nos_trolley*trolleyQty), nos for piece
                                                // units (required_quantity*trolleyQty), falling back to
                                                // required_quantity when $mtrNAi is null (no BOM mtr data).
                                                // T-2026-061: capped at this row's allocated slice of the shared
                                                // per-part stock balance when the controller published one.
                                                $issueQtyAi = $resolveIssueQty($item, $mtrNAi, $trolleyQty);
                                            @endphp
                                            <tr id="extra_row_{{ $ai }}" style="background:#f0fff4;">
                                                <td style="vertical-align:middle;">{{ $ai + 1 }}</td>
                                                <td style="vertical-align:middle; min-width:300px;">
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}"
                                                        readonly style="background:#f8f9fa;">
                                                    <input type="hidden"
                                                        name="extra_items[{{ $ai }}][part_item_id]"
                                                        value="{{ $item->part_item_id }}">
                                                    <input type="hidden"
                                                        name="extra_items[{{ $ai }}][product_description]"
                                                        value="{{ $item->product_description ?? (optional($item->partItem)->description ?? '') }}">
                                                    @if ($item->is_partial_issue ?? false)
                                                        <small style="color:#b8860b;font-size:11px;">&#9681; BOM
                                                            Partly Available</small>
                                                    @else
                                                        <small style="color:#28a745;font-size:11px;">&#10004; BOM
                                                            Available</small>
                                                    @endif
                                                </td>
                                                <td style="vertical-align:middle; white-space:nowrap;">
                                                    {!! $fmt($item->length ?? null) !!}</td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number"
                                                        name="extra_items[{{ $ai }}][quantity]"
                                                        class="form-control" step="0.001" min="0.001"
                                                        value="{{ number_format($issueQtyAi, 3, '.', '') }}" style="width:110px;">
                                                    {{-- T-2026-061: "Allocated" is this row's reserved slice of the
                                                         shared per-part balance; "in store" is that whole balance.
                                                         Raising the quantity above the allocated figure re-takes
                                                         stock another row is already counting on. --}}
                                                    <small style="color:green;font-size:11px;">&#10004; Allocated:
                                                        {{ number_format($item->available_stock, 3) }}</small>
                                                    @if (isset($item->total_part_stock))
                                                        <br><small class="text-muted" style="font-size:11px;">of
                                                            {{ number_format($item->total_part_stock, 3) }} in
                                                            store</small>
                                                    @endif
                                                </td>
                                                <td style="vertical-align:middle; white-space:nowrap;">
                                                    {!! $fmt($item->total_in_mm ?? null) !!}</td>
                                                <td style="vertical-align:middle;">
                                                    <select name="extra_items[{{ $ai }}][unit_id]"
                                                        class="form-control" style="min-width:100px;">
                                                        <option value="">Select Unit</option>
                                                        @foreach ($unitMasters as $u)
                                                            <option value="{{ $u->id }}"
                                                                {{ $u->id == $item->unit_id ? 'selected' : '' }}>
                                                                {{ $u->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td style="vertical-align:middle; white-space:nowrap;">
                                                    {!! $fmt($item->mtr_for_01_nos_trolley ?? null) !!}</td>
                                                <td style="vertical-align:middle; white-space:nowrap;">
                                                    {!! $fmt($mtrNAi) !!}</td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number" name="extra_items[{{ $ai }}][rate]"
                                                        class="form-control extra-rate-input" step="0.001"
                                                        min="0" value="{{ $item->rate ?? 0 }}"
                                                        style="width:100px;" readonly>
                                                </td>
                                                <td style="vertical-align:middle; white-space:nowrap; font-weight:bold;"
                                                    class="extra-row-total">
                                                    @php $totalAi = $issueQtyAi * ((float) ($item->rate ?? 0)); @endphp
                                                    &#8377;{{ number_format($totalAi, 2) }}
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeExtraRow({{ $ai }})"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- Pre-filled rows for production-requested available items --}}
                                        @foreach ($availableFromProduction as $pi => $pitem)
                                            @php $pIdx = count($available) + $pi; @endphp
                                            <tr id="extra_row_{{ $pIdx }}" style="background:#fff8f0;">
                                                <td style="vertical-align:middle;">{{ $pIdx + 1 }}</td>
                                                <td style="vertical-align:middle; min-width:300px;">
                                                    <input type="text" class="form-control"
                                                        value="{{ $pitem->product_description }}" readonly
                                                        style="background:#f8f9fa;">
                                                    <input type="hidden"
                                                        name="extra_items[{{ $pIdx }}][part_item_id]"
                                                        value="{{ $pitem->part_item_id }}">
                                                    <input type="hidden"
                                                        name="extra_items[{{ $pIdx }}][product_description]"
                                                        value="{{ $pitem->product_description }}">
                                                    {{-- Source production_details row that raised this request.
                                                         Posted back so issueAvailableMaterials() can close it out
                                                         (quantity_minus_status pending -> done) instead of leaving
                                                         an orphan pending row that re-appears on every reload. --}}
                                                    <input type="hidden"
                                                        name="extra_items[{{ $pIdx }}][pd_id]"
                                                        value="{{ $pitem->pd_id }}">
                                                    <small style="color:#fd7e14;font-size:11px;"><i
                                                            class="fa fa-industry"></i> Production Request</small>
                                                    @if ($pitem->is_partial_issue ?? false)
                                                        {{-- Stock covers only part of the request: this row issues what
                                                             is on the shelf, the balance is listed under Shortage. --}}
                                                        <br>
                                                        <small style="color:#c0392b;font-size:11px;">
                                                            <i class="fa fa-exclamation-triangle"></i>
                                                            Partial &mdash; requested
                                                            {{ number_format($pitem->requested_quantity, 3) }},
                                                            {{ number_format($pitem->pending_quantity, 3) }} to purchase
                                                        </small>
                                                    @endif
                                                </td>
                                                {{-- Production-request rows have no BOM context — show em-dash --}}
                                                <td style="vertical-align:middle; white-space:nowrap;">&mdash;</td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number"
                                                        name="extra_items[{{ $pIdx }}][quantity]"
                                                        class="form-control" step="0.001" min="0.001"
                                                        max="{{ $pitem->available_stock }}"
                                                        value="{{ $pitem->required_quantity }}" style="width:110px;">
                                                    <small style="color:green;font-size:11px;">&#10004; Stock:
                                                        {{ number_format($pitem->available_stock, 3) }}</small>
                                                </td>
                                                <td style="vertical-align:middle; white-space:nowrap;">&mdash;</td>
                                                <td style="vertical-align:middle;">
                                                    <select name="extra_items[{{ $pIdx }}][unit_id]"
                                                        class="form-control" style="min-width:100px;">
                                                        <option value="">Select Unit</option>
                                                        @foreach ($unitMasters as $u)
                                                            <option value="{{ $u->id }}"
                                                                {{ $u->id == $pitem->unit_id ? 'selected' : '' }}>
                                                                {{ $u->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                {{-- Production-request rows have no BOM context — show em-dash --}}
                                                <td style="vertical-align:middle; white-space:nowrap;">&mdash;</td>
                                                <td style="vertical-align:middle; white-space:nowrap;">&mdash;</td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number" name="extra_items[{{ $pIdx }}][rate]"
                                                        class="form-control extra-rate-input" step="0.001"
                                                        min="0" value="{{ $pitem->rate ?? 0 }}"
                                                        style="width:100px;" readonly>
                                                </td>
                                                <td style="vertical-align:middle; white-space:nowrap; font-weight:bold;"
                                                    class="extra-row-total">
                                                    {{-- Production-request rows have no BOM Mtr/Nos context --}}
                                                    &mdash;
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeExtraRow({{ $pIdx }})"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- JS-added rows appended here --}}
                                    </tbody>
                                    <tfoot>
                                        <tr style="background:#e8f5e9; font-weight:bold;">
                                            <td colspan="9" style="text-align:right; padding-right:12px;">Grand Total:
                                            </td>
                                            <td id="extraGrandTotalCell" style="white-space:nowrap; font-size:15px; color:#155724;">
                                                &#8377;{{ number_format($extraGrandTotal, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div style="margin-bottom: 30px;">
                                <div id="issueValidationMsg" class="issue-validation-msg"></div>
                                @if ($isClosed ?? false)
                                    {{-- Production CLOSED: preview only — no Issue/Save action --}}
                                @elseif($issuedExceedsEstimation)
                                    <button type="button" class="btn btn-danger" disabled
                                        style="cursor:not-allowed; opacity:0.85;">
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
                         TABLE 2 — Shortage Items (unified: BOM rows + draft/manual rows in one table)
                         ======================== --}}
                        @php
                            // Count BOM-derived shortage rows and draft/manual rows for button label logic
                            $bomShortageCount  = count($shortageSent);
                            $draftCount        = count($shortageDraft);
                            $hasBomShortage    = $bomShortageCount > 0;
                            $hasDraftOrManual  = $draftCount > 0; // JS-added rows also count via JS
                        @endphp

                        <div class="bom-check-section-title"
                            style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                            <span>
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                Shortage Materials
                                @if ($requisitionSent)
                                    <span
                                        style="background:#28a745;color:#fff;padding:3px 10px;border-radius:4px;font-size:12px;">
                                        <i class="fa fa-check"></i> Requisition Sent to Purchase
                                    </span>
                                @elseif($hasBomShortage || $hasDraftOrManual)
                                    <span class="badge-shortage">Need to purchase</span>
                                @endif
                                {{-- Draft pending badge --}}
                                <span id="pendingDraftCountBadge"
                                    style="background:#fd7e14;color:#fff;border-radius:4px;padding:2px 8px;font-size:12px;{{ $draftCount > 0 ? '' : 'display:none;' }}">
                                    <span id="pendingDraftCount">{{ $draftCount }}</span> draft
                                </span>
                            </span>
                            @if (!($isClosed ?? false))
                                <button type="button" class="btn btn-sm btn-danger" id="addShortageManualRow"
                                    style="white-space:nowrap;">
                                    <i class="fa fa-plus"></i> Add More
                                </button>
                            @endif
                        </div>

                        {{-- Unified Shortage Materials table (BOM rows + draft rows + JS-added rows) --}}
                        @if ($hasBomShortage || $hasDraftOrManual || !($isClosed ?? false))
                            <div class="table-responsive" style="margin-bottom: 10px;">
                                <table class="table table-bordered table-hover table-shortage" id="shortageUnifiedTable">
                                    <thead>
                                        <tr>
                                            <th style="width:45px;">Sr.</th>
                                            <th style="white-space:nowrap;">Date &amp; Time</th>
                                            <th class="shortage-desc-col">Product Description</th>
                                            <th style="width:110px;">Length</th>
                                            <th>Required Qty</th>
                                            <th style="width:130px;">Total in mm</th>
                                            <th>Available Stock</th>
                                            <th class="qty-highlight" style="color:#fff;">Shortage Qty</th>
                                            <th>Unit</th>
                                            <th>Mtr for 01 Nos Trolley</th>
                                            <th>Mtr/Nos for {{ $trolleyQty }} Trolley(s)</th>
                                            <th>Rate</th>
                                            <th style="width:40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="shortageTableBody">
                                        {{-- BOM-derived shortage rows (read-only display) --}}
                                        @foreach ($shortageSent as $i => $item)
                                            @php
                                                $isSent =
                                                    isset($item->is_sent_to_purchase) &&
                                                    (int) $item->is_sent_to_purchase === 1;
                                                $rowClass  = $isSent ? 'shortage-sent-row' : 'shortage-row';
                                                $reqItemId = $item->requisition_item_id ?? null;
                                                $unitNameSh = optional($item->unitMaster)->name ?? null;
                                                $mtrNSh = $computeMtrN(
                                                    $item->mtr_for_01_nos_trolley ?? null,
                                                    $item->required_quantity ?? null,
                                                    $unitNameSh,
                                                    $trolleyQty,
                                                );
                                            @endphp
                                            <tr class="{{ $rowClass }}"
                                                id="shortage-row-{{ $reqItemId ?? 'bom-' . $i }}">
                                                <td style="width:45px;" class="sr-cell">{{ $i + 1 }}</td>
                                                <td style="white-space:nowrap; font-size:12px; color:#555;">
                                                    {{ $item->created_at ?? null ? \Carbon\Carbon::parse($item->created_at)->format('d M Y, h:i A') : '—' }}
                                                </td>
                                                <td class="shortage-desc-col"><span>
                                                        {{ $item->product_description ?? (optional($item->partItem)->description ?? '—') }}
                                                        @if ($isSent)
                                                            <span class="badge-sent-purchase"><i class="fa fa-check"></i>
                                                                Sent to Purchase</span>
                                                        @elseif($requisitionSent)
                                                            <span class="badge-not-sent"><i
                                                                    class="fa fa-exclamation"></i>
                                                                Not in Requisition</span>
                                                        @endif
                                                        @if ($item->is_partial_issue ?? false)
                                                            {{-- Part of this request is covered by stock and is
                                                                 pre-filled above under "Additional Items to Issue";
                                                                 only the balance below needs purchasing. --}}
                                                            <br>
                                                            <small style="color:#1e7e34;font-size:11px;">
                                                                <i class="fa fa-check-circle"></i>
                                                                {{ number_format($item->issuable_quantity, 3) }}
                                                                issuable from stock now
                                                            </small>
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>{!! $fmt($item->length ?? null) !!}</td>
                                                <td>{{ number_format($item->required_quantity, 3) }}</td>
                                                <td>{!! $fmt($item->total_in_mm ?? null) !!}</td>
                                                <td>{{ number_format($item->available_stock, 3) }}</td>
                                                <td><strong
                                                        class="qty-highlight">{{ number_format($item->shortage_quantity, 3) }}</strong>
                                                </td>
                                                <td>{{ $unitNameSh ?? ($item->unit_id ?? '—') }}</td>
                                                <td style="white-space:nowrap;">{!! $fmt($item->mtr_for_01_nos_trolley ?? null) !!}</td>
                                                <td style="white-space:nowrap;">{!! $fmt($mtrNSh) !!}</td>
                                                <td>{{ isset($item->rate) && $item->rate !== null ? number_format((float) $item->rate, 3) : '—' }}
                                                </td>
                                                <td></td>{{-- no action for BOM rows --}}
                                            </tr>
                                        @endforeach

                                        {{-- Pre-rendered draft/manual rows (is_sent_to_purchase=0, editable) --}}
                                        @foreach ($shortageDraft as $di => $ditem)
                                            <tr id="draft_row_{{ $ditem->requisition_item_id }}"
                                                class="shortage-row-manual"
                                                data-req-item-id="{{ $ditem->requisition_item_id }}"
                                                style="background:#fff8f0;">
                                                <td style="vertical-align:middle;" class="sr-cell">{{ $bomShortageCount + $di + 1 }}</td>
                                                <td style="vertical-align:middle; font-size:12px; color:#999;">
                                                    {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
                                                </td>
                                                <td class="shortage-desc-col" style="vertical-align:middle; min-width:220px;">
                                                    <input type="text" class="form-control"
                                                        value="{{ $ditem->product_description ?? (optional($ditem->partItem)->description ?? '—') }}"
                                                        readonly style="background:#f8f9fa;">
                                                    <input type="hidden" class="sm-part-id"
                                                        value="{{ $ditem->part_item_id }}">
                                                    <input type="hidden" class="sm-desc"
                                                        value="{{ $ditem->product_description ?? (optional($ditem->partItem)->description ?? '') }}">
                                                    @if ($ditem->is_partial_issue ?? false)
                                                        {{-- Part of this request is covered by stock and is pre-filled
                                                             above under "Additional Items to Issue"; only the balance
                                                             in this row needs purchasing. --}}
                                                        <small style="color:#1e7e34;font-size:11px;">
                                                            <i class="fa fa-check-circle"></i>
                                                            {{ number_format($ditem->issuable_quantity, 3) }}
                                                            issuable from stock now
                                                        </small>
                                                    @endif
                                                </td>
                                                {{-- requisition_items has no length/total_in_mm columns — no BOM context, show em-dash --}}
                                                <td style="vertical-align:middle;">&mdash;</td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number" class="form-control sm-qty-input"
                                                        step="0.001" min="0.001" style="width:110px;"
                                                        value="{{ $ditem->required_quantity }}">
                                                </td>
                                                <td style="vertical-align:middle;">&mdash;</td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number" class="form-control sm-avail-stock"
                                                        step="0.001" readonly
                                                        style="width:110px; background:#f8f9fa;"
                                                        value="{{ $ditem->available_stock }}">
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number" class="form-control sm-shortage-qty"
                                                        step="0.001" readonly
                                                        style="width:110px; background:#f8f9fa;"
                                                        value="{{ $ditem->shortage_quantity }}">
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <select class="form-control sm-unit-select"
                                                        style="min-width:100px;">
                                                        <option value="">Select Unit</option>
                                                        @foreach ($unitMasters as $u)
                                                            <option value="{{ $u->id }}"
                                                                {{ $u->id == $ditem->unit_id ? 'selected' : '' }}>
                                                                {{ $u->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                {{-- Editable trolley inputs for manual/draft rows --}}
                                                <td style="vertical-align:middle;">
                                                    <input type="number" class="form-control short-mtr1"
                                                        step="any" min="0"
                                                        data-trolley-qty="{{ $trolleyQty }}"
                                                        placeholder="0.000"
                                                        style="min-width:90px;"
                                                        value="{{ $ditem->mtr_for_01_nos_trolley ?? '' }}">
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    @php
                                                        // Draft rows come from requisition_items and are normalised into
                                                        // stdClass in the controller — guard with ?? so a row missing the
                                                        // property never fatals the whole page.
                                                        $dMtr1 = $ditem->mtr_for_01_nos_trolley ?? null;
                                                    @endphp
                                                    <input type="text" class="form-control short-mtrN"
                                                        readonly tabindex="-1"
                                                        style="background:#f3f4f6;cursor:not-allowed;min-width:90px;"
                                                        value="{{ $dMtr1 !== null && $dMtr1 !== '' ? number_format((float) $dMtr1 * (int) ($trolleyQty ?: 1), 3) : '' }}">
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <input type="number" class="form-control sm-rate-input"
                                                        step="0.001" min="0" style="width:100px;"
                                                        value="{{ $ditem->rate ?? 0 }}">
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm delete-draft-btn"
                                                        data-id="{{ $ditem->requisition_item_id }}"
                                                        title="Delete this draft item">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- JS-added new manual rows appended here by addShortageManualRow() --}}
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if ($bomShortageCount === 0 && !($isClosed ?? false) && $draftCount === 0)
                            {{-- No BOM shortages yet and no drafts: show "all available" notice --}}
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i>
                                All BOM items are available in stock. No purchase requisition is needed. You can still add manual shortage items using the "+ Add More" button above.
                            </div>
                        @endif

                        {{-- Validation message for manual shortage rows --}}
                        <div id="shortageManualValidationMsg" class="issue-validation-msg"></div>

                        {{-- ========================
                         Single unified submit button + Cancel/Back
                         ======================== --}}
                        @if (!($isClosed ?? false))
                            <div class="login-btn-inner" style="margin-top:14px;">
                                <div class="login-horizental cancel-wp" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                                    <a href="{{ route('list-accepted-design-from-prod') }}"
                                        class="btn btn-white">Cancel</a>

                                    @if ($requisitionSent)
                                        {{-- Already sent: show disabled "Requisition Sent" pill + unified "Send [N] + drafts" button --}}
                                        <button type="button" class="btn btn-success" disabled
                                            style="cursor:not-allowed; opacity:0.85;">
                                            <i class="fa fa-check-circle"></i>
                                            Requisition Already Sent to Purchase
                                        </button>
                                        {{-- T-2026-059: requisition drift/resync — an already-sent requisition is
                                             otherwise a frozen snapshot; this reconciles it against the CURRENT
                                             BOM + stock without ever touching rows a Purchase Order already covers. --}}
                                        <button type="button" class="btn btn-outline-secondary" id="resyncRequisitionBtn"
                                            data-bd="{{ $productDetails->id }}"
                                            title="Refresh this requisition against the current BOM and stock (e.g. after a BOM re-upload). Rows already covered by a Purchase Order are never modified — only new/grown shortages are added.">
                                            <i class="fa fa-refresh"></i> Resync with Current BOM/Stock
                                        </button>
                                    @endif

                                    {{-- Unified send button — JS controls label + visibility --}}
                                    <button type="button" class="btn btn-danger" id="unifiedSendBtn"
                                        style="{{ (!$hasBomShortage && $draftCount === 0) ? 'display:none;' : '' }}"
                                        data-bom-count="{{ $bomShortageCount }}"
                                        data-draft-count="{{ $draftCount }}">
                                        <i class="fa fa-paper-plane"></i>
                                        <span id="unifiedSendLabel">
                                            @if ($hasBomShortage && $draftCount > 0)
                                                Send Shortage List + {{ $draftCount }} New Item(s) to Purchase
                                            @elseif($hasBomShortage)
                                                Send Shortage List to Purchase
                                            @else
                                                Send {{ $draftCount }} New Item(s) to Purchase
                                            @endif
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div style="margin-top:14px;">
                                <a href="{{ route('list-accepted-design-from-prod') }}" class="btn btn-white">Back</a>
                            </div>
                        @endif

                        {{-- ========================
                         Hidden forms used by unified JS submit logic
                         ======================== --}}
                        @php
                            $design_id_for_form = ($shortageSent[0]->design_id ?? null) ?? null;
                        @endphp

                        @if (!$requisitionSent)
                            {{-- First-time submission: BOM items[] + manual_shortage[] go to store-shortage-requisition --}}
                            <form action="{{ route('store-shortage-requisition') }}" method="POST"
                                id="shortageReqForm" style="display:none;">
                                @csrf
                                <input type="hidden" name="business_details_id" value="{{ $productDetails->id }}">
                                <input type="hidden" name="business_id" value="{{ $productDetails->business_id }}">
                                <input type="hidden" name="design_id" value="{{ $design_id_for_form }}">

                                @foreach ($shortageSent as $i => $item)
                                    <input type="hidden" name="items[{{ $i }}][part_item_id]"
                                        value="{{ $item->part_item_id }}">
                                    <input type="hidden" name="items[{{ $i }}][product_description]"
                                        value="{{ $item->product_description ?? (optional($item->partItem)->description ?? '') }}">
                                    <input type="hidden" name="items[{{ $i }}][required_quantity]"
                                        value="{{ $item->required_quantity }}">
                                    <input type="hidden" name="items[{{ $i }}][available_quantity]"
                                        value="{{ $item->available_stock }}">
                                    <input type="hidden" name="items[{{ $i }}][shortage_quantity]"
                                        value="{{ $item->shortage_quantity }}">
                                    <input type="hidden" name="items[{{ $i }}][unit_id]"
                                        value="{{ $item->unit_id }}">
                                    <input type="hidden" name="items[{{ $i }}][rate]"
                                        value="{{ $item->rate }}">
                                    <input type="hidden" name="items[{{ $i }}][mtr_for_01_nos_trolley]"
                                        value="{{ $item->mtr_for_01_nos_trolley ?? '' }}">
                                    {{-- T-2026-059: BOM length — lets this exact BOM row be re-matched to its
                                         own requisition_items row (by part_item_id + length) on future page
                                         loads, instead of colliding with other BOM rows for the same part. --}}
                                    <input type="hidden" name="items[{{ $i }}][length]"
                                        value="{{ $item->length ?? '' }}">
                                @endforeach
                                {{-- manual_shortage[] hidden inputs injected by JS before submit --}}
                            </form>
                        @else
                            {{-- Requisition already sent: additional items go to store-additional-shortage-requisition --}}
                            @if (!($isClosed ?? false))
                                <form action="{{ route('store-additional-shortage-requisition') }}" method="POST"
                                    id="manualAddMoreForm" style="display:none;">
                                    @csrf
                                    <input type="hidden" name="business_details_id"
                                        value="{{ $productDetails->id }}">
                                    {{-- manual_shortage[] hidden inputs injected by JS before submit --}}
                                </form>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <style>
            .ei-dropdown {
                position: relative;
            }

            .ei-dropdown-menu {
                display: none;
                position: fixed;
                z-index: 9999;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, .15);
                width: 420px;
                max-height: 300px;
                overflow: hidden;
            }

            .ei-dropdown-menu.ei-open {
                display: flex;
                flex-direction: column;
            }

            .ei-search-box {
                padding: 6px 8px;
                border-bottom: 1px solid #eee;
                flex-shrink: 0;
            }

            .ei-search-box input {
                width: 100%;
                box-sizing: border-box;
            }

            .ei-options-list {
                overflow-y: auto;
                flex: 1;
            }

            .ei-option {
                padding: 6px 10px;
                cursor: pointer;
                font-size: 13px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .ei-option:hover {
                background: #f0f4ff;
            }

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
            (function() {
                // Start after all Blade-pre-rendered rows (BOM available + production requests). T-2026-036.
                var extraRowCount = {{ count($available) + count($availableFromProduction) }};

                // Build part items data array for the custom dropdown
                var partData = [];
                @foreach ($partItems as $p)
                    partData.push({
                        id: {{ $p->id }},
                        label: {!! json_encode($p->description) !!},
                        rate: '{{ $p->basic_rate }}'
                    });
                @endforeach

                var unitOptions = '<option value="">Select Unit</option>';
                @foreach ($unitMasters as $u)
                    unitOptions += '<option value="{{ $u->id }}">{{ $u->name }}</option>';
                @endforeach

                var checkStockUrl = '{{ route('check-stock-quantity') }}';

                // ── Custom dropdown logic ──────────────────────────────────────────
                var activeMenu = null;

                function closeActiveMenu() {
                    if (activeMenu) {
                        activeMenu.classList.remove('ei-open');
                        activeMenu = null;
                    }
                }

                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.ei-dropdown') && !e.target.closest('.ei-dropdown-menu')) {
                        closeActiveMenu();
                    }
                });

                function openMenu(trigger, menu) {
                    closeActiveMenu();
                    var rect = trigger.getBoundingClientRect();
                    menu.style.top = rect.bottom + 'px';
                    menu.style.left = rect.left + 'px';
                    menu.classList.add('ei-open');
                    activeMenu = menu;
                    var searchInput = menu.querySelector('.ei-search-input');
                    searchInput.value = '';
                    filterOptions(menu, '');
                    searchInput.focus();
                }

                function filterOptions(menu, term) {
                    term = term.toLowerCase();
                    menu.querySelectorAll('.ei-option').forEach(function(opt) {
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
                    partData.forEach(function(p) {
                        var opt = document.createElement('div');
                        opt.className = 'ei-option';
                        opt.textContent = p.label;
                        opt.setAttribute('data-id', p.id);
                        opt.setAttribute('data-rate', p.rate);
                        list.appendChild(opt);
                    });

                    menu.querySelector('.ei-search-input').addEventListener('input', function() {
                        filterOptions(menu, this.value);
                    });

                    menu.addEventListener('click', function(e) {
                        var opt = e.target.closest('.ei-option');
                        if (!opt) return;
                        var rowEl = document.getElementById('extra_row_' + rowIndex);
                        rowEl.querySelector('.ei-trigger').textContent = opt.textContent;
                        rowEl.querySelector('.extra-part-id').value = opt.getAttribute('data-id');
                        rowEl.querySelector('.extra-rate-hidden').value = opt.getAttribute('data-rate');
                        rowEl.querySelector('.extra-rate-input').value = opt.getAttribute('data-rate');
                        rowEl.querySelector('.extra-desc').value = opt.textContent;
                        closeActiveMenu();
                        checkStock(rowIndex);
                        // Manually-added rows: refresh merged-cell + Total + Grand Total
                        // since selecting a part item populates the Rate.
                        if (rowEl.classList.contains('extra-row-manual')) {
                            updateManualRowComputed(rowEl);
                        }
                    });

                    document.body.appendChild(menu);
                    return menu;
                }

                // ── Stock check ───────────────────────────────────────────────────
                function checkStock(rowIndex) {
                    var rowEl = document.getElementById('extra_row_' + rowIndex);
                    if (!rowEl) return;
                    var partItemId = rowEl.querySelector('.extra-part-id').value;
                    var quantity = rowEl.querySelector('.extra-qty-input').value;
                    var msgEl = rowEl.querySelector('.extra-stock-msg');

                    if (!partItemId || !quantity || parseFloat(quantity) <= 0) {
                        msgEl.textContent = '';
                        return;
                    }

                    fetch(checkStockUrl + '?part_item_id=' + encodeURIComponent(partItemId) +
                            '&quantity=' + encodeURIComponent(quantity) +
                            '&material_send_production=0&quantity_minus_status=pending')
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            if (data.status === 'success') {
                                msgEl.textContent = '✔ Available: ' + data.available_quantity;
                                msgEl.style.color = 'green';
                            } else {
                                var avail = data.available_quantity !== undefined ? data.available_quantity : 0;
                                msgEl.textContent = '✘ Not available (Stock: ' + avail + ')';
                                msgEl.style.color = 'red';
                            }
                        })
                        .catch(function() {
                            msgEl.textContent = '';
                        });
                }

                // ── Add row ───────────────────────────────────────────────────────
                // Manually-added rows have no BOM trolley context. The two trolley columns
                // ("Mtr for 01 Nos Trolley" + "Mtr/Nos for N Trolley(s)") are merged into one
                // cell that shows the Quantity, and Total = Quantity × Rate.
                var serverExtraGrandTotal = {{ $extraGrandTotal ?? 0 }};

                function fmtInrAmount(n) {
                    return '₹' + Number(n).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                function recomputeExtraGrandTotal() {
                    var jsTotal = 0;
                    document.querySelectorAll('#extraItemsBody tr.extra-row-manual').forEach(function(r) {
                        var mtrInput = r.querySelector('.extra-mtr-input');
                        var mtrVal   = mtrInput ? (parseFloat(mtrInput.value) || 0) : 0;
                        var rate     = parseFloat(r.querySelector('.extra-rate-input').value) || 0;
                        jsTotal += mtrVal * rate;
                    });
                    var cell = document.getElementById('extraGrandTotalCell');
                    if (cell) cell.innerHTML = fmtInrAmount(serverExtraGrandTotal + jsTotal);
                }

                function updateManualRowComputed(rowEl) {
                    var mtrInput = rowEl.querySelector('.extra-mtr-input');
                    var mtrVal   = mtrInput ? (parseFloat(mtrInput.value) || 0) : 0;
                    var rate     = parseFloat(rowEl.querySelector('.extra-rate-input').value) || 0;
                    var totalCell = rowEl.querySelector('.extra-row-total');
                    if (totalCell) {
                        totalCell.innerHTML = (mtrVal > 0 && rate > 0) ? fmtInrAmount(mtrVal * rate) : '—';
                    }
                    recomputeExtraGrandTotal();
                }

                function addExtraRow() {
                    var i = extraRowCount++;
                    var row =
                        '<tr id="extra_row_' + i + '" class="extra-row-manual">' +
                        '<td style="vertical-align:middle;">' + (i + 1) + '</td>' +
                        '<td style="vertical-align:middle; min-width:300px;">' +
                        '<div class="ei-dropdown">' +
                        '<button type="button" class="btn btn-default form-control ei-trigger" style="text-align:left;">-- Select Part Item --</button>' +
                        '</div>' +
                        '<input type="hidden" name="extra_items[' + i + '][part_item_id]" class="extra-part-id" value="">' +
                        '<input type="hidden" name="extra_items[' + i +
                        '][product_description]" class="extra-desc" value="">' +
                        '<input type="hidden" name="extra_items[' + i + '][rate]" class="extra-rate-hidden" value="0">' +
                        '</td>' +
                        // Manually-added rows have no BOM context — Length/Total in mm show em-dash (T-2026-058)
                        '<td style="vertical-align:middle; white-space:nowrap;">&mdash;</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="number" name="extra_items[' + i +
                        '][quantity]" class="form-control extra-qty-input" step="0.001" min="0.001" required style="width:110px;">' +
                        '<small class="extra-stock-msg d-block mt-1" style="font-size:11px;"></small>' +
                        '</td>' +
                        '<td style="vertical-align:middle; white-space:nowrap;">&mdash;</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<select name="extra_items[' + i +
                        '][unit_id]" class="form-control" required style="min-width:100px;">' + unitOptions + '</select>' +
                        '</td>' +
                        '<td colspan="2" class="extra-mtr-merged" style="vertical-align:middle; white-space:nowrap; text-align:center;">' +
                        '<input type="number" name="extra_items[' + i +
                        '][mtr_for_01_nos_trolley]" class="form-control extra-mtr-input" step="any" min="0" placeholder="0" style="width:120px; margin:0 auto;" value="">' +
                        '</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="number" name="extra_items[' + i +
                        '][rate]" class="form-control extra-rate-input" step="0.001" min="0" style="width:100px;" value="0" readonly>' +
                        '</td>' +
                        '<td style="vertical-align:middle; white-space:nowrap; font-weight:bold;" class="extra-row-total">&mdash;</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<button type="button" class="btn btn-danger btn-sm" onclick="removeExtraRow(' + i +
                        ')"><i class="fa fa-trash"></i></button>' +
                        '</td>' +
                        '</tr>';

                    document.getElementById('extraItemsBody').insertAdjacentHTML('beforeend', row);

                    var rowEl = document.getElementById('extra_row_' + i);
                    var menu = buildMenu(i);

                    rowEl.querySelector('.ei-trigger').addEventListener('click', function(e) {
                        e.stopPropagation();
                        openMenu(this, menu);
                    });

                    rowEl.querySelector('.extra-qty-input').addEventListener('input', function() {
                        checkStock(i);
                    });

                    // Manually-entered "value" input in the merged trolley column.
                    // Total = entered value × Rate; updates live on input.
                    var mtrInputEl = rowEl.querySelector('.extra-mtr-input');
                    if (mtrInputEl) {
                        mtrInputEl.addEventListener('input', function() {
                            updateManualRowComputed(rowEl);
                        });
                    }
                }

                window.removeExtraRow = function(i) {
                    // also remove the floating menu if present
                    var menu = document.querySelector('.ei-dropdown-menu[data-row="' + i + '"]');
                    if (menu) menu.remove();
                    var row = document.getElementById('extra_row_' + i);
                    if (row) row.remove();
                    recomputeExtraGrandTotal();
                };

                document.getElementById('addExtraRow').addEventListener('click', addExtraRow);

                // ── Manual Shortage Rows (Add More — unified into main shortage table) ──────
                var shortageManualRowCount = 0;
                var requisitionSentFlag = {{ $requisitionSent ? 'true' : 'false' }};

                // Count of BOM-derived shortage rows rendered server-side
                var bomRowCount = {{ count($shortageSent) }};

                // BOM rows that are NOT yet in the requisition (shown with "Not in Requisition" badge).
                // These are BOM-derived rows where is_sent_to_purchase != 1.
                // In State 2 (requisition already sent) they must be inserted as new rows via
                // store-additional-shortage-requisition so they get picked up by send-pending-shortage-to-purchase.
                var bomNotSentItems = [];
                @if ($requisitionSent)
                    @foreach ($shortageSent as $bomItem)
                        @php
                            $bomIsSent = isset($bomItem->is_sent_to_purchase) && (int) $bomItem->is_sent_to_purchase === 1;
                        @endphp
                        @if (!$bomIsSent)
                            bomNotSentItems.push({
                                part_item_id: '{{ $bomItem->part_item_id ?? '' }}',
                                product_description: {!! json_encode($bomItem->product_description ?? (optional($bomItem->partItem)->description ?? '')) !!},
                                required_quantity: '{{ $bomItem->required_quantity ?? 0 }}',
                                available_quantity: '{{ $bomItem->available_stock ?? 0 }}',
                                shortage_quantity: '{{ $bomItem->shortage_quantity ?? 0 }}',
                                unit_id: '{{ $bomItem->unit_id ?? '' }}',
                                rate: '{{ $bomItem->rate ?? '' }}',
                                mtr_for_01_nos_trolley: '{{ $bomItem->mtr_for_01_nos_trolley ?? '' }}',
                                length: '{{ $bomItem->length ?? '' }}',
                                // T-2026-059: required_quantity above is ALREADY the final unit-aware,
                                // trolley-scaled figure (computed server-side in showBomInventoryCheck()) —
                                // tell the server not to re-scale it (only genuinely-raw manual rows need
                                // server-side scaling). See storeAdditionalShortageRequisition().
                                already_scaled: '1',
                                // T-2026-060: this payload is re-posted verbatim on every retry of
                                // the send chain, so the server must keep treating it idempotently
                                // (skip if already present) — unlike a user-added row.
                                row_origin: 'bom'
                            });
                        @endif
                    @endforeach
                @endif

                // Count of pre-rendered draft rows (from server — is_sent_to_purchase=0)
                var preRenderedDraftCount = {{ count($shortageDraft) }};

                // Renumber all Sr. cells in the unified shortage table
                function renumberShortageRows() {
                    var cells = document.querySelectorAll('#shortageTableBody tr .sr-cell');
                    cells.forEach(function(cell, idx) {
                        cell.textContent = idx + 1;
                    });
                }

                // Update the pending draft count badge in the header and unified send button label
                function updateDraftCount() {
                    var manualRows = document.querySelectorAll('#shortageTableBody tr.shortage-row-manual');
                    var total = manualRows.length;
                    var countEl = document.getElementById('pendingDraftCount');
                    if (countEl) countEl.textContent = total;
                    // Show/hide the badge
                    var badge = document.getElementById('pendingDraftCountBadge');
                    if (badge) badge.style.display = total > 0 ? '' : 'none';

                    // T-2026-059 (Defect 2iv): the "Items: N shortage" counter in the product-info
                    // header is otherwise a static PHP-rendered number from page load — it never
                    // reflected rows added client-side via addShortageManualRow(). Re-derive it from
                    // the live DOM row count (BOM shortage rows + pre-rendered drafts + JS-added rows
                    // all live in the same #shortageTableBody) so it always matches what is visible.
                    var shortageCountEl = document.getElementById('itemsShortageCount');
                    if (shortageCountEl) {
                        var tbody = document.getElementById('shortageTableBody');
                        shortageCountEl.textContent = tbody ? tbody.querySelectorAll('tr').length : 0;
                    }

                    // Update unified send button label + visibility
                    var sendBtn = document.getElementById('unifiedSendBtn');
                    var sendLabel = document.getElementById('unifiedSendLabel');
                    if (!sendBtn) return;
                    var hasBom = bomRowCount > 0;
                    var hasDraft = total > 0;
                    if (!hasBom && !hasDraft) {
                        sendBtn.style.display = 'none';
                    } else {
                        sendBtn.style.display = '';
                        if (hasBom && hasDraft) {
                            sendLabel.textContent = 'Send Shortage List + ' + total + ' New Item(s) to Purchase';
                        } else if (hasBom) {
                            sendLabel.textContent = 'Send Shortage List to Purchase';
                        } else {
                            sendLabel.textContent = 'Send ' + total + ' New Item(s) to Purchase';
                        }
                    }
                }

                // Wire qty-change on pre-rendered draft rows (recompute shortage qty on input)
                document.querySelectorAll('#shortageTableBody tr.shortage-row-manual[data-req-item-id]').forEach(function(row) {
                    var qtyInput = row.querySelector('.sm-qty-input');
                    var availInput = row.querySelector('.sm-avail-stock');
                    var shortInput = row.querySelector('.sm-shortage-qty');
                    if (qtyInput) {
                        qtyInput.addEventListener('input', function() {
                            var qty = parseFloat(this.value) || 0;
                            var avail = parseFloat(availInput ? availInput.value : 0) || 0;
                            if (shortInput) shortInput.value = Math.max(0, qty - avail).toFixed(3);
                        });
                    }
                });

                // Delegated listener: Mtr for 01 Nos Trolley input → auto-compute N-trolleys readonly field
                document.addEventListener('input', function(e) {
                    if (!e.target.classList.contains('short-mtr1')) return;
                    var row = e.target.closest('tr');
                    if (!row) return;
                    var mtrNInput = row.querySelector('.short-mtrN');
                    if (!mtrNInput) return;
                    var mtr1 = parseFloat(e.target.value) || 0;
                    var trolleyQty = parseInt(e.target.getAttribute('data-trolley-qty') || '1', 10) || 1;
                    mtrNInput.value = (mtr1 * trolleyQty).toFixed(3);
                });

                function buildShortageMenu(rowIndex) {
                    var menu = document.createElement('div');
                    menu.className = 'ei-dropdown-menu';
                    menu.setAttribute('data-srow', rowIndex);
                    menu.innerHTML =
                        '<div class="ei-search-box"><input type="text" class="form-control form-control-sm ei-search-input" placeholder="Search part..."></div>' +
                        '<div class="ei-options-list"></div>';

                    var list = menu.querySelector('.ei-options-list');
                    partData.forEach(function(p) {
                        var opt = document.createElement('div');
                        opt.className = 'ei-option';
                        opt.textContent = p.label;
                        opt.setAttribute('data-id', p.id);
                        opt.setAttribute('data-rate', p.rate);
                        list.appendChild(opt);
                    });

                    menu.querySelector('.ei-search-input').addEventListener('input', function() {
                        filterOptions(menu, this.value);
                    });

                    menu.addEventListener('click', function(e) {
                        var opt = e.target.closest('.ei-option');
                        if (!opt) return;
                        var rowEl = document.getElementById('shortage_manual_row_' + rowIndex);
                        if (!rowEl) return;
                        rowEl.querySelector('.sm-trigger').textContent = opt.textContent;
                        rowEl.querySelector('.sm-part-id').value = opt.getAttribute('data-id');
                        rowEl.querySelector('.sm-rate-input').value = opt.getAttribute('data-rate');
                        rowEl.querySelector('.sm-desc').value = opt.textContent;
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
                    var quantity = rowEl.querySelector('.sm-qty-input').value;
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
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            var avail = (data.available_quantity !== undefined) ? parseFloat(data.available_quantity) :
                                0;
                            if (availInput) availInput.value = avail.toFixed(3);
                            var shortage = Math.max(0, qty - avail);
                            if (shortInput) shortInput.value = shortage.toFixed(3);
                        })
                        .catch(function() {
                            if (availInput) availInput.value = '0';
                            var shortage = Math.max(0, parseFloat(quantity) || 0);
                            if (shortInput) shortInput.value = shortage.toFixed(3);
                        });
                }

                function addShortageManualRow() {
                    var i = shortageManualRowCount++;
                    var trolleyQtyVal = {{ (int)($trolleyQty ?? 1) }};
                    var row =
                        '<tr id="shortage_manual_row_' + i + '" class="shortage-row-manual" style="background:#fff8f0;">' +
                        '<td style="vertical-align:middle;" class="sr-cell">?</td>' +
                        '<td style="vertical-align:middle; white-space:nowrap; font-size:12px; color:#999;">' + new Date().toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'}) + '</td>' +
                        '<td class="shortage-desc-col" style="vertical-align:middle; min-width:220px;">' +
                        '<div class="ei-dropdown">' +
                        '<button type="button" class="btn btn-default form-control sm-trigger" style="text-align:left;">-- Select Part Item --</button>' +
                        '</div>' +
                        '<input type="hidden" class="sm-part-id" value="">' +
                        '<input type="hidden" class="sm-desc" value="">' +
                        '</td>' +
                        // requisition_items has no length/total_in_mm columns — no BOM context, show em-dash (T-2026-058)
                        '<td style="vertical-align:middle;">&mdash;</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="number" class="form-control sm-qty-input" step="0.001" min="0.001" style="width:110px;" placeholder="0">' +
                        '</td>' +
                        '<td style="vertical-align:middle;">&mdash;</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="number" class="form-control sm-avail-stock" step="0.001" min="0" value="0" readonly style="width:110px; background:#f8f9fa;">' +
                        '</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="number" class="form-control sm-shortage-qty" step="0.001" min="0" value="0" readonly style="width:110px; background:#f8f9fa;">' +
                        '</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<select class="form-control sm-unit-select" style="min-width:100px;">' + unitOptions +
                        '</select>' +
                        '</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="number" class="form-control short-mtr1" step="any" min="0" data-trolley-qty="' + trolleyQtyVal + '" placeholder="0.000" style="min-width:90px;">' +
                        '</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="text" class="form-control short-mtrN" readonly tabindex="-1" style="background:#f3f4f6;cursor:not-allowed;min-width:90px;">' +
                        '</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<input type="number" class="form-control sm-rate-input" step="0.001" min="0" value="0" style="width:100px;">' +
                        '</td>' +
                        '<td style="vertical-align:middle;">' +
                        '<button type="button" class="btn btn-danger btn-sm" onclick="removeShortageManualRow(' + i +
                        ')"><i class="fa fa-trash"></i></button>' +
                        '</td>' +
                        '</tr>';

                    // Append to unified shortage table tbody
                    var tbody = document.getElementById('shortageTableBody');
                    if (tbody) {
                        tbody.insertAdjacentHTML('beforeend', row);
                    }

                    renumberShortageRows();
                    updateDraftCount();

                    var rowEl = document.getElementById('shortage_manual_row_' + i);
                    var menu = buildShortageMenu(i);

                    rowEl.querySelector('.sm-trigger').addEventListener('click', function(e) {
                        e.stopPropagation();
                        openMenu(this, menu);
                    });

                    rowEl.querySelector('.sm-qty-input').addEventListener('input', function() {
                        var availInput = rowEl.querySelector('.sm-avail-stock');
                        var shortInput = rowEl.querySelector('.sm-shortage-qty');
                        var qty = parseFloat(this.value) || 0;
                        var avail = parseFloat(availInput ? availInput.value : 0) || 0;
                        if (shortInput) shortInput.value = Math.max(0, qty - avail).toFixed(3);
                        checkShortageStock(i);
                    });
                }

                window.removeShortageManualRow = function(i) {
                    var menu = document.querySelector('.ei-dropdown-menu[data-srow="' + i + '"]');
                    if (menu) menu.remove();
                    var row = document.getElementById('shortage_manual_row_' + i);
                    if (row) row.remove();
                    renumberShortageRows();
                    updateDraftCount();
                };

                function hasIncompleteShortageRow() {
                    var incomplete = false;
                    // Only check manual/draft rows (shortage-row-manual), not BOM read-only rows
                    document.querySelectorAll('#shortageTableBody tr.shortage-row-manual').forEach(function(row) {
                        var partId = row.querySelector('.sm-part-id') ? row.querySelector('.sm-part-id').value
                        .trim() : '';
                        var qty = parseFloat(row.querySelector('.sm-qty-input') ? row.querySelector('.sm-qty-input')
                            .value : 0) || 0;
                        var unitId = row.querySelector('.sm-unit-select') ? row.querySelector('.sm-unit-select')
                            .value.trim() : '';
                        if (!partId || qty <= 0 || !unitId) {
                            incomplete = true;
                        }
                    });
                    return incomplete;
                }

                function prepareManualShortageInputs(formEl) {
                    // Remove any previously injected manual_shortage inputs
                    formEl.querySelectorAll('input[name^="manual_shortage"]').forEach(function(el) {
                        el.remove();
                    });
                    // Only serialize manual/draft rows (shortage-row-manual)
                    var rows = document.querySelectorAll('#shortageTableBody tr.shortage-row-manual');
                    rows.forEach(function(row, idx) {
                        var partId = row.querySelector('.sm-part-id') ? row.querySelector('.sm-part-id').value : '';
                        var desc = row.querySelector('.sm-desc') ? row.querySelector('.sm-desc').value : '';
                        var qty = row.querySelector('.sm-qty-input') ? row.querySelector('.sm-qty-input').value : '0';
                        var availStock = row.querySelector('.sm-avail-stock') ? row.querySelector('.sm-avail-stock').value : '0';
                        var shortageQty = row.querySelector('.sm-shortage-qty') ? row.querySelector('.sm-shortage-qty').value : '0';
                        var unitId = row.querySelector('.sm-unit-select') ? row.querySelector('.sm-unit-select').value : '';
                        var rate = row.querySelector('.sm-rate-input') ? row.querySelector('.sm-rate-input').value : '0';
                        var mtr1 = row.querySelector('.short-mtr1') ? row.querySelector('.short-mtr1').value : '';

                        function addHidden(name, val) {
                            var inp = document.createElement('input');
                            inp.type = 'hidden';
                            inp.name = name;
                            inp.value = val;
                            formEl.appendChild(inp);
                        }
                        addHidden('manual_shortage[' + idx + '][part_item_id]', partId);
                        addHidden('manual_shortage[' + idx + '][product_description]', desc);
                        addHidden('manual_shortage[' + idx + '][required_quantity]', qty);
                        addHidden('manual_shortage[' + idx + '][available_quantity]', availStock);
                        addHidden('manual_shortage[' + idx + '][shortage_quantity]', shortageQty);
                        addHidden('manual_shortage[' + idx + '][unit_id]', unitId);
                        addHidden('manual_shortage[' + idx + '][rate]', rate);
                        addHidden('manual_shortage[' + idx + '][mtr_for_01_nos_trolley]', mtr1);
                        // T-2026-059: genuinely manual/free-typed row — required_quantity/mtr1 above
                        // are raw per-1-trolley bases; server must unit-aware + trolley-scale them.
                        addHidden('manual_shortage[' + idx + '][already_scaled]', '0');
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

                // Wire unified send button — handles both BOM shortage + manual/draft rows in one click
                var unifiedSendBtn = document.getElementById('unifiedSendBtn');
                if (unifiedSendBtn) {
                    unifiedSendBtn.addEventListener('click', function() {
                        var allManualRows = document.querySelectorAll('#shortageTableBody tr.shortage-row-manual');
                        var msgEl = document.getElementById('shortageManualValidationMsg');
                        if (msgEl) {
                            msgEl.style.display = 'none';
                        }

                        // Validate manual rows (BOM rows are read-only, no validation needed)
                        if (allManualRows.length > 0 && hasIncompleteShortageRow()) {
                            if (msgEl) {
                                msgEl.textContent =
                                    'Please complete Part Item, Required Qty (> 0), and Unit for all manually-added rows.';
                                msgEl.style.display = 'block';
                                msgEl.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            } else {
                                showAlert('Validation',
                                    'Please complete Part Item, Required Qty, and Unit for all rows.', 'warning'
                                    );
                            }
                            return;
                        }

                        // State 1: No requisition sent yet — submit shortageReqForm (BOM items[] + manual_shortage[])
                        if (!requisitionSentFlag) {
                            var reqForm = document.getElementById('shortageReqForm');
                            if (reqForm) {
                                reqForm.dispatchEvent(new Event('submit', {
                                    bubbles: true,
                                    cancelable: true
                                }));
                            } else {
                                showAlert('Error', 'Form not found. Please reload the page.', 'error');
                            }
                            return;
                        }

                        // State 2: Requisition already sent — AJAX chain: update existing drafts + insert new rows + sendPending
                        var existingDraftRows = [];
                        var newRows = [];

                        allManualRows.forEach(function(row) {
                            var reqItemId = row.getAttribute('data-req-item-id') || '';
                            var partId = row.querySelector('.sm-part-id') ? row.querySelector('.sm-part-id')
                                .value.trim() : '';
                            var qty = parseFloat(row.querySelector('.sm-qty-input') ? row.querySelector(
                                '.sm-qty-input').value : 0) || 0;
                            var unitId = row.querySelector('.sm-unit-select') ? row.querySelector(
                                '.sm-unit-select').value.trim() : '';
                            var rate = parseFloat(row.querySelector('.sm-rate-input') ? row.querySelector(
                                '.sm-rate-input').value : 0) || 0;
                            var avail = parseFloat(row.querySelector('.sm-avail-stock') ? row.querySelector(
                                '.sm-avail-stock').value : 0) || 0;
                            var shortage = parseFloat(row.querySelector('.sm-shortage-qty') ? row
                                .querySelector('.sm-shortage-qty').value : 0) || 0;
                            var desc = row.querySelector('.sm-desc') ? row.querySelector('.sm-desc').value :
                                '';
                            var mtr1 = row.querySelector('.short-mtr1') ? (row.querySelector('.short-mtr1').value || '') : '';

                            if (!partId || qty <= 0 || !unitId) return; // skip incomplete

                            if (reqItemId) {
                                existingDraftRows.push({
                                    requisition_item_id: reqItemId,
                                    required_quantity: qty,
                                    unit_id: unitId,
                                    rate: rate,
                                    mtr_for_01_nos_trolley: mtr1
                                });
                            } else {
                                newRows.push({
                                    part_item_id: partId,
                                    product_description: desc,
                                    required_quantity: qty,
                                    available_quantity: avail,
                                    shortage_quantity: shortage,
                                    unit_id: unitId,
                                    rate: rate,
                                    mtr_for_01_nos_trolley: mtr1,
                                    // T-2026-059: genuinely manual/free-typed row — qty/mtr1 are raw
                                    // per-1-trolley bases, server must unit-aware + trolley-scale them.
                                    already_scaled: '0',
                                    // T-2026-060: deliberately added by the Store user via +Add More —
                                    // the server must never silently discard it as a duplicate.
                                    row_origin: 'manual'
                                });
                            }
                        });

                        // Prepend BOM-not-yet-sent items (server-rendered) so they are inserted
                        // via store-additional-shortage-requisition alongside any new manual rows.
                        // The controller skips duplicates (same part_item_id already in requisition),
                        // so this is safe to call even if some were added in a previous attempt.
                        newRows = bomNotSentItems.concat(newRows);

                        var totalCount = existingDraftRows.length + newRows.length;
                        var bdId = '{{ $productDetails->id }}';
                        var csrfToken = getCsrfToken();

                        confirmWithPrompt({
                            title: 'Send to Purchase?',
                            text: 'Send shortage items to the Purchase department?',
                            icon: 'question',
                            confirmButtonText: 'Yes, Send'
                        }, function() {
                            unifiedSendBtn.disabled = true;
                            unifiedSendBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

                            // Outcome of Step 2, needed by the Step 3 handler below.
                            var lastInsertResult = null;

                            // Step 1: Update existing draft rows
                            var updatePromises = existingDraftRows.map(function(rowData) {
                                return fetch('{{ route('update-draft-shortage-item') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify(rowData)
                                }).then(function(r) {
                                    return r.json();
                                });
                            });

                            Promise.all(updatePromises).then(function(updateResults) {
                                var updateFailed = updateResults.some(function(r) {
                                    return r && r.status !== 'success';
                                });
                                if (updateFailed) {
                                    var failMsg = updateResults.filter(function(r) {
                                            return r && r.status !== 'success';
                                        })
                                        .map(function(r) {
                                            return r.msg || 'Update failed';
                                        }).join('; ');
                                    showAlert('Error', 'Could not update draft items: ' + failMsg, 'error');
                                    unifiedSendBtn.disabled = false;
                                    unifiedSendBtn.innerHTML = '<i class="fa fa-paper-plane"></i> <span id="unifiedSendLabel">Send to Purchase</span>';
                                    updateDraftCount();
                                    return Promise.reject('update_failed');
                                }

                                // Step 2: Insert new rows
                                if (newRows.length === 0) {
                                    return Promise.resolve({ status: 'success', inserted: 0, merged: 0, skipped: 0 });
                                }

                                var formData = new FormData();
                                formData.append('_token', csrfToken);
                                formData.append('business_details_id', bdId);
                                newRows.forEach(function(rowData, idx) {
                                    Object.keys(rowData).forEach(function(key) {
                                        formData.append('manual_shortage[' + idx + '][' + key + ']', rowData[key]);
                                    });
                                });

                                return fetch('{{ route('store-additional-shortage-requisition') }}', {
                                    method: 'POST',
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                    body: formData
                                }).then(function(r) { return r.json(); });

                            }).then(function(insertResult) {
                                if (!insertResult || insertResult.status !== 'success') {
                                    var errMsg = insertResult ? (insertResult.msg || 'Failed to save new items.') : 'No response from server.';
                                    showAlert('Error', errMsg, 'error');
                                    unifiedSendBtn.disabled = false;
                                    unifiedSendBtn.innerHTML = '<i class="fa fa-paper-plane"></i> <span id="unifiedSendLabel">Send to Purchase</span>';
                                    updateDraftCount();
                                    return Promise.reject('insert_failed');
                                }

                                // T-2026-060: keep the insert outcome (inserted/merged/skipped
                                // counts) so the final alert can explain a no-op instead of
                                // showing a green "Success" over "nothing happened".
                                lastInsertResult = insertResult;

                                // Step 3: Flip all drafts to sent
                                return fetch('{{ route('send-pending-shortage-to-purchase') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ business_details_id: bdId })
                                }).then(function(r) { return r.json(); });

                            }).then(function(sendResult) {
                                if (!sendResult) return;
                                if (sendResult.status === 'success') {
                                    var okMsg = sendResult.msg || 'Items sent to Purchase.';
                                    // Surface partially-skipped rows even on an otherwise
                                    // successful send, so nothing disappears silently.
                                    if (lastInsertResult && lastInsertResult.skipped > 0) {
                                        okMsg += ' ' + (lastInsertResult.msg || '');
                                    }
                                    showAlert('Success', okMsg, 'success');
                                    setTimeout(function() { location.reload(); }, 2500);
                                } else if (sendResult.status === 'info') {
                                    // T-2026-060: updated === 0 means the click changed nothing.
                                    // Report it as a warning with the reason (usually: every row
                                    // posted was already present in the requisition), never as a
                                    // green success tick.
                                    var infoMsg = sendResult.msg || 'Nothing was sent.';
                                    if (lastInsertResult && lastInsertResult.msg &&
                                        (lastInsertResult.skipped > 0 || lastInsertResult.inserted === 0)) {
                                        infoMsg += ' ' + lastInsertResult.msg;
                                    }
                                    // Reload only once the user has actually read and dismissed
                                    // this — a timed reload would tear the dialog down first.
                                    showAlert('Nothing to Send', infoMsg, 'warning', function() {
                                        location.reload();
                                    });
                                } else {
                                    showAlert('Error', sendResult.msg || 'Something went wrong.', 'error');
                                    unifiedSendBtn.disabled = false;
                                    unifiedSendBtn.innerHTML = '<i class="fa fa-paper-plane"></i> <span id="unifiedSendLabel">Send to Purchase</span>';
                                    updateDraftCount();
                                }
                            }).catch(function(reason) {
                                if (reason !== 'update_failed' && reason !== 'insert_failed') {
                                    showAlert('Error', 'Network error. Please try again.', 'error');
                                    unifiedSendBtn.disabled = false;
                                    unifiedSendBtn.innerHTML = '<i class="fa fa-paper-plane"></i> <span id="unifiedSendLabel">Send to Purchase</span>';
                                    updateDraftCount();
                                }
                            });
                        });
                    });
                }

                // Budget validation + double-submit guard
                var estimationAmt = {{ (float) ($estimationAmount ?? 0) }};
                var issuedTotal = {{ $issuedGrandTotal }};
                var budgetExceeded = {{ $issuedExceedsEstimation ? 'true' : 'false' }};

                function calcNewItemsTotal() {
                    // T-2026-058: all rows in #extraItemsBody (BOM-prefilled, production-request,
                    // and manually-added) carry their own live [quantity]/[rate] DOM inputs, so a
                    // single DOM-driven pass over every <tr> here already covers every row exactly
                    // once. A previous version of this function ALSO summed required_quantity*rate
                    // for $available server-side (hardcoded at page load) before this loop, which
                    // double-counted every BOM row — and, after this task pre-fills the Quantity
                    // input with the unit-aware issue figure instead of required_quantity, that
                    // hardcoded duplicate would no longer even match the DOM value it duplicated.
                    // Removed: this loop alone is the single source of truth and reflects any live
                    // edits the user makes to quantity/rate before submit.
                    var total = 0;
                    document.querySelectorAll('#extraItemsBody tr').forEach(function(row) {
                        var qtyEl = row.querySelector('input[name*="[quantity]"]');
                        var rateEl = row.querySelector('input[name*="[rate]"].extra-rate-hidden') ||
                            row.querySelector('input[name*="[rate]"]');
                        var qty = qtyEl ? parseFloat(qtyEl.value) || 0 : 0;
                        var rate = rateEl ? parseFloat(rateEl.value) || 0 : 0;
                        total += qty * rate;
                    });
                    return total;
                }

                function getIssueSelectionError() {
                    var hasBomItems = {{ count($available) > 0 ? 'true' : 'false' }};
                    var hasValidExtraItem = false;
                    var hasIncompleteExtraItem = false;

                    document.querySelectorAll('#extraItemsBody tr').forEach(function(row) {
                        var partEl = row.querySelector('input[name*="[part_item_id]"]');
                        var qtyEl = row.querySelector('input[name*="[quantity]"]');
                        var unitEl = row.querySelector('select[name*="[unit_id]"], input[name*="[unit_id]"]');
                        var partId = partEl ? String(partEl.value || '').trim() : '';
                        var qty = qtyEl ? parseFloat(qtyEl.value) || 0 : 0;
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
                    msgEl.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                function showAlert(title, text, icon, onClose) {
                    if (window.Swal) {
                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon || 'info',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#25385F'
                        }).then(function() {
                            if (typeof onClose === 'function') onClose();
                        });
                    } else if (typeof onClose === 'function') {
                        // Swal missing — still honour the caller's continuation.
                        onClose();
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
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                onConfirm();
                            }
                        });
                    }
                }

                // Wire shortageReqForm (hidden form for first-time BOM+manual send)
                var shortageReqForm = document.getElementById('shortageReqForm');
                if (shortageReqForm) {
                    shortageReqForm.addEventListener('submit', function(e) {
                        if (shortageReqForm.dataset.confirmed === '1') return;

                        e.preventDefault();

                        // Validate and serialize manual shortage rows into the form
                        if (typeof hasIncompleteShortageRow === 'function' && hasIncompleteShortageRow()) {
                            var smMsgEl = document.getElementById('shortageManualValidationMsg');
                            if (smMsgEl) {
                                smMsgEl.textContent =
                                    'Please complete Part Item, Required Qty (> 0), and Unit for all manually-added rows, or remove them.';
                                smMsgEl.style.display = 'block';
                                smMsgEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
                                showAlert('Validation',
                                    'Please complete or remove the incomplete manually-added shortage rows.', 'warning');
                            }
                            return;
                        }
                        if (typeof prepareManualShortageInputs === 'function') {
                            prepareManualShortageInputs(shortageReqForm);
                        }

                        var manualCount = document.querySelectorAll('#shortageTableBody tr.shortage-row-manual').length;
                        var bomCount = {{ count($shortageSent) }};
                        var confirmText = bomCount > 0 && manualCount > 0
                            ? 'Send ' + bomCount + ' BOM shortage item(s) + ' + manualCount + ' manually-added item(s) to Purchase department?'
                            : bomCount > 0
                                ? 'Send ' + bomCount + ' shortage item(s) as a requisition to Purchase department?'
                                : 'Send ' + manualCount + ' manually-added item(s) to Purchase department?';

                        confirmWithPrompt({
                            title: 'Send Shortage Requisition?',
                            text: confirmText,
                            confirmButtonText: 'Yes, Send Requisition'
                        }, function() {
                            shortageReqForm.dataset.confirmed = '1';
                            if (unifiedSendBtn) {
                                unifiedSendBtn.disabled = true;
                                unifiedSendBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
                            }
                            shortageReqForm.submit();
                        });
                    });
                }

                var issueForm = document.getElementById('issueAvailableForm');
                if (issueForm) {
                    issueForm.addEventListener('submit', function(e) {
                        var btn = document.getElementById('issueBtn');
                        if (!btn || btn.disabled) {
                            e.preventDefault();
                            return false;
                        }

                        if (budgetExceeded) {
                            e.preventDefault();
                            showAlert('Issue Blocked', 'Already issued amount exceeds the estimation amount.',
                                'error');
                            return false;
                        }

                        var selectionError = getIssueSelectionError();
                        if (selectionError) {
                            e.preventDefault();
                            showIssueValidation(selectionError);
                            return false;
                        }

                        if (estimationAmt > 0) {
                            var newTotal = calcNewItemsTotal();
                            var combinedTotal = issuedTotal + newTotal;
                            if (combinedTotal > estimationAmt) {
                                e.preventDefault();
                                var msg = 'Total to be issued (' + combinedTotal.toFixed(2) +
                                    ') will exceed the Estimation Amount (' + estimationAmt.toFixed(2) +
                                    ').\n\nDo you want to proceed anyway?';
                                confirmWithPrompt({
                                    title: 'Estimation Amount Exceeded',
                                    text: msg,
                                    icon: 'warning',
                                    confirmButtonText: 'Yes, Proceed'
                                }, function() {
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
                    $(issuedCollapse).on('show.bs.collapse', function() {
                        var toggle = document.querySelector('[data-target="#alreadyIssuedCollapse"]');
                        if (toggle) toggle.setAttribute('aria-expanded', 'true');
                    });
                    $(issuedCollapse).on('hide.bs.collapse', function() {
                        var toggle = document.querySelector('[data-target="#alreadyIssuedCollapse"]');
                        if (toggle) toggle.setAttribute('aria-expanded', 'false');
                    });
                }

                // ── Send Pending to Purchase ──────────────────────────────────────────
                var sendPendingBtn = document.getElementById('sendPendingBtn');
                if (sendPendingBtn) {
                    sendPendingBtn.addEventListener('click', function() {
                        var bd = sendPendingBtn.getAttribute('data-bd');
                        confirmWithPrompt({
                            title: 'Send Pending Items?',
                            text: 'Send all pending (draft) shortage items to the Purchase department?',
                            icon: 'question',
                            confirmButtonText: 'Yes, Send'
                        }, function() {
                            sendPendingBtn.disabled = true;
                            sendPendingBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

                            var csrfToken = document.querySelector('meta[name="csrf-token"]') ?
                                document.querySelector('meta[name="csrf-token"]').getAttribute('content') :
                                (document.querySelector('input[name="_token"]') ? document.querySelector(
                                    'input[name="_token"]').value : '');

                            fetch('{{ route('send-pending-shortage-to-purchase') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        business_details_id: bd
                                    })
                                })
                                .then(function(r) {
                                    return r.json();
                                })
                                .then(function(data) {
                                    if (data.status === 'success') {
                                        showAlert('Success', data.msg, 'success');
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1500);
                                    } else if (data.status === 'info') {
                                        showAlert('Nothing to Send', data.msg, 'info');
                                        sendPendingBtn.disabled = false;
                                        sendPendingBtn.innerHTML =
                                            '<i class="fa fa-paper-plane"></i> Send Pending to Purchase';
                                    } else {
                                        showAlert('Error', data.msg || 'Something went wrong.',
                                        'error');
                                        sendPendingBtn.disabled = false;
                                        sendPendingBtn.innerHTML =
                                            '<i class="fa fa-paper-plane"></i> Send Pending to Purchase';
                                    }
                                })
                                .catch(function() {
                                    showAlert('Error', 'Network error. Please try again.', 'error');
                                    sendPendingBtn.disabled = false;
                                    sendPendingBtn.innerHTML =
                                        '<i class="fa fa-paper-plane"></i> Send Pending to Purchase';
                                });
                        });
                    });
                }

                // ── T-2026-059: Resync Requisition with Current BOM/Stock ─────────────
                var resyncRequisitionBtn = document.getElementById('resyncRequisitionBtn');
                if (resyncRequisitionBtn) {
                    resyncRequisitionBtn.addEventListener('click', function() {
                        var bd = resyncRequisitionBtn.getAttribute('data-bd');
                        confirmWithPrompt({
                            title: 'Resync Requisition?',
                            text: 'Refresh this requisition against the current BOM and stock? Rows already covered by a Purchase Order will never be modified — only new or grown shortages will be added.',
                            icon: 'question',
                            confirmButtonText: 'Yes, Resync'
                        }, function() {
                            resyncRequisitionBtn.disabled = true;
                            resyncRequisitionBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Resyncing...';

                            fetch('{{ route('resync-shortage-requisition') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': getCsrfToken(),
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        business_details_id: bd
                                    })
                                })
                                .then(function(r) {
                                    return r.json();
                                })
                                .then(function(data) {
                                    if (data.status === 'success') {
                                        showAlert('Resync Complete', data.msg, 'success');
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1500);
                                    } else {
                                        showAlert('Error', data.msg || 'Something went wrong.', 'error');
                                        resyncRequisitionBtn.disabled = false;
                                        resyncRequisitionBtn.innerHTML =
                                            '<i class="fa fa-refresh"></i> Resync with Current BOM/Stock';
                                    }
                                })
                                .catch(function() {
                                    showAlert('Error', 'Network error. Please try again.', 'error');
                                    resyncRequisitionBtn.disabled = false;
                                    resyncRequisitionBtn.innerHTML =
                                        '<i class="fa fa-refresh"></i> Resync with Current BOM/Stock';
                                });
                        });
                    });
                }

                // ── Delete Draft Shortage Item ────────────────────────────────────────
                document.addEventListener('click', function(e) {
                    var btn = e.target.closest('.delete-draft-btn');
                    if (!btn) return;
                    var reqItemId = btn.getAttribute('data-id');
                    // Row is now in the unified shortage table — find by draft_row_{id}
                    var rowEl = document.getElementById('draft_row_' + reqItemId);

                    confirmWithPrompt({
                        title: 'Delete Draft Item?',
                        text: 'Remove this pending shortage item? It has not been sent to Purchase yet.',
                        icon: 'warning',
                        confirmButtonText: 'Yes, Delete'
                    }, function() {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                        var csrfToken = getCsrfToken();

                        fetch('{{ route('delete-draft-shortage-item') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    requisition_item_id: reqItemId
                                })
                            })
                            .then(function(r) {
                                return r.json();
                            })
                            .then(function(data) {
                                if (data.status === 'success') {
                                    if (rowEl) rowEl.remove();
                                    // Decrement preRenderedDraftCount if this was a server-side draft row
                                    if (preRenderedDraftCount > 0) preRenderedDraftCount--;
                                    renumberShortageRows();
                                    updateDraftCount();
                                } else if (data.status === 'error' && data.msg && data.msg.indexOf(
                                        'already been sent') !== -1) {
                                    showAlert('Cannot Delete', data.msg, 'error');
                                    btn.disabled = false;
                                    btn.innerHTML = '<i class="fa fa-trash"></i>';
                                } else {
                                    showAlert('Error', data.msg || 'Something went wrong.', 'error');
                                    btn.disabled = false;
                                    btn.innerHTML = '<i class="fa fa-trash"></i>';
                                }
                            })
                            .catch(function() {
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
