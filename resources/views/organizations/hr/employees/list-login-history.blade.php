@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list" style="padding-bottom: 100px">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Login History List</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control"
                                                    placeholder="Search Name / Email / Location / IP Address">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar"> --}}

                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>latitude</th>
                                                <th>longitude</th>
                                                <th>Location Address</th>
                                                <th>IP Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($register_user as $item)
                                                <tr>
                                                    <td>{{ $register_user->firstItem() + $loop->index }}</td>
                                                    <td>
                                                        {{ $item->updated_at ? $item->updated_at->format('d-m-Y h:i:s A') : 'N/A' }}
                                                    </td>
                                                    <td>{{ $item->f_name }} {{ $item->m_name }} {{ $item->l_name }}
                                                        ({{ $item->u_email }})
                                                    </td>
                                                    <td>{{ $item->latitude }}</td>
                                                    <td>{{ $item->longitude }}</td>
                                                    <td>{{ $item->location_address }}</td>
                                                    <td>{{ $item->ip_address }}</td>
                                                    {{-- <td class="d-flex">
                                                        <div style="display: flex; align-items: center;">
                                                            <a href="{{ route('show-login-history', base64_encode($item->id)) }} "><button
                                                                    data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed"><i class="fa fa-eye"
                                                                        aria-hidden="true"></i></button></a>
                                                        </div>
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p>
                                                Showing {{ $register_user->firstItem() }} to
                                                {{ $register_user->lastItem() }}
                                                of {{ $register_user->total() }} rows
                                            </p>
                                        </div>

                                        <div class="col-md-6 d-flex justify-content-end mt-3">
                                            {{ $register_user->onEachSide(1)->links() }}
                                        </div>
                                    </div>
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
            $('.show-btn').click(function(e) {
                alert('hii');
                $("#show_id").val($(this).attr("data-id"));
                $("#showform").submit();
            })
        </script>
        <form method="POST" action="{{ url('/show-login-history') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
    @endpush
@endsection
