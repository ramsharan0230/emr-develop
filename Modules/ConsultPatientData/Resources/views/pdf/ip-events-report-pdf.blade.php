@extends('inpatient::pdf.layout.main')

@section('title')
IP EVENTS REPORT
@endsection

<style> 
.text-left{
    text-align: left !important;
}

.text-right{
    text-align: right !important;
}
</style>

@section('content')
    <h3 style="display: flex; justify-content:center;">{{$certificate}}</h3>
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>SUMMARY: {{$from_date}} TO {{$to_date}}</p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>Index</th>
                <th>EncID</th>
                <th width="300">Name</th>
                <th>Age</th>
                <th class="text-left">Gender</th>
                <th class="text-left">DOA</th>
                <th class="text-left">LastLocation</th>
                <th class="text-left">AdmitLocation</th>
                <th class="text-left">LastStatus</th>
                <th class="text-left">Consultant</th>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
