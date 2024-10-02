<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
<!-- ============= pratiksha (21/08/24) =============  -->
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
    width: 3rem;
    height: 3rem;
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
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline14-list">
                    @if (session()->get('role_id') == config('constants.ROLE_ID.HR'))
                    <div class="product-sales-area mg-tb-30">
                        <!-- <div class="container-fluid"> -->
                        <div class="row">
                            <!-- Total Visit -->
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Leave Request</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Leave Approved</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Leave Not Approved</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Total Leaves Type</span>
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
                                                <a href="{{ route('list-users') }}">
                                                    <span
                                                        class="badge badge-pill bg-soft-success text-success me-2">
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
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Total Notice</span>
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
                                                <a href="{{ route('list-users') }}">
                                                    <span
                                                        class="badge badge-pill bg-soft-success text-success me-2">
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
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Total Employee</span>
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
                                                    <span
                                                        class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.DESIGNER'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Receive For Design</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Designs Sent To Production</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Accept For Design</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Rejecte For Design</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PRODUCTION'))
                  
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">New Design</span>
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
                                            <a href="{{ route('list-new-requirements-received-for-production') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Accepted Design</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected Design</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Revised Design</span>
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
                                            <a href="{{ route('list-new-requirements-received-for-design') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Material Received For Production</span>
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
                                            <a href="{{ route('list-material-recived') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Production Completed</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.STORE'))
                    
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">New Requirements</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                            </div>
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Material For Purchase</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Received From Quality</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected Chalan</span>
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
                                            <a href="{{ route('list-rejected-chalan') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PURCHASE'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Purchase Orders</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Part Item</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">PO Approved</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">PO Sent To Vendor</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.SECURITY'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Gate Pass</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif

                    @if (session()->get('role_id') == config('constants.ROLE_ID.QUALITY'))
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Material Sent to Store</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected Chalan</span>
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
                                            <a href="{{ route('list-rejected-chalan-po-wise') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.LOGISTICS'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Production Completed Product</span>
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
                                            <a href="{{ route('list-final-production-completed-recive-to-logistics') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Total Logistics  List</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Submited by Fianance</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Total Transport Name</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.FINANCE'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Need to check for Payment</span>
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
                                            <a href="{{ route('list-sr-and-gr-genrated-business') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">PO Submited For Sanction For Payment</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">PO Pyament Need To Release</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Recive Logistics List</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Product Submited to Dispatch</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif

                    @if (session()->get('role_id') == config('constants.ROLE_ID.INVENTORY'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="card shadow border-0">
                                            <div class="card-body">
                                                <div class="row border-bottom">
                                                    <div class="col mb-2">
                                                        <span
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2"> All New Requirements</span>
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
                                                        <span
                                                            class="badge badge-pill bg-soft-success text-success me-2">
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
                                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Inventory Material List</span>
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
                                                        <span
                                                            class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Production Department send material list</span>
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
                                            <a href="{{ route('list-product-inprocess-received-from-production') }}">
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif  























                    @if (session()->get('role_id') == config('constants.ROLE_ID.DISPATCH'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                    <div class="col-xl-4 col-sm-6 col-12">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span
                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Received From Finance</span>
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
                                    <a href="{{ route('list-vehicle-type') }}">
                                        <span
                                            class="badge badge-pill bg-soft-success text-success me-2">
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
                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Completed Dispatch</span>
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
                                        <span
                                            class="badge badge-pill bg-soft-success text-success me-2">
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
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.EMPOLYEE'))
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-xl-6 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row border-bottom">
                                            <div class="col mb-2">
                                                <span
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Total Leave Request</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">accept Leave Request</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Rejected Leave Request</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                    <i class="fa-solid fa-arrow-right"></i> </span>
                                                <span class="text-nowrap text-xs text-muted">View
                                                    Details</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-xl-6 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body"> <span
                                        class="h6 font-semibold text-muted text-sm d-block mb-2">Total Leaves Count:
                                    
                                    @foreach($employee_leave_type as $leave)
                                       <p>{{ $leave->name }}: {{ $leave->leave_count }}</p>
                                    @endforeach
                                </span>
                                    <h2>
                                        
                                      <span class="counter"></span>/<span class="counter"
                                        >54</span
                                      >
                                    </h2>
                                    <div class="text-center">
                                      <div id="sparkline52"></div>
                                    </div>
                                  </div>
                                </div>
                              </div> --}}
                        </div>
                    </div> 
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PRODUCTION'))
                     {{-- <h6>DISPATCH</h6> --}}
                    {{-- <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>PO Submited For Sanction</h5>
                                        <h2><span class="counter">90</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 68%;" class="progress-bar"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 1:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>PO Pyament Need To Release</h5>
                                        <h2><span class="counter">70</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 78%;" class="progress-bar"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 12:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Recive Logistics</h5>
                                        <h2><span class="counter">70</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 78%;" class="progress-bar"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 12:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Submited to Dispatch</h5>
                                        <h2><span class="counter">70</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 78%;" class="progress-bar"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 12:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.CMS'))
                    
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Director Desk</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Vision Mission</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
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
                                                    class="h6 font-semibold text-muted text-sm d-block mb-2">Contactus Form</span>
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
                                                <span
                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                    <i class="fa-solid fa-arrow-right"></i> </span>
                                                <span class="text-nowrap text-xs text-muted">view
                                                    details</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <canvas id="myPieChart11" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Step 2: Create Pie Chart using JavaScript
    const ctx = document.getElementById('myPieChart11').getContext('2d');

    // Getting PHP data into JavaScript
    const cmsCounts = @json($cms_counts);

    const myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(cmsCounts), // Dynamically set the labels
            datasets: [{
                data: Object.values(cmsCounts), // Dynamically set the data values
                backgroundColor: ['#243772', '#2b4288', '#3755ae', '#516ec8', '#778fd4', '#9eafe0', '#c5cfed'], // Add more colors if needed
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'CMS Counts Distribution'
                }
            }
        }
    });
</script>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.HIGHER_AUTHORITY'))
                    <div class="d-flex flex-column flex-lg-row h-lg-full">

                        <div class="h-screen flex-grow-1 overflow-y-lg-auto">

                            <header class="pt-6">
                                <div class="container-fluid">
                                    <div class="mb-npx">
                                        <div class="row align-items-center">
                                            <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                                            </div>

                                            <div class="col-sm-6 col-12 text-sm-end">
                                                <div class="mx-n1">
                                                    <a href="#" class="btn  btn-sm btn-primary mx-1" type="button"
                                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                                        aria-controls="offcanvasRight">
                                                        <span class="p-1">
                                                            <i class="fa-solid fa-bars"></i>
                                                        </span>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </header>

                            <main class="py-6">
                                <div class="container-fluid">

                                    <div class="row g-6 mb-6">
                                        <div class="col-xl-4 col-sm-6 col-12 mb-2">
                                            <div class="card shadow border-0">
                                                <div class="card-body">
                                                    <div class="row border-bottom">
                                                        <div class="col mb-2">
                                                            <span
                                                                class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                                Customer PO </span>
                                                            <span
                                                                class="h5 font-bold mb-0">{{ $return_data['active_businesses'] }}

                                                            </span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div
                                                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                <i class="fa-solid fa-user-tie"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 mb-0 text-sm">
                                                        <a href="{{ route('list-business') }}">
                                                            <span
                                                                class="badge badge-pill bg-soft-success text-success me-2">
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
                                                                Product</span>
                                                            <span
                                                                class="h5 font-bold mb-0">{{ $return_data['business_details'] }}</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div
                                                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                <i class="fa-solid fa-paint-brush"></i>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 mb-0 text-sm">
                                                        <a href="{{ route('list-business') }}">
                                                            <span
                                                                class="badge badge-pill bg-soft-success text-success me-2">
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
                                                                class="h6 font-semibold text-muted text-sm d-block mb-2">Inprocess
                                                                Product</span>
                                                            <span
                                                                class="h5 font-bold mb-0">{{ $return_data['product_inprocess'] }}</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div
                                                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                <i class="fa-solid fa-store"></i>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 mb-0 text-sm">
                                                        <a href="{{ route('list-product-dispatch-completed') }}">
                                                            <span
                                                                class="badge badge-pill bg-soft-success text-success me-2">
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
                                                                class="h6 font-semibold text-muted text-sm d-block mb-2">Completed
                                                                Customer PO</span>
                                                            <span class="h5 font-bold mb-0">
                                                                {{ $return_data['business_completed'] }}
                                                            </span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div
                                                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                <i class="fa-solid fa-industry"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 mb-0 text-sm">
                                                        <a href="{{ route('list-product-dispatch-completed') }}">
                                                            <span
                                                                class="badge badge-pill bg-soft-success text-success me-2">
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
                                                                class="h6 font-semibold text-muted text-sm d-block mb-2">Completed
                                                                Product</span>
                                                            <span class="h5 font-bold mb-0">
                                                                {{ $return_data['product_completed'] }}
                                                            </span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div
                                                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                <i class="fa-solid fa-star"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 mb-0 text-sm">
                                                        <a href="{{ route('list-product-dispatch-completed') }}">
                                                            <span
                                                                class="badge badge-pill bg-soft-success text-success me-2">
                                                                <i class="fa-solid fa-arrow-right"></i> </span>
                                                            <span class="text-nowrap text-xs text-muted">view details</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6 col-12 mb-2">
                                            <div class="card shadow border-0">
                                                <div class="card-body">
                                                    <div class="row border-bottom">
                                                        <div class="col mb-2">
                                                            <span
                                                                class="h6 font-semibold text-muted text-sm d-block mb-2">Total Employee</span>
                                                            <span
                                                                class="h5 font-bold mb-0">{{ $return_data['user_active_count'] }}</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div
                                                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                <i class="fa-solid fa-shopping-cart"></i>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 mb-0 text-sm">
                                                        <a href="{{ route('list-users') }}">
                                                            <span
                                                                class="badge badge-pill bg-soft-success text-success me-2">
                                                                <i class="fa-solid fa-arrow-right"></i> </span>
                                                            <span class="text-nowrap text-xs text-muted">view details</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                       
                                    </div>
                                </div>
                            </main>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <canvas id="myBarChart"></canvas>
                                </div>
                            </div>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                            var ctx = document.getElementById('myPieChart').getContext('2d');

                            // Pass PHP data to JavaScript
                            var counts = @json($return_data); // Ensure $return_data is correctly set in the controller

                            var backgroundColors = [
                                '#2d4e59',
                                '#33b78c',
                                '#34bab8',
                                '#199cc2',
                                '#3585b2',
                                '#6d9baa'
                            ];

                            var labels = Object.keys(counts);
                            var data = Object.values(counts);

                            var myPieChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        data: data,
                                        backgroundColor: backgroundColors
                                    }]
                                },
                                options: {
                                    plugins: {
                                        tooltip: {
                                            enabled: true
                                        },
                                        legend: {
                                            position: 'right'
                                        },
                                        title: {
                                            display: true,
                                            text: 'Product Pie Chart',
                                            font: {
                                                size: 20
                                            }
                                        }
                                    },
                                    responsive: true
                                }
                            });
                            </script>

                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                            var ctx = document.getElementById('myBarChart').getContext('2d');

                            // Pass PHP data to JavaScript
                            var counts = @json($return_data); // Ensure $return_data is correctly set in the controller

                            var backgroundColors = [
                                '#2d4e59',
                                '#33b78c',
                                '#34bab8',
                                '#199cc2',
                                '#3585b2',
                                '#6d9baa'
                            ];

                            var labels = Object.keys(counts);
                            var data = Object.values(counts);

                            var myBarChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Products',
                                        data: data,
                                        backgroundColor: backgroundColors,
                                        borderColor: '#000', // Optional: border color for the bars
                                        borderWidth: 1 // Optional: border width for the bars
                                    }]
                                },
                                options: {
                                    plugins: {
                                        tooltip: {
                                            enabled: true
                                        },
                                        legend: {
                                            position: 'top'
                                        },
                                        title: {
                                            display: true,
                                            text: 'Product Bar Chart',
                                            font: {
                                                size: 20
                                            }
                                        }
                                    },
                                    responsive: true,
                                    scales: {
                                        x: {
                                            beginAtZero: true
                                        },
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                            </script>


                        </div>
                    </div>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.HIGHER_AUTHORITY'))
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                        aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 id="offcanvasRightLabel">Customer PO List</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <div class="accordion" id="accordionExample">
                                @foreach($return_data['data_output_offcanvas'] as $po_number => $grouped_data)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $loop->index }}" aria-expanded="true"
                                            aria-controls="collapse{{ $loop->index }}">
                                            {{ $po_number }} - {{ $grouped_data->first()->title }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div>
                                                <ul style="list-style-type: disc; list-style-position: outside;">
                                                    @foreach($grouped_data as $data)
                                                    <li class="right-side"
                                                        style="color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }};">
                                                        <span><b>{{ $data->product_name }}</b> : -</span>
                                                        @if($data->off_canvas_status == 22 && $data->business_status_id == 1118 || $data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id
                                                        =="1146" && $data->dispatch_status_id =="1148" )
                                                        Dispatch Department Product Dispatch Completed 
                                                        @elseif($data->off_canvas_status == 21 && $data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id
                                                        =="1146" && $data->dispatch_status_id =="1147" )
                                                        Finance Department sent to Dispatch Department
                                                        @elseif($data->off_canvas_status == 20 && $data->dispatch_status_id ==NULL && $data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id
                                                        =="1146")
                                                        Finance Department Received from Logistics Department
                                                        @elseif($data->off_canvas_status == 19 && $data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id =="1145" )
                                                         Logistics Department  Submitted Form
                                                        @elseif($data->off_canvas_status == 18 && $data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114)
                                                        Production Department Completed Production and Received Logistics Department
                                                        @elseif($data->off_canvas_status == 17 && $data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114)
                                                        Store Department forward to Production Department
                                                        @elseif($data->business_status_id == 1127 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1140 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134)
                                                        Quality Department(Generated GRN) and Store Department Material Received
                                                        @elseif($data->business_status_id == 1127 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1140 && $data->security_status_id ==1132)
                                                        Security Department Received Material and PO also Generated Gate Pass
                                                        @elseif($data->business_status_id == 1127 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1140)
                                                        Purchase Department PO Send to Vendor
                                                        @elseif($data->business_status_id == 1127 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_purchase == 1126 && $data->finanace_store_receipt_status_id == 1140 && $data->purchase_status_from_owner == "1127")
                                                        Purchase Department Approved Owner
                                                        @elseif($data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 ||$data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_purchase == 1126)
                                                        Purchase Department 
                                                        @elseif($data->off_canvas_status == 16 && $data->business_status_id == "1123" &&
                                                        $data->design_status_id == "1114" &&
                                                        $data->production_status_id == "1117" &&
                                                        $data->store_status_id == "1123")
                                                        Store Department submitted requistion form
                                                   
                                                        {{-- @elseif($data->business_status_id == 1115 &&
                                                        $data->design_status_id == 1115 &&
                                                        $data->production_status_id == 1115)
                                                        Production Department Rejected Design and Received Design Department --}}
                                                        @elseif($data->off_canvas_status == 15 && $data->business_status_id == 1112 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1114)
                                                        Accepted Production Department and send to store Department
                                                        @elseif($data->off_canvas_status == 14 && $data->business_status_id == 1116 || $data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1116 &&
                                                        $data->production_status_id == 1116 )
                                                        Corrected Design Submitted to Production Department
                                                        {{-- @elseif($data->off_canvas_status == 14 && $data->business_status_id == 1115 || $data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1115 &&
                                                        $data->production_status_id == 1115 && $data->reject_reason_prod == "")
                                                        Corrected Design Submitted to Production Department --}}

                                                        @elseif($data->off_canvas_status == 13 && $data->business_status_id == 1115 || $data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1115 &&
                                                        $data->production_status_id == 1115 && $data->reject_reason_prod == "")
                                                        Rejected Design in Production Department
                                                        @elseif($data->off_canvas_status == 12 && $data->business_status_id == 1112 || $data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1111 && $data->production_status_id == 0 && $data->design_image == "" && $data->bom_image == "")
                                                         Design Department Submited Design and Received Production Department
                                                        @elseif($data->off_canvas_status == 11 && $data->business_status_id == 1112 || $data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1111 )
                                                        Business Department Request send to Design Department
                                                        @else
                                                        Unknown Department
                                                        @endif
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    

                </div>
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