<style type="text/css">
    .th-style {
        padding: 10px;
        border: 1px solid;
    }
    .td-style {
        padding: 10px;
        border: 1px solid;
    }
    .th-bak {
        background-color: #d8d7d7;
    }
    .div-table {
        margin-top: 20px;
        margin: 0 auto;
        width: 95%;
    }
    .table-first {
        width: 95%;
    }
    .table-second {
        width: 95%;
    }
    .th-head {
        width: 13%;
    }
     h3 h2 {
        margin: 15px 0px 2px 0px;
    }
    .table-break2{width: 100%;}
    @media print {
       .table-break{page-break-before: always;}
       .table-break2{page-break-before: always;}
       .th-bak {-webkit-print-color-adjust: exact;}
       .table-bak{-webkit-print-color-adjust: exact;}
    }
</style>
<div class="pdf-container">

    @php
        $patientInfo = $encounterData->patientInfo;
        $iterationCount = 1;
    @endphp
    @include('pdf-header-footer.header-footer')
    <div class="table" style="margin-bottom: 20px;">
        <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table-bak" style="margin-top: 20px; margin: 0 auto; background-color: #d8d7d7;">
            <thead>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px; width: 53%;">Name :  {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Enc ID : {{ $encounterId }}</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Age/Sex : {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</th>
                {{-- <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Age/Sex : {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</th> --}}
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Address : {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</th>
            </tr>
             <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Admission Date : {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}</th>
                <th colspan="2" style="padding: 6px; border: none; text-align: left; font-size: 14px;">Discharge Date : {{ $encounterData->flddod ? \Carbon\Carbon::parse($encounterData->flddod)->format('d/m/Y'):'' }}</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Relation :</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Bed No :</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Speciality :</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Phone : </th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">&nbsp;</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">{!! Helpers::generateQrCode($encounterId)!!}</th>
            </tr>

            </thead>
        </table>
        @if(isset($CourseofTreatment) && count($CourseofTreatment))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Course of Treatment</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($CourseofTreatment as $symptoms)
                                <p>{{ $symptoms->fldtime }}: {{ $symptoms->fldcomment }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($bed) && count($bed))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Bed Transitions</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($bed as $b)
                                <p>{{ $b->flditem }}</p>
                                <p>{{ $b->fldfirsttime }}</p>
                                <p>{{ $b->fldsecondtime }}</p>
                                <p>{{ $b->fldsecondreport }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($EssentialExaminations) && count($EssentialExaminations))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">OPD Examinations</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @for($i = 0; $i<count($EssentialExaminations['fldhead']); $i++)
                                <p>{{ $EssentialExaminations['fldhead'][$i] }}{{ !is_array($EssentialExaminations['fldrepquali'][$i]) ? ':' .$EssentialExaminations['fldrepquali'][$i] :''}}</p>
                                @if(is_array($EssentialExaminations['fldrepquali'][$i]) && count($EssentialExaminations['fldrepquali'][$i])>1)
                                    <table border="1px" rules="all" style="width: 60%;">
                                        <tr>
                                            @foreach($EssentialExaminations['fldrepquali'][$i] as $row => $val)

                                                <th>{{ $row }}</th>

                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($EssentialExaminations['fldrepquali'][$i] as $row => $val)
                                                <td>{{ $val }}</td>
                                            @endforeach
                                        </tr>
                                    </table>
                                @endif
                            @endfor
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($demographics) && count($demographics))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Demographics</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($demographics as $b)
                                <p>{{ $b->flditem }}</p>
                                <p>Date:{{ Carbon\Carbon::parse($b->fldfirsttime)->format('Y/m/d l h:i:s') }} </p>
                                <p>{{ $b->fldreportquali }}</p>
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($triage_examinations) && count($triage_examinations))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Triage Examinations</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($triage_examinations as $b)
                                <p>{{ $b->fldhead }}</p>
                                <p>Date:{{ Carbon\Carbon::parse($b->fldtime)->format('Y/m/d l h:i:s') }} </p>

                                <p>{{ $b->fldrepquali }}</p>
                                <p>{{ $b->fldrepquanli }}</p>
                                <p>{{ $b->fldtype }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($cause_of_admission) && count($cause_of_admission))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Cause of Admission</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($cause_of_admission as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($present_symptoms) && count($present_symptoms))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Presenting Complaints</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($present_symptoms as $b)
                                <p>Date:{{ Carbon\Carbon::parse($b->fldtime)->format('Y/m/d w h:i:s') }} </p>
                                <p>{{ $b->flditem }} : @if($b->fldreportquanti <= 24) {{ $b->fldreportquanti }} hr @endif @if($b->fldreportquanti > 24 && $b->fldreportquanti <=720 ) {{ round($b->fldreportquanti/24,2) }} Days @endif @if($b->fldreportquanti > 720 && $b->fldreportquanti <8760) {{ round($b->fldreportquanti/720,2) }}
                                    Months @endif @if($b->fldreportquanti >= 8760) {{ round($b->fldreportquanti/8760) }} Years @endif
                                    {{ $b->fldreportquali }} {{ strip_tags(strip_tags($b->flddetail)) }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($patientExam) && count($patientExam))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">OPD Examinations</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @for($i = 0; $i<count($patientExam['fldhead']); $i++) <p>{{ $patientExam['fldhead'][$i] }}{{ !is_array($patientExam['fldrepquali'][$i]) ? ':' .$patientExam['fldrepquali'][$i] :''}}</p>
                            @if(is_array($patientExam['fldrepquali'][$i]) && count($patientExam['fldrepquali'][$i])>1)
                                <table border="1px" rules="all" style="width: 60%;">
                                    <tr>
                                        @foreach($patientExam['fldrepquali'][$i] as $row => $val)

                                            <th>{{ $row }}</th>

                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($patientExam['fldrepquali'][$i] as $row => $val)
                                            <td>{{ $val }}</td>
                                        @endforeach
                                    </tr>
                                </table>
                            @endif
                            @endfor
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($general_complaints) && count($general_complaints))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">General Complaints</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($general_complaints as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($history_illness) && count($history_illness))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">History of Illness</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($history_illness as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($past_history) && count($past_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Past History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($past_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($treatment_history) && count($treatment_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Treatment History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($treatment_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($medicated_history) && count($medicated_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Medication History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($medicated_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($family_history) && count($family_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Family History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($family_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($personal_history) && count($personal_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Personal History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($personal_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($surgical_history) && count($surgical_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Surgical History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($surgical_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($occupational_history) && count($occupational_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Occupational History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($occupational_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($social_history) && count($social_history))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Social History</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($social_history as $b)
                                <p>{{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($allergy_drugs) && count($allergy_drugs))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Drug Allergy</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($allergy_drugs as $b)
                                <p>{{ $b->fldcode }} : {{ $b->fldcodeid }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($provisinal_diagnosis) && count($provisinal_diagnosis))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Provisional Diagnosis</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($provisinal_diagnosis as $b)
                                <p>[{{ $b->fldcodeid }}] {{ $b->fldcode }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif

        @if(isset($initial_planning) && count($initial_planning))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Advice</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($initial_planning as $b)
                                <p>{!! $b->flddetail !!}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($final_diagnosis) && count($final_diagnosis))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Final Diagnosis</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($final_diagnosis as $b)
                                <p>{{ $b->fldcode }} : {{ $b->fldcodeid }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($prominent_symptoms) && count($prominent_symptoms))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Prominent Symptoms</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($prominent_symptoms as $b)
                                <p>Date: {{ $b->fldtime }} :: {{ $b->flditem }} : {{ $b->fldreportquali }} ,{{ strip_tags($b->flddetail) }} </p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($procedures) && count($procedures))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Major Procedures</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($procedures as $b)
                                <p>{{ $b->fldnewdate }} :: {{ $b->flditem }} {{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($minor_procedure) && count($minor_procedure))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Minor Procedures</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($minor_procedure as $b)
                                <p>{{ $b->fldnewdate }} :: {{ $b->flditem }} {{ strip_tags($b->flddetail) }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($consult) && count($consult))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Consultations</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($consult as $b)
                                <p>Date: {{ $b->fldconsulttime }} :: {{ $b->fldconsultname }} ({{ $b->fldstatus }})</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($equipment) && count($equipment))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Equipments Used</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($equipment as $b)
                                <p>{{ $b->flditem }} ,
                                    {{ $b->fldfirsttime }} ,
                                    {{ $b->fldsecondtime }} ,
                                    {{ $b->fldsecondreport }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($planned) && count($planned))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Extra Procedures</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($planned as $b)
                                <p>Date: {{ $b->fldnewdate }} :: {{ $b->flditem }} : {{ $b->detail }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($MedicationUsed) && count($MedicationUsed))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Medication Used</th>
                    </tr>


                </thead>

            </table>
            <table class="table-first"  border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto; margin-top: 20px; border: 3px solid; background-color: #d8d7d7;">
                <thead>
                    <tr>
                        <th colspan="4" style="padding: 3px; border: none; text-align: left;">Surgical Note:</th>
                    </tr>
                    <tr>
                        <th class="th-borderbottom">Sr No:</th>
                        <th class="th-borderbottom">Description:</th>
                        <th class="th-borderbottom">Remark:</th>
                        <th class="th-borderbottom">Duration:</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($MedicationUsed as $bk=>$b)
                        @php
                            $sn = $bk+1;
                        @endphp

                         @if($b->fldlevel == "Dispensed")
                        <tr>
                            <td class="td-borderbottom">{{$sn}}</td>
                            <td class="td-borderbottom">{{ $b->flditem }}</td>
                            <td class="td-borderbottom"></td>
                            <td class="td-borderbottom">{{ $b->flddays }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        @endif
        @if(isset($MedicationUsed) && count($MedicationUsed))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Treatment Advised</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($MedicationUsed as $b)
                                @if($b->fldlevel == "Requested")
                                <p>{{ $b->flditem }} () {{ $b->fldroute }} {{ $b->flddose }} X {{ $b->flddays }} ({{ $b->fldfreq }})</p>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($confinement) && count($confinement))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Delivery Profile</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($confinement as $b)
                                <p>Delivery Date: {{ $b->flddeltime }}</p>
                                <p>Delivery Type: {{ $b->flddeltype }}</p>
                                <p>Delivery Result: {{ $b->flddelresult }}</p>
                                <br>
                                @if($b->fldbabypatno != "" || $b->fldbabypatno != null)
                                    <p>Baby Patient No: {{ $b->fldbabypatno }}</p>
                                    <p>Baby Gender: {{ $b->flddeltime }}</p>
                                    <p>Baby Weight: {{ $b->flddelwt }} grams</p>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Clinical Findings</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                            @foreach($ClinicianPlanPatPlanning as $b)
                                <p>Date: {{ $b->fldtime }}
                                    {{ $b->fldproblem }} ,
                                    {{ $b->fldsubjective }} ,
                                    {{ $b->fldobjective }} ,
                                    {{ $b->fldassess }} ,
                                    {{ $b->fldplan }}</p>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($reportedPatLab) && count($reportedPatLab))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Laboratory</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($reportedPatLab as $labValue)
                                {{ $labValue->fldtestid }} [Spec: {{ $labValue->fldsampletype }}]
                                @if($labValue->fldreportquanti != null && $labValue->fldreportquanti != 0.0)
                                    <br>
                                    {{ $iterationCount }}.. {{ $labValue->fldreportquanti }}
                                @endif
                                <ul>
                                    @foreach($labValue->subTest as $patTestResult)
                                        <li>{{ $patTestResult->fldsubtest }}:</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($patRadioTest) && count($patRadioTest))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Radio Diagnostics</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($patRadioTest as $radioValue)
                                {{--<p>{{ $b->radioData }} : {{ $b->radioSubTest }}</p>--}}
                                {{ $iterationCount }}..
                                <br>
                                @if(count($radioValue->radioSubTest))
                                    @foreach($radioValue->radioSubTest as $radioSubTestValue)
                                        <p>{{ $radioSubTestValue->fldsubtest }}: {{ $radioSubTestValue->fldreport }}</p>
                                    @endforeach

                                @endif
                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Clinical Planes</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($ClinicianPlanPatPlanning as $b)
                                <p>{{ $b->fldtime }}</p>
                                <p>Problem: {{ $b->fldproblem }}</p>
                                @if($b->fldsubjective != "" || $b->fldsubjective != null)
                                    <p>Subjective: {{ $b->fldsubjective }}</p>
                                @endif
                                @if($b->fldsubjective != "" || $b->fldsubjective != null)
                                    <p>Subjective: {{ $b->fldsubjective }}</p>
                                @endif
                                @if($b->fldobjective != "" || $b->fldobjective != null)
                                    <p>Objective: {{ $b->fldobjective }}</p>
                                @endif
                                @if($b->fldassess != "" || $b->fldassess != null)
                                    <p>Assessment: {{ $b->fldassess }}</p>
                                @endif
                                @if($b->fldplan != "" || $b->fldplan != null)
                                    <p>Planning: {{ $b->fldplan }}</p>
                                @endif

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($generalExamProgressCliniciansNurses) && count($generalExamProgressCliniciansNurses))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Clinical Notes</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($generalExamProgressCliniciansNurses as $b)
                                <p>{{ $b->fldtime }} :: {{ $b->flditem }} : {{ $b->fldreportquali }} , {!! $b->flddetail !!}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($IPMonitoringPatPlanning) && count($IPMonitoringPatPlanning))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">IP Monitoring</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($IPMonitoringPatPlanning as $b)
                                <p>{{ $b->fldtime }} :: {{ $b->fldproblem }} , {{ $b->fldsubjective }} , {{ $b->fldobjective }} ,{{ $b->fldassess }} ,{{ $b->fldplan }} </p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Therapeutic Planning</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($ClinicianPlanPatPlanning as $b)
                                <h5>{{ $b->fldproblem . ' ' . $b->fldtime }}</h5>
                                @if($b->fldsubjective != '' || $b->fldsubjective != null)
                                    <p>Route: {{ $b->fldsubjective }}</p>
                                @endif
                                @if($b->fldobjective != '' || $b->fldobjective != null)
                                    <p>Route: {{ $b->fldobjective }}</p>
                                @endif
                                @if($b->fldassess != '' || $b->fldassess != null)
                                    <p>Route: {{ $b->fldassess }}</p>
                                @endif
                                @if($b->fldplan != '' || $b->fldplan != null)
                                    <p>Route: {{ $b->fldplan }}</p>
                                @endif
                                {!! !$loop->last?'<hr>':'' !!}

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($patGeneral) && count($patGeneral))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Planned Procedures</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                           @foreach($patGeneral as $b)
                                <p>Date :: {{ $b->flditem }} : {{ $b->flddetail }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($DischargeExaminationspatientExam) && count($DischargeExaminationspatientExam))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Discharge Examinations</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                          @foreach($DischargeExaminationspatientExam as $b)
                                <p>{{ $b->fldtime }} :: {{ $b->fldhead }} : {{ $b->fldrepquali }} {{ $b->fldrepquanti }} ,{{ $b->fldtype }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($ConditionOfDischargeExamGeneral) && count($ConditionOfDischargeExamGeneral))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Condition at Discharge</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                          @foreach($ConditionOfDischargeExamGeneral as $b)
                                <p>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
        @if(isset($DischargedLAMADeathReferAbsconderPatDosing) && count($DischargedLAMADeathReferAbsconderPatDosing))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Discharge Medication</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                          @foreach($DischargedLAMADeathReferAbsconderPatDosing as $b)
                                <p>{{ $b->flditem }} () {{ $b->fldroute }} {{ flddose }} X {{ $b->flddays }} ({{ $b->fldfreq }})</p>

                            <!-- <p>{{ $b->flditemtype }}</p> -->

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>

        @endif
        @if(isset($AdviceOfDischargeExamGeneral) && count($AdviceOfDischargeExamGeneral))
            <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid;" class="th-bak">Advice on Discharge</th>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 14px; padding: 10px;">
                          @foreach($AdviceOfDischargeExamGeneral as $b)
                                <p>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</p>

                            @endforeach
                        </td>
                    </tr>
                </thead>
            </table>
        @endif
    </div>
    @php
        $signatures = Helpers::getSignature('opd');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</div>
