<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use App\GeneralLedgerMap;
use App\AutoId;
use App\Entry;
use App\EntryAccountLedgerView;
use App\HospitalDepartmentUsers;
use App\TempTransaction;
use App\TransactionMaster;
use App\TempDepositTransaction;

use App\Utils\Options;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;
use DB;
use Exception;
use App\PatBillDetail;
use App\PatBilling;
use App\Utils\Helpers;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $data['accounts'] = AccountLedger::all();

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->where('user_id', $user->id)
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        }

        return view('coreaccount::transaction.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
     //   dd($request->all());
        $validated = $request->validate([
            'accountId.*' => 'required',
            'voucher_entry.*' => 'required',
            'amount.*' => 'required',
        ]);

        try {
            \DB::beginTransaction();
            $transactionVoucherName = 'transactionVoucher'; // this is for journal

            if ($request->voucher_entry[0] == "Payment") {
                $transactionVoucherName = 'transactionVoucherPayment';
            }
            if ($request->voucher_entry[0] == "Receipt") {
                $transactionVoucherName = 'transactionVoucherReceipt';
            }
            if ($request->voucher_entry[0] == "Contra") {
                $transactionVoucherName = 'transactionVoucherContra';
            }
            $newAutoIdForTransaction = AutoId::where('fldtype', $transactionVoucherName)->first();

            if ($newAutoIdForTransaction) {
                $newTransactionNumber = $newAutoIdForTransaction->fldvalue;
                $newTransactionNumberUpdate = $newAutoIdForTransaction->fldvalue + 1;
                AutoId::where('fldtype', $transactionVoucherName)
                    ->update(['fldvalue' => $newTransactionNumberUpdate]);
            } else {
                $newTransactionNumber = 1;
                $newTransactionNumberUpdate = 2;
                AutoId::create(['fldtype' => $transactionVoucherName, 'fldvalue' => $newTransactionNumberUpdate]);
            }
            $voucherNo = '';
            for ($i = 0, $iMax = count($request->accountId); $i < $iMax; $i++) {
                $voucherInitial = '';
                if ($request->voucher_entry[$i] == "Journal") {
                    $voucherInitial = 'JV-';
                }
                if ($request->voucher_entry[$i] == "Payment") {
                    $voucherInitial = 'PV-';
                }
                if ($request->voucher_entry[$i] == "Receipt") {
                    $voucherInitial = 'RV-';
                }
                if ($request->voucher_entry[$i] == "Contra") {
                    $voucherInitial = 'CV-';
                }
                $voucherNo = $insertData['VoucherNo'] = $voucherInitial . $newTransactionNumber;

                $groupID = AccountLedger::select('GroupId', 'AccountName')->where('AccountNo', $request->accountId[$i])->first();
                $insertData['AccountNo'] = $request->accountId[$i];
                $insertData['GroupId'] = $groupID->GroupId;
                $insertData['VoucherCode'] = $request->voucher_entry[$i];
                $insertData['TranAmount'] = $request->amount[$i];
                $insertData['TranDate'] = $request->transaction_date;
                $insertData['ChequeNo'] = $request->cheque_number;
                $insertData['Narration'] = $request->narration[$i];
                $insertData['Remarks'] = $request->remarks_textarea;
                $insertData['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertData['CreatedDate'] = date("Y-m-d H:i:s");
                // echo 'test';
                // dd($insertData);
                TransactionMaster::create($insertData);
            }
            \DB::commit();
            Session::flash('voucher_number', $voucherNo);
            return redirect()->back()->with('success_message', __('messages.success', ['name' => 'Transaction']));
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }

    public function tempTransactionSync($AccountId = '')
    {
        if (Options::get('default_cash_in_hand') === false) {
            return redirect()->back()->with('error_message', 'Cash in hand not set.');
        }

        //         SELECT
        // account_ledger.AccountNo,
        // account_ledger.AccountName,
        // sum( tblpatbilling.fldditemamt )
        // FROM
        // tblpatbilling
        // JOIN tblservicecost ON tblpatbilling.flditemname = tblservicecost.flditemname
        // join account_ledger on tblservicecost.account_ledger = account_ledger.AccountNo
        // where tblpatbilling.fldsave = '1'
        // and tblpatbilling.fldtime >= '2021-10-18 00:00:00'
        // and tblpatbilling.fldtime <= '2021-11-15 00:00:00'
        // group by account_ledger.AccountName

        $data['accounts'] = AccountLedger::all();
        $data['ledgers'] = $ledgers = TempTransaction::where('sync', 0)
            ->with(['accountLedger', 'accountLedgerDiscount', 'patbill.billDetail'])
            ->get();

        if (is_countable($ledgers) && count($ledgers) > 0) {
            $data['AccountName'] = $data['ledgers'][0]->accountLedger ? $data['ledgers'][0]->accountLedger->AccountName : '';
            $data['Narration'] = $data['ledgers'][0] ? $data['ledgers'][0]->Narration : '';
        } else {
            return redirect()->route('map.list.by.account')->with('error_message', 'No data to sync.');
        }


        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->where('user_id', $user->id)
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        }

        return view('coreaccount::transaction.sync-transaction', $data);
    }

    public function tempTransactionAdd(Request $request)
    {
          //dd($request->all());
        $validated = $request->validate([
            'accountId.*' => 'required',
            'voucher_entry.*' => 'required',
            'amount.*' => 'required',
        ]);

        //dd($request);

        $from_date = $request->fromdate ? $request->fromdate : date('Y-m-d');
        $to_date = $request->todate ? $request->todate : date('Y-m-d');

        $startTime = $request->fromdate ? $request->fromdate.' 00:00:00' : date('Y-m-d').' 00:00:00';
        $endTime = $request->todate ? $request->todate.' 23:59:59' : date('Y-m-d').' 23:59:59';

        $compid = $request->department ;

        $fromdate = date('Y-m-d',strtotime($from_date));

        $transactionDate = $request->transaction_date;

        $tempsumArr = PatBilling::select(DB::raw("SUM(tblpatbilling.flditemrate*tblpatbilling.flditemqty) as amount"))
        ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
        ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
        ->where('tblpatbilling.fldsave' ,'=','1')
        ->where('tblpatbilling.account_sync' ,'=','0')
        ->where('tblpatbilling.fldtime','>',$startTime)
        ->where('tblpatbilling.fldtime','<',$endTime)
        ->where('tblpatbilling.fldbillno','not like' ,'RET%')

       ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
        ->get();
        $tempsum =  $tempsumArr[0]->amount;




        $tempRsumAA =  PatBilling::select(DB::raw("SUM(tblpatbilling.flditemrate*tblpatbilling.flditemqty) as amount"))
        ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
        ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
        ->where('tblpatbilling.fldsave' ,'=','1')
        ->where('tblpatbilling.account_sync' ,'=','0')
        ->where('tblpatbilling.fldtime','>',$startTime)
        ->where('tblpatbilling.fldtime','<',$endTime)
        ->where('tblpatbilling.fldbillno','like' ,'RET%')
        ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
        ->get();
        $tempRsum =  $tempRsumAA[0]->amount;





        $patbillingreceivedAmount  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->sum('fldreceivedamt');
           // dd($data['PrevDeposit']);



        $sumdisdataCAA  = PatBilling::select(DB::raw('sum(tblpatbilling.flddiscamt) as amount'))
                    ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
                    ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
                    ->where('tblpatbilling.fldsave' ,'=','1')
                    ->where('tblpatbilling.account_sync' ,'=','0')
                    ->where('tblpatbilling.fldtime','>',$startTime)
                    ->where('tblpatbilling.fldtime','<',$endTime)
                    ->where('tblpatbilling.flddiscamt','<' ,'0')
                    ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
                    ->get();

                    $sumdisdataC = $sumdisdataCAA[0]->amount;



        $sumdisdataAA = PatBilling::select(DB::raw('sum(tblpatbilling.flddiscamt) as amount'))
            ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
            ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
            ->where('tblpatbilling.fldsave', '=','1')
            ->where('tblpatbilling.account_sync' ,'=','0')
            ->where('tblpatbilling.fldtime','>',$startTime)
            ->where('tblpatbilling.fldtime','<',$endTime)
            ->where('tblpatbilling.flddiscamt','>' ,'0')
            ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')

            ->get();
            $sumdisdata = $sumdisdataAA[0]->amount;

        $temppreviousdeposit = PatBillDetail::where('fldtime','>=', $startTime)
        ->where('fldtime','<=', $endTime)
        ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
        ->where('fldpayitemname','NOT LIKE','Credit Clearance')
        ->sum('fldprevdeposit');


        $curdeposit  = PatBillDetail::where('fldtime','>=', $startTime)
        ->where('fldtime','<=', $endTime)
        ->where('fldcurdeposit', '>',0)
        ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
        ->sum('fldcurdeposit');
            //dd(DB::getQueryLog());

          $patientcredit = PatBillDetail::where('fldtime','>=', $startTime)
          ->where('fldtime','<=', $endTime)
          ->where(function ($query) use ($startTime) {
              $query->orwhere('fldcurdeposit', '<', 0);
              $query->orwhere('fldpayitemname', '=', 'Discharge Clearance');
              $query->orwhere('fldbillno','LIKE','%CRE%');
          })

          ->where('fldcurdeposit', '<',0)
          ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
          ->sum('fldcurdeposit');

            $prevcr  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->where(function ($query) use ($startTime) {

                $query->orwhere('fldpayitemname', '=', 'Credit Clearance');
                $query->orwhere('fldbillno','LIKE','%CRE%');
                $query->orwhere('fldbillno','LIKE','%RET%');
            })

            ->sum('fldprevdeposit');





        if($patbillingreceivedAmount < 0){
            $totalDebit = abs($sumdisdata) + abs($temppreviousdeposit)+abs($tempRsum)+abs($patientcredit) ;
            $totalcollection = abs($tempsum) + abs($request->amountCash) + abs($sumdisdataC) + abs($curdeposit)+abs($prevcr);
        }else{
            $totalDebit = abs($request->amountCash) + abs($sumdisdata)  + abs($temppreviousdeposit)+abs($tempRsum)+abs($patientcredit);
            $totalcollection = abs($tempsum) + abs($sumdisdataC) + abs($curdeposit)+abs($prevcr);
        }

        // if ($totalDebit != $totalcollection) {
        //     return redirect()->back()->with('error_message', "Transaction amount mismatch");
        // }

        try {
            \DB::beginTransaction();
           // dd($request);
            $newAutoIdForTransaction = AutoId::where('fldtype', 'transactionVoucher')->first();
            if ($newAutoIdForTransaction) {
                $newTransactionNumber = $newAutoIdForTransaction->fldvalue;
                $newTransactionNumberUpdate = $newAutoIdForTransaction->fldvalue + 1;
                AutoId::where('fldtype', 'transactionVoucher')->update(['fldvalue' => $newTransactionNumberUpdate]);
            } else {
                $newTransactionNumber = 1;
                $newTransactionNumberUpdate = 1;
                AutoId::create(['fldtype' => 'transactionVoucher', 'fldvalue' => $newTransactionNumberUpdate]);
            }
            $voucherInitial = '';

            $voucher_entry = $request->voucher_entryCash;
            if ($request->voucher_entryCash === "Journal") {
                $voucherInitial = 'JV-';
            }
            if ($request->voucher_entryCash === "Payment") {
                $voucherInitial = 'PV-';
            }
            if ($request->voucher_entryCash === "Receipt") {
                $voucherInitial = 'RV-';
            }
            if ($request->voucher_entryCash === "Contra") {
                $voucherInitial = 'CV-';
            }

            $insertDataNonTempCashin = [];
            /**insert entry except form temp table CASH IN HAND*/
            $insertDataNonTempCashin['VoucherNo'] = $voucherInitial . $newTransactionNumber;

            $groupID = AccountLedger::select('GroupId', 'AccountName')->where('AccountNo', $request->accountIdCash)->first();
            $insertDataNonTempCashin['AccountNo'] = $request->accountIdCash;
            $insertDataNonTempCashin['GroupId'] =  $groupID ? $groupID->GroupId : 0;
            $insertDataNonTempCashin['VoucherCode'] = $voucher_entry;
            $insertDataNonTempCashin['TranAmount'] = $request->amountCash;
            $insertDataNonTempCashin['TranDate'] = isset($transactionDate) ?  $transactionDate : date('Y-m-d');
            $insertDataNonTempCashin['ChequeNo'] = $request->cheque_number;
            $insertDataNonTempCashin['Narration'] =  $groupID ?  $groupID->AccountName . ' (' .$fromdate. ')' : 0;
            $insertDataNonTempCashin['Remarks'] =  $request->remarks_textarea;
            $insertDataNonTempCashin['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $insertDataNonTempCashin['CreatedDate'] = date("Y-m-d H:i:s");
            //dd($insertDataNonTemp);
            TransactionMaster::create($insertDataNonTempCashin);



            if(!empty($request->accountId)){
                foreach($request->accountId as $c => $newentry){
                    $insertDataNonTempneww = [];
                    $groupId = Helpers::getAccountGroupId($newentry);
                    $insertDataNonTempneww['AccountNo'] = $newentry;
                    $insertDataNonTempneww['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                    $insertDataNonTempneww['GroupId'] = $groupId;
                    $insertDataNonTempneww['VoucherCode'] = $request->voucher_entry[$c];
                    $insertDataNonTempneww['TranAmount'] =  $request->amount[$c];
                    $insertDataNonTempneww['TranDate'] = isset($transactionDate) ?  $transactionDate : date('Y-m-d');
                    $insertDataNonTempneww['ChequeNo'] = $request->cheque_number;
                    $insertDataNonTempneww['Narration'] =  $request->narration[$c]  . ' (' .$fromdate. ')' ;
                    $insertDataNonTempneww['Remarks'] =  $request->remarks_textarea;
                    $insertDataNonTempneww['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                    $insertDataNonTempneww['CreatedDate'] = date("Y-m-d H:i:s");
                    //dd($insertDataNonTempneww);
                    TransactionMaster::create($insertDataNonTempneww);


                }
            }


            $temppreviousdeposit = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->where('fldpayitemname','NOT LIKE','Credit Clearance')
            ->sum('fldprevdeposit');



            //dd(DB::getQueryLog());




            if (isset($temppreviousdeposit)) {
                $insertDataNonTemptest = [];
                $account = Helpers::getAccountLedger('Previous Deposit');

                $groupId = Helpers::getAccountGroupId($account[0]);
                $insertDataNonTemptest['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                $insertDataNonTemptest['AccountNo'] = $account[0] ? $account[0] :0;
                $insertDataNonTemptest['GroupId'] = $groupId;
                $insertDataNonTemptest['VoucherCode'] = $voucher_entry;
                $insertDataNonTemptest['TranAmount'] =$temppreviousdeposit;
                $insertDataNonTemptest['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');
                $insertDataNonTemptest['Narration'] = $account[1].' for ' . ' (' . $fromdate . ')';
                $insertDataNonTemptest['Remarks'] = $request->remarks_textarea;
                $insertDataNonTemptest['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertDataNonTemptest['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::insert($insertDataNonTemptest);

            }
            $curdeposit  =PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('fldcurdeposit', '>',0)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->sum('fldcurdeposit');

            if (isset($curdeposit)) {
                $insertDataNonTempDeposit = [];

                $account = Helpers::getAccountLedger('Deposit and collection');
                $groupId = Helpers::getAccountGroupId($account[0]);

                $insertDataNonTempDeposit['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                $insertDataNonTempDeposit['AccountNo'] = $account[0] ? $account[0] :0;
                $insertDataNonTempDeposit['GroupId'] = $groupId;
                $insertDataNonTempDeposit['VoucherCode'] = $voucher_entry;
                $insertDataNonTempDeposit['TranAmount'] =-$curdeposit;
                $insertDataNonTempDeposit['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');
                $insertDataNonTempDeposit['Narration'] = $account[1].' for ' . ' (' . $fromdate . ')';
                $insertDataNonTempDeposit['Remarks'] = $request->remarks_textarea;
                $insertDataNonTempDeposit['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertDataNonTempDeposit['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::insert($insertDataNonTempDeposit);

            }


            $patientcredit  =  PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where(function ($query) use ($startTime) {
                $query->orwhere('fldcurdeposit', '<', 0);
                $query->orwhere('fldpayitemname', '=', 'Discharge Clearance');
                $query->orwhere('fldbillno','LIKE','%CRE%');
            })

            ->where('fldcurdeposit', '<',0)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->sum('fldcurdeposit');

            if (isset($patientcredit)) {
                $insertDataNonTempDeposit = [];

                $account = Helpers::getAccountLedger('Patient Credit');
                $groupId = Helpers::getAccountGroupId($account[0]);

                $insertDataNonTempDepositc['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                $insertDataNonTempDepositc['AccountNo'] = $account[0] ? $account[0] :0;
                $insertDataNonTempDepositc['GroupId'] = $groupId;
                $insertDataNonTempDepositc['VoucherCode'] = $voucher_entry;
                $insertDataNonTempDepositc['TranAmount'] = abs($patientcredit);
                $insertDataNonTempDepositc['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');
                $insertDataNonTempDepositc['Narration'] = $account[1].' for ' . ' (' . $fromdate . ')';
                $insertDataNonTempDepositc['Remarks'] = $request->remarks_textarea;
                $insertDataNonTempDepositc['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertDataNonTempDepositc['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::insert($insertDataNonTempDepositc);

            }

            $prevcr =$data['Prevcr']  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->where(function ($query) use ($startTime) {

                $query->orwhere('fldpayitemname', '=', 'Credit Clearance');
                $query->orwhere('fldbillno','LIKE','%CRE%');
                $query->orwhere('fldbillno','LIKE','%RET%');
            })

            ->sum('fldprevdeposit');

            if (isset($prevcr)) {
                $insertDataNonTempDeposit = [];

                $account = Helpers::getAccountLedger('Previous Credit');
                $groupId = Helpers::getAccountGroupId($account[0]);

                $insertDataNonTempDepositpc['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                $insertDataNonTempDepositpc['AccountNo'] = $account[0] ? $account[0] :0;
                $insertDataNonTempDepositpc['GroupId'] = $groupId;
                $insertDataNonTempDepositpc['VoucherCode'] = $voucher_entry;
                $insertDataNonTempDepositpc['TranAmount'] = $prevcr;
                $insertDataNonTempDepositpc['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');
                $insertDataNonTempDepositpc['Narration'] = $account[1].' for ' . ' (' . $fromdate . ')';
                $insertDataNonTempDepositpc['Remarks'] = $request->remarks_textarea;
                $insertDataNonTempDepositpc['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertDataNonTempDepositpc['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::insert($insertDataNonTempDepositpc);

            }





            $countRows = 1;
            $accountIds = [];
            $grandTotal = 0;
            $sumTotalDebit = 0;
            $ledgerDiscount = [];
            $tempIdArray = [];

            $creditDisc = PatBilling::select(DB::raw('sum(tblpatbilling.flddiscamt ) as amount'))
                    ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
                    ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
                    ->where('tblpatbilling.fldsave' ,'=','1')
                    ->where('tblpatbilling.account_sync' ,'=','0')
                    ->where('tblpatbilling.fldtime','>',$startTime)
                    ->where('tblpatbilling.fldtime','<',$endTime)
                    ->where('tblpatbilling.flddiscamt','<' ,'0')
                    ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
                    ->get();
            $data['creditDisc'] = $creditDisc[0]->amount ? $creditDisc[0]->amount : 0;
            $data['creditDiscDetail'] = $creditDiscDetail = [$creditDisc[0]->AccountName,$creditDisc[0]->AccountNo];


            $debitDisc = PatBilling::select('account_ledger.accountname',DB::raw('sum(tblpatbilling.flddiscamt) as amount'))
            ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
            ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
            ->where('tblpatbilling.fldsave', '=','1')
            ->where('tblpatbilling.account_sync' ,'=','0')
            ->where('tblpatbilling.fldtime','>',$startTime)
            ->where('tblpatbilling.fldtime','<',$endTime)
            ->where('tblpatbilling.flddiscamt','>' ,'0')
            ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
            ->get();
            $data['debitDisc'] = $debitDisc[0]->amount ? $debitDisc[0]->amount : 0;
            $data['debitDiscDetail'] = $debitDiscDetail = [$debitDisc[0]->AccountName,$debitDisc[0]->AccountNo];


            if (isset($data['creditDisc'])) {
                $creditba = 0;
                if($data['creditDisc'] < 0){
                    $creditba = $data['creditDisc'];
                }else{
                    $creditba = -$data['creditDisc'];
                }
                $insertDataNonTemp = [];
                $accno =  !empty($creditDiscDetail[1]) ? $creditDiscDetail[1] : '2000000005';
                $groupId = Helpers::getAccountGroupId($accno);
                $insertDataNonTemp['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                $insertDataNonTemp['AccountNo'] = !empty($creditDiscDetail[1]) ? $creditDiscDetail[1] : '2000000005';
                $insertDataNonTemp['GroupId'] = $groupId;
                $insertDataNonTemp['VoucherCode'] = $voucher_entry;
                $insertDataNonTemp['TranAmount'] =  $creditba;
                $insertDataNonTemp['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');
                $insertDataNonTemp['Narration'] = 'Discount for' . ' (' . $fromdate . ')';
                $insertDataNonTemp['Remarks'] = $request->remarks_textarea;
                $insertDataNonTemp['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertDataNonTemp['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::insert($insertDataNonTemp);

            }

            if (isset($data['debitDisc'])) {
                $insertDataNonTempDiscount = [];
                $accno =  !empty($debitDiscDetail[1]) ? $debitDiscDetail[1] : '2000000005';
                $groupId = Helpers::getAccountGroupId($accno);
                $insertDataNonTempDiscount['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                $insertDataNonTempDiscount['AccountNo'] = !empty($debitDiscDetail[1]) ? $debitDiscDetail[1] : '2000000005';
                $insertDataNonTempDiscount['GroupId'] = $groupId;
                $insertDataNonTempDiscount['VoucherCode'] = $voucher_entry;
                $insertDataNonTempDiscount['TranAmount'] = $data['debitDisc'];
                $insertDataNonTempDiscount['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');

                $insertDataNonTempDiscount['Narration'] = 'Discount for' . ' (' . $fromdate . ')';
                $insertDataNonTempDiscount['Remarks'] = $request->remarks_textarea;
                $insertDataNonTempDiscount['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertDataNonTempDiscount['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::insert($insertDataNonTempDiscount);

            }





            $datatoinsert = [];

            $data['ledgersCredit'] =   PatBilling::select('tblpatbilling.fldid','account_ledger.AccountName','account_ledger.AccountNo',DB::raw("SUM(tblpatbilling.flditemrate*tblpatbilling.flditemqty) as amount"))
            ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
            ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
            ->where('tblpatbilling.fldsave' ,'=','1')
            ->where('tblpatbilling.account_sync' ,'=','0')
            ->where('tblpatbilling.fldtime','>',$startTime)
            ->where('tblpatbilling.fldtime','<',$endTime)
            ->where('tblpatbilling.fldbillno','not like' ,'RET%')
            ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
            ->groupBy('tblservicecost.account_ledger')
            ->get();

            if ($data['ledgersCredit'] ) {
                foreach ($data['ledgersCredit']  as $key => $ledger) {
                    $groupId = Helpers::getAccountGroupId($ledger->AccountNo);
                        $datatoinsert[$key]['accountIdCash'] =  $ledger->AccountNo;
                        $datatoinsert[$key]['GroupId'] = $groupId;
                        $datatoinsert[$key]['AccountName'] =  $ledger->AccountName ? $ledger->AccountName : '';
                        $datatoinsert[$key]['amountCash'] =  -$ledger->amount;
                        $datatoinsert[$key]['type'] = 'Cr';
                        $datatoinsert[$key]['TranDate'] =  $from_date;
                        $datatoinsert[$key]['ChequeNo'] = 0;
                        $datatoinsert[$key]['today_date'] = $ledger->CreatedDate;


                }

            }


            if (isset($datatoinsert)) {
                foreach ($datatoinsert as $datas) {
                    $insertDataNonTemp = [];
                    //dd($groupID);
                    $groupId = Helpers::getAccountGroupId($datas['accountIdCash']);
                    $insertDataNonTemp['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                    $insertDataNonTemp['AccountNo'] = $datas['accountIdCash'];
                    $insertDataNonTemp['GroupId'] = $groupId;
                    $insertDataNonTemp['VoucherCode'] = $voucher_entry;
                    $insertDataNonTemp['TranAmount'] = $datas['amountCash'];
                    $insertDataNonTemp['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');
                    $insertDataNonTemp['ChequeNo'] = $datas['ChequeNo'];
                    $insertDataNonTemp['Narration'] = $datas['AccountName'] . ' (' .  $fromdate . ')';
                    $insertDataNonTemp['Remarks'] = $request->remarks_textarea;
                    $insertDataNonTemp['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                    $insertDataNonTemp['CreatedDate'] = date("Y-m-d H:i:s");
                   // dd($insertDataNonTemp);
                    TransactionMaster::insert($insertDataNonTemp);


                }
            }

            $datatoinsertReturn = [];

            $data['ledgersReturn'] = PatBilling::select('tblpatbilling.fldid','account_ledger.AccountName', 'account_ledger.AccountNo',DB::raw("SUM(tblpatbilling.flditemrate*tblpatbilling.flditemqty) as amount"))
            ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
            ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
            ->where('tblpatbilling.fldsave' ,'=','1')
            ->where('tblpatbilling.account_sync' ,'=','0')
            ->where('tblpatbilling.fldtime','>',$startTime)
            ->where('tblpatbilling.fldtime','<',$endTime)
            ->where('tblpatbilling.fldbillno','like' ,'RET%')
            ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
            ->groupBy('tblservicecost.account_ledger')
            ->get();

            if ($data['ledgersReturn'] ) {
                foreach ($data['ledgersReturn']  as $key => $ledger) {
                    $groupId = Helpers::getAccountGroupId($ledger->AccountNo);
                        $datatoinsertReturn[$key]['accountIdCash'] =  $ledger->AccountNo;
                        $datatoinsertReturn[$key]['GroupId'] = $groupId;
                        $datatoinsertReturn[$key]['AccountName'] =  $ledger->AccountName ? $ledger->AccountName : '';
                        $datatoinsertReturn[$key]['amountCash'] =  abs($ledger->amount);
                        $datatoinsertReturn[$key]['type'] = 'Dr';
                        $datatoinsertReturn[$key]['TranDate'] = $request->transaction_date;
                        $datatoinsertReturn[$key]['ChequeNo'] = 0;
                        $datatoinsertReturn[$key]['today_date'] = $ledger->CreatedDate;

                }

            }

            if (isset($datatoinsertReturn)) {
                foreach ($datatoinsertReturn as $data1) {
                    $insertDataNonTemp = [];
                    //dd($groupID);
                    $groupId = Helpers::getAccountGroupId($data1['accountIdCash']);
                    $insertDataNonTemp['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                    $insertDataNonTemp['AccountNo'] = $data1['accountIdCash'];
                    $insertDataNonTemp['GroupId'] = $groupId;
                    $insertDataNonTemp['VoucherCode'] = $voucher_entry;
                    $insertDataNonTemp['TranAmount'] = $data1['amountCash'];
                    $insertDataNonTemp['TranDate'] = isset($transactionDate) ? $transactionDate : date('Y-m-d');
                    $insertDataNonTemp['ChequeNo'] = $data1['ChequeNo'];
                    $insertDataNonTemp['Narration'] = $data1['AccountName'] . ' (' .  $fromdate . ')';
                    $insertDataNonTemp['Remarks'] = $request->remarks_textarea;
                    $insertDataNonTemp['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                    $insertDataNonTemp['CreatedDate'] = date("Y-m-d H:i:s");
                   //dd($insertDataNonTemp);
                    TransactionMaster::insert($insertDataNonTemp);


                }
            }

            PatBilling::where('fldtime','>=', $startTime)->where('fldtime','<=', $endTime)->update(['account_sync' => 1]);

            //yaha samma
           // dd($datatoinsert);











        //    var urlReport = baseUrl + "/account/daybook/voucher-details?voucher_no=" + $(this).html();
        //    window.open(urlReport, '_blank');


            \DB::commit();
            return redirect()->route('accounts.voucher.details', ['voucher_no' => $voucherInitial . $newTransactionNumber]);
        } catch (\Exception $e) {
            dd($e);
            \DB::rollBack();
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }

    public function viewMapItems($AccountNo)
    {
        $data['items'] = EntryAccountLedgerView::where('AccountNo', $AccountNo)
            ->paginate(100);

        $data['AccountName'] = AccountLedger::where('AccountNo', $AccountNo)->first();

        return view('coreaccount::account-group-map.view-by-account', $data);
    }

    public function miscellaneous()
    {
        $data['notMapped'] = Entry::select('fldstockid')->doesnthave('accountServiceMap')->orderBy('fldstockid', 'asc')->distinct('fldstockid')->paginate(100);

        return view('coreaccount::account-group-map.miscellaneout', $data);
    }

    //yaha display ko lagi

    public function tempTransactionAddAll(Request $request)
    {
        if (Options::get('default_cash_in_hand') === false) {
            return redirect()->back()->with('error_message', 'Cash in hand not set.');
        }

        $data['fromdate'] = $startTime = Carbon::parse($request->fromdate)->setTime(00, 00, 00);
        $data['todate'] = $endTime = Carbon::parse($request->todate)->setTime(23, 59, 59);
        $data['dateshow'] =  $request->fromdate;
        $data['department'] = $compid = $request->department;
       // dd($request);
       $data['alreadysynced'] = PatBilling::select('account_sync')->where('tblpatbilling.fldtime','>',$startTime)
       ->where('tblpatbilling.fldtime','<',$endTime)->first();



        $data['accounts'] = AccountLedger::all();
        $data['LedgerAccount'] = PatBilling::select('account_ledger.AccountName','account_ledger.AccountNo')
        ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
        ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
        ->where('tblpatbilling.fldsave' ,'=','1')
        ->where('tblpatbilling.account_sync' ,'=','0')
        ->where('tblpatbilling.fldtime','>',$startTime)
        ->where('tblpatbilling.fldtime','<',$endTime)
        ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
        ->groupBy('tblservicecost.account_ledger')
        ->get();

        $data['creditdiscountdata'] = PatBilling::select(
        'tblpatbilling.fldencounterval'
        ,'tblpatbilling.fldbillno'
        ,'tblpatbilling.flditemname'
        ,'tblpatbilling.fldditemamt'
        ,'tblpatbilling.flditemrate'
        ,'tblpatbilling.flditemqty'
        ,'tblpatbilling.fldtaxamt'
        ,'tblpatbilling.flddiscamt'
        ,'tblpatbilling.fldditemamt','tblpatbilling.flduserid')
                    ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
                    ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
                    ->where('tblpatbilling.fldsave' ,'=','1')
                    ->where('tblpatbilling.account_sync' ,'=','0')
                    ->where('tblpatbilling.fldtime','>',$startTime)
                    ->where('tblpatbilling.fldtime','<',$endTime)
                    ->where('tblpatbilling.flddiscamt','<' ,'0')
                    ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
                    ->get();
        $data['debitdiscountdata'] = PatBilling::select(
            'tblpatbilling.fldencounterval'
            ,'tblpatbilling.fldbillno'
            ,'tblpatbilling.flditemname'
            ,'tblpatbilling.fldditemamt'
            ,'tblpatbilling.flditemrate'
            ,'tblpatbilling.flditemqty'
            ,'tblpatbilling.fldtaxamt'
            ,'tblpatbilling.flddiscamt'
            ,'tblpatbilling.fldditemamt','tblpatbilling.flduserid')
                    ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
                    ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
                    ->where('tblpatbilling.fldsave' ,'=','1')
                    ->where('tblpatbilling.account_sync' ,'=','0')
                    ->where('tblpatbilling.fldtime','>',$startTime)
                    ->where('tblpatbilling.fldtime','<',$endTime)
                    ->where('tblpatbilling.flddiscamt','>' ,'0')
                    ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
                    ->get();


        $data['depositdata'] = PatBillDetail::select('fldencounterval',
        'fldbillno'
        ,'fldpayitemname'
        ,'fldprevdeposit'
        ,'flditemamt'
        ,'fldtaxamt'
        ,'flddiscountamt'
        ,'fldchargedamt'
        ,'fldreceivedamt'
        ,'fldcurdeposit','flduserid')
        ->where('fldbillno','LIKE', 'DEP%')
            ->where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('fldreceivedamt', '>', 0)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->get();


        $data['PrevDepositdata']  = PatBillDetail::select('fldencounterval',
        'fldbillno'
        ,'fldpayitemname'
        ,'fldprevdeposit'
        ,'flditemamt'
        ,'fldtaxamt'
        ,'flddiscountamt'
        ,'fldchargedamt'
        ,'fldreceivedamt'
        ,'fldcurdeposit','flduserid')->where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->get();


        $data['patbillingreceivedAmountdata']  = PatBillDetail::select('fldencounterval',
        'fldbillno'
        ,'fldpayitemname'
        ,'fldprevdeposit'
        ,'flditemamt'
        ,'fldtaxamt'
        ,'flddiscountamt'
        ,'fldchargedamt'
        ,'fldreceivedamt'
        ,'fldcurdeposit','flduserid')->where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->get();

        $data['curdepositdata']  = PatBillDetail::select('fldencounterval',
        'fldbillno'
        ,'fldpayitemname'
        ,'fldprevdeposit'
        ,'flditemamt'
        ,'fldtaxamt'
        ,'flddiscountamt'
        ,'fldchargedamt'
        ,'fldreceivedamt'
        ,'fldcurdeposit','flduserid')->where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->get();



        $data['ledgerAll'] = PatBilling::select('account_ledger.AccountName','account_ledger.AccountNo'
        ,'tblpatbilling.fldencounterval'
        ,'tblpatbilling.fldbillno'
        ,'tblpatbilling.flditemname'
        ,'tblpatbilling.fldditemamt'
        ,'tblpatbilling.flditemrate'
        ,'tblpatbilling.flditemqty'
        ,'tblpatbilling.fldtaxamt'
        ,'tblpatbilling.flddiscamt'
        ,'tblpatbilling.fldditemamt','tblpatbilling.flduserid')
        ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
        ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
        ->where('tblpatbilling.fldsave' ,'=','1')
        ->where('tblpatbilling.account_sync' ,'=','0')
        ->where('tblpatbilling.fldtime','>',$startTime)
        ->where('tblpatbilling.fldtime','<',$endTime)
        ->where('tblpatbilling.fldbillno','not like' ,'RET%')
        ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
        ->orderBy('account_ledger.AccountNo','ASC')
        ->get();

        $data['ledgerAllR'] = PatBilling::select('account_ledger.AccountName','account_ledger.AccountNo'
        ,'tblpatbilling.fldencounterval'
        ,'tblpatbilling.fldbillno'
        ,'tblpatbilling.flditemname'
        ,'tblpatbilling.fldditemamt'
        ,'tblpatbilling.flditemrate'
        ,'tblpatbilling.flditemqty'
        ,'tblpatbilling.fldtaxamt'
        ,'tblpatbilling.flddiscamt'
        ,'tblpatbilling.fldditemamt','tblpatbilling.flduserid')
        ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
        ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
        ->where('tblpatbilling.fldsave' ,'=','1')
        ->where('tblpatbilling.account_sync' ,'=','0')
        ->where('tblpatbilling.fldtime','>',$startTime)
        ->where('tblpatbilling.fldtime','<',$endTime)
        ->where('tblpatbilling.fldbillno','like' ,'%RET%')
        ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
        ->orderBy('account_ledger.AccountNo','ASC')
        ->get();





        $data['ledgersCredit'] = $ledgers =  PatBilling::select('account_ledger.AccountName','account_ledger.AccountNo',DB::raw("SUM(tblpatbilling.flditemrate*tblpatbilling.flditemqty) as amount"))
        ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
        ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
        ->where('tblpatbilling.fldsave' ,'=','1')
        ->where('tblpatbilling.account_sync' ,'=','0')
        ->where('tblpatbilling.fldtime','>',$startTime)
        ->where('tblpatbilling.fldtime','<',$endTime)
        ->where('tblpatbilling.fldbillno','not like' ,'RET%')
        ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
        ->groupBy('tblservicecost.account_ledger')
        ->get();
        //dd($data['ledgersCredit']);



        $data['ledgersReturn'] = PatBilling::select('account_ledger.AccountName','account_ledger.AccountNo', DB::raw("SUM(tblpatbilling.flditemrate*tblpatbilling.flditemqty) as amount"))
        ->join('tblservicecost','tblpatbilling.flditemname', '=', 'tblservicecost.flditemname')
        ->join('account_ledger','account_ledger.AccountNo', '=', 'tblservicecost.account_ledger')
        ->where('tblpatbilling.fldsave' ,'=','1')
        ->where('tblpatbilling.account_sync' ,'=','0')
        ->where('tblpatbilling.fldtime','>',$startTime)
        ->where('tblpatbilling.fldtime','<',$endTime)
        ->where('tblpatbilling.fldbillno','like' ,'RET%')
        ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
        ->groupBy('tblservicecost.account_ledger')
        ->get();

        $creditDisc = PatBilling::select('account_ledger.AccountName','account_ledger.AccountNo',DB::raw('sum(tblpatbilling.flddiscamt ) as amount'))
                    ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
                    ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
                    ->where('tblpatbilling.fldsave' ,'=','1')
                    ->where('tblpatbilling.account_sync' ,'=','0')
                    ->where('tblpatbilling.fldtime','>',$startTime)
                    ->where('tblpatbilling.fldtime','<',$endTime)
                    ->where('tblpatbilling.flddiscamt','<' ,'0')
                    ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
                    ->get();
            $data['creditDisc'] = $creditDisc[0]->amount ? $creditDisc[0]->amount : 0;
            $data['creditDiscDetail'] = [$creditDisc[0]->AccountName,$creditDisc[0]->AccountNo];





        $debitDisc = PatBilling::select('account_ledger.AccountName','account_ledger.AccountNo',DB::raw('sum(tblpatbilling.flddiscamt) as amount'))
            ->join('discount_account_map','discount_account_map.discount_name', '=', 'tblpatbilling.discount_mode')
            ->join('account_ledger','account_ledger.AccountId', '=', 'discount_account_map.ledger_id')
            ->where('tblpatbilling.fldsave', '=','1')
            ->where('tblpatbilling.account_sync' ,'=','0')
            ->where('tblpatbilling.fldtime','>',$startTime)
            ->where('tblpatbilling.fldtime','<',$endTime)
            ->where('tblpatbilling.flddiscamt','>' ,'0')
            ->where('tblpatbilling.fldcomp','LIKE', '%'.$compid.'%')
            ->get();
            $data['debitDisc'] = $debitDisc[0]->amount ? $debitDisc[0]->amount : 0;
            $data['debitDiscDetail'] = [$debitDisc[0]->AccountName,$debitDisc[0]->AccountNo];


        $data['deposit'] = PatBillDetail::where('fldbillno','LIKE', 'DEP%')
            ->where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('fldreceivedamt', '>', 0)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->sum('fldreceivedamt');


        $data['PrevDeposit']  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->where('fldpayitemname','NOT LIKE','Credit Clearance')
            ->sum('fldprevdeposit');


            $data['Prevcr']  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->where(function ($query) use ($startTime) {

                $query->orwhere('fldpayitemname', '=', 'Credit Clearance');
                $query->orwhere('fldbillno','LIKE','%CRE%');
                $query->orwhere('fldbillno','LIKE','%RET%');
            })

            ->sum('fldprevdeposit');
            //dd(DB::getQueryLog());

        $data['patbillingreceivedAmount']  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->sum('fldreceivedamt');
           // dd($data['PrevDeposit']);

        $data['curdeposit']  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where('fldcurdeposit', '>',0)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->sum('fldcurdeposit');

        $data['patientcredit']  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where(function ($query) use ($startTime) {
                $query->orwhere('fldcurdeposit', '<', 0);
                $query->orwhere('fldpayitemname', '=', 'Discharge Clearance');
                $query->orwhere('fldbillno','LIKE','%CRE%');
            })

            ->where('fldcurdeposit', '<',0)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->sum('fldcurdeposit');

            $data['patientcreditdetail']  = PatBillDetail::where('fldtime','>=', $startTime)
            ->where('fldtime','<=', $endTime)
            ->where(function ($query) use ($startTime) {
                $query->orwhere('fldcurdeposit', '<', 0);
                $query->orwhere('fldpayitemname', '=', 'Discharge Clearance');
                $query->orwhere('fldbillno','LIKE','%CRE%');
            })

            ->where('fldcurdeposit', '<',0)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->get();


        $data['advanceDetail'] = PatBillDetail::whereNotNull('fldpayitemname')
        ->where('tblpatbilldetail.fldtime','>=',$startTime)
        ->where('tblpatbilldetail.fldtime','<=',$endTime)
        //->where('fldcurdeposit', '>',0)
        ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
        ->get();
        $data['depositonly'] = $data['deposit'] ;
        $data['remainingdeposit'] = $data['curdeposit'] - $data['deposit'] ;



            $data['differences'] = PatBillDetail::select('tblpatbilling.fldbillno',
            'fldprevdeposit as PRVDep',
            DB::raw('sum( tblpatbilling.flditemrate * tblpatbilling.flditemqty ) as rateQTY'),
            DB::raw('sum(tblpatbilling.flddiscamt) as DIS'),
            'tblpatbilldetail.flddiscountamt as detaildis',
            'tblpatbilldetail.flditemamt as Detailrateqty',
            DB::raw('sum(fldditemamt) as gross'),
            'fldreceivedamt as final',
            'fldcurdeposit as remain'
            )
            ->join('tblpatbilling','tblpatbilling.fldbillno', '=', 'tblpatbilldetail.fldbillno')
            ->where('tblpatbilling.fldtime','>=',$startTime)
            ->where('tblpatbilling.fldtime','<=',$endTime)
            ->where('tblpatbilldetail.fldcomp','LIKE', '%'.$compid.'%')
            ->groupBy('tblpatbilling.fldbillno')
            ->get();






           // dd($data['debitDisc']);

        if (is_countable($ledgers) && count($ledgers) > 0) {
            $data[]['AccountName'] = $data['ledgersCredit'][0]->AccountName ? $data['ledgersCredit'][0]->AccountName : '';
            $data[]['Narration'] = $data['ledgersCredit'][0] ? $data['ledgersCredit'][0]->Narration : '';
        }
        // else {
        //     // return response()->json([
        //     //     'success' => false
        //     // ]);
        //     return redirect()->route('map.list.by.account')->with('error_message', 'No data to sync.');
        // }

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->where('user_id', $user->id)
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        }

        return view('coreaccount::transaction.sync-transaction-all', $data);
    }


//     function change_depart_append(){
//         $bigrekobills =DB::select("SELECT
//         pbd.fldbillno,
//         ( pbd.flditemamt - pbd.fldreceivedamt ),
//         pbd.flddiscountamt,
//         pbd.flditemamt,
//         ( ( pbd.flditemamt - pbd.fldreceivedamt ) - pbd.flddiscountamt ),
//         sum( pb.flddiscamt ),
//         sum( pb.fldditemamt )
//         from tblpatbilldetail pbd
//         JOIN tblpatbilling pb ON pbd.fldbillno = pb.fldbillno
//         AND pbd.fldcomp = 'comp01'

//       AND pbd.fldtime > '2021-09-12 00:00:00'
//  AND pbd.fldtime < '2021-09-12 23:59:59'



//         AND pbd.fldpayitemname IS NULL
//         AND ( ( pbd.flditemamt - pbd.fldreceivedamt ) - pbd.flddiscountamt ) != '0'
//         GROUP BY
//         pbd.fldbillno");


//         if($bigrekobills){
//             foreach($bigrekobills  as $bills){
//                 $patbilling = PatBilling::where('fldbillno',$bills->fldbillno)->get();
//                 if(!empty($patbilling)){
//                     foreach($patbilling as $pb){
//                        // \DB::connection()->enableQueryLog();
//                        $val = (($pb->flditemrate*$pb->flditemqty) - $pb->fldditemamt);
//                         PatBilling::where('fldid',$pb->fldid)->update(['flddiscamt'=> number_format($val)]);
//                        // print_r(\DB::getQueryLog());

//                         echo $bills->fldbillno.(($pb->flditemrate*$pb->flditemqty) - $pb->fldditemamt).'<br>';

//                     }
//                 }


//             }
//         }

//         if($bigrekobills){
//             foreach($bigrekobills  as $bills){
//                 $patbilling = PatBillDetail::where('fldbillno',$bills->fldbillno)->get();
//                 if(!empty($patbilling)){
//                     foreach($patbilling as $pbd){
//                     $valv = (($pbd->flditemamt - $pbd->fldreceivedamt));
//                         PatBillDetail::where('fldid',$pbd->fldid)->update(['flddiscountamt'=> number_format($valv)]);

//                     }
//                 }
//                 echo $bills->fldbillno.(($pbd->flditemamt - $pbd->fldreceivedamt)).'<br>';

//             }
//         }




//     }
}
