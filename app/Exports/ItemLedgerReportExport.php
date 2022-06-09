<?php

namespace App\Exports;

use App\Utils\Options;
use App\Year;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ItemLedgerReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(array $filterdata)
    {
        $this->filterdata = $filterdata;
    }

    public function drawings()
    {
        if(Options::get('brand_image')){
            if(file_exists(public_path('uploads/config/'.Options::get('brand_image')))){
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'');
                $drawing->setPath(public_path('uploads/config/'.Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
            }else{
                $drawing = [];
            }
        }else{
            $drawing = [];
        }
        return $drawing;
    }

    public function view(): View
    {
        $filterdata = $this->filterdata;
        $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst','<=',$filterdata['eng_from_date'])->where('fldlast','>=',$filterdata['eng_from_date'])->first();

            $day_before = date( 'Y-m-d', strtotime( $filterdata['eng_from_date'] . ' -1 day' ) );

            /* Closing */
	    $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst','<=',$filterdata['eng_from_date'])->where('fldlast','>=',$filterdata['eng_from_date'])->first();

	    $day_before = date( 'Y-m-d', strtotime( $filterdata['eng_from_date'] . ' -1 day' ) );

	    $opening_sql="with cte as (
select @a:=@a+1 serial_number,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
              select fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty + IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where  fldstockid= '".$filterdata['search_medecine'] ."'and (cast(fldtime as date) >='".$fiscal_year->fldfirst."' ) and (cast(fldtime as date) < '".$filterdata['eng_from_date']."') and fldcomp= '".$filterdata['department'] ."' Group by fldreference,cast(fldtime as date) union ALL
            select fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn where fldstockid= '".$filterdata['search_medecine'] ."' and (cast(fldtime as date) >= '".$fiscal_year->fldfirst."' ) and (cast(fldtime as date) < '".$filterdata['eng_from_date']."') and fldcomp= '".$filterdata['department']."' Group by fldreference,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockid='".$filterdata['search_medecine']."'and (cast(fldtoentrytime as date) >='".$fiscal_year->fldfirst."' ) and (cast(fldtoentrytime as date) < '".$filterdata['eng_to_date']."') and fldfromcomp='".$filterdata['department']."' Group by fldreference,cast(fldtoentrytime as date)  union ALL
     select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(fldqty) as purqty,0 as retQty,+sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockid='".$filterdata['search_medecine']."'and (cast(fldtoentrytime as date) >='".$fiscal_year->fldfirst."' ) and (cast(fldtoentrytime as date) < '".$filterdata['eng_to_date']."') and fldtocomp='".$filterdata['department']."' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale where fldqtydisp is not null and fldstockid='".$filterdata['search_medecine']."'and (cast(fldtime as date) >='".$fiscal_year->fldfirst."' ) and (cast(fldtime as date) < '".$filterdata['eng_to_date']."') and fldcomp='".$filterdata['department']."' Group by fldreference,cast(fldtime as date) union ALL
                        select  fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling where flditemqty is not null and flditemname='" . $filterdata['search_medecine'] . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $filterdata['eng_to_date']. "') and fldcomp='" . $filterdata['department'] . "' Group by fldbillno,cast(fldtime as date) union ALL

        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment where fldcurrqty is not null and fldstockid='".$filterdata['search_medecine']."'and (cast(fldtime as date) >='".$fiscal_year->fldfirst."' ) and (cast(fldtime as date) < '".$filterdata['eng_to_date']."') and fldcomp='".$filterdata['department']."' Group by fldreference,cast(fldtime as date)
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

	    $opening_sql =\DB::select($opening_sql);
	    $initialBalQty = 0;
	    if(isset($opening_sql[0])){
		    $initialBalQty = $opening_sql[0]->BalanceQty;
	    }



	    $calculation_sql="select T.fldbatch as batch,T.description as description,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
        select fldbatch,concat('Pur from ',fldsuppname,':',fldbillno) as description,fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty+IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where fldtotalqty is not null and fldstockid='".$filterdata['search_medecine']."' and (cast(fldtime as date) between '".$filterdata['eng_from_date']."' and '".$filterdata['eng_to_date']."') and fldcomp='".$filterdata['department']."' and fldsav=0 Group by fldreference,cast(fldtime as date) union ALL
        select fldbatch,concat('Returned from:',hospital_departments.name)as description, fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldnewreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn join hospital_departments   on hospital_departments.fldcomp=tblstockreturn.fldcomp where fldqty is not null and fldstockid='".$filterdata['search_medecine']."' and (cast(fldtime as date) between '".$filterdata['eng_from_date']."' and '".$filterdata['eng_to_date']."') and tblstockreturn.fldcomp='".$filterdata['department']."' Group by fldreference,cast(fldtime as date)  union ALL
        select tblentry.fldbatch as fldbatch,concat('Transfer to:',hospital_departments.name) as description, fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(tbltransfer.fldqty) as retQty,-sum(tbltransfer.fldqty) as tempQty from tbltransfer  join tblentry   on tblentry.fldstockno=tbltransfer.fldstockno join hospital_departments   on hospital_departments.fldcomp=tbltransfer.fldtocomp where tbltransfer.fldqty is not null and tbltransfer.fldstockid='".$filterdata['search_medecine']."' and (cast(fldtoentrytime as date) between '".$filterdata['eng_from_date']."' and '".$filterdata['eng_to_date']."') and fldfromcomp='".$filterdata['department']."' Group by tbltransfer.fldreference,cast(tbltransfer.fldtoentrytime as date)  union ALL
         select tblentry.fldbatch as fldbatch,concat('Transfer From:',hospital_departments.name) as description, fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(tbltransfer.fldqty)  as purqty,0 as retQty,+sum(tbltransfer.fldqty) as tempQty from tbltransfer  join tblentry   on tblentry.fldstockno=tbltransfer.fldstockno join hospital_departments   on hospital_departments.fldcomp=tbltransfer.fldfromcomp where tbltransfer.fldqty is not null and tbltransfer.fldstockid='".$filterdata['search_medecine']."' and (cast(fldtoentrytime as date) between '".$filterdata['eng_from_date']."' and '".$filterdata['eng_to_date']."') and fldtocomp='".$filterdata['department']."' Group by tbltransfer.fldreference,cast(tbltransfer.fldtoentrytime as date)  union ALL
        select tblentry.fldbatch as fldbatch,concat('Bulk sale to ',fldtarget) as description, fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale  join tblentry   on tblentry.fldstockno=tblbulksale.fldstockno where fldqtydisp is not null and tblbulksale.fldstockid='".$filterdata['search_medecine']."' and (cast(fldtime as date) between '".$filterdata['eng_from_date']."' and '".$filterdata['eng_to_date']."') and tblbulksale.fldcomp='".$filterdata['department']."' Group by tblbulksale.fldreference,cast(tblbulksale.fldtime as date) union ALL
                select  tblentry.fldbatch as fldbatch,concat('Dispense From:',hospital_departments.name) as description,fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling join tblentry   on tblentry.fldstockno=tblpatbilling.flditemno join hospital_departments   on hospital_departments.fldcomp=tblpatbilling.fldcomp where flditemqty is not null and flditemname='" . $filterdata['search_medecine']. "'and (cast(fldtime as date) between '" . $filterdata['eng_from_date'] . "'  and '" . $filterdata['eng_to_date'] . "') and tblpatbilling.fldcomp='" . $filterdata['department'] . "' Group by fldbillno,cast(fldtime as date) union ALL
        select tblentry.fldbatch as fldbatch,'Adjustment' as description, fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment join tblentry   on tblentry.fldstockno=tbladjustment.fldstockno where fldcurrqty is not null and tbladjustment.fldstockid='".$filterdata['search_medecine']."' and (cast(fldtime as date) between '".$filterdata['eng_from_date']."' and '".$filterdata['eng_to_date']."') and tbladjustment.fldcomp='".$filterdata['department']."'   Group by tbladjustment.fldreference,cast(tbladjustment.fldtime as date)
    ) as T
JOIN (SELECT @running_total:='".$initialBalQty."') r
ORDER BY T.datetime ASC";

	    $calculation_sql = \DB::select($calculation_sql);

	    $medicinename = $filterdata['search_medecine'];

	    $from = $filterdata['from_date'];
	    $to = $filterdata['to_date'];
        return view('reports::excel.item-ledger-report-excel', compact('calculation_sql','medicinename','from','to','opening_sql'));
    }

}
