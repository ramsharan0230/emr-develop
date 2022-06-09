<?php

namespace Modules\Outpatient\Http\Controllers;

use App\CogentUsers;
use App\Encounter;
use App\PatBilling;
use App\PatBillingShare;
use App\ServiceCost;
use App\Services\UserShareService;
use App\Settings;
use App\Utils\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class GeneralServicesOutPatientController extends Controller
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

        $data['patBilling'] = PatBilling::where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'General Services')
            ->where('fldsave', 0)
            // ->where(function ($query) {
            //     return $query
            //         ->orWhere('fldstatus', '=', 'Done')
            //         ->orWhere('fldstatus', '=', 'Cleared');
            // })
            ->get();

        $data['patBillingList'] = PatBilling::where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'General Services')
            ->where('fldsave', 0)
            ->get();

        $data['patBillingListPunched'] = PatBilling::where('fldencounterval', $request->encounterId)
            ->where('flditemtype', 'General Services')
            ->where('fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Punched')
                    ->orWhere('fldstatus', '=', 'Cancelled');
            })
            ->get();

        $data['itemsForMultiselect'] = ServiceCost::select('flditemname')
            ->where([['fldgroup', 'General'], ['fldstatus', 'Active'], ['flditemtype', 'General Services']])
            ->orWhere([['fldgroup', '%'], ['fldstatus', 'Active'], ['flditemtype', 'General Services']])
            ->orWhere([['fldgroup', '=', $billingmode], ['fldstatus', 'Active'], ['flditemtype', 'General Services']])
            // ->where('fldgroup','General')
            // ->orwhere('fldgroup','%')
            // ->where('fldstatus', 'Active')
            // ->where('flditemtype','General Services')
            ->get();

        $data['refer_by'] = CogentUsers::where('fldreferral', 1)->where('status', 'active')->get();
        $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
        $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();

        $html = view('outpatient::dynamic-views.general-services-data', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function saveServicesRequest(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
            return false;
        }

        try {
            foreach ($request->servicesreport as $report) {
                $serviceData = ServiceCost::select('fldid', 'flditemcost', 'fldtarget')->where('flditemname', $report)->where('flditemtype', 'General Services')->first();

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
                $insertData['fldcomp'] = null;
                $insertData['fldsave'] = 0;
                $insertData['fldbillno'] = null;
                $insertData['fldparent'] = null;
                $insertData['fldprint'] = 0;
                $insertData['fldstatus'] = 'Punched';
                $insertData['fldalert'] = 1;
                $insertData['fldtarget'] = $serviceData->fldtarget;//need check
                $insertData['fldpayto'] = NULL;
                $insertData['fldrefer'] = $request->referer_by;
                $insertData['fldreason'] = NULL;
                $insertData['fldretbill'] = NULL;
                $insertData['fldretqty'] = 0;
                $insertData['fldsample'] = 'Waiting';
                $insertData['xyz'] = 0;
                $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                PatBilling::insert($insertData);
            }

            $data['patBillingPunched'] = PatBilling::where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'General Services')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $html = view('outpatient::dynamic-views.general-services-waiting-request', $data)->render();
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
    public function saveIpRoundRequest(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
            return false;
        }

        try {
            foreach ($request->servicesreport as $report) {
                $serviceData = ServiceCost::select('fldid', 'flditemcost', 'fldtarget')->where('flditemname', $report)->where('flditemtype', 'General Services')->first();

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
                $insertData['fldcomp'] = null;
                $insertData['fldsave'] = 0;
                $insertData['fldbillno'] = null;
                $insertData['fldparent'] = null;
                $insertData['fldprint'] = 0;
                $insertData['fldstatus'] = 'Punched';
                $insertData['fldalert'] = 1;
                $insertData['fldtarget'] = $serviceData->fldtarget; //need check
                $insertData['fldpayto'] = NULL;
                $insertData['fldrefer'] = $request->referer_by;
                $insertData['fldreason'] = NULL;
                $insertData['fldretbill'] = NULL;
                $insertData['fldretqty'] = 0;
                $insertData['fldsample'] = 'Waiting';
                $insertData['xyz'] = 0;
                $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                PatBilling::insert($insertData);
            }

            $data['patBillingPunched'] = PatBilling::where('fldencounterval', $request->encounter)
                ->where('flditemtype', 'General Services')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $data['doctors'] = CogentUsers::where('fldipconsult', 1)->get();

            $html = view('outpatient::dynamic-views.general-ip-round-waiting-request', $data)->render();
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
    public function cancelRequest(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
            //            return redirect()->back();
            return false;
        }

        try {
            if (count($request['fldid-request']) > 0) {
                foreach ($request['fldid-request'] as $cancelledRequest) {
                    $updateData['fldstatus'] = 'Cancelled';
                    PatBilling::where('fldid', $cancelledRequest)->update($updateData);
                }
            }

            return $this->getPunchedAndDone($request->encounter);
        } catch (\GearmanException $e) {

        }
    }

    public function getPunchedAndDone($encounter)
    {
        $data['patBillingPunched'] = PatBilling::where('fldencounterval', $encounter)
            ->where('flditemtype', 'General Services')
            ->where('fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Punched')
                    ->orWhere('fldstatus', '=', 'Cancelled');
            })
            ->get();
        $data['patBillingDone'] = PatBilling::where('fldencounterval', $encounter)
            ->where('flditemtype', 'General Services')
            ->where('fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Punched');
                // ->orWhere('fldstatus', '=', 'Done')
                // ->orWhere('fldstatus', '=', 'Cancelled');
            })
            ->get();

        $data['done'] = view('outpatient::dynamic-views.general-services-waiting-request-done', $data)->render();
        $data['punched'] = view('outpatient::dynamic-views.general-services-waiting-request', $data)->render();
        $patBilling = PatBilling::where('fldencounterval', $encounter)
            ->where('flditemtype', 'General Services')
            ->where('fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Punched')
                    ->orWhere('fldstatus', '=', 'Cancelled');
                // ->orWhere('fldstatus', '=', 'Done')
                // ->orWhere('fldstatus', '=', 'Cleared');
            })
            ->get();
        $html = '';
        if ($patBilling) {
            foreach ($patBilling as $key => $patBill) {
                $html .= "<tr>";
                $html .= "<td>$patBill->flditemname</td>";
                $html .= "<td>$patBill->fldordtime</td>";
                $html .= "<td>$patBill->fldrefer</td>";
                $html .= "<td>$patBill->flditemqty</td>";
                $html .= "<td class='flditemrate' data-rate='" . $patBill->flditemrate . "' data-currency='" . $patBill->fldcurrency . "'>" . $patBill->fldcurrency . " " . $patBill->flditemrate . "</td>";
                $html .= "<td class='fldditemamt' data-amount='" . $patBill->flditemrate . "' data-currency='" . $patBill->fldcurrency . "'>" . $patBill->fldcurrency . " " . $patBill->fldditemamt . "</td>";
                $html .= "<td>$patBill->fldstatus</td>";
                $html .= "</tr>";
            }
        }
        $data['reported_html'] = $html;
        return $data;
    }

    public function deleteRequest(Request $request)
    {
        try {
            PatBilling::where('fldid', $request->fldid)->delete();
            $data['patBillingPunched'] = PatBilling::where('fldencounterval', $request->encounterId)
                ->where('flditemtype', 'General Services')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $html = view('outpatient::dynamic-views.general-services-waiting-request', $data)->render();
            return $html;
        } catch (\GearmanException $e) {
            dd($e);
        }
    }

    public function deleteIpRoundRequest(Request $request)
    {
        try {
            PatBilling::where('fldid', $request->fldid)->delete();
            $data['patBillingPunched'] = PatBilling::where('fldencounterval', $request->encounterId)
                ->where('flditemtype', 'General Services')
                ->where('fldsave', 0)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->get();

            $data['doctors'] = CogentUsers::where('fldipconsult', 1)->get();

            $html = view('outpatient::dynamic-views.general-ip-round-waiting-request', $data)->render();
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
    public function updateServicesRequestDone(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
            return false;
        }

        try {
            for ($i = 0; $i < count($request['fldid-request']); $i++) {
                $itemCost = ServiceCost::where('fldid', $request['flditemno-request'][$i])->first()->flditemcost;
                $updateData['fldsave'] = 0;
                $updateData['fldstatus'] = 'Punched';
                $updateData['fldordcomp'] = $request->patientLocation;
                $updateData['fldditemamt'] = $itemCost * $request['service_quantity'][$i];
                $updateData['flditemqty'] = $request['service_quantity'][$i];

                if ($request['status-request'][$i] != 'Cancelled') {
                    PatBilling::where([['fldid', $request['fldid-request'][$i]]])->update($updateData);
                }

            }

            return $this->getPunchedAndDone($request->encounter);
        } catch (\GearmanException $e) {

        }
    }

    public function ipdRoundForm(Request $request)
    {
        $ip_round = 'IP ROUND';
        $request->validate([
            'encounterId' => 'required',
        ]);

        $data['encounterId'] = $request->encounterId;
        $billingmode = $request->billingmode ?? "General";
        $data['settings'] = Settings::orWhere('fldindex', 'LIKE', 'comp01:LowDeposit/Test')
            ->orWhere('fldindex', 'LIKE','comp01:FixPayableUser/Test')
            ->get();

        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        $data['patBilling'] = PatBilling::where('fldencounterval', $request->encounterId)
            ->join('tblservicecost', function ($join) {
                $join->on('tblpatbilling.flditemname', '=', 'tblservicecost.flditemname');
                $join->on('tblpatbilling.flditemtype', '=', 'tblservicecost.flditemtype');
            })
            ->select('tblpatbilling.*', 'tblservicecost.fldreport')
            ->where('fldreport', 'LIKE',$ip_round)
            ->where('tblpatbilling.flditemtype', 'LIKE','General Services')
            ->where('fldsave', 0)
            // ->where(function ($query) {
            //     return $query
            //         ->orWhere('fldstatus', '=', 'Done')
            //         ->orWhere('fldstatus', '=', 'Cleared');
            // })
            ->get();

        $data['patBillingList'] = PatBilling::where('fldencounterval', $request->encounterId)
            ->join('tblservicecost', function ($join) {
                $join->on('tblpatbilling.flditemname', '=', 'tblservicecost.flditemname');
                $join->on('tblpatbilling.flditemtype', '=', 'tblservicecost.flditemtype');
            })
            ->select('tblpatbilling.*', 'tblservicecost.fldreport')
            ->where('fldreport', 'LIKE',$ip_round)
            ->where('tblpatbilling.flditemtype', 'LIKE','General Services')
            ->where('tblpatbilling.fldsave', 0)
            ->get();

        $data['patBillingListPunched'] = PatBilling::where('fldencounterval', $request->encounterId)
            ->join('tblservicecost', function ($join) {
                $join->on('tblpatbilling.flditemname', '=', 'tblservicecost.flditemname');
                $join->on('tblpatbilling.flditemtype', '=', 'tblservicecost.flditemtype');
            })
            ->select('tblpatbilling.*', 'tblservicecost.fldreport')
            ->where('fldreport', 'LIKE',$ip_round)
            ->where('tblpatbilling.flditemtype', 'LIKE','General Services')
            ->where('tblpatbilling.fldsave', 0)
            ->where(function ($query) {
                return $query
                    ->orWhere('tblpatbilling.fldstatus', '=', 'Punched')
                    ->orWhere('tblpatbilling.fldstatus', '=', 'Cancelled');
            })
            ->with(['pat_billing_shares'])
            ->get();

        $data['itemsForMultiselect'] = ServiceCost::select('flditemname')
            ->where([
                ['fldgroup', 'LIKE','General'],
                ['fldstatus', 'LIKE','Active'],
                ['flditemtype', 'LIKE','General Services'],
                ['fldreport', 'LIKE',$ip_round]
            ])
            ->where(function ($q) use ($billingmode) {
                $q->orWhere([['fldgroup', '%'], ['fldstatus', 'Active'], ['flditemtype', 'General Services']])
                    ->orWhere([['fldgroup', '=', $billingmode], ['fldstatus', 'Active'], ['flditemtype', 'General Services']]);
            })
            ->get();

        $data['refer_by'] = CogentUsers::where('fldreferral', 1)->where('status', 'active')->get();
        $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
        $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();

        $data['doctors'] = CogentUsers::where('fldipconsult', 1)->get();
        $html = view('outpatient::dynamic-views.ip_round_service', $data)->render();
        return $html;
    }

    public function saveDocShare(Request $request)
    {
        $msg = "Nothing changed.";
        $bill = PatBilling::where('fldid', $request->bill_id)->first();

        // if bill exists
        if ($bill) {
            $share = new PatBillingShare();
            $share->pat_billing_id = $request->bill_id;
            $share->user_id = $request->user_id;
            $share->type = 'IPD Round';
            $share->share = UserShareService::getUserShareByItemNameType($bill->flditemname, $bill->flditemtype);
            $share->save();
            $msg = "Saved successfully.";
        }

        return response()->json([
            'data' => [],
            'success' => true,
            'message' => $msg
        ]);
    }

    public function removeDocShare(Request $request)
    {
        $msg = "Nothing changed.";
        $bill = PatBilling::where('fldid', $request->bill_id)->first();

        // if bill exists
        if ($bill) {
            $share = PatBillingShare::where([
                ['pat_billing_id', $request->bill_id],
                ['user_id', $request->user_id]
            ])->first();

            if ($share) {
                $share->delete();
                $msg = "Removed successfully.";
            }
        }

        return response()->json([
            'data' => [],
            'success' => true,
            'message' => $msg
        ]);
    }
}
