<?php

namespace Modules\Reports\Http\Controllers;

use App\Encounter;
use App\Municipal;
use App\PatientInfo;
use App\Districts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayPatientProfile(Request $request)
    {
        // $patient= array();

        if (isset($request->type) && $request->type == 'P') {
            $patientID = $request->encounterId;
            $patient = PatientInfo::where('fldpatientval', $patientID)->first();
            $encounter = Encounter::select('fldencounterval')
                ->where('fldpatientval', $patientID)
                ->first();
            // echo $encounter->fldpatientval; exit;
            if (isset($patient->fldptbirday) and $patient->fldptbirday != '') {
                $dto = explode(' ', $patient->fldptbirday);
                $dob = explode('-', $dto[0]);
                // dd($dob);
                $age = Carbon::createFromDate($dob[0], $dob[1], $dob[2])->diff(Carbon::now())->format('%y,%m,%d');
                $finalage = explode(',', $age);
            } else {
                $finalage = array(0, 0, 0);
            }
            $genderview = '';
            $gender = array('Male', 'Female', 'Other');
            foreach ($gender as $g) {
                $sel = ((!is_null($patient->fldptsex) or ($patient->fldptsex != '')) && ($g == $patient->fldptsex)) ? "selected" : "";
                $genderview .= '<option value=' . $g . ' ' . $sel . '>' . $g . '</option>';
            }

//            $district = Districts::all();
            $district = Municipal::select("flddistrict")->distinct('flddistrict')->where('fldprovince',$patient->fldprovince)->get();

            $dist = '';
            if (isset($district) and count($district) > 0) {

                foreach ($district as $d) {
                    $sel = ((!is_null($patient->fldptadddist) or ($patient->fldptadddist != '')) && ($d->flddistrict == $patient->fldptadddist)) ? "selected" : "";
                    $dist .= '<option value=' . $d->flddistrict . ' ' . $sel . ' >' . $d->flddistrict . '</option>';
                }
            }
            //province
            $provinces = Municipal::select("fldpality", "flddistrict", "fldprovince")->groupBy("fldprovince")->orderBy("fldprovince")->get();

            $prov = '';
            if (isset($provinces) and count($provinces) > 0) {

                foreach ($provinces as $province) {
                    $sel = ((!is_null($patient->fldprovince) or ($patient->fldprovince != '')) && ($province->fldprovince == $patient->fldprovince)) ? "selected" : "";
                    $prov .= '<option value="' . $province->fldprovince . '" ' . $sel . '>' . $province->fldprovince . '</option>';
                }

            }
            // Pality
            $municipals = Municipal::select("fldpality")->where('flddistrict',$patient->fldptadddist)->get();
            $mun = '';
            if (isset($municipals) and count($municipals) > 0) {
                foreach ($municipals as $municipal) {
                    //pality
                    $sel_mun = ((!is_null($patient->fldmunicipality) or ($patient->fldmunicipality != '')) && ($municipal->fldpality == $patient->fldmunicipality)) ? "selected" : "";
                    $mun .= '<option value="' . $municipal->fldpality . '" ' . $sel_mun . '>' . $municipal->fldpality . '</option>';
                }
            }
            $data['encounterId'] = $encounter->fldencounterval;
            $data['age'] = $finalage[0];
            $data['gender'] = $genderview;
            $data['month'] = $finalage[1] . '.' . $finalage[2];
            $data['districts'] = $dist;
            $data['provinces'] = $prov;
            $data['municipal'] = $mun;
            $data['result'] = $patient;
            // dd($data);
            echo json_encode($data);
            exit;
        } elseif (isset($request->type) && $request->type == 'F') {

            $fileindex = $request->encounterId;


            $patient = PatientInfo::where('fldadmitfile', $fileindex)->first();

            $encounter = Encounter::select('fldencounterval')
                ->where('fldpatientval', $patient->fldpatientval)
                ->first();
            // echo $encounter->fldpatientval; exit;
            if (isset($patient->fldptbirday) and $patient->fldptbirday != '') {
                $dto = explode(' ', $patient->fldptbirday);
                $dob = explode('-', $dto[0]);
                // dd($dob);
                $age = Carbon::createFromDate($dob[0], $dob[1], $dob[2])->diff(Carbon::now())->format('%y,%m,%d');
                $finalage = explode(',', $age);
            } else {
                $finalage = array(0, 0, 0);
            }

            $genderview = '';
            $gender = array('Male', 'Female', 'Other');
            foreach ($gender as $g) {
                $sel = ((!is_null($patient->fldptsex) or ($patient->fldptsex != '')) && ($g == $patient->fldptsex)) ? "selected" : "";
                $genderview .= '<option value=' . $g . ' ' . $sel . '>' . $g . '</option>';
            }

//            $district = Districts::all();
            $district = Municipal::select("flddistrict")->distinct('flddistrict')->where('fldprovince',$patient->fldprovince)->get();
            $dist = '';
            if (isset($district) and count($district) > 0) {

                foreach ($district as $d) {
                    $sel = ((!is_null($patient->fldptadddist) or ($patient->fldptadddist != '')) && ($d->flddistrict == $patient->fldptadddist)) ? "selected" : "";
                    $dist .= '<option value=' . $d->flddistrict . ' ' . $sel . '>' . $d->flddistrict . '</option>';
                }
            }

            //province
            $provinces = Municipal::select("fldpality", "flddistrict", "fldprovince")->groupBy("fldprovince")->orderBy("fldprovince")->get();

            $prov = '';
            if (isset($provinces) and count($provinces) > 0) {

                foreach ($provinces as $province) {
                    $sel = ((!is_null($patient->fldprovince) or ($patient->fldprovince != '')) && ($province->fldprovince == $patient->fldprovince)) ? "selected" : "";
                    $prov .= '<option value="' . $province->fldprovince . '" ' . $sel . '>' . $province->fldprovince . '</option>';
                }

            }
            // Pality
            $municipals = Municipal::select("fldpality")->where('flddistrict',$patient->fldptadddist)->get();
            $mun = '';
            if (isset($municipals) and count($municipals) > 0) {
                foreach ($municipals as $municipal) {
                    //pality
                    $sel_mun = ((!is_null($patient->fldmunicipality) or ($patient->fldmunicipality != '')) && ($municipal->fldpality == $patient->fldmunicipality)) ? "selected" : "";
                    $mun .= '<option value="' . $municipal->fldpality . '" ' . $sel_mun . '>' . $municipal->fldpality . '</option>';
                }
            }

            $data['encounterId'] = $encounter->fldencounterval;
            $data['age'] = $finalage[0];
            $data['month'] = $finalage[1] . '.' . $finalage[2];
            $data['districts'] = $dist;
            $data['provinces'] = $prov;
            $data['municipal'] = $mun;
            $data['result'] = $patient;
            $data['gender'] = $genderview;
            // dd($data);
            echo json_encode($data);
            exit;
        } else {
            $encounter_id = $data['encounterId'] = $request->encounterId;

            $encounter = Encounter::select('fldpatientval')
                ->where('fldencounterval', $encounter_id)
                ->first();
            // echo $encounter->fldpatientval; exit;
            $patient = PatientInfo::where('fldpatientval', $encounter->fldpatientval)->first();
            if (isset($patient->fldptbirday) and $patient->fldptbirday != '') {
                $dto = explode(' ', $patient->fldptbirday);
                $dob = explode('-', $dto[0]);
                // dd($dob);
                $age = Carbon::createFromDate($dob[0], $dob[1], $dob[2])->diff(Carbon::now())->format('%y,%m,%d');
                $finalage = explode(',', $age);
            } else {
                $finalage = array(0, 0, 0);
            }
            $genderview = '';
            $gender = array('Male', 'Female', 'Other');
            foreach ($gender as $g) {
                $sel = ((!is_null($patient->fldptsex) or ($patient->fldptsex != '')) && ($g == $patient->fldptsex)) ? "selected" : "";
                $genderview .= '<option value=' . $g . ' ' . $sel . '>' . $g . '</option>';
            }
//            $district = Districts::all();
            $district = Municipal::select("flddistrict")->distinct('flddistrict')->where('fldprovince',$patient->fldprovince)->get();
            $dist = '';
            if (isset($district) and count($district) > 0) {

                foreach ($district as $d) {
                    $sel = ((!is_null($patient->fldptadddist) or ($patient->fldptadddist != '')) && ($d->flddistrict == $patient->fldptadddist)) ? "selected" : "";
                    $dist .= '<option value=' . $d->flddistrict . ' ' . $sel . '>' . $d->flddistrict . '</option>';
                }
            }


            //province
            $provinces = Municipal::select("fldpality", "flddistrict", "fldprovince")->groupBy("fldprovince")->orderBy("fldprovince")->get();

            $prov = '';
            if (isset($provinces) and count($provinces) > 0) {

                foreach ($provinces as $province) {
                    $sel = ((!is_null($patient->fldprovince) or ($patient->fldprovince != '')) && ($province->fldprovince == $patient->fldprovince)) ? "selected" : "";
                    $prov .= '<option value="' . $province->fldprovince . '" ' . $sel . '>' . $province->fldprovince . '</option>';
                }

            }
            // Pality
            $municipals = Municipal::select("fldpality")->where('flddistrict',$patient->fldptadddist)->get();
            $mun = '';
            if (isset($municipals) and count($municipals) > 0) {
                foreach ($municipals as $municipal) {
                    //pality
                    $sel_mun = ((!is_null($patient->fldmunicipality) or ($patient->fldmunicipality != '')) && ($municipal->fldpality == $patient->fldmunicipality)) ? "selected" : "";
                    $mun .= '<option value="' . $municipal->fldpality . '" ' . $sel_mun . '>' . $municipal->fldpality . '</option>';
                }
            }

            $data['age'] = $finalage[0];
            $data['month'] = $finalage[1] . '.' . $finalage[2];
            $data['districts'] = $dist;
            $data['provinces'] = $prov;
            $data['municipal'] = $mun;
            $data['result'] = $patient;
            $data['gender'] = $genderview;
            // dd($data);
            echo json_encode($data);
            exit;
        }


    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function updatePatientProfile(Request $request)
    {
//        dd($request->all());
        // $patient= array();
        $encounter_id = $data['encounterId'] = $request->encounterId;

        $encounter = Encounter::select('fldpatientval')
            ->where('fldencounterval', $encounter_id)
            ->first();

        $mytime = Carbon::now();
        $pdata['fldptnamefir'] = $request->name;
        $pdata['fldmidname'] = $request->mid_name;
        $pdata['fldptaddvill'] = $request->address;
        $pdata['fldptadddist'] = $request->district;
        $pdata['fldptsex'] = $request->gender;
        $pdata['fldptcontact'] = $request->contact;
        $pdata['fldptguardian'] = $request->guardian;
        $pdata['fldcomment'] = $request->comment;
        $pdata['fldpassword'] = $request->password;
        $pdata['fldencrypt'] = $request->encryption;
        $pdata['fldptnamelast'] = $request->surname;
        $pdata['fldptadddist'] = $request->district;
        $pdata['fldemail'] = $request->email;
        $pdata['fldrelation'] = $request->relation;
        $pdata['fldptcode'] = $request->code_pan;
        $pdata['fldptbirday'] = $request->dob;
        $pdata['fldprovince'] = $request->province;
        $pdata['fldmunicipality'] = $request->muncipal;
        $pdata['fldwardno'] = $request->ward;
        $pdata['fldptaddvill'] = $request->tole;
        $pdata['flduptime'] = $mytime->toDateTimeString();
        $pdata['fldupuser'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;

        PatientInfo::where([['fldpatientval', $encounter->fldpatientval]])->update($pdata);
        \App\PatientCredential::where([
            'fldpatientval' => $encounter->fldpatientval
        ])->update([
            'fldpassword' => \App\Utils\Helpers::encodePassword($request->get('password'))
        ]);

        $patient = PatientInfo::where('fldpatientval', $encounter->fldpatientval)->first();
        $patient = PatientInfo::where('fldpatientval', $encounter->fldpatientval)->first();
        if (isset($patient->fldptbirday) and $patient->fldptbirday != '') {
            $dto = explode(' ', $patient->fldptbirday);
            $dob = explode('-', $dto[0]);
            // dd($dob);
            $age = Carbon::createFromDate($dob[0], $dob[1], $dob[2])->diff(Carbon::now())->format('%y,%m,%d');
            $finalage = explode(',', $age);
        } else {
            $finalage = array(0, 0, 0);
        }

        $genderview = '';
        $gender = array('Male', 'Female', 'Other');
        foreach ($gender as $g) {
            $sel = ((!is_null($patient->fldptsex) or ($patient->fldptsex != '')) && ($g == $patient->fldptsex)) ? "selected" : "";
            $genderview .= '<option value=' . $g . ' ' . $sel . '>' . $g . '</option>';
        }
//        $district = Districts::all();
        $district = Municipal::select("flddistrict")->distinct('flddistrict')->where('fldprovince',$patient->fldprovince)->get();
        $dist = '';
        if (isset($district) and count($district) > 0) {

            foreach ($district as $d) {
                $sel = ((!is_null($patient->fldptadddist) or ($patient->fldptadddist != '')) && ($d->flddistrict == $patient->fldptadddist)) ? "selected" : "";
                $dist .= '<option value=' . $d->flddistrict . ' ' . $sel . '>' . $d->flddistrict . '</option>';
            }
        }
        $data['age'] = $finalage[0];
        $data['month'] = $finalage[1] . '.' . $finalage[2];
        $data['districts'] = $dist;
        $data['result'] = $patient;
        $data['gender'] = $genderview;
        echo json_encode($data);
        exit;


    }


    public function searchByPatientNo(Request  $request){

        if(!$request->patientNo){
            return \response()->json('Please check Patient No');
        }

        $patient = PatientInfo::with('latestEncounter')->where('fldpatientval', $request->patientNo)->first();
        if($patient){

            if (isset($patient->fldptbirday) and $patient->fldptbirday != '') {
                $dto = explode(' ', $patient->fldptbirday);
                $dob = explode('-', $dto[0]);
                // dd($dob);
                $age = Carbon::createFromDate($dob[0], $dob[1], $dob[2])->diff(Carbon::now())->format('%y,%m,%d');
                $finalage = explode(',', $age);
            } else {
                $finalage = array(0, 0, 0);
            }

            $district = Municipal::select("flddistrict")->distinct('flddistrict')->where('fldprovince',$patient->fldprovince)->get();

            $dist = '';
            if (isset($district) and count($district) > 0) {

                foreach ($district as $d) {
                    $sel = ((!is_null($patient->fldptadddist) or ($patient->fldptadddist != '')) && ($d->flddistrict == $patient->fldptadddist)) ? "selected" : "";
                    $dist .= '<option value=' . $d->flddistrict . ' ' . $sel . ' >' . $d->flddistrict . '</option>';
                }
            }
            //province
            $provinces = Municipal::select("fldpality", "flddistrict", "fldprovince")->groupBy("fldprovince")->orderBy("fldprovince")->get();

            $prov = '';
            if (isset($provinces) and count($provinces) > 0) {

                foreach ($provinces as $province) {
                    $sel = ((!is_null($patient->fldprovince) or ($patient->fldprovince != '')) && ($province->fldprovince == $patient->fldprovince)) ? "selected" : "";
                    $prov .= '<option value="' . $province->fldprovince . '" ' . $sel . '>' . $province->fldprovince . '</option>';
                }

            }
            // Pality
            $municipals = Municipal::select("fldpality")->where('flddistrict',$patient->fldptadddist)->get();
            $mun = '';
            if (isset($municipals) and count($municipals) > 0) {
                foreach ($municipals as $municipal) {
                    //pality
                    $sel_mun = ((!is_null($patient->fldmunicipality) or ($patient->fldmunicipality != '')) && ($municipal->fldpality == $patient->fldmunicipality)) ? "selected" : "";
                    $mun .= '<option value="' . $municipal->fldpality . '" ' . $sel_mun . '>' . $municipal->fldpality . '</option>';
                }
            }

            $data['districts'] = $dist;
            $data['provinces'] = $prov;
            $data['municipal'] = $mun;
            $data['result'] = $patient;
            $data['age'] = $finalage[0];
            $data['month'] = $finalage[1] . '.' . $finalage[2];

            return \response()->json($data);
        }else{

            return  \response()->json(['error' =>'Patient Not Found!']);
        }

    }
}
