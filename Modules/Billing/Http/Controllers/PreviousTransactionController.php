<?php

namespace Modules\Billing\Http\Controllers;

use App\Encounter;
use App\PatBilling;
use Illuminate\Routing\Controller;

class PreviousTransactionController extends Controller
{
    public function receivedAmount($encounter)
    {
        $encounterData = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->get();

        $data['enpatient'] = $encounterData->first();

        $data['totalAmountReceivedByEncounter'] = PatBilling::where('fldsave', 1)
            ->where('fldencounterval', $encounter)
            ->get();

        return view('billing::previous-details.received', $data);
    }

    public function tpAmount($encounter)
    {
        $encounterData = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->get();
        $encounterIdsForTpBill = Encounter::where('fldpatientval', $encounterData[0]->fldpatientval)->pluck('fldencounterval');
        $data['enpatient'] = $encounterData->first();

        $data['totalAmountReceivedByEncounter'] = PatBilling::where('fldsave', 1)
            ->whereIn('fldencounterval', $encounterIdsForTpBill)
            ->where('fldtempbillno', '!=', null)
            ->where('fldcomp','=',\App\Utils\Helpers::getCompName())
            ->get();

        return view('billing::previous-details.received', $data);
    }
}
