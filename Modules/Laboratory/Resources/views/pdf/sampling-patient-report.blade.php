<!DOCTYPE html>
<html>
<head>
    <title>LABORATORY SAMPLE REPORT</title>
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
        <li>TEST REQUESTS</li>
        <li>{{$category}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">Encounter</th>
            <th class="tittle-th">PatientName</th>
            <th class="tittle-th">Age/Sex</th>
            <th class="tittle-th">Particulars</th>
            <th class="tittle-th">Remarks</th>
        </tr>
        </thead>
        <tbody>
        @if(count($tests))
            @foreach($tests as $test)
                <tr>
                    <td>{{ $test['encounterid'] }}</td>
                    <td>{{ $test['name'] }}</td>
                    <td>{{ $test['age'] }}/{{ $test['sex'] }}</td>
                    <td>{{ implode('; ', $test['tests']) }}</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>

    @php
        $signatures = Helpers::getSignature('laboratory');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
