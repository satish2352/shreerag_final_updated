@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
   
    @if($purchaseOrder->purchase_status_from_owner == 1127)

   
    @else
    <div class="" style="padding-bottom: 100px; padding-left: 20px; ">
        <a href="{{ route('accept-purchase-order', ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button data-toggle="tooltip"
                title="Accept Purchase Order" class="accept-btn">Accept</button></a> &nbsp;
        &nbsp; &nbsp;

        <a href="{{ route('rejected-purchase-order', ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button data-toggle="tooltip"
            title="Rejected Purchase Order" class="reject-btn">Rejected</button></a> &nbsp;
    &nbsp; &nbsp;
    </div>  
    @endif

@endsection
