@extends('frontend.layouts.master') 

@section('content')
    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        @php
            $disableClass = 'disableInsertUpdate';
        @endphp
    @else
        @php
            $disableClass = '';
        @endphp
    @endif
    @php
        $segment = Request::segment(1);
        $segment2 = Request::segment(2);
    @endphp

    <style>
       /* .accordion-nav ul li a[aria-expanded="true"]{
            border-bottom:2px solid #fff;
        } */
    
    </style>
    <!-- TOP Nav Bar Start -->

    {{--@include('menu::common.nav-bar')--}}
    <div class="iq-top-navbar second-nav">
        <input type="hidden" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
        <div class="iq-navbar-custom">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button> -->
                <!-- <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-more-fill"></i></div>
                        <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                    </div>
                </div> -->

                <div class="navbar-collapse">
                    <ul class="navbar-nav navbar-list">

                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">File <i
                                        class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card" href="{{ route('physiotherapy.reset.encounter') }}">Blank form</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="fileMenu.waitingModalDisplay()">Waiting</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="fileMenu.searchModalDisplay()">Search</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="fileMenu.LastEncounter()">Last EncID</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">Request <i
                                        class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="laboratory.displayModal()">Laboratory</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="pharmacy.displayModal()">Pharmacy</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="radiology.displayModal()">Radiology</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="consultation.displayModal()">Consultation</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="requestMenu.majorProcedureModal()">Major Procedure</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="requestMenu.extraProcedureModal()">Extra Procedure</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="requestMenu.monitoringModal()">Monitoring</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="services.displayModal()">Services</a>
                                    </li>

                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="admissionRequest.displayModal()">Admission Request</a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">Data Entry <i
                                        class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="triageExam.displayModal()">Triage Exams</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="demographics.displayModal()">Demographics</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="essenseExam.displayModal()">Essen Exams</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:;" id="menu-general-image">General
                                            images</a>
                                    </li>
                                    <li>
                                    @php
                                        $host = \Options::get('pac_server_host');
                                        $port = \Options::get('pac_server_port');
                                        $encID = \Session::get('encounter_id');
                                        $encshaencryption = sha1($encID);
                                        $finalencryption = \Helpers::GetTextBreakString($encshaencryption);

                                        $url = "http://".$host.":".$port."/app/explorer.html#patient?uuid=".$finalencryption
                                    @endphp
                                    <!-- <a class="iq-sub-card" href="javascript:;" id="menu-dicom-image">Dicom images</a> -->
                                        @if($host !='' and $encID !='' and $port !='')
                                            <a class="iq-sub-card" href="{{$url}}" id="menu-pacs-image" target="_blank">PACS
                                                images</a>
                                        @else
                                            <a class="iq-sub-card" href="javascript:void(0)" id="menu-pacs-image"
                                               onclick="alert('Update Settings for DICOM in Device Settings');">PACS
                                                images</a>
                                        @endif
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="menuMinorProcedure.displayModal()">Minor Procedure</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="menuEquipment.displayModal()">Equipments</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="vaccination.displayModal()">Vaccination</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="dosingRecord.displayModal()">Med Dosing</a>
                                    </li>

                                    <li><a class="iq-sub-card" href="{{ route('neuro') }}" target="_blank">GCS Form</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">Data View <i
                                        class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown">
                                <ul>
                                    <li class="inner-submenu">
                                        <a class="iq-sub-card">Laboratory <i class="ri-arrow-right-s-line"></i></a>
                                        <div class="iq-inner-sub-dropdown">

                                            <ul>
                                                <li><a tabindex="-1" href="javascript:void(0);"
                                                       onclick="DataviewMenu.sampleModalDisplay()" class="iq-sub-card">Sample
                                                        Wise</a></li>
                                                <li>
                                                    <a href="{{ Session::has('encounter_id')?route('patient.dataview.pdf.complete', [Session::get('encounter_id'),'opd']??0 ): '' }}"
                                                       target="_blank" class="iq-sub-card">Complete</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card"
                                           href="{{ Session::has('encounter_id')?route('patient.dataview.pdf.examination', [Session::get('encounter_id'),'opd']??0 ): '' }}"
                                           target="_blank">Examination</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card"
                                           href="{{ Session::has('encounter_id')?route('dataview.radiology', ['encounter_id' => Session::get('encounter_id')??0,'opd'] ): '' }}"
                                           target="_blank">Radiology</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">Report <i
                                        class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card"
                                           href="{{ route('outpatient.pdf.generate.opd.sheet', $enpatient->fldencounterval??0) }}?opd"
                                           target="_blank">OPD Sheet</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card"
                                           href="{{ route('outpatient.history.generate', $patient->fldpatientval??0) }}?opd"
                                           target="_blank">Complete</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">Outcome <i
                                        class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)" id="dischargeModal"
                                           data-toggle="modal" data-target="#confirm-box">Discharge</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="outcomeMenu.followupModal()">Follow Up</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="outcomeMenu.refertoModal()">Refer To</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="finish.displayModal()">Finish</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">History <i
                                        class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="historyNav.encounterModal()">Encounter(local)</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card"
                                           href="{{ Session::has('encounter_id')?route('patient.menu.history.pdf.laboratory', Session::get('encounter_id')??0 ): '' }}?opd"
                                           target="_blank">Laboratory(local)</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card"
                                           href="{{ Session::has('encounter_id')?route('patient.menu.history.pdf.radiology', Session::get('encounter_id')??0 ): '' }}?opd"
                                           target="_blank">Radiology(local)</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card"
                                           href="{{ Session::has('encounter_id')?route('patient.menu.history.pdf.medicine', Session::get('encounter_id')??0 ): '' }}?opd"
                                           target="_blank">Medicine(local)</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="historyNav.selectionModal()">Selection(local)</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        {{--<li class="nav-item">--}}
                            {{--<a class="search-toggle iq-waves-effect language-title" href="#">Edit <i--}}
                                        {{--class="ri-arrow-down-s-line"></i></a>--}}
                            {{--<div class="iq-sub-dropdown">--}}
                                {{--<ul>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0)"--}}
                                           {{--onclick="laboratory.displayModal()">Laboratory</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0)"--}}
                                           {{--onclick="pharmacy.displayModal()">Pharmacy</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0)"--}}
                                           {{--onclick="radiology.displayModal()">Radiology</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0)"--}}
                                           {{--onclick="consultation.displayModal()">Consultation</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="requestMenu.majorProcedureModal()">Major Procedure</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="requestMenu.extraProcedureModal()">Extra Procedure</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="requestMenu.monitoringModal()">Monitoring</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0)"--}}
                                           {{--onclick="services.displayModal()">Services</a>--}}
                                    {{--</li>--}}

                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0)"--}}
                                           {{--onclick="admissionRequest.displayModal()">Admission Request</a>--}}
                                    {{--</li>--}}

                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</li>--}}
                        {{--<li class="nav-item">--}}
                            {{--<a class="search-toggle iq-waves-effect language-title" href="#">View <i--}}
                                        {{--class="ri-arrow-down-s-line"></i></a>--}}
                            {{--<div class="iq-sub-dropdown">--}}
                                {{--<ul>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="triageExam.displayModal()">Triage Exams</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="demographics.displayModal()">Demographics</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="essenseExam.displayModal()">Essen Exams</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:;" id="menu-general-image">General--}}
                                            {{--images</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                    {{--@php--}}
                                        {{--$host = \Options::get('pac_server_host');--}}
                                        {{--$port = \Options::get('pac_server_port');--}}
                                        {{--$encID = \Session::get('encounter_id');--}}
                                        {{--$encshaencryption = sha1($encID);--}}
                                        {{--$finalencryption = \Helpers::GetTextBreakString($encshaencryption);--}}

                                        {{--$url = "http://".$host.":".$port."/app/explorer.html#patient?uuid=".$finalencryption--}}
                                    {{--@endphp--}}
                                    {{--<!-- <a class="iq-sub-card" href="javascript:;" id="menu-dicom-image">Dicom images</a> -->--}}
                                        {{--@if($host !='' and $encID !='' and $port !='')--}}
                                            {{--<a class="iq-sub-card" href="{{$url}}" id="menu-pacs-image" target="_blank">PACS--}}
                                                {{--images</a>--}}
                                        {{--@else--}}
                                            {{--<a class="iq-sub-card" href="javascript:void(0)" id="menu-pacs-image"--}}
                                               {{--onclick="alert('Update Settings for DICOM in Device Settings');">PACS--}}
                                                {{--images</a>--}}
                                        {{--@endif--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="menuMinorProcedure.displayModal()">Minor Procedure</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="menuEquipment.displayModal()">Equipments</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="vaccination.displayModal()">Vaccination</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card" href="javascript:void(0);"--}}
                                           {{--onclick="dosingRecord.displayModal()">Med Dosing</a>--}}
                                    {{--</li>--}}

                                    {{--<li><a class="iq-sub-card" href="{{ route('neuro') }}" target="_blank">GCS Form</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</li>--}}
                        {{--<li class="nav-item">--}}
                            {{--<a class="search-toggle iq-waves-effect language-title" href="#">Help <i--}}
                                        {{--class="ri-arrow-down-s-line"></i></a>--}}
                            {{--<div class="iq-sub-dropdown">--}}
                                {{--<ul>--}}
                                    {{--<li class="inner-submenu">--}}
                                        {{--<a class="iq-sub-card">Laboratory <i class="ri-arrow-right-s-line"></i></a>--}}
                                        {{--<div class="iq-inner-sub-dropdown">--}}

                                            {{--<ul>--}}
                                                {{--<li><a tabindex="-1" href="javascript:void(0);"--}}
                                                       {{--onclick="DataviewMenu.sampleModalDisplay()" class="iq-sub-card">Sample--}}
                                                        {{--Wise</a></li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="{{ Session::has('encounter_id')?route('patient.dataview.pdf.complete', [Session::get('encounter_id'),'opd']??0 ): '' }}"--}}
                                                       {{--target="_blank" class="iq-sub-card">Complete</a></li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card"--}}
                                           {{--href="{{ Session::has('encounter_id')?route('patient.dataview.pdf.examination', [Session::get('encounter_id'),'opd']??0 ): '' }}"--}}
                                           {{--target="_blank">Examination</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a class="iq-sub-card"--}}
                                           {{--href="{{ Session::has('encounter_id')?route('dataview.radiology', ['encounter_id' => Session::get('encounter_id')??0,'opd'] ): '' }}"--}}
                                           {{--target="_blank">Radiology</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</li>--}}
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!-- TOP Nav Bar END -->
    <div class="container-fluid">
        <input type="hidden" id="get_content" value="{{ route('get_content') }}">
        <div class="row">
            <!--Here patient profile -->
            @include('frontend.common.patientProfile')

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Vital Exam</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Pulse Rate</label>
                                    <input type="text" class="form-control @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti >=  $pulse->fldhigh) highline @endif  @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti <=  $pulse->fldlow) lowline @endif remove_zero_to_empty" id="pulse_rate" high="@if(isset($pulse_range->fldhigh)){{$pulse_range->fldhigh}}@endif"  low="@if(isset($pulse_range->fldlow)){{$pulse_range->fldlow}}@endif" placeholder="" pulse_rate="Pulse Rate"
                                           value="{{ isset($pulse->fldrepquanti) ?  $pulse->fldrepquanti : 0 }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Syst BP</label>
                                    <input type="text" class="form-control @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti >=  $systolic_bp->fldhigh) highline @endif  @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti <=  $systolic_bp->fldlow) lowline @endif remove_zero_to_empty" id="sys_bp" high="@if(isset($systolic_bp_range->fldhigh)){{$systolic_bp_range->fldhigh}}@endif"  low="@if(isset($systolic_bp_range->fldlow)){{$systolic_bp_range->fldlow}}@endif" placeholder="" sys_bp="Systolic BP"
                                           value="{{ isset($systolic_bp->fldrepquanti) ?  $systolic_bp->fldrepquanti : 0  }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Diast BP</label>
                                    <input type="text" class="form-control @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti >=  $diasioli_bp->fldhigh) highline @endif  @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti <=  $diasioli_bp->fldlow) lowline @endif remove_zero_to_empty " id="dia_bp" high="@if(isset($diasioli_bp_range->fldhigh)){{$diasioli_bp_range->fldhigh}}@endif"  low="@if(isset($diasioli_bp_range->fldlow)){{$diasioli_bp_range->fldlow}}@endif" placeholder="" dia_bp="Diastolic BP"
                                           value="{{ isset($diasioli_bp->fldrepquanti) ? $diasioli_bp->fldrepquanti : 0  }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Resp Rate</label>
                                    <input type="text" class="form-control @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti >=  $respiratory_rate->fldhigh) highline @endif  @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti <=  $respiratory_rate->fldlow) lowline @endif remove_zero_to_empty" id="respi" high="@if(isset($respiratory_rate_range->fldhigh)){{$respiratory_rate_range->fldhigh}}@endif"  low="@if(isset($respiratory_rate_range->fldlow)){{$respiratory_rate_range->fldlow}}@endif" placeholder="" respi="Respiratory Rate"
                                           value="{{ isset($respiratory_rate->fldrepquanti) ? $respiratory_rate->fldrepquanti : 0 }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">S P O2</label>
                                    <input type="text" class="form-control @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti >=  $o2_saturation->fldhigh) highline @endif  @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti <=  $o2_saturation->fldlow) lowline @endif remove_zero_to_empty" id="saturation"  high="@if(isset($o2_saturation_range->fldhigh)){{$o2_saturation_range->fldhigh}}@endif"  low="@if(isset($o2_saturation_range->fldlow)){{$o2_saturation_range->fldlow}}@endif" placeholder="" saturation="O2 Saturation"
                                           value="{{ isset($o2_saturation->fldrepquanti) ? $o2_saturation->fldrepquanti : 0 }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Temp</label>
                                    <input type="text" class="form-control @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti >=  $temperature->fldhigh) highline @endif  @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti <=  $temperature->fldlow) lowline @endif remove_zero_to_empty" id="pulse_rate_rate" high="@if(isset($temperature_range->fldhigh)){{$temperature_range->fldhigh}}@endif"  low="@if(isset($temperature_range->fldlow)){{$temperature_range->fldlow}}@endif" placeholder="" pulse_rate_rate="Temperature (F)"
                                           value="{{ isset($temperature->fldrepquanti) ?  $temperature->fldrepquanti : 0 }}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <input type="hidden" id="check_vital" url="{{route('check_vital')}}">
                            <a href="javascript:;" class="btn btn-primary rounded-pill {{$disableClass}}" type="button" url="{{ route('insert_essential_exam') }}" id="save_essential">

                                Vital Save
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>


      

    <div class="iq-card">
        <div class="iq-card-body">
            <div id="accordion">
                <div class="accordion-nav">
                    <ul>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#complaints" aria-expanded="true"
                                aria-controls="collapseOne">Complaints</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#history" aria-expanded="false"
                                aria-controls="collapseOne">History</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#findings" aria-expanded="false" aria-controls="collapseOne">Findings</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#essentials_examination" aria-expanded="false" aria-controls="collapseOne">Essentials Examinations</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#special_test" aria-expanded="false" aria-controls="collapseOne">Special Test</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#diagnosis" aria-expanded="false" aria-controls="collapseOne">Diagnosis</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#treatment" aria-expanded="false" aria-controls="collapseOne">Treatment</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#other_modalities" aria-expanded="false"
                                aria-controls="collapseOne">Other Modalities</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#therapeutic_excercises" aria-expanded="false"
                                aria-controls="collapseOne">Therapeutic Excercises</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#advices" aria-expanded="false"
                                aria-controls="collapseOne">Advices</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#next_assessment" aria-expanded="false" aria-controls="collapseOne">Next Assessment</a>
                        </li>
                    </ul>
                </div>
                @include('physiotherapy::layouts.menus.complaints')
                @include('physiotherapy::layouts.menus.history')
                @include('physiotherapy::layouts.menus.findings')
                @include('physiotherapy::layouts.menus.essentials_examinations')
                @include('physiotherapy::layouts.menus.special_test')
                @include('physiotherapy::layouts.menus.diagnosis')
                @include('physiotherapy::layouts.menus.treatment')
                @include('physiotherapy::layouts.menus.other_modalities')
                @include('physiotherapy::layouts.menus.therapeutic_exercises')
                @include('physiotherapy::layouts.menus.advices')
                @include('physiotherapy::layouts.menus.next_assessment')
                @include('physiotherapy::layouts.modal.provisional-diagnosis-freetext-modal')
                @include('physiotherapy::layouts.modal.final-diagnosis-freetext-modal')
                @include('physiotherapy::layouts.modal.past-diagnosis-freetext-modal')
                @include('physiotherapy::layouts.modal.provisional-diagnosis-icd-modal')
                {{--@include('physiotherapy::layouts.modal.final-diagnosis-icd-modal')--}}
                @include('physiotherapy::layouts.modal.provisional-obstetric-modal')
                {{--@include('physiotherapy::layouts.modal.final-obstetric-modal')--}}
            </div>
        </div>
    </div>
    

    <div class="form-row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="d-flex justify-content-around">
                            <a href="javascript:void(0)" onclick="laboratory.displayModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Laboratory
                            </a>
                            <a href="javascript:void(0)" onclick="radiology.displayModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Radiology
                            </a>

                            <a href="javascript:void(0)" onclick="pharmacy.displayModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Pharmacy
                            </a>
                            <a href="javascript:void(0);" onclick="requestMenu.majorProcedureModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Procedure
                            </a>
                            <a href="{{ route('outpatient.history.generate', $patient->fldpatientval??0) }}?opd" target="_blank" class="btn btn-primary btn-action {{ $disableClass }}">History
                            </a>
                            <a @if(isset($enpatient)) href="{{ route('outpatient.pdf.generate.opd.sheet', $enpatient->fldencounterval??0) }}?opd" target="_blank" @else href="#" @endif class="btn btn-primary btn-action  {{ $disableClass }}">OPD Sheet
                            </a>
                            <a href="{{ route('physiotherapy.reset.encounter') }}" onclick="return checkFormEmpty();" class="btn btn-primary btn-action  {{ $disableClass }}">Save
                            </a>
                            <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish" class="btn btn-primary btn-action  {{ $disableClass }}">Finish
                            </a>
                            <a href="#" data-toggle="modal" data-target=""  class="btn btn-primary btn-action">Preview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @include('outpatient::modal.lnr-boxLabel-modal')
    {{--@include('outpatient::modal.text-boxLabel-modal')--}}
    @include('outpatient::modal.scale-boxLabel-modal')
    @include('outpatient::modal.number-boxLabel-modal')
    @include('outpatient::modal.single-selection-box-modal')

    @include('outpatient::modal.laboratory-radiology-modal')
    @include('outpatient::modal.diagnosis-freetext-modal')
    @include('outpatient::modal.allergy-freetext-modal')

    @include('outpatient::modal.diagnosis-obstetric-modal')
    @include('outpatient::modal.opd-history-modal')


        {{--@include('inpatient::layouts.menus.triage')--}}
        {{--@include('inpatient::layouts.menus.demographics', ['module' => 'opd'])--}}
        {{--@include('inpatient::layouts.menus.patient-image')--}}

        @include('physiotherapy::layouts.modal.finish-boxLabel-model')
    </div>

@stop
@push('after-script')

    <script type="text/javascript">
        $(document).ready(function() {
            CKEDITOR.replace('complaints_textarea',
                {
                    height: '150px',
                });

            CKEDITOR.replace('history_textarea',
                {
                    height: '150px',
                });

            CKEDITOR.replace('findings_textarea',
                {
                    height: '150px',
                });

            CKEDITOR.replace('special_test_textarea',
                {
                    height: '150px',
                });

            CKEDITOR.replace('advices_textarea',
                {
                    height: '150px',
                });
            CKEDITOR.replace('other_modalities_textarea',
                {
                    height: '150px',
                });
            CKEDITOR.replace('therapeutic_exercises_textarea',
                {
                    height: '150px',
                });

            <?php /*?>
            var requestMenu = {
                majorProcedureModal: function () {
                    $('form').submit(false);
                    $('.file-form-data').empty();
                    $('.file-modal-title').text('Major Procedure');
                    $('#size').removeClass('modal-dialog modal-xl');
                    $('#size').addClass('modal-dialog modal-lg');
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }
                    $.ajax({
                        url: '<?php echo e(route('patient.request.menu.majorprocedure')); ?>',
                        type: "POST",
                        data: {encounterId: $('#encounter_id').val()},
                        success: function (response) {
                            // console.log(response);
                            $('.file-form-data').html(response);
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                    $('#file-modal').modal('show');
                },
                monitoringModal: function () {
                    $('.file-form-data').empty();
                    $('.file-modal-title').text('Monitoring');
                    $('#size').removeClass('modal-dialog modal-xl');
                    $('#size').addClass('modal-dialog modal-lg');
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }
                    $.ajax({
                        url: '<?php echo e(route('patient.request.menu.monitoring')); ?>',
                        type: "POST",
                        data: {encounterId: $('#encounter_id').val()},
                        success: function (response) {
                            // console.log(response);
                            $('.file-form-data').html(response);
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                    $('#file-modal').modal('show');
                },

                extraProcedureModal: function () {
                    $('.file-form-data').empty();
                    $('.file-modal-title').text('Extra Procedure');
                    $('#size').removeClass('modal-dialog modal-xl');
                    $('#size').addClass('modal-dialog modal-lg');
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }
                    $.ajax({
                        url: '<?php echo e(route('patient.request.menu.extraprocedure')); ?>',
                        type: "POST",
                        data: {encounterId: $('#encounter_id').val()},
                        success: function (response) {
                            // console.log(response);
                            $('.file-form-data').html(response);
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                    $('#file-modal').modal('show');
                },

            }

            var finish = {
                displayModal: function () {
                    // if($('encounter_id').val() == 0)
                    // alert($('#encounter_id').val());
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.', 'error');
                        return false;
                    }

                    $('#finish_box').modal('show');
                },
            }
            <?php */?>

            // saving vital examination essentials

            $("#save_essential").on("click", function () {
                // console.log('here');
                var pulse_rate = $("#pulse_rate").attr("pulse_rate") + ":" + $("#pulse_rate").val();
                var sys_bp = $("#sys_bp").attr("sys_bp") + ":" + $("#sys_bp").val();
                var dia_bp = $("#dia_bp").attr("dia_bp") + ":" + $("#dia_bp").val();
                var respi = $("#respi").attr("respi") + ":" + $("#respi").val();
                var saturation =
                    $("#saturation").attr("saturation") + ":" + $("#saturation").val();
                var pulse_rate_rate =
                    $("#pulse_rate_rate").attr("pulse_rate_rate") +
                    ":" +
                    $("#pulse_rate_rate").val();
                var fldencounterval = $("#fldencounterval").val();
                var flduserid = $("#flduserid").val();
                var fldcomp = $("#fldcomp").val();

                var url = $(this).attr("url");
                var formData = {
                    fldencounterval: fldencounterval,
                    flduserid: flduserid,
                    fldcomp: fldcomp,
                    "essential[]": [
                        pulse_rate,
                        sys_bp,
                        dia_bp,
                        respi,
                        saturation,
                        pulse_rate_rate
                    ]
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert("Information saved!!");
                            $('#pulse_rate_rate').val(null);
                            $('#pulse_rate').val(null);
                            $('#sys_bp').val(null);
                            $('#respi').val(null);
                            $('#saturation').val(null);
                            $('#grbs').val(null);
                            $('#dia_bp').val(null);

                            $('#sys_bp').removeClass('highline');
                            $('#sys_bp').removeClass('lowline');
                            $('#dia_bp').removeClass('highline');
                            $('#dia_bp').removeClass('lowline');
                            $('#pulse_rate').removeClass('highline');
                            $('#pulse_rate').removeClass('lowline');
                            $('#pulse_rate_rate').removeClass('highline');
                            $('#pulse_rate_rate').removeClass('lowline');
                            $('#respi').removeClass('highline');
                            $('#respi').removeClass('lowline');
                            $('#saturation').removeClass('highline');
                            $('#saturation').removeClass('lowline');
                            $('#grbs').removeClass('highline');
                            $('#grbs').removeClass('lowline');
                            $.get('essential_exam/get_essential_exam?fldencounterval=' + fldencounterval, function (data) {
                                // console.log(data);
                                $.each(data, function (index, getValue) {
                                    if (index == 'systolic_bp') {
                                        $('#sys_bp').val(getValue.fldrepquanti);
                                        if (getValue.fldrepquanti >= getValue.fldhigh) {
                                            $('#sys_bp').addClass('highline');
                                        }
                                        if (getValue.fldrepquanti <= getValue.fldlow) {
                                            $('#sys_bp').addClass('lowline');
                                        }
                                    }

                                    if (index == 'diasioli_bp') {
                                        $('#dia_bp').val(getValue.fldrepquanti);
                                        if (getValue.fldrepquanti >= getValue.fldhigh) {
                                            $('#dia_bp').addClass('highline');
                                        }
                                        if (getValue.fldrepquanti <= getValue.fldlow) {
                                            $('#dia_bp').addClass('lowline');
                                        }
                                    }

                                    if (index == 'pulse') {
                                        $('#pulse_rate').val(getValue.fldrepquanti);
                                        if (getValue.fldrepquanti >= getValue.fldhigh) {
                                            $('#pulse_rate').addClass('highline');
                                        }
                                        if (getValue.fldrepquanti <= getValue.fldlow) {
                                            $('#pulse_rate').addClass('lowline');
                                        }
                                    }

                                    if (index == 'temperature') {
                                        $('#pulse_rate_rate').val(getValue.fldrepquanti);
                                        if (getValue.fldrepquanti >= getValue.fldhigh) {
                                            $('#pulse_rate_rate').addClass('highline');
                                        }
                                        if (getValue.fldrepquanti <= getValue.fldlow) {
                                            $('#pulse_rate_rate').addClass('lowline');
                                        }
                                    }

                                    if (index == 'respiratory_rate') {
                                        $('#respi').val(getValue.fldrepquanti);
                                        if (getValue.fldrepquanti >= getValue.fldhigh) {
                                            $('#respi').addClass('highline');
                                        }
                                        if (getValue.fldrepquanti <= getValue.fldlow) {
                                            $('#respi').addClass('lowline');
                                        }
                                    }

                                    if (index == 'o2_saturation') {
                                        $('#saturation').val(getValue.fldrepquanti);
                                        if (getValue.fldrepquanti >= getValue.fldhigh) {
                                            $('#saturation').addClass('highline');
                                        }
                                        if (getValue.fldrepquanti <= getValue.fldlow) {
                                            $('#saturation').addClass('lowline');
                                        }
                                    }

                                    // if(index == 'grbs'){
                                    //     $('#grbs').val(getValue.fldrepquanti);
                                    //     if(getValue.fldrepquanti >= getValue.fldhigh){
                                    //         $('#grbs').addClass('highline');
                                    //     }
                                    //     if(getValue.fldrepquanti <= getValue.fldlow){
                                    //         $('#grbs').addClass('lowline');
                                    //     }
                                    // }
                                });
                            });
                            //location.reload();
                        } else {
                            showAlert("Something went wrong!!", 'error');
                        }
                    }
                });
            });

            $(".remove_zero_to_empty").on("focusin", function () {
                var current_val = $(this).val();
                if (current_val == 0) {
                    $(this).val(null);
                }
            });

            // end of vital examination saving

            $('#js-complaints-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.complaints_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#complaints_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-history-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.history_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#history_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-findings-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.findings_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#findings_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-special-test-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.special_test_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#special_test_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-special-test-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.special_test_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#special_test_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-other-modalities-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.other_modalities_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#other_modalities_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-therapeutic-exercises-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.therapeutic_exercises_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#therapeutic_exercises_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-advices-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "Physiotherapy";
                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = CKEDITOR.instances.advices_textarea.getData();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#advices_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            $('#js-next-assessment-add-btn').on('click', function (e) {
                e.preventDefault();

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var flditem = "physiotherapy";

                // var fldreportquali = $('.note__fldreportquali').val();
                var flddetail = $('#next_assessment_textarea').val();
                var flduserid = $('#flduserid').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var followupdate = $("#followup_date").val();

                var formData = {
                    "fldencounterval": fldencounterval,
                    "flditem": flditem,
                    "flddetail": flddetail,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                    "date": followupdate,
                };
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            // $("#next_assessment_textarea").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            Provisionaldiagnosisfreetext = {
                displayModal: function () {
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }

                    $('#provisional-diagnosis-freetext-modal').modal('show');
                },
            };

            Finaldiagnosisfreetext = {
                displayModal: function () {
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }

                    $('#final-diagnosis-freetext-modal').modal('show');
                },
            };

            Pastdiagnosisfreetext = {
                displayModal: function () {
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }
                    $('#past-diagnosis-freetext-modal').modal('show');
                },
            };

            $("#delete__provisional_item").click(function (e) {
                e.preventDefault();
                if (confirm('Are you sure?')) {
                    // alert($("#pat_findings_delete").val());
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }
                    $('#provisional_delete').each(function () {
                        // alval = [];
                        var finalval = $(this).val().toString();
                        // alert(finalval);

                        // alert(finalval);
                        var diagnosistypemessage = "Provisional Diagnosis deleted successfully";
                        $.ajax({
                            url: '{{ route("physiotherapy.diagnosis.delete") }}',
                            type: "POST",
                            dataType: "json",
                            data: {ids: finalval, diagnosistypemessage: diagnosistypemessage},
                            success: function (data) {
                                // console.log(data);
                                if ($.isEmptyObject(data.error)) {
                                    $('#provisional_delete option:selected').remove();
                                    showAlert(data.delete_success_message);


                                } else {
                                    showAlert('Something went wrong!!', 'error');
                                }
                            }
                        });
                    });

                }
            });

            $("#delete__final_item").click(function (e) {
                e.preventDefault();
                if (confirm('Are you sure?')) {
                    // alert($("#pat_findings_delete").val());
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }
                    $('#final_delete').each(function () {
                        // alval = [];
                        var finalval = $(this).val().toString();
                        // alert(finalval);

                        // alert(finalval);
                        var diagnosistypemessage = "Final Diagnosis deleted successfully";
                        $.ajax({
                            url: '{{ route("physiotherapy.diagnosis.delete") }}',
                            type: "POST",
                            dataType: "json",
                            data: {ids: finalval, diagnosistypemessage: diagnosistypemessage},
                            success: function (data) {
                                // console.log(data);
                                if ($.isEmptyObject(data.error)) {
                                    $('#final_delete option:selected').remove();
                                    showAlert(data.delete_success_message);


                                } else {
                                    showAlert('Something went wrong!!', 'error');
                                }
                            }
                        });
                    });

                }
            });

            $("#delete__past_item").click(function (e) {
                e.preventDefault();
                if (confirm('Are you sure?')) {
                    // alert($("#pat_findings_delete").val());
                    if ($('#encounter_id').val() == "") {
                        alert('Please select encounter id.');
                        return false;
                    }
                    $('#past_delete').each(function () {
                        // alval = [];
                        var finalval = $(this).val().toString();
                        // alert(finalval);

                        // alert(finalval);
                        var diagnosistypemessage = "Final Diagnosis deleted successfully";
                        $.ajax({
                            url: '{{ route("physiotherapy.diagnosis.delete") }}',
                            type: "POST",
                            dataType: "json",
                            data: {ids: finalval, diagnosistypemessage: diagnosistypemessage},
                            success: function (data) {
                                // console.log(data);
                                if ($.isEmptyObject(data.error)) {
                                    $('#past_delete option:selected').remove();
                                    showAlert(data.delete_success_message);
                                } else {
                                    showAlert('Something went wrong!!', 'error');
                                }
                            }
                        });
                    });

                }
            });

            $("#js-treatment-add-btn").click(function () {

                if ($('#encounter_id').val() == '') {
                    alert('Please select encounter id.');
                    return false;
                }
                var fldencounterval = $('#fldencounterval').val();
                var ust_mode = $('#ust_mode').val();
                var ust_frequency = $('#ust_frequency').val();
                var ust_intensity = $('#ust_intensity').val();
                var ust_time = $('#ust_time').val();
                var ust_site = $('#ust_site').val();
                var ust_days = $('#ust_days').val();
                var tens_mode = $('#tens_mode').val();
                var tens_frequency = $('#tens_frequency').val();
                var tens_time = $('#tens_time').val();
                var tens_site = $('#tens_site').val();
                var tens_days = $('#tens_days').val();
                var ust_channel = $('#ust_channel').val();
                var ift_mode = $('#ift_mode').val();
                var ift_site = $('#ift_site').val();
                var ift_program_selection = $('#ift_program_selection').val();
                var ift_treatment_mode = $('#ift_treatment_mode').val();
                var ift_frequency = $('#ift_frequency').val();
                var ift_time = $('#ift_time').val();
                var ift_days = $('#ift_days').val();
                var traction_mode = $('#traction_mode').val();
                var traction_hold_time = $('#traction_hold_time').val();
                var traction_rest_time = $('#traction_rest_time').val();
                var traction_weight = $('#traction_weight').val();
                var traction_types = $('#traction_types').val();
                var tracttion_time = $('#tracttion_time').val();
                var traction_days = $('#traction_days').val();
                var ems_mode = $('#ems_mode').val();
                var ems_intensity = $('#ems_intensity').val();
                var ems_pulse_duration = $('#ems_pulse_duration').val();
                var ems_surge_seconds = $('#ems_surge_seconds').val();
                var ems_site = $('#ems_site').val();
                var ems_days = $('#ems_days').val();
                var irr_time = $('#irr_time').val();
                var irr_site = $('#irr_site').val();
                var irr_days = $('#irr_days').val();
                var swd_application_mode = $('#swd_application_mode').val();
                var swd_frequency = $('#swd_frequency').val();
                var swd_time = $('#swd_time').val();
                var swd_days = $('#swd_days').val();
                var md_frequency = $('#md_frequency').val();
                var md_intensity = $('#md_intensity').val();
                var md_time = $('#md_time').val();
                var md_site = $('#md_site').val();
                var md_days = $('#md_days').val();
                var wax_bath_methods = $('#wax_bath_methods').val();
                var wax_bath_time = $('#wax_bath_time').val();
                var wax_bath_site = $('#wax_bath_site').val();
                var wax_bath_days = $('#wax_bath_days').val();
                var moist_head_pack_time = $('#moist_head_pack_time').val();
                var moist_head_pack_site = $('#moist_head_pack_site').val();
                var moist_head_pack_days = $('#moist_head_pack_days').val();
                var cryotherapy_temperature = $('#cryotherapy_temperature').val();
                var cryotherapy_time = $('#cryotherapy_time').val();
                var cryotherapy_site = $('#cryotherapy_site').val();
                var cryotherapy_days = $('#cryotherapy_days').val();
                var laser_program_selection = $('#laser_program_selection').val();
                var laser_time = $('#laser_time').val();
                var laser_site = $('#laser_site').val();
                var laser_days = $('#laser_days').val();
                var ecswt_site = $('#ecswt_site').val();
                var ecswt_energy_flux_density = $('#ecswt_energy_flux_density').val();
                var ecswt_frequency = $('#ecswt_frequency').val();
                var ecswt_session = $('#ecswt_session').val();
                var fldcomp = $('#fldcomp').val();
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('url');
                var formData = {
                    'fldencounterval': fldencounterval,
                    'ust_mode': ust_mode,
                    'ust_frequency': ust_frequency,
                    'ust_intensity': ust_intensity,
                    'ust_time': ust_time,
                    'ust_site': ust_site,
                    'ust_days': ust_days,
                    'tens_mode': tens_mode,
                    'tens_frequency': tens_frequency,
                    'tens_time': tens_time,
                    'tens_site': tens_site,
                    'tens_days': tens_days,
                    'ust_channel': ust_channel,
                    'ift_mode': ift_mode,
                    'ift_site': ift_site,
                    'ift_program_selection': ift_program_selection,
                    'ift_treatment_mode': ift_treatment_mode,
                    'ift_frequency': ift_frequency,
                    'ift_time': ift_time,
                    'ift_days': ift_days,
                    'traction_mode': traction_mode,
                    'traction_hold_time': traction_hold_time,
                    'traction_rest_time': traction_rest_time,
                    'traction_weight': traction_weight,
                    'traction_types': traction_types,
                    'tracttion_time': tracttion_time,
                    'traction_days': traction_days,
                    'ems_mode': ems_mode,
                    'ems_intensity': ems_intensity,
                    'ems_pulse_duration': ems_pulse_duration,
                    'ems_surge_seconds': ems_surge_seconds,
                    'ems_site': ems_site,
                    'ems_days': ems_days,
                    'irr_time': irr_time,
                    'irr_site': irr_site,
                    'irr_days': irr_days,
                    'swd_application_mode': swd_application_mode,
                    'swd_frequency': swd_frequency,
                    'swd_time': swd_time,
                    'swd_days': swd_days,
                    'md_frequency': md_frequency,
                    'md_intensity': md_intensity,
                    'md_time': md_time,
                    'md_site': md_site,
                    'md_days': md_days,
                    'wax_bath_methods': wax_bath_methods,
                    'wax_bath_time': wax_bath_time,
                    'wax_bath_site': wax_bath_site,
                    'wax_bath_days': wax_bath_days,
                    'moist_head_pack_time': moist_head_pack_time,
                    'moist_head_pack_site': moist_head_pack_site,
                    'moist_head_pack_days': moist_head_pack_days,
                    'cryotherapy_temperature': cryotherapy_temperature,
                    'cryotherapy_time': cryotherapy_time,
                    'cryotherapy_site': cryotherapy_site,
                    'cryotherapy_days': cryotherapy_days,
                    'laser_program_selection': laser_program_selection,
                    'laser_time': laser_time,
                    'laser_site': laser_site,
                    'laser_days': laser_days,
                    'ecswt_site': ecswt_site,
                    'ecswt_energy_flux_density': ecswt_energy_flux_density,
                    'ecswt_frequency': ecswt_frequency,
                    'ecswt_session': ecswt_session,
                    'fldcomp': fldcomp
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Inserted Successfully');
                            // getTodayNoteList(fldencounterval);
                            $("#ust_mode").val(null);
                            $("#ust_frequency").val(null);
                            $("#ust_intensity").val(null);
                            $("#ust_time").val(null);
                            $("#ust_site").val(null);
                            $("#ust_days").val(null);
                            $("#tens_mode").val(null);
                            $("#tens_frequency").val(null);
                            $("#tens_time").val(null);
                            $("#tens_site").val(null);
                            $("#ust_channel").val(null);
                            $("#ift_mode").val(null);
                            $("#ift_site").val(null);
                            $("#tens_site").val(null);
                            $("#tens_site").val(null);
                            $("#ift_program_selection").val(null);
                            $("#ift_treatment_mode").val(null);
                            $("#ift_frequency").val(null);
                            $("#ift_time").val(null);
                            $("#ift_days").val(null);
                            $("#traction_mode").val(null);
                            $("#traction_hold_time").val(null);
                            $("#traction_rest_time").val(null);
                            $("#traction_weight").val(null);
                            $("#traction_types").val(null);
                            $("#tracttion_time").val(null);
                            $("#traction_days").val(null);
                            $("#ems_mode").val(null);
                            $("#ems_modems_intensity").val(null);
                            $("#ems_pulse_duration").val(null);
                            $("#ems_surge_seconds").val(null);
                            $("#ems_site").val(null);
                            $("#ems_days").val(null);
                            $("#irr_time").val(null);
                            $("#irr_site").val(null);
                            $("#irr_days").val(null);
                            $("#swd_application_mode").val(null);
                            $("#swd_frequency").val(null);
                            $("#swd_time").val(null);
                            $("#swd_days").val(null);
                            $("#md_frequency").val(null);
                            $("#md_intensity").val(null);
                            $("#md_time").val(null);
                            $("#md_site").val(null);
                            $("#md_days").val(null);
                            $("#wax_bath_methods").val(null);
                            $("#wax_bath_time").val(null);
                            $("#wax_bath_site").val(null);
                            $("#wax_bath_days").val(null);
                            $("#moist_head_pack_time").val(null);
                            $("#moist_head_pack_site").val(null);
                            $("#moist_head_pack_days").val(null);
                            $("#cryotherapy_temperature").val(null);
                            $("#cryotherapy_time").val(null);
                            $("#cryotherapy_site").val(null);
                            $("#cryotherapy_days").val(null);
                            $("#laser_time").val(null);
                            $("#laser_site").val(null);
                            $("#laser_days").val(null);
                            $("#ecswt_site").val(null);
                            $("#ecswt_energy_flux_density").val(null);
                            $("#ecswt_frequency").val(null);
                            $("#ecswt_session").val(null);
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });

            });
        });

    </script>

@endpush
