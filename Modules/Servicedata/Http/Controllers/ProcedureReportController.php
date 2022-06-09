<?php

namespace Modules\Servicedata\Http\Controllers;

use App\Encounter;
use App\PatGeneral;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProcedureReportController extends Controller
{
    public function displayReport()
    {
        //if (Permission::checkPermissionFrontendAdmin('view-procedure-report')) {
            Helpers::jobRecord('fmProcedReport', 'Procedure Report');

            $data['procedure'] = PatGeneral::select('flditem')
                ->where('fldinput', 'Procedures')
                ->distinct('flditem')
                ->get();

            return view('servicedata::procedure-report', $data);
        // }

        // Session::flash('display_popup_error_success', true);
        // Session::flash('error_message', 'You are not authorized for this action.');
        // return redirect()->route('admin.dashboard');
    }

    public function searchList(Request $request)
    {
        $resultData = PatGeneral::query();
        $resultData->select('fldencounterval', 'fldtime', 'flditem', 'fldreportquali', 'fldid', 'flddetail');

        $to_date = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $from_date = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $last_status = $request->last_status;
        $procedure = $request->procedure;
        $gender = $request->gender;
        $age_from = $request->age_from * 365;
        $age_to = $request->age_to * 365;

        if (isset($last_status) && $last_status == "Exists(All)") {
            $resultData->where(function ($query) {
                return $query
                    ->orWhere('fldhead', '=', 'Discharged')
                    ->orWhere('fldhead', '=', 'LAMA')
                    ->orWhere('fldhead', '=', 'Refer')
                    ->orWhere('fldhead', '=', 'Death')
                    ->orWhere('fldhead', '=', 'Absconder');
            });
        }

        if (isset($last_status)) {
            $resultData->where('fldreportquali', 'LIKE', $last_status);
        }

        if (isset($procedure)) {
            $resultData->where('flditem', 'LIKE', $procedure);
        }

        if (isset($from_date)) {
            $resultData->where('fldtime', '>=', $from_date);
        }

        if (isset($to_date)) {
            $resultData->where('fldtime', '<=', $to_date);
        }

        if (isset($age_from) && isset($age_to)) {
            $resultDataPatientValAge = \DB::table('tblpatientinfo')->select('tblpatientinfo.fldpatientval')
                ->join('tblencounter', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->join('tblpatientdate', 'tblpatientdate.fldencounterval', '=', 'tblencounter.fldencounterval')
                ->where('tblpatientinfo.fldptbirday', 'LIKE', '%');

            if (isset($gender)) {
                $resultDataPatientValAge->where('tblpatientinfo.fldptsex', 'LIKE', $gender);
            }

            if ($age_from != null && $age_to != null) {
                $resultDataPatientValAge->whereRaw('DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday) >= ' . $age_from)
                    ->whereRaw('DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday) < ' . $age_to)
                    ->pluck('tblpatientinfo.fldpatientval');
            }


            $encounter = [];
            if ($resultDataPatientValAge)
                $encounter = Encounter::whereIn('fldpatientval', $resultDataPatientValAge)->pluck('fldencounterval');

            if (count($encounter))
                $resultData->whereIn('fldencounterval', $encounter);
        }

        $resultArray = $resultData->with(['encounter', 'encounter.patientInfo'])->paginate(25);
        $count = 1;
        $html = '';

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . $patient->fldtime . '</td>';
            $html .= '<td>' . $patient->flditem . '</td>';
            $html .= '<td>' . $patient->fldhead . '</td>';
            $html .= '<td>' . $patient->flddetail . '</td>';
            $html .= '</tr>';
            $count++;
        }
        $html .= '<tr><td colspan="20">' . $resultArray->appends(request()->all())->links() . '</td></tr>';
        return $html;
    }

    public function searchDataName(Request $request)
    {
        $patientInfo = PatientInfo::select('fldpatientval')
            ->whereRaw('lower(fldptnamefir) like \'' . $request->firstname . '\'')
            ->whereRaw('lower(fldptnamelast) like \'' . $request->lastname . '\'')
            ->pluck('fldpatientval');

        $encounterIds = Encounter::select('fldencounterval')
            ->whereIn('fldpatientval', $patientInfo)
            ->pluck('fldencounterval');

        $resultArray = PatGeneral::select('fldencounterval', 'fldtime', 'flditem', 'fldreportquali', 'fldid', 'flddetail')
            ->whereIn('fldencounterval', $encounterIds)
            ->with(['encounter', 'encounter.patientInfo'])
            ->get();

        $html = '';
        $count = 1;

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast. '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . $patient->fldtime . '</td>';
            $html .= '<td>' . $patient->flditem . '</td>';
            $html .= '<td>' . $patient->fldhead . '</td>';
            $html .= '<td>' . $patient->flddetail . '</td>';
            $html .= '</tr>';
            $count++;
        }
        return $html;
    }

    public function displaySearchNameForm()
    {
        $data['routeName'] = route('display.consultation.procedure.search.name');
        $data['appendId'] = 'procedure-data';
        return view('consultpatientdata::common.search-name', $data);
    }

    public function generatePdf(Request $request)
    {
        $resultData = PatGeneral::query();
        $resultData->select('fldencounterval', 'fldtime', 'flditem', 'fldreportquali', 'fldid', 'flddetail');

        $to_date = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $from_date = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $last_status = $request->last_status;
        $procedure = $request->procedure;
        $gender = $request->gender;
        $age_from = $request->age_from * 365;
        $age_to = $request->age_to * 365;

        if (isset($last_status) && $last_status == "Exists(All)") {
            $resultData->where(function ($query) {
                return $query
                    ->orWhere('fldhead', '=', 'Discharged')
                    ->orWhere('fldhead', '=', 'LAMA')
                    ->orWhere('fldhead', '=', 'Refer')
                    ->orWhere('fldhead', '=', 'Death')
                    ->orWhere('fldhead', '=', 'Absconder');
            });
        }

        if (isset($last_status)) {
            $resultData->where('fldreportquali', 'LIKE', $last_status);
        }

        if (isset($procedure)) {
            $resultData->where('flditem', 'LIKE', $procedure);
        }

        if (isset($from_date)) {
            $resultData->where('fldtime', '>=', $from_date);
        }

        if (isset($to_date)) {
            $resultData->where('fldtime', '<=', $to_date);
        }

        if (isset($age_from) && isset($age_to)) {
            $resultDataPatientValAge = \DB::table('tblpatientinfo')->select('tblpatientinfo.fldpatientval')
                ->join('tblencounter', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->join('tblpatientdate', 'tblpatientdate.fldencounterval', '=', 'tblencounter.fldencounterval')
                ->where('tblpatientinfo.fldptbirday', 'LIKE', '%');

            if (isset($gender)) {
                $resultDataPatientValAge->where('tblpatientinfo.fldptsex', 'LIKE', $gender);
            }

            if ($age_from != null && $age_to != null) {
                $resultDataPatientValAge->whereRaw('DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday) >= ' . $age_from)
                    ->whereRaw('DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday) < ' . $age_to)
                    ->pluck('tblpatientinfo.fldpatientval');
            }


            $encounter = [];
            if ($resultDataPatientValAge)
                $encounter = Encounter::whereIn('fldpatientval', $resultDataPatientValAge)->pluck('fldencounterval');

            if (count($encounter))
                $resultData->whereIn('fldencounterval', $encounter);
        }

        $resultArray = $resultData->with(['encounter', 'encounter.patientInfo'])->get();
        $count = 1;
        $html = '';

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . $patient->fldtime . '</td>';
            $html .= '<td>' . $patient->flditem . '</td>';
            $html .= '<td>' . $patient->fldhead . '</td>';
            $html .= '<td>' . $patient->flddetail . '</td>';
            $html .= '</tr>';
            $count++;
        }
        $data['view'] = $html;
        return view('servicedata::pdf.procedure', $data);
    }
}
