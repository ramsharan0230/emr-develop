<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Cogent Health - Forgot Password</title>
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
<style>
    .owl-item .cloned {
        width: 370px !important;
    }
</style>
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
                    <img src="{{asset('assets/images/emrlogo.svg')}}" alt="logo-cogent">
                    {{--@if( Options::get('brand_image') && Options::get('brand_image') != "" )
                        <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="logo"/>
                    @endif--}}
                </div>
                <h3 class="mb-0 text-primary">Reset Password</h3>
                <p>Welcome To Chirayu National Hospital</p>

                <form method="POST" action="{{ route('password.update') }}" id="login-form" method="POST" class="mt-4">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Reset Password') }}
                            </button>

                        </div>
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
        // jQuery('.owl-carousel').each(function () {
        //     let jQuerycarousel = jQuery(this);
        //     jQuerycarousel.owlCarousel({
        //         items: jQuerycarousel.data("items"),
        //         loop: jQuerycarousel.data("loop"),
        //         margin: jQuerycarousel.data("margin"),
        //         nav: jQuerycarousel.data("nav"),
        //         dots: jQuerycarousel.data("dots"),
        //         autoplay: jQuerycarousel.data("autoplay"),
        //         autoplayTimeout: jQuerycarousel.data("autoplay-timeout"),
        //         navText: ["<i class='fa fa-angle-left fa-2x'></i>", "<i class='fa fa-angle-right fa-2x'></i>"],
        //         responsiveClass: true,
        //         responsive: {
        //             // breakpoint from 0 up
        //             0: {
        //                 items: jQuerycarousel.data("items-mobile-sm"),
        //                 nav: false,
        //                 dots: true
        //             },
        //             // breakpoint from 480 up
        //             480: {
        //                 items: jQuerycarousel.data("items-mobile"),
        //                 nav: false,
        //                 dots: true
        //             },
        //             // breakpoint from 786 up
        //             786: {
        //                 items: jQuerycarousel.data("items-tab")
        //             },
        //             // breakpoint from 1023 up
        //             1023: {
        //                 items: jQuerycarousel.data("items-laptop")
        //             },
        //             1199: {
        //                 items: jQuerycarousel.data("items")
        //             }
        //         }
        //     });
        // });
    });

    $('.login-carousel').owlCarousel({
        items: 1,
        loop: true,
        nav: false,
        dots: true,
        margin: 0,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 1
            },
            786: {
                items: 1
            },
            1023: {
                items: 1
            },
            1199: {
                items: 1
            }
        }
    })

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

