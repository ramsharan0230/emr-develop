<?php

namespace App\Exports;

use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProfitLossExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date,string  $eng_from_date,string  $eng_to_date,string $action )
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->eng_from_date = $eng_from_date;
        $this->eng_to_date = $eng_to_date;
        $this->action = $action;
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
        $data['eng_from_date'] = $eng_from_date= $this->eng_from_date;
        $data['eng_to_date'] = $eng_to_date= $this->eng_to_date;
        $data['action'] = $action= $this->action;

        try{
            // // dd($request->all());
            // $groups = AccountGroup::where('ParentId','0')->get();
            // $incomedata = array();
            $html = '';
            // $finalperiodamount = array();
            // $finalyearlyamount = array();

            #Income Query
            $incomesql = "SELECT Y.*, SUM(case when P.TranDate>='".$eng_from_date."' and P.TranDate<='".$eng_to_date."'then P.TranAmount else 0 end)
                   as period_amount , SUM(P.TranAmount) yearto_date_amount
                FROM (
                SELECT X.GroupId as group_code,X.GroupName as group_name,X.GroupTree,IFNULL(SUB.GroupId,X.GroupId) as sub_code,IFNULL(SUB.GroupName,X.GroupName) as sub_name
                FROM (
                    SELECT GroupId,GroupName,GroupTree FROM account_group WHERE left(GroupTree,2) ='1.'
                ) as X
                LEFT JOIN account_group as SUB ON LEFT(SUB.GroupTree,1) = X.GroupTree AND SUB.ParentId <> 1
                ) as Y
                INNER JOIN transaction_view as P ON P.GroupId = Y.group_code
                GROUP BY Y.GroupTree,Y.group_code,Y.group_name,Y.group_code,Y.group_name
                ORDER BY Y.GroupTree";

            $incomeresult = json_decode(json_encode(\DB::select($incomesql)), true);
            // dd($result);
            $incomesubgroup = array();
            $incomeperiodamount = array();
            $incomeyearlyamount = array();
            $incomeperiodamountdisplay = array();
            $incomeyearlyamountdisplay = array();
            // echo $->group

            // echo $groupname; exit;
            if(isset($incomeresult) and count($incomeresult) > 0){
                foreach($incomeresult as $r){

                    $incomesubgroup[] = str_replace("&", "And", $r['sub_name']);
                    $incomeperiodamount[] = $r['period_amount'];
                    $incomeyearlyamount[] = $r['yearto_date_amount'];
                    $incomeperiodamountdisplay[] = abs(\App\Utils\Helpers::numberFormat($r['period_amount']));
                    $incomeyearlyamountdisplay[] = abs(\App\Utils\Helpers::numberFormat($r['yearto_date_amount']));
                }
                $incomegroupname = $incomeresult[0]['group_name'];
                // $finalperiodamount[] = array_sum($periodamount);
                // $finalyearlyamount[] = array_sum($yearlyamount);
                $html .='<tr>';
                $html .='<td>1</td>';
                $html .='<td>Income</td>';
                $html .='<td>'.implode('<br/>', $incomesubgroup).'</td>';
                $html .='<td>'.implode('<br/>', $incomeperiodamountdisplay).'</td>';
                $html .='<td>'.implode('<br/>', $incomeyearlyamountdisplay).'</td>';
                $html .='</tr>';
                $html .='<tr>';
                $html .='<td></td>';
                $html .='<td></td>';
                $html .='<td>Total</td>';
                $html .='<td>'.abs(array_sum($incomeperiodamount)).'</td>';
                $html .='<td>'.abs(array_sum($incomeyearlyamount)).'</td>';
                $html .='</tr>';
            }
            #Expense Query
            $expensesql = "SELECT Y.*, SUM(case when P.TranDate>='".$eng_from_date."' and P.TranDate<='".$eng_to_date."'then P.TranAmount else 0 end)
                   as period_amount , SUM(P.TranAmount) yearto_date_amount
                FROM (
                SELECT X.GroupId as group_code,X.GroupName as group_name,X.GroupTree,IFNULL(SUB.GroupId,X.GroupId) as sub_code,IFNULL(SUB.GroupName,X.GroupName) as sub_name
                FROM (
                    SELECT GroupId,GroupName,GroupTree FROM account_group WHERE left(GroupTree,2) ='2.'
                ) as X
                LEFT JOIN account_group as SUB ON LEFT(SUB.GroupTree,2) = X.GroupTree AND SUB.ParentId <> 2

                ) as Y
                INNER JOIN transaction_view as P ON P.GroupId = Y.group_code
                GROUP BY Y.GroupTree,Y.group_code,Y.group_name,Y.group_code,Y.group_name
                ORDER BY Y.GroupTree";

            $expenseresult = json_decode(json_encode(\DB::select($expensesql)), true);
            // dd($result);
            $expensesubgroup = array();
            $expenseperiodamount = array();
            $expenseyearlyamount = array();
            $expenseyearlyamountforsum =  array();
            $expenseperiodamountforsum = array();
            // echo $->group

            // echo $groupname; exit;
            if(isset($expenseresult) and count($expenseresult) > 0){
                foreach($expenseresult as $er){

                    $expensesubgroup[] = str_replace("&", "And", $er['sub_name']);
                    $expenseperiodamount[] = \App\Utils\Helpers::numberFormat($er['period_amount']);
                    $expenseperiodamountforsum[] = $er['period_amount'];
                    $expenseyearlyamount[] = \App\Utils\Helpers::numberFormat($er['yearto_date_amount']);
                    $expenseyearlyamountforsum[] = $er['yearto_date_amount'];
                }
                $expensegroupname = $expenseresult[0]['group_name'];
                // $finalperiodamount[] = array_sum($periodamount);
                // $finalyearlyamount[] = array_sum($yearlyamount);
                $html .='<tr>';
                $html .='<td>2</td>';
                $html .='<td >Expenses</td>';
                $html .='<td>'.implode('<br/>', $expensesubgroup).'</td>';
                $html .='<td>'.implode('<br/>', $expenseperiodamount).'</td>';
                $html .='<td>'.implode('<br/>', $expenseyearlyamount).'</td>';
                $html .='</tr>';
                $html .='<tr>';
                $html .='<td></td>';
                $html .='<td></td>';
                $html .='<td >Total</td>';
                $html .='<td >'.\App\Utils\Helpers::numberFormat(array_sum($expenseperiodamountforsum)).'</td>';
                $html .='<td >'.\App\Utils\Helpers::numberFormat(array_sum($expenseyearlyamountforsum)).'</td>';
                $html .='</tr>';
            }

            #Final Row
            $html .='<tr>';
            $html .='<td></td>';
            $html .='<td></td>';
            $html .='<td>Net Profit/Loss</td>';
            $html .='<td>'.\App\Utils\Helpers::numberFormat((abs(array_sum($incomeperiodamount))-array_sum($expenseperiodamountforsum))).'</td>';
            $html .='<td>'.\App\Utils\Helpers::numberFormat((abs(array_sum($incomeyearlyamount))-array_sum($expenseyearlyamountforsum))).'</td>';
            $html .='</tr>';


            $data['html'] = $html;
//            dd($data);
            return view('coreaccount::profitloss.profitloss-excel', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;

        }catch(\Exception $e){
            dd($e);
        }

    }
}
