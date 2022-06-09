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
        <tr><th colspan="12" style="text-align: center;"><b>Reconciliation Report Summary</b></th></tr>
        <tr><th></th></tr>
        <tr><th>{{$nepalifromdate}} BS TO {{$nepalitodate}} BS</th><th></th><th></th><th>{{$filterdata['eng_from_date']}} AD TO {{$filterdata['eng_to_date']}} AD</th></tr>
        <tr><th></th></tr> 
        
        <tr>
            <td><b>Year</b></td>
            <td><b>Month</b></td>
            <td><b>Total Sales</b></td>
            <td><b>Credit Sales</b></td>
            <td><b>Deposit Adjustment</b></td>
            <td><b>Cash Sales</b></td>
            
            <td><b>Diff</b></td>
        </tr>
        
    </thead>
    <tbody>
        @if(isset($datedata) && count($datedata) > 0)
            @foreach($datedata as $d)
                @php
                    if(strlen($d->Month) == 1){
                        $month = '0'.$d->Month;
                    }else{
                        $month = $d->Month;
                    }
                    $newdate = $d->Year.'-'.$month;
                    $totalsales = \App\PatBilling::where('fldcomp',$department)
                                ->where('fldtime','LIKE',$newdate.'%')
                                ->where('fldtime','<=',$filterdata['eng_to_date'])
                                ->where('fldsave','1')
                                ->sum('fldditemamt');
                    $paidsales = \DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.fldbilltype','LIKE','cash')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$newdate.'%')
                                    ->where('pb.fldtime','<=',$filterdata['eng_to_date'])
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    $creditsales = \DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.payment_mode','LIKE','credit')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$newdate.'%')
                                    ->where('pb.fldtime','<=',$filterdata['eng_to_date'])
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    $depositadjustment = \DB::table('tblpatbilldetail as pb')
                                    ->where('pb.fldcomp',$department)
                                    ->where('pb.fldtime','LIKE',$newdate.'%')
                                    ->where('pb.fldtime','<=',$filterdata['eng_to_date'])
                                    ->sum('pb.fldprevdeposit');
                    
                    $cashsales = $paidsales-$depositadjustment;
                    $diff = ($totalsales-$creditsales-$depositadjustment-$cashsales);
                @endphp
                <tr>
                    <td>{{$d->Year}}</td>
                    <td>{{date('F', mktime(0, 0, 0, $month, 10))}}</td>
                    <td>{{(isset($totalsales) && !is_null($totalsales))? $totalsales : '0'}}</td>
                    <td>{{(isset($creditsales) && !is_null($creditsales))? $creditsales : '0'}}</td>
                    <td>{{(isset($depositadjustment) && !is_null($depositadjustment))? $depositadjustment : '0'}}</td>
                    <td>{{(isset($cashsales) && !is_null($cashsales))? $cashsales : '0'}}</td>
                    <td>{{(isset($diff) && !is_null($diff))? $diff : '0'}}</td>
                    
                </tr>
            @endforeach
        @endif
        
    </tbody>
</table>