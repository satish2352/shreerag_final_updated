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
                            {{-- <div class="mx-n1">
                                <a href="#" class="btn btn-sm btn-primary mx-1 load-offcanvas-data" 
                                    type="button" data-bs-toggle="offcanvas" 
                                    data-bs-target="#offcanvasRight"
                                    aria-controls="offcanvasRight">
                                    <span class="p-1">
                                        <i class="fa-solid fa-bars"></i>
                                    </span>
                                </a>
                            </div>  --}}
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
                                        href="{{ route('list-product-dispatch-completed') }}">
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
            @foreach ($offcanvas['offcanvas'] as $po_number => $grouped_data)
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
                            style="overflow-y: auto; max-height: 80vh; padding-bottom: 20px;">
                            <ul class="list-unstyled">
                                @foreach ($grouped_data as $data)
                                    <li class="right-side"
                                        style="color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }};">
                                        <b>{{ $data->product_name }}</b> :
                                        @switch(true)
                                            @case($data->quantity_tracking_status == 3005)
                                                Dispatch Department Product Dispatch Completed Quantity {{ $data->completed_quantity }}
                                                @break
                                            @case($data->quantity_tracking_status == 3004)
                                                Finance Department sent to Dispatch Department {{ $data->completed_quantity }}
                                                @break
                                            @case($data->quantity_tracking_status == 3003)
                                                Finance Department Received from Logistics Department Quantity {{ $data->completed_quantity }}
                                                @break
                                            @case($data->quantity_tracking_status == 3002)
                                                Logistics Department Submitted Form {{ $data->completed_quantity }}
                                                @break
                                            @case($data->quantity_tracking_status == 3001)
                                                Production Department Completed Production and Received Logistics Department Quantity {{ $data->completed_quantity }}
                                                @break
                                            @case($data->po_tracking_status == 4003)
                                                Store Department forward to Production Department
                                                @break
                                            @case($data->po_tracking_status == 4002)
                                                Quality Department (Generated GRN) and Store Department Material Received PO {{ $data->purchase_orders_id }} & {{ $data->tracking_id }} time
                                                @break
                                            @case($data->po_tracking_status == 4001)
                                                Security Department Received Material and PO {{ $data->purchase_orders_id }} also Generated Gate Pass {{ $data->tracking_id }} time
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
{{-- <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Customer PO List</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Loading Spinner -->
        <div id="loadingIndicator" style="display: none; text-align: center;">
            <i class="fa fa-spinner fa-spin fa-3x"></i>
        </div>

        <!-- Dynamic Data will be loaded here -->
        <div id="offcanvasContent"></div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let page = 1;
        let isLoading = false; // Prevent multiple AJAX calls
        let hasMorePages = true; // Track if more pages exist
    
        function loadOffcanvasData() {
            if (isLoading || !hasMorePages) return; // Stop loading if already in progress or no more pages
    
            let offcanvasContent = $('#offcanvasContent');
            let loadingIndicator = $('#loadingIndicator');
    
            isLoading = true; // Prevent duplicate calls
            loadingIndicator.show();
    
            $.ajax({
                url: "{{ route('get-offcanvas-data') }}?page=" + page,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    loadingIndicator.hide();
                    isLoading = false; // Allow next requests
    
                    if (response && Object.keys(response).length > 0) {
                        let contentHtml = '';
    
                        $.each(response, function(po_number, grouped_data) {
                            let firstItem = grouped_data[0];
    
                            contentHtml += `
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading${po_number}">
                                        <button class="accordion-button" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse${po_number}"
                                            aria-expanded="true"
                                            aria-controls="collapse${po_number}">
                                            ${po_number} - ${firstItem.title}
                                        </button>
                                    </h2>
                                    <div id="collapse${po_number}" class="accordion-collapse collapse"
                                        aria-labelledby="heading${po_number}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body" style="overflow-y: auto; max-height: 80vh; padding-bottom: 20px;">
                                            <ul class="list-unstyled">`;
    
                            $.each(grouped_data, function(index, data) {
                                let statusMessage = getStatusMessage(data);
                                contentHtml += `<li class="right-side"
                                        style="color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }};"><b>${data.product_name}</b> : ${statusMessage}</li>`;
                            });
    
                            contentHtml += `</ul></div></div></div>`;
                        });
    
                        offcanvasContent.append(contentHtml);
    
                        // Check if more pages exist
                        hasMorePages = response.next_page_url !== null;
                        if (hasMorePages) {
                            page++; // Increment page for next request
                        }
                    } else {
                        hasMorePages = false; // No more pages exist
                    }
                },
                error: function(xhr, status, error) {
                    loadingIndicator.hide();
                    isLoading = false; // Reset loading state
                    offcanvasContent.append('<p class="text-danger">Error loading data. Please try again.</p>');
                }
            });
        }
    
        // Detect scrolling to bottom and load more data
        $('#offcanvasRight').on('scroll', function() {
            if (!isLoading && hasMorePages) {
                let scrollBottom = $(this).scrollTop() + $(this).innerHeight();
                let scrollThreshold = this.scrollHeight - 50; // Load more slightly before reaching bottom
                if (scrollBottom >= scrollThreshold) {
                    loadOffcanvasData();
                }
            }
        });
    
        // Initial load
        loadOffcanvasData();
    
        function getStatusMessage(data) {
            let statusMessages = {
                3005: `Dispatch Department Product Dispatch Completed Quantity ${data.completed_quantity}`,
                3004: `Finance Department sent to Dispatch Department ${data.completed_quantity}`,
                3003: `Finance Department Received from Logistics Department Quantity ${data.completed_quantity}`,
                3002: `Logistics Department Submitted Form ${data.completed_quantity}`,
                3001: `Production Department Completed Production and Received Logistics Department Quantity ${data.completed_quantity}`,
                4003: `Store Department forward to Production Department`,
                4002: `Quality Department (Generated GRN) and Store Department Material Received PO ${data.purchase_orders_id} & ${data.tracking_id} time`,
                4001: `Security Department Received Material and PO ${data.purchase_orders_id} also Generated Gate Pass ${data.tracking_id} time`,
                25: `Purchase Department PO ${data.purchase_orders_id} Send to Vendor`,
                24: `Purchase Department Approved Owner`,
                23: `Purchase Department`,
                16: `Store Department submitted requisition form`,
                15: `Accepted Production Department and send to store Department`,
                14: `Corrected Design Submitted to Production Department`,
                13: `Rejected Design in Production Department`,
                12: `Design Department Submitted Design and Received Production Department`,
                11: `Business Department Request sent to Design Department`
            };
            return statusMessages[data.off_canvas_status] || "Unknown Department";
        }
    });
    </script>
     --}}

     {{-- <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Customer PO List</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Loading Spinner -->
        <div id="loadingIndicator" style="display: none; text-align: center;">
            <i class="fa fa-spinner fa-spin fa-3x"></i>
        </div>

        <!-- Dynamic Data will be loaded here -->
        <div id="offcanvasContent"></div>
    </div>
</div>
<script>
    $(document).ready(function() {
    let page = 1;
    let isLoading = false; // Prevent multiple AJAX calls
    let hasMorePages = true; // Track if more pages exist

    function loadOffcanvasData() {
        if (isLoading || !hasMorePages) return; // Stop loading if already in progress or no more pages

        let offcanvasContent = $('#offcanvasContent');
        let loadingIndicator = $('#loadingIndicator');

        isLoading = true; // Prevent duplicate calls
        loadingIndicator.show();

        $.ajax({
            url: "{{ route('get-offcanvas-data') }}?page=" + page,
            type: "GET",
            dataType: "json",
            success: function(response) {
                // console.log(response);
                // alert(JSON.stringify(response)); 
                loadingIndicator.hide();
                isLoading = false; // Allow next requests

                if (response && Object.keys(response).length > 0) {
                    let contentHtml = '';

                    // Loop through each PO number and its grouped data
                    // Object.entries(response).forEach(([po_number, grouped_data], index) => {
                    //     let firstItem = grouped_data[0];

        // **Sort PO numbers in descending order**
        let sortedPOs = Object.keys(response).sort((a, b) => b - a);

sortedPOs.forEach((po_number, index) => {
    let grouped_data = response[po_number];

    // **Sort items inside each PO number by created_at or another field (if needed)**
    grouped_data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

    let firstItem = grouped_data[0];

                        contentHtml += `
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading${index}">
                                    <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse${index}"
                                        aria-expanded="true"
                                        aria-controls="collapse${index}">
                                        ${po_number} - ${firstItem.title}
                                    </button>
                                </h2>
                                <div id="collapse${index}" class="accordion-collapse collapse"
                                    aria-labelledby="heading${index}"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body" style="overflow-y: auto; max-height: 80vh; padding-bottom: 20px;">
                                        <ul class="list-unstyled">`;

                        // Inner loop to iterate over grouped data
                        grouped_data.forEach(data => {
                            let statusMessage = getStatusMessage(data);
                            let randomColor = generateRandomColor(); // Function to generate random color

                            contentHtml += `<li class="right-side" style="color:#${randomColor};">
                                <b>${data.product_name}</b> : ${statusMessage}
                            </li>`;
                        });

                        contentHtml += `</ul></div></div></div>`;
                    });

                    offcanvasContent.append(contentHtml);

                    // Check if more pages exist
                    hasMorePages = response.next_page_url !== null;
                    if (hasMorePages) {
                        page++; // Increment page for next request
                    }
                } else {
                    hasMorePages = false; // No more pages exist
                }
            },
            error: function(xhr, status, error) {
                loadingIndicator.hide();
                isLoading = false; // Reset loading state
                offcanvasContent.append('<p class="text-danger">Error loading data. Please try again.</p>');
            }
        });
    }

    // Detect scrolling to bottom and load more data
    $('#offcanvasRight').on('scroll', function() {
        if (!isLoading && hasMorePages) {
            let scrollBottom = $(this).scrollTop() + $(this).innerHeight();
            let scrollThreshold = this.scrollHeight - 50; // Load more slightly before reaching bottom
            if (scrollBottom >= scrollThreshold) {
                loadOffcanvasData();
            }
        }
    });

    // Initial load
    loadOffcanvasData();

    // Function to generate status message
    function getStatusMessage(data) {
        let statusMessages = {
            3005: `Dispatch Department Product Dispatch Completed Quantity ${data.completed_quantity}`,
            3004: `Finance Department sent to Dispatch Department ${data.completed_quantity}`,
            3003: `Finance Department Received from Logistics Department Quantity ${data.completed_quantity}`,
            3002: `Logistics Department Submitted Form ${data.completed_quantity}`,
            3001: `Production Department Completed Production and Received Logistics Department Quantity ${data.completed_quantity}`,
            4003: `Store Department forward to Production Department`,
            4002: `Quality Department (Generated GRN) and Store Department Material Received PO ${data.purchase_orders_id} & ${data.tracking_id} time`,
            4001: `Security Department Received Material and PO ${data.purchase_orders_id} also Generated Gate Pass ${data.tracking_id} time`,
            25: `Purchase Department PO ${data.purchase_orders_id} Send to Vendor`,
            24: `Purchase Department Approved Owner`,
            23: `Purchase Department`,
            16: `Store Department submitted requisition form`,
            15: `Accepted Production Department and send to Store Department`,
            14: `Corrected Design Submitted to Production Department`,
            13: `Rejected Design in Production Department`,
            12: `Design Department Submitted Design and Received Production Department`,
            11: `Business Department Request sent to Design Department`
        };
        return statusMessages[data.off_canvas_status] || "Unknown Department";
    }

    // Function to generate a random hex color
    function generateRandomColor() {
        return Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
    }
});

</script> --}}