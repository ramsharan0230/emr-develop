<!DOCTYPE html>
<html>

<head>
    <title>Consultation Report</title>
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
    <ul>
        <li>SUMMARY: {{$from_date}} TO {{$to_date}}</li>
        {{-- <li>TOTAL : {{$total}}</li> --}}
    </ul>
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th>SNo</th>
            <th>EncId</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Patient No</th>
            <th>Consult Date</th>
            <th>Department</th>
            <th>Consultant</th>
        </tr>
        </thead>
        <tbody>
            {!! $html !!}
        {{-- @if(isset($result) and count($result) > 0)
            @foreach($result as $k=>$data)
                @php
                    $sn = $k+1;
                    $encounter = \App\Encounter::select('fldpatientval','fldrank')->where('fldencounterval', $data->fldencounterval)->first();
                    $patient   = \App\PatientInfo::select('fldptnamefir','fldpatientval', 'fldptnamelast', 'fldptsex', 'fldptbirday', 'fldpatientval', 'fldmidname', 'fldrank')->where('fldpatientval', $encounter->fldpatientval)->first();

                @endphp
                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$data->fldencounterval}}</td>
                    <td>{{ Options::get('system_patient_rank')  == 1 ?$encounter->fldrank:''}} {{$patient->fldptnamefir}} {{$patient->fldmidname}} {{$patient->fldptnamelast}}</td>
                    <td></td>
                    <td>{{$patient->fldptsex}}</td>
                    <td>{{$encounter->fldpatientval}}</td>
                    <td>{{$data->fldconsulttime}}</td>
                    <td>{{$data->fldconsultname}}</td>
                    <td>{{$data->flduserid}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">No Data Available</td>
            </tr>
        @endif --}}

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('consultation');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>

</body>

</html>
