@extends('inpatient::pdf.layout.main')

@section('content')
	<ul>
		<li>
			@if(request('moduleName') == 'majorprocedure')
			Major Procedures
			@elseif(request('moduleName') == 'extraprocedure')
			Extra Procedures
			@elseif(request('moduleName') == 'radiologylist')
			Radiology List
			@endif
			Report
		</li>
		<li> {{ request('date') }}</li>
	</ul>

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
                <th>&nbsp;</th>
                <th>Time</th>
                <th>Particulars</th>
                <th>EncID</th>
                <th>Name</th>
                <th>Age/Sex</th>
                <th>Contact</th>
                <th>Consultant</th>
                <th>FileNo</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($all_data as  $data)
                <tr>
	                <td>{{ $loop->iteration }}</td>
	                <td>{{ explode(' ', $data->fldnewdate)[0] }}</td>
	                <td>{{ $data->fldtestid }}</td>
	                <td>{{ $data->fldencounterval }}</td>
	                <td>{{ $data->encounter->patientInfo->fldfullname }}</td>
	                <td>{{ $data->encounter->patientInfo->fldagestyle }}/ {{ $data->encounter->patientInfo->fldptsex }}</td>
	                <td>{{ $data->encounter->patientInfo->fldptcontact ?: '' }}</td>
	                <td>{{ $data->encounter->consultant ? $data->encounter->consultant->fldconsultname : '' }}</td>
	                <td>&nbsp;</td>
                </tr>
        	@endforeach
        </tbody>
    </table>
@endsection
