<div class="product-sales-area mg-tb-30">
    <div class="row">
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #1a3a6b !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Total Employee</span>
                            <span class="h5 font-bold mb-0">{{ $hr_counts['total_employee'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#1a3a6b;">
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
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span
                                class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                Department</span>
                            <span
                                class="h5 font-bold mb-0">{{ $department_count['department_total_count'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fas fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-roles') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i> </span>
                            <span class="text-nowrap text-xs text-muted">View
                                Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>