@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Exceed Amount Requests — Pending Owner Review</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    @if(isset($message) || (isset($data_output) && (is_array($data_output) ? count($data_output) === 0 : $data_output->isEmpty())))
                                        <div class="alert alert-info mt-3">No exceed amount requests pending.</div>
                                    @else
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">#</th>
                                                <th data-field="date" data-editable="false">Date Requested</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="po_number" data-editable="false">PO Number</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="business_limit" data-editable="false">Business Limit (&#8377;)</th>
                                                <th data-field="estimator_amount" data-editable="false">Estimator Amount (&#8377;)</th>
                                                <th data-field="difference" data-editable="false">Difference (&#8377;)</th>
                                                <th data-field="reason" data-editable="false">Estimator Reason</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') : 'N/A' }}</td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ $data->customer_po_number }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ number_format($data->total_amount, 2) }}</td>
                                                    <td>
                                                        <span style="color:#c0392b;font-weight:bold;">
                                                            {{ number_format($data->total_estimation_amount, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span style="color:#e67e22;font-weight:bold;">
                                                            +{{ number_format($data->exceed_difference, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $data->exceed_remark ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('edit-business', base64_encode($data->business_id)) }}">
                                                            <button class="btn btn-sm btn-warning" type="button"
                                                                style="color:#fff;">Update Amount</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
