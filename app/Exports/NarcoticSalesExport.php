<?php

namespace App\Exports;

use App\Utils\Options;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class NarcoticSalesExport implements FromView,ShouldAutoSize
{
    

    public function __construct(String $finalfrom, String $todate, $medicine = null, $request)
    {
        $this->finalfrom = $finalfrom;
        $this->todate =  $todate;
        $this->medicine = $medicine ;
        $this->request = $request ;

    }

    // public function drawings()
    // {
    //     if(Options::get('brand_image')){
    //         if(file_exists(public_path('uploads/config/'.Options::get('brand_image')))){
    //             $drawing = new Drawing();
    //             $drawing->setName(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'');
    //             $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'');
    //             $drawing->setPath(public_path('uploads/config/'.Options::get('brand_image')));
    //             $drawing->setHeight(80);
    //             $drawing->setCoordinates('B2');
    //         }else{
    //             $drawing = [];
    //         }
    //     }else{
    //         $drawing = [];
    //     }
    //     return $drawing;
    // }
 
    public function bkview(): View
    {
        $todate = $this->todate;
        $finalfrom = $this->finalfrom;
        $request = $this->request ;

        $userid = \Auth::guard('admin_frontend')->user()->flduserid;

        $result = DB::select(DB::raw("select
            pd.fldencounterval as enc,
            CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
            round(Datediff(Now(),fldptbirday)/365,0) as age,
            pd.flditem as med,
            t2.flditemqty-t2.fldretqty as qty,
            pd.flddose as dose,
            pd.fldfreq as freq,
            Upper(CONCAT(u.firstname,' ',u.lastname)) as Prescribed_By,
            pd.fldregno as regno,
            Upper(Replace(t2.flduserid,'.',' ')) as Dispensed_By ,
            t2.fldbillno as BillNo ,
            pd.fldtime as DateTime ,
            UNIX_TIMESTAMP(pd.fldtime) AS UnixTime,
            pd.fldtime as FullDate
            from
            tblpatdosing pd
            inner join tblmedbrand t on
            t.fldbrandid = pd.flditem
            inner JOIN tblpatbilling t2 on
            t2.fldparent = pd.fldid
            INNER join users u on
            u.username = pd.fldconsultant
            INNER join tblencounter on
            tblencounter.fldencounterval = pd.fldencounterval
            INNER join tblpatientinfo as pt on
            pt.fldpatientval = tblencounter.fldpatientval
            where
            cast(pd.fldtime as date) >= '". $finalfrom . "'
            and cast(pd.fldtime as date) <= '"  . $todate ."'
            and pd.fldlevel  = 'Dispensed'
            and pd.fldsave = '1'
            and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
            and t2.flditemqty - t2.fldretqty > 0
            and t2.fldbillno like 'PHM%'"));


        return view('reports::pdf.narcotic-sales-report-pdf',array('result' => $result,'fromdateeng' => $finalfrom, 'todateeng' => $todate, 'userid' => $userid)); 
    }

    public function view(): View
    {
        $todateeng = $this->todate;
        $fromdateeng = $this->finalfrom;
        $medicine =  $this->medicine ;
        $request = $this->request ;
        // dd($medicine);
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;

        // $result =  
            // (!is_null($medicine)) ? 
        //     DB::select(DB::raw("select
    
        //     entry.fldexpiry as Expiry_date,
        //     entry.fldbatch as Batch_no,
        //     sum(entry.fldqty) as Quantity,
        //     (CASE tblpurchase.fldtotalqty 
        //         WHEN  tblpurchase.fldtotalqty IS Null
        //         THEN sum(transferPurchase.fldtotalqty)
        //         Else sum(tblpurchase.fldtotalqty)
        //     END) as Quantity_receive,
        //     (CASE tblpurchase.fldsuppname
        //             WHEN  null
        //             THEN transferPurchase.fldsuppname
        //             Else tblpurchase.fldsuppname
        //         END) as Supplier_name,
        //     pbd.remarks as remarks,
        //     pd.fldencounterval as enc,
        //     CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
        //     round(Datediff(Now(),fldptbirday)/365,0) as age,
        //     pd.flditem as med,
        //     t2.flditemqty-t2.fldretqty as qty,
        //     pd.flddose as dose,
        //     pd.fldfreq as freq,
        //     UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
        //     pd.fldregno as regno,
        //     UPPER(REPLACE(t2.flduserid,'.',' ')) as Dispensed_By,
        //     t2.fldbillno as BillNo,
        //     DATE_FORMAT(pd.fldtime, '%Y-%m-%d') as DateTime
        //     from
        //     tblpatdosing pd
        //     inner join tblmedbrand t on
        //     t.fldbrandid = pd.flditem
        //     inner JOIN tblpatbilling t2 on
        //     t2.fldparent = pd.fldid
        //     left join tblpatbilldetail as pbd on
        //         pbd.fldbillno = t2.fldbillno
        //     INNER join users u on
        //     u.username = pd.fldconsultant
        //     INNER join tblencounter on
        //     tblencounter.fldencounterval = pd.fldencounterval
        //     INNER join tblentry as entry on 
        //         entry.fldstockno  = pd.fldstockno
        //     Left join tblpurchase on 
        //         tblpurchase.fldstockno = entry.fldstockno
        //     Left join tbltransfer as transfer on 
        //         transfer.fldstockno = entry.fldstockno
        //     Left join tblpurchase as transferPurchase on 
        //         tblpurchase.fldstockno = transfer.fldoldstockno
        //     INNER join tblpatientinfo as pt on
        //     pt.fldpatientval = tblencounter.fldpatientval
        //     where
        //     cast(pd.fldtime as date) >= '".$finalfrom."'
        //     and cast(pd.fldtime as date) <= '".$todate."'
        //     and pd.fldlevel  = 'Dispensed'
        //     and pd.fldsave = '1'
        //     and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
        //     and t2.flditemqty - t2.fldretqty > 0
        //     and t2.fldbillno like 'PHM%'
        //     and  pd.flditem = :medicine
        //     Group by Supplier_name, DateTime
        // "),['medicine' => $medicine]);
        // :
        // DB::select(DB::raw("select
        //     entry.fldexpiry as Expiry_date,
        //     entry.fldbatch as Batch_no,
        //     sum(entry.fldqty) as Quantity,
        //     (CASE tblpurchase.fldtotalqty 
        //         WHEN  tblpurchase.fldtotalqty IS Null
        //         THEN sum(transferPurchase.fldtotalqty)
        //         Else sum(tblpurchase.fldtotalqty)
        //     END) as Quantity_receive,
        //     (CASE tblpurchase.fldsuppname
        //             WHEN  null
        //             THEN transferPurchase.fldsuppname
        //             Else tblpurchase.fldsuppname
        //         END) as Supplier_name,
        //     pbd.remarks as remarks,
        //     pd.fldencounterval as enc,
        //     CONCAT(pt.fldptnamefir,' ',pt.fldptnamelast) as name,
        //     round(Datediff(Now(),fldptbirday)/365,0) as age,
        //     pd.flditem as med,
        //     t2.flditemqty-t2.fldretqty as qty,
        //     pd.flddose as dose,
        //     pd.fldfreq as freq,
        //     UPPER(CONCAT(u.firstname, ' ', u.lastname)) as Prescribed_By,
        //     pd.fldregno as regno,
        //     UPPER(REPLACE(t2.flduserid,'.',' ')) as Dispensed_By,
        //     t2.fldbillno as BillNo,
        //     DATE_FORMAT(pd.fldtime, '%Y-%m-%d') as DateTime
        //     from
        //     tblpatdosing pd
        //     inner join tblmedbrand t on
        //     t.fldbrandid = pd.flditem
        //     inner JOIN tblpatbilling t2 on
        //     t2.fldparent = pd.fldid
        //     left join tblpatbilldetail as pbd on
        //         pbd.fldbillno = t2.fldbillno
        //     INNER join users u on
        //     u.username = pd.fldconsultant
        //     INNER join tblencounter on
        //     tblencounter.fldencounterval = pd.fldencounterval
        //     INNER join tblentry as entry on 
        //         entry.fldstockno  = pd.fldstockno
        //     Left join tblpurchase on 
        //         tblpurchase.fldstockno = entry.fldstockno
        //     Left join tbltransfer as transfer on 
        //         transfer.fldstockno = entry.fldstockno
        //     Left join tblpurchase as transferPurchase on 
        //         tblpurchase.fldstockno = transfer.fldoldstockno
        //     INNER join tblpatientinfo as pt on
        //     pt.fldpatientval = tblencounter.fldpatientval
        //     where
        //     cast(pd.fldtime as date) >= '".$finalfrom."'
        //     and cast(pd.fldtime as date) <= '".$todate ."'
        //     and pd.fldlevel  = 'Dispensed'
        //     and pd.fldsave = '1'
        //     and (t.fldnarcotic = 'Yes' or pd.fldconsultant is not Null)
        //     and t2.flditemqty - t2.fldretqty > 0
        //     and t2.fldbillno like 'PHM%'
        //     Group by Supplier_name, DateTime
        // ")) ;

        // dd($result);


        // $result =  (!is_null($medicine)) ? 
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
                            UNIX_TIMESTAMP(pd.fldtime) AS UnixTime
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
        return view('reports::excel.narcotic-sales-report-excel',['result' => $result,'fromdateeng' => $fromdateeng, 'todateeng' => $todateeng, 'userid' => $userid , 'medicine' => $medicine, 'request' =>  $request ] ); 
    }
}

