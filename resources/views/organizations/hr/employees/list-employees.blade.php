
@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list" style="padding-bottom: 100px">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Employee <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        <div class="login-horizental cancel-wp pull-left">
                                            <a href="{{ route('add-employee') }}"><button
                                                    class="btn btn-sm btn-primary login-submit-cs" type="submit">Add
                                                    Employee</button></a>
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
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">

                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Name</th>
                                                <th>Department</th>
                                                {{-- <th>latitude</th>
                                                <th>longitude</th> --}}
                                                <th>Leave Details</th>
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
                                                    {{-- <td>{{ $item->latitude ?? '-' }}</td> --}}
                                                    {{-- <td>{{ $item->longitude  ?? '-'}}</td> --}}
                                                    
                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            <a href="{{ route('users-leaves-details', base64_encode($item->id)) }} "><button
                                                                    data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed"><i class="fa fa-eye"
                                                                        aria-hidden="true"></i></button></a>
                                                        </div>
                                                        
                                                    </td>
                                                    <td class="d-flex">
                                                        <div style="display: flex; align-items: center;">
                                                            <a href="{{ route('show-employee', base64_encode($item->id)) }} "><button
                                                                    data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed"><i class="fa fa-eye"
                                                                        aria-hidden="true"></i></button></a>

                                                            <a href="{{ route('edit-employee', base64_encode($item->id)) }}"><button
                                                                    data-toggle="tooltip" title="Edit"
                                                                    class="pd-setting-ed"><i class="fas fa-pen-square"
                                                                        aria-hidden="true"></i></button></a>


                                                                        @if ($item->id > 14)
                                                                        <a href="{{ route('delete-employee', base64_encode($item->id)) }}">
                                                                            <button data-toggle="tooltip" title="Delete" class="pd-setting-ed delete-btn">
                                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                                            </button>
                                                                        </a>
                                                                    @else
                                                                        <button data-toggle="tooltip" title="Delete (Disabled)" class="pd-setting-ed btn-colour" disabled>
                                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                                        </button>
                                                                    @endif
                                                            {{-- <a
                                                                href="{{ route('delete-employee', base64_encode($item->id)) }} "><button
                                                                    data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed"><i class="fa fa-trash"
                                                                        aria-hidden="true"></i></button></a> --}}
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
     @push('scripts')
    <script>
        $(document).ready(function() {
            $(".delete-btn").click(function(e) {
                e.preventDefault();
                var deleteUrl = "{{ route('delete-employee', ':id') }}";
                var userId = $(this).data('id');
                deleteUrl = deleteUrl.replace(':id', userId);

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to delete route
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
    <script>
        $('.show-btn').click(function(e) {
            alert('hii');
            $("#show_id").val($(this).attr("data-id"));
            $("#showform").submit();
        })
    </script>
     <script>
        $('.leavesdetails-btn').click(function(e) {
            alert('hii');
            $("#leavesdetails_id").val($(this).attr("data-id"));
            $("#leavesdetailsform").submit();
        })
    </script>
    <form method="POST" action="{{ url('/show-employee') }}" id="showform">
        @csrf
        <input type="hidden" name="show_id" id="show_id" value="">
    </form>
    <form method="POST" action="{{ url('/users-leaves-details') }}" id="leavesdetailsform">
        @csrf
        <input type="hidden" name="leavesdetails_id" id="leavesdetails_id" value="">
    </form>
    @endpush
@endsection
