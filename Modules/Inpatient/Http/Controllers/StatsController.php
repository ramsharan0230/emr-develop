<?php

namespace Modules\Inpatient\Http\Controllers;

use App\Label;
use App\MedicineBrand;
use App\NurseDosing;
use App\Pathdosing;
use App\Utils\Helpers;
use Auth;
use Barryvdh\DomPDF\Facade;
use DB;
use Exception;
use GearmanException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Session;

class StatsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function onclick(Request $request)
    {
        $get_patdosing = Pathdosing::where([
            ['fldencounterval', '=', Input::get('fldencounterval')],
            ['fldsave_order', '=', 1],
            ['flditemtype', '=', 'Medicines'],
            ['flddispmode', '=', 'IPD'],
            ['fldroute', '!=', 'fluid'],
        ])->where(function ($query) {
            $query->where('fldfreq', 'like', '%PRN%');
            $query->orWhere('fldfreq', 'like', '%stat%');
        })->with('medBrand:fldvolunit,fldbrandid')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();

        if ($request->get('value') == 'label') {
            foreach ($get_patdosing as &$list_detail) {
                $temp_data = DB::select('select fldstrength from tbldrug where flddrug in(select flddrug from tblmedbrand where fldbrandid=?)', [$list_detail->flditem]);
                $list_detail->flddose = $list_detail->flddose / $temp_data[0]->fldstrength . ' ' . $list_detail->medBrand->fldvolunit;
            }
        }

        /*
        select fldid,fldstarttime,fldroute,flditem,fldid,fldfreq,flddays,fldcurval,fldid,fldstarttime,fldstatus from tblpatdosing where fldencounterval='E29128' and fldsave_order='1' and fldroute<>'fluid' and flditemtype='Medicines' and (fldfreq like 'stat' or fldfreq like 'PRN') ORDER BY fldid DESC
        select flditemtype,fldroute,flditem,flddose from tblpatdosing where fldid=50368
        select COUNT(fldid) as cnt from tblnurdosing where flddoseno=50368
        */
        return response()->json($get_patdosing);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listall()
    {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', 'like', '%stat%')
            ->orWhere('fldfreq', 'like', '%PRN%')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();
        return response()->json($get_patdosing);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMedicineStat()
    {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', 'like', '%stat%')
            ->orWhere('fldfreq', 'like', '%PRN%')
            ->orderBy('fldid', 'DESC')
            ->select('flditem', 'fldid')
            ->get();
        return response()->json($get_patdosing);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMedicineDetails()
    {
        $get_patdosing = Pathdosing::where([
            'fldid' => Input::get('fldid'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', 'like', '%stat%')
            ->orWhere('fldfreq', 'like', '%PRN%')
            ->orderBy('fldid', 'DESC')
            ->select('flditem', 'flddose', 'fldroute')
            ->get();
        return response()->json($get_patdosing);
    }

    // When input:value clicked

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showValue()
    {
        $data = Input::get('value');
        $date = Input::get('list_date') ?: '2020-04-01';
        if ($date) {
            if ($date == 'all')
                $date = NULL;
            elseif ($date == 'today')
                $date = date('Y-m-d');
        } else
            $date = date('Y-m-d');

        $from = $date . ' 00:00:00';
        $to = $date . ' 23:59:59.99';
        $get_list_detail = Pathdosing::whereBetween('fldstarttime', [$from, $to])->where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', 'like', '%stat%')
            ->where('fldfreq', 'like', '%PRN%')
            ->with('medBrand:fldvolunit,fldbrandid')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();

        if ($data == 'label') {
            /*
            select flditemtype,fldroute,flditem,flddose from tblpatdosing where fldid=50368
            select fldstrength from tbldrug where flddrug in(select flddrug from tblmedbrand where fldbrandid='Diclofenac Na -75mg/ml (DICINAC 75 MG)')
            select fldvolunit from tblmedbrand where fldbrandid='Diclofenac Na -75mg/ml (DICINAC 75 MG)'
            */
            foreach ($get_list_detail as &$list_detail) {
                $temp_data = DB::select('select fldstrength from tbldrug where flddrug in(select flddrug from tblmedbrand where fldbrandid=?)', [$list_detail->flditem]);
                $list_detail->flddose = $list_detail->flddose / $temp_data[0]->fldstrength . ' ' . $list_detail->medBrand->fldvolunit;
            }
        }

        return response()->json($get_list_detail);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listByDate()
    {
        $related_list = Input::get('list_date');
        $from = $related_list . ' 00:00:00';
        $to = $related_list . ' 23:59:59.99';
        $get_list_detail = Pathdosing::whereBetween('fldstarttime', [$from, $to])->where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', 'like', '%stat%')
            ->orWhere('fldfreq', 'like', '%PRN%')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();
        return response()->json($get_list_detail);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatus()
    {
        $fldid = Input::get('fldid');
        $get_list_detail = Pathdosing::where('fldid', $fldid)->select('fldid', 'flditem', 'flddays')->get();
        return response()->json($get_list_detail);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request)
    {
        try {
            $table = Pathdosing::where([
                'fldid' => $request->fldid,
                'fldencounterval' => $request->fldencounterval
            ])->first();
            $table->fldcurval = $request->fldcurval;
            $table->update();
            if ($table) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $table
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => 'Something went wrong.'
                    ]
                ]);
            }
        } catch (GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('patient');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeDays(Request $request)
    {
        try {
            $table = Pathdosing::where([
                'fldid' => $request->fldid,
                'fldencounterval' => $request->fldencounterval
            ])->first();
            $table->flddays = $request->flddays;
            $table->update();
            if ($table) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $table
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => 'Something went wrong.'
                    ]
                ]);
            }
        } catch (GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('patient');
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChangeDays()
    {
        $fldid = Input::get('fldid');
        $get_change_day = Pathdosing::where('fldid', $fldid)
            ->select('flddays')
            ->first();
        return response()->json($get_change_day);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChangeStatus()
    {
        $fldid = Input::get('fldid');
        $get_change_day = Pathdosing::where('fldid', $fldid)
            ->select('fldcurval')
            ->first();
        return response()->json($get_change_day);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDosingRecord(Request $request)
    {
        $encounter_id = $request->get('encounterId') ?: Session::get('inpatient_encounter_id');
        $date = date('Y-m-d');

        return response()->json(
            Pathdosing::select('fldid', 'flditem')->where([
                ["fldencounterval", "=", $encounter_id],
                ["fldsave_order", "=", "1"],
                ["fldendtime", ">=", "$date 00:00:00"],
                ["fldstarttime", "<=", "$date 23:59:59.999"],
                ["fldroute", "<>", "fluid"],
                ["flditemtype", "LIKE", "Medicines"],
                ["fldcurval", "LIKE", "Continue"],
                ["flddispmode", "LIKE", "IPD"],
            ])->get()
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDosingDetail(Request $request)
    {
        $encounter_id = $request->get('encounterId') ?: Session::get('inpatient_encounter_id');
        $fldid = $request->get('fldid');
        $flditem = $request->get('flditem');

        $detail = Pathdosing::select('fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldstatus')
            ->where([
                'fldid' => $fldid,
                'flditem' => $flditem,
            ])->first();
        $lists = NurseDosing::select('fldid', 'fldtime', 'fldvalue', 'fldunit', 'fldfromtime', 'fldtotime')
            ->where([
                'fldencounterval' => $encounter_id,
                'flddoseno' => $fldid,
            ])->get();
        $med_detail = MedicineBrand::select('flddrug', 'fldvolunit')->where('fldbrandid', $flditem)->first();
        $detail->dayCount = $this->_get_day_count($encounter_id, $fldid, $lists);

        return response()->json(
            compact('detail', 'lists', 'med_detail')
        );
    }

    /**
     * @param $encounter_id
     * @param $flddoseno
     * @param null $data
     * @return int
     */
    private function _get_day_count($encounter_id, $flddoseno, $data = NULL)
    {
        if ($data == NULL) {
            $data = NurseDosing::select('fldid', 'fldtime', 'fldvalue', 'fldunit', 'fldfromtime', 'fldtotime')
                ->where([
                    'fldencounterval' => $encounter_id,
                    'flddoseno' => $flddoseno,
                ])->get();
        }

        $dates = [];
        foreach ($data as $d) {
            $date = explode(' ', $d->fldtime);
            $dates[$date[0]] = $date[1];
        }

        return count($dates);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDosingDetail(Request $request)
    {
        try {
            $encounter_id = $request->get('encounterId') ?: Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();

            $flddoseno = $request->get('flddoseno');
            $fldvalue = $request->get('fldvalue');
            $fldunit = $request->get('fldunit');

            $fldid = NurseDosing::insertGetId([
                'fldencounterval' => $encounter_id,
                'flddoseno' => $flddoseno,
                'fldvalue' => $fldvalue,
                'fldunit' => $fldunit,
                'fldfromtime' => NULL,
                'fldtotime' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return response()->json([
                'status' => TRUE,
                'data' => [
                    'fldid' => $fldid,
                    'fldvalue' => $fldvalue,
                    'fldunit' => $fldunit,
                    'fldtime' => $time,
                    'dayCount' => $this->_get_day_count($encounter_id, $flddoseno),
                ],
                'message' => 'Successfully saved information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save information.',
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDosingDetail(Request $request)
    {
        try {
            $column = $request->get('column');
            $fldid = $request->get('fldid');

            NurseDosing::where('fldid', $fldid)->update([
                $column => date('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully updated information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function generateLabelPDF(Request $request)
    {
        $fldid = $request->get('fldid');
        $encounter_id = Session::get('inpatient_encounter_id');

        $patientinfo = $this->_get_patient_detail($encounter_id);
        $data = Pathdosing::select('tblpatdosing.fldid', 'tblpatdosing.fldstarttime', 'tblpatdosing.fldroute', 'tblpatdosing.flditem', 'tblpatdosing.flddose', 'tblpatdosing.fldfreq', 'tblpatdosing.flddays', 'tblpatdosing.flditemtype', 'mb.fldvolunit')
            ->join('tblmedbrand AS mb', 'mb.fldbrandid', '=', 'tblpatdosing.flditem')
            ->where([
                'tblpatdosing.fldencounterval' => $encounter_id,
                'tblpatdosing.fldid' => $fldid,
            ])->first();
        $comment = Label::select('fldopinfo', 'fldipinfo', 'fldasepinfo')
            ->where('fldroute', 'injection')
            ->whereIn('flddrug', MedicineBrand::where('fldbrandid', $data->flditem)->pluck('flddrug')->toArray())
            ->first();

        return Facade::loadView('inpatient::pdf.statPrnLabels', compact('patientinfo', 'data', 'comment'))
            ->stream('labels_report.pdf');
    }

    /**
     * @param $encounter_id
     * @return mixed
     */
    private function _get_patient_detail($encounter_id)
    {
        return Helpers::getPatientByEncounterId($encounter_id);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function generateDrugInfoPDF(Request $request)
    {
        $fldid = $request->get('fldid');
        $encounter_id = Session::get('inpatient_encounter_id');

        $patientinfo = $this->_get_patient_detail($encounter_id);
        $data = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldid' => $fldid,
            ])->first();

        return Facade::loadView('inpatient::pdf.statPrnDrugInfo', compact('patientinfo', 'data'))
            ->stream('drug_info_report.pdf');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function generateReviewPDF(Request $request)
    {
        $fldid = $request->get('fldid');
        $encounter_id = Session::get('inpatient_encounter_id');

        $patientinfo = $this->_get_patient_detail($encounter_id);
        $all_data = [];

        return Facade::loadView('inpatient::pdf.statPrnReview', compact('patientinfo', 'all_data'))
            ->stream('review_report.pdf');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function generateExportMedicineDetailPDF(Request $request)
    {
        $fldid = $request->get('fldid');
        $flditem = $request->get('flditem');
        $encounter_id = $request->get('encounterId') ?: Session::get('inpatient_encounter_id');

        $patientinfo = $this->_get_patient_detail($encounter_id);
        $detail = Pathdosing::select('fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldstatus')
            ->where([
                'fldid' => $fldid,
                'flditem' => $flditem,
            ])->first();
        $drugs = NurseDosing::select('fldid', 'fldtime', 'fldvalue', 'fldunit', 'fldfromtime', 'fldtotime')
            ->where([
                'fldencounterval' => $encounter_id,
                'flddoseno' => $fldid,
            ])->get();
        $med_detail = MedicineBrand::select('flddrug', 'fldvolunit')->where('fldbrandid', $flditem)->first();

        foreach ($drugs as &$list) {
            $list->flddrug = $med_detail->flddrug;
            $list->flddose = $detail->flddose * $list->fldvalue;

            $csscolor = '70d470';
            $lockitem = '<i class="fa fa-lock"></i>';
            if ($detail->fldroute == 'injection') {
                if ($list->fldfromtime === null && $list->fldtotime === null) {
                    $lockitem = '<i class="fa fa-play"></i>';
                } else if ($list->fldfromtime !== null && $list->fldtotime === null) {
                    $csscolor = '000';
                    $lockitem = '<i class="fa fa-stop"></i>';
                }
            }
            $list->lockitem = $lockitem;
            $list->csscolor = $csscolor;
        }

        return Facade::loadView('inpatient::pdf.statPrnMedicineDetail', compact('patientinfo', 'drugs', 'flditem'))
            ->stream('drugs_detail_report.pdf');
    }
}
