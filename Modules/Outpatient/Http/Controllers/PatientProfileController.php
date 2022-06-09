<?php


namespace Modules\Outpatient\Http\Controllers;



use App\Encounter;

use App\PatientExam;
use App\PatientInfo;
use App\Services\ImageUpload\Strategy\UploadWithAspectRatio;
use App\Test;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Outpatient\Services\OutpatientImageUploader;
use Session;

/**
 * Class OutpatientController
 * @package Modules\Outpatient\Http\Controllers
 */
class PatientProfileController extends Controller
{


    public function save_height(Request $request)
    {
        $height       = round($request->get('height'));
        $encounter_id = $request->get('encounter_id');


        $data = array(
            'fldencounterval' => $encounter_id,
            'fldrepquali'     => $height,
            'fldinput'        => 'OPD Examination',
            'fldtype'         => 'Quantitative',
            'fldhead'         => 'Birth Height',
            'fldmethod'       => 'Regular',
            'flduserid'       => 'admin',
            'fldtime'         => now(),
            'fldcomp'         => Helpers::getCompName(),
            'fldsave'         => 1,
            'fldsysconst'     => 'body_height',
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

        );

        $patient = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->first();

        if ($patient)
            PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->update($data);
        else {
            $patient_n = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 0)->where('fldsysconst', 'body_height')->first();
            if ($patient_n)
                PatientExam::where('fldencounterval', $encounter_id)->where('fldsysconst', 'body_height')->update($data);
            else
                PatientExam::insert($data);
        }

        $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'Body_Weight')->orderBy('fldid', 'desc')->first();
        $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

        $bmi = '';

        if (isset($body_height) && isset($body_weight)) {
            $hei        = ($body_height->fldrepquali / 100); //changing in meter
            $divide_bmi = ($hei * $hei);
            if ($divide_bmi > 0) {

                $bmi = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
            }
        }

        return response()->json([
            'success' => [
                'options' => $height,

                'bmi' => ($bmi),
            ]
        ]);
    }



    public function getAgeurl(Request $request)
    {
        //$patient->
        $age          = \Carbon\Carbon::parse($request->date)->age;
        $encounter_id = $request->encounter_id;
        // echo $request->date; exit;
        $data = array(
            'fldptbirday' => $request->date,
            'flduptime'   => now(), //'2020-02-23 11:13:27.709'

        );

        $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        $patient_id = $enpatient->fldpatientval;


        PatientInfo::where('fldpatientval', $patient_id)->update($data);

        return response()->json([
            'success' => [
                'age' => $age,
            ]
        ]);
    }


    public function save_weight(Request $request)
    {


        $weight       = round($request->get('weight'));
        $encounter_id = $request->get('encounter_id');


        $data    = array(
            'fldencounterval' => $encounter_id,
            'fldrepquali'     => $weight,
            'fldinput'        => 'OPD Examination',
            'fldtype'         => 'Quantitative',
            'fldhead'         => 'Birth Weight',
            'fldmethod'       => 'Regular',
            'flduserid'       => 'admin',
            'fldtime'         => now(),
            'fldcomp'         => Helpers::getCompName(),
            'fldsave'         => 1,
            'fldsysconst'     => 'body_weight',
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        );
        $patient = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->first();

        if ($patient)
            PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->update($data);
        else {
            $patient_n = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 0)->where('fldsysconst', 'body_weight')->first();
            if ($patient_n)
                PatientExam::where('fldencounterval', $encounter_id)->where('fldsysconst', 'body_weight')->update($data);
            else
                PatientExam::insert($data);
        }


        $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'Body_Weight')->orderBy('fldid', 'desc')->first();
        $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

        $bmi = '';

        if (isset($body_height) && isset($body_weight)) {
            $hei        = ($body_height->fldrepquali / 100); //changing in meter
            $divide_bmi = ($hei * $hei);
            if ($divide_bmi > 0) {

                $bmi = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
            }
        }

        return response()->json([
            'success' => [
                'options' => $weight,

                'bmi' => ($bmi),
            ]
        ]);
    }



    public function changebirthday(Request $request)
    {
        $end = Carbon::parse($request->get('birthday'));
        $now = Carbon::now();

        $length = $end->diffInDays($now);

        if ($length < 1) {

            $data['years'] = 'Hours';
            $data['hours'] = $end->diffInHours($now);
        }


        if ($length > 0 && $length <= 30)
            $years = 'Days';

        if ($length > 30 && $length <= 365)
            $years = 'Months';

        if ($length > 365)
            $years = 'Years';

        return response()->json([
            'success' => [
                'options' => $years,
            ]
        ]);
    }


    public function get_encounter_number(Request $request)
    {
        $patient_id = $request->get('patient_id');
        $encounters = Encounter::select('fldencounterval')->where('fldpatientval', $patient_id)->orderBy('fldregdate', 'DESC')->get()->toArray();


        $html = '<select name="encounter_id" class="form-control">';
        if (!empty($encounters)) {
            foreach ($encounters as $en) {
                $html .= '<option value="' . $en['fldencounterval'] . '"> ' . $en['fldencounterval'] . '</option>';
            }
        }
        $html .= '</select>';


        return response()->json([
            'success' => [
                'options' => $html,
            ]
        ]);
    }


    function update_abnormal(Request $request)
    {

        //dd($request);
        $data = array(
            'fldabnormal' => $request->status,
            'updated_at'  => config('constants.current_date_time')

        );
        PatientExam::where('fldid', $request->fldid)->update($data);
        Session::flash('display_popup_error_success', true);

        Session::flash('success_message', 'Finding update Successfully.');

        return redirect()->route('patient');
    }





    public function getPhotographForm(Request $request)
    {
        // echo $request->encounterId; exit;
        $request->validate([
            'encounterId' => 'required',
        ]);
        // $encounter_id        = $request->encounterId;
        // $data['encounterId'] = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter']   = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        $html = view('outpatient::dynamic-views.photograph-form', $data)->render();
        return $html;
    }


    public function savePhotographss(Request $request)
    {


        try {
            $mytime = Carbon::now();
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $uploader = new OutpatientImageUploader(new UploadWithAspectRatio());

                $data['image'] = $uploader->saveOriginalImage($image);
                // echo $data

            }

            session()->flash('success_message', __('Allergy Drug Added Successfully.'));

            return redirect()->route('patient');
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));

            return redirect()->route('patient');
        }
    }


    public function savePhotograph(Request $request)
    {
        // echo "here"; exit;
        $image = $request->image;
        if (isset($image)) {
            $data          = $request->image;
            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $data          = base64_decode($image_array_2[1]);

            $path      = asset('uploads/outpatient/full/');
            $imageName = time() . '.png';
            File::put(public_path('uploads/outpatient/full/' . $imageName), $data);

            echo '<img src="' . $path . '/' . $imageName . '" class="img-thumbnail" />';
        }
    }


}
