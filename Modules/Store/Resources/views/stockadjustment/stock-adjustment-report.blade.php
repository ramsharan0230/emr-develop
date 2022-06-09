@extends('inpatient::pdf.layout.main')

@section('title')
    Stock Adjustment Report
@endsection

@section('content')
    @php
        $totalamount = 0;
    @endphp
    <div style="width: 100%;">
        <p><b>Stock Adjustment Reference No:</b> {{ $fldreference }}</p>
        @php $currentDatetime = Carbon\Carbon::now()->format('m/d/Y H:i'); @endphp
        <p><b>Date:</b> {{ $currentDatetime }}</p>
    </div>
    <table class="table content-body">
        <thead>
        <tr>
            <th>SN</th>
            <td>Stock No</td>
            <td>Category</td>
            <td>Particulars</td>
            <td>Batch</td>
            <td>Expiry</td>
            <td>Qty</td>
            <td>Reason</td>
        </tr>
        </thead>
        <tbody>
        @if($stockAdjusted)
            @foreach($stockAdjusted as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->fldstockno }}</td>
                    <td>{{ $stock->fldcategory }}</td>
                    <td>{{ $stock->fldstockid }}</td>
                    <td>{{ $stock->stock->fldbatch}}</td>
                    <td>{{ $stock->stock->fldexpiry }}</td>
                    <td>{{ $stock->fldcurrqty }}</td>
                    <td>{{ $stock->fldreason }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
