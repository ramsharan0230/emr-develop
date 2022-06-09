<!DOCTYPE html>
<html>
<head>
    <title>LABORATORY TEST GROUPING</title>
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
            <p>LABORATORY TEST GROUPING </p>
            @php $currentDatetime = Carbon\Carbon::now()->format('m/d/Y H:i'); @endphp
            <p>{{ $currentDatetime }}</p>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>
        <th class="tittle-th">Particulars</th>
        <th class="tittle-th">Type</th>
        <th class="tittle-th">Method</th>
    </tr>
    </thead>
    <tbody>
    @forelse($labgroups as $labgroup)
        <tr>
            <td colspan="3" style="text-align: center;">{{ $labgroup->fldgroupname }}</td>
        </tr>
        @php $tests = \App\Utils\Groupinghelpers::getAlltestsfromgroup($labgroup->fldgroupname, 'lab') @endphp
        @forelse($tests as $test)
            <tr>
                <td>{{ $test->fldtestid }}</td>
                <td>{{ $test->fldtesttype }}</td>
                <td>{{ $test->fldactive }}</td>
            </tr>
        @empty
        @endforelse
    @empty
    @endforelse
    </tbody>
</table>
<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p>admin, {{ Carbon\Carbon::now()->format('m/d/Y') }} </p>
        </td>
    </tr>
    </tbody>
</table>
@php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</body>
</html>
