<!DOCTYPE html>
<html>
<head>
    <title>Dental History Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@include('pdf-header-footer.header-footer')
<main>

    @if($encounters)
        <div class="pdf-container">

                <br>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 40%;">Name: {{ Options::get('system_patient_rank')  == 1 && (isset($encounters[0]['encounter_detail'])) && (isset($encounters[0]['encounter_detail']->fldrank) ) ?$encounters[0]['encounter_detail']->fldrank:''}} {{ $patientinfo->fldptnamefir . ' ' . $patientinfo->fldmidname . ' ' . $patientinfo->fldptnamelast }} ({{$patientinfo->fldpatientval}})</td>
                        <td>Age/Sex: {{ $patientinfo->fldagestyle }} /{{ $patientinfo->fldptsex??"" }}</td>
                        {{-- <td>Age/Sex: {{ \Carbon\Carbon::parse($patientinfo->fldptbirday??"")->age }}yrs/{{ $patientinfo->fldptsex??"" }}</td> --}}
                        <td>Address: {{ $patientinfo->fldptaddvill??"" . ', ' . $patientinfo->fldptadddist??"" }}</td>

                    </tr>
                    <tr>
                        <td>REPORT: {{$title}}</td>
                        <td>Regd Date: {{ $encounters[0]['encounter_detail']->fldregdate ? \Carbon\Carbon::parse(  $encounters[0]['encounter_detail']->fldregdate)->format('d/m/Y'):'' }}</td>
                        <td>{!! Helpers::generateQrCode($patientinfo->fldpatientval)!!}</td>
                    </tr>
                </table>
                <ul>
                    <li>Diagnosis: Diagnosis of Patient</li>

                    <li>BP: Sys/Diast : @if(isset($systolic_bp) and $systolic_bp !='') {{ $systolic_bp->fldrepquali }} @endif / @if(isset($diasioli_bp) and $diasioli_bp !='') {{ $diasioli_bp->fldrepquali }}@endif</li>
                </ul>

            </div>
            @foreach($encounters as $encounter)

                @if($encounter['form_type'] == 'normal')
                    @php
                        $iterationCount = $loop->iteration;

                        $encounter_detail = $encounter['encounter_detail'];

                        $systolic_bp = $encounter['systolic_bp'];
                        $diasioli_bp = $encounter['diasioli_bp'];
                        $pulse = $encounter['pulse'];
                        $temperature = $encounter['temperature'];
                        $respiratory_rate = $encounter['respiratory_rate'];
                        $o2_saturation = $encounter['o2_saturation'];

                        $reportedPatLab = $encounter['reportedPatLab'];
                        $patRadioTest = $encounter['patRadioTest'];
                        $generalExamProgressCliniciansNurses = $encounter['generalExamProgressCliniciansNurses'];
                        $IPMonitoringPatPlanning = $encounter['IPMonitoringPatPlanning'];
                        $ClinicianPlanPatPlanning = $encounter['ClinicianPlanPatPlanning'];
                        $patGeneral = $encounter['patGeneral'];
                        $DischargeExaminationspatientExam = $encounter['DischargeExaminationspatientExam'];
                        $AdviceOfDischargeExamGeneral = $encounter['AdviceOfDischargeExamGeneral'];

                        $ConditionOfDischargeExamGeneral = $encounter['ConditionOfDischargeExamGeneral'];
                        $DischargedLAMADeathReferAbsconderPatDosing = $encounter['DischargedLAMADeathReferAbsconderPatDosing'];

                        $encounter_detail = $encounter['encounter_detail'];
                        $patient_date = $encounter['patient_date'];

                        $medicines_dose = $singleData = $encounter['singleData'];

                        $bed = $encounter['bed'];
                        $demographics = $encounter['demographics'];
                        $triage_examinations = $encounter['triage_examinations'];
                        $cause_of_admission = $encounter['cause_of_admission'];
                        $present_symptoms = $encounter['present_symptoms'];
                        $patientExam = $encounter['patientExam'];
                        $general_complaints = $encounter['general_complaints'];
                        $history_illness = $encounter['history_illness'];
                        $past_history = $encounter['past_history'];
                        $treatment_history = $encounter['treatment_history'];
                        $medicated_history = $encounter['medicated_history'];
                        $family_history = $encounter['family_history'];
                        $personal_history = $encounter['personal_history'];
                        $surgical_history = $encounter['surgical_history'];
                        $occupational_history = $encounter['occupational_history'];
                        $social_history = $encounter['social_history'];
                        $allergy_drugs = $encounter['allergy_drugs'];
                        $provisinal_diagnosis = $encounter['provisinal_diagnosis'];
                        $initial_planning = $encounter['initial_planning'];
                        $final_diagnosis = $encounter['final_diagnosis'];
                        $prominent_symptoms = $encounter['prominent_symptoms'];
                        $procedures = $encounter['procedures'];
                        $minor_procedure = $encounter['minor_procedure'];
                        $consult = $encounter['consult'];
                        $equipment = $encounter['equipment'];
                        $planned = $encounter['planned'];
                        $confinement = $encounter['confinement'];

                        $mainDataForPatDosing = $encounter['mainDataForPatDosing'];
                    @endphp

                    <table style="width: 100%;" border="1px" rules="all" class="content-body">
                        <tbody>
                        <tr>
                            <th style="width: 96px; text-align: center;">Category</th>
                            <th style="width: 467.2px; text-align: center;">Observations</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p>Encounter Id : <b>{{ $encounter_detail->col }}</b></p>
                            </td>

                        </tr>
                        @if($patient_date)
                            <tr>
                                <td>
                                    <p>Course of Treatment </p>
                                </td>
                                <td>


                                    <p>Date:{{ Carbon\Carbon::parse($patient_date->fldtime)->format('Y/m/d l h:i:s') }} ::{{ $patient_date->fldhead }}</p>

                                    <p>{{ strip_tags($patient_date->fldcomment) }}</p>


                                </td>
                            </tr>
                        @endif
                        @if($bed)
                            <tr>
                                <td>
                                    <p>Bed Transitions </p>
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
                        @if($demographics)
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
                        @if($triage_examinations)
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
                        @if($cause_of_admission)
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
                        @if($present_symptoms)
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
                        @if($patientExam)
                            <tr>
                                <td>
                                    <p>OPD Examinations</p>
                                </td>
                                <td>

                                    @if(count($patientExam))
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
                                    @endif

                                </td>
                            </tr>
                        @endif
                        @if($general_complaints)
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

                        @if($history_illness)
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
                        @if($past_history)
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
                        @if($treatment_history)
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

                        @if($medicated_history)
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
                        @if($family_history)
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
                        @if($personal_history)
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
                        @if($surgical_history)
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
                        @if($occupational_history)
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

                        @if($social_history)
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
                        @if($allergy_drugs)
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
                        @if($provisinal_diagnosis)
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
                        @if($initial_planning)
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
                        @if($final_diagnosis)
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
                        @if($prominent_symptoms)
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
                        @if($procedures)
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

                        @if($minor_procedure)
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

                        @if($consult)
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
                        @if($equipment)
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
                        @if($planned)
                            <tr>
                                <td>
                                    <p>Extra Procedures </p>
                                </td>
                                <td>

                                    @foreach($planned as $b)
                                        <p>Date: {{ $b->fldnewdate }} :: {{ $b->flditem }} : {{strip_tags( $b->detail) }}</p>

                                    @endforeach


                                </td>
                            </tr>
                        @endif
                        @if($mainDataForPatDosing)
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

                        @if($mainDataForPatDosing)

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
                        @if(count($confinement))
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


                        <tr>
                            <td>
                                <p>Essential Examinations</p>
                            </td>
                            <td>

                                @if($systolic_bp)

                                    <p>{{ $systolic_bp->fldhead }} : {{ $systolic_bp->fldrepquali }} {{ $systolic_bp->fldunit }}</p>

                                @endif

                                @if($diasioli_bp)

                                    <p>{{ $diasioli_bp->fldhead }} : {{ $diasioli_bp->fldrepquali }} {{ $diasioli_bp->fldunit }}</p>

                                @endif


                                @if($pulse)

                                    <p>{{ $pulse->fldhead }} : {{ $pulse->fldrepquali }} {{ $pulse->fldunit }}</p>

                                @endif


                                @if($temperature)

                                    <p>{{ $temperature->fldhead }} : {{ $temperature->fldrepquali }} {{ $temperature->fldunit }}</p>

                                @endif

                                @if($respiratory_rate)

                                    <p>{{ $respiratory_rate->fldhead }} : {{ $respiratory_rate->fldrepquali }}{{ $respiratory_rate->fldunit }} </p>

                                @endif

                                @if($o2_saturation)

                                    <p>{{ $o2_saturation->fldhead }} : {{ $o2_saturation->fldrepquali }} {{ $o2_saturation->fldunit }}</p>

                                @endif
                            </td>
                        </tr>
                        @if($ClinicianPlanPatPlanning)
                            <tr>
                                <td>
                                    <p> Clinical Findings </p>
                                </td>
                                <td>

                                    @foreach($ClinicianPlanPatPlanning as $b)
                                        <p>Date: {{ $b->fldtime }}
                                            {{ strip_tags($b->fldproblem) }} ,
                                            {{strip_tags( $b->fldsubjective) }} ,
                                            {{ strip_tags($b->fldobjective) }} ,
                                            {{ strip_tags($b->fldassess) }} ,
                                            {{ strip_tags($b->fldplan) }}</p>
                                    @endforeach


                                </td>
                            </tr>
                        @endif
                        @if(count($reportedPatLab))
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
                        @if(count($patRadioTest))
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
                        @if($ClinicianPlanPatPlanning)
                            <tr>
                                <td>
                                    <p> Clinical Planes </p>
                                </td>
                                <td>


                                    @foreach($ClinicianPlanPatPlanning as $b)
                                        <p>{{ $b->fldtime }}</p>
                                        <p>Problem: {{ strip_tags($b->fldproblem) }}</p>
                                        @if($b->fldsubjective != "" || $b->fldsubjective != null)
                                            <p>Subjective: {{ strip_tags($b->fldsubjective) }}</p>
                                        @endif
                                        @if($b->fldsubjective != "" || $b->fldsubjective != null)
                                            <p>Subjective: {{ strip_tags($b->fldsubjective) }}</p>
                                        @endif
                                        @if($b->fldobjective != "" || $b->fldobjective != null)
                                            <p>Objective: {{ strip_tags($b->fldobjective) }}</p>
                                        @endif
                                        @if($b->fldassess != "" || $b->fldassess != null)
                                            <p>Assessment: {{ strip_tags($b->fldassess) }}</p>
                                        @endif
                                        @if($b->fldplan != "" || $b->fldplan != null)
                                            <p>Planning: {{ strip_tags($b->fldplan) }}</p>
                                        @endif

                                    @endforeach


                                </td>
                            </tr>
                        @endif
                        @if($generalExamProgressCliniciansNurses)
                            <tr>
                                <td>
                                    <p> Notes </p>
                                </td>
                                <td>

                                    @foreach($generalExamProgressCliniciansNurses as $b)
                                        <p>{{ $b->fldtime }} :: {{ $b->flditem }} : {{ $b->fldreportquali }} , {{ strip_tags($b->flddetail) }}</p>

                                    @endforeach


                                </td>
                            </tr>
                        @endif
                        @if($IPMonitoringPatPlanning)
                            <tr>
                                <td>
                                    <p> IP Monitoring </p>
                                </td>
                                <td>

                                    @foreach($IPMonitoringPatPlanning as $b)
                                        <p>{{ $b->fldtime }} :: {{ strip_tags($b->fldproblem) }} , {{ strip_tags($b->fldsubjective) }} , {{ strip_tags($b->fldobjective) }} ,{{strip_tags($b->fldassess) }} ,{{ strip_tags($b->fldplan) }} </p>

                                    @endforeach


                                </td>
                            </tr>
                        @endif
                        @if(count($ClinicianPlanPatPlanning))
                            <tr>
                                <td>
                                    <p> Therapeutic Planning </p>
                                </td>
                                <td>

                                    @foreach($ClinicianPlanPatPlanning as $b)
                                        <h5>{{ strip_tags($b->fldproblem) . ' ' . $b->fldtime }}</h5>
                                        @if($b->fldsubjective != '' || $b->fldsubjective != null)
                                            <p>Route: {{ strip_tags($b->fldsubjective) }}</p>
                                        @endif
                                        @if($b->fldobjective != '' || $b->fldobjective != null)
                                            <p>Route: {{ strip_tags($b->fldobjective) }}</p>
                                        @endif
                                        @if($b->fldassess != '' || $b->fldassess != null)
                                            <p>Route: {{ strip_tags($b->fldassess) }}</p>
                                        @endif
                                        @if($b->fldplan != '' || $b->fldplan != null)
                                            <p>Route: {{ strip_tags($b->fldplan) }}</p>
                                        @endif
                                        {!! !$loop->last?'<hr>':'' !!}

                                    @endforeach

                                </td>
                            </tr>
                        @endif

                        @if($patGeneral)
                            <tr>
                                <td>
                                    <p> Planned Procedures </p>
                                </td>
                                <td>


                                    @foreach($patGeneral as $b)
                                        <p>Date :: {{ $b->flditem }} : {{ strip_tags($b->flddetail) }}</p>

                                    @endforeach


                                </td>
                            </tr>
                        @endif
                        @if($DischargeExaminationspatientExam)
                            <tr>
                                <td>
                                    <p> Discharge Examinations </p>
                                </td>
                                <td>
                                    <?php //dd($DischargeExaminationspatientExam) ?>

                                    @foreach($DischargeExaminationspatientExam as $b)
                                        <p>{{ $b->fldtime }} :: {{ $b->fldhead }} : {{ $b->fldrepquali }} {{ $b->fldrepquanti }} ,{{ $b->fldtype }}</p>

                                    @endforeach


                                </td>
                            </tr>
                        @endif
                        @if($ConditionOfDischargeExamGeneral)
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
                        @if($DischargedLAMADeathReferAbsconderPatDosing)
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
                        @if($AdviceOfDischargeExamGeneral)
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
                @endif
                @if($encounter['form_type'] == 'dental')

                    <div class="pdf-container-body" style="border: 1px solid #212529">
                        <div class="row">
                            <div class="table-right">
                                <table class="dental-teeth" style="font-size: 16px;float: right; margin: 0 auto; width: 80%; padding: 20px;">
                                    <img src="{{asset('assets/images/teeth.jpg')}}" style="width: 95%; padding: 20px">
                                </table>
                            </div>
                            @if(isset($encounter['imdData']) or isset($encounter['softtissuelessonData']) or isset($encounter['smoker']) or isset($encounter['periodentalData']) or isset($encounter['gingivalData']))
                                <div class="pdf-sub-eading">
                                    <h3 style="margin-left: 2%;">Basic Information:</h3>
                                </div>
                            @endif
                            @if(isset($encounter['imdData']) or isset($encounter['softtissuelessonData']) or isset($encounter['smoker']) or isset($encounter['periodentalData']) or isset($encounter['gingivalData']))
                                <div class="table-left">
                                    <table style="border: 1px solid;border-collapse: collapse; font-size: 17px; margin: 0 auto; width: 95%; margin-top: 7px;">
                                        <tbody>
                                        @if(isset($encounter['imdData']) and $encounter['imdData']->fldvalue !='')
                                            <tr>
                                                <td style="border: 1px solid; padding:5px; width:42%;">TMD/Clicks/Muscle Pain</td>
                                                <td style="border: 1px solid; padding:5px; width: 42%;">
                                                    @if($encounter['imdData']->fldteeth !='')({{$encounter['imdData']->fldteeth}})@endif
                                                    {{ $encounter['imdData']->fldvalue }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if(isset($encounter['softtissuelessonData']) and $encounter['softtissuelessonData']->fldvalue !='')
                                            <tr>
                                                <td style="border: 1px solid; padding:5px; width: 50%">Soft Tissue Lesion</td>
                                                <td style="border: 1px solid; padding:5px;">
                                                    @if($encounter['softtissuelessonData']->fldteeth !='')({{$encounter['softtissuelessonData']->fldteeth}})@endif
                                                    {{ $encounter['softtissuelessonData']->fldvalue }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if(isset($encounter['smoker']) and $encounter['smoker']->fldvalue !='')
                                            <tr>
                                                <td style="border: 1px solid; padding:5px; width: 50%">Smoker</td>
                                                <td style="border: 1px solid; padding:5px;">
                                                    @if($encounter['smoker']->fldteeth !='')({{$encounter['smoker']->fldteeth}})@endif
                                                    {{ $encounter['smoker']->fldvalue }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if(isset($encounter['periodentalData']) and $encounter['periodentalData']->fldvalue !='')
                                            <tr>
                                                <td style="border: 1px solid; padding:5px; width: 50%">Periodontal Diseases</td>
                                                <td style="border: 1px solid; padding:5px;">
                                                    @if($encounter['periodentalData']->fldteeth !='')({{$encounter['periodentalData']->fldteeth}})@endif
                                                    {{ $encounter['periodentalData']->fldvalue }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if(isset($encounter['gingivalData']) and $encounter['gingivalData']->fldvalue !='')
                                            <tr>
                                                <td style="border: 1px solid; padding:5px; width: 50%">Genigival Recession</td>
                                                <td style="border: 1px solid; padding:5px;">
                                                    @if($encounter['periodentalData']->fldteeth !='')({{$encounter['periodentalData']->fldteeth}})@endif
                                                    {{ $encounter['periodentalData']->fldvalue }}
                                                </td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>

                                </div>
                            @endif
                        </div>
                        <br>
                        <div class="row" style="margin-top: 7px;">
                            <div class="table-dental2">
                                <table style="border: 1px solid;border-collapse: collapse; font-size: 16px; width: 95%;margin: 0 auto;">
                                    <thead>
                                    <tr>
                                        <th colspan="2" style="border: 1px solid; padding:5px; width: 42%;">Dental Restoration</th>
                                        <th colspan="2" style="border: 1px solid; padding:5px; width: 42%;"> Dental Anomolies</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Crowns</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['crownData']) && $encounter['crownData']->fldvalue !='')
                                                ({{$encounter['crownData']->fldteeth}}) {{ $encounter['gingivalData']->fldvalue }}
                                            @endif
                                        </td>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Hypodontia</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['hypodontiaTeethData']) && $encounter['hypodontiaTeethData']->fldvalue !='')
                                                ({{$encounter['hypodontiaTeethData']->fldteeth}}) {{ $encounter['hypodontiaTeethData']->fldvalue }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">RCTS</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['rctData']) && $encounter['rctData']->fldvalue !='')
                                                ({{$encounter['rctData']->fldteeth}}) {{ $encounter['rctData']->fldvalue }}
                                            @endif
                                        </td>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Supernumerary Teeth</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['sntData']) &&  $encounter['sntData']->fldvalue !='')
                                                ({{$encounter['sntData']->fldteeth}}) {{ $encounter['sntData']->fldvalue }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Filling</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['fillingsData']) &&  $encounter['fillingsData']->fldvalue !='')
                                                ({{$encounter['fillingsData']->fldteeth}}) {{ $encounter['fillingsData']->fldvalue }}
                                            @endif
                                        </td>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Small Teeth</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['smallteethData']) &&  $encounter['smallteethData']->fldvalue !='')
                                                ({{$encounter['smallteethData']->fldteeth}}) {{ $encounter['smallteethData']->fldvalue }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Tooth Wears</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['toothwearsData']) &&  $encounter['toothwearsData']->fldvalue !='')
                                                ({{$encounter['toothwearsData']->fldteeth}}) {{ $encounter['toothwearsData']->fldvalue }}
                                            @endif
                                        </td>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Malformed Teeth</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['malformedteethData']) &&  $encounter['malformedteethData']->fldvalue !='')
                                                ({{$encounter['malformedteethData']->fldteeth}}) {{ $encounter['malformedteethData']->fldvalue }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Extraction</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['extractionData']) &&  $encounter['extractionData']->fldvalue !='')
                                                ({{$encounter['extractionData']->fldteeth}}) {{ $encounter['extractionData']->fldvalue }}
                                            @endif
                                        </td>
                                        <td rowspan="2" style="border: 1px solid; padding:5px; width: 25%;">Short/Abnormal Roots
                                        </td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['sarData']) &&  $encounter['sarData']->fldvalue !='')
                                                ({{$encounter['sarData']->fldteeth}}) {{ $encounter['sarData']->fldvalue }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">Impacted Teeth</td>
                                        <td style="border: 1px solid; padding:5px; width: 25%;">
                                            @if(isset($encounter['impactTeethData']) &&  $encounter['impactTeethData']->fldvalue !='')
                                                ({{$encounter['impactTeethData']->fldteeth}}) {{ $encounter['impactTeethData']->fldvalue }}
                                            @endif
                                        </td>
                                        <td style="border: 1px solid; padding:5px;">

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-history" style="margin-top: 25px;">
                                <table style="border: 1px solid;border-collapse: collapse; font-size: 16px; width: 95%;margin: 0 auto;">
                                    <tbody>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 40%;">Medical History</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['otherdata']['medical_history']))
                                                {!! $encounter['otherdata']['medical_history'] !!}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 40%;">Dental History</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['otherdata']['dental_history']))
                                                {!! $encounter['otherdata']['dental_history'] !!}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; padding:5px; width: 40%;">Notes</td>
                                        <td style="border: 1px solid; padding:5px;">
                                            @if(isset($encounter['otherdata']['dental_notes']))
                                                {!! $encounter['otherdata']['dental_notes'] !!}
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-eading">
                                <h3 style="text-align: center;">Finding:</h3>
                            </div>
                            <div class="table-boderless">
                                <table style="border: none; margin: 0 auto; width: 95%; margin-top: -23px;">
                                    <tbody>
                                    <tr>
                                        <td style="height: 40px;">
                                            Orthodontic Findings:
                                            @if(isset($encounter['orthodata']) and count($encounter['orthodata']) > 0)
                                                @foreach($encounter['orthodata'] as $odata)
                                                    <p><b>{{$odata->fldinput}} :</b> @if($odata->fldteeth !='')({{$odata->fldteeth}})@endif {{$odata->fldvalue}}</p>

                                                @endforeach
                                                <br/>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="height: 40px;">
                                            Cephalometric Findings:
                                            @if(isset($encounter['cephalometricData']) and count($encounter['cephalometricData']) > 0)
                                                @foreach($encounter['cephalometricData'] as $cdata)
                                                    <p><b>{{$cdata->fldinput}} :</b> @if($cdata->fldteeth !='')({{$cdata->fldteeth}})@endif {{$cdata->fldvalue}}</p>

                                                @endforeach
                                                <br/>
                                            @endif                                </td>
                                        <td>Advice:
                                            @if(isset($encounter['otherdata']['dental_advice']))
                                                {!! $encounter['otherdata']['dental_advice'] !!}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 40px;">
                                            Extra Laboratory:
                                            @if(isset($encounter['otherdata']['dental_extra_laboratory']))
                                                {!! $encounter['otherdata']['dental_extra_laboratory'] !!}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 40px;">
                                            Procedures:
                                            @if(isset($encounter['otherdata']['dental_procedures']))
                                                {!! $encounter['otherdata']['dental_procedures'] !!}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="height: 40px;">
                                            Allergy:
                                            @if(isset($encounter['patdrug']))
                                                <ul>
                                                    @foreach($encounter['patdrug'] as $pd)
                                                        <li>{{$pd->fldcode}}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 40px;">
                                            Diagnosis:
                                            @if(isset($encounter['patdiago']) and count($encounter['patdiago']) > 0)
                                                <ul>
                                                    @foreach($encounter['patdiago'] as $pg)
                                                        <li>{{$pg->fldcode}}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 40px;">Follow up: {{$encounters[0]['encounter_detail']->fldfollowdate}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="signaturediv" style="width: 20%; float: right; margin-top: 2%;">
                        <img src="">
                        <label>Dr. XYZ</label><br>
                        <label style="font-weight: bold;">Dental Surgeon</label><br>
                        <label style="font-weight: bold;">NMC:123456</label><br>
                        <label>Signature</label>
                    </div>
                @endif
                <div class="page-break"></div>
            @endforeach


        </div>
    @endif
    @php
        $signatures = Helpers::getSignature('history-dental');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
