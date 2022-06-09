<?php

namespace Modules\Dietitian\Http\Controllers;

use App\Departmentbed;
use App\Department;
use App\Encounter;
use App\PatFindings;
use App\PatientInfo;
use App\PatPlanning;
use App\StructExam;
use App\ExamGeneral;
use App\ExtraDosing;
use App\FoodType;
use App\Utils\Helpers;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class DietitianController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['encounters'] = [];
        $data['departments'] = Department::where('fldcateg', 'Patient Ward')->orWhere('fldcateg','Emergency')->get();
        return view('dietitian::index', $data);
    }

   public function searchBed(Request $request)
    {
        // dd($request->all());
        $data['requestData'] = $request->all();
        $encname = $request->encounter_name;
        $nameIds = Encounter::query();
        if ($encname != ""){
            $nameIds = Encounter::whereHas('patientInfo', function ($query) use ($encname) {
                $query->where('fldptnamefir', 'like', $encname . '%')
                    ->orwhere('fldptnamelast', 'like', $encname . '%');
            });
        }

//            ->pluck('fldencounterval');
        if(isset($request->door) and $request->door !=''){
          $searchdoor = $request->door;
        }else{
            $searchdoor = '%';
        }

        // $searchdoor = (isset($request->door) && $request->door !='') ? $request->door : '';
        $color = $request->color;
        $encid = $request->encounter_id;

        //color of the bed
        if ($color != "") {
            $finalEncounters = $nameIds->where('fldheight', 'LIKE', $color)->limit(100)->pluck('fldencounterval');
        } else {
            $finalEncounters = [];
//            $finalEncounters = $nameIds->limit(100)->pluck('fldencounterval');
        }
        // echo $searchdoor; exit;

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

            $nameIds->with(['patientInfo', 'departmentBed'])->limit(100);
//                $qbeds->orderBy('fldcurrlocat', 'ASC');
        } elseif ($searchdoor == 'Unallocated') {
//            $qbeds = Encounter::select('fldencounterval', 'fldcurrlocat')
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

            $nameIds->with(['patientInfo', 'departmentBed']);
        } else {

            $nameIds->where('fldcurrlocat', $searchdoor);

            if ($finalEncounters)
                $nameIds->whereIn('fldencounterval', $finalEncounters);
            else
                $nameIds->where('fldencounterval', 'like', '%');
            //
        }

        $beds = $nameIds->select('fldheight', 'flduserid', 'flddoa', 'fldencounterval', 'fldpatientval', 'fldregdate', 'fldfollowdate')->orderBy('fldencounterval', 'ASC')
            ->with('PatFindings')
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

                $information[$k]['encounter_id'] = $encounter_id;
                $information[$k]['patient_id'] = $patient_info->fldpatientval??"";
                $information[$k]['name'] = $patient_info->fullname??"";
                $information[$k]['agesex'] = $patient_info && $patient_info->fldptbirday && $patient_info->fldptsex? $patient_info->fldagestyle . ' /' . $patient_info->fldptsex ?? "":"";
                // $information[$k]['agesex'] = $patient_info && $patient_info->fldptbirday && $patient_info->fldptsex? \Carbon\Carbon::parse($patient_info->fldptbirday ?? "")->age . ' yrs/' . $patient_info->fldptsex ?? "":"";
                $information[$k]['birthday'] = $patient_info &&  $patient_info->fldptbirday?\Carbon\Carbon::parse($patient_info->fldptbirday)->format('d/m/Y'):"";
                $information[$k]['sex'] = $patient_info->fldptsex ?? "";

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
                $information[$k]['progress'][] = PatPlanning::select('fldtime', 'fldproblem', 'fldplan', 'flduserid')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldplancategory', 'IP Monitoring')
                    ->get();
                $information[$k]['reasonofadmission'] = ExamGeneral::select('flddetail')->where('fldinput','LIKE','History')->where('fldtype','LIKE','Qualitative')->where('flditem','LIKE','Cause of Admission')->where('fldencounterval',$encounter_id)->first();

                $information[$k]['dietitian_followup_date'] = ExtraDosing::select('flddietitianfollowupdate')->where('fldencounterval',$encounter_id)->first();
            }
            // dd($information);

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
        $data['departments'] = Department::where('fldcateg', 'Patient Ward')->orWhere('fldcateg','Emergency')->get();
        return view('dietitian::index', $data);
    }

    public
    function setdietitiansessionbed(Request $request)
    {
        $type = $request->type;
        $seesion = $type . '_encounter_id';

        session([$seesion => $request->encounter_id]);
        //dd(Session::get('inpatient_encounter_id'));
        return response()->json(['status' => 'success']);
    }

    public function addDailyDietPlan(Request $request)
    {
        // dd($request->all());
        try {
            $encounter_id = $request->get('encounter');
            // echo $encounter_id; exit;
            $time         = date('Y-m-d H:i:s');
            $userid       = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer     = \App\Utils\Helpers::getCompName();

            $req_time    = $request->get('time', date('H:i:s'));
            $type        = $request->get('type');
            $particulars = $request->get('item');
            $dose        = $request->get('dose');
            $frequency        = $request->get('frequency');
            $dosetime    = $request->get('date', date('Y-m-d')) . " " . $req_time;
            $status      = $request->get('status', 'Planned');
            $feedingroute = $request->get('feedingroute');
            $fluidrestriction = $request->get('fluidrestriction');
            $therapeuticneed = $request->get('therapeuticneed');
            $energy = $request->get('energy');
            $fluid  = $request->get('fluid');
            $otherrecommendation = $request->get('otherrecommendation');
            $recommendeddiet = $request->get('recommendeddiet');
            $foodsupplement = $request->get('foodsupplement');
            $anyrestriction = $request->get('anyrestriction');
            $extradiet = $request->get('extradiet');
            $prescribeddiet = $request->get('prescribeddiet');
            $fldid = ExtraDosing::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldcategory'     => $type,
                'flditem'         => $particulars,
                'flddose'         => $dose,
                'fldfreq'         => $frequency,
                'fldtype'         => 'Input Food/Fluid',
                'flddosetime'     => $dosetime,
                'flddosecode'     => NULL,
                'fldstatus'       => $status,
                'flduserid'       => $userid,
                'fldtime'         => $time,
                'fldcomp'         => $computer,
                'fldsave'         => '0',
                'xyz'             => '0',
                'fldfeedingroute' => $feedingroute,
                'fldfluidrestriction' => $fluidrestriction,
                'fldtherapeuticneed' => $therapeuticneed,
                'fldotherrecommendation' => $otherrecommendation,
                'fldrecommendeddiet' => $recommendeddiet,
                'fldprescribeddiet' => $prescribeddiet,
                'fldfoodsupplement' => $foodsupplement,
                'fldanyrestriction' => $anyrestriction,
                'fldextradiet' => $extradiet,
                'fldfluid' => $fluid,
                'fldenergy' => $energy,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            if ($status == 'Completed') {
                ExamGeneral::insert([
                    'fldencounterval' => $encounter_id,
                    'fldinput'        => 'Input Food/Fluid',
                    'fldtype'         => 'Qualitative',
                    'flditem'         => $particulars,
                    'fldreportquali'  => $type,
                    'fldreportquanti' => $dose,
                    'flddetail'       => NULL,
                    'flduserid'       => $userid,
                    'fldtime'         => $time,
                    'fldcomp'         => $computer,
                    'fldsave'         => '1',
                    'flduptime'       => NULL,
                    'xyz'             => '0',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);
            }

            return response()->json([
                'status'  => TRUE,
                'data'    => [
                    'feedingroute'=> $feedingroute,
                    'therapeuticneed' => $therapeuticneed,
                    'fluidrestriction' => $fluidrestriction,
                    'energy' => $energy,
                    'fluid' => $fluid,
                    'fldid'       => $fldid,
                    'type'        => $type,
                    'particulars' => $particulars,
                    'dose'        => $dose,
                    'time'        => $req_time,
                    'flddosetime' => $dosetime,
                    'status'      => $status,
                ],
                'message' => 'Successfully saved planned diet.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => FALSE,
                'message' => 'Failed to save planned diet.',
            ]);
        }
    }

    public function deleteDiet(Request $request)
    {
        try {
            $status = $request->get('status');
            $fldid  = $request->get('fldid');

            if ($status === 'Planned') {
                ExtraDosing::where([
                        'fldid' => $fldid,
                    ])
                    ->delete();
            } else {
                ExtraDosing::where([
                        'fldid' => $fldid,
                    ])->update([
                        'fldstatus' => 'Discontinue',
                    ]);
            }
            return response()->json([
                'status'  => TRUE,
                'message' => 'Successfully deleted planned diet.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => False,
                'message' => 'Failed to delete planned diet.'
            ]);
        }
    }

    public function saveDailyDietPlan(Request $request)
    {
        try {
            $userid   = \Auth::guard('admin_frontend')->user()->flduserid;
            $time     = date('Y-m-d H:i:s');
            $computer = \App\Utils\Helpers::getCompName();

            ExtraDosing::whereIn('fldid', $request->get('fldids'))
                        ->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                        ->update([
                            'fldstatus' => 'Continue',
                            'flduserid' => $userid,
                            'fldtime'   => $time,
                            'fldcomp'   => $computer,
                            'fldsave'   => '1',
                            'xyz'       => '0'
                        ]);
            return response()->json([
                'status'  => TRUE,
                'message' => 'Saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => FALSE,
                'message' => 'Failed to save ',
            ]);
        }
    }

    public function getTypeData(Request $request)
    {
        $types = \App\FoodType::select('fldfoodtype')
            ->distinct()
            ->get();

        return response()->json($types);
    }

    /**
     * Planned Functions
     */

    public function getDiets(Request $request)
    {
        $encounter_id = $request->get('encounter');
        $date         = $request->get('date');
        $status       = $request->get('status');
        if (!$date)
            $date = date('Y-m-d');

        $plannedDiets = ExtraDosing::select('tblextradosing.fldid', 'tblextradosing.fldcategory AS type','tblextradosing.fldfeedingroute AS feedingroute','tblextradosing.fldfluidrestriction AS fluidrestriction','tblextradosing.fldtherapeuticneed AS therapeuticneed', 'tblextradosing.flditem AS particulars', 'tblextradosing.flddose AS dose', 'tblextradosing.flddosetime', 'tblextradosing.fldstatus AS status', 'fc.fldfluid', 'fc.fldenergy')
            ->join('tblfoodcontent AS fc', 'fc.fldfoodid', '=', 'tblextradosing.flditem')
            ->where([
                'tblextradosing.fldencounterval' => $encounter_id,
                'tblextradosing.fldstatus'       => $status,
                'tblextradosing.fldsave'         => ($status == 'Continue'),
            ]);

        if ($date) {
            $plannedDiets->where([
                ["tblextradosing.fldtime", ">=", "$date 00:00:00"],
                ["tblextradosing.fldtime", "<=", "$date 23:59:59.999"],
            ]);
        }

        $flddosecode = $request->get('flddosecode');
        if ($flddosecode && $flddosecode == 'set_null')
            $plannedDiets->where('tblextradosing.flddosecode', 'like', '%');
        else
            $plannedDiets->whereNull('tblextradosing.flddosecode');

        $plannedDiets = $plannedDiets->get();

        foreach ($plannedDiets as &$diet) {
            $diet->time = explode(' ', $diet->flddosetime)[1];
            $diet->time = substr($diet->time, 0, -3);
        }
        // dd($plannedDiets);
        return response()->json($plannedDiets);
    }

    public function getTypeItems(Request $request)
    {
        $typeItems = \App\FoodContent::select('fldfoodid', 'fldfluid', 'fldenergy')
            ->where([
                'fldfoodtype' => $request->get('type'),
                'fldfoodcode' => 'Active',
            ])->get();

        return response()->json($typeItems);
    }

    public function updateExtraDosing(Request $request)
    {
        try{
            $field = $request->get('field');
            ExtraDosing::where([
                'fldid' => $request->get('fldid')
            ])->update([
                $field => $request->get('value'),
            ]);

            return response()->json([
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Information']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function saveDietitianFollowupDate(Request $request){
        try{
            // dd($request);
            $encounter = $request->encounter;
            $dietrow = ExtraDosing::where('fldencounterval',$encounter)->get();
            // dd($dietrow);
            if(isset($dietrow) and count($dietrow) > 0){
                foreach($dietrow as $rowdata){

                    $data['flddietitianfollowupdate'] = $request->date;
                    ExtraDosing::where([['fldid', $rowdata->fldid]])->update($data);
                }
            }
            return response()->json([
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Information']),
            ]);
        }catch(\Exception $e){
            dd($e);
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }
}
