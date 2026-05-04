@extends('admin.layouts.master')
@section('content')
    <style>
        /* Style for disabled buttons */
        .disabled-btn {
            background-color: #ccc;
            /* Light gray background */
            color: #666;
            /* Darker gray text */
            cursor: not-allowed;
            /* Show not-allowed cursor */
            opacity: 0.7;
            /* Slightly transparent */
        }

        /* Style for enabled buttons */
        .enabled-btn {
            background-color: #28a745;
            /* Green background */
            color: black;
            /* White text */
        }
    </style>
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

                        @if (Session::get('status') == 'success')
                            <div class="alert alert-success alert-success-style1">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                {{-- <i class="fa fa-check adminpro-checked-pro admin-check-pro" aria-hidden="true"></i> --}}
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
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>

                                                <th data-field="id">ID</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="total_amount" data-editable="false">Estimated Amount</th>

                                                <th data-field="design_image" data-editable="false">Design</th>
                                                <th data-field="re_design_image" data-editable="false">Revised Design</th>
                                                <th data-field="bom_items" data-editable="false">BOM Items</th>
                                                <th data-field="dispatch_status_id" data-editable="false">Product Status
                                                </th>
                                                <th data-field="action" data-editable="false"><span
                                                        style="display: flex; justify-content: center;">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->total_estimation_amount) }}</td>
                                                    <td>
                                                        @if (!empty($data->design_image))
                                                            <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                                alt="Design">Click to view</a>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    @if ($data->reject_reason_prod == '')
                                                        <td>-</td>
                                                    @else
                                                        <td> <a class="img-size" target="_blank"
                                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['re_design_image'] }}"
                                                                alt="Design">Click to view</a>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if (!empty($data->design_id) && !empty($data->business_details_id))
                                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                                onclick="storeOpenBomModal({{ $data->business_details_id }}, {{ $data->design_id }})">
                                                                <i class="fa fa-list"></i> View BOM
                                                            </button>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    {{-- PRODUCT STATUS --}}
                                                    <td>
                                                        @if ($data->dispatch_status_id == 1154)
                                                            <span class="badge badge-success">CLOSED</span>
                                                        @else
                                                            <span class="badge badge-warning">OPEN</span>
                                                        @endif
                                                    </td>

                                                    {{-- ACTION BUTTONS — always enabled (was previously disabled
                                                         when dispatch_status_id == 1154 / CLOSED, but the store
                                                         dept needs to be able to issue material / start a purchase
                                                         requisition regardless of that status). --}}
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <a
                                                                href="{{ route('bom-inventory-check', base64_encode($data->business_details_id)) }}">
                                                                <button class="btn btn-sm btn-bg-colour"
                                                                    style="padding: 7px;"
                                                                    data-toggle="tooltip" title="Issue Material and Need to Purchase">
                                                                    Issue Material and Need to Purchase
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    {{-- <td>
                                                        @if ($data->dispatch_status_id == 1148)
                                                            <span class="badge badge-success">CLOSED</span>
                                                        @else
                                                            <span class="badge badge-warning">OPEN</span>
                                                        @endif
                                                    </td>
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
        'mode' => 'view_only',
        'businessId' => 0,
        'businessDetailsId' => 0,
        'designId' => 0,
        'bomModalId' => 'storeBomItemsModal',
    ])

    @push('scripts')
        <script>
            function storeOpenBomModal(businessDetailsId, designId) {
                var bdEncoded = btoa(businessDetailsId);
                var dEncoded = btoa(designId);
                var fetchUrl = '{{ url('production/get-bom-material-items') }}/' + bdEncoded + '/' + dEncoded;
                openBomModal_storeBomItemsModal(fetchUrl);
            }
        </script>
    @endpush
@endsection
