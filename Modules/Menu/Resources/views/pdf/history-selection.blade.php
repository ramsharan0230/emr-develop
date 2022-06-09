<!DOCTYPE html>
<html>
<head>
    <title>OPD Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }
    </style>

</head>
<body>

@php
    $patientInfo = $encounterData->patientInfo;
    $iterationCount = 1;
@endphp
@include('pdf-header-footer.header-footer')
<main>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p><strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{  $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' .  $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
                <p><strong>REPORT:</strong> OUTPATIENT REPORT</p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $encounterId }}</p>
                <p><strong>DOReg:</strong> {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}</p>
                <p><strong>Phone: </strong></p>
            </td>
            <td style="width: 130px;">{!! Helpers::generateQrCode($encounterId)!!}</td>
        </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="1px" rules="all" class="content-body">
        <tbody>
        <tr>
            <th style="width: 96px; text-align: center;">Category</th>
            <th style="width: 467.2px; text-align: center;">Observations</th>
        </tr>
        @if(isset($CourseofTreatment) && count($CourseofTreatment))
            <tr>
                <td>
                    <p>Course of Treatment</p>
                </td>
                <td>

                    @foreach($CourseofTreatment as $symptoms)
                        <p>{{ $symptoms->fldtime }}: {{ $symptoms->fldcomment }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($bed) && count($bed))
            <tr>
                <td>
                    <p>Bed Transitions</p>
                </td>
                <td>
                    @foreach($bed as $b)
                        <p>{{ $b->flditem }}</p>
                        <p>{{ $b->fldfirsttime }}</p>
                        <p>{{ $b->fldsecondtime }}</p>
                        <p>{{ $b->fldsecondreport }}</p>
                    @endforeach
                </td>
            </tr>
        @endif
        @if(isset($EssentialExaminations) && count($EssentialExaminations))

            <tr>
                <td>
                    <p>OPD Examinations</p>
                </td>
                <td>

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
        @endif
        @if(isset($demographics) && count($demographics))
            <tr>
                <td>
                    <p>Demographics </p>
                </td>
                <td>
                    @foreach($demographics as $b)

                        <p>{{ $b->flditem }}</p>
                        <p>Date:{{ Carbon\Carbon::parse($b->fldfirsttime)->format('Y/m/d l h:i:s') }} </p>
                        <p>{{ $b->fldreportquali }}</p>
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($triage_examinations) && count($triage_examinations))

            <tr>
                <td>
                    <p>Triage Examinations </p>
                </td>
                <td>

                    @foreach($triage_examinations as $b)
                        <p>{{ $b->fldhead }}</p>
                        <p>Date:{{ Carbon\Carbon::parse($b->fldtime)->format('Y/m/d l h:i:s') }} </p>

                        <p>{{ $b->fldrepquali }}</p>
                        <p>{{ $b->fldrepquanli }}</p>
                        <p>{{ $b->fldtype }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($cause_of_admission) && count($cause_of_admission))
            <tr>
                <td>
                    <p>Cause of Admission</p>
                </td>
                <td>
                    @foreach($cause_of_admission as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif

        @if(isset($present_symptoms) && count($present_symptoms))

            <tr>
                <td>
                    <p>Presenting Complaints</p>
                </td>
                <td>

                    @foreach($present_symptoms as $b)
                        <p>Date:{{ Carbon\Carbon::parse($b->fldtime)->format('Y/m/d w h:i:s') }} </p>
                        <p>{{ $b->flditem }} : @if($b->fldreportquanti <= 24) {{ $b->fldreportquanti }} hr @endif @if($b->fldreportquanti > 24 && $b->fldreportquanti <=720 ) {{ round($b->fldreportquanti/24,2) }} Days @endif @if($b->fldreportquanti > 720 && $b->fldreportquanti <8760) {{ round($b->fldreportquanti/720,2) }}
                            Months @endif @if($b->fldreportquanti >= 8760) {{ round($b->fldreportquanti/8760) }} Years @endif
                            {{ $b->fldreportquali }} {{ strip_tags(strip_tags($b->flddetail)) }}</p>

                    @endforeach
                </td>
            </tr>
        @endif
        @if(isset($patientExam) && count($patientExam))

            <tr>
                <td>
                    <p>OPD Examinations</p>
                </td>
                <td>
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
        @endif
        @if(isset($general_complaints) && count($general_complaints))

            <tr>
                <td>
                    <p>General Complaints </p>
                </td>
                <td>

                    @foreach($general_complaints as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($history_illness) && count($history_illness))

            <tr>
                <td>
                    <p>History of Illness</p>
                </td>
                <td>
                    @foreach($history_illness as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($past_history) && count($past_history))

            <tr>
                <td>
                    <p>Past History </p>
                </td>
                <td>

                    @foreach($past_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($treatment_history) && count($treatment_history))

            <tr>
                <td>
                    <p>Treatment History </p>
                </td>
                <td>

                    @foreach($treatment_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($medicated_history) && count($medicated_history))

            <tr>
                <td>
                    <p>Medication History </p>
                </td>
                <td>

                    @foreach($medicated_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($family_history) && count($family_history))

            <tr>
                <td>
                    <p>Family History </p>
                </td>
                <td>

                    @foreach($family_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($personal_history) && count($personal_history))

            <tr>
                <td>
                    <p>Personal History </p>
                </td>
                <td>
                    @foreach($personal_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($surgical_history) && count($surgical_history))

            <tr>
                <td>
                    <p>Surgical History </p>
                </td>
                <td>
                    @foreach($surgical_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif

        @if(isset($occupational_history) && count($occupational_history))

            <tr>
                <td>
                    <p>Occupational History </p>
                </td>
                <td>
                    @foreach($occupational_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif

        @if(isset($social_history) && count($social_history))

            <tr>
                <td>
                    <p>Social History
                    </p>
                </td>
                <td>
                    @foreach($social_history as $b)
                        <p>{{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($allergy_drugs) && count($allergy_drugs))

            <tr>
                <td>
                    <p>Drug Allergy</p>
                </td>
                <td>

                    @foreach($allergy_drugs as $b)
                        <p>{{ $b->fldcode }} : {{ $b->fldcodeid }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($provisinal_diagnosis) && count($provisinal_diagnosis))

            <tr>
                <td>
                    <p>Provisional Diagnosis</p>
                </td>
                <td>

                    @foreach($provisinal_diagnosis as $b)
                        <p>[{{ $b->fldcodeid }}] {{ $b->fldcode }}</p>

                    @endforeach
                </td>
            </tr>
        @endif
        @if(isset($initial_planning) && count($initial_planning))

            <tr>
                <td>
                    <p>Advice</p>
                </td>
                <td>

                    @foreach($initial_planning as $b)
                        <p>{{ $b->fldcode }} : {{ $b->fldcodeid }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($final_diagnosis) && count($final_diagnosis))

            <tr>
                <td>
                    <p>Final Diagnosis </p>
                </td>
                <td>
                    @foreach($final_diagnosis as $b)
                        <p>{{ $b->fldcode }} : {{ $b->fldcodeid }}</p>
                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($prominent_symptoms) && count($prominent_symptoms))
            <tr>
                <td>
                    <p>Prominent Symptoms </p>
                </td>
                <td>
                    @foreach($prominent_symptoms as $b)
                        <p>Date: {{ $b->fldtime }} :: {{ $b->flditem }} : {{ $b->fldreportquali }} ,{{ strip_tags($b->flddetail) }} </p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($procedures) && count($procedures))
            <tr>
                <td>
                    <p>Major Procedures </p>
                </td>
                <td>
                    @foreach($procedures as $b)
                        <p>{{ $b->fldnewdate }} :: {{ $b->flditem }} {{ strip_tags($b->flddetail) }}</p>


                    @endforeach


                </td>
            </tr>
        @endif
        @if(isset($minor_procedure) && count($minor_procedure))

            <tr>
                <td>
                    <p>Minor Procedures </p>
                </td>
                <td>

                    @foreach($minor_procedure as $b)
                        <p>{{ $b->fldnewdate }} :: {{ $b->flditem }} {{ strip_tags($b->flddetail) }}</p>
                    @endforeach

                </td>
            </tr>
        @endif

        @if(isset($consult) && count($consult))
            <tr>
                <td>
                    <p>Consultations</p>
                </td>
                <td>

                    @foreach($consult as $b)
                        <p>Date: {{ $b->fldconsulttime }} :: {{ $b->fldconsultname }} ({{ $b->fldstatus }})</p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($equipment) && count($equipment))

            <tr>
                <td>
                    <p>Equipments Used </p>
                </td>
                <td>

                    @foreach($equipment as $b)
                        <p>{{ $b->flditem }} ,
                            {{ $b->fldfirsttime }} ,
                            {{ $b->fldsecondtime }} ,
                            {{ $b->fldsecondreport }}</p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($planned) && count($planned))
            <tr>
                <td>
                    <p>Extra Procedures </p>
                </td>
                <td>
                    @foreach($planned as $b)
                        <p>Date: {{ $b->fldnewdate }} :: {{ $b->flditem }} : {{ $b->detail }}</p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($mainDataForPatDosing) && count($mainDataForPatDosing))

            <tr>
                <td>
                    <p>Medication Used </p>
                </td>
                <td>
                    @foreach($mainDataForPatDosing as $b)
                        <p>{{ $b->flditem }} () {{ $b->fldroute }} {{ $b->flddose }} X {{ $b->flddays }} ({{ $b->fldfreq }})</p>
                    @endforeach

                </td>
            </tr>
        @endif

        @if(isset($mainDataForPatDosing) && count($mainDataForPatDosing))
            <tr>
                <td>
                    <p>Treatment Advised</p>
                </td>
                <td>
                    @foreach($mainDataForPatDosing as $b)
                        <p>{{ $b->flditem }} () {{ $b->fldroute }} {{ $b->flddose }} X {{ $b->flddays }} ({{ $b->fldfreq }})</p>
                    @endforeach
                </td>
            </tr>
        @endif
        @if(isset($confinement) && count($confinement))

            <tr>
                <td>
                    <p>Delivery Profile </p>
                </td>
                <td>
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
        @endif
        @if(isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))

            <tr>
                <td>
                    <p> Clinical Findings </p>
                </td>
                <td>
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
        @endif
        @if(isset($reportedPatLab) && count($reportedPatLab))

            <tr>
                <td>
                    <p> Laboratory </p>
                </td>
                <td>
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
        @endif
        @if(isset($patRadioTest) && count($patRadioTest))

            <tr>
                <td>
                    <p> Radio Diagnostics </p>
                </td>
                <td>

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
        @endif
        @if(isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))

            <tr>
                <td>
                    <p> Clinical Planes </p>
                </td>
                <td>

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
        @endif
        @if(isset($generalExamProgressCliniciansNurses) && count($generalExamProgressCliniciansNurses))

            <tr>
                <td>
                    <p> Clinical Notes </p>
                </td>
                <td>
                    @foreach($generalExamProgressCliniciansNurses as $b)
                        <p>{{ $b->fldtime }} :: {{ $b->flditem }} : {{ $b->fldreportquali }} , {{ $b->flddetail }}</p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($IPMonitoringPatPlanning) && count($IPMonitoringPatPlanning))

            <tr>
                <td>
                    <p> IP Monitoring </p>
                </td>
                <td>
                    @foreach($IPMonitoringPatPlanning as $b)
                        <p>{{ $b->fldtime }} :: {{ $b->fldproblem }} , {{ $b->fldsubjective }} , {{ $b->fldobjective }} ,{{ $b->fldassess }} ,{{ $b->fldplan }} </p>

                    @endforeach


                </td>
            </tr>
        @endif
        @if(isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))

            <tr>
                <td>
                    <p> Therapeutic Planning </p>
                </td>
                <td>
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
        @endif

        @if(isset($patGeneral) && count($patGeneral))
            <tr>
                <td>
                    <p> Planned Procedures </p>
                </td>
                <td>

                    @foreach($patGeneral as $b)
                        <p>Date :: {{ $b->flditem }} : {{ $b->flddetail }}</p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($DischargeExaminationspatientExam) && count($DischargeExaminationspatientExam))

            <tr>
                <td>
                    <p> Discharge Examinations </p>
                </td>
                <td>
                    @foreach($DischargeExaminationspatientExam as $b)
                        <p>{{ $b->fldtime }} :: {{ $b->fldhead }} : {{ $b->fldrepquali }} {{ $b->fldrepquanti }} ,{{ $b->fldtype }}</p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($ConditionOfDischargeExamGeneral) && count($ConditionOfDischargeExamGeneral))

            <tr>
                <td>
                    <p> Condition at Discharge </p>
                </td>
                <td>

                    @foreach($ConditionOfDischargeExamGeneral as $b)
                        <p>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</p>

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($DischargedLAMADeathReferAbsconderPatDosing) && count($DischargedLAMADeathReferAbsconderPatDosing))
            <tr>
                <td>
                    <p> Discharge Medication </p>
                </td>
                <td>

                    @foreach($DischargedLAMADeathReferAbsconderPatDosing as $b)
                        <p>{{ $b->flditem }} () {{ $b->fldroute }} {{ flddose }} X {{ $b->flddays }} ({{ $b->fldfreq }})</p>

                    <!-- <p>{{ $b->flditemtype }}</p> -->

                    @endforeach

                </td>
            </tr>
        @endif
        @if(isset($AdviceOfDischargeExamGeneral) && count($AdviceOfDischargeExamGeneral))

            <tr>
                <td>
                    <p> Advice on Discharge </p>
                </td>
                <td>

                    @foreach($AdviceOfDischargeExamGeneral as $b)
                        <p>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</p>

                    @endforeach

                </td>
            </tr>
        @endif
        </tbody>
    </table>
</main>
</body>
</html>
