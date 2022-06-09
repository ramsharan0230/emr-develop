@extends('frontend.layouts.master')
@section('content')
    <div>
    <ul class="nav nav-tabs"  role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#department-tab" role="tab"  >Department</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  data-toggle="tab" href="#doctor-tab" role="tab"  >Doctor</a>
        </li>
    </ul>
    <div class="tab-content" >
        <div class="tab-pane fade show active" id="department-tab" role="tabpanel" >
            @include('department::autobilling')
        </div>
        <div class="tab-pane fade" id="doctor-tab" role="tabpanel" >
            @include('department::autobillingdoctor')
        </div>
    </div>
    </div>
@endsection
