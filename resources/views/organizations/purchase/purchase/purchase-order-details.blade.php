@extends('admin.layouts.master')
@section('content')
    <style>
        .form-control {
            border: 2px solid #ced4da;
            border-radius: 4px;
        }

        .error {
            color: red;
        }
    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Purchase Order <span class="table-project-n">Details</span></h1>
                            </div><br>



                            <a href="{{route('list-check-final-purchase-order', $data->purchase_order_id)}}"><button data-toggle="tooltip" title="View Details" class="pd-setting-ed"><i class="fa fa-check" aria-hidden="true"></i>Send Mail To Vendor</button></a>

                        </div>
                    </div>
                @endsection
