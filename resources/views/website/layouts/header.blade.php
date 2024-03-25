<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shreerag Engineering </title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Calling Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('website/assets/img/logo/Layer 2.png')}}">
    <!-- Calling Favicon -->
    <!-- ********************* -->
    <!-- CSS files -->
    <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/animate.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/meanmenu.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('website/assets/css/default.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/style.css')}}">
    <link rel="stylesheet" class="color-changing" href="{{ asset('website/assets/css/color.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-brands-400.eot')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-brands-400.svg')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-brands-400.ttf')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-brands-400.woff')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-brands-400.woff2')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-brands-400d41d.eot')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-regular-400.eot')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-regular-400.svg')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-regular-400.ttf')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-regular-400.woff')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-regular-400.woff2')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-regular-400d41d.eot')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-solid-900.eot')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-solid-900.svg')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-solid-900.ttf')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-solid-900.woff')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-solid-900.woff2')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/webfonts/fa-solid-900d41d.eot')}}">
    
    <!-- End CSS files -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- Preloader start -->
    {{-- <div class="loader-page flex-center">
        <img src="{{ asset('website/assets/img/loader.gif')}}" alt="">
    </div> --}}
    <!-- Preloader end -->

    <!-- Header start -->
    <header class="transperant-head transition-4  ">
        <div class="container-fluid d-flex  justify-content-center   nvvv">
            <div class="row align-items-center tab-navbar">
                <div class="col-lg-2 col-md-5 col-sm-4 col-3">
                    <div class="logo transition-4">
                        <a href="{{url('/')}}">
                            <img src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png')}}" class="transition-4" alt="logo">
                        </a>
                    </div>
                </div>
                <!-- <div class="col-md-1"> </div> -->
                <div class="col-lg-10 col-md-7 col-sm-8">
                    <!-- {{-- <div class="icon-links d-flex align-items-start">
                
                        <a href="{{url('/contact')}}" class="btn btn-round d-none d-sm-block blob-small">Contact Us</a>
                    </div> --}} -->
                    <div class="menu-links">
                        <nav class="main-menu white">
                            <ul>
                                <li>
                                    <a href="{{url('/')}}">Home</a>
                                    <ul class="">
                                       
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{url('/about')}}">About Us </a>
                                    <ul class="">
                                        
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{url('/product')}}">Product</a>
                                    
                                </li>
                                <li>
                                    <a href="{{url('/services')}}">Services</a>
                                    <ul class="">
                                      
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{url('/contact')}}" class="btn btn-round2 d-md-none d-sm-none d-lg-block navcont">Contact Us</a>

                                    <a href="{{url('/contact')}}" class="d-lg-none ">Contact us</a>
                                    
                                    <ul class="">
                                        
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mobile-menu"></div>
                </div>
            </div>
        </div>

    </header>
    <!-- The search Modal start -->
    <div class="search-popup modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="#">
                        <div class="form-group relative">
                            <input type="text" class="form-control input-search" id="search" placeholder="Search here...">
                            <i class="fas fa-search transform-v-center"></i>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <i class="fas fa-times close-search-modal" data-dismiss="modal" aria-label="Close"></i>
    </div>
    <!-- The search Modal end -->
    <!-- Header end -->
