<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shreerag Engineering</title>
    <meta name="description" content="Shreerag Engineering is a trusted production company specializing in the design and manufacturing of high-quality industrial trolleys, warehouse trolleys, and material handling solutions.">
    <meta name="keywords" content="Shreerag Engineering, trolley manufacturer, industrial trolley, warehouse trolley, heavy duty trolley, customized trolley, fabrication trolley, material handling equipment, logistics trolley, quality trolleys, factory trolley, production company, engineering solutions India">

    <!-- ✅ Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('website/assets/img/logo/Layer 2.png')}}">

    <!-- ✅ CSS files -->
    <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/animate.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/meanmenu.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/default.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/formstyle.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/color.css')}}" class="color-changing">
    <link rel="stylesheet" href="{{ asset('website/assets/css/responsive.css')}}">

    <!-- ✅ Font Awesome (do NOT load webfont files manually, just use CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- Preloader (Optional, uncomment if you want it visible) -->
    {{-- 
    <div class="loader-page flex-center">
        <img src="{{ asset('website/assets/img/loader.gif')}}" alt="Loading...">
    </div> 
    --}}

    <!-- ✅ Header Start -->
    <header class="transperant-head transition-4">
        <div class="container-fluid d-flex justify-content-center nvvv">
            <div class="row align-items-center tab-navbar">

                <!-- ✅ Logo -->
                <div class="col-lg-2 col-md-5 col-sm-4 col-3">
                    <div class="logo transition-4">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png')}}" class="transition-4" alt="Shreerag Engineering Logo">
                        </a>
                    </div>
                </div>

                <!-- ✅ Navigation -->
                <div class="col-lg-10 col-md-7 col-sm-8">
                    <div class="menu-links">
                        <nav class="main-menu white">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="{{ url('/about') }}">About Us</a></li>
                                <li><a href="{{ url('/product') }}">Product</a></li>
                                <li><a href="{{ url('/services') }}">Services</a></li>
                                <li>
                                    <!-- Desktop CTA Button -->
                                    <a href="{{ url('/contactus') }}" class="btn btn-round2 d-none d-lg-block navcont">
                                        Contact Us
                                    </a>
                                    <!-- Mobile Link -->
                                    <a href="{{ url('/contactus') }}" class="d-lg-none">Contact Us</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- ✅ Mobile Menu Placeholder -->
                <div class="col-12">
                    <div class="mobile-menu"></div>
                </div>
            </div>
        </div>
    </header>
    <!-- ✅ Header End -->

    <!-- ✅ Search Modal -->
    <div class="search-popup modal fade" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="#">
                        <div class="form-group relative">
                            <input type="text" class="form-control input-search" placeholder="Search here...">
                            <i class="fas fa-search transform-v-center"></i>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <i class="fas fa-times close-search-modal" data-dismiss="modal" aria-label="Close"></i>
    </div>
    <!-- ✅ Search Modal End -->
    
    <!-- The search Modal end -->
    <!-- Header end -->
