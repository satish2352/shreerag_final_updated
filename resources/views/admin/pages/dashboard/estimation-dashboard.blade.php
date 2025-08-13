<div class="product-sales-area mg-tb-30">
    <div class="row">
        <div class="col-xl-4 col-sm-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col mb-2">
                            <span
                                class="h6 font-semibold text-muted text-sm d-block mb-2">New Design</span>
                            <span
                                class="h5 font-bold mb-0">{{ $estimation_counts['design_received'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-new-requirements-received-for-estimation') }}">
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
                                class="h6 font-semibold text-muted text-sm d-block mb-2">Accepted BOM</span>
                            <span
                                class="h5 font-bold mb-0">{{ $estimation_counts['estimation_accepted_bom'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-accept-bom-estimation') }}">
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
                                class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected BOM</span>
                            <span
                                class="h5 font-bold mb-0">{{ $estimation_counts['estimation_rejected_bom'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-rejected-bom-estimation') }}">
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
                            <span
                                class="h6 font-semibold text-muted text-sm d-block mb-2">Send to Production</span>
                            <span
                                class="h5 font-bold mb-0">{{ $estimation_counts['estimation_send_tp_production'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div
                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                <i class="fa-solid fa-paint-brush"></i>

                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-send-to-production') }}">
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