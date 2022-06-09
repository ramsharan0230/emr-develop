<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\Exports\BalanceSheetExport;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Maatwebsite\Excel\Excel;

class BalanceSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;

        $data['liabilities'] = array();
        $data['assets'] = array();

        return view('coreaccount::balancesheet.index', $data);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function searchBalanceSheet(Request $request)
    {
        try {
            $data['eng_from_date'] = $eng_from_date =  Helpers::dateNepToEng($request->from_date)->full_date;
            $data['eng_to_date'] = $eng_to_date =  Helpers::dateNepToEng($request->to_date)->full_date;
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;

             $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;

        $liabilities = DB::select("SELECT Y.*,A.AccountName,SUM(P.TranAmount) AMT FROM ( SELECT X.GroupId as group_code,X.GroupName AS group_name,X.GroupTree,IFNULL(SUB.GroupId,X.GroupId) as sub_code,IFNULL(SUB.GroupName,X.GroupName) as sub_name
            FROM (
                SELECT GroupId,GroupName,GroupTree FROM account_group WHERE ParentId = 4
            ) as X
            LEFT JOIN account_group SUB ON LEFT(SUB.GroupTree,length(X.GroupTree)) = X.GroupTree AND SUB.ParentId not in('4')
            ) as Y
            INNER JOIN transaction_view P ON P.GroupId = Y.sub_code
            INNER JOIN account_ledger A ON A.AccountNo = P.AccountNo
            where P.TranDate >= '".$eng_from_date."' and P.TranDate <= '".$eng_to_date."'
            GROUP BY Y.GroupTree,Y.group_code,Y.group_name,Y.sub_code,Y.sub_name,A.AccountName
            ORDER BY Y.GroupTree");
        // dd($data['liabilities']);

        $assets = DB::select("SELECT Y.*,A.AccountName,SUM(P.TranAmount) AMT FROM ( SELECT X.GroupId as group_code,X.GroupName as group_name,X.GroupTree,IFNULL(SUB.GroupId,X.GroupId) as sub_code,IFNULL(SUB.GroupName,X.GroupName) as sub_name
            FROM (
                SELECT GroupId,GroupName,GroupTree FROM account_group WHERE ParentId IN ('1','2','3')
            ) as X
            LEFT JOIN account_group SUB ON LEFT(SUB.GroupTree,length(X.GroupTree)) = X.GroupTree AND SUB.ParentId NOT IN ('1','2','3')
            ) as Y
            INNER JOIN transaction_view P ON P.GroupId = Y.sub_code
            INNER JOIN account_ledger A ON A.AccountNo = P.AccountNo
            where P.TranDate >= '".$eng_from_date."' and P.TranDate <= '".$eng_to_date."'
            GROUP BY Y.GroupTree,Y.group_code,Y.group_name,Y.sub_code,Y.sub_name,A.AccountName
            ORDER BY Y.GroupTree");
        // dd($assets);
        // return view('coreaccount::balancesheet.index', $data);
          $totalLiabilities = 0;
          $totalAssets = 0;
          $html = '';
          $html .='<tr><td>2</td><td>Liabilities</td><td></td><td></td><td></td><td></td></tr>';
          $arrayLiabilityGroupName = [];
          $liabilityTotal = 0;

          if(isset($liabilities) and count($liabilities) > 0){
            foreach($liabilities as $l=>$liability){
                if (!in_array($liability->group_name, $arrayLiabilityGroupName) && $l != 1){
                    array_push($arrayLiabilityGroupName, $liability->group_name);
                    $html .='<tr><td></td><td></td><td></td><th>Sub Total</th><th>'.Helpers::numberFormat($liabilityTotal).'</th><td></td></tr>';
                    $liabilityTotal = 0;
                }
                $totalLiabilities += ($liability->AMT);
                $singleliabilitytotal  = $liability->AMT < 0 ? ($liability->AMT * -1) : ($liability->AMT);
                // echo $singleliabilitytotal; exit;
                $html .='<tr>';
                $html .='<td>'.$liability->GroupTree.'</td>';
                $html .='<td>'.$liability->group_name.'</td>';
                $html .='<td>'.$liability->sub_name.'</td>';
                $html .='<td>'.$liability->AccountName.'</td>';
                $html .='<td>'.Helpers::numberFormat($singleliabilitytotal).'</td>';
                $html .='<td></td></tr>';

                $liabilityTotal += ($liability->AMT);
                if($l == array_key_last($liabilities)){
                    $html .='<tr><td></td><td></td><td>';
                    $html .='</td><th>Sub Total</th><th>'.Helpers::numberFormat($liabilityTotal).'</th><td></td></tr>';
                    $liabilityTotal = 0;
                }
            }
          }

        $html .='<tr>
                        <td>1</td>
                        <td>Assets</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
        $arrayAssetGroupName = [];
        $assetSubTotal = 0;

        if(isset($assets) and count($assets) > 0){
            foreach($assets as $a=>$asset){
                if (!in_array($asset->group_name, $arrayAssetGroupName) && $a != 1){
                    array_push($arrayAssetGroupName, $asset->group_name);
                    $html .='<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th>Sub Total</th>
                                <th></th>
                                <th>'.Helpers::numberFormat($assetSubTotal).'</th>
                            </tr>';
                    $assetSubTotal = 0;
                }
                $totalAssets += ($asset->AMT);
                // dd($asset);
                $singleassettotal = $asset->AMT < 0 ? ($asset->AMT * -1) : ($asset->AMT);
                $html .='<tr>
                            <td>'.$asset->GroupTree.'</td>
                            <td>'.$asset->group_name.'</td>
                            <td>'.$asset->sub_name.'</td>
                            <td>'.$asset->AccountName.'</td>
                            <td></td>
                            <td>'.Helpers::numberFormat($singleassettotal).'</td>
                        </tr>';
                $assetSubTotal += $asset->AMT;
                if($a == array_key_last($assets)){
                    $html .='<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th>Sub Total</th>
                                <td></td>
                                <th>'.Helpers::numberFormat($assetSubTotal).'</th>
                            </tr>';
                    $assetSubTotal = 0;
                }
            }
        }
        $finaltotalliabilities = $totalLiabilities < 0 ? ($totalLiabilities * -1) : ($totalLiabilities);
        $finaltotalassets = $totalAssets < 0 ? ($totalAssets * -1) : ($totalAssets);
        $html .='<tr>
                    <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                    <td>'.Helpers::numberFormat($finaltotalliabilities).'</td>
                    <td>'.Helpers::numberFormat($finaltotalassets).'</td>
                </tr>';
        echo $html; exit;

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function exportExcel(Request $request)
    {
        $from_date = Helpers::dateNepToEng($request->from_date)->full_date ?? '';
        $to_date = Helpers::dateNepToEng($request->to_date)->full_date ?? '';
        ob_end_clean();
        ob_start();
        return \Maatwebsite\Excel\Facades\Excel::download(new BalanceSheetExport($from_date, $to_date), 'Balancesheet-Report.xlsx');

    }

    public function exportPdf(Request $request)
    {
        $data['eng_from_date'] = $eng_from_date =  Helpers::dateNepToEng($request->from_date)->full_date;
        $data['eng_to_date'] = $eng_to_date = Helpers::dateNepToEng($request->to_date)->full_date;
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        // echo $eng_from_date; exit;
        try {

            $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

            $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;

            $data['liabilities'] = DB::select("SELECT Y.*,A.AccountName,SUM(P.TranAmount) AMT FROM ( SELECT X.GroupId as group_code,X.GroupName AS group_name,X.GroupTree,IFNULL(SUB.GroupId,X.GroupId) as sub_code,IFNULL(SUB.GroupName,X.GroupName) as sub_name
            FROM (
                SELECT GroupId,GroupName,GroupTree FROM account_group WHERE ParentId = 4
            ) as X
            LEFT JOIN account_group SUB ON LEFT(SUB.GroupTree,length(X.GroupTree)) = X.GroupTree AND SUB.ParentId not in('4')
            ) as Y
            INNER JOIN transaction_view P ON P.GroupId = Y.sub_code
            INNER JOIN account_ledger A ON A.AccountNo = P.AccountNo
            where P.TranDate >= '".$eng_from_date."' and P.TranDate <= '".$eng_to_date."'
            GROUP BY Y.GroupTree,Y.group_code,Y.group_name,Y.sub_code,Y.sub_name,A.AccountName
            ORDER BY Y.GroupTree");

            $data['assets'] = DB::select("SELECT Y.*,A.AccountName,SUM(P.TranAmount) AMT FROM ( SELECT X.GroupId as group_code,X.GroupName as group_name,X.GroupTree,IFNULL(SUB.GroupId,X.GroupId) as sub_code,IFNULL(SUB.GroupName,X.GroupName) as sub_name
            FROM (
                SELECT GroupId,GroupName,GroupTree FROM account_group WHERE ParentId IN ('1','2','3')
            ) as X
            LEFT JOIN account_group SUB ON LEFT(SUB.GroupTree,length(X.GroupTree)) = X.GroupTree AND SUB.ParentId NOT IN ('1','2','3')
            ) as Y
            INNER JOIN transaction_view P ON P.GroupId = Y.sub_code
            INNER JOIN account_ledger A ON A.AccountNo = P.AccountNo
            where P.TranDate >= '".$eng_from_date."' and P.TranDate <= '".$eng_to_date."'
            GROUP BY Y.GroupTree,Y.group_code,Y.group_name,Y.sub_code,Y.sub_name,A.AccountName
            ORDER BY Y.GroupTree");

            return view('coreaccount::balancesheet.balancesheet-pdf', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;
        } catch (\Exception $e) {
//            dd($e);
        }

    }
}
