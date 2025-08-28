@extends('admin.layouts.master')
@section('content')
    <style>
        .disabled-btn {
        background-color: #ccc;  /* Light gray background */
        color: #666;             /* Darker gray text */
        cursor: not-allowed;     /* Show not-allowed cursor */
        opacity: 0.7;            /* Slightly transparent */
    }

    /* Style for enabled buttons */
    .enabled-btn {
        background-color: #28a745; /* Green background */
        color: black;              /* White text */
    }
    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Material Need To Sent To<span class="table-project-n"> Production</span> Department</h1>
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
                                                    <td>{{ ucwords($data->purchase_orders_id) }}</td>
                                                    <td>{{ ucwords($data->Title) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <a href="{{ route('list-grn-details', [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id), base64_encode($data->id)]) }}">
                                                            <button data-toggle="tooltip" title="GRN Details" class="pd-setting-ed">GRN Details</button>
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
