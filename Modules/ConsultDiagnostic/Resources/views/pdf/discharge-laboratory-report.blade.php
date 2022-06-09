<!DOCTYPE html>
<html>
<head>
    <title>Laboratory Discharge Report</title>
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
        <li>Laboratory Discharge Report : {{ $section }}</li>
        <li>{{ $fromdate }} To {{ $todate }}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>
            <th class="tittle-th">EncID</th>
            <th class="tittle-th">Name</th>
            <th class="tittle-th">Age</th>
            <th class="tittle-th">Gender</th>
            <th class="tittle-th">TestName</th>
            <th class="tittle-th">SampID</th>
            <th class="tittle-th">Specimen</th>
            <th class="tittle-th">Date</th>

        </tr>
        </thead>
        <tbody>
        @if(count($data))
            @foreach($data as $index => $d)
                @php
                    $name = implode(' ', array_filter([$d->fldptnamefir, $d->fldmidname, $d->fldptnamelast]));
                    $age = \App\Utils\Helpers::getAgeDetail($d->fldptbirday);
                @endphp
                <tr>
                    <td>{{ ($index+1) }}</td>
                    <td>{{ $d->fldencounterval }}</td>
                    <td>{{ $name }}</td>
                    <td>{{ $age }}</td>
                    <td>{{ $d->fldptsex }}</td>
                    <td>{{ $d->fldtestid }}</td>
                    <td>{{ $d->fldsampleid }}</td>
                    <td>{{ $d->fldsampletype }}</td>
                    <td>{{ $d->fldtime }}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('laboratory-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
