@extends('ssf::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('ssf.name') !!}
    </p>
@endsection
