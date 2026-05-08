{{--
    BOM Material Items Modal (Reusable Partial)
    =====================================================
    Required variables passed via @include:
      $mode              — 'design_edit' | 'estimation_edit' | 'view_only'
      $businessId        — int
      $businessDetailsId — int
      $designId          — int
      $bomSaveUrl        — route URL string (only needed for edit modes)

    Optional:
      $bomModalId        — string, unique modal id (default: 'bomMaterialItemsModal')
                           Use a unique value when including multiple modals on one page.
--}}
@php
    $isEditMode = in_array($mode, ['design_edit', 'estimation_edit']);
    $modalId    = $bomModalId ?? 'bomMaterialItemsModal';
    $tableId    = $modalId . 'Table';
    $saveUrl    = $bomSaveUrl ?? '';
@endphp

{{-- Inline CSS for the custom Part Item dropdown (same pattern as Store dept) --}}
<style>
.bom-custom-dropdown-{{ $modalId }} {
    position: relative;
    display: block;
}
.bom-dropdown-options-{{ $modalId }} {
    display: none;
    position: absolute;
    max-height: 280px;
    overflow-y: auto;
    border: 1px solid #ddd;
    background: #fff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    z-index: 9999;
    padding: 8px;
    border-radius: 4px;
    width: 100%;
    min-width: 260px;
}
.bom-dropdown-options-{{ $modalId }}.dropdown-opened {
    display: block !important;
}
.bom-dropdown-options-{{ $modalId }} .bom-search-box {
    margin-bottom: 6px;
    width: 100%;
    box-sizing: border-box;
}
.bom-dropdown-options-{{ $modalId }} .bom-option {
    padding: 5px 8px;
    cursor: pointer;
    font-size: 13px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.bom-dropdown-options-{{ $modalId }} .bom-option:hover {
    background: #f2f2f2;
}
.bom-dropdown-options-{{ $modalId }} .bom-no-results {
    padding: 5px 8px;
    color: #999;
    font-size: 13px;
}
/* Context header block (Excel-matching layout) */
.bom-context-header-{{ $modalId }} {
    border: 1px solid #dee2e6;
    margin-bottom: 12px;
    font-size: 13px;
}
/* Title row: 3-column flex so MATERIAL INDENT stays centered while Add More
   sits flush in the right slot (and a same-width spacer on the left keeps the
   center true). */
.bom-context-header-{{ $modalId }} .bom-ctx-title {
    background: #f8f9fa;
    color: #212529;
    font-weight: bold;
    font-size: 15px;
    padding: 6px 10px;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.bom-context-header-{{ $modalId }} .bom-ctx-title-text {
    flex: 1;
    text-align: center;
}
.bom-context-header-{{ $modalId }} .bom-ctx-title-spacer,
.bom-context-header-{{ $modalId }} .bom-ctx-title-action {
    flex: 0 0 110px;          /* same width on both sides keeps title visually centered */
    display: flex;
    align-items: center;
}
.bom-context-header-{{ $modalId }} .bom-ctx-title-action {
    justify-content: flex-end;
}
.bom-context-header-{{ $modalId }} .bom-ctx-row {
    display: flex;
    border-top: 1px solid #dee2e6;
}
.bom-context-header-{{ $modalId }} .bom-ctx-cell {
    flex: 1;
    padding: 5px 10px;
    border-right: 1px solid #dee2e6;
}
.bom-context-header-{{ $modalId }} .bom-ctx-cell:last-child {
    border-right: none;
}
.bom-context-header-{{ $modalId }} .bom-ctx-label {
    font-weight: 600;
    color: #555;
}
/* Totals footer row */
.bom-tfoot-totals td {
    background: #f8f9fa;
    font-weight: bold;
    border-top: 2px solid #dee2e6 !important;
}
/* Estimation amount line */
.bom-estimation-amount-{{ $modalId }} {
    text-align: right;
    font-weight: bold;
    font-size: 14px;
    margin-top: 8px;
    padding: 6px 10px;
    border-top: 1px solid #dee2e6;
}
/* Final Total Amount line */
.bom-final-total-{{ $modalId }} {
    text-align: right;
    font-weight: bold;
    font-size: 14px;
    padding: 4px 10px;
    color: #212529;
}
/* Delta line color helpers */
.bom-delta-positive { color: #e53935; } /* red: amount increasing */
.bom-delta-negative { color: #388e3c; } /* green: amount decreasing */
.bom-delta-zero     { color: #555555; } /* gray: no change */
/* Error/success banner and exceed warning banner — use custom classes so the
   global Bootstrap auto-close timer ($(".alert").alert('close') in
   footer.blade.php) does NOT remove these elements from the DOM before the
   user opens the modal. */
.bom-modal-error-msg {
    padding: 12px 16px;
    margin-bottom: 12px;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
    background-color: #f8d7da;
    color: #721c24;
}
.bom-modal-error-msg.bom-modal-success-msg {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}
.bom-modal-warning-msg {
    padding: 12px 16px;
    margin-top: 8px;
    border: 1px solid #ffeeba;
    border-radius: 4px;
    background-color: #fff3cd;
    color: #856404;
}
.bom-company-name-{{ $modalId }} {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    font-size: 16px;
    font-weight: 700;
    color: #212529;
    text-align: center;
    white-space: nowrap;
}
.bom-modal-header-{{ $modalId }} {
    position: relative;
    min-height: 58px;
}
/* Make the modal body scroll internally when many rows are added —
   header and footer stay pinned, only the rows area scrolls. */
#{{ $modalId }} .modal-dialog-scrollable {
    max-height: calc(100vh - 60px);
}
#{{ $modalId }} .modal-dialog-scrollable .modal-content {
    max-height: calc(100vh - 60px);
    overflow: hidden;
}
#{{ $modalId }} .modal-dialog-scrollable .modal-body {
    overflow-y: auto;
    max-height: calc(100vh - 220px); /* leaves room for header + footer */
}
/* Highlight rows whose product description didn't match any active part_item
   master row (i.e. NOT present in the store). Helps the user spot items they
   need to add to the part-item master or pick a similar existing one. */
#{{ $modalId }} tr.bom-row-not-in-store > td {
    background-color: #fff4e5 !important;
}
#{{ $modalId }} tr.bom-row-not-in-store .bom-part-input {
    background-color: #fff4e5 !important;
    border-color: #ffb74d !important;
}
#{{ $modalId }} .bom-not-in-store-badge {
    display: inline-block;
    margin-top: 3px;
    padding: 2px 7px;
    background: #ffb74d;
    color: #5d3a00;
    font-size: 10px;
    font-weight: 600;
    border-radius: 9px;
    line-height: 1.3;
    white-space: nowrap;
}
/* Inline per-field validation (jQuery-Validate-style) for BOM rows */
#{{ $modalId }} .bom-row-error {
    color: #dc3545;
    font-size: 11px;
    font-weight: 500;
    margin-top: 3px;
    display: block;
    line-height: 1.3;
}
#{{ $modalId }} .bom-input-error,
#{{ $modalId }} input.bom-input-error,
#{{ $modalId }} select.bom-input-error {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.1rem rgba(220, 53, 69, 0.25) !important;
}
</style>

