<!-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Module ICU</title>

       {{-- Laravel Mix - CSS File --}}
       {{-- <link rel="stylesheet" href="{{ mix('css/icu.css') }}"> --}}

    </head>
    <body>
        @yield('content')

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/icu.js') }}"></script> --}}
    </body>
</html> -->

@extends('frontend.layouts.master')

@section('content')
  

    <div class="container-fluid">
        <div class="row">
            @include('frontend.common.patientProfile')

            @include('inpatient::layouts._inpatient')
        </div>
    </div>

 
@endsection
