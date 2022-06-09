@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

<section class="cogent-nav">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <!-- <li class="nav-item">
            <a class="nav-link active active-back" id="outPatient" data-toggle="tab" href="#out-patient" role="tab" aria-controls="home" aria-selected="true"><span></span> Form</a>
        </li> -->
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="out-patient" role="tabpanel" aria-labelledby="home-tab">


        </div>
    </div>

    <div class="container-fluid">



        <div>
            <div class="col-md-12">
                <h1>Bar Code</h1>
                <label>
                    Content
                </label>
                <select name="bar-code-content">
                    <option value=""></option>
                    <option value="EncounterID">EncounterID</option>
                    <option value="SampleNo">SampleNo</option>
                    <option value="SampleNo@EncID">SampleNo@EncID</option>
                </select>
                <a href="#"> <img src="{{asset('assets/images/tick.png')}}" alt=""> </a>

                <label>
                    Seperation
                </label>
                <select name="bar-code-seperation">
                    <option value=""></option>
                    <option value="TestName">TestName</option>
                    <option value="Section">Section</option>
                    <option value="None">None</option>
                </select>
                <a href="#"> <img src="{{asset('assets/images/tick.png')}}" alt=""> </a>

                <label>
                    Templete
                </label>

                <input name="templete" value="">
                <a href="#"> <img src="{{asset('assets/images/tick.png')}}" alt=""> </a>


            </div>
        </div>




    </div>

</section>


@stop
