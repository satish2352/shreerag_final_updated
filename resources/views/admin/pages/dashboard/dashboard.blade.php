<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
 <!-- ============= pratiksha (21/08/24) =============  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
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
    background-color: #31497D!important;
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
    font-size: 15px!important;
    font-weight:bold;
}

.text-nowrap a{
    text-decoration: none!important;
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
.text-sm a{
    text-decoration: none;
    font-size:larger;
}

</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline14-list">
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
                                                        <a href="#" class="btn  btn-sm btn-primary mx-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total Customer PO </span>
                                                                <span class="h5 font-bold mb-0">{{ $return_data['active_businesses'] }}
                                                                    
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
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">View Details</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total Product</span>
                                                                <span class="h5 font-bold mb-0">{{ $return_data['business_details'] }}</span>
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
                                                                <span class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">View Details</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Completed Customer PO</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Completed Product</span>
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
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Inprocess Business</span>
                                                                <span class="h5 font-bold mb-0">{{ $return_data['business_inprocess'] }}</span>
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
                                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Inprocess Product</span>
                                                                <span class="h5 font-bold mb-0">{{ $return_data['product_inprocess'] }}</span>
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
                                                                <span class="badge badge-pill bg-soft-success text-success me-2">
                                                                    <i class="fa-solid fa-arrow-right"></i>                                                           </span>
                                                                <span class="text-nowrap text-xs text-muted">view details</span>
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
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                              <h5 id="offcanvasRightLabel">Customer PO List</h5>
                              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul>
                                <li><span  class="">Leave Management</span></li>
                                <li><span  class="">Leave Management</span></li>
                                </ul>
                            </div>
                       </div>
                       @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection