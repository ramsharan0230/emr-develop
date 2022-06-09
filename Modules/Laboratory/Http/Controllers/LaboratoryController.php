<?php

namespace Modules\Laboratory\Http\Controllers;

use App\CostGroup;
use App\Encounter;
use App\GroupTest;
use App\MacAccess;
use App\NoDiscount;
use App\PatBilling;
use App\PatLabTest;
use App\ServiceCost;
use App\Settings;
use App\Utils\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);

        $data['encounterId'] = $request->encounterId;
        $data['settings'] = Settings::where('fldindex', 'comp01:LowDeposit/Test')
            ->orWhere('fldindex', 'comp01:FixPayableUser/Test')
            ->get();

        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        $data['macAddress'] = MacAccess::select('fldcompname')->where('fldcomp', Helpers::getCompName())->get();

        $data['costGroup'] = CostGroup::where('flditemtype', 'Test')->distinct()->get();

        $data['patBilling'] = PatBilling::select('fldid', 'fldtime', 'fldordtime', 'flditemname', 'fldrefer', 'fldtarget', 'fldstatus')
            ->where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'Diagnostic Tests')
            ->where('fldsave', '1')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Done')
                    ->orWhere('fldstatus', '=', 'Cleared');
            })
            ->get();

        $data['patBillingList'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
            ->where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'Diagnostic Tests')
            ->where('fldsave', 0)
            ->get();

        $data['patBillingListPunched'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
            ->where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'Diagnostic Tests')
            ->where('fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Punched')
                    ->orWhere('fldstatus', '=', 'Cancelled');
            })
            ->get();

        //        select flditemname from tblservicecost where (fldgroup='General' or fldgroup='%') and fldstatus='Active'
        $flditemname = ServiceCost::where(function ($query) {
            return $query
                ->orWhere('fldgroup', '=', 'General')
                ->orWhere('fldgroup', '=', '%');
        })
            ->where('fldstatus', 'Active')
            ->pluck('flditemname');

        $data['itemsForMultiselect'] = GroupTest::select('fldgroupname')->whereIn('fldgroupname', $flditemname)
            ->groupBy('fldgroupname')
            ->get();

        $data['patlabtest'] = PatLabTest::where('fldencounterval', $request->encounterId)
            ->where('fldstatus', 'Sampled')
            ->select('fldid', 'fldsampletype', 'fldtestid', 'fldabnormal', 'fldsampleid', 'fldmethod', 'fldtime_sample')
            ->get();

        $data['patlabtestRequest'] = PatLabTest::where('fldencounterval', $request->encounterId)
            ->select('fldtestid as col')
            ->distinct()
            ->get();

        $html = view('outpatient::dynamic-views.laboratory-data', $data)->render();
        return $html;
    }

    public function saveLaboratoryRequest(Request $request)
    {
        //        return $request->all();
        try {
            foreach ($request->labreport as $report) {
                $serviceData = ServiceCost::select('fldid', 'flditemcost')->where('flditemname', $report)->where('flditemtype', 'Diagnostic Tests')->first();
                $encounter = Encounter::select('flddisctype')->where('fldencounterval', $request->encounter)->first();

                $noDiscount = NoDiscount::where('flditemname', $report)->first();

                $insertData['fldencounterval'] = $request->encounter;
                $insertData['fldbillingmode'] = $request->patient_mode;
                $insertData['flditemtype'] = $request->flditemtype;
                $insertData['flditemno'] = $serviceData->fldid; //need check
                $insertData['flditemname'] = $report;
                $insertData['flditemrate'] = $serviceData->flditemcost;
                $insertData['flditemqty'] = 1; //need check
                $insertData['fldtaxper'] = 0;
                $insertData['flddiscper'] = 0;
                $insertData['fldtaxamt'] = 0;
                $insertData['flddiscamt'] = 0;
                $insertData['fldditemamt'] = $serviceData->flditemcost;
                $insertData['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $insertData['fldordtime'] = date("Y-m-d H:i:s");
                $insertData['fldordcomp'] = Helpers::getCompName();//need check
                $insertData['flduserid'] = NULL;
                $insertData['fldtime'] = date("Y-m-d H:i:s");
                $insertData['fldcomp'] = null;
                $insertData['fldsave'] = 0;
                $insertData['fldbillno'] = null;
                $insertData['fldparent'] = null;
                $insertData['fldprint'] = 0;
                $insertData['fldstatus'] = 'Punched';
                $insertData['fldalert'] = 1;
                $insertData['fldtarget'] = 'comp01';//need check
                $insertData['fldpayto'] = NULL;
                $insertData['fldrefer'] = NULL;
                $insertData['fldreason'] = NULL;
                $insertData['fldretbill'] = NULL;
                $insertData['fldretqty'] = 0;
                $insertData['fldsample'] = 'Waiting';
                $insertData['xyz'] = 0;

                PatBilling::insert($insertData);
            }
            $data['patBilling'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
                ->where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'Diagnostic Tests')
                ->where('fldsave', 0)
                ->get();

            $html = view('outpatient::dynamic-views.laboratory-waiting-request', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
            //            return $e;
        }
    }

    public function updateLaboratoryRequestDone(Request $request)
    {
        try {
            for ($i = 0, $iMax = count($request->labreport); $i < $iMax; $i++) {
                //                return $request['fldid-request'][$i];
                $updateData['fldsave'] = 1;
                $updateData['fldstatus'] = 'Done';

                if ($request['fldid-request'][$i] != 'Cancelled') {
                    PatBilling::where('fldid', $request['fldid-request'][$i])->update($updateData);
                }
            }
            $data['patBilling'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
                ->where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'Diagnostic Tests')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $html = view('outpatient::dynamic-views.laboratory-waiting-request', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function labReported(Request $request)
    {
        return $request->all();
    }


}
