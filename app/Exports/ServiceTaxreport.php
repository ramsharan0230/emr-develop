<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

use Illuminate\Support\Facades\DB;
use App\Utils\Helpers;
use App\HospitalDepartment;

class ServiceTaxReport implements FromView,ShouldAutoSize
{

    public function __construct(String $finalfrom, String $todate,String $deptcomp){
        $this->finalfrom = $finalfrom;
        $this->todate = $todate;
        $this->deptcomp = $deptcomp;

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $fromdate = $this->finalfrom;
        $todate = $this->todate;
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $deptcomp = $this->deptcomp;
        $deptname = HospitalDepartment::where('fldcomp',$deptcomp)->pluck('name')->first();

        $nepdatefrom = Helpers::dateEngToNepdash($fromdate)->full_date;
        $nepdateto = Helpers::dateEngToNepdash($todate)->full_date;

        try{


            $html = "";

            $totalgross = 0;
            $totaldiscount = 0;
            $totalsubtotal = 0;
            $totaltaxable = 0;
            $totalnontaxable = 0;
            $totaltaxamt = 0;
            $totalnet_total = 0;


            $begin = new \DateTime( $fromdate );
            $end   = new \DateTime( $todate );



            for($i = $begin; $i <= $end; $i->modify('+1 day')){

               $result = DB::select(DB::raw("SELECT
               cast(fldtime as date) as date1,
               sum(flditemrate * flditemqty) as gross,
               sum(flddiscamt) as discount,
               sum((fldditemamt)-fldtaxamt) as subtotal,
            sum(case
                when fldtaxamt > 0 Then
                (flditemrate * flditemqty) else 0
                end) as taxable,
                    sum(case
                when fldtaxamt = 0 Then
                (flditemrate * flditemqty) else 0
                end) as nontaxable,
                    sum(fldtaxamt) as taxamt,
                    sum((fldditemamt)) as net_total
                from
                    tblpatbilling t
                where
                    (fldbillno like 'REG%'
                or fldbillno  like 'CAS%'
                or fldbillno like 'PHM%'
                or fldbillno  like 'CRE%')
                and fldsave = '1'
                and fldcomp = '" . $deptcomp . "'
                and cast(fldtime as date) ='" . $i->format("Y-m-d") . "' and cast(fldtime as date) = '" . $i->format("Y-m-d") . "'
                group by cast(fldtime as date)"));


                $resultret = DB::select(DB::raw("SELECT
                cast(fldtime as date) as date1,
                sum(flditemrate * flditemqty) as gross,
                sum(flddiscamt) as discount,
                sum((flditemrate * flditemqty)-(flddiscamt)) as subtotal,
                sum(case
                    when fldtaxamt != 0 Then
                    flditemrate * flditemqty else 0
                    end) as taxable,
                        sum(case
                    when fldtaxamt = 0 Then
                    (flditemrate * flditemqty) else 0
                    end) as nontaxable,
                        sum(fldtaxamt) as taxamt,
                        sum((flditemrate * flditemqty)-(flddiscamt)+(fldtaxamt)) as net_total
                    from
                        tblpatbilling t
                    where
                        (fldbillno like 'RET%')
                    and fldsave = '1'
                    and fldcomp = '" . $deptcomp . "'
                    and cast(fldtime as date) ='" . $i->format("Y-m-d") . "' and cast(fldtime as date) = '" . $i->format("Y-m-d") . "'
                    group by cast(fldtime as date)"));


                if(($result)){

                    if($result){

                        $nepdate = Helpers::dateEngToNepdash($i->format("Y-m-d"))->full_date;

                        $resbill = \DB::table('tblpatbilling')
                                 ->where('fldtime',">=", $i->format("Y-m-d"). " 00:00:00")
                                 ->where('fldtime',"<=", $i->format("Y-m-d"). " 23:59:59.999")
                                 ->where(function ($query) {
                                    $query->where('fldbillno', 'like', 'CAS%')
                                          ->orWhere('fldbillno', 'like', 'PHM%');
                                 })
                                 ->where('fldcomp',$deptcomp)
                                 ->selectRaw('min(fldbillno) as billfirst')
                                 ->selectRaw('max(fldbillno) as billlast')
                                 ->selectRaw('count(distinct(fldbillno)) as qty')
                                 ->get();

                        $resbillreg = \DB::table('tblpatbilling')
                        ->where('fldtime',">=", $i->format("Y-m-d"). " 00:00:00")
                        ->where('fldtime',"<=", $i->format("Y-m-d"). " 23:59:59.999")
                        ->where('fldbillno','like','REG%')
                        ->where('fldcomp',$deptcomp)
                        ->selectRaw('min(fldbillno) as billfirst')
                        ->selectRaw('max(fldbillno) as billlast')
                        ->selectRaw('count(distinct(fldbillno)) as qty')
                        ->get();

                        if($resbillreg[0]->billfirst != Null){

                        $html .="<tr><td colspan=\"10\">". $nepdate ." </td></tr>";
                        $html .= "<tr><td rowspan=\"2\">CASH</td>";
                        $html .= "<td>" . $resbill[0]->billfirst . "</td>";
                        $html .= "<td>" . $resbill[0]->billlast . " (" . $resbill[0]->qty . ")</td>";
                        $html .= "<td rowspan=\"2\">" . \App\Utils\Helpers::numberFormat($result[0]->gross) . "</td>";
                        $html .= "<td rowspan=\"2\">" . \App\Utils\Helpers::numberFormat($result[0]->discount) . "</td>";
                        $html .= "<td rowspan=\"2\">" . \App\Utils\Helpers::numberFormat($result[0]->subtotal) . "</td>";
                        $html .= "<td rowspan=\"2\">" . \App\Utils\Helpers::numberFormat($result[0]->taxable) . "</td>";
                        $html .= "<td rowspan=\"2\">" . \App\Utils\Helpers::numberFormat($result[0]->nontaxable) . "</td>";
                        $html .= "<td rowspan=\"2\">" . \App\Utils\Helpers::numberFormat($result[0]->taxamt) . "</td>";
                        $html .= "<td rowspan=\"2\">" . \App\Utils\Helpers::numberFormat($result[0]->net_total) . "</td></tr>";

                        $html .= "<tr><td>" . $resbillreg[0]->billfirst . "</td>";
                        $html .= "<td>" . $resbillreg[0]->billlast . " (" . $resbillreg[0]->qty . ")</td></tr>";

                    }else{
                        $html .="<tr><td colspan=\"10\">". $nepdate ." </td></tr>";
                        $html .= "<tr><td>CASH</td>";
                        $html .= "<td>" . $resbill[0]->billfirst . "</td>";
                        $html .= "<td>" . $resbill[0]->billlast . " (" . $resbill[0]->qty . ")</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($result[0]->gross) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($result[0]->discount) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($result[0]->subtotal) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($result[0]->taxable) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($result[0]->nontaxable) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($result[0]->taxamt) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($result[0]->net_total) . "</td></tr>";
                    }



                        $gross = $result[0]->gross;
                        $discount = $result[0]->discount;
                        $subtotal = $result[0]->subtotal;
                        $taxable = $result[0]->taxable;
                        $nontaxable = $result[0]->nontaxable;
                        $taxamt = $result[0]->taxamt;
                        $net_total = $result[0]->net_total;

                    }

                    if($resultret){

                        $resbillret = \DB::table('tblpatbilling')
                                 ->where('fldtime',">=", $i->format("Y-m-d"). " 00:00:00")
                                 ->where('fldtime',"<=", $i->format("Y-m-d"). " 23:59:59.999")
                                 ->where('fldbillno','like','RET%')
                                 ->where('fldcomp',$deptcomp)
                                 ->selectRaw('min(fldbillno) as billfirst')
                                 ->selectRaw('max(fldbillno) as billlast')
                                 ->selectRaw('count(distinct(fldbillno)) as qty')
                                 ->get();

                        $html .= "<tr><td>REFUND</td>";
                        $html .= "<td>" . $resbillret[0]->billfirst . "</td>";
                        $html .= "<td>" . $resbillret[0]->billlast . " (" . $resbillret[0]->qty . ")</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($resultret[0]->gross) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($resultret[0]->discount) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($resultret[0]->subtotal) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($resultret[0]->taxable) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($resultret[0]->nontaxable) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($resultret[0]->taxamt) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($resultret[0]->net_total) . "</td></tr>";

                        $gross += $resultret[0]->gross;
                        $discount += $resultret[0]->discount;
                        $subtotal += $resultret[0]->subtotal;
                        $taxable += $resultret[0]->taxable;
                        $nontaxable += $resultret[0]->nontaxable;
                        $taxamt += $resultret[0]->taxamt;
                        $net_total += $resultret[0]->net_total;



                    }

                        $html .= "<tr><td colspan=\"3\">TOTAL</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($gross) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($discount) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($subtotal) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($taxable) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($nontaxable) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($taxamt) . "</td>";
                        $html .= "<td>" . \App\Utils\Helpers::numberFormat($net_total) . "</td></tr>";


                        $totalgross += $gross;
                        $totaldiscount += $discount;
                        $totalsubtotal += $subtotal;
                        $totaltaxable += $taxable;
                        $totalnontaxable += $nontaxable;
                        $totaltaxamt += $taxamt;
                        $totalnet_total += $net_total;



                }

            }

            $html .= "<tr><td colspan=\"3\"><b>GRAND TOTAL</b></td>";
            $html .= "<td><b>" . \App\Utils\Helpers::numberFormat($totalgross) . "</b></td>";
            $html .= "<td><b>" . \App\Utils\Helpers::numberFormat($totaldiscount) . "</b></td>";
            $html .= "<td><b>" . \App\Utils\Helpers::numberFormat($totalsubtotal) . "</b></td>";
            $html .= "<td><b>" . \App\Utils\Helpers::numberFormat($totaltaxable) . "</b></td>";
            $html .= "<td><b>" . \App\Utils\Helpers::numberFormat($totalnontaxable) . "</b></td>";
            $html .= "<td><b>" . \App\Utils\Helpers::numberFormat($totaltaxamt) . "</b></td>";
            $html .= "<td><b>" . \App\Utils\Helpers::numberFormat($totalnet_total) . "</b></td></tr>";


        }catch(\Exception $e){
            dd($e);
        }

        return view('billing::pdf.service-tax-report-pdf',array('html'=>$html,'fromdateeng'=>$fromdate,'todateeng'=>$todate, 'fromdatenep'=>$nepdatefrom, 'todatenep'=>$nepdateto,'userid'=>$userid, 'department'=>$deptname));


    }
}
