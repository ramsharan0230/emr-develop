
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cogent Health</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/kit.fontawesome.js')}}" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #525659;
            font-family: 'Roboto', sans-serif;
        }

        .wrapper {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
        }

        table td{
            border: 1px solid #000;
            border-right: none;
            border-bottom: none;
            padding: 2px;
        }

        .page-break {
            page-break-after: always !important;
        }

        i.fa.fa-circle {
            font-size: 6px;
            vertical-align: middle;
        }

        .color_respiratory_rate{
            color: #000000;
        }

        .color_pulse_rate{
            color: #ff1100;
        }

        .color_saturation{
            color: #3F51B5;
        }

        .color_temperature{
            color: #000000;
        }

        .color_systolic_bp{
            /*color: #672ea0;*/
        }

        .color_diastolic_bp{
            /*color: #2d0808;*/
        }
    </style>
</head>

<body>
<!-- First page -->
<div class="wrapper">

    <table width="1180" style="margin-top: 10px; font-size: 13px;">
        <tr>
            <td style="border: 2px solid #000;" width="450">
                <table style="margin-bottom: 10px; margin-top: 10px;">
                    <tr>
                        <td style="border: none">Rank & Name: </td>
                        <td style="border: none">
                            <strong>
                                {!! (isset($patient_info)) ? $patient_info->fldrank : null !!} - {!! (isset($patient_info)) ? $patient_info->getFullNameAttribute() :  null !!}
                            </strong>
                        </td>
                    </tr>
                </table>
                <table style="margin-bottom: 10px;">
                    <tr>
                        <td style="border: none">P. No: </td>
                        <td style="border: none"><strong>{!! (isset($patient_info)) ? $patient_info->fldptcode : null !!}</strong></td>
                    </tr>
                </table>
                <table style="margin-bottom: 10px;">
                    <tr>
                        <td style="border: none">Bed No.:</td>
                        <td style="border: none"><strong>{!! ( isset($encounter) && $encounter->fldcurrlocat) ? $encounter->fldcurrlocat : null !!} </strong></td>
                    </tr>
                </table>
                <table style="margin-bottom: 10px;">
                    <tr>
                        <td style="border: none">Body Weight:</td>
                        <td style="border: none"><strong>{!! ( isset($body_weight) && $body_weight) ? $body_weight : null !!}</strong></td>
                    </tr>
                </table>
            </td>
            <td style="border: none; text-align: center;">
                <table width="500">
                    <tr>
                        <td style="border: none; font-size: 24px; font-weight: 700;">SHREE BIRENDRA HOSPITAL</td>
                    </tr>
                    <tr>
                        <td style="border: none; font-size: 20px; font-weight: 700;">Neurosurgical ICU Chart</td>
                    </tr>
                </table>
            </td>
            <td style="border: 2px solid #000;" width="380">
                <table style="margin-bottom: 10px; margin-top: 10px;">
                    <tr>
                        {{-- @forelse($diagnosis as $diag)
                         <td style="border: none">Diagnosis:</td>
                         <td style="border: none" width="150">&nbsp;{{ $diag->fldtype ?? null }}</td>
                             @empty
                             <td style="border: none">Diagnosis:</td>
                             <td style="border: none" width="150">&nbsp;</td>
                         @endforelse--}}
                    </tr>
                </table>
                <table style="margin-bottom: 10px;">
                    <tr>
                        <td style="border: none">NeuroICU Admission Day:</td>
                        <td style="border: none"><strong>{!! ( isset($encounter) && $encounter->flddod) ?  \Carbon\Carbon::parse($encounter->flddod)->format('Y-m-d') : null !!}</strong></td>
                    </tr>
                </table>
                <table style="margin-bottom: 10px;">
                    <tr>
                        <td style="border: none">Post Op Day:</td>
                        <td style="border: none">&nbsp;</td>
                    </tr>
                </table>
                <table style="margin-bottom: 10px;">
                    <tr>
                        <td style="border: none">Mechanical Ventilation Day:</td>
                        <td style="border: none">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tbody>

        <!-- TIME KEY LABELS -->
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Date: <strong>{{ $report_date ?? null }}</strong></td>
            <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['time_key_label'] !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" rowspan="26" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
        </tr>

        <!-- GCS DATA STARTS -->
        <tr>
            <td rowspan="16" align="center" valign="middle" bgcolor="#FFFFFF" style="writing-mode: vertical-rl; border-bottom: 1px solid #000;">GLASGOW COMA SCALE</td>
            <td rowspan="4" bgcolor="#FFFFFF">Eye Opening</td>
            <td colspan="2" bgcolor="#FFFFFF">Spontaneous</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_eye_spontaneous'] ? $neuro_report['gcs_eye_spontaneous'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">To Speech</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_eye_to_speech'] ? $neuro_report['gcs_eye_to_speech'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">To Pain</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_eye_to_pain'] ? $neuro_report['gcs_eye_to_pain'] : "" }} </strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">None</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_eye_none'] ? $neuro_report['gcs_eye_none'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td rowspan="6" bgcolor="#FFFFFF">Verbal Response</td>
            <td colspan="2" bgcolor="#FFFFFF">Oriented</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_verbal_oriented'] ? $neuro_report['gcs_verbal_oriented'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Confused</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_verbal_confused'] ? $neuro_report['gcs_verbal_confused'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Words</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_verbal_words'] ? $neuro_report['gcs_verbal_words'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Sounds</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_verbal_sounds'] ? $neuro_report['gcs_verbal_sounds'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>

        <tr>
            <td colspan="2" bgcolor="#FFFFFF">T</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_verbal_t'] ?  $neuro_report['gcs_verbal_t'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>

        <tr>
            <td colspan="2" bgcolor="#FFFFFF">None</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_verbal_none'] ?  1 : "" }}</strong></td>
                @endforeach
            @endif
        </tr>

        <tr>
            <td rowspan="6" bgcolor="#FFFFFF" style="border-bottom: 1px solid #000;">Best Motor<br/> Response</td>
            <td colspan="2" bgcolor="#FFFFFF">Obeys Command</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_motor_obeys'] ? $neuro_report['gcs_motor_obeys'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Localizing</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_motor_localizing'] ?  $neuro_report['gcs_motor_localizing'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Normal Flexion</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_motor_flexion'] ? $neuro_report['gcs_motor_flexion'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Abnormal Flexion</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_motor_abnormal'] ? $neuro_report['gcs_motor_abnormal'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Extension</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_motor_extension'] ? $neuro_report['gcs_motor_extension'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">None</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{{ $neuro_report['gcs_motor_none'] ? $neuro_report['gcs_motor_none'] : "" }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF" style="border: none;">&nbsp;</td>
            <td colspan="2" bgcolor="#FFFFFF">TOTAL</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['gcs_total'] ? $neuro_report['gcs_total'] : "" !!} {{ $neuro_report['gcs_verbal_t'] ? 'T':'' }}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF" style="border: none;">&nbsp;</td>
            <td colspan="2" bgcolor="#FFFFFF">PASS</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
        <!-- GCS DATA ENDS -->

        <!-- PUPILS DATA STARTS -->
        <tr>
            <td rowspan="4" bgcolor="#FFFFFF" style="writing-mode: vertical-rl;">PUPILS</td>
            <td rowspan="2" bgcolor="#FFFFFF">Right</td>

            <td colspan="2" bgcolor="#FFFFFF">Size</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['pupil_right_size'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Reaction</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! config('constants.pupils_reaction.' . $neuro_report['pupil_right_reaction']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td rowspan="2" bgcolor="#FFFFFF">Left</td>
            <td colspan="2" bgcolor="#FFFFFF">Size</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['pupil_left_size'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Reaction</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! config('constants.pupils_reaction.' . $neuro_report['pupil_left_reaction']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <!-- PUPILS DATA ENDS -->

        <!-- MUSCLE POWER STARTS -->
        <tr>
            <td rowspan="4" align="center" bgcolor="#FFFFFF" style="writing-mode: vertical-rl;">MUSCLE<br/>POWER</td>
            <td rowspan="2" bgcolor="#FFFFFF">Upper Limbs</td>
            <td colspan="2" bgcolor="#FFFFFF">Right</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['right_Upper_Limbs'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Left</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['left_Upper_Limbs'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td rowspan="2" bgcolor="#FFFFFF">Lower Limbs</td>
            <td colspan="2" bgcolor="#FFFFFF">Right</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['right_lower_Limbs'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Left</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['left_lower_Limbs'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <!-- MUSCLE POWER STARTS -->

        <!-- VITALS PLOT-->
        @include('neuro::report.vitals_graph')

        <!-- MAP, CVP, ECTO2 -->
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">MAP</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">
                        <strong>{!! $neuro_report['map'] ?? "" !!}</strong>
                    </td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">CVP</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['cvp'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">ETCO2</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['etco'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <!-- MUSCLE POWER STARTS -->

        <tr>
            <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="4" align="center" bgcolor="#FFFFFF">KEY ABG PARAMETTERS</td>
            <td colspan="2" align="center" bgcolor="#FFFFFF">pH</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['ph'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">pO2</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['po'] ?? "" !!}</strong></td>
                @endforeach
            @endif

        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">pCO2</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['pco'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">HCO3</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['hco'] ?? "" !!}</strong></td>
                @endforeach
            @endif

        </tr>

        <!-- Ventalator plotting starts from  here -->
        <tr>
            <td colspan="2" rowspan="7" align="center" bgcolor="#FFFFFF">VENTILATOR PARAMETERS</td>
            <td colspan="2" align="center" bgcolor="#FFFFFF">Mode</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! ( ($neuro_report['mode'] && $neuro_report['mode']=='other') ?  $neuro_report['remarks'] : $neuro_report['mode']) ?? ''  !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">FiO2</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['fio2'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">PEEP</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['peep'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">Pressure Support</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['pressure_support'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">Tidal Volume (s)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['tidal_volume'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">Minute Volume</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['minute_volume'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#FFFFFF">IE</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}"><strong>{!! $neuro_report['ie'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <!-- Ventalator plotting ends here -->

        <!-- Intake plotting starts here -->
        <tr>
            <td colspan="2" rowspan="7" align="center" bgcolor="#FFFFFF">INTAKE<br/>(mL)</td>
            <td colspan="2" bgcolor="#FFFFFF">IV Fluid</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['intake_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">IV Drugs</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['iv_drug'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">3% NaCl</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['nacl'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Mannitel</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['manintel'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Enteral</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['enteral'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Total</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['total_intake'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="5" align="center" bgcolor="#FFFFFF">OUTPUT<br/>(mL)</td>
            <td colspan="2" bgcolor="#FFFFFF">Urine</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['urine'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">EVD</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['evd'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Drain/Suction</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drain'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['extra'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Total</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['total'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" bgcolor="#FFFFFF" style="border-left: none;">&nbsp;</td>
            <td colspan="2" bgcolor="#FFFFFF">GRBS</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['grbs'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Regular Insufin</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['regular_insulin'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="8" align="center" bgcolor="#FFFFFF">DRUGS</td>
            <td colspan="2" bgcolor="#FFFFFF">Labetaical</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Nertzoglycerine</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF"  style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Noradrenarine</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Vacuronium</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Madourolam</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000;">Total:</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#FFFFFF">Fentanyl</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['drug_value'] ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Total: <strong></strong></td>
        </tr>

        <!-- FIRST PAGE BOTTOM TIME LABELS -->
        <tr>
            <td colspan="4" bgcolor="#FFFFFF" style="border-left: none;">&nbsp;</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="border-bottom: 1px solid #000;{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['time_key_label'] !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" bgcolor="#FFFFFF" style="border: none;">&nbsp;</td>
        </tr>

        </tbody>
    </table>

</div>
<!-- First Page Ends here -->

<!--Second Page Starts from here -->
<div class="wrapper">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tbody>

        <tr>
            <td colspan="2" style="border: none;">&nbsp;</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['time_key_label'] !!}</strong></td>
                @endforeach
            @endif
        </tr>

        <tr>
            <td rowspan="3" align="center">CHEST</td>
            <td>Air Entry</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! config('constants.air_entry.' . $neuro_report['air_entry']) ?? "" !!}</strong></td>
                @endforeach
            @endif
            <td colspan="2" rowspan="26" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
        </tr>
        <tr>
            <td>Wheeze</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! config('constants.wheeze.' . $neuro_report['wheeze']) ?? "" !!}</strong></td>
                @endforeach
            @endif

        </tr>
        <tr>
            <td>Crackles</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! config('constants.crackles.' . $neuro_report['crackles']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td rowspan="18" align="center" valign="middle" style="writing-mode: vertical-rl;">VAP PROPHYLAXIS</td>
            <td>Interruption of Sefation/<br/>Spontanteous Awakening<br/>Trial (SAT)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['sat'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Spontaneous breathing<br/>trial (SBT)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['sbt'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Assessment of<br/>Readiness to Extubate</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['are'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Elevation of Head of Bed<br/>to 30-45 degrees</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['ehb']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Ventillatory Circuit Check<br/>(Solling/Malfunction)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['vcc']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>ET Suction</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['et_suction']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Sadne Instillation before<br/>suctioning</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['sib']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Sedation</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['sedation']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Oral/Digestive<br/>Decontamination</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['oral_digestive']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Oral Care with<br/>Chlorheridine</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['oral_care']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Prophytactic Probiotics</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['prophylactic_probiotics']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Stress Ulcer Prophylaxis</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['stress_ulcer_prophylaxis']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>ET Cuff Pressure</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! $neuro_report['et_cuff_pressure'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>ET Length</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!!  $neuro_report['et_length'] ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Nebulization (NS)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['nebulization']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Nebulization (A:I:NS)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['nebulization_ains']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Nebulization (NAC)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['nebulization_nac']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Nebulization (Flohale)</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong> {!! config("constants.vaps." . $neuro_report['nebulization_flohale']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td rowspan="4" align="center">EYE &amp; SKIN CARE</td>
            <td>Eye</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['eye_e'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Position Change</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! config('constants.position.' . $neuro_report['eye_position']) ?? "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Back</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['eye_b'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Pressure Point</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['eye_pp'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td rowspan="6" align="center">LINES &amp; WOUND CARE</td>
            <td>Foley's</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center"><strong>{!! $neuro_report['lines_foley'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>CVP</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['lines_cvp'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Tracheostomy</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['lines_tracheostomy'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Wound</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['lines_wound'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>EVD</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['lines_evd'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-left: none;">&nbsp;</td>
            <td>Thromboembolic<br/>Prophylaxis</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['lines_thromboembolic_prophylaxis'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td rowspan="3" align="center">PHYSICAL THERAPY</td>
            <td>Chest Physiotherapy</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['chest_physiotherapy'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Limb Physiotherapy</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['limb_physiotherapy'] ??  "" !!}</strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td>Ambulation</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!!  config("constants.ambulation." . $neuro_report['ambulation']) ?? "" !!} </strong></td>
                @endforeach
            @endif
        </tr>
        <tr>
            <td colspan="2" style="border-left: none;">&nbsp;</td>
            @if( count($neuro_reports) > 0 )
                @foreach( $neuro_reports as $neuro_report )
                    <td align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : ""!!}"><strong>{!! $neuro_report['time_key_label'] !!}</strong></td>
                @endforeach
            @endif
        </tr>



        <tr>
            <td style="border-left: none; border-top: none;">&nbsp;</td>
            <td rowspan="2" align="center">INVESTIGATIONS<br/>TRACKER</td>
            <td colspan="3" align="center">Tracheal C/Sthis</td>
            <td colspan="3" align="center">Blood C/S</td>
            <td colspan="3" align="center">Urine C/S</td>
            <td colspan="3" align="center">CFS C/S</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="3" align="center">&nbsp;</td>
            <td colspan="3" align="center">&nbsp;</td>
            <td colspan="3" align="center" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-left: none; border-top: none;">&nbsp;</td>
            <td>Day</td>
            <td colspan="2">&nbsp;</td>
            <td>Day</td>
            <td colspan="2">&nbsp;</td>
            <td>Day</td>
            <td colspan="2">&nbsp;</td>
            <td>Day</td>
            <td colspan="2">&nbsp;</td>
            <td>Day</td>
            <td colspan="2">&nbsp;</td>
            <td>Day</td>
            <td colspan="2">&nbsp;</td>
            <td>Day</td>
            <td colspan="2">&nbsp;</td>
            <td>Day</td>
            <td colspan="2" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="26" align="center">DUTY NURSE'S NOTES/REMARKS
            </td>
        </tr>

        {{--            <td colspan="6" align="center">DUTY NURSE'S NOTES/REMARKS</td>--}}
        {{--            <td colspan="10" align="center">DUTY NURSE'S NOTES/REMARKS</td>--}}
        {{--            <td colspan="10" align="center" style="border-right: 1px solid #000;">DUTY NORSE'S NOTE/REMARKS</td>--}}
        </tr>
        <tr>
        @if( count($neuro_reports) > 0 )
            <tr>
                <td colspan="26">
                    <ul type="disc">
                        @foreach( $neuro_reports as $neuro_report )
                            @if($neuro_report['nurses_notes'])
                                <li>
                                    {{ $neuro_report['nurses_notes'] ??  "" }} ( at  {{ $neuro_report['time_key_label'] ??  "" }}   by   {{ $username->fullname ?? null }} )
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endif
            {{--            <td colspan="6" style="height: 150px;">&nbsp;</td>--}}
            {{--            <td colspan="10">&nbsp;</td>--}}
            {{--            <td colspan="10" style="border-right: 1px solid #000;">&nbsp;</td>--}}
            </tr>
            <tr>
                <td colspan="6" align="center">NUTRITIONST'S NOTE</td>
                <td colspan="10" align="center">PHYSICAL THERAPIST'S NOTE</td>
                <td colspan="10" align="center" style="border-right: 1px solid #000;">ADDITIONAL NOTE</td>
            </tr>
            <tr>
                <td colspan="6">
                    @if( count($neuro_reports) > 0 )
                        <ul type="disc">
                            @foreach( $neuro_reports as $neuro_report )
                                @if($neuro_report['nutritionists_notes'])
                                    <li>
                                        {{ $neuro_report['nutritionists_notes'] ??  "" }} ( at  {{ $neuro_report['time_key_label'] ??  "" }}   by   {{ $username->fullname ?? null }} )

                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td colspan="10">
                    @if( count($neuro_reports) > 0 )
                        <ul type="disc">
                            @foreach( $neuro_reports as $neuro_report )
                                @if($neuro_report['physical_therapist_notes'])
                                    <li>
                                        {{ $neuro_report['physical_therapist_notes'] ??  "" }} ( at  {{ $neuro_report['time_key_label'] ??  "" }}   by   {{ $username->fullname ?? null }} )

                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td colspan="10" style="border-right: 1px solid #000;">
                    @if( count($neuro_reports) > 0 )
                        <ul type="disc">
                            @foreach( $neuro_reports as $neuro_report )
                                @if($neuro_report['additional_note'])
                                    <li>
                                        {{ $neuro_report['additional_note'] ??  "" }} ( at  {{ $neuro_report['time_key_label'] ??  "" }}   by   {{ $username->fullname ?? null }} )

                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="13" align="center">MEDICAL OFFICER/RESIDENT'S NOTES</td>
                <td colspan="13" align="center" style="border-right: 1px solid #000;">ATTENDING NEUROSURGEON'S NOTES</td>
            </tr>
            <tr>
                <td colspan="13" rowspan="3" style="border-bottom: 1px solid #000;">&nbsp;
                    @if( count($neuro_reports) > 0 )
                        <ul type="disc">
                            @foreach( $neuro_reports as $neuro_report )
                                @if($neuro_report['medical_officer_notes'])
                                    <li>
                                     {{ $neuro_report['medical_officer_notes'] ??  "" }} ( at  {{ $neuro_report['time_key_label'] ??  "" }}   by   {{ $username->fullname ?? null }} )
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                </td>
                <td colspan="13" style="border-right: 1px solid #000; height: 150px;">&nbsp;
                    @if( count($neuro_reports) > 0 )
                        <ul type="disc">
                            @foreach( $neuro_reports as $neuro_report )
                                @if($neuro_report['attending_neurosurgeon_note'])
                                    <li>
                                        {{ $neuro_report['attending_neurosurgeon_note'] ??  "" }} ( at  {{ $neuro_report['time_key_label'] ??  "" }}   by   {{ $username->fullname ?? null }} )

                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="13" align="center">ADDITIONAL CONSULTATION/REVIEW NOTES</td>
            </tr>
            <!-- There is no data in databsase for this -->
            <tr>
                {{--            <td colspan="13" style="border-right: 1px solid #000; height: 150px;">&nbsp;--}}
                {{--                @if( count($neuro_reports) > 0 )--}}
                {{--                    <ul type="disc">--}}
                {{--                        @foreach( $neuro_reports as $neuro_report )--}}
                {{--                            @if($neuro_report['attending_neurosurgeon_note'])--}}
                {{--                                <li>--}}
                {{--                                    {{ $neuro_report['attending_neurosurgeon_note'] ??  "" }} ( at  {{ $neuro_report['time_key_label'] ??  "" }}   by   {{ $username->fullname ?? null }} )--}}

                {{--                                </li>--}}
                {{--                            @endif--}}
                {{--                        @endforeach--}}
                {{--                    </ul>--}}
                {{--                @endif--}}
                {{--            </td>--}}
            </tr>
        </tbody>
    </table>


</div>
<!-- Second page ends here-->

</body>
</html>
