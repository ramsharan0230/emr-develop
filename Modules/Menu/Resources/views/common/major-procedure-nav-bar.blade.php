@php
$segment = Request::segment(1);
@endphp
<div class="iq-top-navbar second-nav">
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
                        <a class="search-toggle iq-waves-effect language-title" href="#">File <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li> <a class="iq-sub-card" href="{{ route('major.reset.encounter') }}">Blank form</a> </li>
                                <li> <a class="iq-sub-card" href="javascript:void(0)" onclick="fileMenu.patientEncounterModalDisplay()">History</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0);" onclick="requestMenu.majorProcedureModal()">New Procedure</a></li>
                                <li> <a class="iq-sub-card" href="javascript:void(0)" id="yes_no_register" url="{{ route('update.patient.fldadmission') }}">Admission</a></li>
                                <div id="confirm_dialog_box_register"></div>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="fileMenu.LastEncounterMajor()">Last EncID</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Data Entry <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li>
                                    <a class="iq-sub-card" href="javascript:void(0);" onclick="requestMenu.monitoringModal()">Monitoring</a></li>
                                <!-- <a class="iq-sub-card" href="#">Event Timings</a> -->

                                <li> <a class="iq-sub-card" href="javascript:;" onclick="essenseExam.displayModal()">Essen Exams</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Data View <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li> <a class="iq-sub-card" href="{{ Session::has('major_procedure_encounter_id')?route('dataview.laboratory', ['encounter_id' => Session::get('major_procedure_encounter_id')??0,'major procedure'] ): '' }}" target="_blank"> Laboratory</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('major_procedure_encounter_id')?route('dataview.radiology', ['encounter_id' => Session::get('major_procedure_encounter_id')??0,'major procedure'] ): '' }}" target="_blank">Radiology</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('major_procedure_encounter_id')?route('dataview.ot.checklist-report', ['encounter_id' => Session::get('major_procedure_encounter_id')??0,'major procedure'] ): '' }}" target="_blank">OT Checklist</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('major_procedure_encounter_id')?route('dataview.preanaethestic.evaluation.report', ['encounter_id' => Session::get('major_procedure_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Pre-Anaesthetic Evaluation</a></li>
                                <li><a class="iq-sub-card" href="{{ route('outpatient.history.complete', $enpatient->fldencounterval??0) }}?major procedure" target="_blank">Complete</a></li>
                                <li><a class="iq-sub-card" href="{{ route('outpatient.history.generate', $patient->fldpatientval??0) }}?major procedure" target="_blank">AllHistory</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">App Forms<i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li> <a class="iq-sub-card gotopatient" href="javascript:;" url="{{ route('inpatient') }}" type="inpatient" encounter_id="{{$enpatient->fldencounterval??0}}">Inpatient Form</a></li>
                                <!-- <a class="iq-sub-card" href="">Examination</a> -->
                                <li> <a class="iq-sub-card gotopatient" href="javascript:;" url="{{ route('delivery') }}" type="delivery" encounter_id="{{$enpatient->fldencounterval??0}}">Delivery Form</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
