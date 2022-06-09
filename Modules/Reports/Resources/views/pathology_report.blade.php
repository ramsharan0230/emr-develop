@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Pathology report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form method="POST" action="{{route('pathology.count.generate.report')}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3">Form:</label>
                                    <div class="col-sm-9">
                                        <input type="text" autocomplete="off" name="from_date" id="from_date" class="form-control nepaliDatePicker" />
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-2">To:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autocomplete="off"  name="to_date" id="to_date" class="form-control nepaliDatePicker" />
                                    </div>
                                   
                                </div>
                            </div>



                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <div class="col-sm-12">
                                        <select name="category" class="form-control">
                                            <option value="">All Category</option>
                                            @if($categories)
                                            @foreach($categories as $cat)
                                            <option value="{{$cat->fldcategory}}">{{$cat->fldcategory}}</option>
                                            @endforeach
                                            @endif


                                        </select>
                                    </div>

                                </div>

                            </div>
                            <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary rounded-pill" target="_blank"><i class="fa fa-code"></i>&nbsp;Export</button>
                                 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">

        </div>
    </div>
</div>
@endsection