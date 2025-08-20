@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Purchase Order Accepted<span class="table-project-n"></span></h1>
                            </div>
                        </div>                       
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
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="purchase_orders_id" data-editable="false">Purchase Order ID
                                                </th>
                                                <th data-field="client_name" data-editable="false">Client Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Client Company
                                                    Name</th>
                                                <th data-field="email" data-editable="false">Email</th>
                                                <th data-field="contact_no" data-editable="false">Phone Number</th>
                                                <th data-field="vendor_address" data-editable="false">Address</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->purchase_order_id }}</td>
                                                    <td>{{ $data->vendor_name }}</td>
                                                    <td>{{ $data->vendor_company_name }}</td>
                                                    <td>{{ $data->vendor_email }}</td>
                                                    <td>{{ $data->contact_no }}</td>
                                                    <td>{{ $data->vendor_address }}</td>

                                                    <td>
                                                        <div style="display: inline-block; align-items: center;">
                                                            <a
                                                                href="{{ route('list-submit-final-purchase-order-particular-business', $data->purchase_order_id) }} "><button
                                                                    data-toggle="tooltip" title="View Details"
                                                                    class="btn btn-sm btn-bg-colour">View Details</button></a>
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
@endsection
