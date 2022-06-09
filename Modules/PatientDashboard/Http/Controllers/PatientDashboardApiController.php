<?php

namespace Modules\PatientDashboard\Http\Controllers;

use App\Encounter;
use App\PatientCredential;
use App\PatientExam;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientDashboardApiController extends Controller
{
    use Authenticatable;
    public function __construct(){
        // Unique Token
        $this->apiToken = uniqid(base64_encode(str_random(60)));
    }
    public function test(){
        return \response()->json('Hello I am working',200);
    }

    public function login(Request $request)
    {
        if(!$request->username || !$request->password){
            return  \response()->json(['message' => 'Please enter username and password'],200);
        }
        $pwd = $request->get('password');
        $generated_pwd = "";
        for ($i = 0; $i < strlen($pwd); $i++) {
            $current_string = substr($pwd, $i, 1);
            $temp_ascii = ord($current_string);
            if (strlen($temp_ascii) == 1) {
                $temp_ascii = "00" . $temp_ascii;
            } elseif (strlen($temp_ascii) == 2) {
                $temp_ascii = "0" . $temp_ascii;
            }
            $generated_pwd .= $temp_ascii;
        }
        // dd($generated_pwd);
        $user = PatientCredential::where('fldusername', $request->get('username'))
            ->first();

        /**USER DOES NOT EXITS*/
        if (!$user) {
            return \response()->json([
                'message' =>"User doesn't exist in our database.",
                'status' =>false,
            ],401);
        }

        /**CHECK USER PASSWORD*/
        if (!($generated_pwd == $user->fldpassword)) {
            return \response()->json([
                'message' =>"Invalid username or password!.",
                'status' =>false,
            ],401);
        }

        if ($user) {
            // \Auth::guard('patient_admin')->login($user);
            // setting the cookies for remember me
            if ($request->get('remember-me') == "yes") {
                //setting cookie for a year
                setcookie("patient_admin_rem_username", $request->get('username'), time() + 31556926, '/');
                try {
                    setcookie("patient_admin_rem_password", Crypt::encrypt($request->get('password')), time() + 31556926, '/');
                } catch (\Exception $e) {

                }
            } else {
                unset($_COOKIE['patient_admin_rem_username']);
                unset($_COOKIE['patient_admin_rem_password']);
                setcookie('patient_admin_rem_username', null, -1, '/');
                setcookie('patient_admin_rem_password', null, -1, '/');
            }
            $api_token = ['api_token' => $this->apiToken];
            PatientCredential::where('fldusername', $request->get('username'))->update($api_token);
            return \response()->json(['message' => 'Login Successful','access_token' => $this->apiToken,'status' => true], 200);
        } else {
            return \response()->json([
                'message' =>"Invalid username or password!.",
                'status' =>false,
            ],401);
        }

    }

    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'username' => 'required|exists:tblpatientcredential,fldusername|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'username.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules, $messages);

    }

    public function profile(Request $request){
        $token = $request->header('Authorization');
        $userPatientVal = PatientCredential::where('api_token',$token)->first();
//            \Auth::guard('patient_admin')->user()->fldpatientval;
        $patientData = Encounter::where('fldpatientval', $userPatientVal->fldpatientval)->with(['patientInfo'])->first();

        $heightWeight = PatientExam::where('fldencounterval', $patientData->fldencounterval)
            ->where('fldsave', 1)
            ->where(function ($queryNested) {
                $queryNested->orWhere('fldsysconst', 'body_Weight')
                    ->orWhere('fldsysconst', 'body_height');
            })
            ->orderBy('fldid', 'desc')
            ->get();

        if ($heightWeight) {
            $body_weight = $body_weight = $heightWeight->where('fldsysconst', 'body_weight')->first();
            $body_height = $body_height = $heightWeight->where('fldsysconst', 'body_height')->first();
        } else {
            $body_weight = "";
            $body_height = "";
        }

        if (isset($body_height)) {
            if ($body_height->fldrepquali <= 100) {
                $heightrate = 'cm';
                $dataheight = $body_height->fldrepquali;
            } else {
                $heightrate = 'm';
                $height = $body_height->fldrepquali / 100;
            }
        } else {
            $heightrate= 'cm';
            $height = '';
        }


        $bmi = '';

        if (isset($body_height) && isset($body_weight)) {
            $hei = ($body_height->fldrepquali / 100); //changing in meter
            $divide_bmi = ($hei * $hei);
            if ($divide_bmi > 0) {
                $bmi = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
            }
        }

        $exams = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $patientData->fldencounterval)
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
        $patient_information = [
            'image_url' =>'',
            'patient_val' =>$patientData->patientInfo ? $patientData->patientInfo->fldpatientval : '',
            'encounter_val' =>$patientData->fldencounterval ?? '',
            'height'=>$height,
            'weight' =>$body_weight,
            'bmi' =>$bmi,
            'consultant' =>$patientData->patientInfo ? $patientData->patientInfo->flduserid :'',
            'DoReg'=>$patientData->patientInfo ? $patientData->patientInfo->fldregdate :'',
            'location'=>$patientData->patientInfo ? $patientData->patientInfo->fldptaddvill .', '.  $patientData->patientInfo->fldptadddist :'',
            'status'=>$patientData->patientInfo ? $patientData->patientInfo->fldadmission :'',
            'billing'=>$patientData->patientInfo ? $patientData->patientInfo->fldbillingmode :'',
            'patient_inside' =>'',
            'vitals'=>[
                'pulse_rate' =>$exams->where('fldhead', 'Pulse Rate')->first()->fldrepquanti ?? '',
                'syst_bp'=>$exams->where('fldhead', 'Systolic BP')->first()->fldrepquanti??'',
                'diast_bp'=>$exams->where('fldhead', 'Diastolic BP')->first()->fldrepquanti??'',
                'resp_bp'=>$exams->where('fldhead', 'Respiratory Rate')->first()->fldrepquanti??'',
                'spo2'=>$exams->where('fldhead', 'O2 Saturation')->first()->fldrepquanti??'',
                'temp'=>$exams->where('fldhead', 'Temperature (F)')->first()->fldrepquanti??'',
            ],
            'basic_information'=>[
                'full_name' => $patientData->patientInfo ? $patientData->patientInfo->fullname :'',
                'gender' =>$patientData->patientInfo ? $patientData->patientInfo->fldptsex :'',
                'age' =>$patientData->patientInfo ? $patientData->patientInfo->fldagestyle :'',
                'nationality' =>$patientData->patientInfo ? $patientData->patientInfo->fldcountry :'',
                'blood_group' =>$patientData->patientInfo ? $patientData->patientInfo->fldbloodgroup :'',
                'mobile' =>$patientData->patientInfo ? $patientData->patientInfo->fldptcontact :'',
                'location' =>'',
            ],
        ];

        return \response()->json($patient_information);
    }

    public function labReportAndHistory(Request $request){
        $token = $request->header('Authorization');
        $userPatientVal = PatientCredential::where('api_token',$token)->first();
        $record=DB::table('tblpatientcredential as pc')
            ->select(
                'pt.fldtestid', 
                'pt.fldmethod', 
                'pt.fldsampleid', 
                'pt.fldsampletype', 
                'pt.fldreportquali', 
                'pt.fldreportquanti', 
                'pt.flduserid_sample', 
                'pt.fldtime_sample', 
                'pt.flduserid_start', 
                'pt.fldtime_start', 
                'pt.flduserid_report', 
                'pt.fldtime_report', 
                'pt.fldcomp_report', 
                'pt.fldsave_report', 
                'pt.flduptime_report', 
                'pt.flduserid_verify', 
                'pt.fldtime_verify', 
                'pt.fldcomp_verify', 
                'pt.fldsave_verify', 
                'pt.flduptime_verify' 
            )
            ->join('tblencounter as ec', 'pc.fldpatientval', '=', 'ec.fldpatientval')
            ->join('tblpatlabtest as pt', 'ec.fldencounterval', '=', 'pt.fldencounterval')
            ->where('pc.fldpatientval', $userPatientVal->fldpatientval)
            ->get();
            return \response()->json(['status' => true,'message' => 'success','data' => $record], 200);
    }

    /**
     * Logout
     */
    public function patientLogout(Request $request){
        $token = $request->header('Authorization');
        $user = PatientCredential::where('api_token',$token)->first();
        if($user) {
        $api_token = ['api_token' => null];
        $logout = PatientCredential::where('id',$user->id)->update($api_token);
        if($logout) {
            return response()->json([
            'message' => 'User Logged Out',
            ]);
        }
        } else {
        return response()->json([
            'message' => 'User not found',
        ]);
        }
    }

}
