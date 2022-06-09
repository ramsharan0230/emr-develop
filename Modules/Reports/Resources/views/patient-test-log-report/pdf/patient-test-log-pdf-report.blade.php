<!DOCTYPE html>
<html>
<head>
    <title>User Collection Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">

        @page {
            margin: 24mm 0 11mm;
        }


        body {
            margin: 0 auto;
            padding: 10px 10px 5px;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }


        .content-body tr td {
            padding: 5px;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body table {
            page-break-inside: auto
        }

        .content-body tr {
            page-break-inside: avoid;
            page-break-after: auto
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

        .content-body td, .content-body th{
            text-align: right;
        }

        .content-body td:nth-child(1), .content-body th:nth-child(1){
            text-align: left;
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
        <li>Patient Test Log Report</li>
        {{-- <li>Date:  {{$from_date}}  TO
            {{$to_date}} 
        </li> --}}

        {{-- <li>Department: {{ $resultdata ? \App\Utils\Helpers::getDepartmentFromCompID($resultdata[array_key_first($resultdata)]['fldcomp']) : ''}}</li> --}}
    </ul>

    <table class="table" id="table">
        <thead>
        <tr>
            <th>S.N</th>
            <th>Patient Id</th>
            <th>Encounter Id</th>
            <th>Name</th>
            <th>Sample Id</th>
            {{-- <th>fldptnamelast</th> --}}
            <th>Test Id</th>
            <th>Sample by</th>
            <th>Sample Time</th>
            <th>Reported By</th>
            <th>Reported Time</th>
            <th>Verified By</th>
            <th>Verified Time</th>
        </tr>
        </thead>
        <tbody>
            @if(!$records->isEmpty())
                <?php 
                    $count = 1; 
                ?>
                @foreach ($records as $key => $list)
                    <tr data-node="treetable-{{$list->fldpatientval}}">
                        <td>{{$count++}}</td>
                        <td>{{$list->fldpatientval??''}}</td>
                        <td>{{$list->fldencounterval??''}}</td>
                        <td>{{strtoupper($list->fldptnamefir)??''}} {{strtoupper($list->fldptnamelast)??''}}</td>
                        <td>{{$list->fldsampleid ?? ''}}</td>
                        <td>{{$list->fldtestid??''}}</td>
                        <td>{{$list->flduserid_sample??''}}</td>
                        <td>{{$list->fldtime_sample??''}}</td>
                        <td>{{$list->flduserid_report??''}}</td>
                        <td>{{$list->fldtime_report??''}}</td>
                        <td>{{$list->flduserid_verify??''}}</td>
                        <td>{{$list->fldtime_verify??''}}</td>
                    </tr>
                @php
                $patient_wise_log=  \App\Utils\Helpers::patientTestLogReport($list->fldpatientval,$list->fldtestid);
                @endphp
                    @if(!$patient_wise_log->isEmpty())
                        @foreach ($patient_wise_log as $pwg)
                            <tr  data-pnode="treetable-parent-{{$list->fldpatientval}}">
                                <td></td> 
                                <td>{{$pwg->fldpatientval??''}}</td>
                                <td>{{$pwg->fldencounterval??''}}</td>
                                <td>{{strtoupper($pwg->fldptnamefir)??''}} {{strtoupper($pwg->fldptnamelast)??''}}</td>
                                <td>{{$pwg->fldsampleid ?? ''}}</td>
                                <td>{{$pwg->fldtestid??''}}</td>
                                <td>{{$pwg->flduserid_sample??''}}</td>
                                <td>{{$pwg->fldtime_sample??''}}</td>
                                <td>{{$pwg->flduserid_report??''}}</td>
                                <td>{{$pwg->fldtime_report??''}}</td>
                                <td>{{$pwg->flduserid_verify??''}}</td>
                                <td>{{$pwg->fldtime_verify??''}}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>
    @php
        // $signatures = Helpers::getSignature('billing-user-collection-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
