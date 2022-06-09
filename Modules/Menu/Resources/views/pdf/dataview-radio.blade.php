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
    </style>
</head>

<body>


@include('pdf-header-footer.header-footer')
<main>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p>Name: {{ Options::get('system_patient_rank')  == 1 && (isset($encounter)) && (isset($encounter->fldrank) ) ?$encounter->fldrank:''}} {{ $patientinfo->fldptnamefir . ' ' . $patientinfo->fldmidname . ' ' .  $patientinfo->fldptnamelast }} ({{$patientinfo->fldpatientval}})</p>
                <p>Age/Sex: {{ $patientinfo->fldagestyle }} /{{ $patientinfo->fldptsex??"" }}</p>
                {{-- <p>Age/Sex: {{ \Carbon\Carbon::parse($patientinfo->fldptbirday??"")->age }}yrs/{{ $patientinfo->fldptsex??"" }}</p> --}}
                <p>Address: {{ $patientinfo->fldptaddvill??"" . ', ' . $patientinfo->fldptadddist??"" }}</p>
                <p>REPORT : INVESTIGATIONS</p>
                <p>Sample Time: {{$timesample}}</p>
            </td>
            <td style="width: 185px;">

                <p>DOReg: {{ $encounter->fldregdate ? \Carbon\Carbon::parse($encounter->fldregdate)->format('d/m/Y'):'' }}</p>
                <p>Phone: {{ $patientinfo->fldptcontact ?? "" }}</p>
            </td>
            <td style="width: 130px;"><img src="" alt="" width="100" height="100"/></td>
        </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="1px" rules="all" class="content-body">
        <tbody>
        <tr>
            <th style="width: 96px; text-align: center;">Time</th>
            <th style="width: 96px; text-align: center;">specimen</th>
            <th style="width: 96px; text-align: center;">Category</th>
            <th style="width: 96.2px; text-align: center;">Observations</th>
            <th style="width: 96.2px; text-align: center;">Comment</th>
        </tr>

        @if($labTests)
            @foreach($labTests as $k => $test)
                <tr>
                    <td>
                        <p>{{ Carbon\Carbon::parse($test->fldtime_sample)->format('h:i') }}</p>
                    </td>
                    <td>
                        <p>{{$test->fldsampletype}}</p>
                    </td>
                    <td>
                        <p>{{$test->fldtestid}}</p>
                    </td>
                    <td>

                        @if(isset($subTests[$k]['sub']) && isset($subTests[$k]['sub'][0]))

                            @foreach($subTests[$k]['sub']  as $sub)

                                <p>{{$sub->fldsubtest}} : {{$sub->fldreport}}</p>


                            @endforeach

                        @else

                            <p>{{$test->fldreportquanti }} {{$limits[$k]->unit }}</p>

                        @endif


                    </td>
                    <td></td>
                </tr>
            @endforeach
        @endif


        </tbody>
    </table>
</main>

</body>

</html>
