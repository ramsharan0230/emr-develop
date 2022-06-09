<?php

namespace Modules\ConsultPatientData\Http\Controllers;

use App\Departmentbed;
use App\Encounter;
use App\PatientDate;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class IpEventsController
 * @package Modules\ConsultPatientData\Http\Controllers
 */
class IpEventsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayReport()
    {
        if (Permission::checkPermissionFrontendAdmin('inpatient-report')) {
            $data['department'] = Departmentbed::select('flddept')->distinct('flddept')->get();
            return view('consultpatientdata::ip-events-report', $data);
        }

        Session::flash('display_popup_error_success', true);
        Session::flash('error_message', 'You are not authorized for this action.');
        return redirect()->route('admin.dashboard');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchList(Request $request)
    {
        $resultData = PatientDate::query();
        $resultData->select('fldid', 'fldtime', 'fldencounterval', 'fldhead');
        $from_date = $request->from_date;
        $last_status = $request->last_status;
        $to_date = $request->to_date;
        $department = $request->department;
        $gender = $request->gender;

        if (isset($last_status) && $last_status == "Exits(All)") {
            $resultData->where(function ($query) {
                return $query
                    ->orWhere('fldhead', '=', 'Discharged')
                    ->orWhere('fldhead', '=', 'LAMA')
                    ->orWhere('fldhead', '=', 'Refer')
                    ->orWhere('fldhead', '=', 'Death')
                    ->orWhere('fldhead', '=', 'Absconder');
            });
        }

        if (isset($last_status) && $last_status != "Exits(All)") {
            $resultData->where('fldhead', $last_status);
        }

        if (isset($department)) {
           // $dept = Departmentbed::select('fldbed')->where('flddept', $department)->pluck('fldbed');
            $encounterDept = [];

          //  DB::enableQueryLog();

            $encounterDept = Encounter::where('fldregdate','!=','')
            ->when($department != '', function ($q) use ($department) {
                return $q->where('fldcurrlocat', $department)
                ->orwhere('fldadmitlocat', $department);
            })
            ->pluck('fldencounterval');

           // dd(DB::getQueryLog());

            if (count($encounterDept)) {
                $resultData->whereIn('fldencounterval', $encounterDept);
            }
        }

        if (isset($from_date)) {
            $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
            $resultData->where('fldtime', '>=', $startTime);
        }

        if (isset($to_date)) {
            $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
            $resultData->where('fldtime', '<=', $endTime);
        }

        if ($gender != "") {
            $resultDataPatientgender = DB::table('tblpatientinfo')->select('tblencounter.fldencounterval')
                ->join('tblencounter', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->where('tblpatientinfo.fldptsex', 'LIKE', $gender)
                ->pluck('tblencounter.fldencounterval');

            if (count($resultDataPatientgender)) {
                $resultData->whereIn('fldencounterval', $resultDataPatientgender);
            }
        }

        if ($request->age_from  != "" && $request->age_to != "") {
            $age_from = $request->age_from * 365;
            $age_to = $request->age_to + 1;
            $age_to = $age_to * 365;
            $resultDataPatientValAge = DB::table('tblpatientinfo')->select('tblencounter.fldencounterval')
                ->join('tblencounter', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->join('tblpatientdate', 'tblpatientdate.fldencounterval', '=', 'tblencounter.fldencounterval')
                ->where('tblpatientinfo.fldptbirday', 'LIKE', '%')
                ->whereRaw('DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday) >= ' . $age_from)
                ->whereRaw('DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday) < ' . $age_to)
                ->pluck('tblencounter.fldencounterval');

            if (count($resultDataPatientValAge)) {
                $resultData->whereIn('fldencounterval', $resultDataPatientValAge);
            }
        }
        if ($request->has('typePdf')) {
            $resultArray = $resultData->with(['encounter', 'encounter.patientInfo', 'encounter.consultant'])->groupBy('fldencounterval')->orderBy('fldid', 'DESC')->get();
        } else {
            $resultArray = $resultData->with(['encounter', 'encounter.patientInfo', 'encounter.consultant'])->groupBy('fldencounterval')->orderBy('fldid', 'DESC')->paginate(25);
        }
        //dd($resultArray);
        $count = 1;
        $html = '';

        foreach ($resultArray as $patient) {
            if ($patient->encounter) {
                $html .= '<tr>';
                $html .= '<td>' . $count . '</td>';
                $html .= '<td>' . $patient->fldencounterval . '</td>';
                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
                $namePatient = $patient->encounter && $patient->encounter->patientInfo ? $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast : '';
                $BodPatient = $patient->encounter && $patient->encounter->patientInfo ? $patient->encounter->patientInfo->fldagestyle : '';
                // $BodPatient = $patient->encounter && $patient->encounter->patientInfo ? Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) : '';
                $sexPatient = $patient->encounter && $patient->encounter->patientInfo ? $patient->encounter->patientInfo->fldptsex : '';
                $html .= '<td>' . $user_rank . ' ' . $namePatient . '</td>';
                $html .= '<td>' . $BodPatient . '</td>';
                $html .= '<td class="text-left">' . $sexPatient . '</td>';
                $html .= '<td class="text-left">' . $patient->fldtime . '</td>';
                $lastLocation = $patient->encounter ? $patient->encounter->fldcurrlocat ?? "" : "";
                $html .= '<td class="text-left">' . $lastLocation . '</td>';
                $admitLocation = $patient->encounter ? $patient->encounter->fldadmitlocat ?? "" : "";
                $html .= '<td class="text-left">' . $admitLocation . '</td>';
                $consultant = $patient->encounter && $patient->encounter->consultant ? $patient->encounter->consultant->flduserid : "";
                $html .= '<td class="text-left">' . $patient->fldhead . '</td>';
                $html .= '<td class="text-left">' . ucwords(str_replace('.', ' ', $consultant)) . '</td>';
                $html .= '</tr>';

                $count++;
            }

        }
        if (!$request->has('typePdf')) {
            $html .= '<tr><td colspan="20">' . $resultArray->appends(request()->all())->links() . '</td></tr>';
        }
        if($request->has('typePdf')){
            $data = [];
            $data['html'] = $html;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['certificate'] = "IP EVENTS REPORT";
            return view('consultpatientdata::pdf.ip-events-report-pdf', $data);
        }else{
            return $html;
        }
    }

    public function searchDataName(Request $request)
    {
        $patientInfo = PatientInfo::select('fldpatientval')
            ->whereRaw('lower(fldptnamefir) like \'' . $request->firstname . '\'')
            ->whereRaw('lower(fldptnamelast) like \'' . $request->lastname . '\'')
            ->pluck('fldpatientval');

        $encounterIds = Encounter::select('fldencounterval')
            ->whereIn('fldpatientval', $patientInfo)
            ->pluck('fldencounterval');

        $resultArray = PatientDate::select('fldid', 'fldtime', 'fldencounterval', 'fldhead')
            ->whereIn('fldencounterval', $encounterIds)
            ->with(['encounter', 'encounter.patientInfo'])
            ->get();

        $html = '';
        $count = 1;

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
            $html .= '<td>DOA</td>';
            $html .= '<td>' . $patient->encounter->fldcurrlocat . '</td>';
            $html .= '<td>' . $patient->fldhead . '</td>';
            $html .= '<td>ccc</td>';
            $html .= '</tr>';
            $count++;
        }
        return $html;
    }

    public function displaySearchNameForm()
    {
        $data['routeName'] = route('display.consultation.ip.events.search.name');
        $data['appendId'] = 'ip-events-data';
        return view('consultpatientdata::common.search-name', $data);
    }

    public function generatePdf(Request $request)
    {
        try {
            $type = $data['pdfType'] = $request->typePdf;
            $to = Carbon::parse($request->to_date)->setTime(23, 59, 59);
            $from = Carbon::parse($request->from_date)->setTime(00, 00, 00);
            if ($encounterPatientVal = Encounter::select("fldpatientval")->whereBetween('fldregdate', array($from, $to))->pluck('fldpatientval')) {

                /*DEPARTMENT*/
                if ($type == 'Department') {
                    $departReg = Encounter::select("fldadmitlocat", "fldrank")
                        ->whereBetween('fldregdate', array($from, $to))
                        ->orderBy('fldadmitlocat', 'ASC')
                        ->get();

                    foreach ($departReg as $PI) {
                        $dataLoop[$PI->fldadmitlocat]['total'] = Encounter::select(DB::raw('fldencounterval', 'fldrank', "COUNT(fldencounterval) as total"))
                            ->whereBetween('fldregdate', array($from, $to))
                            ->where('fldadmitlocat', $PI->fldadmitlocat)
                            ->count();
                    }

                    $totaldata = 0;
                    if (isset($dataLoop) and count($dataLoop) > 0) {
                        foreach ($dataLoop as $r) {
                            $totaldata = $totaldata + (int)$r;
                        }
                    }

                    $data['date_from'] = date('m/d/Y', strtotime($from));
                    $data['date_to'] = date('m/d/Y', strtotime($to));
                    $data['total'] = $totaldata;
                    $data['result'] = $dataLoop;
                    return view('consultpatientdata::pdf.sex-gender-district', $data);
                        // ->setPaper('a4')
                        // ->download('consultation_summary_' . $type . '.pdf');
                }
            } else {
                return "No data found.";
            }
        } catch (\GearmanException $e) {
            return $e;
        }
    }

    public function monthWiseAdmissionDischarge(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;

        $fiscal_year_range=Helpers::getNepaliFiscalYearRange();
        $current_start_fiscal_year_neapli=$fiscal_year_range['startdate'];
        $current_start_fiscal_year_english=Helpers::dateNepToEng($current_start_fiscal_year_neapli)->full_date;
        $today_english_date=date("Y-m-d");
        // dd($current_start_fiscal_year_english);
        $admission_count=Encounter::select(
            DB::raw('count(fldencounterval) as admission_data')
        )
        ->whereNotNull('flddoa')
        ->whereNotIn('fldadmission', ['Registered','Recorded']);
        
        if($eng_from_date){
			$admission_count=	$admission_count->whereDate('flddoa','>=', $eng_from_date)->whereDate('flddoa','<=', $eng_to_date);
		}else{
            $admission_count=	$admission_count->whereDate('flddoa','>=', $current_start_fiscal_year_english)->whereDate('flddoa','<=', $today_english_date);
        }
        $admission_count=$admission_count->first();

        $discharge_count=Encounter::select(
            DB::raw('count(fldencounterval) as discharge_data')
        )
        ->whereNotNull('flddod')
        ->whereNotIn('fldadmission', ['Registered','Recorded']);
        if($eng_from_date){
			$discharge_count=	$discharge_count->whereDate('flddod','>=', $eng_from_date)->whereDate('flddod','<=', $eng_to_date);
		}else{
            $discharge_count=	$discharge_count->whereDate('flddod','>=', $current_start_fiscal_year_english)->whereDate('flddod','<=', $today_english_date);
        }
        $discharge_count=$discharge_count->first();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('consultpatientdata::month-wise-admission-discharge-report.index', compact('admission_count','discharge_count','date'));
    }

    public function monthWiseAdmissionDischargePdf(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $admission_count=Encounter::select(
            DB::raw('count(fldencounterval) as admission_data')
        )
        ->whereNotNull('flddoa')
        ->whereNotIn('fldadmission', ['Registered','Recorded']);
        
        if($eng_from_date){
			$admission_count=	$admission_count->whereDate('flddoa','>=', $eng_from_date)->whereDate('flddoa','<=', $eng_to_date);
		}
        $admission_count=$admission_count->first();

        $discharge_count=Encounter::select(
            DB::raw('count(fldencounterval) as discharge_data')
        )
        ->whereNotNull('flddod')
        ->whereNotIn('fldadmission', ['Registered','Recorded']);
        if($eng_from_date){
			$discharge_count=	$discharge_count->whereDate('flddod','>=', $eng_from_date)->whereDate('flddod','<=', $eng_to_date);
		}
        $discharge_count=$discharge_count->first();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('consultpatientdata::month-wise-admission-discharge-report.month-wise-admission-discharge-pdf', compact('admission_count','discharge_count','date'));
    }
}
