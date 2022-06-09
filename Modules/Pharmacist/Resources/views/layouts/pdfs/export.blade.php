<!DOCTYPE html>
<html>
<head>
    <title>MEDICINE GROUPING REPORT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }
        table{
            border-collapse:collapse;
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
            <p> {{ $fldmedgroup }} </p>
            @php $currentDatetime = Carbon\Carbon::now()->format('m/d/Y H:i'); @endphp
            <p>{{ $currentDatetime }}</p>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>

        <th class="tittle-th">SNo</th>
        <th class="tittle-th">Route</th>
        <th class="tittle-th">Particulars</th>
        <th class="tittle-th">Dose</th>
        <th class="tittle-th">Unit</th>
        <th class="tittle-th">Freq</th>
        <th class="tittle-th">Day</th>
        <th class="tittle-th">QTY</th>
        <th class="tittle-th">StartHour</th>
    </tr>
    </thead>
    <tbody>
        @forelse($productgroups as $productgroup)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $productgroup->fldroute }}</td>
                <td>{{ $productgroup->flditem }}</td>
                <td>{{ $productgroup->flddose }}</td>
                <td>{{ $productgroup->flddoseunit }}</td>
                <td>{{ $productgroup->fldfreq }}</td>
                <td>{{ $productgroup->fldday }}</td>
                <td>{{ $productgroup->fldqty }}</td>
                <td>{{ $productgroup->fldstart }}</td>
            </tr>
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
</main>
</body>
</html>

