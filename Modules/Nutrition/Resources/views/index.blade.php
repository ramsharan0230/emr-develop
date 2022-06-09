@extends('nutrition::layouts.master')
<link rel="stylesheet" href="{{ asset('styles/nutritionmodal.css') }}"> 

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('nutrition.name') !!}
    </p>
@endsection
