<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AutoId;
use App\TransactionMaster;
use Illuminate\Routing\Controller;

class UpdateVoucherNoController extends Controller
{
    public function updateVoucherNumber($initials)
    {
        $transactions = TransactionMaster::where('VoucherNo', 'LIKE', $initials . '%')->groupBY('VoucherNo')->orderBy('TranId', 'asc')->get();
        $i = 1;
        foreach ($transactions->chunk(50) as $chunk) {
            foreach ($chunk as $product) {
                TransactionMaster::where('VoucherNo', $product->VoucherNo)->update(['VoucherNo' => $initials . '-' . $i]);
                $i++;
            }
        }

        foreach ($transactions->chunk(50) as $chunk) {
            foreach ($chunk as $product) {
                echo $product->TranId . '-' . $product->VoucherNo;
                echo '<br>';
            }
        }
        $transactionVoucherName = '';

        if ($initials == "JV") {
            $transactionVoucherName = 'transactionVoucherJournal';
        }
        if ($initials == "PV") {
            $transactionVoucherName = 'transactionVoucherPayment';
        }
        if ($initials == "RV") {
            $transactionVoucherName = 'transactionVoucherReceipt';
        }
        if ($initials == "CV") {
            $transactionVoucherName = 'transactionVoucherContra';
        }
        $newAutoIdForTransaction = AutoId::where('fldtype', $transactionVoucherName)->first();
        $newTransactionNumberUpdate = $i;
        if ($newAutoIdForTransaction) {
            AutoId::where('fldtype', $transactionVoucherName)
                ->update(['fldvalue' => $newTransactionNumberUpdate]);
        } else {
            AutoId::create(['fldtype' => $transactionVoucherName, 'fldvalue' => $newTransactionNumberUpdate]);
        }
    }
}
