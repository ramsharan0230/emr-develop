<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laboratory PDF</title>
    <style>
        .table-design {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-design td, .table-design th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-design tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-design tr:hover {
            background-color: #ddd;
        }

        .table-design th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #999;
            color: white;
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

    <table class="table-design content-body">
        <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Account</th>
            <th>Test Name</th>
            <th>Sample Date</th>
            <th>Sample ID</th>
            <th>Specimen</th>
            <th>Vial</th>
            <th>Referral</th>
        </tr>
        </thead>
        <tbody>
        @if(count($tests))
            @foreach($tests as $test)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $test->bill->flditemname }}</td>
                    <td>{{ $test->fldtestid }}</td>
                    <td>{{ $test->fldtime_sample }}</td>
                    <td>{{ $test->fldsampleid }}</td>
                    <td>{{ $test->fldsampletype }}</td>
                    <td>{{ $test->test->fldvial }}</td>
                    <td>{{ $test->fldrefername }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    @php
        $signatures = Helpers::getSignature('laboratory');
    @endphp
    @include('frontend.common.footer-signature-pdf')
    <p>Admin, {{ date('M j, Y') }}</p>
</main>
</body>
</html>
