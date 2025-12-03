
@extends('admin.layouts.master')
@section('content')
    

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Leaves Request <span class="table-project-n">Data</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2">
                                        <div class="login-horizental cancel-wp pull-left">
                                            <a href="{{ route('add-leaves') }}"><button
                                                    class="btn btn-sm btn-primary login-submit-cs" type="submit">Add
                                                    Leaves</button></a>
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

                                                <th data-field="id">ID</th>
                                                <th data-field="other_employee_name" data-editable="false">Employee Name
                                                </th>
                                                <th data-field="leave_start_date" data-editable="false">Leave From Date
                                                </th>
                                                <th data-field="leave_end_date" data-editable="false">Leave To Date</th>
                                                <th data-field="leave_day" data-editable="false">Leave Day</th>
                                                <th data-field="leave_type_id" data-editable="false">Leave Type</th>
                                                <th data-field="leave_count" data-editable="false">Leave Count</th>
                                                <th data-field="reason" data-editable="false">Reason</th>
                                                <th data-field="is_approved" data-editable="false">Status</th>
                                                <th data-field="action">Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                         
                                            @foreach ($getOutput as $data)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->other_employee_name) }}</td>
                                                    <td>{{ ucwords($data->leave_start_date) }}</td>
                                                    <td>{{ ucwords($data->leave_end_date) }}</td>
                                                    <td>
                                                        @if ($data->leave_day == 'half_day')
                                                            Half Day
                                                        @elseif($data->leave_day == 'full_day')
                                                            Full Day
                                                        @else
                                                            Unknown Status
                                                        @endif
                                                    </td>
                                                    <td>{{ ucwords($data->leave_type_name) }}</td>
                                                    <td>{{ ucwords($data->leave_count) }}</td>
                                                    <td>{{ ucwords($data->reason) }}</td>
                                                    <td>
                                                        @if ($data->is_approved == 0)
                                                            Send for Approval
                                                        @elseif($data->is_approved == 1)
                                                            Not Approved
                                                        @elseif($data->is_approved == 2)
                                                            Approved
                                                        @else
                                                            Unknown Status
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div style="display: flex; align-items: center;">
                                                            @if ($data->is_approved == 0)
                                                                <a
                                                                    href="{{ route('edit-leaves', base64_encode($data->id)) }}">
                                                                    <button data-toggle="tooltip" title="Edit"
                                                                        class="pd-setting-ed"
                                                                        style="color: green;
                                                            border: 1px solid;">
                                                                        <i class="fas fa-pen-square"
                                                                            aria-hidden="true"></i>
                                                                    </button>
                                                                </a>
                                                                {{-- <a
                                                                    href="{{ route('delete-leaves', base64_encode($data->id)) }}">
                                                                    <button data-toggle="tooltip" title="Trash"
                                                                        class="pd-setting-ed"
                                                                        style="color: red;
                                                            border: 1px solid;">
                                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                                    </button>
                                                                </a> --}}

                                                                {{-- <button data-toggle="tooltip" title="Trash"
                                                        class="pd-setting-ed delete-btn"
                                                        data-url="{{ route('delete-leaves', base64_encode($data->id)) }}"
                                                        style="color: red; border: 1px solid;">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button> --}}
                                                    <form class="delete-form" action="{{ route('delete-leaves', base64_encode($data->id)) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="pd-setting-ed delete-btn" style="color: red; border: 1px solid;" title="Delete">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </form>

                                                            @else
                                                                <button data-toggle="tooltip" title="Edit"
                                                                    class="pd-setting-ed" disabled>
                                                                    <i class="fas fa-pen-square" aria-hidden="true"></i>
                                                                </button>
                                                                <button data-toggle="tooltip" title="Trash"
                                                                    class="pd-setting-ed" disabled>
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

    @push('scripts')
<script>
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();

        let form = $(this).closest("form");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // ✔ submit form properly
            }
        });
    });
</script>

   @endpush
@endsection
