@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Purchase Order Submited by Vendor</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">

                                    </div>
                                    <div class="col-lg-10"></div>
                                </div>
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
                                                    placeholder="Search Product Name / PO No. / Vendor Name ">
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
                                                {{-- <th data-field="po_number" data-editable="false">PO Number</th> --}}
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>

                                                <th data-field="purchase_orders_id" data-editable="false">Purchase Order ID
                                                </th>
                                                <th data-field="client_name" data-editable="false">Client Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Client Company
                                                    Name</th>
                                                <th data-field="email" data-editable="false">Email</th>
                                                <th data-field="contact_no" data-editable="false">Phone Number</th>
                                                {{-- <th data-field="vendor_address" data-editable="false">Address</th>                                      --}}
                                            </tr>

                                        </thead>



                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>
                                                    <td>{{ $data_output->firstItem() + $loop->index }}</td>

                                                    {{-- <td>{{$data['purchase_order_id']}}</td> --}}
                                                    <td>{{ ucwords($data['product_name']) }}</td>
                                                    <td>{{ ucwords($data['description']) }}</td>
                                                    <td>{{ $data->purchase_order_id }}</td>
                                                    <td>{{ $data->vendor_name }}</td>
                                                    <td>{{ $data->vendor_company_name }}</td>
                                                    <td>{{ $data->vendor_email }}</td>
                                                    <td>{{ $data->contact_no }}</td>
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
                                                Showing {{ $data_output->firstItem() }} to {{ $data_output->lastItem() }}
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
