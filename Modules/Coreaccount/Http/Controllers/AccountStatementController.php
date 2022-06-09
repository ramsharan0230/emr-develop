<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\Exports\AccountStatementExport;
use App\Exports\VoucherDetailsExport;
use App\AccountLedger;
use App\TransactionView;
use App\Utils\Helpers;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AccountStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $fiscalYear = Helpers::getNepaliFiscalYearRange();
        $dateFiscalStart = Helpers::dateNepToEng($fiscalYear['startdate'])->full_date;
        $datevalueStart = Helpers::dateEngToNepdash($dateFiscalStart);
        $data['dateStart'] = $datevalueStart->year . '-' . $datevalueStart->month . '-' . $datevalueStart->date;

        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['accounts'] = AccountLedger::select('AccountName', 'AccountNo')->get();
        return view('coreaccount::accountstatement.index', $data);
    }

    public function filterStatement(Request $request)
    {
        // dd($request->all());
        try {
            $voucherCode = $request->voucher_code;
            $voucherNo = $request->voucher_number;
            $accountnumber = $request->account_num;
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . ' 00:00:00';
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . ' 23:59:59';
            $statements = TransactionView::where('TranDate', '>=', $finalfrom)
                ->where('TranDate', '<=', $finalto)
                ->where(function ($query) use ($voucherCode) {
                    if ($voucherCode != "")
                        $query->where('VoucherCode', 'LIKE', $voucherCode);
                })
                ->where(function ($query) use ($voucherNo) {
                    if ($voucherNo != "")
                        $query->where('VoucherNo', strtoupper($voucherNo));
                })
                ->where(function ($query) use ($accountnumber) {
                    if ($accountnumber!= "")
                        $query->where('AccountNo', $accountnumber);
                })
                // ->where('AccountNo',$request->account_no)
                ->orderBy('TranDate', 'asc')
                ->paginate(50);
            $opening = TransactionView::select(\DB::raw('sum(TranAmount) as Amount'))
                ->where('TranDate', '<', $finalfrom)

                 ->where('AccountNo',$accountnumber)
                ->first();

            $balance = 0;
            if($request->page > 1){
                $html = '';
            }else{
                $html = "<tr>
                        <td>1</td>

                        <td></td>
                        <td>Opening Balance</td>
                        <td></td>
                        <td></td>";
            if (isset($opening)) {
                $balance = $opening->Amount;
                if ($opening->Amount > 0) {
                    $opening_balance = $opening->Amount;
                    $html .= "<td>" . Helpers::numberFormat($opening_balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                } else {
                    $opening_balance = ($opening->Amount) * (-1);
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat($opening_balance) . "</td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                }
            } else {
                $html .= "<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>";
            }
            $html .= "</tr>";
            }
            if($request->page > 1){
                $i = 0;
            }else{
                $i = 1;
            }

            $credittotal = 0;
            $debittotal = 0;
            $totalbalance = 0;
            foreach ($statements as $key => $statement) {
                $html .= "<tr>";
                $html .= "<td>" . ++$i . "</td>";
                // if (isset($statement->branch)) {
                //     $html .= "<td>" . $statement->branch->name . "</td>";
                // } else {
                //     $html .= "<td></td>";
                // }
                $html .= "<td>" . ((isset($statement->TranDate) ? Helpers::dateToNepali($statement->TranDate) : '')) . "</td>";
                $html .= "<td>" . $statement->Narration . "</td>";
                $html .= "<td>" . $statement->VoucherCode . "</td>";
                // $html .= "<td class='voucher_details'>".$statement->VoucherCode ."-". $statement->VoucherNo."</td>";
                $html .= "<td class='voucher_details' style='cursor: pointer'>" . $statement->VoucherNo . "</td>";

                if ($statement->TranAmount > 0) {
                    $amount = $statement->TranAmount;
                    $balance += $amount;
                    $html .= "<td>" . Helpers::numberFormat($amount) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat(abs($balance)) . "</td>";
                    $debittotal +=$amount;
                    // $html .= "<td>Debited</td>";
                } else {
                    $amount = $statement->TranAmount * (-1);
                    $balance -= $amount;
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat($amount) . "</td>";
                    $html .= "<td>" . Helpers::numberFormat(abs($balance)) . "</td>";
                    $credittotal +=$amount;
                    // $html .= "<td>Credited</td>";
                }
                $totalbalance += $balance;
                $html .= "<td>" . $statement->ChequeNo . "</td>";
                $html .= "<td>" . $statement->Remarks . "</td>";
                $html .= "</tr>";
            }
            // $html .='<tr><td></td><td></td><td></td><td></td><td></td><td>'.$debittotal.'</td><td>'.$credittotal.'</td><td></td><td></td><td></td></tr>';
            $html .= '<tr><td colspan="20">' . $statements->appends(request()->all())->links() . '</td></tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html
                ]
            ]);

        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function exportStatement(Request $request)
    {
        $voucherCode = $request->voucher_code;
        $voucherNo = $request->voucher_number;
        $accountnumber = $request->account_number;
        $from_date = Helpers::dateNepToEng($request->from_date);
        $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . ' 00:00:00';
        $to_date = Helpers::dateNepToEng($request->to_date);
        $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . ' 23:59:59';
        $export = new AccountStatementExport($finalfrom, $finalto, $voucherCode, $voucherNo, $accountnumber);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'AccountStatement.xlsx');
    }

    public function printStatement(Request $request)
    {
        try {
            $voucherCode = $request->voucher_code;
            $from_date = Helpers::dateNepToEng($request->from_date);
            $voucherNo = $request->voucher_number;
            $accountnumber = $request->account_number;
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . ' 00:00:00';
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . ' 23:59:59';
            $statements = TransactionView::where('TranDate', '>=', $finalfrom)
                ->where('TranDate', '<=', $finalto)
                ->where(function ($query) use ($voucherCode) {
                    if ($voucherCode != "")
                        $query->where('VoucherCode', 'LIKE', $voucherCode);
                })
                ->where(function ($query) use ($voucherNo) {
                    if ($voucherNo != "")
                        $query->where('VoucherNo', strtoupper($voucherNo));
                })
                ->where(function ($query) use ($accountnumber) {
                    if ($accountnumber!= "")
                        $query->where('AccountNo', $accountnumber);
                })
                // ->where('AccountNo',$request->account_no)
                ->orderBy('TranDate', 'asc')
                ->get();

            $data['from_date'] = $finalfrom;
            $data['to_date'] = $finalto;
            $data['account_no'] = $request->account_no;
            $opening = TransactionView::select(\DB::raw('sum(TranAmount) as Amount'))
            ->where('TranDate', '<', $finalfrom)

            ->where('AccountNo',$accountnumber)
           ->first();
            $html = "";
            $html .= "<tr>
            <td>1</td>

            <td></td>
            <td>Opening Balance</td>
            <td></td>
            <td></td>";
            if (isset($opening)) {
                $balance = $opening->Amount;
                if ($opening->Amount > 0) {
                    $opening_balance = $opening->Amount;
                    $html .= "<td>" . Helpers::numberFormat($opening_balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                } else {
                    $opening_balance = ($opening->Amount) * (-1);
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat($opening_balance) . "</td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                }
            } else {
                $html .= "<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>";
            }
            $html .= "</tr>";
            $i = 1;
            $balance = 0;
            $inWords = 0;
            foreach ($statements as $key => $statement) {
                $html .= "<tr>";
                $html .= "<td>" . ++$i . "</td>";
                /*if (isset($statement->branch)) {
                    $html .= "<td>" . $statement->branch->name . "</td>";
                } else {
                    $html .= "<td></td>";
                }*/
                $html .= "<td>" . ((isset($statement->TranDate) ? Helpers::dateToNepali($statement->TranDate) : '')) . "</td>";
                $html .= "<td>" . $statement->TranDate . "</td>";
                $html .= "<td>" . $statement->Narration . "</td>";
                $html .= "<td>" . $statement->VoucherCode . "</td>";
                $html .= "<td>" . $statement->VoucherNo . "</td>";
                if ($statement->TranAmount > 0) {
                    $inWords += $statement->TranAmount;
                    $balance += $statement->TranAmount;
                    $html .= "<td>" . Helpers::numberFormat($statement->TranAmount) . "</td>";
                    $html .= "<td>0</td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td>".$statement->ChequeNo."</td>";
                    $html .= "<td  style='text-align:left;'>".$statement->Remarks."</td>";
                } else {
                    $balance += $statement->TranAmount;
                    $html .= "<td>0</td>";
                    $html .= "<td>" . Helpers::numberFormat($statement->TranAmount) . "</td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td>".$statement->ChequeNo."</td>";
                    $html .= "<td style='text-align:left;'>".$statement->Remarks."</td>";
                }
                $html .= "</tr>";
            }
            $data['html'] = $html;
            $data['inWords'] = $inWords;
            return view('coreaccount::accountstatement.pdf', $data);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function voucherDetails(Request $request)
    {
        $data['voucher_no'] = $voucher_no = $request->voucher_no;

        $voucher = explode("-", $voucher_no);
        $data['voucherDatas'] = TransactionView::where('VoucherNo', $data['voucher_no'])->orderBy('TranDate', 'asc')->get();
        $data['date'] = $data['voucherDatas']->first() ? $data['voucherDatas']->first()->TranDate : date('Y-m-d');
        return view('coreaccount::accountstatement.voucher-details', $data);
    }

    public function exportVoucherDetails(Request $request)
    {
        $voucher_no = $request->voucher_no;
        $export = new VoucherDetailsExport($voucher_no);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'VoucherDetail_' . $voucher_no . '.xlsx');
    }

    public function printVoucherDetails(Request $request)
    {
        $data['voucher_no'] = $voucher_no = $request->voucher_no;

        $voucher = explode("-", $voucher_no);
        $data['voucherDatas'] = TransactionView::where('VoucherNo', $data['voucher_no'])->orderBy('TranDate', 'asc')->get();
        $data['date'] = $data['voucherDatas']->first() ? $data['voucherDatas']->first()->TranDate : date('Y-m-d');
        return view('coreaccount::accountstatement.pdf-voucher-details', $data);
    }
}
