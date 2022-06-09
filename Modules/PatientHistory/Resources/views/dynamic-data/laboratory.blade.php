@if($laboratory)
    <table class="table">
    <thead>
    <tr>
        <th>Specimen</th>
        <th>SampleId</th>
        <th>Examination</th>
        <th>Observation</th>
        <th>Comment</th>
        <th>Verified By</th>
        <th>Time</th>
    </tr>
    </thead>
    <tbody>

        @foreach($laboratory as $key => $dates)
            <tr>
                <td colspan="5" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
                <tr>
                    <td>{{ $data->fldsampletype }}</td>
                    <td>{{ $data->fldsampleid }}</td>
                    <td>{{ $data->fldtestid }}</td>
                    <td>
                        @if($data->fldtestid == 'Culture & Sensitivity')
                            @if ($data->subTest->isNotEmpty())
                                <ul>
                                    @foreach ($data->subTest as $subTest)
                                        <li>
                                            {{ $subTest->fldsubtest }}
                                            <ul style="padding: 0px;">
                                                @foreach ($subTest->subtables as $subtable)
                                                    <li style="list-style: none;">{{ $subtable->fldvariable }} : {{ $subtable->fldvalue }} [{{ $subtable->fldcolm2 }}]</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                {!! $data->fldreportquali !!}
                            @endif
                        @elseif($data->subTest->isNotEmpty())
                            <ul>
                                @foreach ($data->subTest as $answer)
                                    <li><strong>{{ $answer->fldsubtest }}</strong>: {!! $answer->fldreport !!}</li>
                                @endforeach
                            </ul>
                        @else
                            {!! $data->fldreportquanti !!}
                            @if($data->testLimit)
                                @foreach($data->testLimit as $testlimit)
                                    {{ $testlimit->fldsiunit }}
                                @endforeach
                            @endif
                        @endif
                    </td>
                    <td>{!! $data->fldcomment !!}</td>
                    <td>{{ $data->flduserid_verify }}</td>
                    <td>{{ $data->time }}</td>
                </tr>
            @endforeach
        @endforeach

    </tbody>
</table>
@endif
