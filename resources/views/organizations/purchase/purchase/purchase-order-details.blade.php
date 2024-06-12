@extends('admin.layouts.master')
@section('content')
    @include('organizations.common-pages.purchase-order-view')
    <div class="" style="margin-bottom: 70px;">
        <a href="{{ route('list-check-final-purchase-order', $purchase_order_id) }}"><button data-toggle="tooltip"
                title="Send Mail" class="pd-setting-ed"><i class="fa fa-check" aria-hidden="true"></i>Send Mail To
                Vendor</button></a>
    </div>
@endsection
