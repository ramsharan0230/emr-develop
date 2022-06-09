<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPD Sheet</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        p {
            margin: 2px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            font-size: small;
            color: #333333;
        }

        .container {
            padding: 0 20px 0 20px;
        }

        .flex-row {
            display: flex;
            flex-direction: row;
        }

        .flex-column {
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #cccccc;
        }

        h2,
        h2 {
            margin: 0;
        }

        img {
            height: 100px;
            width: 100px;
            background-color: #cccccc;
            margin: 10px;
        }

        .title-address {
            font-size: large;
            font-weight: 700;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-right {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            height: 70px;
            min-width: 120px;
        }

        .texts {
            font-weight: 400;
            font-size: small;
        }

        .date {
            float: right;
            margin: 10px 0 10px 0;
        }

        .body-head {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
        }

        .head-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            height: 60px;
        }

        .top,
        .mid,
        .bottom {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        hr {
            color: #cccccc;
        }

        .acc {
            font-size: x-small;
            margin: 10px 0 10px 0;
        }

        .details {
            width: 60%;
            margin-right: 50px;
            justify-content: space-between;
            line-height: 1.5;
        }

        .list {
            width: 40%;
        }

        .handed {
            height: 255px;
            justify-content: space-between;
        }

        .complaints,
        .findings,
        .investigations,
        .diagnosis,
        .advice {
            margin-bottom: 10px;
        }

        span {
            margin-top: 5px;
            margin-left: 5px;
        }

        .medicines {
            margin-top: 30px;
        }

        .med {
            margin-top: 5px;
        }

        .follow-up {
            margin-top: 30px;
            font-size: 14px;
            border: 1px solid #cccccc;
            padding: 5px;
        }

    </style>
</head>
@php
$patientInfo = $encounterData->patientInfo;
$iterationCount = 1;
@endphp

<body>
    <div class="container">
        <div class="header">
            <img src="logo-social.png" alt="Logo" src="{{ asset('uploads/config/' . Options::get('brand_image')) }}">
            <div class="title-address">
                {{-- <span>Neuro and Allied Clinic</span> --}}
                {{-- <span>Delivering Holistic Care</span> --}}
                <span>{{ isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '' }}</span>
                <span>{{ ucfirst($certificate) }} REPORT</span>
                <span
                    style="font-weight: 400; font-size: small; margin-top: 20px;">{{ isset(Options::get('siteconfig')['system_address']) ? Options::get('siteconfig')['system_address'] : '' }}</span>
            </div>
            <div class="header-right">
                <div>Phone:
                    {!! isset(Options::get('siteconfig')['system_telephone_no']) ? Options::get('siteconfig')['system_telephone_no'] : '' !!}
                </div>
                <div>PAN: {!! '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' !!}</div>
                <div>Reg. no.: {!! '' !!}</div>
            </div>
        </div>
        <div class="body-content">
            <div class="body-head">
                {{-- <div class="date">
                    <b>Date: {!! $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y') : '' !!}</b>
                </div> --}}
                <div class="head-details">
                    <div class="top">
                        <div style="width: 25%;">
                            <b>Name:</b>
                            {{ Options::get('system_patient_rank') == 1 && isset($encounterData) && isset($encounterData->fldrank) ? $encounterData->fldrank : '' }}
                            {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }}
                            ({{ $patientInfo->fldpatientval }})
                        </div>
                        <div style="width: 25%;"><b>Address:</b> {{ $patientInfo->fldptaddvill ? $patientInfo->fldptaddvill . ', ' : '' }}{{ $patientInfo->fldptadddist ?? '' }}</div>
                        {{-- <div style="width: 25%;"><b>Religion:</b> {!! $patientInfo->fldreligion ?? '' !!}</div> --}}
                        <div style="width: 25%;"><b>Date:</b> {!! $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y') : '' !!}</div>
                    </div>
                    <div class="mid">
                        <div style="width: 25%;"><b>Phone</b>: {{ $patientInfo->fldptcontact ?? '' }}</div>
                        <div style="width: 25%;"><b>H/O Allergy:</b> {{ $allergy_drugs->count() > 0 ? 'Yes' : 'No' }}</div>
                        <div style="width: 25%;"><b>Reg no.:</b> {{ $patientInfo->fldpatientval ?? '' }}</div>
                        {{-- <div style="width: 25%;"><b>Marital Status:</b> {{ $patientInfo->fldmaritalstatus ?? '' }}</div> --}}
                    </div>
                    <div class="bottom">
                        <div style="width: 25%;"><b>Age/Gender:</b> {{ $patientInfo->fldptbirday ? \Carbon\Carbon::parse($patientInfo->fldptbirday)->age . 'yrs/' : '' }}{{ $patientInfo->fldptsex ?? '' }}</div>
                        <div style="width: 25%;"><b>Id no.:</b> {{ $encounterId ?? '' }}</div>
                        <div style="width: 25%;"><b>Ref. by:</b> {{ $Consultations && $Consultations->count() > 0 && $Consultations[0]->user ? $Consultations[0]->user->fullname : '' }}</div>
                        {{-- <div style="width: 25%;"></div> --}}
                    </div>
                </div>
            </div>
            <hr />

            <div class="body-details">
                <div class="acc">
                    Acc by:
                </div>
                <div class="contents">
                    <div class="details flex-column">
                        @if (isset($CourseofTreatment) && count($CourseofTreatment))
                            <div class="complaints flex-column">
                                @foreach ($CourseofTreatment as $key=>$symptoms)
                                    @if($symptoms->fldcomment)
                                        @if($key==0)
                                        <b>Course of Treatment</b>
                                        @endif
                                        <span>{{ $symptoms->fldtime }}: {{ $symptoms->fldcomment }}</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if (isset($bed) && count($bed))
                            <div class="findings flex-column">
                                <b>Bed Transitions</b>
                                @foreach ($bed as $b)
                                    <span>{{ $b->flditem }}</span>
                                    <span>{{ $b->fldfirsttime }}</span>
                                    <span>{{ $b->fldsecondtime }}</span>
                                    <span>{{ $b->fldsecondreport }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($EssentialExaminations) && count($EssentialExaminations))
                            <div class="investigations flex-column">
                                <b>OPD Examinations</b>
                                @for ($i = 0; $i < count($EssentialExaminations['fldhead']); $i++)
                                    <span>{{ $EssentialExaminations['fldhead'][$i] }}{{ !is_array($EssentialExaminations['fldrepquali'][$i]) ? ':' . $EssentialExaminations['fldrepquali'][$i] : '' }}</span>
                                    @if (is_array($EssentialExaminations['fldrepquali'][$i]) && count($EssentialExaminations['fldrepquali'][$i]) > 1)
                                        <table border="1px" rules="all" style="width: 60%;">
                                            <tr>
                                                @foreach ($EssentialExaminations['fldrepquali'][$i] as $row => $val)

                                                    <th>{{ $row }}</th>

                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach ($EssentialExaminations['fldrepquali'][$i] as $row => $val)
                                                    <td>{{ $val }}</td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @endif
                                @endfor
                            </div>
                        @endif
                        @if (isset($demographics) && count($demographics))
                            <div class="diagnosis flex-column">
                                <b>Demographics</b>
                                @foreach ($demographics as $b)
                                    <span>{{ $b->flditem }}</span>
                                    <span>Date:{{ Carbon\Carbon::parse($b->fldfirsttime)->format('Y/m/d l h:i:s') }}</span>
                                    <span>{{ $b->fldreportquali }}</span>
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($triage_examinations) && count($triage_examinations))
                            <div class="diagnosis flex-column">
                                <b>Triage Examinations </b>
                                @foreach ($triage_examinations as $b)
                                    <span>{{ $b->fldhead }}</span>
                                    <span>Date:{{ Carbon\Carbon::parse($b->fldtime)->format('Y/m/d l h:i:s') }}
                                    </span>
                                    <span>{{ $b->fldrepquali }}</span>
                                    <span>{{ $b->fldrepquanli }}</span>
                                    <span>{{ $b->fldtype }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($cause_of_admission) && count($cause_of_admission))
                            <div class="diagnosis flex-column">
                                <b>Cause of Admission</b>
                                @foreach ($cause_of_admission as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($present_symptoms) && count($present_symptoms))
                            <div class="diagnosis flex-column">
                                <b>Presenting Complaints</b>
                                @foreach ($present_symptoms as $b)
                                    <span>Date:{{ Carbon\Carbon::parse($b->fldtime)->format('Y/m/d w h:i:s') }}</span>
                                    <span>
                                        {{ $b->flditem }} : @if ($b->fldreportquanti <= 24) {{ $b->fldreportquanti }} hr @endif @if ($b->fldreportquanti > 24 && $b->fldreportquanti <= 720) {{ round($b->fldreportquanti / 24, 2) }} Days @endif @if ($b->fldreportquanti > 720 && $b->fldreportquanti < 8760)
                                            {{ round($b->fldreportquanti / 720, 2) }}
                                            Months @endif @if ($b->fldreportquanti >= 8760) {{ round($b->fldreportquanti / 8760) }} Years @endif
                                        {{ $b->fldreportquali }} {{ strip_tags(strip_tags($b->flddetail)) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($patientExam) && count($patientExam))
                            <div class="diagnosis flex-column">
                                <b>OPD Examinations</b>
                                @for ($i = 0; $i < count($patientExam['fldhead']); $i++)
                                    <span>{{ $patientExam['fldhead'][$i] }}{{ !is_array($patientExam['fldrepquali'][$i]) ? ':' . $patientExam['fldrepquali'][$i] : '' }}</span>
                                    @if (is_array($patientExam['fldrepquali'][$i]) && count($patientExam['fldrepquali'][$i]) > 1)
                                        <table border="1px" rules="all" style="width: 60%;">
                                            <tr>
                                                @foreach ($patientExam['fldrepquali'][$i] as $row => $val)

                                                    <th>{{ $row }}</th>

                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach ($patientExam['fldrepquali'][$i] as $row => $val)
                                                    <td>{{ $val }}</td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @endif
                                @endfor
                            </div>
                        @endif
                        @if (isset($general_complaints) && count($general_complaints))
                            <div class="diagnosis flex-column">
                                <b>General Complaints </b>
                                @foreach ($general_complaints as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($history_illness) && count($history_illness))
                            <div class="diagnosis flex-column">
                                <b>History of Illness</b>
                                @foreach ($history_illness as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($past_history) && count($past_history))
                            <div class="diagnosis flex-column">
                                <b>Past History </b>
                                @foreach ($past_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($treatment_history) && count($treatment_history))
                            <div class="diagnosis flex-column">
                                <b>Treatment History </b>
                                @foreach ($treatment_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($medicated_history) && count($medicated_history))
                            <div class="diagnosis flex-column">
                                <b>Medication History </b>
                                @foreach ($medicated_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($family_history) && count($family_history))
                            <div class="diagnosis flex-column">
                                <b>Family History </b>
                                @foreach ($family_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($personal_history) && count($personal_history))
                            <div class="diagnosis flex-column">
                                <b>Personal History </b>
                                @foreach ($personal_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($surgical_history) && count($surgical_history))
                            <div class="diagnosis flex-column">
                                <b>Surgical History </b>
                                @foreach ($surgical_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($occupational_history) && count($occupational_history))
                            <div class="diagnosis flex-column">
                                <b>Occupational History </b>
                                @foreach ($occupational_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($social_history) && count($social_history))
                            <div class="diagnosis flex-column">
                                <b>Social History</b>
                                @foreach ($social_history as $b)
                                    <span>{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($allergy_drugs) && count($allergy_drugs))
                            <div class="diagnosis flex-column">
                                <b>Drug Allergy</b>
                                @foreach ($allergy_drugs as $b)
                                    <span>{{ $b->fldcode }} : {{ $b->fldcodeid }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($provisinal_diagnosis) && count($provisinal_diagnosis))
                            <div class="diagnosis flex-column">
                                <b>Provisional Diagnosis</b>
                                @foreach ($provisinal_diagnosis as $b)
                                    <span>[{{ $b->fldcodeid }}] {{ $b->fldcode }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($initial_planning) && count($initial_planning))
                            
                                @foreach ($initial_planning as $b)
                                    @if($b->fldinput=='History')
                                    <div class="diagnosis flex-column">
                                        <b>History</b>
                                        <span>{!! $b->flddetail !!}</span>
                                    </div>
                                    @endif
                                    @if($b->fldinput=='Notes')
                                    <div class="diagnosis flex-column">
                                        <b>Notes</b>
                                        <span>{!! $b->flddetail !!}</span>
                                    </div>
                                    @endif
                                    @if($b->fldinput=='Sensitive Note')
                                    <div class="diagnosis flex-column">
                                        <b>Sensitive Note</b>
                                        <span>{!! $b->flddetail !!}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if (isset($final_diagnosis) && count($final_diagnosis))
                            <div class="diagnosis flex-column">
                                <b>Final Diagnosis </b>
                                @foreach ($final_diagnosis as $b)
                                    <span>{{ $b->fldcode }} : {{ $b->fldcodeid }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($prominent_symptoms) && count($prominent_symptoms))
                            <div class="diagnosis flex-column">
                                <b>Prominent Symptoms </b>
                                @foreach ($prominent_symptoms as $b)
                                    <span>
                                        Date: {{ $b->fldtime }} :: {{ $b->flditem }} : {{ $b->fldreportquali }}
                                        ,{{ strip_tags($b->flddetail) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($procedures) && count($procedures))
                            <div class="diagnosis flex-column">
                                <b>Major Procedures </b>
                                @foreach ($procedures as $b)
                                    <span>{{ $b->fldnewdate }} ::
                                        {{ $b->flditem }}{{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($minor_procedure) && count($minor_procedure))
                            <div class="diagnosis flex-column">
                                <b>Minor Procedures </b>
                                @foreach ($minor_procedure as $b)
                                    <span>{{ $b->fldnewdate }} :: {{ $b->flditem }}
                                        {{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($Consultations) && count($Consultations))
                            <div class="diagnosis flex-column">
                                @foreach ($Consultations as $key=>$b)
                                    @if($b->fldconsultname)
                                        @if($key==0)
                                        <b>Consultations</b>
                                        @endif
                                        <span>Date: {{ $b->fldconsulttime }} :: {{ $b->fldconsultname }}({{ $b->fldstatus }})</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if (isset($equipment) && count($equipment))
                            <div class="diagnosis flex-column">
                                <p>Equipments Used </p>
                                @foreach ($equipment as $b)
                                    <span>
                                        {{ $b->flditem }} ,
                                        {{ $b->fldfirsttime }} ,
                                        {{ $b->fldsecondtime }} ,
                                        {{ $b->fldsecondreport }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($planned) && count($planned))
                            <div class="diagnosis flex-column">
                                <b>Extra Procedures </b>
                                @foreach ($planned as $b)
                                    <span>Date: {{ $b->fldnewdate }} :: {{ $b->flditem }} :
                                        {{ $b->detail }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($MedicationUsed) && count($MedicationUsed))
                            <div class="diagnosis flex-column">
                                <b>Treatment Advised</b>
                                @foreach ($MedicationUsed as $b)
                                    @if ($b->fldlevel == 'Requested')
                                        <span>{{ $b->flditem }} () {{ $b->fldroute }} {{ $b->flddose }} X
                                            {{ $b->flddays }} ({{ $b->fldfreq }})</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if (isset($confinement) && count($confinement))
                            <div class="diagnosis flex-column">
                                <b>Delivery Profile </b>
                                @foreach ($confinement as $b)
                                    <span>Delivery Date: {{ $b->flddeltime }}</span>
                                    <span>Delivery Type: {{ $b->flddeltype }}</span>
                                    <span>Delivery Result: {{ $b->flddelresult }}</span>
                                    <br>
                                    @if ($b->fldbabypatno != '' || $b->fldbabypatno != null)
                                        <span>Baby Patient No: {{ $b->fldbabypatno }}</span>
                                        <span>Baby Gender: {{ $b->flddeltime }}</span>
                                        <span>Baby Weight: {{ $b->flddelwt }} grams</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if (isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))
                            <div class="diagnosis flex-column">
                                <b> Clinical Findings </b>
                                @foreach ($ClinicianPlanPatPlanning as $b)
                                    <span>
                                        Date: {{ $b->fldtime }}
                                        {{ $b->fldproblem }} ,
                                        {{ $b->fldsubjective }} ,
                                        {{ $b->fldobjective }} ,
                                        {{ $b->fldassess }} ,
                                        {{ $b->fldplan }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))
                            <div class="diagnosis flex-column">
                                <b> Clinical Planes </b>
                                @foreach ($ClinicianPlanPatPlanning as $b)
                                    <span>{{ $b->fldtime }}</span>
                                    <span>Problem: {{ $b->fldproblem }}</span>
                                    @if ($b->fldsubjective != '' || $b->fldsubjective != null)
                                        <span>Subjective: {{ $b->fldsubjective }}</span>
                                    @endif
                                    @if ($b->fldsubjective != '' || $b->fldsubjective != null)
                                        <span>Subjective: {{ $b->fldsubjective }}</span>
                                    @endif
                                    @if ($b->fldobjective != '' || $b->fldobjective != null)
                                        <span>Objective: {{ $b->fldobjective }}</span>
                                    @endif
                                    @if ($b->fldassess != '' || $b->fldassess != null)
                                        <span>Assessment: {{ $b->fldassess }}</span>
                                    @endif
                                    @if ($b->fldplan != '' || $b->fldplan != null)
                                        <span>Planning: {{ $b->fldplan }}</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if (isset($ClinicianPlanPatPlanning) && count($ClinicianPlanPatPlanning))
                            <div class="diagnosis flex-column">
                                <b> Therapeutic Planning </b>
                                @foreach ($ClinicianPlanPatPlanning as $b)
                                    <h5>{{ $b->fldproblem . ' ' . $b->fldtime }}</h5>
                                    @if ($b->fldsubjective != '' || $b->fldsubjective != null)
                                        <span>Route: {{ $b->fldsubjective }}</span>
                                    @endif
                                    @if ($b->fldobjective != '' || $b->fldobjective != null)
                                        <span>Route: {{ $b->fldobjective }}</span>
                                    @endif
                                    @if ($b->fldassess != '' || $b->fldassess != null)
                                        <span>Route: {{ $b->fldassess }}</span>
                                    @endif
                                    @if ($b->fldplan != '' || $b->fldplan != null)
                                        <span>Route: {{ $b->fldplan }}</span>
                                    @endif
                                    {!! !$loop->last ? '<hr>' : '' !!}
                                @endforeach
                            </div>
                        @endif
                        @if (isset($reportedPatLab) && count($reportedPatLab))
                            <div class="diagnosis flex-column">
                                <b> Laboratory </b>
                                @foreach ($reportedPatLab as $labValue)
                                    <span>{{ $labValue->fldtestid }} [Spec: {{ $labValue->fldsampletype }}]</span>
                                    @if ($labValue->fldreportquanti != null && $labValue->fldreportquanti != 0.0)
                                        <br>
                                        <span>{{ $iterationCount }}.. {{ $labValue->fldreportquanti }}</span>
                                    @endif
                                    <ul>
                                        @foreach ($labValue->subTest as $patTestResult)
                                            <li>{{ $patTestResult->fldsubtest }}:</li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($patRadioTest) && count($patRadioTest))
                            <div class="diagnosis flex-column">
                                <b> Radio Diagnostics </b>
                                @foreach ($patRadioTest as $radioValue)
                                    <span>{{ $iterationCount }}..</span>
                                    <br>
                                    <span>{!! $radioValue->fldtestid !!} :
                                        {!! $radioValue->fldreportquali !!}</span>
                                    <br>
                                    @if (count($radioValue->radioSubTest))
                                        @foreach ($radioValue->radioSubTest as $radioSubTestValue)
                                            <span>{!! $radioSubTestValue->fldsubtest !!}:{!! $radioSubTestValue->fldreport !!}</span>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if (isset($generalExamProgressCliniciansNurses) && count($generalExamProgressCliniciansNurses))
                            <div class="diagnosis flex-column">
                                <b> Clinical Notes </b>
                                @foreach ($generalExamProgressCliniciansNurses as $b)
                                    <span>{{ $b->fldtime }} :: {{ $b->flditem }} : {{ $b->fldreportquali }},
                                        {!! $b->flddetail !!}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($IPMonitoringPatPlanning) && count($IPMonitoringPatPlanning))
                            <div class="diagnosis flex-column">
                                <b> IP Monitoring </b>
                                @foreach ($IPMonitoringPatPlanning as $b)
                                    <span>
                                        {{ $b->fldtime }} :: {{ $b->fldproblem }} ,
                                        {{ $b->fldsubjective }} , {{ $b->fldobjective }} ,
                                        {{ $b->fldassess }} ,{{ $b->fldplan }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($patGeneral) && count($patGeneral))
                            <div class="diagnosis flex-column">
                                <b> Planned Procedures </b>
                                @foreach ($patGeneral as $b)
                                    <span>Date :: {{ $b->flditem }} : {{ $b->flddetail }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($DischargeExaminationspatientExam) && count($DischargeExaminationspatientExam))
                            <div class="diagnosis flex-column">
                                <b> Discharge Examinations </b>
                                @foreach ($DischargeExaminationspatientExam as $b)
                                    <span>{{ $b->fldtime }} :: {{ $b->fldhead }} :
                                        {{ $b->fldrepquali }}{{ $b->fldrepquanti }}
                                        ,{{ $b->fldtype }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($ConditionOfDischargeExamGeneral) && count($ConditionOfDischargeExamGeneral))
                            <div class="diagnosis flex-column">
                                <b> Condition at Discharge </b>
                                @foreach ($ConditionOfDischargeExamGeneral as $b)
                                    <span>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($DischargedLAMADeathReferAbsconderPatDosing) && count($DischargedLAMADeathReferAbsconderPatDosing))
                            <div class="diagnosis flex-column">
                                <b> Discharge Medication </b>
                                @foreach ($DischargedLAMADeathReferAbsconderPatDosing as $b)
                                    <span>{{ $b->flditem }} () {{ $b->fldroute }} {{ flddose }} X
                                        {{ $b->flddays }} ({{ $b->fldfreq }})</span>
                                    <span>{{ $b->flditemtype }}</span>
                                @endforeach
                                </td>
                                </tr>
                        @endif
                        @if (isset($AdviceOfDischargeExamGeneral) && count($AdviceOfDischargeExamGeneral))
                            <div class="diagnosis flex-column">
                                <b> Advice on Discharge </b>
                                @foreach ($AdviceOfDischargeExamGeneral as $b)
                                    <span>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="lists">
                        {{-- <div class="handed flex-column">
                            <div class="title"><b>Handedeness</b></div>
                            <div class="onset">Onset: Acute / subacute / insidious </div>
                            <div class="status">Status: Walking / WC / Bedridden</div>
                            <div class="pdiv">P: MR / IR</div>
                            <div class="bp">BP (mmHg):</div>
                            <div class="ht">Ht. (m)</div>
                            <div class="wt">Wt. (kg):</div>
                            <div class="bmi">BMI (Kg/m2):</div>
                            <div class="htm">HTN: Y / N</div>
                            <div class="dm">DM: Y/N</div>
                            <div class="diet">Diet: Veg / Non-veg</div>
                        </div> --}}
                        <div class="medicines flex-column">
                            @if (isset($MedicationUsed) && count($MedicationUsed))
                               
                                @php $medcount = 0; @endphp
                                @foreach ($MedicationUsed as $key=>$b)
                               
                                    @if ($b->fldlevel == 'Dispensed')
                                    @if($key==0)
                                        <div class="title" style="margin-bottom: 2px;"><b>List of Current Medicines</b>
                                        </div>
                                    @endif
                                        <div class="med">{{ ++$medcount }}. {{ $b->flditem }} ()
                                            {{ $b->fldroute }} {{ $b->flddose }} X {{ $b->flddays }}
                                            ({{ $b->fldfreq }})</div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @if(isset($encounterData->fldfollowdate))
                        <div class="follow-up">
                            <b>Physiotherapy Next Assessment date:</b> {{ $encounterData->fldfollowdate ?? '' }}
                        </div>
                        @endif
                        @if(isset($physiotherapy_history->flddetail))
                        <div class="follow-up">
                            <b>Physiotherapy History:</b> {!! $physiotherapy_history->flddetail ?? '' !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="footer">

            </div>
        </div>
    </div>
</body>

</html>
