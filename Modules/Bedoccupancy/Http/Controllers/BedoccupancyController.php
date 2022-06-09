<?php

namespace Modules\Bedoccupancy\Http\Controllers;

use App\Department;
use App\Encounter;
use App\StructExam;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Session;

class BedoccupancyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function bedoccc(Request $request)
    {
        $data['departments'] = $department = StructExam::distinct()->where('fldclass', 'Departmental')->get(['fldsubclass as col']);

        $information = array();
        if ($request->isMethod('post')) {
            $searchdoor = $request->door;
            $color = $request->color;
            $encid = $request->encounter_id;
            $encname = $request->encounter_name;
            // dd($encname);
            /*$nameIds = Encounter::whereHas('patientInfo', function ($query) use ($encname) {
                $query->where('fldptnamefir', 'like', $encname . '%');
                $query->orwhere('fldptnamelast', 'like', $encname . '%');
            })->limit(100)->pluck('fldencounterval');*/
            if ($searchdoor == '%') {
                $qbeds = Encounter::select('fldencounterval', 'fldcurrlocat', 'fldrank')
                    ->where('fldadmission', 'Admitted');

                $qbeds->orderBy('fldcurrlocat', 'ASC');

                // sql = "select fldencounterval,fldcurrlocat from tblencounter where fldadmission="Admitted" ORDER BY fldcurrlocat ASC"                       ''
                // res = modDatabase.$myConn.Exec(sql, )
                // For Each res
                //   xbedlist.Add(res["fldencounterval"] & "@" & res["fldcurrlocat"])
                //   xenclist.Add(res["fldencounterval"])
            } elseif ($searchdoor == 'Unallocated') {
                $qbeds = Encounter::select('fldencounterval', 'fldcurrlocat', 'fldrank')
                    ->where('fldadmission', 'Admitted');

                $qbeds->orderBy('fldcurrlocat', 'ASC');
                //             sql = "select fldencounterval,fldcurrlocat from tblencounter where fldadmission=&1 and fldcurrlocat IS &2"                       ''
                // res = modDatabase.$myConn.Exec(sql, "Admitted", Null)
                // For Each res
                //   xbedlist.Add(res["fldencounterval"] & "@" & res["fldcurrlocat"])
                //   xenclist.Add(res["fldencounterval"])
            } else {
                // sql = "select fldencounterval,fldbed from tbldepartmentbed where fldencounterval like &1 and flddept=&2 ORDER BY fldbed ASC"                       ''
                // res = modDatabase.$myConn.Exec(sql, "%", cmbdept.Text)
                // For Each res
                //   xbedlist.Add(res["fldencounterval"] & "@" & res["fldbed"])
                //   xenclist.Add(res["fldencounterval"])
            }
        }
    }

    public function index(Request $request)
    {

        $data['encounters'] = [];
        $data['departments'] = $departments = Department::where('fldcateg', 'Patient Ward')->orWhere('fldcateg', 'Emergency')->get();

        $data['user_department'] = Helpers::getUserdepartmentInArray(Auth::guard('admin_frontend')->user()->id);

        //dd($data['user_department']);
        $data['requestData'] = $request->all();
        $data['encounter_name'] = $encname = $request->encounter_name;
        $data['encounter_id'] = $encounter_id = $request->encounter_id;


        if ($departments) {
            foreach ($departments as $department) {
                $beds = Encounter::whereHas('departmentBed', function ($query) use ($department) {
                    $query->where('flddept', 'like', $department->flddept);
                })


                    ->where('fldadmission', 'Admitted')
                    ->select('fldheight', 'flduserid', 'flddoa', 'fldencounterval', 'fldpatientval', 'fldregdate', 'fldfollowdate', 'fldadmission')->orderBy('fldencounterval', 'ASC')
                    ->with('PatFindings', 'patientInfo', 'PatPlanning')
                    ->get();
                $information = [];
                if ($beds) {
                    $door = 0;
                    foreach ($beds as $k => $b) {
                        $information[$k]['dignosis'] = '';

                        if ($door === $b->flddept)
                            $information[$k]['door'] = $b->flddept;
                        else
                            $information[$k]['door'] = 0;

                        $door = $b->flddept;


                        $encounter_id = $b->fldencounterval;
                        $encounter_detail = $beds->where('fldencounterval', $encounter_id)
                            ->first();

                        $patient_info = $encounter_detail->patientInfo;

                        /*$patient_info = PatientInfo::select('fldptcontact', 'fldpatientval', 'fldencrypt', 'fldptadddist', 'fldptaddvill', 'fldptnamefir', 'fldptnamelast', 'fldencrypt', 'fldptsex', 'fldptbirday')
                            ->where('fldpatientval', $patient_id)
                            ->first();*/

                        $final_diagnosis = $b->PatFindings
                            ->where('fldtype', 'Final Diagnosis')
                            ->where('fldsave', '1')
                            ->all();

                        $pro_diagnosis = $b->PatFindings
                            ->where('fldtype', 'Provisional Diagnosis')
                            ->where('fldsave', '1')
                            ->all();

                        $information[$k]['fldadmission'] = $b->fldadmission;
                        $information[$k]['encounter_id'] = $encounter_id;
                        $information[$k]['patient_id'] = $patient_info->fldpatientval ?? "";
                        $information[$k]['name'] = $patient_info->fullname ?? "";
                        $information[$k]['agesex'] = $patient_info && $patient_info->fldptbirday && $patient_info->fldptsex ? $patient_info->fldagestyle . ' /' . $patient_info->fldptsex ?? "" : "";
                        // $information[$k]['agesex'] = $patient_info && $patient_info->fldptbirday && $patient_info->fldptsex ? \Carbon\Carbon::parse($patient_info->fldptbirday ?? "")->age . ' yrs/' . $patient_info->fldptsex ?? "" : "";
                        $information[$k]['birthday'] = $patient_info &&  $patient_info->fldptbirday ? \Carbon\Carbon::parse($patient_info->fldptbirday)->format('d/m/Y') : "";
                        $information[$k]['sex'] = $patient_info->fldptsex ?? "";


                        $information[$k]['fldptaddvill'] = $patient_info->fldptaddvill ?? "";
                        $information[$k]['fldmunicipality'] = $patient_info->fldmunicipality ?? "";
                        $information[$k]['fldptcontact'] = $patient_info->fldptcontact ?? "";
                        $information[$k]['fldptguardian'] = $patient_info->fldptguardian ?? "";


                        if ($final_diagnosis) {
                            foreach ($final_diagnosis as $fin) {
                                $information[$k]['dignosis'] .= '[' . $fin->fldcodeid . '] ' . $fin->fldcode . ',';
                            }
                        }
                        if ($pro_diagnosis) {
                            foreach ($pro_diagnosis as $pro) {
                                $information[$k]['dignosis'] .= '[' . $pro->fldcodeid . '] ' . $pro->fldcode . ',';
                            }
                        }

                        $information[$k]['consult'] = $encounter_detail->flduserid;

                        $information[$k]['fldbed'] = $b->fldcurrlocat ?? $b->departmentBed->fldbed ?? 0;
                        $information[$k]['flddept'] = $b->flddept;
                        $information[$k]['progress'][] = $b->PatPlanning
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldplancategory', 'IP Monitoring')
                            ->all();
                    }

                    $data['encounters'] = $information;
                }



                $data['details'][$department->flddept] =  $data['encounters'];
                $data['encounters'] = [];
            }
        }
     //    dd($data['details']);






        return view('bedoccupancy::bedoccupancy', $data);
    }

    public function searchBed(Request $request)
    {
        $data['requestData'] = $request->all();
        $data['encounter_name'] = $encname = $request->encounter_name;

        $nameIds = Encounter::query();
        if ($encname != "") {
            $nameIds->whereHas('patientInfo', function ($query) use ($encname) {
                $query->orWhere('fldptnamefir', 'like', $encname . '%')
                    ->orWhere('fldptnamelast', 'like', $encname . '%');
            });
        }
        //            ->pluck('fldencounterval');

        $searchdoor = (isset($request->door) && $request->door != '') ? $request->door : '';
        $color = $request->color;
        $encid = $request->encounter_id;

        //color of the bed
        if ($color != "") {
            $finalEncounters = $nameIds->where('fldheight', 'LIKE', $color)->limit(100)->pluck('fldencounterval');
        } else {
            $finalEncounters = [];
            //            $finalEncounters = $nameIds->limit(100)->pluck('fldencounterval');
        }

        if ($searchdoor == '%') {

            //            $qbeds = Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval')
            $nameIds->where('fldadmission', 'Admitted');

            /*if (count($indoorColorEncounters)) {
                $qbeds->whereIn('fldencounterval', $indoorColorEncounters);
            }*/

            if ($finalEncounters)
                $nameIds->whereIn('fldencounterval', $finalEncounters);
            else
                $nameIds->where('fldencounterval', 'like', '%');


            if ($encid) $nameIds->where('fldencounterval', 'like', $encid);

            $nameIds->with(['departmentBed'])->limit(100);
            // //                $qbeds->orderBy('fldcurrlocat', 'ASC');
        } elseif ($searchdoor == 'Unallocated') {
            // //            $qbeds = Encounter::select('fldencounterval', 'fldcurrlocat')
            $nameIds->where('fldadmission', 'Admitted')
                ->where('fldcurrlocat', Null);

            /*if (count($indoorColorEncounters)) {
                $qbeds->whereIn('fldencounterval', $indoorColorEncounters);
            }*/

            if ($finalEncounters)
                $nameIds->whereIn('fldencounterval', $finalEncounters);
            else
                $nameIds->where('fldencounterval', 'like', '%');


            if ($encid) $nameIds->where('fldencounterval', 'like', $encid);

            $nameIds->with(['departmentBed']);
        } else {
            // echo "here"; exit;
            //            $nameIds->where('fldcurrlocat','LIKE', $searchdoor);
            $nameIds->whereHas('departmentBed', function ($query) use ($searchdoor) {
                $query->where('flddept', 'like', $searchdoor);
            });

            if ($finalEncounters)
                $nameIds->whereIn('fldencounterval', $finalEncounters);
            else
                $nameIds->where('fldencounterval', 'like', '%');
        }

        $beds = $nameIds->select('fldheight', 'flduserid', 'flddoa', 'fldencounterval', 'fldpatientval', 'fldregdate', 'fldfollowdate', 'fldadmission')->orderBy('fldencounterval', 'ASC')
            ->with('PatFindings', 'patientInfo', 'PatPlanning')
            ->get();

        $information = [];
        if ($beds) {
            $door = 0;
            foreach ($beds as $k => $b) {
                $information[$k]['dignosis'] = '';

                if ($door === $b->flddept)
                    $information[$k]['door'] = $b->flddept;
                else
                    $information[$k]['door'] = 0;

                $door = $b->flddept;


                $encounter_id = $b->fldencounterval;
                $encounter_detail = $beds->where('fldencounterval', $encounter_id)
                    ->first();

                $patient_info = $encounter_detail->patientInfo;

                /*$patient_info = PatientInfo::select('fldptcontact', 'fldpatientval', 'fldencrypt', 'fldptadddist', 'fldptaddvill', 'fldptnamefir', 'fldptnamelast', 'fldencrypt', 'fldptsex', 'fldptbirday')
                    ->where('fldpatientval', $patient_id)
                    ->first();*/

                $final_diagnosis = $b->PatFindings
                    ->where('fldtype', 'Final Diagnosis')
                    ->where('fldsave', '1')
                    ->all();

                $pro_diagnosis = $b->PatFindings
                    ->where('fldtype', 'Provisional Diagnosis')
                    ->where('fldsave', '1')
                    ->all();

                $information[$k]['fldadmission'] = $b->fldadmission;
                $information[$k]['encounter_id'] = $encounter_id;
                $information[$k]['patient_id'] = $patient_info->fldpatientval ?? "";
                $information[$k]['name'] = $patient_info->fullname ?? "";
                $information[$k]['agesex'] = $patient_info && $patient_info->fldptbirday && $patient_info->fldptsex ? $patient_info->fldagestyle . ' /' . $patient_info->fldptsex ?? "" : "";
                // $information[$k]['agesex'] = $patient_info && $patient_info->fldptbirday && $patient_info->fldptsex ? \Carbon\Carbon::parse($patient_info->fldptbirday ?? "")->age . ' yrs/' . $patient_info->fldptsex ?? "" : "";
                $information[$k]['birthday'] = $patient_info &&  $patient_info->fldptbirday ? \Carbon\Carbon::parse($patient_info->fldptbirday)->format('d/m/Y') : "";
                $information[$k]['sex'] = $patient_info->fldptsex ?? "";


                $information[$k]['encounter_id'] = $patient_info->fldptaddvill ?? "";
                $information[$k]['encounter_id'] = $patient_info->fldmunicipality ?? "";
                $information[$k]['encounter_id'] = $patient_info->fldptcontact ?? "";


                if ($final_diagnosis) {
                    foreach ($final_diagnosis as $fin) {
                        $information[$k]['dignosis'] .= '[' . $fin->fldcodeid . '] ' . $fin->fldcode . ',';
                    }
                }
                if ($pro_diagnosis) {
                    foreach ($pro_diagnosis as $pro) {
                        $information[$k]['dignosis'] .= '[' . $pro->fldcodeid . '] ' . $pro->fldcode . ',';
                    }
                }

                $information[$k]['consult'] = $encounter_detail->flduserid;

                $information[$k]['fldbed'] = $b->fldcurrlocat ?? $b->departmentBed->fldbed ?? 0;
                $information[$k]['flddept'] = $b->flddept;
                $information[$k]['progress'][] = $b->PatPlanning
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldplancategory', 'IP Monitoring')
                    ->all();
            }

            $data['encounters'] = $information;

            switch ($request->input('action')) {
                case 'Report':
                    return view('bedoccupancy::depart', $data)/*->setPaper('a4')->stream('bed_occupancy.pdf')*/;
                    break;


                case 'Progress':
                    return view('bedoccupancy::progress', $data)/*->setPaper('a4')->stream('bed_occupancy.pdf')*/;
                    break;

                case 'All':
                    return view('bedoccupancy::all', $data)/*->setPaper('a4')->stream('bed_occupancy.pdf')*/;
                    break;

                case 'Refresh':
                    $data['encounters'] = $information;
                    break;
            }
        }
        $data['departments'] = Department::where('fldcateg', 'Patient Ward')->orWhere('fldcateg', 'Emergency')->get();

        return view('bedoccupancy::bedoccupancy', $data);
    }

    public function setsessionbed(Request $request)
    {
        $type = $request->type;
        $seesion = $type . '_encounter_id';

        session([$seesion => $request->encounter_id]);
        //dd(Session::get('inpatient_encounter_id'));
        return response()->json(['status' => 'success']);
    }

    public function getBedOccupacyDetails(Request $request)
    {
        $fldbed = $request->fldbed;
        $encounterval = $request->encounterId;
        $encounter = Helpers::getPatientByEncounterId($encounterval);
        if ($encounter && $encounter->patientInfo && $encounter->PatFindings) {
            $patientInfo = $encounter->patientInfo;
            $patdiago = $encounter->PatFindings->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->all();
            $past_patdiagno = $encounter->PatFindings
                ->where('fldsave', 1)
                ->whereIn('fldtype', ['Provisional Diagnosis', 'Final Diagnosis'])
                ->all();
            // $consult = $encounter->PatFindings->first('fldconsultname');
            $consult = $encounter->consultant;
            $title = "Bed No.: " . $fldbed . "<br>Patient Id: " . $patientInfo->fldpatientval . "<br>Encounter Id: " . $encounterval . "<br>Date of Admission: "
                . $patientInfo->fldptadmindate . "<br>Gender: " . $patientInfo->fldptsex;
            if (isset($patientInfo->fldptcontact)) {
                $title .= "<br>Phone No.: " . $patientInfo->fldptcontact;
            }

            if (isset($consult->user)) {
                $title .= "<br>Consult.: " . $consult->user->getFldfullnameAttribute();
            }
            if (count($past_patdiagno) > 0) {
                foreach ($past_patdiagno as $past_patdiag) {
                    $title .= "<br>" . $past_patdiag->fldtype . ": " . $past_patdiag->fldcode;
                }
            }
            return response()->json([
                'success' => [
                    'status' => true,
                    'title' => $title,
                ]
            ]);
        }
        return response()->json([
            'success' => [
                'status' => false,
                'title' => "",
            ]
        ]);
    }
}
