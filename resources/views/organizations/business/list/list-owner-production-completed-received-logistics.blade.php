@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Production Completed Received Logistics Department List</h1>
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
                                                    placeholder="Search  Project Name / Product Name / Quantity ">
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
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Actual Quantity</th>
                                                <th data-field="completed_quantity" data-editable="false">Production
                                                    Completed Quantity</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td> {{ $data->created_at ? $data->created_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->completed_quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
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
@endsection
