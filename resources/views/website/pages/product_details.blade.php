@extends('website.layouts.master')
@section('content')
<section>
    <!-- immer banner start -->
    <section class="inner-banner pt-80 pb-95" style="background-image: url('{{ asset('website/assets/img/banner/inner-banner.jpg')}}');" data-overlay="7">
        <div class="container">
            <div class="row z-5 align-items-center">
                <div class="col-md-8 text-center text-md-left">
                    <h1 class="f-700 white">Product Details</h1>
                    {{-- <span class="green-line bg-green d-none d-md-inline-block"></span> --}}
                </div>
                {{-- <div class="col-md-4 text-center text-md-right">
                    <nav aria-label="breadcrumb" class="banner-breadcump">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home fs-12 mr-5"></i>Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Detail</li>
                        </ol>
                    </nav>
                </div> --}}
            </div>
        </div>
    </section>

    <section class="team-detail pt-40 pb-20">
        <div class="container">
        @forelse ($data_output_product_detail as $product)
            <div class="row">
                <div class="col-lg-4">
                    <div class="team-detail-image">
                        <div class="team-member-image img-lined">
                            <img src="{{ Config::get('DocumentConstant.PRODUCT_VIEW') }}{{ $product['image'] }}" alt="">
                            {{-- <img src="{{ asset('website/assets/img/products/prod2.png')}}" class="pt-10 pb-30" alt=""> --}}
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-8 mb-70 ">
                    <h2 class="f-700 mb-20">{{ $product['title'] }}</h2>
                    <h5>{{ $product['description'] }}</h5>
                    {{-- <ul class="check-list-2">
                        <li><h6>Pellentesque varius turpis ligula, in cursus leo dictum quis. Nam volutpat. </h6></li>
                        <li><h6>Gravida odio ex in nulla. Donec pulvinar odio a sapien vehicula, in accumsan libero</h6></li>
                        <li><h6>Donec faucibus sapien sed bibendum dignissim. Nam ac elit nec diam vestibulum.</h6></li>
                        <li><h6>Donec sed pretium massa pulvinar efficitur</h6></li>
                    </ul> --}}
                    {{-- <div class="hr-2 bg-light-white mt-20 "></div>
                    <h5 class="f-700">Purchase Price</h5>
                    <ul class="">
                        <li>
                            <h5>Lower</h5> Office Manager, Rehabilitation Institute
                        </li>
                        <li>
                            <h5>Medium</h5> Marketing Manager, JWM Private Texas
                        </li>
                        <li>
                            <h5>Higher</h5> Financial Manager, Bizz, California
                        </li>
                    </ul> --}}
                    <!-- <a href="#" class="btn btn-round mt-5">Order Now</a> -->
                </div>
            </div>

            @empty
            <p>
                @if (session('language') == 'mar')
                    {{ Config::get('marathi.PARTICULAR_DEPARTMENT_INFORMATION.NO_DEPARTMENT_INFORMATION') }}
                @else
                    {{ Config::get('english.PARTICULAR_DEPARTMENT_INFORMATION.NO_DEPARTMENT_INFORMATION') }}
                @endif
            </p>
        @endforelse
        </div>
    </section>

</section>
@endsection