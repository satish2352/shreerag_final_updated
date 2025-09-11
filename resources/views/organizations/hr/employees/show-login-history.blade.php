
@extends('admin.layouts.master')
@section('content')
    <style>
        .btn-colour{
            color: gray !important;
        }
    </style>

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list" style="padding-bottom: 100px">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Login History List</h1>
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
                                                <th>Sr. No.</th>
                                                <th>Name</th>
                                                <th>latitude</th>
                                                <th>longitude</th>
                                                  <th>Address</th>
                                                
                                            </tr>
                                        </thead>
                                      <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $user_detail->f_name }} {{ $user_detail->m_name }} {{ $user_detail->l_name }}</td>
                                                <td>{{ $user_detail->latitude }}</td>
                                                <td>{{ $user_detail->longitude }}</td>
                                                 <td>{{ $user_detail->location_address }}</td>
                                            </tr>
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
