<table  style="width: 100%" 
id="myTableResponse" data-show-columns="true" data-search="true" data-show-toggle="true"
data-pagination="true"
data-resizable="true"
>
    <thead class="thead-light">
        <tr>
            <th>S.N.</th>
            <th>Date</th>
            <th>Batch No and Exp date</th>
            <th>Medicine </th>
            <th>Qty. Receive</th>
            <th>Suppliers Name</th>
            <th>Patients name</th>
            <th>Quantity Dispensed</th>
            <th>Prescribed By</th>
            <th>Name and sign of recipient</th>
            <th>Dispensers name and sign </th>
            <th>Qty. in Stock </th>
            <th>Remarks</th>
            {{-- <th>Stock No</th> --}}
        </tr>
    </thead>

    <tbody id="item_result">
        @forelse ($result as $key => $results)


            @php
                $key++;
                $rem =$results->remarks ;

                $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
                $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
                $medicine = $request->medicine_name ;

                $fiscal_year = $data['fiscal_year'] = \App\Year::where('fldfirst', '<=', $fromdateeng)->where('fldlast', '>=', $fromdateeng)->first();


                    $fiscal_year = $data['fiscal_year'] = \App\Year::where('fldfirst', '<=', $fromdateeng)->where('fldlast', '>=', $fromdateeng)->first();

                    $day_before = date('Y-m-d', strtotime($fromdateeng . ' -1 day'));
                    $request['search_medecine'] = $results->stocknumber;
                    $request['department'] = \App\Utils\Helpers::getCompName() ;
                    $dateFrom = \Carbon\Carbon::parse($results->DateTime)->subDay(1)->format('Y-m-d');

                    $opening_sql = "with cte as (
                select @a:=@a+1 serial_number,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
                    select fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty + IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where  fldstockno= '" . $request->search_medecine . "'and (cast(fldpurdate  as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldpurdate  as date) <= '" . $dateFrom . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
                    select fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn where fldstockno= '" . $request->search_medecine . "' and (cast(fldtime as date) >= '" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $dateFrom . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
                select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $dateFrom . "') and fldfromcomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
                select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(fldqty) as purqty,0 as retQty,+sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $dateFrom . "') and fldtocomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
                select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale where fldqtydisp is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $dateFrom . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
                /* select  fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling where flditemqty is not null and flditemno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) <= '" . $dateFrom . "') and fldcomp='" . $request->department . "' Group by fldbillno,cast(fldtime as date) union ALL */

                select  0 as cost,cast(fldtime as date) as datetime,(0) as amount,0 as fldbillno,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblpatdosing where fldqtydisp is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) <= '" . $dateFrom . "') and fldcomp='" . $request->department . "' Group by cast(fldtime as date) union ALL

                select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment where fldcurrqty is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $dateFrom . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date)
                ) as T,  (SELECT @a:= 0) AS a
                JOIN (SELECT @running_total:=0) r
                ORDER BY T.datetime  ASC)
                , cte2 as (
                select BalanceQty , Rate from cte order by serial_number desc limit 1
                )

                , cte3 as (
                select sum(PurQty) as PurQty , sum(QtyIssue) as QtyIssue  from cte
                )

                select *, cte2.BalanceQty*cte2.Rate as BalAmt from cte2, cte3
                ";

                $opening_sql = \DB::select($opening_sql);

                $openingStockMed = collect($opening_sql) ;

                // $stockAtThatTime[$results->med] [$results->Supplier_name] =  $openingStockMed->isNotEmpty() ? $openingStockMed->BalanceQt : $results->Quantity  ;
                if(!empty($stockAtThatTime[$results->med] [$results->Supplier_name]) && $openingStockMed->isNotEmpty())
                {
                    $stockAtThatTime[$results->med] [$results->Supplier_name]  =  $stockAtThatTime[$results->med] [$results->Supplier_name] - $results->qty  ;   ; 
                }
                elseif (!empty($stockAtThatTime[$results->med] [$results->Supplier_name]) && $openingStockMed->isEmpty()) {
                    $stockAtThatTime[$results->med] [$results->Supplier_name] =$stockAtThatTime[$results->med] [$results->Supplier_name] - $results->qty ;
                }
                else {
                    $stockAtThatTime[$results->med] [$results->Supplier_name] =  
                    ($openingStockMed->isNotEmpty() && !is_null($results->qty) ) ? 
                        
                        $openingStockMed->first()->BalanceQty -$results->qty  : 
                        ( !is_null($results->Quantity_receive) && !is_null($results->qty)  ?  (int)   $results->Quantity_receive - (int) $results->qty  : null);
                    // $stockAtThatTime[$results->med] [$results->Supplier_name] = 0 ;
                }

                $receivequantitySql = "select sum(fldtotalqty) as receiveForthatDay from tblpurchase as purchaseSingle where 
                    cast(purchaseSingle.fldtime as date) = '".$results->DateTime . "' 
                    and fldsuppname = '".$results->Supplier_name . "' 
                    /* and fldbatch = '".$results->Batch_no . "' */ 
                    and fldstockid = '".$results->med . "' 
                ";
                $receivequantity = collect(\DB::select($receivequantitySql));
                $checkbalanceIsPositive = true ;
                $checkbalanceIsPositive =  (!is_null($results->Quantity_receive) ) ? true : false ;

                // dd($receivequantity);


                // dump($stockAtThatTime[$results->med] [$results->Supplier_name] );
                // select  0 as cost,cast(fldtime as date) as datetime,(0) as amount,0 as fldbillno,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblpatdosing where fldqtydisp is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) <= '" . $dateFrom . "') and fldcomp='" . $request->department . "' Group by cast(fldtime as date) union ALL
            @endphp
            @if($checkbalanceIsPositive)
                <tr>
                    <td>{{ $key }}</td>
                    <td>{{ $results->FullDate }}</td>
                    <td>{{ $results->Batch_no }} <br>
                        {{ $results->Expiry_date }}
                    </td>
                    <td>{{ $results->med }}</td>
                    <td> 
                        {{ $results->Quantity_receive}} 
                        {{-- {{$receivequantity }} --}}
                        {{-- @dump($receivequantity->first()->receiveForthatDay, $results->DateTime) --}}
                    </td> 
                    <td>{{ $results->Supplier_name }}</td>
                    <td>{{ $results->name  }}</td>
                    <td> {{ $results->qty }} &nbsp; 
                    </td>
                    <td>{{ $results->Prescribed_By  }}</td>
                    <td></td>
                    <td>{{ $results->Dispensed_By  }}</td>
                    {{-- <td>{{ $results->Quantity }}</td> --}}
                    <td> 
                        {{ $stockAtThatTime[$results->med] [$results->Supplier_name]  }}
                        {{-- {{ $openingStockMed->isNotEmpty() ? $openingStockMed->first()->BalanceQty - $results->qty : $results->qty  }}  --}}
                        {{-- {{ $openingStockMed->isNotEmpty() ? $openingStockMed->first()->BalanceQty + $results->qty - $openingStockMed->first()->QtyIssue  : $results->qty  }}  --}}
                        {{-- {{ $openingStockMed->isNotEmpty() ? $openingStockMed->first()->BalanceQty  : $results->Quantity  }}  --}}
                    
                        {{-- {{ $openingStockMed }}  --}}
                    </td>
                    <td>{{ $rem }} </td>
                    {{-- <td>{{ $results->stocknumber}}</td> --}}
                </tr>
            @endif
        @empty
            
        @endforelse
    </tbody>
</table>