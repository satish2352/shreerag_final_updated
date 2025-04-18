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

                @if (empty($data_output_product))
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <h3 class="d-flex justify-content-center" style="color: #00000">No Data Found For
                            Product</h3>
                    </div>
                </div>
            @else
                @foreach ($data_output_product as $product)
                <div class="col-xl-4 col-lg-6 col-md-6 p-4">
                    <div class="team-each team1 prosha  ">
                        <div class="team-image  relative">
                            
                            <img src="{{ Config::get('DocumentConstant.PRODUCT_VIEW') }}{{ $product['image'] }}" alt="">            
                        </div>
                        <div class="team-info transition-4">
                            <h3 class="ml-30" style="font-size:20px;">
                            <a 
                           
                            data-id="{{ $product['id'] }}"

                            {{-- href="{{url('/product_details')}}" --}}
                             class="show-btn f-700">{{ $product['title'] }}</a>
                            </h3>
                            <p class="mb-0 ml-30"><a 
                                data-id="{{ $product['id'] }}"
                                {{-- href="{{url('/product_details')}}" --}}
                                 class="show-btn btn btn-round-blue wide mt-10 z-8">Product</a></p>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
              

            </div>
            
        </div>
    {{-- </section> --}}
    
    <!-- Product area end -->
    <form method="POST" action="{{ url('/product-details') }}" id="showform">
        @csrf
        <input type="hidden" name="show_id" id="show_id" value="">
    </form>
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
<script src="{{ asset('js/vendor/jquery-1.11.3.min.js') }}"></script>
<script>
    $('.show-btn').click(function(e) {
        $("#show_id").val($(this).attr("data-id"));
        $("#showform").submit();
    })
</script>
@endsection
