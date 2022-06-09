<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;

use App\Exports\NarcoticSalesExport;
use App\Year;
use Maatwebsite\Excel\Facades\Excel;

class NarcoticDispenseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct()
    {
        
    }

    public function bkindex(Request $request){

        if($request->fromdate){
            $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
            $todateeng = Helpers::dateNepToEng($request->todate)->full_date;

            try{

                    // $result = DB::select(DB::raw("select
                    // CONCAT(pd.fldencounterval,' / ' ,pt.fldptnamefir,' ',pt.fldptnamelast, '/Age: ',round(Datediff(Now(),fldptbirday)/365,0)) as Patient_Details,
                    // CONCAT(pd.flditem ,' /QTY: ', t2.flditemqty -t2.fldretqty , ' /Dose: ' ,pd.flddose ,' /Freq: ' , pd.fldfreq ) as Medicine_Item,
                    // CONCAT(u.firstname,' ',u.lastname,' / NMC: ',pd.fldregno) as Prescribed_By ,
                    // CONCAT(u.firstname,' ',u.lastname) as Dispensed_By ,
                    // t2.fldbillno as BillNo ,
                    // pd.fldtime as DateTime
                    // from
                    // tblpatdosing pd
                    // inner join tblmedbrand t on
                    // t.fldbrandid = pd.flditem
                    // inner JOIN tblpatbilling t2 on
                    // t2.fldparent = pd.fldid
                    // INNER join users u on
                    // u.username = pd.fldconsultant
                    // INNER join tblencounter on
                    // tblencounter.fldencounterval = pd.fldencounterval
                    // INNER join tblpatientinfo as pt on
                    // pt.fldpatientval = tblencounter.fldpatientval
                    // where
                    // cast(pd.fldtime as date) >= '". $fromdateeng . "'
                    // and cast(pd.fldtime as date) <= '"  . $todateeng ."'
                    // and pd.fldlevel  = 'Dispensed'
                    // and pd.fldsave = '1'
                    // and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                    // and t2.flditemqty - t2.fldretqty > 0
                    // and t2.fldbillno like 'PHM%'"));

                    $result = DB::select(DB::raw("select
                    pd.fldencounterval as enc,
                    CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
                    round(Datediff(Now(),fldptbirday)/365,0) as age,
                    pd.flditem as med,
                    t2.flditemqty-t2.fldretqty as qty,
                    pd.flddose as dose,
                    pd.fldfreq as freq,
                    UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
                    pd.fldregno as regno,
                    UPPER(REPLACE(t2.flduserid,'.',' ')) as Dispensed_By,
                    t2.fldbillno as BillNo,
                    pd.fldtime as DateTime
                    from
                    tblpatdosing pd
                    inner join tblmedbrand t on
                    t.fldbrandid = pd.flditem
                    inner JOIN tblpatbilling t2 on
                    t2.fldparent = pd.fldid
                    left join tblpatbilldetail as pbd on
                        pbd.fldbillno = t2.fldbillno
                    INNER join users u on
                    u.username = pd.fldconsultant
                    INNER join tblencounter on
                    tblencounter.fldencounterval = pd.fldencounterval
                    INNER join tblpatientinfo as pt on
                    pt.fldpatientval = tblencounter.fldpatientval
                    where
                    cast(pd.fldtime as date) >= '". $fromdateeng . "'
                    and cast(pd.fldtime as date) <= '"  . $todateeng ."'
                    and pd.fldlevel  = 'Dispensed'
                    and pd.fldsave = '1'
                    and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                    and t2.flditemqty - t2.fldretqty > 0
                    and t2.fldbillno like 'PHM%'"));

                    $html = '';

                    foreach ($result as $key => $results) {

                        $key++;

                        $html .= '<tr><td>' .$key . '</td>';
                        $html .= '<td>' . $results->enc . '<br/>' . $results->name . '<br/>' . 'Age: ' . $results->age . '</td>';
                        // $html .= '<td>' . $results->med . '<br/>' . 'QTY: ' . $results->qty . '<br/>' . 'Dose: ' . $results->dose  . '<br/>' . 'Freq: ' . $results->freq. '</td>';
                        $html .= '<td>' . $results->med . '<br/>' . 'QTY: ' . $results->qty . '</td>';
                        $html .= "<td>" . $results->Prescribed_By . '<br/>' . 'Regd No.: ' . $results->regno . "</td>";
                        $html .= "<td>" . $results->Dispensed_By . "</td>";
                        $html .= "<td>" . $results->BillNo . "</td>";
                        $html .= '<td>' . $results->DateTime . '</td>';
                        $html .= '</tr>';
                    }

                    return response()->json([
                        'data' => [
                            'status' => true,
                            'html' => $html
                        ]
                    ]);
        
                      
                }catch(\Exception $e){
                    dd($e);
                }
        }

        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));


        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('reports::pharmacy.narcotic-sales-report',array('date'=>$date));
    }

        /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request){

        if($request->fromdate){
            $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
            $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
            $medicine = $request->medicine_name ;

            // dd($request->all());

            try{

                    // $result = DB::select(DB::raw("select
                    // CONCAT(pd.fldencounterval,' / ' ,pt.fldptnamefir,' ',pt.fldptnamelast, '/Age: ',round(Datediff(Now(),fldptbirday)/365,0)) as Patient_Details,
                    // CONCAT(pd.flditem ,' /QTY: ', t2.flditemqty -t2.fldretqty , ' /Dose: ' ,pd.flddose ,' /Freq: ' , pd.fldfreq ) as Medicine_Item,
                    // CONCAT(u.firstname,' ',u.lastname,' / NMC: ',pd.fldregno) as Prescribed_By ,
                    // CONCAT(u.firstname,' ',u.lastname) as Dispensed_By ,
                    // t2.fldbillno as BillNo ,
                    // pd.fldtime as DateTime
                    // from
                    // tblpatdosing pd
                    // inner join tblmedbrand t on
                    // t.fldbrandid = pd.flditem
                    // inner JOIN tblpatbilling t2 on
                    // t2.fldparent = pd.fldid
                    // INNER join users u on
                    // u.username = pd.fldconsultant
                    // INNER join tblencounter on
                    // tblencounter.fldencounterval = pd.fldencounterval
                    // INNER join tblpatientinfo as pt on
                    // pt.fldpatientval = tblencounter.fldpatientval
                    // where
                    // cast(pd.fldtime as date) >= '". $fromdateeng . "'
                    // and cast(pd.fldtime as date) <= '"  . $todateeng ."'
                    // and pd.fldlevel  = 'Dispensed'
                    // and pd.fldsave = '1'
                    // and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                    // and t2.flditemqty - t2.fldretqty > 0
                    // and t2.fldbillno like 'PHM%'"));
                    // purchase.fldsuppname as Supplier_name,
                    // INNER join tblpurchase as purchase on 
                            // purchase.fldstockno = entry.fldstockno
                                // purchase.fldsuppname as Supplier_name,
                    // INNER join tblpurchase as purchase on 
                            // purchase.fldstockno = entry.fldstockno
                            // (sum(entry.fldqty) - (flditemqty-t2.fldretqty ) )as remainingStock,
                            // (sum( tblentry.fldqty from tblentry INNER join tblentry on tblentry.fldstockno = tblpatdosing.fldstockno where cast(tblpatdosing.fldtime as date) <= '"  . $todateeng ."' ) - (flditemqty-t2.fldretqty ) ) as remainingStock,
                            // (sum( tblentry.fldqty  INNER join tblentry on 
                            // tblentry.fldstockno = tblpatdosing.fldstockno where cast(tblpatdosing.fldtime ) <= '"  . $todateeng ."' )  ) as stockCalulation,
                            // JOIN (
                            //     SELECT *,  SUM(tblentry.fldqty) as total
                            //     FROM tblentry
                            //     GROUP BY med
                            //     ) AS t ON pd.fldstockno = t.fldstockno
                            

                            // sum(entry.fldqty) as totalReceive,
                            // (sum(entry.fldqty) - (flditemqty-t2.fldretqty ) )as remainingStock,
                            // tblpurchase.fldsuppname as Supplier_name,
                            // (
                                // ::table('tblpatdosing')
                                // ->when(!is_null($medicine), function($query) use($medicine){
                                //     $query->where('tblpatdosing.flditemtype', $med);
                                // })
                                $medicinesql ='';
                                if(!is_null($medicine)){
                                    $medicinesql =" and pd.flditem LIKE '%".$medicine."%'";
                                }

                                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $fromdateeng)->where('fldlast', '>=', $fromdateeng)->first();

                                $openingStock = "select sum(fldtotalqty) from tblpurchase where
                                    cast(tblpurchase.fldtime as date) <= '".$fromdateeng . "' and cast(tblpurchase.fldtime as date) >= '". $fiscal_year->fldfirst . "'
                                   /* Group by med, Supplier_name, Batch_no */
                                    "  ;


                                    $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $fromdateeng)->where('fldlast', '>=', $fromdateeng)->first();

                                    $day_before = date('Y-m-d', strtotime($fromdateeng . ' -1 day'));
                                    $request['search_medecine'] = '8356';
                                    $request['department'] = 'comp01' ;
                        
                                    $opening_sql = "with cte as (
                        select @a:=@a+1 serial_number,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
                                    select fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty + IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where  fldstockno= '" . $request->search_medecine . "'and (cast(fldpurdate  as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldpurdate  as date) < '" . $fromdateeng . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
                                    select fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn where fldstockno= '" . $request->search_medecine . "' and (cast(fldtime as date) >= '" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $fromdateeng . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
                                select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $todateeng . "') and fldfromcomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
                                select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(fldqty) as purqty,0 as retQty,+sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $todateeng . "') and fldtocomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
                                select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale where fldqtydisp is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $todateeng . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
                                select  fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling where flditemqty is not null and flditemno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $todateeng . "') and fldcomp='" . $request->department . "' Group by fldbillno,cast(fldtime as date) union ALL
                                select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment where fldcurrqty is not null and fldstockno='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $todateeng . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date)
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

                        // dd($opening_sql);
                                    

                               

                    $result = DB::select(DB::raw("select
                        
                            ($openingStock) as openingStoock,
                            entry.fldexpiry as Expiry_date,
                            entry.fldbatch as Batch_no,
                            pd.fldstockno as stocknumber,
                           /* extryToPurchase.fldsuppname  as Supplier_name, 
                            extryToPurchase.fldtotalqty as Quantity_receive,  */
                            entry.fldqty as Quantity, 
                           (CASE 
                                WHEN  extryToPurchase.fldtotalqty IS NOT NULL
                                    THEN (extryToPurchase.fldtotalqty) 
                                WHEN transferToPurchase.fldtotalqty IS NOT NULL
                                    THEN (transferToPurchase.fldtotalqty)
                                ELSE
                                    ''
                            END) as Quantity_receive,
                        
                            (CASE
                                WHEN  extryToPurchase.fldsuppname IS NOT NULL
                                    THEN extryToPurchase.fldsuppname 
                                WHEN transferToPurchase.fldsuppname IS NOT NULL
                                    Then transferToPurchase.fldsuppname
                                ELSE
                                    ''
                            END) as Supplier_name, 
                            
                           
                            
                             pbd.remarks as remarks, 
                            pd.fldencounterval as enc,
                            CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
                            round(Datediff(Now(),fldptbirday)/365,0) as age,
                            pd.flditem as med,
                           sum( t2.flditemqty-t2.fldretqty) as qty,
                            pd.flddose as dose,
                            pd.fldfreq as freq,
                            UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
                            pd.fldregno as regno,
                            UPPER(REPLACE(t2.flduserid,'.',' ')) as Dispensed_By,
                            t2.fldbillno as BillNo,
                            DATE_FORMAT(pd.fldtime, '%Y-%m-%d') as DateTime,
                            UNIX_TIMESTAMP(pd.fldtime) AS UnixTime,
                            pd.fldtime as FullDate
                            from
                            tblpatdosing pd
                            inner join tblmedbrand t on
                            t.fldbrandid = pd.flditem
                            inner JOIN tblpatbilling t2 on
                            t2.fldparent = pd.fldid
                            left join tblpatbilldetail as pbd on
                                pbd.fldbillno = t2.fldbillno
                            INNER join users u on
                            u.username = pd.fldconsultant
                            INNER join tblencounter on
                            tblencounter.fldencounterval = pd.fldencounterval
                            left join tblentry as entry on 
                                pd.fldstockno = entry.fldstockno 
                            left join tblpurchase as extryToPurchase on 
                                extryToPurchase.fldstockno = entry.fldstockno 
                            left join tbltransfer as transfer on 
                                transfer.fldstockno = entry.fldstockno 
                           left join tblpurchase as transferToPurchase on 
                                transferToPurchase.fldstockno = transfer.fldoldstockno 
                            INNER join tblpatientinfo as pt on
                            pt.fldpatientval = tblencounter.fldpatientval
                            where
                            cast(pd.fldtime as date) >= '".$fromdateeng . "'
                            and cast(pd.fldtime as date) <= '". $todateeng ."'
                            and pd.fldlevel  = 'Dispensed'
                            and pd.fldsave = '1'
                            and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                            and t2.flditemqty - t2.fldretqty > 0
                            and t2.fldbillno like 'PHM%'
                            ".$medicinesql."
                           /* Group by name, Supplier_name, DateTime */ 
                           Group by med, Supplier_name, name , DateTime, Batch_no,Prescribed_By
                           /* order by DateTime */
                           order by UnixTime
                           

                        "));
                        
                            
                    // dd($result);

                    $data['result'] = $result ;
                    $data['request'] = $request ;
                    $html = view('reports::pharmacy.ajax-views.narcotic-sales-report', $data)->render();
                    return response()->json([
                        'data' => [
                            'status' => true,
                            'html' => $html
                        ]
                    ]);
        
                      
                }catch(\Exception $e){
                    dd($e);
                }
        }

        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $medicines = DB::select(
            DB::raw('select
                fldbrandid as name from  tblmedbrand as med where fldnarcotic = "Yes" '));
        // dd($medicines);
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        
        return view('reports::pharmacy.narcotic-sales-report-new',[
            'date'=>$date,
            'medicines' => $medicines,
        ]);
    }




    public function bkexport(Request $request)
    {
        $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
        $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;

        try{

            $result = DB::select(DB::raw("select
                    pd.fldencounterval as enc,
                    CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
                    round(Datediff(Now(),fldptbirday)/365,0) as age,
                    pd.flditem as med,
                    t2.flditemqty-t2.fldretqty as qty,
                    pd.flddose as dose,
                    pd.fldfreq as freq,
                    UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
                    pd.fldregno as regno,
                    Upper(Replace(t2.flduserid,'.',' ')) as Dispensed_By ,
                    t2.fldbillno as BillNo ,
                    pd.fldtime as DateTime
                    from
                    tblpatdosing pd
                    inner join tblmedbrand t on
                    t.fldbrandid = pd.flditem
                    inner JOIN tblpatbilling t2 on
                    t2.fldparent = pd.fldid
                    left join tblpatbilldetail as pbd on
                        pbd.fldbillno = t2.fldbillno
                    INNER join users u on`
                    u.username = pd.fldconsultant
                    INNER join tblencounter on
                    tblencounter.fldencounterval = pd.fldencounterval
                    INNER join tblpatientinfo as pt on
                    pt.fldpatientval = tblencounter.fldpatientval
                    where
                    cast(pd.fldtime as date) >= '". $fromdateeng . "'
                    and cast(pd.fldtime as date) <= '"  . $todateeng ."'
                    and pd.fldlevel  = 'Dispensed'
                    and pd.fldsave = '1'
                    and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                    and t2.flditemqty - t2.fldretqty > 0
                    and t2.fldbillno like 'PHM%'"));

            return view('reports::pdf.narcotic-sales-report-pdf', array('result'=>$result,'userid'=>$userid,'fromdateeng'=>$fromdateeng,'todateeng'=>$todateeng));
        }catch(\Exception $e){
            dd($e);
        }
 
    }

    public function export(Request $request)
    {
        if($request->fromdate){
            $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
            $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
            $medicine = $request->medicine_name ;
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;

            // dd($request->all());

            try{
                //  $result =  (!is_null($medicine)) ? 
                //             DB::select(DB::raw("select
                        
                //             entry.fldexpiry as Expiry_date,
                //             entry.fldbatch as Batch_no,
                //             pd.fldstockno as stocknumber,
                //             (entry.fldqty) as Quantity,
                          
                        
                        
                //             (CASE 
                //                 WHEN  extryToPurchase.fldtotalqty IS NOT NULL
                //                     THEN (extryToPurchase.fldtotalqty) 
                //                 WHEN transferToPurchase.fldtotalqty IS NOT NULL
                //                     THEN (transferToPurchase.fldtotalqty)
                //                 ELSE
                //                     ''
                //             END) as Quantity_receive,
                        
                //             (CASE
                //                 WHEN  extryToPurchase.fldsuppname IS NOT NULL
                //                     THEN extryToPurchase.fldsuppname 
                //                 WHEN transferToPurchase.fldsuppname IS NOT NULL
                //                     Then transferToPurchase.fldsuppname
                //                 ELSE
                //                     ''
                //             END) as Supplier_name,
                        
                            
                //             pbd.remarks as remarks,
                //             pd.fldencounterval as enc,
                //             CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
                //             round(Datediff(Now(),fldptbirday)/365,0) as age,
                //             pd.flditem as med,
                //             t2.flditemqty-t2.fldretqty as qty,
                //             pd.flddose as dose,
                //             pd.fldfreq as freq,
                //             UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
                //             pd.fldregno as regno,
                //             UPPER(REPLACE(t2.flduserid,'.',' ')) as Dispensed_By,
                //             t2.fldbillno as BillNo,
                //             DATE_FORMAT(pd.fldtime, '%Y-%m-%d') as DateTime
                //             from
                //             tblpatdosing pd
                //             inner join tblmedbrand t on
                //             t.fldbrandid = pd.flditem
                //             inner JOIN tblpatbilling t2 on
                //             t2.fldparent = pd.fldid
                //             left join tblpatbilldetail as pbd on
                //                 pbd.fldbillno = t2.fldbillno
                //             INNER join users u on
                //             u.username = pd.fldconsultant
                //             INNER join tblencounter on
                //             tblencounter.fldencounterval = pd.fldencounterval
                //             left join tblentry as entry on 
                //                 entry.fldstockno  = pd.fldstockno
                //             left join tblpurchase extryToPurchase on 
                //                 extryToPurchase.fldstockno = entry.fldstockno
                //             left join tbltransfer as transfer on 
                //                 transfer.fldstockno = entry.fldstockno
                //             left join tblpurchase as transferToPurchase on 
                //                 transferToPurchase.fldstockno = transfer.fldoldstockno
                //             INNER join tblpatientinfo as pt on
                //             pt.fldpatientval = tblencounter.fldpatientval
                //             where
                //             cast(pd.fldtime as date) >= '".$fromdateeng . "'
                //             and cast(pd.fldtime as date) <= '". $todateeng ."'
                //             and pd.fldlevel  = 'Dispensed'
                //             and pd.fldsave = '1'
                //             and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                //             and t2.flditemqty - t2.fldretqty > 0
                //             and t2.fldbillno like 'PHM%'
                //             and pd.flditem = :medicine
                //            /* Group by Supplier_name, DateTime */
                //         "),['medicine' => $medicine])
                //         :
                //         DB::select(DB::raw("select
                        
                //         entry.fldexpiry as Expiry_date,
                //         entry.fldbatch as Batch_no,
                //         pd.fldstockno as stocknumber,
                //         sum(entry.fldqty) as Quantity,
                    


                //         (CASE 
                //             WHEN  extryToPurchase.fldtotalqty IS NOT NULL
                //                 THEN sum(extryToPurchase.fldtotalqty) 
                //             WHEN transferToPurchase.fldtotalqty IS NOT NULL
                //                 THEN sum(transferToPurchase.fldtotalqty)
                //             ELSE
                //                 ''
                //         END) as Quantity_receive,

                //         (CASE
                //             WHEN  extryToPurchase.fldsuppname IS NOT NULL
                //                 THEN extryToPurchase.fldsuppname 
                //             WHEN transferToPurchase.fldsuppname IS NOT NULL
                //                 Then transferToPurchase.fldsuppname
                //             ELSE
                //                 ''
                //         END) as Supplier_name,

                        
                //         pbd.remarks as remarks,
                //         pd.fldencounterval as enc,
                //         CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
                //         round(Datediff(Now(),fldptbirday)/365,0) as age,
                //         pd.flditem as med,
                //         t2.flditemqty-t2.fldretqty as qty,
                //         pd.flddose as dose,
                //         pd.fldfreq as freq,
                //         UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
                //         pd.fldregno as regno,
                //         UPPER(REPLACE(t2.flduserid,'.',' ')) as Dispensed_By,
                //         t2.fldbillno as BillNo,
                //         DATE_FORMAT(pd.fldtime, '%Y-%m-%d') as DateTime
                //         from
                //         tblpatdosing pd
                //         inner join tblmedbrand t on
                //         t.fldbrandid = pd.flditem
                //         inner JOIN tblpatbilling t2 on
                //         t2.fldparent = pd.fldid
                //         left join tblpatbilldetail as pbd on
                //             pbd.fldbillno = t2.fldbillno
                //         INNER join users u on
                //         u.username = pd.fldconsultant
                //         INNER join tblencounter on
                //         tblencounter.fldencounterval = pd.fldencounterval
                //         left join tblentry as entry on 
                //             entry.fldstockno  = pd.fldstockno
                //         left join tblpurchase extryToPurchase on 
                //             extryToPurchase.fldstockno = entry.fldstockno
                //         left join tbltransfer as transfer on 
                //             transfer.fldstockno = entry.fldstockno
                //         left join tblpurchase as transferToPurchase on 
                //             transferToPurchase.fldstockno = transfer.fldoldstockno
                //         INNER join tblpatientinfo as pt on
                //         pt.fldpatientval = tblencounter.fldpatientval
                //         where
                //         cast(pd.fldtime as date) >= '".$fromdateeng . "'
                //         and cast(pd.fldtime as date) <= '". $todateeng ."'
                //         and pd.fldlevel  = 'Dispensed'
                //         and pd.fldsave = '1'
                //         and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                //         and t2.flditemqty - t2.fldretqty > 0
                //         and t2.fldbillno like 'PHM%'
                //         /* Group by Supplier_name, DateTime */
                //     ")) ;
            
                // dd($result);
                $medicinesql ='';
                if(!is_null($medicine)){
                    $medicinesql =" and pd.flditem LIKE '%".$medicine."%'";
                }

                $result = DB::select(DB::raw("select
                        
                entry.fldexpiry as Expiry_date,
                            entry.fldbatch as Batch_no,
                            pd.fldstockno as stocknumber,
                           /* extryToPurchase.fldsuppname  as Supplier_name, 
                            extryToPurchase.fldtotalqty as Quantity_receive,  */
                            entry.fldqty as Quantity, 
                           (CASE 
                                WHEN  extryToPurchase.fldtotalqty IS NOT NULL
                                    THEN (extryToPurchase.fldtotalqty) 
                                WHEN transferToPurchase.fldtotalqty IS NOT NULL
                                    THEN (transferToPurchase.fldtotalqty)
                                ELSE
                                    ''
                            END) as Quantity_receive,
                        
                            (CASE
                                WHEN  extryToPurchase.fldsuppname IS NOT NULL
                                    THEN extryToPurchase.fldsuppname 
                                WHEN transferToPurchase.fldsuppname IS NOT NULL
                                    Then transferToPurchase.fldsuppname
                                ELSE
                                    ''
                            END) as Supplier_name, 
                            
                           
                            
                             pbd.remarks as remarks, 
                            pd.fldencounterval as enc,
                            CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
                            round(Datediff(Now(),fldptbirday)/365,0) as age,
                            pd.flditem as med,
                           sum( t2.flditemqty-t2.fldretqty) as qty,
                            pd.flddose as dose,
                            pd.fldfreq as freq,
                            UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
                            pd.fldregno as regno,
                            UPPER(REPLACE(t2.flduserid,'.',' ')) as Dispensed_By,
                            t2.fldbillno as BillNo,
                            DATE_FORMAT(pd.fldtime, '%Y-%m-%d') as DateTime,
                            UNIX_TIMESTAMP(pd.fldtime) AS UnixTime,
                            pd.fldtime as FullDate
                            from
                            tblpatdosing pd
                            inner join tblmedbrand t on
                            t.fldbrandid = pd.flditem
                            inner JOIN tblpatbilling t2 on
                            t2.fldparent = pd.fldid
                            left join tblpatbilldetail as pbd on
                                pbd.fldbillno = t2.fldbillno
                            INNER join users u on
                            u.username = pd.fldconsultant
                            INNER join tblencounter on
                            tblencounter.fldencounterval = pd.fldencounterval
                            left join tblentry as entry on 
                                pd.fldstockno = entry.fldstockno 
                            left join tblpurchase as extryToPurchase on 
                                extryToPurchase.fldstockno = entry.fldstockno 
                            left join tbltransfer as transfer on 
                                transfer.fldstockno = entry.fldstockno 
                           left join tblpurchase as transferToPurchase on 
                                transferToPurchase.fldstockno = transfer.fldoldstockno 
                            INNER join tblpatientinfo as pt on
                            pt.fldpatientval = tblencounter.fldpatientval
                            where
                            cast(pd.fldtime as date) >= '".$fromdateeng . "'
                            and cast(pd.fldtime as date) <= '". $todateeng ."'
                            and pd.fldlevel  = 'Dispensed'
                            and pd.fldsave = '1'
                            and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
                            and t2.flditemqty - t2.fldretqty > 0
                            and t2.fldbillno like 'PHM%'
                            ".$medicinesql."
                           /* Group by name, Supplier_name, DateTime */ 
                           Group by med, Supplier_name, name , DateTime, Batch_no,Prescribed_By
                           /* order by DateTime */
                           order by UnixTime
            "));
            $data['request'] = $request ;


                return view('reports::pdf.narcotic-sales-report-new-pdf', array('result'=>$result,'userid'=>$userid,'fromdateeng'=>$fromdateeng,'todateeng'=>$todateeng, 'medicine' => $medicine, 'request' => $request ))->render();
            }catch(\Exception $e){
                dd($e);
            }
        }
 
    }

    public function bkexportExcel(Request $request){

        $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
        $todateeng = Helpers::dateNepToEng($request->todate)->full_date;

        $export = new NarcoticSalesExport($fromdateeng,$todateeng);
        ob_end_clean();
        ob_start();
        
        return Excel::download($export, 'NarcoticSalesExport.xlsx');

    }

    public function exportExcel(Request $request){

        $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
        $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
        $medicine = $request->medicine_name ;

        $export = new NarcoticSalesExport($fromdateeng,$todateeng,$medicine, $request );
        // ob_end_clean();
        // ob_start();
        
        return Excel::download($export, 'NarcoticSalesExport.xlsx');
        $response =  array(
            'name' => "NarcoticSalesExport.xlsx'", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($export) //mime type of used format
            // 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
         );
         
         return response()->json($response);

    }

}