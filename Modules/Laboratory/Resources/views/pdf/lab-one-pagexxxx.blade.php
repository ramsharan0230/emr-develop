<!DOCTYPE html>
<html>
<head>
    <title>Lab Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
            font-size: {{ \App\Utils\Options::get('lab_print_test_detail', 18) }}px;
        }
        .patient-info-td {
            font-size: {{ \App\Utils\Options::get('lab_print_patient_info', 12) }}px;
        }
        .reception-table {
            width: 100%;
            font-size: {{ \App\Utils\Options::get('lab_print_phone_number', 8) }}px;
        }
        header img {
            height: {{ \App\Utils\Options::get('lab_print_logo_image_height', 150) }}px;
            width: {{ \App\Utils\Options::get('lab_print_logo_image_width', 150) }}px;
        }
        header h3 {
            font-size: {{ \App\Utils\Options::get('lab_print_hospital_name', 40) }}px;
        }
        header h4 {
            font-size: {{ \App\Utils\Options::get('lab_print_hospital_address', 35) }}px;
        }

        .signature-div {
            font-size: {{ \App\Utils\Options::get('lab_print_signature', 12) }}px;
        }

        .signature-image {
            height: {{ \App\Utils\Options::get('lab_print_signature_height', 50) }}px;
            width: {{ \App\Utils\Options::get('lab_print_signature_image_width', 70) }}px;
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }
        .border-tr {
            border: 2px solid black;
        }

        .content-body {
            font-size: 13px;
        }
        .clearfix::after {
            display: block;
            clear: both;
            content: "";
        }
        .test-content tr td, .test-content tr td, h4,h3 {
            padding: 0px;
            margin: 0px;
        }
        .td-width{
            width: 33.33%;
            float: left;
        }

        .td-width ul {
            margin: 0px;
            padding: 0px;
        }

        .td-width ul li {
            list-style: none;
        }

        .form-row{
            display:flex;
        }
        .form-row p {
            margin: 0;
            padding: 0;
        }

        .text-right{
            text-align: right;;
        }
        .background-color {
            background: gray;
            -webkit-print-color-adjust: economy;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        @media print {
            .background-color {
                -webkit-print-color-adjust: economy;
                -webkit-print-color-adjust: exact;
                background: gray;
            }
        }
        .td-align-top {
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
        }
    </style>

</head>
<body>

@php
    $patientInfo = $encounter_data->patientInfo;
    $iterationCount = 1;
@endphp
@include('pdf-header-footer.header-footer')
<main>
    {{-- <table class="reception-table">
        <tbody>
            <tr>
                <td class="text-right"><strong>Reception Number:</strong> {{ Options::get('reception_number')??'' }}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Lab extension Number:</strong> {{ Options::get('lab_extension_number')??'' }}</td>
            </tr>
        </tbody>
    </table> --}}

    @foreach($samples as $category => $category_sample)
    @php
        $allcomments = [];
    @endphp
    <table style="width: 100%;" class="border-tr">
        <tbody>
            <tr class="patient-info-td">
                <td style="width: 170px;">
                    <p>
                        <strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 ? $patientInfo->fldrank : '' }} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }}
                        ({{$patientInfo->fldpatientval}})</p>
                    <p>
                        <strong>Age/:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday ??"")->age }}yrs
                    </p>
                    <p>
                        <strong>Sex:</strong> {{ $patientInfo->fldptsex??"" }}
                    </p>
                    <p>
                        <strong>Referred By:</strong> {{ $fldrefername }}
                    </p>

                    <p><strong>Verified Date:</strong> {{ $verifyTime }} {{ isset($verifyTime) ?"(".  \App\Utils\Helpers::dateToNepali($verifyTime) .")" :'' }}</p>
                </td>
                <td style="width: 300px;">
                    <p><strong>EncID:</strong> {{ $encounter_data->fldencounterval }}</p>
                    <p><strong>Sample Id: {{ $sampleid }}</strong></p>
                    <p><strong>Sampled Collection:</strong> {{ $sampleTime }} {{ isset($sampleTime) ? "(". \App\Utils\Helpers::dateToNepali($sampleTime) .")" :'' }}</p>
                    <p><strong>Reporting Date:</strong> {{ $reportTime }} {{ isset($reportTime) ? "(". \App\Utils\Helpers::dateToNepali($reportTime) .")" :'' }}</p>
                </td>
                <td style="width: 70px;">
                    {!!  Helpers::generateQrCodeQr($encounter_data->fldencounterval) !!}
                </td>
            </tr>
        </tbody>
    </table>
    <br>
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
        <table style="width: 100%;" class="content-body test-content">
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
                                    <table style="width: 100%;" class="content-body test-content">
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
                                            <table style="width: 100%;" class="content-body test-content">
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
        <table style="width: 100%;" class="content-body test-content">
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

        @php
            if (count($reportUsers)){
                $reportSignatures = \App\Utils\Helpers::getSignatures($reportUsers);
            }else{
                $reportSignatures = [];
            }
            if (count($verifyUsers)){
                $verifySignatures = \App\Utils\Helpers::getSignatures($verifyUsers);
            }else{
                $verifySignatures = [];
            }
        @endphp
        <div style="width: 100%;" class="signature-div">
            <!-- report signture -->
            <div style="width: 50%; float: left;">
                <div class="row">
                    @if(count($reportSignatures))
                        @forelse($reportSignatures as $reportSignature)
                            <div style="width: 50%; float: left;">
                                @if($reportSignature->signature_image == null)
                                    <p style="margin-top: 106px">_________________________</p>
                                @else
                                    <img class="signature-image"
                                         src="data:image/jpg;base64,{{ $reportSignature->signature_image }}" alt="">
                                    <p style="margin-top: -12px; padding-top: 0;">_________________________</p>
                                @endif

                                    <p>Performed By : </p>
                                    <p>{{ $reportSignature->firstname .' '.$reportSignature->middlename.' '.$reportSignature->lastname  }}</p>
                                @if($reportSignature->designation)
                                    <p>{{ $reportSignature->designation }}</p>
                                @endif
                                @if($reportSignature->signature_profile)
                                    <p>{{ $reportSignature->signature_profile }}</p>
                                @endif
                                @if($reportSignature->signature_title)
                                    <p>{{ $reportSignature->signature_title }}</p>
                                @endif
                                @if($reportSignature->nmc)
                                    <p>NMC: {{ $reportSignature->nmc }}</p>
                                @endif
                                @if($reportSignature->nhbc)
                                    <p>NHPC: {{ $reportSignature->nhbc }}</p>
                                @endif
                            </div>
                        @empty
                        @endforelse
                    @endif
                </div>
            </div>
            <!-- end report signture -->
            <!-- verify signture -->
            <div style="width: 50%; float: right; text-align: right;">
                <div class="row">
                    @if(count($verifySignatures))
                        @forelse($verifySignatures as $verifySignature)
                            <div style="width: 50%; float: right;">
                                @if($verifySignature->signature_image == null)
                                    <p style="margin-top: 106px">_________________________</p>
                                @else
                                    <img class="signature-image"
                                         src="data:image/jpg;base64,{{ $verifySignature->signature_image }}" alt="">
                                    <p style="margin-top: -12px; padding-top: 0;">_________________________</p>
                                @endif

                                    <p>Approved By : </p>
                                    <p>{{ $verifySignature->firstname .' '.$verifySignature->middlename.' '.$verifySignature->lastname  }}</p>
                                @if($verifySignature->designation)
                                    <p>{{ $verifySignature->designation }}</p>
                                @endif
                                @if($verifySignature->signature_profile)
                                    <p>{{ $verifySignature->signature_profile }}</p>
                                @endif
                                @if($verifySignature->signature_title)
                                    <p>{{ $verifySignature->signature_title }}</p>
                                @endif
                                @if($verifySignature->nmc)
                                    <p>NMC: {{ $verifySignature->nmc }}</p>
                                @endif
                                @if($verifySignature->nhbc)
                                    <p>NHPC: {{ $verifySignature->nhbc }}</p>
                                @endif
                            </div>
                        @empty
                        @endforelse
                    @endif
                </div>
            </div>

            <div style="clear: both"></div>
            <!-- verify signture -->
        </div>
    </div>
    <div class="clearfix"></div>
    <div>
        <p style="font-size: {{ \App\Utils\Options::get('lab_print_footer_comment', 8) }}px;">This is a computer generated report. Hence cannot be used for medical analysis.</p>
    </div>
    <div class="clearfix" style="page-break-after: always;"></div>
    @endforeach

</main>
</body>
</html>
