@extends('inpatient::pdf.layout.main')

@section('title')
TRANSITION REPORT
@endsection

@section('content')
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
                <th width="200">Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>DOA</th>
                <th>BedNo</th>
                <th>Trans In</th>
                <th>Trans Out</th>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
