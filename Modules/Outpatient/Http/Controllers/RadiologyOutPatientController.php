<?php

namespace Modules\Outpatient\Http\Controllers;

use App\CogentUsers;
use App\CostGroup;
use App\Encounter;
use App\MacAccess;
use App\NoDiscount;
use App\PatBilling;
use App\PatLabSubTest;
use App\PatLabTest;
use App\PatRadioTest;
use App\Radio;
use App\RadioGroup;
use App\ServiceCost;
use App\Settings;
use App\Utils\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session;

class RadiologyOutPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);

        $data['encounterId'] = $request->encounterId;
        $billingmode = $request->billingmode ?? 'General';
        $data['settings'] = Settings::where('fldindex', 'comp01:LowDeposit/Test')
            ->orWhere('fldindex', 'comp01:FixPayableUser/Test')
            ->get();

        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();


        //$data['macAddress'] = MacAccess::select('fldcompname')->where('fldcomp', Helpers::getCompName())->get();


        $data['costGroup'] = CostGroup::select('fldgroup')->where('flditemtype', 'Radio')->distinct()->get();

        $data['patBilling'] = PatBilling::select('fldid', 'fldtime', 'fldordtime', 'flditemname', 'fldrefer', 'fldtarget', 'fldstatus')
            ->where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'Radio Diagnostics')
            ->where('fldsave', '1')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Done')
                    ->orWhere('fldstatus', '=', 'Cleared');
            })
            ->get();

        $data['patBillingListPunched'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
            ->where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'Radio Diagnostics')
            ->where('fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Punched')
                    ->orWhere('fldstatus', '=', 'Cancelled');
            })
            ->get();

