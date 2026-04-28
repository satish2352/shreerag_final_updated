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
                                <a href="#" class="btn btn-sm btn-primary mx-1" type="button"
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

                {{-- ================================================================
                     ROW 1 — Financial KPIs (4 cards full width)
                ================================================================ --}}
                <div class="row g-4 mb-4">

                    {{-- Business Value --}}
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card shadow border-0 h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col font-size-dashboard">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Business Value</span>
                                        <span class="h5 font-bold mb-0">
                                            &#8377;&nbsp;{{ number_format($owner_kpis['total_business_value'], 2) }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-briefcase"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Estimation --}}
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card shadow border-0 h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col font-size-dashboard">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total Estimation</span>
                                        <span class="h5 font-bold mb-0">
                                            &#8377;&nbsp;{{ number_format($owner_kpis['total_estimation_all'], 2) }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape text-white text-lg rounded-circle" style="background-color:#20a38e;">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total PO Amount --}}
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card shadow border-0 h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col font-size-dashboard">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total PO Amount</span>
                                        <span class="h5 font-bold mb-0">
                                            &#8377;&nbsp;{{ number_format($owner_kpis['total_po_amount'], 2) }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape text-white text-lg rounded-circle" style="background-color:#1a3a5c;">
                                            <i class="fa-solid fa-shopping-cart"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- GRN Received --}}
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card shadow border-0 h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col font-size-dashboard">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">GRN Received</span>
                                        <span class="h5 font-bold mb-0">
                                            &#8377;&nbsp;{{ number_format($owner_kpis['total_grn_accepted_amount'], 2) }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-success text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-boxes"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- end Row 1 --}}

                {{-- ================================================================
                     ROW 2 — Operational Counts (4 count cards + donut chart)
                ================================================================ --}}
                <div class="row g-4 mb-4">

                    {{-- Left: 4 count cards --}}
                    <div class="col-xl-8">
                        <div class="row g-3">

                            {{-- Active Projects --}}
                            <div class="col-xl-6 col-sm-6 col-12">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="row border-bottom pb-2">
                                            <div class="col font-size-dashboard">
                                                <span class="font-semibold text-muted text-sm d-block mb-2">
                                                    <i class="fa-solid fa-city me-1 text-primary"></i>Active Projects
                                                </span>
                                                <span class="h5 font-bold mb-0">{{ $return_data['active_businesses'] }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-sm">
                                            <a href="{{ route('list-business') }}">
                                                <span class="badge badge-pill bg-soft-success text-success me-2">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </span>
                                                <span class="text-nowrap text-xs text-muted">View Details</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Products --}}
                            <div class="col-xl-6 col-sm-6 col-12">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="row border-bottom pb-2">
                                            <div class="col font-size-dashboard">
                                                <span class="font-semibold text-muted text-sm d-block mb-2">
                                                    <i class="fa-solid fa-cube me-1 text-primary"></i>Total Products
                                                </span>
                                                <span class="h5 font-bold mb-0">{{ $return_data['business_details'] }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-sm">
                                            <a href="{{ route('list-business') }}">
                                                <span class="badge badge-pill bg-soft-success text-success me-2">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </span>
                                                <span class="text-nowrap text-xs text-muted">View Details</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Products In Progress --}}
                            <div class="col-xl-6 col-sm-6 col-12">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="row border-bottom pb-2">
                                            <div class="col font-size-dashboard">
                                                <span class="font-semibold text-muted text-sm d-block mb-2">
                                                    <i class="fa-solid fa-spinner me-1 text-warning"></i>Products In Progress
                                                </span>
                                                <span class="h5 font-bold mb-0">{{ $owner_kpis['products_in_progress'] }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-sm">
                                            <span class="text-nowrap text-xs text-muted">Active workflow items</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Products Completed --}}
                            <div class="col-xl-6 col-sm-6 col-12">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="row border-bottom pb-2">
                                            <div class="col font-size-dashboard">
                                                <span class="font-semibold text-muted text-sm d-block mb-2">
                                                    <i class="fa-solid fa-check-circle me-1 text-success"></i>Products Completed
                                                </span>
                                                <span class="h5 font-bold mb-0">{{ $owner_kpis['products_completed'] }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-sm">
                                            <span class="text-nowrap text-xs text-muted">Fully dispatched</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end inner row --}}
                    </div>{{-- end col-xl-8 --}}

                    {{-- Right: Donut Chart --}}
                    <div class="col-xl-4">
                        <div class="card shadow border-0 h-100">
                            <canvas id="myDonutChart" width="300" height="300"></canvas>
                        </div>
                    </div>

                </div>{{-- end Row 2 --}}

                {{-- ================================================================
                     ROW 3 — Action Required + Realized Financials (3 cards)
                ================================================================ --}}
                <div class="row g-4 mb-4">

                    {{-- Pending Approvals --}}
                    <div class="col-xl-4 col-md-4 col-12">
                        <div class="card shadow border-0 h-100 {{ $owner_kpis['pending_owner_actions'] > 0 ? 'border-warning' : '' }}"
                             style="{{ $owner_kpis['pending_owner_actions'] > 0 ? 'border-left:4px solid #fd7e14 !important;' : '' }}">
                            <div class="card-body">
                                <div class="row align-items-center border-bottom pb-2">
                                    <div class="col font-size-dashboard">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">
                                            <i class="fa-solid fa-clock me-1 {{ $owner_kpis['pending_owner_actions'] > 0 ? 'text-warning' : 'text-muted' }}"></i>Pending Approvals
                                        </span>
                                        <span class="h5 font-bold mb-0 {{ $owner_kpis['pending_owner_actions'] > 0 ? 'text-warning' : '' }}">
                                            {{ $owner_kpis['pending_owner_actions'] }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape text-white text-lg rounded-circle"
                                             style="background-color:{{ $owner_kpis['pending_owner_actions'] > 0 ? '#fd7e14' : '#6c757d' }};">
                                            <i class="fa-solid fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm">
                                    <a href="{{ route('list-approved-purchase-orders-owner') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </span>
                                        <span class="text-nowrap text-xs text-muted">Review Now</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total PO Orders (count) --}}
                    <div class="col-xl-4 col-md-4 col-12">
                        <div class="card shadow border-0 h-100">
                            <div class="card-body">
                                <div class="row align-items-center border-bottom pb-2">
                                    <div class="col font-size-dashboard">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">
                                            <i class="fa-solid fa-file-alt me-1 text-primary"></i>Total PO Orders
                                        </span>
                                        <span class="h5 font-bold mb-0">
                                            {{ $owner_kpis['total_po_orders'] }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-file-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm">
                                    <a href="{{ route('list-business') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </span>
                                        <span class="text-nowrap text-xs text-muted">View Details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Realized Profit (dispatched products only) --}}
                    <div class="col-xl-4 col-md-4 col-12">
                        <div class="card shadow border-0 h-100">
                            <div class="card-body">
                                <div class="row align-items-center border-bottom pb-2">
                                    <div class="col font-size-dashboard">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">
                                            <i class="fa-solid fa-chart-line me-1 text-success"></i>Realized Profit
                                        </span>
                                        <span class="h5 font-bold mb-0">
                                            &#8377;&nbsp;{{ number_format($business_total_amount['profit'], 2) }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-success text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-chart-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm">
                                    <span class="text-nowrap text-xs text-muted">From fully dispatched products</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- end Row 3 --}}

            </div>{{-- end container-fluid --}}
        </main>

        {{-- Donut Chart Script --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ctx = document.getElementById('myDonutChart').getContext('2d');

                const counts = @json($return_data);

                const labels = [
                    "Business Completed",
                    "Business In Process",
                    "Product Completed",
                    "Product In Process"
                ];

                const data = [
                    counts.business_completed ?? 0,
                    counts.business_inprocess ?? 0,
                    counts.product_completed ?? 0,
                    counts.product_inprocess ?? 0
                ];

                const backgroundColors = [
                    '#2D4E59',
                    '#33B78C',
                    '#34BAB8',
                    '#199CC2'
                ];

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        cutout: '80%',
                        borderWidth: 2,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            title: {
                                display: true,
                                text: 'Project Details',
                                font: {
                                    size: 18
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            });
        </script>

        <style>
            #myDonutChart {
                max-width: 400px;
                height: 235px;
                padding: 30px;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    </div>
</div>

{{-- Customer PO List offcanvas (unchanged) --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Customer PO List</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="accordion" id="accordionExample">
            @foreach ($offcanvas['offcanvas'] as $po_number => $grouped_data)
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
                        <div class="accordion-body" style="overflow-y: auto; max-height: 80vh; padding-bottom: 20px;">
                            <ul class="list-unstyled">
                                @foreach ($grouped_data as $data)
                                    <li class="right-side"
                                        style="color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }};">
                                        <b>{{ $data->product_name }}</b> :
                                        @switch(true)
                                            @case($data->quantity_tracking_status == 3005 && $data->dispatch_status_id == 1154)
                                                Product Dispatch Completed
                                            @break

                                            @case($data->quantity_tracking_status == 3005)
                                                Dispatch Department Product Dispatch Completed Quantity
                                                {{ $data->completed_quantity }}
                                            @break

                                            @case($data->quantity_tracking_status == 3004)
                                                Finance Department sent to Dispatch Department {{ $data->completed_quantity }}
                                            @break

                                            @case($data->quantity_tracking_status == 3003)
                                                Finance Department Received from Logistics Department Quantity
                                                {{ $data->completed_quantity }}
                                            @break

                                            @case($data->quantity_tracking_status == 3002)
                                                Logistics Department Submitted Form {{ $data->completed_quantity }}
                                            @break

                                            @case($data->quantity_tracking_status == 3001)
                                                Production Department Completed Production and Received Logistics Department
                                                Quantity {{ $data->completed_quantity }}
                                            @break

                                            @case($data->po_tracking_status == 4003)
                                                Store Department forward to Production Department
                                            @break

                                            @case($data->po_tracking_status == 4002)
                                                Quality Department (Generated GRN) and Store Department Material Received PO
                                                {{ $data->purchase_orders_id }} &amp; {{ $data->tracking_id }} time
                                            @break

                                            @case($data->po_tracking_status == 4001)
                                                Security Department Received Material and PO {{ $data->purchase_orders_id }}
                                                also Generated Gate Pass {{ $data->tracking_id }} time
                                            @break

                                            @case($data->off_canvas_status == 25)
                                                Purchase Department PO {{ $data->purchase_orders_id }} Send to Vendor
                                            @break

                                            @case($data->off_canvas_status == 24)
                                                Purchase Department Approved Owner
                                            @break

                                            @case($data->off_canvas_status == 23)
                                                Purchase Department
                                            @break

                                            @case($data->off_canvas_status == 17)
                                                Store Department Issue Material Send to Production Dept
                                            @break

                                            @case($data->off_canvas_status == 16)
                                                Store Department submitted requisition form
                                            @break

                                            @case($data->off_canvas_status == 15)
                                                Accepted Production Dept and send to store Department
                                            @break

                                            @case($data->off_canvas_status == 14)
                                                Corrected Design Submitted to Production Department
                                            @break

                                            @case($data->off_canvas_status == 13)
                                                Rejected Design in Production Department
                                            @break

                                            @case($data->off_canvas_status == 33)
                                                Estimation Department Estimation Send Production Department
                                            @break

                                            @case($data->off_canvas_status == 32)
                                                Owner Department Estimation Accepted and Send Estimation Department
                                            @break

                                            @case($data->off_canvas_status == 31)
                                                Owner Department Revised Estimation Received
                                            @break

                                            @case($data->off_canvas_status == 30)
                                                Owner Department Estimation Rejected and Send Estimation Department
                                            @break

                                            @case($data->off_canvas_status == 28)
                                                Owner Department Received Estimation for Accept or Reject
                                            @break

                                            @case($data->off_canvas_status == 12)
                                                Design Department Submitted Design and Received Estimation Department
                                            @break

                                            @case($data->off_canvas_status == 11)
                                                Business Department Request sent to Design Department
                                            @break

                                            @default
                                                Unknown Department
                                        @endswitch
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
