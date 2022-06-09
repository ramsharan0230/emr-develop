<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
        <tr><th></th></tr>
    </thead>
</table>
<table style="width: 100%;border-collapse: collapse;border: 1px solid black;">
    <tbody>
        <tr>
            <td style="border: 1px solid black;">Paytype</td>
            <td style="border: 1px solid black;">Billdate</td>
            <td style="border: 1px solid black;">Billtime</td>
            <td style="border: 1px solid black;">Billno</td>
            <td style="border: 1px solid black;">Patientid</td>
            <td style="border: 1px solid black;">Inpatientid</td>
            <td style="border: 1px solid black;">Billby</td>
            <td style="border: 1px solid black;">Billtype</td>
            <td style="border: 1px solid black;">Patientcategory</td>
            <td style="border: 1px solid black;">Orgbillcategory</td>
            <td style="border: 1px solid black;">Name</td>
            <td style="border: 1px solid black;">Gross</td>
            <td style="border: 1px solid black;">Discount</td>
            <td style="border: 1px solid black;">Subtotal</td>
            <td style="border: 1px solid black;">Taxabletotal</td>
            <td style="border: 1px solid black;">Nontaxabletotal</td>
            <td style="border: 1px solid black;">Svrtax</td>
            <td style="border: 1px solid black;">Nettotal</td>
        </tr>
        @php
            $grosssum = $discountsum = $itemamountsum = $taxablesum = $nontaxablesum = $taxtotal = $grandtotalsum = 0;
        @endphp
        @if (isset($results['Cash']) && $results['Cash'])
            @foreach($results['Cash'] as $r)
                @php
                    $datetime = explode(' ', $r->fldtime);

                    $taxable = $r->fldtaxper == 0 ? 0 : $r->fldditemamt;
                    $nontaxable = $r->fldtaxper == 0 ? $r->fldditemamt : 0;
                    $grandtotal = $r->fldditemamt+$r->fldtaxamt;

                    $grosssum += $r->fldgross;
                    $discountsum += $r->flddiscamt;
                    $itemamountsum += $r->fldditemamt;
                    $taxablesum += $taxable;
                    $nontaxablesum += $nontaxable;
                    $taxtotal += $r->fldtaxamt;
                    $grandtotalsum += $grandtotal;
                @endphp
                <tr>
                    <td style="border: 1px solid black;">Cash</td>
                    <td style="border: 1px solid black;">{{ $datetime[0] ? \App\Utils\Helpers::dateToNepali($datetime[0]) :'' }}</td>
                    <td style="border: 1px solid black;">{{ isset($datetime[1]) ? $datetime[1] : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillno }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldencounterval }}</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldadmitfile : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->flduserid }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillingmode }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldfullname : '' }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldgross) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->flddiscamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldditemamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxable) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($nontaxable) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldtaxamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotal) }}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="11" style="text-align: right;border: 1px solid black;">Total Amount:</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grosssum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($discountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($itemamountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxablesum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($nontaxablesum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxtotal) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotalsum) }}</td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <td style="font-weight: 600;" colspan="13">Cash Refund Bill</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Billdate</td>
            <td style="border: 1px solid black;">Billtime</td>
            <td style="border: 1px solid black;">Billno</td>
            <td style="border: 1px solid black;">Patientid</td>
            <td style="border: 1px solid black;">Prevbillno</td>
            <td style="border: 1px solid black;">Name</td>
            <td style="border: 1px solid black;">Billby</td>
            <td style="border: 1px solid black;">Gross</td>
            <td style="border: 1px solid black;">Discount</td>
            <td style="border: 1px solid black;">Subtotal</td>
            <td style="border: 1px solid black;">Svrtax</td>
            <td style="border: 1px solid black;">Nettotal</td>
        </tr>
    </thead>
    <tbody>
        @php
            $grosssum = $discountsum = $itemamountsum = $taxablesum = $nontaxablesum = $taxtotal = $grandtotalsum = 0;
        @endphp
        @if (isset($results['CashReturn']) && $results['CashReturn'])
            @foreach($results['CashReturn'] as $r)
                @php
                    $datetime = explode(' ', $r->fldtime);
                    $grandtotal = $r->fldditemamt+$r->fldtaxamt;

                    $grosssum += $r->fldgross;
                    $discountsum += $r->flddiscamt;
                    $itemamountsum += $r->fldditemamt;
                    $taxtotal += $r->fldtaxamt;
                    $grandtotalsum += $grandtotal;
                @endphp
                <tr>
                    <td style="border: 1px solid black;">{{ $datetime[0] ? \App\Utils\Helpers::dateToNepali($datetime[0]) :'' }}</td>
                    <td style="border: 1px solid black;">{{ isset($datetime[1]) ? $datetime[1] : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillno }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldencounterval }}</td>
                    <td style="border: 1px solid black;">{{ ($r->parentDetail) ? $r->parentDetail->fldbillno : '' }}</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldfullname : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->flduserid }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldgross) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->flddiscamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldditemamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldtaxamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotal) }}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="7" style="text-align: right;border: 1px solid black;">Total Amount:</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grosssum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($discountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($itemamountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxtotal) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotalsum) }}</td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <td style="font-weight: 600;" colspan="13">Deposit Bill</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Depositid</td>
            <td style="border: 1px solid black;">Patientid</td>
            <td style="border: 1px solid black;">Name</td>
            <td style="border: 1px solid black;">Billdate</td>
            <td style="border: 1px solid black;">Billtime</td>
            <td style="border: 1px solid black;">Depositno</td>
            <td style="border: 1px solid black;">Deposit Amt</td>
            <td style="border: 1px solid black;">Orgbillcategory</td>
            <td style="border: 1px solid black;">Deposittype</td>
            <td style="border: 1px solid black;">Referenceno</td>
        </tr>
    </thead>
    <tbody>
        @php
            $grosssum = $discountsum = $itemamountsum = $taxablesum = $nontaxablesum = $taxtotal = $grandtotalsum = 0;
        @endphp
        @if (isset($results['Deposit']) && $results['Deposit'])
            @foreach($results['Deposit'] as $r)
                @php
                    $datetime = explode(' ', $r->fldtime);
                    $grandtotal = $r->fldreceivedamt;
                    $grandtotalsum += $grandtotal;
                @endphp
                <tr>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    <td style="border: 1px solid black;">{{ $r->fldencounterval }}</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldfullname : '' }}</td>
                    <td style="border: 1px solid black;">{{ $datetime[0] ? \App\Utils\Helpers::dateToNepali($datetime[0]) :'' }}</td>
                    <td style="border: 1px solid black;">{{ isset($datetime[1]) ? $datetime[1] : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillno }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotal) }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    {{-- <td style="border: 1px solid black;">{{ $r->flditemname }}</td> --}}
                    <td style="border: 1px solid black;">{{ $r->fldbilltype }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="6" style="text-align: right;border: 1px solid black;">Total Amount:</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotalsum) }}</td>
            <td style="border: 1px solid black;">&nbsp;</td>
            <td style="border: 1px solid black;">&nbsp;</td>
            <td style="border: 1px solid black;">&nbsp;</td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <td style="font-weight: 600;" colspan="13">Deposit Refund Bill</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Depositid</td>
            <td style="border: 1px solid black;">Patientid</td>
            <td style="border: 1px solid black;">Name</td>
            <td style="border: 1px solid black;">Billdate</td>
            <td style="border: 1px solid black;">Billtime</td>
            <td style="border: 1px solid black;">Depositno</td>
            <td style="border: 1px solid black;">Deposit Amt</td>
            <td style="border: 1px solid black;">Orgbillcategory</td>
            <td style="border: 1px solid black;">Deposittype</td>
            <td style="border: 1px solid black;">Referenceno</td>
        </tr>
    </thead>
    <tbody>
        @php
            $grosssum = $discountsum = $itemamountsum = $taxablesum = $nontaxablesum = $taxtotal = $grandtotalsum = 0;
        @endphp
        @if (isset($results['DepositReturn']) && $results['DepositReturn'])
            @foreach($results['DepositReturn'] as $r)
                @php
                    $datetime = explode(' ', $r->fldtime);
                    $grandtotal = $r->fldreceivedamt;
                    $grandtotalsum += $grandtotal;
                @endphp
                <tr>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    <td style="border: 1px solid black;">{{ $r->fldencounterval }}</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldfullname : '' }}</td>
                    <td style="border: 1px solid black;">{{ $datetime[0] ? \App\Utils\Helpers::dateToNepali($datetime[0]) :'' }}</td>
                    <td style="border: 1px solid black;">{{ isset($datetime[1]) ? $datetime[1] : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillno }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotal) }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    {{-- <td style="border: 1px solid black;">{{ $r->flditemname }}</td> --}}
                    <td style="border: 1px solid black;">{{ $r->fldbilltype }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="6" style="text-align: right;border: 1px solid black;">Total Amount:</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotalsum) }}</td>
            <td style="border: 1px solid black;">&nbsp;</td>
            <td style="border: 1px solid black;">&nbsp;</td>
            <td style="border: 1px solid black;">&nbsp;</td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <td style="font-weight: 600;" colspan="13">Credit Bill</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Paytype</td>
            <td style="border: 1px solid black;">Billdate</td>
            <td style="border: 1px solid black;">Billtime</td>
            <td style="border: 1px solid black;">Billno</td>
            <td style="border: 1px solid black;">Patientid</td>
            <td style="border: 1px solid black;">Inpatientid</td>
            <td style="border: 1px solid black;">Billby</td>
            <td style="border: 1px solid black;">Billtype</td>
            <td style="border: 1px solid black;">Patientcategory</td>
            <td style="border: 1px solid black;">Orgbillcategory</td>
            <td style="border: 1px solid black;">Name</td>
            <td style="border: 1px solid black;">Gross</td>
            <td style="border: 1px solid black;">Discount</td>
            <td style="border: 1px solid black;">Subtotal</td>
            <td style="border: 1px solid black;">Taxabletotal</td>
            <td style="border: 1px solid black;">Nontaxabletotal</td>
            <td style="border: 1px solid black;">Svrtax</td>
            <td style="border: 1px solid black;">Nettotal</td>
        </tr>
    </thead>
    <tbody>
        @php
            $grosssum = $discountsum = $itemamountsum = $taxablesum = $nontaxablesum = $taxtotal = $grandtotalsum = 0;
        @endphp
        @if (isset($results['Credit']) && $results['Credit'])
            @foreach($results['Credit'] as $r)
                @php
                    $datetime = explode(' ', $r->fldtime);

                    $taxable = $r->fldtaxper == 0 ? 0 : $r->fldditemamt;
                    $nontaxable = $r->fldtaxper == 0 ? $r->fldditemamt : 0;
                    $grandtotal = $r->fldditemamt+$r->fldtaxamt;

                    $grosssum += $r->fldgross;
                    $discountsum += $r->flddiscamt;
                    $itemamountsum += $r->fldditemamt;
                    $taxablesum += $taxable;
                    $nontaxablesum += $nontaxable;
                    $taxtotal += $r->fldtaxamt;
                    $grandtotalsum += $grandtotal;
                @endphp
                <tr>
                    <td style="border: 1px solid black;">Credit</td>
                    <td style="border: 1px solid black;">{{ $datetime[0] ? \App\Utils\Helpers::dateToNepali($datetime[0]) :'' }}</td>
                    <td style="border: 1px solid black;">{{ isset($datetime[1]) ? $datetime[1] : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillno }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldencounterval }}</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldadmitfile : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->flduserid }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillingmode }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldfullname : '' }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldgross) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->flddiscamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldditemamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxable) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($nontaxable) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldtaxamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotal) }}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="11" style="text-align: right;border: 1px solid black;">Total Amount:</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grosssum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($discountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($itemamountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxablesum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($nontaxablesum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxtotal) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotalsum) }}</td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;border-collapse: collapse;">
    <thead>
        <tr>
            <td style="font-weight: 600;" colspan="13">Credit Refund Bill</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Billdate</td>
            <td style="border: 1px solid black;">Billtime</td>
            <td style="border: 1px solid black;">Billno</td>
            <td style="border: 1px solid black;">Patientid</td>
            <td style="border: 1px solid black;">Prevbillno</td>
            <td style="border: 1px solid black;">Name</td>
            <td style="border: 1px solid black;">Billby</td>
            <td style="border: 1px solid black;">Gross</td>
            <td style="border: 1px solid black;">Discount</td>
            <td style="border: 1px solid black;">Subtotal</td>
            <td style="border: 1px solid black;">Svrtax</td>
            <td style="border: 1px solid black;">Nettotal</td>
        </tr>
    </thead>
    <tbody>
        @php
            $grosssum = $discountsum = $itemamountsum = $taxablesum = $nontaxablesum = $taxtotal = $grandtotalsum = 0;
        @endphp
        @if (isset($results['CreditReturn']) && $results['CreditReturn'])
            @foreach($results['CreditReturn'] as $r)
                @php
                    $datetime = explode(' ', $r->fldtime);
                    $grandtotal = $r->fldditemamt+$r->fldtaxamt;

                    $grosssum += $r->fldgross;
                    $discountsum += $r->flddiscamt;
                    $itemamountsum += $r->fldditemamt;
                    $taxtotal += $r->fldtaxamt;
                    $grandtotalsum += $grandtotal;
                @endphp
                <tr>
                    <td style="border: 1px solid black;">{{ $datetime[0] ? \App\Utils\Helpers::dateToNepali($datetime[0]) :'' }}</td>
                    <td style="border: 1px solid black;">{{ isset($datetime[1]) ? $datetime[1] : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldbillno }}</td>
                    <td style="border: 1px solid black;">{{ $r->fldencounterval }}</td>
                    <td style="border: 1px solid black;">{{ ($r->parentDetail) ? $r->parentDetail->fldbillno : '' }}</td>
                    <td style="border: 1px solid black;">{{ ($r->encounter && $r->encounter->patientInfo) ? $r->encounter->patientInfo->fldfullname : '' }}</td>
                    <td style="border: 1px solid black;">{{ $r->flduserid }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldgross) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->flddiscamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldditemamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($r->fldtaxamt) }}</td>
                    <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotal) }}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="7" style="text-align: right;border: 1px solid black;">Total Amount:</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grosssum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($discountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($itemamountsum) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($taxtotal) }}</td>
            <td style="border: 1px solid black;">{{ \App\Utils\Helpers::numberFormat($grandtotalsum) }}</td>
        </tr>
    </tbody>
</table>
