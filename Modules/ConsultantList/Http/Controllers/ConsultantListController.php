<?php

namespace Modules\ConsultantList\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Consult;
use App\Department;
use App\Encounter;
use App\PatientDate;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Cache;

/**
 * Class ConsultantListController
 * @package Modules\ConsultantList\Http\Controllers
 */
class ConsultantListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['department'] = Cache::remember('department_list', 60 * 60 * 24, function () {
            return Department::select('flddept', 'fldcateg')->where('fldcateg', 'like', '%')->get();
        });
        $data['modes'] = Cache::remember('billing_set_list', 60 * 60 * 24, function () {
            return BillingSet::all();
        });
        $data['departmentConsult'] = $data['department']->where('fldcateg', 'like', 'Consultation')->all();
        return view('consultantlist::consultant-list', $data);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchData(Request $request)
    {
//        return $request->all();
        $resultData = Consult::query();
        $resultData->select('fldencounterval', 'fldconsulttime', 'fldorduserid', 'fldconsultname', 'fldid', 'flduserid', 'fldcomment', 'fldbillingmode');

        $billing = $request->billing;
        $consult_mode = $request->consult_mode;
        $consultant = $request->consultant;
        $date_eng = $request->date_eng;
        $department = $request->department;
        $status = $request->status;
        $visit = $request->visit;

        if ($billing != "") {
            $resultData->where('fldbillingmode', 'LIKE', $billing);
        }

        if ($date_eng != "") {
            $startTime = Carbon::parse($date_eng)->setTime(00, 00, 00);
            $endTime = Carbon::parse($date_eng)->setTime(23, 59, 59);
            $resultData->where('fldconsulttime', '>=', $startTime);
            $resultData->where('fldconsulttime', '<=', $endTime);
        }

        if ($status != "") {
            $resultData->where('fldstatus', 'LIKE', $status);
        }

        if ($department != "") {
            $resultData->where('fldconsultname', 'LIKE', $department);
        }

        if ($consultant != "") {
            $resultData->where('flduserid', 'LIKE', $consultant);
        }

        $consultData = $resultData->with(['user', 'encounter', 'encounter.patientInfo'])->limit(200)->get();
        $html = '';

        $numberCount = 0;
        if ($consultData) {
            foreach ($consultData as $consult) {
                $name = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldfullname : "";
                $age = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldagestyle : "";
                $contact = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldptcontact : "";
                $consultant = $consult->user ? $consult->user->fullname : '';
                $consultant .= $consult->fldconsultname;

                $html .= '<tr>';
                $html .= '<td>' . ++$numberCount . '</td>';
//                $html .= '<td><input type="checkbox" name="consult[]" value="' . $consult->fldid . '"></td>';
                $html .= '<td>' . $consult->fldconsulttime . '</td>';
                // $html .= '<td>' . $consult->fldconsultname . '</td>';

                $html .= '<td>' . $consult->fldencounterval . '</td>';
                $html .= '<td>' . $name . '<br>';
                $html .= $age . ',&nbsp;';
                $html .= $contact . '</td>';
                $html .= '<td>' . $consultant . '</td>';
                $html .= '<td>' . $consult->fldcomment . '</td>';
                $html .= '<td></td>';
                $encounterForFollowUp = "'" . $consult->fldencounterval . "'";
                $html .= '<td><a href="javascript:;" title="Edit" onclick="editConsultantList(' . $consult->fldid . ')"><i class="fas fa-edit text-primary"></i></a> | <a href="javascript:;" title="Edit" onclick="consultantList.followUpDateAdd(' . $encounterForFollowUp . ')"><i class="fas fa-user-edit text-info"></i></a></td>';
                $html .= '</tr>';
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchDataByEncounter(Request $request)
    {
        $consultData = Consult::where('fldencounterval', $request->encounter)
            ->with(['encounter', 'encounter.patientInfo', 'user'])
            ->get();

        $html = '';

        $numberCount = 0;
        if ($consultData) {
            foreach ($consultData as $consult) {
                $name = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldfullname : "";
                $age = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldagestyle : "";
                $contact = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldptcontact : "";
                $consultant = $consult->user ? $consult->user->fullname : '';
                $consultant .= $consult->fldconsultname;

                $html .= '<tr>';
                $html .= '<td>' . ++$numberCount . '</td>';
//                $html .= '<td><input type="checkbox" name="consult[]" value="' . $consult->fldid . '"></td>';
                $html .= '<td>' . $consult->fldconsulttime . '</td>';
                // $html .= '<td>' . $consult->fldconsultname . '</td>';

                $html .= '<td>' . $request->encounter . '</td>';
                $html .= '<td>' . $name . '<br>';
                $html .= $age . ',&nbsp;';
                $html .= $contact . '</td>';
                $html .= '<td>' . $consultant . '</td>';
                $html .= '<td>' . $consult->fldcomment . '</td>';
                $html .= '<td></td>';
                $encounterForFollowUp = "'" . $consult->fldencounterval . "'";
                $html .= '<td><a href="javascript:;" title="Edit" onclick="editConsultantList(' . $consult->fldid . ')"><i class="fas fa-edit text-primary"></i></a> | <a href="javascript:;" title="Edit" onclick="consultantList.followUpDateAdd(' . $encounterForFollowUp . ')"><i class="fas fa-user-edit text-info"></i></a></td>';
                $html .= '</tr>';
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchDataByName(Request $request)
    {
        $consultData = Consult::where('fldorduserid', 'LIKE', $request->fullname)
            ->with(['encounter', 'encounter.patientInfo', 'user'])
            ->get();

        $html = '';
        $numberCount = 0;
        if ($consultData) {
            foreach ($consultData as $consult) {
                $name = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldfullname : "";
                $age = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldagestyle : "";
                $contact = $consult->encounter->patientInfo ? $consult->encounter->patientInfo->fldptcontact : "";
                $consultant = $consult->user ? $consult->user->fullname : '';
                $consultant .= $consult->fldconsultname;

                $html .= '<tr>';
                $html .= '<td>' . ++$numberCount . '</td>';
//                $html .= '<td><input type="checkbox" name="consult[]" value="' . $consult->fldid . '"></td>';
                $html .= '<td>' . $consult->fldconsulttime . '</td>';
                // $html .= '<td>' . $consult->fldconsultname . '</td>';

                $html .= '<td>' . $request->encounter . '</td>';
                $html .= '<td>' . $name . '<br>';
                $html .= $age . ',&nbsp;';
                $html .= $contact . '</td>';
                $html .= '<td>' . $consultant . '</td>';
                $html .= '<td>' . $consult->fldcomment . '</td>';
                $html .= '<td></td>';
                $encounterForFollowUp = "'" . $consult->fldencounterval . "'";
                $html .= '<td><a href="javascript:;" title="Edit" onclick="editConsultantList(' . $consult->fldid . ')"><i class="fas fa-edit text-primary"></i></a> | <a href="javascript:;" title="Edit" onclick="consultantList.followUpDateAdd(' . $encounterForFollowUp . ')"><i class="fas fa-user-edit text-info"></i></a></td>';
                $html .= '</tr>';
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function encounterDataForAddition(Request $request)
    {
        $encounterData = Encounter::where('fldencounterval', $request->encounter)->with('patientInfo')->first();
        $data['fullName'] = $encounterData->patientInfo->fullName();
        $data['billingMode'] = $encounterData->fldbillingmode;
        $data['currLocation'] = $encounterData->fldcurrlocat;
        return $data;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createConsultant(Request $request)
    {
        $validatedData = $request->validate([
            'billing_mode' => 'required',
            'consult_date_add' => 'required',
            'fullname' => 'required',
            'encounter' => 'required|exists:tblencounter,fldencounterval',
            'department' => 'required',
        ]);
        try {
            $insertData['fldencounterval'] = $request->encounter;
            $insertData['fldconsultname'] = $request->department;
            $insertData['fldconsulttime'] = date('Y-m-d H:i:s', strtotime("$request->date_eng_add $request->consult_time_add"));
            $insertData['fldcomment'] = $request->comment;
            $insertData['fldstatus'] = 'Planned';
            $insertData['flduserid'] = $request->consultant_add;
            $insertData['fldbillingmode'] = $request->billing_mode;
            $insertData['fldorduserid'] = \Auth::guard('admin_frontend')->user()->flduserid;
            $insertData['fldtime'] = date('Y-m-d H:i:s');
            $insertConsultDate['fldcomp'] = null;
            $insertData['fldsave'] = 0;
            $insertData['xyz'] = 0;
            $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            Consult::create($insertData);

            $insertConsultDate['fldencounterval'] = $request->encounter;
            $insertConsultDate['fldhead'] = 'Registered';
            $insertConsultDate['fldcomment'] = null;
            $insertConsultDate['flduserid'] = \Auth::guard('admin_frontend')->user()->flduserid;
            $insertConsultDate['fldtime'] = date('Y-m-d H:i:s');
            $insertConsultDate['fldcomp'] = null;
            $insertConsultDate['fldsave'] = 1;
            $insertConsultDate['flduptime'] = null;
            $insertConsultDate['xyz'] = 0;
            $insertConsultDate['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            PatientDate::create($insertConsultDate);

            Encounter::where('fldencounterval', $request->encounter)->update(['fldadmission' => 'Registered', 'xyz' => 0]);

            return response()->json([
                'success' => true,
                'message' => "consultant added successfully."
            ]);
        } catch (\Exception $e) {
//            dd($e);
            return response()->json([
                'success' => false,
                'message' => "Something went wrong."
            ]);
        }

    }

    public function editFormConsultantList(Request $request)
    {
        $data['consultData'] = Consult::where('fldid', $request->fldid)->first();
        $data['department'] = Cache::remember('department_list', 60 * 60 * 24, function () {
            return Department::select('flddept', 'fldcateg')->where('fldcateg', 'like', '%')->get();
        });
        $data['modes'] = Cache::remember('billing_set_list', 60 * 60 * 24, function () {
            return BillingSet::all();
        });
        $data['departmentConsult'] = $data['department']->where('fldcateg', 'like', 'Consultation')->all();
        $html = view('consultantlist::dynamic-views.edit-consultant-list', $data)->render();
        return $html;
    }

    public function updateConsultant(Request $request)
    {
        $validatedData = $request->validate([
            'billing_mode' => 'required',
            'date_eng_edit' => 'required',
            'consult_date_add' => 'required',
            'department' => 'required',
        ]);
        try {
            $insertData['fldconsultname'] = $request->department;
            $insertData['fldconsulttime'] = date('Y-m-d H:i:s', strtotime("$request->date_eng_edit $request->consult_time_edit"));
            $insertData['fldcomment'] = $request->comment;
            $insertData['fldstatus'] = $request->status;
            $insertData['flduserid'] = $request->consultant_edit;
            $insertData['fldbillingmode'] = $request->billing_mode;
            $insertData['fldorduserid'] = \Auth::guard('admin_frontend')->user()->flduserid;
            $insertData['fldtime'] = date('Y-m-d H:i:s');
            $insertData['xyz'] = 0;

            Consult::where([['fldid', $request->fldid]])->update($insertData);

            return response()->json([
                'success' => true,
                'message' => "consultant added successfully."
            ]);
        } catch (\Exception $e) {
//            dd($e);
            return response()->json([
                'success' => false,
                'message' => "Something went wrong."
            ]);
        }

    }

    public function followUpDateAdd(Request $request)
    {
        $validatedData = $request->validate([
            'after_days' => 'required',
            'date_eng_follow_up' => 'required',
            'encounter_id_follow_up' => 'required',
            'consult_time_edit' => 'required',
        ]);
        $encounter_id_follow_up = $request->encounter_id_follow_up;
        try {
            $date = Carbon::createFromFormat('Y-m-d', $request->date_eng_follow_up);
            $daysToAdd = $request->after_days;
            $date = $date->addDays($daysToAdd);
            $newDate = date('Y-m-d', strtotime($date));
            $updateData = [
                'fldfollowdate' => date('Y-m-d H:i:s', strtotime("$newDate $request->consult_time_edit")),
                'xyz' => 0
            ];
            Encounter::where([['fldencounterval', $encounter_id_follow_up]])->update($updateData);

            return response()->json([
                'success' => true,
                'message' => "consultant added successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong."
            ]);
        }
    }
}
