@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Request Send to Production</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control"
                                                    placeholder="Search Project Name / Project Name / PO No.">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th data-field="id">ID</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="purchase_id" data-editable="false">Remark</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="design_image" data-editable="false">Design Layout</th>
                                                {{-- <th data-field="bom_image" data-editable="false">Estimated BOM</th> --}}
                                                <th data-field="total_estimation_amount" data-editable="false">Total
                                                    Estimation Amount</th>
                                                <th data-field="bom_items" data-editable="false">BOM Items</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                @if (is_object($data))
                                                    <tr>
                                                        <td> {{ ($data_output->currentPage() - 1) * $data_output->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td>{{ optional($data->updated_at)->format('d-m-Y') ?? 'N/A' }}</td>
                                                        <td>{{ ucwords($data->project_name) }}</td>
                                                        <td>{{ ucwords($data->customer_po_number) }}</td>
                                                        <td>{{ ucwords($data->remarks) }}</td>
                                                        <td>{{ ucwords($data->product_name) }}</td>
                                                        <td>{{ ucwords($data->quantity) }}</td>
                                                        <td>{{ ucwords($data->description) }}</td>
                                                        <td><a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                                alt="Design"> Click to view</a>
                                                        </td>
                                                        {{-- <td>
                                                            @if (!empty($data->business_details_id) && !empty($data->design_id))
                                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                                    onclick="estimSendToProdOpenBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                    <i class="fa fa-list"></i> View BOM
                                                                </button>
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td> --}}
                                                        <td>{{ ucwords($data->total_estimation_amount) }}</td>
                                                        <td>
                                                            @if (!empty($data->business_details_id) && !empty($data->design_id))
                                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                                    onclick="estimSendToProdOpenBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                    <i class="fa fa-list"></i> View BOM
                                                                </button>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="12" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p>
                                                Showing {{ $data_output->firstItem() }} to
                                                {{ $data_output->lastItem() }}
                                                of {{ $data_output->total() }} rows
                                            </p>
                                        </div>

                                        <div class="col-md-6 d-flex justify-content-end mt-3">
                                            {{ $data_output->onEachSide(1)->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BOM Material Items Modal (estimator view-only — send to production list) --}}
    @include('organizations.common.bom-material-items-modal', [
        'mode' => 'view_only',
        'businessId' => 0,
        'businessDetailsId' => 0,
        'designId' => 0,
        'bomModalId' => 'estimSendToProdBomModal',
    ])

    @push('scripts')
        <script>
            function estimSendToProdOpenBomModal(businessDetailsId, designId) {
                var bdEncoded = btoa(businessDetailsId);
                var dEncoded = btoa(designId);
                var fetchUrl = '{{ url('estimationdept/get-bom-material-items') }}/' + bdEncoded + '/' + dEncoded;
                openBomModal_estimSendToProdBomModal(fetchUrl);
            }
        </script>
    @endpush
@endsection
