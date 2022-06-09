<?php

namespace Modules\Outpatient\Http\Controllers;

use App\CogentUsers;
use App\CostGroup;
use App\Encounter;
use App\ExamGeneral;
use App\GroupTest;
use App\PatBilling;
use App\PatLabTest;
use App\ServiceCost;
use App\Settings;
use App\Utils\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class LaboratoryOutPatientController extends Controller
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
        $billingmode = $request->billingmode ?? "General";
        $data['settings'] = Settings::where('fldindex', 'comp01:LowDeposit/Test')
            ->orWhere('fldindex', 'comp01:FixPayableUser/Test')
            ->get();

        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        //$data['macAddress'] = MacAccess::select('fldcompname')->where('fldcomp', Helpers::getCompName())->get();


        $data['costGroup'] = CostGroup::select('fldgroup')->where('flditemtype', 'Test')->distinct()->get();

        $data['patBilling'] = PatBilling::select('fldid', 'fldtime', 'fldordtime', 'flditemname', 'fldrefer', 'fldtarget', 'fldstatus')
            ->where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'Diagnostic Tests')
            ->where('fldsave', 1)
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
        $flditemname = ServiceCost::where(function ($query) use ($billingmode) {
            return $query
                ->orWhere('fldgroup', '=', $billingmode)
                ->orWhere('fldgroup', '=', '%');
        })
            ->where('fldstatus', 'Active')
            ->pluck('flditemname');

        $data['itemsForMultiselect'] = GroupTest::select('fldgroupname', 'fldactive')->whereIn('fldgroupname', $flditemname)
            ->distinct('fldgroupname')
            ->get();

        $data['patlabtest'] = PatLabTest::where('fldencounterval', $request->encounterId)
            ->where('fldstatus', 'Sampled')
            ->select('fldid', 'fldsampletype', 'fldtestid', 'fldabnormal', 'fldsampleid', 'fldmethod', 'fldtime_sample')
            ->get();

        $data['patlabtestRequest'] = PatLabTest::where('fldencounterval', $request->encounterId)
            ->select('fldtestid as col')
            ->distinct()
            ->get();

        $data['refer_by'] = CogentUsers::where('fldreferral', 1)->where('status', 'active')->get();
        $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
        $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();

        $html = view('outpatient::dynamic-views.laboratory-data', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function saveLaboratoryRequest(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
//            return redirect()->back();
            return false;
        }

        try {
            foreach ($request->labreport as $report) {
                $serviceData = ServiceCost::select('fldid', 'flditemcost', 'fldtarget')->where('flditemname', $report)->where('flditemtype', 'Diagnostic Tests')->first();
                /*$encounter = Encounter::select('flddisctype')->where('fldencounterval', $request->encounter)->first();

                $noDiscount = NoDiscount::where('flditemname', $report)->first();*/

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
                $insertData['fldordcomp'] = $request->patientLocation;
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

            $data['patBillingPunched'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
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
            return $e;
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function updateLaboratoryRequestDone(Request $request)
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
                $updateData['fldordcomp'] = $request->patientLocation;

                if ($request['status-request'][$i] != 'Cancelled') {
                    PatBilling::where([['fldid', $request['fldid-request'][$i]]])->update($updateData);
                }

            }
            return $this->getPunchedAndDone($request->encounter);
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
            if (count($request['laboratory-request-check']) > 0) {
                foreach ($request['laboratory-request-check'] as $cancelledMed) {
                    $updateData['fldstatus'] = 'Cancelled';
                    PatBilling::where('fldid', $cancelledMed)->update($updateData);
                }
            }

            return $this->getPunchedAndDone($request->encounter);
        } catch (\GearmanException $e) {

        }
    }

    public function deleteRequest(Request $request)
    {
        try {
            PatBilling::where('fldid', $request->fldid)->delete();
            $data['patBillingPunched'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
                ->where('fldencounterval', $request->encounterId)
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
            dd($e);
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function labReported(Request $request)
    {
        try {
            $fldTestId = $request->fldtestid;
            $fldUnit = $request->MedUnit;
            $encounterId = $request->encounterId;

            $data['reportedData'] = PatLabTest::select('fldid', 'fldsampletype', 'fldmethod', 'fldabnormal', 'fldid', 'fldstatus', 'fldtime_sample', 'fldtime_report', 'fldtest_type')
                ->where(['fldencounterval' => $encounterId, 'fldtestunit' => $fldUnit, 'fldtestid' => $fldTestId])
                ->with('subTest')
                ->get();

            $html = view('outpatient::dynamic-views.laboratory-waiting-reported', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
            return false;
        }

    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
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

                $data['itemsForMultiselect'] = GroupTest::select('fldgroupname', 'fldactive')->whereIn('fldgroupname', $flditemname)
                    ->distinct('fldgroupname')
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

                $data['itemsForMultiselect'] = GroupTest::select('fldgroupname', 'fldactive')->whereIn('fldgroupname', $flditemname)
                    ->whereIn('fldgroupname', $costGroup)
                    ->distinct('fldgroupname')
                    ->get();
            }
            $html = view('outpatient::dynamic-views.laboratory-list-by-group', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
            return false;
        }

    }

    public function getPunchedAndDone($encounter)
    {
        $data['patBillingPunched'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
            ->where('fldencounterval', $encounter)
            ->where('flditemtype', 'Diagnostic Tests')
            ->where('fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Punched')
                    ->orWhere('fldstatus', '=', 'Cancelled');
            })
            ->get();
        $data['patBillingDone'] = PatBilling::select('fldid', 'fldordtime', 'flditemname', 'fldstatus', 'fldreason', 'fldtarget')
            ->where('fldencounterval', $encounter)
            ->where('flditemtype', 'Diagnostic Tests')
            ->where('fldsave', 1)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Done')
                    ->orWhere('fldstatus', '=', 'Cancelled');
            })
            ->get();

        $data['done'] = view('outpatient::dynamic-views.laboratory-waiting-request-done', $data)->render();
        $data['punched'] = view('outpatient::dynamic-views.laboratory-waiting-request', $data)->render();
        return $data;
    }

    public function saveExtraOrder(Request $request)
    {
        try {
            $data = [
                'fldencounterval' => $request->encounterId,
                'fldinput' => 'Extra',
                'flditem' => 'Extra Item',
                'fldtype' => 'Qualitative',
                'fldreportquali' => strip_tags($request->extraOrder),
                'fldreportquanti' => 0,
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => 1,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime' => date("Y-m-d H:i:s"),
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            ExamGeneral::create($data);
        } catch (\Exception $e) {

        }
    }

}
