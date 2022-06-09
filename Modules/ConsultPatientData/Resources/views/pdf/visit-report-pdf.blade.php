@extends('inpatient::pdf.layout.main')

@section('title')
VISIT REPORT
@endsection

@section('content')

<style>
.content-body th,.content-body td{
    text-align: left;
}
</style>
<div style="width: 100%;"><h4 style="text-align:center;">Patient Visit Report</h4></div>
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>SUMMARY: {{\Carbon\Carbon::parse($from_date)->format('Y-m-d')}} TO {{\Carbon\Carbon::parse($to_date)->format('Y-m-d')}}</p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>Index</th>
                <th>EncID</th>
                <th width="100">Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>DORec</th>
                <th>DOAdmission</th>
                <th>DODischarge</th>
                <th>Stay Days</th>
                <th>Status</th>
                <th>Department</th>
                <th>Consultation</th>
                <th>Registration No.</th>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
