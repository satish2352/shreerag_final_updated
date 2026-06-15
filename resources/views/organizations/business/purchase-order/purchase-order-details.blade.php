@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')

    <div style="display: flex; align-items: center; gap: 12px; padding-left: 20px; padding-bottom: 100px;">
        <button data-toggle="tooltip" title="Print Purchase Order" onclick="printInvoice()" type="button"
            class="print-button"
            style="padding: 8px 15px; font-size: 16px; border-radius: 3px; border: 1px solid rgba(0, 0, 0, .12); background: #007bff; color: #fff; cursor: pointer;">Print</button>

        @if ($purchaseOrder->purchase_status_from_owner != 1127)
            <a
                href="{{ route('accept-purchase-order', ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button
                    data-toggle="tooltip" title="Accept Purchase Order" class="accept-btn">Accept</button></a>

            <a
                href="{{ route('rejected-purchase-order', ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button
                    data-toggle="tooltip" title="Rejected Purchase Order" class="reject-btn">Reject</button></a>
        @endif
    </div>
@endsection
