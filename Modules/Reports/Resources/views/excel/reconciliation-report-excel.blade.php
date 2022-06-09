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
        <tr><th colspan="12" style="text-align: center; font-weight: bold;">Reconciliation Report</th></tr>
        <tr><th></th></tr>
        <tr><th>{{$nepalifromdate}} TO {{$nepalitodate}}</th></tr>
        <tr><th></th></tr> 
        
        <tr>
            <td rowspan="2"><b>Date</b></td>
            <td rowspan="2"><b>Total Sales</b></td>
            <td rowspan="2"><b>Credit Sales</b></td>
            <td rowspan="2"><b>Paid Sales</b></td>
            <td rowspan="2"><b>VAT</b></td>
            <td colspan="2" style="text-align: center;"><b>Collection</b></td>
            <td colspan="2" style="text-align: center;"><b>Deposit Received</b></td>
            <td colspan="1"><b>Deposit </b></td>
            <td colspan="1"><b>Direct </b></td>
            <td colspan="1"><b>Cash Sales </b></td>
            <td colspan="1"><b>Collection for </b></td>
            <td rowspan="2"><b>Diff</b></td>
        </tr>
        <tr>
            
            <td><b>Cash And Card</b></td>
            <td><b>Bank</b></td>

            <td><b>Cash And Card</b></td>
            <td><b>Bank</b></td>

            <td><b>Adjustment</b></td>
            <td><b>Adjustment</b></td>

            <td><b>for the period</b></td>
            <td><b>Cash Sales</b></td>
        </tr>
    </thead>
    <tbody>
        @if(isset($datedata) && count($datedata) > 0)
            @php
                $totalsalesinmonth = 0;
                $totalcreditsales = 0;
                $totaldepositadjustments = 0;
                $cashsales = 0;
                $totaldiff = 0;
            @endphp
            @foreach($datedata as $d)
                @php
                    $totalsales = \App\PatBilling::where('fldcomp',$department)->where('fldtime','LIKE',$d->date.'%')->where('fldsave','1')->sum('fldditemamt');
                    $paidsales = \DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.fldbilltype','LIKE','cash')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    $creditsales = \DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.payment_mode','LIKE','credit')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    // echo $creditsales; exit;
                    $vatamt = \DB::table('tblpatbilling as p')
                                    ->where('p.fldcomp',$department)
                                    ->where('p.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldtaxamt');
                    $bankamt = \DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.payment_mode','LIKE','fonepay')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    $depositcashandcardamt = \DB::table('tblpatbilldetail as pb')
                                    ->where('pb.fldbillno','LIKE','DEP%')
                                    ->where(function ($query) {
                                        $query->orWhere('pb.payment_mode','LIKE','cash')
                                            ->orWhere('pb.payment_mode','LIKE','card');
                                    })
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->sum('pb.fldreceivedamt');
                    $depositbankdamt = \DB::table('tblpatbilldetail as pb')
                                    ->where('pb.fldbillno','LIKE','DEP%')
                                    ->where('pb.payment_mode','LIKE','fonepay')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->sum('pb.fldreceivedamt');

                    $cashandcreditsales = \DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where(function ($query) {
                                        $query->orWhere('pb.payment_mode','LIKE','cash')
                                            ->orWhere('pb.payment_mode','LIKE','card');
                                    })
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');

                    $depositadjustment = \DB::table('tblpatbilldetail as pb')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->sum('pb.fldprevdeposit');
                    $value1 = $cashandcreditsales+$bankamt;
                    $value2 = $depositcashandcardamt+$depositbankdamt;
                    $m3 = $paidsales-$depositadjustment;
                    $n3 = $value1 - $value2;
                    $totalsalesinmonth += $totalsales;
                    $totalcreditsales += $creditsales;
                    $totaldepositadjustments +=$depositadjustment;
                    $cashsales += $m3;
                    $totaldiff += ($m3-$n3);
                @endphp
                <tr>
                    <td>{{$d->date}}</td>
                    <td>{{(isset($totalsales) && !is_null($totalsales))? $totalsales : '0'}}</td>
                    <td>{{(isset($creditsales) && !is_null($creditsales))? $creditsales : '0'}}</td>
                    <td>{{(isset($paidsales) && !is_null($paidsales))? $paidsales : '0'}}</td>
                    <td>{{(isset($vatamt) && !is_null($vatamt))? $vatamt : '0'}}</td>
                    <td>{{(isset($cashandcreditsales) && !is_null($cashandcreditsales))? $cashandcreditsales : '0'}}</td>
                    <td>{{(isset($bankamt) && !is_null($bankamt))? $bankamt : '0'}}</td>
                    <td>{{(isset($depositcashandcardamt) && !is_null($depositcashandcardamt))? $depositcashandcardamt : '0'}}</td>
                    <td>{{(isset($depositbankdamt)  && !is_null($depositbankdamt))? $depositbankdamt : '0'}}</td>
                    <td>{{(isset($depositadjustment) && !is_null($depositadjustment))? $depositadjustment : '0'}}</td>
                    <td></td>
                    <td>{{(isset($m3) && !is_null($m3))? $m3 : '0'}}</td>
                    <td>{{(isset($n3) && !is_null($n3))? $n3 : '0'}}</td>
                    <td>{{$m3-$n3}}</td>
                </tr>
            @endforeach
        @endif
        <tr><th></th></tr> 
        <tr>
            <th></th>
            <th></th>
            <th colspan="2">Total Sales in the Month</th>
            <th></th>
            <th>{{$totalsalesinmonth}}</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th colspan="2">Total Credit Sales</th>
            <th></th>
            <th>{{$totalcreditsales}}</th>
        </tr> 
        <tr>
            <th></th>
            <th></th>
            <th colspan="2">Total Deposit Adjustment</th>
            <th></th>
            <th>{{$totaldepositadjustments}}</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th colspan="2">Cash/Actual sales</th>
            <th></th>
            <th>{{$cashsales}}</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th colspan="2">Diff</th>
            <th></th>
            <th>{{$totaldiff}}</th>
        </tr>
    </tbody>
</table>