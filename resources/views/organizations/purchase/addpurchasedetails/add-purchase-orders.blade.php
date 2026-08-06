@extends('admin.layouts.master')
@section('content')
    <style>


    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Purchase Order <span class="table-project-n">Form</span> </h1>
                            </div><br>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label><strong>Business Name</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ $businessDetails->project_name ?? 'N/A' }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Product Name</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ $businessDetails->product_name ?? 'N/A' }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label><strong>Grand Total Amount</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ number_format($grand_total_amount, 2) }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>Used PO Amount</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ number_format($used_po_amount, 2) }}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>Remaining Amount</strong></label>
                                    <input type="text" class="form-control text-danger" id="remaining_amount_display"
                                        value="{{ number_format($remaining_amount, 2) }}" readonly>
                                </div>
                            </div>

                            <input type="hidden" id="remaining_amount" value="{{ $remaining_amount }}">
                            <form action="{{ route('store-purchase-order') }} " id="forms" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input class="form-control" type="hidden" name="business_details_id"
                                    id="business_details_id" value="{{ $business_detailsId }}">
                                <input class="form-control" type="hidden" name="requistition_id" id="requistition_id"
                                    value="{{ $requistition_id }}">
                                <input class="form-control" type="hidden" name="vendor_id" id="vendor_id">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Vendor Company Name <span class="text-danger">*</span></label>
                                            {{-- <select class="form-control"  name="vendor_id" id="vendor_id">
                          <option>Select</option> --}}

                                            <select class="form-control mb-2 select2" name="vendor_id" id="vendor_id">
                                                <option value="" default>Vendor Company Name</option>

                                                @foreach ($dataOutputVendor as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['vendor_company_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="vendor_type_id">Vendor Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control mb-2 select2" name="vendor_type_id"
                                                id="vendor_type_id">
                                                <option value="" default>Vendor Type</option>

                                                @foreach ($dataOutputVendorTyper as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Contact Person Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_person_name"
                                                value="{{ old('contact_person_name') }}" name="contact_person_name"
                                                placeholder="Enter Contact Person Name">
                                            @if ($errors->has('contact_person_name'))
                                                <span class="red-text"><?php echo $errors->first('contact_person_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Contact Person Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_person_number"
                                                value="{{ old('contact_person_number') }}" name="contact_person_number"
                                                placeholder="Enter Contact Person Number" maxlength="10" pattern="\d{10}">
                                            @if ($errors->has('contact_person_number'))
                                                <span class="red-text"><?php echo $errors->first('contact_person_number', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Tax Type<span class="text-danger">*</span></label>
                                            <select name="tax_type" class="form-control" title="select tax" id="tax_type">
                                                <option value="">Select Tax Type</option>
                                                <option value="GST">GST</option>
                                                <option value="SGST">SGST</option>
                                                <option value="CGST">CGST</option>
                                                <option value="SGST+CGST">SGST+CGST</option>
                                                <option value="IGST">IGST</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="tax_id">Tax<span class="text-danger">*</span></label>
                                            <select class="form-control mb-2" name="tax_id" id="tax_id">
                                                <option value="">Tax</option>
                                                @foreach ($dataOutputTax as $data)
                                                    <option value="{{ $data['id'] }}"
                                                        data-tax-rate="{{ $data['name'] }}">
                                                        {{ $data['name'] }}
                                                    </option>

                                                    {{-- <option value="{{ $data['id'] }}" data-tax-rate="{{ $data['value'] }}">
                                                {{ $data['name'] }}
                                            </option> --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Purchase Order Date <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                    name="invoice_date" id="invoice_date" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Payment Terms <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="payment_terms"
                                                value="{{ old('payment_terms') }}" name="payment_terms"
                                                placeholder="Enter Payment Terms">
                                            {{-- <select name="payment_terms" class="form-control" title="select tax" id="">
                      <option value="">Select Payment Terms</option>
                      <option value="30">30 Days</option>
                      <option value="60">60 Days</option>
                      <option value="90">90 Days</option>

                    </select> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Quote Number (optional)</label>
                                            <input class="form-control" type="text" name="quote_no" value=""
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>




                                @if($purchasedItems->count() > 0)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div style="background:#f0fff4; border:1px solid #28a745; border-radius:6px; padding:12px 16px;">
                                            <strong style="color:#28a745;"><i class="fa fa-check-circle"></i>
                                                Already in Purchase Order ({{ $purchasedItems->count() }} item(s) — not added again)</strong>
                                            <table class="table table-sm table-bordered mt-2 mb-0" style="background:#fff;">
                                                <thead style="background:#28a745; color:#fff;">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Product Description</th>
                                                        <th>Shortage Qty</th>
                                                        <th>Unit</th>
                                                        <th>Rate</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($purchasedItems as $pi => $pitem)
                                                    @php
                                                        // T-2026-059 iteration 3 fix: never read requisition_items.shortage_quantity
                                                        // raw — always go through BomTotalCalculator::effectiveShortageQuantity()
                                                        // so a LEGACY (is_qty_trolley_scaled=0) row is retroactively, correctly
                                                        // trolley-scaled here too (this is the actual PO-creation screen).
                                                        $piUnitName = optional(\App\Models\UnitMaster::find($pitem->unit_id))->name ?? '';
                                                        $piIsScaled = (int) ($pitem->is_qty_trolley_scaled ?? 0) === 1;
                                                        $piShortQty = \App\Support\BomTotalCalculator::effectiveShortageQuantity(
                                                            $pitem->available_quantity, $pitem->required_quantity, $pitem->mtr_for_01_nos_trolley, $piUnitName,
                                                            $pitem->trolley_qty, $trolleyQty, $piIsScaled
                                                        );
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $pi + 1 }}</td>
                                                        <td>{{ $pitem->product_description ?? '—' }}</td>
                                                        <td>{{ number_format($piShortQty, 3) }}</td>
                                                        <td>{{ $piUnitName !== '' ? $piUnitName : ($pitem->unit_id ?? '—') }}</td>
                                                        <td>{{ $pitem->rate !== null ? number_format((float)$pitem->rate, 3) : '—' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Requisition reference panel: shows pending requisition items not yet ordered.
                                     Read-only — user picks items manually from the editable grid below. --}}
                                @if($newRequisitionItems->count() > 0)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        {{-- Bootstrap 4 accordion: COLLAPSED by default; user clicks header to expand --}}
                                        <div style="border:1px solid #17a2b8; border-radius:6px; overflow:hidden;">
                                            <div id="reqRefPanelHeading"
                                                 style="background:#17a2b8; padding:10px 16px; cursor:pointer;"
                                                 data-toggle="collapse"
                                                 data-target="#reqRefPanelBody"
                                                 aria-expanded="false"
                                                 aria-controls="reqRefPanelBody">
                                                <strong style="color:#fff;">
                                                    <i class="fa fa-info-circle"></i>
                                                    Requisition Items &mdash; Not Yet Ordered ({{ $newRequisitionItems->count() }} item(s) &mdash; add manually below)
                                                </strong>
                                                <span class="float-right" style="color:#fff;">
                                                    {{-- stopPropagation so clicking Print/Download doesn't toggle the accordion --}}
                                                    <button type="button" class="btn btn-light btn-sm mr-1"
                                                            style="padding:2px 8px; font-size:12px;"
                                                            onclick="event.stopPropagation(); printPoRequisition();">
                                                        <i class="fa fa-print"></i> Print
                                                    </button>
                                                    <button type="button" class="btn btn-light btn-sm mr-2"
                                                            style="padding:2px 8px; font-size:12px;"
                                                            onclick="event.stopPropagation(); downloadPoRequisitionCsv();">
                                                        <i class="fa fa-download"></i> CSV
                                                    </button>
                                                    <i class="fa fa-chevron-down req-ref-chevron"></i>
                                                </span>
                                            </div>
                                            <div id="reqRefPanelBody"
                                                 class="collapse"
                                                 aria-labelledby="reqRefPanelHeading">
                                                <div style="background:#e8f4fd; padding:12px 16px;">
                                                    @php
                                                        // T-2026-059 iteration 3 fix: quantities read from requisition_items must
                                                        // ALWAYS go through BomTotalCalculator's effective-* helpers (never a
                                                        // locally re-derived formula) so a LEGACY (is_qty_trolley_scaled=0) row
                                                        // is retroactively, correctly trolley-scaled for display here too — same
                                                        // rule already applied on Store's own
                                                        // list-material-sent-to-purchase.blade.php. This is the actual
                                                        // PO-creation screen, so it must never show the old, wrong, unscaled
                                                        // figure for a legacy sent requisition.
                                                        $fmt_PO = fn($n) => ($n === null || $n === '') ? '—' : rtrim(rtrim(number_format((float) $n, 3, '.', ''), '0'), '.');
                                                    @endphp
                                                    <table class="table table-sm table-bordered mt-1 mb-0" style="background:#fff;">
                                                        <thead style="background:#17a2b8; color:#fff;">
                                                            <tr>
                                                                <th>Sr.</th>
                                                                <th>Product Description</th>
                                                                <th>Shortage Qty</th>
                                                                <th>Unit</th>
                                                                <th>Mtr for 01 Nos Trolley</th>
                                                                <th>Mtr/Nos for {{ $trolleyQty }} Trolley(s)</th>
                                                                <th>Rate</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($newRequisitionItems as $pi => $pitem)
                                                            @php
                                                                // T-2026-059 iteration 3 fix: reuse BomTotalCalculator's
                                                                // effectiveShortageQuantity() (never a locally duplicated
                                                                // formula) so a legacy (is_qty_trolley_scaled=0) row is
                                                                // retroactively, correctly trolley-scaled here too. Used for
                                                                // BOTH the "Shortage Qty" column and the "Mtr/Nos for N
                                                                // Trolley(s)" column (the latter previously derived its own
                                                                // value FROM shortage_quantity in the removed closure — same
                                                                // canonical value is reused here, single source of truth).
                                                                $unitNamePO = optional(\App\Models\UnitMaster::find($pitem->unit_id))->name;
                                                                $mtr1PO = $pitem->mtr_for_01_nos_trolley ?? null;
                                                                $poIsScaled = (int) ($pitem->is_qty_trolley_scaled ?? 0) === 1;
                                                                $poShortQty = \App\Support\BomTotalCalculator::effectiveShortageQuantity(
                                                                    $pitem->available_quantity, $pitem->required_quantity, $pitem->mtr_for_01_nos_trolley,
                                                                    (string) ($unitNamePO ?? ''), $pitem->trolley_qty, $trolleyQty, $poIsScaled
                                                                );
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $pi + 1 }}</td>
                                                                <td>{{ $pitem->product_description ?? optional($pitem->partItem)->description ?? '—' }}</td>
                                                                <td>{{ number_format($poShortQty, 3) }}</td>
                                                                <td>{{ $unitNamePO ?? $pitem->unit_id ?? '—' }}</td>
                                                                <td>{{ $fmt_PO($mtr1PO) }}</td>
                                                                <td>{{ $fmt_PO($poShortQty) }}</td>
                                                                <td>{{ $pitem->rate !== null ? number_format((float)$pitem->rate, 3) : '—' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Flip chevron direction on collapse/expand --}}
                                        <script>
                                            (function () {
                                                var el = document.getElementById('reqRefPanelBody');
                                                if (el) {
                                                    el.addEventListener('hide.bs.collapse', function () {
                                                        var icon = document.querySelector('.req-ref-chevron');
                                                        if (icon) { icon.classList.remove('fa-chevron-up'); icon.classList.add('fa-chevron-down'); }
                                                    });
                                                    el.addEventListener('show.bs.collapse', function () {
                                                        var icon = document.querySelector('.req-ref-chevron');
                                                        if (icon) { icon.classList.remove('fa-chevron-down'); icon.classList.add('fa-chevron-up'); }
                                                    });
                                                }
                                            })();

                                            // Print + Download helpers for the Requisition Items accordion table.
                                            function printPoRequisition() {
                                                var table = document.querySelector('#reqRefPanelBody table');
                                                if (!table) { alert('No table data to print.'); return; }
                                                var w = window.open('', '_blank', 'width=1100,height=800');
                                                if (!w) { alert('Please allow pop-ups for this site to print.'); return; }
                                                w.document.write(
                                                    '<!doctype html><html><head><title>Requisition Items — Not Yet Ordered</title>' +
                                                    '<style>' +
                                                        'body{font-family:Arial,Helvetica,sans-serif;padding:20px;color:#222;}' +
                                                        'h1{font-size:18px;margin:0 0 12px;}' +
                                                        'table{width:100%;border-collapse:collapse;font-size:12px;}' +
                                                        'th,td{border:1px solid #444;padding:6px 8px;text-align:left;}' +
                                                        'thead{background:#17a2b8;color:#fff;}' +
                                                        '@media print{button{display:none;}}' +
                                                    '</style></head><body>' +
                                                    '<h1>Requisition Items — Not Yet Ordered</h1>' +
                                                    table.outerHTML +
                                                    '<script>window.onload=function(){window.print();};<\/script>' +
                                                    '</body></html>'
                                                );
                                                w.document.close();
                                            }

                                            function downloadPoRequisitionCsv() {
                                                var table = document.querySelector('#reqRefPanelBody table');
                                                if (!table) { alert('No table data to download.'); return; }
                                                var rows = [];
                                                table.querySelectorAll('tr').forEach(function (tr) {
                                                    var cells = [];
                                                    tr.querySelectorAll('th, td').forEach(function (cell) {
                                                        var text = (cell.innerText || cell.textContent || '').replace(/\s+/g, ' ').trim();
                                                        if (/[",\n]/.test(text)) text = '"' + text.replace(/"/g, '""') + '"';
                                                        cells.push(text);
                                                    });
                                                    if (cells.length) rows.push(cells.join(','));
                                                });
                                                var csv = '﻿' + rows.join('\r\n');
                                                var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                                                var url  = URL.createObjectURL(blob);
                                                var a    = document.createElement('a');
                                                a.href = url; a.download = 'Requisition_Items_Not_Yet_Ordered.csv';
                                                document.body.appendChild(a); a.click(); document.body.removeChild(a);
                                                setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
                                            }
                                        </script>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-white repeater"
                                                id="purchase_order_table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="col-sm-2">Description</th>
                                                        <th class="col-md-2">HSN No.</th>
                                                        <th class="col-md-2">Part No.</th>
                                                        {{-- <th class="col-md-2">Due Date</th> --}}
                                                        <th class="col-md-2">Quantity</th>
                                                        <th class="col-md-2">Unit</th>
                                                        <th class="col-md-2">Rate</th>
                                                        <th class="col-md-2">Discount</th>

                                                        <th>Amount</th>
                                                        <th>
                                                            <button type="button"
                                                                class="btn btn-sm btn-bg-colour font-18 mr-1"
                                                                id="add_more_btn" title="Add" data-repeater-create>
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Hidden counter for "Add More" JS — always starts at 0 (one blank row at index 0) --}}
                                                    <input type="hidden" id="i_id" value="0">

                                                    {{-- Item grid always opens empty (one blank row).
                                                         See the requisition reference panel above for pending items. --}}
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="id" class="form-control"
                                                                style="min-width:15px" readonly value="1">
                                                        </td>
                                                        <td class="reverse-label">
                                                            <select class="form-control mb-2 part_no_id select2"
                                                                name="addmore[0][part_no_id]" style="width:100%">
                                                                <option value="" default>Select Description</option>
                                                                @foreach ($dataOutputPartItem as $data)
                                                                    <option value="{{ $data['id'] }}"
                                                                        data-part-number="{{ $data['part_number'] }}">
                                                                        {{ $data['description'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control hsn_name" type="text"
                                                                style="min-width:100px" disabled>
                                                            <input type="hidden" class="form-control hsn_id"
                                                                name="addmore[0][hsn_id]" type="text"
                                                                style="min-width:100px">
                                                        </td>
                                                        <td>
                                                            <input class="form-control description"
                                                                name="addmore[0][description]" type="text"
                                                                style="min-width:100px">
                                                        </td>
                                                        <td>
                                                            <input class="form-control quantity"
                                                                name="addmore[0][quantity]" style="width:100%"
                                                                type="text">
                                                        </td>
                                                        <td>
                                                            <select class="form-control mb-2 unit"
                                                                name="addmore[0][unit]" style="min-width:100px">
                                                                <option value="" default>Select Unit</option>
                                                                @foreach ($dataOutputUnitMaster as $data)
                                                                    <option value="{{ $data['id'] }}">
                                                                        {{ $data['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control rate" name="addmore[0][rate]"
                                                                style="min-width:100px" type="text">
                                                        </td>
                                                        <td>
                                                            <select class="form-control discount"
                                                                name="addmore[0][discount]" style="width:80px">
                                                                @for($d = 0; $d <= 50; $d++)
                                                                    <option value="{{ $d }}">{{ $d }} %</option>
                                                                @endfor
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control total_amount"
                                                                name="addmore[0][amount]" readonly style="width:150px"
                                                                type="text">
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger font-18 ml-2 remove-row"
                                                                title="Delete" data-repeater-delete>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <tfoot>
                                                    <tr class="grand-total-row">
                                                        <td colspan="8" class="text-end"><strong>Grand Total:</strong>
                                                        </td>
                                                        <td colspan="4"> <input type="text"
                                                                id="po_grand_total_amount" name="po_grand_total_amount"
                                                                class="form-control" readonly> </td>
                                                    </tr>
                                                </tfoot>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Transport-Dispatch <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="transport_dispatch"
                                                value="" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Remark <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="note"></textarea>
                                        </div>
                                    </div>


                                </div>
                                <div class="login-btn-inner">
                                    <div class="row">
                                        <div class="col-lg-5"></div>
                                        <div class="col-lg-7">
                                            <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('list-purchase') }}" class="btn btn-white"
                                                    style="margin-bottom:50px">Cancel</a>
                                                <button class="btn btn-sm btn-primary login-submit-cs" type="submit"
                                                    style="margin-bottom:50px">Save
                                                    Data</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    @push('scripts')
                        <!-- ========== 1) TAX CALCULATION SCRIPT ========== -->
                        {{-- <script>
                        $(document).ready(function() {
 $(".select2").select2({ width: '100%' });
                            $('#tax_id').on('change', function() {

                                let taxId = $(this).val();

                                if (!taxId) {
                                    $('#po_grand_total_amount').val("0.00");
                                    return;
                                }

                                $.ajax({
                                    url: "{{ route('get-tax-value') }}",
                                    type: "GET",
                                    data: {
                                        tax_id: taxId
                                    },
                                    success: function(response) {

                                        let taxRate = parseFloat(response.tax_value) || 0;
                                        calculateGrandTotal(taxRate);
                                    }
                                });
                            });

                            $(document).on('keyup change', '.quantity, .rate, .discount', function() {

                                let taxRate = parseFloat($('#tax_id option:selected').data('tax-rate')) || 0;
                                calculateGrandTotal(taxRate);
                            });

                            function calculateGrandTotal(taxRate) {

                                let grandTotal = 0;

                                $('#purchase_order_table tbody tr').each(function() {

                                    let qty = parseFloat($(this).find('.quantity').val()) || 0;
                                    let rate = parseFloat($(this).find('.rate').val()) || 0;
                                    let disc = parseFloat($(this).find('.discount').val()) || 0;

                                    let baseAmount = qty * rate;
                                    let discountAmount = (baseAmount * disc) / 100;
                                    let afterDiscount = baseAmount - discountAmount;

                                    let taxAmount = (afterDiscount * taxRate) / 100;
                                    let rowTotal = afterDiscount + taxAmount;

                                    $(this).find('.total_amount').val(rowTotal.toFixed(2));
                                    grandTotal += rowTotal;
                                });

                                $('#po_grand_total_amount').val(grandTotal.toFixed(2));
                            }

                        });
                    </script> --}}
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                        <script>
                            $(document).ready(function() {

                                $(document).on('keyup change', '.quantity, .rate, .discount, #tax_id', function() {
                                    calculateGrandTotal();
                                });

                                function calculateGrandTotal() {

                                    let totalWithoutTax = 0;

                                    $('#purchase_order_table tbody tr').each(function() {

                                        let qty = parseFloat($(this).find('.quantity').val()) || 0;
                                        let rate = parseFloat($(this).find('.rate').val()) || 0;
                                        let discount = parseFloat($(this).find('.discount').val()) || 0;

                                        let baseAmount = qty * rate;
                                        let discountAmount = (baseAmount * discount) / 100;
                                        let afterDiscount = baseAmount - discountAmount;

                                        $(this).find('.total_amount').val(afterDiscount.toFixed(2));

                                        totalWithoutTax += afterDiscount;
                                    });

                                    let taxRate = parseFloat($('#tax_id option:selected').data('tax-rate')) || 0;
                                    let taxAmount = (totalWithoutTax * taxRate) / 100;
                                    let finalTotal = totalWithoutTax + taxAmount;

                                    $('#po_grand_total_amount').val(finalTotal.toFixed(2));


                                    let remaining = parseFloat($('#remaining_amount').val()) || 0;

                                    if (finalTotal > remaining) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'PO amount exceeds remaining estimation amount!',
                                            confirmButtonText: 'OK'
                                        });

                                        $('.login-submit-cs').prop('disabled', true);
                                    } else {
                                        $('.login-submit-cs').prop('disabled', false);
                                    }

                                    // if (finalTotal > remaining) {
                                    //     alert("PO amount exceeds remaining estimation amount!");
                                    //     $('.login-submit-cs').prop('disabled', true);
                                    // } else {
                                    //     $('.login-submit-cs').prop('disabled', false);
                                    // }
                                }
                            });
                        </script>


                        <!-- ========== 2) NO CONFLICT SCRIPT ========== -->


                        <!-- ========== 3) FORM VALIDATION & ADD ROW SCRIPT ========== -->
                        <script>
                            $(document).ready(function() {

                                var validator = $("#forms").validate({

                                    ignore: [],
                                    rules: {

                                        vendor_type_id: {
                                            required: true
                                        },
                                        contact_person_name: {
                                            required: true
                                        },
                                        contact_person_number: {
                                            required: true
                                        },
                                        tax_type: {
                                            required: true
                                        },
                                        tax_id: {
                                            required: true
                                        },
                                        invoice_date: {
                                            required: true
                                        },
                                        payment_terms: {
                                            required: true
                                        },
                                        transport_dispatch: {
                                            required: true
                                        },
                                        note: {
                                            required: true
                                        },

                                        'addmore[0][part_no_id]': {
                                            required: true
                                        },
                                        'addmore[0][discount]': {
                                            required: true
                                        },
                                        'addmore[0][quantity]': {
                                            required: true
                                        },
                                        'addmore[0][hsn_id]': {
                                            required: true,
                                            number: true
                                        },
                                        'addmore[0][rate]': {
                                            required: true,
                                            number: true
                                        },
                                        'addmore[0][amount]': {
                                            required: true
                                        },
                                    },

                                    messages: {
                                        vendor_type_id: {
                                            required: "Please select Vendor Type"
                                        },
                                        contact_person_name: {
                                            required: "Enter Contact Person Name"
                                        },
                                        contact_person_number: {
                                            required: "Enter Contact Person Number"
                                        },
                                        tax_type: {
                                            required: "Select Tax Type"
                                        },
                                        tax_id: {
                                            required: "Select Tax"
                                        },
                                        invoice_date: {
                                            required: "Please select Purchase Order Date"
                                        },
                                        payment_terms: {
                                            required: "Enter Payment Terms"
                                        },
                                        transport_dispatch: {
                                            required: "Enter Transport/Dispatch field"
                                        },
                                        note: {
                                            required: "Enter Remark"
                                        },

                                        'addmore[0][part_no_id]': {
                                            required: "Please Enter the Part Number"
                                        },
                                        'addmore[0][discount]': {
                                            required: "Please Enter the Discount"
                                        },
                                        'addmore[0][quantity]': {
                                            required: "Please Enter the Quantity"
                                        },
                                        'addmore[0][rate]': {
                                            required: "Please Enter the Rate"
                                        },
                                        'addmore[0][amount]': {
                                            required: "Please Enter the Amount"
                                        },
                                    },

                                    errorPlacement: function(error, element) {

                                        if (element.hasClass("select2-hidden-accessible")) {
                                            var select2Container = element.next('.select2');
                                            error.insertAfter(select2Container);
                                        } else if (
                                            element.hasClass("part_no_id") ||
                                            element.hasClass("discount") ||
                                            element.hasClass("quantity") ||
                                            element.hasClass("unit") ||
                                            element.hasClass("rate") ||
                                            element.hasClass("total_amount")
                                        ) {
                                            error.insertAfter(element);
                                        } else {
                                            error.insertAfter(element);
                                        }
                                    }

                                });

                                $(document).on('change', '.part_no_id', function() {
                                    if ($(this).val()) $(this).valid();
                                });

                                function initializeValidation(context) {
                                    $(context).find('.part_no_id').rules("add", {
                                        required: true
                                    });
                                    $(context).find('.discount').rules("add", {
                                        required: true
                                    });
                                    $(context).find('.quantity').rules("add", {
                                        required: true,
                                        number: true,
                                        min: 0.001
                                    });
                                    $(context).find('.unit').rules("add", {
                                        required: true
                                    });
                                    $(context).find('.rate').rules("add", {
                                        required: true,
                                        number: true
                                    });
                                    $(context).find('.total_amount').rules("add", {
                                        required: true
                                    });
                                }
                                $('.part_no_id').select2({
                                    width: '100%'
                                });
                                $("#add_more_btn").click(function() {

                                    var i_count = parseInt($('#i_id').val()) || 0;
                                    var i = i_count + 1;
                                    $('#i_id').val(i);

                                    var newRow = `
                <tr>
                    <td>
                <input type="text" name="id" class="form-control" style="min-width:15px" readonly value="${i + 1}"> <!-- This will start numbering from 2 -->
            </td>
                      <td class="reverse-label">
                    <select class="form-control part_no_id select2 mb-2" name="addmore[${i}][part_no_id]" id="" required style="width:100%">
                        <option value="" default>Select Description</option>
                        @foreach ($dataOutputPartItem as $data)
                            <option value="{{ $data['id'] }}" data-part-number="{{ $data['part_number'] }}">{{ $data['description'] }}</option>
                        @endforeach
                    </select>
                </td>
                      <td>
                        <input class="form-control hsn_name"  type="text" style="min-width:80px" disabled>
                             <input type="hidden" class="form-control hsn_id" name="addmore[${i}][hsn_id]" type="text" style="min-width:80px">
                        </td>
                    <td>
                        <input class="form-control description" name="addmore[${i}][description]" type="text" style="min-width:80px">
                    </td>
                    
                    <td>
                        <input class="form-control quantity" name="addmore[${i}][quantity]" style="width:100%" type="text" required>
                    </td>
                  
                   <td>
                             <select class="form-control mb-2 unit" name="addmore[${i}][unit]" required style="width:100%">
                                <option value="" default>Select Unit</option>
                                @foreach ($dataOutputUnitMaster as $data)
                                    <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                @endforeach
                            </select>
                        </td>
                    

                    <td>
                        <input class="form-control rate" name="addmore[${i}][rate]" style="min-width:100px" type="text" required>
                    </td>
                     <td>
                                       <select class="form-control discount" name="addmore[${i}][discount]"  style="width:80px">
                                                <option value="0">0 %</option>
                                                <option value="1">1 %</option>
                                                <option value="2">2 %</option>
                                                <option value="3">3 %</option>
                                                <option value="4">4 %</option>
                                                <option value="5">5 %</option>
                                                <option value="6">6 %</option>
                                                <option value="7">7 %</option>
                                                <option value="8">8 %</option>
                                                <option value="9">9 %</option>
                                                <option value="10">10 %</option>
                                                <option value="11">11 %</option>
                                                <option value="12">12 %</option>
                                                <option value="13">13 %</option>
                                                <option value="14">14 %</option>
                                                <option value="15">15 %</option>
                                                <option value="16">16 %</option>
                                                <option value="17">17 %</option>
                                                <option value="18">18 %</option>
                                                <option value="19">19 %</option>
                                                <option value="20">20 %</option>
                                                <option value="21">21 %</option>
                                                <option value="22">22 %</option>
                                                <option value="23">23 %</option>
                                                <option value="24">24 %</option>
                                                <option value="25">25 %</option>
                                                <option value="26">26 %</option>
                                                <option value="27">27 %</option>
                                                <option value="28">28 %</option>
                                                <option value="29">29 %</option>
                                                <option value="30">30 %</option>
                                                <option value="31">31 %</option>
                                                <option value="32">32 %</option>
                                                <option value="33">33 %</option>
                                                <option value="34">34 %</option>
                                                <option value="35">35 %</option>
                                                <option value="36">36 %</option>
                                                <option value="37">37 %</option>
                                                <option value="38">38 %</option>
                                                <option value="39">39 %</option>
                                                <option value="40">40 %</option>
                                                <option value="41">41 %</option>
                                                <option value="42">42 %</option>
                                                <option value="43">43 %</option>
                                                <option value="44">44 %</option>
                                                <option value="45">45 %</option>
                                                <option value="46">46 %</option>
                                                <option value="47">47 %</option>
                                                <option value="48">48 %</option>
                                                <option value="49">49 %</option>
                                                <option value="50">50 %</option>
                                              </select>
                                    </td>
                    <td>
                        <input class="form-control total_amount" name="addmore[${i}][amount]" readonly style="width:150px" type="text" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete" data-repeater-delete>
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

                                    $("#purchase_order_table tbody").append(newRow);

                                    $("#purchase_order_table tbody tr:last .select2").select2({
                                        width: '100%'
                                    });

                                    validator.resetForm();
                                    initializeValidation($("#purchase_order_table tbody tr:last"));
                                });
                                $("#purchase_order_table tbody tr:last .select2").select2({
                                    width: '100%'
                                });
                                $(document).on("click", ".remove-row", function() {

                                    var i = parseInt($('#i_id').val()) - 1;
                                    $('#i_id').val(i);

                                    $(this).closest("tr").remove();
                                    validator.resetForm();
                                    calculateGrandTotal();
                                });

                                initializeValidation($("#purchase_order_table"));

                            });
                        </script>
                        <!-- ========== 5) HSN FETCH SCRIPT ========== -->
                        <script>
                            $(document).ready(function() {

                                function fetchHsn(partNoId, row) {
                                    if (!partNoId) return;
                                    $.ajax({
                                        url: '{{ route('get-hsn-for-part') }}',
                                        type: 'GET',
                                        data: { part_no_id: partNoId },
                                        success: function(response) {
                                            if (response.part && response.part.length > 0) {
                                                row.find('.hsn_name').val(response.part[0].name);
                                                row.find('.hsn_id').val(response.part[0].id);
                                            }
                                        }
                                    });
                                }

                                // When the user picks a part from the Description dropdown, push
                                // the matching part_number (data-part-number on the option) into
                                // the Part No. cell (.description input) of the same row.
                                function fillPartNumber($select) {
                                    var partNumber = $select.find('option:selected').data('part-number') || '';
                                    $select.closest('tr').find('.description').val(partNumber);
                                }

                                $(document).on('change', '.part_no_id', function(e) {
                                    var $select = $(this);
                                    fetchHsn($select.val(), $select.closest('tr'));
                                    fillPartNumber($select);
                                });

                                // Auto-fetch HSN + Part No. for pre-filled rows on page load
                                $('#purchase_order_table tbody tr').each(function() {
                                    var $row = $(this);
                                    var $select = $row.find('.part_no_id');
                                    var partId = $select.val();
                                    if (partId) {
                                        fetchHsn(partId, $row);
                                        // Only overwrite the Part No. cell when it's empty,
                                        // so any value loaded server-side wins on first paint.
                                        if (!$row.find('.description').val()) {
                                            fillPartNumber($select);
                                        }
                                    }
                                });

                                // Trigger initial amount calculation for pre-filled rows.
                                // Triggering 'keyup' on $(document) doesn't fire the delegated
                                // handler bound to '.quantity, .rate, .discount' because the
                                // event target wouldn't match. Trigger 'change' on the actual
                                // .quantity inputs so the bubbled event reaches the handler.
                                $('#purchase_order_table tbody tr .quantity').trigger('change');

                            });
                        </script>
                    @endpush
                @endsection
