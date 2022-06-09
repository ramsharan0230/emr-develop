<tr>
    <td bgcolor="#FFFFFF">TUBE(S) IN-S/TU</td>
    <td bgcolor="#FFFFFF">DAY(S)</td>
    <td rowspan="28" bgcolor="#FFFFFF" class="color_systolic_bp" style="writing-mode: vertical-rl;padding-top: 415px;">BLOOD PRESSURE</td>
    <td rowspan="1" bgcolor="#FFFFFF">240</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '235' && $neuro_report['respiratory_rate'] <= '240' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '235' && $neuro_report['pulse_rate'] <= '240' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '235' && $neuro_report['saturation'] <= '240' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '235' && $neuro_report['temperature'] <= '240' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '235' && $neuro_report['systolic_bp'] <= '240' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '0' && $neuro_report['diastolic_bp'] <= '5' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;">&nbsp;</td>
    <td class="color_temperature" rowspan="28" bgcolor="#FFFFFF" style="writing-mode: vertical-rl; border-right: 1px solid #000;vertical-align: bottom">TEMPERATURE</td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">Foley's/SPC</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">235</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '230' && $neuro_report['respiratory_rate'] <= '235' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '230' && $neuro_report['pulse_rate'] <= '235' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '230' && $neuro_report['saturation'] <= '235' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '230' && $neuro_report['temperature'] <= '235' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '230' && $neuro_report['systolic_bp'] <= '235' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '230' && $neuro_report['diastolic_bp'] <= '235' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td bgcolor="#FFFFFF">Endotrached</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="1" bgcolor="#FFFFFF">230</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '225' && $neuro_report['respiratory_rate'] <= '230' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '225' && $neuro_report['pulse_rate'] <= '230' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '225' && $neuro_report['saturation'] <= '230' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '225' && $neuro_report['temperature'] <= '230' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '225' && $neuro_report['systolic_bp'] <= '230' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '225' && $neuro_report['diastolic_bp'] <= '230' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">Tracheostomy</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">225</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '220' && $neuro_report['respiratory_rate'] <= '225' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '220' && $neuro_report['pulse_rate'] <= '225' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '220' && $neuro_report['saturation'] <= '225' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '220' && $neuro_report['temperature'] <= '225' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '220' && $neuro_report['systolic_bp'] <= '225' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '220' && $neuro_report['diastolic_bp'] <= '225' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td bgcolor="#FFFFFF">CVP</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="1" bgcolor="#FFFFFF">220</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '215' && $neuro_report['respiratory_rate'] <= '220' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '215' && $neuro_report['pulse_rate'] <= '220' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '215' && $neuro_report['saturation'] <= '220' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '215' && $neuro_report['temperature'] <= '220' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '215' && $neuro_report['systolic_bp'] <= '220' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '215' && $neuro_report['diastolic_bp'] <= '220' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">Arterial</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">215</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '210' && $neuro_report['respiratory_rate'] <= '215' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '210' && $neuro_report['pulse_rate'] <= '215' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '210' && $neuro_report['saturation'] <= '215' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '210' && $neuro_report['temperature'] <= '215' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '210' && $neuro_report['systolic_bp'] <= '215' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '210' && $neuro_report['diastolic_bp'] <= '215' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td bgcolor="#FFFFFF">Peripherial</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="1" bgcolor="#FFFFFF">210</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '205' && $neuro_report['respiratory_rate'] <= '210' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '205' && $neuro_report['pulse_rate'] <= '210' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '205' && $neuro_report['saturation'] <= '210' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '205' && $neuro_report['temperature'] <= '210' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '205' && $neuro_report['systolic_bp'] <= '210' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '205' && $neuro_report['diastolic_bp'] <= '210' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">Masso/Oeo-ahstric</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">205</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '200' && $neuro_report['respiratory_rate'] <= '205' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '200' && $neuro_report['pulse_rate'] <= '205' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '200' && $neuro_report['saturation'] <= '205' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '200' && $neuro_report['temperature'] <= '205' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '200' && $neuro_report['systolic_bp'] <= '205' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '200' && $neuro_report['diastolic_bp'] <= '205' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td bgcolor="#FFFFFF">Enberostomy</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="1" bgcolor="#FFFFFF">200</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '195' && $neuro_report['respiratory_rate'] <= '200' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '195' && $neuro_report['pulse_rate'] <= '200' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '195' && $neuro_report['saturation'] <= '200' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '195' && $neuro_report['temperature'] <= '200' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '195' && $neuro_report['systolic_bp'] <= '200' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '195' && $neuro_report['diastolic_bp'] <= '200' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">Epidurnal</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">195</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '190' && $neuro_report['respiratory_rate'] <= '195' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '190' && $neuro_report['pulse_rate'] <= '195' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '190' && $neuro_report['saturation'] <= '195' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '190' && $neuro_report['temperature'] <= '195' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '190' && $neuro_report['systolic_bp'] <= '195' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '190' && $neuro_report['diastolic_bp'] <= '195' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td bgcolor="#FFFFFF">Dialysis</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="1" bgcolor="#FFFFFF">190</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '185' && $neuro_report['respiratory_rate'] <= '190' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '185' && $neuro_report['pulse_rate'] <= '190' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '185' && $neuro_report['saturation'] <= '190' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '185' && $neuro_report['temperature'] <= '190' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '185' && $neuro_report['systolic_bp'] <= '190' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '185' && $neuro_report['diastolic_bp'] <= '190' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">185</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '180' && $neuro_report['respiratory_rate'] <= '185' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '180' && $neuro_report['pulse_rate'] <= '185' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '180' && $neuro_report['saturation'] <= '185' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '180' && $neuro_report['temperature'] <= '185' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '180' && $neuro_report['systolic_bp'] <= '185' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '180' && $neuro_report['diastolic_bp'] <= '185' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td bgcolor="#FFFFFF" style="border-bottom: 1px solid #000">&nbsp;</td>
    <td bgcolor="#FFFFFF" style="border-bottom: 1px solid #000">&nbsp;</td>
    <td rowspan="1" bgcolor="#FFFFFF">180</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '175' && $neuro_report['respiratory_rate'] <= '180' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '175' && $neuro_report['pulse_rate'] <= '180' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '175' && $neuro_report['saturation'] <= '180' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '175' && $neuro_report['temperature'] <= '180' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '175' && $neuro_report['systolic_bp'] <= '180' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '175' && $neuro_report['diastolic_bp'] <= '180' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border: none;">&nbsp;</td>
    <td bgcolor="#FFFFFF">175</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '170' && $neuro_report['respiratory_rate'] <= '175' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '170' && $neuro_report['pulse_rate'] <= '175' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '170' && $neuro_report['saturation'] <= '175' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '170' && $neuro_report['temperature'] <= '175' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '170' && $neuro_report['systolic_bp'] <= '175' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '170' && $neuro_report['diastolic_bp'] <= '175' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF">TOTAL 24 HRS INTAKE: {{ $total_intake ??  null }}</td>
    <td rowspan="1" bgcolor="#FFFFFF">170</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '165' && $neuro_report['respiratory_rate'] <= '170' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '165' && $neuro_report['pulse_rate'] <= '170' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '165' && $neuro_report['saturation'] <= '170' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '165' && $neuro_report['temperature'] <= '170' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '165' && $neuro_report['systolic_bp'] <= '170' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '165' && $neuro_report['diastolic_bp'] <= '170' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
    <td bgcolor="#FFFFFF" >165</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '160' && $neuro_report['respiratory_rate'] <= '165' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '160' && $neuro_report['pulse_rate'] <= '165' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '160' && $neuro_report['saturation'] <= '165' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '160' && $neuro_report['temperature'] <= '165' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '160' && $neuro_report['systolic_bp'] <= '165' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '160' && $neuro_report['diastolic_bp'] <= '165' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">TOTAL 24 HRS OUTPUT: {{ $total_output ??  null }}</td>
    <td rowspan="1" bgcolor="#FFFFFF">160</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '155' && $neuro_report['respiratory_rate'] <= '160' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '155' && $neuro_report['pulse_rate'] <= '160' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '155' && $neuro_report['saturation'] <= '160' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '155' && $neuro_report['temperature'] <= '160' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '155' && $neuro_report['systolic_bp'] <= '160' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '155' && $neuro_report['diastolic_bp'] <= '160' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
    <td bgcolor="#FFFFFF">155</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '150' && $neuro_report['respiratory_rate'] <= '155' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '150' && $neuro_report['pulse_rate'] <= '155' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '150' && $neuro_report['saturation'] <= '155' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '150' && $neuro_report['temperature'] <= '155' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '150' && $neuro_report['systolic_bp'] <= '155' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '150' && $neuro_report['diastolic_bp'] <= '155' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">24 HRS BALANCE: {{ $twentifour_hour_balance ?? null }}</td>
    <td rowspan="1" bgcolor="#FFFFFF">150</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '145' && $neuro_report['respiratory_rate'] <= '150' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '145' && $neuro_report['pulse_rate'] <= '150' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '145' && $neuro_report['saturation'] <= '150' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '145' && $neuro_report['temperature'] <= '150' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '145' && $neuro_report['systolic_bp'] <= '150' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '145' && $neuro_report['diastolic_bp'] <= '150' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
    <td bgcolor="#FFFFFF">145</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '140' && $neuro_report['respiratory_rate'] <= '145' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '140' && $neuro_report['pulse_rate'] <= '145' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '140' && $neuro_report['saturation'] <= '145' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '140' && $neuro_report['temperature'] <= '145' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '140' && $neuro_report['systolic_bp'] <= '145' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '140' && $neuro_report['diastolic_bp'] <= '145' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">PREVIOUS DAY'S BALANCE: {{ $previous_days_balance ?? null }}</td>
    <td rowspan="1" bgcolor="#FFFFFF">140</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '135' && $neuro_report['respiratory_rate'] <= '140' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '135' && $neuro_report['pulse_rate'] <= '140' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '135' && $neuro_report['saturation'] <= '140' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '135' && $neuro_report['temperature'] <= '140' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '135' && $neuro_report['systolic_bp'] <= '140' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '135' && $neuro_report['diastolic_bp'] <= '140' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
    <td bgcolor="#FFFFFF">135</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '130' && $neuro_report['respiratory_rate'] <= '135' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '130' && $neuro_report['pulse_rate'] <= '135' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '130' && $neuro_report['saturation'] <= '135' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '130' && $neuro_report['temperature'] <= '135' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '130' && $neuro_report['systolic_bp'] <= '135' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '130' && $neuro_report['diastolic_bp'] <= '135' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">CUMULATIVE BALANCE: {{ $cumulative_balance ?? null }}</td>
    <td rowspan="1" bgcolor="#FFFFFF">130</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '125' && $neuro_report['respiratory_rate'] <= '130' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '125' && $neuro_report['pulse_rate'] <= '130' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '125' && $neuro_report['saturation'] <= '130' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '125' && $neuro_report['temperature'] <= '130' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '125' && $neuro_report['systolic_bp'] <= '130' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '125' && $neuro_report['diastolic_bp'] <= '130' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td colspan="2" bgcolor="#FFFFFF" style="border-top: none;">&nbsp;</td>
    <td bgcolor="#FFFFFF">125</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '120' && $neuro_report['respiratory_rate'] <= '125' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '120' && $neuro_report['pulse_rate'] <= '125' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '120' && $neuro_report['saturation'] <= '125' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '120' && $neuro_report['temperature'] <= '125' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '120' && $neuro_report['systolic_bp'] <= '125' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '120' && $neuro_report['diastolic_bp'] <= '125' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td colspan="2" rowspan="28" bgcolor="#FFFFFF" style="border-left: none;">&nbsp;</td>
    <td rowspan="1" bgcolor="#FFFFFF">120</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '115' && $neuro_report['respiratory_rate'] <= '120' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '115' && $neuro_report['pulse_rate'] <= '120' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '115' && $neuro_report['saturation'] <= '120' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '115' && $neuro_report['temperature'] <= '120' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '115' && $neuro_report['systolic_bp'] <= '120' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '115' && $neuro_report['diastolic_bp'] <= '120' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="4" bgcolor="#FFFFFF" style="border: none;">&nbsp;</td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">115</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '110' && $neuro_report['respiratory_rate'] <= '115' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '110' && $neuro_report['pulse_rate'] <= '115' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '110' && $neuro_report['saturation'] <= '115' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '110' && $neuro_report['temperature'] <= '115' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '110' && $neuro_report['systolic_bp'] <= '115' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '110' && $neuro_report['diastolic_bp'] <= '115' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">110</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '105' && $neuro_report['respiratory_rate'] <= '110' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '105' && $neuro_report['pulse_rate'] <= '110' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '105' && $neuro_report['saturation'] <= '110' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '105' && $neuro_report['temperature'] <= '110' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '105' && $neuro_report['systolic_bp'] <= '110' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '105' && $neuro_report['diastolic_bp'] <= '110' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td bgcolor="#FFFFFF">105</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '100' && $neuro_report['respiratory_rate'] <= '105' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '100' && $neuro_report['pulse_rate'] <= '105' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '100' && $neuro_report['saturation'] <= '105' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '100' && $neuro_report['temperature'] <= '105' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '100' && $neuro_report['systolic_bp'] <= '105' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '100' && $neuro_report['diastolic_bp'] <= '105' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif
