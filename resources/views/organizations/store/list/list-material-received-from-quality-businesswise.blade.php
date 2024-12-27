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
        .disabled-btn {
        background-color: #ccc;  /* Light gray background */
        color: #666;             /* Darker gray text */
        cursor: not-allowed;     /* Show not-allowed cursor */
        opacity: 0.7;            /* Slightly transparent */
    }

    /* Style for enabled buttons */
    .enabled-btn {
        background-color: #28a745; /* Green background */
        color: black;              /* White text */
    }
    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Material Need To Sent To<span class="table-project-n"> Production</span> Department</h1>
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
                                {{-- <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Export Basic</option>
                                        <option value="all">Export All</option>
                                        <option value="selected">Export Selected</option>
                                    </select>
                                </div> --}}


                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                
                                                <th data-field="id">ID</th>
                                                <th data-field="grn_number" data-editable="false">PO Number</th>
                                                <th data-field="grn_date" data-editable="false">Title</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="grn" data-editable="false">GRN</th>
                                                {{-- <th data-field="purchase_id" data-editable="false">Remark</th> --}}
                                                {{-- <th data-field="design_image" data-editable="false">Design Layout</th>
                                                <th data-field="bom_image" data-editable="false">BOM</th> --}}
                                                {{-- <th data-field="action" data-editable="false">Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                       <?php
                                    //    dd($data_output );
                                    //    die();
                                       ?>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->purchase_orders_id) }}</td>
                                                    <td>{{ ucwords($data->Title) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    {{-- <td>{{ ucwords($data->remarks) }}</td> --}}
                                                    {{-- <td> <a class="img-size" target="_blank"
                                                        href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                        alt="Design"> Click to view</a>
                                                </td>
                                                <td> <a class="img-size"
                                                        href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                        alt="bill of material" >Click to download</a>
                                                </td> --}}
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <a href="{{ route('list-grn-details', [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id), base64_encode($data->id)]) }}">
                                                            <button data-toggle="tooltip" title="GRN Details" class="pd-setting-ed">GRN Details</button>
                                                        </a>
                                                       
                                                    </div>
                                                </td>
                                                    {{-- <td> --}}
                                                        {{-- <div style="display: flex; align-items: center;">
                                                            <a
                                                                href="{{ route('accepted-store-material-sent-to-production', base64_encode($data->purchase_orders_id)/base64_encode($data->business_id)) }} "><button
                                                                    data-toggle="tooltip" title="Forwared For production"
                                                                    class="pd-setting-ed">Forwared For production  </button></a>

                                                        </div> --}}
                                                        {{-- <div style="display: inline-block; align-items: center;">
                                                            <a href="{{route('edit-material-list-bom-wise',[base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id)])}}"><button data-toggle="tooltip" title="View Details" class="pd-setting-ed"><i class="fa fa-check" aria-hidden="true"></i>Edit Product Material</button></a>
                                                        </div> --}}
                                                        
                                                        {{-- <div style="display: flex; align-items: center;">
                                                            <a href="{{ route('accepted-store-material-sent-to-production', [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id)]) }}">
                                                                <button data-toggle="tooltip" title="Forwarded For Production" class="pd-setting-ed">Forwarded For Production</button>
                                                            </a>
                                                        </div> --}}
                                                        {{-- @if($data->material_send_production == 1)
                                                        <a href="{{ route('accepted-store-material-sent-to-production',  [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id)]) }}">
                                                            <button class="pd-setting-ed enabled-btn" title="Requirement forwarded for production">Requirement forwarded For production</button>
                                                        </a>
                                                    @else
                                                    <button class="pd-setting-ed disabled-btn" style="margin-top:10px;" title="Requirement forwarded for production" disabled>Requirement forwarded For production</button>  
                                                    @endif --}}
                                                    {{-- </td> --}}

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
