
@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="margin:0; font-weight:600; color:#1a3a6b;">Leave Types Management</h5>
                            <a href="{{ route('add-yearly-leave-management') }}">
                                <button class="btn btn-sm btn-add-custom" type="button">
                                    <i class="fa fa-plus mr-1"></i> Add Leave Type
                                </button>
                            </a>
                        </div>
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <div class="table-responsive">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                    data-search-align="right"
                                    data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false"
                                    data-key-events="true" data-show-toggle="false" data-resizable="false" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false">
                                    <thead>
                                        <tr>
                                            
                                            <th data-field="id">ID</th>
                                            <th data-field="leave_year" data-editable="false">Year</th>
                                            <th data-field="name" data-editable="false">Name</th>
                                            <th data-field="leave_count" data-editable="false">Count</th>
                                            <th data-field="action">Action</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                       
                                        @foreach($getOutput as $data)
                                        <tr>
                                            
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ucwords($data->leave_year)}}</td>
                                            <td>{{ucwords($data->name)}}</td>
                                            <td>{{ucwords($data->leave_count)}}</td>
                                            <td>
                                                <div style="display: flex; align-items: center;">
                                                    @if($data->is_approved == 0)
                                                        <a href="{{ route('edit-yearly-leave-management', base64_encode($data->id)) }}">
                                                            <button data-toggle="tooltip" title="Edit" class="pd-setting-ed" style="color: green;
                                                            border: 1px solid;">
                                                                <i class="fas fa-pen-square" aria-hidden="true"></i>
                                                            </button>
                                                        </a>
                                                        {{-- <a href="{{ route('delete-yearly-leave-management', base64_encode($data->id)) }}">
                                                            <button data-toggle="tooltip" title="Trash" class="pd-setting-ed" style="color: red;
                                                            border: 1px solid;">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        </a> --}}

                                                        <a href="{{ route('delete-yearly-leave-management', base64_encode($data->id)) }}">
                                                            <button data-toggle="tooltip" title="Trash" class="pd-setting-ed" style="color: red; border: 1px solid;">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        </a>
                                                        
                                                    @else
                                                        <button data-toggle="tooltip" title="Edit" class="pd-setting-ed" disabled>
                                                            <i class="fas fa-pen-square" aria-hidden="true"></i>
                                                        </button>
                                                        <button data-toggle="tooltip" title="Trash" class="pd-setting-ed" disabled>
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </button>
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
