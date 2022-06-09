<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use App\TransactionMaster;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TransactionUpdateController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     * @param string $voucherNumber
     * @return Response
     */
    public function edit($voucherNumber)
    {
        $data['accounts'] = AccountLedger::all();

        $data['VoucherData'] = TransactionMaster::where('VoucherNo', $voucherNumber)->get();

        $data['VoucherCode'] = $data['VoucherData'][0]->VoucherCode;
        $data['TranDate'] = $data['VoucherData'][0]->TranDate;
        $data['ChequeNo'] = $data['VoucherData'][0]->ChequeNo;
        $data['Remarks'] = $data['VoucherData'][0]->Remarks;
        return view('coreaccount::transaction.update', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $insertData['TranDate'] = $request->transaction_date;
            $insertData['ChequeNo'] = $request->cheque_number;
            $insertData['Remarks'] = $request->remarks_textarea;
            for ($i = 0, $iMax = count($request->tranID); $i < $iMax; $i++) {
                $insertData['AccountNo'] = $request->account_name[$i];
                $insertData['TranAmount'] = $request->debit_credit[$i] == '+' ? $request->amount[$i] : $request->amount[$i] * (-1);
                $insertData['Narration'] = $request->Narration[$i];

                $transctionData = TransactionMaster::where('TranId', $request->tranID[$i])->first();
                $transctionData->update($insertData);
            }

            return redirect()->route('accounts.statement.index')->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {

        }

    }
}
