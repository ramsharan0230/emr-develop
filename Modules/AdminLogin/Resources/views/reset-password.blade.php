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
</head>
<style>
    .owl-item .cloned {
        width: 370px !important;
    }
</style>
<body>
<!-- loader Start -->
<div class="loader-block sign-in-page page-login">
    <div class="container sign-in-page-bg mt-4 mb-4 p-0">
        <div class="row no-gutters">
            <div class="col-md-6">
                <div class="sign-in-detail">
                    <div class="loader-login-logo"></div>
                    <div class="loader-login-banner">
                        <div class="loader-login-banner-block"></div>
                        <div class="loader-login-banner-text"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 position-relative">
                <div class="sign-in-from">
                    <div class="loader-h1"></div>
                    <div class="loader-p"></div>
                    <div class="loader-form">
                        <div class="loader-label"></div>
                        <div class="loader-input"></div>
                    </div>
                    <div class="loader-form">
                        <div class="loader-label"></div>
                        <div class="loader-input"></div>
                    </div>
                    <div class="loader-form">
                        <div class="loader-label"></div>
                        <div class="loader-input"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- loader END -->
<!-- Sign in Start -->
<section class="sign-in-page">
    <div class="container sign-in-page-bg mt-4 mb-4 p-0">
        <div class="row no-gutters">
            <div class="col-md-6 text-center">
                <div class="sign-in-detail text-white">
                    <a class="sign-in-logo mb-5" href="#">
                        @if( Options::get('brand_image') && Options::get('brand_image') != "" )
                            <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="logo"/>
                        @endif

                    </a>
                    <div class="">

                    </div>
                </div>
                <div class="copy-license text-white">
                    <p>Licensed for: {{ Options::get('licensed_by') }}</p>
                </div>


            </div>
            <div class="col-md-6">
                <div class="sign-in-from">
                    <div class="sign-logo">
                        <img src="{{asset('assets/images/logo.png')}}" alt="">
                    </div>
                    <h1 class="mb-0">Sign in</h1>

                    @if ($message = Session::get('error_message'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <form role="form" method="POST" action="{{ route('password.reset') }}">
                        <h3>Reset Password</h3>

                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email"/>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                            @endif
                            <input type="password_confirmation" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"/>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-default submit">Reset Password</button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">

                            <div class="clearfix"></div>
                            <br />

                            <div>
                                <h1><i class="fa fa-paw"></i> Larashop Admin Panel</h1>
                                <p>©2017 All Rights Reserved.</p>
                            </div>
                        </div>
                    </form>
                    <div class="">
                        <p class="text-center">&copy;{{ \Carbon\Carbon::now()->year }} Cogent Health Pvt. Ltd.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sign in END -->
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
