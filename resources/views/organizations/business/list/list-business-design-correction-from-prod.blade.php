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
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Design Correction ask by production <span class="table-project-n">Data</span> Table</h1>
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
                                    data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="id">Sr.No.</th> 
                                            <th data-field="grn_number" data-editable="true">Title</th>
                                            <th data-field="grn_date" data-editable="true">Description</th>
                                            <th data-field="purchase_id" data-editable="true">Remark</th>                                         
                                            <th data-field="reject_reason" data-editable="true">Reject Reason</th>                                         
                                            <th data-field="design_image" data-editable="false">Design Layout</th>
                                            <th data-field="bom_image" data-editable="false">BOM</th>                                                                                                                           
                                        </tr>

                                    </thead>

                                   

                                    <tbody>
                                        @foreach($data_output as $data)
                                        <?php //dd($data['design_image']); ?>
                                        <tr>
                                            
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ucwords($data->title)}}</td>
                                            <td>{{ucwords($data->descriptions)}}</td>
                                            <td>{{ucwords($data->remarks)}}</td>
                                            <td>{{ucwords($data->reject_reason_prod)}}</td>
                                            <td> <img class="img-size"
                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                alt="No Image" />
                                        </td>
                                        <td> <img class="img-size"
                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                alt="No Image" />
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
