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
#table thead th{
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
.btncolor{
    color: gray !important;
}
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Material Received From Store Department BusinessWise<span class="table-project-n"></span></h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2" >
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
                            {{-- <div id="toolbar">
                                <select class="form-control">
                                    <option value="">Export Basic</option>
                                    <option value="all">Export All</option>
                                    <option value="selected">Export Selected</option>
                                </select>
                            </div>                          --}}
                           
                          
                            <div class="table-responsive"> 
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                    data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                    data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="id">Sr.No.</th> 
                                            <th data-field="product_name" data-editable="false">Product Name</th>
                                            <th data-field="quantity" data-editable="false">Quantity</th>
                                            <th data-field="completed_quantity" data-editable="false">Completed Production</th>
                                            <th data-field="remaining_quantity" data-editable="false">Balance Quantity</th>
                                            <th data-field="grn_date" data-editable="false">Description</th>
                                            <th data-field="action" data-editable="false">Action</th>

                                        </tr>

                                    </thead>
                                  
                                    <tbody>
                                        @foreach($data_output as $data)
    @php
        $disableButton = ($data->completed_quantity >= $data->quantity);
    @endphp

    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ ucwords($data->product_name) }}</td>
        <td>{{ $data->quantity }}</td>
        <td>{{ $data->completed_quantity }}</td>
        <td>{{ $data->remaining_quantity }}</td>
        <td>{{ ucwords($data->description) }}</td>
        <td>
            <div style="display: inline-block; align-items: center;">
                @if ($disableButton)
                    <button data-toggle="tooltip" title="Production Completed" class="pd-setting-ed btncolor" disabled>
                        <i class="fa fa-check" aria-hidden="true"></i> Edit Product
                    </button>
                    <div style="display: inline-block; align-items: center; margin-top: 10px;">
                        <a href="{{ route('edit-recived-bussinesswise-quantity-tracking', $data->business_details_id) }}">
                            <button data-toggle="tooltip" title="View Details" class="pd-setting-ed btncolor" disabled>
                                Production Completed
                            </button>
                        </a>
                    </div>
                @else
                    <a href="{{ route('edit-recived-inprocess-production-material', $data->id) }}">
                        <button data-toggle="tooltip" title="Edit Product" class="pd-setting-ed">
                            <i class="fa fa-check" aria-hidden="true"></i> Edit Product
                        </button>
                    </a>
                    <div style="display: inline-block; align-items: center; margin-top: 10px;">
                        <a href="{{ route('edit-recived-bussinesswise-quantity-tracking', $data->business_details_id) }}">
                            <button data-toggle="tooltip" title="View Details" class="pd-setting-ed">View Details</button>
                        </a>
                    </div>
                @endif
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
