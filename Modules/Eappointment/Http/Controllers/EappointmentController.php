<?php

namespace Modules\Eappointment\Http\Controllers;

use App\CogentUsers;
use App\Encounter;
use App\PatientInfo;
use App\Eappointment;
use App\Utils\Helpers;
use App\Department;
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

class EappointmentController extends Controller
{
    private $api_key;
    private $eurl;

    public function __construct(){
        $this->api_key = Options::get('e_appointment_hmac_key') ? Options::get('e_appointment_hmac_key') : Options::get('e_appointment_hmac_key');
        $this->eurl = Options::get('e_appointment_url') ? Options::get('e_appointment_url') : Options::get('e_appointment_url');
    }

    public function index(Request $request)
    {
        $total = 0;
        $size = 15;
        $data['appointments'] = [];
        $page = $request->page ?? 1;
        $today = date('Y-m-d', strtotime("+7 day"));
        $amonthdate = date('Y-m-d', strtotime("-1 months"));
        $emrurl = $this->eurl . 'appointment/pending-approval';
        $filter = [
            "appointmentId" => $request->appointmentId ?? null,
            "appointmentNumber" => $request->appointmentNumber ?? null,
            "doctorId" => $request->doctorId ?? null,
            "fromDate" =>  $request->fromDate ?? $amonthdate,
            "patientMetaInfoId" =>  $request->patientMetaInfoId ?? null,
            "patientType" =>  $request->patientType ?? null,
            "specializationId" =>  $request->specializationId ?? null,
            "toDate" =>   $request->toDate ?? $today
        ];

        $response = $this->getPendingAppointment($emrurl, $page, $size, $filter);

        if ($response) {
            $data['appointments'] = $response->pendingAppointmentApprovals;
            $total = $response->totalItems;
        }


        $partition = 0;
        if ($total > $size) {
            $partition = $total / $size;
        }

        $data['DoctorHospitalwise'] = $this->getDoctorHospitalwise();
        $data['SpecializationActive'] = $this->getSpecialization();
        $data['PatientMetadatainfo'] = $this->getPatientMetadatainfo();

        return view('eappointment::appointment-list', $data);
    }

    public function revenueReport(Request $request)
    {
        $total = 0;
        $size = 10;
        $data['appointments'] = [];
        $page = $request->page ?? 1;
        $today = date('Y-m-d', strtotime("+7 day"));
        $emrurl = $this->eurl . 'appointment/log';
        $amonthdate = date('Y-m-d', strtotime("-1 months"));
        $filter = [
            "appointmentCategory" => $request->appointmentCategory ?? "",
            "appointmentId" => $request->appointmentId ?? null,
            "appointmentNumber" => $request->appointmentNumber ?? "",
            "appointmentServiceTypeCode" => $request->appointmentServiceTypeCode ?? "DOC",
            "doctorId" => $request->doctorId ?? null,
            "fromDate" => $request->fromDate ??  $amonthdate,
            "hospitalDepartmentId" => $request->hospitalDepartmentId ?? null,
            "hospitalDepartmentRoomInfoId" => $request->hospitalDepartmentRoomInfoId ?? null,
            "patientMetaInfoId" => $request->patientMetaInfoId ?? null,
            "patientType" => $request->patientType ?? "",
            "specializationId" => $request->specializationId ?? null,
            "status" => $request->status ?? "",
            "toDate" => $request->toDate ?? $today
        ];

        $response = $this->getLogAppointment($emrurl, $page, $size, $filter);

        if ($response) {
            $data['appointments'] = $response->appointmentLogs;
            $data['bookedInfo'] = $response->bookedInfo;

            $data['checkedInInfo'] = $response->checkedInInfo;
            $data['cancelledInfo'] = $response->cancelledInfo;

            $data['refundInfo'] = $response->refundInfo;
            $data['revenueFromRefundInfo'] = $response->revenueFromRefundInfo;

            $total = $response->totalItems;
        }

        $partition = 0;
        if ($total > $size) {
            $partition = $total / $size;
        }

        $data['DoctorHospitalwise'] = $this->getDoctorHospitalwise();
        $data['SpecializationActive'] = $this->getSpecialization();
        $data['PatientMetadatainfo'] = $this->getPatientMetadatainfo();
        $data['HospitalDepartment'] = ''; //$this->getHospitalDepartment();
        $data['AppointmentServiceType'] = ''; //$this->getAppointmentServiceType();

        return view('eappointment::appointment-log-list', $data);
    }

