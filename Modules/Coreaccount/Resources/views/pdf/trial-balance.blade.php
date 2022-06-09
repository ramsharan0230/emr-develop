<!DOCTYPE html>
<html>
<head>
    <title>Trial Balance Report</title>
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
@include('frontend.common.account-header')
<div style="text-align: center">
    <h3>Trial Balance</h3>
</div>
<div>
    <p>
        From Date: {{$from_date}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateNepToEng($from_date)->full_date .")" : ''}}
    </p>
    <p>
        To Date: {{$to_date}} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateNepToEng($to_date)->full_date . ")":'' }}
    </p>
</div>

<main>
    <table style="width: 100%;" class="content-body">
        <thead class="thead-light">
        <tr>
            <th class="text-center" rowspan="2">S/N</th>
            <th class="text-center" rowspan="2">Group</th>
            <th class="text-center" rowspan="2">SubGroup</th>
            <th class="text-center" rowspan="2">Account</th>
            <th class="text-center" colspan="2">Opening</th>
            <th class="text-center" colspan="2">Turnover</th>
            <th class="text-center" colspan="2">Closing</th>
        </tr>
        <tr>
            <th class="text-center">Dr</th>
            <th class="text-center">Cr</th>
            <th class="text-center">Dr</th>
            <th class="text-center">Cr</th>
            <th class="text-center">Dr</th>
            <th class="text-center">Cr</th>
        </tr>
        </thead>
        <tbody>
        {!! $html !!}
        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('billing-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
