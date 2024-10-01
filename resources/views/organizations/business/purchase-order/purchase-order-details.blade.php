@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
    @if($purchaseOrder->purchase_status_from_purchase == '1126' &&  $purchaseOrder->business_status_id == '1123' || $purchaseOrder->business_status_id == '1126')

    <div class="" style="margin-bottom: 70px;">
        <a href="{{ route('accept-purchase-order', ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button data-toggle="tooltip"
                title="Accept Purchase Order" class="pd-setting-ed">Accept</button></a> &nbsp;
        &nbsp; &nbsp;
    </div>
    @else

    @endif


    
@endsection
