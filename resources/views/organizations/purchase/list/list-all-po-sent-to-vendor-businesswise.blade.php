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
                                <h1>Purchase Order<span class="table-project-n">Sent To Vendor</span></h1>
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
                                                <th data-field="id">Sr.No.</th>
                                               


                                                <th data-field="purchase_orders_id" data-editable="false">Purchase Order ID</th>
                                                <th data-field="client_name" data-editable="false">Client Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Client Company Name</th>
                                                <th data-field="email" data-editable="false">Email</th>
                                                <th data-field="contact_no" data-editable="false">Phone Number</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                                {{-- <th data-field="vendor_id" data-editable="false">Vendor</th>                                         
                                            <th data-field="client_name" data-editable="false">Client Name</th>                                          --}}
                                            </tr>

                                        </thead>



                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    {{-- <td>{{$data['purchase_order_id']}}</td>
                                                    <td>{{ ucwords($data['product_name']) }}</td>
                                                    <td>{{ ucwords($data['descriptions']) }}</td> --}}


                                                    <td>{{ $data->purchase_order_id }}</td>
                                                    <td>{{ $data->vendor_name }}</td>
                                                   <td>{{ $data->vendor_company_name }}</td>
                                                   <td>{{ $data->vendor_email }}</td> 
                                                   <td>{{ $data->contact_no }}</td> 
                                                   <td>
                                                    <div style="display: inline-block; align-items: center;">
                                                        <a
                                                        href="{{ route('check-details-of-po-before-send-vendor', $data->purchase_order_id) }}"><button
                                                                data-toggle="tooltip" title="View Details"
                                                                class="pd-setting-ed"> View Details</button></a> &nbsp;
                                                        &nbsp; &nbsp;
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
