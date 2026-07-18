@extends('admin.layouts.master')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        @import url("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.4.0/font/bootstrap-icons.min.css");

        .btn-primary {
            background-color: #25385F;
            border-color: #25385F;
        }

        .btn-primary:hover {
            background-color: #25385F;
            border-color: #25385F;
        }

        .card {
            border: 0;
            border-radius: 0.375rem;
        }

        .card.shadow {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .d-flex {
            display: flex;
        }

        .flex-column {
            flex-direction: column;
        }

        .flex-lg-row {
            flex-direction: row;
        }

        .h-lg-full {
            height: 100%;
        }

        .h-screen {
            height: 100vh;
        }

        .overflow-y-lg-auto {
            overflow-y: auto;
        }

        .icon-shape {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
        }

        .bg-tertiary {
            background-color: #6c757d;
        }

        .bg-primary {
            background-color: #25385F;
        }

        .bg-info {
            background-color: #17a2b8;
        }

        .bg-warning {
            background-color: #31497D !important;
        }

        .text-white {
            color: #fff;
        }

        .badge-pill {
            border-radius: 50rem;
            float: right;
        }

        .bg-soft-success {
            background-color: #d4edda;
        }

        .bg-soft-danger {
            background-color: #f8d7da;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .h6 {
            font-size: 1.25rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-muted {
            color: #6c757d;
        }

        .text-nowrap {
            white-space: nowrap;
            font-size: 15px !important;
            font-weight: bold;
        }

        .text-nowrap a {
            text-decoration: none !important;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .pe-2 {
            padding-right: 0.5rem;
        }

        .px-0 {
            padding-left: 0;
            padding-right: 0;
        }

        .mx-n1 {
            margin-left: -0.25rem;
            margin-right: -0.25rem;
        }

        .mb-npx {
            margin-bottom: -0.5rem;
        }

        .text-sm a {
            text-decoration: none;
            font-size: larger;
        }

        /* ======================= */
        .right-side {
            margin-bottom: 5px;
            padding: 5px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .accordion-button {
            /* background-color: #dee2e6 !important; */
            background-color: #ffff !important;
            color: black;
            text-transform: capitalize;
        }

        .accordion-button.active {
            background-color: #243772 !important;
            color: white;
        }

        .offcanvas-body {
            overflow: scroll;
            height: 600px;
        }

        .sparkline12-hd {
            padding-top: 85px !important;
        }
    </style>


    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-hd">
                    @php
                        $role = session()->get('role_id');
                        $allowedRoles = [
                            config('constants.ROLE_ID.HR'),
                            config('constants.ROLE_ID.DESIGNER'),
                            config('constants.ROLE_ID.PRODUCTION'),
                            config('constants.ROLE_ID.STORE'),
                            config('constants.ROLE_ID.PURCHASE'),
                            config('constants.ROLE_ID.SECURITY'),
                            config('constants.ROLE_ID.QUALITY'),
                            config('constants.ROLE_ID.LOGISTICS'),
                            config('constants.ROLE_ID.FINANCE'),
                            config('constants.ROLE_ID.DISPATCH'),
                            config('constants.ROLE_ID.EMPOLYEE'),
                        ];
                    @endphp
                    @if ($role == config('constants.ROLE_ID.HR'))
                        @include('admin.pages.dashboard.hr-dashboard')
                    @elseif ($role == config('constants.ROLE_ID.SUPER'))
                        @include('admin.pages.dashboard.super-dashboard')
                    @elseif ($role == config('constants.ROLE_ID.DESIGNER'))
                        <div class="analysis-progrebar-area mg-b-15">

                            {{-- Pipeline header --}}
                            <div class="mb-3">
                                <small class="text-muted font-semibold" style="font-size:12px; letter-spacing:0.5px;">
                                    DESIGN PIPELINE &#8594; Receive &#8594; Submit to Estimation &#8594; To Production
                                    &#8594; Accepted / Rejected &#8594; Correct &amp; Resubmit
                                </small>
                            </div>

                            {{-- ROW 1: Pipeline status cards --}}
                            <div class="row g-3 mb-3">

                                {{-- 1. New For Design --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#6c757d; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-inbox"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $design_dept_counts['business_received_for_designs'] ?? 0 }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">New<br>For
                                                Design</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-new-requirements-received-for-design') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &#8594;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Sent to Estimation --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#1a3a6b; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $design_dept_counts['designs_sent_to_estimation'] ?? 0 }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">Sent
                                                to<br>Estimation</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-design-upload') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &#8594;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Sent to Production --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #17a2b8 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#17a2b8; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-industry"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $design_dept_counts['design_sent_for_production'] ?? 0 }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">Sent
                                                to<br>Production</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-design-upload') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &#8594;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 4. Accepted by Production --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #28a745 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#28a745; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $design_dept_counts['accepted_design_production_dept'] ?? 0 }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">Accepted
                                                by<br>Production</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-accept-design-by-production') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &#8594;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 5. Rejected by Production --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #dc3545 !important; {{ ($design_dept_counts['rejected_design_production_dept'] ?? 0) > 0 ? 'background:#fff8f8;' : '' }}">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#dc3545; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1"
                                                style="{{ ($design_dept_counts['rejected_design_production_dept'] ?? 0) > 0 ? 'color:#dc3545;' : '' }}">
                                                {{ $design_dept_counts['rejected_design_production_dept'] ?? 0 }}
                                            </div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">Rejected
                                                by<br>Production</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-reject-design-from-prod') }}"
                                                    style="font-size:11px; color:#dc3545;">View &#8594;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 6. Corrected & Resubmitted --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #20a38e !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#20a38e; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-rotate"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $design_dept_counts['corrected_design_sent'] ?? 0 }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">Corrected
                                                &amp;<br>Resubmitted</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-updated-design') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &#8594;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if (!empty($employee_counts['user_leaves_status']))
                                @include('admin.pages.dashboard.leave-chart', [
                                    'leaveData' => $employee_counts['user_leaves_status'],
                                ])
                            @endif
                            @php $department_leaves = $employee_counts['user_leaves_status'][$role] ?? []; @endphp
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.PRODUCTION'))
                        <div class="analysis-progrebar-area mg-b-15">

                            {{-- Pipeline header --}}
                            <div class="mb-3">
                                <small class="text-muted font-semibold" style="font-size:12px; letter-spacing:0.5px;">
                                    PRODUCTION PIPELINE &#8594; New Design &#8594; Accept / Reject &#8594; Revised &#8594;
                                    Material In &#8594; Completed
                                </small>
                            </div>

                            {{-- Row 1: New Design / Accepted / Rejected --}}
                            <div class="row g-3 mb-3">

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">New
                                                        Design Received</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $production_dept_counts['design_recived_for_production'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#6c757d;">
                                                        <i class="fa-solid fa-file-import"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a
                                                    href="{{ route('list-new-requirements-received-for-production-business-wise') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #28a745 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Design
                                                        Accepted</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $production_dept_counts['accepted_and_sent_to_store'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#28a745;">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-accept-design') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0"
                                        style="border-left:4px solid #dc3545 !important; {{ $production_dept_counts['rejected_design_list_sent'] > 0 ? 'background:#fff8f8;' : '' }}">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Design
                                                        Rejected</span>
                                                    <span class="h4 font-bold mb-0"
                                                        style="{{ $production_dept_counts['rejected_design_list_sent'] > 0 ? 'color:#dc3545;' : '' }}">{{ $production_dept_counts['rejected_design_list_sent'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#dc3545;">
                                                        <i class="fa-solid fa-circle-xmark"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-reject-design') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Row 2: Revised / Material / Completed --}}
                            <div class="row g-3 mb-3">

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #e6a817 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Revised
                                                        Design Received</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $production_dept_counts['corected_design_list_recived'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#e6a817;">
                                                        <i class="fa-solid fa-rotate"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-revislist-material-reciveded-design') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #17a2b8 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Material
                                                        Received</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $production_dept_counts['material_received_for_production'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#17a2b8;">
                                                        <i class="fa-solid fa-boxes-stacked"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-material-received') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span
                                                        class="h6 font-semibold text-muted text-sm d-block mb-1">Production
                                                        Completed</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $production_dept_counts['production_completed_prod_dept'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#1a3a6b;">
                                                        <i class="fa-solid fa-flag-checkered"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-final-production-completed') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if (!empty($employee_counts['user_leaves_status']))
                                @include('admin.pages.dashboard.leave-chart', [
                                    'leaveData' => $employee_counts['user_leaves_status'],
                                ])
                            @endif
                            @php $department_leaves = $employee_counts['user_leaves_status'][$role] ?? []; @endphp
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.STORE'))
                        <div class="analysis-progrebar-area mg-b-15">

                            {{-- ROW 1: Inventory & Chalan KPIs --}}
                            <div class="row g-3 mb-3">

                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">Items In
                                                        Stock</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $store_dept_counts['stock_items_count'] }}</span>
                                                    <small class="text-muted" style="font-size:11px;">distinct part
                                                        items</small>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#1a3a6b;">
                                                        <i class="fa-solid fa-warehouse"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #20a38e !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">Total Stock
                                                        Qty</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ number_format($store_dept_counts['total_stock_qty'], 2) }}</span>
                                                    <small class="text-muted" style="font-size:11px;">units across all
                                                        items</small>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#20a38e;">
                                                        <i class="fa-solid fa-boxes-stacked"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #17a2b8 !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">Delivery
                                                        Chalans</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $store_dept_counts['delivery_chalan'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#17a2b8;">
                                                        <i class="fa-solid fa-truck-ramp-box"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-sm">
                                                <a href="{{ route('list-delivery-chalan') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">Returnable
                                                        Chalans</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $store_dept_counts['returnable_chalan'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#6c757d;">
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-sm">
                                                <a href="{{ route('list-returnable-chalan') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- ROW 2: Material Pipeline (6 small cards) --}}
                            <div class="row g-3 mb-3">

                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #e6a817 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#e6a817; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-inbox"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $store_dept_counts['material_need_to_sent_to_production'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">
                                                New<br>Requirements</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-accepted-design-from-prod') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #20a38e !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#20a38e; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-dolly"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $store_dept_counts['material_sent_to_production'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">Issued
                                                to<br>Production</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-material-sent-to-prod') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#1a3a6b; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $store_dept_counts['material_for_purchase'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">Sent
                                                to<br>Purchase</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-material-sent-to-purchase') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #28a745 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#28a745; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-clipboard-check"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $store_dept_counts['material_received_from_quality'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">
                                                Received<br>From Quality</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-material-received-from-quality') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #dc3545 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#dc3545; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">{{ $store_dept_counts['rejected_chalan'] }}
                                            </div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">
                                                Rejected<br>Chalans</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-rejected-chalan-updated') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white rounded-circle mx-auto mb-2"
                                                style="background:#6c757d; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $store_dept_counts['returnable_chalan'] + $store_dept_counts['delivery_chalan'] }}
                                            </div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">
                                                Total<br>Chalans</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if (!empty($employee_counts['user_leaves_status']))
                                @include('admin.pages.dashboard.leave-chart', [
                                    'leaveData' => $employee_counts['user_leaves_status'],
                                ])
                            @endif
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.PURCHASE'))
                        <div class="analysis-progrebar-area mg-b-15">

                            {{-- ======= ROW 1: Financial KPIs ======= --}}
                            <div class="row g-3 mb-3">
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">Total PO
                                                        Value</span>
                                                    <span
                                                        class="h5 font-bold mb-0">&#8377;&nbsp;{{ number_format($purchase_dept_counts['total_po_value'], 2) }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#1a3a6b;">
                                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #20a38e !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">GRN
                                                        Received Value</span>
                                                    <span
                                                        class="h5 font-bold mb-0">&#8377;&nbsp;{{ number_format($purchase_dept_counts['total_grn_value'], 2) }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#20a38e;">
                                                        <i class="fa-solid fa-boxes-stacked"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">Active
                                                        Vendors</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $purchase_dept_counts['vendor_list'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#6c757d;">
                                                        <i class="fa-solid fa-truck"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-sm">
                                                <a href="{{ route('list-vendor') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 col-12">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <span class="font-semibold text-muted text-sm d-block mb-1">Part
                                                        Items</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $purchase_dept_counts['part_item'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#6c757d;">
                                                        <i class="fa-solid fa-gears"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-sm">
                                                <a href="{{ route('list-part-item') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ======= ROW 2: Pipeline Status ======= --}}
                            <div class="row g-3 mb-3">

                                {{-- Requisitions Pending --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white text-lg rounded-circle mx-auto mb-2"
                                                style="background:#6c757d; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-inbox"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $purchase_dept_counts['requisitions_pending'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">
                                                Requisitions<br>Pending</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-purchase') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- POs Pending Owner --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #e6a817 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white text-lg rounded-circle mx-auto mb-2"
                                                style="background:#e6a817; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-clock"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">{{ $purchase_dept_counts['po_pending_owner'] }}
                                            </div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">PO
                                                Awaiting<br>Owner Approval</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-purchase-orders-sent-to-owner') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- POs Approved, pending send --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #20a38e !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white text-lg rounded-circle mx-auto mb-2"
                                                style="background:#20a38e; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $purchase_dept_counts['po_approved_pending_send'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">PO
                                                Approved<br>(Send to Vendor)</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-approved-purchase-orders') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- POs Sent to Vendor --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white text-lg rounded-circle mx-auto mb-2"
                                                style="background:#1a3a6b; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">
                                                {{ $purchase_dept_counts['po_sent_to_vendor'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">POs
                                                Sent<br>to Vendor</div>
                                            <div class="mt-2">
                                                <a href="{{ route('list-submited-po-to-vendor') }}"
                                                    style="font-size:11px; color:#1a3a6b;">View &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Gatepasses --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #17a2b8 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white text-lg rounded-circle mx-auto mb-2"
                                                style="background:#17a2b8; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-id-card"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">{{ $purchase_dept_counts['gatepasses_count'] }}
                                            </div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">
                                                Gatepasses<br>Generated</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- GRNs --}}
                                <div class="col-xl-2 col-md-4 col-6">
                                    <div class="card shadow border-0 h-100"
                                        style="border-left:4px solid #28a745 !important;">
                                        <div class="card-body text-center py-3">
                                            <div class="icon icon-shape text-white text-lg rounded-circle mx-auto mb-2"
                                                style="background:#28a745; width:2.5rem; height:2.5rem;">
                                                <i class="fa-solid fa-clipboard-check"></i>
                                            </div>
                                            <div class="h4 font-bold mb-1">{{ $purchase_dept_counts['grn_count'] }}</div>
                                            <div class="text-muted" style="font-size:11px; font-weight:600;">
                                                GRNs<br>Generated</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- PO Rejected (show only if > 0) --}}
                            @if ($purchase_dept_counts['po_rejected'] > 0)
                                <div class="row g-3 mb-3">
                                    <div class="col-xl-3 col-md-6 col-12">
                                        <div class="card shadow border-0"
                                            style="border-left:4px solid #dc3545 !important; background:#fff8f8;">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <span class="font-semibold text-sm d-block mb-1"
                                                            style="color:#dc3545;">POs Rejected by Owner</span>
                                                        <span class="h5 font-bold mb-0"
                                                            style="color:#dc3545;">{{ $purchase_dept_counts['po_rejected'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon icon-shape text-white text-lg rounded-circle"
                                                            style="background:#dc3545;">
                                                            <i class="fa-solid fa-circle-xmark"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 text-sm">
                                                    <a href="{{ route('list-purchase-order-rejected') }}"
                                                        style="color:#dc3545; font-size:12px;">
                                                        <i class="fa-solid fa-arrow-right"></i> Review &amp; Resubmit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($employee_counts['user_leaves_status']))
                                @include('admin.pages.dashboard.leave-chart', [
                                    'leaveData' => $employee_counts['user_leaves_status'],
                                ])
                            @endif
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.SECURITY'))
                        <div class="analysis-progrebar-area mg-b-15">
                            <div class="row">
                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Gate
                                                        Pass</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $secuirty_dept_counts['get_pass'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-gatepass') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">View
                                                        Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($employee_counts['user_leaves_status']))
                                    @include('admin.pages.dashboard.leave-chart', [
                                        'leaveData' => $employee_counts['user_leaves_status'],
                                    ])
                                @endif
                            </div>
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.QUALITY'))
                        <div class="analysis-progrebar-area mg-b-15">

                            {{-- Pipeline header --}}
                            <div class="mb-3">
                                <small class="text-muted font-semibold" style="font-size:12px; letter-spacing:0.5px;">
                                    QUALITY PIPELINE &#8594; Received from Security &#8594; Under Inspection &#8594; GRN
                                    Generated &#8594; Sent to Store
                                </small>
                            </div>

                            {{-- Single row: 4 cards --}}
                            <div class="row g-3 mb-3">

                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Received
                                                        from Security</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $quality_dept_counts['GRN_genration'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#6c757d;">
                                                        <i class="fa-solid fa-shield-halved"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-grn') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #28a745 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">GRN
                                                        Generated</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $quality_dept_counts['material_need_to_sent_to_store'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#28a745;">
                                                        <i class="fa-solid fa-file-circle-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-material-sent-to-quality') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #17a2b8 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Material
                                                        Sent to Store</span>
                                                    <span
                                                        class="h4 font-bold mb-0">{{ $quality_dept_counts['material_need_to_sent_to_store'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#17a2b8;">
                                                        <i class="fa-solid fa-boxes-stacked"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-material-sent-to-quality') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="card shadow border-0"
                                        style="border-left:4px solid #fd7e14 !important; {{ $quality_dept_counts['rejected_chalan_po_wise'] > 0 ? 'background:#fff8f0;' : '' }}">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Rejected
                                                        Chalans</span>
                                                    <span class="h4 font-bold mb-0"
                                                        style="{{ $quality_dept_counts['rejected_chalan_po_wise'] > 0 ? 'color:#fd7e14;' : '' }}">{{ $quality_dept_counts['rejected_chalan_po_wise'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle"
                                                        style="background:#fd7e14;">
                                                        <i class="fa-solid fa-file-circle-xmark"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-rejected-chalan-updated') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i
                                                            class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if (!empty($employee_counts['user_leaves_status']))
                                @include('admin.pages.dashboard.leave-chart', [
                                    'leaveData' => $employee_counts['user_leaves_status'],
                                ])
                            @endif
                            @php $department_leaves = $employee_counts['user_leaves_status'][$role] ?? []; @endphp
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.LOGISTICS'))
                        <div class="analysis-progrebar-area mg-b-15">

                            {{-- Pipeline header --}}
                            <div class="mb-3">
                                <small class="text-muted font-semibold" style="font-size:12px; letter-spacing:0.5px;">
                                    LOGISTICS PIPELINE &#8594; Production Completed &#8594; Logistics In Process &#8594; Submitted to Finance
                                </small>
                            </div>

                            {{-- Row 1: Pipeline stages --}}
                            <div class="row g-3 mb-3">

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Production Completed</span>
                                                    <span class="h4 font-bold mb-0">{{ $logistics_counts['production_completed_prod_dept_logisitics'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#6c757d;">
                                                        <i class="fa-solid fa-industry"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-final-production-completed-recive-to-logistics') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #e6a817 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Logistics In Process</span>
                                                    <span class="h4 font-bold mb-0">{{ $logistics_counts['logistics_list_count'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#e6a817;">
                                                        <i class="fa-solid fa-truck-moving"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-logistics') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #17a2b8 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Submitted to Finance</span>
                                                    <span class="h4 font-bold mb-0">{{ $logistics_counts['logistics_send_by_finance_count'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#17a2b8;">
                                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-send-to-fianance-by-logistics') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Row 2: Master data --}}
                            <div class="row g-3 mb-3">

                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Total Vehicle Types</span>
                                                    <span class="h4 font-bold mb-0">{{ $logistics_counts['vehicle_type_count'] }}</span>
                                                    <small class="text-muted" style="font-size:11px;">master records</small>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#1a3a6b;">
                                                        <i class="fa-solid fa-truck"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-vehicle-type') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Total Transport Names</span>
                                                    <span class="h4 font-bold mb-0">{{ $logistics_counts['transport_name_count'] }}</span>
                                                    <small class="text-muted" style="font-size:11px;">master records</small>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#1a3a6b;">
                                                        <i class="fa-solid fa-building-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-transport-name') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if (!empty($employee_counts['user_leaves_status']))
                                @include('admin.pages.dashboard.leave-chart', [
                                    'leaveData' => $employee_counts['user_leaves_status'],
                                ])
                            @endif
                            @php $department_leaves = $employee_counts['user_leaves_status'][$role] ?? []; @endphp
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.FINANCE'))
                        <div class="analysis-progrebar-area mg-b-15">
                            <div class="row">
                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Need
                                                        to check for Payment</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $logistics_counts['need_to_check_for_payment'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-sr-and-gr-genrated-business') }}">
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
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">PO
                                                        Submited For Sanction For Payment</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $logistics_counts['production_completed_prod_dept_logisitics'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-po-sent-for-approval') }}">
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
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">PO
                                                        Payment Release to Vendor
                                                        By Fianance</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $logistics_counts['po_pyament_need_to_release'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-release-approval-payment-by-vendor') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">View
                                                        Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-6 col-12 mt-4">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Recive
                                                        Logistics List</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $fianance_counts['logistics_send_by_finance_received_fianance_count'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('recive-logistics-list') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">View
                                                        Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-6 col-12 mt-4">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Product
                                                        Submited to Dispatch</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $fianance_counts['fianance_send_to_dispatch_count'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-send-to-dispatch') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">View
                                                        Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($employee_counts['user_leaves_status']))
                                    @include('admin.pages.dashboard.leave-chart', [
                                        'leaveData' => $employee_counts['user_leaves_status'],
                                    ])
                                @endif
                            </div>
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.INVENTORY'))
                        <div class="analysis-progrebar-area mg-b-15">
                            <div class="row">
                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2"> All
                                                        New Requirements</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $inventory_dept_counts['material_need_to_sent_to_production_inventory'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-accepted-design-from-prod') }}">
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
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Inventory
                                                        Material List</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $inventory_dept_counts['part_item_inventory'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-inventory-material') }}">
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
                    @elseif ($role == config('constants.ROLE_ID.DISPATCH'))
                        <div class="analysis-progrebar-area mg-b-15">

                            {{-- Pipeline header --}}
                            <div class="mb-3">
                                <small class="text-muted font-semibold" style="font-size:12px; letter-spacing:0.5px;">
                                    DISPATCH PIPELINE &#8594; Received from Finance &#8594; In Process &#8594; Dispatched &#8594; Product Closed
                                </small>
                            </div>

                            {{-- Row 1: Pipeline stages (3 cards) --}}
                            <div class="row g-3 mb-3">

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #6c757d !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Received from Finance</span>
                                                    <span class="h4 font-bold mb-0">{{ $dispatch_counts['dispatch_received_from_finance'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#6c757d;">
                                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-final-production-completed-received-from-fianance') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #e6a817 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Dispatch In Process</span>
                                                    <span class="h4 font-bold mb-0">{{ $dispatch_counts['dispatch_in_process'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#e6a817;">
                                                        <i class="fa-solid fa-dolly"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-dispatch') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #28a745 !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Dispatch Completed</span>
                                                    <span class="h4 font-bold mb-0">{{ $dispatch_counts['dispatch_completed'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#28a745;">
                                                        <i class="fa-solid fa-truck-ramp-box"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-dispatch') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Row 2: Final closure --}}
                            <div class="row g-3 mb-3">

                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="card shadow border-0" style="border-left:4px solid #1a3a6b !important;">
                                        <div class="card-body">
                                            <div class="row border-bottom align-items-center pb-2 mb-2">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-1">Final Product Closed</span>
                                                    <span class="h4 font-bold mb-0">{{ $dispatch_counts['final_product_closed'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape text-white text-lg rounded-circle" style="background:#1a3a6b;">
                                                        <i class="fa-solid fa-flag-checkered"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 text-sm">
                                                <a href="{{ route('list-dispatch-final-product-close') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2"><i class="fa-solid fa-arrow-right"></i></span>
                                                    <span class="text-nowrap text-xs text-muted">View Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if (!empty($employee_counts['user_leaves_status']))
                                @include('admin.pages.dashboard.leave-chart', [
                                    'leaveData' => $employee_counts['user_leaves_status'],
                                ])
                            @endif
                            @php $department_leaves = $employee_counts['user_leaves_status'][$role] ?? []; @endphp
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.EMPOLYEE'))
                        <div class="analysis-progrebar-area mg-b-15">
                            <div class="row">
                                <div class="col-xl-6 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                        Leave Request</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $employee_counts['employee_leave_request'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-leaves') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">View
                                                        Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">accept
                                                        Leave Request</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $employee_counts['employee_accepted_leave_request'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-leaves') }}">
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
                                <div class="col-xl-6 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected
                                                        Leave Request</span>
                                                    <span
                                                        class="h5 font-bold mb-0">{{ $employee_counts['employee_rejected_leave_request'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa-solid fa-paint-brush"></i>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-leaves') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">View
                                                        Details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6 col-md-6 mb-4"  >
                                        <div class="col-lg-9 col-md-9">
                                        <canvas id="leaveStatusChart" width="500" height="500"></canvas>
                                        </div>
                                    </div> --}}
                                @if (!empty($employee_counts['user_leaves_status']))
                                    @include('admin.pages.dashboard.leave-chart', [
                                        'leaveData' => $employee_counts['user_leaves_status'],
                                    ])
                                @endif
                            </div>
                        </div>

                        {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>                            
                            <script>
                                // Get context from canvas
                                const ctx = document.getElementById('leaveStatusChart').getContext('2d');
                            
                                // Parse PHP data into JS
                                const leaveData = @json($employee_counts['user_leaves_status']);
                            
                                // Extracting the data
                                const leaveTypes = leaveData.map(item => item.leave_type_name);
                                const leaveCount = leaveData.map(item => parseInt(item.leave_count));
                                const takenLeaves = leaveData.map(item => parseInt(item.total_leaves_taken) || 0);
                                const remainingLeaves = leaveData.map(item => parseInt(item.remaining_leaves) || 0);
                                // Define datasets
                                const datasets = [
                                    {
                                        label: 'Total Leaves',
                                        data: leaveCount,
                                        backgroundColor: '#2d4e59',
                                        hoverOffset: 4
                                    },
                                    {
                                        label: 'Taken Leaves',
                                        data: takenLeaves,
                                        backgroundColor: '#33b78c',
                                        hoverOffset: 4
                                    },
                                    {
                                        label: 'Balanced Leaves',
                                        data: remainingLeaves,
                                        backgroundColor: '#199cc2',
                                        hoverOffset: 4
                                    }
                                ];
                            
                                // Create the chart
                                new Chart(ctx, {
                                    type: 'pie',
                                    data: {
                                        labels: leaveTypes,
                                        datasets: datasets
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            title: {
                                                display: true,
                                                text: 'Leave Status Breakdown'
                                            }
                                        }
                                    }
                                });
                            </script> --}}
                    @elseif ($role == config('constants.ROLE_ID.CMS'))
                        <div class="analysis-progrebar-area mg-b-15">
                            <div class="row">
                                <div class="col-xl-4 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Product</span>
                                                    <span class="h5 font-bold mb-0">
                                                        {{ $cms_counts['product_count'] }}
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa big-icon fa-cube icon-wrap"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-product') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">view
                                                        details</span>
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
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Services</span>
                                                    <span class="h5 font-bold mb-0">
                                                        {{ $cms_counts['product_services_count'] }}
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa big-icon fa-tools icon-wrap"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-services') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">view
                                                        details</span>
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
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Testimonial</span>
                                                    <span class="h5 font-bold mb-0">
                                                        {{ $cms_counts['testimonial_count'] }}
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa big-icon fa-quote-right icon-wrap"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-testimonial') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">view
                                                        details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-6 col-12 mt-2">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Director
                                                        Desk</span>
                                                    <span class="h5 font-bold mb-0">
                                                        {{-- {{ $cms_counts['progressPercentage'] }} --}}
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa big-icon fa-briefcase icon-wrap"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-director-desk') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">view
                                                        details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-6 col-12 mt-2">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">Vision
                                                        Mission</span>
                                                    <span class="h5 font-bold mb-0">
                                                        {{ $cms_counts['vision_mission_count'] }}
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa big-icon fa-bullseye icon-wrap"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-vision-mission') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">view
                                                        details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-6 col-12 mt-2">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Team</span>
                                                    <span class="h5 font-bold mb-0">
                                                        {{ $cms_counts['team_count'] }}
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa big-icon fa-user-friends icon-wrap"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-team') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">view
                                                        details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-6 col-12 mt-2">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row border-bottom">
                                                <div class="col mb-2">
                                                    <span
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Contactus
                                                        Form</span>
                                                    <span class="h5 font-bold mb-0">
                                                        {{ $cms_counts['contact_us_count'] }}
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                        <i class="fa big-icon fa-edit icon-wrap"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-0 text-sm">
                                                <a href="{{ route('list-contactus-form') }}">
                                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                                    <span class="text-nowrap text-xs text-muted">view
                                                        details</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @elseif ($role == config('constants.ROLE_ID.HIGHER_AUTHORITY'))
                        @include('admin.pages.dashboard.higher-dashboard')
                    @elseif ($role == config('constants.ROLE_ID.ESTIMATION'))
                        @include('admin.pages.dashboard.estimation-dashboard')
                    @endif





                    {{-- <div class="col-lg-6 col-md-6 mb-4">
                            <h4>{{ ucfirst(strtolower($role)) }} Leave Status</h4>
                            <div class="col-lg-9 col-md-9">
                                <canvas id="leaveStatusChart-{{ $role }}" width="500" height="500"></canvas>
                            </div>
                        </div>
                        
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const leaveData = @json($employee_counts['user_leaves_status']);
                                
                                if (leaveData.length > 0) {
                                    const ctx = document.getElementById('leaveStatusChart-{{ $role }}').getContext('2d');
                        
                                    const leaveTypes = leaveData.map(item => item.leave_type_name);
                                    const leaveCount = leaveData.map(item => parseInt(item.leave_count));
                                    const takenLeaves = leaveData.map(item => parseInt(item.total_leaves_taken) || 0);
                                    const remainingLeaves = leaveData.map(item => parseInt(item.remaining_leaves) || 0);
                        
                                    new Chart(ctx, {
                                        type: 'pie',
                                        data: {
                                            labels: leaveTypes,
                                            datasets: [
                                                {
                                                    label: 'Total Leaves',
                                                    data: leaveCount,
                                                    backgroundColor: '#2d4e59',
                                                    hoverOffset: 4
                                                },
                                                {
                                                    label: 'Taken Leaves',
                                                    data: takenLeaves,
                                                    backgroundColor: '#33b78c',
                                                    hoverOffset: 4
                                                },
                                                {
                                                    label: 'Balanced Leaves',
                                                    data: remainingLeaves,
                                                    backgroundColor: '#199cc2',
                                                    hoverOffset: 4
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    position: 'top',
                                                },
                                                title: {
                                                    display: true,
                                                    text: '{{ ucfirst(strtolower($role)) }} Leave Status Breakdown'
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    document.getElementById('leaveStatusChart-{{ $role }}').parentElement.innerHTML = "<p>No leave data available for your department.</p>";
                                }
                            });
                        </script> --}}

                </div>
            </div>
        </div>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('.accordion-button').on('click', function() {
            $('.accordion-button').removeClass('active');

            if (!$(this).hasClass('collapsed')) {
                $(this).addClass('active');
            }
        });
    });
</script>
