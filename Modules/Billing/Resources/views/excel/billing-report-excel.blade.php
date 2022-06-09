<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b>
        </th>
    </tr>

    <tr>
        <th>Billing Report</th>
    </tr>

    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <td>S.No</td>
        <td>Date</td>
        <td>Time</td>
        <td>Invoice</td>
        <td>EncID</td>
        <td>Name</td>
        <td>OldDepo</td>
        <td>TotAmt</td>
        <td>TaxAmt</td>
        <td>DiscAmt</td>
        <td>NetTot</td>
        <td>RecAmt</td>
        <td>TotalDepo</td>
        <td>User</td>
        <td>PaymentMode</td>
        <td>BankName</td>
        <td>ChequeNo</td>
        <td>TaxGroup</td>
        <td>DiscGroup</td>
        <td>Share Type</td>
        <td>Doctor</td>

    </tr>
    </thead>
    <tbody>
    @php
        $totalamount = 0;
        $taxamount = 0;
        $discAmt = 0;
        $nettot = 0;
        $recAmt = 0;
    @endphp
    @foreach($results as $r)
        @php
            $datetime = explode(' ', $r['fldtime']);
            $enpatient = \App\Encounter::where('fldencounterval',$r['fldencounterval'])->with('patientInfo')->first();
            $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';
            $totalamount += $r['flditemamt'];
            $taxamount += $r['fldtaxamt'];
            $discAmt += $r['flddiscountamt'];
            $nettot += $r['fldchargedamt'];
            $recAmt +=$r['fldreceivedamt'];
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>{{$datetime[0] ? \App\Utils\Helpers::dateToNepali($datetime[0]) :''}}</td>
            <td>{{$datetime[1]}}</td>
            <td>{{$r['fldbillno']}}</td>
            <td>{{$r['fldencounterval']}}</td>
            <td>{{$fullname}}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($r['fldprevdeposit']) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($r['flditemamt']) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($r['fldtaxamt']) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($r['flddiscountamt']) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($r['fldchargedamt']) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($r['fldreceivedamt']) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($r['fldcurdeposit']) }}</td>
            <td>{{$r['flduserid']}}</td>
            <td>{{$r['payment_mode']}}</td>
            <td>{{$r['fldbankname']}}</td>
            <td>{{$r['fldchequeno']}}</td>
            <td>{{$r['fldtaxgroup']}}</td>
            <td>{{$r['flddiscountgroup']}}</td>
            {{-- <td>{{$r['type']}}</td> --}}
            {{-- <td>{{$r['fullname']}}</td> --}}
                @php
                    // $r = collect($r);
                @endphp
                {{-- @dd($r); --}}
            <td> {{ ($r->patbill->isNotEmpty() && $r->patbill->first()->pat_billing_shares->isNotEmpty()  )  ?  $r->patbill->first()->pat_billing_shares->first()->type : null }}</td>
            <td> {{ ($r->patbill->isNotEmpty() && $r->patbill->first()->pat_billing_shares->isNotEmpty()  )  ?  $r->patbill->first()->pat_billing_shares->first()->user->fldfullname : null }}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Amount: {{ \App\Utils\Helpers::numberFormat($totalamount) }}</td>
        <td>TotalTaxAmt : {{ \App\Utils\Helpers::numberFormat($taxamount) }}</td>
        <td>TotalDiscAmt : {{ \App\Utils\Helpers::numberFormat($discAmt) }}</td>
        <td>TotalNetAmt : {{ \App\Utils\Helpers::numberFormat($nettot) }}</td>
        <td>TotalRecAmt : {{ \App\Utils\Helpers::numberFormat($recAmt) }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
