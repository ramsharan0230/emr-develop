<?php

namespace App\Exports;

use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class HeadwiseLedgerReportExport implements FromView,WithDrawings,ShouldAutoSize
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
        
        $data['from_date'] = $filterdata['from_date'];
        $data['to_date'] = $filterdata['to_date'];
        $data['account_num'] = $filterdata['account_num'];

        $data['account_name'] = \App\AccountLedger::select('AccountName', 'GroupId')
            ->where('AccountNo', $data['account_num'])
            ->with('account_group')
            ->first();




        $accountvouchernumberdetail = \App\TransactionView::select('*')->distinct('VoucherNo')
            ->where('TranDate','>=', $data['from_date'])
            ->where('TranDate','<=', $data['to_date'])
            ->where('AccountNo', $data['account_num'])
            ->with('accountLedger')
            ->orderBy('TranDate','asc')
            ->get();

        $voucherarray = [];

        if(!empty($accountvouchernumberdetail)){
        foreach($accountvouchernumberdetail as $k =>  $account){

        $voucherarray[$k]['TranAmount'] = (isset($account->TranAmount) and $account->TranAmount !='') ? $account->TranAmount:"0";
        $voucherarray[$k]['VoucherNo'] = (isset($account->VoucherNo) and $account->VoucherNo !='') ? $account->VoucherNo:"";
        $voucherarray[$k]['mainAccountName'] =$account->accountLedger ? $account->accountLedger->AccountName : '' ;
        $voucherarray[$k]['TranDate'] = (isset($account->TranDate) and $account->TranDate !='') ? $account->TranDate:"";
        $voucherarray[$k]['Narration'] = (isset($account->Narration) and $account->Narration !='') ? $account->Narration:"";
        $voucherarray[$k]['ChequeNo'] = (isset($account->ChequeNo) and $account->ChequeNo !='') ? $account->ChequeNo:"";


        if(isset($account->TranAmount) and $account->TranAmount > 0){
            $voucherarray[$k]['type'] = 'DR';
            $detailing = \App\TransactionView::select('*')
            ->where('VoucherNo',$account->VoucherNo)
            ->where('TranAmount','<',0)
            ->first();
            $voucherarray[$k]['amount'] = (isset($detailing->TranAmount) and $detailing->TranAmount !='') ? $detailing->TranAmount : "0";
            $voucherarray[$k]['AccountName'] =(isset($detailing->accountLedger) and $detailing->accountLedger !='') ? $detailing->accountLedger->AccountName : '' ;
        }else{
            $voucherarray[$k]['type'] = 'CR';
            $detailing = \App\TransactionView::select('*')
            ->where('VoucherNo',$account->VoucherNo)
            ->where('TranAmount','>',0)
            ->with('accountLedger')
            ->first();
            $voucherarray[$k]['amount'] = (isset($detailing->TranAmount) and $detailing->TranAmount !='') ? $detailing->TranAmount : "0";
            $voucherarray[$k]['AccountName'] =(isset($detailing->accountLedger) and $detailing->accountLedger !='') ? $detailing->accountLedger->AccountName : '' ;
        }



        }
        }

       // dd($voucherarray);
        $data['transactionData'] =$voucherarray;

                // echo count($results); exit; 
        return view('coreaccount::head-wise-ledger.exportToExcel', $data);
    }

}
