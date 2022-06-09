<?php

namespace Modules\Registration\Http\Controllers;

use App\Autogroup;
use App\AutogroupDoctor;
use App\CogentUsers;
use App\Consult;
use App\Discount;
use App\Encounter;
use App\PatBillCount;
use App\PatBilling;
use App\PatBillingShare;
use App\PatientInfo;
use App\undoDischargeLog;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\MaternalisedService;
use App\Utils\Permission;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use PhpParser\ErrorHandler\Collecting;

class RegistrationController extends Controller
{
    public function getDepartments(Request $request)
    {
        $department = $request->get('department');
        return response()->json(Helpers::getDepartmentByCategory($department));
    }

    public function index(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'registration', 'registration-create'  ])  ) ?
            abort(403) : true ;
        $data = null;
        try {
            $form_errors = [];
            if ($request->isMethod('post')) {
                $validation = $this->_validateData($request->all());
                if ($validation['status']) {


                    $retData = $this->_savePatient($request);

                    // if($retData == 'No Claim Code Found!'){
                    //     $request->session()->flash('error_message', 'No Claim Code Found!');
                    //     return view('registration::index', $data);

                    // }else
                    if ($retData) {
                        $patient_id = $retData['patient_id'];
                        $encounter_id = $retData['encounter_id'];
                        $with = [
                            'reg_patient_id' => $patient_id,
                            'reg_encounter_id' => $encounter_id,
                        ];
                        if (Options::get('register_bill') && $retData['billno'])
                            $with['billno'] = $retData['billno'];

                        return redirect()->route('registrationform')->with($with);
                    }

                    $request->session()->flash('error_message', 'Something went wrong. Please try again.');
                } else {
                    $form_errors = $validation['errors'] ?? [];
                }
            }

            $data = [
                'addresses' => $this->_getAllAddress(),
                'billingModes' => Helpers::getBillingModes(),
                'countries' => Helpers::getCountries(),
                'districts' => \App\Municipal::select("flddistrict", "fldprovince")->groupBy("flddistrict")->orderBy("flddistrict")->get(),
                'discounts' => Helpers::getDiscounts(),
                'departments' => Helpers::getDepartmentByCategory('Consultation'),
                'ethinicGroups' => Helpers::getEthinicGroups(),
                'relations' => Helpers::getRelations(),
                'genders' => Helpers::getGenders(),
                'insurances' => Helpers::getInsurances(),
                'bloodGroups' => Helpers::getBloodGroups(),
                'surnames' => Helpers::getSurnames(),
                'todaydate' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
                'payables' => \App\User::select('flduserid', 'fldusername')->where('fldpayable', 1)->get(),
                'referals' => \App\User::select('flduserid', 'fldusername')->where('fldreferral', 1)->get(),
                'form_errors' => $form_errors,
                'banks' => \App\Banks::all(),
                'maritalStatus' => ['Single', 'Married', 'Widowed', 'Divorced', 'Separated',],
            ];
        } catch (\Exception $e) {
            dd($e);
        }

        return view('registration::index', $data);
    }

    private function _getAllAddress($encode = TRUE)
    {
        $all_data = \App\Municipal::all();
        $addresses = [];
        foreach ($all_data as $data) {
            $fldprovince = $data->fldprovince;
            $flddistrict = $data->flddistrict;
            $fldpality = $data->fldpality;
            if (!isset($addresses[$fldprovince])) {
                $addresses[$fldprovince] = [
                    'fldprovince' => $fldprovince,
                    'districts' => [],
                ];
            }

            if (!isset($addresses[$fldprovince]['districts'][$flddistrict])) {
                $addresses[$fldprovince]['districts'][$flddistrict] = [
                    'flddistrict' => $flddistrict,
                    'municipalities' => [],
                ];
            }

            $addresses[$fldprovince]['districts'][$flddistrict]['municipalities'][] = $fldpality;
        }

        if ($encode)
            return json_encode($addresses);

        return $addresses;
    }

    public function list(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'registration-list', 'registration-list-view'  ])  ) ?
            abort(403) : true ;
        $departments = Helpers::getDepartments();
        $patient_id = NULL;
        $patients = \App\Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid', 'fldregdate', 'created_by')->with([
            'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
            'patientInfo.credential:fldpatientval,fldusername,fldpassword',
            'allConsultant:fldencounterval,fldconsultname,flduserid',
            // 'consultant.userRefer:flduserid,firstname,middlename,lastname'
        ])->whereHas('patientInfo')->orderBy('fldregdate', 'DESC');

        if ($request->get('name')) {
            $ename = $request->get('name');
            $patients = $patients->whereHas('patientInfo', function ($q) use ($ename) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $ename . '%');
            });
        }
        if ($request->get('from_date')) {
            $from_date = $request->get('from_date');
            $from_date = Helpers::dateNepToEng($from_date)->full_date;
            $patients = $patients->where('fldregdate', '>=', $from_date . " 00:00:00");
        } else {
            $patients = $patients->where('fldregdate', '>=', date('Y-m-d') . " 00:00:00");
        }
        if ($request->get('to_date')) {
            $to_date = $request->get('to_date');
            $to_date = Helpers::dateNepToEng($to_date)->full_date;
            $patients = $patients->where('fldregdate', '<=', $to_date . " 23:59:59");
        } else {
            $patients = $patients->where('fldregdate', '<=', date('Y-m-d') . " 23:59:59");
        }
        if ($request->get('department')) {
            $patients = $patients->where('fldcurrlocat', $request->get('department'));
        }

        $patients = $patients->paginate(25);
        $pagination = $patients->appends(request()->all())->links();
        $addresses = $this->_getAllAddress();
        $districts = \App\Municipal::select("flddistrict", "fldprovince")->groupBy("flddistrict")->orderBy("flddistrict")->get();

        return view('registration::list', compact('departments', 'patients', 'patient_id', 'addresses', 'districts','pagination'));
    }

    private function _validateData($form_data)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($form_data, [
            'billing_mode' => 'required',
            // 'department.*' => 'required',
            'discount_scheme' => 'required',
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
//            'year' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'email' => 'nullable|email',
            // 'claim_code' => 'required_with:insurance_type',
            // 'claim_code' =>
            // Rule::requiredIf(function () use ($form_data) {
            //     if (isset($form_data['insurance_type'])) {
            //         if ($form_data['insurance_type'] == 1 && strtolower($form_data['insurance_type']) == 'health insurance') {
            //             return true;
            //         }
            //     }
            //     return false;
            // }),
            // 'ssf_number' => [
            //     'unique:tblpatientinfo,ssf_number',
            //     Rule::requiredIf(function () use ($form_data) {
            //         if(isset($form_data['insurance_type'])) {
            //             return $form_data['insurance_type'] == 1;
            //         }
            //         return false;
            //     }),
            // ],
            'nhsi_id' => 'required_with:insurance_type',
            // 'nhsi_id' =>
            // Rule::requiredIf(function () use ($form_data) {
            //     if (isset($form_data['insurance_type'])) {
            //         if ($form_data['insurance_type'] == 1 && strtolower($form_data['insurance_type']) == 'health insurance') {
            //             return true;
            //         }
            //     }
            //     return false;
            // }),
            'contact' => 'nullable|min:10|max:10',
            'country' => 'required',
            //            'province' => 'required_if:country,==,Nepal',
            'district' => 'required_if:country,==,Nepal',
            //            'municipality' => 'required_if:country,==,Nepal',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->getMessageBag()->messages() as $key => $value) {
                $errors[$key] = $value[0];
            }

            return [
                'status' => FALSE,
                'errors' => $errors,
            ];
        } else {
            return [
                'status' => TRUE
            ];
        }
    }

    public function _savePatient($request)
    {
        $datetime = date('Y-m-d H:i:s');
        $time = date('H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = Helpers::getCompName();
        $fiscalYear = Helpers::getNepaliFiscalYearRange();
        $startdate = Helpers::dateNepToEng($fiscalYear['startdate'])->full_date . ' 00:00:00';
        $enddate = Helpers::dateNepToEng($fiscalYear['enddate'])->full_date . ' 23:59:59';

        $dob = $request->get('dob');

//        if(isset($request->date_hour)){
//            if( $request->date_hour > 0 && $request->day>0)
//                $dob = $dob.' '.$request->date_hour.':00';
//        }

        // if ($dob)
        //     $dob = Helpers::dateNepToEng($request->get('dob'))->full_date . ' ' . $time;
        $formatData = \App\Patsubs::first();
        if (!$formatData)
            $formatData = new \App\Patsubs();

        $followupDate = $request->get('followup_date');
        $followupDate = ($request->get('is_follow_up') && $followupDate) ? Helpers::dateNepToEng($followupDate)->full_date : NULL;
        $billingMode = $request->get('billing_mode');
        $fldptadmindate = ($request->get('date')) ? Helpers::dateNepToEng($request->get('date'))->full_date . ' ' . $time : NULL;

        $claim_code = $request->get('claim_code');
        $insurance_type = $request->get('insurance_type');

        if($insurance_type){
            $claim = \App\Claim::where([
                // 'claim_code' => $claim_code,
                'has_used' => FALSE,
            ])->whereHas('insurancetype', function ($query) use ($insurance_type) {
                $query->where('id', $insurance_type);
            })->pluck('claim_code')->first();
            $claim_code = ($claim) ? $claim : '';

            if(is_Null($claim)){

                return false;

                session()->flash('error_message', 'No claim code found!');

                return redirect()->back();

            }
        }

        \DB::beginTransaction();
        try {
            $department_seperate_num = $request->get('department_seperate_num');
            $fldopip = 'OP';

            if (Options::get('reg_seperate_num') == 'Yes' && !empty($department_seperate_num)) {
                $today_date = \Carbon\Carbon::now()->format('Y-m-d');
                $current_fiscalyr = \App\Year::select('fldname')->where([
                    ['fldfirst', '<=', $today_date],
                    ['fldlast', '>=', $today_date],
                ])->first();
                $current_fiscalyr = ($current_fiscalyr) ? $current_fiscalyr->fldname : '';
                if ($department_seperate_num)
                    $formatedEncId = ($department_seperate_num == 'OPD') ? "OP" : $department_seperate_num;
                else
                    $formatedEncId = $formatData->fldencid;

                if ($department_seperate_num == 'IP') {
                    $fldopip = 'IP';
                    $encounterID = Helpers::getNextAutoId('IpEncounterID', TRUE);
                } elseif ($department_seperate_num == 'ER') {
                    $fldopip = 'IP';
                    $encounterID = Helpers::getNextAutoId('ErEncounterID', TRUE);
                } else {
                    $encounterID = Helpers::getNextAutoId('EncounterID', TRUE);
                }

                $formatedEncId .= $current_fiscalyr . '-' . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            } else {
                $encounterID = Helpers::getNextAutoId('EncounterID', TRUE);
                $formatedEncId = $formatData->fldencid . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            }

            $departments = $request->get('department') ? array_filter($request->get('department')) : [];
            $consultants = $request->get('consultant') ? array_filter($request->get('consultant')) : [];
            $consultantsId = $request->get('consultantid') ? array_filter($request->get('consultantid')) : [];
            $fldcurrlocat = !empty($departments) ? $departments[0] : '';
            $fldconsultant =  '';
            if(!empty($consultants) && count($consultants)){
                if(isset($consultants[0])){
                    $fldconsultant = $consultants[0] ;
                }else{
                    $fldconsultant =  '';
                }
            }
            // $fldconsultant = !empty($consultants) && count($consultants) > 0? $consultants[0] : '';

            $hospital_department_id = Helpers::getUserSelectedHospitalDepartmentIdSession();

            $patientId = $request->get('patient_no');
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');
            $dbPatientDetail = \App\PatientInfo::where('fldpatientval', $patientId)->first();
            $fldptcontact = implode(', ', array_filter([$request->get('contact'), $request->get('phone')]));
            if (!$dbPatientDetail) {
                $patientId = Helpers::getNextAutoId('PatientNo', TRUE);
                $title = $request->get('other_title') ?: $request->get('title');
                $patdata = [
                    'fldpatientval' => $patientId,
                    'fldptnamefir' => $first_name,
                    'fldptnamelast' => $last_name,
                    'fldptsex' => $request->get('gender'),
                    'fldptaddvill' => $request->get('tole'),
                    'fldptadddist' => $request->get('district'),
                    'fldptcontact' => $fldptcontact,
                    'fldptguardian' => $request->get('guardian'),
                    'fldrelation' => $request->get('relation'),
                    'fldptbirday' => $dob,
                    'fldptadmindate' => $fldptadmindate,
                    'fldemail' => $request->get('email'),
                    'flddiscount' => $request->get('discount_scheme'),
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'xyz' => '0',

                    'fldbookingid' => $request->get('booking_id'),
                    'fldnhsiid' => $request->get('nhsi_id'),
                    'fldtitle' => $request->get('title'),
                    'fldmidname' => $request->get('middle_name'),
                    'fldethnicgroup' => $request->get('ethnicgroup'),
                    'fldcountry' => $request->get('country'),
                    'fldprovince' => $request->get('province'),
                    'fldmunicipality' => $request->get('municipality'),
                    'fldwardno' => $request->get('wardno'),
                    'fldnationalid' => $request->get('national_id'),
                    'fldpannumber' => $request->get('pan_number'),
                    'fldcitizenshipno' => $request->get('citizenship_no') ?? "",
                    'fldbloodgroup' => $request->get('blood_group'),
                    'fldmaritalstatus' => $request->get('marital_status'),
                    'fldreligion' => $request->get('religion'),

                    'hospital_department_id' => $hospital_department_id,
                    'fldoldpatientid' => $request->get('fldoldpatientid'),
                    'ssf_number' => $request->get('ssf_number') ?? null
                ];
                $patinfo = PatientInfo::create($patdata);
                Helpers::logStack(["Patient created", "Event", ], ['current_data' => $patinfo]);
                // Helpers::logStack(["Patient created", "Pat-Info", $patientId], [$patinfo]);
                // patient credential
                $username = "{$first_name}.{$last_name}";
                $username = strtolower($username);
                $username = Helpers::getUniquePatientUsetname($username);
                $patcredata = [
                    'fldpatientval' => $patientId,
                    'fldusername' => $username,
                    'fldpassword' => Helpers::encodePassword($username),
                    'fldstatus' => 'Active',
                    'fldconsultant' => $fldconsultant,
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'fldcomp' => $computer,
                    'xyz' => '0',
                ];
                \App\PatientCredential::insert($patcredata);
                Helpers::logStack(["Patient credential created", "Event"], ['current_data' => $patcredata]);
                $this->_sendPatientCredential($patientId, $request->get('bill'));
            } else {
                $patdata = [
                    'fldptnamefir' => $first_name,
                    'fldptnamelast' => $last_name,
                    'fldptsex' => $request->get('gender'),
                    'fldptaddvill' => $request->get('tole'),
                    'fldptadddist' => $request->get('district'),
                    'fldptcontact' => $fldptcontact,
                    'fldptguardian' => $request->get('guardian'),
                    'fldrelation' => $request->get('relation'),
                    'fldptbirday' => $dob,
                    'fldemail' => $request->get('email'),
                    'flddiscount' => $request->get('discount_scheme'),
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'xyz' => '0',
                    'fldbookingid' => $request->get('booking_id'),
                    'fldnhsiid' => $request->get('nhsi_id'),
                    'fldtitle' => $request->get('title'),
                    'fldmidname' => $request->get('middle_name'),
                    'fldethnicgroup' => $request->get('ethnicgroup'),
                    'fldcountry' => $request->get('country'),
                    'fldprovince' => $request->get('province'),
                    'fldmunicipality' => $request->get('municipality'),
                    'fldwardno' => $request->get('wardno'),
                    'fldnationalid' => $request->get('national_id'),
                    'fldpannumber' => $request->get('pan_number'),
                    'fldcitizenshipno' => $request->get('citizenship_no') ?? "",
                    'fldbloodgroup' => $request->get('blood_group'),
                ];
                $previousData = PatientInfo::where('fldpatientval', $patientId)->first();
                PatientInfo::where('fldpatientval', $patientId)->update($patdata);
                Helpers::logStack(["Patient updated", "Event"], ['current_data' => $patdata, 'previous_data' => $previousData]);
            }

            $retData = [
                'encounter_id' => $formatedEncId,
                'patient_id' => $patientId
            ];

            $image = $request->image;
           
            if (isset($image)) {
                $logo = $request->file('image');

                $path = 'emr/patient/'.$patientId . '-' . rand() . time() . '.' . $logo->getClientOriginalExtension();
                // $request['logo'] = $path;
    
    try{
        Storage::disk('minio')->put($path, file_get_contents($logo));

    }catch(\Exception $e){
        Helpers::logStack([$e->getMessage() . ' in patient register image', "Error"]);
    }
                

                $image = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image));
                $personimage = [
                    'fldcateg' => 'Patient Image',
                    'fldname' => $patientId,
                    'fldpic' => $image,
                    'fldlink' => $path,
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'fldcomp' => $computer,
                    'fldsave' => 1,

                    'hospital_department_id' => $hospital_department_id
                ];
                \App\PersonImage::insert($personimage);
                Helpers::logStack(["Patient image created", "Event"], ['current_data' => $personimage]);
            }

            $fldvisit = \App\Encounter::where('fldpatientval', $patientId)->whereBetween('fldregdate', [$startdate, $enddate])->count() > 0 ? 'OLD' : 'NEW';
            $encounterData = [
                'fldencounterval' => $formatedEncId,
                'fldpatientval' => $patientId,
                'fldadmitlocat' => $fldcurrlocat,
                'fldcurrlocat' => $fldcurrlocat,
                'flddoa' => $datetime,
                'flddisctype' => $request->get('discount_scheme'),
                'fldcashcredit' => '0',
                'fldadmission' => 'Registered',
                'fldfollowdate' => $followupDate,
                'fldreferfrom' => $request->get('referal'),
                'fldregdate' => $datetime,
                'fldbillingmode' => $billingMode,
                'fldcomp' => $computer,
                'fldvisit' => $fldvisit,
                'xyz' => '0',
                'fldinside' => '0',
                'flduserid' => isset($consultants[0]) ? $consultants[0] : NULL,
                'fldclaimcode' => $claim_code,
                'created_by' => $userid,

                'hospital_department_id' => $hospital_department_id
            ];
            \App\Encounter::insert($encounterData);
            Helpers::logStack(["Patient encounter created", "Event"], ['current_data' => $encounterData]);

            if($insurance_type){
                \App\Claim::where(function($query) use ($claim_code, $insurance_type) {
                    $query->where('claim_code', $claim_code)
                        ->where('insurance_type_id', $insurance_type);
                })->update(['has_used' => 1]);


                $patientinsurancetype = [

                    'fldpatientval' => $patientId,
                    'fldencounterval' => $formatedEncId,
                    'fldinsurance_type' => $insurance_type,
                    'fldpatinsurance_id' => $request->get('nhsi_id'),
                    'fldallowedamt' => $request->get('nhsi_amount'),
                    'flduser' => $userid,
                    'created_at' => Carbon::now()
                ];

                \App\PatInsuranceDetails::insert($patientinsurancetype);
                Helpers::logStack(["Patient insurence details created", "Event"], ['current_data' => $patientinsurancetype]);

            }


            $retData['billno'] = "";
            $billNumberGeneratedString = "";
            if (Options::get('register_bill')) {
                // $is_followup = ($request->get('is_follow') == 'Followup') ? $request->get('is_follow') : $request->get('type');
                $is_followup = $request->get('billing_mode');
                $fldregtype = $request->get('fldregtype');
                $department = $request->get('department');
                $consultant = $request->get('consultant');
                $consultantid = $request->get('consultantid');
                $servicecosts = new Collection();
                foreach($department as $i=>$dep){
                    $grp = Autogroup::where([
                        ["fldbillingmode", $is_followup],
                        ["fldregtype", $fldregtype]])
                        ->where("fldgroup", $dep)->first();
                        if($grp){
                            if($grp->fldenabledept == 0 && !isset($consultantid[$i])){
                              continue;
                            }
            
                            if($grp->fldenabledept == 1){
                                $servicecosts = $servicecosts->concat($this->_getOldServiceCost($is_followup, $fldregtype, $dep));
                            }
            
                            if($grp->fldenabledept == 0 && isset($consultantid[$i])){
                                    $servicecosts = $servicecosts->concat($this->_getNewServiceCost($is_followup, $fldregtype, $dep,$consultantid[$i]));
                            }
                        }
            }

                // $servicecosts = $this->_getServiceCost($is_followup, $fldregtype, $departments);
                if ($servicecosts->isNotEmpty()) {
                    $payment_mode = $request->get('payment_mode');
                    $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
                    $dateToday = \Carbon\Carbon::now();
                    $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
                    if ($payment_mode == "Credit") {
                        $billNumberGeneratedString = "CRE-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                    } else {
                        $billNumberGeneratedString = "REG-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                    }


                    if (Options::get('reg_print_bill') != 'No')
                        $retData['billno'] = $billNumberGeneratedString;

                    $discountPercent = $request->get('flddiscper', 0);
                    $discountAmount = $request->get('flddiscamt', 0);
                    $grandTotal = 0;
                    $totalDiscount = 0;
                    $totalTax = 0;
                    $chargedAmount = 0;
                    foreach ($servicecosts as $servicecost) {

                        $discountPercent = $discountAmount = 0;
                        $amount = $servicecost->flditemcost;

                        $disCalcClass = new DiscountDataController();

                        $discountPercentCalculation = $disCalcClass->getDiscountPercentCalculate($request->get('discount_scheme'), $servicecost->flditemname);



                        if ($discountPercentCalculation) {
                            $discountPercent = ($discountPercentCalculation);
                            $discountAmount = ($discountPercentCalculation * $amount) / 100;
                        }
                        $amount -= $discountAmount;

                        $taxPercent = $servicecost->fldtaxper ?: 0;
                        $taxAmount = ($taxPercent * $amount) / 100;
                        $amount += $taxAmount;
                        $patbillingdata = [
                            'fldencounterval' => $formatedEncId,
                            'fldbillingmode' => $billingMode,
                            'flditemtype' => $servicecost->flditemtype,
                            'flditemno' => $servicecost->fldid,
                            'flditemname' => $servicecost->flditemname,
                            'flditemrate' => $servicecost->flditemcost,
                            'flditemqty' => $servicecost->flditemqty,
                            'fldtaxper' => $taxPercent,
                            'fldtaxamt' => Helpers::numberFormat($taxAmount,'insert'),
                            'fldreason' => $request->get('price_remarks'), //Added by Anish
                            'flddiscper' => $discountPercent,
                            'flddiscamt' => Helpers::numberFormat($discountAmount,'insert'),
                            'fldditemamt' => Helpers::numberFormat($amount,'insert'),
                            'fldpayto' => $request->get('payable'),
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
                            'hospital_department_id' => $hospital_department_id,
                            'fldopip' => $fldopip,
                            'discount_mode' => $request->get('discount_scheme'),
                        ];
                        $patBillingid = \App\PatBilling::insertGetId($patbillingdata);
                        Helpers::logStack(["Patient bill created", "Event"], ['current_data' => $patbillingdata]);

                        $dept = $servicecost->fldgroup;
                        $deptIndex = array_search($dept, $department);

                        $user_id = isset($consultantid[$deptIndex]) ? $consultantid[$deptIndex] : NULL;

                        if ($user_id) {

                            $category = json_decode($servicecost->category);
                            $userpay = \App\UserShare::select('flditemshare', 'category')->where([
                                'flditemname' => $servicecost->flditemname,
                                // 'category' => $servicecost->category,
                                'flditemtype' => $servicecost->flditemtype,
                            ]);
                            if (is_array($category) && count($category) > 0)
                                $userpay->whereIn('category', $category);
                            $userpay = $userpay->first();

                            $patbillshares = [];
                            $sharePercentage = 0;
                            $category = "OPD Consultation";
                            if ($userpay) {
                                $sharePercentage = $userpay->share;
                                // $category = $userpay->category;
                            } else if ($servicecost->other_share)
                                $sharePercentage = $servicecost->other_share;

                            if ($sharePercentage > 0) {
                                $shareamount = ($amount * $sharePercentage) / 100;
                                $patbillshares[] = [
                                    'pat_billing_id' => $patBillingid,
                                    'type' => $category,
                                    'total_amount' => Helpers::numberFormat($amount,'insert'),
                                    'hospitalshare' => $servicecost->hospital_share,
                                    'usersharepercent' => $sharePercentage,
                                    'user_id' => $user_id,
                                    'share' => Helpers::numberFormat($shareamount,'insert'),
                                    'created_at' => $datetime,
                                    'updated_at' => $datetime,
                                    'tax_amt' =>  Helpers::numberFormat(((15 / 100) * $shareamount),'insert'),
                                    'shareqty' => $servicecost->flditemqty,

                                ];
                            }

                            \App\PatBillingShare::insert($patbillshares);
                            Helpers::logStack(["Patient bill share created", "Event"], ['current_data' => $patbillshares]);
                        }
                        $grandTotal += Helpers::numberFormat($servicecost->flditemcost,'insert');
                        $totalDiscount += Helpers::numberFormat($discountAmount,'insert');
                        $totalTax += Helpers::numberFormat($taxAmount,'insert');
                        $chargedAmount += Helpers::numberFormat($amount,'insert');
                    }


                    $fldchargedamt = $grandTotal + $totalTax - $totalDiscount;

                    $patbilldetail = [
                        'fldencounterval' => $formatedEncId,
                        'fldbillno' => $billNumberGeneratedString,
                        'fldprevdeposit' => '0',
                        'flditemamt' => $grandTotal,
                        'fldtaxamt' => $totalTax,
                        'flddiscountamt' => $totalDiscount,
                        'flddiscountgroup' => $request->get('discount_scheme'),
                        'fldchargedamt' => abs($fldchargedamt),
                        'fldreceivedamt' => $payment_mode == "Credit" ? 0 : abs($fldchargedamt),
                        'fldcurdeposit' => $payment_mode == "Credit" ? ($fldchargedamt * (-1)): 0,
                        'fldbilltype' => ($payment_mode == 'Credit') ? 'Credit' : 'Cash',
                        'flduserid' => $userid,
                        'fldtime' => $datetime,
                        'fldcomp' => $computer,
                        'fldbill' => $payment_mode == "Credit" ? 'CREDIT INVOICE' : 'INVOICE',
                        'fldsave' => '1',
                        'xyz' => '0',
                        'hospital_department_id' => $hospital_department_id,
                        'payment_mode' => $payment_mode
                    ];

                    if ($payment_mode == "cheque") {
                        $patbilldetail['fldchequeno'] = $request->get('cheque_number');
                        $patbilldetail['fldbankname'] = $request->get('bank_name');
                    } elseif ($payment_mode == 'credit') {
                        $patbilldetail['fldcurdeposit'] = '-' . $fldchargedamt;
                    }

                    $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $formatedEncId)->first();
                    $patDetailsData = \App\PatBillDetail::create($patbilldetail);
                    $patDetailsData['location'] = $encounterData->fldcurrlocat;
                    \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);
                    $insertdata['fldbillno'] = $billNumberGeneratedString;
                    $insertdata['fldcount'] = 1;
                    PatBillCount::insert($insertdata);
                    Helpers::logStack(["Patient bill count created", "Event"], ['current_data' => $insertdata]);

                    MaternalisedService::insertMaternalisedFiscal($formatedEncId,$billNumberGeneratedString,$payment_mode);
                }
            }

            $insertConsultant = [];
            foreach ($departments as $deptIndex => $department) {
                $departmentConsultant = isset($consultants[$deptIndex]) ? $consultants[$deptIndex] : NULL;
                if(!$departmentConsultant && isset($consultantsId[$deptIndex])){
                    $departmentConsultant = CogentUsers::where('id',$consultantsId[$deptIndex])->first()->flduserid;
                }
                $insertConsultant[] = [
                    'fldencounterval' => $formatedEncId,
                    'fldconsultname' => $department,
                    'fldconsulttime' => $datetime,
                    'fldcomment' => NULL,
                    'fldstatus' => 'Planned',
                    'flduserid' => $departmentConsultant,
                    'fldbillingmode' => $billingMode,
                    'fldorduserid' => $userid,
                    'fldtime' => $datetime,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'xyz' => '0',
                    'fldcategory' => NULL,
                    'fldbillno' => $billNumberGeneratedString,
                    'hospital_department_id' => $hospital_department_id
                ];
            }
            $fonepaylogdata['fldencounterval'] = $formatedEncId;
            $fonepaylogdata['fldpatientval'] = $patientId;
            $fonepaylogdata['fldbillno'] = $billNumberGeneratedString;
            \App\Fonepaylog::where('id',$request->get('fonepaylog_id'))->update($fonepaylogdata);
            \App\Consult::insert($insertConsultant);
            Helpers::logStack(["Patient consult created", "Event"], ['current_data' => $insertConsultant]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            dd($e);
            Helpers::logStack([$e->getMessage() . ' in patient register', "Error"]);
            return FALSE;
        }
        return $retData;
    }

    private function _getServiceCost($is_followup, $fldregtype, $department)
    {
        return \App\Autogroup::select("tblservicecost.flditemcost", "tblautogroup.flditemtype", "tblservicecost.category", "tblautogroup.flditemqty", "tblautogroup.flditemname", "tblautogroup.fldgroup", "tblservicecost.fldid", "tblservicecost.other_share", "tbltaxgroup.fldtaxper")
            ->leftJoin("tblservicecost", function ($join) {
                $join->on("tblservicecost.flditemname", "=", "tblautogroup.flditemname")
                    ->on("tblservicecost.flditemtype", "=", "tblautogroup.flditemtype");
            })->leftJoin("tbltaxgroup", "tbltaxgroup.fldgroup", "=", "tblservicecost.fldcode")->where([
                ["tblautogroup.fldbillingmode", $is_followup],
                ["tblservicecost.fldstatus", "Active"],
                ["tblautogroup.fldregtype", $fldregtype],
            ])->where('fldenabledept',1)->whereIn("tblautogroup.fldgroup", $department)->get();
    }

    private function _getOldServiceCost($is_followup, $fldregtype, $departments)
    {
        return \App\Autogroup::select("tblservicecost.flditemcost", "tblautogroup.flditemtype", "tblservicecost.category", "tblautogroup.flditemqty", "tblautogroup.flditemname", "tblautogroup.fldgroup", "tblservicecost.fldid", "tblservicecost.other_share", "tbltaxgroup.fldtaxper")
            ->leftJoin("tblservicecost", function ($join) {
                $join->on("tblservicecost.flditemname", "=", "tblautogroup.flditemname")
                    ->on("tblservicecost.flditemtype", "=", "tblautogroup.flditemtype");
            })->leftJoin("tbltaxgroup", "tbltaxgroup.fldgroup", "=", "tblservicecost.fldcode")->where([
                ["tblautogroup.fldbillingmode", $is_followup],
                ["tblservicecost.fldstatus", "Active"],
                ["tblautogroup.fldregtype", $fldregtype],
            ])->where('fldenabledept',1)->where("tblautogroup.fldgroup", $departments)->get();
    }

    private function _getNewServiceCost($is_followup, $fldregtype, $department,$consultant_id)
    {
        try{
            $user = CogentUsers::where('id',$consultant_id)->first();
            if($user){
                $user_id =$user->id;
                $auto_group_doctor =  \App\AutogroupDoctor::select("tblautogroupdoctor.fldid as groupdoctorid","tblservicecost.flditemcost", "tblautogroupdoctor.flditemtype", "tblservicecost.category", "tblautogroupdoctor.flditemqty", "tblautogroupdoctor.flditemname", "tblautogroupdoctor.fldgroup", "tblservicecost.fldid", "tblservicecost.other_share", "tbltaxgroup.fldtaxper")
                    ->leftJoin("tblservicecost", function ($join) {
                        $join->on("tblservicecost.flditemname", "=", "tblautogroupdoctor.flditemname")
                            ->on("tblservicecost.flditemtype", "=", "tblautogroupdoctor.flditemtype");
                    })->leftJoin("tbltaxgroup", "tbltaxgroup.fldgroup", "=", "tblservicecost.fldcode")->where([
                        ["tblautogroupdoctor.fldbillingmode", $is_followup],
                        ["tblservicecost.fldstatus", "Active"],
                        ["tblautogroupdoctor.fldregtype", $fldregtype],
                        ["tblautogroupdoctor.doctor_id", $user_id],
                    ])->where("tblautogroupdoctor.fldgroup", $department)->get();
    
                return $auto_group_doctor;
            }
        }catch( \Exception $e){
            return [
                'status' => FALSE,
                'message' => $e->getMessage()
            ];
        }

    }

    public function getRegistrationCost(Request $request)
    {
        try{
            $is_followup = $request->get('type');
            $fldregtype = $request->get('fldregtype');
            $departments = $request->get('department');
            $costData = new Collection();
                foreach($departments as $i=>$department){
                    $grp = Autogroup::where([
                        ["fldbillingmode", $is_followup],
                        ["fldregtype", $fldregtype]])
                        ->where("fldgroup", $department)->first();
                        if($grp){
                            if($grp->fldenabledept == 0 && !isset($request->consultant[$i])){
                              continue;
                            }
            
                            if($grp->fldenabledept == 1){
                                $costData = $costData->concat($this->_getOldServiceCost($is_followup, $fldregtype, $department));
                            }
            
                            if($grp->fldenabledept == 0 && isset($request->consultant[$i])){
                                $costData = $costData->concat($this->_getNewServiceCost($is_followup, $fldregtype, $department,$request->consultant[$i]));
                            }
                        }else{
                            $grp_doc = AutogroupDoctor::where([
                                ["fldbillingmode", $is_followup],
                                ["fldregtype", $fldregtype]])
                                ->where("fldgroup", $department)->first();
                                if($grp_doc){
                                    if(isset($request->consultant[$i])){
                                        $costData = $costData = $costData->concat($this->_getNewServiceCost($is_followup, $fldregtype, $department,$request->consultant[$i]));
                                    }
                                }
                }
            }
            // if($autogrp){
            //     if($autogrp->fldenabledept == 0 && !isset($request->consultant)){
            //         return [
            //             'status' => FALSE,
            //             'message' => 'No fld enable dept'
            //         ];
            //     }

            //     if($autogrp->fldenabledept == 1){
            //         $costData = $this->_getServiceCost($is_followup, $fldregtype, $department);
            //     }

            //     if($autogrp->fldenabledept == 0 && isset($request->consultant)){
            //         $costData = $this->_getNewServiceCost($is_followup, $fldregtype, $department,$request->consultant_id);
            //     }
            // }else{
            //     if(isset($request->consultant)){
            //         $costData = $this->_getNewServiceCost($is_followup, $fldregtype, $department,$request->consultant_id);
            //     }else{
            //         return [
            //             'status' => FALSE,
            //             'message' => 'NO consultant'
            //         ];
            //     }
            // }

            $followup = $this->_getFollowup($request);
            return compact('costData', 'followup');
        }catch( \Exception $e){
            return [
                'status' => FALSE,
                'message' => $e->getMessage()
            ];
        }
    }

    private function _sendPatientCredential($patientId, $bill = FALSE, $patientData = NULL)
    {
        $text_message = Options::get('text_messgae');
        if ($text_message) {
            $patientData = $patientData ?: \App\PatientCredential::select('fldpatientval', 'fldusername', 'fldpassword')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldemail,fldptcontact,fldrank')
                ->where('fldpatientval', $patientId)
                ->first();
            $patientInfo = $patientData->patientInfo ?: PatientInfo::select('fldpatientval', 'fldptnamefir', 'fldmidname', 'fldptnamelast', 'fldemail', 'fldptcontact', 'fldrank')
                ->where('fldpatientval', $patientId)->first();
            $name = $patientInfo->fldfullname;
            $email = $patientInfo->fldemail;
            $username = $patientData->fldusername;
            $systemname = isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '';

            $patient_credential_setting = Options::get('patient_credential_setting');
            if ($patient_credential_setting) {
                $password = Helpers::decodePassword($patientData->fldpassword);
                $text = strtr($text_message, [
                    '{$name}' => $name,
                    '{$username}' => $username,
                    '{$systemname}' => $systemname,
                    '{$password}' => $password,
                ]);

                if ($patient_credential_setting == 'Email' || $patient_credential_setting == 'Both') {
                    $emailData = [
                        'template_id' => 1,
                        'email' => $email,
                        'full_name' => $name
                    ];

                    $email = new \Modules\AdminEmailTemplate\Http\Controllers\AdminEmailTemplateController();
                    $email->sendEmail(NULL, $emailData);

                    if ($patientData->fldsent == FALSE) {
                        $patientData->fldsent = TRUE;
                        $patientData->save();
                    }
                }

                if ($patient_credential_setting == 'SMS' || $patient_credential_setting == 'Both') {
                    (new \Modules\AdminEmailTemplate\Http\Controllers\AdminSmsTemplateController())->sendSms([
                        'text' => $text,
                        'to' => $patientInfo->fldptcontact,
                    ]);

                    if ($patientData->fldsent == FALSE) {
                        $patientData->fldsent = TRUE;
                        $patientData->save();
                    }
                }
            }

            if ($bill) {
                if (Options::get('low_deposit_text_message')) {
                    $text = strtr(Options::get('low_deposit_text_message'), [
                        '{$name}' => $name,
                        '{$username}' => $username,
                        '{$systemname}' => $systemname,
                    ]);
                    (new \Modules\AdminEmailTemplate\Http\Controllers\AdminSmsTemplateController())->sendSms([
                        'text' => $text,
                        'to' => $patientInfo->fldptcontact,
                    ]);
                }
            }
        }
    }

    public function getProvinces(Request $request)
    {
        return \App\Municipal::select('fldprovince')->groupBy('fldprovince')->get();
    }

    public function getDistricts(Request $request, $id)
    {
        return \App\Municipal::select('flddistrict')->where('fldprovince', $id)->groupBy('flddistrict')->get();
    }

    public function getMunicipalities(Request $request, $id)
    {
        return \App\Municipal::where('flddistrict', $id)->get();
    }

    public function getPatientDetailByPatientId(Request $request)
    {
        $id = $request->get('patientId');
        $registration_type = $request->get('registration_type');


        return $this->_getPatientDetailByPatientId($id, $registration_type);
    }

    public function getOldPatientDetail(Request $request)
    {
        $oldPatientId = $request->get('oldPatientId');
        $appointmentNo = $request->get('appointmentNo');

        if ($oldPatientId) {
            $patient = \App\PatientInfo::where([
                'fldoldpatientid' => $oldPatientId,
            ])->first();

            if ($patient)
                return response()->json([
                    'type' => 'registred',
                    'patientInfo' => $this->_getPatientDetailByPatientId($patient->fldpatientval),
                ]);
            return response()->json([
                'type' => 'new',
                'patientInfo' => \App\PatientInfoOld::where(['fldpatientval' => $oldPatientId])->with('municipality')->first(),
            ]);
        } else if ($appointmentNo) {
            $patient = \App\Eappointment::where([
                'appointmentNo' => $appointmentNo,
            ])->first();

            if ($patient)
                return response()->json([
                    'type' => 'registred',
                    'patientInfo' => $this->_getPatientDetailByPatientId($patient->fldpatientval),
                ]);
        }
    }

    private function _getPatientDetailByPatientId($id, $registration_type = NULL)
    {
        if ($registration_type == 'family' || $registration_type == 'regular')
            return \App\StaffList::where('fldptcode', $id)->with('municipality')->first();
        else {
            $information = \App\PatientInfo::where('fldpatientval', $id)
                ->with([
                    'municipality',
                    'latestEncounter',
                    'latestEncounter.allConsultant' => function ($query) {
                        $query->where('fldtime', 'like', '%' . date('Y-m-d') . '%');
                    },
                    'latestImage'
                ])->first();

            $data = [];
            if ($information) {
                $data['patientDepartments'] = [];
                if ($information && $information->latestEncounter && $information->latestEncounter->allConsultant)
                    $data['patientDepartments'] = $information->latestEncounter->allConsultant->pluck('fldconsultname')->toArray();
                if ($information && $information->latestEncounter) {
                    $data['fldencounterval'] = $information->latestEncounter->fldencounterval;
                    $data['fldregdate'] = $information->latestEncounter->fldregdate;
                }

                $data['fldptbirday'] = $information->fldptbirday;
                $data['patientId'] = $information->fldpatientval;
                $data['fldpatientval'] = $information->fldpatientval;
                $data['fldptnamefir'] = $information->fldptnamefir;
                $data['fldptnamelast'] = $information->fldptnamelast;
                $data['fldptguardian'] = $information->fldptguardian;
                $data['fldptaddvill'] = $information->fldptaddvill;
                $data['fldptsex'] = $information->fldptsex;
                $data['fldptcontact'] = $information->fldptcontact;
                $data['fldcomment'] = $information->fldcomment;
                $data['fldptadddist'] = $information->fldptadddist;
                $data['fldemail'] = $information->fldemail;
                $data['fldrelation'] = $information->fldrelation;
                $data['fldbillingmode'] = $information->latestEncounter->fldbillingmode;
                $data['fldtitle'] = $information->fldtitle;
                $data['fldmidname'] = $information->fldmidname;
                $data['fldpannumber'] = $information->fldpannumber;
                $data['fldclaimcode'] = $information->fldclaimcode;
                $data['fldnationalid'] = $information->fldnationalid;
                $data['fldnhsiid'] = $information->fldnhsiid;
                $data['fldwardno'] = $information->fldwardno;
                $data['fldpic'] = (isset($information->latestImage) && !empty($information->latestImage)) ? $information->latestImage->fldpic : '';
                $data['fldcitizenshipno'] = $information->fldcitizenshipno;
                $data['fldfollowdate'] = $information->latestEncounter->fldfollowdate;
                $data['fldethnicgroup'] = $information->fldethnicgroup;
                $data['fldbloodgroup'] = $information->fldbloodgroup;
                $data['fldprovince'] = ($information->municipality) ? $information->municipality->fldprovince : '';
                $data['flddistrict'] = ($information->municipality) ? $information->municipality->flddistrict : '';
                $data['fldpality'] = ($information->municipality) ? $information->municipality->fldpality : '';
                $data['ssf_number'] = $information->ssf_number ?? '';
                $data['discount_scheme'] = $information->flddiscount ?? '';
                $data['fldmaritalstatus'] = $information->fldmaritalstatus ?? '';
                $data['remaining_credit_amount'] = Helpers::getCreditAmount($information->fldpatientval);
            }
            return $data;
        }
    }

    public function getDepatrmentUser(Request $request)
    {
        $department = $request->get('department');
        return \App\CogentUsers::where('fldopconsult', 1)->whereHas('department', function ($q) use ($department) {
            $q->where('flddept', $department);
        })->get();
    }

    function printcard($id)
    {
        $data['patient'] = PatientInfo::where('fldpatientval', $id)->first();
        $data['encounter'] = Encounter::where('fldpatientval', $id)->orderBy('fldregdate', 'DESC')->first();

        return PDF::loadView('registration::cardpdf', $data)->setPaper('a4')->stream('card.pdf');
    }

    public function getSurname(Request $request)
    {
        return Helpers::getSurnames();
    }

    public function addSurname(Request $request)
    {
        try {
            $flditem = $request->get('flditem');
            $id = \App\Surname::insertGetId([
                'flditem' => $flditem,
            ]);

            return [
                'status' => TRUE,
                'data' => compact('id', 'flditem'),
                'message' => 'Successfully saved data.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to save data.'
            ];
        }
    }

    public function deleteSurname(Request $request)
    {
        try {
            \App\Surname::where('fldid', $request->get('id'))->delete();

            return [
                'status' => TRUE,
                'message' => 'Successfully deleted data.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to delete data.'
            ];
        }
    }

    function printticket(Request $request, $id)
    {
        $where = ['fldpatientval' => $id];
        if ($request->get('fldencounterval'))
            $where = ['fldencounterval' => $request->get('fldencounterval')];

        $data['patient'] = \App\Encounter::with('patientInfo', 'patientInfo.credential:fldpatientval,fldusername,fldpassword', 'consultant')
            ->where($where)->first();
        $data['encounter'] = $encounter = Encounter::where($where)->orderBy('fldregdate', 'DESC')->first();
        $registrationCost = \App\PatBillDetail::where([
            ['fldencounterval', $encounter->fldencounterval],
            ['fldbillno', 'LIKE', "%REG%"],
        ])->first();
        $data['registrationCost'] = $registrationCost ? $registrationCost->fldreceivedamt : '0';
        // $data['registerby'] = User::where('flduserid', $patient->patientInfo->flduserid)->first();
        $customPaper = array(0, 0, 200.00, 320.00);

        $consult = Consult::select('fldconsultname', 'flduserid')
            ->with('user:flduserid,firstname,middlename,lastname,fldcategory')
            ->where('fldencounterval', $encounter->fldencounterval)
            ->get();
        if ($consult->isEmpty()) {
            $consult = Encounter::select('flduserid')
                ->with('user:flduserid,firstname,middlename,lastname,fldcategory')
                ->where('fldencounterval', $encounter->fldencounterval)
                ->get();
        }
        $doc = $consult->pluck('user.fldtitlefullname')->toArray();
        $data['doc'] = implode(', ', array_filter($doc));

        $dept = $consult->pluck('fldconsultname')->toArray();
        $data['dept'] = implode(', ', array_filter($dept));
        $data['dept'] = $data['dept'] ? $data['dept'] : $encounter->fldcurrlocat;

        return PDF::loadView('registration::ticket', $data)->setPaper('a4')->setPaper($customPaper, 'landscape')->stream('card.pdf');
    }
    function printnextticket(Request $request, $id)
    {
        $where = ['fldpatientval' => $id];
        if ($request->get('fldencounterval'))
            $where = ['fldencounterval' => $request->get('fldencounterval')];

        $data['patient'] = \App\Encounter::with('patientInfo', 'patientInfo.credential:fldpatientval,fldusername,fldpassword', 'consultant')
            ->where($where)->first();
        $data['encounter'] = $encounter = Encounter::where($where)->orderBy('fldregdate', 'DESC')->first();
        $registrationCost = \App\PatBillDetail::where([
            ['fldencounterval', $encounter->fldencounterval],
            ['fldbillno', 'LIKE', "%REG%"],
        ])->first();
        $data['registrationCost'] = $registrationCost ? $registrationCost->fldreceivedamt : '0';
        // $data['registerby'] = User::where('flduserid', $patient->patientInfo->flduserid)->first();
        $customPaper = array(0, 0, 200.00, 320.00);

        $consult = Consult::select('fldconsultname', 'flduserid')
            ->with('user:flduserid,firstname,middlename,lastname,fldcategory')
            ->where('fldencounterval', $encounter->fldencounterval)
            ->get();
        if ($consult->isEmpty()) {
            $consult = Encounter::select('flduserid')
                ->with('user:flduserid,firstname,middlename,lastname,fldcategory')
                ->where('fldencounterval', $encounter->fldencounterval)
                ->get();
        }
        $doc = $consult->pluck('user.fldtitlefullname')->toArray();
        $data['doc'] = implode(', ', array_filter($doc));

        $dept = $consult->pluck('fldconsultname')->toArray();
        $data['dept'] = implode(', ', array_filter($dept));
        $data['dept'] = $data['dept'] ? $data['dept'] : $encounter->fldcurrlocat;

        return PDF::loadView('registration::nextticket', $data)->setPaper('a4')->setPaper($customPaper, 'landscape')->stream('card.pdf');
    }

    function printband(Request $request, $id)
    {
        $encounterId = $request->get('encounterId');
        if ($encounterId) {
            $encounterDetail = \App\Encounter::where('fldencounterval', $encounterId)->first();
            $id = $encounterDetail->fldpatientval;
        }

        $data['patient'] = PatientInfo::with(
            'latestEncounter',
            'latestEncounter.consultant',
            'latestEncounter.departmentBed'
        )->where('fldpatientval', $id)->first();

        return PDF::loadView('registration::band', $data)->setPaper('a4')->stream('card.pdf');
    }

    function printBarCode($id)
    {
        $data['patient'] = PatientInfo::with(
            'latestEncounter',
            'latestEncounter.consultant',
            'latestEncounter.departmentBed'
        )->where('fldpatientval', $id)->first();

        return view('registration::barcode', $data);
    }

    function getDiscmode(Request $request)
    {
        $billingMode = $request->billingmode;
        $discountmode = Discount::where('fldbillingmode', $billingMode)->get();
        $html = '<option value="">--select--</option>';
        
        if (!empty($discountmode)) {
            
            if((strtolower($billingMode) == 'health insurance') || (strtolower($billingMode) == 'health insurance') || (strtolower($billingMode) == 'health insurance') ){

                foreach ($discountmode as $disc) {
                    if((strtolower($disc->fldtype) == 'health insurance') || (strtolower($disc->fldtype) == 'healthinsurance') || (strtolower($disc->fldtype) == 'hi')) {
                        $selected = 'selected="selected"';
                    }else{
                        $selected = '';
                    }
                    $html .= "<option value='" . $disc->fldtype . "' data-fldmode='" . $disc->fldmode . "' data-fldpercent='" . $disc->fldpercent . "' data-fldamount='" . $disc->fldamount . "' ".$selected.">" . $disc->fldtype . "</option>";
                }
            }else{
                $selected = '';
                foreach ($discountmode as $disc) {
                    // $html .= "<option value='" . $disc->fldtype . "' data-fldmode='" . $disc->fldmode . "' data-fldpercent='" . $disc->fldpercent . "' data-fldamount='" . $disc->fldamount . "'>" . $disc->fldtype . "</option>";
                    $html .= "<option value='" . $disc->fldtype . "' data-fldmode='" . $disc->fldmode . "' data-fldpercent='" . $disc->fldpercent . "' data-fldamount='" . $disc->fldamount . "' ".$selected.">" . $disc->fldtype . "</option>";
                }
            }
            
        }
        return json_encode($html);
    }


    public function _getFollowup($request)
    {
        $followupPatientType = Options::get('followup_patient_type');
        $followupdepartmenttype = Options::get('followup_department_type');
        $followupconsultant = Options::get('followup_consultant');
        $followuphours = Options::get('followup_days') * 24;
        $fldpatientval = $request->get('patientid');
        $followup = '0';

        $where = [];
        if ($followupPatientType == 'all' and $followupdepartmenttype == 'same')
            $where = [
                'e.fldpatientval' => $fldpatientval,
                // 'c.fldconsultname' => $request->get('department'),
            ];
        else if ($followupPatientType == 'same' and $followupdepartmenttype == 'all')
            $where = [
                'e.fldpatientval' => $fldpatientval,
                'c.fldbillingmode' => $request->get('type'),
            ];
        else if ($followupPatientType == 'same' and $followupdepartmenttype == 'same')
            $where = [
                'e.fldpatientval' => $fldpatientval,
                'c.fldbillingmode' => $request->get('type'),
                // 'c.fldconsultname' => $request->get('department'),
            ];
        else if ($followupPatientType == 'all' and $followupdepartmenttype == 'all') {
            $where = [];
            $followup = '1';
        }

        if ($where) {
            $patient = DB::table('tblconsult as c')
                ->select('c.fldencounterval', 'e.flddoa', 'e.fldfollowdate')
                ->join('tblencounter as e', 'e.fldencounterval', 'c.fldencounterval')
                ->where($where)
                ->whereIn('c.fldconsultname',$request->get('department'))
                ->get();
            $followups = array_filter($patient->pluck('fldfollowdate')->toArray());
            if ($followups)
                return $followup;

            if (count($patient) > 0) {
                $today = now();
                $admitdate = (isset($patient) and !empty($patient)) ? $patient[0]->flddoa : '';

                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $admitdate);
                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', now());

                $diffInHours = $to->diffInHours($from);
                if ($diffInHours < ($followuphours)) {
                    $followup = '1';
                }
            }
        }

        return $followup;
    }

    public function registrationCsv(Request $request)
    {
        $export = new \App\Exports\RegistrationExport(
            $request->name,
            $request->from_date,
            $request->to_date,
            $request->department
        );
        ob_end_clean();
        ob_start();
        return \Excel::download($export, 'RegistrationExport.xlsx');
    }

    public function registrationPdf(Request $request)
    {
        $department = $request->get('department');
        $name = $request->get('name');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $patients = \App\Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
                'patientInfo.credential:fldpatientval,fldusername,fldpassword',
                'consultant:fldencounterval,fldorduserid',
                'consultant.userRefer:flduserid,firstname,middlename,lastname'
            ])->where([
                ['fldregdate', '>=', "{$from_date} 00:00:00"],
                ['fldregdate', '<=', "{$to_date} 23:59:59"],
            ])
            ->orderBy('fldregdate', 'DESC');

        if ($name) {
            $name = $name;
            $patients = $patients->whereHas('patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });
        }

        if ($department)
            $patients = $patients->where('fldcurrlocat', $department);
        $patients = $patients->get();

        return view('registration::registration-pdf', compact('name', 'from_date', 'to_date', 'department', 'patients'));
    }

    public function idcard(Request $request)
    {
        $patientId = $request->get('patientId');
        $data['patientinfo'] = \App\PatientInfo::with('latestImage')->where('fldpatientval', $patientId)->first();
        return view('registration::idcard', $data);
    }

    public function UpdateConsultantList(Request $request)
    {
        if (!$request->get('edit-consult-patient')) {
            return redirect()->back();
        }
        $encounter = Encounter::select('fldregdate','fldencounterval','fldpatientval')->where('fldencounterval', $request->get('edit-consult-patient'))->first();

        if ($encounter) {
            $diff = Carbon::parse($encounter->fldregdate)->diffInHours(Carbon::now());
            if ($diff > 12) {
                Session::put(['edit_consult_message' => 'Patient has been registered for more than 12 hrs!']);
                return redirect()->back();
            }
        }


        $oldConsultants = Consult::select('fldid','fldbillno')->where('fldencounterval', $request->get('edit-consult-patient'))->get();
        try {
            $departments = $request->get('department') ? array_filter($request->get('department')) : [];
            $consultants = $request->get('consultant') ? array_filter($request->get('consultant')) : [];
            $consultantsId = $request->get('consultantid') ? array_filter($request->get('consultantid')) : [];
            if ($oldConsultants) {
                $billNumberGeneratedString = $oldConsultants[0]->fldbillno;
                Consult::whereIn('fldid',$oldConsultants->pluck('fldid')->toArray())->delete();
                foreach ($departments as $deptIndex => $department) {
                    $departmentConsultant = isset($consultants[$deptIndex]) ? $consultants[$deptIndex] : NULL;
                    if(!$departmentConsultant && isset($consultantsId[$deptIndex])){
                        $departmentConsultant = CogentUsers::where('id',$consultantsId[$deptIndex])->first()->flduserid;
                    }
                    $insertConsultant[] = [
                        'fldencounterval' => $request->get('edit-consult-patient'),
                        'fldconsultname' => $department,
                        'fldconsulttime' => Carbon::now(),
                        'fldcomment' => NULL,
                        'fldstatus' => 'Planned',
                        'flduserid' => $departmentConsultant,
                        'fldorduserid' => Helpers::getCurrentUserName(),
                        'fldtime' => Carbon::now(),
                        'fldcomp' => Helpers::getCompName(),
                        'fldsave' => '1',
                        'xyz' => '0',
                        'fldcategory' => NULL,
                        'fldbillno' => $billNumberGeneratedString,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                }
                \App\Consult::insert($insertConsultant);
                $doc = CogentUsers::select('id')->where('username',$departmentConsultant)->first();
                $regbill = PatBilling::where('fldencounterval', $request->get('edit-consult-patient'))
                    ->where('fldbillno', 'LIKE','%'.$billNumberGeneratedString.'%')->orderBy('fldid','DESC')->first();
                if ($regbill) {
                    // foreach ($regbill as $bill) {
                        $patbillshares = [
                            'user_id' => $doc->id,
                        ];
                        PatBillingShare::where('pat_billing_id',$regbill->fldid)->update($patbillshares);
                   // }
                }
                $loging = [
                    'fldencounterval' =>$encounter->fldencounterval,
                    'fldpatientval' =>$encounter->fldpatientval,
                    'flddate' => date('Y:m:d'),
                    'fldtime' => date('H:i:s'),
                    'flduserid' => Helpers::getCurrentUserName()

                ];
                undoDischargeLog::insert($loging);


                return redirect()->back();
            } else {
                return redirect()->back()->with('edit_consult_message', 'Encounter not found!');
            }
        } catch (\Exception $exception) {
            dd($exception);
            return redirect()->back()->with('edit_consult_message', 'Something Went Wrong!');
        }
    }


    public function getConsultantByEncounter(Request  $request)
    {

        if (!$request->encounterId) {
            return response()->json(['error' => 'Please check encounter']);
        }

        $encounter = Encounter::select('fldregdate')->where('fldencounterval', $request->encounterId)->first();
        if ($encounter) {
            $diff = Carbon::parse($encounter->fldregdate)->diffInHours(Carbon::now());
            if ($diff > 12) {
                return response()->json(['error' => 'Patient has been registered for more than 12 hrs!']);
            }
        }
        $consultantsList = Helpers::getConsultantList();
        $consultants = Consult::select('flduserid', 'fldconsultname')->where('fldencounterval', $request->encounterId)->get();
        $html = '';
        $departments = Helpers::getDepartments();
        if (isset($consultants) and count($consultants) > 0) {
            foreach ($consultants as $c) {
                $html .= '<div class="row">';
                $html .= '<div class="col-md-6">';
                $html .= '<td>';
                $html .= '<select name="department[]" class="form-control select2 js-registration-department" required>';
                $html .= '<option value="">--Select--</option>';
                foreach ($departments as $department) {
                    $html .= '<option value="' . $department->flddept . '" ' . (($department->flddept == $c->fldconsultname) ? 'selected' : '') . '>' .  $department->flddept . '</option>';
                }
                $html .= '</select>';
                $html .= '</td>';
                $html .= '</div>';

                $html .= '<div class="col-md-6">';
                $html .= '<td>';
                $html .= '<select name="consultant[]" class="form-control js-registration-consultant select2" required>';
                $html .= '<option value="">--Select--</option>';
                foreach ($consultantsList as $consult) {
                    $html .= '<option value="' . $consult->username . '" ' . (($consult->username == $c->flduserid) ? 'selected' : '') . '>' .  $consult->fldfullname . '</option>';
                }
                $html .= '</select>';
                $html .= '</td>';
                $html .= '</div>';
                $html .= '</div>';
            }
        }
        return  response()->json(['select' => $html]);
    }

    public function checkeligibility(Request $request){

        $patientid = $request->patientid;

        try {

            $request_body = [
                "resourceType" => "EligibilityRequest",
                "patient" => [
                    "reference" => "Patient/" . $patientid
                ]
            ];

            $request_body_json = json_encode($request_body);

            // dd($request_body_json);

            $url = Options::get('hi_settings')['hi_url'] ?? '';
            $username = Options::get('hi_settings')['hi_username'] ?? '';
            $password = Options::get('hi_settings')['hi_password'] ?? '';

            $urlel = $url . 'EligibilityRequest/';

            $remote_user = 'remote-user:' . Options::get('hi_settings')['hi_remote_user'] ?? '';

            // $client = new Client();

            // $options= array(
            //     'auth' => [
            //       $username,
            //       $password
            //     ],
            //     'headers'  => ['content-type' => 'application/json'],
            //     'body' => $request_body_json
            //   );

            // $result = $client->post($url, $options);

            // dd($client->post($url, $options));

            // $response = $Client->post(
            //     config($url),
            //     [
            //         'json' => $request_body
            //     ]
            // );

            //from here

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $urlel);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                $remote_user)
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS,$request_body_json);

            $result = curl_exec($ch);

            $responseBody = json_decode($result, true);

            // dd(count($responseBody));



            $ch = curl_init();

            $urlpat = $url . 'Patient/?identifier=' . $patientid;

            curl_setopt($ch, CURLOPT_URL, $urlpat);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                $remote_user)
            );

            $result = curl_exec($ch);

            $responsePatDetail = json_decode($result, true);

            // $valtest = includesMultiDimension($responseBody, 'insurance');
            // // (in_array('insurance',$responseBody));
            // dd($valtest);

            $oldpatient = \DB::table('tblpatientinfo')->where('fldnhsiid',$patientid)->first();


            if(is_array($responseBody) && count($responseBody) > 1 && isset($oldpatient->fldpatientval)){
                return ['response' => $responseBody,'responsePatDetail' => $responsePatDetail,'patientval' => $oldpatient->fldpatientval ];
            }elseif(is_array($responseBody) && count($responseBody) > 1) {
                return ['response' => $responseBody,'responsePatDetail' => $responsePatDetail,'patientval'=> ''];
            }else{
                return ['response' => 'error'];
            }


        } catch (\Exception $e) {
          dd($e);
        }

        return $responseBody;

    }

    public function previousRegistration(Request $request)
    {
        try {
            $previousIds = \App\PatientInfo::where([
                'fldptnamefir' => $request->get('firstname'),
                'fldmidname' => $request->get('middlename'),
                'fldptnamelast' => $request->get('lastname'),
                'fldptbirday' => $request->get('dob'),
                'fldptcontact' => $request->get('contact')
            ])->pluck('fldpatientval');
            return  response()->json($previousIds);
        } catch (\Exception $e) {
            return  response()->json([]);
        }
    }
}
