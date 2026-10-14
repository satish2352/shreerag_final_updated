@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-graph">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 style="margin:0; font-weight:600; color:#1a3a6b;">Purchase Order Details</h5>
                            </div>
                            @include('organizations.common-pages.purchase-order-view')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
