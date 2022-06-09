<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\DB;
use App\Utils\Helpers;

use App\Exports\PurchaseVatREportsExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Routing\Redirector;

class VATReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        if($request->fromdate){

            $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
            $todateeng = Helpers::dateNepToEng($request->todate)->full_date;


            try{

                if($request->reporttype == '0'){


                    $result = DB::select(DB::raw("SELECT
                    cast(tblpurchasebill.fldpurdate as date) as fldpurdate,
                    tblpurchasebill.fldreference,
                    tblpurchasebill.fldbillno,
                    tblpurchasebill.fldsuppname,
                    case
                    when tblsupplier.fldvatno is not null then (tblsupplier.fldvatno) else tblsupplier.fldpanno
                                end as fldvatpan,
                    tblpurchasebill.nonvatableamount AS NonTaxableAmount,
                    -- (CASE
                    --     WHEN fldtotalvat != 0 or fldtotaltax != 0 or nonvatableamount = 0 THEN
                    --     (vatableamount- tblpurchasebill.fldlastdisc)
                    --     ELSE vatableamount
                    --     END) AS TaxableAmount,
                    vatableamount AS TaxableAmount,
                    -- tblpurchasebill.fldlastdisc as flddiscount,
                    -- add individual discount sum from tblpurchasebill
                    sum(tblpurchasebill.fldlastdisc + tblpurchasebill.flddiscounted) as flddiscount,

                    tblpurchasebill.fldcredit as Total_Amount,
                    tblpurchasebill.fldtotalvat,
                    sum( tblpurchasebill.fldtotaltax ) as Individual_Tax,
                    sum(fldtotaltax + fldtotalvat) as VATAMT,
                    -- tblpurchasebill.fldtotalvat as VATAMT,
                    case when
                    tblpurchasebill.fldtotaltax !=0 then
                    (tblpurchasebill.fldcredit - tblpurchasebill.fldlastdisc  + (tblpurchasebill.cccharge))
                else
                ((tblpurchasebill.fldcredit) - (tblpurchasebill.fldlastdisc) + (tblpurchasebill.fldtotalvat) + (tblpurchasebill.cccharge)) End As NetAmt,
                    case
                              WHEN tblpurchasebill.fldreference = (
                              SELECT
                                  fldreference
                              from
                                  tblstockreturn
                              where
                                  tblstockreturn.fldreference = tblpurchasebill.fldreference
                              limit 1) Then 'Purchase Return'
                              else ''
                          END AS itemreturn
                    FROM
                    tblpurchasebill

                    JOIN tblsupplier ON tblpurchasebill.fldsuppname = tblsupplier.fldsuppname
                    where cast(tblpurchasebill.fldpurdate as date) >= '". $fromdateeng .
                    "' and cast(tblpurchasebill.fldpurdate as date) <= '" . $todateeng .
                    "' GROUP BY
                    tblpurchasebill.fldreference"));


                    $nontax = 0;
                    $tax = 0;
                    $discount = 0;
                    $totalamt = 0;
                    $vat = 0;
                    $nettotal = 0;
                    $discount1 = 0;
                    $tax1 = 0;

                    $html = '';
                    $header = '';

                    $header = ' <tr>

                            <th>S.N.</th>
                            <th>Date</th>
                            <th>Purchase Ref</th>
                            <th>Bill No.</th>
                            <th>Supp Name</th>
                            <th>PAN/VAT</th>
                            <th>Non Taxable (Exc. Dis)</th>
                            <th>Discount</th>
                            <th>Taxable</th>
                            <th>Sub Total</th>
                            <th>VAT Amt</th>
                            <th>Net Total</th>
                            <th>Remarks</th>

                        </tr>';

                    foreach ($result as $key => $results) {



                        if(($results->NonTaxableAmount) > 0){
                            $nontax +=  (($results->NonTaxableAmount));
                            $nontaxinv = (($results->NonTaxableAmount));

                        }else{
                            $nontax +=  ($results->NonTaxableAmount);
                            $nontaxinv = (($results->NonTaxableAmount));
                        }



                        if(($results->TaxableAmount) > 0){
                            $tax += (($results->TaxableAmount));
                            $taxinv = (($results->TaxableAmount));
                        }else{
                            $tax += ($results->TaxableAmount);
                            $taxinv = (($results->TaxableAmount));
                        }

                        $discount += ($results->flddiscount);

                        $discountinv = ($results->flddiscount);

                        // $totalamt += ($results->Total_Amount);

                        $subtotal = $nontaxinv + $taxinv - $discountinv;

                        $totalamt += $subtotal;

                        $vat += ($results->VATAMT);
                        // $nettotal += ($results->NetAmt);

                        $nettotalinv = $nontaxinv + $taxinv - $discountinv +  ($results->VATAMT);
                        $nettotal += $nettotalinv;

                        $key = $key + 1;

                        $html .= '<tr><td>' .$key . '</td>';
                        $html .= '<td>' . $results->fldpurdate . '</td>';
                        $html .= "<td>" . $results->fldreference . "</td>";
                        $html .= "<td>" . $results->fldbillno . "</td>";
                        $html .= "<td>" . $results->fldsuppname . "</td>";
                        $html .= "<td>" . $results->fldvatpan . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($results->NonTaxableAmount) ?? '0' . "</td>";
                        $html .= '<td>' . Helpers::numberFormat($results->flddiscount) ?? '0'. '</td>';

                        $html .= '<td>' . Helpers::numberFormat($results->TaxableAmount) ?? '0' . '</td>';

                        // $html .= '<td>' . ($results->Total_Amount) ?? '0'. '</td>';
                        $html .= '<td>' . Helpers::numberFormat($subtotal) ?? '0'. '</td>';

                        $html .= '<td>' . Helpers::numberFormat($results->VATAMT) ?? '0'. '</td>';

                        // $html .= '<td>' . ($results->NetAmt) ?? '0'. '</td>';
                        $html .= '<td>' . Helpers::numberFormat($nettotalinv) ?? '0'. '</td>';

                        $html .= '<td>' . $results->itemreturn . '</td>';

                        $html .= '</tr>';
                    }

                    $html .= '<tr><td colspan="6" style="text-align:center"><b>Total </b></td>';
                    $html .= '<td><b>'.Helpers::numberFormat($nontax).'</b></td>';
                    $html .= '<td><b>'.Helpers::numberFormat($discount).'</b></td>';
                    $html .= '<td><b>'.Helpers::numberFormat($tax).'</b></td>';
                    $html .= '<td><b>'.Helpers::numberFormat($totalamt).'</b></td>';
                    $html .= '<td><b>'.Helpers::numberFormat($vat).'</b></td>';
                    $html .= '<td><b>'.Helpers::numberFormat($nettotal).'</b></td>';
                    $html .= '<td></td></tr>';

                }elseif($request->reporttype == '1'){


                    \DB::enableQueryLog();
                    $result = DB::select(DB::raw("SELECT (case when nonvatableamount > 0 then
                    (nonvatableamount-tblpurchasebill.fldlastdisc-tblpurchasebill.flddiscounted)
                    else nonvatableamount end) AS NonTaxableAmount,
                    (CASE
                    WHEN nonvatableamount = 0 THEN
                    (vatableamount-tblpurchasebill.fldlastdisc-tblpurchasebill.flddiscounted)
                    ELSE vatableamount
                    END) AS TaxableAmount, (fldtotaltax + fldtotalvat) as VATAMT,
                    (cccharge) as cccharge
                    from
                    tblpurchasebill
                    where cast(tblpurchasebill.fldpurdate as date) >= '" . $fromdateeng .
                    "' and cast(tblpurchasebill.fldpurdate as date) <= '" . $todateeng . "'"));



                    if(($result)){
                        $nontax = 0;
                        $tax = 0;
                        $discount = 0;
                        $totalamt = 0;
                        $vat = 0;
                        $nettotal = 0;
                        $tax1 = 0;
                        $nontax1 = 0;
                        $vat1 = 0;

                        $html = '';
                        $header = '';

                        $header = ' <tr>


                                <th> Billing Range </th>
                                <th> Non-Taxable Amount (After Discount) </th>
                                <th> Taxable Amount (After Discount) </th>
                                <th> VAT Amount </th>
                                <th> CC Charge </th>
                                <th> Net Total </th>

                                </tr>';

                        $result1 = DB::select(DB::raw("SELECT fldreference from tblpurchase
                        where cast(fldtime as date) >= '" . $fromdateeng .
                        "' and cast(fldtime as date) <= '" . $todateeng . "'
                        and fldsav = '0'
                        order by fldtime ASC"
                        ));

                        if(empty($result1) == false){
                            $billnofirst = $result1[0]->fldreference;
                        }else{
                            $billnofirst = '';
                        }

                        $result2 = DB::select(DB::raw("SELECT fldreference from tblpurchase
                        where cast(fldtime as date) >= '" . $fromdateeng .
                        "' and cast(fldtime as date) <= '" . $todateeng . "'
                        and fldsav = '0'
                        order by fldtime DESC"
                        ));

                        if(empty($result2) == false){
                            $billnolast = $result2[0]->fldreference;
                        }else{
                            $billnolast = '';
                        }

                        foreach ($result as $key => $results) {

                            if($results->NonTaxableAmount > 0){
                                $nontax +=  ($results->NonTaxableAmount);
                                $nontax1 = ($results->NonTaxableAmount);
                            }else{
                                $nontax += 0;
                                $nontax1 = 0;
                            }
                            if($results->TaxableAmount > 0){
                                $tax += ($results->TaxableAmount);
                                $tax1 = ($results->TaxableAmount);
                            }else{
                                $tax += 0;
                                $tax1 = 0;
                            }

                            $vat += ($results->VATAMT);
                            $vat1 = ($results->VATAMT);

                            $cccharge = ($results->cccharge);

                            // $nettotal += $nontax1 + $tax1 + $vat1;
                            // $nettotal = $nontax + $tax + $vat + $cccharge;
                            $nettotal = $nontax + $tax + $vat;

                        }


                        $html .= '<td>' . $billnofirst . '-<br>' . $billnolast . '</td>';
                        // $html .= '<td>' . $results->fldreference . '</td>';
                        $html .= "<td>" . Helpers::numberFormat($nontax) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($tax) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($vat) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($cccharge) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($nettotal) . "</td>";
                        $html .= '</tr>';
                    }



                }elseif($request->reporttype == '2'){

                    $result = DB::select(DB::raw("SELECT
                    sum(fldtotaltax + fldtotalvat) as VATAMT
                    from
                    tblpurchasebill
                    where cast(tblpurchasebill.fldpurdate as date) >= '" . $fromdateeng .
                    "' and cast(tblpurchasebill.fldpurdate as date) <= '" . $todateeng . "'"
                    ));

                    $purvat = $result[0]->VATAMT;

                    $result = DB::select(DB::raw("SELECT
                        sum(fldtaxamt) as salesvat
                    from
                        tblpatbilling t
                    where
                        cast(fldtime as date) >='" . $fromdateeng .
                        "' and cast(fldtime as date) <= '". $todateeng .
                        "' and fldsave ='1'
                    and (flditemtype = 'Medicines' or flditemtype = 'Surgicals' or flditemtype = 'Extra Items')
                    "));

                    $salesvat = $result[0]->salesvat;
                    $vatdiff = $salesvat - $purvat;

                    $html = '';
                    $header = '';

                    $header = ' <tr>

                            <th> Total Purchase VAT </th>
                            <th> Total Sales VAT </th>
                            <th> VAT Difference </th>
                            </tr>';


                    $html .= '<tr>';
                    $html .= '<td>' . Helpers::numberFormat($purvat) . '</td>';
                    $html .= "<td>" . Helpers::numberFormat($salesvat) . "</td>";
                    $html .= "<td>" . Helpers::numberFormat($vatdiff) . "</td>";
                    $html .= '</tr>';



            }elseif($request->reporttype == '3'){

                $result = DB::select(DB::raw("SELECT
                sum(case when tblpatbilling.fldtaxamt = 0 then tblpatbilling.fldditemamt
            else '0'
            end) as NonTaxable,
                sum(CASE
            WHEN tblpatbilling.fldtaxamt > 0 THEN tblpatbilling.fldditemamt - tblpatbilling.fldtaxamt
            else '0'
            END) AS TaxableAmount,
                sum(tblpatbilling.fldtaxamt) as VAT
            from
                tblpatbilling
            where
                cast(fldtime as date) >= '" . $fromdateeng .
                "' and
            cast(fldtime as date) <= '" . $todateeng .
                "' and
            fldbillno like 'PHM%'
                and
            fldsave ='1'
                and
            (flditemtype = 'Medicines'
            or flditemtype = 'Surgicals'
            or flditemtype = 'Extra Items')"));


                $nontaxcash = ($result[0]->NonTaxable) ?? 0;
                $taxcash = ($result[0]->TaxableAmount) ?? 0;
                $vatcash = ($result[0]->VAT) ?? 0;

                $result = DB::select(DB::raw("SELECT
                sum(case when tblpatbilling.fldtaxamt = 0 then tblpatbilling.fldditemamt
            else '0'
            end) as NonTaxable,
                sum(CASE
            WHEN tblpatbilling.fldtaxamt != 0 THEN tblpatbilling.fldditemamt - tblpatbilling.fldtaxamt
            else '0'
            END) AS TaxableAmount,
                sum(tblpatbilling.fldtaxamt) as VAT
            from
                tblpatbilling
            where
                cast(fldtime as date) >= '" . $fromdateeng .
                "' and
            cast(fldtime as date) <= '" . $todateeng .
                "' and
            fldbillno like 'RET%'
                and
            fldsave ='1'
                and
                (flditemtype = 'Medicines'
                or flditemtype = 'Surgicals'
                or flditemtype = 'Extra Items')
                "));

                $nontaxret = ($result[0]->NonTaxable) ?? 0;
                $taxret = ($result[0]->TaxableAmount) ?? 0;
                $vatret = ($result[0]->VAT) ?? 0;

                $html = '';
                $header = '';

                $header = ' <tr>

                        <th> Bill Type </th>
                        <th> NonTaxable Amount (After Discount) </th>
                        <th> Taxable Amount (After Discount) </th>
                        <th> VAT </th>
                        <th> Net Total </th>
                        </tr>';

                $nettotalcash = $nontaxcash + $taxcash +$taxcash;
                $nettotalret = $nontaxret + $taxret +$taxret;

                $html .= '<tr>';
                $html .= '<td> CASH </td>';
                $html .= '<td>' . Helpers::numberFormat($nontaxcash) . '</td>';
                $html .= "<td>" . Helpers::numberFormat($taxcash) . "</td>";
                $html .= "<td>" . Helpers::numberFormat($vatcash) . "</td>";
                $html .= "<td>" . Helpers::numberFormat($nettotalcash) . "</td>";
                $html .= '</tr>';


                $html .= '<tr>';
                $html .= '<td> CASH RETURN </td>';
                $html .= '<td>' . Helpers::numberFormat($nontaxret) . '</td>';
                $html .= "<td>" . Helpers::numberFormat($taxret) . "</td>";
                $html .= "<td>" . Helpers::numberFormat($vatret) . "</td>";
                $html .= "<td>" . Helpers::numberFormat($nettotalret) . "</td>";
                $html .= '</tr>';

                // dd($nontaxret + $nontaxcash);
                $nontaxtotal = $nontaxret + $nontaxcash;
                $taxtotal = $taxret + $taxcash;
                $vattotal = $vatret + $vatcash;
                $nettotaltot = $nettotalret + $nettotalcash;

                $html .= '<tr>';
                $html .= '<td> TOTAL </td>';
                $html .= '<td>' . Helpers::numberFormat($nontaxtotal) . '</td>';
                $html .= "<td>" . Helpers::numberFormat($taxtotal) . "</td>";
                $html .= "<td>" . Helpers::numberFormat($vattotal) . "</td>";
                $html .= "<td>" . Helpers::numberFormat($nettotaltot) . "</td>";
                $html .= '</tr>';



        }
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                    'header' => $header
                ]
            ]);


        }catch(\Exception $e){
            dd($e);
        }
        }else{



            $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

            $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;

            return view('inventory::purchase-vat-report',array('date'=>$date));
        }


    }

    public function export(Request $request)
    {
        $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
        $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $reporttype = $request->reporttype;

        try{
            if($request->reporttype == '0'){
                $result = DB::select(DB::raw("SELECT
                cast(tblpurchasebill.fldpurdate as date) as fldpurdate,
                tblpurchasebill.fldreference,
                tblpurchasebill.fldbillno,
                tblpurchasebill.fldsuppname,
                case
                when tblsupplier.fldvatno is not null then (tblsupplier.fldvatno) else tblsupplier.fldpanno
                            end as fldvatpan,
                -- tblpurchasebill.nonvatableamount AS NonTaxableAmount,
                tblpurchasebill.nonvatableamount AS NonTaxableAmount,
                vatableamount AS TaxableAmount,
                sum(tblpurchasebill.fldlastdisc + tblpurchasebill.flddiscounted) as flddiscount,
                tblpurchasebill.fldcredit as Total_Amount,
                tblpurchasebill.fldtotalvat,
                sum( tblpurchasebill.fldtotaltax ) as Individual_Tax,
                sum(fldtotaltax + fldtotalvat) as VATAMT,
                -- tblpurchasebill.fldtotalvat as VATAMT,
                case when
                    tblpurchasebill.fldtotaltax !=0 then
                    (tblpurchasebill.fldcredit - tblpurchasebill.fldlastdisc  + (tblpurchasebill.cccharge))
                else
                ((tblpurchasebill.fldcredit) - (tblpurchasebill.fldlastdisc) + (tblpurchasebill.fldtotalvat) + (tblpurchasebill.cccharge)) End As NetAmt,
                case
                          WHEN tblpurchasebill.fldreference = (
                          SELECT
                              fldreference
                          from
                              tblstockreturn
                          where
                              tblstockreturn.fldreference = tblpurchasebill.fldreference
                          limit 1) Then 'Purchase Return'
                          else ''
                      END AS itemreturn
                FROM
                tblpurchasebill

                JOIN tblsupplier ON tblpurchasebill.fldsuppname = tblsupplier.fldsuppname
                where cast(tblpurchasebill.fldpurdate as date) >= '". $fromdateeng .
                "' and cast(tblpurchasebill.fldpurdate as date) <= '" . $todateeng .
                "' GROUP BY
                tblpurchasebill.fldreference"));

                $nontax = 0;
                $tax = 0;
                $discount = 0;
                $totalamt = 0;
                $vat = 0;
                $nettotal = 0;

                foreach($result as $results){
                    $nontax +=  ($results->NonTaxableAmount);
                    $tax += ($results->TaxableAmount);
                    $discount += ($results->flddiscount);
                    // $totalamt += ($results->Total_Amount);
                    // $totalamt += ($results->NonTaxableAmount) + ($results->Total_Amount) - ($results->flddiscount);
                    $totalamt += ($results->NonTaxableAmount) + ($results->TaxableAmount) - ($results->flddiscount);
                    $vat += ($results->VATAMT);

                    // $nettotal += ($results->NetAmt);
                    $nettotal += ($results->NonTaxableAmount) + ($results->TaxableAmount) - ($results->flddiscount) + ($results->VATAMT);
                }

                $totaldata = array('nontax'=>$nontax,
                                    'tax'=>$tax,
                                    'discount'=>$discount,
                                    'totalamt'=>$totalamt,
                                    'vat'=>$vat,
                                    'nettotal'=>$nettotal);



             return view('inventory::pdf.purchase-vat-pdf', array('result'=>$result,'userid'=>$userid,'totaldata'=>$totaldata,'fromdateeng'=>$fromdateeng,'todateeng'=>$todateeng,'reporttype'=>$reporttype));
            }elseif($request->reporttype == '1'){

                $result = DB::select(DB::raw("SELECT sum(case when nonvatableamount > 0 then nonvatableamount-tblpurchasebill.fldlastdisc-tblpurchasebill.flddiscounted
                else nonvatableamount end) AS NonTaxableAmount,
                sum(CASE
                WHEN nonvatableamount = 0 THEN
                -- WHEN fldtotalvat != 0 or fldtotaltax != 0 or nonvatableamount = 0 THEN
                (vatableamount-fldlastdisc-flddiscounted)
                ELSE vatableamount
                END) AS TaxableAmount, sum(fldtotaltax + fldtotalvat) as VATAMT,
                sum(cccharge) as cccharge
                from
                tblpurchasebill
                where cast(tblpurchasebill.fldpurdate as date) >= '" . $fromdateeng .
                "' and cast(tblpurchasebill.fldpurdate as date) <= '" . $todateeng . "'"));

                $nontax = 0;
                $tax = 0;
                $discount = 0;
                $totalamt = 0;
                $vat = 0;
                $nettotal = 0;

                $result1 = DB::select(DB::raw("SELECT fldreference from tblpurchase
                where fldsav = '0' and cast(fldtime as date) >= '" . $fromdateeng .
                "' and cast(fldtime as date) <= '" . $todateeng . "'
                 order by fldtime ASC"
                ));



                if(empty($result1) == false){
                    $billnofirst = $result1[0]->fldreference;
                }else{
                    $billnofirst = '';
                }


                $result2 = DB::select(DB::raw("SELECT fldreference from tblpurchase
                where fldsav = '0' and cast(fldtime as date) >= '" . $fromdateeng .
                "' and cast(fldtime as date) <= '" . $todateeng . "'
                 order by fldtime DESC"
                ));

                if(empty($result2) == false){
                    $billnolast = $result2[0]->fldreference;
                }else{
                    $billnolast = '';
                }

                foreach ($result as $key => $results) {

                    if($results->NonTaxableAmount){
                        $nontax +=  $results->NonTaxableAmount;
                    }else{
                        $nontax += 0;
                    }
                    if($results->TaxableAmount > 0){
                        $tax += $results->TaxableAmount;
                    }else{
                        $tax += 0;
                    }

                    $vat += $results->VATAMT;

                    $cccharge = $results->cccharge;

                    $nettotal += $nontax + $tax + $vat;

                }
                $totaldata = array('billnolast'=>$billnolast,
                                    'billnofirst'=>$billnofirst,
                                    'nontax'=>($nontax),
                                    'tax'=>($tax),
                                    'vat'=>($vat),
                                    'nettotal'=>($nettotal),
                                    'cccharge'=>$cccharge);
             return view('inventory::pdf.purchase-vat-pdf', array('result'=>$result,'userid'=>$userid,'totaldata'=>$totaldata,'fromdateeng'=>$fromdateeng,'todateeng'=>$todateeng,'reporttype'=>$reporttype,'cccharge'=>$cccharge));

            }elseif($request->reporttype == '2'){



                $result = DB::select(DB::raw("SELECT
                sum(fldtotaltax + fldtotalvat) as VATAMT
                from
                tblpurchasebill
                where cast(tblpurchasebill.fldpurdate as date) >= '" . $fromdateeng .
                "' and cast(tblpurchasebill.fldpurdate as date) <= '" . $todateeng . "'"
                ));

                $purvat = $result[0]->VATAMT;

                $result = DB::select(DB::raw("SELECT
                    sum(fldtaxamt) as salesvat
                from
                    tblpatbilling t
                where
                    cast(fldtime as date) >='" . $fromdateeng .
                    "' and cast(fldtime as date) <= '". $todateeng .
                    "' and fldsave ='1'
                and (flditemtype = 'Medicines' or flditemtype = 'Surgicals' or flditemtype = 'Extra Items')
                "));

                $salesvat = $result[0]->salesvat;
                $vatdiff = $salesvat - $purvat;

                $totaldata = array('purvat'=>($purvat),
                                    'salesvat'=>($salesvat),
                                    'vatdiff'=>($vatdiff));
            return view('inventory::pdf.purchase-vat-pdf', array('result'=>$result,'userid'=>$userid,'totaldata'=>$totaldata,'fromdateeng'=>$fromdateeng,'todateeng'=>$todateeng,'reporttype'=>$reporttype));


        }elseif($request->reporttype == '3'){

            $result = DB::select(DB::raw("SELECT
            sum(case when tblpatbilling.fldtaxamt = 0 then tblpatbilling.fldditemamt
        else '0'
        end) as NonTaxable,
            sum(CASE
        WHEN tblpatbilling.fldtaxamt > 0 THEN tblpatbilling.fldditemamt - tblpatbilling.fldtaxamt
        else '0'
        END) AS TaxableAmount,
            sum(tblpatbilling.fldtaxamt) as VAT
        from
            tblpatbilling
        where
            cast(fldtime as date) >= '" . $fromdateeng .
            "' and
        cast(fldtime as date) <= '" . $todateeng .
            "' and
        fldbillno like 'PHM%'
            and
            (flditemtype = 'Medicines'
            or flditemtype = 'Surgicals'
            or flditemtype = 'Extra Items')"
            ));

            $nontaxcash = ($result[0]->NonTaxable) ?? 0;
            $taxcash = ($result[0]->TaxableAmount) ?? 0;
            $vatcash = ($result[0]->VAT) ?? 0;

            $result = DB::select(DB::raw("SELECT
            sum(case when tblpatbilling.fldtaxamt = 0 then tblpatbilling.fldditemamt
        else '0'
        end) as NonTaxable,
            sum(CASE
        WHEN tblpatbilling.fldtaxamt != 0 THEN tblpatbilling.fldditemamt - tblpatbilling.fldtaxamt
        else '0'
        END) AS TaxableAmount,
            sum(tblpatbilling.fldtaxamt) as VAT
        from
            tblpatbilling
        where
            cast(fldtime as date) >= '" . $fromdateeng .
            "' and
        cast(fldtime as date) <= '" . $todateeng .
            "' and
        fldbillno like 'RET%'
            and
            (flditemtype = 'Medicines'
            or flditemtype = 'Surgicals'
            or flditemtype = 'Extra Items')
            "));

            $nontaxret = ($result[0]->NonTaxable) ?? 0;
            $taxret = ($result[0]->TaxableAmount) ?? 0;
            $vatret = ($result[0]->VAT) ?? 0;

            $nettotalcash = $nontaxcash + $taxcash +$taxcash;
            $nettotalret = $nontaxret + $taxret +$taxret;


            // dd($nontaxret + $nontaxcash);
            $nontaxtotal = $nontaxret + $nontaxcash;
            $taxtotal = $taxret + $taxcash;
            $vattotal = $vatret + $vatcash;
            $nettotaltot = $nettotalret + $nettotalcash;

            $totaldata = array('nontaxcash'=>$nontaxcash,
                                'taxcash'=>$taxcash,
                                'vatcash'=>$vatcash,
                                'nettotalcash'=>$nettotalcash,
                                'nontaxret'=>$nontaxret,
                                'taxret'=>$taxret,
                                'vatret'=>$vatret,
                                'nettotalret'=>$nettotalret,
                                'nontaxtotal'=>$nontaxtotal,
                                'taxtotal'=>$taxtotal,
                                'vattotal'=>$vattotal,
                                'nettotaltotal'=>$nettotaltot);

            return view('inventory::pdf.purchase-vat-pdf', array('result'=>$result,'userid'=>$userid,'totaldata'=>$totaldata,'fromdateeng'=>$fromdateeng,'todateeng'=>$todateeng,'reporttype'=>$reporttype));
        }
        }catch(\Exception $e){
            dd($e);
        }

    }
}
