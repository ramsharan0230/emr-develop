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
                <p>Age/Sex: {{ $patientinfo->fldagestyle }} /{{ $patientinfo->fldptsex??"" }}</p>
                {{-- <p>Age/Sex: {{ \Carbon\Carbon::parse($patientinfo->fldptbirday??"")->age }}yrs/{{ $patientinfo->fldptsex??"" }}</p> --}}
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
                $iterationCount = $loop->iteration;

                    $encounter_detail = $encounter['encounter_detail'];


                   // $encounter['patientSerialValue'];
                    //$encounter['AntenatalExam3rd'];

                    $systolic_bp = $encounter['systolic_bp'];
                    $diasioli_bp = $encounter['diasioli_bp'];
                    $pulse = $encounter['pulse'];
                    $temperature = $encounter['temperature'];
                    $respiratory_rate = $encounter['respiratory_rate'];
                    $o2_saturation = $encounter['o2_saturation'];


                    //$encounter['AnkleJerkPatientExam'];
                    //$encounter['ADRProbabilityScalePatientExam'];
                    //$encounter['AbdominalGirthPatientExam'];
                    //$encounter['AbdomenExaminationPatientExam'];
                    //$encounter['ActivityPatientExam'];
                    //$encounter['LocalExaminationPatientExam'];
                    //$encounter['BreastFeedingExaminationPatientExam'];







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
                @if(!empty($patient_date))
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
                @if(!empty($bed))
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
                @if(!empty($demographics))
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
                @if(!empty($triage_examinations))
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
                @if(!empty($cause_of_admission))
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
                @if(!empty($present_symptoms))
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
                @if(!empty($patientExam))
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
                @if(!empty($general_complaints))
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

                @if(!empty($history_illness))
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
                @if(!empty($past_history))
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
                @if(!empty($treatment_history))
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

                @if(!empty($medicated_history))
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
                @if(!empty($family_history))
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
                @if(!empty($personal_history))
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
                @if(!empty($surgical_history))
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
                @if(!empty($occupational_history))
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

                @if(!empty($social_history))
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
                @if(!empty($allergy_drugs))
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
                @if(!empty($provisinal_diagnosis))
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
                @if(!empty($initial_planning))
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
                @if(!empty($final_diagnosis))
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
                @if(!empty($prominent_symptoms))
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
                @if(!empty($procedures))
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

                @if(!empty($minor_procedure))
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

                @if(!empty($consult))
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
                @if(!empty($equipment))
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
                @if(!empty($planned))
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
                @if(!empty($mainDataForPatDosing))
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

                @if(!empty($mainDataForPatDosing))

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
                @if(count($confinement) && !empty($confinement))
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
                @if(!empty($ClinicianPlanPatPlanning))
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
                @if(count($reportedPatLab) && !empty($reportedPatLab))
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
                @if(count($patRadioTest) && !empty($patRadioTest))
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
                @if(!empty($ClinicianPlanPatPlanning))
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
                @if(!empty($generalExamProgressCliniciansNurses))
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
                @if(!empty($IPMonitoringPatPlanning))
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
                @if(count($ClinicianPlanPatPlanning) && !empty($ClinicianPlanPatPlanning))
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

                @if(!empty($patGeneral))
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
                @if(!empty($DischargeExaminationspatientExam))
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
                @if(!empty($ConditionOfDischargeExamGeneral))
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
                @if(!empty($DischargedLAMADeathReferAbsconderPatDosing))
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
                @if(!empty($AdviceOfDischargeExamGeneral))
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
@endforeach
@endif
</main>
</body>

</html>
