<?php

namespace Modules\Patientregister\Http\Controllers;

use App\Encounter;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PatientregisterController extends Controller
{
    public function index()
    {
        return view('patientregister::patientregister');
    }

    public function get_encounter_number(Request $request)
    {
        $patient_id = $request->get('patient_id');
        if (ctype_alpha(str_replace(' ', '', $patient_id)) === false) {
            //'Name must contain letters and spaces only';
            $encounters = Encounter::select('fldencounterval')->where('fldpatientval', $patient_id)->orderBy('fldregdate', 'DESC')->get()->toArray();


            $html = '<select name="encounter_id" class="form-control">';
            if (!empty($encounters)) {
                foreach ($encounters as $en) {
                    $html .= '<option value="' . $en['fldencounterval'] . '"> ' . $en['fldencounterval'] . '</option>';
                }
            }
            $html .= '</select>';
        } else {
            $patientname = $patient_id . '%';
            $encounters = DB::table('tblencounter')
                ->join('tblpatientinfo', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->where('tblpatientinfo.fldptnamefir', 'LIKE', $patientname)
                ->orwhere('tblpatientinfo.fldptnamelast', 'LIKE', $patientname)
                ->select('tblencounter.fldencounterval','tblencounter.fldrank', 'tblpatientinfo.*')
                ->orderBy('fldregdate', 'DESC')->get()->toArray();
            // dd($encounters);


            $html = '<select name="encounter_id" class="form-control">';
            if (!empty($encounters)) {
                foreach ($encounters as $en) {
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($en) && isset($en->fldrank)) ? $en->fldrank : '';
                    $html .= '<option value="' . $en->fldencounterval . '"> ' . $user_rank . ' ' . $en->fldptnamefir . ' ' . $en->fldmidname . ' ' . $en->fldptnamelast . ' (' . $en->fldencounterval . ')</option>';
                }
            }
            $html .= '</select>';
        }


        return response()->json([
            'success' => [
                'options' => $html,
            ]
        ]);
    }

    public function getDetails(Request  $request)
    {
        if(!$request->get('encounter_id') || $request->get('encounter_id')== '' || $request->get('encounter_id')==null)
        {
            return redirect()->back()->with('error_message', 'something went wrong');
        }

        $data = [];
        $data['encounterDetails'] =  Encounter::with('patientInfo')->where('fldencounterval','=',$request->get('encounter_id'))->first();

        return  view('patientregister::patientregister', $data);

    }



}
