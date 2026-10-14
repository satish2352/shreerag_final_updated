<div class="product-sales-area mg-tb-30">
    <div class="mb-3">
        <small class="text-muted font-semibold" style="font-size:12px; letter-spacing:0.5px;">
            ESTIMATION PIPELINE &rarr; New Design &rarr; BOM Review &rarr; Send to Production
        </small>
    </div>
    <div class="row mb-3">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #6c757d !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">New Design</span>
                            <span class="h5 font-bold mb-0">{{ $estimation_counts['design_received'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#6c757d;">
                                <i class="fas fa-inbox"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-new-requirements-received-for-estimation') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #28a745 !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Accepted BOM</span>
                            <span class="h5 font-bold mb-0">{{ $estimation_counts['estimation_accepted_bom'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#28a745;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-accept-bom-estimation') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-xl-3 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #dc3545 !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Rejected BOM</span>
                            <span class="h5 font-bold mb-0">{{ $estimation_counts['estimation_rejected_bom'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#dc3545;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-rejected-bom-estimation') }}">
                            <span class="badge badge-pill bg-soft-success text-success mr-2">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text-nowrap text-xs text-muted">View Details</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
          <div class="col-xl-3 col-sm-6 col-12">
            <div class="card shadow border-0 h-100" style="border-left:4px solid #20a38e !important;">
                <div class="card-body">
                    <div class="row border-bottom align-items-center pb-2 mb-2">
                        <div class="col">
                            <span class="h6 font-semibold text-muted text-sm d-block mb-1">Production</span>
                            <span class="h5 font-bold mb-0">{{ $estimation_counts['estimation_send_tp_production'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#20a38e;">
                                <i class="fas fa-industry"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-0 text-sm">
                        <a href="{{ route('list-send-to-production') }}">
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

</div>
