@extends('inpatient::pdf.layout.main')

@section('title')
IPD MEDICATION REVIEW
@endsection

@section('report_type')
{{ $flditem }}
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
            <th>SNo</th>
            <th>DateTIme</th>
            <th>Particulars</th>
            <th>QTY</th>
            <th>Unit</th>
            <th>&nbsp;</th>
            <th>Dose</th>
        </tr>
    </thead>
    <tbody>
        @foreach($drugs as $key => $drug)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $drug->fldtime }}</td>
            <td>{{ $drug->flddrug }}</td>
            <td>{{ $drug->fldvalue }}</td>
            <td>{{ $drug->fldunit }}</td>
            <td style="color: #{{ $drug->csscolor  }}">{!! $drug->lockitem !!}</td>
            <td>{{ $drug->flddose }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
