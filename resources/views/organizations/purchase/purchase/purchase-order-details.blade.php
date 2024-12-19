@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
  
    @if($purchaseOrder->purchase_status_from_owner == '1127' &&  $purchaseOrder->purchase_status_from_purchase == '1126')
    <div class="" style="padding-bottom:130px; padding-left: 17px; margin-top: 20px;">
        <a href="{{ route('finalize-and-submit-mail-to-vendor',  ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button data-toggle="tooltip"
                title="Send Mail" class="accept-btn">Send Mail To
                Vendor</button></a>
    </div>
     @else 

    @endif 
@endsection

