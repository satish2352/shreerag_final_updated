@extends('admin.layouts.master')
@section('content')
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
                                                <th data-field="po_number" data-editable="false">PO Number</th>
                                                <th data-field="purchase_id" data-editable="false">Remark</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="bom_image" data-editable="false">BOM</th>
                                                <th data-field="re_design_image" data-editable="false">Revised Design Layout
                                                </th>
                                                <th data-field="re_bom_image" data-editable="false">Revised BOM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td> <a class="img-size" target="_blank"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                            alt="Design"> Click to view</a>
                                                    </td>
                                                    <td> <a class="img-size"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                            alt="bill of material">Click to download</a>
                                                    </td>
                                                    @if ($data->reject_reason_prod == '')
                                                        <td>-</td>
                                                        <td>-</td>
                                                    @else
                                                        <td> <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_design_image'] }}"
                                                                alt="Design"> Click to view</a>
                                                        </td>
                                                        <td> <a class="img-size"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_bom_image'] }}"
                                                                alt="bill of material">Click to download</a>
                                                        </td>
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
     <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection