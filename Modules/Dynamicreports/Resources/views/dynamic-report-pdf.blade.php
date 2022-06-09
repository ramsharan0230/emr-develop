@extends('inpatient::pdf.layout.main')

@section('title')
{{$reportData->fldreportname}}
@endsection

@section('content')
<div style="width: 100%;text-align:center;">
        
            <h4>Supplier Wise VAT Report</h4>
       
    </div>
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>SUMMARY: {{$from_date}} TO {{$to_date}}</p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            {!! $thead !!}
        </thead>
        <tbody>
            {!! $tbody !!}
        </tbody>
    </table>
@endsection
