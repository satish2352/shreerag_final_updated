@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')

    <div style="display: flex; align-items: center; gap: 12px; padding-left: 17px; padding-bottom: 130px; margin-top: 20px;">
        <button data-toggle="tooltip" title="Print Purchase Order" onclick="printInvoice()" type="button"
            class="print-button"
            style="padding: 8px 15px; font-size: 16px; border-radius: 3px; border: 1px solid rgba(0, 0, 0, .12); background: #007bff; color: #fff; cursor: pointer;">Print</button>

        @if($purchaseOrder->purchase_status_from_owner == '1127' &&  $purchaseOrder->purchase_status_from_purchase == '1126')
            <a href="{{ route('finalize-and-submit-mail-to-vendor',  ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button data-toggle="tooltip"
                    title="Send Mail" class="accept-btn">Send Mail To
                    Vendor</button></a>
        @endif
    </div>
@endsection

