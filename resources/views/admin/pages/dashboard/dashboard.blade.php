
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
        .sparkline12-hd{
            padding-top:85px !important;
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
                            config('constants.ROLE_ID.EMPOLYEE')

                        ];
                        @endphp
                        @if ($role == config('constants.ROLE_ID.HR'))
                        @include('admin.pages.dashboard.hr-dashboard')
                     
                        @elseif ($role == config('constants.ROLE_ID.SUPER'))
                        @include('admin.pages.dashboard.super-dashboard')
                        @elseif ($role == config('constants.ROLE_ID.DESIGNER'))
                            <div class="analysis-progrebar-area mg-b-15">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Receive
                                                            For Design</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $design_dept_counts['business_received_for_designs'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-new-requirements-received-for-design') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Designs
                                                            Sent To Production</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $design_dept_counts['design_sent_for_production'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-design-upload') }}">
                                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                                            <i class="fa-solid fa-arrow-right"></i> </span>
                                                        <span class="text-nowrap text-xs text-muted">View
                                                            Details</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12 ">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Accepted
                                                            Design</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $design_dept_counts['accepted_design_production_dept'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-accept-design-by-production') }}">
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected
                                                            Design</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $design_dept_counts['rejected_design_production_dept'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-reject-design-from-prod') }}">
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                        @php $department_leaves = $employee_counts['user_leaves_status'][$role] ?? []; @endphp
                                </div>
                            </div>
                         
                        @elseif ($role == config('constants.ROLE_ID.PRODUCTION'))
                            <div class="analysis-progrebar-area mg-b-15">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">New
                                                            Design</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $production_dept_counts['design_recived_for_production'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a
                                                        href="{{ route('list-new-requirements-received-for-production-business-wise') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Accepted
                                                            Design</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $production_dept_counts['accepted_and_sent_to_store'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-accept-design') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected
                                                            Design</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $production_dept_counts['rejected_design_list_sent'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-reject-design') }}">
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
                                <div class="row mt-4">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Revised
                                                            Design</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $production_dept_counts['corected_design_list_recived'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-revislist-material-reciveded-design') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Material
                                                            Received For Production</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $production_dept_counts['material_received_for_production'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-material-received') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Production
                                                            Completed</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $production_dept_counts['production_completed_prod_dept'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-final-production-completed') }}">
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                        @php $department_leaves = $employee_counts['user_leaves_status'][$role] ?? []; @endphp
                                </div>
                            </div>
                        @elseif ($role == config('constants.ROLE_ID.STORE'))
                            <div class="analysis-progrebar-area mg-b-15">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">New
                                                            Requirements</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $store_dept_counts['material_need_to_sent_to_production'] }}</span>
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
                                    {{-- <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Sent To Production</span>
                                                <span
                                                    class="h5 font-bold mb-0">{{ $store_dept_counts['material_sent_to_production'] }}</span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                    <i class="fa-solid fa-paint-brush"></i>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 mb-0 text-sm">
                                            <a href="{{ route('list-material-sent-to-prod') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                    <i class="fa-solid fa-arrow-right"></i> </span>
                                                <span class="text-nowrap text-xs text-muted">View
                                                    Details</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Material
                                                            For Purchase</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $store_dept_counts['material_for_purchase'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-material-sent-to-purchase') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Received
                                                            From Quality</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $store_dept_counts['material_received_from_quality'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-material-received-from-quality') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected
                                                            Chalan</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $store_dept_counts['rejected_chalan'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-rejected-chalan-updated') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Delivery
                                                            Chalan</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $store_dept_counts['delivery_chalan'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-delivery-chalan') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Returnable
                                                            Chalan</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $store_dept_counts['returnable_chalan'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-returnable-chalan') }}">
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                </div>
                            </div>
                          
                        @elseif ($role == config('constants.ROLE_ID.PURCHASE'))
                            <div class="analysis-progrebar-area mg-b-15">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Purchase
                                                            Orders</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $purchase_dept_counts['BOM_recived_for_purchase'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-purchase') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Vendor</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $purchase_dept_counts['vendor_list'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-vendor') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Tax</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $purchase_dept_counts['tax'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-tax') }}">
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
                                                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">Part
                                                            Item</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $purchase_dept_counts['part_item'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-part-item') }}">
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
                                                            Approved</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $purchase_dept_counts['purchase_order_approved'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-approved-purchase-orders') }}">
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
                                                            Sent To Vendor</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $purchase_dept_counts['purchase_order_submited_by_vendor'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-submited-po-to-vendor') }}">
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                </div>
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                </div>
                            </div>
                        @elseif ($role == config('constants.ROLE_ID.QUALITY'))
                            <div class="analysis-progrebar-area mg-b-15">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">GRN</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $quality_dept_counts['GRN_genration'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-grn') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Material
                                                            Sent to Store</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $quality_dept_counts['material_need_to_sent_to_store'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-material-sent-to-quality') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected
                                                            Chalan</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $quality_dept_counts['rejected_chalan_po_wise'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-rejected-chalan-updated') }}">
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                </div>
                            </div>
                        @elseif ($role == config('constants.ROLE_ID.LOGISTICS'))
                            <div class="analysis-progrebar-area mg-b-15">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Production
                                                            Completed Product</span>
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
                                                    <a
                                                        href="{{ route('list-final-production-completed-recive-to-logistics') }}">
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
                                                            Logistics List</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $logistics_counts['logistics_list_count'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-logistics') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Submited
                                                            by Fianance</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $logistics_counts['logistics_send_by_finance_count'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-send-to-fianance-by-logistics') }}">
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                            Vehicle Type</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $logistics_counts['vehicle_type_count'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-vehicle-type') }}">
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                            Transport Name</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $logistics_counts['transport_name_count'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-transport-name') }}">
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                </div>
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
                                                        <span class="h6 font-semibold text-muted text-sm d-block mb-2">PO Payment Release to Vendor
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
                                                    <a
                                                        href="{{ route('list-release-approval-payment-by-vendor') }}">
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Recive
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Product
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
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
                                <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Received
                                                            From Finance</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $dispatch_counts['dispatch_received_from_finance'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a
                                                        href="{{ route('list-final-production-completed-received-from-fianance') }}">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Completed
                                                            Dispatch</span>
                                                        <span
                                                            class="h5 font-bold mb-0">{{ $dispatch_counts['dispatch_completed'] }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div
                                                            class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                            <i class="fa-solid fa-paint-brush"></i>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-0 text-sm">
                                                    <a href="{{ route('list-dispatch') }}">
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
                                    @endif
                                </div>
                            </div>
                        @elseif ($role == config('constants.ROLE_ID.EMPOLYEE'))
                            <div class="analysis-progrebar-area mg-b-15">
                                <div class="row">
                                    <div class="col-xl-6 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Total
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">accept
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected
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
                                    @include('admin.pages.dashboard.leave-chart', ['leaveData' => $employee_counts['user_leaves_status']])
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Director
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
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Vision
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