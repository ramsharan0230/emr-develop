<?php

namespace Modules\Inpatient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ExpressMenuController extends Controller
{
    public function getExpressPdf(Request $request)
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        $itemtype = $request->get('itemtype');

      

        if ($encounter_id && in_array($itemtype, ['Medicines', 'Surgicals', 'Extra Items', 'Other'])) {
            $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
            $records = \App\PatBilling::select('tblpatbilling.fldid', 'tblpatbilling.fldtime', 'tblpatbilling.fldcomp', 'tblpatbilling.flditemname', 'tblpatbilling.flditemno', 'tblpatbilling.flditemrate', 'tblpatbilling.fldtaxper', 'tblpatbilling.flddiscper', 'tblpatbilling.flditemqty', 'tblpatbilling.fldditemamt as tot', 'tblpatbilling.flduserid', 'tblpatbilling.fldstatus', 'tblpatbilling.fldbillno', 'e.fldbatch', 'e.fldexpiry')
                ->join('tblentry AS e', 'e.fldstockno', '=', 'tblpatbilling.flditemno')
                ->where([
                    'tblpatbilling.fldencounterval' => $encounter_id,
                    'tblpatbilling.fldsave' => '1',
                    'tblpatbilling.flditemtype' => $itemtype,
                ])->get();

        return \Barryvdh\DomPDF\Facade::loadView('inpatient::pdf.expressPdf', compact('patientinfo', 'records', 'itemtype'))
            ->stream('express_report.pdf');
        }

        return redirect()->back()->with('error_message', 'Invalid patient id or report type.');
    }
}
