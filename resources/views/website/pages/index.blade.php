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
                    <div class="fancy-head text-center relative z-5 m-3 pt-10 wow fadeInDown">
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
                <div class="col-lg-4 col-md-4 col-sm-6 mb-20 p-4">
                    <div class="team-2-each card relative border shadow-3">
                    <div class="product_img text-center">
                        <h4 class="f-800 pt-4 fs2 clrtext">TAILOR</h4>
                        <a ><img src="{{ asset('website/assets/img/products/Ellipse 7.png')}}" id="prodimgss" alt=""></a>
                    </div>
                        <div class="team-hover-div procard text-center transition-3">
                            {{-- <h4 class="white f-700"><a href="#">TAILOR</a></h4> --}}
                            <!-- <p class="green mb-0">Co Founder</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mb-20 p-4">
                    <div class="team-2-each card relative border shadow-3">
                        <div class="product_img text-center">
                            <h4 class="f-800 pt-4 fs2 clrtext">PLATFORM</h4>
                            <a><img src="{{ asset('website/assets/img/products/Ellipse 6.png')}}" id="prodimgss" alt=""></a>
                        </div>
                        <div class="team-hover-div procard text-center transition-4">
                            {{-- <h5 class="white f-700"><a href="#">PLATFORM</a></h5> --}}
                            <!-- <p class="green mb-0">Marketing Manager</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mb-20 p-4">
                    <div class="team-2-each card relative border shadow-3">
                    <div class="product_img text-center">
                        <h4 class="f-800 pt-4 fs2 clrtext">PLATS</h4>
                        <a><img src="{{ asset('website/assets/img/products/Ellipse 5.png')}}" id="prodimgss" alt=""></a>
                    </div>
                        <div class="team-hover-div procard text-center transition-4">
                            {{-- <h5 class="white f-700"><a href="#">PLATS</a></h5> --}}
                            <!-- <p class="green mb-0">Web Designer</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mb-20 p-4">
                    <div class="team-2-each card relative border shadow-3">
                    <div class="product_img text-center">
                        <h4 class="f-800 pt-4 fs2 clrtext">PLATFORM</h4>
                        <a><img src="{{ asset('website/assets/img/products/Ellipse 6.png')}}" id="prodimgss" alt=""></a>
                    </div>
                        <div class="team-hover-div procard text-center transition-4">
                            {{-- <h5 class=" f-700"><a href="#">TAILOR</a></h5> --}}
                            <!-- <p class="green mb-0">Finance Manager</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mb-20 p-4">
                    <div class="team-2-each card relative border shadow-3">
                    <div class="product_img text-center">
                        <h4 class="f-800 pt-4 fs2 clrtext">TAILOR</h4>
                        <a><img src="{{ asset('website/assets/img/products/Ellipse 7.png')}}" id="prodimgss" alt=""></a>
                    </div>
                        <div class="team-hover-div procard text-center transition-4">
                            {{-- <h5 class="white f-700"><a href="#">PLATFORM</a></h5> --}}
                            <!-- <p class="green mb-0">Web Designer</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mb-20 p-4">
                    <div class="team-2-each card relative border shadow-3">
                    <div class="product_img text-center">
                        <h4 class="f-800 pt-4 fs2 clrtext">PLATS</h4>
                        <a><img src="{{ asset('website/assets/img/products/Ellipse 5.png')}}" id="prodimgss" alt=""></a>
                    </div>
                        <div class="team-hover-div procard text-center transition-4">
                            {{-- <h5 class="white f-700"><a href="#">PLATS</a></h5> --}}
                            <!-- <p class="green mb-0">Web Designer</p> -->
                        </div>
                    </div>
                </div>
                                
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
                <div class="row align-items-center mb-10 mt-4">
                    
                    <div class="col-lg-4 col-xx-3 z-5 text-center text-lg-left wow fadeIn">
                        <div class="exp-cta pr-50 pr-lg-00 servicetext">
                            <h2 class="white text-center d-flex justify-content-center hptext1  f-700 mb-10">
                                <span class="fontsize30 f-900 fs-1   mr-20">01</span>
                                WOODWORKING
                                <span class="green"></span>
                            </h2>
                            <p class="white1 pfonts mb-55 mb-md-30 pr-70 pl-70 mt-20  f-500 bigfont hptext text-justify">Lorem, ipsum dolor sit amet consectetur adipisicing elit. stiae exercitationem debitis enim quaerat.</p>
                            <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                        </div>
                    </div>
                    <div class="col-lg-4 z-5 col-xxl-3 text-center text-lg-left wow fadeIn">
                        <div class="exp-cta pr-50 pr-lg-00 servicetext">
                            <h2 class="white f-700 text-center d-flex  justify-content-center hptext1  mb-10">
                                <span class="fontsize30 f-900  fs-1 mr-20">02</span>
                                METALWORKING
    
                            </h2>
                            <p class="white1 pfonts mb-55 mb-md-30 pr-70 pl-70 mt-20  f-500 bigfont hptext text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit. stiae exercitationem debitis enim quaerat.</p>
                            <!-- <a href="contact-us.html" class="btn btn-square">Contact us<i class="fas fa-long-arrow-alt-right ml-20"></i></a> -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-xxl-3  z-5 text-center text-lg-left wow fadeIn">
                        <div class="exp-cta pr-50 pr-lg-00 servicetext">
                            <h2 class="white text-center d-flex justify-content-center hptext1 f-700 mb-10">
                                <span class="fontsize30 f-900  fs-1 mr-20">03</span>
                                WOODWORKING
                                <span class="green"></span>
                            </h2>
                            <p class="white1 pfonts mb-55 mb-md-30 pr-70 pl-70 mt-20 hptext f-500 bigfont   text-justify">Lorem, ipsum dolor sit amet consectetur adipisicing elit. stiae exercitationem debitis enim quaerat.</p>
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
                                <p class="mb-35 pb-10 clrtext text-justify pr-10 lnh">"Shreerag Engineering's trolleys and rollers have transformed our warehouse operations. Their products' durability and efficiency exceeded our expectations, making them our top choice for material handling solutions."</p>
                                <div class="client-2-img d-flex  fixed-bottom1 justify-content-md-start justify-content-start">
                                    <div class="img-div   pb-20">
                                        <div class="client-image">
                                            <img src="{{ asset('website/assets/img/team/team5b.png')}}" class=" rounded-circle" alt="">
                                        </div>
                                    </div>
                                    <div class="client-text-2 mb-30 pl-20">
                                        <h6 class="client-name green fs-17 f-700 clrtext">Mr. Sachin Katkade </h6>
                                        <p class="mb-0 fs-13 f-400 clrtext mb-30">Technical Expert</p>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                                <h4 class="f-700 mb-20 pt-20 icn" id="pr"><i class="fa-solid fa-quote-left"></i></h4>
                                <p class="mb-35 pb-10 clrtext text-justify pr-10 lnh">"Their conveyors elevated our production efficiency. Their craftsmanship and design expertise make them the go-to partner for reliable material handling solutions."</p>
                                <div class="client-2-img d-flex  fixed-bottom1  justify-content-md-start justify-content-start">
                                    <div class="img-div  pb-20 ">
                                        <div class="client-image">
                                            <img src="{{ asset('website/assets/img/team/team3b.png')}}" class=" rounded-circle" alt="">
                                        </div>
                                    </div>
                                    <div class="client-text-2 mb-30  ">
                                        <h6 class="client-name green fs-17 f-700 clrtext">Dhananjay Makhwani</h6>
                                        <p class="mb-0 fs-13 f-400 clrtext mb-30">Co- worker</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" ">
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

                                <h4 class="f-700 mb-20 pt-20 icn" id="pr"><i class="fa-solid fa-quote-left"></i></h4>
                                <p class="mb-35 pb-10 clrtext text-justify pr-10 lnh">"Shreerag’s metallic pallets have been a valuable asset to our inventory management. The precision in their manufacturing process and attention to detail are evident in the quality of their products."</p>
                                <div class="client-2-img d-flex fixed-bottom1  justify-content-md-start justify-content-start">
                                    <div class="img-div  pb-20 ">
                                        <div class="client-image">
                                            <img src="{{ asset('website/assets/img/team/team1b.png')}}" class=" rounded-circle" alt="">
                                        </div>
                                    </div>
                                    <div class="client-text-2 mb-30 pl-5 ">
                                        <h6 class="client-name green fs-17 f-700 clrtext">Abhaykumar Sansare</h6>
                                        <p class="mb-0 fs-13 f-400 clrtext">Branch Manager</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" ">
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
                                <h4 class="f-700 mb-20 pt-20 icn" id="pr"><i class="fa-solid fa-quote-left"></i></h4>
                                <p class="mb-35 pb-10 clrtext text-justify pr-10 lnh">"Shreerag Engineering's custom trolleys have streamlined our operations. Their commitment to delivering innovative solutions tailored to our industry's needs sets them apart as a trusted partner."</p>
                                <div class="client-2-img d-flex fixed-bottom1  justify-content-md-start justify-content-start">
                                    <div class="img-div pb-20">
                                        <div class="client-image">
                                            <img src="{{ asset('website/assets/img/team/team3b.png')}}" class=" rounded-circle" alt="">
                                        </div>
                                    </div>
                                    <div class="client-text-2 mb-30 pl-20">
                                        <h6 class="client-name green fs-17 f-700 clrtext">Prakash Ingale</h6>
                                        <p class="mb-0 fs-13 f-400 clrtext">Production Manager</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" ">
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
                                <h4 class="f-700 mb-20 pt-20 icn" id="pr"><i class="fa-solid fa-quote-left"></i></h4>
                                <p class="mb-35 pb-10 clrtext text-justify pr-10 lnh">"The rollers are robust, durable, and remarkably efficient. Their professionalism make them our trusted supplier for all material handling equipment."
                                </p>
                                <div class="client-2-img d-flex fixed-bottom1 justify-content-md-start justify-content-start">
                                    <div class="img-div  pb-20">
                                        <div class="client-image">
                                            <img src="{{ asset('website/assets/img/team/team2b.png')}}" class=" rounded-circle" alt="">
                                        </div>
                                    </div>
                                    <div class="client-text-2  mb-30 pl-20">
                                        <h6 class="client-name green fs-17 f-700 clrtext">Vikas Sawant</h6>
                                        <p class="mb-0 fs-13 f-400 clrtext">Expert Advisor</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" ">
                            <div class="each-quote-2 pl-20 pr-sm-00 card cardwidth" style="height: 350px; border-top:15px solid #243772; background:#e2feff;">
                                <!-- <ul class="stars-rate mb-5" data-starsactive="5">
                                    <li class="text-md-left text-center">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </li>
                                </ul> -->
                                
                                <h4 class="f-700 mb-20 pt-20 icn" id="pr"><i class="fa-solid fa-quote-left"></i></h4>
                                <p class="mb-35 pb-10 clrtext text-justify pr-10 lnh">"Shreerag Engineering's conveyors have significantly improved our production line. Their commitment to delivering comprehensive solutions tailored to our business needs makes them a reliable partner."
                                </p>
                                <div class="client-2-img d-flex fixed-bottom1 justify-content-md-start justify-content-start">
                                    <div class="img-div  pb-20">
                                        <div class="client-image">
                                            <img src="{{ asset('website/assets/img/team/team2b.png')}}" class=" rounded-circle" alt="">
                                        </div>
                                    </div>
                                    <div class="client-text-2  mb-30 pl-20">
                                        <h6 class="client-name green fs-17 f-700 clrtext">Shubham Pawar</h6>
                                        <p class="mb-0 fs-13 f-400 clrtext">Safety Manager</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
