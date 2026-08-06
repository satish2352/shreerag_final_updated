@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>BOM Received For <span class="table-project-n">Purchase</span></h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control"
                                                    placeholder="Search Project Name / Product Name  / PO No.">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar"> --}}
                                        <thead>
                                            <tr>
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="bom" data-editable="false">Action</th>
                                                <th data-field="bom_file" data-editable="false">Requisition BOM</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="grand_total_amount" data-editable="false">Grand Total Amount
                                                </th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="total_estimation_amount" data-editable="false"> <span
                                                        style="margin-right:20px">Estimation
                                                        Amount</span></th>



                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>
                                                    <td>{{ ($data_output->currentPage() - 1) * $data_output->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td> {{ $data->created_at ? $data->created_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <div style="display: inline-block; align-items: center;">
                                                            {{--
                                                                T-2026-059 iteration 6 fix: $data->business_details_id
                                                                is aliased from the fragile `production.business_
                                                                details_id` LEFT JOIN (see AllListRepository::
                                                                getAllListMaterialReceivedForPurchase()) and is NULL
                                                                whenever this project has no `production` row yet — a
                                                                real, common state (the SAME root cause iteration 5
                                                                fixed for $trolleyQtyMap in AllListController.php).
                                                                base64_encode(null) => '', and Laravel's route()
                                                                helper drops a trailing empty parameter entirely
                                                                instead of encoding an empty segment, producing a URL
                                                                with only 1 path segment against a route that requires
                                                                2 ({requistition_id}/{business_details_id}) — a
                                                                genuine 404 (NotFoundHttpException) for the MAJORITY
                                                                of real "sent to Purchase" rows. $data->id (aliased
                                                                from `businesses_details.id`, confirmed via
                                                                PurchaseOrderController::index()/create() — both treat
                                                                the incoming business_details_id route param as
                                                                businesses_details.id, e.g.
                                                                BusinessDetails::where('businesses_details.id', ...))
                                                                is always present and is the value this route/
                                                                controller pair actually expects.
                                                            --}}
                                                            <a
                                                                href="{{ route('list-purchase-order', [
                                                                    base64_encode($data->requistition_id),
                                                                    base64_encode($data->id),
                                                                ]) }} "><button
                                                                    data-toggle="tooltip"
                                                                    title="Accept and Send For Purchase "
                                                                    class="btn btn-sm btn-bg-colour"> Accept and Send For
                                                                    Purchase </button></a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info"
                                                            data-toggle="modal"
                                                            data-target="#bomModal{{ $data->requistition_id }}">
                                                            <i class="fa fa-list"></i> View BOM
                                                        </button>
                                                    </td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td><b>{{ ucwords($data->grand_total_amount) }}</b></td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td><b>{{ ucwords($data->total_estimation_amount) }}</b></td>



                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        {{ $data_output->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- BOM Requisition Modals --}}
@php
    // T-2026-059 iteration 3 fix: never re-derive the trolley/unit-aware formula
    // locally — always go through BomTotalCalculator's effective-* helpers so a
    // LEGACY (is_qty_trolley_scaled=0) row is retroactively, correctly trolley-scaled
    // for display here too (same rule already applied on Store's own
    // list-material-sent-to-purchase.blade.php). This is the page where Purchase
    // reviews BOM shortages before creating a PO, so it must never show the old,
    // wrong, unscaled figure for a legacy sent requisition.
    $fmt_BOM = fn($n) => ($n === null || $n === '') ? '—' : rtrim(rtrim(number_format((float) $n, 3, '.', ''), '0'), '.');