<!-- BOM Material Items Modal -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog"
     aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document" style="max-width:95%;">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bom-modal-header-{{ $modalId }}">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    BOM Material Items
                    @if($mode === 'design_edit')
                        &mdash; <small>Design Department</small>
                    @elseif($mode === 'estimation_edit')
                        &mdash; <small>Estimation Department</small>
                    @else
                        &mdash; <small>View Only</small>
                    @endif
                </h5>
                <div class="bom-company-name-{{ $modalId }}">Shreerag Engineering Pvt. Ltd</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div id="{{ $modalId }}LoadingMsg" class="text-center" style="display:none;">
                    <span class="text-muted">Loading BOM items...</span>
                </div>
                <div id="{{ $modalId }}ErrorMsg" class="bom-modal-error-msg" style="display:none;"></div>

                {{-- Context header block (Excel MATERIAL INDENT layout) --}}
                <div class="bom-context-header-{{ $modalId }}" id="{{ $modalId }}ContextHeader" style="display:none;">
                    <div class="bom-ctx-title">
                        <span class="bom-ctx-title-spacer"></span>
                        <span class="bom-ctx-title-text" id="{{ $modalId }}CtxTitle">MATERIAL INDENT</span>
                        <span class="bom-ctx-title-action">
                            @if($isEditMode)
                                <button type="button" class="btn btn-success btn-sm"
                                        id="{{ $modalId }}AddMoreBtn">
                                    <i class="fa fa-plus"></i> Add More
                                </button>
                            @endif
                        </span>
                    </div>
                    <div class="bom-ctx-row">
                        <div class="bom-ctx-cell">
                            <span id="{{ $modalId }}CtxBomRef"></span>
                        </div>
                        <div class="bom-ctx-cell">
                            <span class="bom-ctx-label">Customer Name:-</span>
                            <span id="{{ $modalId }}CtxCustomer"></span>
                        </div>
                    </div>
                    <div class="bom-ctx-row">
                        <div class="bom-ctx-cell">
                            <span class="bom-ctx-label">DATE:</span>
                            <span id="{{ $modalId }}CtxDate"></span>
                        </div>
                        <div class="bom-ctx-cell">
                            <span class="bom-ctx-label">TOTAL QTY:</span>
                            <span id="{{ $modalId }}CtxTotalQty"></span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="{{ $tableId }}">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:50px;">Sr No</th>
                                <th>Product Description <span class="text-danger">*</span></th>
                                <th style="width:110px;">Length</th>
                                <th style="width:110px;">Quantity <span class="text-danger">*</span></th>
                                <th style="width:130px;">Total in mm</th>
                                <th style="width:140px;">Mtr for 01 Nos Trolley</th>
                                <th style="width:120px;">Rate <span class="text-danger">*</span></th>
                                <th style="width:130px;">Unit <span class="text-danger">*</span></th>
                                @if($isEditMode)
                                    <th style="width:60px;">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="{{ $modalId }}Tbody">
                            {{-- Rows are rendered/managed by JS --}}
                            <tr id="{{ $modalId }}EmptyRow">
                                <td colspan="{{ $isEditMode ? 9 : 8 }}" class="text-center text-muted">
                                    No BOM items found.
                                </td>
                            </tr>
                        </tbody>
                        {{-- Totals footer row --}}
                        <tfoot>
                            <tr class="bom-tfoot-totals" id="{{ $modalId }}TotalsRow">
                                <td colspan="3" class="text-right" style="font-weight:bold;">TOTAL</td>
                                <td id="{{ $modalId }}TotalQty" class="text-center">0</td>
                                <td id="{{ $modalId }}TotalInMm" class="text-center">0</td>
                                <td id="{{ $modalId }}TotalMtrTrolley" class="text-center">0</td>
                                <td></td>{{-- Rate column — no sum shown in totals row --}}
                                <td colspan="{{ $isEditMode ? 2 : 1 }}"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($mode === 'estimation_edit')
                {{-- estimation_edit: show CURRENT (saved) amount first, then NEW (live BOM) amount --}}
                <div class="bom-estimation-amount-{{ $modalId }}" id="{{ $modalId }}EstimationAmountBlock" style="display:none;">
                    <span class="text-muted" style="font-size:13px;">Current Estimation Amount:</span>
                    <strong><span id="{{ $modalId }}EstimationAmountVal">—</span></strong>
                </div>
                <div class="bom-final-total-{{ $modalId }}" id="{{ $modalId }}FinalTotalBlock">
                    <span style="font-size:13px;">New Estimation Amount (from BOM):</span>
                    <strong><span id="{{ $modalId }}FinalTotalVal" style="font-size:16px;">₹0.00</span></strong>
                </div>
                {{-- Delta line: difference between current saved amount and new BOM total --}}
                <div id="{{ $modalId }}DeltaBlock" style="display:none; text-align:right; font-size:12px; padding:2px 10px 4px;">
                    &#916; Will change by:
                    <span id="{{ $modalId }}DeltaVal"></span> on Save
                </div>
                @elseif($mode === 'view_only')
                {{-- view_only: static snapshot labels (no editing context needed) --}}
                <div class="bom-estimation-amount-{{ $modalId }}" id="{{ $modalId }}EstimationAmountBlock" style="display:none;">
                    <span class="text-muted" style="font-size:13px;">Saved Estimation Amount:</span>
                    <strong><span id="{{ $modalId }}EstimationAmountVal">—</span></strong>
                </div>
                <div class="bom-final-total-{{ $modalId }}" id="{{ $modalId }}FinalTotalBlock">
                    <span style="font-size:13px;">BOM Items Total:</span>
                    <strong><span id="{{ $modalId }}FinalTotalVal" style="font-size:16px;">₹0.00</span></strong>
                </div>
                @else
                {{-- design_edit and any other mode: generic labels --}}
                <div class="bom-final-total-{{ $modalId }}" id="{{ $modalId }}FinalTotalBlock">
                    Final Total Amount: <span id="{{ $modalId }}FinalTotalVal">₹0.00</span>
                </div>
                <div class="bom-estimation-amount-{{ $modalId }}" id="{{ $modalId }}EstimationAmountBlock" style="display:none;">
                    Estimation Amount: <span id="{{ $modalId }}EstimationAmountVal">—</span>
                </div>
                @endif

                @if($mode === 'estimation_edit')
                {{-- T-2026-035: Exceed warning + reason textarea inside the modal.
                     Alert uses InModal suffix (bomMaterialItemsModalExceedWarningInModal) to avoid duplicate-ID
                     collision with the main-form alert (#bomMaterialItemsModalExceedWarning).
                     Textarea uses canonical IDs (bomMaterialItemsModalExceedReasonBlock / bomMaterialItemsModalExceedReason /
                     bomMaterialItemsModalExceedReasonError) — these match the JS selectors (EXCEED_REASON_BLK, EXCEED_REASON,
                     EXCEED_REASON_ERR) so the Save handler and updateExceedUI() operate directly on the modal textarea.
                     No syncExceedToModal() needed — updateExceedUI() controls both the main-form alert and the modal alert directly. --}}
                <div id="bomMaterialItemsModalExceedWarningInModal"
                     class="bom-modal-warning-msg mt-2"
                     style="display:none;">
                    <strong><i class="fa fa-exclamation-triangle"></i> Amount Exceeds Business Limit</strong><br>
                    <span id="bomMaterialItemsModalExceedWarningTextInModal"></span><br>
                    Saving will automatically send an approval request to the Owner.
                </div>
                <div class="form-group mt-2" id="bomMaterialItemsModalExceedReasonBlock"
                     style="display:none;">
                    <label for="bomMaterialItemsModalExceedReason">
                        Reason for Excess Amount <span class="text-danger">*</span>
                        <small class="text-muted">(required when total exceeds business limit)</small>
                    </label>
                    <textarea class="form-control" id="bomMaterialItemsModalExceedReason"
                        rows="2"
                        placeholder="Explain why the BOM total exceeds the business limit..."
                        maxlength="1000"></textarea>
                    <span class="text-danger" id="bomMaterialItemsModalExceedReasonError" style="display:none;">
                        Please provide a reason for the exceeded amount.
                    </span>
                </div>
                @endif

            </div>

            <!-- Footer -->
            <div class="modal-footer">
                @if($isEditMode)
                    <button type="button" class="btn btn-primary" id="{{ $modalId }}SaveBtn">
                        Save BOM Items
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

{{-- Hidden context fields for JS --}}
<input type="hidden" id="{{ $modalId }}BusinessId"        value="{{ $businessId }}">
<input type="hidden" id="{{ $modalId }}BusinessDetailsId" value="{{ $businessDetailsId }}">
<input type="hidden" id="{{ $modalId }}DesignId"          value="{{ $designId }}">
<input type="hidden" id="{{ $modalId }}Mode"              value="{{ $mode }}">
<input type="hidden" id="{{ $modalId }}SaveUrl"           value="{{ $saveUrl }}">
<input type="hidden" id="{{ $modalId }}GetPartItemsUrl"   value="{{ route('common.get-part-items') }}">
<input type="hidden" id="{{ $modalId }}GetUnitsUrl"       value="{{ route('common.get-units') }}">
{{-- Business limit: populated from API context on modal open (estimation_edit only) --}}
<input type="hidden" id="{{ $modalId }}BusinessLimit"     value="">

@push('scripts')
<script>
(function ($) {
    'use strict';

    var MODAL_ID          = '#{{ $modalId }}';
    var TBODY_ID          = '#{{ $modalId }}Tbody';
    var EMPTY_ROW         = '#{{ $modalId }}EmptyRow';
    var LOADING_MSG       = '#{{ $modalId }}LoadingMsg';
    var ERROR_MSG         = '#{{ $modalId }}ErrorMsg';
    var ADD_BTN           = '#{{ $modalId }}AddMoreBtn';
    var SAVE_BTN          = '#{{ $modalId }}SaveBtn';
    var NS                = '{{ $modalId }}'; // namespace for class selectors

    // Context header selectors
    var CTX_HEADER        = '#{{ $modalId }}ContextHeader';
    var CTX_TITLE         = '#{{ $modalId }}CtxTitle';
    var CTX_BOM_REF       = '#{{ $modalId }}CtxBomRef';
    var CTX_CUSTOMER      = '#{{ $modalId }}CtxCustomer';
    var CTX_DATE          = '#{{ $modalId }}CtxDate';
    var CTX_TOTAL_QTY     = '#{{ $modalId }}CtxTotalQty';

    // Totals footer selectors
    var TOTAL_QTY         = '#{{ $modalId }}TotalQty';
    var TOTAL_IN_MM       = '#{{ $modalId }}TotalInMm';
    var TOTAL_MTR_TROLLEY = '#{{ $modalId }}TotalMtrTrolley';

    // Estimation amount selectors
    var EST_AMT_BLOCK     = '#{{ $modalId }}EstimationAmountBlock';
    var EST_AMT_VAL       = '#{{ $modalId }}EstimationAmountVal';

    // Final Total Amount selectors
    var FINAL_TOTAL_BLOCK = '#{{ $modalId }}FinalTotalBlock';
    var FINAL_TOTAL_VAL   = '#{{ $modalId }}FinalTotalVal';

    // Delta block selectors (estimation_edit only)
    var DELTA_BLOCK       = '#{{ $modalId }}DeltaBlock';
    var DELTA_VAL         = '#{{ $modalId }}DeltaVal';

    // Exceed flow selectors (estimation_edit only)
    var EXCEED_WARNING    = '#{{ $modalId }}ExceedWarning';
    var EXCEED_WARN_TEXT  = '#{{ $modalId }}ExceedWarningText';
    var EXCEED_REASON_BLK = '#{{ $modalId }}ExceedReasonBlock';
    var EXCEED_REASON     = '#{{ $modalId }}ExceedReason';
    var EXCEED_REASON_ERR = '#{{ $modalId }}ExceedReasonError';
    var BIZ_LIMIT_INPUT   = '#{{ $modalId }}BusinessLimit';

    var businessId        = parseInt($('#{{ $modalId }}BusinessId').val(), 10);
    var businessDetailsId = parseInt($('#{{ $modalId }}BusinessDetailsId').val(), 10);
    var designId          = parseInt($('#{{ $modalId }}DesignId').val(), 10);
    var mode              = $('#{{ $modalId }}Mode').val();
    var saveUrl           = $('#{{ $modalId }}SaveUrl').val();
    var getPartItemsUrl   = $('#{{ $modalId }}GetPartItemsUrl').val();
    var getUnitsUrl       = $('#{{ $modalId }}GetUnitsUrl').val();
    var isEditMode        = (mode === 'design_edit' || mode === 'estimation_edit');
    var isEstimationEdit  = (mode === 'estimation_edit');

    // Runtime state for exceed check (populated from context API response)
    var _businessLimit            = null; // float or null
    // Saved (current) estimation amount used for delta calculation (estimation_edit only)
    var _currentEstimationAmount  = null; // float or null

    var rowCounter  = 0;    // for unique row keys
    var deletedIds  = [];   // ids to soft-delete on save
    var unitOptions = [];   // [{id, name}] cached on modal open

    // ----------------------------------------------------------------
    // RECALCULATE TOTALS (live, called after any row add/remove/edit)
    // Also computes Final Total Amount = SUM(rate × quantity).
    // ----------------------------------------------------------------
    function recalculateTotals() {
        var totalQty        = 0;
        var totalInMm       = 0;
        var totalMtrTrolley = 0;
        var finalTotal      = 0;

        $(TBODY_ID).find('tr[data-row-idx]').each(function () {
            var $row = $(this);
            // Skip rows locally marked for deletion (they are removed from DOM immediately,
            // but this guard is belt-and-suspenders)
            if ($row.data('deleted')) return;

            if (isEditMode) {
                var qty  = parseFloat($row.find('.bom-quantity').val())    || 0;
                var rate = parseFloat($row.find('.bom-rate').val())         || 0;
                totalQty        += qty;
                totalInMm       += parseFloat($row.find('.bom-total-mm').val())    || 0;
                totalMtrTrolley += parseFloat($row.find('.bom-mtr-trolley').val()) || 0;
                finalTotal      += rate * qty;
            } else {
                // view_only: read from td text content (set in buildRow)
                // Column order: Sr No(0) | Product Desc(1) | Length(2) | Quantity(3) | Total in mm(4) | Mtr(5) | Rate(6) | Unit(7)
                var qty  = parseFloat($row.find('td').eq(3).text()) || 0;
                var rate = parseFloat($row.find('td').eq(6).text()) || 0;
                totalQty        += qty;
                totalInMm       += parseFloat($row.find('td').eq(4).text()) || 0;
                totalMtrTrolley += parseFloat($row.find('td').eq(5).text()) || 0;
                finalTotal      += rate * qty;
            }
        });

        // Format: strip trailing zeros but keep at most 3 decimal places
        function fmt(n) {
            if (n === 0) return '0';
            var s = n.toFixed(3);
            return s.replace(/\.?0+$/, '');
        }

        $(TOTAL_QTY).text(fmt(totalQty));
        $(TOTAL_IN_MM).text(fmt(totalInMm));
        $(TOTAL_MTR_TROLLEY).text(fmt(totalMtrTrolley));
        $(FINAL_TOTAL_VAL).text(fmtInr(finalTotal));

        // Update exceed warning banner and delta line (estimation_edit only)
        if (isEstimationEdit) {
            updateExceedUI(finalTotal);
            updateDeltaUI(finalTotal);
        }
    }

    // ----------------------------------------------------------------
    // EXCEED WARNING UI (estimation_edit only)
    // Shows/hides the exceed warning banners (main form + modal) and reason textarea (modal only).
    // T-2026-035: textarea is now exclusively inside the modal; main form shows alert only.
    // ----------------------------------------------------------------
    function updateExceedUI(finalTotal) {
        if (!isEstimationEdit || _businessLimit === null) return;

        if (finalTotal > _businessLimit) {
            var warningText = 'Final Total ' + fmtInr(finalTotal)
                + ' exceeds Business Limit ' + fmtInr(_businessLimit)
                + ' (available limit: ' + fmtInr(_businessLimit) + ').';
            // Main form alert
            $('#bomMaterialItemsModalExceedWarningText').text(warningText);
            $(EXCEED_WARNING).show();
            // Modal alert (InModal suffix to avoid duplicate ID with main form alert)
            $('#bomMaterialItemsModalExceedWarningTextInModal').text(warningText);
            $('#bomMaterialItemsModalExceedWarningInModal').show();
            // Reason textarea block (lives inside the modal, uses canonical IDs)
            $(EXCEED_REASON_BLK).show();
        } else {
            // Main form alert
            $(EXCEED_WARNING).hide();
            // Modal alert
            $('#bomMaterialItemsModalExceedWarningInModal').hide();
            // Reason block + error (modal)
            $(EXCEED_REASON_BLK).hide();
            $(EXCEED_REASON_ERR).hide();
        }
    }

    // ----------------------------------------------------------------
    // DELTA LINE UI (estimation_edit only)
    // Shows the change between the current (saved) estimation amount
    // and the live BOM total that will become the new estimation amount on Save.
    // Green = decreasing (good), Red = increasing (may exceed limit), Gray = no change.
    // ----------------------------------------------------------------
    function updateDeltaUI(finalTotal) {
        if (!isEstimationEdit || _currentEstimationAmount === null) {
            $(DELTA_BLOCK).hide();
            return;
        }
        var delta    = finalTotal - _currentEstimationAmount;
        var absDelta = Math.abs(delta);
        var sign     = delta > 0 ? '+' : (delta < 0 ? '-' : '');
        var cls      = delta > 0 ? 'bom-delta-positive' : (delta < 0 ? 'bom-delta-negative' : 'bom-delta-zero');
        // Format: "+₹X,XX,XXX.XX" / "-₹X,XX,XXX.XX" / "₹0.00"
        $(DELTA_VAL).text(sign + fmtInr(absDelta)).attr('class', cls);
        $(DELTA_BLOCK).show();
    }

    // fmtInr available outside recalculateTotals for updateExceedUI
    function fmtInr(n) {
        return '₹' + parseFloat((+n).toFixed(2)).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // ----------------------------------------------------------------
    // POPULATE CONTEXT HEADER FROM API response.context
    // Also stores business_limit for exceed detection (estimation_edit only).
    // ----------------------------------------------------------------
    function populateContext(context) {
        if (!context) return;

        $(CTX_TITLE).text(context.title || 'MATERIAL INDENT');
        $(CTX_BOM_REF).html('<strong>' + escHtml(context.bom_reference || '') + '</strong>');
        $(CTX_CUSTOMER).text(context.customer_name || '');
        $(CTX_DATE).text(context.date || '');
        $(CTX_TOTAL_QTY).text(context.total_qty !== null && context.total_qty !== undefined ? context.total_qty : '');
        $(CTX_HEADER).show();

        // Estimation amount
        if (context.estimation_amount !== null && context.estimation_amount !== undefined && context.estimation_amount !== '') {
            var formatted = '₹' + parseFloat(context.estimation_amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            $(EST_AMT_VAL).text(formatted);
            // Store for delta calculation (estimation_edit only)
            _currentEstimationAmount = parseFloat(context.estimation_amount) || 0;
        } else {
            $(EST_AMT_VAL).text('—'); // em dash for "not yet estimated"
            _currentEstimationAmount = 0; // treat no-estimation as 0 for delta calc
        }
        $(EST_AMT_BLOCK).show();

        // Store business limit for exceed detection (estimation_edit only)
        if (isEstimationEdit && context.business_limit !== null && context.business_limit !== undefined) {
            _businessLimit = parseFloat(context.business_limit);
            $(BIZ_LIMIT_INPUT).val(_businessLimit);
        }
    }

    // ----------------------------------------------------------------
    // LOAD UNIT MASTER (once per modal open)
    // ----------------------------------------------------------------
    function loadUnitOptions(callback) {
        if (unitOptions.length > 0) {
            if (callback) callback();
            return;
        }
        $.ajax({
            url: getUnitsUrl,
            type: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    unitOptions = response.units || [];
                }
                if (callback) callback();
            },
            error: function () {
                if (callback) callback();
            }
        });
    }

    // Build a <select> for Unit with pre-selected value
    function buildUnitSelect(selectedUnitId, disabled) {
        var html = '<select class="form-control form-control-sm bom-unit-select" ' + (disabled ? 'disabled' : '') + ' style="width:120px;">';
        html += '<option value="">-- Unit --</option>';
        $.each(unitOptions, function (i, u) {
            html += '<option value="' + escHtml(u.id) + '"' + (parseInt(u.id, 10) === parseInt(selectedUnitId, 10) ? ' selected' : '') + '>' + escHtml(u.name) + '</option>';
        });
        html += '</select>';
        return html;
    }

    // ----------------------------------------------------------------
    // CUSTOM PART ITEM DROPDOWN HELPERS (matching store dept pattern)
    // ----------------------------------------------------------------
    var _partDropState = { $menu: null, $dropdown: null };
    var _partSearchXhr = null;
    var _partSearchSeq = 0;

    function openPartDropdown($dropdown) {
        // Close any existing open dropdown in this modal
        closePartDropdown();

        var $menu   = $dropdown.find('.bom-dropdown-options-' + NS);
        var $input  = $dropdown.find('.bom-part-input');
        var $search = $menu.find('.bom-search-box');

        // Position the menu fixed (relative to viewport) so it escapes the
        // table-responsive horizontal-scroll container without being clipped.
        // We deliberately do NOT appendTo('body') — Bootstrap 4's modal focus
        // trap would then yank focus out of the search box, breaking typing.
        var offset = $input.offset();
        $menu.css({
            top:  (offset.top + $input.outerHeight()) + 'px',
            left: offset.left + 'px',
            width: Math.max($input.outerWidth(), 260) + 'px',
            position: 'fixed'
        }).addClass('dropdown-opened');

        _partDropState.$menu = $menu;
        _partDropState.$dropdown = $dropdown;

        // Attach the search handler DIRECTLY to the search input element now that it
        // lives in <body>.  Using a namespaced event (.bomSearch) so we can safely
        // remove exactly this handler on close without touching anything else.
        // This avoids any ambiguity with delegated selectors after the DOM move.
        $search.off('input.bomSearch keyup.bomSearch').on('input.bomSearch keyup.bomSearch', function () {
            var term      = $(this).val();
            var $drop     = _partDropState.$dropdown;
            if (!$drop) return;
            clearTimeout(_searchDebounceTimer);
            _searchDebounceTimer = setTimeout(function () {
                searchPartItems($drop, term);
            }, 250);
        });

        // Clear the search box and focus it.
        // The click handler (on .bom-part-input) calls searchPartItems('') immediately after
        // openPartDropdown(), so we don't trigger 'input' here (that would queue a duplicate
        // debounced AJAX call for the initial load).
        $search.val('').focus();
    }

    function closePartDropdown() {
        if (_partDropState.$menu) {
            // Remove the directly-attached search handler
            _partDropState.$menu.find('.bom-search-box').off('input.bomSearch keyup.bomSearch');
            // Reset positioning back to CSS defaults (menu was never moved out of its parent)
            _partDropState.$menu.removeClass('dropdown-opened').hide().css({position: '', top: '', left: '', width: ''});
        }
        _partDropState.$menu = null;
        _partDropState.$dropdown = null;
    }

    // Search part items via AJAX and populate the options list
    // NOTE: the menu (.bom-dropdown-options-NS) is appended to <body> while open,
    // so we must look for .bom-options-list inside _partDropState.$menu, NOT inside $dropdown.
    function searchPartItems($dropdown, searchTerm) {
        // Resolve the live options-list container from the tracked open menu
        var $menu = _partDropState.$menu;
        var $list = ($menu && $menu.length) ? $menu.find('.bom-options-list') : $dropdown.find('.bom-options-list');
        var requestSeq = ++_partSearchSeq;
        searchTerm = $.trim(searchTerm || '');

        if (_partSearchXhr && _partSearchXhr.readyState !== 4) {
            _partSearchXhr.abort();
        }

        $list.html('<div class="bom-no-results">Searching...</div>');

        _partSearchXhr = $.ajax({
            url: getPartItemsUrl,
            type: 'GET',
            data: { search: searchTerm },
            success: function (response) {
                if (requestSeq !== _partSearchSeq) return;
                $list.empty();
                if (response.status === 'success' && response.items && response.items.length > 0) {
                    $.each(response.items, function (i, item) {
                        $list.append(
                            $('<div>').addClass('bom-option')
                                .attr('data-id', item.id)
                                .attr('data-name', item.name)
                                .attr('data-rate', item.basic_rate !== null && item.basic_rate !== undefined ? item.basic_rate : '')
                                .text(item.name)
                        );
                    });
                } else {
                    $list.html('<div class="bom-no-results">No results found.</div>');
                }
            },
            error: function (xhr) {
                if (xhr && xhr.statusText === 'abort') return;
                if (requestSeq !== _partSearchSeq) return;
                $list.html('<div class="bom-no-results">Search failed.</div>');
            }
        });
    }

    // ----------------------------------------------------------------
    // BUILD A TABLE ROW
    // ----------------------------------------------------------------
    function buildRow(item) {
        rowCounter++;
        var idx         = rowCounter;
        var itemId      = item.id || '';
        var srNo        = item.serial_no || idx;
        var partItemId  = item.part_item_id || '';
        var partDesc    = item.product_description || '';
        var unitId      = item.unit_id || '';
        var unitText    = item.unit || '';
        var rateVal     = (item.rate !== null && item.rate !== undefined && item.rate !== '') ? item.rate : '';

        // "Not in store" = no part_item_id → row didn't match any active master part.
        // Shown in orange so the user can either add it to the master or pick a
        // similar existing item from the dropdown.
        var notInStore = !partItemId || parseInt(partItemId, 10) <= 0;
        var rowCls     = notInStore ? ' class="bom-row-not-in-store"' : '';
        var notInStoreBadge = notInStore
            ? '<span class="bom-not-in-store-badge" title="This item was not found in the part-item master">' +
                '<i class="fa fa-exclamation-triangle"></i> Not in store' +
              '</span>'
            : '';

        if (!isEditMode) {
            // View-only row: show plain text
            // Column order: Sr No | Product Desc | Length | Quantity | Total in mm | Mtr | Rate | Unit
            return '<tr data-row-idx="' + idx + '"' + rowCls + '>' +
                '<td>' + escHtml(srNo) + '</td>' +
                '<td>' + escHtml(partDesc) +
                    (notInStore ? '<br>' + notInStoreBadge : '') +
                '</td>' +
                '<td>' + escHtml(item.length !== null && item.length !== undefined ? item.length : '') + '</td>' +
                '<td>' + escHtml(item.quantity || '') + '</td>' +
                '<td>' + escHtml(item.total_in_mm !== null && item.total_in_mm !== undefined ? item.total_in_mm : '') + '</td>' +
                '<td>' + escHtml(item.mtr_for_01_nos_trolley !== null && item.mtr_for_01_nos_trolley !== undefined ? item.mtr_for_01_nos_trolley : '') + '</td>' +
                '<td>' + escHtml(rateVal) + '</td>' +
                '<td>' + escHtml(unitText) + '</td>' +
                '</tr>';
        }

        // Build Part Item custom dropdown — append the "Not in store" badge below the input
        var partDropHtml =
            '<div class="bom-custom-dropdown-' + NS + '">' +
                '<input type="hidden" class="bom-part-id" value="' + escHtml(partItemId) + '">' +
                '<input type="text" class="form-control form-control-sm bom-part-input" ' +
                    'value="' + escHtml(partDesc) + '" ' +
                    'placeholder="Search Part Item..." readonly style="cursor:pointer;background:#fff;">' +
                '<div class="bom-dropdown-options-' + NS + '">' +
                    '<input type="text" class="form-control form-control-sm bom-search-box" placeholder="Search...">' +
                    '<div class="bom-options-list"></div>' +
                '</div>' +
                (notInStore ? notInStoreBadge : '') +
            '</div>';

        // Build Unit select
        var unitSelectHtml = buildUnitSelect(unitId, false);

        // Edit mode row — includes Rate input
        return '<tr data-row-idx="' + idx + '" data-item-id="' + escHtml(itemId) + '"' + rowCls + '>' +
            '<td><input type="number" class="form-control form-control-sm bom-serial-no" value="' + escHtml(srNo) + '" min="1" style="width:60px;"></td>' +
            '<td>' + partDropHtml + '</td>' +
            '<td><input type="number" class="form-control form-control-sm bom-length" value="' + escHtml(item.length !== null && item.length !== undefined ? item.length : '') + '" step="0.001" placeholder="0.000"></td>' +
            '<td><input type="number" class="form-control form-control-sm bom-quantity" value="' + escHtml(item.quantity || '') + '" step="0.001" min="0.001" placeholder="0.000" required></td>' +
            '<td><input type="number" class="form-control form-control-sm bom-total-mm" value="' + escHtml(item.total_in_mm !== null && item.total_in_mm !== undefined ? item.total_in_mm : '') + '" step="0.001" placeholder="0.000"></td>' +
            '<td><input type="number" class="form-control form-control-sm bom-mtr-trolley" value="' + escHtml(item.mtr_for_01_nos_trolley !== null && item.mtr_for_01_nos_trolley !== undefined ? item.mtr_for_01_nos_trolley : '') + '" step="0.001" placeholder="0.000"></td>' +
            '<td><input type="number" class="form-control form-control-sm bom-rate" value="' + escHtml(rateVal) + '" step="0.001" min="0" placeholder="0.000"></td>' +
            '<td>' + unitSelectHtml + '</td>' +
            '<td><button type="button" class="btn btn-danger btn-sm bom-delete-row" title="Remove row"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';
    }

    function escHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ----------------------------------------------------------------
    // LOAD ITEMS VIA AJAX
    // ----------------------------------------------------------------
    function loadItems(fetchUrl) {
        $(LOADING_MSG).show();
        $(ERROR_MSG).hide();
        $(TBODY_ID).empty();
        $(CTX_HEADER).hide();
        $(EST_AMT_BLOCK).hide();
        deletedIds = [];
        rowCounter = 0;
        _businessLimit = null;
        _currentEstimationAmount = null;

        // Reset totals
        $(TOTAL_QTY).text('0');
        $(TOTAL_IN_MM).text('0');
        $(TOTAL_MTR_TROLLEY).text('0');
        $(FINAL_TOTAL_VAL).text('₹0.00');

        // Reset exceed UI and delta line
        if (isEstimationEdit) {
            $(EXCEED_WARNING).hide();
            $('#bomMaterialItemsModalExceedWarningInModal').hide();
            $(EXCEED_REASON_BLK).hide();
            $(EXCEED_REASON).val('');
            $(EXCEED_REASON_ERR).hide();
            $(DELTA_BLOCK).hide();
        }

        // Load unit options first, then fetch BOM items
        loadUnitOptions(function () {
            $.ajax({
                url: fetchUrl,
                type: 'GET',
                success: function (response) {
                    $(LOADING_MSG).hide();
                    if (response.status === 'success') {
                        // Populate context header
                        if (response.context) {
                            populateContext(response.context);
                        }

                        if (response.items && response.items.length > 0) {
                            var html = '';
                            $.each(response.items, function (i, item) {
                                html += buildRow(item);
                            });
                            $(TBODY_ID).html(html);
                        } else {
                            $(TBODY_ID).html(
                                '<tr><td colspan="{{ $isEditMode ? 9 : 8 }}" class="text-center text-muted">No BOM items found. Use "Add More" to add rows.</td></tr>'
                            );
                        }

                        // Compute totals after rendering (view_only: once; edit: initial load)
                        recalculateTotals();
                    } else {
                        $(ERROR_MSG).text(response.message || 'Failed to load BOM items.').show();
                    }
                },
                error: function () {
                    $(LOADING_MSG).hide();
                    $(ERROR_MSG).text('Network error while loading BOM items.').show();
                }
            });
        });
    }

    // ----------------------------------------------------------------
    // PART ITEM DROPDOWN — EVENT HANDLERS (scoped to modal)
    // ----------------------------------------------------------------

    // Open dropdown on input click
    $(document).on('click', MODAL_ID + ' .bom-part-input', function (e) {
        e.stopPropagation();
        var $dropdown = $(this).closest('.bom-custom-dropdown-' + NS);
        openPartDropdown($dropdown);
        // Load initial results (empty search = all)
        searchPartItems($dropdown, '');
    });

    // Search as user types — handler is attached directly to the search <input> element
    // inside openPartDropdown() (namespaced event: input.bomSearch / keyup.bomSearch) and
    // removed in closePartDropdown().  Using direct attachment avoids any ambiguity with
    // delegated selectors after the menu element is moved to <body>.
    var _searchDebounceTimer = null;

    // Select an option — only fills the Product Description; Rate stays as-is so the
    // Picking a Product Description from the dropdown — also auto-fills the Rate
    // input from the part-master's basic_rate (data-rate set by searchPartItems).
    // The Excel-import path already does this server-side; this handler covers
    // the manual-pick path so the UX matches.
    $(document).on('click', '.bom-dropdown-options-' + NS + ' .bom-option', function (e) {
        e.stopPropagation();
        var $option      = $(this);
        var selectedId   = $option.attr('data-id');
        var selectedName = $option.attr('data-name') || $option.text();
        var masterRate   = $option.attr('data-rate');   // may be '' or numeric

        var $dropdown = _partDropState.$dropdown;
        if (!$dropdown) {
            $dropdown = $option.closest('.bom-custom-dropdown-' + NS);
        }

        $dropdown.find('.bom-part-id').val(selectedId);
        var $partInput = $dropdown.find('.bom-part-input');
        $partInput.val(selectedName);

        // Clear inline validation error on the Product Description field
        if ($partInput.hasClass('bom-input-error')) {
            clearFieldError($partInput);
        }

        // Picking a real master part removes the "Not in store" highlight + badge
        // from this row.
        var $row = $dropdown.closest('tr');
        if ($row.hasClass('bom-row-not-in-store')) {
            $row.removeClass('bom-row-not-in-store');
            $dropdown.find('.bom-not-in-store-badge').remove();
        }

        // Auto-fill Rate from master basic_rate when:
        //   (a) the master has a numeric rate, AND
        //   (b) the row's Rate cell is currently empty / 0 (don't overwrite manual input)
        var rateNum = parseFloat(masterRate);
        if (!isNaN(rateNum) && rateNum > 0) {
            var $rateInput = $row.find('.bom-rate');
            if ($rateInput.length) {
                var current = parseFloat($rateInput.val());
                if (isNaN(current) || current === 0) {
                    $rateInput.val(rateNum);
                    // Also clear any inline error on the Rate field and recompute totals
                    if ($rateInput.hasClass('bom-input-error')) clearFieldError($rateInput);
                    if (typeof recalculateTotals === 'function') recalculateTotals();
                }
            }
        }

        closePartDropdown();
    });

    // Close dropdown on click outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.bom-dropdown-options-' + NS).length &&
            !$(e.target).closest('.bom-part-input').length) {
            closePartDropdown();
        }
    });

    // Reposition menu on viewport scroll/resize
    $(window).on('scroll resize', function () {
        if (_partDropState.$dropdown && _partDropState.$menu) {
            var $input = _partDropState.$dropdown.find('.bom-part-input');
            if ($input.length) {
                var offset = $input.offset();
                _partDropState.$menu.css({
                    top:  (offset.top + $input.outerHeight()) + 'px',
                    left: offset.left + 'px'
                });
            }
        }
    });

    // The modal body now scrolls internally (modal-dialog-scrollable). Reposition
    // the part-item dropdown menu so it tracks the input as the user scrolls
    // inside the modal — fixed-position elements don't follow inner scrollers.
    $(MODAL_ID).on('scroll', '.modal-body', function () {
        if (_partDropState.$dropdown && _partDropState.$menu) {
            var $input = _partDropState.$dropdown.find('.bom-part-input');
            if ($input.length) {
                var offset = $input.offset();
                _partDropState.$menu.css({
                    top:  (offset.top + $input.outerHeight()) + 'px',
                    left: offset.left + 'px'
                });
            }
        }
    });

    // ----------------------------------------------------------------
    // LIVE RECALC: triggered by qty/total_in_mm/mtr/rate field input/change
    // ----------------------------------------------------------------
    $(document).on('input change',
        MODAL_ID + ' .bom-quantity, ' +
        MODAL_ID + ' .bom-total-mm, ' +
        MODAL_ID + ' .bom-mtr-trolley, ' +
        MODAL_ID + ' .bom-rate',
        function () {
            recalculateTotals();
        }
    );

    // ----------------------------------------------------------------
    // ADD MORE ROW
    // ----------------------------------------------------------------
    $(document).on('click', ADD_BTN, function () {
        var existingRows = $(TBODY_ID).find('tr[data-row-idx]').length;
        // Ensure unit options loaded before building new row
        loadUnitOptions(function () {
            var newRow = buildRow({ serial_no: existingRows + 1 });
            // Remove empty-state row if present
            $(TBODY_ID).find('td[colspan]').closest('tr').remove();
            $(TBODY_ID).append(newRow);
            // Recalculate totals after adding row (new row has 0 values initially)
            recalculateTotals();
            // Items for the new row's dropdown are loaded on demand when the user
            // clicks the input (openPartDropdown → searchPartItems('')).
            // Pre-loading here is wasteful since the menu is hidden until clicked.
        });
    });

    // ----------------------------------------------------------------
    // DELETE ROW
    // ----------------------------------------------------------------
    $(document).on('click', MODAL_ID + ' .bom-delete-row', function () {
        var $row   = $(this).closest('tr');
        var itemId = $row.data('item-id');
        if (itemId && !isNaN(parseInt(itemId, 10)) && parseInt(itemId, 10) > 0) {
            deletedIds.push(parseInt(itemId, 10));
        }
        $row.remove();

        // If no rows left, show empty state
        if ($(TBODY_ID).find('tr[data-row-idx]').length === 0) {
            $(TBODY_ID).html(
                '<tr><td colspan="{{ $isEditMode ? 9 : 8 }}" class="text-center text-muted">No BOM items. Use "Add More" to add rows.</td></tr>'
            );
        }

        // Recalculate totals after removing row
        recalculateTotals();
    });

    // ----------------------------------------------------------------
    // INLINE FIELD VALIDATION (jQuery-Validate-style per-field errors)
    // ----------------------------------------------------------------
    function clearFieldError($field) {
        $field.removeClass('bom-input-error');
        $field.closest('td').find('.bom-row-error').remove();
    }

    function showFieldError($field, msg) {
        $field.addClass('bom-input-error');
        var $cell = $field.closest('td');
        var $existing = $cell.find('.bom-row-error');
        if ($existing.length === 0) {
            $cell.append($('<span>').addClass('bom-row-error').text(msg));
        } else {
            $existing.text(msg);
        }
    }

    function validateRow($row) {
        var ok = true;

        // T-2026-019: Product Description validation — accept the row if EITHER:
        //   (a) a valid part_item_id is set (matched to an existing master record), OR
        //   (b) the visible product description text is non-empty (auto-create path).
        // Rows imported from Excel that didn't match any tbl_part_item master arrive with
        // part_item_id empty but product_description filled; the backend will auto-create
        // the master record on save — so these rows are valid and must not be blocked here.
        var $partInput = $row.find('.bom-part-input');
        var partItemId = $.trim($row.find('.bom-part-id').val());
        var partDesc   = $.trim($partInput.val());
        clearFieldError($partInput);
        if ((partItemId === '' || parseInt(partItemId, 10) <= 0) && partDesc === '') {
            showFieldError($partInput, 'Product Description is required.');
            ok = false;
        }

        // Quantity — required, numeric, > 0
        var $qty   = $row.find('.bom-quantity');
        var qtyVal = $.trim($qty.val());
        clearFieldError($qty);
        if (qtyVal === '') {
            showFieldError($qty, 'Quantity is required.');
            ok = false;
        } else if (isNaN(parseFloat(qtyVal)) || parseFloat(qtyVal) <= 0) {
            showFieldError($qty, 'Must be a number greater than 0.');
            ok = false;
        }

        // Rate — required, numeric, >= 0 (allow 0 for items whose rate is unknown at upload time)
        var $rate   = $row.find('.bom-rate');
        var rateVal = $.trim($rate.val());
        clearFieldError($rate);
        if (rateVal === '') {
            showFieldError($rate, 'Rate is required.');
            ok = false;
        } else if (isNaN(parseFloat(rateVal)) || parseFloat(rateVal) < 0) {
            showFieldError($rate, 'Must be a number 0 or greater.');
            ok = false;
        }

        // Unit — optional; backend falls back to NOS (unit_id=1) when not selected.
        // We do NOT block save for missing unit so Excel rows without a unit column still save.
        // (No showFieldError for unit here — it was previously blocking "Not in store" rows.)

        return ok;
    }

    // Auto-clear inline errors as the user fixes each field
    $(document).on('input change',
        MODAL_ID + ' .bom-quantity, ' +
        MODAL_ID + ' .bom-rate, ' +
        MODAL_ID + ' .bom-unit-select',
        function () {
            if ($(this).hasClass('bom-input-error')) {
                clearFieldError($(this));
            }
        }
    );

    // ----------------------------------------------------------------
    // SAVE ITEMS
    // ----------------------------------------------------------------
    $(document).on('click', SAVE_BTN, function () {
        if (!isEditMode) return;

        var items   = [];
        var isValid = true;
        var $firstInvalid = null;

        // Run inline validation on every row first — collect results across ALL rows
        // (don't bail on the first error like the old banner did) so the user sees
        // every field that needs fixing in one pass.
        $(TBODY_ID).find('tr[data-row-idx]').each(function () {
            var $row = $(this);
            if (!validateRow($row)) {
                isValid = false;
                if (!$firstInvalid) {
                    $firstInvalid = $row.find('.bom-input-error').first();
                }
            }
        });

        if (!isValid) {
            $(ERROR_MSG).text('Please fix the highlighted fields before saving.').show();
            if ($firstInvalid && $firstInvalid.length) {
                // Scroll the modal so the first error is visible, then focus it.
                var $modalEl = $(MODAL_ID);
                var fieldTop = $firstInvalid.offset().top;
                var modalTop = $modalEl.offset().top;
                $modalEl.animate({ scrollTop: $modalEl.scrollTop() + (fieldTop - modalTop) - 80 }, 200);
                try { $firstInvalid.focus(); } catch (e) {}
            }
            return;
        }

        $(ERROR_MSG).hide();

        // Collect the (now-validated) row payloads
        $(TBODY_ID).find('tr[data-row-idx]').each(function (i) {
            var $row        = $(this);
            var itemId      = $row.data('item-id');
            var partItemId  = $.trim($row.find('.bom-part-id').val());
            var partDesc    = $.trim($row.find('.bom-part-input').val());
            var $unitSelect = $row.find('.bom-unit-select');
            var unitId      = $.trim($unitSelect.val());
            var unitText    = $unitSelect.find('option:selected').text();
            var quantity    = $.trim($row.find('.bom-quantity').val());

            var rateRaw = $row.find('.bom-rate').val();
            // T-2026-019: send 0 when no part_item_id is set so the backend auto-creates the PartItem
            var resolvedPartItemId = (partItemId !== '' && !isNaN(parseInt(partItemId, 10))) ? parseInt(partItemId, 10) : 0;
            items.push({
                id:                      (itemId && parseInt(itemId, 10) > 0) ? parseInt(itemId, 10) : null,
                serial_no:               parseInt($row.find('.bom-serial-no').val(), 10) || (i + 1),
                part_item_id:            resolvedPartItemId,
                product_description:     partDesc,
                length:                  $row.find('.bom-length').val() !== '' ? $row.find('.bom-length').val() : '',
                quantity:                quantity,
                total_in_mm:             $row.find('.bom-total-mm').val() !== '' ? $row.find('.bom-total-mm').val() : '',
                mtr_for_01_nos_trolley:  $row.find('.bom-mtr-trolley').val() !== '' ? $row.find('.bom-mtr-trolley').val() : '',
                rate:                    rateRaw !== '' ? rateRaw : '',
                unit_id:                 parseInt(unitId, 10),
                unit:                    unitText !== '-- Unit --' ? unitText : '',
            });
        });

        // Exceed-reason validation (estimation_edit only): required when total > limit
        var exceedReason = null;
        if (isEstimationEdit && _businessLimit !== null) {
            // Compute current final total from DOM (authoritative pre-save value)
            var currentFinalTotal = 0;
            $(TBODY_ID).find('tr[data-row-idx]').each(function () {
                var qty  = parseFloat($(this).find('.bom-quantity').val()) || 0;
                var rate = parseFloat($(this).find('.bom-rate').val())     || 0;
                currentFinalTotal += rate * qty;
            });
            if (currentFinalTotal > _businessLimit) {
                var reason = $.trim($(EXCEED_REASON).val());
                if (reason === '') {
                    $(EXCEED_REASON_ERR).show();
                    $(EXCEED_REASON).focus();
                    return;
                }
                $(EXCEED_REASON_ERR).hide();
                exceedReason = reason;
            }
        }

        var payload = {
            _token:              '{{ csrf_token() }}',
            business_id:         businessId,
            business_details_id: businessDetailsId,
            design_id:           designId,
            items:               items,
            deleted_ids:         deletedIds,
        };

        // Include exceed_reason in payload when provided
        if (exceedReason !== null) {
            payload.exceed_reason = exceedReason;
        }

        $(SAVE_BTN).prop('disabled', true).text('Saving...');

        $.ajax({
            url:         saveUrl,
            type:        'POST',
            contentType: 'application/json',
            data:        JSON.stringify(payload),
            success: function (response) {
                $(SAVE_BTN).prop('disabled', false).text('Save BOM Items');
                if (response.status === 'success') {
                    deletedIds = [];
                    // Re-render with returned items (unit options already loaded)
                    $(TBODY_ID).empty();
                    rowCounter = 0;
                    if (response.items && response.items.length > 0) {
                        var html = '';
                        $.each(response.items, function (i, item) {
                            html += buildRow(item);
                        });
                        $(TBODY_ID).html(html);
                    }
                    // Recalculate totals after save re-render
                    recalculateTotals();

                    // Reset exceed reason textarea after successful save
                    if (isEstimationEdit) {
                        $(EXCEED_REASON).val('');
                        $(EXCEED_REASON_ERR).hide();
                    }

                    // Show success message
                    var successMsg = response.message || 'BOM items saved successfully.';
                    $(ERROR_MSG).addClass('bom-modal-success-msg')
                        .text(successMsg).show();

                    // Auto-close the modal after the user sees the success banner.
                    // Skip this for the exceed-triggered case below — that path
                    // shows its own Swal and closes the modal on OK click.
                    var _exceedTriggeredFlag = isEstimationEdit && response.exceed_triggered === true;
                    if (!_exceedTriggeredFlag) {
                        setTimeout(function () {
                            $(ERROR_MSG).hide().removeClass('bom-modal-success-msg');
                            $(MODAL_ID).modal('hide');
                        }, 1500);
                    } else {
                        // Exceed flow: still hide the inline banner after the usual delay
                        // (the Swal handles modal close itself).
                        setTimeout(function () {
                            $(ERROR_MSG).hide().removeClass('bom-modal-success-msg');
                        }, 5000);
                    }

                    // Dispatch custom event so the parent estimation form can:
                    //   (a) update the readonly Total Estimation Amount field
                    //   (b) react to exceed_triggered if needed
                    if (isEstimationEdit) {
                        var bomFinalTotal  = response.bom_final_total  !== undefined ? parseFloat(response.bom_final_total)  : null;
                        var exceedTriggered = response.exceed_triggered !== undefined ? response.exceed_triggered : false;

                        // Update parent form's Total Estimation Amount field (if present on same page)
                        if (bomFinalTotal !== null && !isNaN(bomFinalTotal)) {
                            $('#total_estimation_amount').val(bomFinalTotal.toFixed(2));
                        }

                        // Dispatch window event for any other listeners
                        if (typeof window.dispatchEvent === 'function') {
                            window.dispatchEvent(new CustomEvent('bom-saved', {
                                detail: {
                                    businessDetailsId: businessDetailsId,
                                    bomFinalTotal:     bomFinalTotal,
                                    businessLimit:     _businessLimit,
                                    exceedTriggered:   exceedTriggered,
                                    message:           response.message
                                }
                            }));
                        }

                        // Show exceed alert when owner approval was triggered.
                        // Closing the BOM modal once the user dismisses the alert so
                        // they aren't left staring at the same modal after the
                        // approval request has already been sent.
                        if (exceedTriggered) {
                            setTimeout(function () {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon:  'warning',
                                        title: 'Approval Request Sent',
                                        text:  response.message || 'BOM Final Total exceeds Business Limit. Approval request sent to Owner.',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        $(MODAL_ID).modal('hide');
                                    });
                                } else {
                                    alert(response.message || 'BOM Final Total exceeds Business Limit. Approval request sent to Owner.');
                                    $(MODAL_ID).modal('hide');
                                }
                            }, 100);
                        }
                    }

                    // T-2026-007: For design_edit mode, dispatch a lightweight bom-saved event
                    // so the design upload form can update its hasBomItems flag without a page reload.
                    if (!isEstimationEdit && typeof window.dispatchEvent === 'function') {
                        var savedItemCount = response.items ? response.items.length : 0;
                        window.dispatchEvent(new CustomEvent('bom-saved', {
                            detail: {
                                businessDetailsId: businessDetailsId,
                                itemCount:         savedItemCount
                            }
                        }));
                    }
                } else {
                    $(ERROR_MSG).text(response.message || 'Failed to save BOM items.').show();
                }
            },
            error: function (xhr) {
                $(SAVE_BTN).prop('disabled', false).text('Save BOM Items');
                var msg = 'Network error while saving BOM items.';
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if (resp.message) { msg = resp.message; }
                } catch (e) {}
                $(ERROR_MSG).text(msg).show();
            }
        });
    });

    // ----------------------------------------------------------------
    // EXPOSE OPEN FUNCTION ON WINDOW (called by page-level buttons)
    // window.openBomModal_<modalId>(fetchUrl)
    // ----------------------------------------------------------------
    var fnName = 'openBomModal_{{ $modalId }}';
    window[fnName] = function (fetchUrl) {
        loadItems(fetchUrl);
        $(MODAL_ID).modal('show');
    };

    // Reset on close
    $(MODAL_ID).on('hidden.bs.modal', function () {
        closePartDropdown();
        $(TBODY_ID).empty();
        $(ERROR_MSG).hide();
        $(LOADING_MSG).hide();
        $(CTX_HEADER).hide();
        $(EST_AMT_BLOCK).hide();
        $(TOTAL_QTY).text('0');
        $(TOTAL_IN_MM).text('0');
        $(TOTAL_MTR_TROLLEY).text('0');
        $(FINAL_TOTAL_VAL).text('₹0.00');
        // Reset exceed UI and delta line
        if (isEstimationEdit) {
            $(EXCEED_WARNING).hide();
            $('#bomMaterialItemsModalExceedWarningInModal').hide();
            $(EXCEED_REASON_BLK).hide();
            $(EXCEED_REASON).val('');
            $(EXCEED_REASON_ERR).hide();
            $(DELTA_BLOCK).hide();
        }
        _businessLimit = null;
        _currentEstimationAmount = null;
        deletedIds = [];
        rowCounter = 0;
        unitOptions = []; // reload fresh on next open
    });

})(jQuery);
</script>
@endpush
