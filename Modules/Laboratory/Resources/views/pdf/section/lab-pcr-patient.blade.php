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
</style>

@php
    $patientInfo = $encounter_data->patientInfo;
    $iterationCount = 1;
    $histoData = NULL;
    $allcomments = [];
@endphp
<section class="pdf-container">
    <h4 class="text-center" style="margin-top:12px;">Department Of Labroratory</h4>
    <div class="reception-no">
        <p>Reception No:<span>{{Options::get('reception_number')}}</span>&nbsp;&nbsp;</p>
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
                                <td style="padding:0;"> {{ Options::get('system_patient_rank')  == 1 ? $patientInfo->fldrank : '' }} {{ $patientInfo->fldptnamefir. ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><span class="label">Age/Sex:</span> {{ $patientInfo->fldagestyle??'' }}/{{ $patientInfo->fldptsex??"" }}</td>
                </tr>
                <tr>
                    <td>
                        <table style="margin-left:-4px">
                            <tr>
                                <td class="label">Address: </td>
                                <td style="padding:0;"> {{ $patientInfo->fulladdress??'' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><span class="label">Phone No: </span>{{ $patientInfo->fldptcontact??'' }}</td>
                </tr>
                <tr>
                    <td><span class="label">Reffered By: </span>{{ $referable_doctor ?? '' }}</td>
                </tr>
                <tr>
                    <td><span class="label">Department: </span>{{ $encounter_data->fldcurrlocat ?? '' }}</td>
                </tr>
                {{-- <tr>
                    <td><span class="label">Passport No : </span></td>
                </tr> --}}
    
            </tbody>
        </table>
        <table class="sample-detail">
            <tbody>
                <tr>
                    <td>{!! Helpers::generateQrCodeQr($encounter_data->fldencounterval) !!}
                    </td>
                </tr>
                <tr>
                    <td><span class="label">Lab Ref No :</span> {{ $sampleid }} </td>
                </tr>
                <tr>
                    <td><span class="label">Registered On :</span> {{ $encounter_data->fldregdate ? \App\Utils\Helpers::dateToNepali($encounter_data->fldregdate, FALSE) : '' }} ({{$encounter_data->fldregdate}})</td>
                </tr>
                <tr>
                    <td><span class="label">Reported On:</span> {{ \App\Utils\Helpers::dateToNepali($reportTime, FALSE) }}&nbsp;({{ isset($reportTime) ? $reportTime :'' }})</td>
                </tr>
                <tr>
                    <td><span class="label">Sample Received Date:</span> 2022-12-09(2078-2-2) - 11: 00 AM</td>
                </tr>    
                {{-- <tr>
                    <td><span class="label">Country Issued : </span></td>
                </tr> --}}
    
            </tbody>
        </table>
    </div>
    
        @php
            $report_segment = Request::segment(3)
        @endphp
        @if($report_segment == 'printing')
            @if(isset($_GET['status']) && $_GET['status']=='reported')
                <div style="text-align: center"><h3>UNVERIFIED</h3></div>
            @endif
        @endif
        <table class="testids" style="width: 100%; margin-top: 20px;"class="content-body">
            <tbody>
                <tr id="js-report-table-header">
                    <td class="heading">Test</td>
                    <td  class="heading">Result</td>
                    <td  class="heading" >Reference Range</td>
                    {{-- <td>Comment</td> --}}
                </tr>
                @php
                $hasothertest = false;
                @endphp
                @foreach($samples as $category => $category_sample)

                @php
                $conditionCategory = strtolower($category);
                $allHisto = ['histopathology', 'histo pathology', 'hesto pathology', 'cytology'];
                if (in_array($conditionCategory, $allHisto)) {
                $histoData = $category_sample;
                continue;
                }
                $hasothertest = true;
                @endphp
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <h4>{{ $category ?? "" }}</h4>
                    </td>
                </tr>
                @foreach($category_sample as $specimens)
                @foreach($specimens as $groupnamekey => $groupname)
                @php $displaygroupname = ($groupname && count($groupname) > 1); @endphp
                @if ($displaygroupname)
                <td colspan="3">
                    <h4>{{ $groupnamekey ?? "" }}</h4>
                </td>
                @endif
                @foreach($groupname as $sample)
                <tr>
                    <td @if ($displaygroupname) style="padding-left: 15px;" @endif>
                        {{ $sample->fldtestid }}
                        @if($sample->fldtestid == 'Culture & Sensitivity')
                        [{{ $sample->fldsampletype }}]
                        @endif
                    </td>
                    @if(count($sample->subTest) == 0 && $sample->fldstatus != 'Not Done')
                    <td>
                        @if($sample->fldreportquali !== NULL)
                        @if ($sample->fldabnormal == '1')
                        <strong>
                            @endif
                            {!! $sample->fldreportquali !!}
                            @if ($sample->fldabnormal == '1')
                        </strong>
                        @endif
                        @endif
                    </td>
                    <td>
                        @if ($sample->test && $sample->test->fldoption == 'Text Reference' && $sample->test->testoptions->isNotEmpty())
                        @foreach($sample->test->testoptions as $testoption)
                        {{ $testoption->fldanswer }} <br>
                        @endforeach
                        @else
                        {{ \App\Utils\LaboratoryHelpers::getQuantitativeTestLimit($sample->fldtestid, $patientInfo->fldptsex) }}
                        @endif
                    </td>
                    @else
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    @endif
                    @if($sample->fldcomment)
                    @php
                    array_push($allcomments,strip_tags($sample->fldcomment));
                    @endphp
                    @endif
                </tr>
                @if(isset($sample->subTest) && count($sample->subTest))
                @foreach($sample->subTest as $subTest)
                <tr>
                    <td style="padding-left: 25px;">{{ $subTest->fldsubtest }}</td>
                    <td>
                        @if($sample->fldtestid == 'Culture & Sensitivity' && $subTest->subtables)
                        <ul>
                            @foreach($subTest->subtables as $subtable)
                            <li>
                                {{ $subtable->fldvariable }} : {{ $subtable->fldvalue }}
                                @if ($subtable->fldcolm2) [{{ $subtable->fldcolm2 }}] @endif
                            </li>
                            @endforeach
                        </ul>
                        @else
                        {!! $subTest->fldreport !!}
                        @endif
                    </td>
                    <td>{{ \App\Utils\LaboratoryHelpers::getRefranceRange($sample->fldtestid, $subTest->fldsubtest) }}</td>
                </tr>
                @endforeach
                @endif
                @endforeach
                @endforeach
                @endforeach
                @endforeach

            </tbody>
        </table>

        @if ($histoData)
        @foreach($histoData as $specimens)
        @foreach($specimens as $groupnamekey => $groupname)
        @foreach($groupname as $sample)
        <p style="text-align: center;font-size: 20px"><strong>{{ $sample->fldtestid }}</strong></p>
        @if(isset($sample->subTest) && count($sample->subTest))
        @foreach($sample->subTest as $subTest)
        <div style="width: 100%;">
            <div style="width: 30%; float: left;"><strong>{{ $subTest->fldsubtest }}:</strong></div>
            <div style="width: 70%; float: left;">
                <p>{!! $subTest->fldreport !!}</p>
            </div>
        </div>
        <div class="clearfix"></div>
        @endforeach
        @endif
        @endforeach
        @endforeach
        @endforeach
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
</section>