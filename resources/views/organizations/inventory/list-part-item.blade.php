<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
<style>
.fixed-table-loading {
    display: none;
} 
.disabled {
    pointer-events: none; /* Prevent clicks */
    opacity: 0.5; /* Visual indication of being disabled */
}

/*
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
} */
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Material <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2" >
                                        <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('add-product-stock') }}" ><button class="btn btn-sm btn-primary login-submit-cs" type="submit" href="{{route('add-product-stock')}}">Add Stock</button></a>
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
                                            {{-- <th data-field="part_number" data-editable="false">Part Number</th> --}}
                                            <th data-field="description" data-editable="false">Description</th>
                                            <th data-field="quantity" data-editable="false">Quantity</th>
                                            <th data-field="unit_id" data-editable="false">Unit</th>
                                            <th data-field="hsn_id" data-editable="false">HSN</th>
                                            <th data-field="group_type_id" data-editable="false">Group</th>
                                            <th data-field="action">Action</th>
                                            {{-- <th data-field="extra_description" data-editable="false">Extra Description</th>
   
                                            <th data-field="basic_rate" data-editable="false">Basic Rate</th>
                                            <th data-field="opening_stock" data-editable="false">Open Stock</th> --}}
                                            {{--  --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($getOutput as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            {{-- <td>{{ucwords($data->part_number)}}</td> --}}
                                            <td>{{ucwords($data->description)}}</td>
                                            <td>
                                                @if(is_null($data->quantity))
                                                    {{ ucwords($data->opening_stock) }}
                                                @else
                                                    {{ ucwords($data->quantity) }}
                                                @endif
                                            </td>
                                            <td>{{ucwords($data->name)}}</td>
                                            <td>{{ucwords($data->hsn_name)}}</td>
                                            <td>{{ucwords($data->group_name)}}</td>
                                            {{-- <td>{{ucwords($data->extra_description)}}</td>
                                          
                                       
                                           
                                            <td>{{ucwords($data->basic_rate)}}</td>
                                            <td>{{ucwords($data->opening_stock)}}</td> --}}
                                          
                                          
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        @if(is_null($data->quantity))
                                                            <a href="#" data-toggle="tooltip" title="Edit" class="pd-setting-ed disabled">
                                                                <button class="disabled" disabled>
                                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                                </button>
                                                            </a>
                                                        @else
                                                            <a href="{{route('edit-product-stock', base64_encode($data->id))}}">
                                                                <button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
                                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                                </button>
                                                            </a>
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
