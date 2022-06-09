<?php

namespace App\Exports;

use App\AccountGroup;
use App\TransactionView;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TrailBalanceExport implements FromView,WithDrawings,ShouldAutoSize
{

    public function __construct(string $from_date,string $to_date,string $eng_date,string $groupby, string $eng_to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->eng_date = $eng_date;
        $this->eng_to_date = $eng_to_date;
        $this->groupby = $groupby;
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
        $data['from_date'] = $from_date = $this->from_date;
        $data['to_date'] = $to_date= $this->to_date;
        $data['eng_from_date'] = $eng_from_date= $this->eng_date;
        $data['eng_to_date'] = $eng_to_date= $this->eng_to_date;
        $data['group_by'] = $groupby= $this->groupby;


        try {
            $openingdata = DB::table('transaction_view as t')
            ->select(DB::raw('SUM(TranAmount) as total'))
            ->where('t.TranDate', '<', $eng_from_date)
            ->first();

           $trailbal =  "SELECT
            sum(x.opening) as opening,
            sum(x.CR) as TCR,
            sum(x.DR) as TDR,
            g.GroupId,
            g.GroupName,
            al.AccountNo AS accountnumber,
            al.AccountNo,
            al.AccountName,
            al.GroupId AS accountGroupId,
            g.GroupTree
            FROM (
            SELECT SUM(TranAmount) as opening,0 as CR,0 as DR,AccountNo
            FROM transaction_view where TranDate<'".$eng_from_date."'
            GROUP BY accountno
            UNION
            SELECT 0 as opening,
            sum(case WHEN TranAmount<0 then TranAmount else 0 end) as CR,
            sum(case WHEN TranAmount>0 then TranAmount else 0 end) as DR,
            AccountNo
            FROM transaction_view
            WHERE
            transaction_view.TranDate >= '".$eng_from_date."'
            AND transaction_view.TranDate <= '".$eng_to_date."'
            GROUP BY accountno
            ) x
            JOIN account_ledger as al on x.AccountNo = al.AccountNo
            JOIN account_group AS g ON g.GroupId = al.GroupId
            GROUP BY
            al.AccountNo
            ORDER BY
            g.GroupTree ASC";
           $trialbalancedata= DB::select(
                $trailbal
            );


        $data['openingdata'] = $openingdata;
        $data['trialbalancedata'] = $trialbalancedata;
        $data['groupby'] = $groupby;

        $accountGroup = Cache::remember('account_group', Carbon::now()->addMinutes(60), function () {
            return AccountGroup::select('GroupId', 'GroupName', 'ReportId')->get();
        });

        $html = '';
        $totalopening = 0;
        $totalturnover = 0;
        $totalclosing = 0;
        $openingDrSubTotal = $openingCrSubTotal = $turnoverDrSubTotal = $turnoverCrSubTotal = $closingDrSubTotal = $closingCrSubTotal = 0;
        $opendr=$opencr=$turndr=$turncr=$smdr=$smcr=0;
        if (isset($openingdata) and !empty($openingdata)) {
            $html .= '<tr>';
            $html .= '<td>1</td>';
            $html .= '<td>&nbsp;</td>';
            $html .= '<td>&nbsp;</td>';
            $html .= '<td>Opening Balance</td>';
            if ($openingdata->total < 0) {
                $html .= '<td></td>';
                $html .= '<td>' . Helpers::numberFormat($openingdata->total) . '</td>';
                $opencr += $openingdata->total;
                $openingCrSubTotal = $openingdata->total;
            } else {
                $html .= '<td>' . Helpers::numberFormat($openingdata->total) . '</td>';
                $html .= '<td></td>';
                $opendr += $openingdata->total;
                $openingDrSubTotal = $openingdata->total;
            }
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '</tr>';
        }

        if (isset($trialbalancedata) and count($trialbalancedata) > 0) {

            $i = 2;
            $groupArray = $groupArraySubTotal = [];
            $subname='';


            foreach ($trialbalancedata as $k => $tdata) {

                $treeArray = explode('.', $tdata->GroupTree);

                $GroupName = $accountGroup->where('GroupId', $treeArray[0])->first();
                $subGroupName = $accountGroup->where('GroupId', $tdata->accountGroupId)->first();


                // echo $openingbalance; exit;
                $closing = $tdata->opening + $tdata->TDR + $tdata->TCR;
                   $totalopening += $tdata->opening;
                    $totalturnover += $tdata->TDR + $tdata->TCR;
                    $totalclosing += $closing;

                /**loop of sub total*/
                if (in_array($GroupName->GroupName, $groupArraySubTotal)) {

                } else {
                    array_push($groupArraySubTotal, $GroupName->GroupName);
                    $html .= '<tr>';
                    $html .= '<td>' . $i++ . '</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td><b>Sub Total</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat($openingDrSubTotal) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat(abs($openingCrSubTotal)) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat($turnoverDrSubTotal) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat(abs($turnoverCrSubTotal)) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat($closingDrSubTotal) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat(abs($closingCrSubTotal)) . '</b></td>';
                    $html .= '</tr>';
                    //                        first clear data; since it is true the first loop
                    $openingDrSubTotal = $openingCrSubTotal = $turnoverDrSubTotal = $turnoverCrSubTotal = $closingDrSubTotal = $closingCrSubTotal = 0;

                }
                /**loop of sub total*/

                $html .= '<tr>';
                $html .= '<td>' . $i++ . '</td>';
                if (in_array($GroupName->GroupName, $groupArray)) {
                    $html .= '<td></td>';
                } else {
                    array_push($groupArray, $GroupName->GroupName);
                    $html .= '<td>' . str_replace("&", "And", $GroupName->GroupName) . '</td>';
                }

                $html .= '<td>' . str_replace("&", "And", $subGroupName->GroupName) . '</td>';
                $html .= '<td>' . str_replace("&", "And", $tdata->AccountName) . '</td>';

                if ($tdata->opening < 0) {
                    $html .= '<td></td>';
                    $html .= '<td>' . Helpers::numberFormat(abs($tdata->opening)) . '</td>';
                } else {
                    $html .= '<td>' . (($tdata->opening > 0) ? Helpers::numberFormat($tdata->opening) : '') . '</td>';
                    $html .= '<td></td>';
                }



                $html .= '<td>' . Helpers::numberFormat($tdata->TDR) . '</td>';
                $html .= '<td>' . Helpers::numberFormat(abs($tdata->TCR)) . '</td>';
                if ($closing < 0) {
                    $html .= '<td></td>';
                    $html .= '<td>' . Helpers::numberFormat(abs($closing)) . '</td>';
                } else {
                    $html .= '<td>' . Helpers::numberFormat($closing) . '</td>';
                    $html .= '<td></td>';
                }
                $html .= '</tr>';

                /**last loop subtotal*/

                if ($tdata->opening < 0) {
                    $opencr += $tdata->opening;
                    $openingCrSubTotal += $tdata->opening;
                } else {
                    $opendr += $tdata->opening;
                    $openingDrSubTotal += $tdata->opening;
                }


                $subname = $subGroupName->GroupName;

                $turncr += $tdata->TCR;
                $turnoverCrSubTotal += $tdata->TCR;

                $turndr += $tdata->TDR;
                $turnoverDrSubTotal += $tdata->TDR;


                if ($closing < 0) {
                    $smcr += $closing;
                    $closingCrSubTotal += $closing;
                } else {
                    $smdr += $closing;
                    $closingDrSubTotal += $closing;
                }

                if (count($trialbalancedata)-1 == ($k)) {
                    $html .= '<tr>';
                    $html .= '<td>' . $i++ . '</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td><b>Sub Total</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat($opendr) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat(abs($opencr)) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat($turndr) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat(abs($turncr)) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat($smdr) . '</b></td>';
                    $html .= '<td><b>' . Helpers::numberFormat(abs($smcr)) . '</b></td>';
                    $html .= '</tr>';
                }
                /**last loop subtotal*/
            }
        }

        $data['html'] = $html;

            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['eng_from_date'] = $eng_from_date;
//            dd($data);
            return view('coreaccount::trialbalance.trailbalance-excel', $data);
        } catch (\Exception $e) {
//            dd($e);
        }

    }


}
