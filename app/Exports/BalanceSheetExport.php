<?php

namespace App\Exports;

use App\AccountGroup;
use App\TransactionView;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BalanceSheetExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
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
            where P.TranDate >= '".$from_date."' and P.TranDate <= '".$to_date."'
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
            where P.TranDate >= '".$from_date."' and P.TranDate <= '".$to_date."'
            GROUP BY Y.GroupTree,Y.group_code,Y.group_name,Y.sub_code,Y.sub_name,A.AccountName
            ORDER BY Y.GroupTree");

            return view('coreaccount::balancesheet.balancesheet-excel', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;
        } catch (\Exception $e) {
//            dd($e);
        }

    }
}
