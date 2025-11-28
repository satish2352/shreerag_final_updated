@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>BOM List Received from Estimation Department Business Wise</h1>
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
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>

                                                <th data-field="id">ID</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="bom_image" data-editable="false">Estimated BOM</th>
                                                 <th data-field="total_amount" data-editable="false">Owner Side Amount</th>
                                                  <th data-field="total_estimation_amount" data-editable="false">Estimated BOM Amount</th>
                                                <th data-field="action">Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td> <a class="img-size" target="_blank"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                            alt="Design"> Click to view</a>
                                                    </td>
                                                    <td> <a class="img-size"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                            alt="bill of material">Click to download</a>
                                                    </td>
                                                    </td>
                                                      <td>{{ ucwords($data->total_amount) }}</td>
                                                    <td>{{ ucwords($data->total_estimation_amount) }}</td>
                                                   <td>
                                                <div>
                                                    <a
                                                        href="{{ route('accept-bom-estimation', base64_encode($data->id)) }}"><button
                                                            data-toggle="tooltip" title="Accept BOM Estimation" class="accept-btn">Accept</button></a> &nbsp;
                                                    &nbsp; &nbsp;

                                                    <a
                                                        href="{{ route('edit-reject-estimation-owner-side', base64_encode($data->id)) }}"><button
                                                            data-toggle="tooltip" title="Rejected BOM Estimation" class="reject-btn">Reject</button></a> &nbsp;
                                                    &nbsp; &nbsp;
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
