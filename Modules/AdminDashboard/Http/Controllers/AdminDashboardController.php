<?php

namespace Modules\AdminDashboard\Http\Controllers;

use App\BillingSet;
use App\Confinement;
use App\Consult;
use App\Delivery;
use App\Department;
use App\HospitalDepartment;
use App\Departmentbed;
use App\Eappointment;
use App\Encounter;
use App\PatBilling;
use App\PatBillingShare;
use App\PatientInfo;
use App\PatLabSubTest;
use App\PatLabTest;
use App\PatRadioTest;
use App\Services\UserService;
use App\Services\UserShareReportService;
use App\Test;
use App\Utils\Permission;
use App\Year;
use Cache;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use PHPUnit\Exception;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function newdashboard(Request $request)
    {
        $today_date = Carbon::now()->format('Y-m-d');
        $data = [];
        $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

        $data['normalCount'] = $data['abnormalCount'] = $data['totalPatientAdmitted'] = $data['totalPatientDischarged'] = $data['totalPatientDeath'] = 0;
        $data['totalNFollowPatient'] = $data['totalNOldPatient'] = $data['inpatientCount'] = $data['totalNewPatient'] = $data['todayRadioWaiting'] = $data['todayRadioReported'] = $data['todayRadioVerified'] = $data['todayLabWaiting'] = $data['todayLabSampled'] = $data['todayLabReported'] = $data['todayLabVerified'] = $data['normalCount'] = $data['abnormalCount'] = $data['outpatientCount'] = 0;

        $opd_permission = $data['opd_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("opd");
        $ipd_permission = $data['ipd_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("ipd");
        $lab_permission = $data['lab_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("lab_status");
        $radio_permission = $data['radio_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("radiology");
        $account_permission = $data['account_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("account");
        $emergency_permission = $data['emergency_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("emergency");
        $pharmacy_permission = $data['pharmacy_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("pharmacy");
        // $nutrition_permission = $data['nutrition_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("nutrition");
        $billing_permission = $data['billing_permission'] = \App\Utils\Helpers::getPermissionsByModuleName("billing");


        /*count of patient by billing mode*/
        if ($billing_permission) {
            $PatientByDepartment = $this->patientByBillingMode($request, $fiscal_year);
            $data += $PatientByDepartment;
        }
        /*end count of patient by billing mode*/

        /*count of patient by OPD*/
        if ($opd_permission) {
            $PatientByOPD = $this->getPatientByOPD($request, $fiscal_year);
            $data += $PatientByOPD;
        }
        /*end count of patient by OPD*/

        /*count of patient by IPD*/
        if ($ipd_permission) {
            $PatientByIPD = $this->getPatientByIPD($request, $fiscal_year);
            $PatientByBedOccupacy = $this->getBedOccupacyDetails($request, $fiscal_year);
            $data += $PatientByIPD + $PatientByBedOccupacy;
        }
        /*end count of patient by IPD*/

        /*count of patient by Emergency*/
        if ($emergency_permission) {
            $PatientByEmergency = $this->getPatientByEmergency($request, $fiscal_year);
            $data += $PatientByEmergency;
        }
        /*end count of patient by Emergency*/

        /*count of lab status*/
        if ($lab_permission) {
            $labStatus = $this->labStatus($request, $fiscal_year);
            $labOrder = $this->labOrderStatus($request, $fiscal_year);
            $lab = $this->laboratoryStatusCount($fiscal_year);
            $data['todayLabWaiting'] = $lab['Waiting'];
            $data['todayLabSampled'] = $lab['Sampled'];
            $data['todayLabReported'] = $lab['Reported'];
            $data['todayLabVerified'] = $lab['Verified'];

            $data += $labStatus + $labOrder;
        }
        /*end count of lab status*/

        /*count of radiology status*/
        if ($radio_permission) {
            $radiologyStatus = $this->radiologyStatus($request, $fiscal_year);
            $radiologyOrder = $this->radiologyOrderStatus($request, $fiscal_year);
            $radio = $this->radiologyStatusCount($fiscal_year);
            $data['todayRadioWaiting'] = $radio['Waiting'];
            $data['todayRadioCheckin'] = $radio['CheckIn'];
            $data['todayRadioReported'] = $radio['Reported'];
            $data['todayRadioVerified'] = $radio['Verified'];
            $data += $radiologyStatus + $radiologyOrder;
        }
        /*end count of radiology status*/

        /*count of pharmacy status*/
        if ($pharmacy_permission) {
            $pharmacyOpStatus = $this->opSales($request, $fiscal_year);
            $pharmacyIpStatus = $this->ipSales($request, $fiscal_year);
            $data += $pharmacyOpStatus + $pharmacyIpStatus;
        }
        /*end count of pharmacy status*/

        if ($lab_permission || $radio_permission || $opd_permission || $ipd_permission || $account_permission) {
            $data['totalNewPatient'] = $this->EncounterNewOld('NEW', $fiscal_year);
            $data['totalNOldPatient'] = $this->EncounterNewOld('OLD', $fiscal_year);
            $data['totalNFollowPatient'] = $this->EncounterNewOld('FOLLOWUP', $fiscal_year);

            //$data['totalNOnlinePatient'] = $this->OnlineWalkPatient('ONLINE', $fiscal_year);
            //$data['totalNWalkinPatient'] = $this->OnlineWalkPatient('WALKIN', $fiscal_year);
        }
        if ($ipd_permission || $emergency_permission) {
            $data['totalPatientAdmitted'] = $this->EncounterPatientAdmittedDischarged('Admitted', $fiscal_year);
            $data['totalPatientDischarged'] = $this->EncounterPatientAdmittedDischarged('Discharged', $fiscal_year);
            $data['totalPatientDeath'] = $this->EncounterPatientAdmittedDischarged('Death', $fiscal_year);
        }
        // if($opd_permission){
        //     $data['followupCount'] = $this->followupCount();
        // }

        if ($radio_permission) {
            $data['outpatientCount'] = $this->OutpatientInpatientCount('Outpatient', $fiscal_year);
            $data['inpatientCount'] = $this->OutpatientInpatientCount('Inpatient', $fiscal_year);
        }
        if ($lab_permission) {
            $data['abnormalCount'] = $this->outOfThreshold($request, 1);
            $data['normalCount'] = $this->outOfThreshold($request, 0);
        }

        $data['billingSet'] = Cache::remember('billing-set', 60 * 60 * 24, function () {
            return BillingSet::all();
        });

        return view('admindashboard::index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index()
    {
        $data['patient_info_permission'] = Permission::checkDashboardModulePermission(['Settings', 'User', 'Account Settings', 'Accounts', 'Item Master', 'Registration', 'Billing']);
        $data['operation_theater_permission'] = Permission::checkDashboardModulePermission(['Account Settings', 'Accounts', 'OT Management']);
        $data['delivery_permission'] = (Permission::checkDashboardModulePermission(['Account Settings', 'Accounts']) || Permission::checkPermissionFrontendAdmin("delivery-form")) ? true : false;
        $data['pharmacy_permission'] = Permission::checkDashboardModulePermission(['Accounts', 'Store-Inventory', 'Pharmacy Billing']);
        $data['current_inpatient_permission'] = Permission::checkDashboardModulePermission(['Accounts', 'Inpatient']);
        $data['lab_permission'] = (Permission::checkDashboardModulePermission(['Accounts', 'Laboratory']) || Permission::checkPermissionFrontendAdmin("laboratory") || Permission::checkPermissionFrontendAdmin("laboratory-grouping")) ? true : false;
        $data['radio_permission'] = (Permission::checkDashboardModulePermission(['Accounts', 'Radiology']) || Permission::checkPermissionFrontendAdmin("radiology") || Permission::checkPermissionFrontendAdmin("radiology-grouping") || Permission::checkPermissionFrontendAdmin("radio-template")) ? true : false;
        $data['province_wise_permission'] = Permission::checkDashboardModulePermission(['Accounts', 'OPD', 'Inpatient', 'OT Management']);
        $data['fiscal_year'] = Year::where('fldfirst', '<=', Carbon::now()->format('Y-m-d'))->where('fldlast', '>=', Carbon::now()->format('Y-m-d'))->first();

        if ($data['patient_info_permission']) {
            $data['normalCount'] = $data['abnormalCount'] = $data['totalPatientAdmitted'] = $data['totalPatientDischarged'] = $data['totalPatientDeath'] = 0;
            $data['totalNFollowPatient'] = $data['totalNOldPatient'] = $data['inpatientCount'] = $data['totalNewPatient'] = $data['todayRadioWaiting'] = $data['todayRadioReported'] = $data['todayRadioVerified'] = $data['todayLabWaiting'] = $data['todayLabSampled'] = $data['todayLabReported'] = $data['todayLabVerified'] = $data['normalCount'] = $data['abnormalCount'] = $data['outpatientCount'] = $data['emergencyCount'] = 0;
        }

        $data['billingSet'] = Cache::remember('billing-set', 60 * 60 * 24, function () {
            return BillingSet::all();
        });

        // $data['departments'] = Department::get();

        $user = \Auth::guard("admin_frontend")->user();

        if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
            $data['departments'] = HospitalDepartment::orderBy('name', "asc")->get();
        } else {
            // fetch departments accroding to user
            // $departments = HospitalDepartmentUsers::where('user_id', $user->id)->pluck('hospital_department_id');
            $data['departments'] = HospitalDepartment::whereHas('hospitalDepartmentUser', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('name', "asc")->get();
        }


        if ($data['province_wise_permission']) {
            $data['ProvincesPatient'] = $this->getProvincesPatient();
            /*might need in the future*/
            $data['doctors'] = UserService::getDoctors(['id', 'firstname', 'middlename', 'lastname']);
            $data['doctor_shares'] = $this->doctorshare();

            $data['categories'] = config('usershare.categories');
        }
        return view('admindashboard::index-new', $data);
    }



    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function maleFemaleChart(Request $request)
    {
        try {
            /*pie chart of male and female*/
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            } else {
                $from_date = \Carbon\Carbon::now()->startOfYear();
                $to_date = \Carbon\Carbon::now();
            }

            $consultEncounter = Cache::remember('encounter_male_female', 60 * 60 * 24, function () {
                return Consult::select('fldencounterval')->distinct()->pluck('fldencounterval');
            });
            $resultData = Encounter::select('fldpatientval')->whereIn('fldencounterval', $consultEncounter)->distinct();

            if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
                $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
                $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
                $resultData->where('fldregdate', '>=', $startTime);
                $resultData->where('fldregdate', '<=', $endTime);
            }

            $patientId = Cache::remember('patient_val_male_female', 60 * 60 * 24, function () use ($resultData) {
                return $resultData->pluck('fldpatientval');
            });
            $maleCount = 0;
            $femalleCount = 0;
            //            $patientId = $resultData->pluck('fldpatientval');
            foreach ($patientId->chunk(300) as $chunk) {
                $maleCount += PatientInfo::select('fldptsex')->whereIn('fldpatientval', $chunk)->where('fldptsex', 'Male')->count();
                $femalleCount += PatientInfo::select('fldptsex')->whereIn('fldpatientval', $chunk)->where('fldptsex', 'Female')->count();
            }

            $data['genderChart'] = [];
            $data['genderChartTitle'] = [];

            $data['genderChart'][] = 'Male';
            $data['genderChartTitle'][] = $maleCount;

            $data['genderChart'][] = 'Female';
            $data['genderChartTitle'][] = $femalleCount;

            /*end pie chart of male and female*/
            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function patientByDepartment(Request $request)
    {
        try {
            /*count of patient by department*/
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Year") {
                $from_date = \Carbon\Carbon::now()->startOfYear();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            }

            $countPatientByDepartmentRequestConsultName = Consult::query();

            if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
                $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
                $countPatientByDepartmentRequestConsultName->where('fldtime', '>=', $startTime);

                $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
                $countPatientByDepartmentRequestConsultName->where('fldtime', '<=', $endTime);
            }

            $countPatientByDepartment = $countPatientByDepartmentRequestConsultName->select('fldconsultname', \DB::raw('count(*) as count'))->groupBy('fldconsultname')->get();
            //            $countPatientByDepartment = $countPatientByDepartmentRequestConsultName->select('fldconsultname', \DB::raw('count(*) as count'))->groupBy('fldconsultname')->get();
            $data['patientByDepartment'] = [];
            $data['patientByDepartmentTitle'] = [];
            if (count($countPatientByDepartment)) {
                foreach ($countPatientByDepartment as $consultData) {
                    if ($consultData->fldconsultname != null || $consultData->fldconsultname != "") {
                        $data['patientByDepartment'][] = $consultData->count;
                        $data['patientByDepartmentTitle'][] = $consultData->fldconsultname;
                    }
                }
            }
            //            return $data;
            /*end count of patient by department*/
            //            $html = view('admindashboard::charts.patient-department', $data)->render();
            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function patientByBillingMode(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            /*count of patient by department*/
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
                $from_date = $fiscal_year->fldfirst;
                $to_date = $fiscal_year->fldlast;
                // $from_date = \Carbon\Carbon::now()->startOfYear();
                // $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            }

            $countPatientByDepartmentRequestBillingMode = Consult::query();

            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
            $countPatientByDepartmentRequestBillingMode->where('fldtime', '>=', $startTime);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            $countPatientByDepartmentRequestBillingMode->where('fldtime', '<=', $endTime);
            // }

            $countPatientByBillingMode = $countPatientByDepartmentRequestBillingMode->select('fldbillingmode', \DB::raw('count(*) as count'))->groupBy('fldbillingmode')->get();
            $data['patientByBillingMode'] = [];

            if (count($countPatientByBillingMode)) {
                foreach ($countPatientByBillingMode as $consultDataBill) {
                    if ($consultDataBill->fldbillingmode != null || $consultDataBill->fldbillingmode != "") {
                        $dataNew['Billing'] = $consultDataBill->fldbillingmode;
                        $dataNew['Count'] = $consultDataBill->count;
                        $data['patientByBillingMode'][] = $dataNew;
                    }
                }
            }

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $encounter
     * @param $fiscal_year
     * @return mixed
     */
    public function EncounterNewOld($encounter = "NEW", $fiscal_year)
    {
        if ($encounter == "NEW") {
            $encounterCountNew = Cache::remember('encounterCountNew', 60 * 60 * 24, function () use ($encounter, $fiscal_year) {
                return Encounter::where('fldvisit', 'LIKE', $encounter)
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        } else if ($encounter == "OLD") {
            $encounterCountNew = Cache::remember('encounterCountOld', 60 * 60 * 24, function () use ($encounter, $fiscal_year) {
                return Encounter::whereIn('fldvisit', ['OLD', 'FOLLOWUP'])
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        } else if ($encounter == "FOLLOWUP") {
            $encounterCountNew = Cache::remember('encounterCountOld', 60 * 60 * 24, function () use ($encounter, $fiscal_year) {
                return Encounter::where('fldvisit', 'LIKE', 'FOLLOWUP')
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        }
        return $encounterCountNew;
    }


    /**
     * @param string $encounter
     * @param $fiscal_year
     * @return mixed
     */
    public function OnlineWalkPatient($encounter = "WALKIN", $fiscal_year)
    {
        $onlinepatient = Eappointment::where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)->count();
        $allpatient = Encounter::where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)->count();
        $walkin = $allpatient - $onlinepatient;

        if ($encounter == "WALKIN") {
            return $walkin;
        }
        if ($encounter == "ONLINE") {
            return $onlinepatient;
        }
    }

    /**
     * @param string $encounter
     * @param $fiscal_year
     * @return array
     */
    public function PharmacyPatient($encounter = "Inpatient", $fiscal_year)
    {


        $group = ['Medicines', 'Surgicals', 'Extra Items'];
        $datas = [];
        //where tblentry.fldcategory IN '.$group.'
        if ($encounter == "Outpatient") {
            $query = 'SELECT YEAR(fldordtime) as SalesYear,
                        MONTH(fldordtime) as SalesMonth,
                        SUM(`fldditemamt`) AS TotalSales
                            FROM tblpatbilling as tblpatbilling
                            Where  tblpatbilling.fldopip = "OP"
                            and fldordtime >= "' . $fiscal_year->fldfirst . '"
                and fldordtime <= "' . $fiscal_year->fldlast . '"
            GROUP BY YEAR(fldordtime), MONTH(fldordtime)
            ORDER BY YEAR(fldordtime), MONTH(fldordtime)';
            $op = DB::select(DB::raw($query));
            if ($op) {
                foreach ($op as $opp) {
                    $monthNum = $opp->SalesMonth;
                    $dateObj = DateTime::createFromFormat('!m', $monthNum);
                    $monthName = $dateObj->format('F'); // March

                    $datas['months'][] = $monthName;
                    $datas['TotalSales'][] = (int)$opp->TotalSales;
                }
                // dd($datas);
            }
            return $datas;
        } else if ($encounter == "Inpatient") {

            $query = 'SELECT YEAR(fldordtime) as SalesYear,
            MONTH(fldordtime) as SalesMonth,
            SUM(`fldditemamt`) AS TotalSales
                FROM tblpatbilling as tblpatbilling
                JOIN tblentry as tblentry on tblpatbilling.flditemname = tblentry.fldstockid
                Where  tblpatbilling.fldopip = "IP"
                and fldordtime >= "' . $fiscal_year->fldfirst . '"
                and fldordtime <= "' . $fiscal_year->fldlast . '"
                GROUP BY YEAR(fldordtime), MONTH(fldordtime)
                ORDER BY YEAR(fldordtime), MONTH(fldordtime)';
            $op = DB::select(DB::raw($query));
            if ($op) {
                foreach ($op as $opp) {
                    $monthNum = $opp->SalesMonth;
                    $dateObj = DateTime::createFromFormat('!m', $monthNum);
                    $monthName = $dateObj->format('F'); // March

                    $datas['months'][] = $monthName;
                    $datas['TotalSales'][] = (int)$opp->TotalSales;
                }
                // dd($datas);
            }
            return $datas;
        }
    }

    /**
     * @param string $encounter
     * @param $fiscal_year
     * @return mixed
     */
    public function EncounterPatientAdmittedDischarged($encounter = "Admitted", $fiscal_year)
    {
        if ($encounter == "Admitted") {
            $encounterCount = Cache::remember('encounterCountPatientAdmitted', 60 * 60 * 24, function () use ($encounter, $fiscal_year) {
                return Encounter::where('fldadmission', 'LIKE', $encounter)
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        } else if ($encounter == "Death") {
            $encounterCount = Cache::remember('encounterCountPatientDeath', 60 * 60 * 24, function () use ($encounter, $fiscal_year) {
                return Encounter::where('fldadmission', 'LIKE', $encounter)
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        } else if ($encounter == "Discharged") {
            $encounterCount = Cache::remember('encounterCountPatientDischarged', 60 * 60 * 24, function () use ($encounter, $fiscal_year) {
                return Encounter::where('fldadmission', 'LIKE', $encounter)
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        } else {
            $encounterCount = Cache::remember('encounterCountPatientDischarged', 60 * 60 * 24, function () use ($encounter, $fiscal_year) {
                return Encounter::where('fldadmission', 'LIKE', $encounter)
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        }

        return $encounterCount;
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array
     */
    public function getPatientByOPD(Request $request, $fiscal_year = null)
    {
        if ($fiscal_year == null) {
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
        }
        if ($request->chartParam == "Month") {
            $from_date = \Carbon\Carbon::now()->startOfMonth();
            $to_date = \Carbon\Carbon::now();
        } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
            $from_date = $fiscal_year->fldfirst;
            $to_date = $fiscal_year->fldlast;
            // $from_date = \Carbon\Carbon::now()->startOfYear();
            // $to_date = \Carbon\Carbon::now();
        } elseif ($request->chartParam == "Day") {
            $from_date = \Carbon\Carbon::now();
            $to_date = \Carbon\Carbon::now();
        }

        $department = Department::where('fldcateg', 'Consultation')->pluck('flddept');
        $countPatientByOPD = Encounter::query();

        // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
        $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
        $countPatientByOPD->where('fldregdate', '>=', $startTime);

        $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
        $countPatientByOPD->where('fldregdate', '<=', $endTime);
        // }

        if ($request->billingSet) {
            $countPatientByOPD->where('fldbillingmode', $request->billingSet);
        }
        $encounterDataOPD = $countPatientByOPD->select('fldcurrlocat', \DB::raw('count(*) as count'))->groupBy('fldcurrlocat')->whereIn('fldcurrlocat', $department)->get();
        $data['patientByOPD'] = [];
        $data['patientByOPDTitle'] = [];

        if (count($encounterDataOPD)) {
            foreach ($encounterDataOPD as $consultDataBill) {
                if ($consultDataBill->fldcurrlocat != null || $consultDataBill->fldcurrlocat != "") {
                    array_push($data['patientByOPD'], $consultDataBill->count);
                    array_push($data['patientByOPDTitle'], $consultDataBill->fldcurrlocat);
                }
            }
        }
        return $data;
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array
     */
    public function getPatientByIPD(Request $request, $fiscal_year = null)
    {
        if ($fiscal_year == null) {
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
        }
        if ($request->chartParam == "Month") {
            $from_date = \Carbon\Carbon::now()->startOfMonth();
            $to_date = \Carbon\Carbon::now();
        } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
            $from_date = $fiscal_year->fldfirst;
            $to_date = $fiscal_year->fldlast;
            // $from_date = \Carbon\Carbon::now()->startOfYear();
            // $to_date = \Carbon\Carbon::now();
        } elseif ($request->chartParam == "Day") {
            $from_date = \Carbon\Carbon::now();
            $to_date = \Carbon\Carbon::now();
        }

        $department = Department::where('fldcateg', 'Patient Ward')->pluck('flddept');
        $countPatientByIPD = Encounter::query();

        // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
        $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
        $countPatientByIPD->where('fldregdate', '>=', $startTime);

        $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
        $countPatientByIPD->where('fldregdate', '<=', $endTime);
        // }

        $encounterDataIPD = $countPatientByIPD->select('fldcurrlocat', \DB::raw('count(*) as count'))->groupBy('fldcurrlocat')->whereIn('fldcurrlocat', $department)->get();

        $data['patientByIPD'] = [];
        $data['patientIPD'] = [];
        $data['patientByIPDTitle'] = [];
        if (count($encounterDataIPD)) {
            foreach ($encounterDataIPD as $consultDataBill) {
                if ($consultDataBill->fldcurrlocat != null || $consultDataBill->fldcurrlocat != "") {
                    $dataNew['IPD'] = $consultDataBill->fldcurrlocat;
                    $dataNew['Count'] = $consultDataBill->count;
                    array_push($data['patientIPD'], $consultDataBill->count);
                    array_push($data['patientByIPDTitle'], $consultDataBill->fldcurrlocat);
                    array_push($data['patientByIPD'], $dataNew);
                }
            }
        }
        return $data;
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array
     */
    public function getPatientByEmergency(Request $request, $fiscal_year = null)
    {
        if ($fiscal_year == null) {
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
        }
        if ($request->chartParam == "Month") {
            $from_date = \Carbon\Carbon::now()->startOfMonth();
            $to_date = \Carbon\Carbon::now();
        } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
            $from_date = $fiscal_year->fldfirst;
            $to_date = $fiscal_year->fldlast;
            // $from_date = \Carbon\Carbon::now()->startOfYear();
            // $to_date = \Carbon\Carbon::now();
        } elseif ($request->chartParam == "Day") {
            $from_date = \Carbon\Carbon::now();
            $to_date = \Carbon\Carbon::now();
        }

        $countPatientByEmergency = Encounter::query();

        // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
        $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
        $countPatientByEmergency->where('fldregdate', '>=', $startTime);

        $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
        $countPatientByEmergency->where('fldregdate', '<=', $endTime);
        // }

        $encounterDataEmergency = $countPatientByEmergency->select('fldreferto', \DB::raw('count(*) as count'))
            ->where('fldcurrlocat', 'Emergency')
            ->where('fldreferto', '!=', null)
            ->groupBy('fldreferto')
            ->get();
        $data['patientByEmergency'] = [];
        $data['patientByEmergencyTitle'] = [];

        if (count($encounterDataEmergency)) {
            foreach ($encounterDataEmergency as $emergencyData) {
                if ($emergencyData->fldcurrlocat != null || $emergencyData->fldcurrlocat != "") {
                    array_push($data['patientByEmergency'], $emergencyData->count);
                    array_push($data['patientByEmergencyTitle'], $emergencyData->fldreferto);
                }
            }
        }
        return $data;
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function labStatus(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            /*count of lab status*/
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
                $from_date = $fiscal_year->fldfirst;
                $to_date = $fiscal_year->fldlast;
                // $from_date = \Carbon\Carbon::now()->startOfYear();
                // $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            }

            $countLabStatus = PatBilling::query();

            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
            $countLabStatus->where('fldordtime', '>=', $startTime);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            $countLabStatus->where('fldordtime', '<=', $endTime);
            // }


            $countPatientByDepartment = $countLabStatus->select('fldstatus', \DB::raw('count(*) as count'))->where('flditemtype', 'Diagnostic Tests')->groupBy('fldstatus')->get();
            $data['labStatus'] = [];
            $data['labStatusTitle'] = [];
            if (count($countPatientByDepartment)) {
                foreach ($countPatientByDepartment as $consultData) {
                    if ($consultData->fldstatus != null || $consultData->fldstatus != "") {
                        array_push($data['labStatus'], $consultData->count);
                        array_push($data['labStatusTitle'], $consultData->fldstatus);
                    }
                }
            }
            // dd($data['labStatus']);

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function labOrderStatus(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
                $from_date = $fiscal_year->fldfirst;
                $to_date = $fiscal_year->fldlast;
                // $from_date = \Carbon\Carbon::now()->startOfYear();
                // $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            }
            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            // }

            $patBilling = PatBilling::select(\DB::raw('count(*) as count'))
                ->where('flditemtype', 'Diagnostic Tests')
                ->where('fldsave', 1)
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldordtime', '<=', $endTime);
                })
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Done')
                        ->orWhere('fldstatus', '=', 'Cleared');
                })
                ->first();

            $patBillingListPunched = PatBilling::select(\DB::raw('count(*) as count'))
                ->where('flditemtype', 'Diagnostic Tests')
                ->where('fldsave', 0)
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldordtime', '<=', $endTime);
                })
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->first();

            $reportedData = PatLabTest::select(\DB::raw('count(*) as count'))
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldtime_report', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldtime_report', '<=', $endTime);
                })
                ->first();

            $data['labOrder'] = [$patBilling->count, $patBillingListPunched->count, $reportedData->count];
            $data['labOrderTitle'] = ['Order Pending', 'Order Requested', 'Order Reported'];

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function radiologyStatus(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            /*count of radiology status*/
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
                $from_date = $fiscal_year->fldfirst;
                $to_date = $fiscal_year->fldlast;
                // $from_date = \Carbon\Carbon::now()->startOfYear();
                // $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            }

            $countRadiologyStatus = PatBilling::query();

            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
            $countRadiologyStatus->where('fldordtime', '>=', $startTime);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            $countRadiologyStatus->where('fldordtime', '<=', $endTime);
            // }


            $countPatientByDepartment = $countRadiologyStatus->select('fldstatus', \DB::raw('count(*) as count'))->where('flditemtype', 'Radio Diagnostics')->groupBy('fldstatus')->get();
            $data['radiologyStatus'] = [];
            $data['radiologyStatusTitle'] = [];
            if (count($countPatientByDepartment)) {
                foreach ($countPatientByDepartment as $consultData) {
                    if ($consultData->fldstatus != null || $consultData->fldstatus != "") {
                        array_push($data['radiologyStatus'], $consultData->count);
                        array_push($data['radiologyStatusTitle'], $consultData->fldstatus);
                    }
                }
            }
            $radiologyStatusGroup = [];
            $data['radiologyStatusGroup'] = [];
            if (!empty($data['radiologyStatusTitle'])) {
                foreach ($data['radiologyStatusTitle'] as $k => $title) {
                    $radiologyStatusGroup['country'] = $title;
                    $radiologyStatusGroup['value'] = $data['radiologyStatus'][$k];
                    array_push($data['radiologyStatusGroup'], $radiologyStatusGroup);
                }
            }
            //dd($radiologyStatusGroup);

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function radiologyOrderStatus(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Year" || !$request->has('chartParam') || $request->chartParam == null) {
                $from_date = $fiscal_year->fldfirst;
                $to_date = $fiscal_year->fldlast;
                // $from_date = \Carbon\Carbon::now()->startOfYear();
                // $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            }
            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            // }

            $patBilling = PatBilling::select(\DB::raw('count(*) as count'))
                ->where('flditemtype', 'Radio Diagnostics')
                ->where('fldsave', 1)
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldordtime', '<=', $endTime);
                })
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Done')
                        ->orWhere('fldstatus', '=', 'Cleared');
                })
                ->first();

            $patBillingListPunched = PatBilling::select(\DB::raw('count(*) as count'))
                ->where('flditemtype', 'Radio Diagnostics')
                ->where('fldsave', 0)
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldordtime', '<=', $endTime);
                })
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Punched')
                        ->orWhere('fldstatus', '=', 'Cancelled');
                })
                ->first();

            $reportedData = PatRadioTest::select(\DB::raw('count(*) as count'))
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldtime_report', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldtime_report', '<=', $endTime);
                })
                ->first();

            $data['radiologyOrder'] = [$patBilling->count, $patBillingListPunched->count, $reportedData->count];
            $data['radiologyOrderTitle'] = ['Order Pending', 'Order Requested', 'Order Reported'];

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function accessForbidden()
    {
        $data = array();
        $data['title'] = "Access Forbidden - " . \Options::get('siteconfig')['system_name'];
        return view('access-forbidden', $data);
    }

    /**
     * @return mixed
     */
    public function followupCount()
    {
        $encounterCount = Cache::remember('followupCount', 60 * 60 * 24, function () {
            return Encounter::where([['fldfollowup', 'Yes']])->count();
        });
        return $encounterCount;
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function getBedOccupacyDetails(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            $departmentBeds = Departmentbed::orderBy('flddept', "DESC")
                ->orderBy('fldfloor', "ASC")
                ->whereHas('encounter', function ($q) use ($fiscal_year) {
                    $q->where('fldregdate', '>=', $fiscal_year->fldfirst)
                        ->where('fldregdate', '<=', $fiscal_year->fldlast);
                })
                ->get()
                ->groupBy(['flddept', 'fldencounterval'])
                ->toArray();

            $data['bedDetails'] = [];
            $data['patientByBedOccupacy'] = [];
            $data['patientBedOccupacy'] = [];
            $data['patientBedOccupacyTitle'] = [];
            foreach ($departmentBeds as $deptKey => $departmentBed) {
                $bedData = [];
                $bedData['Department'] = $deptKey;
                if (count($departmentBed) > 0) {
                    if (array_key_exists("", $departmentBed)) {
                        $bedData['emptyBed'] = $emptyBed = count($departmentBed[""]);
                        $bedData['occupiedBed'] = $occupiedBed = count($departmentBed) - 1;
                    } else {
                        $bedData['emptyBed'] = $emptyBed = 0;
                        $bedData['occupiedBed'] = $occupiedBed = count($departmentBed);
                    }
                } else {
                    $bedData['emptyBed'] = $emptyBed = 0;
                    $bedData['occupiedBed'] = $occupiedBed = 0;
                }
                $bedData['totalBed'] = $emptyBed + $occupiedBed;
                $dataNew['IPD'] = $deptKey;
                $dataNew['Count'] = $occupiedBed;
                array_push($data['patientByBedOccupacy'], $dataNew);
                array_push($data['patientBedOccupacy'], $occupiedBed);
                array_push($data['patientBedOccupacyTitle'], $deptKey);
                array_push($data['bedDetails'], $bedData);
            }
            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param string $type
     * @param $fiscal_year
     * @return mixed
     */
    public function OutpatientInpatientCount($type = "Outpatient", $fiscal_year)
    {
        if ($type == "Outpatient") {
            $result = Cache::remember('outpatientCount', 60 * 60 * 24, function () use ($fiscal_year) {
                return Encounter::where('fldadmission', "Admitted")
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        } elseif ($type == "Inpatient") {
            $result = Cache::remember('inpatientCount', 60 * 60 * 24, function () use ($fiscal_year) {
                return Encounter::whereIn('fldadmission', ['Admitted', 'Discharged', 'Death', 'LAMA'])
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        } elseif ($type == "Emergency") {
            $result = Cache::remember('emergencyCount', 60 * 60 * 24, function () use ($fiscal_year) {
                return Encounter::whereIn('fldadmission', ['Emergency'])
                    ->where('fldregdate', '>=', $fiscal_year->fldfirst)
                    ->where('fldregdate', '<=', $fiscal_year->fldlast)
                    ->count();
            });
        }

        return $result;
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function radioInpatientOutpatientPatient(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Year" || !$request->has('chartParam')) {
                $from_date = $fiscal_year->fldfirst;
                $to_date = $fiscal_year->fldlast;
                // $from_date = \Carbon\Carbon::now()->startOfYear();
                // $to_date = \Carbon\Carbon::now();
            } elseif ($request->chartParam == "Day") {
                $from_date = \Carbon\Carbon::now();
                $to_date = \Carbon\Carbon::now();
            }
            $startTime = null;
            $endTime = null;

            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            // }

            $opdDepartment = Department::where('fldcateg', 'Consultation')->pluck('flddept')->toArray();
            $ipdDepartment = Department::where('fldcateg', 'Patient Ward')->pluck('flddept')->toArray();
            $radioOpdDatas = Encounter::select(\DB::raw('count(*) as count'))
                ->rightJoin('tblpatbilling', 'tblpatbilling.fldencounterval', '=', 'tblencounter.fldencounterval')
                ->where('tblpatbilling.flditemtype', 'Radio Diagnostics')
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('tblpatbilling.fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('tblpatbilling.fldordtime', '<=', $endTime);
                })
                ->whereIn('tblencounter.fldcurrlocat', $opdDepartment)
                ->first();
            $radioIpdDatas = Encounter::select(\DB::raw('count(*) as count'))
                ->rightJoin('tblpatbilling', 'tblpatbilling.fldencounterval', '=', 'tblencounter.fldencounterval')
                ->where('tblpatbilling.flditemtype', 'Radio Diagnostics')
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('tblpatbilling.fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('tblpatbilling.fldordtime', '<=', $endTime);
                })
                ->whereIn('tblencounter.fldcurrlocat', $ipdDepartment)
                ->first();

            $data['radiologyStatus'] = [$radioIpdDatas->count, $radioOpdDatas->count];
            $data['radiologyStatusTitle'] = ["Inpatient", "Outpatient"];

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function opSales(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            // if ($request->chartParam == "Month") {
            //     $from_date = \Carbon\Carbon::now()->startOfMonth();
            //     $to_date = \Carbon\Carbon::now();
            // } elseif ($request->chartParam == "Day") {
            //     $from_date = \Carbon\Carbon::now();
            //     $to_date = \Carbon\Carbon::now();
            // } elseif ($request->chartParam == "Year"){
            // $from_date = \Carbon\Carbon::now()->startOfYear();
            // $to_date = \Carbon\Carbon::now();
            $from_date = $fiscal_year->fldfirst;
            $to_date = $fiscal_year->fldlast;
            // }

            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            // }
            $pharmacyOpdDatas = PatBilling::select(\DB::raw('DATE(fldordtime) as date'), \DB::raw('SUM(fldditemamt) as amount'))
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldordtime', '<=', $endTime);
                })
                ->when($request->has('billingSet') && $request->billingSet != null, function ($q) use ($request) {
                    return $q->where('fldbillingmode', $request->billingSet);
                })
                ->where('fldopip', "OPD")
                ->whereIn('flditemtype', ['Medicines', 'Surgicals', 'Extra Items'])
                ->groupBy('date')
                ->get();
            $data['pharmacyOpStatus'] = [];
            $data['pharmacyOpStatusTitle'] = [];
            foreach ($pharmacyOpdDatas as $pharmacyOpdData) {
                array_push($data['pharmacyOpStatus'], $pharmacyOpdData->amount);
                array_push($data['pharmacyOpStatusTitle'], $pharmacyOpdData->date);
            }

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @param null $fiscal_year
     * @return array|\Exception|Exception
     */
    public function ipSales(Request $request, $fiscal_year = null)
    {
        try {
            if ($fiscal_year == null) {
                $today_date = Carbon::now()->format('Y-m-d');
                $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            }
            // if ($request->chartParam == "Month") {
            //     $from_date = \Carbon\Carbon::now()->startOfMonth();
            //     $to_date = \Carbon\Carbon::now();
            // } elseif ($request->chartParam == "Year") {
            // $from_date = \Carbon\Carbon::now()->startOfYear();
            // $to_date = \Carbon\Carbon::now();
            $from_date = $fiscal_year->fldfirst;
            $to_date = $fiscal_year->fldlast;
            // } elseif ($request->chartParam == "Day") {
            //     $from_date = \Carbon\Carbon::now();
            //     $to_date = \Carbon\Carbon::now();
            // }

            // if ($request->chartParam == "Month" || $request->chartParam == "Day" || $request->chartParam == "Year") {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);

            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            // }

            $pharmacyIpdDatas = PatBilling::select(\DB::raw('DATE(fldordtime) as date'), \DB::raw('SUM(fldditemamt) as amount'))
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('fldordtime', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('fldordtime', '<=', $endTime);
                })
                ->when($request->has('billingSet') && $request->billingSet != null, function ($q) use ($request) {
                    return $q->where('fldbillingmode', $request->billingSet);
                })
                ->where('fldopip', "IPD")
                ->whereIn('flditemtype', ['Medicines', 'Surgicals', 'Extra Items'])
                ->groupBy('date')
                ->get();
            $data['pharmacyIpStatus'] = [];
            $data['pharmacyIpStatusTitle'] = [];
            foreach ($pharmacyIpdDatas as $pharmacyIpdData) {
                array_push($data['pharmacyIpStatus'], $pharmacyIpdData->amount);
                array_push($data['pharmacyIpStatusTitle'], $pharmacyIpdData->date);
            }
            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @param int $value
     * @return int
     */
    public function outOfThreshold(Request $request, $value = 1)
    {
        $test = PatLabTest::select('fldencounterval')
            ->where('fldabnormal', $value)
            ->distinct('fldencounterval')
            ->pluck('fldencounterval')
            ->toArray();
        $subTest = PatLabSubTest::select('fldencounterval')
            ->where('fldabnormal', $value)
            ->distinct('fldencounterval')
            ->pluck('fldencounterval')
            ->toArray();

        return count(array_unique(array_merge($test, $subTest)));
    }

    /**
     * @param Request $request
     * @param $type
     * @param $fiscal_year
     * @return int
     */
    public function laboratoryStatusCount($fiscal_year)
    {
        $type = ['Ordered', 'Sampled', 'Reported', 'Verified'];
        $data = PatLabTest::select('fldstatus', \DB::raw('COUNT(*) as count'))
            ->where('fldtime_sample', '>=', $fiscal_year->fldfirst)
            ->where('fldtime_sample', '<=', $fiscal_year->fldlast)
            ->whereIn('fldstatus', $type)
            ->groupBy('fldstatus')
            ->pluck('count', 'fldstatus');

        return $data;
    }

    /**
     * @param Request $request
     * @param $type
     * @param $fiscal_year
     * @return int
     */
    public function radiologyStatusCount($fiscal_year)
    {
        $type = ['Waiting', 'CheckIn', 'Reported', 'Verified'];
        $data = PatRadioTest::select('fldstatus', \DB::raw('count(*) as count'))
            ->where('fldtime_sample', '>=', $fiscal_year->fldfirst)
            ->where('fldtime_sample', '<=', $fiscal_year->fldlast)
            ->whereIn('fldstatus', $type)
            ->groupBy('fldstatus')
            ->pluck('count', 'fldstatus');

        return $data;
    }

    /**
     * @return array
     */
    public function getProvincesPatient()
    {
        $data['provinces'] = $provinces = \App\Municipal::groupBy('fldprovince')->pluck('fldprovince');
        $totalMale = 0;
        $totalFemale = 0;
        $totalOther = 0;
        $grandTotal = 0;
        if ($provinces) {
            foreach ($provinces as $province) {
                $data[$province]['Male'] = $male = PatientInfo::where([['fldprovince', $province], ['fldptsex', 'Male']])->count();
                $data[$province]['Female'] = $female = PatientInfo::where([['fldprovince', $province], ['fldptsex', 'Female']])->count();
                $data[$province]['Other'] = $other = PatientInfo::where([['fldprovince', $province], ['fldptsex', 'Other']])->count();
                $data[$province]['Total'] = $total = $male + $female + $other;
                $totalMale += $male;
                $totalFemale += $female;
                $totalOther += $other;
                $grandTotal += $total;
            }
        }
        $data['totalMale'] = $totalMale;
        $data['totalFemale'] = $totalFemale;
        $data['totalOther'] = $totalOther;
        $data['grandTotal'] = $grandTotal;

        return $data;
    }

    /**
     * @param $fiscal_year
     * @return array
     */
    public function ageWiseHospitalServices($fiscal_year)
    {

        $hospital_services_response = [
            'new_male_female' => null,
            'total_male_female' => null
        ];

        /** NEW MALE & FEMALE */
        $new_male_female = DB::table('tblencounter')
            ->selectRaw("COUNT(*) AS total,
            CASE WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 0 AND 9) AND tblpatientinfo.fldptsex = 'Male' THEN '0_9_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 10 AND 19) AND tblpatientinfo.fldptsex = 'Male' THEN '10_19_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 20 AND 59) AND tblpatientinfo.fldptsex = 'Male' THEN '20_59_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) > 60) AND tblpatientinfo.fldptsex = 'Male' THEN '60_above_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 0 AND 9) AND tblpatientinfo.fldptsex = 'Female' THEN '0_9_female'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 10 AND 19) AND tblpatientinfo.fldptsex = 'Female' THEN '10_19_female'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 20 AND 59) AND tblpatientinfo.fldptsex = 'Female' THEN '20_59_female'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) > 60) AND tblpatientinfo.fldptsex = 'Female' THEN '60_above_female'
                 ELSE 'Others'
            END AS age_group");
        $new_male_female->join(
            'tblpatientinfo',
            'tblencounter.fldpatientval',
            'tblpatientinfo.fldpatientval'
        );
        $new_male_female->where('tblencounter.fldregdate', '>=', $fiscal_year->fldfirst);
        $new_male_female->where('tblencounter.fldregdate', '<=', $fiscal_year->fldlast);
        $new_male_female->where('tblencounter.fldvisit', 'NEW');
        $new_male_female->groupBy('age_group');
        $new_male_female_response = $new_male_female->get();
        if ($new_male_female_response->count() > 0) {
            $hospital_services_response['new_male_female'] = $new_male_female_response;
        }

        /** TOTAL MALE & FEMALE */
        $total_male_female = DB::table('tblencounter')
            ->selectRaw("COUNT(*) AS total,
            CASE WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 0 AND 9) AND tblpatientinfo.fldptsex = 'Male' THEN '0_9_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 10 AND 19) AND tblpatientinfo.fldptsex = 'Male' THEN '10_19_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 20 AND 59) AND tblpatientinfo.fldptsex = 'Male' THEN '20_59_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) > 60) AND tblpatientinfo.fldptsex = 'Male' THEN '60_above_male'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 0 AND 9) AND tblpatientinfo.fldptsex = 'Female' THEN '0_9_female'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 10 AND 19) AND tblpatientinfo.fldptsex = 'Female' THEN '10_19_female'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) BETWEEN 20 AND 59) AND tblpatientinfo.fldptsex = 'Female' THEN '20_59_female'
                 WHEN (TIMESTAMPDIFF(YEAR, fldptbirday, CURDATE()) > 60) AND tblpatientinfo.fldptsex = 'Female' THEN '60_above_female'
                 ELSE 'Others'
            END AS age_group");
        $total_male_female->join(
            'tblpatientinfo',
            'tblencounter.fldpatientval',
            'tblpatientinfo.fldpatientval'
        );
        $total_male_female->where('tblencounter.fldregdate', '>=', $fiscal_year->fldfirst);
        $total_male_female->where('tblencounter.fldregdate', '<=', $fiscal_year->fldlast);
        $total_male_female->groupBy('age_group');
        $total_male_female_response = $total_male_female->get();

        if ($total_male_female_response->count() > 0) {
            $hospital_services_response['total_male_female'] = $total_male_female_response;
        }

        if ($total_male_female_response->count() > 0) {
            $age['male'] = [
                $hospital_services_response['total_male_female']->where('age_group', '0_9_male')->first()->total ?? 0,
                $hospital_services_response['total_male_female']->where('age_group', '10_19_male')->first()->total ?? 0,
                $hospital_services_response['total_male_female']->where('age_group', '20_59_male')->first()->total ?? 0,
                $hospital_services_response['total_male_female']->where('age_group', '60_above_male')->first()->total ?? 0
            ];
            $age['female'] = [
                $hospital_services_response['total_male_female']->where('age_group', '0_9_female')->first()->total ?? 0,
                $hospital_services_response['total_male_female']->where('age_group', '10_19_female')->first()->total ?? 0,
                $hospital_services_response['total_male_female']->where('age_group', '20_59_female')->first()->total ?? 0,
                $hospital_services_response['total_male_female']->where('age_group', '60_above_female')->first()->total ?? 0
            ];
        } else {
            $age['male'] = [];
            $age['female'] = [];
        }

        return $age;
    }

    /**
     * @param string $encounter
     * @param $fiscal_year
     * @return array
     */
    public function RevenuePatient($request)
    {
        $today_date = Carbon::now()->format('Y-m-d');
        $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

        $results = \App\PatBilling::selectRaw("
                    YEAR(fldordtime) as SalesYear,
                    MONTH(fldordtime) as SalesMonth,
                    Day(fldordtime) as SalesDay,
                    SUM(`fldditemamt`) AS TotalSales,
                    fldopip
                ")
            ->whereNotNull("fldopip")
            ->where("fldordtime", ">=", $fiscal_year->fldfirst)
            ->where("fldordtime", "<=", $fiscal_year->fldlast)
            ->when($request->paymentType != "", function ($query) use ($request) {
                $query->where('fldbillingmode', $request->paymentType);
            })
            ->when($request->department != "", function ($query) use ($request) {
                $query->where('fldcomp', $request->department);
            })
            ->when($request->dateType == "Month", function ($query) {
                $query->groupBY(\DB::raw('YEAR(fldordtime), MONTH(fldordtime), fldopip'));
            })
            ->when($request->dateType == "Week", function ($query) {
                $query->groupBY(\DB::raw('YEAR(fldordtime), MONTH(fldordtime), WEEK(fldordtime), fldopip'));
            })
            ->get();

        $datas['RevenuePatientIn'] = [];
        $datas['RevenuePatientOut'] = [];
        $datas['labels'] = [];
        if ($results) {

            foreach ($results as $opp) {
                $monthNum = $opp->SalesMonth;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName = $request->dateType == "Week" ? $dateObj->format('M') . '-' . $opp->SalesDay : $dateObj->format('F'); // March

                if (strtolower($opp->fldopip) == "ip") {
                    $datas['RevenuePatientIn'][] = (int)$opp->TotalSales;
                } else {
                    $datas['RevenuePatientOut'][] = (int)$opp->TotalSales;
                }
                if (!in_array($monthName, $datas['labels'])) $datas['labels'][] = $monthName;
            }
            return $datas;
        }
        return $datas;
    }

    /**
     * @param null $docname
     * @param null $type
     * @param null $from_date
     * @param null $to_date
     * @return array
     */
    public function doctorshare($docname = null, $type = null, $from_date = null, $to_date = null)
    {
        $data = [];
        $data['billing_share_reports'] = [];
        $docname = request()->docname ?? $docname;
        $type = request()->type ?? $type;
        $from_date = request()->from_date ?? $from_date;
        $to_date = request()->to_date ?? $to_date;

        // get billing share report.
        $data['billing_share_reports'] = PatBillingShare::selectRaw('sum(share) as total_sum, user_id, type')
            ->when(isset($docname) && $docname != "All", function ($query) use ($docname) {
                $query->whereHas('user', function ($query) use ($docname) {
                    $query->where(DB::raw("REPLACE(CONCAT(IFNULL(firstname, ''), IFNULL(middlename, ''), IFNULL(lastname, '')), '\n', '')"), 'LIKE', '%' . str_replace(' ', '', $docname) . '%');
                });
            })
            ->when(isset($type), function ($query) use ($type) {
                $query->where('type', 'LIKE', $type);
            })
            ->when(isset($from_date), function ($query) use ($from_date) {
                $query->whereHas('pat_billing', function ($query) use ($from_date) {
                    $query->whereDate('fldordtime', '>=', $from_date);
                });
            })
            ->when(isset($to_date), function ($query) use ($to_date) {
                $query->whereHas('pat_billing', function ($query) use ($to_date) {
                    $query->whereDate('fldordtime', '<=', $to_date);
                });
            })
            ->groupBy('type', 'user_id')
            ->with('user:id,firstname,middlename,lastname')
            ->limit(5)
            ->get();

        return $data;
    }


    // public function doctorshare($docname = null, $type = null, $from_date = null, $to_date = null)
    // {
    //     $data = [];
    //     $data['billing_share_reports'] = [];
    //     $docname = request()->docname ?? $docname;
    //     $type = request()->type ?? $type;
    //     $from_date = request()->from_date ?? $from_date;
    //     $to_date = request()->to_date ?? $to_date;

    //     // get billing share report.
    //     $query = UserShareReportService::query();

    //     if (isset($docname) && $docname != "All") {

    //         $query = $query->where(DB::raw("REPLACE(CONCAT(IFNULL(firstname, ''), IFNULL(middlename, ''), IFNULL(lastname, '')), '\n', '')"), 'LIKE', '%' . str_replace(' ', '', $docname) . '%');
    //     }

    //     if (isset($type)) {
    //         $query->where('type','LIKE',$type);
    //     }

    //     if (isset($from_date)) {
    //         $query->whereDate('fldordtime', '>=', $from_date);
    //     }

    //     if (isset($to_date)) {
    //         $query->whereDate('fldordtime', '<=', $to_date);
    //     }


    //     $query->groupBy('type', 'user_id')->selectRaw('sum(share) as total_sum, user_id, type');
    //     $query->limit(5);
    //     $data['billing_share_reports'] = $query->get();
    //     return $data;


    // }

    /**
     * @param $fiscal_year
     * @return array
     */
    public function CategorylaboratoryStatusCount($fiscal_year = null)
    {
        $data['Sampled'] = [];
        $data['Reported'] = [];
        $data['Verified'] = [];
        $data['Ordered'] = [];

        if ($fiscal_year == null) {
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
        }

        $categories = Test::groupBy('fldcategory')->orderBy('fldcategory')->pluck('fldcategory');
        $data = array();
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $sampled = PatLabTest::where('fldstatus', 'Sampled')
                    ->where('fldtime_sample', '>=', $fiscal_year->fldfirst)
                    ->where('fldtime_sample', '<=', $fiscal_year->fldlast)
                    ->whereHas('patTestResults', function ($query) use ($category) {
                        $query->where('fldcategory', $category);
                    })
                    ->count();

                $data['Sampled'][] = $sampled;

                $reported = PatLabTest::where('fldstatus', 'Reported')
                    ->where('fldtime_sample', '>=', $fiscal_year->fldfirst)
                    ->where('fldtime_sample', '<=', $fiscal_year->fldlast)
                    ->whereHas('patTestResults', function ($query) use ($category) {
                        $query->where('fldcategory', $category);
                    })
                    ->count();

                $data['Reported'][] = $reported;

                $verify = PatLabTest::where('fldstatus', 'Verified')
                    ->where('fldtime_sample', '>=', $fiscal_year->fldfirst)
                    ->where('fldtime_sample', '<=', $fiscal_year->fldlast)
                    ->whereHas('patTestResults', function ($query) use ($category) {
                        $query->where('fldcategory', $category);
                    })
                    ->count();
                $data['Verified'][] = $verify;

                $ordered = PatLabTest::where('fldstatus', 'Ordered')
                    ->where('fldtime_sample', '>=', $fiscal_year->fldfirst)
                    ->where('fldtime_sample', '<=', $fiscal_year->fldlast)
                    ->whereHas('patTestResults', function ($query) use ($category) {
                        $query->where('fldcategory', $category);
                    })
                    ->count();
                $data['Ordered'][] = $ordered;
            }
        }
        $data['categories'] = $categories;
        return $data;
    }

    /**
     * @param $fiscal_year
     * @param string $categoryextra
     * @return array
     */
    public function CategoryRadioStatusCount($fiscal_year = null)
    {
        $data['Sampled'] = [];
        $data['Reported'] = [];
        $data['Verified'] = [];
        $data['Waiting'] = [];
        $data['Ordered'] = [];

        if ($fiscal_year == null) {
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
        }

        $data = array();
        $categories = ['MRI', 'CT Scan', 'Ultrasound(USG)', 'Mammogram', 'X-RAY', 'Extra'];

        if ($categories) {
            foreach ($categories as $category) {
                if ($category = 'Extra') {
                    $sampled = PatRadioTest::where('tblpatradiotest.fldstatus', 'Sampled')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($categories) {
                            $query->whereNotIn('tblradio.fldcategory', $categories);
                        })
                        ->count();
                    $data['Sampled'][] = $sampled;

                    $reported = PatRadioTest::where('tblpatradiotest.fldstatus', 'Reported')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($categories) {
                            $query->whereNotIn('tblradio.fldcategory', $categories);
                        })
                        ->count();

                    $data['Reported'][] = $reported;

                    $verify = PatRadioTest::where('tblpatradiotest.fldstatus', 'Verified')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($categories) {
                            $query->whereNotIn('tblradio.fldcategory', $categories);
                        })
                        ->count();

                    $data['Verified'][] = $verify;

                    $waiting = PatRadioTest::where('tblpatradiotest.fldstatus', 'Waiting')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($categories) {
                            $query->whereNotIn('tblradio.fldcategory', $categories);
                        })
                        ->count();

                    $data['Waiting'][] = $waiting;

                    $ordered = PatRadioTest::where('tblpatradiotest.fldstatus', 'Ordered')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($categories) {
                            $query->whereNotIn('tblradio.fldcategory', $categories);
                        })
                        ->count();

                    $data['Ordered'][] = $ordered;
                } else {
                    $sampled = PatRadioTest::where('tblpatradiotest.fldstatus', 'Sampled')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($category) {
                            $query->where('tblradio.fldcategory', $category);
                        })
                        ->count();
                    $data['Sampled'][] = $sampled;

                    $reported = PatRadioTest::where('tblpatradiotest.fldstatus', 'Reported')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($category) {
                            $query->where('tblradio.fldcategory', $category);
                        })
                        ->count();

                    $data['Reported'][] = $reported;

                    $verify = PatRadioTest::where('tblpatradiotest.fldstatus', 'Verified')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($category) {
                            $query->where('tblradio.fldcategory', $category);
                        })
                        ->count();

                    $data['Verified'][] = $verify;

                    $waiting = PatRadioTest::where('tblpatradiotest.fldstatus', 'Waiting')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($category) {
                            $query->where('tblradio.fldcategory', $category);
                        })
                        ->count();

                    $data['Waiting'][] = $waiting;

                    $ordered = PatRadioTest::where('tblpatradiotest.fldstatus', 'Ordered')
                        ->where('tblpatradiotest.fldtime_report', '>=', $fiscal_year->fldfirst)
                        ->where('tblpatradiotest.fldtime_report', '<=', $fiscal_year->fldlast)
                        ->whereHas('radioData', function ($query) use ($category) {
                            $query->where('tblradio.fldcategory', $category);
                        })
                        ->count();

                    $data['Ordered'][] = $ordered;
                }
            }
            $data['categories'] = $categories;
            return $data;
        }
    }

    /**
     * @param string $encounter
     * @param $fiscal_year
     * @return mixed
     */
    public function PharmacyPatientCount($encounter = "Inpatient", $fiscal_year)
    {
        $group = ['Medicines', 'Surgicals', 'Extra Items'];
        if ($encounter == "Outpatient") {

            return DB::table('tblpatbilling')
                ->join('tblentry', 'tblpatbilling.flditemname', '=', 'tblentry.fldstockid')
                ->where('tblpatbilling.fldopip', '=', 'OP')
                ->whereIn('tblentry.fldcategory', $group)
                ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
                ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
                ->groupBy('fldencounterval')
                ->count();
        } else if ($encounter == "Inpatient") {

            return DB::table('tblpatbilling')
                ->join('tblentry', 'tblpatbilling.flditemname', '=', 'tblentry.fldstockid')
                ->where('tblpatbilling.fldopip', '=', 'IP')
                ->whereIn('tblentry.fldcategory', $group)
                ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
                ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
                ->groupBy('fldencounterval')
                ->count();
        }
    }

    /**
     * @param $fiscal_year
     * @return array
     */
    public function OperationStatusCount($fiscal_year)
    {
        $data['Major'] = [];
        $data['Minor'] = [];
        $data['Intermediate'] = [];

        $Major = DB::table('tblpatbilling')
            ->join('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')
            ->select('tblpatbilling.fldencounterval')
            ->where('tblservicecost.fldreport', 'Major')
            ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
            ->groupBy('tblpatbilling.fldencounterval')
            ->count();

        $data['Major'] = $Major;

        $Minor = DB::table('tblpatbilling')
            ->join('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')
            ->select('tblpatbilling.fldencounterval')
            ->where('tblservicecost.fldreport', 'Minor')
            ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
            ->groupBy('tblpatbilling.fldencounterval')
            ->count();

        $data['Minor'] = $Minor;

        $Intermediate = DB::table('tblpatbilling')
            ->join('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')
            ->select('tblpatbilling.fldencounterval')
            ->where('tblservicecost.fldreport', 'Intermediate')
            ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
            ->groupBy('tblpatbilling.fldencounterval')
            ->count();

        $data['Intermediate'] = $Intermediate;

        return $data;
    }

    /**
     * @param $fiscal_year
     * @return array
     */
    public function deliveryPatient($fiscal_year = null)
    {
        if ($fiscal_year == null) {
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
        }

        $data = [
            'Normal' => 0,
            'CS' => 0,
            'Other' => 0,
            'total' => 0,
        ];
        $getdeliveryType = Delivery::groupBy('flditem')->pluck('flditem');
        if ($getdeliveryType) {
            foreach ($getdeliveryType as $type) {
                $data[$type] = Confinement::where('flddeltype', $type)
                    ->where('flddeltime', '>=', $fiscal_year->fldfirst)
                    ->where('flddeltime', '<=', $fiscal_year->fldlast)
                    ->count();
            }
        }

        return $data;
    }
}
