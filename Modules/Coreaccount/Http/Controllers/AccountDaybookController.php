<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\Exports\AccountStatementExport;
use App\Exports\DayBookExport;
use App\Exports\VoucherDetailsExport;
use App\TransactionMaster;
use App\TransactionMasterPost;
use App\Utils\Helpers;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountDaybookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['voucher_types'] = TransactionMaster::distinct('VoucherCode')->pluck('VoucherCode')->toArray();
        $data['voucher_number'] = TransactionMaster::distinct('VoucherNo')->pluck('VoucherNo')->toArray();
        $data['users'] = TransactionMaster::distinct('CreatedBy')->pluck('CreatedBy')->toArray();
        return view('coreaccount::accountdaybook.index', $data);
    }

    public function filterDaybook(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
            $daybooks = TransactionMaster::select('TranId', 'AccountNo', 'GroupId', 'BranchId', 'VoucherNo', 'VoucherCode', 'TranDate', 'Remarks', 'CreatedBy', \DB::raw('sum(TranAmount) as Amount'), 'BranchId')
                ->where('TranDate', '>=', $finalfrom)
                ->where('TranDate', '<=', $finalto)
                ->where('TranAmount', '>', 0)
                ->when($request->voucher_type != "%", function ($q) use ($request) {
                    return $q->where('VoucherCode', $request->voucher_type);
                })
                ->when($request->voucher_number != "%", function ($q) use ($request) {
                    return $q->where('VoucherNo', $request->voucher_number);
                })
                ->when($request->user != "%", function ($q) use ($request) {
                    return $q->where('CreatedBy', $request->user);
                })
                ->groupBy('VoucherNo')
                ->groupBy('VoucherCode')
                ->paginate(15);
            $html = '';
            if (isset($daybooks) and count($daybooks) > 0) {
                foreach ($daybooks as $key => $daybook) {
                    $html .= "<tr>";
                    $html .= "<td>" . ++$key . "</td>";
                    $html .= "<td class='voucher_details'>" . $daybook->VoucherNo . "</td>";
                    $html .= "<td>" . $daybook->VoucherCode . "</td>";
                    $html .= "<td>" . ((isset($daybook->TranDate) ? Helpers::dateToNepali($daybook->TranDate) : '')) . "</td>";
                    $html .= "<td>" . $daybook->Amount . "</td>";
                    $html .= "<td>" . $daybook->CreatedBy . "</td>";
                    $html .= "<td></td>";
                    $html .= "</tr>";
                }
            }

            $html .= '<tr><td colspan="10">' . $daybooks->appends(request()->all())->links() . '</td></tr>';
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

    public function voucherDetails(Request $request)
    {
        $data['voucher_no'] = $voucher_no = $request->voucher_no;
        $data['date'] = date('Y-m-d');
        $voucher = explode("-", $voucher_no);
        $data['voucherDatas'] = TransactionMaster::where('VoucherNo', $data['voucher_no'])->orderBy('TranDate', 'asc')->get();
        return view('coreaccount::accountdaybook.voucher-details', $data);
    }

    public function printVoucherDetails(Request $request)
    {
        $data['voucher_no'] = $voucher_no = $request->voucher_no;
        $data['date'] = date('Y-m-d');
        $voucher = explode("-", $voucher_no);
        $data['voucherDatas'] = TransactionMaster::where('VoucherNo', $data['voucher_no'])->orderBy('TranDate', 'asc')->get();
        return view('coreaccount::accountstatement.pdf-voucher-details', $data);
    }

    public function closeDay()
    {
        DB::beginTransaction();
        try {
            TransactionMaster::chunk(100, function ($transactions) {
                foreach ($transactions as $transaction) {
                    $data = array(
                        'AccountNo' => $transaction->AccountNo,
                        'GroupId' => $transaction->GroupId,
                        'BranchId' => $transaction->BranchId,
                        'VoucherNo' => $transaction->VoucherNo,
                        'VoucherCode' => $transaction->VoucherCode,
                        'TranAmount' => $transaction->TranAmount,
                        'TranDate' => $transaction->TranDate,
                        'TranDateNep' => $transaction->TranDateNep,
                        'BillNo' => $transaction->BillNo,
                        'ChequeNo' => $transaction->ChequeNo,
                        'Narration' => $transaction->Narration,
                        'Remarks' => $transaction->Remarks,
                        'Field1' => $transaction->Field1,
                        'CreatedBy' => $transaction->CreatedBy,
                        'CreatedDate' => $transaction->CreatedDate
                    );
                    TransactionMasterPost::insert($data);
                    TransactionMaster::where('TranId', $transaction->TranId)->delete();
                }
            });
            DB::commit();
            Session::flash('message', 'Day Closed Successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('msg', 'Error Closing Day!');;
        }
    }

    public function exportExcel(Request $request)
    {

        $from_date = $request->from_date ?? '';
        $to_date = $request->to_date ?? '';
        $voucher_type = $request->voucher_type ?? '';
        $voucher_number = $request->voucher_number ?? '';
        $user = $request->user ?? '';
        ob_end_clean();
        ob_start();
        return Excel::download(new DayBookExport($from_date, $to_date, $voucher_type, $user, $voucher_number), 'DayBook-Report.xlsx');

    }

    public function exportPdf(Request $request)
    {
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['voucher_type'] = $request->voucher_type;
        $data['voucher_number'] = $request->voucher_number;
        $data['user'] = $request->user;

        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
            $daybooks = TransactionMaster::select('TranId', 'AccountNo', 'GroupId', 'BranchId', 'VoucherNo', 'VoucherCode', 'TranDate', 'Remarks', 'CreatedBy', \DB::raw('sum(TranAmount) as Amount'), 'BranchId')
                ->where('TranDate', '>=', $finalfrom)
                ->where('TranDate', '<=', $finalto)
                ->where('TranAmount', '>', 0)
                ->when($request->voucher_type != "%", function ($q) use ($request) {
                    return $q->where('VoucherCode', $request->voucher_type);
                })
                ->when($request->voucher_number != "%", function ($q) use ($request) {
                    return $q->where('VoucherNo', $request->voucher_number);
                })
                ->when($request->user != "%", function ($q) use ($request) {
                    return $q->where('CreatedBy', $request->user);
                })
                ->groupBy('VoucherNo')
                ->limit(20)
                ->get();
            $html = '';
            if (isset($daybooks) and count($daybooks) > 0) {
                foreach ($daybooks as $key => $daybook) {
                    $html .= "<tr>";
                    $html .= "<td>" . ++$key . "</td>";
                    $html .= "<td>" . ((isset($daybook->TranDate) ? Helpers::dateToNepali($daybook->TranDate) : '')) . "</td>";
                    $html .= "<td>" . $daybook->TranDate . "</td>";
                    $html .= "<td class='voucher_details'>" . $daybook->VoucherNo . "</td>";
                    $html .= "<td>" . $daybook->VoucherCode . "</td>";
                    $html .= "<td>" . ((isset($daybook->TranDate) ? Helpers::dateToNepali($daybook->TranDate) : '')) . "</td>";
                    $html .= "<td>" . $daybook->Amount . "</td>";
                    $html .= "<td>" . $daybook->CreatedBy . "</td>";
                    $html .= "<td></td>";
                    $html .= "</tr>";
                }
            }

            $data['html'] = $html;
            return view('coreaccount::accountdaybook.accountdaybook-pdf', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;

        } catch (\Exception $e) {
            dd($e);
        }


    }

    public function getVoucherNumber(Request $request){
        if($request->vouchertype == 'Journal'){
            $searchquery = 'JV-';
        }elseif($request->vouchertype == 'Payment'){
            $searchquery = 'PV-';
        }

        $vouchernumber = TransactionMaster::distinct('VoucherNo')->where('VoucherNo','Like',$searchquery.'%')->pluck('VoucherNo')->toArray();
        $html = '';
        if(isset($vouchernumber) and count($vouchernumber) > 0){
            $html .='<option value="">--All--</option>';
            foreach($vouchernumber as $vc){
                $html .='<option value="'.$vc.'">'.$vc.'</option>';
            }
        }
        echo $html; exit;
    }

}