//        select flditemname from tblservicecost where (fldgroup='General' or fldgroup='%') and fldstatus='Active'
        $flditemname = ServiceCost::where(function ($query) use ($billingmode) {
            return $query
                ->orWhere('fldgroup', '=', $billingmode)
                ->orWhere('fldgroup', '=', '%');
        })
            ->where('fldstatus', 'Active')
            ->pluck('flditemname');

        $data['itemsForMultiselect'] = RadioGroup::select('fldgroupname')->whereIn('fldgroupname', $flditemname)
            ->groupBy('fldgroupname')
            ->get();

        $data['patlabtest'] = PatLabTest::where('fldencounterval', $request->encounterId)
            ->where('fldstatus', 'Sampled')
            ->select('fldid', 'fldsampletype', 'fldtestid', 'fldabnormal', 'fldsampleid', 'fldmethod', 'fldtime_sample')
            ->get();

        $data['patlabtestRequest'] = PatRadioTest::where('fldencounterval', $request->encounterId)
            ->select('fldtestid as col')
            ->with('radioSubTest')
            ->distinct()
            ->get();

        $data['refer_by'] = CogentUsers::where('fldreferral', 1)->get();

        $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
        $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();

        $html = view('outpatient::dynamic-views.radiology-data', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function saveRadiologyRequest(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
//            return redirect()->back();
            return false;
        }

//        return $request->all();
        try {
            foreach ($request->labreport as $report) {
                $serviceData = ServiceCost::select('fldid', 'flditemcost','fldtarget')->where('flditemname', 'LIKE', $report)->where('flditemtype', 'Radio Diagnostics')->first();
//                return $serviceData;
                $encounter = Encounter::select('flddisctype')->where('fldencounterval', $request->encounter)->first();

                $noDiscount = NoDiscount::where('flditemname', $report)->first();

                $insertData['fldencounterval'] = $request->encounter;
                $insertData['fldbillingmode'] = $request->billing_mode;
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
                $insertData['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertData['fldordtime'] = date("Y-m-d H:i:s");
                $insertData['fldordcomp'] = 'Comp05';//need check
                $insertData['flduserid'] = NULL;
                $insertData['fldtime'] = date("Y-m-d H:i:s");
                $insertData['fldcomp'] = $serviceData->fldtarget;
                $insertData['fldsave'] = 0;
                $insertData['fldbillno'] = null;
                $insertData['fldparent'] = null;
                $insertData['fldprint'] = 0;
                $insertData['fldstatus'] = 'Punched';
                $insertData['fldalert'] = 1;
                $insertData['fldtarget'] = $serviceData->fldtarget;//need check
                $insertData['fldpayto'] = NULL;
                $insertData['fldrefer'] = NULL;
                $insertData['fldreason'] = NULL;
                $insertData['fldretbill'] = NULL;
                $insertData['fldretqty'] = 0;
                $insertData['fldsample'] = 'Waiting';
                $insertData['xyz'] = 0;
                $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                PatBilling::insert($insertData);

            }
            $data['patBillingCancelled'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
                ->where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'LIKE', 'Radio Diagnostics')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $html = view('outpatient::dynamic-views.radiology-waiting-request', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
//            return $e;
        }
        return false;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function updateRadiologyRequestDone(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
//            return redirect()->back();
            return false;
        }

        try {
            for ($i = 0, $iMax = count($request['fldid-request']); $i < $iMax; $i++) {
//                return $request['fldid-request'][$i];
                $updateData['fldsave'] = 1;
                $updateData['fldstatus'] = 'Done';
                $updateData['fldordcomp'] = $request->request_department_radiology;


                /*
                 * patradiotest insert
                 */
                $PatBilling = PatBilling::where('fldid', $request['fldid-request'][$i])->first();
                $groupRadio = RadioGroup::where('fldgroupname', $PatBilling->flditemname)->first();

                $insertRadioTest['fldencounterval'] = $request->encounter;
                $insertRadioTest['fldstatus'] = "Sampled";
                $insertRadioTest['fldmethod'] = $groupRadio->fldactive;
                $insertRadioTest['fldtestid'] = $groupRadio->fldtestid;
                $insertRadioTest['flvisible'] = "Visible";
                $insertRadioTest['fldtest_type'] = $groupRadio->fldtesttype;
                PatRadioTest::insert($insertRadioTest);

                if ($request['status-request'][$i] != 'Cancelled') {
                    $PatBilling->update($updateData);
                }

            }
            $data['patBillingCancelled'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
                ->where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'Radio Diagnostics')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $data['patBillingDone'] = PatBilling::select('fldid', 'fldtime', 'fldordtime', 'flditemname', 'fldrefer', 'fldtarget', 'fldstatus')
                ->where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'Radio Diagnostics')
                ->where('fldsave', '1')
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Done')
                        ->orWhere('fldstatus', '=', 'Cleared');
                })
                ->get();

            $data['done'] = view('outpatient::dynamic-views.radiology-waiting-request', $data)->render();
            $data['cancelled'] = view('outpatient::dynamic-views.radiology-waiting-request-done', $data)->render();
            return $data;
        } catch (\GearmanException $e) {

        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function cancelRequest(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
//            return redirect()->back();
            return false;
        }

        try {
            if (count($request['radiology-request-check']) > 0) {
                foreach ($request['radiology-request-check'] as $cancelledMed) {
                    $updateData['fldstatus'] = 'Cancelled';
                    PatBilling::where('fldid', $cancelledMed)->update($updateData);
                }
            }

            $data['patBillingCancelled'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
                ->where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'Radio Diagnostics')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $html = view('outpatient::dynamic-views.radiology-waiting-request', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
        return false;
    }

    public function deleteRequest(Request $request)
    {
        try {
            PatBilling::where('fldid', $request->fldid)->delete();
            $data['patBillingCancelled'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
                ->where('fldencounterval', $request->encounterId)
                ->where('flditemtype', 'Radio Diagnostics')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $html = view('outpatient::dynamic-views.radiology-waiting-request', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
//            dd($e);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function radioReported(Request $request)
    {
        try {
            $fldTestId = $request->fldtestid;
            $encounterId = $request->encounterId;

            $data['reportedData'] = PatRadioTest::select('fldid', 'fldsampletype', 'fldmethod', 'fldabnormal', 'fldid', 'fldstatus', 'fldtime_report', 'fldtest_type')
                ->where(['fldencounterval' => $encounterId, 'fldtestid' => $fldTestId])
                ->with('radioSubTest')
                ->get();

            $html = view('outpatient::dynamic-views.radiology-waiting-reported', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
            return $e;
        }
        return false;
    }

    public function listByGroup(Request $request)
    {
        try {
            $billingmode = $request->billingmode ?? "General";
            if ($request->fldgroup == "") {
                $flditemname = ServiceCost::where(function ($query) use ($billingmode) {
                    return $query
                        ->orWhere('fldgroup', '=', $billingmode)
                        ->orWhere('fldgroup', '=', '%');
                })
                    ->where('fldstatus', 'Active')
                    ->pluck('flditemname');

                $data['itemsForMultiselect'] = RadioGroup::select('fldgroupname')->whereIn('fldgroupname', $flditemname)
                    ->groupBy('fldgroupname')
                    ->get();
            } else {
                $flditemname = ServiceCost::where(function ($query) use ($billingmode) {
                    return $query
                        ->orWhere('fldgroup', '=', $billingmode)
                        ->orWhere('fldgroup', '=', '%');
                })
                    ->where('fldstatus', 'Active')
                    ->pluck('flditemname');

                $costGroup = CostGroup::select('flditemname')->where('fldgroup', $request->fldgroup)->pluck('flditemname');

                $data['itemsForMultiselect'] = RadioGroup::select('fldgroupname')->whereIn('fldgroupname', $flditemname)
                    ->whereIn('fldgroupname', $costGroup)
                    ->groupBy('fldgroupname')
                    ->get();
            }
            $html = view('outpatient::dynamic-views.laboratory-list-by-group', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
            return false;
        }

    }

    public function commentRequest(Request $request)
    {
        try {
            PatBilling::where('fldid', $request->fldid)->update(['fldreason' => $request->comment]);
            return response()->json([
                'status' => TRUE,
            ]);
        } catch (\GearmanException $e) {
            return false;
        }

    }

}
