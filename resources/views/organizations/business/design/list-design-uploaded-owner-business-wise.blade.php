@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Design Sent For Estimation <span class="table-project-n"></span></h1>
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
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="bom_image" data-editable="false">BOM</th>
                                                <th data-field="design_image_re" data-editable="false">Revised Design Layout
                                                </th>
                                                <th data-field="re_bom_image" data-editable="false">Estimation BOM</th>
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
                                                    <td>
                                                        @if(!empty($data->design_id) && !empty($data->business_details_id))
                                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                                onclick="openDesignSentEstimationBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                <i class="fa fa-list"></i> View BOM
                                                            </button>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                   
                                                    @if ($data->reject_reason_prod == '')
                                                            <td>-</td>
                                                        @else
                                                            <td>
                                                                <a class="img-size" target="_blank"
                                                                    href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_design_image'] }}"
                                                                    alt="Design">Click to view</a>
                                                            </td>
@endif
                                                            @if ($data->remark_by_estimation == '')
                                                                <td>-</td>
                                                            @else
                                                                <td>
                                                                    @if(!empty($data->design_id) && !empty($data->business_details_id))
                                                                        <button type="button" class="btn btn-outline-info btn-sm"
                                                                            onclick="openDesignSentEstimationBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                            <i class="fa fa-list"></i> View BOM
                                                                        </button>
                                                                    @else
                                                                        <span class="text-muted">—</span>
                                                                    @endif
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
{{-- BOM Material Items Modal (view-only — Design Sent For Estimation, owner side) --}}
@include('organizations.common.bom-material-items-modal', [
    'mode'              => 'view_only',
    'businessId'        => 0,
    'businessDetailsId' => 0,
    'designId'          => 0,
    'bomModalId'        => 'designSentEstimationBomModal',
])

@push('scripts')
<script>
    function openDesignSentEstimationBomModal(businessDetailsId, designId) {
        var bdEncoded = btoa(businessDetailsId);
        var dEncoded  = btoa(designId);
        var fetchUrl  = '{{ url("owner/get-bom-material-items") }}/' + bdEncoded + '/' + dEncoded;
        openBomModal_designSentEstimationBomModal(fetchUrl);
    }
</script>
@endpush
@endsection
