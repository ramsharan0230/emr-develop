<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Cogent EMR</title>
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
    <link rel="stylesheet" href="{{ asset('new/css/responsive.css') }}"/>

    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css')}}">
{{--    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-timepicker.css')}}">--}}
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
{{--    <script src="{{asset('assets/js/jquery-ui-timepicker.js')}}"></script>--}}

    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}"/>
    <script src="{{asset('js/select2.min.js')}}"></script>
<!-- <script src="{{asset('../vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script> -->

    <!--Script for Neuro -->
{{--    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}"/>
<!-- <script type="text/javascript" src="{{  asset('DataTables/datatables.min.js') }}"></script> -->

    {{--Neapli datepicker--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/css/nepali.datepicker.v2.2.min.css') }}">--}}
{{--    <script src="{{ asset('assets/js/nepali.datepicker.v2.2.min.js') }}"></script>--}}
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <style>
        .content-page-stock {
            overflow: hidden;
            padding: 120px 15px 0;
            min-height: 100vh;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
            background: #eff7f8;
            border-radius: 25px 0 0 25px;
        }
        .iq-top-navbar{
            width: calc(100% - 60px);
        }
    </style>
    @stack('after-styles')
</head>
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
{{--@include('frontend.layouts.sidebar')--}}

<!-- Page Content  -->
    <div id="content-page-stock" class="content-page-stock">
        <!-- TOP Nav Bar -->
    @include('frontend.layouts.header')

    @yield('content')

    <!-- Footer -->
        @include('frontend.layouts.footer')
        @include('modal.bed-list')
    </div>
</div>
<!-- Wrapper END -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="{{ asset('new/js/jquery.min.js') }}"></script> -->
<script src="{{ asset('new/js/popper.min.js') }}"></script>
<script src="{{ asset('new/js/bootstrap.min.js') }}"></script>
<!-- Appear JavaScript -->
<script src="{{ asset('new/js/jquery.appear.js') }}"></script>
<!-- Countdown JavaScript -->
{{--    <script src="{{ asset('new/js/countdown.min.js') }}"></script>--}}
<!-- Counterup JavaScript -->
<!-- <script src="{{ asset('new/js/waypoints.min.js') }}"></script> -->
{{--    <script src="{{ asset('new/js/jquery.counterup.min.js') }}"></script>--}}
<!-- Wow JavaScript -->
<script src="{{ asset('new/js/wow.min.js') }}"></script>
<!-- Apexcharts JavaScript -->
<script src="{{ asset('new/js/apexcharts.js') }}"></script>
<!-- Slick JavaScript -->
{{--    <script src="{{ asset('new/js/slick.min.js') }}"></script>--}}
<!-- Select2 JavaScript -->
{{--    <script src="{{ asset('new/js/select2.min.js') }}"></script>--}}
<!-- Owl Carousel JavaScript -->
{{--<script src="{{ asset('new/js/owl.carousel.min.js') }}"></script>--}}
<!-- Magnific Popup JavaScript -->
<script src="{{ asset('new/js/jquery.magnific-popup.min.js') }}"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="{{ asset('new/js/smooth-scrollbar.js') }}"></script>
<!-- lottie JavaScript -->
<script src="{{ asset('new/js/lottie.js') }}"></script>

<!-- Chart Custom JavaScript -->
{{--<script src="{{ asset('new/js/chart-custom.js') }}"></script>--}}
<!-- Custom JavaScript -->
<script src="{{ asset('new/js/custom.js') }}"></script>
@stack('after-script')

</body>
</html>
