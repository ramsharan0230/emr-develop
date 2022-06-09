<!DOCTYPE html>
<html>
<head>
    <title>Radiology REPORT</title>
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

        <li>Radiology Report : {{$status}}</li>
        <li>{{$from_date}} To {{$to_date}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>
            <th class="tittle-th">EncID</th>
            <th class="tittle-th">Name</th>
            <th class="tittle-th">Age</th>
            <th class="tittle-th">Gender</th>
            <th class="tittle-th">TestName</th>
            <th class="tittle-th">Date</th>
            <th class="tittle-th">Status</th>
            <th class="tittle-th">Observation</th>

        </tr>
        </thead>
        <tbody>
        @if(count($result))
            @foreach($result as $k=>$data)
            @php
                $encounter = \App\Encounter::where('fldencounterval', $data->fldencounterval)->first();
                $patient = \App\PatientInfo::where('fldpatientval',$encounter->fldpatientval)->first();
                // $bday = $patient->fldptbirday;
                // $diff = (date('Y') - date('Y',strtotime($bday)));
                // $age = $diff;
                $sn = $k+1;
            @endphp

                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$data->fldencounterval}}</td>
                    <td>{{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{$patient->fldptnamefir}} {{$patient->fldmidname}} {{$patient->fldptnamelast}}</td>
                    <td>{{$patient->fldagestyle}}</td>
                    {{-- <td>{{$age}} Yr</td> --}}
                    <td>{{$patient->fldptsex}}</td>
                    <td>{{$data->fldtestid}}</td>
                    <td>{{$data->$comparecolumn}}</td>
                    <td>{{$status}}</td>

                    <td>{{$data->$comparecolumn}}</td>

                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
     @php
        $signatures = Helpers::getSignature('radiology-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
