<!DOCTYPE html>
<html>
<head>
    <title>Dental OPD Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
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
@include('pdf-header-footer.header-footer')
<main>
    <div class="pdf-container">
        <div class="row" style="margin: 0 auto; width: 95%;">


            <br>
            <table style="width: 100%">
                <tr>
                    <td style="width: 40%;">Name: {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ isset($patient) ? $patient->fldptnamefir . ' ' . $patient->fldmidname . ' '. $patient->fldptnamelast:'' }}</td>
                    <td style="width: 30%;">Encounter ID: {{ $encounterData->fldencounterval }}</td>
                    <td>Age/Sex: @if(isset($patient)){{ $patient->fldagestyle }}  @endif / {{ $patient->fldptsex }}</td>
                    {{-- <td>Age/Sex: @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->age }} Years  @endif / {{ $patient->fldptsex }}</td> --}}
                </tr>
                <tr>
                    <td>Address: @if(isset($patient)){{ $patient->fldptaddvill }} , {{ $patient->fldptadddist }}@endif</td>
                    <td>Department: @if(isset($encounterData)){{ $encounterData->fldcurrlocat }}@endif</td>
                    <td>Regd Date: @if(isset($encounterData)){{ $encounterData->fldregdate }}@endif</td>
                </tr>
            </table>
            <ul>
                <li>Diagnosis: Diagnosis of Patient</li>

                <li>BP: Sys/Diast : @if(isset($systolic_bp) and $systolic_bp !='') {{ $systolic_bp->fldrepquali }} @endif / @if(isset($diasioli_bp) and $diasioli_bp !='') {{ $diasioli_bp->fldrepquali }}@endif</li>
            </ul>
        </div>
        <div class="pdf-container-body" style="border: 1px solid #212529">
            <div class="row">
                <div class="table-right">
                    <table class="dental-teeth" style="font-size: 16px;float: right; margin: 0 auto; width: 80%; padding: 20px;">
                        <img src="{{asset('assets/images/teeth.jpg')}}" style="width: 95%; padding: 20px">
                    </table>
                </div>
                @if(isset($imdData) or isset($softtissuelessonData) or isset($smoker) or isset($periodentalData) or isset($gingivalData))
                    <div class="pdf-sub-eading">
                        <h3 style="margin-left: 2%;">Basic Information:</h3>
                    </div>
                @endif
                @if(isset($imdData) or isset($softtissuelessonData) or isset($smoker) or isset($periodentalData) or isset($gingivalData))
                    <div class="table-left">
                        <table style="border: 1px solid;border-collapse: collapse; font-size: 17px; margin: 0 auto; width: 95%; margin-top: 7px;">
                            <tbody>
                            @if(isset($imdData) and $imdData->fldvalue !='')
                                <tr>
                                    <td style="border: 1px solid; padding:5px; width:42%;">TMD/Clicks/Muscle Pain</td>
                                    <td style="border: 1px solid; padding:5px; width: 42%;">
                                        @if($imdData->fldteeth !='')({{$imdData->fldteeth}})@endif
                                        {{ $imdData->fldvalue }}
                                    </td>
                                </tr>
                            @endif
                            @if(isset($softtissuelessonData) and $softtissuelessonData->fldvalue !='')
                                <tr>
                                    <td style="border: 1px solid; padding:5px; width: 50%">Soft Tissue Lesion</td>
                                    <td style="border: 1px solid; padding:5px;">
                                        @if($softtissuelessonData->fldteeth !='')({{$softtissuelessonData->fldteeth}})@endif
                                        {{ $softtissuelessonData->fldvalue }}
                                    </td>
                                </tr>
                            @endif
                            @if(isset($smoker) and $smoker->fldvalue !='')
                                <tr>
                                    <td style="border: 1px solid; padding:5px; width: 50%">Smoker</td>
                                    <td style="border: 1px solid; padding:5px;">
                                        @if($smoker->fldteeth !='')({{$smoker->fldteeth}})@endif
                                        {{ $smoker->fldvalue }}
                                    </td>
                                </tr>
                            @endif
                            @if(isset($periodentalData) and $periodentalData->fldvalue !='')
                                <tr>
                                    <td style="border: 1px solid; padding:5px; width: 50%">Periodontal Diseases</td>
                                    <td style="border: 1px solid; padding:5px;">
                                        @if($periodentalData->fldteeth !='')({{$periodentalData->fldteeth}})@endif
                                        {{ $periodentalData->fldvalue }}
                                    </td>
                                </tr>
                            @endif
                            @if(isset($gingivalData) and $gingivalData->fldvalue !='')
                                <tr>
                                    <td style="border: 1px solid; padding:5px; width: 50%">Genigival Recession</td>
                                    <td style="border: 1px solid; padding:5px;">
                                        @if($periodentalData->fldteeth !='')({{$periodentalData->fldteeth}})@endif
                                        {{ $periodentalData->fldvalue }}
                                    </td>
                                </tr>
                            @endif

                            </tbody>
                        </table>

                    </div>
                @endif
            </div>
            <br>
            <div class="row" style="margin-top: 7px;">
                <div class="table-dental2">
                    <table style="border: 1px solid;border-collapse: collapse; font-size: 16px; width: 95%;margin: 0 auto;">
                        <thead>
                        <tr>
                            <th colspan="2" style="border: 1px solid; padding:5px; width: 42%;">Dental Restoration</th>
                            <th colspan="2" style="border: 1px solid; padding:5px; width: 42%;"> Dental Anomolies</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Crowns</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($crownData) && $crownData->fldvalue !='')
                                    ({{$crownData->fldteeth}}) {{ $gingivalData->fldvalue }}
                                @endif
                            </td>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Hypodontia</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($hypodontiaTeethData) && $hypodontiaTeethData->fldvalue !='')
                                    ({{$hypodontiaTeethData->fldteeth}}) {{ $hypodontiaTeethData->fldvalue }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 25%;">RCTS</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($rctData) && $rctData->fldvalue !='')
                                    ({{$rctData->fldteeth}}) {{ $rctData->fldvalue }}
                                @endif
                            </td>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Supernumerary Teeth</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($sntData) &&  $sntData->fldvalue !='')
                                    ({{$sntData->fldteeth}}) {{ $sntData->fldvalue }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Filling</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($fillingsData) &&  $fillingsData->fldvalue !='')
                                    ({{$fillingsData->fldteeth}}) {{ $fillingsData->fldvalue }}
                                @endif
                            </td>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Small Teeth</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($smallteethData) &&  $smallteethData->fldvalue !='')
                                    ({{$smallteethData->fldteeth}}) {{ $smallteethData->fldvalue }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Tooth Wears</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($toothwearsData) &&  $toothwearsData->fldvalue !='')
                                    ({{$toothwearsData->fldteeth}}) {{ $toothwearsData->fldvalue }}
                                @endif
                            </td>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Malformed Teeth</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($malformedteethData) &&  $malformedteethData->fldvalue !='')
                                    ({{$malformedteethData->fldteeth}}) {{ $malformedteethData->fldvalue }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Extraction</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($extractionData) &&  $extractionData->fldvalue !='')
                                    ({{$extractionData->fldteeth}}) {{ $extractionData->fldvalue }}
                                @endif
                            </td>
                            <td rowspan="2" style="border: 1px solid; padding:5px; width: 25%;">Short/Abnormal Roots
                            </td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($sarData) &&  $sarData->fldvalue !='')
                                    ({{$sarData->fldteeth}}) {{ $sarData->fldvalue }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 25%;">Impacted Teeth</td>
                            <td style="border: 1px solid; padding:5px; width: 25%;">
                                @if(isset($impactTeethData) &&  $impactTeethData->fldvalue !='')
                                    ({{$impactTeethData->fldteeth}}) {{ $impactTeethData->fldvalue }}
                                @endif
                            </td>
                            <td style="border: 1px solid; padding:5px;">

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="table-history" style="margin-top: 25px;">
                    <table style="border: 1px solid;border-collapse: collapse; font-size: 16px; width: 95%;margin: 0 auto;">
                        <tbody>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 40%;">Medical History</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($otherdata['medical_history']))
                                    {!! $otherdata['medical_history'] !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 40%;">Dental History</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($otherdata['dental_history']))
                                    {!! $otherdata['dental_history'] !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid; padding:5px; width: 40%;">Notes</td>
                            <td style="border: 1px solid; padding:5px;">
                                @if(isset($otherdata['dental_notes']))
                                    {!! $otherdata['dental_notes'] !!}
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="table-eading">
                    <h3 style="text-align: center;">Finding:</h3>
                </div>
                <div class="table-boderless">
                    <table style="border: none; margin: 0 auto; width: 95%; margin-top: -23px;">
                        <tbody>
                        <tr>
                            <td style="height: 40px;">
                                Orthodontic Findings:
                                @if(isset($orthodata) and count($orthodata) > 0)
                                    @foreach($orthodata as $data)
                                        <p><b>{{$data->fldinput}} :</b> @if($data->fldteeth !='')({{$data->fldteeth}})@endif {{$data->fldvalue}}</p>

                                    @endforeach
                                    <br/>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="height: 40px;">
                                Cephalometric Findings:
                                @if(isset($cephalometricData) and count($cephalometricData) > 0)
                                    @foreach($cephalometricData as $data)
                                        <p><b>{{$data->fldinput}} :</b> @if($data->fldteeth !='')({{$data->fldteeth}})@endif {{$data->fldvalue}}</p>

                                    @endforeach
                                    <br/>
                                @endif
                            </td>
                            <td>Advice:
                                @if(isset($otherdata['dental_advice']))
                                    {!! $otherdata['dental_advice'] !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px;">
                                Extra Laboratory:
                                @if(isset($otherdata['dental_extra_laboratory']))
                                    {!! $otherdata['dental_extra_laboratory'] !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px;">
                                Procedures:
                                @if(isset($otherdata['dental_procedures']))
                                    {!! $otherdata['dental_procedures'] !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px;">
                                Allergy:
                                @if(isset($patdrug) and count($patdrug) > 0)
                                    <ul>
                                        @foreach($patdrug as $pd)
                                            <li>{{$pd->fldcode}}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px;">
                                Diagnosis:
                                @if(isset($patdiago) and count($patdiago) > 0)
                                    <ul>
                                        @foreach($patdiago as $pg)
                                            <li>{{$pg->fldcode}}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px;">Follow up: {{$encounterData->fldfollowdate}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- <div class="signaturediv" style="width: 20%; float: right; margin-top: 2%;">
            <img src="">
            <label>Dr. XYZ</label><br>
            <label style="font-weight: bold;">Dental Surgeon</label><br>
            <label style="font-weight: bold;">NMC:123456</label><br>
            <label>Signature</label>
        </div> -->
    </div>]
    @php
        $signatures = Helpers::getSignature('opdsheet-dental');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
