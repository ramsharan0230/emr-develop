<?php

namespace Modules\Servicedata\Http\Controllers;

use App\Encounter;
use App\PatientInfo;
use App\PatTiming;
use App\ServiceCost;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class EquipmentController
 * @package Modules\Servicedata\Http\Controllers
 */
class EquipmentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayReport()
    {
        Helpers::jobRecord('fmEquipReport', 'Equipments Use');

        $data['equipments'] = ServiceCost::select('flditemname')
            ->where('flditemtype', 'Equipment')
            ->where(function ($query) {
                return $query->orWhere('fldgroup', 'LIKE', '%')
                    ->orWhere('fldgroup', 'LIKE', '%');
            })
            ->get();

        return view('servicedata::equipment-report', $data);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchList(Request $request)
    {
        $resultData = PatTiming::query();
        $resultData->select('fldid', 'fldencounterval', 'flditem', 'fldfirsttime', 'fldsecondtime');

        $to_date    = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $from_date  = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $equipments = $request->equipments;

        if (isset($equipments)) {
            $resultData->where('flditem', 'LIKE', $equipments);
        }

        if (isset($request->start_stop_date) && $request->start_stop_date == "start_date") {
            $resultData->where('fldfirsttime', '>=', $from_date);
            $resultData->where('fldfirsttime', '<=', $to_date);
        }

        if (isset($request->start_stop_date) && $request->start_stop_date == "stop_date") {
            $resultData->where('fldsecondtime', '>=', $from_date);
            $resultData->where('fldsecondtime', '<=', $to_date);
        }

        $resultArray = $resultData->with(['encounter', 'encounter.patientInfo'])
            ->where('fldtype', 'Equipment')
            ->paginate(25);
        $count       = 1;
        $html        = '';

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . $patient->fldfirsttime . '</td>';
            $html .= '<td>' . $patient->fldsecondtime . '</td>';
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

        $encounterIds= Encounter::select('fldencounterval')
            ->whereIn('fldpatientval', $patientInfo)
            ->pluck('fldencounterval');

        $resultArray = PatTiming::select('fldid', 'fldencounterval', 'flditem', 'fldfirsttime', 'fldsecondtime')
            ->whereIn('fldencounterval', $encounterIds)
            ->where('fldtype', 'Equipment')
            ->with(['encounter', 'encounter.patientInfo'])
            ->get();

        $html  = '';
        $count = 1;

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . $patient->fldfirsttime . '</td>';
            $html .= '<td>' . $patient->fldsecondtime . '</td>';
            $html .= '</tr>';
            $count++;
        }
        return $html;
    }

    public function displaySearchNameForm()
    {
        $data['routeName']   = route('display.consultation.equipment.search.name');
        $data['appendId'] = 'equipment_data';
        return view('consultpatientdata::common.search-name', $data);
    }

    public function generatePdf(Request $request)
    {
        $patientInfo = PatientInfo::select('fldpatientval')
            ->whereRaw('lower(fldptnamefir) like \'' . $request->firstname . '\'')
            ->whereRaw('lower(fldptnamelast) like \'' . $request->lastname . '\'')
            ->pluck('fldpatientval');

        $encounterIds= Encounter::select('fldencounterval')
            ->whereIn('fldpatientval', $patientInfo)
            ->pluck('fldencounterval');

        $resultArray = PatTiming::select('fldid', 'fldencounterval', 'flditem', 'fldfirsttime', 'fldsecondtime')
            ->whereIn('fldencounterval', $encounterIds)
            ->where('fldtype', 'Equipment')
            ->with(['encounter', 'encounter.patientInfo'])
            ->get();


        $data['result'] = $resultArray;
        $data['total'] = count($resultArray);
        return view('servicedata::pdf.equipment', $data);
    }
}
