@php
    $segment = Request::segment(1);
    $segment2 = Request::segment(2);
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
                    @if($segment == 'patient')
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">File <i
                                    class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown navbar-scroll">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card" href="{{ route('reset.encounter') }}">Blank form</a>
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
                            <div class="iq-sub-dropdown navbar-scroll">
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
                            <div class="iq-sub-dropdown navbar-scroll">
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

                                    <li>
                                        <a class="iq-sub-card" href="{{ route('neuro') }}" target="_blank">GCS Form</a>
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
                            <div class="iq-sub-dropdown navbar-scroll">
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
                            <div class="iq-sub-dropdown navbar-scroll">
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
                            <div class="iq-sub-dropdown navbar-scroll">
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
                    @endif
                    @if($segment == 'consultation')
                        {{--consult Service Data Consult Report Menu--}}
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">File <i
                                    class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown navbar-scroll">
                                <ul>
                                    <li><a class="iq-sub-card" href="javascript:void(0)"
                                           onclick="consultationReport.SearchEncModal()">Search(EncID)</a></li>
                                    @if($segment == 'consultation' && $segment2 == "")
                                        <li>
                                            <a class="iq-sub-card" href="javascript:void(0)"
                                               onclick="consultationReport.SearchNameModal()">Search(Name)</a>
                                        </li>
                                    @endif
                                </ul>


                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">Summary<i
                                    class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown navbar-scroll">
                                <ul>
                                    <li><a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationSummarizeReportPdf('department')">Department</a></li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationSummarizeReportPdf('billing')">Billing Mode</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationSummarizeReportPdf('consultant')">Consultant</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationSummarizeReportPdf('location')">Location</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationSummarizeReportPdf('status')">Status</a>
                                    </li>
                                </ul>


                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="search-toggle iq-waves-effect language-title" href="#">Date Wise <i
                                    class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown navbar-scroll">
                                <ul>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationDatewiseReportPdf('department')">Department</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationDatewiseReportPdf('billing')">Billing Mode</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationDatewiseReportPdf('consultant')">Consultant</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationDatewiseReportPdf('location')">Location</a>
                                    </li>
                                    <li>
                                        <a class="iq-sub-card" href="javascript:void(0);"
                                           onclick="consultationDatewiseReportPdf('status')">Status</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{--End consult Service Data Consult Report Menu--}}
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
