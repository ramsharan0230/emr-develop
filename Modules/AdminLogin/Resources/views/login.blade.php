<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Cogent Health - Sign in</title>
    @include('frontend.layouts.root-color')
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('new/images/favicon.ico') }}"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/bootstrap.min.css') }}"/>
    <!-- Fontawesomg css -->

<!-- Typography CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/typography.css') }}"/>
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/style.css') }}"/>
    <!-- Responsive CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('new/css/responsive.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('new/css/login.css') }}"/>

</head>

<body>

<!-- Sign in Start -->
<section class="context">
    <div class="container ">

            <div class="sign-in-page">
                <div class="sign-in-form">
            <!-- @if( Options::get('brand_image') && Options::get('brand_image') != "" )
                <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="logo"  style="position: relative; left: 50%; height: 100px; transform: translate(-50%);"/>
                    @endif -->
                <div class="sign-logo">
                    <img src="{{asset('assets/images/emrlogo.png')}}" alt="logo-cogent">
                    {{--@if( Options::get('brand_image') && Options::get('brand_image') != "" )
                        <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="logo"/>
                    @endif--}}
                </div>
                <h3 class="mb-0 text-primary">Login</h3>
                <p>Welcome To {{Options::get('siteconfig')['system_name']??''}}</p>


                <form action="{{ route('admin.user.profile.login') }}" id="login-form" method="POST" class="mt-3">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="mb-1" for="exampleInputEmail1">Username</label>
                        <input type="username" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter username" name="username"/>
                    </div>
                    <div class="form-group ">
                        <label class="mb-1" for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Password" name="password"/>
                        <!-- <div class="show-box">
                        <input type="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Password" name="password"/>
                        <button type="submit" class="btn btn-light" onclick="showPassowrdToggel()" id="show-password">Show</button>
                        </div> -->

                    </div>

                    @if ($message = Session::get('error_message'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif

                    <div class="below-pwd">
                    <div class="form-group">
                        <input type="checkbox" onclick="showPassowrdToggel()" id="show-password">
                        <label class="mb-1" for="show-password">Show Password</label>
                    </div>
                    <div class="form-group ">
                    <a href="{{ route('password.request') }}" target="_blank">Forgot Password</a>
                    </div>
                    </div>

                    <div class="d-inline-block w-100">
                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>
                    </div>

                </form>

            </div>
            <div class="sign-in-info">
            <p class="text-center mt-3">Please feel free to contact us during office hours at : 980-1879200 l 980-1879201<br>
                &copy;{{ \Carbon\Carbon::now()->year }} <a href="https://cogenthealth.com.np/" target="_blank"> Cogent Health Pvt. Ltd.</a>
            </p>
        </div>
        </div>



    </div>
</section>
<!-- Sign in END -->

<!-- sign in background animation-->
<div class="area" >
        <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
        </ul>
</div >

<!-- end sign in background animation-->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ asset('new/js/jquery.min.js') }}"></script>
{{--<script src="{{ asset('new/js/popper.min.js') }}"></script>--}}
<script src="{{ asset('new/js/bootstrap.min.js') }}"></script>
<!-- Appear JavaScript -->
{{--<script src="{{ asset('new/js/jquery.appear.js') }}"></script>--}}
<!-- Countdown JavaScript -->
{{--<script src="{{ asset('new/js/countdown.min.js') }}"></script>--}}
<!-- Counterup JavaScript -->
{{--<script src="{{ asset('new/js/waypoints.min.js') }}"></script>--}}
{{--<script src="{{ asset('new/js/jquery.counterup.min.js') }}"></script>--}}
<!-- Wow JavaScript -->
{{--<script src="{{ asset('new/js/wow.min.js') }}"></script>--}}
<!-- Apexcharts JavaScript -->
{{--<script src="{{ asset('new/js/apexcharts.js') }}"></script>--}}
<!-- Slick JavaScript -->
{{--<script src="{{ asset('new/js/slick.min.js') }}"></script>--}}
<!-- Select2 JavaScript -->
{{--<script src="{{ asset('new/js/select2.min.js') }}"></script>--}}
<!-- Owl Carousel JavaScript -->
<script src="{{ asset('new/js/owl.carousel.min.js') }}"></script>
<!-- Magnific Popup JavaScript -->
<script src="{{ asset('new/js/jquery.magnific-popup.min.js') }}"></script>
<!-- Smooth Scrollbar JavaScript -->
{{--<script src="{{ asset('new/js/smooth-scrollbar.js') }}"></script>--}}
<!-- Chart Custom JavaScript -->
{{--<script src="{{ asset('new/js/chart-custom.js') }}"></script>--}}
<!-- Custom JavaScript -->
{{--<script src="{{ asset('new/js/custom.js') }}"></script>--}}
<script>
    jQuery(document).ready(function () {
        /*---------------------------------------------------------------------
        Page Loader
        -----------------------------------------------------------------------*/
        jQuery("#load").fadeOut();
        jQuery(".loader-block").delay().fadeOut("");
        jQuery("body").removeClass("loading");

    });



    $("body").on("submit", "form#login-form", function () {
        $(this).submit(function () {
            return false;
        });
        return true;
    });
    function showPassowrdToggel() {
        var x = document.getElementById("exampleInputPassword1");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
</body>

</html>
