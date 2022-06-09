<!DOCTYPE html>
<html>
<head>
    <title>User Collection Report</title>
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
        <li>User Collection Report</li>
        <li>Date:
            {{ isset($from_date) ? $from_date : ''}} TO
            {{ isset($to_date) ? $to_date :'' }}
            {{--            {{$from_date}} TO {{$to_date}}--}}
        </li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead class="thead-light">
        <tr>
            <th rowspan="2">User Name</th>
            <th rowspan="2">Department</th>
            <th colspan="6">OP Collection</th>
            <th colspan="6">IP Collection</th>
            <th rowspan="2">Deposit</th>
            <th rowspan="2">Deposit Refund</th>
            <th rowspan="2"> Total Collection</th>
            <th rowspan="2"> Miscellaneous</th>
            <th rowspan="2"> Grand Total Collection</th>

        </tr>
        <tr>
            <th>Cash Bill(+)</th>
            <th>Cash Refund(-)</th>
            <th>Net Cash Total</th>

            <th>Credit Bill(+)</th>
            <th>Credit Refund(-)</th>
            <th>Net Credit Total</th>

            <th>Cash Bill(+)</th>
            <th>Cash Refund(-)</th>
            <th>Net Cash Total</th>

            <th>Credit Bill(+)</th>
            <th>Credit Refund(-)</th>
            <th>Net Credit Total</th>
        </tr>
        </thead>
        <tbody>
        {!! $resultdata !!}
        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('billing-user-collection-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
