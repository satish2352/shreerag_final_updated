<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shreerag Engineering And Auto Pvt Ltd</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    {{-- <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/favicon.ico')}}"> --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('website/assets/img/logo/Layer 2.png')}}" >
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.transitions.css')}}">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/animate.css')}}">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/morrisjs/morris.css')}}">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/scrollbar/jquery.mCustomScrollbar.min.css')}}">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/metisMenu/metisMenu.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/metisMenu/metisMenu-vertical.css')}}">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/calendar/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/calendar/fullcalendar.print.min.css')}}">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/form/all-type-forms.css')}}">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">
    <!-- modernizr JS
		============================================ -->
    <script src="{{asset('js/vendor/modernizr-2.8.3.min.js')}}"></script>
</head>
<style>
  .error{
    color: red !important;
  }
  .footer{
    padding-top: 10px;
  }
  .cover-image {
        width: 100%;
        height: 100vh; /* Full height of the viewport */
        display: flex; /* Center content inside if needed */
        justify-content: center; /* Horizontal center */
        align-items: center; /* Vertical center */
    }
</style>
<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    {{-- <div class="color-line"></div> --}}
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="back-link back-backend">
                  
                </div>
            </div> --}}
        </div>
    </div>
    <div class="container-fluid">
        <div class="row " style="display: flex; justify-content: center; ">
          
            <div class="col-md-6 col-md-6 col-sm-6  d-flex justify-content-center" style="display: flex;
    justify-content: center;
    align-items: center;">
              <div class="col-md-8 col-md-8 col-sm-8 col-xs-12 ">
             
                <div class="text-center m-b-md custom-login">
                   
                   <a href="https://shreeragengineering.com/"> <img src="{{ asset('website/assets/img/logo/Layer 2.png')}}" alt="" style="width:120px;"></a>
                    <p></p>
                </div>
                <div class="hpanel">
                    <div class="panel-body">
                      @if (isset($return_data['msg_alert']) && $return_data['msg_alert'] == 'green')
                      <div class="alert alert-success" role="alert">
                          {{ $return_data['msg'] }}
                      </div>
                      @endif

                      @if (session('error'))
                          <div class="alert alert-danger" role="alert">
                              <p>{{ session()->get('error') }} </p>
                          </div>
                      @endif
                      @if (session('success'))
                          <div class="alert alert-danger" role="alert">
                              <p> {{ session('success') }} </p>
                          </div>
                      @endif
                        <form action="{{ route('login') }}" method="POST" id="loginForm">
                          @csrf
                            <div class="form-group">
                                <label class="control-label" for="username">Email Id</label>
                                <input type="text"  placeholder="Please enter your email Id" value="" name="email" id="email" class="form-control">
                                {{-- <span class="help-block small">Your unique username for the app</span> --}}
                            </div>
                            {{-- <div class="form-group">
                                <label class="control-label" for="password">Password</label>
                                <input type="password" placeholder="Please enter your password" value="" name="password" id="password" class="form-control">
                            </div> --}}
                            <div class="form-group">
                              <label class="control-label" for="password">Password</label>
                              <div class="input-group">
                                  <input type="password" placeholder="Please enter your password" name="password" id="password" class="form-control">
                                  <div class="input-group-addon">
                                      <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}

                            @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block">
                                    <span class="red-text">{{ $errors->first('g-recaptcha-response') }}</span>
                                </span>
                            @endif
                        </div>
                            <button type="submit" class="btn btn-success btn-block loginbtn">Login</button>
                        </form>

                    </div>
                </div>
                <div class="row footer">
                  <div class="col-md-12 col-md-12 col-sm-12 col-xs-12 text-center ">
                      <p>Copyright &copy; {{date('Y')}} <a href="https://shreeragengineering.com">Shreerag Engineering Pvt Ltd</a> All rights reserved.</p>
                  </div>
              </div>
              </div>
            </div>
           
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-right: 0px;">
              <img src="{{ asset('website/assets/img/logo/login.png')}}" class="cover-image img-fluid" alt="">
            </div>
        </div>
        
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
        <script>
            $(document).ready(function() {
                // Custom validation rule to check if the input does not contain only spaces
                $.validator.addMethod("spcenotallow", function(value, element) {
                    return this.optional(element) || value.trim().length > 0;
                }, "Enter some valid text");
            
                // Initialize the form validation
                $("#loginForm").validate({
                    rules: {
                      email: {
                            required: true,
                            email: true,  // Check for valid email format
                            // spcenotallow: true // Prevent spaces only
                        },
                        password: {
                            required: true,
                        },
                    },
                    messages: {
                      email: {
                        required: "Please enter your email Id.",
                        email: "Please enter a valid email Id.",
                        // spcenotallow: "Email cannot contain only spaces."

                        },
                        password: {
                            required: "Please enter the password.",
                        },
                    },
                });
            });
            </script>
            <script>
              $(document).ready(function() {
                  // Click event for eye icon
                  $("#togglePassword").click(function() {
                      // Get the password field
                      const passwordField = $("#password");
                      
                      // Check the current type of the password field
                      const type = passwordField.attr("type") === "password" ? "text" : "password";
                      
                      // Toggle the type attribute
                      passwordField.attr("type", type);
                      
                      // Toggle the eye / eye-slash icon
                      $(this).toggleClass("fa-eye fa-eye-slash");
                  });
              });
          </script>
          
    <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.11.3.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/jquery.meanmenu.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/scrollbar/mCustomScrollbar-active.js"></script>
    <!-- metisMenu JS
		============================================ -->
    <script src="js/metisMenu/metisMenu.min.js"></script>
    <script src="js/metisMenu/metisMenu-active.js"></script>
    <!-- tab JS
		============================================ -->
    <script src="js/tab.js"></script>
    <!-- icheck JS
		============================================ -->
    <script src="js/icheck/icheck.min.js"></script>
    <script src="js/icheck/icheck-active.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
</body>

</html>