</tr>

<tr>
    <td rowspan="10" bgcolor="#FFFFFF" class="color_pulse_rate" style="border-top: none;writing-mode: vertical-rl;vertical-align: top;">PULSE</td>
    <td rowspan="1" bgcolor="#FFFFFF">100</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '95' && $neuro_report['respiratory_rate'] <= '100' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '95' && $neuro_report['pulse_rate'] <= '100' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '95' && $neuro_report['saturation'] <= '100' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '95' && $neuro_report['temperature'] <= '100' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '95' && $neuro_report['systolic_bp'] <= '100' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '95' && $neuro_report['diastolic_bp'] <= '100' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
    <td rowspan="14" class="color_saturation" bgcolor="#FFFFFF" style="writing-mode: vertical-rl; border-right: 1px solid #000; border-top: none;vertical-align: top;">O2 SATURATION</td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">95</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '90' && $neuro_report['respiratory_rate'] <= '95' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '90' && $neuro_report['pulse_rate'] <= '95' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '90' && $neuro_report['saturation'] <= '95' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '90' && $neuro_report['temperature'] <= '95' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '90' && $neuro_report['systolic_bp'] <= '95' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '90' && $neuro_report['diastolic_bp'] <= '95' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">90</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '85' && $neuro_report['respiratory_rate'] <= '90' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '85' && $neuro_report['pulse_rate'] <= '90' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '85' && $neuro_report['saturation'] <= '90' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '85' && $neuro_report['temperature'] <= '90' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '85' && $neuro_report['systolic_bp'] <= '90' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '85' && $neuro_report['diastolic_bp'] <= '90' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">85</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '80' && $neuro_report['respiratory_rate'] <= '85' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '80' && $neuro_report['pulse_rate'] <= '85' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '80' && $neuro_report['saturation'] <= '85' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '80' && $neuro_report['temperature'] <= '85' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '80' && $neuro_report['systolic_bp'] <= '85' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '80' && $neuro_report['diastolic_bp'] <= '85' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">80</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '75' && $neuro_report['respiratory_rate'] <= '80' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '75' && $neuro_report['pulse_rate'] <= '80' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '75' && $neuro_report['saturation'] <= '80' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '75' && $neuro_report['temperature'] <= '80' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '75' && $neuro_report['systolic_bp'] <= '80' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '75' && $neuro_report['diastolic_bp'] <= '80' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">75</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '70' && $neuro_report['respiratory_rate'] <= '75' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '70' && $neuro_report['pulse_rate'] <= '75' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '70' && $neuro_report['saturation'] <= '75' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '70' && $neuro_report['temperature'] <= '75' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '70' && $neuro_report['systolic_bp'] <= '75' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '70' && $neuro_report['diastolic_bp'] <= '75' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">70</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '65' && $neuro_report['respiratory_rate'] <= '70' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '65' && $neuro_report['pulse_rate'] <= '70' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '65' && $neuro_report['saturation'] <= '70' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '65' && $neuro_report['temperature'] <= '70' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '65' && $neuro_report['systolic_bp'] <= '70' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '65' && $neuro_report['diastolic_bp'] <= '70' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">65</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '60' && $neuro_report['respiratory_rate'] <= '65' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '60' && $neuro_report['pulse_rate'] <= '65' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '60' && $neuro_report['saturation'] <= '65' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '60' && $neuro_report['temperature'] <= '65' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '60' && $neuro_report['systolic_bp'] <= '65' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '60' && $neuro_report['diastolic_bp'] <= '65' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">60</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '55' && $neuro_report['respiratory_rate'] <= '60' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '55' && $neuro_report['pulse_rate'] <= '60' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '55' && $neuro_report['saturation'] <= '60' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '55' && $neuro_report['temperature'] <= '60' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '55' && $neuro_report['systolic_bp'] <= '60' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '55' && $neuro_report['diastolic_bp'] <= '60' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">55</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '50' && $neuro_report['respiratory_rate'] <= '55' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '50' && $neuro_report['pulse_rate'] <= '55' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '50' && $neuro_report['saturation'] <= '55' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '50' && $neuro_report['temperature'] <= '55' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '50' && $neuro_report['systolic_bp'] <= '55' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '50' && $neuro_report['diastolic_bp'] <= '55' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="10" class="color_respiratory_rate" bgcolor="#FFFFFF" style="border-top:none;writing-mode: vertical-rl;padding-top: 80px;">RESPIRATORY RATE</td>
    <td rowspan="1" bgcolor="#FFFFFF">50</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '45' && $neuro_report['respiratory_rate'] <= '50' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '45' && $neuro_report['pulse_rate'] <= '50' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '45' && $neuro_report['saturation'] <= '50' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '45' && $neuro_report['temperature'] <= '50' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '45' && $neuro_report['systolic_bp'] <= '50' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '45' && $neuro_report['diastolic_bp'] <= '50' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">45</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '40' && $neuro_report['respiratory_rate'] <= '45' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '40' && $neuro_report['pulse_rate'] <= '45' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '40' && $neuro_report['saturation'] <= '45' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '40' && $neuro_report['temperature'] <= '45' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '40' && $neuro_report['systolic_bp'] <= '45' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '40' && $neuro_report['diastolic_bp'] <= '45' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">40</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '35' && $neuro_report['respiratory_rate'] <= '40' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '35' && $neuro_report['pulse_rate'] <= '40' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '35' && $neuro_report['saturation'] <= '40' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '35' && $neuro_report['temperature'] <= '40' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '35' && $neuro_report['systolic_bp'] <= '40' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '35' && $neuro_report['diastolic_bp'] <= '40' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">35</td>
    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '30' && $neuro_report['respiratory_rate'] <= '35' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '30' && $neuro_report['pulse_rate'] <= '35' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '30' && $neuro_report['saturation'] <= '35' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '30' && $neuro_report['temperature'] <= '35' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '30' && $neuro_report['systolic_bp'] <= '35' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '30' && $neuro_report['diastolic_bp'] <= '35' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">30</td>

    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '25' && $neuro_report['respiratory_rate'] <= '30' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '25' && $neuro_report['pulse_rate'] <= '30' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '25' && $neuro_report['saturation'] <= '30' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '25' && $neuro_report['temperature'] <= '30' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '25' && $neuro_report['systolic_bp'] <= '30' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '25' && $neuro_report['diastolic_bp'] <= '30' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
    <td rowspan="3" bgcolor="#FFFFFF" style="writing-mode: vertical-rl; border-right: 1px solid #000; border-top: none;">BIS</td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">25</td>

    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '20' && $neuro_report['respiratory_rate'] <= '25' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '20' && $neuro_report['pulse_rate'] <= '25' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '20' && $neuro_report['saturation'] <= '25' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '20' && $neuro_report['temperature'] <= '25' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '20' && $neuro_report['systolic_bp'] <= '25' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '20' && $neuro_report['diastolic_bp'] <= '25' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">20</td>

    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '15' && $neuro_report['respiratory_rate'] <= '20' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '15' && $neuro_report['pulse_rate'] <= '20' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '15' && $neuro_report['saturation'] <= '20' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '15' && $neuro_report['temperature'] <= '20' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '15' && $neuro_report['systolic_bp'] <= '20' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '15' && $neuro_report['diastolic_bp'] <= '20' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">15</td>

    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '10' && $neuro_report['respiratory_rate'] <= '15' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '10' && $neuro_report['pulse_rate'] <= '15' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '10' && $neuro_report['saturation'] <= '15' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '10' && $neuro_report['temperature'] <= '15' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '10' && $neuro_report['systolic_bp'] <= '15' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '10' && $neuro_report['diastolic_bp'] <= '15' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td bgcolor="#FFFFFF" style="border-top: none;border-left: none;">&nbsp;</td>
    <td rowspan="3" bgcolor="#FFFFFF" style="writing-mode: vertical-rl; border-bottom: 1px solid #000; border-right: 1px solid #000; border-top: none;">ICP</td>
