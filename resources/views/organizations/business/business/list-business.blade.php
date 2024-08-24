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
                            <h1>Business <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2" >
                                        <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('add-business') }}" ><button class="btn btn-sm btn-primary login-submit-cs" type="submit" >Add Business</button></a>
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
                            <div id="toolbar">
                                <select class="form-control">
                                    <option value="">Export Basic</option>
                                    <option value="all">Export All</option>
                                    <option value="selected">Export Selected</option>
                                </select>
                            </div>
                            <div class="table-responsive">

                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                    data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                    data-key-events="true" data-show-toggle="true" data-resizable="true"
                                    data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                    data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="id">Sr.No.</th>
                                            <th data-field="customer_po_number" data-editable="true">PO Number</th>
                                            {{-- <th data-field="product_name" data-editable="true">Product Name</th> --}}
                                            <th data-field="title" data-editable="true">Name</th>
                                            {{-- <th data-field="quantity" data-editable="true">Quantity</th> --}}
                                            {{-- <th data-field="rate" data-editable="true">Rate</th> --}}
                                            <th data-field="po_validity" data-editable="true">PO Validity</th>
                                            {{-- <th data-field="hsn_number" data-editable="true">HSN Number</th> --}}
                                            <th data-field="customer_payment_terms" data-editable="true">Payment Terms
                                            </th>
                                            <th data-field="customer_terms_condition" data-editable="true">Terms
                                                Condition</th>
                                            <th data-field="remarks" data-editable="true">Remark</th>
                                            {{-- <th data-field="descriptions" data-editable="true">Description</th> --}}
                                            <th data-field="action">Action</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        @foreach($data_output as $data)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ucwords($data->customer_po_number)}}</td>
                                            {{-- <td>{{ucwords($data->product_name)}}</td> --}}
                                            <td>{{ucwords($data->title)}}</td>
                                            {{-- <td>{{ucwords($data->quantity)}}</td> --}}
                                            {{-- <td>{{ucwords($data->rate)}}</td> --}}
                                            <td>{{ucwords($data->po_validity)}}</td>
                                            {{-- <td>{{ucwords($data->hsn_number)}}</td> --}}
                                            <td>{{ucwords($data->customer_payment_terms)}}</td>
                                            <td>{{ucwords($data->customer_terms_condition)}}</td>
                                            <td>{{ucwords($data->remarks)}}</td>
                                            {{-- <td>{{ucwords($data->descriptions)}}</td> --}}


                                            <td>
                                                @if($data->is_approved_production == 1)
                                                <button data-toggle="tooltip" title="Edit" class="pd-setting-ed"
                                                    disabled>
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </button>
                                                <button data-toggle="tooltip" title="Trash" class="pd-setting-ed"
                                                    disabled>
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                                @else
                                                <div style="display: flex; align-items: center;">
                                                    <a href="{{route('edit-business', base64_encode($data->id))}}"><button
                                                            data-toggle="tooltip" title="Edit" class="pd-setting-ed"><i
                                                                class="fa fa-pencil-square-o"
                                                                aria-hidden="true"></i></button></a>
                                                    <a href="{{route('delete-business', base64_encode($data->id))}} "><button
                                                            data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></button></a>
                                                </div>
                                                @endif
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