@extends('inpatient::pdf.layout.main')

@section('title')
    Stock Consume Report
@endsection

@section('content')
    @php
        $totalamount = 0;
    @endphp
    <div style="width: 100%;">
        <p><b>Stock Consume Reference No:</b> {{ $fldreference }}</p>
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
        </tr>
        </thead>
        <tbody>
        @if($stockConsumed)
            @foreach($stockConsumed as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->fldstockno }}</td>
                    <td>{{ $stock->fldcategory }}</td>
                    <td>{{ $stock->fldstockid }}</td>
                    <td>{{ $stock->stock->fldbatch}}</td>
                    <td>{{ $stock->stock->fldexpiry }}</td>
                    <td>{{ $stock->fldqtydisp }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
