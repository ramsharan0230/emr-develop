@php
$segment = Request::segment(1);
$segment2 = Request::segment(2);
@endphp
<div class="iq-top-navbar second-nav">
    <input type="hidden" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif " />
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
                                <li><a class="iq-sub-card" href="{{ route('delivery.reset.encounter') }}">Blank form</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="fileMenu.patientEncounterModalDisplay()">History</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" id="yes_no_register" url="{{ route('update.patient.fldadmission') }}">Admission</a></li>
                                <div id="confirm_dialog_box_register"></div>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="fileMenu.LastEncounterDelivery()">Last EncID</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Request <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li> <a class="iq-sub-card" href="javascript:void(0)" onclick="laboratory.displayModal()">Laboratory</a></li>

                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="radiology.displayModal()">Radiology</a></li>

                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="consultation.displayModal()">Consultation</a></li>

                                <li> <a class="iq-sub-card" href="javascript:void(0);" onclick="requestMenu.monitoringModal()">Monitoring</a></li>
                                <li> <a class="iq-sub-card" href="javascript:void(0);" onclick="requestMenu.majorProcedureModal()">Procedure Plan</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="services.displayModal()">Services</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Data Entry <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li> <a class="iq-sub-card" href="javascript:;" class="disableInsertUpdate" data-toggle="modal" data-target="#obstetricdiagnosis" onclick="obstetric.displayModal()">Diagnosis</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="demographics.displayModal()">Demographics</a></li>
                                <!-- <a class="iq-sub-card" href="#">Event Timings</a> -->
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="essenseExam.displayModal()">Essen Exams</a></li>
                                <li><a class="iq-sub-card" href="{{ route('neuro') }}" target="_blank">GCS Form</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Data View <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li> <a class="iq-sub-card" target="_blank" href="{{ Session::has('delivery_encounter_id')?route('dataview.laboratory', ['encounter_id' => Session::get('delivery_encounter_id')??0] ): '' }}" target="_blank">Laboratory</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('delivery_encounter_id')?route('dataview.radiology', ['encounter_id' => Session::get('delivery_encounter_id')??0] ): '' }}" target="_blank">Radiology</a></li>

                                <li><a class="iq-sub-card" href="{{ route('dataview.menu.delivery', $enpatient->fldencounterval??0) }}" target="_blank">Delivery</a></li>
                                <li><a class="iq-sub-card" href="{{ route('outpatient.history.complete', $enpatient->fldencounterval??0) }}" target="_blank">Complete</a></li>
                                <li><a class="iq-sub-card" href="{{ route('outpatient.history.generate', $patient->fldpatientval??0) }}" target="_blank">AllHistory</a></li>
                            </ul>
                        </div>
                    </li>


                </ul>
            </div>
        </nav>
    </div>
</div>
