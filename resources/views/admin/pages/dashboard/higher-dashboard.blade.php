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
                                <a href="#" class="btn  btn-sm btn-primary mx-1"
                                    type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasRight"
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
                                    {{-- <a href="{{ route('list-product-dispatch-completed') }}"> --}}
                                    <span
                                        class="badge badge-pill bg-soft-success text-success me-2">
                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                    <span class="text-nowrap text-xs text-muted">
                                        {{-- view
                                    details --}}
                                    </span>
                                    {{-- </a> --}}
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
                                    <a
                                        href="{{ route('list-dispatch-final-product-close') }}">
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
                                    <a
                                        href="{{ route('list-dispatch-final-product-close') }}">
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
                    <div class="col-xl-4 col-sm-6 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span
                                            class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                            Employee</span>
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
                                        <span class="text-nowrap text-xs text-muted">view
                                            details</span>
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
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Customer PO List</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="accordion" id="accordionExample">
            @foreach ($return_data['data_output_offcanvas'] as $po_number => $grouped_data)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                        <button class="accordion-button" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $loop->index }}"
                            aria-expanded="true"
                            aria-controls="collapse{{ $loop->index }}">
                            {{ $po_number }} - {{ $grouped_data->first()->title }}
                        </button>
                    </h2>
                    <div id="collapse{{ $loop->index }}"
                        class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $loop->index }}"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body"
                            style="overflow: scroll; height: 100vh; padding-bottom:400px;">
                            <div>
                                <ul
                                    style="list-style-type: disc; list-style-position: outside;">
                                    @foreach ($grouped_data as $data)
                                        <li class="right-side"
                                            style="color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }};">
                                            <span><b>{{ $data->product_name }}</b> : -</span>

                                            @if ($data->quantity_tracking_status == 3005)
                                                Dispatch Department Product Dispatch Completed
                                                Quantity {{ $data->completed_quantity }}
                                            @elseif($data->quantity_tracking_status == 3004)
                                                Finance Department sent to Dispatch Department
                                                {{ $data->completed_quantity }}
                                            @elseif($data->quantity_tracking_status == 3003)
                                                Finance Department Received from Logistics
                                                Department Quantity
                                                {{ $data->completed_quantity }}
                                            @elseif($data->quantity_tracking_status == 3002)
                                                Logistics Department Submitted Form
                                                {{ $data->completed_quantity }}
                                            @elseif($data->quantity_tracking_status == 3001)
                                                Production Department Completed Production and
                                                Received Logistics Department Quantity
                                                {{ $data->completed_quantity }}
                                            @elseif($data->po_tracking_status == 4003)
                                                Store Department forward to Production
                                                Department
                                            @elseif($data->po_tracking_status == 4002)
                                                Quality Department(Generated GRN) and Store
                                                Department Material Received PO
                                                {{ $data->purchase_orders_id }} &
                                                {{ $data->tracking_id }} time
                                            @elseif($data->po_tracking_status == 4001)
                                                Security Department Received Material and PO
                                                {{ $data->purchase_orders_id }} also Generated
                                                Gate Pass {{ $data->tracking_id }} time
                                            @elseif($data->off_canvas_status == 25)
                                                Purchase Department PO
                                                {{ $data->purchase_orders_id }} Send to Vendor
                                            @elseif($data->off_canvas_status == 24)
                                                Purchase Department Approved Owner
                                            @elseif($data->off_canvas_status == 23)
                                                Purchase Department
                                            @elseif($data->off_canvas_status == 16)
                                                Store Department submitted requistion form

                                                {{-- @elseif($data->business_status_id == 1115 &&
                            $data->design_status_id == 1115 &&
                            $data->production_status_id == 1115)
                            Production Department Rejected Design and Received Design Department --}}
                                            @elseif($data->off_canvas_status == 15)
                                                Accepted Production Department and send to store
                                                Department
                                            @elseif($data->off_canvas_status == 14)
                                                Corrected Design Submitted to Production
                                                Department
                                                {{-- @elseif($data->off_canvas_status == 14 && $data->business_status_id == 1115 || $data->business_status_id == 1126 &&
                            $data->design_status_id == 1115 &&
                            $data->production_status_id == 1115 && $data->reject_reason_prod == "")
                            Corrected Design Submitted to Production Department --}}
                                            @elseif($data->off_canvas_status == 13)
                                                Rejected Design in Production Department
                                            @elseif($data->off_canvas_status == 12)
                                                Design Department Submited Design and Received
                                                Production Department
                                            @elseif($data->off_canvas_status == 11)
                                                Business Department Request send to Design
                                                Department
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