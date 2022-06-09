<?php

namespace Modules\Eappointment\Http\Controllers;

use App\CogentUsers;
use App\Encounter;
use App\PatientInfo;
use App\Eappointment;
use App\Utils\Helpers;
use App\Department;
use App\EappUser;
use Intervention\Image\Facades\Image;
use Validator;
use Session;
use Auth;
use File;
use Hash;
use Illuminate\Support\Facades\Input;
use App\Utils\Options;
use App\Group;
use App\GroupComputerAccess;
use App\PermissionGroup;
use App\PermissionModule;
use App\HospitalDepartment;
use App\HospitalDepartmentUsers;
use App\UserDepartment;
use App\UserDetail;
use App\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DoctorController extends Controller
{
    private $api_key;
    private $eurl;

    public function __construct(){
        $this->api_key = Options::get('e_appointment_hmac_key') ? Options::get('e_appointment_hmac_key') : Options::get('e_appointment_hmac_key');
        $this->eurl = Options::get('e_appointment_url') ? Options::get('e_appointment_url') : Options::get('e_appointment_url');
    }

   
    private function _apiCall($url, $method, $data = null)
    {
        try {
            \Log::info(json_encode([$url, $method, $data ? json_decode($data) : ""]));

            $headers = [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: '.$this->api_key,
            ];

            $curl_connection = curl_init($url);
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
            if($method == "PUT") curl_setopt($curl_connection, CURLOPT_CUSTOMREQUEST, "PUT");

            //set data to be posted
            if($method != "GET") curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $data);

            // set headers
            curl_setopt($curl_connection, CURLOPT_HTTPHEADER, $headers);

            //perform our request
            $result = curl_exec($curl_connection);

            \Log::info($result);

            //close the connection
            curl_close($curl_connection);

            return json_decode($result);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    public function doctorSetup(){

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.list') . '">Users</a></li><li>Create New User</li>';
        $data['title'] = "Create User - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['side_nav'] = 'users';
        $data['side_sub_nav'] = 'users';
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();

        $url = $this->eurl . 'qualification/min';

        $data['department'] = Department::all();
        $method = 'GET';
        $data['specializations'] = $this->_apiCall($url,$method);
        $emrurl = $this->eurl . 'serviceInfo/active/min/DOC';
       
      
        $data['services'] = $this->_apiCall($emrurl, $method);
        $emrurl = $this->eurl . 'salutation/active/min';
       
      
        $data['salutations'] = $this->_apiCall($emrurl, $method);
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
        $data['user_category'] = CogentUsers::select('fldcategory')->where('fldcategory', '!=', null)->distinct()->get();
        return view('eappointment::doctor_setup', $data);
    }

    public function doctorAdd(Request $request)
    {
        
        $request->validate([
            'name'                  => 'required',
            'gender'                => 'required',
            'address'               => 'required',
            'username'              => 'required|unique:users,username',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required',
            'expirydate'            => 'required|date_format:Y-m-d|after:today',
            'nurse'                 => 'required',
            'status'                => 'required',
            'two_fa'                => 'required',
            'profile_image'         => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=400',
            'signature_image'       => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=200'
        ]);
        \DB::beginTransaction();
        try {
            // 1 : Inserting in users table
            $profile_image = '';
            $signature_image = '';
            /*profile image crop*/
            if ($request->hasFile('profile_image')) {
                if ($request->x2 == NULL) {
                    $request->x2 = 400;
                }
                if ($request->y2 == NULL) {
                    $request->y2 = 400;
                }

                $width = $request->w;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->h;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('profile_image');
                $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filename);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                $profile_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
                @unlink(public_path('uploads/images/fullimage/' . $filename));
                @unlink(public_path('uploads/images/croppedimage/' . $filename));
            }

            /*profile image crop*/
            /*signature image crop*/
            if ($request->hasFile('signature_image')) {
                if ($request->x2s == NULL) {
                    $request->x2s = 400;
                }
                if ($request->y2s == NULL) {
                    $request->y2s = 400;
                }

                $width = $request->ws;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->hs;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('signature_image');
                $filenamesignature = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filenamesignature);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filenamesignature));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filenamesignature), 70);
                $signature_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
                @unlink(public_path('uploads/images/fullimage/' . $filenamesignature));
                @unlink(public_path('uploads/images/croppedimage/' . $filenamesignature));
            }
            /*signature image crop*/

            if ($request->designation_free != '') {
                $user_category = $request->designation_free;
            } else {
                $user_category = 'Dr';
            }
            $fullName = $request->name;
            $nameArray = explode(' ', $fullName);
            $firstName = array_shift($nameArray);
            $lastName = array_pop($nameArray);
            $middleName = implode(' ', $nameArray);
            $password = $this->generatePassword();
            $user_data = [
                'fldcategory' => ucfirst($user_category),
                'firstname' => $firstName,
                'middlename' => $middleName != "" ? $middleName : Null,
                'lastname' => $lastName,
                'username' => $request->username,
                'flduserid' => $request->username,
                'email' => $request->email,
                'password' => $this->passwordGenerate($password),
                'signature_title' => $request->get('signature_title'),
                'nmc' => $request->identification_type == 'nmc' ? $request->identification : NULL,
                'nhbc' => $request->identification_type == 'nhbc' ? $request->identification : NULL,
                'nnc' => $request->identification_type == 'nnc' ? $request->identification : NULL,
                'npc' => $request->identification_type == 'npc' ? $request->identification : NULL,
                'fldfaculty' => in_array('faculty', $request->role ?? []) ? 1 : 0,
                'fldpayable' => in_array('payable', $request->role ?? []) ? 1 : 0,
                'fldreferral' => in_array('referral', $request->role ?? []) ? 1 : 0,
                'fldopconsult' => in_array('consultant', $request->role ?? []) ? 1 : 0,
                'fldipconsult' => in_array('ip_clinician', $request->role ?? []) ? 1 : 0,
                'fldsigna' => in_array('signature', $request->role ?? []) ? 1 : 0,
                'fldreport' => in_array('data_export', $request->role ?? []) ? 1 : 0,
                'fldexpirydate' => $request->expirydate,
                'fldnursing' => $request->nurse,
                'status' => $request->status,
                'two_fa' => $request->two_fa,
                'profile_image' => isset($profile_image) ? $profile_image : '',
                'signature_image' => isset($signature_image) ? $signature_image : '',
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $user_id = CogentUsers::insertGetId($user_data);
            Helpers::logStack(["User created", "Event"], ['current_data' => $user_data]);
            if (!$user_id) {
               \DB::rollBack();
                \Session::flash('error_message', 'Something went wrong. Please try again.');
                return redirect()->route('admin.user.add.new');
            }

            // 2 : Updating in users details table
            $user_details_data = [
                'user_id' => $user_id,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            UserDetail::insert($user_details_data);
            Helpers::logStack(["User detail created", "Event"], ['current_data' => $user_details_data]);
            // 3 : Users Group Table
            $groups = $request->get('groups') ?? [];
            if (count($groups) > 0) {
                $final_grps = [];
                foreach ($groups as $grps) {
                    $temp['user_id'] = $user_id;
                    $temp['group_id'] = $grps;
                    $temp['created_at'] = config('constants.current_date_time');
                    $temp['updated_at'] = config('constants.current_date_time');
                    $temp['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                    $final_grps[] = $temp;
                }
                if (count($final_grps) > 0) UserGroup::insert($final_grps);
                Helpers::logStack(["User group created", "Event"], ['current_data' => $final_grps]);
            }

            // 4 : Users department table
            $department = $request->get('department') ?? [];
            if (count($department) > 0) {
                $final_dept = [];
                foreach ($department as $dept) {
                    $temp_dept['user_id'] = $user_id;
                    $temp_dept['department_id'] = $dept;
                    $final_dept[] = $temp_dept;
                }
                if (count($final_dept) > 0) UserDepartment::insert($final_dept);
                Helpers::logStack(["User department created", "Event"], ['current_data' => $final_dept]);
            }

            // 5 : Users hospital department users table
            $hospitalDepartment = $request->get('hospital_department') ?? [];
            if (count($hospitalDepartment) > 0) {
                $final_hosp_dept = [];
                foreach ($hospitalDepartment as $hdept) {
                    $temp_hosp_dept['hospital_department_id'] = $hdept;
                    $temp_hosp_dept['user_id'] = $user_id;
                    $final_hosp_dept[] = $temp_hosp_dept;
                }
                if (count($final_hosp_dept) > 0) HospitalDepartmentUsers::insert($final_hosp_dept);
                Helpers::logStack(["User hospital department created", "Event"], ['current_data' => $final_hosp_dept]);
            }

            $success_mesg = "User created successfully. Please save the user credentials.";
            $success_mesg .= "<strong>Username : " . $request->get('username') . "</strong>";
            $success_mesg .= "<strong>Password : " . $password . "</strong>";
            $this->mapDoctorToEappointment($request,$user_id);
            \DB::commit();
            \Session::flash('success_message_special', $success_mesg);
            return redirect()->route('eappointment-doctor-setup');
        } catch (\Exception $e) {
            \DB::rollBack();
            // dd($e);
            \Session::flash('error_message', $e->getMessage());
            Helpers::logStack([$e->getMessage() . ' in admin user create', "Error"]);
            return redirect()->route('eappointment-doctor-setup')->withInput();
        }
    }

    public function passwordGenerate($password)
    {
        $pwd = $password;

        $generated_pwd = "";
        for ($i = 0, $iMax = strlen($pwd); $i < $iMax; $i++) {
            $current_string = substr($pwd, $i, 1);
            $temp_ascii = ord($current_string);
            if (strlen($temp_ascii) == 1) {
                $temp_ascii = "00" . $temp_ascii;
            } elseif (strlen($temp_ascii) == 2) {
                $temp_ascii = "0" . $temp_ascii;
            }
            $generated_pwd .= $temp_ascii;
        }
        return $generated_pwd;
    }

    protected function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    protected function mapDoctorToEappointment($request,$user_id){
        try {
            if($request->gender == 'male'){
                $gender = "M";
            }else if($request->gender == 'female'){
                $gender = "F";
            } else{
                $gender = "O";
            }
            $emrurl = $this->eurl . 'doctor';
            // Need to log request data.
            $url = $this->eurl . 'billingMode/service-wise/active/'.$request->service;
		$response = $this->_apiCall($url, 'GET');
            $args = json_encode([
                "appointmentChargeDetails" => [
                    [
                    "serviceInfoChargeId" => $response->billingModeDetails[0]->serviceInfoChargeId
                   ]
                  ],
                "avatar" => "",
                "email" => $request->email,
                "genderCode" => $gender,
                "mobileNumber" => '+977-'.$request->phone,
                "name" => $request->name,
                "nmcNumber" => $request->identification,
                "qualificationIds" => $request->qualifications,
                "salutationIds" => $request->salutations,
                "specializationIds" => [110],
                "status" => "Y",
            ]);

            $response =  $this->_apiCall($emrurl, "POST", $args);
            if(isset($response) && isset($response->responseCode)){
                if($response->responseCode){
                    $request->session()->flash( 'error_message', $response->errorMessage );
                    return redirect()->back();
                }
               
            }else if(isset($response) && isset($response->error)){
                $request->session()->flash( 'error_message', $response->error );
                return redirect()->back();
        }else{
            $eapp_doc_id = $response;
            $this->addToTable($eapp_doc_id,$user_id);
                $request->session()->flash( 'success_message', 'Doctor Added' );
                return redirect()->route('eappointment-doctor-view');
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return $e;
        }
    }

    protected function updateEappointmentDoctor($request,$user_id){
        try {
            $doctor_qualifications = array();
            $old_qualifications_ids = array();
            $doctor_salutations = array();
            $old_salutations_ids = array();
            $url = $this->eurl . 'doctor/updateDetails/'.$request->eapp_doc_id;
            $response = $this->_apiCall($url,'GET');
            $old_doctor_salutations = $response->doctorSalutationResponseDTOS;
            $old_doctor_qualifications = $response->doctorQualificationResponseDTOS;
            $old_doctor_specialization = $response->doctorSpecializationResponseDTOS;
            $appointmentChargeDetails = $response->appointmentChargeDetails;

            foreach( $old_doctor_qualifications as $oqs){
                array_push($old_qualifications_ids,$oqs->qualificationId);
                if(in_array($oqs->qualificationId,$request->qualifications)){
                array_push($doctor_qualifications,array(
                    "doctorQualificationId"=> $oqs->doctorQualificationId,
                      "qualificationId"=> $oqs->qualificationId,
                      "status" =>"Y"
                ));} else {
                    array_push($doctor_qualifications,array(
                        "doctorQualificationId"=> $oqs->doctorQualificationId,
                          "qualificationId"=> $oqs->qualificationId,
                          "status" =>"N"
                    ));
                }
            }
            foreach( $request->qualifications as $qual){
                if(!in_array($qual,$old_qualifications_ids)){
                array_push($doctor_qualifications,array(
                    "doctorQualificationId"=> "",
                      "qualificationId"=> $qual,
                      "status" =>"Y"
                ));
            }
            }

            foreach( $old_doctor_salutations as $os){
                array_push($old_salutations_ids,$os->salutationId);
                if(in_array($os->salutationId,$request->salutations)){
                    array_push($doctor_salutations,array(
                        "doctorSalutationId"=> $os->doctorSalutationId,
                          "salutationId"=> $os->salutationId,
                          "status" =>"Y"
                    ));
                }else{
                    array_push($doctor_salutations,array(
                        "doctorSalutationId"=> $os->doctorSalutationId,
                          "salutationId"=> $os->salutationId,
                          "status" =>"N"
                    ));
                }
               
            }

            foreach( $request->salutations as $sal){
                if(!in_array($sal,$old_salutations_ids)){
                array_push($doctor_salutations,array(
                    "doctorSalutationId"=> "",
                      "salutationId"=> $sal,
                      "status" =>"Y"
                ));
            }
            }
            if($request->gender == 'male'){
                $gender = "M";
            }else if($request->gender == 'female'){
                $gender = "F";
            } else{
                $gender = "O";
            }
            $emrurl = $this->eurl . 'doctor';
            $url = $this->eurl . 'billingMode/service-wise/active/'.$request->service;
		    $response = $this->_apiCall($url, 'GET');
            // Need to log request data.
            $args = json_encode([
                "appointmentChargeDetails" => [
                    array(
                        "billingModeId"=> null,
                        "doctorAppointmentChargeId"=> $appointmentChargeDetails[0]->doctorAppointmentChargeId,
                        "serviceInfoChargeId"=> $response->billingModeDetails[0]->serviceInfoChargeId,
                        "status"=> 'Y'
                    )
                  ],
                  "doctorInfo" => [
                    "avatar"=> "",
                    "email"=> $request->email,
                    "genderCode"=> $gender,
                    "id"=> $request->eapp_doc_id,
                    "isAvatarUpdate"=> "N",
                    "mobileNumber"=> '+977-'.$request->phone,
                    "name"=> $request->name,
                    "nmcNumber"=>$request->identification,
                    "remarks"=> "done",
                    "serviceInfoId"=> $request->service,
                    "status"=> "Y"
                  ],
                  "doctorQualificationInfo"=> 
                    $doctor_qualifications
                  ,
                  "doctorSalutationInfo"=> 
                    $doctor_salutations
                  ,
                  "doctorSpecializationInfo"=> [
                    [
                      "doctorSpecializationId"=> $old_doctor_specialization[0]->doctorSpecializationId,
                      "specializationId"=> $old_doctor_specialization[0]->specializationId,
                      "status"=> "Y"
                    ]
                  ],
                  "hasServiceInfoChargeUpdated"=> true
            ]);

            $response =  $this->_apiCall($emrurl, "PUT", $args);
            if(isset($response) && isset($response->responseCode)){
                if($response->responseCode){
                    $request->session()->flash( 'error_message', $response->errorMessage );
                    return redirect()->back();
                }
               
            }else if(isset($response) && isset($response->error)){
                $request->session()->flash( 'error_message', $response->error );
                return redirect()->back();
        }else{
            $eapp_doc_id = $response;
                $request->session()->flash( 'success_message', 'Doctor updated' );
                return redirect()->back();
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return $e;
        }
    }

    protected function addToTable($eapp_doc_id,$user_id)
    {
        \DB::table('eapp_users')->insert(
            ['eapp_doc_id' => $eapp_doc_id,
             'user_id' => $user_id,
            "created_at" =>  \Carbon\Carbon::now(), 
            "updated_at" => \Carbon\Carbon::now() ]
        );
    }

    protected function getEappointmentDoctors()
    {
        try {
            $url = $this->eurl . 'doctor/active/min';

            return $this->_apiCall($url, "GET");
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    public function doctorEdit($id)
    {
        $data = array();
        $data['user'] = CogentUsers::where('id', $id)->where('status', '!=', 'deleted')->with('department', 'hospitalDepartment', 'user_group', 'user_details','eapp')->first();
        if (!$data['user']) {
            Session::flash('error_message', 'Something went wrong. User records not found.');
            return redirect()->route('admin.user.userview');
        }
        if(isset($data['user']->eapp)){
        $method = 'GET';
        $url = $this->eurl . 'doctor/updateDetails/'.$data['user']->eapp->eapp_doc_id;
        $response = $this->_apiCall($url,$method);
    
       $data['service_data'] = $response->serviceInfoId;
       $data['nmc_number'] = $response->nmcNumber;
       $data['eapp_doc_id'] = $data['user']->eapp->eapp_doc_id;
       $data['doctor_salutations'] = $response->doctorSalutationResponseDTOS;
       $data['doctor_salutations'] = array_pluck($data['doctor_salutations'], 'salutationId');
       $data['doctor_qualifications'] = $response->doctorQualificationResponseDTOS;
       $data['doctor_qualifications'] = array_pluck($data['doctor_qualifications'], 'qualificationId');
       $data['doctor_specializations'] = $response->doctorSpecializationResponseDTOS;
       $data['doctor_specializations'] = array_pluck($data['doctor_specializations'], 'specializationId');


        $data['department'] = Department::all();
        $url = $this->eurl . 'qualification/min';
        $data['qualifications'] = $this->_apiCall($url,$method);
        $emrurl = $this->eurl . 'serviceInfo/active/min/DOC';
       
      
        $data['services'] = $this->_apiCall($emrurl, $method);
        $emrurl = $this->eurl . 'salutation/active/min';
       
      
        $data['salutations'] = $this->_apiCall($emrurl, $method);
        }

       
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.list') . '">Users</a></li><li>Create New User</li>';
        $data['title'] = "Create User - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['side_nav'] = 'users';
        $data['side_sub_nav'] = 'users';
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();


        $data['department'] = Department::all();
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
        $data['user_category'] = CogentUsers::select('fldcategory')->where('fldcategory', '!=', null)->distinct()->get();
        return view('eappointment::doctor_edit', $data);
    }

    public function doctorView(Request $request)
    {
        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li>Users Management</li>';
        $data['title'] = "User Management - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['header_nav'] = 'users';
        $data["group"] = $request->group ?? "";
        $data["department"] = $request->department ?? "";
        $data["hospital_department"] = $request->hospital_department ?? "";
        $data["role"] = $request->role ?? "";
        $data['users'] = CogentUsers::where('status', '!=', 'deleted')
        ->whereHas('eapp')
             ->where('fldopconsult', 1)
            ->when($request->group, function ($query) use ($request) {
                $query->whereHas('user_group', function ($query) use ($request) {
                    $query->where('group_id', $request->groups);
                });
            })
            ->when($request->department, function ($query) use ($request) {
                $query->whereHas('department', function ($query) use ($request) {
                    $query->where('department_id', $request->department);
                });
            })
            ->when($request->hospital_department, function ($query) use ($request) {
                $query->whereHas('hospitalDepartment', function ($query) use ($request) {
                    $query->where('hospital_department_id', $request->hospital_department);
                });
            })
            ->with('user_details', 'user_group.group_detail', 'user_group')->get();
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
        $data['departments'] = Department::all();
        return view('eappointment::doctor_view', $data);
    }

    public function doctorUpdate(Request $request, $id)
    {
        $request->validate([
            'designation'           => 'required',
            'name'                  => 'required',
            'gender'                => 'required',
            'address'               => 'required',
            'username'              => 'required|unique:users,username,' . $id,
            'email'                 => 'required|email|unique:users,email,' . $id,
            'phone'                 => 'required',
            // 'signature_title'       => 'required',
            // 'groups'                => 'required',
            // 'department'            => 'required',
            // 'hospital_department'   => 'required',
            // 'role'                  => 'required',
            // 'identification_type'   => 'required',
            // 'identification'        => 'required',
            'expirydate'            => 'required|date_format:Y-m-d|after:today',
            'nurse'                 => 'required',
            'status'                => 'required',
            'two_fa'                => 'required',
            'profile_image'         => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=400',
            'signature_image'       => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=200'
        ]);
        \DB::beginTransaction();
        try {
            $user = CogentUsers::where('id', $id)->first();
            if (!$user) {
                \Session::flash('error_message', "User not found.");
                Helpers::logStack(['User not found in admin user update', "Error"]);
                return redirect()->route('admin.user.edit.new', $id);
            }
            // 1 : Inserting in users table
            $profile_image = '';
            $signature_image = '';
            /*profile image crop*/
            if ($request->hasFile('profile_image')) {
                if ($request->x2 == NULL) {
                    $request->x2 = 400;
                }
                if ($request->y2 == NULL) {
                    $request->y2 = 400;
                }

                $width = $request->w;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->h;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('profile_image');
                $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filename);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                $profile_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
                @unlink(public_path('uploads/images/fullimage/' . $filename));
                @unlink(public_path('uploads/images/croppedimage/' . $filename));
            }

            /*profile image crop*/
            /*signature image crop*/
            if ($request->hasFile('signature_image')) {
                if ($request->x2s == NULL) {
                    $request->x2s = 400;
                }
                if ($request->y2s == NULL) {
                    $request->y2s = 400;
                }

                $width = $request->ws;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->hs;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('signature_image');
                $filenamesignature = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filenamesignature);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filenamesignature));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filenamesignature), 70);
                $signature_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
                @unlink(public_path('uploads/images/fullimage/' . $filenamesignature));
                @unlink(public_path('uploads/images/croppedimage/' . $filenamesignature));
            }
            /*signature image crop*/

            if ($request->designation_free != '') {
                $user_category = $request->designation_free;
            } else {
                $user_category = $request->designation;
            }
            $fullName = $request->name;
            $nameArray = explode(' ', $fullName);
            $firstName = array_shift($nameArray);
            $lastName = array_pop($nameArray);
            $middleName = implode(' ', $nameArray);
            $user_data = [
                'fldcategory' => ucfirst($user_category),
                'firstname' => $firstName,
                'middlename' => $middleName != "" ? $middleName : Null,
                'lastname' => $lastName,
                'username' => $request->username,
                'flduserid' => $request->username,
                'email' => $request->email,
                'signature_title' => $request->get('signature_title'),
                'nmc' => $request->identification_type == 'nmc' ? $request->identification : NULL,
                'nhbc' => $request->identification_type == 'nhbc' ? $request->identification : NULL,
                'nnc' => $request->identification_type == 'nnc' ? $request->identification : NULL,
                'npc' => $request->identification_type == 'npc' ? $request->identification : NULL,
                'fldfaculty' => in_array('faculty', $request->role ?? []) ? 1 : 0,
                'fldpayable' => in_array('payable', $request->role ?? []) ? 1 : 0,
                'fldreferral' => in_array('referral', $request->role ?? []) ? 1 : 0,
                'fldopconsult' => in_array('consultant', $request->role ?? []) ? 1 : 0,
                'fldipconsult' => in_array('ip_clinician', $request->role ?? []) ? 1 : 0,
                'fldsigna' => in_array('signature', $request->role ?? []) ? 1 : 0,
                'fldreport' => in_array('data_export', $request->role ?? []) ? 1 : 0,
                'fldexpirydate' => $request->expirydate,
                'fldnursing' => $request->nurse,
                'status' => $request->status,
                'two_fa' => $request->two_fa,
                'profile_image' => isset($profile_image) ? $profile_image : '',
                'signature_image' => isset($signature_image) ? $signature_image : '',
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $user->update($user_data);
            Helpers::logStack(["User updated", "Event"], ['current_data' => $user_data, 'previous_data' => $user]);

            // 2 : Updating in users details table
            $user_details_data = [
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $userdetails = UserDetail::where('user_id', $user->id)->first();
            UserDetail::where('user_id', $user->id)->update($user_details_data);
            Helpers::logStack(["User detail updated", "Event"], ['current_data' => $user_details_data, 'previous_data' => $userdetails]);
            // 3 : Users Group Table
            $groups = $request->get('groups');
            if (count($groups) > 0) {
                $final_grps = [];
                foreach ($groups as $grps) {
                    $temp['user_id'] = $user->id;
                    $temp['group_id'] = $grps;
                    $temp['created_at'] = config('constants.current_date_time');
                    $temp['updated_at'] = config('constants.current_date_time');
                    $temp['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                    $final_grps[] = $temp;
                }
                $usergroups = UserGroup::where('user_id', $user->id)->get();
                UserGroup::where('user_id', $user->id)->delete();
                if (count($final_grps) > 0) UserGroup::insert($final_grps);
                Helpers::logStack(["User group updated", "Event"], ['current_data' => $final_grps, 'previous_data' => $usergroups]);
            }

            // 4 : Users department table
            $department = $request->get('department');
            if (is_array($department)) {
                $final_dept = [];
                foreach ($department as $dept) {
                    $temp_dept['user_id'] = $user->id;
                    $temp_dept['department_id'] = $dept;
                    $final_dept[] = $temp_dept;
                }
                $userdepartments = UserDepartment::where('user_id', $user->id)->get();
                UserDepartment::where('user_id', $user->id)->delete();
                if (count($final_dept) > 0) UserDepartment::insert($final_dept);
                Helpers::logStack(["User department updated", "Event"], ['current_data' => $final_dept, 'previous_data' => $userdepartments]);
            }

            // 5 : Users hospital department users table
            $hospitalDepartment = $request->get('hospital_department');
            if (isset($hospitalDepartment) && count($hospitalDepartment) > 0) {
                $final_hosp_dept = [];
                foreach ($hospitalDepartment as $hdept) {
                    $temp_hosp_dept['hospital_department_id'] = $hdept;
                    $temp_hosp_dept['user_id'] = $user->id;
                    $final_hosp_dept[] = $temp_hosp_dept;
                }
                $hospitalsdepartments = HospitalDepartmentUsers::where('user_id', $user->id)->get();
                HospitalDepartmentUsers::where('user_id', $user->id)->delete();
                if (count($final_hosp_dept) > 0) HospitalDepartmentUsers::insert($final_hosp_dept);
                Helpers::logStack(["User hospital department updated", "Event"], ['current_data' => $final_hosp_dept, 'previous_data' => $hospitalsdepartments]);
            }

            \DB::commit();
            $this->updateEappointmentDoctor($request,$user->id);
            return redirect()->route('eappointment-doctor-view');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Session::flash('error_message', $e->getMessage());
            Helpers::logStack([$e->getMessage() . ' in admin user create', "Error"]);
            return redirect()->route('eappointment-doctor-edit', $id);
        }
    }

}
