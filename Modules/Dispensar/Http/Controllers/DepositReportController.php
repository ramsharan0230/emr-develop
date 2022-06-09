<?php

namespace Modules\Dispensar\Http\Controllers;

use App\Encounter;
use App\Exports\DepositReportExport;
use App\Exports\DepositReportExportNew;
use App\PatBillDetail;
use App\PatBilling;
use App\Utils\Helpers;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DepositReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->full_date;
        return view('dispensar::report', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchDepositDetail(Request $request)
    {

        try {
	        $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->full_date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->full_date . " 23:59:59";
            $last_status = $request->last_status;
            $deposit = $request->deposit;
	        $type = $request->type;
            $expense = ($request->has('expense')) ? $request->expense : 0;
            $payment = ($request->has('payment')) ? $request->payment : 0;

            $depositData = PatBillDetail::select("tblencounter.fldregdate as fldregdate", "tblencounter.fldcurrlocat as fldcurrlocat", "tblencounter.fldpatientval as fldpatientval", "tblencounter.fldadmission as fldadmission", "tblencounter.fldadmitlocat as fldadmitlocat", "tblencounter.fldencounterval as fldencounterval", 'tblpatbilldetail.fldreceivedamt as fldcashdeposit','tblpatbilldetail.fldpayitemname as deposittype','tblpatbilldetail.fldbillno as depositbillno','tblpatbilldetail.fldtime as depositdate')
                ->join('tblencounter','tblencounter.fldencounterval','=','tblpatbilldetail.fldencounterval')
                ->when(($finalfrom == $finalto), function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilldetail.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto), function ($q) use ($finalfrom,$finalto) {
                    return $q->where('tblpatbilldetail.fldtime', '>=', $finalfrom)
                            ->where('tblpatbilldetail.fldtime', '<=', $finalto);
                })
                ->when($last_status !== "%", function ($q) use ($last_status) {
                    return $q->where('tblencounter.fldadmission', $last_status);

                })
                ->when(isset($type) && $type != 'All', function ($q) use ($type){
		            return $q->where('tblpatbilldetail.fldpayitemname',$type);
                })
	            ->where('tblpatbilldetail.fldbillno','like','DEP%')
                ->where('tblpatbilldetail.fldpayitemname','!=','Discharge Clearance')
                ->where('tblpatbilldetail.fldpayitemname','!=','Credit Clearance')
                ->where('tblpatbilldetail.fldcomp', Helpers::getCompName())
                ->with('patientInfo')
                ->groupBy('tblpatbilldetail.fldencounterval')
                ->paginate(25);

            $sn = $depositData->perPage() * ($depositData->currentPage() - 1) + 1;
	        $totOldDeposit = 0;
	        $totFinDeposit = 0;
	        $totRefDeposit = 0;
	        $totDeposit = 0;

            $html = '';
            if (isset($depositData->toArray()['data']) and count($depositData->toArray()['data']) > 0) {
                foreach ($depositData->toArray()['data'] as $depositDaton) {
                	$english_date = \Carbon\Carbon::parse($depositDaton['depositdate'])->format('Y-m-d');
                	$nepali_date = Helpers::dateEngToNepdash($english_date);
                    $html .= '<tr>';
	                $html .= '<td>' . $sn++ . '</td>';

	                if (isset($depositDaton['patient_info'])) {
                        $html .= '<td>' . $depositDaton['patient_info']['fldpatientval'] . '</td>';
                    } else {
                        $html .= '<td>' . $depositDaton['fldencounterval'] . '</td>';
                    }
                    $html .= '<td>' . $depositDaton['deposittype'] . '</td>';
                    $html .= '<td>' . $depositDaton['fldadmission'] . '</td>';
                    $html .= '<td>' . $depositDaton['fldencounterval'] . '</td>';
                    $html .= '<td>' . $depositDaton['patient_info']['fldptnamefir'] .' '.$depositDaton['patient_info']['fldptnamelast'].'</td>';
                    $html .= '<td>' . $depositDaton['depositbillno'] . '</td>';
                    $html .= '<td>' . $nepali_date->full_date. '</td>';

                    if($depositDaton['fldcashdeposit'] && $depositDaton['deposittype'] != 'Deposit Refund' && $depositDaton['deposittype'] != 'Pharmacy Deposit Refund'){
	                    $totOldDeposit += $depositDaton['fldcashdeposit'];
                        $html .= '<td>Rs ' . Helpers::numberFormat($depositDaton['fldcashdeposit']).'</td>';
                    }else{
                        $html .= '<td>Rs 0.00</td>';
                    }

	                if($depositDaton['fldcashdeposit'] && $depositDaton['deposittype'] == 'Deposit Refund' || $depositDaton['deposittype'] == 'Pharmacy Deposit Refund'){
	                	$refund = abs($depositDaton['fldcashdeposit']);
		                $totDeposit += $refund;
		                $html .= '<td>Rs ' . Helpers::numberFormat($refund) .'</td>';
	                }else{
		                $html .= '<td>Rs 0.00</td>';
	                }

	                $html .= '<td>Rs 0.00</td>';

                    $html .= '</tr>';
                }
            }
            $html .= '<tr><td colspan="11">' . $depositData->appends(request()->all())->render() . '</td></tr>';
	        $html .= '<tr><td><b><u>Total:</u></b></td>
						<td></td>
		                <td></td>
		                <td></td>
		                <td></td>
		                <td></td>
		                <td></td>
		                <td></td>
		                <td>' . Helpers::numberFormat($totOldDeposit) . '</td>
		                <td>' . Helpers::numberFormat($totDeposit) . '</td>
		                <td>Rs 0.00</td>
                        </tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function exportPdf(Request $request)
    {
        $from_date = Helpers::dateNepToEng($request->from_date);
        $data['finalfrom'] = $finalfrom = $from_date->full_date . " 00:00:00";
        $to_date = Helpers::dateNepToEng($request->to_date);
        $data['finalto'] = $finalto = $to_date->full_date . " 23:59:59";
        $data['last_status'] = $last_status = $request->lastStatus;
        $data['deposit'] = $deposit = $request->deposit;
        $data['type'] = $type = $request->type;
        $expense = $data['expense'] = ($request->has('expense')) ? $request->expense : 0;
        $payment = $data['payment'] = ($request->has('payment')) ? $request->payment : 0;
        $data['certificate'] = "DEPOSIT";

        $data['depositData'] = $depositData = PatBillDetail::select("tblencounter.fldregdate as fldregdate", "tblencounter.fldcurrlocat as fldcurrlocat", "tblencounter.fldpatientval as fldpatientval", "tblencounter.fldadmission as fldadmission", "tblencounter.fldadmitlocat as fldadmitlocat", "tblencounter.fldencounterval as fldencounterval", 'tblpatbilldetail.fldreceivedamt as fldcashdeposit','tblpatbilldetail.fldpayitemname as deposittype','tblpatbilldetail.fldbillno as depositbillno','tblpatbilldetail.fldtime as depositdate')
                ->join('tblencounter','tblencounter.fldencounterval','=','tblpatbilldetail.fldencounterval')
                ->when(($finalfrom == $finalto), function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilldetail.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto), function ($q) use ($finalfrom,$finalto) {
                    return $q->where('tblpatbilldetail.fldtime', '>=', $finalfrom)
                            ->where('tblpatbilldetail.fldtime', '<=', $finalto);
                })
                ->when($last_status !== "%", function ($q) use ($last_status) {
                    return $q->where('tblencounter.fldadmission', $last_status);
                })
		        ->when(isset($type) && $type != 'All', function ($q) use ($type){
			        return $q->where('tblpatbilldetail.fldpayitemname',$type);
		        })
		        ->where('tblpatbilldetail.fldpayitemname','!=','Discharge Clearance')
		        ->where('tblpatbilldetail.fldpayitemname','!=','Credit Clearance')
                ->where('tblpatbilldetail.fldbillno','like','DEP%')
                ->with('patientInfo')
                ->groupBy('tblpatbilldetail.fldencounterval')
                ->get();

        return view('dispensar::pdf.report', $data);
    }

    public function exportDepositReportCsv(Request $request)
    {
        $expense = $data['expense'] = ($request->has('expense')) ? $request->expense : 0;
        $payment = $data['payment'] = ($request->has('payment')) ? $request->payment : 0;
        $export = new DepositReportExport($request->from_date, $request->to_date, $request->lastStatus,$expense,$payment,$request->type);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'DepositReport.xlsx');
    }

    public function exportDepositReportCsvNew(Request $request)
    {
        $export = new DepositReportExportNew($request->from_date, $request->to_date, $request->lastStatus, $request->deposit);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'Deposit-Report-New.xlsx');
    }
}
