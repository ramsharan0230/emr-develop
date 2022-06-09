<?php

namespace App\Utils;

use App\AccessComp;
use App\Bedfloor;
use App\CheckRedirectLastEncounter;
use App\CogentUsers;
use App\Consult;
use App\Department;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\GroupComputerAccess;
use App\GroupMac;
use App\HospitalDepartment;
use App\HospitalDepartmentUsers;
use App\Http\Controllers\GetMacAddress;
use App\Http\Controllers\Nepali_Calendar;
use App\Notifications\NearExpiryMedicine;
use App\PatBillDetail;
use App\PatBilling;
use App\PatBillingShare;
use App\PatientInfo;
use App\PermissionGroup;
use App\PersonImage;
use App\Service;
use App\ServiceCost;
use App\SidebarMenu;
use App\SignatureForm;
use App\TaxGroup;
use App\TransactionView;
use App\UserDepartment;
use App\UserGroup;
use App\Year;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS2D;
use Session;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\AccountLedger;
use App\GeneralLedgerMap;
use App\Group;
use App\PatientDate;
use App\PatLabTest;
use Illuminate\Support\Arr;

/**
 * Class Helpers
 * @package App\Utils
 */
class Helpers
{

	/**
	 * @var GetMacAddress
	 */
	protected $macAddr;

	/**
	 * Helpers constructor.
	 * @param GetMacAddress $macObj
	 */
	public function __construct(GetMacAddress $macObj)
	{
		$this->macAddr = $macObj;
	}

	/**
	 * Convert english date to nepali
	 * @param $date
	 * @return mixed
	 */
	public static function dateEngToNep($date)
	{
		$nepali_calender = new Nepali_Calendar();
		$dateExplode = explode('/', $date);
		$date_day = $dateExplode[2];
		$date_month = $dateExplode[1];
		$date_year = $dateExplode[0];
		$date_nepali = $nepali_calender->eng_to_nep($date_year, $date_month, $date_day);

		return json_decode(json_encode($date_nepali));
	}

	/**
	 * @param null $date
	 * @return array
	 */
	public static function getNepaliFiscalYearRange($date = NULL)
	{
		$nepali_calender = new Nepali_Calendar();
		if ($date == NULL)
			$date = date('Y-m-d');
		$date = explode('-', $date);
		$date_nepali = $nepali_calender->eng_to_nep($date[0], $date[1], $date[2]);

		$startdate = "-04-01";
		$enddate = "-03-";
		if ($date_nepali['month'] <= 3) {
			$startdate = ($date_nepali['year'] - 1) . $startdate;
			$enddate = ($date_nepali['year']) . $enddate . $nepali_calender->get_month_last_date($date_nepali['year'], $date_nepali['month']);
		} else {
			$startdate = $date_nepali['year'] . $startdate;
			$enddate = ($date_nepali['year'] + 1) . $enddate . $nepali_calender->get_month_last_date(($date_nepali['year'] + 1), $date_nepali['month']);
		}
		return compact('startdate', 'enddate');
	}

	public static function getNepaliFiscalYear($date = NULL)
	{
		$daterange = self::getNepaliFiscalYearRange($date);
		$startdate = explode('-', $daterange['startdate'])[0];
		$enddate = explode('-', $daterange['enddate'])[0];

		return substr($startdate, -2) . '-' . substr($enddate, -2);
	}

	// Added by Anish modified for HMIS
	public static function getNepaliFiscalYearHMIS($date = NULL)
	{
		$daterange = self::getNepaliFiscalYearRange($date);
		$startdate = explode('-', $daterange['startdate'])[0];
		$enddate = explode('-', $daterange['enddate'])[0];

		return substr($startdate, -2) . '/' . substr($enddate, -2);
	}

	/**
	 * @param $date
	 * @return mixedOperation Theatre Count
	 */
	public static function dateEngToNepdash($date)
	{
		$nepali_calender = new Nepali_Calendar();
		$dateExplode = explode('-', $date);
		$date_day = $dateExplode[2];
		$date_month = $dateExplode[1];
		$date_year = $dateExplode[0];
		$date_nepali = $nepali_calender->eng_to_nep($date_year, $date_month, $date_day);

		return json_decode(json_encode($date_nepali));
	}

	/**
	 * @param $date
	 * @param string $seprator
	 * @return mixed
	 */
	public static function dateNepToEng($date, $seprator = '-')
	{
		$nepali_calender = new Nepali_Calendar();
		$dateExplode = explode($seprator, $date);
		$date_year = $dateExplode[0];
		$date_month = $dateExplode[1];
		$date_day = $dateExplode[2];
		$date_nepali = $nepali_calender->nep_to_eng($date_year, $date_month, $date_day);
		return json_decode(json_encode($date_nepali));
	}

	public static function changeNepaliDateFormat($date)
	{
		$dates = explode("-", $date);
		$nepali_calender = new Nepali_Calendar();
		$month = $nepali_calender->_get_nepali_month($dates[1]);

		return $month . ' ' . $dates[2] . ' ' . $dates[0];
	}



	/**
	 * @param $date
	 * @param string $seprator
	 * @return mixed
	 */
	public static function getMonthFromNepaliDate($month)
	{
		$nepali_calender = new Nepali_Calendar();
		$month = $nepali_calender->_get_nepali_month($month);
		return $month;
	}

	/**
	 * @param $encounterId
	 * @return mixed
	 */
	public static function getPatientByEncounterId($encounterId)
	{
		return \App\Encounter::where('fldencounterval', $encounterId)
			->with(['patientInfo', 'PatFindings', 'consultant'])
			->first();
	}

	/**
	 * @param $encounterId
	 * @return bool
	 */
	public static function checkIfDischarged($encounterId)
	{
		$enpatient = Encounter::where('fldencounterval', $encounterId)->first();
		if (isset($enpatient->fldadmission) && $enpatient->fldadmission == 'Discharged') {
			return true;
		}
		return false;
	}

	/**
	 * @param null $fldfood
	 * @return \Illuminate\Support\Collection
	 */
	public static function GetFldFoodId($fldfood = NULL)
	{
		$foodcontent = DB::table('tblfoodcontent')->where('fldfood', $fldfood)->distinct()->orderBy('fldfoodid', 'ASC')->get();
		return $foodcontent;
	}

	/**
	 * @param $encounterId
	 */
	public static function encounterQueue($encounterId)
	{

		$encounterIds = Options::get('last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('last_encounter_id', serialize($arrayEncounter));
	}


	/**
	 * @param $encounterId
	 */
	public static function deliveryEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('delivery_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('delivery_last_encounter_id', serialize($arrayEncounter));
	}


	/**
	 * @param $encounterId
	 */

	public static function majorEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('major_procedure_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('major_procedure_last_encounter_id', serialize($arrayEncounter));
	}

	/**
	 * @param $encounterId
	 */

	public static function haemodialysisEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('haemodialysis_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('haemodialysis_last_encounter_id', serialize($arrayEncounter));
	}

	/**
	 * @param $encounterId
	 */

	public static function dischargeEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('discharge_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('discharge_last_encounter_id', serialize($arrayEncounter));
	}

	/**
	 * @param $encounterId
	 */
	public static function inpatientEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('inpatient_last_encounter_id');
		$arrayEncounter = array();
		if (isset($encounterIds) && $encounterIds != '') {
			$arrayEncounter = unserialize($encounterIds);
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('inpatient_last_encounter_id', serialize($arrayEncounter));
	}


	/**
	 * @param $encounterId
	 */
	public static function emergencyEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('emergency_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if (!empty($arrayEncounter)) {
			if ((is_array($arrayEncounter) || is_object($arrayEncounter))) {
				if (count($arrayEncounter)) {
					if (!in_array($encounterId, $arrayEncounter)) {
						if (count($arrayEncounter) > 10) {
							array_shift($arrayEncounter);
						}
						array_push($arrayEncounter, $encounterId);
					}
				}
			} else {
				$arrayEncounter[] = $encounterId;
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}

		Options::update('emergency_last_encounter_id', serialize($arrayEncounter));
	}


	/**
	 * @param $encounterId
	 */
	public static function eyeEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('eye_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('eye_last_encounter_id', serialize($arrayEncounter));
	}

	/**
	 * @param $encounterId
	 */
	public static function dentalEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('dental_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('dental_last_encounter_id', serialize($arrayEncounter));
	}

	/**
	 * @param $encounterId
	 */
	public static function entEncounterQueue($encounterId)
	{

		$encounterIds = Options::get('ent_last_encounter_id');
		if (isset($encounterIds) && $encounterIds != '') {
			// die('sdfsd');
			$arrayEncounter = unserialize($encounterIds);
		} else {
			$arrayEncounter = array();
		}

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10) {
						array_shift($arrayEncounter);
					}
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else {
			$arrayEncounter[] = $encounterId;
		}
		Options::update('ent_last_encounter_id', serialize($arrayEncounter));
	}


	/**
	 * @param $session_key
	 * @param $encounterId
	 */
	public static function moduleEncounterQueue($session_key, $encounterId)
	{
		$encounterIds = Options::get($session_key);
		if (isset($encounterIds) && $encounterIds != '')
			$arrayEncounter = unserialize($encounterIds);
		else
			$arrayEncounter = array();

		if ((is_array($arrayEncounter) || is_object($arrayEncounter)) && !empty($arrayEncounter)) {
			if (count($arrayEncounter)) {
				if (!in_array($encounterId, $arrayEncounter)) {
					if (count($arrayEncounter) > 10)
						array_shift($arrayEncounter);
					array_push($arrayEncounter, $encounterId);
				}
			}
		} else
			$arrayEncounter[] = $encounterId;

		Options::update($session_key, serialize($arrayEncounter));
	}


	/**
	 * Populate contents for pdf
	 */
	public static function contentsForOPDPDF()
	{
		$array = array(
			"content_1" => 'Advice on Discharge',
			"content_2" => 'Bed Transitions',
			"content_3" => 'Cause of Admission',
			"content_4" => 'Clinical Findings',
			"content_5" => 'Clinical Notes',
			"content_12" => 'Condition at Discharge',
			"content_14" => 'Consultations',
			"content_15" => 'Course of Treatment',
			"content_16" => 'Delivery Profile',
			"content_17" => 'Demographics',
			"content_18" => 'Discharge examinations',
			"content_19" => 'Discharge Medication',
			"content_20" => 'Drug Allergy',
			"content_21" => 'Equipments Used',
			"content_22" => 'Essential examinations',
			"content_23" => 'Extra Procedures',
			"content_24" => 'Final Diagnosis',
			"content_25" => 'IP Monitoring',
			"content_26" => 'Initial Planning',
			"content_27" => 'Investigation Advised',
			"content_28" => 'Laboratory Tests',
			"content_29" => 'Major Procedures',
			"content_30" => 'Medication History',
			"content_31" => 'Medication Used',
			"content_32" => 'Minor Procedures',
			"Name" => 'OPD Sheet',
			"content_34" => 'Occupational History',
			"content_35" => 'Personal History',
			"content_36" => 'Planned Procedures',
			"content_37" => 'Prominent Symptoms',
			"content_38" => 'Provisional Diagnosis',
			"content_39" => 'Radiological Findings',
			"content_40" => 'Social History',
			"content_41" => 'Structured examinations',
			"content_42" => 'Surgical History',
			"content_43" => 'Therapeutic Planning',
			"content_44" => 'Treatment Advised',
			"content_33" => 'Triage examinations',
			"HeaderType" => 'True',
			"BodyType" => 'True',
			"FooterType" => 'True',
		);
		Options::update('opd_pdf_options', serialize($array));
	}


	/**
	 * Get computer name as per user logged in
	 * @return mixed
	 */
	public static function getCompName()
	{

		try {
			return Session::has('selected_user_hospital_department') ? Session::get('selected_user_hospital_department')->fldcomp : 'comp01';
		} catch (\GearmanException $e) {
			return "comp01";
		}
	}

	/**
	 * Get list of all comp name
	 * @return mixed
	 */
	public static function getAllCompName()
	{
		// return AccessComp::distinct('name')->pluck('name');
		return HospitalDepartment::distinct('name')->pluck('name');
	}


	/**
	 * Get list of all comp name
	 * @return mixed
	 */
	public static function getDepartmentByCategory($cat)
	{
		return Department::select('fldid', 'flddept')->where([
			['fldcateg', 'LIKE', $cat],
			['fldstatus', 1],
		])->get();
	}

	/**
	 *
	 */
	public static function GetFoodlists()
	{
	}


	/**
	 * @return mixed
	 */
	public static function getDeliveredTypeList()
	{
		return \App\Delivery::select('flditem')->get();
	}

	/**
	 * @return string[]
	 */
	public static function getDeliveredBabyList()
	{
		return ['Fresh Still Birth', 'Live Baby', 'Macerated Still Birth'];
	}

	/**
	 * @return mixed
	 */
	public static function getComplicationList()
	{
		return \App\Delcomplication::select('flditem')->get();
	}

	/**
	 * @return string[]
	 */
	public static function getGenders()
	{
		return ['Male', 'Female', 'Others'];
	}

	/**
	 * Job record insertion according to module
	 * @param null $fromName
	 * @param null $fromLabel
	 * @return bool
	 */
	public static function jobRecord($fromName = null, $fromLabel = null)
	{
		try {
			$jobRecord['fldindex'] = date('YmdHis') . ':' . Auth::guard('admin_frontend')->user()->fldcode . ':63000666';
			$jobRecord['fldfrmname'] = $fromName;
			$jobRecord['fldfrmlabel'] = $fromLabel;
			$jobRecord['flduser'] = Auth::guard('admin_frontend')->user()->flduserid;
			$jobRecord['fldcomp'] = Helpers::getCompName();
			$jobRecord['fldentrytime'] = date("Y-m-d H:i:s");
			$jobRecord['fldexittime'] = NULL;
			$jobRecord['fldpresent'] = '1';
			$jobRecord['fldhostuser'] = get_current_user();
			$jobRecord['fldhostip'] = NULL;
			$jobRecord['fldhostname'] = gethostname();

			$MAC = exec('getmac');
			$MAC = strtok($MAC, ' ');

			$jobRecord['fldhostmac'] = $MAC;
			$job = true;
			//        $job = JobRecord::insert($jobRecord);
			return $job;
		} catch (\GearmanException $e) {
		}
	}

	/**
	 * @param $dob
	 * @return string
	 */
	public static function ageCalculation($dob)
	{
		$age = \Carbon\Carbon::parse($dob)->diff(\Carbon\Carbon::now())
			->format('%y yrs, %m m and %d d');
		return $age ?? '';
	}


	/**
	 * @return string[]
	 */
	public static function getChiefComplationDuration()
	{
		return ['Hours', 'Days', 'Months', 'Years'];
	}

	/**
	 * @return string[]
	 */
	public static function getChiefComplationQuali()
	{
		return ['Left Side', 'Right Side', 'Both Side', 'Episodes', 'On/Off', 'Present'];
	}

	/**
	 * @return mixed
	 */
	public static function getCurrentUserName()
	{
		return Auth::guard('admin_frontend')->user()->flduserid;
	}

	/**
	 * @param null $category
	 * @return mixed
	 */
	public static function getPathoCategory($category = NULL)
	{
		$data = \App\Pathocategory::select("flclass")->orderBy("flclass");
		if ($category)
			$data->where("fldcategory", "like", "%$category%");

		return $data->get();
	}

	/**
	 * @param null $category
	 * @return mixed
	 */
	public static function getWhereInForCategory($category = NULL)
	{
		$category = $category ? "%$category%" : '%%';
		$tests = \App\Test::where('fldcategory', 'like', $category)->distinct('fldtestid')->pluck('fldtestid')->toArray();
		$laboratoryByCategroy = \App\GroupTest::whereIn('fldtestid', $tests)->distinct('fldgroupname')->pluck('fldgroupname')->toArray();

		return $laboratoryByCategroy;
	}

	/**
	 * @param null $category
	 * @return mixed
	 */
	public static function getWhereInForRadioCategory($category = NULL, $returnradio = FALSE)
	{
		$category = $category ?: '';
		$category = "%$category%";

		$exams = \App\Radio::where('fldcategory', 'like', $category)->pluck('fldexamid')->toArray();
		if ($returnradio)
			return $exams;
		return \App\GroupRadio::whereIn('fldtestid', $exams)->pluck('fldgroupname')->toArray();
	}

	/**
	 * @return mixed
	 */
	public static function getSampleTypes()
	{
		return \App\Sampletype::select('fldsampletype')->orderBy('fldsampletype')->get();
	}

	public static function getMethodsByCategory($fldcateg = 'Test')
	{
		return \App\TestMethod::select('fldmethod')->where('fldcateg', $fldcateg)->orderBy('fldmethod')->get();
	}

	/**
	 * @return mixed
	 */
	public static function getTestCondition()
	{
		return \App\TestCondition::select('fldtestcondition')->orderBy('fldtestcondition')->get();
	}

	/**
	 * @param $form
	 * @return bool|int
	 */
	public static function getCurrentRole($form)
	{

		$current_id = Auth::guard('admin_frontend')->user()->id;
		if ($form == 'inpatient') {
			$data = \App\CogentUsers::select("fldipconsult")->where('id', $current_id)->first();
			return isset($data->fldipconsult) ?? 0;
		} else {
			$data = \App\CogentUsers::select("fldopconsult")->where('id', $current_id)->first();
			return isset($data->fldipconsult) ?? 0;
		}
	}

	/**
	 * @return string[]
	 */
	public static function getVisibilities()
	{
		return ['Visible', 'Hidden'];
	}

	/**
	 * @return string[]
	 */
	public static function getMethods()
	{
		return ['Regular'];
	}

	/**
	 * @return \App\AssetsName[]|\Illuminate\Database\Eloquent\Collection
	 */
	public static function getAssetNames()
	{
		return \App\AssetsName::all();
	}

	/**
	 * @return mixed
	 */
	public static function getSuppliers()
	{
		return \App\Supplier::select('fldsuppname')->where('fldactive', 'Active')->get();
	}

	public static function getSuppliersinfo($suppname)
	{
		return \App\Supplier::select()->where('fldsuppname', $suppname)->first();
	}

	public static function getSuppName($fldstockno)
	{
		return \App\Purchase::select('fldsuppname', 'fldreference')->where('fldstockno', $fldstockno)->first();
	}
	/**
	 * @param $fldtype
	 * @param bool $update
	 * @return mixed
	 */
	public static function getNextAutoId($fldtype, $update = FALSE, $appendPrefix = FALSE)
	{
		$nextid = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
		if ($nextid) {
			$nextid = $nextid->fldvalue;
			if ($update) {
				if ($fldtype == 'InvoiceNo') {
					$dateToday = Carbon::now();
					$year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
					$count = PatBilling::where('fldbillno', 'like', "%-" . $year->fldname . "-%")->count();
					if ($count == 0) {
						$nextid = 1;
						\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
					} else {
						++$nextid;
						\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
					}
				} else {
					++$nextid;
					\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
				}
			}
		} else {
			$nextid = 1;
			\App\AutoId::insert([
				'fldtype' => $fldtype,
				'fldvalue' => $nextid,
			]);
		}

		if ($appendPrefix) {
			$prefix = Options::get('prefix_text_for_sample_id');
			return ($prefix) ? "{$prefix}-{$nextid}" : $nextid;
		}

		return $nextid;
	}

