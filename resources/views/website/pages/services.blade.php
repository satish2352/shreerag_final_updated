@extends('website.layouts.master')
@section('content')
<section>

    <!-- bannar start -->
    <div class="banrimgs">
        <img src="{{ asset('website/assets/img/banner/SERVICES.png')}}" alt="">
    </div>
    <div class="mobibanrimgs">
        <img src="{{ asset('website/assets/img/banner/mobservice.png')}}" alt="">
    </div>
    <!-- bannar end -->


    <!-- imgs -->
    <section class="paddiall2 mb-50">
        <div class="" data-overlay="9">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="fancy-head text-center relative z-5 mt-30 mb-40 wow fadeInDown">
                            <h1 class="clrtext fs5">Manufacturing <span class="green fs6">Of</span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bakimgbnr">
            <div class="container-fluid serv pt-5 pb-30">
                <div class="row">
                    @if (empty($data_output_services))
                    <div class="container">
                        <div class="row d-flex justify-content-center">
                            <h3 class="d-flex justify-content-center" style="color: #00000">No Data Found For
                                Services</h3>
                        </div>
                    </div>
                @else
                    @foreach ($data_output_services as $services)
                    <div class="col-lg-3 manuimg1 text-center">
                        <img src="{{ Config::get('DocumentConstant.SERVICES_VIEW') }}{{ $services['image'] }}" id="sevimg" alt="">
                        <div class="p-3">
                            <h2 class="f-700 clrtext">{{ $services['title'] }}</h2>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    {{-- <div class="col-lg-3 manuimg2 text-center">
                        <img src="{{ asset('website/assets/img/service/roller.png')}}" id="sevimg" alt="">
                        <div class="p-3">
                            <h2 class="f-700 clrtext">Rollers</h2>
                        </div>
                    </div>
                    <div class="col-lg-3 manuimg3 text-center">
                        <img src="{{ asset('website/assets/img/service/conveyer.png')}}" id="sevimg" alt="">
                        <div class="p-3">
                            <h2 class="f-700 clrtext">Conveyers</h2>
                        </div>
                    </div>
                    <div class="col-lg-3 manuimg4 text-center">
                        <img src="{{ asset('website/assets/img/service/metalic_pallet.png')}}" id="sevimg" alt="">
                        <div class="p-3">
                            <h2 class="f-700 clrtext">Metalic Pallet</h2>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- end imgs -->
    <!-- services start -->
    <section class="servicebnnr paddiall">
        <div class="container-fluid testmo">
            {{-- <div class="row">
                <div class="col-xl-12">
                    <div class="fancy-head text-center relative z-5 mb-40 wow fadeInDown">
                        <h1 class="white">Our Services</h1>
                    </div>
                </div>
            </div> --}}
            <div class="row  paddiall4">
                <div class="col-lg-4 z-5 text-center text-lg-left wow fadeIn">
                    <div class="exp-cta pr-50 pr-lg-00 servicetext">
                        <h2 class="white text-left d-flex justify-content-left f-700 " style="font-size:20px;">
                            <span class=" fs-1 mr-20">01</span>
                            Research and development.
                            <span class="green"></span>
                        </h2>
                        <p
                            class="white1 pfonts mb-55 mb-md-30  mt-30  f-500 pr-md-00  bigfont text-justify">
                            Introducing and developing new concepts with the latest technology and bringing quality products to the customers. From concept to creation, we guide your project at every stage of the product development journey. Our platform connects you with experienced professionals and leverages cutting-edge technology to help you launch innovative, market-ready products efficiently and cost-effectively.</p>
                        <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                    </div>
                </div>
                <div class="col-lg-4 z-5 text-center text-lg-left wow fadeIn ">
                    <div class="exp-cta pr-50 pr-lg-00 servicetext">
                        <h2 class="white text-left d-flex justify-content-left f-700" style="font-size:20px;">
                            <span class=" fs-1 mr-20">02</span>
                            Product designing, Prototyping & Testing.

                        </h2>
                        <p
                            class="white1 pfonts mb-55 mb-md-30 mt-30 xxl-pr f-500 pr-md-00 bigfont text-justify">
                            We are specialists in the development of technically-challenging useful and innovative physical real-world products. Our design and engineering team finds creative solutions to technical challenges, allowing your product to meet marketing, regulatory and user requirements with reasonable manufacturing cost and quality expectations by eliminating risk, executing design, and accelerating your path-to-volume production with prototyping. The technique we use depends on the quality, time and functional requirements for the part.</p>
                        <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                    </div>
                </div>
                <div class="col-lg-4 z-5 text-center text-lg-left wow fadeIn">
                    <div class="exp-cta pr-50 pr-lg-00 servicetext ">
                        <h2 class="white text-left d-flex justify-content-left f-700" style="font-size:20px;">
                            <span class=" fs-1 mr-20">03</span>
                            Engineering & Manufacturing with timely delivery.
                            <span class="green"></span>
                        </h2>
                        <p
                            class="white1 pfonts mb-55 mb-md-15 mt-30 f-500 pr-md-00 bigfont text-justify">
                            Before moving into high volume production, it is important to make sure everything is set.
Alpha Production Intent Prototypes mimic your volume manufacturing process and allow engineers, regulators, and users to carefully assess the quality and function of your product
We provide you with Alpha prototypes so you can move into high-volume production with confidence. Then the high volume production with timely delivery is given</p>
                        <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                    </div>
                </div>


            </div>


        </div>
    </section>
    <!-- services end -->


    <!-- material handling  -->

    <section class="paddiall2">
        {{-- <div class="bkmtrl"> --}}

        <div data-overlay="9">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="fancy-head text-center relative z-5 wow fadeInDown ">
                            <h1 class="fs6 mthtext">Material Handling <span class="green">Equipment For</span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container-fluid text-center col-12 banrimgs imgnag  ">

                <img src="{{ asset('website/assets/img/banner/material.png')}}" alt="">

            </div>

            <div class="container-fluid text-center mobibanrimgs mhmi ">


                <img src="{{ asset('website/assets/img/banner/Materialnew2.jpg')}}" alt="">
            </div>

            {{-- <div class="container  materl maetrialback">
                <div class="row d-flex justify-content-center m-5">
                        <div class="col-md-8 col-lg-10 card shadows pdmat">
                                            <div class="row shadow-lg ">
                                                <div class="col-md-6 oneim p-3 d-flex justify-content-center">
                                                    
                                                    <div class="team-each ">
                                                    <div class="team-image">
                                                        <img src="{{ asset('website/assets/img/service/AUTOMOBILE.png')}}"
            alt="">

        </div>
        <div class=" text-center mt-10">
            <h3 class="clrtext f-700">
                AUTOMOBILE
            </h3>

        </div>
        </div>
        </div>
        <div class="col-md-6 fourim p-3 d-flex justify-content-center">

            <div class="team-each mb-20">
                <div class="team-image">
                    <img src="{{ asset('website/assets/img/service/HOSPITAL.png')}}" alt="">

                </div>
                <div class=" text-center mt-10">
                    <h3 class="clrtext f-700">
                        HOSPITAL
                    </h3>

                </div>
            </div>
        </div>
        <div class="col-md-6 threeim p-3 d-flex justify-content-center">

            <div class="team-each mb-20 mt-10">
                <div class="team-image">
                    <img src="{{ asset('website/assets/img/service/TRAVELING.png')}}" alt="">

                </div>
                <div class=" text-center mt-10">
                    <h3 class="clrtext f-700">
                        TRAVELING
                    </h3>

                </div>
            </div>
        </div>
        <div class="col-md-6 twoim p-3 d-flex justify-content-center">

            <div class="team-each mt-10">
                <div class="team-image">
                    <img src="{{ asset('website/assets/img/service/PHARMA.png')}}" alt="">

                </div>
                <div class=" text-center">
                    <h3 class="clrtext f-700">
                        PHARMA <br>
                        INDUSTRIES
                    </h3>

                </div>
            </div>
        </div>

        </div>
        </div>
        </div>
        </div> --}}

        </div>
        {{-- </div> --}}
    </section>
    <!-- end material handling -->

    <!-- img -->
    <section>
        <div class="">
            <!-- bannar start -->
            <div class="banrimgs">
                <img src="{{ asset('website/assets/img/banner/HOME_PAGE3old.png')}}" alt="">
            </div>
            <div class="mobibanrimgs">
                <img src="{{ asset('website/assets/img/banner/mobconnectold.jpg')}}" alt="">
            </div>
            <!-- bannar end -->
        </div>
    </section>
    <!-- img -->

    <!-- cards -->


    <section class="cardbkclr paddiall2 ">
        <div class="container-fluid testmo1 paddiall">
            <div class="row">
                <div class="col-lg-12"></div>
                @if (empty($data_output_services))
                    <div class="container">
                        <div class="row d-flex justify-content-center">
                            <h3 class="d-flex justify-content-center" style="color: #00000">No Data Found For
                                Services</h3>
                        </div>
                    </div>
                @else
                    @foreach ($data_output_services as $services)

                <div class="col-lg-6 col-md-12 p-2 exp">
                    <div class="card border-0 bkcl">
                        <img src="{{ Config::get('DocumentConstant.SERVICES_VIEW') }}{{ $services['image'] }}" class="card-img-top" alt="...">
                        <div class="card-body  text-center card-info">
                            <h1 class="white fs2 f-600">{{ $services['title'] }}</h1>

                        </div>
                    </div>
                </div>
                @endforeach
                @endif
                {{-- <div class="col-lg-6 col-md-12 p-2 exp ">
                    <div class="card border-0 bkcl">
                        <img src="{{ asset('website/assets/img/service/movement.png')}}" class="card-img-top" alt="...">
                        <div class="card-body text-center  card-info">
                            <h1 class="white fs2 f-600 line-ht pptt"> Movement Trollies </h1>

                        </div>

                    </div>
                </div> --}}
            </div>

            {{-- <div class="row">


                <div class="col-lg-6 col-md-12 p-2 exp ">
                    <div class="card border-0 bkcl">
                        <img src="{{ asset('website/assets/img/service/fabrication.png')}}" class="card-img-top"
                            alt="...">
                        <div class="card-body text-center card-info1">
                            <h1 class="white fs2 f-600 line-ht ptb "> Challenging Fabrication Works </h1>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 p-2 exp ">
                    <div class="card border-0 bkcl ">
                        <img src="{{ asset('website/assets/img/service/presshop.png')}}" class="card-img-top" alt="...">
                        <div class="card-body text-center  card-info1">
                            <h1 class="white fs2 f-600 line-ht  topbottom   "> Press Shop Works </h1>

                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
    <!-- cards -->
</section>
<div class="paddiall2"></div>
@endsection