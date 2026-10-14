@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list" style="padding-bottom: 100px">
                        <div class="sparkline13-graph">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 style="margin:0; font-weight:600; color:#1a3a6b;">Login History List</h5>
                            </div>
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
   @endsection
