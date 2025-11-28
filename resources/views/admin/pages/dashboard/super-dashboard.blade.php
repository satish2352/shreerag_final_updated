<div class="product-sales-area mg-tb-30">
    <div class="row">
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span
                                class="h6 font-semibold text-muted text-sm d-block mb-2">Total
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
                        <a href="{{ route('list-employee') }}">
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
                            <span
                                class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                Department</span>
                            <span
                                class="h5 font-bold mb-0">{{ $department_count['department_total_count'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-roles') }}">
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