<!DOCTYPE html>
<html>

<head>
    <title>History Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }
    </style>
</head>

<body>
@include('pdf-header-footer.header-footer')
<main>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p>Name: {{ Options::get('system_patient_rank')  == 1 && (isset($encounters[0]['encounter_detail'])) && (isset($encounters[0]['encounter_detail']->fldrank) ) ?$encounters[0]['encounter_detail']->fldrank:''}} {{ $patientinfo->fldptnamefir . ' ' . $patientinfo->fldmidname . ' ' . $patientinfo->fldptnamelast }} ({{$patientinfo->fldpatientval}})</p>
                <p>Age/Sex: {{ \Carbon\Carbon::parse($patientinfo->fldptbirday??"")->age }}yrs/{{ $patientinfo->fldptsex??"" }}</p>
                <p>Address: {{ $patientinfo->fldptaddvill??"" . ', ' . $patientinfo->fldptadddist??"" }}</p>
                <p>REPORT: {{$title}}</p>
            </td>
            <td style="width: 185px;">

                <p>DOReg: {{ $encounters[0]['encounter_detail']->fldregdate ? \Carbon\Carbon::parse(  $encounters[0]['encounter_detail']->fldregdate)->format('d/m/Y'):'' }}</p>
                <p>Phone: {{ $patientinfo->fldptcontact??"" }}</p>
            </td>
            <td style="width: 130px;">{!! Helpers::generateQrCode($patientinfo->fldpatientval)!!}</td>
        </tr>
        </tbody>
    </table>


    @if($encounters)
        @foreach($encounters as $encounter)
            @php
            $encounter_detail = $encounter['encounter_detail'];
            @endphp
            @if($encounter['form_type'] == 'normal')
                @php
                    $iterationCount = $loop->iteration;


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

            @if($encounter['form_type'] == 'ent')
                <style type="text/css">
                    .img-eye img {
                        width: 20%;
                        height: auto;
                        position: absolute;
                        margin-bottom: 20px;
                    }
                </style>
                <div class="pdf-container">
                    <div class="row">
                        <div class="table-chief" style="margin-top: 16px;">

                            <table class="table content-body" style="border: 1px solid;border-collapse: collapse; width: 80%; margin: 0 auto; font-size: 18px;">
                                <tbody>
                                <tr>
                                    <td colspan="2" style="border: 1px solid; padding:5px; width: 45%;">Chief Complaints{{ $encounter_detail->col }}</td>
                                    <td style="border: 1px solid; padding:5px;">
                                        @if(isset($encounter['complaint']))
                                            @foreach($encounter['complaint'] as $comp)
                                                <li>{{$comp->flditem}} || <strong>{{$comp->fldreportquali}}</strong></li>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid; padding:5px;">Systemic Illness</td>
                                    <td style="border: 1px solid; padding:5px;">{!! (isset($encounter['systemic_illiness']->fldreportquali)) ? strip_tags($encounter['systemic_illiness']->fldreportquali) : '' !!}</td>
                                </tr>
                                <tr>
                                    <td rowspan="2" style="border: 1px solid; padding:5px;">Allergy</td>
                                    <td style="border: 1px solid; padding:5px;">Drug</td>
                                    <td style="border: 1px solid; padding:20px;">
                                        @if(isset($encounter['allergy']))
                                            @foreach($encounter['allergy'] as $a)
                                                <li>{{ $a->fldcode}}</li>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid; padding:5px;">General</td>
                                    <td style="border: 1px solid; padding:5px;"></td>
                                </tr>
                                <tr>
                                    <td rowspan="2" style="border: 1px solid; padding:5px;">History</td>
                                    <td style="border: 1px solid; padding:5px;">Past History</td>
                                    <td style="border: 1px solid; padding:5px;">{!! (isset($encounter['history_past']->fldreportquali)) ? $encounter['history_past']->fldreportquali : '' !!}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid; padding:5px;">Family History</td>
                                    <td style="border: 1px solid; padding:5px;">{!! (isset($encounter['history_family']->fldreportquali)) ? $encounter['history_family']->fldreportquali : '' !!}</td>
                                </tr>
                                <tr>
                                    <td rowspan="2" style="border: 1px solid; padding:5px;">On Examination</td>
                                    <td style="border: 1px solid; padding:5px;">Right</td>
                                    <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['exam_right']->fldreportquali)) ? $encounter['exam_right']->fldreportquali : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid; padding:5px;">Left</td>
                                    <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['exam_left']->fldreportquali)) ? $encounter['exam_left']->fldreportquali : '' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid; padding:5px;">Current Medication</td>
                                    <td style="border: 1px solid; padding:5px;">{!! (isset($encounter['current_medication']->fldreportquali)) ? strip_tags($encounter['current_medication']->fldreportquali) : '' !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid; padding:5px;">Digonosis</td>
                                    <td style="border: 1px solid; padding:5px;"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="img-table" style="width: 100%; margin-bottom: 5%;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr>
                                <td rowspan="8">
                                    <div class="img-eye" style="height: 250px">
                                        <img src="{{ asset('assets/images/pdfeye.jpg')}}" style="width: 170;">
                                        @if(isset($encounter['eyeimage']) && isset($encounter['eyeimage']->left_eye))
                                            <img src="{{ $encounter['eyeimage']->left_eye }}" style="width: 170;">
                                        @endif
                                    </div>
                                </td>
                                <td colspan="3" style="width: 35%; padding:20px;"></td>
                                <td rowspan="8" style=" text-align: center;">
                                    <div class="img-eye" style="height: 250px">
                                        <img src="{{ asset('assets/images/pdfeye.jpg')}}" style="width: 170;">
                                        @if(isset($encounter['eyeimage']) && isset($encounter['eyeimage']->right_eye))
                                            <img src="{{ $encounter['eyeimage']->right_eye }}" style="width: 170;">
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid; text-align: center;">RE</td>
                                <td style="border: 1px solid; text-align: center;">Distance</td>
                                <td style="border: 1px solid; text-align: center;">LE</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid;">{{ (isset($encounter['unaided_distance_RE']->fldreading)) ? $encounter['unaided_distance_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; text-align: center;">Unaided</td>
                                <td style="border: 1px solid;">{{ (isset($encounter['unaided_distance_LE']->fldreading)) ? $encounter['unaided_distance_LE']->fldreading : '' }}</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid;">{{ (isset($encounter['aided_distance_RE']->fldreading)) ? $encounter['aided_distance_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; text-align: center;">Aided</td>
                                <td style="border: 1px solid;">{{ (isset($encounter['aided_distance_LE']->fldreading)) ? $encounter['aided_distance_LE']->fldreading : '' }}</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid;">{{ (isset($encounter['pinhole_distance_RE']->fldreading)) ? $encounter['pinhole_distance_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; text-align: center;">Pinhole</td>
                                <td style="border: 1px solid;">{{ (isset($encounter['pinhole_distance_LE']->fldreading)) ? $encounter['pinhole_distance_LE']->fldreading : '' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-content" style="width: 100%;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr>
                                <td colspan="2" style="padding:5px;border: 1px solid; "></td>
                                <td style="border: 1px solid; padding:5px;">Spherical</td>
                                <td style="border: 1px solid; padding:5px;">Cylindrical</td>
                                <td style="border: 1px solid; padding:5px;">Axis</td>
                                <td style="border: 1px solid; padding:5px;">Vision</td>
                                <td rowspan="13" style="border: none; padding:5px; ">&nbsp;</td>
                                <td colspan="2" style="border: 1px solid; padding:5px; text-align: center;">RE</td>
                                <td style="border: 1px solid; padding:5px; width: 10%; text-align: center;">LOP</td>
                                <td colspan="2" style="border: 1px solid; padding:5px; text-align: center;">LE</td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="border: 1px solid; padding:5px; width: 20%">Auto Refraction</td>
                                <td style="border: 1px solid; padding:5px; width: 7%;">RE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['auto_reaction_spherical_RE']->fldreading)) ? $encounter['auto_reaction_spherical_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['auto_reaction_cylindrical_RE']->fldreading)) ? $encounter['auto_reaction_cylindrical_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['auto_reaction_axis_RE']->fldreading)) ? $encounter['auto_reaction_axis_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px; width: 5%;"></td>
                                <td style="border: 1px solid; padding:5px;">mmHg</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px; width: 5%;"></td>
                                <td style="border: 1px solid; padding:5px;">mmHg</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid; padding:5px;">LE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['auto_reaction_spherical_LE']->fldreading)) ? $encounter['auto_reaction_spherical_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['auto_reaction_cylindrical_LE']->fldreading)) ? $encounter['auto_reaction_cylindrical_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['auto_reaction_axis_LE']->fldreading)) ? $encounter['auto_reaction_axis_LE']->fldreading : ''  }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['AT_RE']->fldreadingprefix)) ? $encounter['AT_RE']->fldreadingprefix : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['AT_RE']->fldreading)) ? $encounter['AT_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px; text-align: center;">AT</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['AT_LE']->fldreadingprefix)) ? $encounter['AT_LE']->fldreadingprefix : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['AT_LE']->fldreading)) ? $encounter['AT_LE']->fldreading : '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="border: 1px solid; padding:5px;">Add</td>
                                <td style="border: 1px solid; padding:5px;">RE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['add_spherical_RE']->fldreading)) ? $encounter['add_spherical_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['add_vision_RE']->fldreading)) ? $encounter['add_vision_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['NCT_RE']->fldreadingprefix)) ? $encounter['NCT_RE']->fldreadingprefix : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['NCT_RE']->fldreading)) ? $encounter['NCT_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px; text-align: center;">NCT</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['NCT_LE']->fldreadingprefix)) ? $encounter['NCT_LE']->fldreadingprefix : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['NCT_LE']->fldreading)) ? $encounter['NCT_LE']->fldreading : '' }}</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid; padding:5px;">LE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['add_spherical_LE']->fldreading)) ? $encounter['add_spherical_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['add_vision_LE']->fldreading)) ? $encounter['add_vision_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['SA_RE']->fldreadingprefix)) ? $encounter['SA_RE']->fldreadingprefix : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['SA_RE']->fldreading)) ? $encounter['SA_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px; text-align: center;">SA</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['SA_LE']->fldreadingprefix)) ? $encounter['SA_LE']->fldreadingprefix : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['SA_LE']->fldreading)) ? $encounter['SA_LE']->fldreading : '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="border: 1px solid; padding:5px;">Acceptance</td>
                                <td style="border: 1px solid; padding:5px;">RE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_spherical_RE']->fldreading)) ? $encounter['acceptance_spherical_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_cylindrical_RE']->fldreading)) ? $encounter['acceptance_cylindrical_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_axis_RE']->fldreading)) ? $encounter['acceptance_axis_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_vision_RE']->fldreading)) ? $encounter['acceptance_vision_RE']->fldreading : '' }}</td>
                                <td colspan="5" style="border: 1px solid; padding:9px; background-color: #eee;"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid; padding:5px;">LE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_spherical_LE']->fldreading)) ? $encounter['acceptance_spherical_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_cylindrical_LE']->fldreading)) ? $encounter['acceptance_cylindrical_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_axis_LE']->fldreading)) ? $encounter['acceptance_axis_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['acceptance_vision_LE']->fldreading)) ? $encounter['acceptance_vision_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['schimers_test_type_I_RE']->fldreading)) ? $encounter['schimers_test_type_I_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px; text-align: center;">Schir-I</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['schimers_test_type_I_LE']->fldreading)) ? $encounter['schimers_test_type_I_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="border: 1px solid; padding:5px;">Previous Glass Prescription(PGP)</td>
                                <td style="border: 1px solid; padding:5px;">RE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['PGP_spherical_RE']->fldreading)) ? $encounter['PGP_spherical_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['PGP_cylindrical_RE']->fldreading)) ? $encounter['PGP_cylindrical_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['PGP_axis_RE']->fldreading)) ? $encounter['PGP_axis_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['schimers_test_type_II_RE']->fldreading)) ? $encounter['schimers_test_type_II_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;text-align: center;">Schir-II</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['schimers_test_type_II_LE']->fldreading)) ? $encounter['schimers_test_type_II_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid; padding:5px;">LE</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['PGP_spherical_LE']->fldreading)) ? $encounter['PGP_spherical_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['PGP_cylindrical_LE']->fldreading)) ? $encounter['PGP_cylindrical_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['PGP_axis_LE']->fldreading)) ? $encounter['PGP_axis_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['schimers_test_type_III_RE']->fldreading)) ? $encounter['schimers_test_type_III_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px; text-align: center;">Schir-III</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['schimers_test_type_III_LE']->fldreading)) ? $encounter['schimers_test_type_III_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>

                            </tr>
                            <tr>
                                <td rowspan="2" style="border: 1px solid; padding:5px;">Color Vision</td>
                                <td style="border: 1px solid; padding:5px;">RE</td>
                                <td colspan="4" style="border: 1px solid; padding:5px;">{{ (isset($encounter['color_vision_axis_RE']->fldreading)) ? $encounter['color_vision_axis_RE']->fldreading : '' }}
                                </td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['k_reading_k_I_RE']->fldreading)) ? $encounter['k_reading_k_I_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;text-align: center;">K1</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['k_reading_k_I_LE']->fldreading)) ? $encounter['k_reading_k_I_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid; padding:5px;">LE</td>
                                <td colspan="4" style="border: 1px solid; padding:5px;">{{ (isset($encounter['color_vision_axis_LE']->fldreading)) ? $encounter['color_vision_axis_LE']->fldreading : '' }}
                                </td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['k_reading_k_II_RE']->fldreading)) ? $encounter['k_reading_k_II_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;text-align: center;">K2</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['k_reading_k_II_LE']->fldreading)) ? $encounter['k_reading_k_II_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                            </tr>
                            <tr>
                                <td style="border: none; padding:5px;"></td>
                                <td style="border: none; padding:5px;"></td>
                                <td style="border: none; padding:5px;"></td>
                                <td style="border: none; padding:5px;"></td>
                                <td style="border: none; padding:5px;"></td>
                                <td style="border: none; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['k_reading_k_III_RE']->fldreading)) ? $encounter['k_reading_k_III_RE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                                <td style="border: 1px solid; padding:5px;text-align: center;">K3</td>
                                <td style="border: 1px solid; padding:5px;">{{ (isset($encounter['k_reading_k_III_LE']->fldreading)) ? $encounter['k_reading_k_III_LE']->fldreading : '' }}</td>
                                <td style="border: 1px solid; padding:5px;"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="table-last" style="margin-top: 16px;">
                            <table style="border: 1px solid;border-collapse: collapse; font-size: 18px; width: 99%;margin: 0 auto;">
                                <tbody>
                                <tr>
                                    <td style="border: 1px solid; padding:5px; width: 25%;">Lab Test Advised</td>
                                    <td style="border: 1px solid; padding:5px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid; padding:5px;">Radio Examination Advised</td>
                                    <td style="border: 1px solid; padding:5px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid; padding:5px;">Treatment Advised</td>
                                    <td style="border: 1px solid; padding:5px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid; padding:5px;">Notes</td>
                                    <td style="border: 1px solid; padding:5px;">{!! (isset($encounter['note']->fldreportquali)) ? strip_tags($encounter['note']->fldreportquali) : '' !!}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid; padding:5px;">Advised</td>
                                    <td style="border: 1px solid; padding:5px;">{!! (isset($encounter['advice']->fldreportquali)) ? strip_tags($encounter['advice']->fldreportquali) : '' !!}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    @php
        $signatures = Helpers::getSignature('ent');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>

</html>