</tr>

<tr>
    <td rowspan="1" bgcolor="#FFFFFF">10</td>

    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '5' && $neuro_report['respiratory_rate'] <= '10' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '5' && $neuro_report['pulse_rate'] <= '10' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '5' && $neuro_report['saturation'] <= '10' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '5' && $neuro_report['temperature'] <= '10' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '5' && $neuro_report['systolic_bp'] <= '10' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '5' && $neuro_report['diastolic_bp'] <= '10' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">5</td>

    @if( count($neuro_reports) > 0 )
        @foreach( $neuro_reports as $neuro_report )
            <td bgcolor="#FFFFFF" align="center" style="{!! $loop->last ? "border-right: 1px solid #000;" : "" !!}">

                <!-- RESPIRATORY RATE -->
                <strong class="color_respiratory_rate">
                    {!! isset( $neuro_report['respiratory_rate'] ) && $neuro_report['respiratory_rate'] > '0' && $neuro_report['respiratory_rate'] <= '5' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- PULSE RATE -->
                <strong class="color_pulse_rate">
                    {!! isset( $neuro_report['pulse_rate'] ) && $neuro_report['pulse_rate'] > '0' && $neuro_report['pulse_rate'] <= '5' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- SPO2 -->
                <strong class="color_saturation">
                    {!! isset( $neuro_report['saturation'] ) && $neuro_report['saturation'] > '0' && $neuro_report['saturation'] <= '5' ? '<i class="fa fa-times" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- TEMPERATURE -->
                <strong class="color_temperature">
                    {!! isset( $neuro_report['temperature'] ) && $neuro_report['temperature'] > '0' && $neuro_report['temperature'] <= '5' ? '<i class="fa fa-circle" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- systolic_bp -->
                <strong class="color_systolic_bp">
                    {!! isset( $neuro_report['systolic_bp'] ) && $neuro_report['systolic_bp'] > '0' && $neuro_report['systolic_bp'] <= '5' ? '<i class="fa fa-angle-down" aria-hidden="true"></i>' : "" !!}
                </strong>
                <!-- diastolic_bp-->
                <strong class="color_diastolic_bp">
                    {!! isset( $neuro_report['diastolic_bp'] ) && $neuro_report['diastolic_bp'] > '0' && $neuro_report['diastolic_bp'] <= '5' ? '<i class="fa fa-angle-up" aria-hidden="true"></i>' : "" !!}
                </strong>

            </td>
        @endforeach
    @endif

    <td rowspan="1" bgcolor="#FFFFFF" style="border: none;"></td>
</tr>
