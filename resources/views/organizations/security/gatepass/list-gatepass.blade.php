@extends('admin.layouts.master')
@section('content')
    <style>
        button.disabled {
            opacity: 0.5;
            /* Makes the button appear grayed out */
            cursor: not-allowed;
            /* Changes the cursor to indicate it's disabled */
        }
    </style>

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Gatepass <span class="table-project-n">Data</span> Table</h1>

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
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control" placeholder="Search Gatepass Name / PO No.">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar"> --}}
                                        <thead>
                                            <tr>

                                                <th data-field="id">ID</th>
                                                <th data-field="purchase_id" data-editable="false">PO Number</th>
                                                <th data-field="name" data-editable="false">Name</th>
                                                <th data-field="date" data-editable="false">Date</th>
                                                <th data-field="time" data-editable="false">Time</th>
                                                <th data-field="remark" data-editable="false">Remark</th>
                                                <th data-field="" data-editable="false">Purchase Order</th>
                                                <th data-field="action">Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @forelse ($all_gatepass as $data)
                                                <tr>
                                                    <td> {{ ($all_gatepass->currentPage() - 1) * $all_gatepass->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{ ucwords($data->purchase_orders_id) }}</td>
                                                    <td>{{ ucwords($data->gatepass_name) }}</td>
                                                    <td>{{ ucwords($data->gatepass_date) }}</td>
                                                    <td>{{ ucwords($data->gatepass_time) }}</td>
                                                    <td>{{ ucwords($data->remark) }}</td>
                                                    <td>
                                                        <div style="display: flex; align-items: center;">

                                                            <a
                                                                href="{{ route('list-po-details', [base64_encode($data->purchase_id), base64_encode($data->purchase_orders_id)]) }}">
                                                                <button data-toggle="tooltip" title="View PO"
                                                                    class="btn btn-sm btn-bg-colour">Check PO
                                                                    Details</button></a>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <a
                                                                href="{{ route('edit-gatepass', base64_encode($data->id)) }}">
                                                                <button data-toggle="tooltip" title="Edit"
                                                                    class="btn btn-sm btn-bg-colour @if ($data->quality_status_id === 1134) disabled @endif"
                                                                    @if ($data->quality_status_id === 1134) disabled @endif>
                                                                    <i class="fas fa-pen-square" aria-hidden="true"></i>
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p>
                                                Showing {{ $all_gatepass->firstItem() }} to
                                                {{ $all_gatepass->lastItem() }}
                                                of {{ $all_gatepass->total() }} rows
                                            </p>
                                        </div>

                                        <div class="col-md-6 d-flex justify-content-end mt-3">
                                            {{ $all_gatepass->onEachSide(1)->links() }}
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
@endsection
