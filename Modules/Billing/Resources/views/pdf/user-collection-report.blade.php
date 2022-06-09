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
        <li>User Collection Report</li>
        <li>Date:  {{$from_date}}  TO
            {{$to_date}} 
{{--            {{$from_date}} TO {{$to_date}}--}}
        </li>

        <li>Department: {{ $resultdata ? \App\Utils\Helpers::getDepartmentFromCompID($resultdata[array_key_first($resultdata)]['fldcomp']) : ''}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead class="thead-light">

        <tr>
            <th>User </th>
            <th>Total Amount</th>
            <th>Tax Amt</th>
            <th>Disc Amt</th>
            <th>Total</th>
            <th>Deposit Amount</th>
            <th>Deposit Refund Amount</th>
            <th>Received Total</th>

            {{--            <th>Department</th>--}}

        </tr>

        </thead>
        <tbody>
        @php
            $itemtot = $tax = $disc = $tot = $recv = $depo = 0;
        @endphp
        @if(isset($resultdata) and !empty($resultdata))
            @foreach($resultdata as $rdata)
                <tr>

                </tr>
                <tr>
                <td >{{ ucwords(str_replace('.', ' ' , $rdata['username'])) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($rdata['itemtot']) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($rdata['tax']) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($rdata['disc']) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($rdata['tot']) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($rdata['depo']) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($rdata['refdepo']) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($rdata['recv']) }}</td>

                    {{--                    <td style="text-align: center;">{{\App\Utils\Helpers::getDepartmentFromCompID($rdata['fldcomp'])}}</td>--}}
                </tr>
                @php
                    $itemtot += $rdata['itemtot'];
                    $tax += $rdata['tax'];
                    $disc += $rdata['disc'];
                    $tot += $rdata['tot'];
                    $recv += $rdata['recv'];
                    $depo += $rdata['depo'];
                @endphp
            @endforeach

            <tr>
                 <th  style="text-align: left; font-size: 14px;">Total</th>
                <th>{{ \App\Utils\Helpers::numberFormat($itemtot) }}</th>
                <th>{{ \App\Utils\Helpers::numberFormat($tax) }}</th>
                <th>{{ \App\Utils\Helpers::numberFormat($disc) }}</th>
                <th>{{ \App\Utils\Helpers::numberFormat($tot) }}</th>
                <th>{{ \App\Utils\Helpers::numberFormat($depo) }}</th>
                <th></th>
                <th>{{ \App\Utils\Helpers::numberFormat($recv) }}</th>
            </tr>
        @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('billing-user-collection-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
