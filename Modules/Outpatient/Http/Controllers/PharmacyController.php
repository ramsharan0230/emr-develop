<?php

namespace Modules\Outpatient\Http\Controllers;

use App\Code;
use App\CogentUsers;
use App\Department;
use App\Drug;
use App\Encounter;
use App\ExamGeneral;
use App\MedicineBrand;
use App\PatDosing;
use App\PatFindings;
use App\Pathdosing;
use App\Regimen;
use App\Settings;
use App\SurgBrand;
use App\Surgical;
use App\Utils\Helpers;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session;

/**
 * Class PharmacyController
 * @package Modules\Outpatient\Http\Controllers
 */
class PharmacyController extends Controller
{
    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);

        $data['encounterId'] = $request->encounterId;
        $data['settings'] = Settings::where('fldindex', 'comp01:LowDeposit/Pharmacy')
            ->first();

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldadmission', 'fldcurrlocat', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldencrypt,fldptsex,fldptaddvill,fldptadddist,fldmidname,fldrank')
            ->first();

        $data['currentData'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flditemtype', 'flddose', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave_order', '1')
            ->where('flditemtype', 'Medicines')
            ->where('flddispmode', 'IPD')
            ->where('fldstarttime', '<=', Carbon::now()->toDateTimeString())
            ->get();


        $data['patFindings'] = PatFindings::select('fldcode as col')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave', '1')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldtype', '=', 'Final Diagnosis')
                    ->orWhere('fldtype', '=', 'Provisional Diagnosis');
            })
            ->distinct()
            ->get();

        $encounter = Encounter::where('fldencounterval', $request->encounterId)->first();

        $data['newOrdersPathDosing'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldcomment')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave_order', 0)
            ->where('fldstatus', $encounter->fldadmission ?? "")
            ->where('fldorder', 'Request')
            ->where('fldcurval', 'Continue')
            ->get();

        //        $encounters = Encounter::where('fldpatientval', $data['encounterData']->patientInfo->fldpatientval)->pluck('fldencounterval');

        $data['newOrders'] = Drug::select('fldroute')->distinct()->orderby('fldroute', 'ASC')->get();
        $data['newOrdersSurgcat'] = Surgical::select('fldsurgcateg')->distinct()->orderby('fldsurgcateg', 'ASC')->get();
        $data['extraOrder'] = ExamGeneral::select('fldreportquali', 'fldid')->where('fldencounterval', $request->encounterId)->get();
        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
            $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();
        } else {
            $data['departments'] = Department::pluck('flddept')->toArray();
        }

        $data['extraOrder'] = ExamGeneral::where('fldencounterval', $request->encounterId)->get();
        $html = view('outpatient::dynamic-views.pharmacy-data', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function discharge(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);

        $data['encounterId'] = $request->encounterId;
        $data['settings'] = Settings::where('fldindex', 'comp01:LowDeposit/Pharmacy')
            ->first();

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldadmission', 'fldcurrlocat', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldencrypt,fldptsex,fldptaddvill,fldptadddist,fldmidname,fldrank')
            ->first();

        $data['currentData'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flditemtype', 'flddose', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave_order', '1')
            ->where('flditemtype', 'Medicines')
            ->where('flddispmode', 'IPD')
            ->where('fldstarttime', '<=', Carbon::now()->toDateTimeString())
            ->get();


        $data['patFindings'] = PatFindings::select('fldcode as col')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave', '1')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldtype', '=', 'Final Diagnosis')
                    ->orWhere('fldtype', '=', 'Provisional Diagnosis');
            })
            ->distinct()
            ->get();

        $encounter = Encounter::where('fldencounterval', $request->encounterId)->first();

        $data['newOrdersPathDosing'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldcomment')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave_order', 0)
            ->where('fldstatus', $encounter->fldadmission ?? "")
            ->where('fldorder', 'Request')
            ->where('fldcurval', 'Continue')
            ->get();

        //        $encounters = Encounter::where('fldpatientval', $data['encounterData']->patientInfo->fldpatientval)->pluck('fldencounterval');

        $data['newOrders'] = Drug::select('fldroute')->distinct()->orderby('fldroute', 'ASC')->get();
        $data['newOrdersSurgcat'] = Surgical::select('fldsurgcateg')->distinct()->orderby('fldsurgcateg', 'ASC')->get();

        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
            $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();
        } else {
            $data['departments'] = Department::pluck('flddept')->toArray();
        }

        //$data['macAddress'] = MacAccess::select('fldcompname')->where('fldcomp', Helpers::getCompName())->get();

        $html = view('outpatient::dynamic-views.pharmacy-data-discharge', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getMedicineListForNewOrders(Request $request)
    {
        $drug = $request->drug;
        /*$data['generic_brand'] = $request->generic_brand;*/
        $inStock = $request->in_stock;
        //        if ($drug === 'msurg' || $drug === 'ortho') {
        $data['newOrderDataSurgical'] = SurgBrand::select('fldsurgid','fldbrand', 'fldbrandid')
            //                ->where('fldsurgcateg', $drug)
            ->whereRaw('lower(fldbrand) like ?', array('%'))
            ->where('fldmaxqty', '<>', '-1')
            ->where('fldactive', 'Active')
            //                ->whereIn('flddrug', $flddrug)
            ->whereHas('entry', function ($query) use ($inStock) {
                if ($inStock == 'yes') {
                    return $query->havingRaw('SUM(fldqty) > 0');
                } else {
                    return $query->havingRaw('SUM(fldqty) = 0');
                }
            })
            ->orderby('fldbrand', 'ASC')
            ->with(['Surgical','entry'])
            ->get();
            // ->orderby('fldsurgid', 'ASC')
            // ->get();
        //            $html = view('outpatient::dynamic-views.pharmacy-new-order-ortho-msurg', $data)->render();
        //        } else {
        //            $flddrug = Drug::where('fldroute', $drug)->pluck('flddrug');

        $data['newOrderData'] = MedicineBrand::select('fldbrand', 'fldbrandid', 'flddrug', 'flddosageform', 'flddeflabel')
            ->whereRaw('lower(fldbrand) like ?', array('%'))
            ->where('fldmaxqty', '<>', '-1')
            ->where('fldactive', 'Active')
            //                ->whereIn('flddrug', $flddrug)
            ->whereHas('entry', function ($query) use ($inStock) {
                if ($inStock == 'yes') {
                    return $query->havingRaw('SUM(fldqty) > 0');
                } else {
                    return $query->havingRaw('SUM(fldqty) = 0');
                }
            })
            ->orderby('fldbrand', 'ASC')
            ->with(['Drug', 'entry'])
            ->get();

        $html = view('outpatient::dynamic-views.pharmacy-new-order', $data)->render();
        //        }

        return $html;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function queryNewOrderBeforeSave(Request $request)
    {
        $medicineBrand = MedicineBrand::where('fldbrandid', $request->drug)->pluck('flddrug');
        $drugcodename = Drug::whereIn('flddrug', $medicineBrand)->pluck('fldcodename');
        $data['dose_unit'] = Code::select('fldrecaddose as dose', 'fldrecaddoseunit as unitdose')->whereIn('fldcodename', $drugcodename)->first();
        $data['strength'] = Drug::whereIn('flddrug', $medicineBrand)->pluck('fldstrength'); //alchi lagera 2 ota same query different pluck
        $data['medicineBrand'] = $request->drug;
        return $data;
    }

    /**
     * @param Request $request
     * @return array|\Exception|\GearmanException|string
     * @throws \Throwable
     */
    public function saveNewOrder(Request $request)
    {
        /*if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
            return false;
        }*/

        try {
            //            select tblmedbrand.fldpackvol as fldpackvol,tbldrug.fldstrength as fldstrength from tblmedbrand inner join tbldrug on tblmedbrand.flddrug=tbldrug.flddrug where tblmedbrand.fldbrandid=&1

            if ($request->pharnmacy_qty || $request->pharnmacy_qty != 0) {
                $quantity = $request->pharnmacy_qty;
            } else {
                $calculateStrengthVol = \DB::table('tblmedbrand')
                    ->select('tblmedbrand.fldpackvol as fldpackvol', 'tbldrug.fldstrength as fldstrength')
                    ->where('tblmedbrand.fldbrandid', $request->itemName)
                    ->join('tbldrug', 'tblmedbrand.flddrug', '=', 'tbldrug.flddrug')
                    ->first();

                switch ($request->pharnmacy_freq) {
                    case "PRN":
                        $frequency = 3;
                        break;
                    case "SOS":
                    case "stat":
                    case "AM":
                    case "HS":
                    case "Pre":
                    case "Post":
                    case "Tapering":
                        $frequency = 1;
                        break;
                    case "Hourly":
                        $frequency = 24;
                        break;
                    case "Alt day":
                        $frequency = 1 / 2;
                        break;
                    case "Weekly":
                        $frequency = 1 / 7;
                        break;
                    case "Biweekly":
                        $frequency = 1 / 14;
                        break;
                    case "Triweekly":
                        $frequency = 1 / 21;
                        break;
                    case "Monthly":
                        $frequency = 1 / 30;
                        break;
                    case "Yearly":
                        $frequency = 1 / 365;
                        break;
                    default:
                        $frequency = $request->pharnmacy_freq;
                        break;
                }

                $quantity = ($request->pharnmacy_dose * $frequency * $request->pharnmacy_day) / ($calculateStrengthVol->fldpackvol * $calculateStrengthVol->fldstrength);
            }

            $encounter = Encounter::where('fldencounterval', $request->encounter)->first();

            $data = [
                'fldencounterval' => $request->encounter,
                'flditemtype' => 'Medicines',
                'fldroute' => $request->route,
                'flditem' => $request->itemName,
                'flddose' => $request->pharnmacy_dose ?? 0,
                'fldfreq' => $request->pharnmacy_freq ?? 0,
                'flddays' => $request->pharnmacy_day ?? 0,
                'fldqtydisp' => ceil($quantity),
                'fldqtyret' => 0,
                'fldprescriber' => NULL,
                'fldregno' => NULL,
                'fldlevel' => 'Requested',
                'flddispmode' => 'IPD',
                'fldorder' => 'Request',
                'fldcurval' => 'Continue',
                'fldstarttime' => date("Y-m-d H:i:s"),
                'fldendtime' => NULL,
                'fldtaxper' => 0,
                'flddiscper' => 0,
                'flduserid_order' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime_order' => date("Y-m-d H:i:s"),
                'fldcomp_order' => $request->request_department_pharmacy,
                'fldsave_order' => '0',
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime' => NULL,
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => '1',
                'fldlabel' => '0',
                'fldstatus' => $encounter->fldadmission ?? "",
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            /*
             * if data is ortho or msurg reset data to match how the data is inserted
             * only quantity is inserted
             */
            if ($request->med_ortho_msurge == "Yes") {
                $data['flddose'] = 0;
                $data['fldfreq'] = 0;
                $data['flddays'] = 0;
                $data['fldqtydisp'] = $request->pharnmacy_qty;
            }

            Pathdosing::insert($data);
            $dataHtml['dosedata'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldstarttime', 'fldcomment')
                ->where('fldencounterval', $request->encounter)
                ->where('fldsave_order', 0)
                ->where('fldstatus', $encounter->fldadmission ?? "")
                ->where('fldorder', 'Request')
                ->where('fldcurval', 'Continue')
                ->get();

            $html = view('outpatient::dynamic-views.pharmacy-list-order', $dataHtml)->render();
            return $html;
        } catch (\GearmanException $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @return array|bool|string
     * @throws \Throwable
     */
    public function deletePharmacyOrder(Request $request)
    {
        if (Helpers::checkIfDischarged($request->encounterId)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
            //            return redirect()->back();
            return false;
        }

        /*DELETE FROM `tblpatdosing` WHERE fldid='700' and fldsave_order='0'*/

        Pathdosing::where('fldid', $request->fldid)
            ->where('fldsave_order', 0)
            ->delete();

        $encounter = Encounter::where('fldencounterval', $request->encounterId)->first();

        $dataHtml['dosedata'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldstarttime')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave_order', 0)
            ->where('fldstatus', $encounter->fldadmission)
            ->where('fldorder', 'Request')
            ->where('fldcurval', 'Continue')
            ->get();

        $html = view('outpatient::dynamic-views.pharmacy-list-order', $dataHtml)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function generateNewOrderList($fldid)
    {
        $encounter = Pathdosing::select('fldencounterval')->where('fldid', $fldid)->first();
        $encounterData = Encounter::where('fldencounterval', $encounter->fldencounterval)->first();

        $dataHtml['dosedata'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldstarttime')
            ->where('fldencounterval', $encounter->fldencounterval)
            ->where('fldsave_order', '0')
            ->where('fldstatus', $encounterData->fldadmission)
            ->where('fldorder', 'Request')
            ->where('fldcurval', 'Continue')
            ->get();

        $html = view('outpatient::dynamic-views.pharmacy-list-order', $dataHtml)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeNewOrderDateForm(Request $request)
    {
        $data['Pathdosing'] = Pathdosing::select('fldstarttime')->where('fldid', $request->fldid)->first();
        $data['html'] = '<input type="text" name="pharmacy_data_value" class="form-input col-sm-12" id="datepicker_pharmacy_start_date">';
        $data['html'] .= '<input type="hidden" name="fldid" value="' . $request->fldid . '">';
        $data['html'] .= '<input type="hidden" name="pharmacy_data_key" value="fldstarttime">';

        $data['routeForForm'] = route("patient.pharmacy.form.new.order.change.update");

        $html = view('outpatient::dynamic-views.pharmacy-order.date-change', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeNewOrderUpdate(Request $request)
    {
        try {
            Pathdosing::where('fldid', $request->fldid)
                ->update([$request->pharmacy_data_key => $request->pharmacy_data_value]);
            return $this->generateNewOrderList($request->fldid);
        } catch (\GearmanException $e) {

        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeNewOrderDoseForm(Request $request)
    {
        $data['Pathdosing'] = Pathdosing::select('flddose')->where('fldid', $request->fldid)->first();
        $data['html'] = '<input type="text" name="pharmacy_data_value" class="form-input col-sm-12" value="' . $data['Pathdosing']->flddose . '">';
        $data['html'] .= '<input type="hidden" name="fldid" value="' . $request->fldid . '">';
        $data['html'] .= '<input type="hidden" name="pharmacy_data_key" value="flddose">';

        $data['routeForForm'] = route("patient.pharmacy.form.new.order.change.update");

        $html = view('outpatient::dynamic-views.pharmacy-order.date-change', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeNewOrderDayForm(Request $request)
    {
        $data['Pathdosing'] = Pathdosing::select('flddays')->where('fldid', $request->fldid)->first();
        $data['html'] = '<input type="text" name="pharmacy_data_value" class="form-input col-sm-12" value="' . $data['Pathdosing']->flddays . '">';
        $data['html'] .= '<input type="hidden" name="fldid" value="' . $request->fldid . '">';
        $data['html'] .= '<input type="hidden" name="pharmacy_data_key" value="flddays">';

        $data['routeForForm'] = route("patient.pharmacy.form.new.order.change.update");

        $html = view('outpatient::dynamic-views.pharmacy-order.date-change', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeNewOrderQtydispForm(Request $request)
    {
        $data['Pathdosing'] = Pathdosing::select('fldqtydisp')->where('fldid', $request->fldid)->first();
        $data['html'] = '<input type="text" name="pharmacy_data_value" class="form-input col-sm-12" value="' . $data['Pathdosing']->fldqtydisp . '">';
        $data['html'] .= '<input type="hidden" name="fldid" value="' . $request->fldid . '">';
        $data['html'] .= '<input type="hidden" name="pharmacy_data_key" value="fldqtydisp">';

        $data['routeForForm'] = route("patient.pharmacy.form.new.order.change.update");

        $html = view('outpatient::dynamic-views.pharmacy-order.date-change', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeNewOrderFrequencyForm(Request $request)
    {
        $data['Pathdosing'] = Pathdosing::select('fldfreq')->where('fldid', $request->fldid)->first();

        $data['html'] = '<input type="hidden" name="fldid" value="' . $request->fldid . '">';
        $data['html'] .= '<input type="hidden" name="pharmacy_data_key" value="fldfreq">';

        $data['routeForForm'] = route("patient.pharmacy.form.new.order.change.update");

        $html = view('outpatient::dynamic-views.pharmacy-order.frequency-order', $data)->render();
        return $html;
    }

    public function directDispensing(Request $request)
    {
        try {
            $dat['fldlevel'] = 'Dispensed';
            //            $dat['fldendtime']    = date("Y-m-d H:i:s");
            $dat['fldsave_order'] = 1;
            $dat['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $dat['fldtime'] = date("Y-m-d H:i:s");
            $dat['fldcomp'] = Helpers::getCompName();
            $dat['xyz'] = 0;

            Pathdosing::where([['fldid', $request->fldid]])->update($dat);

            return $this->generateNewOrderList($request->fldid);
        } catch (\GearmanException $e) {

        }

    }

    public function listSelection(Request $request)
    {
        $data = Regimen::select('fldid', 'fldcodename', 'flddosetype', 'flddose', 'flddoseunit', 'fldfreq', 'fldday')
            ->where('flddisease', $request->searchName)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldgender', '=', 'Male')
                    ->orWhere('fldgender', '=', 'Both Sex');
            })
            ->where(function ($query) {
                return $query
                    ->orWhere('fldagegroup', '=', 'Neonate')
                    ->orWhere('fldagegroup', '=', 'All Age');
            })
            ->get();

        $html = '';
        if (count($data)) {
            foreach ($data as $datum) {
                $html .= "<tr>";
                $html .= "<td>$datum->fldcodename</td>";
                $html .= "<td>$datum->flddosetype</td>";
                $html .= "<td>$datum->flddose</td>";
                $html .= "<td>$datum->flddoseunit</td>";
                $html .= "<td>$datum->fldfreq</td>";
                $html .= "<td>$datum->fldday</td>";
                $html .= "</tr>";
            }
        }
        return $data;
    }

    public function calculateQuantity(Request $request)
    {
        $calculateStrengthVol = \DB::table('tblmedbrand')
            ->select('tblmedbrand.fldpackvol as fldpackvol', 'tbldrug.fldstrength as fldstrength')
            ->where('tblmedbrand.fldbrandid', $request->itemName)
            ->join('tbldrug', 'tblmedbrand.flddrug', '=', 'tbldrug.flddrug')
            ->first();

        switch ($request->pharnmacy_freq) {
            case "PRN":
                $frequency = 3;
                break;
            case "SOS":
            case "stat":
            case "AM":
            case "HS":
            case "Pre":
            case "Post":
            case "Tapering":
                $frequency = 1;
                break;
            case "Hourly":
                $frequency = 24;
                break;
            case "Alt day":
                $frequency = 1 / 2;
                break;
            case "Weekly":
                $frequency = 1 / 7;
                break;
            case "Biweekly":
                $frequency = 1 / 14;
                break;
            case "Triweekly":
                $frequency = 1 / 21;
                break;
            case "Monthly":
                $frequency = 1 / 30;
                break;
            case "Yearly":
                $frequency = 1 / 365;
                break;
            default:
                $frequency = $request->pharnmacy_freq;
                break;
        }

        return (double)($request->pharnmacy_dose * $frequency * $request->pharnmacy_day) / ($calculateStrengthVol->fldpackvol * $calculateStrengthVol->fldstrength);
    }

    public function addComment(Request $request)
    {
        try {
            PatDosing::where('fldid', $request->fldid)->update(['fldcomment' => $request->comment]);
            return response()->json([
                'status' => TRUE,
            ]);
        } catch (\GearmanException $e) {
            return false;
        }
    }

    public function reorderMedicine(Request $request)
    {
        $data['previousOrder'] = Pathdosing::where('fldid', $request->fldid)->first();

        $data['encounterData'] = Encounter::select('fldencounterval', 'fldpatientval', 'flduserid', 'fldadmission', 'fldcurrlocat', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldencrypt,fldptsex,fldptaddvill,fldptadddist,fldmidname,fldrank')
            ->first();

        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
            $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();
        } else {
            $data['departments'] = Department::pluck('flddept')->toArray();
        }

        $html = view('outpatient::dynamic-views.pharmacy-order.pharmacy-reorder', $data)->render();
        return $html;
    }

    public function reorderBulk(Request $request)
    {
        try {
            foreach ($request->reorderData as $reorder) {
                //                return $reorder;
                $tasks = Pathdosing::where('fldid', $reorder)->first();
                $encounter = Encounter::where('fldencounterval', $tasks->fldencounterval)->first();
                $data = [
                    'fldencounterval' => $tasks->fldencounterval,
                    'flditemtype' => $tasks->flditemtype,
                    'fldroute' => $tasks->fldroute,
                    'flditem' => $tasks->flditem,
                    'flddose' => $tasks->flddose ?? 0,
                    'fldfreq' => $tasks->fldfreq ?? 0,
                    'flddays' => $tasks->flddays ?? 0,
                    'fldqtydisp' => $tasks->fldqtydisp,
                    'fldqtyret' => $tasks->fldqtyret,
                    'fldprescriber' => $tasks->fldprescriber,
                    'fldregno' => $tasks->fldregno,
                    'fldlevel' => 'Requested',
                    'flddispmode' => 'IPD',
                    'fldorder' => 'Request',
                    'fldcurval' => 'Continue',
                    'fldstarttime' => date("Y-m-d H:i:s"),
                    'fldendtime' => $tasks->fldendtime,
                    'fldtaxper' => $tasks->fldtaxper,
                    'flddiscper' => $tasks->flddiscper,
                    'flduserid_order' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                    'fldtime_order' => date("Y-m-d H:i:s"),
                    'fldcomp_order' => $request->department,
                    'fldsave_order' => '0',
                    'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                    'fldtime' => NULL,
                    'fldcomp' => NULL,
                    'fldsave' => '1',
                    'fldlabel' => '0',
                    'fldstatus' => $encounter->fldadmission,
                    'flduptime' => NULL,
                    'xyz' => '0',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ];

                Pathdosing::insert($data);
            }

            $encounter = Encounter::where('fldencounterval', $request->encounter)->first();

            $dataHtml['dosedata'] = Pathdosing::select('fldid','fldroute', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldstarttime', 'fldcomment')
                ->where('fldencounterval', $request->encounterId)
                ->where('fldsave_order', 0)
                ->where('fldstatus', $encounter->fldadmission)
                ->where('fldorder', 'Request')
                ->where('fldcurval', 'Continue')
                ->get();

            $html = view('outpatient::dynamic-views.pharmacy-list-order', $dataHtml)->render();
            return $html;
        } catch (\Exception $e) {

        }
    }

    public function saveExtraOrder(Request $request)
    {
        try {
            $data = [
                'fldencounterval' => $request->encounterId,
                'fldinput' => 'Extra',
                'flditem' => 'Extra Item',
                'fldtype' => 'Qualitative',
                'fldreportquali' => $request->extraOrder,
                'fldreportquanti' => 0,
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => 1,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime' => date("Y-m-d H:i:s"),
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            ExamGeneral::create($data);
            $extraOrder = ExamGeneral::select('fldreportquali', 'fldid')->where('fldencounterval', $request->encounterId)->get();
            $html = '';
            if ($extraOrder) {
                foreach ($extraOrder as $extra) {
                    $html .= '<li class=" list-group-item pt-1 pb-1"><span data-fldreportquali="'.$extra->fldreportquali .'" class="fldreportquali-li">'.substr(strip_tags($extra->fldreportquali), 0, 50) .'</span>
                                                <a href="javascript:;" onclick="pharmacyPopup.deleteExtraOrder('.$extra->fldid .')" class="text-danger" style="float: right"><i class="fas fa-trash"></i></a>
                                            </li>';
                }
            }
            return response()->json([
                'success' => true,
                'extra_order' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'extra_order' => ''
            ]);
        }
    }

    public function deleteExtraOrder(Request $request)
    {
        try {
            ExamGeneral::where('fldid', $request->fldid)->delete();
            $extraOrder = ExamGeneral::select('fldreportquali', 'fldid')->where('fldencounterval', $request->encounterId)->get();
            $html = '';
            if ($extraOrder) {
                foreach ($extraOrder as $extra) {
                    $html .= '<li class=" list-group-item pt-1 pb-1"><span data-fldreportquali="'.$extra->fldreportquali .'" class="fldreportquali-li">'.substr(strip_tags($extra->fldreportquali), 0, 50) .'</span>
                                                <a href="javascript:;" onclick="pharmacyPopup.deleteExtraOrder('.$extra->fldid .')" class="text-danger" style="float: right"><i class="fas fa-trash"></i></a>
                                            </li>';
                }
            }
            return response()->json([
                'success' => true,
                'extra_order' => $html
            ]);
        }catch (\Exception $e){

        }
    }

}
