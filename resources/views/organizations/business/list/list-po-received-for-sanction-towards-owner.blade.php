<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
    <style>
        .fixed-table-loading {
            display: none;
        }

        #table thead th {
            white-space: nowrap;
        }

        #table thead th {
            width: 300px !important;
            padding-right: 49px !important;
            padding-left: 20px !important;
        }

        .custom-datatable-overright table tbody tr td {
            padding-left: 19px !important;
            padding-right: 5px !important;
            font-size: 14px;
            text-align: left;
        }
    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>PO Received For Sanction For Payment</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        {{-- <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('add-design-upload') }}" ><button class="btn btn-sm btn-primary login-submit-cs" type="submit" >Add Design</button></a>
                                        </div> --}}
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
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>

                                                <th data-field="id">ID</th>
                                                <th data-field="purchase_orders_id" data-editable="false">PO Number</th>
                                                <th data-field="grn_no_generate" data-editable="false">GRN No.</th>
                                                <th data-field="store_receipt_no_generate" data-editable="false">SR No.</th>
                                                <th data-field="store_remark" data-editable="false">Store Remark.</th>
                                                <th data-field="vendor_name" data-editable="false">Vendor Name</th>

                                                <th data-field="vendor_email" data-editable="false">Vendor Email Id</th>
                                                <th data-field="vendor_name" data-editable="false">Vendor Name</th>
                                                <th data-field="contact_no" data-editable="false">Vendor Contact No</th>
                                                <th data-field="vendor_address" data-editable="false">Vendor Address</th>
                                                <th data-field="gst_no" data-editable="false">GST No.</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($data_output as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->purchase_orders_id) }}</td>
                                                    <td>{{ ucwords($data->grn_no_generate) }}</td>
                                                    <td>{{ ucwords($data->store_receipt_no_generate) }}</td>
                                                    <td>{{ ucwords($data->store_remark) }}</td>
                                                    <td>{{ ucwords($data->vendor_name) }}</td>
                                                    <td>{{ ucwords($data->vendor_company_name) }}</td>
                                                    <td>{{ ucwords($data->vendor_email) }}</td>
                                                    <td>{{ ucwords($data->contact_no) }}</td>
                                                    <td>{{ ucwords($data->vendor_address) }}</td>
                                                    <td>{{ ucwords($data->gst_no) }}</td>
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <a
                                                                href="{{ route('accept-purchase-order-payment-release', [$data->purchase_orders_id, $data->id]) }} "><button
                                                                    data-toggle="tooltip" title="Check details"
                                                                    class="pd-setting-ed">Accept</button></a>


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
@endsection