@endphp
@foreach ($data_output as $data)
    @php
        $reqItems       = $requisitionItemsMap[$data->requistition_id] ?? collect();
        $poCreatedParts = $poCreatedPartsMap[$data->requistition_id] ?? [];
        $tQtyForModal   = $trolleyQtyMap[$data->requistition_id] ?? 1;
    @endphp
    <div class="modal fade" id="bomModal{{ $data->requistition_id }}" tabindex="-1" role="dialog"
         aria-labelledby="bomModalLabel{{ $data->requistition_id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width:95%; margin:30px auto;">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a3a6b; color:#fff;">
                    <h5 class="modal-title" id="bomModalLabel{{ $data->requistition_id }}">
                        <i class="fa fa-list"></i>
                        BOM Requisition — {{ ucwords($data->product_name) }}
                        <small style="font-size:13px; opacity:0.85;">({{ ucwords($data->project_name) }})</small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height:70vh; overflow-y:auto; padding:12px;">
                    @if($reqItems->isEmpty())
                        <div class="alert alert-warning">No BOM items found for this requisition.</div>
                    @else
                        <div style="overflow-x:auto; width:100%;">
                        <table class="table table-bordered table-hover table-sm" style="min-width:800px; width:100%; font-size:13px;">
                            <thead style="background:#1a3a6b; color:#fff;">
                                <tr>
                                    <th style="width:40px; white-space:nowrap;">Sr.</th>
                                    <th style="min-width:180px;">Product Description</th>
                                    <th style="white-space:nowrap;">Required Qty</th>
                                    <th style="white-space:nowrap;">Available Stock</th>
                                    <th style="white-space:nowrap;">Shortage Qty</th>
                                    <th style="white-space:nowrap;">Unit</th>
                                    <th style="white-space:nowrap;">Mtr for 01 Nos Trolley</th>
                                    <th style="white-space:nowrap;">Mtr/Nos for {{ $tQtyForModal }} Trolley(s)</th>
                                    <th style="white-space:nowrap;">Rate</th>
                                    <th style="white-space:nowrap;">Total</th>
                                    <th style="white-space:nowrap;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $modalTotal = 0; @endphp
                                @foreach($reqItems as $ri => $ritem)
                                    @php
                                        // T-2026-059 iteration 3 fix: reuse BomTotalCalculator's
                                        // effectiveRequiredQuantity()/effectiveShortageQuantity() (never a
                                        // locally duplicated formula) so a legacy (is_qty_trolley_scaled=0)
                                        // row is retroactively, correctly trolley-scaled here too. The
                                        // "Mtr/Nos for N Trolley(s)" column reuses the SAME effective
                                        // required-quantity value as the "Required Qty" column (single
                                        // source of truth — previously it was derived from a separate,
                                        // shortage-based closure that could disagree with "Required Qty").
                                        // Total's base MUST be the shortage quantity (what Purchase
                                        // actually needs to buy), matching list-material-sent-to-purchase.
                                        $unitNameBOM   = optional($ritem->unitMaster)->name ?? '';
                                        $bomIsScaled   = (int) ($ritem->is_qty_trolley_scaled ?? 0) === 1;
                                        $riReqQtyBOM   = \App\Support\BomTotalCalculator::effectiveRequiredQuantity(
                                            $ritem->required_quantity, $ritem->mtr_for_01_nos_trolley, $unitNameBOM,
                                            $ritem->trolley_qty, $tQtyForModal, $bomIsScaled
                                        );
                                        $riShortQtyBOM = \App\Support\BomTotalCalculator::effectiveShortageQuantity(
                                            $ritem->available_quantity, $ritem->required_quantity, $ritem->mtr_for_01_nos_trolley, $unitNameBOM,
                                            $ritem->trolley_qty, $tQtyForModal, $bomIsScaled
                                        );
                                        $mtr1BOM      = $ritem->mtr_for_01_nos_trolley ?? null;
                                        $rTotal       = $riShortQtyBOM * (float) ($ritem->rate ?? 0);
                                        $modalTotal  += $rTotal;
                                        $partStr      = (string)($ritem->part_item_id ?? '');
                                        $hasPO        = in_array($partStr, $poCreatedParts);
                                    @endphp
                                    <tr style="{{ $hasPO ? 'background:#f0fff4;' : '' }}">
                                        <td>{{ $ri + 1 }}</td>
                                        <td>{{ $ritem->product_description ?? (optional($ritem->partItem)->description ?? '—') }}</td>
                                        <td>{{ number_format($riReqQtyBOM, 3) }}</td>
                                        <td>{{ number_format($ritem->available_quantity, 3) }}</td>
                                        <td><strong style="color:#dc3545;">{{ number_format($riShortQtyBOM, 3) }}</strong></td>
                                        <td>{{ $unitNameBOM !== '' ? $unitNameBOM : '—' }}</td>
                                        <td>{{ $fmt_BOM($mtr1BOM) }}</td>
                                        <td>{{ number_format($riReqQtyBOM, 3) }}</td>
                                        <td>{{ $ritem->rate !== null ? number_format((float)$ritem->rate, 3) : '—' }}</td>
                                        <td><strong>{{ number_format($rTotal, 2) }}</strong></td>
                                        <td style="white-space:nowrap;">
                                            @if($hasPO)
                                                <span style="background:#28a745;color:#fff;padding:3px 10px;border-radius:12px;font-size:11px;">
                                                    <i class="fa fa-check-circle"></i> PO Created
                                                </span>
                                            @else
                                                <span style="background:#fd7e14;color:#fff;padding:3px 10px;border-radius:12px;font-size:11px;">
                                                    <i class="fa fa-exclamation-circle"></i> Not Ordered
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#f0f0f0; font-weight:700;">
                                    <td colspan="9" style="text-align:right; padding-right:12px;">Grand Total</td>
                                    <td><strong>{{ number_format($modalTotal, 2) }}</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    {{-- {{ }} auto-escapes the JSON's double quotes so they don't terminate the onclick attribute. --}}
                    <button type="button" class="btn btn-info btn-sm"
                            onclick="printBomReq('bomModal{{ $data->requistition_id }}', {{ json_encode(ucwords($data->product_name)) }}, {{ json_encode(ucwords($data->project_name ?? '')) }})">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <button type="button" class="btn btn-success btn-sm"
                            onclick="downloadBomReqCsv('bomModal{{ $data->requistition_id }}', {{ json_encode('BOM_Requisition_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $data->product_name) . '.csv') }})">
                        <i class="fa fa-download"></i> Download CSV
                    </button>
                    @if(!empty($data->bom_file))
                    <a href="{{ Config::get('FileConstant.REQUISITION_VIEW') }}{{ $data->bom_file }}"
                       class="btn btn-secondary btn-sm" target="_blank">
                        <i class="fa fa-file"></i> Original BOM File
                    </a>
                    @endif
                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Print + CSV-download helpers (mirror of the helpers on the Store BOM Requisition modal). --}}
