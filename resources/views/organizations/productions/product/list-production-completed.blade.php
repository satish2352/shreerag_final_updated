<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
    <style>
        .fixed-table-loading {
            display: none;
        }

        #table thead th {
            white-space: nowrap;
        }

        #table thead th {
            width: 300px !important;
            padding-right: 49px !important;
            padding-left: 20px !important;
        }

        .custom-datatable-overright table tbody tr td {
            padding-left: 19px !important;
            padding-right: 5px !important;
            font-size: 14px;
            text-align: left;
        }
    </style>

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Production Completed List</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        {{-- Add Button or Other Actions Here --}}
                                    </div>
                                    <div class="col-lg-10"></div>
                                </div>
                            </div>
                        </div>

                        @if (Session::get('status') == 'success')
                            <div class="alert alert-success alert-success-style1">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
                            </div>
                        @endif
                        @if (Session::get('status') == 'error')
                            <div class="alert alert-danger alert-mg-b alert-success-style4">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <p><strong>Error!</strong> {{ Session::get('msg') }}</p>
                            </div>
                        @endif

                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th data-field="tracking_updated_at" data-editable="false">Completed Date</th>
                                                <th>Project Name</th>
                                                <th>PO Number</th>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>PO Quantity</th>
                                                <th>Completed Production</th>
                                                <th>Balance Quantity</th>
                                                <th>Total Completed Production</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($data->tracking_updated_at)->format('d-m-Y h:i A') }}</td>
                                                    <td>{{ $data->project_name }}</td>
                                                    <td>{{ $data->customer_po_number }}</td>
                                                    <td>{{ $data->product_name }}</td>
                                                    <td>{{ $data->description }}</td>
                                                    <td>{{ $data->quantity }}</td>
                                                    <td><b>{{ $data->completed_quantity }}</b></td>
                                                    <td>{{ $data->remaining_quantity }}</td>
                                                    <td>{{ $data->cumulative_completed_quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
