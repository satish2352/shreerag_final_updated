@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Delivery Challan <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        <div class="login-horizental cancel-wp pull-left">
                                            <form action="{{ route('add-delivery-chalan') }}" method="POST">
                                                @csrf

                                                {{-- <input type="hidden" name="requistition_id" id="requistition_id" value="{{$requistition_id}}"> --}}
                                                <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Add
                                                    Delivery Challan</button>

                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-10"></div>
                                </div>
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
                                                    class="form-control"
                                                    placeholder="Search Vendor Name / Customer PO Number / Vehicle Name">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar"> --}}
                                        <thead>
                                            <tr>
                                                <th data-field="#">Sr. No.</th>
                                                <th data-field="updated_at" data-editable="false">Date</th>
                                                <th data-field="dc_number" data-editable="false">DC No.</th>
                                                <th data-field="customer_po_number" data-editable="false"> PO Number</th>
                                                <th data-field="customer_po_no" data-editable="false">Customer PO Number
                                                </th>
                                                <th data-field="vendor_name" data-editable="false">Vendor Name</th>
                                                <th data-field="transport_name" data-editable="false">Transport Name</th>
                                                <th data-field="vehicle_name" data-editable="false">Vehicle Name</th>

                                                <th data-field="status">Status</th>
                                                <th data-field="action">Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>

                                            @forelse ($getOutput as $data)
                                                <tr>

                                                    <td>{{ ($getOutput->currentPage() - 1) * $getOutput->perPage() + $loop->iteration }}
                                                    </td>
                                                    {{-- <td>{{ucwords($data->customer_po_number)}}</td> --}}
                                                    <td> {{ \Carbon\Carbon::parse($data->po_date)->format('d-m-Y h:i:s A') }}
                                                    </td>
                                                    <td>{{ ucwords($data->dc_number) }}</td>
                                                    <td>
                                                        {{ $data->customer_po_number ? ucwords($data->customer_po_number) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $data->customer_po_no ? ucwords($data->customer_po_no) : 'N/A' }}
                                                    </td>

                                                    <td>{{ ucwords($data->vendor_name) }}</td>
                                                    <td>{{ ucwords($data->transport_name) }}</td>
                                                    <td>{{ ucwords($data->vehicle_name) }}</td>
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <a
                                                                href="{{ route('show-delivery-chalan', base64_encode($data->id)) }}">
                                                                <button data-toggle="tooltip" title="View Details"
                                                                    class="pd-setting-ed"><i class="fa fa-eye"
                                                                        aria-hidden="true"></i>
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('edit-delivery-chalan', base64_encode($data->id)) }}"><button
                                                                data-toggle="tooltip" title="Edit"
                                                                class="pd-setting-ed"><i class="fas fa-pen-square"
                                                                    aria-hidden="true"></i></button></a>

                                                        <a
                                                            href="{{ route('delete-delivery-chalan', base64_encode($data->id)) }} "><button
                                                                data-toggle="tooltip" title="Trash"
                                                                class="pd-setting-ed"><i class="fa fa-trash"
                                                                    aria-hidden="true"></i></button></a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        {{ $getOutput->onEachSide(1)->links() }}
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
