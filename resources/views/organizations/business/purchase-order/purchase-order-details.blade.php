@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
    <?php
// dd($purchaseOrder);
// die();
    ?>
    @if($purchaseOrder->purchase_status_from_purchase == 1126 && $purchaseOrder->finanace_store_receipt_status_id == NULL)
    <div class="" style="margin-bottom: 70px;">
        <a href="{{ route('accept-purchase-order', ['purchase_order_id' => $purchase_order_id, 'business_id' => $business_id]) }}"><button data-toggle="tooltip"
                title="Accept Purchase Order" class="pd-setting-ed">Accept</button></a> &nbsp;
        &nbsp; &nbsp;
    </div>
    @endif


    
@endsection
