@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-block">
                        <div class="form-row form-group ml-2">
<!--                            <div class="col-sm-2 text-center">
                                <div class="history-img mt-2">
                                    <img src="{{ asset('assets/images/dummy-img.jpg')}}" alt="">
                                </div>
                            </div>-->
                            <div class="col-sm-10">
                                <div class="profile-form form-group mt-2">
                                    <h5>{{ $encounterData->patientInfo->fullname }}({{ $encounterData->patientInfo->fullname }})- <small>{{ $encounterData->patientInfo->fldagestyle }} / {{ $encounterData->patientInfo->fldptsex }}</small> </h5>
                                    {{-- <h5>{{ $encounterData->patientInfo->fullname }}({{ $encounterData->patientInfo->fullname }})- <small>{{ \Carbon\Carbon::parse($encounterData->patientInfo->fldptbirday)->age }} Years  / {{ $encounterData->patientInfo->fldptsex }}</small> </h5> --}}
                                </div>
                                <div class="profile-form form-group form-row">
                                    <label class="col-sm-2">@if(isset($encounterData->patientInfo)){{ $encounterData->patientInfo->fldptaddvill }} , {{ $encounterData->patientInfo->fldptadddist }}@endif</label>
                                </div>
                                <div class="profile-form form-group form-row">
                                    <label class="col-sm-2">Date Of Birth:</label>
                                    <label class="col-sm-10">{{ date('Y-m-d', strtotime($encounterData->patientInfo->fldptbirday)) }}</label>
                                </div>
                                <div class="profile-form form-group form-row">
                                    <label class="col-sm-2">Encounter:</label>
                                    <label class="col-sm-10">{{ $encounterData->fldencounterval }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('patienthistory::content-main.transition')
            @include('patienthistory::content-main.symptoms')
            @include('patienthistory::content-main.po_inputs')
            @include('patienthistory::content-main.exam')
            @include('patienthistory::content-main.radiology')
            {{--            @include('patienthistory::content-main.diagnosis')--}}
            @include('patienthistory::content-main.notes')
            @include('patienthistory::content-main.med_dosing')
            @include('patienthistory::content-main.progress')
            @include('patienthistory::content-main.nur_activity')
            @include('patienthistory::content-main.bladder_irrigation')
        </div>
    </div>
@endsection
