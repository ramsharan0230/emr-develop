<!DOCTYPE html>
<html>
<head>
    <title>Radio Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }
    </style>

</head>
<body>

@php
    $patientInfo = $encounter_data->patientInfo;
    $iterationCount = 1;
    //$signatures = Helpers::getSignature('radiology');

    $noOfPage = request('noOfPage', 1);

    $categoryArray = [];
    $reportUsers = array_unique($samples->pluck('flduserid_report')->toArray());
    $verifyUsers = array_unique($samples->pluck('flduserid_verify')->toArray());
    if (count($reportUsers)){
        $reportSignatures = \App\Utils\Helpers::getSignatures($reportUsers);
    }else{
        $reportSignatures = [];
    }
    if (count($reportUsers)){
        $verifySignatures = \App\Utils\Helpers::getSignatures($verifyUsers);
    }else{
        $verifySignatures = [];
    }
@endphp

@for ($i = 0; $i < $noOfPage; $i++)
    @include('pdf-header-footer.header-footer')
    <main>

        <hr>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td style="width: 200px;">
                    <p><strong>Name:</strong> {{ $patientInfo->fldrankfullname }} ({{$patientInfo->fldpatientval}})</p>
                    <p>
                        <strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }}
                        {{-- <strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }} --}}
                        yrs/{{ $patientInfo->fldptsex??"" }}</p>
                    <p>
                        <strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}
                    </p>
                </td>
                <td style="width: 185px;">
                    <p><strong>EncID:</strong> {{ $encounter_data->fldencounterval }}</p>
                    <p>
                        <strong>DOReg:</strong> {{ $encounter_data->fldregdate ? \Carbon\Carbon::parse($encounter_data->fldregdate)->format('d/m/Y'):'' }}
                    </p>
                    <p><strong>Phone: {{ $noOfPage }} </strong></p>
                </td>
                <td style="width: 130px;">{!!  Helpers::generateQrCodeQr($encounter_data->fldencounterval) !!}</td>
            </tr>
            </tbody>
        </table>
        <hr>
        <div>
            @foreach($samples as $sample)
                <div style="text-align: center;"><strong>{{ $sample->fldtestid }}</strong></div>
                @if(isset($sample->subtest)  && count($sample->subtest))
                    <table style="width: 100%;" class="content-body">
                        @foreach($sample->subtest as $subTest)
                            <tr>
                                <td>{{ $subTest->fldsubtest }}</td>
                                <td>{{ $subTest->fldreport }}</td>
                                @if(isset($subTest->quantity_range))
                                    <td>{{ $subTest->quantity_range->fldreference }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                @else
                    @if($sample->fldreportquali !== NULL)
                        {!! $sample->fldreportquali !!}
                        @if($sample->testLimit && $sample->testLimit->isNotEmpty())
                            @foreach($sample->testLimit as $testLimit)
                                {{ $testLimit->fldsiunit }}
                            @endforeach
                        @endif
                    @endif
                @endif
            @endforeach
        </div>

        <div style="width: 70%; float: left;">
            <div class="row">
                @if(count($reportSignatures))
                    @forelse($reportSignatures as $reportSignature)
                        <div style="width: 45%; float: left;">
                            @if($reportSignature->image == null)
                                <p style="margin-top: 106px">_________________________</p>
                            @else
                                <img class="" style="width: 90%;"
                                    src="data:image/jpg;base64,{{ $reportSignature->image }}" alt="">
                                <p style="margin-top: 0; padding-top: 0;">_________________________</p>
                            @endif

                            <p>Reported By : {{ $reportSignature->fullname }}</p>
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
                                <p>NHBC: {{ $reportSignature->nhbc }}</p>
                            @endif
                        </div>
                    @empty

                    @endforelse
                @endif
            </div>
        </div>
        <!-- end report signture -->
        <!-- verify signture -->
        <div style="width: 25%; float: left">
            <div class="row">
                @if(count($verifySignatures))
                    @forelse($verifySignatures as $verifySignature)
                        <div style="width: 45%; float: left;">
                            @if($verifySignature->image == null)
                                <p style="margin-top: 106px">_________________________</p>
                            @else
                                <img class="" style="width: 90%;"
                                    src="data:image/jpg;base64,{{ $verifySignature->image }}" alt="">
                                <p style="margin-top: 0; padding-top: 0;">_________________________</p>
                            @endif

                            <p>Verified By : {{ $verifySignature->fullname }}</p>
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
                                <p>NHBC: {{ $verifySignature->nhbc }}</p>
                            @endif
                        </div>
                        <div style="clear: both"></div>
                    @empty

                    @endforelse

                @endif
            </div>
        </div>
        <!-- verify signture -->
    </main>
    <div class="clearfix" style="page-break-after: always;"></div>
@endfor


</body>
</html>
