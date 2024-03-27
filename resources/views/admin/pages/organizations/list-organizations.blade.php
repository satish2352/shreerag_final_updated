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
  .custom-datatable-overright table tbody tr td a {
    color: blue;
    text-decoration: underline;
    cursor: pointer;
  }

  .custom-datatable-overright table tbody tr td a:hover {
    color: red;
  }
</style>
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Organization <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2" >
                                        <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('add-organizations') }}" ><button class="btn btn-sm btn-primary login-submit-cs" type="submit" href="{{route('add-organizations')}}">Add Organization</button></a>
                                        </div>
                                    </div>
                                <div class="col-lg-10"></div>
                            </div>
                        </div>
                    </div>
                      @if (Session::get('status') == 'success')
                           <div class="alert alert-success alert-success-style1 alert-st-bg">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
										<span class="icon-sc-cl" aria-hidden="true">×</span>
									</button>
                                <i class="fa fa-check adminpro-checked-pro admin-check-pro admin-check-pro-clr" aria-hidden="true"></i>
                                <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
                            </div>
                             @endif
                            @if (Session::get('status') == 'error')
                              <div class="alert alert-danger alert-mg-b alert-success-style4 alert-success-stylenone">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
										<span class="icon-sc-cl" aria-hidden="true">×</span>
									</button>
                                <i class="fa fa-times adminpro-danger-error admin-check-pro admin-check-pro-none" aria-hidden="true"></i>
                                <p class="message-alert-none"><strong>Danger!</strong>{{ Session::get('msg') }}</p>
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
                                            
                                            <th data-field="">#</th>
                                            <th data-field="id">ORG ID</th>
                                            <th data-field="company_name" data-editable="false">Company Name</th>
                                            <th data-field="email" data-editable="false">Email</th>
                                            <!-- <th data-field="password" data-editable="false">Password</th> -->
                                            <th data-field="mobile_number" data-editable="false">Mobile Number</th>
                                            <th data-field="address" data-editable="false">Address</th>
                                            <th data-field="employee_count" data-editable="false">Employee Count</th>
                                            <th data-field="founding_date" data-editable="false">Founding Date</th>
                                            <th data-field="website" data-editable="false">Website Link</th>
                                            <th data-field="instagram_link" data-editable="false">Instagram Link</th>
                                            <th data-field="facebook_link" data-editable="false">Faceboook Link</th>
                                            <th data-field="twitter_link" data-editable="false">Twitter Link</th>
                                            <th data-field="image" data-editable="false">Image</th>
                                            <!-- <th data-field="is_active" data-editable="false">Is Active</th> -->
                                            <th data-field="action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($getOutput as $data)
                                        <tr>
                                            
                                            <td>{{ $loop->iteration }}</td>
                                            <td>ORG-{{ucwords($data->id)}}</td>
                                            <td><a href="{{route('organization-details', base64_encode($data->id))}}">{{ucwords($data->company_name)}}</a></td>
                                            <td>{{ucwords($data->email)}}</td>
                                            <!-- <td style="width:299px !important;">{{ucwords($data->password)}}</td> -->
                                            <td>{{ucwords($data->mobile_number)}}</td>
                                            <td>{{ucwords($data->address)}}</td>
                                            <td>{{ucwords($data->employee_count)}}</td>
                                            <td>{{ucwords($data->founding_date)}}</td>
                                            <td>{{ucwords($data->website)}}</td>
                                            <td>{{ucwords($data->instagram_link)}}</td>
                                            <td>{{ucwords($data->facebook_link)}}</td>
                                            <td>{{ucwords($data->twitter_link)}}</td>
                                            <td><img style="max-width:250px; max-height:150px;" src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') . $data->image }}" alt="{{ strip_tags($data['company_name']) }} Image" /></td>
                                            <td>
                                                <div style="display: flex; align-items: center;">
                                                    <a href="{{route('edit-organizations', base64_encode($data->id))}}"><button data-toggle="tooltip" title="Edit" class="pd-setting-ed"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                                    <a href="{{route('delete-organizations', base64_encode($data->id))}} "><button data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
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
<!-- Use a jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    $('#table').DataTable({
        "columnDefs": [
            {
                "targets": 'company-name-column',
                "width": '400px',
                "className": 'company-name-cell'
            }
        ]
    });
});

</script>
@endsection
