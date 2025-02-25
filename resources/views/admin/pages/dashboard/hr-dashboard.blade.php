<div class="product-sales-area mg-tb-30">
    <!-- <div class="container-fluid"> -->
    <div class="row">
        <!-- Total Visit -->
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Leave
                                Request</span>
                            <span
                                class="h5 font-bold mb-0">{{ $hr_counts['leave_request'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-leaves-acceptedby-hr') }}">
                            <span class="badge badge-pill bg-soft-success text-success me-2">
                                <i class="fa-solid fa-arrow-right"></i> </span>
                            <span class="text-nowrap text-xs text-muted">View
                                Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Leave
                                Approved</span>
                            <span
                                class="h5 font-bold mb-0">{{ $hr_counts['accepted_leave_request'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-leaves-approvedby-hr') }}">
                            <span class="badge badge-pill bg-soft-success text-success me-2">
                                <i class="fa-solid fa-arrow-right"></i> </span>
                            <span class="text-nowrap text-xs text-muted">View
                                Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Leave
                                Not Approved</span>
                            <span
                                class="h5 font-bold mb-0">{{ $hr_counts['rejected__leave_request'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-leaves-not-approvedby-hr') }}">
                            <span class="badge badge-pill bg-soft-success text-success me-2">
                                <i class="fa-solid fa-arrow-right"></i> </span>
                            <span class="text-nowrap text-xs text-muted">View
                                Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                Leaves Type</span>
                            <span
                                class="h5 font-bold mb-0">{{ $hr_counts['total_leaves_type'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-yearly-leave-management') }}">
                            <span class="badge badge-pill bg-soft-success text-success me-2">
                                <i class="fa-solid fa-arrow-right"></i> </span>
                            <span class="text-nowrap text-xs text-muted">View
                                Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                Notice</span>
                            <span
                                class="h5 font-bold mb-0">{{ $hr_counts['total_notice'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-notice') }}">
                            <span class="badge badge-pill bg-soft-success text-success me-2">
                                <i class="fa-solid fa-arrow-right"></i> </span>
                            <span class="text-nowrap text-xs text-muted">View
                                Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                Employee</span>
                            <span
                                class="h5 font-bold mb-0">{{ $hr_counts['total_employee'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-users') }}">
                            <span class="badge badge-pill bg-soft-success text-success me-2">
                                <i class="fa-solid fa-arrow-right"></i> </span>
                            <span class="text-nowrap text-xs text-muted">View
                                Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>