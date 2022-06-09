<style>
    table {
        page-break-inside: auto;
        border-collapse: collapse;
    }

    .patient-detail, .sample-detail {
        float: unset;
    }

    .patient-container {
        display: flex;
        flex-direction: row;
        padding: 5px 0;

    }
    .patient-detail tr td {
        padding-left: 5px;
    }
    .testids tr td {
        border:1px solid #c4c4c4;
        padding: 5px;
    }

    .background-color {
        background-color: #c4c4c4;
    }
</style>

@php
    $patientInfo = $encounter_data->patientInfo;
    $iterationCount = 1;
@endphp
<section class="pdf-container">
    <h4 class="text-center" style="margin-top:12px;">Department Of Labroratory</h4>
    <div class="reception-no">
        <p>Reception No:<span>{{Options::get('reception_number')}}</span></p>
        <p>Extension No:<span>{{Options::get('lab_extension_number')}}</span></p>
    </div>
</section>

<section class="pdf-container">
    <h5 class="bill-title"> </h5>

    <div class="patient-container">
        <table class="patient-detail">
            <tbody>
                <tr>
                    <td>
                        <table style="margin-left:-4px">
                            <tr>
                                <td class="label">Name : </td>
                                <td style="padding:0;"> {{ Options::get('system_patient_rank')  == 1 ? $patientInfo->fldrank : '' }} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }}
                                    ({{$patientInfo->fldpatientval}})</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><span class="label">Age/Sex:</span> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday ??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</td>
                </tr>
                <tr>
                    <td><span class="label">Reffered By: </span>{{ $fldrefername ?? '' }}</td>
                </tr>
                <tr>
                    <td><span class="label">Verified Date: </span>{{ $verifyTime }} {{ isset($verifyTime) ?"(".  \App\Utils\Helpers::dateToNepali($verifyTime) .")" :'' }}</td>
                </tr>
    
            </tbody>
        </table>
        <table class="sample-detail">
            <tbody>
                <tr>
                    <td>{!!  Helpers::generateQrCodeQr($encounter_data->fldencounterval) !!}
                    </td>
                </tr>
                <tr>
                    <td><span class="label">EncID :</span> {{ $encounter_data->fldencounterval }} </td>
                </tr>
                <tr>
                    <td><span class="label">Sample Id :</span> {{ $sampleid }}</td>
                </tr>
                <tr>
                    <td><span class="label">Sampled Collection:</span> {{ $sampleTime }} {{ isset($sampleTime) ? "(". \App\Utils\Helpers::dateToNepali($sampleTime) .")" :'' }}</td>
                </tr>
                <tr>
                    <td><span class="label">Reporting Date:</span> {{ $reportTime }} {{ isset($reportTime) ? "(". \App\Utils\Helpers::dateToNepali($reportTime) .")" :'' }}</td>
                </tr>    
            </tbody>
        </table>
    </div>


    <br>
    @foreach($samples as $category => $category_sample)
    @php
        $allcomments = [];
    @endphp
    <table style="width: 100%">
        <tr>
        </tr>
    </table>
    <div>
        @php
            $reportUsers = [];
            $verifyUsers = [];
            $conditionCategory = strtolower($category);
            $allHisto = ['histopathology', 'histo pathology', 'hesto pathology', 'cytology'];
        @endphp
        @if (in_array($conditionCategory, $allHisto))
            @foreach($category_sample as $specimen => $testgroup)
                {{-- <p><strong>{{ $specimen }}</strong></p> --}}
                @foreach($testgroup as $groupname => $fldsampletype)
                    @if (count($fldsampletype) > 1)
                    <p><strong>{{ $groupname }}</strong></p>
                    @endif
                    @foreach ($fldsampletype as $sample)
                        @php
                            $reportUsers[] = $sample->flduserid_report;
                            $verifyUsers[] = $sample->flduserid_verify;
                        @endphp
                        <br>
                        <p style="text-align: center;font-size: 20px"><strong>{{ $sample->fldtestid }}</strong></p>

                        @if(isset($sample->subTest)  && count($sample->subTest))
                            @foreach($sample->subTest as $subTest)
                            <div style="width: 100%;">
                                <div style="width: 30%; float: left;"><strong>{{ $subTest->fldsubtest }}:</strong></div>
                                <div style="width: 70%; float: left;"><p>{!! $subTest->fldreport !!}</p></div>
                            </div>

                            <div class="clearfix"></div>
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        @elseif($conditionCategory == 'microbiology')
        <table class="testids" style="width: 100%;" class="content-body test-content">
            <tbody>
                <tr class="background-color">
                    <td colspan="2" style="text-align: center;"><h4>{{ $category ?? "" }}</h4></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr class="border-tr">
                    <td>Examination</td>
                    <td>Result</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                @foreach($category_sample as $specimen => $testgroup)
                    <tr>
                        <td colspan="2"><strong>{{ $specimen }}</strong></td>
                    </tr>
                    @foreach($testgroup as $groupname => $fldsampletype)
                        @if (count($fldsampletype) > 1)
                        <tr>
                            <td colspan="2"><strong>{{ $groupname }}</strong></td>
                        </tr>
                        @endif
                        @foreach ($fldsampletype as $sample)
                            @php
                                $reportUsers[] = $sample->flduserid_report;
                                $verifyUsers[] = $sample->flduserid_verify;
                            @endphp
                            <tr>
                                <td style="padding-left: 20px;" class="td-align-top">
                                    {{ $sample->fldtestid }}@if ($sample->fldtestid == 'Culture & Sensitivity')[{{ $sample->fldsampletype }}]@endif
                                </td>
                                <td>
                                    @if (strpos(strtolower($sample->fldtestid), 'afb') !== false)
                                    <table class="testids" style="width: 100%;" class="content-body test-content">
                                        <tbody>
                                            <tr>
                                                @foreach ($sample->subTest as $subtest)
                                                <td class="td-align-top td-width"><strong>{{ $subtest->fldsubtest }}</strong><br>{!! $subtest->fldreport !!}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @elseif(isset($sample->subTest) && count($sample->subTest))
                                    @if ($sample->fldtestid == 'Culture & Sensitivity')
                                        {!! \App\Utils\LaboratoryHelpers::formatCultureReport($sample->subTest) !!}
                                    @else
                                    <table class="testids" style="width: 100%;" class="content-body test-content">
                                        <tbody>
                                            @foreach ($sample->subTest as $subtest)
                                            <tr>
                                                <td class="td-align-top">
                                                    <strong>{{ $subtest->fldsubtest }}</strong>
                                                </td>
                                                @if ($subtest->subtables->isNotEmpty())
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
                                                @else
                                                <td>{!! $subtest->fldreport !!}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                    @elseif(count($sample->subTest) == 0)
                                    {!! $sample->fldreportquali !!}
                                    @endif
                                </td>
                            </tr>
                            @if($sample->fldcomment)
                                @php
                                    array_push($allcomments,strip_tags($sample->fldcomment));
                                @endphp
                            {{-- <tr>
                                <td colspan="2">
                                    <div class="form-row">
                                        Comment:&nbsp; {!! $sample->fldcomment !!}
                                    </div>
                                </td>
                            </tr> --}}
                            @endif
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        @else
        @php
            $collength = ($conditionCategory == 'serology') ? 6 : 5;
        @endphp
        <table class="testids" style="width: 100%;" class="content-body test-content">
            <tbody>
                <tr class="background-color">
                    <td colspan="{{ $collength }}" style="text-align: center;"><h4>{{ $category ?? "" }}</h4></td>
                </tr>
                <tr>
                    <td colspan="{{ $collength }}">&nbsp;</td>
                </tr>
                <tr class="border-tr">
                    <td>Examination</td>
                    @if ($conditionCategory == 'serology')
                    <td>Method</td>
                    @endif
                    <td>Result</td>
                    <td>Unit</td>
                    <td>Reference Range</td>
                </tr>
                <tr>
                    <td colspan="{{ $collength }}">&nbsp;</td>
                </tr>
                @foreach($category_sample as $specimen => $testgroup)
                    <tr>
                        <td colspan="{{ $collength }}"><strong>{{ $specimen }}</strong></td>
                    </tr>
                    @foreach($testgroup as $groupname => $fldsampletype)
                        @if (count($fldsampletype) > 1)
                            <tr>
                                <td colspan="{{ $collength }}"><strong>{{ $groupname }}</strong></td>
                            </tr>
                        @endif
                        @foreach ($fldsampletype as $sample)
                            @php
                                $reportUsers[] = $sample->flduserid_report;
                                $verifyUsers[] = $sample->flduserid_verify;
                            @endphp
                            <tr>
                                <td>
                                    {{ $sample->fldtestid }}
                                    @if($sample->fldtestid == 'Culture & Sensitivity')
                                    [{{ $sample->fldsampletype }}]
                                    @endif
                                    @if($sample->fldcomment)
                                    @php
                                        array_push($allcomments,strip_tags($sample->fldcomment));
                                    @endphp
                                    {{-- <br>
                                    <div class="form-row">
                                        Comment:&nbsp; {!! $sample->fldcomment !!}
                                    </div> --}}
                                    @endif
                                </td>
                                @if ($conditionCategory == 'serology')
                                <td>{{ $sample->fldmethod }}</td>
                                @endif
                                @if ($sample->fldtestid == 'Culture & Sensitivity')
                                    <td>
                                        @if(isset($sample->subTest)  && count($sample->subTest))
                                        <table style="width: 100%;" class="content-body test-content">
                                            <tbody>
                                                @foreach ($sample->subTest as $subtest)
                                                <tr>
                                                    <td style="padding-left: 20px;">{{ $subtest->fldsubtest }}</td>
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
                                        @endif
                                        {!! $sample->fldreportquali !!}
                                    </td>
                                    <td>
                                        @if($sample->testLimit->isNotEmpty())
                                            @foreach($sample->testLimit as $testLimit)
                                                {{ $testLimit->fldsilow }} - {{ $testLimit->fldsihigh }} {{ $testLimit->fldsiunit }}
                                            @endforeach
                                        @endif
                                    </td>
                                @elseif(count($sample->subTest) == 0 && $sample->fldstatus != 'Not Done')
                                    <td>
                                        @if($sample->fldreportquali !== NULL)
                                            {!! $sample->fldreportquali !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if($sample->testLimit->isNotEmpty())
                                            @foreach($sample->testLimit as $testLimit)
                                                {{ $testLimit->fldsiunit }}
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($sample->testLimit->isNotEmpty())
                                            @foreach($sample->testLimit as $testLimit)
                                                {{ $testLimit->fldsilow }} - {{ $testLimit->fldsihigh }}
                                            @endforeach
                                        @endif
                                    </td>
                                @else
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                @endif
                            </tr>
                            @if($sample->fldtestid !== 'Culture & Sensitivity' && isset($sample->subTest)  && count($sample->subTest))
                                @foreach($sample->subTest as $subTest)
                                    <tr>
                                        <td style="padding-left: 20px;">{{ $subTest->fldsubtest }}</td>
                                        <td>
                                            @if($sample->fldtestid == 'Culture & Sensitivity' && $subTest->subtables)
                                                <ul>
                                                    @foreach($subTest->subtables as $subtable)
                                                    <li>{{ $subtable->fldvariable }} : {{ $subtable->fldvalue }} [{{ $subtable->fldcolm2 }}]</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                            {!! $subTest->fldreport !!}
                                            @endif
                                        </td>
                                        @if(isset($subTest->quantity_range))
                                            <td>{{ $subTest->quantity_range->fldreference }}</td>
                                        @else
                                            <td>{{ \App\Utils\LaboratoryHelpers::getRefranceRange($sample->fldtestid, $subTest->fldsubtest) }}</td>
                                        @endif
                                        <td>&nbsp;</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        @endif

        @if(count($allcomments) > 0)
        <div style="width: 100%; margin-top: 20px;">
            <div class="row">
                <h4>Comments : </h4>
                @foreach ($allcomments as $comment)
                    <p>{{$comment}}</p>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach
</section>