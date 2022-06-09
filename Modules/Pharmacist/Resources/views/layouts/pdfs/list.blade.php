<!DOCTYPE html>
<html>
<head>
    <title>MEDICINE GROUPING REPORT</title>
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
                <p>PHARMACY GROUPS </p>
                @php $currentDatetime = Carbon\Carbon::now()->format('m/d/Y H:i'); @endphp
                <p>{{ $currentDatetime }}</p>
            </td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>
        <th class="tittle-th">Category</th>
        <th class="tittle-th">Particulars</th>
        <th class="tittle-th">Dose</th>
        <th class="tittle-th">Freq</th>
        <th class="tittle-th">Day</th>
        <th class="tittle-th">QTY</th>
        <th class="tittle-th">StartHour</th>
    </tr>
    </thead>
    <tbody>
        @forelse($medgroups as $medgroup)
            <tr>
                <td colspan="7" style="text-align: center;">{{ $medgroup->fldmedgroup }}</td>
            </tr>
            @php $productgroups = \App\Utils\Pharmacisthelpers::getAllPrdoctgroupsFromMedGroup($medgroup->fldmedgroup) @endphp
            @forelse($productgroups as $productgroup)
                <tr>
                    <td>{{ $productgroup->fldroute }}</td>
                    <td>{{ $productgroup->flditem }}</td>
                    <td>{{ $productgroup->flddose.' '.$productgroup->flddoseunit }}</td>
                    <td>{{ $productgroup->fldfreq }}</td>
                    <td>{{ $productgroup->fldday }}</td>
                    <td>{{ $productgroup->fldqty }}</td>
                    <td>{{ $productgroup->fldstart }}</td>
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
</main>
</body>
</html>

