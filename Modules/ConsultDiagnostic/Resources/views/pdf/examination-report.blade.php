<!DOCTYPE html>
<html>
<head>
    <title>EXAMINATION REPORT</title>
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
        <li>Examination Report : %</li>
        <li>{{$from}} To {{$to}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>
            <th class="tittle-th">EncID</th>
            <th class="tittle-th">Name</th>
            <th class="tittle-th">Age</th>
            <th class="tittle-th">Gender</th>
            <th class="tittle-th">PatientNo</th>
            <th class="tittle-th">Examiantion</th>
            <th class="tittle-th">DateTime</th>
            <th class="tittle-th">Location</th>
            <th class="tittle-th">Observation</th>
            <th class="tittle-th">Flag</th>
        </tr>
        </thead>
        <tbody>
        @if(count($result))
            @foreach($result as $k=>$data)
            @php
            $sn = $k+1;
            $encounter = \App\Encounter::where('fldencounterval',$data->fldencounterval)->first();
            $patientdata = \App\PatientInfo::where('fldpatientval',$encounter->fldpatientval)->first();
            // $age = '';
            $abnormalhtml = '';
            // $bday = $patientdata->fldptbirday;
            // $diff = (date('Y') - date('Y',strtotime($bday)));
            // $age = $diff;

            @endphp

                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$data->fldencounterval}}</td>
                    <td>{{ Options::get('system_patient_rank')  == 1 && (isset($patientdata)) && (isset($patientdata->fldrank) ) ?$patientdata->fldrank:''}} {{$patientdata->fldptnamefir}} {{$patientdata->fldptnamefir}} {{$patientdata->fldmidname}} {{$patientdata->fldptnamelast}}</td>
                    <td>{{$patientdata->fldagestyle}}</td>
                    {{-- <td>{{$age}} Yr</td> --}}
                    <td>{{$patientdata->fldptsex}}</td>
                    <td>{{$patientdata->fldpatientval}}</td>
                    <td>{{$data->fldhead}}</td>
                    <td>{{$encounter->fldregdate}}</td>
                    <td>{{$encounter->fldcomp}}</td>
                    <td>{{$data->fldhead}}</td>
                    <td>
                        @if($data->fldabnormal == 0)
                        <i style="color:green" class="fas fa-square"></i>
                        @elseif($data->fldabnormal == 1)
                        <i style="color:red" class="fas fa-square"></i>
                        @else

                        @endif

                    </td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('examination-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
