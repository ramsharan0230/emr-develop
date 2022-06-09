@extends('frontend.layouts.master')
@section('content')
    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="outPatient" data-toggle="tab" href="#out-patient" role="tab"
                   aria-controls="home" aria-selected="true"><span></span>Patient Reports / Transition</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="out-patient" role="tabpanel" aria-labelledby="home-tab">
                {{--navbar--}}
                @include('menu::common.nav-bar')
                {{--end navbar--}}
            </div>
        </div>
        <div class="patient-profile">
            <div class="container">

                <div class="profile-form">
                    <form action="javascript:;" id="transition-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group-consult">
                                    <label for="address" class="col-sm-3 col-form-label col-form-label-sm">From:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="from_date" class="form-control form-control-sm" id="from_date" value="{{date('Y-m-d')}}">
                                    </div>

                                </div>
                                <div class="form-group-consult">
                                    <label for="address" class="col-sm-3 col-form-label col-form-label-sm">To:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="to_date" class="form-control form-control-sm" id="to_date" value="{{date('Y-m-d')}}">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="radio-1">
                                    <input type="radio" name="entry_exit_date" class="form-radio" id="entry_date" value="entry_date" checked>
                                    <label for="entry_date" class="">Entry Date</label>&nbsp;&nbsp;

                                    <input type="radio" name="entry_exit_date" value="exit_date" class="form-radio" id="exit_date">
                                    <label for="exit_date" class="">Exit Date</label>
                                </div>

                                <div class="form-group-consult">
                                    <label for="address" class="col-sm-3 col-form-label col-form-label-sm">Depart</label>
                                    <div class="col-sm-8">
                                        <select name="department" id="department" class="form-control form-control-sm">
                                            {{--<option value="%">%</option>--}}
                                            @if(isset($department) and count($department) > 0)
                                                @foreach($department as $d)
                                                    <option value="{{$d->flddept}}">{{$d->flddept}}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>

                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group-consult">

                                    <div class="col-sm-8">
                                        <button  class="default-btn f-btn-icon-f" style="margin-bottom: 3px;" onclick="searchData()"><i class="fa fa-search"></i> Search</button>
                                    </div>

                                </div>
                                <div class="form-group-consult">

                                    <div class="col-sm-8">
                                        <button class="default-btn" onclick="exportData()"><i class="fas fa-external-link-square-alt"></i>&nbsp;&nbsp;Export</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="clearfix"></div>
                <div class="profile-form" style="margin-top: 10px;">
                    <div class="tab-1">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="grid-tab" data-toggle="tab" href="{{ route('display.consultation.transition') }}" role="tab" aria-controls="grid" aria-selected="true">GridView</a>

                                <a class="nav-item nav-link" id="chart-tab" data-toggle="tab" href="#chart" role="tab" aria-controls="chart" aria-selected="true">ChartView</a>

                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@stop

@push('after-script')

@endpush
