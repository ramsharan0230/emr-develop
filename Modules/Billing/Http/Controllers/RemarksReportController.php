<?php

namespace Modules\Billing\Http\Controllers;

use App\PatBillDetail;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RemarksReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $name = $request->get('name');
        $remark = $request->get('remark');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $remarks = PatBillDetail::select('fldid', 'fldencounterval', 'fldbillno', 'fldtime', 'remarks')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptcontact,fldptsex,fldptbirday,fldrank',
            ])->where('fldtime', ">=", "{$from_date} 00:00:00")
            ->where('fldtime', "<=", "{$to_date} 23:59:59.999");

        if ($encounter_id)
            $remarks->where('fldencounterval', $encounter_id);
        if ($remark)
            $remarks->where('remarks', 'like', "%{$remark}%");
        if ($name)
            $remarks->whereHas('encounter.patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', "%{$name}%");
            });

        $from_date = Helpers::dateEngToNepdash($from_date)->full_date;
        $to_date = Helpers::dateEngToNepdash($to_date)->full_date;

        return view('billing::remarks.report', [
            'remarks' => $remarks->paginate(50),
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }


    public function reportPdf(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $name = $request->get('name');
        $remark = $request->get('remark');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $remarks = PatBillDetail::select('fldid', 'fldencounterval', 'fldbillno', 'fldtime', 'remarks')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptcontact,fldptsex,fldptbirday,fldrank',
            ])->where('fldtime', ">=", "{$from_date} 00:00:00")
            ->where('fldtime', "<=", "{$to_date} 23:59:59.999");

        if ($encounter_id) {
            $remarks->where('fldencounterval', $encounter_id);
        }
        if ($remark) {
            $remarks->where('remarks', 'like', "%{$remark}%");
        }
        if ($name) {
            $remarks->whereHas('encounter.patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', "%{$name}%");
            });
        }

        return view('billing::remarks.report-pdf', [
            'remarks' => $remarks->get(),
        ]);
    }
}