    public function checkin(Request $request)
    {
        try {
            $hospitalNumber = null;
            $appid = $request->appid;
            $emrurl = $this->eurl . 'appointment/approve';

            if(isset($request->oldpatientid) && !empty($request->oldpatientid)){
                $hospitalNumber = $request->oldpatientid;
                $ispatientnew = false;
            }elseif(isset($request->patient_id) && !empty($request->patient_id)){
                $hospitalNumber = $request->patient_id;
                $ispatientnew = false;
            }else {
                $ispatientnew = true;
            }

            $url = $emrurl;

            $args = json_encode([
                "appointmentId" => $appid,
                "billingStatus" => "BILLING_SUCCESS",
                "featureCode" => "DOC_APPCH",
                "hospitalNumber" => $hospitalNumber,
                "integrationChannelCode" => "BACK",
                "isPatientNew" => $ispatientnew
            ]);

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
            curl_setopt($curl_connection, CURLOPT_CUSTOMREQUEST, "PUT");

            //set data to be posted
            curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $args);

            // set headers
            curl_setopt($curl_connection, CURLOPT_HTTPHEADER, $headers);

            //perform our request
            $result = curl_exec($curl_connection);
            $status_code = curl_getinfo($curl_connection, CURLINFO_HTTP_CODE);

            //close the connection
            curl_close($curl_connection);

            $checkeappointment  = Eappointment::where('appointmentId', $appid)->first();
            if ($checkeappointment) {
                $encounterID = $checkeappointment->fldencounterval;
                $patientId = $checkeappointment->fldpatientval;
                return response()->json([
                    'responseMessage' => 'Patient created',
                    'statusCode' => 200,
                    'appointment_encounter_id' =>  $encounterID,
                    'appointment_patient_id' =>   $patientId,
                    'urlchange' => route('print.ticket', $patientId),
                    'checkinstatus' => $status_code
                ], 200);
            } else {
                $encounterID = 0;
                $patientId = 0;
                return response()->json([
                    'responseMessage' => 'Got response 400',
                    'statusCode' => 400,
                    'checkinstatus' => $status_code,
                    'urlchange' => route('print.ticket', '00'),
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return response()->json([
                'responseMessage' => $e->getMessage(),
                'statusCode' => 404
            ], 404);
        }
    }

    public function savePatient(Request $request)
    {
        try {

            $file =  base_path().'\\public\\'.'people.txt';
            // Open the file to get existing content
            $current = file_get_contents($file);
            // Append a new person to the file

            // Write the contents back to the file
            file_put_contents($file, $request);
             \DB::beginTransaction();

            $fname = '';
            $mname = '';
            $lname = '';
            $name = $request->name;
            $age =  $request->age;

            $gender =  $request->gender;
            $ageMonth = $request->ageMonth;
            $ageDay = $request->ageDay;
            $district = $request->district;
            $vdcOrMunicipality = $request->vdcOrMunicipality;
            $wardNo = $request->wardNo;
            $emailAddress =  $request->emailAddress;
            $appointmentNo = $request->appointmentNo;

            $mobileNo =  $request->mobileNo;
            $address =  $request->address;
            $txn_amount =  $request->transactionAmount;

            $product_code =  NULL;
            $esewa_id =  $request->eSewaId;
            $is_refund =  $request->isRefund;
            $hospitalName =  $request->hospitalName;
            $remarks =  $request->remarks;
            $appointmentId =  $request->appointmentId;
            $hospitalNumber = $request->hospitalNumber;
            $dob = $request->dateOfBirth ?? '1990-02-02';
            $appointmentDate =$request->appointmentDate;
            $title =$request->title;
            $province = $request->province;
            $consultant =$request->doctorName;
            $department = $request->consultant;
            $appointmentMode = $request->appointmentMode;
            $ageYear = $request->ageYear;
            $country = $request->country;
            $txn_id = $request->transactionNumber;

            $refund_amount = $request->refundAmount;




            if ($name) {
                $names = explode(' ', $name);
                if (count($names) > 2) {
                    $fname = $names[0];
                    $mname = $names[1];
                    $lname = $names[2];
                } else {
                    if(isset($names[0])){
                        $fname = $names[0]?$names[0] :'';
                    }else{
                        $fname = '';
                    }

                    if(isset($names[1])){
                        $lname = $names[1]? $names[1]:'';
                    }else{
                        $lname = '';
                    }



                }
            }


            $datetime = date('Y-m-d H:i:s');
            $time = date('H:i:s');
            $userid = 'chirayu';

            $computer = Helpers::getCompName();
            $fiscalYear = Helpers::getNepaliFiscalYearRange();
            $startdate = Helpers::dateNepToEng($fiscalYear['startdate'])->full_date . ' 00:00:00';
            $enddate = Helpers::dateNepToEng($fiscalYear['enddate'])->full_date . ' 23:59:59';





            if (empty($request->hospitalNumber)) {
                $patientId = Helpers::getNextAutoId('PatientNo', TRUE);

                PatientInfo::insertGetId([
                    'fldpatientval' => $patientId,
                    'fldptnamefir' => $fname,
                    'fldptnamelast' => $lname,
                    'fldptsex' => $gender,
                    'fldptaddvill' => $address,
                    'fldptadddist' => $district,
                    'fldptcontact' => $mobileNo,
                    'fldptbirday' => $dob,
                    'fldptadmindate' => $appointmentDate,
                    'fldemail' => $emailAddress,
                    'flddiscount' => '',
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'xyz' => '0',
                    'fldbookingid' => $appointmentNo,
                    'fldtitle' => $title,
                    'fldmidname' => $mname,
                    'fldcountry' => $country,
                    'fldprovince' => $province,
                    'fldmunicipality' => $vdcOrMunicipality, //$request->get('municipality'),
                    'fldwardno' => $wardNo,

                    //  'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);



                $username = "{$fname}.{$lname}";
                $username = strtolower($username);
                $username = Helpers::getUniquePatientUsetname($username);

                \App\PatientCredential::insert([
                    'fldpatientval' => $patientId,
                    'fldusername' => $username,
                    'fldpassword' => Helpers::encodePassword($username),
                    'fldstatus' => 'Active',
                    'fldconsultant' => $consultant,
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'fldcomp' => $computer,
                    'xyz' => '0',
                ]);




                //$this->_sendPatientCredential($patientId, $request->get('bill'));

            } else {
                $patientId = $hospitalNumber;

            }

            $dob = $request->get('dob');
            if ($dob)
                $dob = Helpers::dateNepToEng($request->get('dob'))->full_date . ' ' . $time;


            $formatData = \App\Patsubs::first();
            if (!$formatData)
                $formatData = new \App\Patsubs();


            $encounterID = Helpers::getNextAutoId('EncounterID', TRUE);
            $today_date = \Carbon\Carbon::now()->format('Y-m-d');
            $current_fiscalyr = \App\Year::select('fldname')->where([
                ['fldfirst', '<=', $today_date],
                ['fldlast', '>=', $today_date],
            ])->first();
            $current_fiscalyr = ($current_fiscalyr) ? $current_fiscalyr->fldname : '';
            if (Options::get('reg_seperate_num') == 'Yes') {


                $formatedEncId = 'OP' . $current_fiscalyr . '-' . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            } else {
                $formatedEncId = 'OP' . $current_fiscalyr . '-' . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            }

            $fldvisit = 'New';
            //fiscal year check garnu parcha la
            $checkencounter = Encounter::where('fldpatientval', $patientId)->first();
            if ($checkencounter) {
                $fldvisit = 'Old';
            }

            \App\Encounter::insert([
                'fldencounterval' => $formatedEncId,
                'fldpatientval' => $patientId,
                'fldadmitlocat' => '',
                'fldcurrlocat' => $department,
                'flddoa' => $datetime,
                'flddisctype' => 'General',
                'fldcashcredit' => '0',
                'fldadmission' => 'Registered',
                'fldregdate' => $datetime,
                'fldbillingmode' => 'General',
                'fldcomp' => $computer,
                'fldvisit' => $fldvisit,
                'xyz' => '0',
                'fldinside' => '0',
                'flduserid' => $userid,
                // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);



            $billNumberGeneratedString = "";

            $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);

            $dateToday = \Carbon\Carbon::now();

            $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();

            $billNumberGeneratedString = "REG-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');


            $patBillings = [];

            $grandTotal = 0;

            $patBillings[] = [
                'fldencounterval' => $formatedEncId,
                'fldbillingmode' => 'General',
                'flditemtype' => 'Registration',
                'flditemname' => 'Registration',
                'flditemrate' => $txn_amount,
                'flditemqty' => 1,
                'fldreason' => $remarks,
                'flddiscper' => 0,
                'flddiscamt' => 0,
                'fldditemamt' => $txn_amount,
                'fldpayto' => '', // yo k huncha
                'fldorduserid' => $userid,
                'fldordtime' => $datetime,
                'fldordcomp' => $computer,
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldprint' => '0',
                'fldtarget' => $computer,
                'fldsave' => '1',
                'fldsample' => 'Waiting',
                'xyz' => '0',
                'fldbillno' => $billNumberGeneratedString,
                'fldstatus' => 'Cleared',
                //'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
            ];

            $grandTotal = $txn_amount;
            \App\PatBilling::insert($patBillings);

            $patbilldetail = [
                'fldencounterval' => $formatedEncId,
                'fldbillno' => $billNumberGeneratedString,
                'fldprevdeposit' => '0',
                'flditemamt' => $txn_amount,
                'fldtaxamt' => 0,

                'flddiscountgroup' => 'General',
                'fldchargedamt' => $grandTotal,
                'fldreceivedamt' => $grandTotal,
                'fldcurdeposit' => '0',
                'fldbilltype' => $appointmentMode,
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldbill' => 'INVOICE',
                'fldsave' => '1',
                'xyz' => '0',
                //'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $formatedEncId)->first();
            $patDetailsData = \App\PatBillDetail::create($patbilldetail);
            $patDetailsData['location'] = $encounterData->fldcurrlocat;
            \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);

            MaternalisedService::insertMaternalisedFiscal($formatedEncId, $billNumberGeneratedString, 'cash');



            \App\Consult::insert([
                'fldencounterval' => $formatedEncId,
                'fldconsultname' => $consultant,
                'fldconsulttime' => $appointmentDate,
                'fldcomment' => NULL,
                'fldstatus' => 'Planned',
                'flduserid' => $consultant,
                'fldbillingmode' => 'General',
                'fldorduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'xyz' => '0',
                'fldcategory' => NULL,
                'fldbillno' => $billNumberGeneratedString,

                //'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            $appointment = [
                'fldpatientval' => $patientId,
                'fldencounterval' => $formatedEncId,
                'product_code' => $product_code,
                'esewa_id' => $esewa_id,
                'is_refund' => $is_refund,
                'hospitalName' => $hospitalName,
                'appointmentId' => $appointmentId,
                'appointmentDate' => $appointmentDate,
                'appointmentMode' => $appointmentMode,
                'transactionNumber' => $txn_id,
                'refundAmount' => $refund_amount,
                'fldregdate' => $datetime,
                'appointmentNo' => $appointmentNo
            ];

            Eappointment::insert($appointment);
            session(['appointment_encounter_id' => $formatedEncId]);
            session(['appointment_patient_id' => $patientId]);

            \DB::commit();

            return response()->json([
                'responseMessage' => 'Patient added successfully',
                'responseData' => $patientId,
                'statusCode' => 200
            ], 200);
        } catch (\Exception $e) {
            $file =  base_path().'\\public\\'.'errpl.txt';
            // Open the file to get existing content
            $current = file_get_contents($file);
            // Append a new person to the file

            // Write the contents back to the file
            file_put_contents($file, $e);

            \DB::rollBack();

            return response()->json([
                'responseMessage' => $e->getMessage(),
                'statusCode' => 404
            ], 404);
        }
    }

    protected function getLogAppointment($emrurl, $page, $size, $filter)
    {

        try {
            // Need to log request data.
            $url = $emrurl . '?page=' . $page . '&size=' . $size;

            $args = json_encode([
                "appointmentCategory" => $filter['appointmentCategory'],
                "appointmentId" => $filter['appointmentId'],
                "appointmentNumber" => $filter['appointmentNumber'],
                "appointmentServiceTypeCode" => $filter['appointmentServiceTypeCode'],
                "doctorId" => $filter['doctorId'],
                "fromDate" => $filter['fromDate'],
                "hospitalDepartmentId" => $filter['hospitalDepartmentId'],
                "hospitalDepartmentRoomInfoId" => $filter['hospitalDepartmentRoomInfoId'],
                "patientMetaInfoId" => $filter['patientMetaInfoId'],
                "patientType" => $filter['patientType'],
                "specializationId" => $filter['specializationId'],
                "status" => $filter['status'],
                "toDate" => $filter['toDate'],
            ]);

            return $this->_apiCall($url, "PUT", $args);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    protected function getPendingAppointment($emrurl, $page, $size, $filter)
    {
        try {
            $url = $emrurl . '?page=' . $page . '&size=' . $size;

            $args = json_encode([
                "appointmentId" => $filter['appointmentId'] ?? null,
                "appointmentNumber" => $filter['appointmentNumber'] ?? null,
                "doctorId" => $filter['doctorId'] ?? null,
                "fromDate" =>  $filter['fromDate'],
                "patientMetaInfoId" =>  $filter['patientMetaInfoId'] ?? null,
                "patientType" =>  $filter['patientType'] ?? null,
                "specializationId" =>  $filter['specializationId'] ?? null,
                "toDate" =>   $filter['toDate']
            ]);

            return $this->_apiCall($url, "PUT", $args);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getDoctorHospitalwise()
    {
        try {
            $url = $this->eurl . 'doctor/hospital-wise';

            return $this->_apiCall($url, "GET");
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    protected function getSpecialization()
    {
        try {
            $url = $this->eurl . 'specialization/active/min';

            return $this->_apiCall($url, "GET");
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    protected function getDoctorActive()
    {
        try {
            $url = $this->eurl . 'doctor/active/min';

            return $this->_apiCall($url, "GET");
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    protected function getHospitalDepartment()
    {
        try {
            $url = $this->eurl . 'hospitalDepartment/min';

            return $this->_apiCall($url, "GET");
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    protected function getAppointmentServiceType()
    {
        try {
            $url = $this->eurl . 'hospital/appointmentServiceType';

            return $this->_apiCall($url, "GET");
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    protected function getPatientMetadatainfo()
    {
        try {
            $url = $this->eurl . 'patient/metaInfo/active/min';

            return $this->_apiCall($url, "GET");
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
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
        $url = $this->eurl . 'qualification/min';

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.list') . '">Users</a></li><li>Create New User</li>';
        $data['title'] = "Create User - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['side_nav'] = 'users';
        $data['side_sub_nav'] = 'users';
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();


        $data['department'] = Department::all();
        $method = 'GET';
        $data['specializations'] = $this->_apiCall($url,$method);
        $emrurl = $this->eurl . 'serviceInfo/min';
       
      
        $data['services'] = $this->_apiCall($emrurl, $method);
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
            $this->mapDoctorToEappointment($request);
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

    protected function mapDoctorToEappointment($request){
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
            $args = json_encode([
                "appointmentChargeDetails" => [
                    [
                    "serviceInfoChargeId" => $request->service
                   ]
                  ],
                "avatar" => "",
                "email" => $request->email,
                "genderCode" => $gender,
                "mobileNumber" => $request->phone,
                "name" => $request->name,
                "nmcNumber" => $request->nmc_number,
                "qualificationIds" => $request->qualification,
                "salutationIds" => [1],
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
                $request->session()->flash( 'success_message', 'Doctor Added' );
                return redirect()->back();
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return $e;
        }
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

}
