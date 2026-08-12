{{--
    Read-only shortage row for bom-inventory-check.blade.php.

    Shared by BOTH shortage tables so their markup can never drift apart:
      1. #shortageTableBody      — rows still awaiting a requisition (is_sent_to_purchase != 1)
      2. #sentShortageBody       — the collapsed "Already Sent to Purchase" panel (= 1)

    Required data (inherited from the parent view, passed explicitly at each @include):
      $item         normalised shortage row (BOM-derived, production-request, or requisition_items)
      $i            zero-based index within ITS OWN table, for the Sr. cell
      $idPrefix     'shortage-row-' or 'sent-shortage-row-', keeps DOM ids unique across the two tables
      $requisitionSent, $trolleyQty, $computeMtrN, $fmt   (see parent view)
--}}
@php
    $isSent = isset($item->is_sent_to_purchase) && (int) $item->is_sent_to_purchase === 1;
    $rowClass = $isSent ? 'shortage-sent-row' : 'shortage-row';
    $reqItemId = $item->requisition_item_id ?? null;
    $unitNameSh = optional($item->unitMaster)->name ?? null;
    $mtrNSh = $computeMtrN(
        $item->mtr_for_01_nos_trolley ?? null,
        $item->required_quantity ?? null,
        $unitNameSh,
        $trolleyQty,
    );
@endphp
<tr class="{{ $rowClass }}" id="{{ $idPrefix }}{{ $reqItemId ?? 'bom-' . $i }}">
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
                <span class="badge-not-sent"><i class="fa fa-exclamation"></i>
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
    <td><strong class="qty-highlight">{{ number_format($item->shortage_quantity, 3) }}</strong>
    </td>
    <td>{{ $unitNameSh ?? ($item->unit_id ?? '—') }}</td>
    <td style="white-space:nowrap;">{!! $fmt($item->mtr_for_01_nos_trolley ?? null) !!}</td>
    <td style="white-space:nowrap;">{!! $fmt($mtrNSh) !!}</td>
    <td>{{ isset($item->rate) && $item->rate !== null ? number_format((float) $item->rate, 3) : '—' }}
    </td>
    <td></td>{{-- no action for BOM rows --}}
</tr>
