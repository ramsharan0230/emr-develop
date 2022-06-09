<!DOCTYPE html>
<html>

<head>
    <title>Patient Registration Report</title>
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
    <ul>
        <li>Registration Report</li>
        <li>Search Report</li>
    </ul>
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th>SNo</th>
            <th>PatientNo</th>
            <th>Name</th>
            <th>SurName</th>
            <th>Gender</th>
            <th>Address</th>
            <th>District</th>
            <th>Contact</th>
            <th>CurAge</th>
        </tr>
        </thead>
        <tbody>

        @if(isset($result) and count($result) > 0)
            @foreach($result as $k=>$data)
                @php
                    $sn = $k+1;

                @endphp
                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$data->fldpatientval}}</td>
                    <td>{{ $data->fldptnamefir}} </td>
                    <td>{{ $data->fldptnamelast}}</td>
                    <td>{{$data->fldptsex}}</td>
                    <td>{{$data->fldptaddvill}}</td>
                    <td>{{$data->fldptadddist}}</td>
                    <td>{{ $data->fldptcontact}}</td>
                    <td>{{ $data->fldagestyle }} </td>
                    {{-- <td>{{ \Carbon\Carbon::parse($data->fldptbirday)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days') }} </td> --}}
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">No Data Available</td>
            </tr>
        @endif

        </tbody>
    </table>
    <p>admin, {{date('Y-m-d')}}
    </p>
</main>

</body>

</html>
