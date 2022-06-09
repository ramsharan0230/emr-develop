<?php

namespace Modules\Registration\Http\Controllers;

use App\Encounter;
use App\Family;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RankRegistrationController extends Controller
{

    public function index(Request $request)
    {
//        dd($request->all());
        $form_errors = [];
        if ($request->isMethod('post')) {
            $validation = $this->_validateData($request->all());
            if ($validation['status']) {
                if ($encounter_id = $this->_savePatient($request)) {
                    if ($request->get('bill') && Options::get('convergent_payment_status') && Options::get('convergent_payment_status') == 'active') {
                        return redirect()->route('convergent.payments', $encounter_id);
//                        session(['billing_encounter_id' => $encounter_id]);
//                        return redirect()->route('billing.display.form');
                    } else
                        return redirect()->route('registrationform.list');
//                    return  redirect()->back();
                }

                $request->session()->flash('error_message', 'Something went wrong. PLease tyr again.');
            } else
                $form_errors = $validation['errors'];
        }

        $data = [
            'addresses' => $this->_getAllAddress(),
            'billingModes' => Helpers::getBillingModes(),
            'countries' => Helpers::getCountries(),
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
            'form_errors' => $form_errors
        ];
        return view('registration::rankRegistration.index', $data);
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

    public function getPatientDetails(Request $request, $id)
    {
        if (!$id || $id == null) {
            return false;
        }

        $registration_type = $request->get('registration_type');
        if ($registration_type == 'family') {

            //family bata nikalne
            return Family::where('fldptcode', $id)->where('fldstatus', 'Active')->with('district')->first();

        } elseif ($registration_type == 'regular') {

            //Staff bata nikalne (1st time aayo bhane tblstaff herne) natra 2nd time ho bhane tblpatient
            $staff = \App\StaffList::where('fldptcode', $id)->first(); // if yes
            $patient = PatientInfo::where('fldptcode', $id)->first(); // if yes

            //if exist in both then 2nd time ho
            if ($staff && $patient) {
                return PatientInfo::where('fldptcode', $id)->with('district')->first();
            }
            return \App\StaffList::where('fldptcode', $id)->with('district')->first();
        } else {
            //natra patientifor nikalne
            return \App\PatientInfo::where('fldpatientval', $id)->with('municipality', 'latestEncounter', 'latestImage')->first();
        }


    }

    public function getMunicipalities(Request $request, $id)
    {
        return \App\Municipal::where('flddistrict', $id)->get();
    }

    public function getDistricts(Request $request, $id)
    {
        return \App\Municipal::select('flddistrict')->where('fldprovince', $id)->groupBy('flddistrict')->get();
    }

    public function getProvinces(Request $request)
    {
        return \App\Municipal::select('fldprovince')->groupBy('fldprovince')->get();
    }

    public function getRegistrationCost(Request $request)
    {
        $is_followup = $request->get('is_follow');
        if ($is_followup == 'Followup') {
            $costData = \DB::select("SELECT sc.flditemcost,ag.flditemqty FROM tblautogroup ag
			LEFT JOIN tblservicecost sc ON sc.flditemname = ag.flditemname AND sc.flditemtype = ag.flditemtype
			WHERE ag.fldgroup=?
			AND ag.fldbillingmode=?", [
                $request->get('department'), $is_followup
            ]);
        } else {
            $costData = \DB::select("SELECT sc.flditemcost,ag.flditemqty FROM tblautogroup ag
			LEFT JOIN tblservicecost sc ON sc.flditemname = ag.flditemname AND sc.flditemtype = ag.flditemtype
			WHERE ag.fldgroup=?
			AND ag.fldbillingmode=?", [
                $request->get('department'), $request->get('type')
            ]);
        }


        $cost = 0;
        if ($costData) {
            $costData = $costData[0];
            $cost = $costData->flditemcost * $costData->flditemqty;
        }
        return $cost;
    }

    public function getDepatrmentUser(Request $request)
    {
        $department = $request->get('department');
        return \App\CogentUsers::where('fldopconsult', 1)->whereHas('department', function ($q) use ($department) {
            $q->where('flddept', $department);
        })->get();
    }

    public function getDepartments(Request $request)
    {
        dd($request->all());
        $department = $request->get('department');
        return response()->json(Helpers::getDepartmentByCategory($department));
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to delete data.'
            ];
        }
    }

    private function _validateData($form_data)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($form_data, [
            'title' => 'required',
            'first_name' => 'required',
            'email' => 'nullable|email',
            'claim_code' => 'required_with:insurance_type',
            'nhsi_id' => 'required_with:insurance_type',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->getMessageBag()->messages() as $key => $value)
                $errors[$key] = $value[0];

            return [
                'status' => FALSE,
                'errors' => $errors,
            ];
        }

        return [
            'status' => TRUE
        ];
    }

    public function _savePatient($request)
    {
        $datetime = date('Y-m-d H:i:s');
        $time = date('H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = Helpers::getCompName();
        $fiscalYear = Helpers::getNepaliFiscalYearRange();
        $startdate = Helpers::dateNepToEng($fiscalYear['startdate'])->full_date . ' 00:00:00';
        $enddate = Helpers::dateNepToEng($fiscalYear['enddate'])->full_date . '23:59:59';

        $dob = $request->get('dob');
        if ($dob)
            $dob = Helpers::dateNepToEng($request->get('dob'))->full_date . ' ' . $time;
        $formatData = \App\Patsubs::first();
        if (!$formatData)
            $formatData = new \App\Patsubs();

        $followupDate = $request->get('followup_date');
        $followupDate = ($request->get('is_follow_up') && $followupDate) ? Helpers::dateNepToEng($followupDate)->full_date : NULL;
        $billingMode = $request->get('billing_mode');
        $fldptadmindate = Helpers::dateNepToEng($request->get('date'))->full_date . ' ' . $time;

        $claim_code = $request->get('claim_code');
        $insurance_type = $request->get('insurance_type');
        $claim = \App\Claim::where([
            'claim_code' => $claim_code,
            'has_used' => FALSE,
        ])->whereHas('insurancetype', function ($query) use ($insurance_type) {
            $query->where('id', $insurance_type);
        })->first();
        $claim_code = ($claim) ? $claim_code : '';

        \DB::beginTransaction();
        try {
            $encounterID = Helpers::getNextAutoId('EncounterID', TRUE);
            if (Options::get('reg_seperate_num') == 'Yes' && !empty($request->get('department_seperate_num'))) {
                
                $today_date = \Carbon\Carbon::now()->format('Y-m-d');
                $current_fiscalyr = \App\Year::select('fldname')->where([
                    ['fldfirst','<=',$today_date],
                    ['fldlast','>=',$today_date],
                ])->first();
                $current_fiscalyr = ($current_fiscalyr) ? $current_fiscalyr->fldname : '';

                $formatedEncId = $request->get('department_seperate_num') . $current_fiscalyr . '-' . $formatData->fldencid . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            } else {
                $formatedEncId = $formatData->fldencid . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            }

            $patientId = $request->get('patient_no');
            $dbPatientDetail = \App\PatientInfo::where('fldpatientval', $patientId)->first();
            if($dbPatientDetail)
            {
                $data = [
                    'fldrank' => $request->get('registration_rank') ?? null,
                    'fldunit' => $request->get('registration-unit') ?? null,
                ];
                Encounter::where('fldpatientval',$dbPatientDetail->fldpatientval)->update($data);
            }

            if (!$dbPatientDetail) {

                $registration_typ = $request->get('registration_type');
                if($registration_typ=='family')
                {
                    $patientId = Helpers::getNextAutoId('PatientNoFamily', TRUE);
                }
                elseif ($registration_typ=='other')
                {
                    $patientId = Helpers::getNextAutoId('CivilPatientNo', TRUE);
                }else{
                    $patientId = Helpers::getNextAutoId('PatientNo', TRUE);
                }
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $hosptal_no = $this->getHospitalNo($patientId, $formatData);
                $patientId = $hosptal_no;

                PatientInfo::insert([
                    'fldpatientval' => $patientId,
                    'fldptnamefir' => $first_name,
                    'fldptnamelast' => $last_name,
                    'fldptsex' => $request->get('gender'),
                    'fldptaddvill' => $request->get('tole'),
                    'fldptadddist' => $request->get('district'),
                    'fldptcontact' => $request->get('contact'),
                    'fldptguardian' =>$request->get('guardian'),
                    'fldrelation' => $request->get('relation'),
                    'fldptbirday' => $dob,
                    'fldptadmindate' => $fldptadmindate,
                    'fldemail' => $request->get('email'),
                    'flddiscount' => $request->get('discount_scheme'),
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'fldrank' => $request->get('registration_rank') ?? null,
                    'fldunit' => $request->get('registration-unit') ?? null,
                    'xyz' => '0',

//                    'fldpatientval' => $hosptal_no,
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
                    'fldcitizenshipno' => $request->get('citizenship_no'),
                    'fldbloodgroup' => $request->get('blood_group'),

                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);

                // patient credential
                $username = "{$first_name}.{$last_name}";
                $username = strtolower($username);
                $username = Helpers::getUniquePatientUsetname($username);

                \App\PatientCredential::insert([
                    'fldpatientval' => $patientId,
                    'fldusername' => $username,
                    'fldpassword' => Helpers::encodePassword($username),
                    'fldstatus' => 'Active',
                    'fldconsultant' => $request->get('consultant'),
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'fldcomp' => $computer,
                    'xyz' => '0',
                ]);

                $image = $request->image;
                if (isset($image)) {
                    $image = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image));

                    \App\PersonImage::insert([
                        'fldcateg' => 'Patient Image',
                        'fldname' => $patientId,
                        'fldpic' => $image,
                        'fldlink' => NULL,
                        'flduserid' => $userid,
                        'fldtime' => $datetime,
                        'fldcomp' => $computer,
                        'fldsave' => 1,

                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ]);
                }
            }

            $fldvisit = \App\Encounter::where('fldpatientval', $patientId)->whereBetween('fldregdate', [$startdate, $enddate])->count() > 0 ? 'OLD' : 'NEW';


            \App\Encounter::insert([
                'fldencounterval' => $formatedEncId,
                'fldpatientval' => $patientId,
                'fldadmitlocat' => '',
                'fldcurrlocat' => $request->get('department'),
                'flddoa' => $datetime,
                'flddisctype' => $request->get('discount_scheme'),
                'fldcashcredit' => '0',
                'fldadmission' => 'Registered',
                'fldfollowdate' => $followupDate,
                // 'fldreferto' => $request->get(''),
                'fldregdate' => $datetime,
                'fldbillingmode' => $billingMode,
                'fldcomp' => $computer,
                'fldvisit' => $fldvisit,
                'xyz' => '0',
                'fldinside' => '0',
                'fldrank' => $request->get('registration_rank') ?? null,
                'fldunit' => $request->get('registration-unit') ?? null,

                'fldclaimcode' => $claim_code,

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            \App\Consult::insert([
                'fldencounterval' => $formatedEncId,
                'fldconsultname' => $request->get('consultant'),
                'fldconsulttime' => $fldptadmindate,
                'fldcomment' => NULL,
                'fldstatus' => 'Planned',
                'flduserid' => NULL,
                'fldbillingmode' => $billingMode,
                'fldorduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'xyz' => '0',
                'fldcategory' => NULL,

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            $autogroup = \App\Autogroup::where([
                'fldgroup' => $request->get('department'),
                'fldbillingmode' => $request->get('is_follow_up') ? 'Followup' : 'General', // thsis change
            ])->first();
            if ($autogroup) {
                $servicecost = \App\ServiceCost::where([
                    'flditemname' => $autogroup->flditemname,
                    'flditemtype' => $autogroup->flditemtype,
                ])->first();
                if ($servicecost) {
                    \App\PatBilling::insert([
                        'fldencounterval' => $formatedEncId,
                        'fldbillingmode' => $billingMode,
                        'flditemtype' => $servicecost->flditemtype,
                        // 'flditemno' => $bill->flditemno,
                        'flditemname' => $servicecost->flditemname,
                        'flditemrate' => $servicecost->flditemcost,
                        'flditemqty' => $autogroup->flditemqty,
                        // 'fldtaxper' => $bill->fldtaxper,
                        // 'fldtaxamt' => $bill->fldtaxamt,
                        'flddiscper' => $request->get('flddiscper'),
                        'fldreason' => $request->get('price_remarks'),
                        'flddiscamt' => $request->get('flddiscamt'),
                        'fldditemamt' => $request->get('amount'),
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
                        'fldstatus' => 'Punched',
                        'fldsample' => 'Waiting',
                        'xyz' => '0',

                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ]);
                    $patbilldetail = [
                        'fldencounterval' => $formatedEncId,
                        // 'fldbillno' =>
                        'fldprevdeposit' => '0',
                        'flditemamt' => $servicecost->flditemcost,
                        // 'fldtaxamt' =>
                        // 'fldtaxgroup' =>
                        'flddiscountamt' => $request->get('flddiscper'),
                        'flddiscountgroup' => $request->get('discount_scheme'),
                        'fldchargedamt' => $servicecost->flditemcost,
                        'fldreceivedamt' => '0',
                        'fldcurdeposit' => '0',
                        'fldbilltype' => 'Cash',
                        // 'fldchequeno' =>
                        // 'fldbankname' =>
                        'flduserid' => $userid,
                        'fldtime' => $datetime,
                        'fldcomp' => $computer,
                        'fldsave' => '1',
                        // 'fldhostmac' =>
                        'xyz' => '0',
                        // 'fldpayitemname' =>

                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];

                    $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $formatedEncId)->first();
                    $patDetailsData = \App\PatBillDetail::create($patbilldetail);
                    $patDetailsData['location'] = $encounterData->fldcurrlocat;
                    \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);

                }
            }
            // $this->_sendPatientCredential($patientId, $request->get('bill'));

            \DB::commit();
        } catch (\Exception $e) {
            dd($e);
            \DB::rollBack();
            return FALSE;
        }
        return $formatedEncId;
    }

    private function getHospitalNo($patientId, $formatData)
    {
        //Hospital No generate by Anish
        $visit = Encounter::select('fldvisitcount')->where('fldpatientval', $patientId)->first();
        $hospital_no = '';
        if ($formatData && $patientId) {
            $prefix = $formatData->fldpatno;
            $hospital_no = $prefix . $patientId;
        }
        if ($visit) {
            $hospital_no .= '-' . ($visit->fldvisitcount + 1);
        } else {
            $hospital_no .= '-' . '1';
        }
        return $hospital_no;

    }
}
