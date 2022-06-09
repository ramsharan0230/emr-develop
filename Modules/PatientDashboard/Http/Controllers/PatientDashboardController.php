<?php

namespace Modules\PatientDashboard\Http\Controllers;

use App\Encounter;
use App\PatientExam;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PatientDashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('patientdashboard::index');
    }

    public function profile()
    {
        $userPatientVal = \Auth::guard('patient_admin')->user()->fldpatientval;
        $data['patientData'] = Encounter::where('fldpatientval', $userPatientVal)->with(['patientInfo'])->first();

        $heightWeight = PatientExam::where('fldencounterval', $data['patientData']->fldencounterval)
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
            $divide_bmi = ($hei * $hei);
            if ($divide_bmi > 0) {
                $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
            }
        }

        $data['exams'] = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $data['patientData']->fldencounterval)
            ->where(function ($query) {
                $query->orWhere('tblpatientexam.fldhead', 'Systolic BP')
                    ->orWhere('tblpatientexam.fldhead', 'Diastolic BP')
                    ->orWhere('tblpatientexam.fldhead', 'Pulse Rate')
                    ->orWhere('tblpatientexam.fldhead', 'Temperature (F)')
                    ->orWhere('tblpatientexam.fldhead', 'Respiratory Rate')
                    ->orWhere('tblpatientexam.fldhead', 'O2 Saturation')
                    ->orWhere('tblpatientexam.fldhead', 'GRBS');
            })
            ->orderBy('tblpatientexam.fldtime', 'desc')
            ->get();

        //dd($data['exams']);

        return view('patientdashboard::profile', $data);
    }
}
