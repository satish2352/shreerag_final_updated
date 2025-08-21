@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>New Design Received For Estimation <span class="table-project-n"></span></h1>
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
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="bom_image" data-editable="false">BOM</th>
                                                <th data-field="total_amount" data-editable="false">Total Amount</th>
                                                @if (session('role_id') === 15)
                                                    <th data-field="action">Action</th>
                                                @else
                                                @endif
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach (is_iterable($data_output) ? $data_output : [] as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td><a class="img-size" target="_blank"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                            alt="Design"> Click to view</a>
                                                    </td>
                                                    <td> <a class="img-size"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                            alt="bill of material">Click to download</a>
                                                    </td>
                                                    <td>{{ ucwords($data->total_amount) }}</td>
                                                    @if (session('role_id') == 15)
                                                        <td>
                                                            <div style="display: inline-block; align-items: center;">
                                                                <a href="{{ route('edit-estimation', base64_encode($data->business_details_id)) }}"
                                                                    data-toggle="tooltip" title="Edit">
                                                                    <button class="btn btn-sm btn-bg-colour"><i
                                                                            class="fa fa-check" aria-hidden="true"></i> Edit
                                                                        Estimation </button>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    @else
                                                    @endif
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
