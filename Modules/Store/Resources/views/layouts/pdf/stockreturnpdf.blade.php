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
            <p><b>Stock Return Reference No:</b> {{ $fldnewreference }}</p>
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
        <th class="tittle-th">Vendor</th>
        <th class="tittle-th">RefNo</th>
    </tr>
    </thead>
    <tbody>
        @forelse($stockreturns as $stockreturn)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ($stockreturn->Entry) ? $stockreturn->Entry->fldcategory : ''}}</td>
                <td>{{ $stockreturn->fldstockid }}</td>
                @php $fldbatch = ($stockreturn->Entry) ? $stockreturn->Entry->fldbatch : ''; @endphp
                <td>{{ $fldbatch }}</td>
                @php $fldexpiry = ($stockreturn->Entry && $stockreturn->Entry->fldexpiry != '' && $stockreturn->Entry->fldexpiry != NULL) ? Carbon\Carbon::parse($stockreturn->Entry->fldexpiry)->format('m/d/Y') : ''; @endphp
                <td>{{ $fldexpiry }}</td>
                <td>{{ $stockreturn->fldqty }}</td>
                <td>{{ $stockreturn->fldcost }}</td>
                <td>{{ $stockreturn->fldsuppname }}</td>
                <td>{{ $stockreturn->fldreference }}</td>
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

