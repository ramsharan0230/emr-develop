@php
    $sum = 0;
    $refund =0;
    $final = 0;
    $refund_sum =0;
    $final_sum = 0;
@endphp


<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b>
        </th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>From date:</b></th>
        <th colspan="2">{{ $finalfrom }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>To date:</b></th>
        <th colspan="2">{{ $finalto }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Status:</b></th>
        <th colspan="2">{{ $last_status }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Deposit:</b></th>
        <th colspan="2">{{ \App\Utils\Helpers::numberFormat($deposit) }}</th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th>Patient ID</th>
        <th>Inpatient ID</th>
        <th>Patient Name</th>
        <th>Deposit Type</th>
        <th>Deposit No</th>
        <th>Deposit Date</th>
        <th>Deposit Collection</th>
        <th>Deposit Refund</th>
        <th>Final Bill AMT</th>
    </tr>


    </thead>
    <tbody>
    @forelse($depositData as $deposit)

        @if(isset($deposit->patbilldetails))
            @foreach($deposit->patbilldetails as $patbill)
            @php
            $types = '';
            $billtype = $patbill->fldbillno;
            $depostitype = explode('-',$billtype);
            $types = $depostitype[0];
            @endphp
            @if($types == 'DEP' || $types == 'DEPRT')
                <tr>
                    <td align="center"> {{  $deposit->patientInfo->fldpatientval ?? null}} </td>
                    <td align="center"> {{ $deposit->fldencounterval ?? null}} </td>
                    <td align="center"> {{ ($deposit->patientInfo->fldptnamefir ?? null)}} {{($deposit->patientInfo->fldmidname ?? null)}} {{ ($deposit->patientInfo->fldptnamelast ?? null) }} </td>
                    <td align="center"> {{ $patbill->fldbilltype ?? null }} </td>
                    <td align="center"> {{ $patbill->fldbillno ?? null }} </td>
                    <td align="center"> {{ $patbill->fldtime ?? null }} </td>
                    <td align="center"> {{ $patbill->fldreceivedamt ?  \App\Utils\Helpers::numberFormat(($patbill->fldreceivedamt)) : 0 }} </td>
                    @php
                        $amount =$patbill->fldreceivedamt;
                        $sum = ($sum+$amount);
                        $refund = ($patbill->fldreceivedamt - $patbill->fldprevdeposit);
                        $final =  ($amount - $refund);
                        $refund_sum =( $refund +$refund_sum);
                        $final_sum =($final+$final_sum);
                    @endphp
                    <td align="center"> {{ \App\Utils\Helpers::numberFormat($refund) ?? null }} </td>
                    <td align="center"> {{ \App\Utils\Helpers::numberFormat($final) ?? null }} </td>
                </tr>
                @endif
            @endforeach
        @endif

    @empty
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforelse

    <tr>
        <td align="center">&nbsp;<strong>Total</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center">&nbsp;<strong>{{ 'Rs.'.\App\Utils\Helpers::numberFormat($sum) }} </strong></td>
        <td align="center">&nbsp;<strong>{{ 'Rs.'.\App\Utils\Helpers::numberFormat($refund_sum) }} </strong></td>
        <td align="center">&nbsp;<strong>{{ 'Rs.'.\App\Utils\Helpers::numberFormat($final_sum) }} </strong></td>
    </tr>



    </tbody>
</table>
