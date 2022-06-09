@extends('inpatient::pdf.layout.main')

@section('title')
LABORATORY SAMPLE REPORT
@endsection

@section('report_type')
LABORATORY SAMPLE REPORT
@endsection

@section('content')
    <table style="width: 100%;"  class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SN</th>
            <th class="tittle-th">SamID</th>
            <th class="tittle-th" style="width: 150px;">Test Name</th>
            <th class="tittle-th">Flag</th>
            <th class="tittle-th">Observation</th>
            {{--        <th class="tittle-th">Visibility</th>--}}
            <th class="tittle-th">Specimen</th>
            <th class="tittle-th">Method</th>
            <th class="tittle-th">Sample Time</th>
            <th class="tittle-th">Report Time</th>
            <th class="tittle-th">Comment</th>
        </tr>
        </thead>
        <tbody>
        @if(count($samples))

            @foreach($samples as $encounterId => $enc_sample)
                <tr>
                    <td colspan="10" style="text-align: center;">{{ $encounterId }}</td>
                </tr>
                @foreach($enc_sample as $sample)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sample->fldsampleid }}</td>
                        <td>{{ $sample->fldtestid }}</td>
                        <td>{!! ($sample->fldabnormal == '0') ? '<div style="background-color: green;width: 15px;height: 15px;"></div>' : '<div style="background-color: red;width: 15px;height: 15px;"></div>' !!}</td>
                        <td>{!! ($sample->fldtest_type == 'Quantitative' ? $sample->fldreportquanti : $sample->fldreportquali) !!}</td>
                        {{--                <td>{{ $sample->flvisible }}</td>--}}
                        <td>{{ $sample->fldsampletype }}</td>
                        <td>{{ $sample->fldmethod }}</td>
                        <td>{{ $sample->fldtime_sample }}</td>
                        <td>{{ $sample->fldtime_report }}</td>
                        <td>{{ $sample->fldcomment }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endif

        </tbody>
    </table>
@endsection