<script>
    function printBomReq(modalId, productName, projectName) {
        var modal = document.getElementById(modalId);
        if (!modal) return;
        var tableHtml = (modal.querySelector('.modal-body table') || {}).outerHTML || '';
        if (!tableHtml) { alert('No table data to print.'); return; }
        var title = 'BOM Requisition — ' + (productName || '');
        var w = window.open('', '_blank', 'width=1100,height=800');
        if (!w) { alert('Please allow pop-ups for this site to print.'); return; }
        w.document.write(
            '<!doctype html><html><head><title>' + title + '</title>' +
            '<style>' +
                'body{font-family:Arial,Helvetica,sans-serif;padding:20px;color:#222;}' +
                'h1{font-size:20px;margin:0 0 4px;}' +
                'h2{font-size:14px;margin:0 0 16px;color:#555;font-weight:normal;}' +
                'table{width:100%;border-collapse:collapse;font-size:12px;}' +
                'th,td{border:1px solid #444;padding:6px 8px;text-align:left;}' +
                'thead{background:#1a3a6b;color:#fff;}' +
                'tfoot{font-weight:bold;background:#f0f0f0;}' +
                '@media print{button{display:none;}}' +
            '</style></head><body>' +
            '<h1>' + escapeHtmlBom(title) + '</h1>' +
            (projectName ? '<h2>' + escapeHtmlBom(projectName) + '</h2>' : '') +
            tableHtml +
            '<script>window.onload=function(){window.print();};<\/script>' +
            '</body></html>'
        );
        w.document.close();
    }

    function downloadBomReqCsv(modalId, filename) {
        var modal = document.getElementById(modalId);
        if (!modal) return;
        var table = modal.querySelector('.modal-body table');
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
        a.href = url; a.download = filename || 'BOM_Requisition.csv';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
        setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
    }

    function escapeHtmlBom(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
</script>
@endsection
