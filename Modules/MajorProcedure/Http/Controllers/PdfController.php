<?php

namespace Modules\MajorProcedure\Http\Controllers;

use App\Encounter;
use App\Pathdosing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Session;

class PdfController extends Controller
{
    public function phramacyPDF($encounterId = null) 
    {
        if ($encounterId == null){
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
       // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

        $data['patdosing'] = Pathdosing::where([
                'fldencounterval' => $encounterId,
                'fldsave_order' => 1,
                'flditemtype' => 'Medicines',
                'flddispmode' => 'IPD',
            ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', '!=', 'stat')
            ->where('fldfreq', '!=', 'PRN')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();

        return view('majorprocedure::layouts.pdf.phramacy-pdf', $data)/*->setPaper('a4')->stream*/;
    }
}
