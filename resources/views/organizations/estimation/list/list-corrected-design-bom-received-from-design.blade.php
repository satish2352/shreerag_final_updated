@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Corrected Design And BOM Material Received From Design Dept</h1>
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
                                                <th data-field="date" data-editable="false">Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="po_number" data-editable="false">PO Number</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="reject_reason" data-editable="false">Reject Reason (Production)</th>
                                                <th data-field="design_image" data-editable="false">Original Design Layout</th>
                                                <th data-field="re_design_image" data-editable="false">Revised Design Layout</th>
                                                <th data-field="bom_items" data-editable="false">BOM Items</th>
                                                <th data-field="remark" data-editable="false">Designer Remark</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(is_iterable($data_output) ? $data_output : [] as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ !empty($data->updated_at) ? \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') : 'N/A' }}</td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td>{{ ucwords($data->reject_reason_prod ?? '—') }}</td>
                                                    <td>
                                                        @if(!empty($data->design_image))
                                                            <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data->design_image }}"
                                                                alt="Original Design">Click to view</a>
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
                                                                onclick="openCorrectedDesignEstimBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                <i class="fa fa-list"></i> View BOM
                                                            </button>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ ucwords($data->remark_by_design ?? '—') }}</td>
                                                    <td>
                                                        <a href="{{ route('edit-estimation', base64_encode($data->business_details_id)) }}">
                                                            <button class="btn btn-sm btn-bg-colour">
                                                                <i class="fa fa-check" aria-hidden="true"></i> Edit Estimation
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="13" class="text-center">No corrected designs received.</td>
                                                </tr>
                                            @endforelse
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

{{-- BOM Material Items Modal (estimation edit mode — corrected design from design dept) --}}
@include('organizations.common.bom-material-items-modal', [
    'mode'              => 'estimation_edit',
    'businessId'        => 0,
    'businessDetailsId' => 0,
    'designId'          => 0,
    'bomSaveUrl'        => route('estimation.save-bom-material-items'),
    'bomModalId'        => 'correctedDesignEstimBomModal',
])

@push('scripts')
<script>
    function openCorrectedDesignEstimBomModal(businessDetailsId, designId) {
        var bdEncoded = btoa(businessDetailsId);
        var dEncoded  = btoa(designId);
        var fetchUrl  = '{{ url("estimationdept/get-bom-material-items") }}/' + bdEncoded + '/' + dEncoded;
        openBomModal_correctedDesignEstimBomModal(fetchUrl);
    }
</script>
@endpush
@endsection
