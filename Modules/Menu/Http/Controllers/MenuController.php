<?php

namespace Modules\Menu\Http\Controllers;

use App\CogentUsers;
use App\Consult;
use App\Department;
use App\Encounter;
use App\PatientInfo;
use App\Utils\Options;
use Carbon\Carbon;
use App\Utils\Helpers;
use Barryvdh\DomPDF\Facade as PDF;
use Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class MenuController
 * @package Modules\Menu\Http\Controllers
 */
class MenuController extends Controller
{
    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayWaitingForm(Request $request)
    {
        $data['hospital_department']=$request->selected_hospital_department;
        $data['flddept']=\Session::get('user_hospital_departments');
        $html = view('menu::menu-dynamic-views.waiting-form', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function waitingData(Request $request)
    {
        $datetimenew = date('Y-m-d');
        $startTime = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $endTime = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $encounterId = $request->encounter;
        $fldid = $request->fldid;

        $consult = Consult::select('fldconsultname', 'fldencounterval', 'fldconsulttime', 'fldid', 'fldstatus as consultstatus')
            ->where(function ($query) {
                $query->orWhere('fldstatus', 'Planned')
                    ->orWhere('fldstatus', 'Calling');
            })
            ->where(function ($queryEncounter) use ($encounterId) {
                if ($encounterId != "") {
                    $queryEncounter->where('fldencounterval', 'LIKE', $encounterId.'%');
                }
            })
            ->where(function ($queryEncounter1) use ($fldid) {
                if ($fldid != "") {
                    $queryEncounter1->where('fldconsultname', $fldid);
                }
            })
            ->where('fldtime', '>=', $startTime)
            ->where('fldtime', '<=', $endTime)
            ->with('encounter', 'encounter.patientInfo')
            ->paginate(200);

        $html = '';
        foreach ($consult as $item) {
            if ($item) {
                $fldstatus = $item->consultstatus == 'Planned' ? 'Call' : $item->consultstatus;
                $html .= '<tr>';
                $html .= '<td>' . $item->fldencounterval . '</td>';
                $html .= '<td>' . $item->encounter->patientInfo->fullname . '</td>';
                $html .= '<td>' . $item->fldconsultname . '</td>';
                $html .= '<td>' . date('Y-m-d', strtotime($item->fldconsulttime)) . '</td>';
                $html .= '<td><a href="javascript:;" encounter_id="' . $item->fldencounterval . '" consult_id="' . $item->fldid . '" class="callencounter btn  btn-sm-in ujhbtn-primary col-sm-2">' . $fldstatus . '</a></td>';
                $html .= '</tr>';
            }
        }

        return $html;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function callwaiting(Request $request)
    {
        $encounterid = $request->encounterid;
        $consult_id = $request->consult_id;
        $roomnum = Session::get('room_number');

        $data = array(
            'fldroom' => $roomnum

        );
        Encounter::where('fldencounterval', $encounterid)->update($data);


        $encounterdetail = Encounter::where('fldencounterval', $encounterid)->first();

        $dataconsult = array(
            'fldstatus' => 'Calling'
        );
        Consult::where('fldid', $consult_id)->where('fldencounterval', $encounterdetail->fldencounterval)->update($dataconsult);

        return response()->json([
            'success' => [
                'msg' => 'saved',
                'patient' => $encounterdetail->fldpatientval,
                'enc' => $encounterid,
            ]
        ]);


    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displaySearchForm()
    {
        $html = view('menu::menu-dynamic-views.search-form')->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function displaySearchResult(Request $request)
    {
        // dd($request);
        $searchName = $request->searchName;
        $searchSurName = $request->searchSurName;
        $searchAddress = $request->searchAddress;
        $searchContact = $request->searchContact;
        $searchDistrict = $request->searchDistrict;
        $searchGender = $request->searchGender;
        if (!isset($searchName) && !isset($searchSurName) && !isset($searchAddress) && !isset($searchContact) && !isset($searchDistrict) && !isset($searchGender)) {
            return "Select at least one option.";
        }

        $patientSearchResult = PatientInfo::select('fldpatientval', 'fldptnamefir', 'fldptnamelast', 'fldptsex', 'fldptaddvill', 'fldptadddist', 'fldptcontact', 'fldptbirday', 'fldadmitfile', 'fldmidname', 'fldrank');

        if (isset($searchGender) && !is_null($searchGender)) {
            $patientSearchResult->whereRaw("LOWER(`fldptsex`) LIKE ? ", [trim(strtolower($searchGender)) . "%"]);
        }

        if (isset($searchName) && !is_null($searchName)) {
            $patientSearchResult->whereRaw("LOWER(`fldptnamefir`) LIKE ? ", [trim(strtolower($searchName)) . "%"]);
        }

        if (isset($searchSurName) && !is_null($searchSurName)) {
            $patientSearchResult->whereRaw("LOWER(`fldptnamelast`) LIKE ? ", [trim(strtolower($searchSurName)) . "%"]);
        }

        if (isset($searchAddress) && !is_null($searchAddress)) {
            $patientSearchResult->whereRaw("LOWER(`fldptaddvill`) LIKE ? ", [trim(strtolower($searchAddress)) . "%"]);
        }

        if (isset($searchDistrict) && !is_null($searchDistrict)) {
            $patientSearchResult->whereRaw("LOWER(`fldptadddist`) LIKE ? ", [trim(strtolower($searchDistrict)) . "%"]);
        }

        if (isset($searchContact) && !is_null($searchContact)) {
            $patientSearchResult->whereRaw("LOWER(`fldptcontact`) LIKE ? ", [trim(strtolower($searchContact)) . "%"]);
        }
        $resultArray = $patientSearchResult->get();
        // dd($resultArray);
        $html = '';
        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $patient->fldpatientval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->fldptnamefir . ' ' . $patient->fldmidname . ' ' . $patient->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->fldptsex . '</td>';
            $html .= '<td>' . $patient->fldptaddvill . '</td>';
            $html .= '<td>' . $patient->fldptadddist . '</td>';
            $html .= '<td>' . $patient->fldptcontact . '</td>';
            $html .= '<td><a href="javascript:void(0);" onclick="displayPatientImage(' . $patient->fldpatientval . ')"><i class="fas fa-eye"></i></a><a href="javascript:void(0);" onclick="lastEncounter(' . $patient->fldpatientval . ')"><i class="fas fa-eye"></i></a><a href="javascript:void(0);" onclick="lastAllEncounter(' . $patient->fldpatientval . ')"><i class="fas fa-eye"></i></a></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function lastEncounter()
    {
        $encounterIds = Options::get('last_encounter_id');

        $emcountersList = unserialize($encounterIds);
        $newArrayOfLastEncounter = array_reverse(array_filter($emcountersList), true);
        $data['arrayEncounter'] = $newArrayOfLastEncounter;
        $html = view('menu::menu-dynamic-views.last-encounter', $data)->render();
        return $html;
    }


    /**
     * @return array|string
     * @throws \Throwable
     */
    public function lastEncounterInpatient()
    {
        $encounterIds = Options::get('inpatient_last_encounter_id');

        $data['arrayEncounter'] = array_reverse(unserialize($encounterIds));

        $html = view('menu::menu-dynamic-views.last-encounter-inpatient', $data)->render();
        return $html;
    }


    /**
     * @return array|string
     * @throws \Throwable
     */
    public function lastEncounterDelivery()
    {
        $encounterIds = Options::get('delivery_last_encounter_id');

        $data['arrayEncounter'] = array_reverse(unserialize($encounterIds));

        $html = view('menu::menu-dynamic-views.last-encounter-delivery', $data)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function lastEncounterMajor()
    {
        $encounterIds = Options::get('major_procedure_last_encounter_id');

        $data['arrayEncounter'] = array_reverse(unserialize($encounterIds));

        $html = view('menu::menu-dynamic-views.last-encounter-major', $data)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function lastEncounterEye()
    {
        $encounterIds = Options::get('eye_last_encounter_id');

        $data['arrayEncounter'] = array_reverse(unserialize($encounterIds));

        $html = view('menu::menu-dynamic-views.last-encounter-eye', $data)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function lastEncounterEmergency()
    {
        $encounterIds = Options::get('emergency_last_encounter_id');

        $data['arrayEncounter'] = array_reverse(unserialize($encounterIds));

        $html = view('menu::menu-dynamic-views.last-encounter-emergency', $data)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function lastEncounterDental()
    {
        $encounterIds = Options::get('dental_last_encounter_id');
        // dd($encounterIds)
        $data['arrayEncounter'] = array_reverse(unserialize($encounterIds));
        $html = view('menu::menu-dynamic-views.last-encounter-dental', $data)->render();
        return $html;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setLastEncounterInpatient(Request $request)
    {
        session(['inpatient_encounter_id' => $request->lastEncounter ?? 0]);
        return redirect('inpatient');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setLastEncounterEye(Request $request)
    {
        session(['eye_encounter_id' => $request->lastEncounter ?? 0]);
        return redirect('eye');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setLastEncounterDelivery(Request $request)
    {
        session(['delivery_encounter_id' => $request->lastEncounter ?? 0]);
        return redirect('delivery');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setLastEncounterEmergency(Request $request)
    {
        session(['emergency_encounter_id' => $request->lastEncounter ?? 0]);
        return redirect('emergency');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setLastEncounterMajor(Request $request)
    {
        session(['major_procedure_encounter_id' => $request->lastEncounter ?? 0]);
        return redirect('majorprocedure');
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLastEncounter(Request $request)
    {
        session(['encounter_id' => $request->lastEncounter ?? 0]);
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function patientEncounters(Request $request)
    {
        $patient_id = $request->patient_id;
        //dd($patient_id)

        $encounterIds = Encounter::getAllEncounterPatient($patient_id);
        $data['encounters'] = $encounterIds;
        $html = view('menu::menu-dynamic-views.patient-encounters', $data)->render();
        return $html;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setLastEncounterDental(Request $request)
    {
        session(['dental_encounter_id' => $request->lastEncounter ?? 0]);
        return redirect('dental');
    }


    /**
     * @param Request $request
     * @return string
     */
    public function exportSearchResultPdf(Request $request)
    {
        // dd($request);
        $searchName = $request->searchName;
        $searchSurName = $request->searchSurName;
        $searchAddress = $request->searchAddress;
        $searchContact = $request->searchContact;
        $searchDistrict = $request->searchDistrict;
        $searchGender = $request->searchGender;
        try {
            if (!isset($searchName) && !isset($searchSurName) && !isset($searchAddress) && !isset($searchContact) && !isset($searchDistrict) && !isset($searchGender)) {
                return "Select at least one option.";
            }

            $patientSearchResult = PatientInfo::select('fldpatientval', 'fldptnamefir', 'fldmidname', 'fldptnamelast', 'fldptsex', 'fldptaddvill', 'fldptadddist', 'fldptcontact', 'fldptbirday', 'fldadmitfile', 'fldrank');

            if (isset($searchGender) && !is_null($searchGender)) {
                $patientSearchResult->whereRaw("LOWER(`fldptsex`) LIKE ? ", [trim(strtolower($searchGender)) . "%"]);
            }

            if (isset($searchName) && !is_null($searchName)) {
                $patientSearchResult->whereRaw("LOWER(`fldptnamefir`) LIKE ? ", [trim(strtolower($searchName)) . "%"]);
            }

            if (isset($searchSurName) && !is_null($searchSurName)) {
                $patientSearchResult->whereRaw("LOWER(`fldptnamelast`) LIKE ? ", [trim(strtolower($searchSurName)) . "%"]);
            }

            if (isset($searchAddress) && !is_null($searchAddress)) {
                $patientSearchResult->whereRaw("LOWER(`fldptaddvill`) LIKE ? ", [trim(strtolower($searchAddress)) . "%"]);
            }

            if (isset($searchDistrict) && !is_null($searchDistrict)) {
                $patientSearchResult->whereRaw("LOWER(`fldptadddist`) LIKE ? ", [trim(strtolower($searchDistrict)) . "%"]);
            }

            if (isset($searchContact) && !is_null($searchContact)) {
                $patientSearchResult->whereRaw("LOWER(`fldptcontact`) LIKE ? ", [trim(strtolower($searchContact)) . "%"]);
            }
            $resultArray = $patientSearchResult->get();
            // dd($resultArray);
            $data['result'] = $resultArray;

            return view('menu::pdf.patient-search-pdf', $data)/*->setPaper('a4')->stream('patient_search_report.pdf')*/ ;
        } catch (\Exception $e) {
            dd($e);
        }


    }
}
