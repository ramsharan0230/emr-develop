<?php

namespace Modules\ConsultPatientData\Http\Controllers;

use App\Departmentbed;
use App\Encounter;
use App\PatientInfo;
use App\PatTiming;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

/**
 * Class TransitionController
 * @package Modules\ConsultPatientData\Http\Controllers
 */
class TransitionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayReport()
    {
        if (Permission::checkPermissionFrontendAdmin('transition-report')) {
            $data['department'] = Departmentbed::select('flddept')->distinct('flddept')->get();
            return view('consultpatientdata::transition-report', $data);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchList(Request $request)
    {
        $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $resultData = PatTiming::query();
        $resultData->select('fldencounterval', 'fldsecondreport', 'fldfirsttime', 'fldsecondtime', 'fldid');

        $department = $request->department;
        $to_date = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $from_date = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $entry_exit_date = $request->entry_exit_date;

        if (isset($department)) {
            $resultDatasurname = Departmentbed::select('fldbed')
                ->where('flddept', $department)
                ->pluck('fldbed');

            if (count($resultDatasurname))
                $resultData->whereIn('fldsecondreport', $resultDatasurname);
        }

        if (isset($entry_exit_date) && $entry_exit_date == "entry_date") {
            $resultData->where('fldfirsttime', '>=', $from_date);
            $resultData->where('fldfirsttime', '<=', $to_date);
        }

        if (isset($entry_exit_date) && $entry_exit_date == "exit_date") {
            $resultData->where('fldsecondtime', '>=', $from_date);
            $resultData->where('fldsecondtime', '<=', $to_date);
        }

        $resultData->where('fldsecondsave', '=', 1)
            ->where('fldtype', 'General Services')
            ->where('fldfirstreport', 'Bed')
            ->with(['encounter', 'encounter.patientInfo']);

        $count = 1;
        $html = '';
        if($request->has('typePdf')){
            $resultArray = $resultData->get();
        }else{
            $resultArray = $resultData->paginate(25);
        }
        foreach ($resultArray as $patient) {
            //            return $patient;
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . $patient->encounter->flddoa . '</td>';
            $html .= '<td>' . $patient->encounter->fldcurrlocat . '</td>';
            $html .= '<td>' . $patient->fldfirsttime . '</td>';
            $html .= '<td>' . $patient->fldsecondtime . '</td>';
            $html .= '</tr>';
            $count++;
        }
        $html .= '<tr><td colspan="20">' . $resultArray->appends(request()->all())->links() . '</td></tr>';
        $data['html'] = $html;

        if($request->has('typePdf')){
            $data = [];
            $data['html'] = $html;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['certificate'] = "TRANSITION REPORT";
            return view('consultpatientdata::pdf.transition-report-pdf', $data);
        }else{
            return $html;
        }
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

        $resultArray = PatTiming::select('fldencounterval', 'fldsecondreport', 'fldfirsttime', 'fldsecondtime', 'fldid')
            ->whereIn('fldencounterval', $encounterIds)
            ->where('fldsecondsave', '=', 1)
            ->with('encounter', 'encounter.patientInfo')
            ->get();

        $html = '';
        $count = 1;

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>Doa</td>';
            $html .= '<td>BedNo</td>';
            $html .= '<td>' . $patient->fldfirsttime . '</td>';
            $html .= '<td>' . $patient->fldsecondtime . '</td>';
            $html .= '</tr>';
            $count++;
        }
        return $html;
    }

    public function displaySearchNameForm()
    {
        $data['routeName'] = route('display.consultation.transition.search.name');
        $data['appendId'] = 'transition_data';
        return view('consultpatientdata::common.search-name', $data);
    }
}
