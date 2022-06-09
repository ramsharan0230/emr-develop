@extends('inpatient::pdf.layout.main')

@section('title')
IPD Complete Report
@endsection

@section('report_type')
SELECTED PARAMETERS
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

                @if($followdate)
                Follow-up: {{ $followdate }}
                @endif
            </td>
        </tr>
        <tr>
            <td>Bed Transitions</td>
            <td>
                @foreach($bed_transactions as $transaction)
                Department: {{ $transaction->flditem }}:: InDate:{{ $transaction->fldfirsttime }}::  InDate:{{ $transaction->fldsecondtime }}:: BedNo: {{ $transaction->fldsecondreport }} <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Demographics</td>
            <td>{{ $demographics->fldreportquali }}</td>
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
            <td>Cause of Admission</td>
            <td>{!! (isset($examgeneral_data['cause_of_admission'])) ? $examgeneral_data['cause_of_admission']: '' !!}</td>
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
            <td>Surgical History</td>
            <td>{!! (isset($examgeneral_data['surgical_history'])) ? $examgeneral_data['surgical_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Occupational History</td>
            <td>{!! (isset($examgeneral_data['occupational_history'])) ? $examgeneral_data['occupational_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Social History</td>
            <td>{!! (isset($examgeneral_data['social_history'])) ? $examgeneral_data['social_history']: '' !!}</td>
        </tr>
        <tr>
            <td>Drug Allergy</td>
            <td>
                @if(isset($diagnosis_data['allergic_drugs']))
                    @foreach($diagnosis_data['allergic_drugs'] as $allergic_drugs)
                    {{ $allergic_drugs->fldcode }}<br>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>Provisional Diagnosis</td>
            <td>
                @if(isset($diagnosis_data['provisional_diagnosis']))
                    @foreach($diagnosis_data['provisional_diagnosis'] as $provisional_diagnosis)
                    {{ $provisional_diagnosis->fldcode }}<br>
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
            <td>Minor Procedures</td>
            <td>
                @foreach($minor_procedures as $b)
                    <p>{{ $b->fldnewdate }} :: {{ $b->flditem }} {{ strip_tags($b->flddetail) }}</p>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Consultations</td>
            <td>
                @foreach($consults as $consult)
                {{ $consult->fldconsultname }} [{{ $consult->fldstatus }}] <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Equipments Used</td>
            <td>
                @foreach($equpiments as $equpiment)
                {{ $equpiment->flditem }}<br>
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
            <td>Treatment Advised</td>
            <td>
                @foreach($medicines as $medicine)
                {{ $medicine->flditem }} {{ $medicine->fldroute }} {{ $medicine->flddose }} mL X {{ $medicine->fldfreq }} X {{ $medicine->flddays }}<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Delivery Profile</td>
            <td>
                @if(count($confinement))
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
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;"><strong>Investigations</strong></td>
        </tr>
        <tr>
            <td>Essential Examinations</td>
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
        <tr>
            <td>Structured Examinations</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Clinical Findings</td>
            <td>
                @if($ClinicianPlanPatPlanning)
                    @foreach($ClinicianPlanPatPlanning as $b)
                        <p>Date: {{ $b->fldtime }}
                            {{ $b->fldproblem }} ,
                            {{ $b->fldsubjective }} ,
                            {{ $b->fldobjective }} ,
                            {{ $b->fldassess }} ,
                            {{ $b->fldplan }}</p>
                    @endforeach
                @endif
            </td>
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
            <td>Clinical Notes</td>
            <td>
                @foreach($notes as $note)
                    Category: {{ $note->flditem }} <br>
                    {{ $note->flddetail }} <br>
                    IMPRESSION: {{ $note->fldreportquali }}<br>
                    <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>IP Monitoring</td>
            <td>
                @foreach($progress as $proce)
                    {{ ($proce->fldsubjective) ? "Problem: " . $proce->fldsubjective : ''}}<br>
                    On Examination
                    @if($proce->exams->isNotEmpty())
                        @foreach($proce->exams as $exam)
                        {{ $exam->fldhead }}: {{ $exam->fldrepquali }} <br>
                        @endforeach
                    @endif
                    <br>
                    {{ ($proce->fldobjective) ? "Treatement: " . $proce->fldobjective : ''}}<br>
                    {{ ($proce->fldassess) ? "I/O Assessment: " . $proce->fldassess : ''}}<br>
                    {{ ($proce->fldproblem) ? "Impression: " . $proce->fldproblem : ''}}<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Therapeutic Planning</td>
            <td>
                @foreach($plannings as $planning)
                    Problem: {{ $planning->fldproblem }} <br>
                    Subjective: {{ $planning->fldsubjective }} <br>
                    Objective: {{ $planning->fldobjective   }} <br>
                    Assessment: {{ $planning->fldassess }} <br>
                    Planning: {{ $planning->fldplan }} <br>
                    <br>
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
@endsection
