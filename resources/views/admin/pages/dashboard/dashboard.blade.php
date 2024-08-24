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
                    <h6>HR</h6>
                    <div class="product-sales-area mg-tb-30">
                        <!-- <div class="container-fluid"> -->
                        <div class="row">
                            <!-- Total Visit -->
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="white-box analytics-info-cs mg-b-10 res-mg-t-30">
                                    <h3 class="box-title">Leave Request</h3>
                                    <ul class="list-inline two-part-sp">
                                        <li>
                                            <div id="sparklinedash"></div>
                                        </li>
                                        <li class="text-right sp-cn-r">
                                            <i class="fa fa-level-up" aria-hidden="true"></i>
                                            <span class="counter text-success">8659</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Total Page Views -->
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="white-box analytics-info-cs mg-b-10">
                                    <h3 class="box-title">Leave Approved</h3>
                                    <ul class="list-inline two-part-sp">
                                        <li>
                                            <div id="sparklinedash2"></div>
                                        </li>
                                        <li class="text-right">
                                            <i class="fa fa-level-up" aria-hidden="true"></i>
                                            <span class="counter text-purple">7469</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Unique Visitor -->
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="white-box analytics-info-cs mg-b-10">
                                    <h3 class="box-title">Leave Not Approved</h3>
                                    <ul class="list-inline two-part-sp">
                                        <li>
                                            <div id="sparklinedash3"></div>
                                        </li>
                                        <li class="text-right">
                                            <i class="fa fa-level-up" aria-hidden="true"></i>
                                            <span class="counter text-info">6011</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Bounce Rate -->
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="white-box analytics-info-cs">
                                    <h3 class="box-title">Pending Leave</h3>
                                    <ul class="list-inline two-part-sp">
                                        <li>
                                            <div id="sparklinedash4"></div>
                                        </li>
                                        <li class="text-right">
                                            <i class="fa fa-level-down" aria-hidden="true"></i>
                                            <span class="text-danger">18%</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6>DESIGNER</h6>
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Receive For Design</h5>
                                        <h2><span class="counter">90</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 68%;" class="progress-bar"></div>
                                        </div>
                                        <!-- <div class="m-t-sm small">
                                            <p>Server down since 1:32 pm.</p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Accept For Design</h5>
                                        <h2><span class="counter">70</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 78%;" class="progress-bar"></div>
                                        </div>
                                        <!-- <div class="m-t-sm small">
                                            <p>Server down since 12:32 pm.</p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Rejecte For Design</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <!-- <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6>PRODUCTION</h6>
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>New Design </h5>
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
                                        <h5>Accepted Design</h5>
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
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Rejected Design</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Revised Design</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mt-1">
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Revised Design</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <h6>STORE</h6>
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>New Requirements</h5>
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
                                        <h5>Sent To Production</h5>
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
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Material For Purchase</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Rejected Chalan</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <h6>PURCHASE</h6>
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Purchase Orders</h5>
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
                                        <h5>Vendor</h5>
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
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Part Item</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Purchase Order Approved</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mt-1">
                                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Purchase Order Sent To Vendor</h5>
                                        <h2><span class="counter">50</span>%</h2>
                                        <div class="progress progress-mini">
                                            <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                        <div class="m-t-sm small">
                                            <p>Server down since 8:32 pm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <h6>QUALITY</h6>
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>GRN</h5>
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
                                        <h5>Material Sent to Store</h5>
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
                    </div> -->
                    <!-- <h6>LOGISTICS</h6>
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>List Logistics</h5>
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
                                        <h5>Submited by Fianance</h5>
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
                    </div> -->
                    <!-- <h6>DISPATCH</h6>
                    <div class="analysis-progrebar-area mg-b-15">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="analysis-progrebar reso-mg-b-30">
                                    <div class="analysis-progrebar-content">
                                        <h5>Received From Finance</h5>
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
                                        <h5>Completed Dispatch</h5>
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
                    </div> -->
                    <!-- <h6>DISPATCH</h6>
                    <div class="analysis-progrebar-area mg-b-15">
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
                    </div> -->
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
                                                        <a href="">
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
                                                        <a href="">
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
                                                        <a href="">
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
                                                        <a href="">
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
                                        <!-- ============= -->
                                        <div class="col-xl-4 col-sm-6 col-12 mb-2">
                                            <div class="card shadow border-0">
                                                <div class="card-body">
                                                    <div class="row border-bottom">
                                                        <div class="col mb-2">
                                                            <span
                                                                class="h6 font-semibold text-muted text-sm d-block mb-2">Inprocess
                                                                Business</span>
                                                            <span
                                                                class="h5 font-bold mb-0">{{ $return_data['business_inprocess'] }}</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div
                                                                class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                <i class="fa-solid fa-shopping-cart"></i>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 mb-0 text-sm">
                                                        <a href="">
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
                                                        <a href="">
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
                                        {{-- <div class="col-xl-4 col-sm-6 col-12">
                                                <div class="card shadow border-0">
                                                    <div class="card-body">
                                                        <div class="row border-bottom">
                                                            <div class="col mb-2">
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Security</span>
                                                                <span class="h5 font-bold mb-0">95%</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div
                                                                    class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                    <i class="fa-solid fa-shield"></i>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 mb-0 text-sm">
                                                            <a href="">
                                                                <span
                                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Finance</span>
                                                                <span class="h5 font-bold mb-0">95%</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div
                                                                    class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                    <i class="fa-solid fa-coins"></i>


                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 mb-0 text-sm">
                                                            <a href="">
                                                                <span
                                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        <!-- ============= -->
                                        {{-- <div class="col-xl-4 col-sm-6 col-12 mb-2">
                                                <div class="card shadow border-0">
                                                    <div class="card-body">
                                                        <div class="row border-bottom">
                                                            <div class="col mb-2">
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">HR</span>
                                                                <span class="h5 font-bold mb-0">95%</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div
                                                                    class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                    <i class="fa-solid fa-users"></i>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 mb-0 text-sm">
                                                            <a href="">
                                                                <span
                                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Logistics</span>
                                                                <span class="h5 font-bold mb-0">95%</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div
                                                                    class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                    <i class="fa-solid fa-truck"></i>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 mb-0 text-sm">
                                                            <a href="">
                                                                <span class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Dispatch</span>
                                                                <span class="h5 font-bold mb-0">95%</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div
                                                                    class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                    <i class="fa-solid fa-shipping-fast"></i>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 mb-0 text-sm">
                                                            <a href="">
                                                                <span
                                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">CMS</span>
                                                                <span class="h5 font-bold mb-0">95%</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div
                                                                    class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                                                    <i class="fa-solid fa-cogs"></i>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 mb-0 text-sm">
                                                            <a href="">
                                                                <span
                                                                    class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
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
                                                        <span>{{ $data->product_name }}</span>
                                                        @if($data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id
                                                        ==1146 && $data->dispatch_status_id ==1148 )
                                                        Dispatch Department Completed Dispatch
                                                        @elseif($data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id
                                                        ==1146 && $data->dispatch_status_id ==1148 )
                                                        Finance Department sent to Dispatch Department
                                                        @elseif($data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id
                                                        ==1146 && $data->dispatch_status_id ==1148 )
                                                        Finance Department Received from Logistics Department
                                                        @elseif($data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 && $data->logistics_status_id
                                                        ==1145 )
                                                        Logistics Department
                                                        @elseif($data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1121 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 )
                                                        Production Department Completed Production
                                                        @elseif($data->business_status_id == 1118 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1119 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1136 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134 )
                                                        Store Department forward to Production Department
                                                        @elseif($data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1140 &&
                                                        $data->security_status_id == 1132 &&
                                                        $data->quality_status_id == 1134)
                                                        Store Department Material Received from Quality Department
                                                        @elseif($data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_owner == "1129" &&
                                                        $data->purchase_status_from_purchase == 1129 &&
                                                        $data->finanace_store_receipt_status_id == 1140)
                                                        Purchase Department PO Send to Vendor
                                                        @elseif($data->business_status_id == 1126 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123 &&
                                                        $data->purchase_status_from_purchase == 1126)
                                                        Purchase Department Approved Owner
                                                        @elseif($data->business_status_id == 1123 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1117 &&
                                                        $data->store_status_id == 1123)
                                                        Store Department
                                                        @elseif($data->business_status_id == 1112 &&
                                                        $data->design_status_id == 1114 &&
                                                        $data->production_status_id == 1114)
                                                        Production Department
                                                        @elseif($data->business_status_id == 1112 &&
                                                        $data->design_status_id == 1113 &&
                                                        $data->production_status_id == 1113)
                                                        Design Department
                                                        @elseif($data->business_status_id == 1112 &&
                                                        $data->design_status_id == 1111)
                                                        Business Department
                                                        @elseif($data->business_status_id == 1127)
                                                        Logistics Department
                                                        @elseif($data->business_status_id == 1127)
                                                        Dispatch Department
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