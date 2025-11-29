@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Leave Request <span class="table-project-n">Data</span> Table</h1>
                        </div>
                    </div>

                    {{-- Session Alerts --}}
                    @if (Session::get('status') == 'success')
                        <div class="alert alert-success alert-success-style1">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
                        </div>
                    @endif
                    @if (Session::get('status') == 'error')
                        <div class="alert alert-danger alert-mg-b alert-success-style4">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="fa fa-times" aria-hidden="true"></i>
                            <p><strong>Error!</strong> {{ Session::get('msg') }}</p>
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
                                            <th>ID</th>
                                            <th>Employee Name</th>
                                            <th>Department</th>
                                            <th>Email</th>
                                            <th>Leave Start Date</th>
                                            <th>Leave End Date</th>
                                            <th>Leave Day</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getOutput as $data)
                                            <tr id="leave-row-{{ $data->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucwords($data->other_employee_name) }}</td>
                                                <td>{{ ucwords($data->role_name) }}</td>
                                                <td>{{ ucwords($data->u_email) }}</td>
                                                <td>{{ $data->leave_start_date }}</td>
                                                <td>{{ $data->leave_end_date }}</td>
                                                <td>
                                                    @if ($data->leave_day == 'half_day')
                                                        Half Day
                                                    @elseif($data->leave_day == 'full_day')
                                                        Full Day
                                                    @else
                                                        Unknown
                                                    @endif
                                                </td>
                                                <td>
                                                      <a href="{{ route('show-leaves', base64_encode($data->id)) }} "><button
                                                                data-toggle="tooltip" title="Trash"
                                                                class="pd-setting-ed"><i class="fa fa-eye"
                                                                    aria-hidden="true"></i></button></a>

                                                                    
                                                    <button type="button" class="approve-btn pd-setting-ed" data-id="{{ $data->id }}" data-action="approve" style="color: green; border: 1px solid;">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </button>
                                                    <button type="button" class="notapprove-btn pd-setting-ed" data-id="{{ $data->id }}" data-action="notapprove" style="color: red; border: 1px solid;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
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

    function updateLeaveStatus(leaveId, action) {

        $.ajax({
            url: "{{ route('update-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                active_id: leaveId,
                action: action
            },
            success: function(response) {

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 1200,
                    showConfirmButton: false
                });

                setTimeout(function() {
                    window.location.href = response.redirect;
                }, 1200);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong, please try again'
                });
            }
        });
    }

    $(document).on('click', '.approve-btn, .notapprove-btn', function() {

        var leaveId = $(this).data('id');
        var action = $(this).data('action');

        let message = action === "approve"
                        ? "Do you want to approve this leave?"
                        : "Do you want to reject this leave?";

        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                updateLeaveStatus(leaveId, action);
            }
        });
    });

});
</script>
@endpush
{{-- <script>
$(document).ready(function() {


  $(document).ready(function() {

    function updateLeaveStatus(leaveId, action) {

        $.ajax({
            url: "{{ route('update-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                active_id: leaveId,
                action: action
            },
            success: function(response) {

                let msg = action === "approve" 
                    ? "Leave approved successfully"
                    : "Leave rejected successfully";

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: msg,
                    timer: 1500,
                    showConfirmButton: false
                });

                $('#leave-row-' + leaveId).fadeOut(800, function() {
                    $(this).remove();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong, please try again'
                });
            }
        });
    }

    $(document).on('click', '.approve-btn, .notapprove-btn', function() {

        var leaveId = $(this).data('id');
        var action = $(this).data('action');

        // Set message based on action
        let message = action === "approve"
                        ? "Do you want to approve this leave?"
                        : "Do you want to reject this leave?";

        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                updateLeaveStatus(leaveId, action);
            }
        });
    });

});


   $(document).on('click', '.approve-btn, .notapprove-btn', function() {
        var leaveId = $(this).data('id');
        var action = $(this).data('action');

        // Confirm before performing action
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to approve this leave?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                updateLeaveStatus(leaveId, action);
            }
        });
    });

});
</script> --}}

@endsection
