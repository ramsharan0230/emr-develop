@extends('inpatient::pdf.layout.main')

@section('title')
IPD
@endsection

@section('report_type')
{{ $itemtype }}
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css')}}">
<style>
    .content-body {
        border-collapse: collapse;
    }
    .content-body td, .content-body th{
        border: 1px solid #ddd;
    }
    .content-body {
        font-size: 10px;
    }
</style>
<table class="table content-body">
    <thead>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>Batch</th>
            <th>Expiry</th>
            <th>Rate</th>
            <th>Tax%</th>
            <th>Disc%</th>
            <th>QTY</th>
            <th>Total</th>
            <th>Invoice</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach($records as $record)
            @php $total += $record->tot; @endphp
            <tr>
                <td>{{ $record->fldtime }}</td>
                <td>{{ $record->flditemname }}</td>
                <td>{{ $record->fldbatch }}</td>
                <td>{{ $record->fldexpiry }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($record->flditemrate) }}</td>
                <td>{{ $record->fldtaxper }}</td>
                <td>{{ $record->flddiscper }}</td>
                <td>{{ $record->flditemqty }}</td>
                <td>Rs. {{  \App\Utils\Helpers::numberFormat($record->tot) }}</td>
                <td>{{ $record->fldbillno }}</td>
            </tr>
        @endforeach
        <tr>
            <td>***</td>
            <td>***</td>
            <td>***</td>
            <td>***</td>
            <td>***</td>
            <td>***</td>
            <td>***</td>
            <td>***</td>
            <td>Rs. {{  \App\Utils\Helpers::numberFormat($total) }}</td>
            <td>***</td>
        </tr>
    </tbody>
</table>
@endsection
