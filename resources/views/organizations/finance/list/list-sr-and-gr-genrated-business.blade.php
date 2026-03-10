@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>SR and GRN <span class="table-project-n">Genarated For </span> Purchase Order List</h1>

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
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control"
                                                    placeholder="Search PO No. / GRN No. / Vendor Name">
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
                                                <th data-field="purchase_orders_id" data-editable="false">PO Number</th>
                                                <th data-field="grn_no_generate" data-editable="false">GRN No.</th>
                                                <th data-field="store_receipt_no_generate" data-editable="false">SR No.</th>
                                                <th data-field="store_remark" data-editable="false">Store Remark.</th>
                                                <th data-field="vendor_name" data-editable="false">Vendor Name</th>

                                                <th data-field="vendor_email" data-editable="false">Vendor Email Id</th>
                                                {{-- <th data-field="vendor_name" data-editable="false">Vendor Name</th> --}}
                                                <th data-field="contact_no" data-editable="false">Vendor Contact No</th>
                                                <th data-field="vendor_company_name" data-editable="false">Vendor Company
                                                    Name</th>
                                                <th data-field="gst_no" data-editable="false">GST No.</th>
                                                <th data-field="vendor_address" data-editable="false">Vendor Address</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>

                                                    <td> {{ ($data_output->currentPage() - 1) * $data_output->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{ ucwords($data->purchase_orders_id) }}</td>
                                                    <td>
                                                        <a href="{{ route('list-grn-details-po-tracking', [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id), base64_encode($data->id)]) }}"
                                                            style="color: blue;">
                                                            {{ ucwords($data->grn_no_generate) }}
                                                        </a>

                                                    </td>
                                                    <td>{{ ucwords($data->store_receipt_no_generate) }}</td>
                                                    <td>{{ ucwords($data->store_remark) }}</td>
                                                    <td>{{ ucwords($data->vendor_name) }}</td>
                                                    <td>{{ ucwords($data->vendor_email) }}</td>
                                                    <td>{{ ucwords($data->contact_no) }}</td>
                                                    <td>{{ ucwords($data->vendor_company_name) }}</td>
                                                    <td>{{ ucwords($data->gst_no) }}</td>
                                                    <td>{{ ucwords($data->vendor_address) }}</td>

                                                    <td>

                                                        <div style="display: flex; align-items: center;">
                                                            <a
                                                                href="{{ route('forward-the-purchase-order-to-the-owner-for-sanction', [$data->purchase_orders_id, $data->id]) }}">
                                                                <button data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed">
                                                                    Send Owner For Approval
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
