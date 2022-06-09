<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
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

                <th colspan="2"><b>Deposit Type:</b></th>
            <th colspan="2">{{ $type }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th>SN</th>
            <th>Patient ID</th>
            <th>DEPO_DEPOSITTYPE</th>
            <th>STATUS</th>
            <th>INPATIENTID</th>
            <th>PATIENTNAME</th>
            <th>DEPOSITNO</th>
            <th>DEPOSITDATE</th>
            <th>DEPOSITCOLN</th>
            <th>DEPOSITREFUND</th>
            <th>FINALBILLAMT</th>
        </tr>
    </thead>
    <tbody>
    @php
        $totOldDeposit = 0;
        $totFinDeposit = 0;
        $totRefDeposit = 0;
                        $totDeposit = 0;

    @endphp
        @foreach ($depositData as $index => $deposit)
            @php
                $english_date = \Carbon\Carbon::parse($deposit->depositdate)->format('Y-m-d');
                    $nepali_date = \App\Utils\Helpers::dateEngToNepdash($english_date);
            @endphp
            <tr>
                <td>
                    {{ $index = $index +1 }}
                </td>
                @if(isset($deposit->patientInfo))
                    <td>
                        {{ $deposit->patientInfo->fldpatientval }}
                    </td>
                @else
                    <td>{{ $deposit->fldencounterval }}</td>
                @endif
                <td>{{ $deposit->deposittype }}</td>
                <td>{{ $deposit->fldadmission }}</td>
                <td>{{ $deposit->fldencounterval }}</td>
                <td>{{ $deposit->patientInfo->fldptnamefir }} {{ $deposit->patientInfo->fldptnamelast }}</td>
                <td>{{ $deposit->depositbillno }}</td>
                <td>{{ $nepali_date->full_date }}</td>
                {{-- @if($deposit->fldcashcredit)
                    <td>Rs {{\App\Utils\Helpers::numberFormat($deposit->fldcashcredit) }}</td>
                @else
                    <td>Rs 0.00</td>
                @endif --}}
                @php
                    $oldDeposit = \App\PatBillDetail::select(\DB::raw('sum(fldreceivedamt) as olddeposit'))
                                            ->where('fldencounterval',$deposit->fldencounterval)
                                            ->where('fldtime', '<', $finalfrom)
                                            ->first();
                    $refund = 0;

                @endphp

                @if($deposit->fldcashdeposit && $deposit->deposittype != 'Deposit Refund' && $deposit->deposittype != 'Pharmacy Deposit Refund')
                    <td>Rs {{\App\Utils\Helpers::numberFormat($deposit->fldcashdeposit) }}</td>
                    @php
                        $totOldDeposit +=($deposit->fldcashdeposit);
                    @endphp
                @else
                    <td>Rs 0.00</td>
                @endif
                    @if($deposit->fldcashdeposit && $deposit->deposittype == 'Deposit Refund' || $deposit->deposittype == 'Pharmacy Deposit Refund')
                        @php
                            $refund = abs($deposit->fldcashdeposit);
                                $totDeposit += $refund;
                        @endphp
                        <td>Rs {{ \App\Utils\Helpers::numberFormat($refund) }}</td>
                    @else
                        <td>Rs 0.00 </td>
                    @endif
                    <td>Rs 0.00</td>
                {{-- @if($expense == 1)
                    @php
                        $pat_expense = \App\PatBilling::select(DB::raw('sum(fldditemamt) as fldditemamt'))->where('fldencounterval',$deposit->fldencounterval)->where('fldsave',1)->first();
                    @endphp
                    @if (isset($pat_expense))
                        @if (isset($pat_expense->fldditemamt))
                            <td>Rs {{\App\Utils\Helpers::numberFormat($pat_expense->fldditemamt)}} </td>
                        @else
                            <td>Rs 0.00</td>
                        @endif
                    @else
                        <td>Rs 0.00</td>
                    @endif
                @endif
                @if($payment == 1)
                    @php
                        $pat_payment = \App\PatBillDetail::select(DB::raw('sum(fldreceivedamt) as fldreceivedamt'))->where('fldencounterval',$deposit->fldencounterval)->first();
                    @endphp
                    @if (isset($pat_payment))
                        @if (isset($pat_payment->fldreceivedamt))
                            <td>Rs {{\App\Utils\Helpers::numberFormat($pat_payment->fldreceivedamt)}} </td>
                        @else
                            <td>Rs 0.00</td>
                        @endif
                    @else{
                        <td>Rs 0.00</td>
                    @endif
                @endif --}}
            </tr>
        @endforeach
        <tr>
            <td>Total:</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs {{ \App\Utils\Helpers::numberFormat($totOldDeposit) }}</td>
            <td>Rs {{ \App\Utils\Helpers::numberFormat($totDeposit) }}</td>
            <td>Rs 0.00</td>
        </tr>
    </tbody>
</table>
