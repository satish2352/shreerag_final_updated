@extends('website.layouts.master')
@section('content')
<section>
      <!-- bannar start -->
      <div class="banrimgs">
        <img src="{{ asset('website/assets/img/banner/home_bnr.png')}}" alt="">
    </div>
    <div class="mobibanrimgs">
    <img src="{{ asset('website/assets/img/banner/homepage.png')}}" alt="">
    </div>
    <!-- bannar end -->

     <!-- Slider start -->
     {{-- <section class="slider-area-2 relative"> --}}

        {{-- <div class="owl-carousel slider-2">
            <div class="item">
                <div class="silder-img" id="sliderbk1" style="background-image: url('{{ asset('website/assets/img/banner/HOME_PAGE.png')}}');" data-overlay="7">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-8">
                                <div class="slider-content z-10">
                                    <h5 class="line-head">
                              25 years of experience
                            <span class="line  after"></span>
                          </h5>
                                    <h1 class="banner-head-2 f-700 mt-25 mb-35 mt-xs-20 mb-xs-30">Effortless Efficiency: Transform your material handling experience</h1>
                                    <a href="#" class="btn btn-square">Learn More<i class="fas fa-long-arrow-alt-right ml-20"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="silder-img" style="background-image: url('{{ asset('website/assets/img/banner/banner_2a.jpg')}}');" data-overlay="7">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-8">
                                <div class="slider-content z-10">
                                    <h5 class="line-head">
                                        1000+ Happy Clients
                                        <span class="line  after"></span>
                                    </h5>
                                    <h1 class="banner-head-2 f-700 mt-25 mb-35 mt-xs-20 mb-xs-30"> Discover durability like never before with our robust solutions</h1>
                                    <a href="#" class="btn btn-square">Learn More<i class="fas fa-long-arrow-alt-right ml-20"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="silder-img" id="sliderbk1" style="background-image: url('{{ asset('website/assets/img/banner/HOME_PAGE.png')}}');" data-overlay="7">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-8">
                                <div class="slider-content z-10">
                                    <h5 class="line-head">
                                        25 years of experience
                                    <span class="line  after"></span>
                                </h5>
                                    <h1 class="banner-head-2 f-700 mt-25 mb-35 mt-xs-20 mb-xs-30">Elevate your business operations with our innovative designs</h1>
                                    <a href="#" class="btn btn-square">Learn More<i class="fas fa-long-arrow-alt-right ml-20"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- <div class="slide-social-outer transform-v-center z-5">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 w-100">
                        <div class="slide-social d-none d-lg-block">
                            <ul class="social-icons">
                                <li>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="fab fa-google-plus-g"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        {{-- <div class="slider-control z-5">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-6">
                        <div class="dots-slider">

                        </div>
                    </div>
                    <div class="col-lg-6 text-right d-none d-lg-block">
                        <div class="nav-slider d-flex justify-content-end">
                            <a href="#" class="slider-btn slides-left flex-center">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" class="slider-btn slides-right flex-center">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Slider end -->

     <!-- title start -->
     <section class="callback-area" data-overlay="9">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="fancy-head text-center relative z-5 m-3 wow fadeInDown">
                        <h1 class="white fs1">Product</h1>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <!-- title end -->

       <!-- products  start -->
<div class="">
     <section class="team-area bg-blue-op-11 pt-40 pb-40">
        <div class="container">
            <div class="row">
                
                @if (empty($data_output_product_limit))
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <h3 class="d-flex justify-content-center" style="color: #00000">No Data Found For
                            Product</h3>
                    </div>
                </div>
            @else
                @foreach ($data_output_product_limit as $product)
                <div class="col-lg-4 col-md-4 col-sm-6 mb-20 p-4">
                    <div class="team-2-each card relative border shadow-3">
                    <div class="product_img text-center">
                        <h4 class="f-800 pt-4 fs2 clrtext">{{ $product['title'] }}</h4>
                        <a ><img src="{{ Config::get('DocumentConstant.PRODUCT_VIEW') }}{{ $product['image'] }}" id="prodimgss" alt=""></a>
                    </div>
                        <div class="team-hover-div procard text-center transition-3">
                            {{-- <h4 class="white f-700"><a href="#">TAILOR</a></h4> --}}
                            <!-- <p class="green mb-0">Co Founder</p> -->
                        </div>
                    </div>
                </div>
                @endforeach
                @endif                                
            </div>
            <div class="row text-center pt-10">
                    <div class="col-lg-9 col-md-6"></div>
                    <div class="col-lg-3 col-md-6">

                        <a href="{{url('/product')}}" class="btn btn-round">View all products</a>
                    </div>
                </div>
        </div>
    </section>
</div>
   
    <!-- Products end -->


        <!-- services start -->
        <section class="servicebk" data-overlay="9" >
            <div class="container-fluid testmo ">
                <div class="row ">
                    <div class="col-xl-12">
                        <div class="fancy-head text-center relative z-5 mb-30  wow fadeInDown">
                            <h1 class="white f-800 fs1 mt-60 ">Service</h1>
                        </div>
                    </div>
                </div>
                <div class="row mb-10 mt-4">
                    
                    <div class="col-lg-4 col-xx-3 z-5 text-center text-lg-left wow fadeIn">
                        <div class="exp-cta pr-50 pr-lg-00 servicetext">
                            <h2 class="white text-center d-flex justify-content-center hptext1  f-700 mb-10">
                                <span class="fontsize30 f-900 fs-1   mr-20">01</span>
                               <span> Research and development.</span>
                                <span class="green"></span>
                            </h2>
                            <p class="white1 pfonts mb-55 mb-md-30 pr-70 pl-70 mt-20  f-500 bigfont hptext text-justify">Introducing and developing new concepts with the latest technology and bringing quality products to the customers. From concept to creation, we guide your project at every stage of the product development journey. Our platform connects you with experienced professionals and leverages cutting-edge technology to help you launch innovative, market-ready products efficiently and cost-effectively.</p>
                            <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                        </div>
                    </div>
                    <div class="col-lg-4 z-5 col-xxl-3 text-center text-lg-left wow fadeIn">
                        <div class="exp-cta pr-50 pr-lg-00 servicetext">
                            <h2 class="white f-700 text-center d-flex  justify-content-center hptext1  mb-10">
                                <span class="fontsize30 f-900  fs-1 mr-20">02</span>
                                Product designing, Prototyping & Testing.
    
                            </h2>
                            <p class="white1 pfonts mb-55 mb-md-30 pr-70 pl-70 mt-20  f-500 bigfont hptext text-justify">We are specialists in the development of technically-challenging useful and innovative physical real-world products. Our design and engineering team finds creative solutions to technical challenges, allowing your product to meet marketing, regulatory and user requirements with reasonable manufacturing cost and quality expectations by eliminating risk, executing design, and accelerating your path-to-volume production with prototyping. The technique we use depends on the quality, time and functional requirements for the part.</p>
                            <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-xxl-3  z-5 text-center text-lg-left wow fadeIn">
                        <div class="exp-cta pr-50 pr-lg-00 servicetext">
                            <h2 class="white text-center d-flex justify-content-center hptext1 f-700 mb-10">
                                <span class="fontsize30 f-900  fs-1 mr-20">03</span>
                                Engineering & Manufacturing with timely delivery.
                                <span class="green"></span>
                            </h2>
                            <p class="white1 pfonts mb-55 mb-md-30 pr-70 pl-70 mt-20 hptext f-500 bigfont   text-justify">Before moving into high volume production, it is important to make sure everything is set.
                                Alpha Production Intent Prototypes mimic your volume manufacturing process and allow engineers, regulators, and users to carefully assess the quality and function of your product
                                We provide you with Alpha prototypes so you can move into high-volume production with confidence. Then the high volume production with timely delivery is given</p>
                            <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                        </div>
                    </div>
                    </div>
                    <div class="row ">
                    <div class="col-lg-11 z-5 text-center text-lg-left wow fadeIn">
                        <div class="servbtn mb-90 mb-md-20 d-flex justify-content-end ">
                            <a href="{{url('/services')}}" class="btn btn-round  justify-content-end">View all services</a>
    
                        </div>
                    </div>
                   
                    </div>
           
            </div>
    </section>
    <!-- services end -->

      <!-- How we work  -->
      {{-- <section class="pt-50" data-overlay="9">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="fancy-head text-center relative z-5 mb-20 wow fadeInDown">
                            <h1>How we work</h1>
                        </div>
                    </div>
                </div>        
            </div>
        </section> --}}
      <section>
        <div class="container-fluid testmo paddiall4 bg-insta mb-3 ">
            <div class="row pb-50 pb-md-20 pb-sm-20">
                <div class="col-xl-4 pl-60 pr-60 mt-30">
                    <div class="item">
                        <h1 class="f-800 clrtext position-absolute"><span class="btn btn-round1 pd00"><h1 class="white fs1 f-800">01 </h1></span> Request</h1>
                        <img src="{{ asset('website/assets/img/icons/1.png')}}" alt="">
                        <h6 class="clrtext text-justify align-content-center  fs-18 mrtp pt-3">Lorem ipsum dolor sit Provident nam illum, maxime ipsum nostrum amet, consectetur adipisicing  aut unde officiis eveniet</h6>
                    </div>
                </div>
                
                <div class="col-xl-4 pl-60 pr-60 mt-30">
                    <div class="item">
                    <h1 class="f-800 clrtext position-absolute"><span class="btn btn-round1 pd00"><h1 class="white fs1 f-800">02 </h1></span> Develop</h1>
                        <img src="{{ asset('website/assets/img/icons/2.png')}}"  alt="">
                        <h6 class="clrtext text-justify fs-18 mrtp pt-3">Lorem ipsum dolor sit Provident nam illum, maxime ipsum nostrum amet, consectetur adipisicing  aut unde officiis eveniet</h6>
                    </div>
                </div>
                <div class="col-xl-4 pl-60 pr-60 mt-30">
                    <div class="item">
                    <h1 class="f-800 clrtext position-absolute"><span class="btn btn-round1 pd00"><h1 class="white fs1 f-800">03 </h1></span> Install</h1>
                        <img src="{{ asset('website/assets/img/icons/3.png')}}"  alt="">
                        <h6 class="clrtext text-justify fs-18 mrtp pt-3">Lorem ipsum dolor sit Provident nam illum, maxime ipsum nostrum amet, consectetur adipisicing  aut unde officiis eveniet</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end  How we work-->

     <!-- Testimonial area start -->
     <section class="testimonials-2 reviw">
        <div class="container-fluid paddiall  paddiall1 testmo1">
            <div class="row align-items-center mb-30">
                <div class="col-lg-12 col-md-12 text-center text-lg-center">
                    <div class="fancy-head left-al wow fadeInLeft">
                        {{-- <h5 class="line-head mb-15">
                        <span class="line before d-lg-none"></span>
                            Testimonials
                        <span class="line after"></span>
                        </h5> --}}
                        <h1 class="clrtext fs1">Testimonial</h1>
                    </div>
                </div>
                {{-- <div class="col-lg-5 text-center text-lg-right">
                    <div class="arrow-navigation mb-15 mt-md-20 wow fadeInRight">
                        <a href="#" class="nav-slide slide-left testi-2">
                            <img src="{{ asset('website/assets/img/icons/ar_lt.png')}}" alt="">
                        </a>
                        <a href="#" class="nav-slide slide-right testi-2">
                            <img src="{{ asset('website/assets/img/icons/ar_rt.png')}}" alt="">
                        </a>
                    </div>
                </div> --}}
                <!-- <div class="col-12">
                    <div class="hr-2 bg-blue opacity-1 mt-45"></div> 
                </div> -->
            </div>
            <div class="row marginleft75">
                <div class="col-xl-12">
                    <div class="owl-carousel owl-theme testimonial-2-slide  wow fadeIn">

                        
                        @if (empty($data_output_testimonial))
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <h3 class="d-flex justify-content-center" style="color: #00000">No Data Found For
                            Testimonial</h3>
                    </div>
                </div>
            @else
                @foreach ($data_output_testimonial as $testimonial)
                        <div class="">
                            <div class="each-quote-2 pl-20 pr-sm-00 card cardwidth" style="height: 350px; border-top:15px solid #243772; background:#e2feff">
                                <!-- <ul class="stars-rate mb-5" data-starsactive="5">
                                    <li class="text-md-left text-center">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </li>
                                </ul> -->
                                <h4 class="f-700 mb-20 pt-20  icn"  id="pr"><i class="fa-solid fa-quote-left  "></i></h4>
                                <p class="mb-35 pb-10 clrtext text-justify pr-10 lnh">{{ $testimonial['description'] }}</p>
                                <div class="client-2-img d-flex  fixed-bottom1 justify-content-md-start justify-content-start">
                                    <div class="img-div   pb-20">
                                        <div class="client-image">
                                            <img src="{{ Config::get('DocumentConstant.TESTIMONIAL_VIEW') }}{{ $testimonial['image'] }}" class=" rounded-circle" alt="">
                                        </div>
                                    </div>
                                    <div class="client-text-2 mb-30 pl-20">
                                        <h6 class="client-name green fs-17 f-700 clrtext">{{ $testimonial['title'] }} </h6>
                                        <p class="mb-0 fs-13 f-400 clrtext mb-30">{{ $testimonial['position'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial area end -->
    
    <!-- contact -->
    <section>
        <div class="container-fluid testmo text-center pt-50 pb-50 indexlastimg">
           
             <!-- bannar start -->
            <div class="banrimgs">
             <div class="   ">  <img src="{{ asset('website/assets/img/banner/contactnew.png')}}" alt="">
              </div>             
             </div> 
            <div class="mobibanrimgs">
            <div class=" ">  <img src="{{ asset('website/assets/img/banner/contact.png')}}" alt="">
              </div>
            </div> 
        <!-- bannar end -->

        </div>
    </section>

<div class="paddiall2"></div>

</section>
@endsection
