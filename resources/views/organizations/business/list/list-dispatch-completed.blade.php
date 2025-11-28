@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Dispatch List</h1>
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
                                                <th data-field="id">Sr.No.</th>
                                                 <th data-field="updated_at" data-editable="false">Dispatch Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="title" data-editable="false">customer Name</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Total Quantity</th>
                                                <th data-field="completed_quantity" data-editable="false">Actual Product
                                                    Quantity</th>
                                                <th data-field="transport_name" data-editable="false">Transport Name</th>
                                                <th data-field="vehicle_name" data-editable="false">Vehicle Name</th>
                                                <th data-field="truck_no" data-editable="false">Truck Number</th>
                                                <th data-field="outdoor_no" data-editable="false">Outdoor Number</th>
                                                <th data-field="gate_entry" data-editable="false">Gate Entry</th>
                                                <th data-field="remark" data-editable="false">Dispatch Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->updated_at) }}</td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->title) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->completed_quantity) }}</td>
                                                    <td>{{ ucwords($data->transport_name) }}</td>
                                                    <td>{{ ucwords($data->vehicle_name) }}</td>
                                                    <td>{{ ucwords($data->truck_no) }}</td>
                                                    <td>{{ ucwords($data->outdoor_no) }}</td>
                                                    <td>{{ ucwords($data->gate_entry) }}</td>
                                                    <td>{{ ucwords($data->remark) }}</td>
                                                  
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
