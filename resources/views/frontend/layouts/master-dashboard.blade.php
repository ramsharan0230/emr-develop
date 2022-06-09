<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <title>CogentEMR</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('new/images/favicon.ico') }}"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/bootstrap.min.css') }}"/>
    <!-- Fontawesomg css -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}"/>
    <!-- Typography CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/typography.css') }}"/>
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/style.css') }}"/>
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('new/css/custom.css') }}"/>
    <!-- design css -->
    <link rel="stylesheet" href="{{ asset('new/css/design.css') }}"/>
    <!-- neuro css -->
{{--    <link rel="stylesheet" href="{{ asset('new/css/.css') }}"/>--}}
<!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/theme-responsive.css') }}"/>

{{--    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css')}}">--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-timepicker.css')}}">--}}
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
{{--    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/jquery-ui-timepicker.js')}}"></script>--}}


    {{--Neapli datepicker--}}

    @stack('after-styles')
    <style>
        .loader-ajax-start-stop {
            position: absolute;
            left: 45%;
            top: 35%;
        }

        .loader-ajax-start-stop-container {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            background: black;
            opacity: .5;
            z-index: 1051;
        }
    </style>
</head>
<style>
    input:checked + label {
    }

</style>
<body class="loading">

<!-- loader Start -->
<div class="loader-block">
    <div class="loader-side-menu">
        <div class="loader-logo-block">
            <div class="loader-logo"></div>
            <div class="lodaer-logo-name"></div>
        </div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
    </div>
    <div class="loader-search-bar">
        <div class="loader-search-block"></div>
        <div class="loader-logo-block">
            <div class="loader-logo"></div>
            <div class="lodaer-logo-name"></div>
        </div>
    </div>
    <div class="loader-content-block">
        <div class="loader-content1"></div>
        <div class="loader-content4"></div>
        <div class="loader-content2"></div>
        <div class="loader-content3"></div>
    </div>
    <div class="loader-content-block">
        <div class="loader-content1"></div>
        <div class="loader-content3"></div>
        <div class="loader-content4"></div>
        <div class="loader-content2"></div>
    </div>
    <div class="loader-content-block">
        <div class="loader-content2"></div>
        <div class="loader-content3"></div>
        <div class="loader-content1"></div>
        <div class="loader-content4"></div>
    </div>
</div>
<!-- loader END -->

<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Sidebar  -->
@include('frontend.layouts.sidebar')

<!-- Page Content  -->
    <div id="content-page" class="content-page">
        <!-- TOP Nav Bar -->
    @include('frontend.layouts.header')


    @yield('content')

    <!-- Footer -->
        @include('frontend.layouts.footer')



    </div>
</div>
<div class="error-success">
    <div class="alert alert-danger" id="error-for-all-container" role="alert" style="position:fixed ;top:12%;right:2%; z-index:1054;">
        <div class="iq-alert-icon">
            <i class="ri-information-line"></i>
        </div>
        <div class="iq-alert-text">
            <div id="error-for-all"></div>
        </div>
    </div>

    <div class="alert alert-success" id="success-for-all-container" role="alert" style="position:fixed ;top:12%;right:2%;z-index:1054;">
        <div class="iq-alert-icon">
            <i class="ri-alert-fill"></i>
        </div>
        <div class="iq-alert-text">
            <div id="success-for-all"></div>
        </div>
    </div>

</div>

<div class="loader-ajax-start-stop-container">
    <div class="loader-ajax-start-stop">
        <img src="{{ asset('images/loader-rolling.svg') }}">
    </div>
</div>
<!-- Wrapper END -->
{{--old scripts--}}
@include('frontend.common.global-script')
@stack('after-script')
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="{{ asset('new/js/jquery.min.js') }}"></script> -->
<script src="{{ asset('new/js/popper.min.js') }}"></script>
<script src="{{ asset('new/js/bootstrap.min.js') }}"></script>
<!-- Counterup JavaScript -->
<!-- <script src="{{ asset('new/js/waypoints.min.js') }}"></script> -->
<script src="{{ asset('new/js/jquery.counterup.min.js') }}"></script>
<!-- Apexcharts JavaScript -->
<script src="{{ asset('new/js/apexcharts.js') }}"></script>
<!-- Owl Carousel JavaScript -->
{{--<script src="{{ asset('new/js/owl.carousel.min.js') }}"></script>--}}
<!-- Smooth Scrollbar JavaScript -->
<script src="{{ asset('new/js/smooth-scrollbar.js') }}"></script>
<!-- am core JavaScript -->
{{--<script src="{{ asset('new/js/core.js') }}"></script>--}}
<!-- am charts JavaScript -->
{{--<script src="{{ asset('new/js/charts.js') }}"></script>--}}
<!-- am animated JavaScript -->
{{--<script src="{{ asset('new/js/animated.js') }}"></script>--}}
<!-- am kelly JavaScript -->
{{--<script src="{{ asset('new/js/kelly.js') }}"></script>--}}
<!-- Chart Custom JavaScript -->
{{--<script src="{{ asset('new/js/chart-custom.js') }}"></script>--}}
<script type="text/javascript">
    $.ajaxSetup({
        headers:
            {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });

    var baseUrl = '{{ url('/') }}';

    /*ajax loader*/
    $(document).ready(function () {
        /*---------------------------------------------------------------------
        Page Loader
        -----------------------------------------------------------------------*/
        $("#load").fadeOut();
        $(".loader-block").delay().fadeOut("");
        $("body").removeClass("loading");

    });
    var $loadingContainer = $('.loader-ajax-start-stop-container').hide();
    $('#loader-ajax-start-stop').show();
    $loadingContainer.show();
    $(document).ready(function () {
        $('#loader-ajax-start-stop').hide();
        $loadingContainer.hide();
    })

    $(document)
        .ajaxStart(function () {
            $('#loader-ajax-start-stop').show();
            $loadingContainer.show();
        })
        .ajaxStop(function () {
            $('#loader-ajax-start-stop').hide();
            $loadingContainer.hide();
        });

</script>

</body>
</html>
