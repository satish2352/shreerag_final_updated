
@extends('admin.layouts.master')
@section('content')
    

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>GRN  <span class="table-project-n">Genration</span></h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        <div class="login-horizental cancel-wp pull-left">

                                        </div>
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
                                <i class="fa fa-check adminpro-checked-pro admin-check-pro" aria-hidden="true"></i>
                                <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
                            </div>
                        @endif
                        @if (Session::get('status') == 'error')
                            <div class="alert alert-danger alert-mg-b alert-success-style4">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <i class="fa fa-times adminpro-danger-error admin-check-pro" aria-hidden="true"></i>
                                <p><strong>Danger!</strong> {{ Session::get('msg') }}</p>
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

                                                <th data-field="id">ID</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="purchase_id" data-editable="false">PO Number</th>
                                                <th data-field="name" data-editable="false">Name</th>
                                                <th data-field="date" data-editable="false">Date</th>
                                                <th data-field="time" data-editable="false">Time</th>
                                                <th data-field="remark" data-editable="false">Remark</th>
                                                {{-- <th data-field="status" data-editable="false">Status</th> --}}
                                                {{-- <th data-field="action">Action</th> --}}
                                            </tr>

                                        </thead>
                                        <tbody>

                                            @foreach ($all_gatepass as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->project_name}}</td>
                                                    <td>{{ $data->purchase_orders_id}}</td>
                                                    <td>{{ ucwords($data->gatepass_name) }}</td>
                                                    <td>{{ ucwords($data->gatepass_date) }}</td>
                                                    <td>{{ ucwords($data->gatepass_time) }}</td>
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
