@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
    @if($purchaseOrder->purchase_status_from_owner == '1127' &&  $purchaseOrder->purchase_status_from_purchase == '1126')
    <div class="" style="margin-bottom: 70px;">
        <a href="{{ route('finalize-and-submit-mail-to-vendor',  ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button data-toggle="tooltip"
                title="Send Mail" class="pd-setting-ed"><i class="fa fa-check" aria-hidden="true"></i>Send Mail To
                Vendor</button></a>
    </div>
     @else 

    @endif 
@endsection

