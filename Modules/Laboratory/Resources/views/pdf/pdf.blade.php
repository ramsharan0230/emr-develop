@extends('inpatient::pdf.layout.main')

@section('title')
LABORATORY SAMPLE REPORT
@endsection

@section('report_type')
LABORATORY SAMPLE REPORT
@endsection

@section('content')
    <table style="width: 100%;" class="content-body">
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
            @foreach($samples as $key => $value)
                <tr>
                    <td style="text-align: center;" colspan="10">{{ $key }}</td>
                </tr>
                @foreach($value as $sample)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sample->fldsampleid??"" }}</td>
                        <td>{{ $sample->fldtestid??"" }}</td>
                        <td>{!! (isset($sample->fldabnormal) && $sample->fldabnormal == '0') ? '<div style="background-color: green;width: 15px;height: 15px;"></div>' : '<div style="background-color: red;width: 15px;height: 15px;"></div>' !!}</td>
                        
                        <td>
                            @if($sample->fldtestid == 'Culture & Sensitivity')
                                @if ($sample->subTest->isNotEmpty())
                                    <table style="width: 100%;" class="content-body test-content table-bordered">
                                        <tbody>
                                            @foreach ($sample->subTest as $subtest)
                                            <tr>
                                                <td>{{ $subtest->fldsubtest }}</td>
                                                <td>
                                                    <table style="width: 100%;" class="content-body test-content">
                                                        <tbody>
                                                            @foreach ($subtest->subtables as $subtable)
                                                            <tr>
                                                                <td class="td-width">{{ $subtable->fldvariable }}</td>
                                                                <td class="td-width">{{ $subtable->fldvalue }}</td>
                                                                <td class="td-width">{{ $subtable->fldcolm2 }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    {!! $sample->fldreportquali !!}
                                @endif
                            @elseif($sample->fldreportquali !== NULL)
                                <span class="quantity-{{ $sample->fldid }}">
                                    {!! $sample->fldreportquali !!}
                                </span>

                                @if($sample->testLimit->isNotEmpty())
                                    @foreach($sample->testLimit as $testLimit)
                                        {{ $testLimit->fldsiunit }}
                                    @endforeach
                                @endif
                            @elseif($sample->subTest)
                                @foreach($sample->subTest as $subTest)
                                    @if($subTest->fldreport)
                                        <strong>{{ $subTest->fldsubtest }}</strong>
                                        <br>
                                        {!! $subTest->fldreport !!} <br>
                                    @endif
                                @endforeach
                            @endif
                        </td>
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
