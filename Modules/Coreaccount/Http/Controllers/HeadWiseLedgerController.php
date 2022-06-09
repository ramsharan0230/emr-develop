<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use App\TransactionMasterPost;
use App\TransactionView;
use Illuminate\Http\Request;
use App\Exports\HeadwiseLedgerReportExport;
use Validator;
use Excel;
use Illuminate\Routing\Controller;

class HeadWiseLedgerController extends Controller
{
    public function index(Request $request)
    {
        $data['transactionData'] = [];
        if ($request->has('from_date') && $request->has('to_date') && $request->has('account_num')){
            $validated = Validator::make($request->all(), [
                'from_date' => 'required',
                'to_date' => 'required',
                'account_num' => 'required',
            ]);

            if ($validated->fails()) {
                return redirect()
                    ->route('accounts.head.wise.ledger.index')
                    ->withErrors($validated)
                    ->withInput();
            }

            $accountvouchernumberdetail = TransactionView::select('*')->distinct('VoucherNo')
            ->where('TranDate','>=', $request->get('from_date'))
            ->where('TranDate','<=', $request->get('to_date'))
            ->where('AccountNo', $request->get('account_num'))
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
                    $detailing = TransactionView::select('*')
                    ->where('VoucherNo',$account->VoucherNo)
                    ->where('TranAmount','<',0)
                    ->first();
                    $voucherarray[$k]['amount'] = (isset($detailing->TranAmount) and $detailing->TranAmount !='') ? $detailing->TranAmount : "0";
                    $voucherarray[$k]['AccountName'] =(isset($detailing->accountLedger) and $detailing->accountLedger !='') ? $detailing->accountLedger->AccountName : '' ;
                }else{
                    $voucherarray[$k]['type'] = 'CR';
                    $detailing = TransactionView::select('*')
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

            // $transactionVoucher = TransactionView::where('TranDate','>=', $request->get('from_date'))
            //     ->where('TranDate','<=', $request->get('to_date'))
            //     ->where('AccountNo', $request->get('account_num'))
            //     ->pluck('VoucherNo');
            //   //  dd($transactionVoucher);

            // $data['transactionData'] = TransactionView::select('TranId','AccountNo', 'TranDate', 'VoucherNo', 'TranAmount', 'ChequeNo', 'Narration')
            //     ->whereIn('VoucherNo', $transactionVoucher)
            //     ->where('AccountNo', '!=',$request->get('account_num'))
            //     ->with('accountLedger')
            //     ->orderBy('TranDate', 'asc')
            //     ->paginate(25)->appends(request()->query());
        }

        $data['accounts'] = AccountLedger::select('AccountName', 'AccountNo')->get();
        return view('coreaccount::head-wise-ledger.ledger', $data);
    }

    public function export(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'from_date' => 'required',
            'to_date' => 'required',
            'account_num' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()
                ->route('accounts.head.wise.ledger.index')
                ->withErrors($validated)
                ->withInput();
        }

        $data['from_date'] = $request->get('from_date');
        $data['to_date'] = $request->get('to_date');
        $data['account_num'] = $request->get('account_num');

        $data['account_name'] = AccountLedger::select('AccountName', 'GroupId')
            ->where('AccountNo', $data['account_num'])
            ->with('account_group')
            ->first();




            $accountvouchernumberdetail = TransactionView::select('*')->distinct('VoucherNo')
            ->where('TranDate','>=', $request->get('from_date'))
            ->where('TranDate','<=', $request->get('to_date'))
            ->where('AccountNo', $request->get('account_num'))
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
                    $detailing = TransactionView::select('*')
                    ->where('VoucherNo',$account->VoucherNo)
                    ->where('TranAmount','<',0)
                    ->first();
                    $voucherarray[$k]['amount'] = (isset($detailing->TranAmount) and $detailing->TranAmount !='') ? $detailing->TranAmount : "0";
                    $voucherarray[$k]['AccountName'] =(isset($detailing->accountLedger) and $detailing->accountLedger !='') ? $detailing->accountLedger->AccountName : '' ;
                }else{
                    $voucherarray[$k]['type'] = 'CR';
                    $detailing = TransactionView::select('*')
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



        return view('coreaccount::head-wise-ledger.export', $data);
    }

    public function exportToExcel(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'from_date' => 'required',
            'to_date' => 'required',
            'account_num' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()
                ->route('accounts.head.wise.ledger.index')
                ->withErrors($validated)
                ->withInput();
        }
        $export = new HeadwiseLedgerReportExport($request->all());
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'HeadwiseLedgerReport.xlsx');
    }
}
