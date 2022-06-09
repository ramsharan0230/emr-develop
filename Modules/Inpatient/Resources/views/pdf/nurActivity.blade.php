@extends('inpatient::pdf.layout.main')

@section('title')
IPD NURSING CHECK REPORT
@endsection

@section('report_type')
NURSING CHECK REPORT
@endsection

@section('content')
    <style>
        .content-body {
            border-collapse: collapse;
        }
        .content-body td, .content-body th{
            border: 1px solid #ddd;
        }
        .content-body {
            font-size: 12px;
        }
    </style>
<table class="table content-body">
    <thead>
        <tr>
            <th>SNo</th>
            <th>Particulars</th>
            <th>Regimen</th>
            <th>Today</th>
        </tr>
    </thead>
    <tbody>
        @if($poIntakes->isNotEmpty())
            <tr>
                <td colspan="4" style="text-align: center;"><strong>PO Intake</strong></td>
            </tr>
            @foreach($poIntakes as $key=> $data)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->flddose }} Grams</td>
                <td>&nbsp;</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endsection
