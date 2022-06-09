<!DOCTYPE html>
<html>
<head>
    <title>Discharge List Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 24mm 0 11mm;
        }

        body {
            margin: 0 auto;
            padding: 10px 10px 5px;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .bill-title {
            position: absolute;
            width: 100%;
            text-align: center;
            margin-bottom: 2px;
            margin-top: 3px;
        }


        .a4 {
            width: auto;
            margin: 0 auto;
        }

        .footer {
            /* position: absolute; */
            width: 100%;
            text-align: center;
           margin:0;
           padding:0;
        }

        .bar-code {
            width: 200px;
            height: auto;
            margin-top:5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
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

        .content-body td:nth-child(1),
        .content-body th:nth-child(1),
        .content-body td:nth-child(2),
        .content-body th:nth-child(2) {
            text-align: left;

        }


        .content-body td,
        .content-body th {
            border: 1px solid #ddd;
            font-size: 13px;
            text-align: right;
            padding-right:4px;
        }

        h2,
        h4 {
            line-height: 0.5rem;
        }

        ul {
            float: right;
            padding: 0;
            margin: 0;
        }

        ul li {
            text-align: right;
            ;
            list-style: none;

        }

        ul li span:first-child {
            text-align: left;
        }

        ul li span:nth-child(2) {
            text-align: right;
            width: 150px;
            display: inline-block;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>

    <ul>
        <li>Billing Report </li>
        <li>{{$from_date}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateEngToNepdash($from_date)->full_date .")" : ''}} TO
            {{$to_date}} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateEngToNepdash($to_date)->full_date . ")":'' }}
{{--            {{$from_date}} To {{$to_date}}--}}
        </li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>

            <th class="tittle-th">Date</th>
            <th class="tittle-th">Time</th>
            <th class="tittle-th">Invoice</th>
            <th class="tittle-th">EncId</th>
            <th class="tittle-th">Name</th>
            <th class="tittle-th">OldDepo</th>
            <th class="tittle-th">TotAmt</th>
            <th class="tittle-th">TaxAmt</th>
            <th class="tittle-th">DiscAmt</th>
            <th class="tittle-th">NetTot</th>
            <th class="tittle-th">RecAmt</th>
            <th class="tittle-th">NewDepo</th>
            <th class="tittle-th">User</th>
            <th class="tittle-th">InvType</th>
            <th class="tittle-th">BankName</th>
            <th class="tittle-th">ChequeNo</th>
            <th class="tittle-th">TaxGroup</th>
            <th class="tittle-th">DiscGroup</th>

        </tr>
        </thead>
        <tbody>
        @if(count($patients))
            @foreach($patients as $k=>$r)
            @php

                $enpatient = \App\Encounter::where('fldencounterval',$r->fldencounterval)->with('patientInfo')->first();

                $sn = $k+1;
            @endphp

                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>
                    <td>{{$sn}}</td>

                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('billing-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
