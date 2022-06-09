@php
    $segment = Request::segment(1);
@endphp
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
                        <a class="search-toggle iq-waves-effect language-title" href="#">File <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card" href="{{ route('reset.neuro.encounter') }}">Blank form</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" id="yes_no_register" url="{{ route('update.patient.fldadmission') }}">Admission</a></li>
                                <div id="confirm_dialog_box_register"></div>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="fileMenu.LastEncounterInpatient()">Last EncID</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Request <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0)" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="laboratory.displayModal()" @endif>Laboratory</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0)" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="pharmacy.displayModal()" @endif>Pharmacy</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0)" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="radiology.displayModal()" @endif>Radiology</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="requestMenu.majorProcedureModal()" @endif>Major Procedure</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="requestMenu.extraProcedureModal()" @endif>Extra Procedure</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0)" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="consultation.displayModal()" @endif>Consultation</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="requestMenu.monitoringModal()" @endif>Monitoring</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0)" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="services.displayModal()" @endif>Services</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Data Entry <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="essenseExam.displayModal()" @endif>Essen Exams</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="triageExam.displayModal()" @endif>Triage Exams</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="demographics.displayModal()" @endif>Demographics</a></li>
                                <li><a class="iq-sub-card" href="javascript:;" id="menu-general-image">General images</a></li>
                                @php
                                    $host = \Options::get('pac_server_host');
                                    $port = \Options::get('pac_server_port');
                                    $encID = \Session::get('neuro_encounter_id');
                                    $encshaencryption = sha1($encID);
                                    $finalencryption = \Helpers::GetTextBreakString($encshaencryption);

                                    $url = "http://".$host.":".$port."/app/explorer.html#patient?uuid=".$finalencryption
                                @endphp
                                @if($host !='' and $encID !='' and $port !='')
                                    <li><a class="iq-sub-card" href="{{$url}}" id="menu-pacs-image" target="_blank">PACS images</a>
                                @else
                                    <li><a class="iq-sub-card" href="javascript:void(0)" id="menu-pacs-image" onclick="alert('Update Settings for DICOM in Device Settings');">PACS images</a></li>
                                @endif
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="menuMinorProcedure.displayModal()" @endif>Minor Procedure</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="menuEquipment.displayModal()" @endif>Equipments</a></li>
                                <li><a class="iq-sub-card removeOnclickDischarge" href="javascript:void(0);" @if(Session::has('icu_encounter_id') && $enpatient->fldadmission !== 'Discharged') onclick="dosingRecord.displayModal()" @endif>Med Dosing</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Expenses <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card" href="{{ route('express.getExpressPdf', ['itemtype' => 'Medicine','ipd']) }}" target="_blank">Medicine</a></li>
                                <li><a class="iq-sub-card" href="{{ route('express.getExpressPdf', ['itemtype' => 'Surgicals','ipd']) }}" target="_blank">Surgical</a></li>
                                <li><a class="iq-sub-card" href="{{ route('express.getExpressPdf', ['itemtype' => 'Extra Items','ipd']) }}" target="_blank">Extra Items</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('menu.expenses.laboratory.pdfReport', [Session::get('icu_encounter_id'),'ipd']??0 ): '' }}" target="_blank">Laboratory</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('menu.expenses.radiology.pdfReport', [Session::get('icu_encounter_id'),'ipd']??0 ): '' }}" target="_blank">Radiology</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('menu.expenses.procedures.pdfReport', [Session::get('icu_encounter_id'),'ipd']??0 ): '' }}" target="_blank">Procedures</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('menu.expenses.general.services.pdfReport', [Session::get('icu_encounter_id'),'ipd']??0 ): '' }}" target="_blank">General Services</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('menu.expenses.equipment.pdfReport', [Session::get('icu_encounter_id'),'ipd']??0 ): '' }}" target="_blank">Equipment</a></li>
                                <li><a class="iq-sub-card" href="{{ route('express.getExpressPdf', ['itemtype' => 'Other','ipd']) }}" target="_blank">Other Items</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('menu.expenses.summary.pdfReport', [Session::get('icu_encounter_id'),'ipd']??0 ): '' }}" target="_blank">Summary</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('menu.expenses.invoice.pdfReport', [Session::get('icu_encounter_id'),'ipd']??0 ): '' }}" target="_blank">Invoice</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Data View <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.transitions', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Transitions</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.symtoms', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Symtoms</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.poInputs', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">PO Inputs</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.exams', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Examination</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.laboratory', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Laboratory</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.radiology', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Radiology</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.diagnosis', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Diagnosis</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.notes', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Notes</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.medDosing', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Med Dosing</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.progress', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Progress</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.planning', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Planning</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.medReturn', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Med Return</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')?route('dataview.nurActivity', ['encounter_id' => Session::get('icu_encounter_id')??0,'ipd'] ): '' }}" target="_blank">Nur Activity</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ route('outpatient.history.generate', [$patient->fldpatientval??0,'ipd']) }}" target="_blank">Complete</a></li>
                                <li><a class="iq-sub-card" target="_blank" href="{{ Session::has('icu_encounter_id')? route('patient.menu.bladder.irrigation', [Session::get('icu_encounter_id')] ?? 0 ) :'' }}" target="_blank">Bladder Irrigation</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Outcome <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card" href="javascript:void(0)" id="dischargeModal" data-toggle="modal" data-target="#confirm-box">Discharge</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" id="markLamaModal" data-toggle="modal" data-target="#confirm-box">Mark LAMA</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" id="markReferModal" data-toggle="modal" data-target="#confirm-box">Mark Refer</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" id="markDeathModal" data-toggle="modal" data-target="#confirm-box">Mark Death</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" id="absconderModal" data-toggle="modal" data-target="#confirm-box">Absconder</a></li>
                                </li>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">Certificate <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="essenseExam.displayModal()">Examination</a>
                                <li><a class="iq-sub-card certificates  discharge" data-url="{{ Session::has('icu_encounter_id') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'discharge','ipd']) : '' }}"
                                       href="{{ (Session::has('icu_encounter_id') && $enpatient->fldencounterval && $enpatient->fldadmission == 'Discharged') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'discharge','ipd']) : 'javascript:void(0);' }}" target="_blank">Discharge Paper</a>
                                <li><a class="iq-sub-card certificates  LAMA" data-url="{{ Session::has('icu_encounter_id') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'LAMA','ipd']) : '' }}"
                                       href="{{ (Session::has('icu_encounter_id') && $enpatient->fldencounterval && $enpatient->fldadmission == 'LAMA') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'LAMA','ipd']) : 'javascript:void(0);' }}" target="_blank">LAMA Certificate</a></li>
                                <li><a class="iq-sub-card certificates  Death" data-url="{{ Session::has('icu_encounter_id') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'death','ipd']) : '' }}"
                                       href="{{ (Session::has('icu_encounter_id') && $enpatient->fldencounterval && $enpatient->fldadmission == 'Death') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'death','ipd']) : 'javascript:void(0);' }}" target="_blank">Death Certificate</a></li>
                                <li><a class="iq-sub-card certificates  referral" data-url="{{ Session::has('icu_encounter_id') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'referral','ipd']) : '' }}"
                                       href="{{ (Session::has('icu_encounter_id') && $enpatient->fldencounterval && $enpatient->fldadmission == 'Refer') ? route('certificate.generate', ['id'=>$enpatient->fldencounterval, 'certificate' => 'referral','ipd']) : 'javascript:void(0);' }}" target="_blank">Referral Letter</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="search-toggle iq-waves-effect language-title" href="#">History <i class="ri-arrow-down-s-line"></i></a>
                        <div class="iq-sub-dropdown navbar-scroll">
                            <ul>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="historyNav.encounterModal()">Encounter(local)</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('patient.menu.history.pdf.laboratory', Session::get('icu_encounter_id')??0 ): '' }}?opd" target="_blank">Laboratory(local)</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('patient.menu.history.pdf.radiology', Session::get('icu_encounter_id')??0 ): '' }}?opd" target="_blank">Radiology(local)</a></li>
                                <li><a class="iq-sub-card" href="{{ Session::has('icu_encounter_id')?route('patient.menu.history.pdf.medicine', Session::get('icu_encounter_id')??0 ): '' }}?opd" target="_blank">Medicine(local)</a></li>
                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="historyNav.selectionModal()">Selection(local)</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
