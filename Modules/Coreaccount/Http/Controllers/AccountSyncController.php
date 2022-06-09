<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use App\PatBilling;
use App\TempTransaction;
use App\Utils\Options;
use Carbon\Carbon;

use App\PatBillDetail;
use App\DiscountLedgerMap;
use App\TempDepositTransaction;
use App\Utils\Helpers;
use App\GeneralLedgerMap;



use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AccountSyncController extends Controller
{
    public function syncListByAccount()
    {
       // DB::enableQueryLog();
        $data['ledgers'] = AccountLedger::whereHas('account_group.accountServiceMap')->get();
        // dd(DB::getQueryLog());

        return view('coreaccount::account-group-map.map-list-by-account', $data);
    }

    public function syncAccountByNo(Request $request, $AccountNo)
    {
        if (Options::get('default_cash_in_hand') === false){
            return response()->json([
                'success' => false,
                'message' => 'Cash in hand not set.'
            ]);
        }
        $startTime = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $endTime = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $data = PatBilling::join('tblservicecost','tblservicecost = tblpatbilling')
        ->where('tblpatbilling.fldsave', 1)
            ->where('tblpatbilling.account_sync', 0)
            ->where('tblpatbilling.fldditemamt', '>', 0)
            ->where('tblpatbilling.fldtime', '>=', $startTime)
            ->where('tblpatbilling.fldtime', '<=', $endTime)
            ->whereHas('accountServiceMap.accountGroup')
            ->select('tblpatbilling.fldid as fldid',
            'tblservicecost.account_ledger as AccountNo',
            '(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as TranAmount',
            'tblpatbilling.flddiscamt as DisAmount',
            'date("Y-m-d") as TranDate',
           'tblpatbilling.flditemname as Remarks',
            'tblpatbilling.fldtaxamt',
            'tblpatbilling.fldditemamt')
            ->with(['accountServiceMap.accountNum', 'discount_account_map.ledger'])
            ->orderBy('fldid', 'asc')
            ->get();


        try {
            \DB::beginTransaction();
            if ($data) {
                foreach ($data as $datum) {
                    if ($datum->accountServiceMap && $datum->accountServiceMap->accountGroup) {
                        /**account number*/
                        $accountNum = $datum->accountServiceMap->accountNum->AccountNo;
                        $fldid = $datum->fldid;

                        $transactionDataInsert = [
                            'fldid' => $fldid,
                            'AccountNo' => $accountNum,
                            'GroupId' => $datum->accountServiceMap->sub_group_id,
                            'TranAmount' => $datum->TranAmount,
                            'TranDate' => date('Y-m-d'),
                            'Remarks' => $datum->flditemname . "($request->today_date)",
                            'CreatedBy' => \Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                        ];
                        if ($datum->flddiscamt != 0) {
                            $transactionDataInsert['DisAccountNo'] = ($datum->discount_account_map && $datum->discount_account_map->ledger) ? $datum->discount_account_map->ledger->AccountNo : Options::get('default_discount');
                            $transactionDataInsert['DisAmount'] = $datum->DisAmount;
                        }

                        $insertTransaction = TempTransaction::create($transactionDataInsert);

                        if ($insertTransaction) {
                            PatBilling::where('fldid', $fldid)->update(['account_sync' => 1]);
                        }
                    }
                }

            }

            \DB::commit();
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function tempTransactionSyncAll(Request $request)
    {
        if (Options::get('default_cash_in_hand') === false){
            return response()->json([
                'success' => false,
                'message' => 'Cash in hand not set.'
            ]);
        }


        $startTime = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $endTime = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $AccountNo = $request->accountNum;

     //   DB::enableQueryLog();

//      select
// 	*
// from
// 	tblpatbilling
// join tblservicecost on
// 	tblpatbilling.flditemname = tblservicecost.flditemname
// join account_ledger on
// 	account_ledger.AccountNo = tblservicecost.account_ledger
// where
// 	tblpatbilling.fldtime >= '2021-07-20 00:00:00'
// 	and tblpatbilling.fldtime <= '2021-07-20 23:59:59'
// 	and tblpatbilling.fldsave = '1'
// 	and tblpatbilling.account_sync = 0
// 	and tblpatbilling.fldstatus = 'Cleared'
// 	and tblpatbilling.fldcomp = 'comp01'

// GROUP BY
// 	account_ledger.AccountName



        $data = PatBilling::select('tblpatbilling.fldid','tblpatbilling.fldbillno','tblpatbilling.fldretbill',
        'tblpatbilling.discount_mode','tblpatbilling.fldditemamt','tblpatbilling.flditemrate','tblpatbilling.flditemqty',
        'tblpatbilling.flddiscamt','tblpatbilling.fldtaxamt','tblpatbilling.flditemname','tblpatbilling.fldtime','account_ledger.*')
            ->join('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')
            ->join('account_ledger', 'account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
            ->where('fldsave', 1)
            ->where('tblpatbilling.account_sync', 0)
            ///->where('tblpatbilling.fldstatus', 'Cleared')
            ->where('tblpatbilling.fldtime', '>=', $startTime)
            ->where('tblpatbilling.fldtime', '<=', $endTime)
           // ->where('tblpatbilling.fldcomp',Helpers::getCompName())
           // ->whereIn('account_ledger.AccountNo', $AccountNo)
            ->orderBy('tblpatbilling.fldid', 'asc')
            ->get();
           // dd(DB::getQueryLog());


        $datadeposit = PatBillDetail::where('fldsave', 1)
            ->where('fldtime', '>=', $startTime)
            ->where('fldtime', '<=', $endTime)
            ->where('fldcomp',Helpers::getCompName())
            ->where('fldbillno','LIKE','%DEP%')
            ->select('fldid','fldbillno','fldreceivedamt','fldtime','fldpayitemname')
            ->orderBy('fldid', 'asc')
            ->get();

          //   dd( $data[0] );
            // dd( $datadeposit );


        try {
            \DB::beginTransaction();
            if ($data) {
                $is_return = 0;
                $discountledger = 0;
                foreach ($data->chunk(100) as $chunk) {

                    foreach ($chunk as $datum) {

                        if ($datum->AccountNo) {
                            /**account number*/
                            $accountNum = $datum->AccountNo;
                            $fldid = $datum->fldid;
                            $type = substr($datum->fldbillno, 0, 3);

                            if($type == 'RET' || $datum->fldretbill != '' ){
                                $is_return = 1;
                            }

                            $patbillingdetail = PatBillDetail::select('fldprevdeposit','fldid')->where('fldbillno',$datum->fldbillno)->first();
                            $disLedger = DiscountLedgerMap::join('account_ledger','account_ledger.AccountId','=','discount_account_map.ledger_id')
                            ->where('discount_account_map.discount_name',$datum->discount_mode)->select('account_ledger.AccountNo')->first();

                            if($disLedger){
                                $discountledger = $disLedger->AccountNo;
                            }

                            $transactionDataInsert = [
                                'fldid' => $fldid,
                                'AccountNo' => $accountNum,
                                'GroupId' => 0,
                                'TranAmount' => $datum->fldditemamt,
                                'TranDate' => date('Y-m-d', strtotime($datum->fldtime)),
                                'Remarks' => $datum->flditemname . "($request->today_date)",
                                'CreatedBy' => \Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                                'patbillingid' => $datum->fldid,
                                'amount' => $datum->flditemrate * $datum->flditemqty ,
                                'discount' => $datum->flddiscamt,
                                'tax' => $datum->fldtaxamt,
                                'finalamt' => $datum->fldditemamt,
                                'billnos' => $datum->fldbillno,
                                'is_return' => $is_return,
                                'deposit_id' => $patbillingdetail ? $patbillingdetail->fldid :0,
                                'deposit_amount' =>$patbillingdetail ? $patbillingdetail->fldprevdeposit : 0,
                                'discount_mode' => $datum->discount_mode ? $datum->discount_mode : 0,
                                'discountledger' =>  $discountledger ? $discountledger : 0,
                                'taxledger' => 0,
                                'CreatedDate' => now()



                            ];
                           // dd($transactionDataInsert);

                            if ($datum->flddiscamt != 0) {
                                $transactionDataInsert['DisAccountNo'] = ($datum->discount_account_map && $datum->discount_account_map->ledger) ? $datum->discount_account_map->ledger->AccountNo : Options::get('default_discount');
                                $transactionDataInsert['DisAmount'] = $datum->flddiscamt;
                            }

                            $insertTransaction = TempTransaction::create($transactionDataInsert);

                            if ($insertTransaction) {
                                PatBilling::where('fldid', $fldid)->update(['account_sync' => 1]);
                            }
                        }
                    }
                }

            }


            if ($datadeposit) {


                foreach ($datadeposit->chunk(100) as $chun) {

                    foreach ($chun as $datu) {
                        $is_refund = 0;
                       // if ($datu->accountServiceMap && $datum->accountServiceMap->accountGroup) {
                            /**account number*/



                            $fldid = $datu->fldid;
                            $type = substr($datu->fldbillno, 0, 3);

                            if($datu->fldreceivedamt < 0){
                                $is_refund = 1;
                            }

                            if($is_refund == 0){
                                $disLedger = GeneralLedgerMap::join('account_ledger','account_ledger.AccountId','=','account_general_map.ledger_id')
                                ->where('account_general_map.name','Deposit')->select('account_ledger.AccountNo')->first();
                            }else{
                                $disLedger = GeneralLedgerMap::join('account_ledger','account_ledger.AccountId','=','account_general_map.ledger_id')
                                ->where('account_general_map.name','Deposit Refund')->select('account_ledger.AccountNo')->first();
                            }

                            $accountNum = $disLedger ? $disLedger->AccountNo : 0;


                            $checkifdepositexist = TempDepositTransaction::select('fldid')->where('patbillingiddetail',$datu->fldid)->first();

                            if(!$checkifdepositexist){
                                $transactionDataDepositInsert = [
                                    'fldid' => $fldid,
                                    'AccountNo' => $accountNum,
                                  //  'GroupId' => $datu->accountServiceMap->sub_group_id,
                                    'TranAmount' => $datu->fldreceivedamt,
                                    'TranDate' => date('Y-m-d', strtotime($datu->fldtime)),
                                    'Remarks' => $datu->fldpayitemname . "($request->today_date)",
                                    'CreatedBy' => \Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                                    'patbillingiddetail' => $datu->fldid,
                                    'amount' =>  $datu->fldreceivedamt,

                                    'finalamt' =>  $datu->fldreceivedamt,
                                    'billnos' => $datu->fldbillno,
                                    'is_refuned' => $is_refund,
                                    'CreatedDate' => now()




                                ];

                                $insertTransactionDeposit = TempDepositTransaction::create($transactionDataDepositInsert);
                            }

                           // dd($transactionDataInsert);





                       // }
                    }
                }

            }


            \DB::commit();
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => false
            ]);
        }
    }

}
