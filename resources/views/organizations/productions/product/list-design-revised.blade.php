@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Corected Design List Received From  <span class="table-project-n">Design</span> Department</h1>
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
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="po_number" data-editable="false">PO Number</th>
                                                <th data-field="product_name" data-editable="false">Product Nmae</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="Remark" data-editable="false">Remark</th>
                                                <th data-field="reject_reason" data-editable="false">Reject Reason</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="design_image_re" data-editable="false">Revised Design Layout</th>
                                                <th data-field="bom_items" data-editable="false">BOM Items</th>
                                                <th data-field="remark_by_design" data-editable="false">Design Team Remark</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ !empty($data->updated_at) ? \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') : 'N/A' }}</td>
                                                    <td>{{ucwords($data->project_name)}}</td>
                                                    <td>{{ucwords($data->customer_po_number)}}</td>
                                                    <td>{{ucwords($data->product_name)}}</td>
                                                    <td>{{ucwords($data->description)}}</td>
                                                    <td>{{ucwords($data->quantity)}}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    <td>{{ ucwords($data->reject_reason_prod) }}</td>
                                                        <td>
                                                        @if(!empty($data->design_image))
                                                            <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data->design_image }}"
                                                                alt="Design">Click to view</a>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($data->re_design_image))
                                                            <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data->re_design_image }}"
                                                                alt="Revised Design">Click to view</a>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($data->design_id) && !empty($data->business_details_id))
                                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                                onclick="openRevisedDesignProdBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                <i class="fa fa-list"></i> View BOM
                                                            </button>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ ucwords($data->remark_by_design) }}</td>
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                         <a href="{{ route('accept-design', base64_encode($data->business_details_id)) }}"  onclick="return confirmAccept('{{ route('accept-design', base64_encode($data->id)) }}')"
                                                                            class="pd-setting-ed"
                                                                            data-toggle="tooltip"
                                                                            title="Accept"><button
                                                            data-toggle="tooltip" title="Accept BOM Estimation" class="accept-btn">Accept</button></a> &nbsp;
                                                    &nbsp; &nbsp;

                                                    <a href="{{ route('reject-design-edit', base64_encode($data->business_details_id)) }}"><button
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
@include('organizations.common.bom-material-items-modal', [
    'mode'              => 'view_only',
    'businessId'        => 0,
    'businessDetailsId' => 0,
    'designId'          => 0,
    'bomModalId'        => 'revisedDesignProdBomModal',
])

@push('scripts')
<script>
    function openRevisedDesignProdBomModal(businessDetailsId, designId) {
        var bdEncoded = btoa(businessDetailsId);
        var dEncoded  = btoa(designId);
        var fetchUrl  = '{{ url("production/get-bom-material-items") }}/' + bdEncoded + '/' + dEncoded;
        openBomModal_revisedDesignProdBomModal(fetchUrl);
    }
</script>
@endpush
@endsection
