<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use App\AutoId;
use App\Entry;
use App\EntryAccountLedgerView;
use App\HospitalDepartmentUsers;
use App\TempTransaction;
use App\TransactionMaster;
use App\Utils\Options;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class TransactionsyncController extends Controller
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

    public function tempTransactionSync($AccountId)
    {
        if (Options::get('default_cash_in_hand') === false) {
            return redirect()->back()->with('error_message', 'Cash in hand not set.');
        }

        $data['accounts'] = AccountLedger::all();
        $data['ledgers'] = $ledgers = TempTransaction::where('AccountNo', $AccountId)
            ->where('sync', 0)
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
        // dd($request->all());
        $validated = $request->validate([
            'accountId.*' => 'required',
            'voucher_entry.*' => 'required',
            'amount.*' => 'required',
        ]);
        // $transIds = explode("--", $request->tempId);
        $transIds = $request->tempId;
        $totalDebit = abs($request->amountCash) + (TempTransaction::select('DisAmount')->whereIn('TranId', $transIds)->pluck('DisAmount'))->sum();

        if ($totalDebit != (TempTransaction::select('TranAmount')->whereIn('TranId', $transIds)->pluck('TranAmount'))->sum()) {
            return redirect()->back()->with('error_message', "Transaction amount mismatch");
        }

        try {
            \DB::beginTransaction();
            $newAutoIdForTransaction = AutoId::where('fldtype', 'transactionVoucher')->first();
            if ($newAutoIdForTransaction) {
                $newTransactionNumber = $newAutoIdForTransaction->fldvalue;
                $newTransactionNumberUpdate = $newAutoIdForTransaction->fldvalue + 1;
                AutoId::where('fldtype', 'transactionVoucher')->where('fldtype', 'transactionVoucher')->update(['fldvalue' => $newTransactionNumberUpdate]);
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

            /**insert entry except form temp table CASH IN HAND*/
            $insertDataNonTemp['VoucherNo'] = $voucherInitial . $newTransactionNumber;

            $groupID = AccountLedger::select('GroupId', 'AccountName')->where('AccountNo', $request->accountIdCash)->first();

            $insertDataNonTemp['AccountNo'] = $request->accountIdCash;
            $insertDataNonTemp['GroupId'] = $groupID->GroupId;
            $insertDataNonTemp['VoucherCode'] = $voucher_entry;
            $insertDataNonTemp['TranAmount'] = $request->amountCash;
            $insertDataNonTemp['TranDate'] = isset($request->TranDate) ? $request->TranDate : date('Y-m-d');
            $insertDataNonTemp['ChequeNo'] = $request->cheque_number;
            $insertDataNonTemp['Narration'] = $groupID->AccountName . ' (' . $request->today_date . ')';
            $insertDataNonTemp['Remarks'] = 'BIlling/Cash (' . $request->today_date . ')';
            $insertDataNonTemp['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $insertDataNonTemp['CreatedDate'] = date("Y-m-d H:i:s");

            TransactionMaster::create($insertDataNonTemp);

            /**insert entry except form temp table*/
            $insertData = [];

            for ($i = 0, $iMax = count($transIds); $i < $iMax; $i++) {

                $insertData['VoucherNo'] = $voucherInitial . $newTransactionNumber;

                $tempData = TempTransaction::where('TranId', $transIds[$i])->first();

                $groupID = AccountLedger::select('GroupId', 'AccountName')->where('AccountNo', $tempData->AccountNo)->first();

                $insertData['AccountNo'] = $tempData->AccountNo;
                $insertData['GroupId'] = $groupID->GroupId;
                $insertData['VoucherCode'] = $voucher_entry;
                $insertData['TranAmount'] = $tempData->TranAmount * (-1);
                /**because all transactions are credit*/
                $insertData['TranDate'] = isset($tempData->TranDate) ? $tempData->TranDate : date('Y-m-d');
                $insertData['ChequeNo'] = $tempData->cheque_number;
                $insertData['Narration'] = $groupID->AccountName . ' (' . $request->today_date . ')';
                $insertData['Remarks'] = 'Collection from (' . $request->today_date . ')';
                $insertData['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertData['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::create($insertData);

                /**Discount insert in transaction table*/
                if ($tempData->DisAccountNo != 0) {
                    $groupIDDiscount = AccountLedger::select('GroupId', 'AccountName')->where('AccountNo', $tempData->DisAccountNo)->first();
                    $insertDataDiscount['AccountNo'] = $tempData->DisAccountNo;
                    $insertDataDiscount['GroupId'] = $groupIDDiscount->GroupId;
                    $insertDataDiscount['VoucherCode'] = $voucher_entry;
                    $insertDataDiscount['VoucherNo'] = $voucherInitial . $newTransactionNumber;
                    $insertDataDiscount['TranAmount'] = $tempData->DisAmount;
                    /**because all transactions are credit*/
                    $insertDataDiscount['TranDate'] = isset($tempData->TranDate) ? $tempData->TranDate : date('Y-m-d');
                    $insertDataDiscount['ChequeNo'] = $tempData->cheque_number;
                    $insertDataDiscount['Narration'] = $groupIDDiscount->AccountName . ' (' . $request->today_date . ')';
                    $insertDataDiscount['Remarks'] = 'Discount (' . $request->today_date . ')';
                    $insertDataDiscount['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                    $insertDataDiscount['CreatedDate'] = date("Y-m-d H:i:s");

                    TransactionMaster::create($insertDataDiscount);
                }

                if (isset($transIds[$i])) {
                    TempTransaction::where('TranId', $transIds[$i])->update(['sync' => 1]);
                }
            }
            \DB::commit();
            return redirect()->route('map.list.by.account')->with('success_message', __('messages.success', ['name' => 'Transaction']));
        } catch (\Exception $e) {
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

    public function tempTransactionAddAll()
    {
        if (Options::get('default_cash_in_hand') === false) {
            return redirect()->back()->with('error_message', 'Cash in hand not set.');
        }
        $data['accounts'] = AccountLedger::all();
        $data['ledgers'] = $ledgers = TempTransaction::where('sync', 0)
            ->with(['accountLedger', 'patbill.billDetail'])
            ->get();

        if (is_countable($ledgers) && count($ledgers) > 0) {
            $data[]['AccountName'] = $data['ledgers'][0]->accountLedger ? $data['ledgers'][0]->accountLedger->AccountName : '';
            $data[]['Narration'] = $data['ledgers'][0] ? $data['ledgers'][0]->Narration : '';
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

        return view('coreaccount::transaction.sync-transaction-all', $data);
    }
}
