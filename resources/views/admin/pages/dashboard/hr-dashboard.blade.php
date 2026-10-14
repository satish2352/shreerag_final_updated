<div class="product-sales-area mg-tb-30">
    <div class="row mb-3">
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #e6a817 !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Leave Request</span>
                            <span class="h5 font-bold mb-0">{{ $hr_counts['leave_request'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#e6a817;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-leaves-acceptedby-hr') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #28a745 !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Leave Approved</span>
                            <span class="h5 font-bold mb-0">{{ $hr_counts['accepted_leave_request'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#28a745;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-leaves-approvedby-hr') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #dc3545 !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Leave Not Approved</span>
                            <span class="h5 font-bold mb-0">{{ $hr_counts['rejected__leave_request'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#dc3545;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-leaves-not-approvedby-hr') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #17a2b8 !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Total Leaves Type</span>
                            <span class="h5 font-bold mb-0">{{ $hr_counts['total_leaves_type'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#17a2b8;">
                                <i class="fas fa-calendar-days"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-yearly-leave-management') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #1a3a6b !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Total Notice</span>
                            <span class="h5 font-bold mb-0">{{ $hr_counts['total_notice'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#1a3a6b;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-notice') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #6c757d !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Total Employee</span>
                            <span class="h5 font-bold mb-0">{{ $hr_counts['total_employee'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#6c757d;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-employee') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if (!empty($employee_counts['user_leaves_status']))
        @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
        @endif
    </div>

</div>
