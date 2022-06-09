<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Cogent EMR</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('new/images/favicon.ico') }}"/>

    <link rel="stylesheet" href="{{ asset('patient-portal/css/bootstrap.css') }}"/>

    <!-- Fontawesomg css -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}"/>

    <!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet"> -->

    <link rel="stylesheet" href="{{ asset('patient-portal/css/slick.css') }}"/>

    <link rel="stylesheet" href="{{ asset('patient-portal/css/slick-theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('patient-portal/css/remixicon.css') }}"/>
    <!-- Responsive CSS -->

    <link rel="stylesheet" href="{{ asset('patient-portal/css/style.css') }}"/>
    <!-- neuro css -->
    {{--    <link rel="stylesheet" href="{{ asset('new/css/.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('patient-portal/css/responsive.css') }}"/>

    <script src="{{asset('patient-portal/js/jquery-3.5.1.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
    @stack('after-styles')
</head>
<body>

@include('patient.layouts.header')

<div class="patient-portal">

    <div class="wrapper">
        @include('patient.layouts.sidebar')

        @yield('content')

    </div>
</div>

<div class="menu-overlay"></div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="{{ asset('new/js/jquery.min.js') }}"></script> -->
    <script src="{{ asset('patient-portal/js/popper.min.js') }}"></script>
    <script src="{{ asset('patient-portal/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('patient-portal/js/script.js') }}"></script>

@stack('after-script')

</body>
</html>