	public static function getNextAutoIdSympleType($fldtype, $update = FALSE)
	{
		$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();

		// dd($dateToday);
		if ($fldtype == 'DailySampleNo') {
			$dateToday = Carbon::now()->format('Y-m-d');
			// $dateToday = '2022-03-11';
			if ($nextid_data) {
				if ($nextid_data->date != $dateToday) {
					\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => 1, 'date' => $dateToday]);
				}
				$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
				$nepali_today_date = self::dateEngToNepdash($nextid_data->date);
				$nextid = $nepali_today_date->year . '-' . $nepali_today_date->month . '-' . $nepali_today_date->date . '-' . $nextid_data->fldvalue;
				if ($update) {
					$nextid = $nextid_data->fldvalue;
					if ($nextid_data->date == $dateToday) {
						++$nextid;
						\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
					}
				}
			} else {
				$today_date = self::dateEngToNepdash($dateToday);
				$nextid = $today_date->year . '-' . $today_date->month . '-' . $today_date->date . '-' . 1;
				\App\AutoId::insert([
					'fldtype' => $fldtype,
					'fldvalue' => 1,
					'date' => $dateToday,
				]);
			}
			// dd($nextid);
		} elseif ($fldtype == 'MonthlySampleNo') {
			$dateToday = Carbon::now()->format('Y-m-d');
			// $dateToday = '2022-03-15';
			$nepali_today_month = self::dateEngToNepdash($dateToday);
			if ($nextid_data) {
				$today_month = self::dateEngToNepdash($nextid_data->date);
				if ($nepali_today_month->month != $today_month->month) {
					\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => 1, 'date' => $dateToday]);
				}
				$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
				$today_month = self::dateEngToNepdash($nextid_data->date);
				$nextid = $today_month->year . '-' . $today_month->month . '-' . $nextid_data->fldvalue;
				if ($update) {
					$nextid = $nextid_data->fldvalue;
					if ($nepali_today_month->month == $today_month->month) {
						++$nextid;
						\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
					}
				}
			} else {
				$today_month = self::dateEngToNepdash($dateToday);
				$nextid =  $today_month->year . '-' . $today_month->month . '-' . 1;
				\App\AutoId::insert([
					'fldtype' => $fldtype,
					'fldvalue' => 1,
					'date' => $dateToday,
				]);
			}
		} elseif ($fldtype == 'YearlySampleNo') {
			$dateToday = Carbon::now()->format('Y-m-d');
			// $dateToday = '2022-04-14';
			$nepali_today_year = self::dateEngToNepdash($dateToday);
			if ($nextid_data) {
				$today_year = self::dateEngToNepdash($nextid_data->date);
				if ($nepali_today_year->year != $today_year->year) {
					\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => 1, 'date' => $dateToday]);
				}
				$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
				$today_year = self::dateEngToNepdash($nextid_data->date);
				$nextid = $today_year->year . '-' . $nextid_data->fldvalue;
				if ($update) {
					$nextid = $nextid_data->fldvalue;
					if ($nepali_today_year->year == $today_year->year) {
						++$nextid;
						\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
					}
				}
			} else {
				$today_year = self::dateEngToNepdash($dateToday);
				$nextid =  $today_year->year . '-' . 1;
				\App\AutoId::insert([
					'fldtype' => $fldtype,
					'fldvalue' => 1,
					'date' => $dateToday,
				]);
			}
		}
		return $nextid;
	}
	public static function getAutoIncrementIdSympleType($fldtype, $current_sampleid_value)
	{
		// dump($current_sampleid_value);
		$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
		$check_sample_id = PatLabTest::where('fldsampleid', $current_sampleid_value)->first();
		if (is_null($check_sample_id)) {
			$date = self::dateEngToNepdash($nextid_data->date);
			if ($fldtype == 'DailySampleNo') {
				$nextid = $date->year . '-' . $date->month . '-' . $date->date . '-' . $nextid_data->fldvalue;
			} elseif ($fldtype == 'MonthlySampleNo') {
				$nextid = $date->year . '-' . $date->month . '-' . $nextid_data->fldvalue;
			} elseif ($fldtype == 'YearlySampleNo') {
				$nextid = $date->year . '-' . $nextid_data->fldvalue;
			}
			// dump($nextid);
			// return $nextid;
			return response()->json(['nextid' => $nextid, 'status' => false]);
		}

		$nextid = $nextid_data->fldvalue;
		if ($fldtype == 'DailySampleNo') {
			++$nextid;
			\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
			$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
			$nepali_today_date = self::dateEngToNepdash($nextid_data->date);
			$nextid = $nepali_today_date->year . '-' . $nepali_today_date->month . '-' . $nepali_today_date->date . '-' . $nextid_data->fldvalue;
		} elseif ($fldtype == 'MonthlySampleNo') {
			++$nextid;
			\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
			$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
			$today_month = self::dateEngToNepdash($nextid_data->date);
			$nextid = $today_month->year . '-' . $today_month->month . '-' . $nextid_data->fldvalue;
		} elseif ($fldtype == 'YearlySampleNo') {
			++$nextid;
			\App\AutoId::where('fldtype', $fldtype)->update(['fldvalue' => ($nextid)]);
			$nextid_data = \App\AutoId::where('fldtype', $fldtype)->lockForUpdate()->first();
			$today_year = self::dateEngToNepdash($nextid_data->date);
			$nextid = $today_year->year . '-' . $nextid_data->fldvalue;
		}
		return $nextid;
	}

	public static function getDepartmentByLocation($location)
	{
		$department = \App\Department::where('flddept', $location)->first();
		$retDepartment = 'IP';
		if ($department) {
			if ($department->fldcateg == 'Consultation')
				$retDepartment = 'OP';
			elseif ($department->fldcateg == 'Emergency')
				$retDepartment = 'ER';
		}
		return $retDepartment;
	}

	/**
	 * @return mixed
	 */
	public static function getPlannedConsultants($url_segment = NULL)
	{
		if ($url_segment) {
			$encounter = self::getSessionEncounterKey($url_segment);
		} else {
			$encounter = self::getSessionEncounterKey(\Request::segment(1));
		}
		if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
			$current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
			$plannedConsultants = Consult::where('fldencounterval', Session::get($encounter))
				->whereIn('fldconsultname', $current_user->department->pluck('flddept'))
				->where(function ($queryNested) {
					$queryNested->orWhere('fldstatus', 'Planned')
						->orWhere('fldstatus', 'Calling');
				})
				->get();
		} else {
			$plannedConsultants = Consult::where('fldencounterval', Session::get($encounter))
				->where(function ($queryNested) {
					$queryNested->orWhere('fldstatus', 'Planned')
						->orWhere('fldstatus', 'Calling');
				})
				->get();
		}

		return $plannedConsultants;
	}

	public static function getSessionEncounterKey($segment)
	{
		$encounter = '';
		if ($segment == 'patient')
			$encounter = 'encounter_id';
		elseif ($segment == 'dental')
			$encounter = 'dental_encounter_id';
		elseif ($segment == 'eye')
			$encounter = 'eye_encounter_id';
		elseif ($segment == 'emergency')
			$encounter = 'emergency_encounter_id';
		elseif ($segment == 'inpatient')
			$encounter = 'inpatient_encounter_id';
		elseif ($segment == 'majorprocedure')
			$encounter = 'major_procedure_encounter_id';
		elseif ($segment == 'delivery')
			$encounter = 'delivery_encounter_id';
		elseif ($segment == 'neuro')
			$encounter = 'neuro_encounter_id';
		elseif ($segment == 'physiotherapy')
			$encounter = 'physiotherapy_encounter_id';

		return $encounter;
	}

	/**
	 * @param $segment
	 * @return array
	 */
	public static function getPatientImage($segment)
	{
		if ($segment == 'patient') {
			$encounter = 'encounter_id';
		} elseif ($segment == 'dental') {
			$encounter = 'dental_encounter_id';
		} elseif ($segment == 'eye') {
			$encounter = 'eye_encounter_id';
		} elseif ($segment == 'ent') {
			$encounter = 'ent_encounter_id';
		} elseif ($segment == 'emergency') {
			$encounter = 'emergency_encounter_id';
		} elseif ($segment == 'inpatient') {
			$encounter = 'inpatient_encounter_id';
		} elseif ($segment == 'majorprocedure') {
			$encounter = 'major_procedure_encounter_id';
		} elseif ($segment == 'delivery') {
			$encounter = 'delivery_encounter_id';
		} elseif ($segment == 'neuro') {
			$encounter = 'neuro_encounter_id';
		} else {
			$encounter = '';
		}

		$encounterdata = Encounter::where('fldencounterval', Session::get($encounter))->first();

		if (isset($encounterdata) and $encounterdata->fldpatientval != '') {
			$patient = PersonImage::where('fldname', $encounterdata->fldpatientval)->where('fldcateg', 'Patient Image')->first();
		} else {
			$patient = array();
		}

		return $patient;
	}

	/**
	 * return mac group id from group mac
	 * @param GetMacAddress $macObj
	 * @return mixed
	 */
	public static function getLoggedInUserMacGroup()
	{
		/*$MAC = $macObj->GetMacAddr(PHP_OS);
        $MacGroupData = GroupMac::where('hostmac', $MAC)->first();*/
		return Session::has('department') ? Session::get('department') : '';
	}

	/**
	 * return map_comp if there is data
	 * @return mixed
	 */
	public static function getFldTarget()
	{
		if (Session::has('department')) {
			$accessComp = AccessComp::where('name', Session::get('department'))->first();
			return $accessComp->map_comp != NULL ? $accessComp->map_comp : Session::get('department');
		}
	}

	/**
	 * User Group
	 * @param GetMacAddress $macObj
	 * @return mixed
	 */
	public static function getGroupIdsFromLoggedInUser(GetMacAddress $macObj)
	{

		//$MAC = $macObj->GetMacAddr(PHP_OS);
		$MacGroupData = GroupMac::whereHas('request', function ($query) {
			$query->where('flduserid', Auth::guard('admin_frontend')->user()->flduserid);
			$query->where('category', Session::get('department'));
		})->first();

		$groupData = GroupComputerAccess::where('computer_access_id', $MacGroupData->group_id)->pluck('group_id');
		return $groupData;
	}

	/**
	 * @return array|false|string
	 */
	public static function getUserIP()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	/**
	 * @param $encounter
	 * @return string
	 */
	public static function getBedNumber($encounter)
	{
		$bedLocation = Departmentbed::select('fldbed')->where('fldencounterval', $encounter)->first();
		return $bedLocation->fldbed ?? "";
	}

	public static function getPatientBed($encounter)
	{
		$bedLocation = PatientDate::where(['fldencounterval' => $encounter, 'fldhead' => 'Location Update'])->first();
		if (!$bedLocation) {
			$bedLocation = PatientDate::where(['fldencounterval' => $encounter, 'fldhead' => 'Admitted'])->first();
		}
		return $bedLocation;
	}
	/**
	 * @param $encounter
	 * @return string
	 */
	public static function getDepartmentFromBED($bed)
	{
		$department = Departmentbed::select('flddept')->where('fldbed', $bed)->first();
		return $department->flddept ?? "";
	}

	/**
	 * @param $encounter
	 * @return string
	 */
	public static function getBillingMode($encounter)
	{
		$billingMode = Encounter::select('fldbillingmode')->where('fldencounterval', $encounter)->first();
		return $billingMode->fldbillingmode ?? "";
	}


	/**
	 * @param $userId
	 * @return bool
	 */
	public static function showSignature($userId)
	{
		$userData = CogentUsers::select('signature_image')->where('fldsigna', 1)->where('id', $userId)->first();
		if ($userData && count($userData) > 0) {
			return $userData->signature_image;
		} else {
			return false;
		}
	}


	/**
	 * @return array
	 */
	public static function getInitialDiagnosisCategory()
	{
		try {
			$handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
			$data = [];
			while ($csvLine = fgetcsv($handle, 1000, ";")) {
				if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
					$data[] = [
						'code' => trim($csvLine[1]),
						'name' => trim($csvLine[3]),
					];
				}
			}
			//sort($data);
			usort($data, function ($a, $b) {
				return $a['name'] <=> $b['name'];
			});
			// dd($data);
			return $data;
		} catch (\Exception $exception) {
			/*return response()->json(['status' => 'error', 'data' => []]);*/
			return [];
		}
	}

	/**
	 * @param $patientId
	 * @return string
	 */
	public static function generateQrCode($patientId)
	{
		return DNS2D::getBarcodeHTML($patientId, 'QRCODE', 2, 2);
	}

	/**
	 * @param $patientId
	 * @return string
	 */
	public static function generateQrCodeQr($patientId)
	{
		return DNS2D::getBarcodeSVG($patientId, 'QRCODE', 3, 3);
	}

	/**
	 * @param $str
	 * @return string
	 */
	public static function GetTextBreakString($str)
	{
		$result = wordwrap($str, 8, '-', true);
		return $result;
	}


	/**
	 * @param $date
	 * @return mixed
	 */
	public static function dateEngToNep_queue($date)
	{
		/*check if date is valid*/
		$start_ts = strtotime('1900-01-01');
		$end_ts = strtotime('3000-01-01');
		$user_ts = strtotime($date);
		/*check if date is valid*/
		if (($user_ts >= $start_ts) && ($user_ts <= $end_ts)) {
			$nepali_calender = new Nepali_Calendar();
			$dateExplode = explode('-', $date);
			//dd($dateExplode);
			$date_day = $dateExplode[2];
			$date_month = $dateExplode[1];
			$date_year = $dateExplode[0];
			$date_nepali = $nepali_calender->eng_to_nep($date_year, $date_month, $date_day);

			return json_decode(json_encode($date_nepali));
		} else {
			return false;
		}
	}

	/**
	 * @param $formName
	 * @return array
	 */
	public static function getSignature($formName)
	{
		$signatureImages = SignatureForm::where('form_name', $formName)->with(['user_signature'])->get();
		$data = [];

		$dataArray = [];
		foreach ($signatureImages as $image) {
			if ($image->user_signature) {
				$data['image'] = $image->user_signature->signature_image;
				$data['name'] = $image->user_signature->FullName;
				$data['designation'] = $image->user_signature->fldcategory;
				$data['nmc'] = $image->user_signature->nmc;
				$data['nhbc'] = $image->user_signature->nhbc;
				$dataArray[$image->position][] = $data;
			}
		}
		return $dataArray;
	}

	public static function getSignatures($users)
	{
		$signatures = CogentUsers::whereIn('username', $users)->get();
		return $signatures;
	}

	/**
	 * @param $brnadid
	 * @return mixed
	 */
	public static function getCurrentQty($brnadid)
	{
		$result = \DB::table('tblentry')->where('fldstockid', $brnadid)->where('fldqty', '>', 0)->sum('fldqty');
		// dd($result);
		return $result;
	}

	/**
	 * @param $request
	 * @return bool|string
	 */
	public static function getModuleNameForSignature($request)
	{
		if (request()->has('laboratory'))
			return 'laboratory';
		elseif (request()->has('dental'))
			return 'dental';
		elseif (request()->has('eye'))
			return 'eye';
		elseif (request()->has('opd'))
			return 'opd';
		elseif (request()->has('ent'))
			return 'ent';
		elseif (request()->has('ipd'))
			return 'ipd';
		elseif (request()->has('emergency'))
			return 'emergency';
		elseif (request()->has('major'))
			return 'major';
		elseif (request()->has('delivery'))
			return 'delivery';
		elseif (request()->has('radiology'))
			return 'radiology';
		elseif (request()->has('major_procedure'))
			return 'major procedure';

		return FALSE;
	}

	/**
	 * @return mixed
	 */
	public static function getBillingModes()
	{
		return \App\BillingSet::pluck('fldsetname');
	}

	/**
	 * @return string[]
	 */
	public static function getCountries()
	{
		$countries = \DB::table('tblcountries')->select('fldname')->get();

		return $countries;
	}

	/**
	 * @return \App\Discount[]|\Illuminate\Database\Eloquent\Collection
	 */
	public static function getDiscounts($billingmode = null)
	{
		if ($billingmode === null) {
			return \App\Discount::all();
		} else {
			return \App\Discount::where('fldbillingmode', 'like', $billingmode)->get();
		}
	}

	/**
	 * @return Department[]|\Illuminate\Database\Eloquent\Collection
	 */
	public static function getDepartments()
	{
		return \App\Department::all();
	}

	/**
	 * @return \App\EthnicGroup[]|\Illuminate\Database\Eloquent\Collection
	 */
	public static function getEthinicGroups()
	{
		return \App\EthnicGroup::all();

		// return ['Bhramin', 'Chettri', 'Janajati'];
	}

	/**
	 * @return \App\Relation[]|\Illuminate\Database\Eloquent\Collection
	 */
	public static function getRelations()
	{
		return \App\Relation::all();
	}

	/**
	 * @return \App\Surname[]|\Illuminate\Database\Eloquent\Collection
	 */
	public static function getSurnames()
	{
		return \App\Surname::where('flditem', '<>', '')->get();
	}

	/**
	 * @return string[]
	 */
	public static function getInsurances()
	{
		return \App\Insurancetype::all();
	}

	/**
	 * @return string[]
	 */
	public static function getBloodGroups()
	{
		return ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', "AB-"];
	}

	/**
	 * @return mixed
	 */
	public static function getConsultantList()
	{
		return CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
	}

	/**
	 * @return mixed
	 */
	public static function getDepartmentBed()
	{
		$departmentFloor = Bedfloor::with('departmentBed')->orderBy('order_by', "ASC")->get();
		$data['departmentFloor'] = $departmentFloor;
		$data['departmentBedList'] = Departmentbed::orderBy('flddept', "DESC")->orderBy('fldfloor', "ASC")->get();
		return $data;
	}

	/**
	 * @param $number
	 * @return string
	 */
	public static function numberToNepaliWords($number)
	{
		$is_minus = ($number != abs($number));
		$number = abs($number);
		$no = floor($number);
		$point = round($number - $no, 2) * 100;
		$hundred = null;
		$digits_1 = strlen($no);
		$i = 0;
		$str = array();

		$words = array('0' => '', '1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six', '7' => 'Seven', '8' => 'Eight', '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen', '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen', '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty', '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty', '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty', '90' => 'Ninety');
		$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
		while ($i < $digits_1) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
			} else {
				$str[] = null;
			}
		}
		$str = array_reverse($str);
		$result = implode('', $str);
		$points = ($point) ? ". " . $words[$point / 10] . " " . $words[$point = $point % 10] : '';
		$paisa = ($points != "" || $points != null) ? $points . " Paisa Only." : ' Only.';
		if ($result == '') {
			$result = 'Zero ';
		}
		return $result . "Rupees" . $paisa;
	}

	public static function getDispensingDepartments()
	{
		return [
			'InPatient', 'OutPatient', 'ER'
		];
	}

	public static function getFrequencies()
	{
		return [
			'1', '2', '3', '4', '6', 'PRN', 'SOS', 'stat', 'AM', 'HS', 'pre', 'post', 'Hourly', 'Alt Day', 'Weekly', 'BiWeekly', 'TriWeekly', 'Monthly', 'Yearly', 'Tapering',
		];
	}

	public static function getItemType($isCashier = FALSE)
	{
		if ($isCashier)
			$itemTypes = ['Equipment', 'Others', 'Procedures', 'Radio', 'Services', 'Test'];
		else
			$itemTypes = ['Extra Items', 'Medicines', 'Surgicals'];

		return $itemTypes;
	}

	public static function getDispenserRoute()
	{
		return [
			'oral' => 'outpatient',
			'liquid' => 'outpatient',
			'fluid' => 'outpatient',
			'injection' => 'outpatient',
			'resp' => 'outpatient',
			'topical' => 'outpatient',
			'eye/ear' => 'outpatient',
			'anal/vaginal' => 'outpatient',
			'suture' => 'outpatient',
			'msurg' => 'outpatient',
			'ortho' => 'outpatient',
			'extra' => 'outpatient',
			'Group' => 'outpatient',

			'IVpush' => 'other',
			'CIV' => 'other',
			'IIV' => 'other',
			'SC' => 'other',
			'IM' => 'other',
			'IT' => 'other',
			'IDer' => 'other',
			'ICar' => 'other',
			'Isyn' => 'other',
		];
	}

	public static function getUserSelectedHospitalDepartmentIdSession()
	{
		return Session::has('selected_user_hospital_department') ? Session::get('selected_user_hospital_department')->id : null;
	}

	public static function getUserSelectedHospitalDepartmentSession()
	{
		return Session::has('selected_user_hospital_department') ? Session::get('selected_user_hospital_department') : null;
	}

	public static function taxGroup()
	{
		return TaxGroup::all();
	}


	public static function encodePassword($password)
	{
		$generated_pwd = "";
		for ($i = 0; $i < strlen($password); $i++) {
			$current_string = substr($password, $i, 1);
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

	public static function decodePassword($encodedPassword)
	{
		$encodedPassword = str_split($encodedPassword, 3);
		$passwordString = "";
		foreach ($encodedPassword as $pwd)
			$passwordString .= chr($pwd);

		return $passwordString;
	}

	public static function getUniquePatientUsetname($username)
	{
		$all_username = \App\PatientCredential::select('fldusername')->get()->pluck('fldusername')->toArray();
		$i = 0;

		do {
			if ($i != 0)
				$username .= $i;
			$i++;
		} while (in_array($username, $all_username));

		return $username;
	}

	public static function getTranslationForLabel($isOpd)
	{
		$keys = ['Cap', 'Every', 'Hour', 'Difference', 'Day'];
		if ($isOpd)
			$keys = \App\Locallabel::whereIn('fldengcode', $keys)->get()->pluck('fldlocaldire', 'fldengcode');
		else
			$keys = array_combine($keys, $keys);

		return $keys;
	}


	public static function getPatientDetails()
	{
		$userPatientVal = \Auth::guard('patient_admin')->user()->fldpatientval;
		return PatientInfo::where('fldpatientval', $userPatientVal)->first();
	}

	public static function appendExpiryStatus($allData, $appendText = FALSE, $columname = 'fldexpiry')
	{
		foreach ($allData as &$data) {
			$data->expiryStatus = self::getExpiryStatus($data->{$columname}, $appendText);
		}

		return $allData;
	}

	public static function getExpiryStatus($date, $getText = FALSE)
	{
		$today = strtotime(date('Y-m-d'));
		$date = strtotime($date);
		if ($today >= $date)
			$expiryStatus = 'expire';
		else {
			$nearExpiryDay = Options::get('near_expire_duration', 0);
			$day = $date - $today;
			$day = (($day) / (24 * 60 * 60));
			if ($day <= $nearExpiryDay)
				$expiryStatus = 'near_expire';
			else
				$expiryStatus = 'non_expire';
		}

		if ($getText)
			return $expiryStatus;

		return Options::get("{$expiryStatus}_color_code");
	}

	public static function getComputerList()
	{
		return \App\HospitalDepartment::select('name', 'fldcomp')->get();
	}

	public static function getDiagnogroup()
	{
		$digno_group = DiagnoGroup::select('fldgroupname')->distinct()->get();
		return $digno_group;
	}

	public static function getTotalDiet($billingmode, $category, $from, $to)
	{
		$totalpatients = DB::table('tblextradosing as ed')
			->join('tblencounter as e', 'e.fldencounterval', '=', 'ed.fldencounterval')
			->where('e.fldbillingmode', $billingmode)
			->where('ed.fldcategory', $category)
			->whereBetween('ed.flddosetime', [$from, $to])
			->get()->count();
		// echo $totalpatients; exit;
		return $totalpatients;
	}

	public static function getSumTotalDiet($billingmode, $from, $to)
	{
		$total = DB::table('tblextradosing as ed')
			->join('tblencounter as e', 'e.fldencounterval', '=', 'ed.fldencounterval')
			->where('e.fldbillingmode', $billingmode)
			->whereBetween('ed.flddosetime', [$from, $to])
			->get()->count();
		// echo $totalpatients; exit;
		return $total;
	}

	public static function checkRedirectLastEncounter()
	{

		if (Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0) {
			return "Yes";
		}

		$redirectData = CheckRedirectLastEncounter::where('user_id', Auth::guard("admin_frontend")->id())->first();
		if ($redirectData) {
			return $redirectData->fld_redirect_encounter;
		} else {
			return "No";
		}
	}

	public static function getConsultReferBy($encounterId)
	{
		$userRefer = Consult::where('fldencounterval', $encounterId)->where('is_refer', 1)->orderBy('fldconsulttime', 'DESC')->with('userRefer')->first();
		if ($userRefer && $userRefer->userRefer) {
			$data['fullname'] = $userRefer->userRefer->fullname;

			$consultName = Consult::where('fldencounterval', $encounterId)->where('is_refer', 1)->where('flduserid', $userRefer->fldorduserid)->orderBy('fldconsulttime', 'DESC')->first();
			$data['consultname'] = $consultName->fldconsultname ?? "";
		} else {
			$data['fullname'] = '';
			$data['consultname'] = '';
		}
		return $data;
	}

	public static function getPermissionsByModuleName($module_name)
	{
		if ($module_name == "opd") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('outpatient-form') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-eye') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-dental') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-ent');
		} elseif ($module_name == "ipd") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('inpatient') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-major-procedures') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-bed-occupancy') || \App\Utils\Permission::checkPermissionFrontendAdmin('delivery-form') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-icu');
		} elseif ($module_name == "billing") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('view-registration-form') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-registration-list') ||
				\App\Utils\Permission::checkPermissionFrontendAdmin('view-cash-billing') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-dispensing-form') || \App\Utils\Permission::checkPermissionFrontendAdmin(
					'view-return-form'
				) || \App\Utils\Permission::checkPermissionFrontendAdmin('view-dispensing-list') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-deposit-form') ||
				\App\Utils\Permission::checkPermissionFrontendAdmin('view-tax-group') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-demand-form') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-bank-list')
				|| \App\Utils\Permission::checkPermissionFrontendAdmin('view-extra-receipt') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-cashier-package');
		} elseif ($module_name == "lab_status") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('view-test-addition') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-test-sampling') || \App\Utils\Permission::checkPermissionFrontendAdmin(
				'view-test-reporting'
			) || \App\Utils\Permission::checkPermissionFrontendAdmin('view-lab-verification') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-test-printing');
		} elseif ($module_name == "radiology") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('view-radio-addition') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-radio-sampling') ||
				\App\Utils\Permission::checkPermissionFrontendAdmin('view-radio-reporting') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-radio-verification') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-radio-xray') || \App\Utils\Permission::checkPermissionFrontendAdmin(
					'view-radio-printing'
				);
		} elseif ($module_name == "emergency") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('view-emergency');
		} elseif ($module_name == "account") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('view-fiscalyear-setting') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-register-setting') || \App\Utils\Permission::checkPermissionFrontendAdmin(
				'view-billing-mode'
			) || \App\Utils\Permission::checkPermissionFrontendAdmin('view-patient-disc-mode') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-autobilling');
		} elseif ($module_name == "pharmacy") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('view-medicine-generic-information') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-medicine-info') ||
				\App\Utils\Permission::checkPermissionFrontendAdmin('view-surgicals-info') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-extra-item-info') || \App\Utils\Permission::checkPermissionFrontendAdmin(
					'view-labeling'
				) || \App\Utils\Permission::checkPermissionFrontendAdmin('view-medicine-grouping') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-pharmacy-item-activation') ||
				\App\Utils\Permission::checkPermissionFrontendAdmin('view-stock-out-order');
		} elseif ($module_name == "nutrition") {
			$permissions = \App\Utils\Permission::checkPermissionFrontendAdmin('view-nutrition-information') || \App\Utils\Permission::checkPermissionFrontendAdmin('view-food-mixture') ||
				\App\Utils\Permission::checkPermissionFrontendAdmin('view-nutrition-requirements');
		}
		return $permissions;
	}

	public static function getEncounterDietDetail($encounter)
	{
		// echo $encounter; exit;
		$dietdetail = array();
		$extradosingdetail = \App\ExtraDosing::select('fldcategory')->where('fldencounterval', $encounter)->get();
		if (isset($extradosingdetail) and count($extradosingdetail) > 0) {
			foreach ($extradosingdetail as $et) {
				$dietdetail[] = $et->fldcategory;
			}
		}
		return $dietdetail;
	}

	public static function getEncounterDietDetailExtra($encounter)
	{
		// echo $encounter; exit;
		$extradietdetail = array();
		$extraitemdosingdetail = \App\ExtraDosing::select('flditem')->where('fldencounterval', $encounter)->get();
		if (isset($extraitemdosingdetail) and count($extraitemdosingdetail) > 0) {
			foreach ($extraitemdosingdetail as $ext) {
				$extradietdetail[] = $ext->flditem;
			}
		}
		return $extradietdetail;
	}

	public static function convertNumber($num = false)
	{
		$num = str_replace(array(',', ''), '', trim($num));
		if (!$num) {
			return false;
		}
		$num = (int)$num;
		$words = array();
		$list1 = array(
			'', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
			'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
		);
		$list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
		$list3 = array(
			'', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
			'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
			'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
		);
		$num_length = strlen($num);
		$levels = (int)(($num_length + 2) / 3);
		$max_length = $levels * 3;
		$num = substr('00' . $num, -$max_length);
		$num_levels = str_split($num, 3);
		for ($i = 0; $i < count($num_levels); $i++) {
			$levels--;
			$hundreds = (int)($num_levels[$i] / 100);
			$hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
			$tens = (int)($num_levels[$i] % 100);
			$singles = '';
			if ($tens < 20) {
				$tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '');
			} elseif ($tens >= 20) {
				$tens = (int)($tens / 10);
				$tens = ' and ' . $list2[$tens] . ' ';
				$singles = (int)($num_levels[$i] % 10);
				$singles = ' ' . $list1[$singles] . ' ';
			}
			$words[] = $hundreds . $tens . $singles . (($levels && (int)($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
		} //end for loop
		$commas = count($words);
		if ($commas > 1) {
			$commas = $commas - 1;
		}
		$words = implode(' ', $words);
		$words = preg_replace('/^\s\b(and)/', '', $words);
		$words = trim($words);
		$words = ucfirst($words);
		$words = $words . ".";
		return $words;
	}

	public static function getDepartmentAndComp()
	{
		$user = Auth::guard('admin_frontend')->user();
		if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
			$userdept = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->get();
			$departmentComp = HospitalDepartment::whereIn('id', $userdept->pluck('hospital_department_id'))->with('branchData')->get();
		} else {
			// $userdept = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->get();
			$departmentComp = HospitalDepartment::with('branchData')->get();
		}

		return $departmentComp;
	}

	//Need to get service added by anish
	public static function getService()
	{
		return Service::all();
	}


	public static function getFiscalYear()
	{
		$today_date = Carbon::now()->format('Y-m-d');
		$fiscal_year = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
		if (!isset($fiscal_year)) {
			$fiscal_year = new Year();
			$fiscal_year->fldname = Carbon::now()->format('Y');
			$fiscal_year->fldfirst = Carbon::now()->startOfYear();
			$fiscal_year->fldlast = Carbon::now();
		}
		return $fiscal_year;
	}

	public static function getFiscalYearId()
	{
		$today_date = Carbon::now()->format('Y-m-d');
		$fiscal_year = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first()->field;
		return $fiscal_year;
	}

	public static function getDetailByEncounter($encounter)
	{
		$patients = Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid')->with([
			'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
			'patientInfo.credential:fldpatientval,fldusername,fldpassword',
			'consultant:fldencounterval,fldorduserid',
			'consultant.userRefer:flduserid,firstname,middlename,lastname'
		])->where('fldencounterval', $encounter)->orderBy('fldregdate', 'DESC')->first();

		return $patients;
	}

	public static function getDepartmentFromCompID($compid)
	{
		if (isset($compid)) {
			$department = \App\HospitalDepartment::where('fldcomp', $compid)->with('branchData')->first();
			$result = json_decode(json_encode($department), true);
			if (isset($result['name'])) {
				$deptName = $result['name'];
			} else {
				$deptName = $compid;
			}
			if (isset($result['branch_data'])) {
				$string = $deptName . '(' . $result['branch_data']['name'] . ')';
			} else {
				$string = $deptName;
			}
			return $string;
		}
	}

	public static function getDepartmentFromComp($compid)
	{
		if (isset($compid)) {
			$department = \App\HospitalDepartment::where('fldcomp', $compid)->with('branchData')->first();
			$result = json_decode(json_encode($department), true);
			if (isset($result['name'])) {
				$deptName = $result['name'];
			} else {
				$deptName = $compid;
			}
			return $deptName;
		}
	}

	public static function getHospitalBranchID()
	{

		$branch = \Illuminate\Support\Facades\Auth::guard('admin_frontend')->user()->load('hospitalBranch')->id;
		if ($branch) {
			return $branch;
		}
	}

	public static function getAllAddress($encode = TRUE)
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

	#Nepali Rupees Format


	public static function formatnpr($input)
	{
		//CUSTOM FUNCTION TO GENERATE ##,##,###.##
		$dec = "";
		$pos = strpos($input, ".");
		if ($pos === false) {
			//no decimals
		} else {
			//decimals
			$dec = substr(round(substr($input, $pos), 2), 1);
			$input = substr($input, 0, $pos);
		}
		$num = substr($input, -3); //get the last 3 digits
		$input = substr($input, 0, -3); //omit the last 3 digits already stored in $num
		while (strlen($input) > 0) //loop the process - further get digits 2 by 2
		{
			$num = substr($input, -2) . "," . $num;
			$input = substr($input, 0, -2);
		}
		return $num . $dec;
	}

	#End Nepali Rupees Format


	public static function getParticularCategory($particularName)
	{
		$particularName = strtolower($particularName);
		$particulars = [
			'oral' => 'Medicines',
			'liquid' => 'Medicines',
			'fluid' => 'Medicines',
			'injection' => 'Medicines',
			'resp' => 'Medicines',
			'topical' => 'Medicines',
			'eye/ear' => 'Medicines',
			'anal/vaginal' => 'Medicines',
			'suture' => 'Surgicals',
			'msurg' => 'Surgicals',
			'ortho' => 'Surgicals',
			'extra' => 'Extra Items'
		];

		return isset($particulars[$particularName]) ? $particulars[$particularName] : '';
	}

	//TO send notification added by anish
	public static function labReportedToBeVerified($item)
	{
		$message = Options::get('lab_verification_notification_message');
		$group_ids = PermissionGroup::where('code', 'test-verification')
			->join('permission_references', 'permission_groups.permission_reference_id', 'permission_references.id')
			->get()->pluck('group_id');
		$user_ids = UserGroup::select('user_id')->whereIn('group_id', $group_ids)->get()->pluck('user_id');
		$data = [
			'item' => $item,
			'message' => $message ? strtr($message, ['{$test}' => $item]) : $item . ' has been reported. Please verify it.',
		];
		if ($user_ids) {
			foreach ($user_ids as $id) {
				$user = CogentUsers::find($id);
				$user->notify(new NearExpiryMedicine($user, $data));
			}
			return true;
		} else {
			return false;
		}
	}

	//TO send notification added by anish
	public static function labSampledToBeReported($item)
	{
		$message = Options::get('lab_reporting_notification_message');
		$group_ids = PermissionGroup::where('code', 'test-reporting')
			->join('permission_references', 'permission_groups.permission_reference_id', 'permission_references.id')
			->get()->pluck('group_id');
		$user_ids = UserGroup::select('user_id')->whereIn('group_id', $group_ids)->get()->pluck('user_id');
		$data = [
			'item' => $item,
			'message' => $message ? strtr($message, ['{$test}' => $item]) : $item . 'has been sampled. Please report it accordingly..',
		];
		if ($user_ids) {
			foreach ($user_ids as $id) {
				$user = CogentUsers::find($id);
				$user->notify(new NearExpiryMedicine($user, $data));
			}
			return true;
		} else {
			return false;
		}
	}


	#End Nepali Rupees Format
	public static function getBedCountByDepartment($department)
	{
		//$available_bed = Departmentbed::where('flddept', $department)->where('tbldepartmentbed.fldencounterval', '=', NULL)->count();
		$available_bed = Departmentbed::where('flddept', $department)->count();

		return $available_bed;
	}

	public static function getBedbookedCountByDepartment($department)
	{

		$booked_bed = Departmentbed::join('tblencounter', 'tblencounter.fldencounterval', '=', 'tbldepartmentbed.fldencounterval')->where('tbldepartmentbed.flddept', $department)->where('tbldepartmentbed.fldencounterval', '!=', NULL)->count();

		return $booked_bed;
	}

	public static function getavailablebed($department)
	{
		$available_bed = Departmentbed::where('flddept', $department)->where('tbldepartmentbed.fldencounterval', '=', NULL)->count();
		return $available_bed;
	}

	public static function getUserdepartmentInArray($user_id)
	{
		if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) != 0) {
			return UserDepartment::join('tbldepartment', 'tbldepartment.fldid', '=', 'department_users.department_id')
				->pluck('tbldepartment.flddept')->toArray();
		} else {
			return UserDepartment::join('tbldepartment', 'tbldepartment.fldid', '=', 'department_users.department_id')
				->where('department_users.user_id', $user_id)
				->pluck('tbldepartment.flddept')->toArray();
		}
	}

	public static function getAgeDetail($fldptbirday)
	{
		if (!empty($fldptbirday)) {
			$date = $fldptbirday;
			$date = \Carbon\Carbon::parse($date)->diff(\Carbon\Carbon::now())->format('%y, %m, %d');
			$date = explode(', ', $date);

			if ($date[0] > 0)
				$date = "{$date[0]} Y";
			elseif ($date[1] > 0)
				$date = "{$date[1]} M";
			elseif ($date[2] > 0)
				$date = "{$date[2]} D";
			return $date;
		} else
			return "0 D";
	}

	//Sends Firebase Cloud Notifications
	public static function sendNotification($titile, $message, $redirect_url)
	{
		if (!$titile || !$message) {
			return false;
		}

		$device_token = CogentUsers::whereNotNull('device_token')->pluck('device_token')->all();
		$SERVER_API_KEY = config('constants.firebase_notification_server_api_key');
		$data = [
			"registration_ids" => $device_token,
			"notification" => [
				"title" => $titile,
				"body" => $message,
				"icon" => asset('/images/cogent-logo.png'),
				"image" => asset('/images/cogent-logo.png'),
				'click_action' => $redirect_url,
			],
			//            "data" => ['title' => $titile,'body' => $message],
		];
		$payload = json_encode($data);

		$headers = [
			'Authorization: key=' . $SERVER_API_KEY,
			'Content-Type: application/json',
		];
		try {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$response = curl_exec($ch);
		} catch (\Exception $exception) {
			//            dd($exception);
		}
	}

	public static function getMenus()
	{
		if (self::isSuperAdmin()) {
			return self::allMenus();
		} else {
			return self::permittedMenus();
		}
	}


	public static function permittedMenus()
	{
		if (session('sidebar_menus')) {
			return session('sidebar_menus');
		}
		$group_ids = Helpers::getAuthUserGroups();
		if ($group_ids && count($group_ids) > 0) {
			$group_ids_str = implode(',', $group_ids);
		} else if ($default = Group::where('name', 'default')->first()) {
			$group_ids_str = $default->id;
		} else {
			$group_ids_str = 0;
		}
		$sidebar = DB::select("SELECT 
			LOWER(REPLACE(menu.mainmenu,  ' ', '-')) AS menu, menu.mainmenu, menu.submenu,
			LOWER(REPLACE(menu.submenu,  ' ', '-')) AS code, menu.route, menu.icon
			FROM sidebarmenu AS menu
            LEFT JOIN permission_references AS perm_ref ON perm_ref.code = CONCAT(LOWER(REPLACE(menu.submenu,  ' ', '-')),'-view')
            LEFT JOIN permission_groups AS perm_group ON perm_group.permission_reference_id = perm_ref.id
        WHERE menu.status=0 AND perm_group.group_id IN ($group_ids_str)
        ORDER BY order_by ASC;");
		return self::mapMenus($sidebar);
	}


	public static function allMenus()
	{
		if (session('sidebar_menus')) {
			return session('sidebar_menus');
		}
		$sidebar = DB::select("SELECT * FROM sidebarmenu WHERE status = 0 ORDER BY order_by ASC;");
		return self::mapMenus($sidebar);
	}

	public static function mapMenus($sidebar)
	{
		$data = [];
		$authorized_routes = [];
		foreach ($sidebar as $menu) {
			$icon = null;
			if ($menu->icon && !empty($menu->icon)) {
				if (!isset($data[$menu->mainmenu]['icon'])) {
					$icon = $menu->icon;
				} else if ($data[$menu->mainmenu]['icon']) {
					$icon = $data[$menu->mainmenu]['icon'];
				}
			} else {
				if (isset($data[$menu->mainmenu]['icon'])) {
					$icon  = $data[$menu->mainmenu]['icon'];
				}
			}
			$data[$menu->mainmenu]['mainmenu'] = $menu->mainmenu;
			$data[$menu->mainmenu]['icon'] = $icon;
			$data[$menu->mainmenu]["sub_menus"][] = [
				"mainmenu" => $menu->mainmenu,
				"submenu" => $menu->submenu,
				"route" => $menu->route,
				"icon" => $menu->icon
			];
			array_push($authorized_routes, $menu->route);
		}
		$sidebarMenus = array_values($data);
		session(['sidebar_menus' => $sidebarMenus]);
		session(['authorized_routes' => $authorized_routes]);
		return $sidebarMenus;
	}


	public static function  isSuperAdmin()
	{
		if (session('auth_guard_name')) {
			$user = session('auth_user');
			$is_superadmin = count($user->user_is_superadmin) > 0 ? true : false;
			session(['is_superadmin' => $is_superadmin]);
			return $is_superadmin;
		}
	}
	public static function getAuthUserGroups()
	{
		if (session('auth_guard_name')) {
			$user = session('auth_user');
			return $user->user_group ? $user->user_group->pluck('group_id')->toArray() : [];
		}
	}
	public static function syncAuthGuards()
	{
		if (Auth::guard('web_admin')->check()) {
			$user = Auth::guard('web_admin')->user();
			session(['auth_guard_name' => 'web_admin', 'auth_user' => $user]);
		} else if (Auth::guard('admin_frontend')->check()) {
			$user = Auth::guard('admin_frontend')->user();
			session(['auth_guard_name' => 'admin_frontend', 'auth_user' => $user]);
		} else if (Auth::guard('patient_admin')->check()) {
			$user = Auth::guard('patient_admin')->user();
			session(['auth_guard_name' => 'patient_admin', 'auth_user' => $user]);
		} else {
			session(['auth_guard_name' => '', 'auth_user' => []]);
		}
		if (!self::isSuperAdmin()) {
			$group_ids = self::getAuthUserGroups();
			$groups_to_string = implode(",", $group_ids);
			$permissions =  DB::select("SELECT code FROM permission_references AS pr
			LEFT JOIN permission_groups AS pg ON pg.permission_reference_id = pr.id 
			WHERE pg.group_id IN ($groups_to_string)");
			if ($permissions) session(['access_permissions' => collect($permissions)->pluck('code')->toArray()]);
		}
		self::getMenus();
	}

	public function getPatientLocationType($encounter_id)
	{
		$enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
		$department = '';
		if (isset($enpatient) and !empty($enpatient)) {
			$patientDepartmentbed = \App\DepartmentBed::select('fldbed', 'flddept')->where('fldencounterval', $encounter_id)->first();
			if (isset($patientDepartmentbed) and !empty($patientDepartmentbed)) {
				$patientDepartment = \App\Department::select('fldcateg')->where('flddept', $patientDepartmentbed->flddept)->first();
			} else {
				$patientDepartment = \App\Department::select('fldcateg')->where('flddept', $enpatient->fldcurrlocat)->first();
			}
			if (isset($patientDepartment) and !empty($patientDepartment)) {
				$department = $patientDepartment->fldcateg;
			} else {
				$department = '';
			}
		}
		return $department;
	}

	public static function dateToNepali($datetime, $appendTime = TRUE)
	{
		$returnDatetime = '';
		if ($datetime) {
			$datetime = explode(' ', $datetime);
			$returnDatetime = self::dateEngToNepdash($datetime[0])->full_date;

			if (isset($datetime[1]) && $datetime[1] && $appendTime)
				$returnDatetime .= " {$datetime[1]}";
		}

		return $returnDatetime;
	}

	public static function getLatestBedDetail($encounter)
	{
		$bedarr = [];

		$departmentbed = DB::table('tbldepartmentbed')
			->join('tbldepartment', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
			->select('tbldepartment.*', 'tbldepartmentbed.*')
			->where('tbldepartmentbed.fldencounterval', $encounter)
			->first();

		$bedarr['flddept'] = ($departmentbed) ? $departmentbed->flddept : '';
		$bedarr['fldbedtype'] = ($departmentbed) ? $departmentbed->fldbedtype : '';

		// if($departmentbed){
		//     $dept = '';
		//     $type = '';
		//     foreach($departmentbed as $t => $bed){
		//         $dept .= $bed->flddept;
		//     }
		// }

		return $bedarr;
	}

	public static function receivedamounttotalperbill($billno)
	{
		$sql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail where fldbillno like '%" . $billno . "%'";
		$totalamount = DB::select(
			$sql
		);

		$totalrecived = $totalamount ? $totalamount[0]->totaldepo : 0;

		return $totalrecived;
	}

	public static function prevamountperbill($billno)
	{
		$sql = "select sum(fldprevdeposit) as totalprev from tblpatbilldetail where fldbillno like '%" . $billno . "%'";
		$totalamount = DB::select(
			$sql
		);

		$totalprev = $totalamount ? $totalamount[0]->totalprev : 0;
		if ($totalprev == null) {
			$totalprev = 0;
		}
		return $totalprev;
	}

	public static function remainamountperbill($billno)
	{
		$sql = "select sum(fldcurdeposit) as totalcurr from tblpatbilldetail where fldbillno like '%" . $billno . "%'";
		$totalamount = DB::select(
			$sql
		);

		$totalcurr = $totalamount ? $totalamount[0]->totalcurr : 0;
		if ($totalcurr == null) {
			$totalcurr = 0;
		}
		return $totalcurr;
	}

	public static function receiveAmountAndPrevDepositPerbill($billno)
	{
		$sql = "select sum(fldreceivedamt) as totaldepo,sum(fldprevdeposit) as totalprev from tblpatbilldetail where fldbillno like '%" . $billno . "%'";
		$totalamount = DB::select(
			$sql
		);

		$totaldepo = $totalamount ? $totalamount[0]->totaldepo : 0;
		$totalprev = $totalamount ? $totalamount[0]->totalprev : 0;
		if ($totaldepo == null) {
			$totaldepo = 0;
		}
		if ($totalprev == null) {
			$totalprev = 0;
		}
		$data['receive'] = $totaldepo;
		$data['previous'] = $totalprev;
		return $data;
	}

	public static function getDischargeBill($encounter)
	{
		if (!$encounter) {
			return false;
		}
		$bill = PatBillDetail::where('fldencounterval', $encounter)->where('fldpayitemname', 'Discharge Clearence')->first();
		if ($bill) {
			return $bill->fldbillno;
		} else {
			return false;
		}
	}

	public static function getEncounterConsultant($encounter)
	{
		$name = '';
		$isdepartment = '';
		$department = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->get();

		if ($users) {
			foreach ($users as $u) {
				$isdepartment = Departmentbed::where('fldbed', $u->fldconsultname)->first();
				if (!empty($isdepartment)) {
					$department = $isdepartment->flddept;
				} else {
					$department = $u->fldconsultname;
				}

				$consult = $department . '-' . $u->firstname . ' ' . $u->middlename . ' ' . $u->lastname . '(NMC:' . $u->nmc . ')';
				$name .= $consult . '<br>';
			}
		}

		return $name;
	}

	public static function getEncounterConsultantVisit($encounter)
	{
		$name = '';
		$isdepartment = '';
		$department = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->get();

		if ($users) {
			foreach ($users as $u) {
				$isdepartment = Departmentbed::where('fldbed', $u->fldconsultname)->first();
				if (!empty($isdepartment)) {
					$department = $isdepartment->flddept;
				} else {
					$department = $u->fldconsultname;
				}

				$consult = $department . '<br>' . $u->fldcategory . '. ' . $u->firstname . ' ' . $u->middlename . ' ' . $u->lastname . '<br>' . 'NMC:' . $u->nmc;
				$name .= $consult . '<br>';
			}
		}

		return $name;
	}

	public static function getEncounterConsultantVisitDepartment($encounter)
	{
		$isdepartment = '';
		$department = '';
		$department_name = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->get();

		if ($users) {
			foreach ($users as $u) {
				$isdepartment = Departmentbed::where('fldbed', $u->fldconsultname)->first();
				if (!empty($isdepartment)) {
					$department = str_replace("&", "and", $isdepartment->flddept);
				} else {
					$department = str_replace("&", "and", $u->fldconsultname);
				}
				$department_name .= $department . '<br>';
			}
		}

		return $department_name;
	}

	public static function getEncounterConsultantVisitDocName($encounter)
	{
		$doctor_name = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->get();

		if ($users) {
			foreach ($users as $u) {
				$doctor_name .= $u->fldcategory . '. ' . $u->firstname . ' ' . $u->middlename . ' ' . $u->lastname . '<br>';
			}
		}

		return $doctor_name;
	}

	public static function getEncounterConsultantVisitReg($encounter)
	{
		$reg_no = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->get();

		if ($users) {
			foreach ($users as $u) {
				$reg_no .=  'NMC:' . $u->nmc . '<br>';
			}
			return $reg_no;
		}
	}

	public static function getTestedPatientByTestname($testname, $bill_no, $eng_from_date, $eng_to_date, $flditemname, $doc_name, $type)
	{
		$patients = [];
		//dd($eng_from_date.''.$eng_to_date);
		$users = DB::table('tblpatbilling')
			->join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatbilling.fldencounterval')
			->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
			->join('pat_billing_shares', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')
			->select('tblpatientinfo.fldptnamefir', 'tblpatientinfo.fldmidname', 'tblpatientinfo.fldptnamelast', 'tblpatbilling.flditemrate', 'tblpatbilling.flditemqty', 'tblpatbilling.fldditemamt', 'pat_billing_shares.share', 'pat_billing_shares.tax_amt', 'pat_billing_shares.shareqty', 'tblpatbilling.fldtime', 'tblpatbilling.fldordtime')
			//->select('tblpatientinfo.fldptnamefir','tblpatientinfo.fldmidname','tblpatientinfo.fldptnamelast',DB::raw('COUNT(tblpatbilling.fldencounterval) as followers'))
			->where('tblpatbilling.flditemname', $testname)
			->where('pat_billing_shares.type', $type)
			->where('pat_billing_shares.user_id', $doc_name)
			->when($bill_no != '', function ($q) use ($bill_no) {

				return $q->where('tblpatbilling.fldbillno', 'LIKE', '%' . $bill_no . '%');
			})
			->when($eng_from_date != '', function ($q) use ($eng_from_date) {

				return $q->whereDate('pat_billing_shares.created_at', '>=', $eng_from_date);
			})

			->when($eng_to_date != '', function ($q) use ($eng_to_date) {

				return $q->whereDate('pat_billing_shares.created_at', '<=', $eng_to_date);
			})

			->when($flditemname != '' && $flditemname != null, function ($q) use ($flditemname) {

				return $q->where('tblpatbilling.flditemname', 'LIKE', '%' . $flditemname . '%');
			})
			// ->groupBy('tblpatbilling.fldencounterval','tblpatbilling.flditemname')
			->where('pat_billing_shares.share', '>', 0)
			->where('pat_billing_shares.is_returned', 0)
			->where('pat_billing_shares.status', 1)
			->orderBy('tblpatbilling.fldtime', 'ASC')
			->get();




		return $users;
	}

	public static function getTestedDatewise($testname, $bill_no, $eng_from_date, $eng_to_date, $flditemname, $doc_name, $type)
	{
		$patients = [];
		//dd($eng_from_date.''.$eng_to_date);
		$users = DB::table('tblpatbilling')
			->join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatbilling.fldencounterval')
			->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
			->join('pat_billing_shares', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')

			->select(
				DB::raw("SUM(tblpatbilling.flditemrate) as flditemrate"),
				DB::raw("SUM(tblpatbilling.flditemqty) as flditemqty"),
				DB::raw("SUM(tblpatbilling.fldditemamt) as fldditemamt"),
				DB::raw("SUM(pat_billing_shares.share) as share"),
				DB::raw("SUM(pat_billing_shares.shareqty) as shareqty"),
				DB::raw("SUM(pat_billing_shares.tax_amt) as tax_amt"),
				'tblpatbilling.fldordtime',
				'tblpatbilling.fldtime'
			)

			->where('tblpatbilling.flditemname', $testname)
			->where('pat_billing_shares.type', $type)
			->where('pat_billing_shares.user_id', $doc_name)
			->when($bill_no != '', function ($q) use ($bill_no) {

				return $q->where('tblpatbilling.fldbillno', 'LIKE', '%' . $bill_no . '%');
			})
			->when($eng_from_date != '', function ($q) use ($eng_from_date) {

				return $q->whereDate('pat_billing_shares.created_at', '>=', $eng_from_date);
			})

			->when($eng_to_date != '', function ($q) use ($eng_to_date) {

				return $q->whereDate('pat_billing_shares.created_at', '<=', $eng_to_date);
			})

			->when($flditemname != '' && $flditemname != null, function ($q) use ($flditemname) {

				return $q->where('tblpatbilling.flditemname', 'LIKE', '%' . $flditemname . '%');
			})
			// ->groupBy('tblpatbilling.fldencounterval','tblpatbilling.flditemname')
			->where('pat_billing_shares.share', '>', 0)
			->where('pat_billing_shares.status', 1)
			->where('pat_billing_shares.is_returned', 0)
			->groupBy(DB::raw("DATE_FORMAT(tblpatbilling.fldtime, '%d-%m-%Y')"))

			->orderBy('tblpatbilling.fldtime', 'ASC')
			->get();




		return $users;
	}


	public static function getNameByUsername($username)
	{
		$name = CogentUsers::where('username', $username)->first();
		if (!empty($name))
			return $name->firstname . ' ' . $name->middlename . ' ' . $name->lastname;
		else
			return '';
	}

	public static function getshareamount($patbillid)
	{
		$amt =  DB::table("pat_billing_shares")->where('pat_billing_id', $patbillid)->sum('share');
		return $this->numberFormat($amt, 'insert');
	}

	public static function getshareamountDr($patbillid)
	{
		$doctors =  DB::table("pat_billing_shares")->where('pat_billing_id', $patbillid)->get();
		$namelist = '';
		if ($doctors) {
			foreach ($doctors  as $dr) {
				$name = CogentUsers::where('id', $dr->user_id)->first();
				$drname =  $dr->type . ' ' . $name->firstname . ' ' . $name->middlename . ' ' . $name->lastname . '( Rs.' . $this->numberFormat($dr->share) . ')';
				$namelist .= $drname . ' , ';
			}
		}

		return rtrim($namelist, ',');
	}

	public static function getNameByUsernameID($user_id)
	{
		$name = CogentUsers::where('id', $user_id)->first();
		if (!empty($name))
			return $name->firstname . ' ' . $name->middlename . ' ' . $name->lastname;
		else
			return '';
	}

	public static function getSumOutdoor($user_id, $from_date, $lastdate)
	{
		$result =  DB::table('pat_billing_shares')
			->select(DB::raw('SUM(pat_billing_shares.share) as share'))
			->where('pat_billing_shares.user_id', $user_id)
			->whereIn('pat_billing_shares.type', ['OPD Consultation'])
			->where('pat_billing_shares.status', 1)
			->where('pat_billing_shares.is_returned', 0)
			->when(isset($from_date), function ($q) use ($from_date, $lastdate) {
				return $q->whereDate('pat_billing_shares.created_at', '>=', $from_date . ' 00:00:00')
					->whereDate('pat_billing_shares.created_at', '<=', $lastdate  . " 23:59:59");
			})
			->first();
		return $result->share ?? 0;
	}

	public static function getSumOutdoortax($user_id, $from_date, $lastdate)
	{
		$result =    DB::table('pat_billing_shares')
			->select(DB::raw('SUM(pat_billing_shares.tax_amt) as tax_amt'))
			->where('pat_billing_shares.user_id', $user_id)
			->whereIn('pat_billing_shares.type', ['OPD Consultation'])
			->where('pat_billing_shares.status', 1)
			->where('pat_billing_shares.is_returned', 0)
			->when(isset($from_date), function ($q) use ($from_date, $lastdate) {
				return $q->whereDate('pat_billing_shares.created_at', '>=', $from_date . ' 00:00:00')
					->whereDate('pat_billing_shares.created_at', '<=', $lastdate  . " 23:59:59");
			})
			->first();

		return $result->tax_amt ?? 0;
	}

	public static function getSumIndoor($user_id, $from_date, $lastdate)
	{
		$result =  DB::table('pat_billing_shares')
			->select(DB::raw('SUM(pat_billing_shares.share) as share'))
			->where('pat_billing_shares.user_id', $user_id)
			->whereIn('pat_billing_shares.type', ['IPD Round', 'payable', 'OT Dr. Individual', 'OT Dr. Group', 'OT Anesthesia', 'OT  OT Assistant', 'ICU Procedure'])
			->where('pat_billing_shares.status', 1)
			->where('pat_billing_shares.is_returned', 0)
			->when(isset($from_date), function ($q) use ($from_date, $lastdate) {
				return $q->whereDate('pat_billing_shares.created_at', '>=', $from_date . ' 00:00:00')
					->whereDate('pat_billing_shares.created_at', '<=', $lastdate  . " 23:59:59");
			})
			->first();

		return $result->share ?? 0;
	}

	public static function getSumIndoortax($user_id, $from_date, $lastdate)
	{
		$result =   DB::table('pat_billing_shares')
			->select(DB::raw('SUM(pat_billing_shares.tax_amt) as tax_amt'))
			->where('pat_billing_shares.user_id', $user_id)
			->whereIn('pat_billing_shares.type', ['IPD Round', 'payable', 'OT Dr. Individual', 'OT Dr. Group', 'OT Anesthesia', 'OT  OT Assistant', 'ICU Procedure'])
			->where('pat_billing_shares.status', 1)
			->where('pat_billing_shares.is_returned', 0)
			->when(isset($from_date), function ($q) use ($from_date, $lastdate) {
				return $q->whereDate('pat_billing_shares.created_at', '>=', $from_date . ' 00:00:00')
					->whereDate('pat_billing_shares.created_at', '<=', $lastdate  . " 23:59:59");
			})
			->first();

		return $result->tax_amt ?? 0;
	}

	public static function getPatientName($user_id)
	{
		$name = DB::table('tblpatientinfo')
			->join('tblencounter', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
			->select('tblpatientinfo.fldptnamefir', 'tblpatientinfo.fldmidname', 'tblpatientinfo.fldptnamelast')
			->where('tblencounter.fldencounterval', $user_id)->first();
		if (!empty($name))
			return ucwords($name->fldptnamefir) . ' ' . ucwords($name->fldmidname) . ' ' . ucwords($name->fldptnamelast);
		else
			return '';
	}

	public static function numberFormatWithDigit($number, $decimal = 2)
	{
		//this
		return $this->numberFormat($number, 'insert');
	}

	public static function getPayableNameByPatbillId($patbill_id)
	{
		if (!$patbill_id) {
			return false;
		}
		$share = PatBillingShare::with('user')->where('pat_billing_id', $patbill_id)->first();
		if ($share && $share->user) {
			return $share->user->fldtitlefullname;
		}
		return '';
	}


	public static function getquantitybyitemname($itemtype, $itemname, $doc_name, $eng_from_date, $eng_to_date)
	{
		$patients = [];
		//dd($eng_from_date.''.$eng_to_date);
		$users = DB::table('tblpatbilling')
			->join('pat_billing_shares', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')
			->select(
				DB::raw("SUM(tblpatbilling.flditemrate) as flditemrate"),
				DB::raw("SUM(tblpatbilling.flditemqty) as flditemqty"),
				DB::raw("SUM(tblpatbilling.fldditemamt) as fldditemamt"),
				DB::raw("SUM(pat_billing_shares.share) as share"),
				DB::raw("SUM(pat_billing_shares.shareqty) as shareqty"),
				DB::raw("SUM(pat_billing_shares.tax_amt) as tax_amt"),
				'tblpatbilling.fldordtime',
				'tblpatbilling.fldtime'
			)

			->where('tblpatbilling.flditemname', $itemname)
			->where('pat_billing_shares.type', $itemtype)
			->where('pat_billing_shares.user_id', $doc_name)

			->when($eng_from_date != '', function ($q) use ($eng_from_date) {

				return $q->whereDate('pat_billing_shares.created_at', '>=', $eng_from_date);
			})

			->when($eng_to_date != '', function ($q) use ($eng_to_date) {

				return $q->whereDate('pat_billing_shares.created_at', '<=', $eng_to_date);
			})

			->when($itemname != '' && $itemname != null, function ($q) use ($itemname) {

				return $q->where('tblpatbilling.flditemname', 'LIKE', '%' . $itemname . '%');
			})

			->where('pat_billing_shares.share', '>', 0)
			->where('pat_billing_shares.is_returned', 0)
			->where('pat_billing_shares.status', 1)
			->orderBy('tblpatbilling.fldtime', 'ASC')
			->get();




		return $users[0]->shareqty;
	}

	public static function getBillingModeByBillno($bill_no)
	{
		$billing = PatBilling::select('fldbillingmode')->where('fldbillno', $bill_no)->first();
		if (isset($billing) && $billing && !empty($billing) & !is_null($billing))
			return $billing->fldbillingmode;
		else
			return '';
	}

	public static function getAccountTransaction($account_no, $voucher_no)
	{
		$trans = TransactionView::select('AccountNo', 'TranDate', 'VoucherNo', 'TranAmount', 'ChequeNo', 'Narration')
			->where('VoucherNo', $voucher_no)
			->where('AccountNo', '=', $account_no)
			//  ->where('TranId', '=',$TranId)
			->with('accountLedger')
			->orderBy('TranDate', 'desc')
			->first();

		if ($trans)
			return $trans->TranAmount;
		else
			return 0;
	}

	public static function getServiceCostRateFlag($itemname)
	{
		$data = ServiceCost::select('rate')
			->where('flditemname', 'LIKE', '%' . $itemname . '%')
			->first();
		if ($data)
			return $data->rate;
		else
			return 0;
	}

	public static function remarkofbill($billno)
	{
		$sql = "select group_concat(fldreason) as remark from tblpatbilling where fldbillno like '%" . $billno . "%' group by fldreason";
		$remarks = DB::select(
			$sql
		);
		//dd($remarks);

		$remarked = $remarks ? $remarks[0]->remark : '';

		return $remarked;
	}

	public static function remarkofbillreturn($billno)
	{
		$sql = "select group_concat(DISTINCT(fldreason)) as remark from tblpatbilling where fldretbill like '%" . $billno . "%' group by fldreason";
		$remarks = DB::select(
			$sql
		);
		//dd($remarks);

		$remarked = $remarks ? $remarks[0]->remark : '';

		return $remarked;
	}

	public static function getConsultant($encounter)
	{
		$doctor_name = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->get();

		if ($users) {
			foreach ($users as $u) {
				$doctor_name .= $u->fldcategory . '. ' . $u->firstname . ' ' . $u->middlename . ' ' . $u->lastname;
			}
		}

		return $doctor_name;
	}

	public static function consultant($encounter)
	{
		$doctor_name = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->where('tblconsult.fldcomment', '!=', 'Follow Up')
			->get();

		if ($users) {
			foreach ($users as $u) {
				$doctor_name .= $u->fldcategory . '. ' . $u->firstname . ' ' . $u->middlename . ' ' . $u->lastname;
			}
		}

		return $doctor_name;
	}

	public static function getConsultantFollowup($encounter)
	{
		$doctor_name = '';
		$users = DB::table('tblconsult')
			->join('users', 'tblconsult.flduserid', '=', 'users.username')
			->select('users.fldcategory', 'users.firstname', 'users.middlename', 'users.nhbc', 'users.nmc', 'users.lastname', 'tblconsult.fldconsultname')
			->where('tblconsult.fldencounterval', $encounter)
			->where('tblconsult.fldcomment', '=', 'Follow Up')
			->get();

		if ($users) {
			foreach ($users as $u) {
				$doctor_name .= $u->fldcategory . '. ' . $u->firstname . ' ' . $u->middlename . ' ' . $u->lastname;
			}
		}

		return $doctor_name;
	}

	public static function getLastestFollowup($encounter)
	{
		return Consult::select('fldconsultname', 'flduserid')
			->where('fldencounterval', $encounter)
			->where('fldcomment', '=', 'Follow Up')
			->first();
	}

	public static function logStack($remarks, $payload = [])
	{
		try {
			if ($remarks && is_array($remarks) && count($remarks) > 1 && $remarks[1] && $remarks[1] == 'Error') {
				\Log::channel('Cogent-Health')->error(json_encode($remarks), $payload);
			} else {
				\Log::channel('Cogent-Health')->debug(json_encode($remarks), $payload);
			}
			// $logFileName = "Cogent-" . date('Y-m-d') . ".log";
			// $logPath = Auth::guard("admin_frontend")->id() ? storage_path() . '/logs/cogent/user-' . Auth::guard("admin_frontend")->id() . "/" . $logFileName : storage_path() . '/logs/cogent/' . $logFileName;
			// $messagesLogger = new Logger("Cogent-Health");
			// $messagesLogger->pushHandler(new StreamHandler($logPath, Logger::INFO));
			// $messagesLogger->info($remarks . " : " . $payload);
		} catch (\Exception $e) {
			dd($e);
		}
	}

	public static function getAccountLedger($type)
	{
		$test = GeneralLedgerMap::join('account_ledger', 'account_general_map.ledger_id', '=', 'account_ledger.AccountId')
			->where('type', $type)
			->first();
		$account = [$test->AccountNo, $test->AccountName];
		return $account;
	}

	public static function getIMUDistricts()
	{
		$districts = [
			[
				"id"            => 1,
				"province_id"   => 1,
				"district_name" => "Taplejung"
			],
			[
				"id"            => 2,
				"province_id"   => 1,
				"district_name" => "Panchthar"
			],
			[
				"id"            => 3,
				"province_id"   => 1,
				"district_name" => "Ilam"
			],
			[
				"id"            => 4,
				"province_id"   => 1,
				"district_name" => "Jhapa"
			],
			[
				"id"            => 5,
				"province_id"   => 1,
				"district_name" => "Morang"
			],
			[
				"id"            => 6,
				"province_id"   => 1,
				"district_name" => "Sunasari"
			],
			[
				"id"            => 7,
				"province_id"   => 1,
				"district_name" => "Dhankuta"
			],
			[
				"id"            => 8,
				"province_id"   => 1,
				"district_name" => "Terhathum"
			],
			[
				"id"            => 9,
				"province_id"   => 1,
				"district_name" => "Sankhusabha"
			],
			[
				"id"            => 10,
				"province_id"   => 1,
				"district_name" => "Bhojpur"
			],
			[
				"id"            => 11,
				"province_id"   => 1,
				"district_name" => "Solukhumbu"
			],
			[
				"id"            => 12,
				"province_id"   => 1,
				"district_name" => "Okhaldunga"
			],
			[
				"id"            => 13,
				"province_id"   => 1,
				"district_name" => "Khotang"
			],
			[
				"id"            => 14,
				"province_id"   => 1,
				"district_name" => "Udayapur"
			],
			[
				"id"            => 15,
				"province_id"   => 2,
				"district_name" => "Saptari"
			],
			[
				"id"            => 16,
				"province_id"   => 2,
				"district_name" => "Siraha"
			],
			[
				"id"            => 17,
				"province_id"   => 2,
				"district_name" => "Dhanusha"
			],
			[
				"id"            => 18,
				"province_id"   => 2,
				"district_name" => "Mahottari"
			],
			[
				"id"            => 19,
				"province_id"   => 2,
				"district_name" => "Sarlahi"
			],
			[
				"id"            => 20,
				"province_id"   => 3,
				"district_name" => "Sindhuli"
			],
			[
				"id"            => 21,
				"province_id"   => 3,
				"district_name" => "Ramechhap"
			],
			[
				"id"            => 22,
				"province_id"   => 3,
				"district_name" => "Dolakha"
			],
			[
				"id"            => 23,
				"province_id"   => 3,
				"district_name" => "Sindhupalchowk"
			],
			[
				"id"            => 24,
				"province_id"   => 3,
				"district_name" => "Kavrepalanchok"
			],
			[
				"id"            => 25,
				"province_id"   => 3,
				"district_name" => "Lalitpur"
			],
			[
				"id"            => 26,
				"province_id"   => 3,
				"district_name" => "Bhaktapur"
			],
			[
				"id"            => 27,
				"province_id"   => 3,
				"district_name" => "Kathmandu"
			],
			[
				"id"            => 28,
				"province_id"   => 3,
				"district_name" => "Nuwakot"
			],
			[
				"id"            => 29,
				"province_id"   => 3,
				"district_name" => "Rasuwa"
			],
			[
				"id"            => 30,
				"province_id"   => 3,
				"district_name" => "Dhading"
			],
			[
				"id"            => 31,
				"province_id"   => 3,
				"district_name" => "Makawanpur"
			],
			[
				"id"            => 32,
				"province_id"   => 2,
				"district_name" => "Rautahat"
			],
			[
				"id"            => 33,
				"province_id"   => 2,
				"district_name" => "Bara"
			],
			[
				"id"            => 34,
				"province_id"   => 2,
				"district_name" => "Parsa"
			],
			[
				"id"            => 35,
				"province_id"   => 3,
				"district_name" => "Chitwan"
			],
			[
				"id"            => 36,
				"province_id"   => 4,
				"district_name" => "Gorkha"
			],
			[
				"id"            => 37,
				"province_id"   => 4,
				"district_name" => "Lamjung"
			],
			[
				"id"            => 38,
				"province_id"   => 4,
				"district_name" => "Tanahun"
			],
			[
				"id"            => 39,
				"province_id"   => 4,
				"district_name" => "Syangja"
			],
			[
				"id"            => 40,
				"province_id"   => 4,
				"district_name" => "Kaski"
			],
			[
				"id"            => 41,
				"province_id"   => 4,
				"district_name" => "Manang"
			],
			[
				"id"            => 42,
				"province_id"   => 4,
				"district_name" => "Mustang"
			],
			[
				"id"            => 43,
				"province_id"   => 4,
				"district_name" => "Myagdi"
			],
			[
				"id"            => 44,
				"province_id"   => 4,
				"district_name" => "Parbat"
			],
			[
				"id"            => 45,
				"province_id"   => 4,
				"district_name" => "Baglung"
			],
			[
				"id"            => 46,
				"province_id"   => 5,
				"district_name" => "Gulmi"
			],
			[
				"id"            => 47,
				"province_id"   => 5,
				"district_name" => "Palpa"
			],
			[
				"id"            => 48,
				"province_id"   => 4,
				"district_name" => " Nawalpur"
			],
			[
				"id"            => 49,
				"province_id"   => 5,
				"district_name" => "Rupandehi"
			],
			[
				"id"            => 50,
				"province_id"   => 5,
				"district_name" => "Kapilbastu"
			],
			[
				"id"            => 51,
				"province_id"   => 5,
				"district_name" => "Arghakhanchi"
			],
			[
				"id"            => 52,
				"province_id"   => 5,
				"district_name" => "Pyuthan"
			],
			[
				"id"            => 53,
				"province_id"   => 5,
				"district_name" => "Rolpa"
			],
			[
				"id"            => 54,
				"province_id"   => 6,
				"district_name" => "Western Rukum"
			],
			[
				"id"            => 55,
				"province_id"   => 6,
				"district_name" => "Salyan"
			],
			[
				"id"            => 56,
				"province_id"   => 5,
				"district_name" => "Dang"
			],
			[
				"id"            => 57,
				"province_id"   => 5,
				"district_name" => "Banke"
			],
			[
				"id"            => 58,
				"province_id"   => 5,
				"district_name" => "Bardiya"
			],
			[
				"id"            => 59,
				"province_id"   => 6,
				"district_name" => "Surkhet"
			],
			[
				"id"            => 60,
				"province_id"   => 6,
				"district_name" => "Dailekh"
			],
			[
				"id"            => 61,
				"province_id"   => 6,
				"district_name" => "Jajarkot"
			],
			[
				"id"            => 62,
				"province_id"   => 6,
				"district_name" => "Dolpa"
			],
			[
				"id"            => 63,
				"province_id"   => 6,
				"district_name" => "Jumla"
			],
			[
				"id"            => 64,
				"province_id"   => 6,
				"district_name" => "Kalikot"
			],
			[
				"id"            => 65,
				"province_id"   => 6,
				"district_name" => "Mugu"
			],
			[
				"id"            => 66,
				"province_id"   => 6,
				"district_name" => "Humla"
			],
			[
				"id"            => 67,
				"province_id"   => 7,
				"district_name" => "Bajura"
			],
			[
				"id"            => 68,
				"province_id"   => 7,
				"district_name" => "Bajhang"
			],
			[
				"id"            => 69,
				"province_id"   => 7,
				"district_name" => "Achham"
			],
			[
				"id"            => 70,
				"province_id"   => 7,
				"district_name" => "Doti"
			],
			[
				"id"            => 71,
				"province_id"   => 7,
				"district_name" => "Kailali"
			],
			[
				"id"            => 72,
				"province_id"   => 7,
				"district_name" => "Kanchanpur"
			],
			[
				"id"            => 73,
				"province_id"   => 7,
				"district_name" => "Dadeldhura"
			],
			[
				"id"            => 74,
				"province_id"   => 7,
				"district_name" => "Baitadi"
			],
			[
				"id"            => 75,
				"province_id"   => 7,
				"district_name" => "Darchula"
			],
			[
				"id"            => 76,
				"province_id"   => 5,
				"district_name" => "Nawalparasi"
			],
			[
				"id"            => 77,
				"province_id"   => 5,
				"district_name" => " Eastern Rukum"
			]
		];

		return $districts;
	}

	public static function getIMUMunicipalities()
	{
		$municipalities = [
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Aathrai Tribeni",
				"id" => 1
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Maiwakhola",
				"id" => 2
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Meringden",
				"id" => 3
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Mikwakhola",
				"id" => 4
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Phaktanglung",
				"id" => 5
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Phungling",
				"id" => 6
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Sidingba",
				"id" => 7
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Sirijangha",
				"id" => 8
			],
			[
				"province_id" => "1",
				"district_id" => "1",
				"district_name" => "Taplejung",
				"municipality_name" => "Pathibhara Yangwarak",
				"id" => 9
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Falelung",
				"id" => 10
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Falgunanda",
				"id" => 11
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Hilihang",
				"id" => 12
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Kummayak",
				"id" => 13
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Miklajung",
				"id" => 14
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Phidim",
				"id" => 15
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Tumbewa",
				"id" => 16
			],
			[
				"province_id" => "1",
				"district_id" => "2",
				"district_name" => "Panchthar",
				"municipality_name" => "Yangwarak",
				"id" => 17
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Chulachuli",
				"id" => 18
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Deumai",
				"id" => 19
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Fakphokthum",
				"id" => 20
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Illam",
				"id" => 21
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Mai",
				"id" => 22
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Maijogmai",
				"id" => 23
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Mangsebung",
				"id" => 24
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Rong",
				"id" => 25
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Sandakpur",
				"id" => 26
			],
			[
				"province_id" => "1",
				"district_id" => "3",
				"district_name" => "Ilam",
				"municipality_name" => "Suryodaya",
				"id" => 27
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Arjundhara",
				"id" => 28
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Barhadashi",
				"id" => 29
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Bhadrapur",
				"id" => 30
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Birtamod",
				"id" => 31
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Buddhashanti",
				"id" => 32
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Damak",
				"id" => 33
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Gauradhaha",
				"id" => 34
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Gauriganj",
				"id" => 35
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Haldibari",
				"id" => 36
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Jhapa",
				"id" => 37
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Kachankawal",
				"id" => 38
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Kamal",
				"id" => 39
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Kankai",
				"id" => 40
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Mechinagar",
				"id" => 41
			],
			[
				"province_id" => "1",
				"district_id" => "4",
				"district_name" => "Jhapa",
				"municipality_name" => "Shivasataxi",
				"id" => 42
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Belbari",
				"id" => 43
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Biratnagar",
				"id" => 44
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Budhiganga",
				"id" => 45
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Dhanpalthan",
				"id" => 46
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Gramthan",
				"id" => 47
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Jahada",
				"id" => 48
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Kanepokhari",
				"id" => 49
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Katahari",
				"id" => 50
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Kerabari",
				"id" => 51
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Letang",
				"id" => 52
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Miklajung",
				"id" => 53
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Patahrishanishchare",
				"id" => 54
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Rangeli",
				"id" => 55
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Ratuwamai",
				"id" => 56
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Sundarharaicha",
				"id" => 57
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Sunwarshi",
				"id" => 58
			],
			[
				"province_id" => "1",
				"district_id" => "5",
				"district_name" => "Morang",
				"municipality_name" => "Uralabari",
				"id" => 59
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Barah",
				"id" => 60
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Barju",
				"id" => 61
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Bhokraha",
				"id" => 62
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Dewanganj",
				"id" => 63
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Dharan",
				"id" => 64
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Duhabi",
				"id" => 65
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Gadhi",
				"id" => 66
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Harinagara",
				"id" => 67
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Inaruwa",
				"id" => 68
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Itahari",
				"id" => 69
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Koshi",
				"id" => 70
			],
			[
				"province_id" => "1",
				"district_id" => "6",
				"district_name" => "Sunsari",
				"municipality_name" => "Ramdhuni",
				"id" => 71
			],
			[
				"province_id" => "1",
				"district_id" => "7",
				"district_name" => "Dhankuta",
				"municipality_name" => "Chaubise",
				"id" => 72
			],
			[
				"province_id" => "1",
				"district_id" => "7",
				"district_name" => "Dhankuta",
				"municipality_name" => "Chhathar Jorpati",
				"id" => 73
			],
			[
				"province_id" => "1",
				"district_id" => "7",
				"district_name" => "Dhankuta",
				"municipality_name" => "Dhankuta",
				"id" => 74
			],
			[
				"province_id" => "1",
				"district_id" => "7",
				"district_name" => "Dhankuta",
				"municipality_name" => "Khalsa Chhintang Shahidbhumi",
				"id" => 75
			],
			[
				"province_id" => "1",
				"district_id" => "7",
				"district_name" => "Dhankuta",
				"municipality_name" => "Mahalaxmi",
				"id" => 76
			],
			[
				"province_id" => "1",
				"district_id" => "7",
				"district_name" => "Dhankuta",
				"municipality_name" => "Pakhribas",
				"id" => 77
			],
			[
				"province_id" => "1",
				"district_id" => "7",
				"district_name" => "Dhankuta",
				"municipality_name" => "Sangurigadhi",
				"id" => 78
			],
			[
				"province_id" => "1",
				"district_id" => "8",
				"district_name" => "Terhathum",
				"municipality_name" => "Aathrai",
				"id" => 79
			],
			[
				"province_id" => "1",
				"district_id" => "8",
				"district_name" => "Terhathum",
				"municipality_name" => "Chhathar",
				"id" => 80
			],
			[
				"province_id" => "1",
				"district_id" => "8",
				"district_name" => "Terhathum",
				"municipality_name" => "Laligurans",
				"id" => 81
			],
			[
				"province_id" => "1",
				"district_id" => "8",
				"district_name" => "Terhathum",
				"municipality_name" => "Menchayam",
				"id" => 82
			],
			[
				"province_id" => "1",
				"district_id" => "8",
				"district_name" => "Terhathum",
				"municipality_name" => "Myanglung",
				"id" => 83
			],
			[
				"province_id" => "1",
				"district_id" => "8",
				"district_name" => "Terhathum",
				"municipality_name" => "Phedap",
				"id" => 84
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Bhotkhola",
				"id" => 85
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Chainpur",
				"id" => 86
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Chichila",
				"id" => 87
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Dharmadevi",
				"id" => 88
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Khandbari",
				"id" => 89
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Madi",
				"id" => 90
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Makalu",
				"id" => 91
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Panchakhapan",
				"id" => 92
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Sabhapokhari",
				"id" => 93
			],
			[
				"province_id" => "1",
				"district_id" => "9",
				"district_name" => "Sankhuwasabha",
				"municipality_name" => "Silichong",
				"id" => 94
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Aamchowk",
				"id" => 95
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Arun",
				"id" => 96
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Bhojpur",
				"id" => 97
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Hatuwagadhi",
				"id" => 98
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Pauwadungma",
				"id" => 99
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Ramprasad Rai",
				"id" => 100
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Salpasilichho",
				"id" => 101
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Shadananda",
				"id" => 102
			],
			[
				"province_id" => "1",
				"district_id" => "10",
				"district_name" => "Bhojpur",
				"municipality_name" => "Tyamkemaiyung",
				"id" => 103
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Dudhkaushika",
				"id" => 104
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Dudhkoshi",
				"id" => 105
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Khumbupasanglahmu",
				"id" => 106
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Likhupike",
				"id" => 107
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Mahakulung",
				"id" => 108
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Nechasalyan",
				"id" => 109
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Solududhakunda",
				"id" => 110
			],
			[
				"province_id" => "1",
				"district_id" => "11",
				"district_name" => "Solukhumbu",
				"municipality_name" => "Sotang",
				"id" => 111
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Champadevi",
				"id" => 112
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Chisankhugadhi",
				"id" => 113
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Khijidemba",
				"id" => 114
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Likhu",
				"id" => 115
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Manebhanjyang",
				"id" => 116
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Molung",
				"id" => 117
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Siddhicharan",
				"id" => 118
			],
			[
				"province_id" => "1",
				"district_id" => "12",
				"district_name" => "Okhaldhunga",
				"municipality_name" => "Sunkoshi",
				"id" => 119
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Ainselukhark",
				"id" => 120
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Barahapokhari",
				"id" => 121
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Diprung",
				"id" => 122
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Halesi Tuwachung",
				"id" => 123
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Jantedhunga",
				"id" => 124
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Kepilasagadhi",
				"id" => 125
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Khotehang",
				"id" => 126
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Lamidanda",
				"id" => 127
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Rupakot Majhuwagadhi",
				"id" => 128
			],
			[
				"province_id" => "1",
				"district_id" => "13",
				"district_name" => "Khotang",
				"municipality_name" => "Sakela",
				"id" => 129
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Belaka",
				"id" => 130
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Chaudandigadhi",
				"id" => 131
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Katari",
				"id" => 132
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Rautamai",
				"id" => 133
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Sunkoshi",
				"id" => 134
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Tapli",
				"id" => 135
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Triyuga",
				"id" => 136
			],
			[
				"province_id" => "1",
				"district_id" => "14",
				"district_name" => "Udayapur",
				"municipality_name" => "Udayapurgadhi",
				"id" => 137
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Agnisair Krishna Savaran",
				"id" => 138
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Balan Bihul",
				"id" => 139
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Belhi Chapena",
				"id" => 140
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Bishnupur",
				"id" => 141
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Bode Barsain",
				"id" => 142
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Chhinnamasta",
				"id" => 143
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Dakneshwori",
				"id" => 144
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Hanumannagar Kankalini",
				"id" => 145
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Kanchanrup",
				"id" => 146
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Khadak",
				"id" => 147
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Mahadeva",
				"id" => 148
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Rajbiraj",
				"id" => 149
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Rupani",
				"id" => 150
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Saptakoshi",
				"id" => 151
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Shambhunath",
				"id" => 152
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Surunga",
				"id" => 153
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Tilathi Koiladi",
				"id" => 154
			],
			[
				"province_id" => "2",
				"district_id" => "15",
				"district_name" => "Saptari",
				"municipality_name" => "Tirahut",
				"id" => 155
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Arnama",
				"id" => 156
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Aurahi",
				"id" => 157
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Bariyarpatti",
				"id" => 158
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Bhagawanpur",
				"id" => 159
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Bishnupur",
				"id" => 160
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Dhangadhimai",
				"id" => 161
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Golbazar",
				"id" => 162
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Kalyanpur",
				"id" => 163
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Karjanha",
				"id" => 164
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Lahan",
				"id" => 165
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Laxmipur Patari",
				"id" => 166
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Mirchaiya",
				"id" => 167
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Naraha",
				"id" => 168
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Nawarajpur",
				"id" => 169
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Sakhuwanankarkatti",
				"id" => 170
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Siraha",
				"id" => 171
			],
			[
				"province_id" => "2",
				"district_id" => "16",
				"district_name" => "Siraha",
				"municipality_name" => "Sukhipur",
				"id" => 172
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Aaurahi",
				"id" => 173
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Bateshwor",
				"id" => 174
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Bideha",
				"id" => 175
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Chhireshwornath",
				"id" => 176
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Dhanauji",
				"id" => 177
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Dhanusadham",
				"id" => 178
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Ganeshman Charnath",
				"id" => 179
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Hansapur",
				"id" => 180
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Janaknandani",
				"id" => 181
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Janakpur",
				"id" => 182
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Kamala",
				"id" => 183
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Lakshminiya",
				"id" => 184
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Mithila",
				"id" => 185
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Mithila Bihari",
				"id" => 186
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Mukhiyapatti Musarmiya",
				"id" => 187
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Nagarain",
				"id" => 188
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Sabaila",
				"id" => 189
			],
			[
				"province_id" => "2",
				"district_id" => "17",
				"district_name" => "Dhanusha",
				"municipality_name" => "Sahidnagar",
				"id" => 190
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Aurahi",
				"id" => 191
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Balwa",
				"id" => 192
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Bardibas",
				"id" => 193
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Bhangaha",
				"id" => 194
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Ekdanra",
				"id" => 195
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Gaushala",
				"id" => 196
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Jaleswor",
				"id" => 197
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Loharpatti",
				"id" => 198
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Mahottari",
				"id" => 199
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Manra Siswa",
				"id" => 200
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Matihani",
				"id" => 201
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Pipra",
				"id" => 202
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Ramgopalpur",
				"id" => 203
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Samsi",
				"id" => 204
			],
			[
				"province_id" => "2",
				"district_id" => "18",
				"district_name" => "Mahottari",
				"municipality_name" => "Sonama",
				"id" => 205
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Bagmati",
				"id" => 206
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Balara",
				"id" => 207
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Barahathawa",
				"id" => 208
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Basbariya",
				"id" => 209
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Bishnu",
				"id" => 210
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Bramhapuri",
				"id" => 211
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Chakraghatta",
				"id" => 212
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Chandranagar",
				"id" => 213
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Dhankaul",
				"id" => 214
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Godaita",
				"id" => 215
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Haripur",
				"id" => 216
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Haripurwa",
				"id" => 217
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Hariwan",
				"id" => 218
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Ishworpur",
				"id" => 219
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Kabilasi",
				"id" => 220
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Kaudena",
				"id" => 221
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Lalbandi",
				"id" => 222
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Malangawa",
				"id" => 223
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Parsa",
				"id" => 224
			],
			[
				"province_id" => "2",
				"district_id" => "19",
				"district_name" => "Sarlahi",
				"municipality_name" => "Ramnagar",
				"id" => 225
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Dudhouli",
				"id" => 226
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Ghanglekh",
				"id" => 227
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Golanjor",
				"id" => 228
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Hariharpurgadhi",
				"id" => 229
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Kamalamai",
				"id" => 230
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Marin",
				"id" => 231
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Phikkal",
				"id" => 232
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Sunkoshi",
				"id" => 233
			],
			[
				"province_id" => "3",
				"district_id" => "20",
				"district_name" => "Sindhuli",
				"municipality_name" => "Tinpatan",
				"id" => 234
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Doramba",
				"id" => 235
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Gokulganga",
				"id" => 236
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Khadadevi",
				"id" => 237
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Likhu",
				"id" => 238
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Manthali",
				"id" => 239
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Ramechhap",
				"id" => 240
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Sunapati",
				"id" => 241
			],
			[
				"province_id" => "3",
				"district_id" => "21",
				"district_name" => "Ramechhap",
				"municipality_name" => "Umakunda",
				"id" => 242
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Baiteshwor",
				"id" => 243
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Bhimeshwor",
				"id" => 244
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Bigu",
				"id" => 245
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Gaurishankar",
				"id" => 246
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Jiri",
				"id" => 247
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Kalinchok",
				"id" => 248
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Melung",
				"id" => 249
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Sailung",
				"id" => 250
			],
			[
				"province_id" => "3",
				"district_id" => "22",
				"district_name" => "Dolakha",
				"municipality_name" => "Tamakoshi",
				"id" => 251
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Balefi",
				"id" => 252
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Barhabise",
				"id" => 253
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Bhotekoshi",
				"id" => 254
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Chautara SangachokGadhi",
				"id" => 255
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Helambu",
				"id" => 256
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Indrawati",
				"id" => 257
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Jugal",
				"id" => 258
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Lisangkhu Pakhar",
				"id" => 259
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Melamchi",
				"id" => 260
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Panchpokhari Thangpal",
				"id" => 261
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Sunkoshi",
				"id" => 262
			],
			[
				"province_id" => "3",
				"district_id" => "23",
				"district_name" => "Sindhupalchok",
				"municipality_name" => "Tripurasundari",
				"id" => 263
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Banepa",
				"id" => 264
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Bethanchowk",
				"id" => 265
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Bhumlu",
				"id" => 266
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Chaurideurali",
				"id" => 267
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Dhulikhel",
				"id" => 268
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Khanikhola",
				"id" => 269
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Mahabharat",
				"id" => 270
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Mandandeupur",
				"id" => 271
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Namobuddha",
				"id" => 272
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Panauti",
				"id" => 273
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Panchkhal",
				"id" => 274
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Roshi",
				"id" => 275
			],
			[
				"province_id" => "3",
				"district_id" => "24",
				"district_name" => "Kabhrepalanchok",
				"municipality_name" => "Temal",
				"id" => 276
			],
			[
				"province_id" => "3",
				"district_id" => "25",
				"district_name" => "Lalitpur",
				"municipality_name" => "Bagmati",
				"id" => 277
			],
			[
				"province_id" => "3",
				"district_id" => "25",
				"district_name" => "Lalitpur",
				"municipality_name" => "Godawari",
				"id" => 278
			],
			[
				"province_id" => "3",
				"district_id" => "25",
				"district_name" => "Lalitpur",
				"municipality_name" => "Konjyosom",
				"id" => 279
			],
			[
				"province_id" => "3",
				"district_id" => "25",
				"district_name" => "Lalitpur",
				"municipality_name" => "Lalitpur",
				"id" => 280
			],
			[
				"province_id" => "3",
				"district_id" => "25",
				"district_name" => "Lalitpur",
				"municipality_name" => "Mahalaxmi",
				"id" => 281
			],
			[
				"province_id" => "3",
				"district_id" => "25",
				"district_name" => "Lalitpur",
				"municipality_name" => "Mahankal",
				"id" => 282
			],
			[
				"province_id" => "3",
				"district_id" => "26",
				"district_name" => "Bhaktapur",
				"municipality_name" => "Bhaktapur",
				"id" => 283
			],
			[
				"province_id" => "3",
				"district_id" => "26",
				"district_name" => "Bhaktapur",
				"municipality_name" => "Changunarayan",
				"id" => 284
			],
			[
				"province_id" => "3",
				"district_id" => "26",
				"district_name" => "Bhaktapur",
				"municipality_name" => "Madhyapur Thimi",
				"id" => 285
			],
			[
				"province_id" => "3",
				"district_id" => "26",
				"district_name" => "Bhaktapur",
				"municipality_name" => "Suryabinayak",
				"id" => 286
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Budhanilakantha",
				"id" => 287
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Chandragiri",
				"id" => 288
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Dakshinkali",
				"id" => 289
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Gokarneshwor",
				"id" => 290
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Kageshwori Manahora",
				"id" => 291
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Kathmandu",
				"id" => 292
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Kirtipur",
				"id" => 293
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Nagarjun",
				"id" => 294
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Shankharapur",
				"id" => 295
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Tarakeshwor",
				"id" => 296
			],
			[
				"province_id" => "3",
				"district_id" => "27",
				"district_name" => "Kathmandu",
				"municipality_name" => "Tokha",
				"id" => 297
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Belkotgadhi",
				"id" => 298
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Bidur",
				"id" => 299
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Dupcheshwar",
				"id" => 300
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Kakani",
				"id" => 301
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Kispang",
				"id" => 302
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Likhu",
				"id" => 303
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Meghang",
				"id" => 304
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Panchakanya",
				"id" => 305
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Shivapuri",
				"id" => 306
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Suryagadhi",
				"id" => 307
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Tadi",
				"id" => 308
			],
			[
				"province_id" => "3",
				"district_id" => "28",
				"district_name" => "Nuwakot",
				"municipality_name" => "Tarkeshwar",
				"id" => 309
			],
			[
				"province_id" => "3",
				"district_id" => "29",
				"district_name" => "Rasuwa",
				"municipality_name" => "Gosaikunda",
				"id" => 310
			],
			[
				"province_id" => "3",
				"district_id" => "29",
				"district_name" => "Rasuwa",
				"municipality_name" => "Kalika",
				"id" => 311
			],
			[
				"province_id" => "3",
				"district_id" => "29",
				"district_name" => "Rasuwa",
				"municipality_name" => "Naukunda",
				"id" => 312
			],
			[
				"province_id" => "3",
				"district_id" => "29",
				"district_name" => "Rasuwa",
				"municipality_name" => "Parbati Kunda",
				"id" => 313
			],
			[
				"province_id" => "3",
				"district_id" => "29",
				"district_name" => "Rasuwa",
				"municipality_name" => "Uttargaya",
				"id" => 314
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Benighat Rorang",
				"id" => 315
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Dhunibesi",
				"id" => 316
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Gajuri",
				"id" => 317
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Galchi",
				"id" => 318
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Gangajamuna",
				"id" => 319
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Jwalamukhi",
				"id" => 320
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Khaniyabash",
				"id" => 321
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Netrawati",
				"id" => 322
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Nilakantha",
				"id" => 323
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Rubi Valley",
				"id" => 324
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Siddhalek",
				"id" => 325
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Thakre",
				"id" => 326
			],
			[
				"province_id" => "3",
				"district_id" => "30",
				"district_name" => "Dhading",
				"municipality_name" => "Tripura Sundari",
				"id" => 327
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Bagmati",
				"id" => 328
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Bakaiya",
				"id" => 329
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Bhimphedi",
				"id" => 330
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Hetauda",
				"id" => 331
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Indrasarowar",
				"id" => 332
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Kailash",
				"id" => 333
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Makawanpurgadhi",
				"id" => 334
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Manahari",
				"id" => 335
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Raksirang",
				"id" => 336
			],
			[
				"province_id" => "3",
				"district_id" => "31",
				"district_name" => "Makawanpur",
				"municipality_name" => "Thaha",
				"id" => 337
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Baudhimai",
				"id" => 338
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Brindaban",
				"id" => 339
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Chandrapur",
				"id" => 340
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Dewahhi Gonahi",
				"id" => 341
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Durga Bhagwati",
				"id" => 342
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Gadhimai",
				"id" => 343
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Garuda",
				"id" => 344
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Gaur",
				"id" => 345
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Gujara",
				"id" => 346
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Ishanath",
				"id" => 347
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Katahariya",
				"id" => 348
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Madhav Narayan",
				"id" => 349
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Maulapur",
				"id" => 350
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Paroha",
				"id" => 351
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Phatuwa Bijayapur",
				"id" => 352
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Rajdevi",
				"id" => 353
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Rajpur",
				"id" => 354
			],
			[
				"province_id" => "2",
				"district_id" => "32",
				"district_name" => "Rautahat",
				"municipality_name" => "Yemunamai",
				"id" => 355
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Adarshkotwal",
				"id" => 356
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Baragadhi",
				"id" => 357
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Bishrampur",
				"id" => 358
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Devtal",
				"id" => 359
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Jitpur Simara",
				"id" => 360
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Kalaiya",
				"id" => 361
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Karaiyamai",
				"id" => 362
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Kolhabi",
				"id" => 363
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Mahagadhimai",
				"id" => 364
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Nijgadh",
				"id" => 365
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Pacharauta",
				"id" => 366
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Parwanipur",
				"id" => 367
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Pheta",
				"id" => 368
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Prasauni",
				"id" => 369
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Simraungadh",
				"id" => 370
			],
			[
				"province_id" => "2",
				"district_id" => "33",
				"district_name" => "Bara",
				"municipality_name" => "Suwarna",
				"id" => 371
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Bahudaramai",
				"id" => 372
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Bindabasini",
				"id" => 373
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Birgunj",
				"id" => 374
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Chhipaharmai",
				"id" => 375
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Dhobini",
				"id" => 376
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Jagarnathpur",
				"id" => 377
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Jirabhawani",
				"id" => 378
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Kalikamai",
				"id" => 379
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Pakahamainpur",
				"id" => 380
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Parsagadhi",
				"id" => 381
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Paterwasugauli",
				"id" => 382
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Pokhariya",
				"id" => 383
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "SakhuwaPrasauni",
				"id" => 384
			],
			[
				"province_id" => "2",
				"district_id" => "34",
				"district_name" => "Parsa",
				"municipality_name" => "Thori",
				"id" => 385
			],
			[
				"province_id" => "3",
				"district_id" => "35",
				"district_name" => "Chitawan",
				"municipality_name" => "Bharatpur",
				"id" => 386
			],
			[
				"province_id" => "3",
				"district_id" => "35",
				"district_name" => "Chitawan",
				"municipality_name" => "Ichchhyakamana",
				"id" => 387
			],
			[
				"province_id" => "3",
				"district_id" => "35",
				"district_name" => "Chitawan",
				"municipality_name" => "Kalika",
				"id" => 388
			],
			[
				"province_id" => "3",
				"district_id" => "35",
				"district_name" => "Chitawan",
				"municipality_name" => "Khairahani",
				"id" => 389
			],
			[
				"province_id" => "3",
				"district_id" => "35",
				"district_name" => "Chitawan",
				"municipality_name" => "Madi",
				"id" => 390
			],
			[
				"province_id" => "3",
				"district_id" => "35",
				"district_name" => "Chitawan",
				"municipality_name" => "Rapti",
				"id" => 391
			],
			[
				"province_id" => "3",
				"district_id" => "35",
				"district_name" => "Chitawan",
				"municipality_name" => "Ratnanagar",
				"id" => 392
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Aarughat",
				"id" => 393
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Ajirkot",
				"id" => 394
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Bhimsen",
				"id" => 395
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Chum Nubri",
				"id" => 396
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Dharche",
				"id" => 397
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Gandaki",
				"id" => 398
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Gorkha",
				"id" => 399
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Palungtar",
				"id" => 400
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Sahid Lakhan",
				"id" => 401
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Siranchok",
				"id" => 402
			],
			[
				"province_id" => "4",
				"district_id" => "36",
				"district_name" => "Gorkha",
				"municipality_name" => "Sulikot",
				"id" => 403
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "Besishahar",
				"id" => 404
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "Dordi",
				"id" => 405
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "Dudhpokhari",
				"id" => 406
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "Kwholasothar",
				"id" => 407
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "MadhyaNepal",
				"id" => 408
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "Marsyangdi",
				"id" => 409
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "Rainas",
				"id" => 410
			],
			[
				"province_id" => "4",
				"district_id" => "37",
				"district_name" => "Lamjung",
				"municipality_name" => "Sundarbazar",
				"id" => 411
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Anbukhaireni",
				"id" => 412
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Bandipur",
				"id" => 413
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Bhanu",
				"id" => 414
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Bhimad",
				"id" => 415
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Byas",
				"id" => 416
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Devghat",
				"id" => 417
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Ghiring",
				"id" => 418
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Myagde",
				"id" => 419
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Rhishing",
				"id" => 420
			],
			[
				"province_id" => "4",
				"district_id" => "38",
				"district_name" => "Tanahu",
				"municipality_name" => "Shuklagandaki",
				"id" => 421
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Aandhikhola",
				"id" => 422
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Arjunchaupari",
				"id" => 423
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Bhirkot",
				"id" => 424
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Biruwa",
				"id" => 425
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Chapakot",
				"id" => 426
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Galyang",
				"id" => 427
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Harinas",
				"id" => 428
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Kaligandagi",
				"id" => 429
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Phedikhola",
				"id" => 430
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Putalibazar",
				"id" => 431
			],
			[
				"province_id" => "4",
				"district_id" => "39",
				"district_name" => "Syangja",
				"municipality_name" => "Waling",
				"id" => 432
			],
			[
				"province_id" => "4",
				"district_id" => "40",
				"district_name" => "Kaski",
				"municipality_name" => "Annapurna",
				"id" => 433
			],
			[
				"province_id" => "4",
				"district_id" => "40",
				"district_name" => "Kaski",
				"municipality_name" => "Machhapuchchhre",
				"id" => 434
			],
			[
				"province_id" => "4",
				"district_id" => "40",
				"district_name" => "Kaski",
				"municipality_name" => "Madi",
				"id" => 435
			],
			[
				"province_id" => "4",
				"district_id" => "40",
				"district_name" => "Kaski",
				"municipality_name" => "Pokhara",
				"id" => 436
			],
			[
				"province_id" => "4",
				"district_id" => "40",
				"district_name" => "Kaski",
				"municipality_name" => "Rupa",
				"id" => 437
			],
			[
				"province_id" => "4",
				"district_id" => "41",
				"district_name" => "Manang",
				"municipality_name" => "Chame",
				"id" => 438
			],
			[
				"province_id" => "4",
				"district_id" => "41",
				"district_name" => "Manang",
				"municipality_name" => "Narphu",
				"id" => 439
			],
			[
				"province_id" => "4",
				"district_id" => "41",
				"district_name" => "Manang",
				"municipality_name" => "Nashong",
				"id" => 440
			],
			[
				"province_id" => "4",
				"district_id" => "41",
				"district_name" => "Manang",
				"municipality_name" => "Neshyang",
				"id" => 441
			],
			[
				"province_id" => "4",
				"district_id" => "42",
				"district_name" => "Mustang",
				"municipality_name" => "Barhagaun Muktikhsetra",
				"id" => 442
			],
			[
				"province_id" => "4",
				"district_id" => "42",
				"district_name" => "Mustang",
				"municipality_name" => "Dalome",
				"id" => 443
			],
			[
				"province_id" => "4",
				"district_id" => "42",
				"district_name" => "Mustang",
				"municipality_name" => "Gharapjhong",
				"id" => 444
			],
			[
				"province_id" => "4",
				"district_id" => "42",
				"district_name" => "Mustang",
				"municipality_name" => "Lomanthang",
				"id" => 445
			],
			[
				"province_id" => "4",
				"district_id" => "42",
				"district_name" => "Mustang",
				"municipality_name" => "Thasang",
				"id" => 446
			],
			[
				"province_id" => "4",
				"district_id" => "43",
				"district_name" => "Myagdi",
				"municipality_name" => "Annapurna",
				"id" => 447
			],
			[
				"province_id" => "4",
				"district_id" => "43",
				"district_name" => "Myagdi",
				"municipality_name" => "Beni",
				"id" => 448
			],
			[
				"province_id" => "4",
				"district_id" => "43",
				"district_name" => "Myagdi",
				"municipality_name" => "Dhaulagiri",
				"id" => 449
			],
			[
				"province_id" => "4",
				"district_id" => "43",
				"district_name" => "Myagdi",
				"municipality_name" => "Malika",
				"id" => 450
			],
			[
				"province_id" => "4",
				"district_id" => "43",
				"district_name" => "Myagdi",
				"municipality_name" => "Mangala",
				"id" => 451
			],
			[
				"province_id" => "4",
				"district_id" => "43",
				"district_name" => "Myagdi",
				"municipality_name" => "Raghuganga",
				"id" => 452
			],
			[
				"province_id" => "4",
				"district_id" => "44",
				"district_name" => "Parbat",
				"municipality_name" => "Bihadi",
				"id" => 453
			],
			[
				"province_id" => "4",
				"district_id" => "44",
				"district_name" => "Parbat",
				"municipality_name" => "Jaljala",
				"id" => 454
			],
			[
				"province_id" => "4",
				"district_id" => "44",
				"district_name" => "Parbat",
				"municipality_name" => "Kushma",
				"id" => 455
			],
			[
				"province_id" => "4",
				"district_id" => "44",
				"district_name" => "Parbat",
				"municipality_name" => "Mahashila",
				"id" => 456
			],
			[
				"province_id" => "4",
				"district_id" => "44",
				"district_name" => "Parbat",
				"municipality_name" => "Modi",
				"id" => 457
			],
			[
				"province_id" => "4",
				"district_id" => "44",
				"district_name" => "Parbat",
				"municipality_name" => "Painyu",
				"id" => 458
			],
			[
				"province_id" => "4",
				"district_id" => "44",
				"district_name" => "Parbat",
				"municipality_name" => "Phalebas",
				"id" => 459
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Badigad",
				"id" => 460
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Baglung",
				"id" => 461
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Bareng",
				"id" => 462
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Dhorpatan",
				"id" => 463
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Galkot",
				"id" => 464
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Jaimini",
				"id" => 465
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Kanthekhola",
				"id" => 466
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Nisikhola",
				"id" => 467
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Taman Khola",
				"id" => 468
			],
			[
				"province_id" => "4",
				"district_id" => "45",
				"district_name" => "Baglung",
				"municipality_name" => "Tara Khola",
				"id" => 469
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Chandrakot",
				"id" => 470
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Chatrakot",
				"id" => 471
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Dhurkot",
				"id" => 472
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Gulmidarbar",
				"id" => 473
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Isma",
				"id" => 474
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Kaligandaki",
				"id" => 475
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Madane",
				"id" => 476
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Malika",
				"id" => 477
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Musikot",
				"id" => 478
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Resunga",
				"id" => 479
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Ruru",
				"id" => 480
			],
			[
				"province_id" => "5",
				"district_id" => "46",
				"district_name" => "Gulmi",
				"municipality_name" => "Satyawati",
				"id" => 481
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Bagnaskali",
				"id" => 482
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Mathagadhi",
				"id" => 483
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Nisdi",
				"id" => 484
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Purbakhola",
				"id" => 485
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Rainadevi Chhahara",
				"id" => 486
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Rambha",
				"id" => 487
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Rampur",
				"id" => 488
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Ribdikot",
				"id" => 489
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Tansen",
				"id" => 490
			],
			[
				"province_id" => "5",
				"district_id" => "47",
				"district_name" => "Palpa",
				"municipality_name" => "Tinau",
				"id" => 491
			],
			[
				"province_id" => "5",
				"district_id" => "76",
				"district_name" => "Nawalparasi_W",
				"municipality_name" => "Bardaghat",
				"id" => 492
			],
			[
				"province_id" => "5",
				"district_id" => "76",
				"district_name" => "Nawalparasi_W",
				"municipality_name" => "Palhi Nandan",
				"id" => 493
			],
			[
				"province_id" => "5",
				"district_id" => "76",
				"district_name" => "Nawalparasi_W",
				"municipality_name" => "Pratappur",
				"id" => 494
			],
			[
				"province_id" => "5",
				"district_id" => "76",
				"district_name" => "Nawalparasi_W",
				"municipality_name" => "Ramgram",
				"id" => 495
			],
			[
				"province_id" => "5",
				"district_id" => "76",
				"district_name" => "Nawalparasi_W",
				"municipality_name" => "Sarawal",
				"id" => 496
			],
			[
				"province_id" => "5",
				"district_id" => "76",
				"district_name" => "Nawalparasi_W",
				"municipality_name" => "Sunwal",
				"id" => 497
			],
			[
				"province_id" => "5",
				"district_id" => "76",
				"district_name" => "Nawalparasi_W",
				"municipality_name" => "Susta",
				"id" => 498
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Butwal",
				"id" => 499
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Devdaha",
				"id" => 500
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Gaidahawa",
				"id" => 501
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Kanchan",
				"id" => 502
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Kotahimai",
				"id" => 503
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Lumbini Sanskritik",
				"id" => 504
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Marchawari",
				"id" => 505
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Mayadevi",
				"id" => 506
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Omsatiya",
				"id" => 507
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Rohini",
				"id" => 508
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Sainamaina",
				"id" => 509
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Sammarimai",
				"id" => 510
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Siddharthanagar",
				"id" => 511
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Siyari",
				"id" => 512
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Sudhdhodhan",
				"id" => 513
			],
			[
				"province_id" => "5",
				"district_id" => "49",
				"district_name" => "Rupandehi",
				"municipality_name" => "Tillotama",
				"id" => 514
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Banganga",
				"id" => 515
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Bijayanagar",
				"id" => 516
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Buddhabhumi",
				"id" => 517
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Kapilbastu",
				"id" => 518
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Krishnanagar",
				"id" => 519
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Maharajgunj",
				"id" => 520
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Mayadevi",
				"id" => 521
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Shivaraj",
				"id" => 522
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Suddhodhan",
				"id" => 523
			],
			[
				"province_id" => "5",
				"district_id" => "50",
				"district_name" => "Kapilbastu",
				"municipality_name" => "Yashodhara",
				"id" => 524
			],
			[
				"province_id" => "5",
				"district_id" => "51",
				"district_name" => "Arghakhanchi",
				"municipality_name" => "Bhumekasthan",
				"id" => 525
			],
			[
				"province_id" => "5",
				"district_id" => "51",
				"district_name" => "Arghakhanchi",
				"municipality_name" => "Chhatradev",
				"id" => 526
			],
			[
				"province_id" => "5",
				"district_id" => "51",
				"district_name" => "Arghakhanchi",
				"municipality_name" => "Malarani",
				"id" => 527
			],
			[
				"province_id" => "5",
				"district_id" => "51",
				"district_name" => "Arghakhanchi",
				"municipality_name" => "Panini",
				"id" => 528
			],
			[
				"province_id" => "5",
				"district_id" => "51",
				"district_name" => "Arghakhanchi",
				"municipality_name" => "Sandhikharka",
				"id" => 529
			],
			[
				"province_id" => "5",
				"district_id" => "51",
				"district_name" => "Arghakhanchi",
				"municipality_name" => "Sitganga",
				"id" => 530
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Airawati",
				"id" => 531
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Gaumukhi",
				"id" => 532
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Jhimruk",
				"id" => 533
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Mallarani",
				"id" => 534
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Mandavi",
				"id" => 535
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Naubahini",
				"id" => 536
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Pyuthan",
				"id" => 537
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Sarumarani",
				"id" => 538
			],
			[
				"province_id" => "5",
				"district_id" => "52",
				"district_name" => "Pyuthan",
				"municipality_name" => "Sworgadwary",
				"id" => 539
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Pariwartan",
				"id" => 540
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Lungri",
				"id" => 541
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Madi",
				"id" => 542
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Rolpa",
				"id" => 543
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Runtigadi",
				"id" => 544
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Ganga Dev",
				"id" => 545
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Sunchhahari",
				"id" => 546
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Sunil Smiriti",
				"id" => 547
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Thawang",
				"id" => 548
			],
			[
				"province_id" => "5",
				"district_id" => "53",
				"district_name" => "Rolpa",
				"municipality_name" => "Tribeni",
				"id" => 549
			],
			[
				"province_id" => "6",
				"district_id" => "54",
				"district_name" => "Rukum_W",
				"municipality_name" => "Aathbiskot",
				"id" => 550
			],
			[
				"province_id" => "6",
				"district_id" => "54",
				"district_name" => "Rukum_W",
				"municipality_name" => "Banfikot",
				"id" => 551
			],
			[
				"province_id" => "6",
				"district_id" => "54",
				"district_name" => "Rukum_W",
				"municipality_name" => "Chaurjahari",
				"id" => 552
			],
			[
				"province_id" => "6",
				"district_id" => "54",
				"district_name" => "Rukum_W",
				"municipality_name" => "Musikot",
				"id" => 553
			],
			[
				"province_id" => "6",
				"district_id" => "54",
				"district_name" => "Rukum_W",
				"municipality_name" => "Sani Bheri",
				"id" => 554
			],
			[
				"province_id" => "6",
				"district_id" => "54",
				"district_name" => "Rukum_W",
				"municipality_name" => "Tribeni",
				"id" => 555
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Bagchaur",
				"id" => 556
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Bangad Kupinde",
				"id" => 557
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Chhatreshwori",
				"id" => 558
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Darma",
				"id" => 559
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Dhorchaur",
				"id" => 560
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Kalimati",
				"id" => 561
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Kapurkot",
				"id" => 562
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Kumakhmalika",
				"id" => 563
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Sharada",
				"id" => 564
			],
			[
				"province_id" => "6",
				"district_id" => "55",
				"district_name" => "Salyan",
				"municipality_name" => "Tribeni",
				"id" => 565
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Babai",
				"id" => 566
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Banglachuli",
				"id" => 567
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Dangisharan",
				"id" => 568
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Gadhawa",
				"id" => 569
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Ghorahi",
				"id" => 570
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Lamahi",
				"id" => 571
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Rajpur",
				"id" => 572
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Rapti",
				"id" => 573
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Shantinagar",
				"id" => 574
			],
			[
				"province_id" => "5",
				"district_id" => "56",
				"district_name" => "Dang",
				"municipality_name" => "Tulsipur",
				"id" => 575
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Baijanath",
				"id" => 576
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Duduwa",
				"id" => 577
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Janki",
				"id" => 578
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Khajura",
				"id" => 579
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Kohalpur",
				"id" => 580
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Narainapur",
				"id" => 581
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Nepalgunj",
				"id" => 582
			],
			[
				"province_id" => "5",
				"district_id" => "57",
				"district_name" => "Banke",
				"municipality_name" => "Rapti Sonari",
				"id" => 583
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Badhaiyatal",
				"id" => 584
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Bansagadhi",
				"id" => 585
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Barbardiya",
				"id" => 586
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Geruwa",
				"id" => 587
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Gulariya",
				"id" => 588
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Madhuwan",
				"id" => 589
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Rajapur",
				"id" => 590
			],
			[
				"province_id" => "5",
				"district_id" => "58",
				"district_name" => "Bardiya",
				"municipality_name" => "Thakurbaba",
				"id" => 591
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Barahtal",
				"id" => 592
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Bheriganga",
				"id" => 593
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Birendranagar",
				"id" => 594
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Chaukune",
				"id" => 595
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Chingad",
				"id" => 596
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Gurbhakot",
				"id" => 597
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Lekbeshi",
				"id" => 598
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Panchpuri",
				"id" => 599
			],
			[
				"province_id" => "6",
				"district_id" => "59",
				"district_name" => "Surkhet",
				"municipality_name" => "Simta",
				"id" => 600
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Aathabis",
				"id" => 601
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Bhagawatimai",
				"id" => 602
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Bhairabi",
				"id" => 603
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Chamunda Bindrasaini",
				"id" => 604
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Dullu",
				"id" => 605
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Dungeshwor",
				"id" => 606
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Gurans",
				"id" => 607
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Mahabu",
				"id" => 608
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Narayan",
				"id" => 609
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Naumule",
				"id" => 610
			],
			[
				"province_id" => "6",
				"district_id" => "60",
				"district_name" => "Dailekh",
				"municipality_name" => "Thantikandh",
				"id" => 611
			],
			[
				"province_id" => "6",
				"district_id" => "61",
				"district_name" => "Jajarkot",
				"municipality_name" => "Barekot",
				"id" => 612
			],
			[
				"province_id" => "6",
				"district_id" => "61",
				"district_name" => "Jajarkot",
				"municipality_name" => "Bheri",
				"id" => 613
			],
			[
				"province_id" => "6",
				"district_id" => "61",
				"district_name" => "Jajarkot",
				"municipality_name" => "Chhedagad",
				"id" => 614
			],
			[
				"province_id" => "6",
				"district_id" => "61",
				"district_name" => "Jajarkot",
				"municipality_name" => "Junichande",
				"id" => 615
			],
			[
				"province_id" => "6",
				"district_id" => "61",
				"district_name" => "Jajarkot",
				"municipality_name" => "Kuse",
				"id" => 616
			],
			[
				"province_id" => "6",
				"district_id" => "61",
				"district_name" => "Jajarkot",
				"municipality_name" => "Shiwalaya",
				"id" => 617
			],
			[
				"province_id" => "6",
				"district_id" => "61",
				"district_name" => "Jajarkot",
				"municipality_name" => "Nalgaad",
				"id" => 618
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Chharka Tangsong",
				"id" => 619
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Dolpo Buddha",
				"id" => 620
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Jagadulla",
				"id" => 621
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Kaike",
				"id" => 622
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Mudkechula",
				"id" => 623
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Shey Phoksundo",
				"id" => 624
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Thuli Bheri",
				"id" => 625
			],
			[
				"province_id" => "6",
				"district_id" => "62",
				"district_name" => "Dolpa",
				"municipality_name" => "Tripurasundari",
				"id" => 626
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Chandannath",
				"id" => 627
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Guthichaur",
				"id" => 628
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Hima",
				"id" => 629
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Kanakasundari",
				"id" => 630
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Patrasi",
				"id" => 631
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Sinja",
				"id" => 632
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Tatopani",
				"id" => 633
			],
			[
				"province_id" => "6",
				"district_id" => "63",
				"district_name" => "Jumla",
				"municipality_name" => "Tila",
				"id" => 634
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Kalika",
				"id" => 635
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Khandachakra",
				"id" => 636
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Mahawai",
				"id" => 637
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Naraharinath",
				"id" => 638
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Pachaljharana",
				"id" => 639
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Palata",
				"id" => 640
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Raskot",
				"id" => 641
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Sanni Tribeni",
				"id" => 642
			],
			[
				"province_id" => "6",
				"district_id" => "64",
				"district_name" => "Kalikot",
				"municipality_name" => "Tilagufa",
				"id" => 643
			],
			[
				"province_id" => "6",
				"district_id" => "65",
				"district_name" => "Mugu",
				"municipality_name" => "Chhayanath Rara",
				"id" => 644
			],
			[
				"province_id" => "6",
				"district_id" => "65",
				"district_name" => "Mugu",
				"municipality_name" => "Khatyad",
				"id" => 645
			],
			[
				"province_id" => "6",
				"district_id" => "65",
				"district_name" => "Mugu",
				"municipality_name" => "Mugum Karmarong",
				"id" => 646
			],
			[
				"province_id" => "6",
				"district_id" => "65",
				"district_name" => "Mugu",
				"municipality_name" => "Soru",
				"id" => 647
			],
			[
				"province_id" => "6",
				"district_id" => "66",
				"district_name" => "Humla",
				"municipality_name" => "Adanchuli",
				"id" => 648
			],
			[
				"province_id" => "6",
				"district_id" => "66",
				"district_name" => "Humla",
				"municipality_name" => "Chankheli",
				"id" => 649
			],
			[
				"province_id" => "6",
				"district_id" => "66",
				"district_name" => "Humla",
				"municipality_name" => "Kharpunath",
				"id" => 650
			],
			[
				"province_id" => "6",
				"district_id" => "66",
				"district_name" => "Humla",
				"municipality_name" => "Namkha",
				"id" => 651
			],
			[
				"province_id" => "6",
				"district_id" => "66",
				"district_name" => "Humla",
				"municipality_name" => "Sarkegad",
				"id" => 652
			],
			[
				"province_id" => "6",
				"district_id" => "66",
				"district_name" => "Humla",
				"municipality_name" => "Simkot",
				"id" => 653
			],
			[
				"province_id" => "6",
				"district_id" => "66",
				"district_name" => "Humla",
				"municipality_name" => "Tanjakot",
				"id" => 654
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Badimalika",
				"id" => 655
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Budhiganga",
				"id" => 656
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Budhinanda",
				"id" => 657
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Chhededaha",
				"id" => 658
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Gaumul",
				"id" => 659
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Himali",
				"id" => 660
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Pandav Gupha",
				"id" => 661
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Swami Kartik",
				"id" => 662
			],
			[
				"province_id" => "7",
				"district_id" => "67",
				"district_name" => "Bajura",
				"municipality_name" => "Tribeni",
				"id" => 663
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Bithadchir",
				"id" => 664
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Bungal",
				"id" => 665
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Chabispathivera",
				"id" => 666
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Durgathali",
				"id" => 667
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "JayaPrithivi",
				"id" => 668
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Kanda",
				"id" => 669
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Kedarseu",
				"id" => 670
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Khaptadchhanna",
				"id" => 671
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Masta",
				"id" => 672
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Surma",
				"id" => 673
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Talkot",
				"id" => 674
			],
			[
				"province_id" => "7",
				"district_id" => "68",
				"district_name" => "Bajhang",
				"municipality_name" => "Thalara",
				"id" => 675
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Bannigadhi Jayagadh",
				"id" => 676
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Chaurpati",
				"id" => 677
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Dhakari",
				"id" => 678
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Kamalbazar",
				"id" => 679
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Mangalsen",
				"id" => 680
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Mellekh",
				"id" => 681
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Panchadewal Binayak",
				"id" => 682
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Ramaroshan",
				"id" => 683
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Sanphebagar",
				"id" => 684
			],
			[
				"province_id" => "7",
				"district_id" => "69",
				"district_name" => "Achham",
				"municipality_name" => "Turmakhad",
				"id" => 685
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Adharsha",
				"id" => 686
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Badikedar",
				"id" => 687
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Bogtan",
				"id" => 688
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Dipayal Silgadi",
				"id" => 689
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Jorayal",
				"id" => 690
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "K I Singh",
				"id" => 691
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Purbichauki",
				"id" => 692
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Sayal",
				"id" => 693
			],
			[
				"province_id" => "7",
				"district_id" => "70",
				"district_name" => "Doti",
				"municipality_name" => "Shikhar",
				"id" => 694
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Bardagoriya",
				"id" => 695
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Bhajani",
				"id" => 696
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Chure",
				"id" => 697
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Dhangadhi",
				"id" => 698
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Gauriganga",
				"id" => 699
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Ghodaghodi",
				"id" => 700
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Godawari",
				"id" => 701
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Janaki",
				"id" => 702
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Joshipur",
				"id" => 703
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Kailari",
				"id" => 704
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Lamkichuha",
				"id" => 705
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Mohanyal",
				"id" => 706
			],
			[
				"province_id" => "7",
				"district_id" => "71",
				"district_name" => "Kailali",
				"municipality_name" => "Tikapur",
				"id" => 707
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Bedkot",
				"id" => 708
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Belauri",
				"id" => 709
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Beldandi",
				"id" => 710
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Bhimdatta",
				"id" => 711
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Krishnapur",
				"id" => 712
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Laljhadi",
				"id" => 713
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Mahakali",
				"id" => 714
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Punarbas",
				"id" => 715
			],
			[
				"province_id" => "7",
				"district_id" => "72",
				"district_name" => "Kanchanpur",
				"municipality_name" => "Shuklaphanta",
				"id" => 716
			],
			[
				"province_id" => "7",
				"district_id" => "73",
				"district_name" => "Dadeldhura",
				"municipality_name" => "Ajaymeru",
				"id" => 717
			],
			[
				"province_id" => "7",
				"district_id" => "73",
				"district_name" => "Dadeldhura",
				"municipality_name" => "Alital",
				"id" => 718
			],
			[
				"province_id" => "7",
				"district_id" => "73",
				"district_name" => "Dadeldhura",
				"municipality_name" => "Amargadhi",
				"id" => 719
			],
			[
				"province_id" => "7",
				"district_id" => "73",
				"district_name" => "Dadeldhura",
				"municipality_name" => "Bhageshwar",
				"id" => 720
			],
			[
				"province_id" => "7",
				"district_id" => "73",
				"district_name" => "Dadeldhura",
				"municipality_name" => "Ganayapdhura",
				"id" => 721
			],
			[
				"province_id" => "7",
				"district_id" => "73",
				"district_name" => "Dadeldhura",
				"municipality_name" => "Nawadurga",
				"id" => 722
			],
			[
				"province_id" => "7",
				"district_id" => "73",
				"district_name" => "Dadeldhura",
				"municipality_name" => "Parashuram",
				"id" => 723
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Dasharathchanda",
				"id" => 724
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Dilasaini",
				"id" => 725
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Dogadakedar",
				"id" => 726
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Melauli",
				"id" => 727
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Pancheshwar",
				"id" => 728
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Patan",
				"id" => 729
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Purchaudi",
				"id" => 730
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Shivanath",
				"id" => 731
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Sigas",
				"id" => 732
			],
			[
				"province_id" => "7",
				"district_id" => "74",
				"district_name" => "Baitadi",
				"municipality_name" => "Surnaya",
				"id" => 733
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Apihimal",
				"id" => 734
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Byas",
				"id" => 735
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Dunhu",
				"id" => 736
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Lekam",
				"id" => 737
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Mahakali",
				"id" => 738
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Malikaarjun",
				"id" => 739
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Marma",
				"id" => 740
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Naugad",
				"id" => 741
			],
			[
				"province_id" => "7",
				"district_id" => "75",
				"district_name" => "Darchula",
				"municipality_name" => "Shailyashikhar",
				"id" => 742
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Binayee Tribeni",
				"id" => 743
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Bulingtar",
				"id" => 744
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Bungdikali",
				"id" => 745
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Devchuli",
				"id" => 746
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Gaidakot",
				"id" => 747
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Hupsekot",
				"id" => 748
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Kawasoti",
				"id" => 749
			],
			[
				"province_id" => "4",
				"district_id" => "48",
				"district_name" => "Nawalparasi_E",
				"municipality_name" => "Madhyabindu",
				"id" => 750
			],
			[
				"province_id" => "5",
				"district_id" => "77",
				"district_name" => "Rukum_E",
				"municipality_name" => "Bhume",
				"id" => 751
			],
			[
				"province_id" => "5",
				"district_id" => "77",
				"district_name" => "Rukum_E",
				"municipality_name" => "Putha Uttarganga",
				"id" => 752
			],
			[
				"province_id" => "5",
				"district_id" => "77",
				"district_name" => "Rukum_E",
				"municipality_name" => "Sisne",
				"id" => 753
			]
		];

		return $municipalities;
	}

	public static function getIMUProvinces()
	{
		$provinces = [
			[
				"id"            => 1,
				"province_name" => "Province No. 1"
			],
			[
				"id"            => 2,
				"province_name" => "Province No. 2"
			],
			[
				"id"            => 3,
				"province_name" => "Bagmati"
			],
			[
				"id"            => 4,
				"province_name" => "Gandaki"
			],
			[
				"id"            => 5,
				"province_name" => "Lumbini"
			],
			[
				"id"            => 6,
				"province_name" => "Karnali"
			],
			[
				"id"            => 7,
				"province_name" => "Sudurpashchim"
			]
		];

		return $provinces;
	}

	public static function getAccountGroupId($AccountNo)
	{
		$name = AccountLedger::select('GroupId', 'AccountName')->where('AccountNo', $AccountNo)->first();
		return $name ? $name->GroupId : 0;
	}

	public static function getPayableDoctorName($service_id)
	{
		// echo $service_id; exit;
		$payablesql = \DB::table('pat_billing_shares as pbs')->select(DB::raw("CONCAT(u.firstname,' ',u.lastname) as full_name"))
			->join('users as u', 'u.id', 'pbs.user_id')
			->where('pat_billing_id', $service_id)
			->get();
		// dd($payablesql);
		$doctorname = '';
		$doctorsarray = array();
		if (isset($payablesql) and count($payablesql) > 0) {
			foreach ($payablesql as $key => $value) {
				$doctorsarray[] = $value->full_name;
			}
			$doctorname = implode(',', $doctorsarray);
		}
		return $doctorname;
	}

	public static function patientTestLogReport($fldpatientval, $testid)
	{
		$result = DB::table('tblpatlabtest as plt')
			->select(
				// DB::raw('pi.fldpatientval in (SELECT MAX(fldpatientval) from tblpatientinfo GROUP BY fldpatientval)'),
				"plt.fldid",
				"pi.fldpatientval",
				"plt.fldencounterval",
				"pi.fldptnamefir",
				"pi.fldptnamelast",
				"plt.fldtestid",
				"plt.fldtime_sample",
				"plt.flduserid_sample",
				"plt.fldtime_report",
				"plt.flduserid_report",
				"plt.fldtime_verify",
				"plt.flduserid_verify"
			)
			->join('tblencounter as en', 'plt.fldencounterval', '=', 'en.fldencounterval')
			->join('tblpatientinfo as pi', 'en.fldpatientval', '=', 'pi.fldpatientval')
			->where('pi.fldpatientval', $fldpatientval)
			->whereNotIn('plt.fldtestid', [$testid])
			->get();
		return $result;
	}

	public static function monthWiseAdmission($month)
	{
		$fiscal_year_range = self::getNepaliFiscalYearRange();
		$current_start_fiscal_year_neapli = $fiscal_year_range['startdate'];
		$current_start_fiscal_year_english = self::dateNepToEng($current_start_fiscal_year_neapli)->full_date;
		$today_english_date = date("Y-m-d");

		$eng_from_date = $_GET['eng_from_date'] ?? '';
		$eng_to_date = $_GET['eng_to_date'] ?? '';
		$admission = Encounter::select(
			DB::raw('(fldencounterval) as admission_data'),
			DB::raw("DATE_FORMAT(flddoa, '%Y-%m-%d') date_of_admission")
		)
			->orderBy('flddoa', 'desc')
			->whereNotNull('flddoa')
			->whereNotIn('fldadmission', ['Registered', 'Recorded']);
		if ($eng_from_date) {
			$admission =	$admission->whereDate('flddoa', '>=', $eng_from_date)->whereDate('flddoa', '<=', $eng_to_date);
		} else {
			$admission =	$admission->whereDate('flddoa', '>=', $current_start_fiscal_year_english)->whereDate('flddoa', '<=', $today_english_date);
		}
		$admission = $admission->get();
		$admissions = $admission->each(function ($admission) {
			return [
				$admission->nepali_month_admission = self::dateEngToNepdash($admission->date_of_admission)->month
			];
		});
		$admission_month_wise = $admissions->groupBy('nepali_month_admission')->toArray();
		$final_admissions = [];
		foreach ($admission_month_wise as $admission_wise) {
			if ($month == $admission_wise[0]['nepali_month_admission']) {

				$final_admissions[] = [
					'admission_data' => count($admission_wise),
					'date_of_admission' => $admission_wise[0]['date_of_admission'],
				];
			}
		}
		return $final_admissions;
	}

	public static function monthWiseDischarge($month)
	{
		$fiscal_year_range = self::getNepaliFiscalYearRange();
		$current_start_fiscal_year_neapli = $fiscal_year_range['startdate'];
		$current_start_fiscal_year_english = self::dateNepToEng($current_start_fiscal_year_neapli)->full_date;
		$today_english_date = date("Y-m-d");

		$eng_from_date = $_GET['eng_from_date'] ?? '';
		$eng_to_date = $_GET['eng_to_date'] ?? '';
		$discharge = Encounter::select(
			DB::raw('(fldencounterval) as discharge_data'),
			DB::raw("DATE_FORMAT(flddod, '%Y-%m-%d') date_of_discharge")
		)

			->orderBy('flddod', 'desc')
			->whereNotNull('flddod')
			->whereNotIn('fldadmission', ['Registered', 'Recorded']);
		if ($eng_from_date) {
			$discharge =	$discharge->whereDate('flddod', '>=', $eng_from_date)->whereDate('flddod', '<=', $eng_to_date);
		} else {
			$discharge =	$discharge->whereDate('flddod', '>=', $current_start_fiscal_year_english)->whereDate('flddod', '<=', $today_english_date);
		}
		$discharge = $discharge->get();
		$discharges = $discharge->each(function ($discharge) {
			return [
				$discharge->nepali_month_discharge = self::dateEngToNepdash($discharge->date_of_discharge)->month
			];
		});
		$discharge_month_wise = $discharges->groupBy('nepali_month_discharge')->toArray();
		$final_discharges = [];
		foreach ($discharge_month_wise as $discharge_wise) {
			if ($month == $discharge_wise[0]['nepali_month_discharge']) {
				$final_discharges[] = [
					'discharge_data' => count($discharge_wise),
					'date_of_discharge' => $discharge_wise[0]['date_of_discharge']
				];
			}
		}

		return $final_discharges;
	}

	public static function getOS($user_agent)
	{
		$os_platform  = "Unknown OS Platform";
		$os_array = array(
			'/windows nt 10/i'      =>  'Windows 10',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile'
		);

		foreach ($os_array as $regex => $value)
			if (preg_match($regex, $user_agent))
				$os_platform = $value;

		return $os_platform;
	}

	public static function getBrowser($user_agent)
	{
		$browser = "Unknown Browser";
		$browser_array = array(
			'/msie/i'      => 'Internet Explorer',
			'/firefox/i'   => 'Firefox',
			'/safari/i'    => 'Safari',
			'/chrome/i'    => 'Chrome',
			'/edge/i'      => 'Edge',
			'/opera/i'     => 'Opera',
			'/netscape/i'  => 'Netscape',
			'/maxthon/i'   => 'Maxthon',
			'/konqueror/i' => 'Konqueror',
			'/mobile/i'    => 'Handheld Browser'
		);

		foreach ($browser_array as $regex => $value)
			if (preg_match($regex, $user_agent))
				$browser = $value;

		return $browser;
	}

	public static function getCreditAmount($patientID)
	{
		$encounterIdsForPatient = Encounter::where('fldpatientval', $patientID)->pluck('fldencounterval');

		$creditAmount = 0;
		if (isset($encounterIdsForPatient) and count($encounterIdsForPatient)) {
			foreach ($encounterIdsForPatient as $encounter) {
				$amount = \App\PatBillDetail::select('fldcurdeposit')
					->where('fldencounterval', $encounter)
					->where('fldbilltype', '=', 'Credit')
					->where('fldcurdeposit', '<', 0)
					->where('fldcomp', '=', self::getCompName())
					->orderBy('fldid', 'DESC')
					->first();

				$creditAmount += (isset($amount) and !is_null($amount->fldcurdeposit)) ? $amount->fldcurdeposit : 0;
			}
		}
		return $creditAmount;
	}

	public static function getTpAmount($encounter_id)
	{
		$tpAmount = \App\PatBilling::where('fldsave', 0)
			->where('fldencounterval', $encounter_id)
			->where(function ($query) {
				$query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
					->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%')
					->orWhere('fldtempbillno', 'LIKE', '%TP-%')
					->orWhere('fldtempbillno', '!=', NULL);
			})
			->where(function ($query) {
				$query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
					->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
					->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
			})
			->where('fldcomp', self::getCompName())
			->sum('fldditemamt');
		return $tpAmount;
	}

	public static function totalDepositAmountReceived($encounter_id)
	{
		$depositAmount = \App\PatBillDetail::select('fldcurdeposit')
			->where('fldencounterval', $encounter_id)
			->where('fldbilltype', '=', 'Credit')
			->where('fldcomp', '=', self::getCompName())
			->orderBy('fldid', 'DESC')
			->where('fldcurdeposit', '>', '0')
			->first();
		$depositAmount = (isset($depositAmount) and $depositAmount->fldcurdeposit != '') ? $depositAmount->fldcurdeposit : '0';
		return $depositAmount;
	}

	public static function numberFormat($amount, $type = 'view')
	{
		$dec = 2;
		if ($type == 'view') {
			return number_format(floor($amount * pow(10, $dec)) / pow(10, $dec), $dec);
		} else {
			if ($amount != '') {
				$valuegot = str_replace(',', '', $amount);
				return number_format(floor($valuegot * pow(10, $dec)) / pow(10, $dec), $dec, '.', '');
			} else {
				return '0.00';
			}
		}
	}

	public static function getAccountName($AccountNo)
	{
		$name = AccountLedger::select('GroupId', 'AccountName')->where('AccountNo', $AccountNo)->first();
		return $name ? $name->AccountName : 'No Name';
	}
	public static function billType($type = '')
	{
		if ($type) {
			switch (trim(strtolower($type))) {
				case 'cas':
					return 'Service Billing';
					break;
				case 'dep':
					return 'Deposit Billing';
					break;
				case 'cre':
					return 'Credit Billing';
					break;
				case 'phm':
					return 'Pharmacy Billing';
					break;
				case 'ret':
					return 'Return Billing';
					break;
				case 'disclr':
					return 'Discharge Clearance Billing';
					break;
				case 'refund':
					return 'Refund Billing';
					break;
				default:
					return $type;
			}
		}
		return [
			"cas" => "Service Billing",
			"dep" => "Deposit Billing",
			"cre" => "Credit Billing",
			"phm" => "Pharmacy Billing",
			"ret" => "Return Billing",
			"disclr" => "Discharge Clearance Billing",
			"refund" => "Refund Billing",
		];
	}
}
