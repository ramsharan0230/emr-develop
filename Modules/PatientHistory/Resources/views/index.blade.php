@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            @include('patienthistory::layouts.patient-number')
            <div class="col-sm-6">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-block">
                        <div class="col-sm-12">
                            <div class="profile-form form-group mt-2">
                                <h5>{{ $patientDetails->fullname??'' }}</h5>
                            </div>
                            <div class="profile-form form-group">
                                <label>
                                    @if(isset($patientDetails))
                                        {{ $patientDetails->fldagestyle }} / {{ $patientDetails->fldptsex }}
                                        {{-- {{ \Carbon\Carbon::parse($patientDetails->fldptbirday)->age }} Years  / {{ $patientDetails->fldptsex }} --}}
                                    @endif
                                </label>
                            </div>
                            <div class="profile-form form-group form-row">
                                <label class="col-sm-2">Address:</label>
                                <label class="col-sm-10">
                                    @if(isset($patientDetails)){{ $patientDetails->fldptaddvill }} , {{ $patientDetails->fldptadddist }}@endif
                                </label>
                            </div>
                            <div class="profile-form form-group form-row">
                                <label class="col-sm-2">Patient ID:</label>
                                <label class="col-sm-10">
                                    @if(isset($patientDetails)){{ $patientDetails->fldpatientval }}@endif
                                </label>
                            </div>
                            <div class="profile-form form-group form-row">
                                <label class="col-sm-2">EncID:</label>
                                <label class="col-sm-10">@if(count($encounters)){{ implode(', ', $encounters->pluck('fldencounterval')->toArray()) }} @endif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Latest Visit
                            </h3>
                        </div>
                        <!-- <button class="btn btn-primary float-right"><i class="fa fa-eye"></i> View Details</button> -->
                    </div>
                    <div class="iq-card-body">
                        <ul class="list-group list-group-flush">
                            @if($encounters)
                                @foreach($encounters->take(5) as $encounter)
                                    <li class="list-group-item"><a href="{{ route('encounter.history', $encounter->fldencounterval) }}">{{ $encounter->fldcurrlocat }}</a><span class="float-right">{{ $encounter->fldregdate }}</span></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            @include('patienthistory::content-main.transition')
            @include('patienthistory::content-main.symptoms')
            @include('patienthistory::content-main.po_inputs')
            @include('patienthistory::content-main.exam')
            @include('patienthistory::content-main.laboratory')
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
