<?php

namespace Modules\Physiotherapy\Http\Controllers;

use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\NurseDosing;
use App\Pathdosing;
use App\PatientExam;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Session;
use Auth;

class PhysiotherapyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        $encounter_id_session = Session::get('physiotherapy_encounter_id');

        //            for diagnosis
        $digno_group = DiagnoGroup::select('fldgroupname')->distinct()->get();
        $data['digno_group'] = $digno_group;

        $diagnocat = $this->getInitialDiagnosisCategory();
//        dd($diagnocat);
        $data['digno_group_list'] = $diagnocat;

        $data['enable_freetext'] = Options::get('free_text');

        if ($request->has('encounter_id') || $encounter_id_session) {

            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['physiotherapy_encounter_id' => $encounter_id]);

            /*create last encounter id*/
            Helpers::encounterQueue($encounter_id);
            $encounterIds = Options::get('physiotherapy_last_encounter_id');

            $arrayEncounter = unserialize($encounterIds);
            /*create last encounter id*/
            $dataflag = array(
                'fldinside' => 1,
            );

            Encounter::where('fldencounterval', $encounter_id)->update($dataflag);
            $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->with('patientInfo')->first();

            $data['patient'] = $patient = $enpatient->patientInfo;
            $patient_id = $enpatient->fldpatientval;
            $data['patient_id'] = $enpatient->fldpatientval;

            $data['systolic_bp'] = $systolic_bp = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();


            $data['diasioli_bp'] = $diasioli_bp = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $data['pulse'] = $pulse = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse Rate')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $data['temperature'] = $temperature = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Temperature (F)')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $data['respiratory_rate'] = $respiratory_rate = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Respiratory Rate')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();


            $data['o2_saturation'] = $o2_saturation = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'O2 Saturation')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            if ($patient) {
                $end = Carbon::parse($patient->fldptbirday);
                $now = Carbon::now();


                $length = $end->diffInDays($now);

                if ($length < 1) {

                    $data['years'] = 'Hours';
                    $data['hours'] = $end->diffInHours($now);
                }


                if ($length > 0 && $length <= 30)
                    $data['years'] = 'Days';

                if ($length > 30 && $length <= 365)
                    $data['years'] = 'Months';

                if ($length > 365)
                    $data['years'] = 'Years';
            }

            $heightWeight = PatientExam::where('fldencounterval', $encounter_id)
                ->where('fldsave', 1)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldsysconst', 'body_Weight')
                        ->orWhere('fldsysconst', 'body_height');
                })
                ->orderBy('fldid', 'desc')
                ->get();

            if ($heightWeight) {
                $data['body_weight'] = $body_weight = $heightWeight->where('fldsysconst', 'body_weight')->first();
                $data['body_height'] = $body_height = $heightWeight->where('fldsysconst', 'body_height')->first();
            } else {
                $data['body_weight'] = "";
                // dd($body_weight);
                $data['body_height'] = "";
            }


            if (isset($body_height)) {
                if ($body_height->fldrepquali <= 100) {
                    $data['heightrate'] = 'cm';
                    $data['height'] = $body_height->fldrepquali;
                } else {
                    $data['heightrate'] = 'm';
                    $data['height'] = $body_height->fldrepquali / 100;
                }
            } else {
                $data['heightrate'] = 'cm';
                $data['height'] = '';
            }


            $data['bmi'] = '';

            if (isset($body_height) && isset($body_weight)) {
                $hei = ($body_height->fldrepquali / 100); //changing in meter
                $divide_bmi = $hei * $hei;

                if ($divide_bmi > 0) {
                    $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                    //                            dd($body_height);
                }
            }

            $data['fluid_list'] = Pathdosing::where([
                ['fldencounterval', $encounter_id],
                ['fldroute', 'fluid'],
                ['fldlevel', 'Dispensed']
            ])
                ->Where([
                    ['fldcurval', '!=', 'DisContinue'],
                    ['fldcurval', '!=', 'Cancelled']
                ])->get();

            $data['fluid_particulars'] = NurseDosing::with('getName')->where([
                ['fldencounterval', $encounter_id],
                ['fldunit', 'ML/Hour'],
            ])->get();


            /*$presentation = ExamGeneral::select('fldreportquanti')->where([
                ['fldencounterval', $encounter_id],
                ['fldinput', 'Obstetrics'],
                ['flditem', 'Presentaion'],
                ['fldtype', 'Qualitative']
            ])->get();*/
            // dd($presentation);
            $patExamMultiple = PatientExam::where('fldencounterval', $encounter_id)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldhead', 'Pallor')
                        ->orWhere('fldhead', 'Icterus')
                        ->orWhere('fldhead', 'Cyanosis')
                        ->orWhere('fldhead', 'Clubbing')
                        ->orWhere('fldhead', 'Oedema')
                        ->orWhere('fldhead', 'Dehydration');
                })
                ->orderBy('fldid', 'DESC')
                ->get();
            $data['Pallor'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Pallor')->first();
            $data['Icterus'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Icterus')->first();
            $data['Cyanosis'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Cyanosis')->first();
            $data['Clubbing'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Clubbing')->first();
            $data['Oedema'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Oedema')->first();
            $data['Dehydration'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Dehydration')->first();

            $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();
            $tab = $request->get('tab');
            $data['tab'] = (isset($tab)) ? $tab : '';

            $data['systolic_bp_range'] = DB::table('tblexamlimit')
                ->select('fldhigh','fldlow')
                ->where('fldexamid', 'Systolic BP')
                ->first();


            $data['diasioli_bp_range'] =  DB::table('tblexamlimit')
                ->select('fldhigh','fldlow')
                ->where('fldexamid',  'Diastolic BP')
                ->first();

            $data['pulse_range'] =   DB::table('tblexamlimit')

                ->select('fldhigh','fldlow')
                ->where('fldexamid', 'Pulse Rate')
                ->first();

            $data['temperature_range'] =  DB::table('tblexamlimit')
                ->select('fldhigh','fldlow')
                ->where('fldexamid', 'Temperature (F)')
                ->first();

            $data['respiratory_rate_range'] =  DB::table('tblexamlimit')
                ->select('fldhigh','fldlow')
                ->where('fldexamid', 'Respiratory Rate')
                ->first();

            $data['o2_saturation_range'] =  DB::table('tblexamlimit')
                ->select('fldhigh','fldlow')
                ->where('fldexamid', 'O2 Saturation')
                ->first();

            $data['grbs_range'] =  DB::table('tblexamlimit')
                ->select('fldhigh','fldlow')
                ->where('fldexamid', 'GRBS')
                ->first();



//            $data['digno_group_list'] = array();


            return view('physiotherapy::physiotherapy', $data);
        }
        return view('physiotherapy::physiotherapy', $data);
    }

    public function resetEncounter() {
        Session::forget('physiotherapy_encounter_id');
        return redirect()->route('physiotherapy');
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getInitialDiagnosisCategory()
    {
        try {
            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $data = [];
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
                    $data[] = [
                        'code' => trim($csvLine[1]),
                        'name' => trim($csvLine[3]),
                    ];
                }
            }
            //sort($data);
            usort($data, function ($a, $b) {
                return $a['name'] <=> $b['name'];
            });
            // dd($data);
            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }


}
