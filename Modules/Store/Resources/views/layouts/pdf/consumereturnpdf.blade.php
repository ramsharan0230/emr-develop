<!DOCTYPE html>
<html>
<head>
    <title>STOCK RETURN</title>
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
            <p><b>Consume Return Reference No:</b> {{ $fldnewreference }}</p>
            @php $currentDatetime = Carbon\Carbon::now()->format('m/d/Y H:i'); @endphp
            <p><b>Date:</b> {{ $currentDatetime }}</p>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>

        <th class="tittle-th">SNo</th>
        <th class="tittle-th">Category</th>
        <th class="tittle-th">Particulars</th>
        <th class="tittle-th">Batch</th>
        <th class="tittle-th">Expiry</th>
        <th class="tittle-th">QTY</th>
        <th class="tittle-th">Cost</th>
        <th class="tittle-th">Taget</th>
        <th class="tittle-th">RefNo</th>
    </tr>
    </thead>
    <tbody>
        @forelse($consumereturns as $consumereturn)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ($consumereturn->Entry) ? $consumereturn->Entry->fldcategory : ''}}</td>
                <td>{{ $consumereturn->fldstockid }}</td>
                @php $fldbatch = ($consumereturn->Entry) ? $consumereturn->Entry->fldbatch : ''; @endphp
                <td>{{ $fldbatch }}</td>
                @php $fldexpiry = ($consumereturn->Entry && $consumereturn->Entry->fldexpiry != '' && $consumereturn->Entry->fldexpiry != NULL) ? Carbon\Carbon::parse($consumereturn->Entry->fldexpiry)->format('m/d/Y') : ''; @endphp
                <td>{{ $fldexpiry }}</td>
                <td>{{ $consumereturn->fldqty }}</td>
                <td>{{ $consumereturn->fldcost }}</td>
                <td>{{ $consumereturn->fldtarget }}</td>
                <td>{{ $consumereturn->fldreference }}</td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>
{{-- <table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p>admin, {{ Carbon\Carbon::now()->format('m/d/Y') }} </p>
        </td>
    </tr>
    </tbody>
</table> --}}
@php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>

