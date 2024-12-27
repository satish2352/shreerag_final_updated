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
                <div class="sparkline13-list" style="padding-bottom: 100px">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Employee <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2" >
                                        <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('add-users') }}" ><button class="btn btn-sm btn-primary login-submit-cs" type="submit" >Add Employee</button></a>
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
                                            <th>Sr. No.</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($register_user as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->f_name }} {{ $item->m_name }} {{ $item->l_name }}
                                                    ({{ $item->u_email }})
                                                </td>
                                                <td>{{ $item->role_name }}</td>


                                                {{-- <td>
                                                    <label class="switch">
                                                        <input data-id="{{ $item->id }}" type="checkbox"
                                                            {{ $item->is_active ? 'checked' : '' }}
                                                            class="active-btn btn btn-sm btn-outline-primary m-1"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                                        <span class="slider round "></span>
                                                    </label>

                                                </td> --}}


                                                {{-- <td>@if ($item->is_active)
                                                <button type="button" class="btn btn-success btn-sm">Active</button>
                                                @else 
                                                <button type="button" class="btn btn-danger btn-sm">In Active</button>
                                                
                                                @endif</td> --}}
                                                <td class="d-flex">

                                                    <div style="display: flex; align-items: center;">
                                                        <a href="{{route('show-users', base64_encode($item->id))}} "><button data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i class="fa fa-eye" aria-hidden="true"></i></button></a>

                                                        <a href="{{route('edit-users', base64_encode($item->id))}}"><button data-toggle="tooltip" title="Edit" class="pd-setting-ed"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                                        {{-- <a data-id="{{ $item->id }}"
                                                            class="show-btn btn btn-sm btn-outline-primary m-1"><i
                                                                class="fas fa-eye"></i></a> --}}
                                                        <a href="{{route('delete-users', base64_encode($item->id))}} "><button data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                                    </div>








                                                    {{-- <a href="{{ route('edit-users', base64_encode($item->id)) }}"
                                                        class="edit-btn btn btn-sm btn-outline-primary m-1"><i
                                                            class="fas fa-pencil-alt"></i></a> --}}
                                                    {{-- <a data-id="{{ $item->id }}"
                                                        class="show-btn btn btn-sm btn-outline-primary m-1"><i
                                                            class="fas fa-eye"></i></a> --}}
                                                    {{-- <a data-id="{{ $item->id }}"
                                                        class="delete-btn btn btn-sm btn-outline-danger m-1"
                                                        title="Delete Tender"><i class="fas fa-archive"></i></a> --}}


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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        
    $('.show-btn').click(function(e) {
        alert('hii');
        $("#show_id").val($(this).attr("data-id"));
        $("#showform").submit();
    })
</script>
{{-- <form method="POST" action="{{ url('/delete-users') }}" id="deleteform">
    @csrf
    <input type="hidden" name="delete_id" id="delete_id" value="">
</form>
<form method="POST" action="{{ url('/show-users') }}" id="showform">
    @csrf
    <input type="hidden" name="show_id" id="show_id" value="">
</form> --}}
{{-- <form method="GET" action="{{ url('/edit-users') }}" id="editform">
    @csrf
    <input type="hidden" name="edit_id" id="edit_id" value="">
</form> --}}
{{-- <form method="POST" action="{{ url('/update-active-user') }}" id="activeform">
    @csrf
    <input type="hidden" name="active_id" id="active_id" value="">
</form> --}}
<form method="POST" action="{{ url('/show-users') }}" id="showform">
    @csrf
    <input type="hidden" name="show_id" id="show_id" value="">
</form>
@endsection
