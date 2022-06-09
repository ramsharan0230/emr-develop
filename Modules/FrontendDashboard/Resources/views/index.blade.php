@extends('frontend.layouts.master')

@section('content')
    <h1>Hello World</h1>
    <?php
    $dateNep = Helpers::dateEngToNep('2020/04/04');
    print_r($dateNep->year) ;
    echo '<br>';
    print_r(date("Y-m-d H:i:s"));
    ?>
    <p>
        This view is loaded from module: {!! config('frontenddashboard.name') !!}
    </p>
@endsection
