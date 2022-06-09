@extends('inpatient::pdf.layout.main')

@section('title')
Laboratory Report
@endsection

@section('content')
<table class="table">
    <thead>
        <tr>
            <th class="tittle-th">SN</th>
            <th class="tittle-th">SamID</th>
            <th class="tittle-th">Test Name</th>
            <th class="tittle-th"></th>
            <th class="tittle-th">Observation</th>
            <th class="tittle-th">Visibility</th>
            <th class="tittle-th">Specimen</th>
            <th class="tittle-th">Method</th>
            <th class="tittle-th">Sample Time</th>
            <th class="tittle-th">Report Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach($samples as $key => $sample)
        <tr data-sampleid="' + sample.fldid + '">
            <td>{{ ($key+1) }}</td>
            <td>{{ $sample->fldsampleid }}</td>
            <td>{{ $sample->fldtestid }}</td>
            <td>{!! ($sample->fldabnormal == '0') ? '<div style="background-color: green;width: 15px;height: 15px;"></div>' : '<div style="background-color: red;width: 15px;height: 15px;"></div>' !!}</td>
            <td>&nbsp;</td>
            <td>{{ $sample->flvisible }}</td>
            <td>{{ $sample->fldsampletype }}</td>
            <td>{{ $sample->fldmethod }}</td>
            <td>{{ $sample->fldtime_sample }}</td>
            <td>{{ ($sample->fldtime_report ? $sample->fldtime_report : '') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection