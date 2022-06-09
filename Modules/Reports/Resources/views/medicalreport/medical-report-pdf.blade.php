@extends('inpatient::pdf.layout.main')

@section('title')
MEDICAL REPORT
@endsection

@section('content')
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p><b>Category: {{ $category }}</b> </p>
            <p><b>From: {{ $finalfrom }}</b> </p>
            <p><b>To: {{ $finalto }}</b> </p>
            <p><b>Gender: {{ $gender }}</b> </p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>Index</th>
            	<td>Date</td>
            	<td>EncID</td>
            	<td>Name</td>
            	<td>Age</td>
            	<td>Gender</td>
            	<td>DOReg</td>
            	<td>Patient No</td>
                <td>Observation</td>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
