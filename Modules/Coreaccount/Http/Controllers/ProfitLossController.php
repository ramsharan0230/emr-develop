<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountGroup;
use App\Exports\ProfitLossExport;
use App\Utils\Helpers;
use App\TransactionMaster;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $datevalue->year.'-'.$datevalue->month.'-'.$datevalue->date;
        return view('coreaccount::profitloss.index',$data);
    }



    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function searchProfitLoss(Request $request)
    {
        try{

            $html = '';


            #Income Query
            $incomesql = "SELECT Y.*, SUM(case when P.TranDate>='".$request->eng_from_date."' and P.TranDate<='".$request->eng_to_date."'then P.TranAmount else 0 end)
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
            // echo $incomesql; exit;
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

                    $incomesubgroup[] = $r['sub_name'];
                    $incomeperiodamount[] = $r['period_amount'];
                    $incomeyearlyamount[] = $r['yearto_date_amount'];
                    $incomeperiodamountdisplay[] = Helpers::numberFormat(abs($r['period_amount']));
                    $incomeyearlyamountdisplay[] = Helpers::numberFormat(abs($r['yearto_date_amount']));
                }
                $incomegroupname = $incomeresult[0]['group_name'];
                // $finalperiodamount[] = array_sum($periodamount);
                // $finalyearlyamount[] = array_sum($yearlyamount);
                $html .='<tr>';
                $html .='<td class="text-center">1</td>';
                $html .='<td class="text-center">Income</td>';
                $html .='<td class="text-center">'.implode('<br/>', $incomesubgroup).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $incomeperiodamountdisplay).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $incomeyearlyamountdisplay).'</td>';
                $html .='</tr>';
                $html .='<tr>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"><b>Total</</td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(abs(array_sum($incomeperiodamount))).'</b></td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(abs(array_sum($incomeyearlyamount))).'</b></td>';
                $html .='</tr>';
            }
            #Expense Query
            $expensesql = "SELECT Y.*, SUM(case when P.TranDate>='".$request->eng_from_date."' and P.TranDate<='".$request->eng_to_date."'then P.TranAmount else 0 end)
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
            // echo $expensesql; exit;
            $expenseresult = json_decode(json_encode(\DB::select($expensesql)), true);
            // dd($result);
            $expensesubgroup = array();
            $expenseperiodamount = array();
            $expenseyearlyamount = array();
            $expenseperiodamountDisplay = array();
            $expenseyearlyamountDisplay = array();

            if(isset($expenseresult) and count($expenseresult) > 0){
                foreach($expenseresult as $er){

                    $expensesubgroup[] = $er['sub_name'];
                    $expenseperiodamount[] =$er['period_amount'];
                    $expenseyearlyamount[] = $er['yearto_date_amount'];
                    $expenseperiodamountDisplay[] =Helpers::numberFormat($er['period_amount']);
                    $expenseyearlyamountDisplay[] = Helpers::numberFormat($er['yearto_date_amount']);
                }
                $expensegroupname = $expenseresult[0]['group_name'];

                $html .='<tr>';
                $html .='<td class="text-center">2</td>';
                $html .='<td class="text-center">Expenses</td>';
                $html .='<td class="text-center">'.implode('<br/>', $expensesubgroup).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $expenseperiodamountDisplay).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $expenseperiodamountDisplay).'</td>';
                $html .='</tr>';
                $html .='<tr>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"><b>Total</b></td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(array_sum($expenseperiodamount)).'</b></td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(array_sum($expenseyearlyamount)).'</b></td>';
                $html .='</tr>';
            }

            #Final Row
            $html .='<tr>';
            $html .='<td></td>';
            $html .='<td></td>';
            $html .='<td><b>Net Profit/Loss</b></td>';
            $html .='<td class="text-center"><b>'.Helpers::numberFormat((abs(array_sum($incomeperiodamount))-array_sum($expenseperiodamount))).'</b></td>';
            $html .='<td class="text-center"><b>'.Helpers::numberFormat((abs(array_sum($incomeyearlyamount))-array_sum($expenseyearlyamount))).'</b></td>';
            $html .='</tr>';


            echo $html;

        }catch(\Exception $e){
            dd($e);
        }
    }

    public function exportExcel(Request $request){
//        dd($request->all());
        $from_date = $request->from_date ?? '';
        $to_date = $request->to_date ?? '';
        $eng_from_date = $request->eng_from_date ?? '';
        $eng_to_date = $request->eng_to_date ?? '';
        $action = $request->action ??'';
        ob_end_clean();
        ob_start();
        return \Maatwebsite\Excel\Facades\Excel::download(new ProfitLossExport($from_date, $to_date,$eng_from_date,$eng_to_date,$action), 'Profitloss-Report.xlsx');

    }

    public function exportPdf(Request $request){

        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['eng_from_date'] = $request->eng_from_date;
        $data['eng_to_date'] = $request->eng_to_date;

        try{

            $html = '';

            #Income Query
            $incomesql = "SELECT Y.*, SUM(case when P.TranDate>='".$request->eng_from_date."' and P.TranDate<='".$request->eng_to_date."'then P.TranAmount else 0 end)
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

                    $incomesubgroup[] = $r['sub_name'];
                    $incomeperiodamount[] =  $r['period_amount'];
                    $incomeyearlyamount[] = $r['yearto_date_amount'];

                    $incomeperiodamountdisplay[] =  Helpers::numberFormat(abs($r['period_amount']));
                    $incomeyearlyamountdisplay[] =  Helpers::numberFormat(abs($r['yearto_date_amount']));
                }
                $incomegroupname = $incomeresult[0]['group_name'];
                // $finalperiodamount[] = array_sum($periodamount);
                // $finalyearlyamount[] = array_sum($yearlyamount);
                $html .='<tr>';
                $html .='<td class="text-center">1</td>';
                $html .='<td class="text-center">Income</td>';
                $html .='<td class="text-center">'.implode('<br/>', $incomesubgroup).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $incomeperiodamountdisplay).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $incomeyearlyamountdisplay).'</td>';
                $html .='</tr>';
                $html .='<tr>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"><b>Total</</td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(abs(array_sum($incomeperiodamount))).'</b></td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(abs(array_sum($incomeyearlyamount))).'</b></td>';
                $html .='</tr>';
            }
            #Expense Query
            $expensesql = "SELECT Y.*, SUM(case when P.TranDate>='".$request->eng_from_date."' and P.TranDate<='".$request->eng_to_date."'then P.TranAmount else 0 end)
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
            $expenseperiodamountDisplay = array();
            $expenseyearlyamountDisplay = array();
            // echo $->group

            // echo $groupname; exit;
            if(isset($expenseresult) and count($expenseresult) > 0){
                foreach($expenseresult as $er){

                    $expensesubgroup[] = $er['sub_name'];
                    $expenseperiodamount[] = $er['period_amount'];
                    $expenseyearlyamount[] = $er['yearto_date_amount'];
                    $expenseperiodamountDisplay[] = Helpers::numberFormat($er['period_amount']);
                    $expenseyearlyamountDisplay[] = Helpers::numberFormat($er['yearto_date_amount']);
                }
                $expensegroupname = $expenseresult[0]['group_name'];
                // $finalperiodamount[] = array_sum($periodamount);
                // $finalyearlyamount[] = array_sum($yearlyamount);
                $html .='<tr>';
                $html .='<td class="text-center">2</td>';
                $html .='<td class="text-center">Expenses</td>';
                $html .='<td class="text-center">'.implode('<br/>', $expensesubgroup).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $expenseperiodamountDisplay).'</td>';
                $html .='<td class="text-center">'.implode('<br/>', $expenseyearlyamountDisplay).'</td>';
                $html .='</tr>';
                $html .='<tr>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"></td>';
                $html .='<td class="text-center"><b>Total</b></td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(array_sum($expenseperiodamount)).'</b></td>';
                $html .='<td class="text-center"><b>'.Helpers::numberFormat(array_sum($expenseyearlyamount)).'</b></td>';
                $html .='</tr>';
            }

            #Final Row
            $html .='<tr>';
            $html .='<td></td>';
            $html .='<td></td>';
            $html .='<td>Net Profit/Loss</td>';
            $html .='<td>'.Helpers::numberFormat((array_sum($incomeperiodamount)+array_sum($expenseperiodamount))).'</td>';
            $html .='<td>'.Helpers::numberFormat((array_sum($incomeyearlyamount)+array_sum($expenseyearlyamount))).'</td>';
            $html .='</tr>';


            $data['html'] = $html;
            return view('coreaccount::profitloss.profitloss-pdf', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;

        }catch(\Exception $e){
            dd($e);
        }

    }
}
