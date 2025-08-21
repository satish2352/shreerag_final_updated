@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>List Rejected Chalan  <span class="table-project-n"></span></h1>
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
                                                <th data-field="purchase_id" data-editable="false">PO Number</th>
                                                <th data-field="name" data-editable="false">PO Date</th>
                                                <th data-field="date" data-editable="false">GRN Date</th>
                                                <th data-field="remark" data-editable="false">Remark</th>
                                                {{-- <th data-field="status" data-editable="false">Status</th> --}}
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($all_gatepass as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->purchase_orders_id}}</td>
                                                    <td>{{ ucwords($data->po_date) }}</td>
                                                    <td>{{ ucwords($data->grn_date) }}</td>
                                                    <td>{{ ucwords($data->remark) }}</td>
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <a href="{{ route('list-rejected-chalan-details', 
                                                             [base64_encode($data->purchase_orders_id), base64_encode($data->id)]) }}"><button
                                                                    data-toggle="tooltip" title="Edit"
                                                                    class="btn btn-sm btn-bg-colour"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
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
