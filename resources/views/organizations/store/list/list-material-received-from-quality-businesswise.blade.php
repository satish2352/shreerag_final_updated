@extends('admin.layouts.master')
@section('content')
    <style>
        .disabled-btn {
            background-color: #ccc;
            /* Light gray background */
            color: #666;
            /* Darker gray text */
            cursor: not-allowed;
            /* Show not-allowed cursor */
            opacity: 0.7;
            /* Slightly transparent */
        }

        /* Style for enabled buttons */
        .enabled-btn {
            background-color: #28a745;
            /* Green background */
            color: black;
            /* White text */
        }
    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Material Received From Quality</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>

                                                <th data-field="id">ID</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                {{-- <th data-field="description" data-editable="false">Description</th> --}}
                                                <th data-field="grn_number" data-editable="false">PO Number</th>
                                                <th data-field="grn_no_generate" data-editable="false">GRN No.</th>
                                                <th data-field="vendor_name" data-editable="false">Vendor Name</th>
                                                <th data-field="grn_date" data-editable="false">Date</th>
                                                <th data-field="bill_date" data-editable="false">Bill No.</th>

                                                <th data-field="grn" data-editable="false">GRN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    {{-- <td>{{ ucwords($data->description) }}</td> --}}
                                                    <td>{{ ucwords($data->purchase_orders_id) }}</td>
                                                    <td>{{ ucwords($data->grn_no_generate) }}</td>
                                                    <td>{{ ucwords($data->vendor_name) }}</td>
                                                    <td>{{ ucwords($data->grn_date) }}</td>
                                                    <td>{{ ucwords($data->bill_date) }}</td>
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <a
                                                                href="{{ route('list-grn-details', [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id), base64_encode($data->id)]) }}">
                                                                <button data-toggle="tooltip" title="GRN Details"
                                                                    class="btn btn-sm btn-bg-colour">GRN Details</button>
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
@endsection
