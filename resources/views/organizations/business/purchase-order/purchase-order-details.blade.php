@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
    <?php
// dd($purchaseOrder);
// die();
    ?>
    {{-- @if($purchaseOrder->off_canvas_status == '23') --}}

    <div class="" style="margin-bottom: 70px;">
        <a href="{{ route('accept-purchase-order', ['purchase_order_id' => $purchase_order_id, 'business_id' => $purchaseOrder->business_details_id]) }}"><button data-toggle="tooltip"
                title="Accept Purchase Order" class="pd-setting-ed">Accept</button></a> &nbsp;
        &nbsp; &nbsp;
    </div>
    {{-- @else --}}

    {{-- @endif --}}


    
@endsection
