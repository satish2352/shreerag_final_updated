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
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">ID</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="po_number" data-editable="false">PO Number</th>
                                                <th data-field="purchase_id" data-editable="false">Remark</th>
                                                {{-- <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="design_image" data-editable="false">Design</th>
                                                <th data-field="bom_image" data-editable="false">BOM</th>
                                                <th data-field="re_design_image" data-editable="false">Revised Design
                                                </th>
                                                <th data-field="re_bom_image" data-editable="false">Revised BOM</th> --}}
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td> {{ $data->created_at ? $data->created_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    {{-- <td>{{ ucwords($data->product_name) }}</td>
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
                                                    @if ($data->reject_reason_prod == '')
                                                        <td>-</td>
                                                    @else
                                                        <td> <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_design_image'] }}"
                                                                alt="Design"> Click to view</a>
                                                        </td>
                                                    @endif
                                                    @if ($data->remark_by_estimation == '')
                                                        <td>-</td>
                                                    @else
                                                        <td> <a class="img-size"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_bom_image'] }}"
                                                                alt="bill of material">Click to download</a>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <div
                                                                style="display: inline-block; align-items: center; margin-right: 10px;">
                                                                <a
                                                                    href="{{ route('edit-material-list-bom-wise-new-req', base64_encode($data->business_details_id)) }}"><button
                                                                        data-toggle="tooltip" style="padding: 7px;"
                                                                        title="View Details"
                                                                        class="btn btn-sm btn-bg-colour">Issue Product
                                                                        Material</button></a>
                                                            </div>
                                                            <a
                                                                href="{{ route('need-to-create-req', base64_encode($data->business_details_id)) }} "><button
                                                                    data-toggle="tooltip" title="Need To Purchase"
                                                                    class="btn btn-sm btn-bg-colour"
                                                                    style="padding: 7px;">Need To Purchase</button></a>

                                                        </div>
                                                    </td> --}}

                                                    <td>
                                                        <a
                                                            href="{{ route('list-accepted-design-from-prod-business-wise', base64_encode($data->id)) }}"><button
                                                                class="btn btn-sm btn-bg-colour" type="submit">View
                                                                Details</button></a>
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
