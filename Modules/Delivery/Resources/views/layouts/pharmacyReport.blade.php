@extends('inpatient::pdf.layout.main')

@section('title')
IPD symptoms
@endsection

@section('report_type')
CLINICAL SYMPTOMS
@endsection

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>SN</th>
            <th>StartDate</th>
            <th>Route</th>
            <th>Particulars</th>
            <th>Dose</th>
            <th>Freq</th>
            <th>Days</th>
            <th>QTY</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medicines as $key => $val)
            <tr>
                <td>{{ ($key+1) }}</td>
                <td>{{ $val->fldstarttime }}</td>
                <td>{{ $val->fldroute }}</td>
                <td>{{ $val->flditem }}</td>
                <td>{{ $val->flddose }}</td>
                <td>{{ $val->fldfreq }}</td>
                <td>{{ $val->flddays }}</td>
                <td>{{ $val->qty }}</td>
                <td>{{ $val->fldcurval }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection