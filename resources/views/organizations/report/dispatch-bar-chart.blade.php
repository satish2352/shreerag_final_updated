@extends('admin.layouts.master')

@section('content')
    <style>
        .chart-container {
            height: 500px;
            width: 100%;
            margin-bottom: 50px;
        }
    </style>

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list">
                            <div class="main-sparkline13-hd">
                                    <h1 class="mb-4">Product-wise Dispatch Status</h1>
                                      <form method="GET" action="{{ url()->current() }}">
                                        <div class="row mb-4">
                                               <div class="col-md-4">
    <label>Project Name</label>
    <select class="form-control select2" name="project_name" id="project_name">
        <option value="">All Projects</option>
        @foreach($getProjectName as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>
                                             <div class="col-md-2">
                                                <label>Product Name</label>
                                                <select class="form-control select2" name="business_details_id" id="business_details_id">
                                                    <option value="">All Product Name</option>
                                                    {{-- Product options will be populated via JS --}}
                                                </select>
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <label for="business_details_id">Product Name</label>
                                                <select name="product_name" id="business_details_id" class="form-control">
                                                    <option value="">All Product Name</option>
                                                    @foreach($getProductName as $id => $name)
                                                        <option value="{{ $id }}" {{ request('product_name') == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div> --}}

                                            <div class="col-md-4 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary filterbg">Filter</button>
                                             <button type="button" class="btn btn-secondary ms-2" id="resetFilters" style="margin-left: 10px;">
        Reset
    </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="chart-container">
                                        <canvas id="dispatchBarChartProduct"></canvas>
                                    </div>
                                    
                               </div>
                                    <h5 class="mb-4">Month-wise Dispatch Status</h5>
                                    <div class="chart-container">
                                        <canvas id="dispatchBarChartMonth"></canvas>
                                    </div>

                                    <h5 class="mb-4">Vendor through material Received</h5>
                                    <div class="chart-container">
                                        <canvas id="vendorPieChart"></canvas>
                                    </div>

                                </div>
                            </div>
            </div>
        </div>
    </div>

@endsection


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.APP_URL = "{{ config('app.url') }}";
</script>
    <script>
        $(document).ready(function () {
            // === Month-wise Dispatch Bar Chart ===
            const dispatchData = @json($data['data']);
            const labelsMonth = dispatchData.map(item => item.month_label);
            const completedMonth = dispatchData.map(item => Number(item.total_completed_quantity));
            const pendingMonth = dispatchData.map(item => Number(item.pending_quantity));
            const totalMonth = dispatchData.map(item => Number(item.total_quantity));

            new Chart(document.getElementById('dispatchBarChartMonth').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsMonth,
                    datasets: [{
                        label: 'Dispatched Quantity',
                        data: completedMonth,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Dispatched Quantity Per Month'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const i = context.dataIndex;
                                    return [
                                        `Dispatched: ${completedMonth[i]}`,
                                        `Pending: ${pendingMonth[i]}`,
                                        `Total: ${totalMonth[i]}`
                                    ];
                                }
                            }
                        },
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Quantity' }},
                        x: { title: { display: true, text: 'Month' }}
                    }
                }
            });

            // === Product-wise Dispatch Bar Chart ===
            const productData = @json($DataProductWise['data']);
            const labelsProduct = productData.map(item => item.product_name);
            const completedProduct = productData.map(item => Number(item.total_completed_quantity));
            const totalProduct = productData.map(item => Number(item.quantity));
            const pendingProduct = productData.map((item, i) => totalProduct[i] - completedProduct[i]);

            new Chart(document.getElementById('dispatchBarChartProduct').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsProduct,
                    datasets: [{
                        label: 'Dispatched Quantity',
                        data: completedProduct,
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Dispatched Quantity Per Product'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const i = context.dataIndex;
                                    return [
                                        `Dispatched: ${completedProduct[i]}`,
                                        `Pending: ${pendingProduct[i]}`,
                                        `Total: ${totalProduct[i]}`
                                    ];
                                }
                            }
                        },
                        legend: { display: false }
                    },
                    scales: {
                        x: { beginAtZero: true, title: { display: true, text: 'Quantity' }},
                        y: { title: { display: true, text: 'Product' }}
                    }
                }
            });

            // === Vendor-wise Pie Chart ===
            const vendorData = @json($vendorWise);
            const vendorLabels = vendorData.map(item => item.vendor_name);
            const vendorQuantities = vendorData.map(item => Number(item.total_quantity));

            new Chart(document.getElementById('vendorPieChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: vendorLabels,
                    datasets: [{
                        data: vendorQuantities,
                        backgroundColor: vendorLabels.map((_, i) =>
                            `hsl(${(i * 360) / vendorLabels.length}, 70%, 60%)`
                        )
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Material Distribution by Vendor'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const i = context.dataIndex;
                                    const quantity = vendorQuantities[i];
                                    const percent = vendorData[i].percentage;
                                    return `${vendorLabels[i]}: ${quantity} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // === Project → Product dynamic dropdown ===
            document.getElementById('project_name').addEventListener('change', function () {
                let projectId = this.value;
                let productSelect = document.getElementById('business_details_id');
                productSelect.innerHTML = '<option value="">All Product Name</option>';

                if (!projectId) return;

                let url = '{{ url("designdept/get-products-by-project") }}/' + projectId;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            data.products.forEach(product => {
                                const option = document.createElement('option');
                                option.value = product.id;
                                option.textContent = product.name;
                                productSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Failed to load products:", error);
                    });
            });
        });
    </script>
  

