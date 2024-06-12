@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
    <div class="" style="margin-bottom: 70px;">
        <a href="{{ route('accept-purchase-order', $purchase_order_id) }}"><button data-toggle="tooltip"
                title="Accept Purchase Order" class="pd-setting-ed">Accept</button></a> &nbsp;
        &nbsp; &nbsp;
    </div>


    
@endsection
