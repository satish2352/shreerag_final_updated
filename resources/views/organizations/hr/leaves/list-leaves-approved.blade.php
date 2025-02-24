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
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Approved Leaves  <span class="table-project-n">Data</span> Table</h1>
                            <div class="form-group-inner login-btn-inner row">
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
                                    data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                    data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="id">ID</th>
                                            <th data-field="employee_id" data-editable="false">Employee Name</th>
                                            <th data-field="department" data-editable="false">Department</th>
                                            <th data-field="u_email" data-editable="false">Email</th>
                                            <th data-field="leave_start_date" data-editable="false">Leave Start Date</th>
                                            <th data-field="leave_end_date" data-editable="false">Leave End Date</th>
                                            <th data-field="leave_day" data-editable="false">Leave Day</th>
                                            <th data-field="leave_type_id" data-editable="false">Leave Type</th>
                                            <th data-field="leave_count" data-editable="false">Leave Count</th>
                                            <th data-field="reason" data-editable="false">Reason</th>
                                            <th data-field="action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($getOutput as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ ucwords($data->other_employee_name) }}</td>
                                            <td>{{ ucwords($data->role_name) }}</td>
                                            <td>{{ ucwords($data->u_email) }}</td>
                                            <td>{{ ucwords($data->leave_start_date) }}</td>
                                            <td>{{ ucwords($data->leave_end_date) }}</td>
                                            <td> @if($data->leave_day == 'half_day')
                                                Half Day
                                              @elseif($data->leave_day == 'full_day')
                                             Full Day
                                              @else
                                                  Unknown Status
                                              @endif</td>
                                              <td>{{ ucwords($data->leave_type_name) }}</td>
                                              <td>{{ ucwords($data->leave_count) }}</td>
                                            <td>{{ ucwords($data->reason) }}</td>
                                            <td>
                                                <div style="display: flex; align-items: center;">
                                                    <button data-id="{{ $data->id }}" data-action="notapprove" data-toggle="tooltip" title="Not Approve" class="notapprove-btn pd-setting-ed" style="color: red;
                                                        border: 1px solid;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
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
<form method="POST" action="{{ url('/hr/update-status') }}" id="activeform">
    @csrf
    <input type="hidden" name="active_id" id="active_id" value="">
    <input type="hidden" name="action" id="action" value="">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.approve-btn, .notapprove-btn').click(function(e) {
            var leaveId = $(this).data('id');
            var action = $(this).data('action');
            $("#active_id").val(leaveId);
            $("#action").val(action);
            $("#activeform").submit();
        });
    });
</script>
@endsection
