@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Purchase Order Details Material Received Tracking</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">ID</th>
                                                 <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="grn_number" data-editable="false">PO Number</th>
                                                <th data-field="grn_date" data-editable="false">Title</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="grn" data-editable="false">GRN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                     <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->purchase_orders_id) }}</td>
                                                    <td>{{ ucwords($data->Title) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <a href="{{ route('list-grn-details-po-tracking', [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id), base64_encode($data->grn_id)]) }}">
                                                            <button data-toggle="tooltip" title="GRN Details" class="btn btn-sm btn-bg-colour">GRN Details</button>
                                                        </a>
                                                    </div>
                                                </td>
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
     <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection
