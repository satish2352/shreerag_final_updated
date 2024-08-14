@extends('admin.layouts.master')

@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Notice Board<span class="table-project-n"></span> </h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        <div class="login-horizental cancel-wp pull-left">
                                            <a href="{{ route('add-notice') }}"><button
                                                    class="btn btn-sm btn-primary login-submit-cs" type="submit">Add
                                                    Notice</button></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10"></div>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Export Basic</option>
                                        <option value="all">Export All</option>
                                        <option value="selected">Export Selected</option>
                                    </select>
                                </div>


                                @include('admin.layouts.alert')
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Department Name</th>
                                                <th>Title</th>
                                                <th>Image</th>
                                                {{-- <th>Status</th> --}}
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getOutput as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ strip_tags($item->department_name) }}</td>
                                                    <td>{{ strip_tags($item->title) }}</td>

                                                    <td> <a class="img-size" target="_blank"
                                                        href="{{ Config::get('DocumentConstant.NOTICE_VIEW') }}{{ $item['image'] }}"
                                                        alt="Design"> Click to view</a>
                                                </td>
                                                    {{-- <td> <img class="img-size"
                                                            src="{{ Config::get('DocumentConstant.NOTICE_VIEW') }}{{ $item->image }}"
                                                            alt=" {{ strip_tags($item['title']) }} Image" />
                                                    </td> --}}


                                                    {{-- <td>
                                                        <label class="switch">
                                                            <input data-id="{{ $item->id }}" type="checkbox"
                                                                {{ $item->is_active ? 'checked' : '' }}
                                                                class="active-btn btn btn-sm btn-outline-primary m-1"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                                            <span class="slider round"></span>
                                                        </label>

                                                    </td> --}}
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ route('edit-notice', base64_encode($item->id)) }}"
                                                                class="btn btn-sm btn-outline-primary m-1"
                                                                title="Edit Slide"><i class="fas fa-pencil-alt"></i></a>

                                                            <a data-id="{{ $item->id }}"
                                                                class="show-btn btn btn-sm btn-outline-primary m-1"
                                                                title="Show Slide "><i class="fas fa-eye"></i></a>
                                                            <a
                                                                href="{{ route('delete-notice', base64_encode($item->id)) }} "><button
                                                                    data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed"><i class="fa fa-trash"
                                                                        aria-hidden="true"></i></button></a>
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

    {{-- <form method="POST" action="{{ url('/delete-notice') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form> --}}
    <form method="POST" action="{{ url('/show-notice') }}" id="showform">
        @csrf
        <input type="hidden" name="show_id" id="show_id" value="">
    </form>
    <form method="POST" action="{{ url('/update-active-notice') }}" id="activeform">
        @csrf
        <input type="hidden" name="active_id" id="active_id" value="">
    </form>



    <!-- content-wrapper ends -->
@endsection
