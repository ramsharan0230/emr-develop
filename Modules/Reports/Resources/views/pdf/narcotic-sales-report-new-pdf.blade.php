<head>
    <title>Narcotic Sales Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @media print {
            .page {
                margin: 5px;
            }           
        }
        .table {
            border-collapse: collapse;
            width: 100%;      
        }
        .table td, .table th {
            border: 1px solid #a79c9c;
            padding: 4px;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        p, h3 {
            margin-bottom: 0; margin-top: 2px;
        }
        main{
            width: 90%;
            margin: 0 auto;;
        }
        .content-body table { page-break-inside:auto; }
        .content-body tr    { page-break-inside:avoid; page-break-after:auto }
        .border-none{
            border: none;
        }
        span{
            margin-top: 10px;
        }
    </style>
</head>

<div class="page">
    <div class="row">
            <table style="width: 100%;" >
                <tr>
                    <th colspan="7" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</th>
                </tr>
                <tr>
                    <th colspan="7" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</th>
                </tr>

                <tr>
                    <th colspan="7" style="text-align:center;">Retailers Records For Narcotics and Psychotropic Medicines</th>
                </tr>

                <tr>
                    <th colspan ="3" style="text-align: left;">Date: {{$fromdateeng}} To {{$todateeng}}</th>
                    <th colspan ="1" style="text-align: left;"></th>
                    <th colspan ="3" style="text-align: right;">Printed By: {{$userid}}<br>Printed Time: {{ \Carbon\Carbon::now() }}</th>

                </tr>
            </table>
    </div>
    <div class="row">
        @if (!is_null($medicine))
            <table style="width: 100%;" >
                <tr>
                    <th colspan ="3" style="text-align: left;">Name of Drug in Generic Name : {{ $medicine }}</th>
                    <th colspan ="1" style="text-align: left;"></th>
                    <th colspan ="3" style="text-align: right;">Name oF Manufacturer</th>
                    

                </tr>
                <th colspan ="3" style="text-align: left;">Trade name</th>
                <tr>

                </tr>
            </table>      
        @endif
      
</div>
    <div class="table-responsive res-table" style="max-height: none">
        <table class="table content-body">
            <thead>
                <tr>    
                    <td>S.N.</td>
                    <td>Date</td>
                    <td>Batch No and Exp date</td>
                   @if (is_null($medicine))
                    <td>Medicine </td>
                   @endif 
                    <td>Qty. Receive</td>
                    <td>Suppliers Name</td>
                    <td>Patients name</td>
                    <td>Quantity Dispensed</td>
                    <td>Prescribed By</td>
                    <td>Name and sign of recipient</td>
                    <td>Dispensers name and sign </td>
                    <td>Qty. in Stock </td>
                    <td>Remarks</td>
                </tr>
            </thead>
            
            <tbody>

                @isset($result)
                @foreach($result as $key => $results)
                    <tr>
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
                    $stockAtThatTime[$results->med] [$results->Supplier_name] =  $openingStockMed->isNotEmpty() ? $openingStockMed->first()->BalanceQty -$results->qty  : $results->Quantity_receive - $results->qty ;
                }
            @endphp
                    <td>{{ $key }}</td>
                    <td>{{ $results->DateTime }}</td>
                    <td>{{ $results->Batch_no }} <br>
                        {{ $results->Expiry_date }}
                    </td>
                    @if(is_null($medicine))<td>{{ $results->med }}</td> @endif
                    <td> {{ $results->Quantity_receive}} </td> 
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
                        {{-- {{ $openingStockMed->isNotEmpty() ? $openingStockMed->first()->BalanceQty  : $results->qty  }}  --}}
                    </td>
                    <td>{{ $results->remarks }}</td>
                    </tr>

                @endforeach
                @endisset
                
            </tbody>

        </table>
    </div>
</div>


            
     


 
