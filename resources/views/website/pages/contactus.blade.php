@extends('website.layouts.master')
@section('content')
<section>
    <!-- bannar start -->
    <div class="banrimgs">
        <img src="{{ asset('website/assets/img/banner/contact_us.png')}}" alt="">
    </div>
    <div class="mobibanrimgs">
        <img src="{{ asset('website/assets/img/banner/mobcontact.png')}}" alt="">
    </div>
    <!-- bannar end -->

    <!--  -->
    <section>
        <div class="contbak">
      
            
            <div class="container mb-10 pt-50 pb-10">
                {{-- <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-12 p-2">
                        <div class="card text-center">
                            <div class="text-center pt-4">

                                <img src="{{ asset('website/assets/img/contact/location.png')}}" style="width: 150px;"
                class="card-img-top" alt="...">
            </div>
            <div class="card-body text-center">
                <h3 class="card-title f-700">Plant No 1</h3>
                <h6 class="card-title">W-127 (A), <br>
                    MIDC, Ambad Nashik- 422010 </h6>
                <p class="card-text card-title"> </p>
            </div>
            <ul class="text-center">
                <li class="f-600">7028082176
                </li>
                <li class="f-600"> 0253 - 2383517</li>
            </ul>
            <div class="p-3 text-center card-info">

                <h4 class="white f-600"> <a href="https://maps.app.goo.gl/ThctFSNi3kxhsAV87" target="blank"
                        class="card-link">Get Direction</a> </h4>

            </div>
        </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 p-2">
            <div class="card text-center">
                <div class="text-center pt-4">

                    <img src="{{ asset('website/assets/img/contact/location.png')}}" style="width: 150px;"
                        class="card-img-top" alt="...">
                </div>
                <div class="card-body text-center">
                    <h3 class="card-title f-700">Plant No 2</h3>
                    <h6 class="card-title"> W-118 (A), <br> MIDC Ambad Nashik
                        422010</h6>
                    <p class="card-text card-title"> </p>
                </div>
                <ul class="text-center">
                    <li class="f-600">7028082176
                    </li>
                    <li class="f-600"> 0253 - 2383517</li>
                </ul>
                <div class="p-3 text-center card-info">

                    <h4 class="white f-600"> <a href="https://maps.app.goo.gl/HkB2JkJixZCUWmQK6" target="blank"
                            class="card-link">Get Direction</a> </h4>

                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 p-2">
            <div class="card text-center">
                <div class="text-center pt-4">

                    <img src="{{ asset('website/assets/img/contact/location.png')}}" style="width: 150px;"
                        class="card-img-top" alt="...">
                </div>
                <div class="card-body text-center">
                    <h3 class="card-title f-700">Plant No 3</h3>
                    <h6 class="card-title"> GAT NO-679/2/1 , Kurli Alandi Road ,Chankan , Tal khed Dist. Pun - 410501
                    </h6>
                    <p class="card-text card-title"> </p>
                </div>
                <ul class="text-center">
                    <li class="f-600">7028082176
                    </li>
                    <li class="f-600"> 0253 - 2383517</li>
                </ul>
                <div class="p-3 text-center card-info">

                    <h4 class="white f-600"> <a href="" target="blank" class="card-link">Get Direction</a> </h4>

                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 p-2">
            <div class="card text-center">
                <div class="text-center pt-4">

                    <img src="{{ asset('website/assets/img/contact/location.png')}}" style="width: 150px;"
                        class="card-img-top" alt="...">
                </div>
                <div class="card-body text-center">
                    <h3 class="card-title f-700">Plant No 4</h3>
                    <h6 class="card-title"> GF PLOT NO-913 Shreeji Engg.,GIDC,Halol , Panchamahal Gujarat - 389350</h6>
                    <p class="card-text card-title"> </p>
                </div>
                <ul class="text-center">
                    <li class="f-600">7028082176
                    </li>
                    <li class="f-600"> 0253 - 2383517</li>
                </ul>
                <div class="p-3 text-center card-info">

                    <h4 class="white f-600"> <a href="https://maps.app.goo.gl/8vvmafbwcG6vSJ5A9" target="blank"
                            class="card-link">Get Direction</a> </h4>

                </div>
            </div>
        </div>

        </div> --}}

        <div class="position-relative ">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30002.54703617599!2d73.6951784831116!3d19.953108539974554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bddecafb71b21c9%3A0xa6dab4828bb274df!2sMIDC%20Ambad%2C%20Nashik%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1707914483331!5m2!1sen!2sin"
                width="100%" id="ifrm" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="addrescard addresscenter  pt-20">
            <div class="card" style="width: 25rem;">
                <h4 class="card-header card-info2 text-center text-white">
                    Address
                </h4>
                <div class="card-body text-justify p-4">
                    <ul class="clrtext">
                        <li><span class="f-600">Plant No. 1</span> W-127 (A),</li>
                        <br>
                        <li><span class="f-600">Plant No. 2</span> - W-118 (A) MIDC Ambad Nashik - 422010 ,</li>
                        <br>
                        <li><span class="f-600">Plant No. 3</span> - GAT NO679/2/1 , Kurli Alandi Road ,Chankan , Tal
                            khed Dist. Pune - 410501,</li>
                        <br>
                        <li><span class="f-600">Plant No. 4</span> - GF Plot No - 913 Shreeji Engg, GIDC , Halol ,
                            Panchamahal Gujarat - 389350</li>


                    </ul>
                </div>
            </div>
        </div>
        </div>

        <div class="contcatcontainer ">
            <div class="card shadow-1">

                <div class="row">
                    <div class="col-lg-4 col-2 col-sm-4">
                        <img src="{{ asset('website/assets/img/contact/callimg1.png')}}" alt="">
                    </div>
                    <div class="col-lg-8 col-4 col-sm-8 contnom mt-60">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <h5 class="f-700 clrtext">Contact</h5>
                            </div>
                            <ul class="d-flex col-lg-9 col-md-9 col-sm-9">
                                <li><a href="tel:+91 7028082176" class="clrtext  f-600">7028082176</a>
                                </li>
                                <li><a href="tel: +91 0253 - 2383517" class="clrtext ml-20 f-600">0253-2383517</a>
                                </li>
                            </ul>


                        </div>
                        <div class="row social-links mb-20 ">
                            <div class="col-lg-5 col-md-5 mt-0 py-3">
                                <h5 class="f-700 clrtext">Follow us on</h5>

                            </div>
                            <div class="col-lg-7 col-md-7 social-links social-links1 ">
                                <ul class="social-icons sociicon">
                                    <li>
                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    </li>

                                    <li>
                                        <a href="#"><i class="fab fa-instagram"></i></a>
                                    </li>
                                    <li class="email">
                                        <a href="#"><i class="fa-regular fa-envelope "></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
    <div class="container card shadow-1 p-5">
        <form class="forms-sample" action="{{ url('add-contactus') }}" id="regForm" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row py-md-2">
          <div class="col-md-6 pt-md-0 pt-2 ">
            <div class="">
              <label for="full_name"><strong style="color:#323232"> Name </strong></label>
              <input type="text" placeholder="Your Full Name" name="full_name" value="{{ old('full_name') }}"
                class="form-control full_nameField">
              <span id="number-validate" class="red-text"></span>
              @if ($errors->has('full_name'))
              <span class="red-text">
                <?php echo $errors->first('full_name', ':message'); ?>
              </span>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="">
              <label for="subject"><strong style="color:#323232"> Company Name </strong></label>
              <input type="text" placeholder="Enter Company Name" name="subject" value="{{ old('subject') }}"
                class="form-control">
              <span id="number-validate" class="red-text"></span>
              @if ($errors->has('subject'))
              <span class="red-text">
                <?php echo $errors->first('subject', ':message'); ?>
              </span>
              @endif
            </div>
          </div>
          <div class="col-md-6 pt-2">
            <div class="">
              <label for="mobile_number"><strong style="color:#323232"> Mobile Number </strong></label>
              <input type="text" placeholder="Mobile Number" name="mobile_number"
                value="{{ old('mobile_number') }}" class="form-control" maxlength="10" minlength="10" onkeyup="addvalidateMobileNumber(this.value)">
              <span id="number-validate" class="red-text"></span>
              @if ($errors->has('mobile_number'))
              <span class="red-text">
                <?php echo $errors->first('mobile_number', ':message'); ?>
              </span>
              @endif

            </div>
          </div>
          <div class="col-md-6 pt-2">
            <div class="">
              <label for="email"><strong style="color:#323232"> Email Id</strong></label>
              <input type="email" placeholder="Email Id" name="email" value="{{ old('email') }}"
                class="form-control">
              <span id="number-validate" class="red-text"></span>
              @if ($errors->has('email'))
              <span class="red-text">
                <?php echo $errors->first('email', ':message'); ?>
              </span>
              @endif
            </div>
          </div>
          <div class="col-md-12 pt-2">
            <div class=" text-message-box">
              <label for="message"><strong style="color:#323232"> Message </strong></label>
              <textarea name="message" id="message" placeholder="Write a Message"
                class="form-control ">{{ old('message') }}</textarea>
              <span id="number-validate" class="red-text"></span>
              @if ($errors->has('message'))
              <span class="red-text">
                <?php echo $errors->first('message', ':message'); ?>
              </span>
              @endif
            </div>
          </div>
          <div class="col-md-12 py-3 captcha_set" style="text-align: left;">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}

            @if ($errors->has('g-recaptcha-response'))
            <span class="help-block">
              <span class="red-text">{{ $errors->first('g-recaptcha-response') }}</span>
            </span>
            @endif
          </div>
        </div>
          <div class="d-flex justify-content-center" style="display: flex; justify-content:center;">
            <button type="submit" id="submitButton" class="btn formSubmit eduact-btn__curve_button" style="background-color: #243772; color:#fff;"><span
                class="eduact-btn__curve"></span>Submit<i class="icon-arrow"></i></button>
          </div>

        
      </form>
      @if(Session::has('success_message'))
      <script>
        { Session::get('success_message') }}");
      </script>
      @endif
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function addvalidateMobileNumber(number) {
        var mobileNumberPattern = /^\d*$/;
        var validationMessage = document.getElementById("validation-message");
  
        if (mobileNumberPattern.test(number)) {
            validationMessage.textContent = "";
        } else {
            validationMessage.textContent = "Please enter only numbers.";
        }
    }
  </script>
  <script>
    $(document).ready(function() {
  
        $("#regForm").validate({
            errorClass: "error",
            rules: {
                full_name: {
                    required: true,
                    spcenotallow: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                mobile_number: {
                    required: true,
                    spcenotallow: true,
                },
                subject: {
                    required: true,
                },
                message: {
                    required: true,
                    spcenotallow: true,
                },
            },
            messages: {
                full_name: {
                    required: "Enter Full Name",
                    spcenotallow: "Enter Some Text",
                },
                email: {
                    required: "Enter Email Id",
                    spcenotallow: "Enter Some Text",
                },
                mobile_number: {
                    required: "Enter Mobile Number",
                    pattern: "Invalid Mobile Number",
                    remote: "This mobile number already exists.",
                    spcenotallow: "Enter Some Text",
                },
                subject: {
                    required: "Enter Company Name",
                },
                message: {
                    required: "Enter Message",
                },
            },
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },
            submitHandler: function(form) {
                // Check if reCAPTCHA challenge is completed
                if (grecaptcha.getResponse() === "") {
                    alert("Please complete the reCAPTCHA challenge.");
                } else {
                    // Proceed with form submission
                    form.submit();
                }
            }
        });
  
        $("input#document_file").hide();
  
    });
  
    $.extend($.validator.methods, {
        spcenotallow: function(b, c, d) {
            if (!this.depend(d, c)) return "dependency-mismatch";
            if ("select" === c.nodeName.toLowerCase()) {
                var e = a(c).val();
                return e && e.length > 0
            }
            return this.checkable(c) ? this.getLength(b, c) > 0 : b.trim().length > 0
        }
    });
  </script>
  
  <script>
    function dismissAlert(alertId) {
        var alertElement = document.getElementById(alertId);
        if (alertElement) {
            setTimeout(function() {
                alertElement.style.display = "none";
            }, 2000); // 2000 milliseconds = 2 seconds
        }
    }
  </script>
  @if(session('sweet_success'))
  <script>
      Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: "{{ session('sweet_success') }}",
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
      });
  </script>
  @endif
  
  @if(session('sweet_error'))
  <script>
      Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: "{{ session('sweet_error') }}",
          confirmButtonColor: '#d33',
          confirmButtonText: 'Try Again'
      });
  </script>
  @endif
  
@endsection