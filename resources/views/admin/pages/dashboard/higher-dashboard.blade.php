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

      <div class="col-xl-8 col-md-8 col-lg-8">
                    {{-- =============================================== --}}
                    <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total Revenue
                                        </span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['active_businesses'] }}

                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total Profit
                                        </span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['active_businesses'] }}

                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total utilize
                                        </span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['active_businesses'] }}

                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- ======================================================= --}}
                    <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total Project
                                        </span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['active_businesses'] }}

                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 mb-0 text-sm">
                                    <a href="{{ route('list-business') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i> </span>
                                        <span class="text-nowrap text-xs text-muted">View
                                            Details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ccol-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total
                                            Product</span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['business_details'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-paint-brush"></i>

                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 mb-0 text-sm">
                                    <a href="{{ route('list-business') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i> </span>
                                        <span class="text-nowrap text-xs text-muted">View
                                            Details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ccol-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total
                                            PO Order</span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['business_details'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-paint-brush"></i>

                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 mb-0 text-sm">
                                    <a href="{{ route('list-business') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i> </span>
                                        <span class="text-nowrap text-xs text-muted">View
                                            Details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Inprocess
                                            Product</span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['product_inprocess'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-store"></i>

                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 mb-0 text-sm">
                                    <span class="badge badge-pill bg-soft-success text-success me-2">
                                        <i class="fa-solid fa-arrow-right"></i> </span>
                                    <span class="text-nowrap text-xs text-muted">
                                        
                                    </span>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Completed
                                            Customer PO</span>
                                        <span class="h5 font-bold mb-0">
                                            {{ $return_data['business_completed'] }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-industry"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 mb-0 text-sm">
                                    <a href="{{ route('list-dispatch-final-product-close') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i> </span>
                                        <span class="text-nowrap text-xs text-muted">view
                                            details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Completed
                                            Product</span>
                                        <span class="h5 font-bold mb-0">
                                            {{ $return_data['product_completed'] }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 mb-0 text-sm">
                                    <a href="{{ route('list-product-dispatch-completed') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i> </span>
                                        <span class="text-nowrap text-xs text-muted">view
                                            details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xl-4 col-sm-4 col-12 mb-2">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col mb-2">
                                        <span class="font-semibold text-muted text-sm d-block mb-2">Total
                                            Employee</span>
                                        <span class="h5 font-bold mb-0">{{ $return_data['user_active_count'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                            <i class="fa-solid fa-shopping-cart"></i>

                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 mb-0 text-sm">
                                    <a href="{{ route('list-users') }}">
                                        <span class="badge badge-pill bg-soft-success text-success me-2">
                                            <i class="fa-solid fa-arrow-right"></i> </span>
                                        <span class="text-nowrap text-xs text-muted">view
                                            details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
      </div>
       <div class="col-xl-4 col-md-4 col-lg-4">
        <div class="card shadow border-0">
             <canvas id="myDonutChart" width="300" height="300" class=""></canvas>
        </div>
       
<script>
     document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myDonutChart').getContext('2d');

    const labels = ["Complete Project", "Pending Project", "Product C", "Product D"];
    const data = [25, 15, 30, 10];

    const backgroundColors = [
        '#2D4E59', // Deep Blue-Teal
        '#33B78C', // Emerald Green
        '#34BAB8', // Aqua Blue
        '#199CC2'  // Sky Blue
    ];

    const donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors
            }]
        },
        options: {
            cutout: '80%', // thinner ring
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
            maintainAspectRatio: false // allow custom height
        }
    });
});
</script>

<style>
#myDonutChart {
    max-width: 400px;   /* width limit */
    height: 235px;      /* reduced height */
    padding: 30px;
}
</style>





{{-- =============== --}}

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
        {{-- <script>
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
        </script> --}}
<style>
#myPieChart {
    max-width: 400px;  /* limit width */
    max-height: 400px; /* limit height */
}
</style>
<script>
var ctx = document.getElementById('myPieChart').getContext('2d');

// Pass PHP data to JavaScript
var counts = @json($return_data);

var backgroundColors = [
      '#1E3A5F', // Dark Navy
    '#3FB8AF', // Teal
    '#7FC7D9', // Aqua
    '#4A90E2', // Sky Blue
    '#5D7290', // Steel Blue
    '#A9B8C9'  // Light Gray-Blue
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
        responsive: true,
        maintainAspectRatio: false, // allow custom size
        plugins: {
            tooltip: { enabled: true },
            legend: { position: 'right' },
            title: {
                display: true,
                text: 'Product Pie Chart',
                font: { size: 16 }
            }
        }
    }
});
</script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        {{-- <script>
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
        </script> --}}


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('myBarChart').getContext('2d');
var counts = @json($return_data);
var labels = Object.keys(counts);
var dataValues = Object.values(counts);

// Create gradient for each bar
function createGradient(ctx, color1, color2) {
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
}

var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Products',
            data: dataValues,
            backgroundColor: [
                createGradient(ctx, 'rgba(45,78,89,0.8)', 'rgba(45,78,89,0.2)'),
                createGradient(ctx, 'rgba(51,183,140,0.8)', 'rgba(51,183,140,0.2)'),
                createGradient(ctx, 'rgba(52,186,184,0.8)', 'rgba(52,186,184,0.2)'),
                createGradient(ctx, 'rgba(25,156,194,0.8)', 'rgba(25,156,194,0.2)'),
                createGradient(ctx, 'rgba(53,133,178,0.8)', 'rgba(53,133,178,0.2)'),
                createGradient(ctx, 'rgba(109,155,170,0.8)', 'rgba(109,155,170,0.2)')
            ],
            borderRadius: 8, // Rounded bars
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Product Bar Chart',
                font: { size: 20 }
            }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
    </div>
</div>
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
                                                {{ $data->purchase_orders_id }} & {{ $data->tracking_id }} time
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

                                            @case($data->off_canvas_status == 16)
                                                Store Department submitted requisition form
                                            @break

                                            @case($data->off_canvas_status == 15)
                                                Accepted Production Department and send to store Department
                                            @break

                                            @case($data->off_canvas_status == 14)
                                                Corrected Design Submitted to Production Department
                                            @break

                                            @case($data->off_canvas_status == 13)
                                                Rejected Design in Production Department
                                            @break

                                            @case($data->off_canvas_status == 12)
                                                Design Department Submitted Design and Received Production Department
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
