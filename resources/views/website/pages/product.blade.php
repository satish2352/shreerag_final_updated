@extends('website.layouts.master')
@section('content')
<section>

 <!-- bannar start -->
 <div class="banrimgs">
    <img src="{{ asset('website/assets/img/banner/PRODUCT.png')}}" alt="">
</div>
<div class="mobibanrimgs">
  <img src="{{ asset('website/assets/img/banner/mobprod.png')}}" alt="">
</div>
<!-- bannar end -->

 

    <!-- Product area start -->
    {{-- <section class="cardbkclr">    --}}
        {{-- <section class="pt-50" data-overlay="9">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="fancy-head text-center relative z-5 mb-40 wow fadeInDown">
                            <h1>Products</h1>
                        </div>
                    </div>
                </div>        
            </div>
        </section> --}}
    
         <!-- Product area start -->
    
        <div class="container-fluid testmo1 team-area pt-50 pb-5">
            <div class="row ">

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha  ">
                        <div class="team-image  relative">
                            <img src="{{ asset('website/assets/img/products/prod1.png')}}" alt="">            
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                            <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                            </h3>
                            <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha  ">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod2.png')}}" alt="">
                           
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                            </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha  ">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod3.png')}}" alt="">
                           
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                            </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha ">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod4.png')}}" alt="">
                            
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha ">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod5.png')}}" alt="">
                           
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha  ">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod6.png')}}" alt="">
                           
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                            </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha ">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod1.png')}}" alt="">
                           
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                            </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha ">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod4.png')}}" alt="">
                            
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                             </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha mb-sm-10">
                        <div class="team-image relative">
                            <img src="{{ asset('website/assets/img/products/prod2.png')}}" alt="">
                           
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30">
                                <a href="{{url('/product_details')}}" class="f-700">Parts Trolley</a>
                                </h3>
                                <p class="mb-0 ml-30"><a href="{{url('/product_details')}}" class="btn btn-round-blue wide mt-10 z-8">Product</a></p>
                              </div>
                    </div>
                </div>

            </div>
            
        </div>
    {{-- </section> --}}
    
    <!-- Product area end -->

    <section>
        <div class="pt-35 pb-50 hidd">
                  <!-- bannar start -->
                  <div class="banrimgs">
                    <img src="{{ asset('website/assets/img/banner/HOME_PAGE31.png')}}" alt="">
                </div>
                <div class="mobibanrimgs">
                <img src="{{ asset('website/assets/img/banner/mobconnect.jpg')}}" alt="">
                </div>
            <!-- bannar end -->
    
        </div>
    </section>
  
</section>
@endsection
