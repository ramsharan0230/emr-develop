@extends('inpatient::pdf.layout.main')

@section('title')
IPD Discharge Report
@endsection

@section('report_type')
DISCHARGE REPORT
@endsection

@section('content')
    <style>
        .content-body {
            border-collapse: collapse;
        }
        .content-body td, .content-body th{
            border: 1px solid #ddd;
        }
        .content-body {
            font-size: 12px;
        }
    </style>
<table class="table content-body">
    <thead>
        <tr>
            <th>Category</th>
            <th>Observation</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" style="text-align: center;"><strong>General</strong></td>
        </tr>
        <tr>
            <td>Course of Treatment</td>
            <td>
                @foreach($course_of_treatments as $treatment)
                Date: {{ $treatment->fldtime }}::{{ $treatment->fldhead }}<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Triage Examinations</td>
            <td>
                @foreach($triage_exams as $data)
                    {{ $data->fldhead }}:
                    @if($data->fldoption === 'Clinical Scale')
                        {{ $data->fldrepquanti }}
                    @elseif($data->fldoption === 'Left and Right')
                    @php
                        $d = json_decode($data->fldrepquali);
                    @endphp
                        <table class="table">
                            <tr>
                                <th>Left</th>
                                <th>Right</th>
                            </tr>
                            <tr>
                                <td>{{ $d->LEFT }}</td>
                                <td>{{ $d->RIGHT }}</td>
                            </tr>

                        </table>
                    @else
                        {{ $data->fldrepquali }}
                    @endif
                    <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Presenting Complaints</td>
            <td>
                @foreach($presenting_complaints as $complain)
                {{ $complain->flditem }}:: {{  ($complain->fldreportquanti) ? $complain->fldreportquanti . " Days" : "" }} ::{{ ($complain->fldreportquali) ? $complain->fldreportquali : '' }} {{ ($complain->fldreportquali) ? $complain->flddetail : '' }}<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>OPD Examinations</td>
            <td>
                @foreach($opd_exams as $data)
                    {{ $data->fldhead }}:
                    @if($data->fldoption === 'Clinical Scale')
                        {{ $data->fldrepquanti }}
                    @elseif($data->fldoption === 'Left and Right')
                    @php
                        $d = json_decode($data->fldrepquali);
                    @endphp
                        <table class="table">
                            <tr>
                                <th>Left</th>
                                <th>Right</th>
                            </tr>
                            <tr>
                                <td>{{ $d->LEFT }}</td>
                                <td>{{ $d->RIGHT }}</td>
                            </tr>

                        </table>
                    @else
                        {{ $data->fldrepquali }}
                    @endif
                    <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>General Complaints</td>
            <td>{!! (isset($examgeneral_data['general_complaints'])) ? $examgeneral_data['general_complaints']: '' !!}</td>
        </tr>
        <tr>
            <td>History of Illness</td>
            <td>{!! (isset($examgeneral_data['history_of_illness'])) ? $examgeneral_data['history_of_illness']: '' !!}</td>
        </tr>
        <tr>
            <td>Past History</td>
            <td>{!! (isset($examgeneral_data['past_history'])) ? $examgeneral_data['past_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Treatment History</td>
            <td>{!! (isset($examgeneral_data['treatment_history'])) ? $examgeneral_data['treatment_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Medication History</td>
            <td>{!! (isset($examgeneral_data['medication_history'])) ? $examgeneral_data['medication_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Family History</td>
            <td>{!! (isset($examgeneral_data['family_history'])) ? $examgeneral_data['family_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Personal History</td>
            <td>{!! (isset($examgeneral_data['personal_history'])) ? $examgeneral_data['personal_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Demographics</td>
            <td>{{ (isset($demographics->fldreportquali)) ? $demographics->fldreportquali : '' }}</td>
        </tr>
        <tr>
            <td>Drug Allergy</td>
            <td>
                @if(isset($diagnosis_data['allergic_drugs']))
                    @foreach($diagnosis_data['allergic_drugs'] as $allergic_drugs)
                    {{ $allergic_drugs->fldcode }}<br>
                    @endforeach
                @else
                    None
                @endif
            </td>
        </tr>
        <tr>
            <td>Provisional Diagnosis</td>
            <td>
                @if(isset($diagnosis_data['provisional_diagnosis']))
                    @foreach($diagnosis_data['provisional_diagnosis'] as $provisional_diagnosis)
                    [{{ $provisional_diagnosis->fldcodeid }}] {{ $provisional_diagnosis->fldcode }}<br>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Advice</td>
            <td>{{ $advice ? $advice->flddetail : '' }}</td>
        </tr>
        <tr>
            <td>Final Diagnosis</td>
            <td>
                @if(isset($diagnosis_data['final_diagnosis']))
                    @foreach($diagnosis_data['final_diagnosis'] as $final_diagnosis)
                    {{ $final_diagnosis->fldcode }}<br>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Prominent Symptoms</td>
            <td>
                @foreach($patient_symptoms as $symptom)
                {{ $symptom->flditem }} ({{ $symptom->fldreportquali }}) [{{ $symptom->flddetail }}]<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Major Procedures</td>
            <td>
                @foreach($major_procedures as $b)
                    <p>{{ $b->fldnewdate }} :: {{ $b->flditem }} {{ strip_tags($b->flddetail) }}</p>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Extra Procedures</td>
            <td>
                @foreach($extra_procedures as $b)
                    <p>Date: {{ $b->fldnewdate }} :: {{ $b->flditem }} : {{ $b->detail }}</p>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Medication Used</td>
            <td>
                @foreach($medicines as $medicine)
                {{ $medicine->flditem }} {{ $medicine->fldroute }} {{ $medicine->flddose }} mL X {{ $medicine->fldfreq }} ({{ $medicine->fldcount }} dose)<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Delivery Profile</td>
            <td>
                @foreach($delivery_profile as $delivery)
                    <br>
                    Delivery Date: {{ $delivery->flddeltime }} <br>
                    Delivery Type: {{ $delivery->flddeltype }} <br>
                    Delivery Result: {{ $delivery->flddelresult }} <br>
                    Baby Patient No: {{ $delivery->fldbabypatno }} <br>
                    Baby Gender: {{ $delivery->fldptsex }} <br>
                    Birth Weight: {{ $delivery->flddelwt }} <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><strong>Investigations</strong></td>
        </tr>
        <tr>
            <td>Examinations</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Laboratory</td>
            <td>
                @foreach($laboratory as $lab)
                    {{ $lab->fldtestid }} [Spec: {{ $lab->fldsampletype }}]<br>
                    1.
                    @if($lab->answers->isNotEmpty())
                        <ul>
                        @foreach ($lab->answers as $answer)
                            <li>{{ $answer->fldsubtest }}: {{ $answer->fldreport }}</li>
                        @endforeach
                        </ul>
                    @else
                        {{ $lab->fldreportquanti }}
                    @endif
                    <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Radio Diagnostics</td>
            <td>
                <ol>
                @foreach($radio_tests as $test)
                    <li>{{ $test->fldtestid }}</li>
                    @if($test->answers->isNotEmpty())
                    <ul>
                        @foreach($test->answers as $answer)
                        <li>{{ $answer->fldsubtest }}: {{ $answer->fldreport }}</li>
                        @endforeach
                    </ul>
                    @endif
                @endforeach
                </ol>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><strong>Discharge</strong></td>
        </tr>
        <tr>
            <td>Planned Procedures</td>
            <td>
                @if($patGeneral)
                    @foreach($patGeneral as $b)
                        <p>Date :: {{ $b->flditem }} : {{ $b->flddetail }}</p>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Discharge Examinations</td>
            <td>
                @if($DischargeExaminationspatientExam)
                    @foreach($DischargeExaminationspatientExam as $b)
                        <p>{{ $b->fldtime }} :: {{ $b->fldhead }} : {{ $b->fldrepquali }} {{ $b->fldrepquanti }} ,{{ $b->fldtype }}</p>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Condition at Discharge</td>
            <td>
                @if($ConditionOfDischargeExamGeneral)
                    @foreach($ConditionOfDischargeExamGeneral as $b)
                        <p>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</p>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Discharge Medication</td>
            <td>
                @if($DischargedLAMADeathReferAbsconderPatDosing)
                    @foreach($DischargedLAMADeathReferAbsconderPatDosing as $b)
                        <p>{{ $b->flditem }} () {{ $b->fldroute }} {{ flddose }} X {{ $b->flddays }} ({{ $b->fldfreq }})</p>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Advice on Discharge</td>
            <td>
                @if($AdviceOfDischargeExamGeneral)
                    @foreach($AdviceOfDischargeExamGeneral as $b)
                        <p>{{ $b->fldtime }} : {{ strip_tags($b->flddetail) }}</p>
                    @endforeach
                @endif
            </td>
        </tr>
    </tbody>
</table>
@endsection
