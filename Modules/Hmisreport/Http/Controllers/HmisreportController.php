<?php

namespace Modules\Hmisreport\Http\Controllers;

use App\Confinement;
use App\DeliveryInfo;
use App\Encounter;
use App\FamilyHealth;
use App\Fiscalyear;
use App\Http\Controllers\Nepali_Calendar;
use App\OtherComplication;
use App\PatBilling;
use App\PatFindings;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HmisreportController extends Controller
{
    public function index()
    {

        $data['fiscals'] = Year::get();
        return view('hmisreport::index', $data);
    }

    public function getLastDate(Request $request)
    {
        $year = (int)"20" . $request->get('year');
        $month = (int)$request->get('month');
        if (!$month) {
            $date = $year . '-03-31';
            return \response(['without_month' => $date]);
        }
        if (!$year || !$month) {
            return \response()->json(['error', 'Please enter year and month']);
        }

        $nep = new Nepali_Calendar();
        $final = $nep->get_month_last_date($year, $month);
        return $final;

    }

    public function generateReport(Request $request)
    {

        ini_set('max_execution_time', 600);
        $date = \App\Utils\Helpers::dateNepToEng($request->report_date)->full_date ?? null;
        $from_date = \App\Utils\Helpers::dateNepToEng($request->report_date)->full_date ?? null;
        $to_date = \App\Utils\Helpers::dateNepToEng($request->to_date)->full_date ?? null;
        if ($date) {

            try {
                $fiscals = explode('/', $request->fiscal_year);
                $data['date'] = $date ?? null;
                $data['fiscal_one'] = $fiscals[0] ?? null;
                $data['fiscal_two'] = $fiscals[1] ?? null;
//                $userDetail = Auth::guard('admin_frontend')->user()->load('organization');
//                $data['userDetail'] = $userDetail ? $userDetail : null;
                $data['familyPlanning'] = $this->familyPlanning($from_date, $to_date);
                $data['inpatientMorbidity'] = $this->inpatientMorbidity($from_date, $to_date);
                //This is for total patients this month
                $data['total_patients_admitted'] = Encounter::whereDate('flddoa', '>=', $from_date)
                    ->whereDate('flddoa', '<=', $to_date)
                    ->distinct()->count();

                //This is for total In-patients this month
                $data['total_inpatients'] = $this->Inpatient($from_date, $to_date) ?? null;

                // Age wise Hospital Services Modified by Rabi
                $data['all_hospital_services'] = $this->ageWiseHospitalServices($from_date, $to_date) ?? null;


                /** Emergency Service*/
                $data['emergency_service'] = $this->emergencyService($from_date, $to_date) ?? null;
                /** Refer */

                $data['refer'] = $this->referals($from_date, $to_date) ?? null;
                $data['inpatient_less_twenty_eight'] = $this->lessThanTwentyEight($from_date, $to_date) ?? null;
                $data['inpatient_twenty_nine_to_years'] = $this->twentyNineToYear($from_date, $to_date) ?? null;
                $data['inpatient_one_to_four_years'] = $this->oneToFourYears($from_date, $to_date) ?? null;
                $data['inpatient_five_to_fourteen_years'] = $this->fiveToFourTeenYears($from_date, $to_date) ?? null;
                $data['inpatient_fifteen_to_nineteen_years'] = $this->fifteenToNinteenYear($from_date, $to_date) ?? null;
                $data['inpatient_twenty_to_twentynine_years'] = $this->twentyToTwentyNine($from_date, $to_date) ?? null;
                $data['inpatient_thirty_to_thirtynine_years'] = $this->thirtyTothirtyNine($from_date, $to_date) ?? null;
                $data['inpatient_fourty_to_fourtynine_years'] = $this->fourtyToFourtyNine($from_date, $to_date) ?? null;
                $data['inpatient_fifty_to_fiftynine_years'] = $this->fiftyToFififtyNine($from_date, $to_date) ?? null;
                $data['inpatient_greater_than_sixty_years'] = $this->inpatientGreaterThanSixty($from_date, $to_date) ?? null;
                $data['death_information'] = $this->deathInformation($from_date, $to_date) ?? null;

//                $data['vaccination'] = $this->vaccination($from_date, $to_date) ?? null;
//                $data['imnc'] = $this->imnc($from_date, $to_date) ?? null;
//                $data['nutrition'] = $this->nutrition($from_date, $to_date) ?? null;
//                $data['population'] = $this->population($from_date, $to_date) ?? null;
                $data['gestation'] = $this->gestationWeek($from_date, $to_date) ?? null;

                $data['outpatient'] = $this->outPateint($from_date, $to_date) ?? null;
                $data['typeofSurgeries'] = $this->typeofSurgeries($from_date, $to_date) ?? null;
//                $data['maternal'] = $this->maternal($from_date, $to_date) ?? null;
                $data['diagnostic'] = $this->diagnostic($from_date, $to_date) ?? null;
                $data['mch'] = $this->mchData($from_date, $to_date) ?? null;
                $data['test'] = $this->laboratoryServices($from_date, $to_date) ?? null;
                return view('hmisreport::report', $data);

//                dd($data);
//                $pdf = \PDF::loadView('hmisreport::report', $data)->setPaper('letter', 'landscape');
//                return $pdf->stream();
//                return  view('hmisreport::report', $data);

            } catch (\Exception $exception) {
                dd($exception);
                return redirect()->route('hmisreport.index')->with('error_message', 'Something Went Wrong!!');
            }
        }
        return redirect()->route('hmisreport.index')->with('error_message', 'Something Went Wrong!!');
    }

    /** BY RABI : AGE WISE HOSPITAL SERVICES */
    private function ageWiseHospitalServices($from_date, $to_date)
    {
        try {

            $hospital_services_response = [
                'new_male_female' => null,
                'total_male_female' => null
            ];

            // \DB::enableQueryLog();

            /** NEW MALE & FEMALE */
            $new_male_female = DB::table('tblencounter')
                ->selectRaw("COUNT(*) AS total,
            CASE WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 0 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 3285) AND tblpatientinfo.fldptsex = 'Male' THEN '0_9_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 3285 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 6935) AND tblpatientinfo.fldptsex = 'Male' THEN '10_19_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 6935 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 21535) AND tblpatientinfo.fldptsex = 'Male' THEN '20_59_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 21535 ) AND tblpatientinfo.fldptsex = 'Male' THEN '60_above_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 0 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 3285) AND tblpatientinfo.fldptsex = 'Female' THEN '0_9_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 3285 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 6935) AND tblpatientinfo.fldptsex = 'Female' THEN '10_19_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 6935 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 21535) AND tblpatientinfo.fldptsex = 'Female' THEN '20_59_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 21535 ) AND tblpatientinfo.fldptsex = 'Female' THEN '60_above_female'
                 ELSE 'Others'
            END AS age_group");
            $new_male_female->join('tblpatientinfo', 'tblencounter.fldpatientval', 'tblpatientinfo.fldpatientval');
            $new_male_female->where('tblencounter.fldregdate', '>=', $from_date . ' 00:00:00');
            $new_male_female->where('tblencounter.fldregdate', '<=', $to_date . ' 23:59:59.99');
            $new_male_female->where('tblencounter.fldvisit', 'NEW');
            $new_male_female->groupBy('age_group');

            // dd(\DB::getQueryLog());

            $new_male_female_response = $new_male_female->get();
            if ($new_male_female_response->count() > 0) {
                $hospital_services_response['new_male_female'] = $new_male_female_response;
            }

            /** TOTAL MALE & FEMALE */
            $total_male_female = DB::table('tblencounter')
                ->selectRaw("COUNT(*) AS total,
             CASE WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 0 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 3285) AND tblpatientinfo.fldptsex = 'Male' THEN '0_9_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 3285 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 6935) AND tblpatientinfo.fldptsex = 'Male' THEN '10_19_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 6935 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 21535) AND tblpatientinfo.fldptsex = 'Male' THEN '20_59_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 21535 ) AND tblpatientinfo.fldptsex = 'Male' THEN '60_above_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 0 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 3285) AND tblpatientinfo.fldptsex = 'Female' THEN '0_9_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 3285 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 6935) AND tblpatientinfo.fldptsex = 'Female' THEN '10_19_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 6935 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 21535) AND tblpatientinfo.fldptsex = 'Female' THEN '20_59_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 21535 ) AND tblpatientinfo.fldptsex = 'Female' THEN '60_above_female'
                 ELSE 'Others'
            END AS age_group");
            $total_male_female->join('tblpatientinfo', 'tblencounter.fldpatientval', 'tblpatientinfo.fldpatientval');
            $total_male_female->where('tblencounter.fldregdate', '>=', $from_date . ' 00:00:00');
            $total_male_female->where('tblencounter.fldregdate', '<=', $to_date . ' 23:59:59.99');
            $total_male_female->groupBy('age_group');
            $total_male_female_response = $total_male_female->get();
            if ($total_male_female_response->count() > 0) {
                $hospital_services_response['total_male_female'] = $total_male_female_response;
            }
            return $hospital_services_response;

        } catch (\Exception $exception) {
            return redirect()->route('hmisreport.index')->with('success_message', 'Something Went Wrong!!');
        }
    }

    /** Function for Refereals */
    private function referals($from_date, $to_date)
    {
        $referals_final = [
            'refer_in_male' => 0,
            'refer_in_female' => 0,
            'refer_out_outpatient_male' => 0,
            'refer_out_outpatient_female' => 0,
            'refer_out_inpatient_male' => 0,
            'refer_out_inpatient_female' => 0,
            'refer_out_emergency_male' => 0,
            'refer_out_emergency_female' => 0
        ];

        /** REFER INS **/
        $refer_ins = DB::table('tblencounter')
            ->selectRaw("COUNT(*) AS total,
            CASE WHEN tblpatientinfo.fldptsex = 'male' THEN 'refer_in_male'
                 WHEN tblpatientinfo.fldptsex = 'female' THEN 'refer_in_female'
                 ELSE 'Others'
            END AS refer_in_type");
        $refer_ins->join('tblpatientinfo', 'tblencounter.fldpatientval',
            'tblpatientinfo.fldpatientval');
        $refer_ins->where('tblencounter.fldregdate', '>=', $from_date . ' 00:00:00');
        $refer_ins->where('tblencounter.fldregdate', '<=', $to_date . ' 23:59:59.99');
        $refer_ins->where('tblencounter.fldreferfrom', "!=", 'NULL');
        $refer_ins->groupBy('refer_in_type');
        $refer_ins_response = $refer_ins->get();

        if ($refer_ins_response->count() > 0) {
            $refer_in_male = $refer_ins_response->where('refer_in_type', 'refer_in_male')->first();
            $refer_in_female = $refer_ins_response->where('refer_in_type', 'refer_in_female')->first();
            $referals_final['refer_in_male'] = $refer_in_male ? $refer_in_male->total : 0;
            $referals_final['refer_in_female'] = $refer_in_female ? $refer_in_female->total : 0;
        }

        /** REFER OUTS : Out Patients & Inpatients */
        $refer_outs = DB::table('tblencounter')
            ->selectRaw("COUNT(*) AS total,
            CASE WHEN tbldepartment.fldcateg = 'Consultation' AND tblpatientinfo.fldptsex = 'Male' THEN 'outpatient_male'
                 WHEN tbldepartment.fldcateg = 'Consultation' AND tblpatientinfo.fldptsex = 'Female' THEN 'outpatient_female'
                 WHEN tbldepartment.fldcateg = 'Patient Ward' AND tblpatientinfo.fldptsex = 'Male' THEN 'inpatient_male'
                 WHEN tbldepartment.fldcateg = 'Patient Ward' AND tblpatientinfo.fldptsex = 'Female' THEN 'inpatient_female'
                 ELSE 'Others'
            END AS patient_type");
        $refer_outs->join('tblpatientinfo', 'tblencounter.fldpatientval',
            'tblpatientinfo.fldpatientval');
        $refer_outs->join('tbldepartment', 'tbldepartment.flddept', 'tblencounter.fldcurrlocat');
        $refer_outs->where('tblencounter.fldregdate', '>=', $from_date . ' 00:00:00');
        $refer_outs->where('tblencounter.fldregdate', '<=', $to_date . ' 23:59:59.99');
        $refer_outs->where('tblencounter.fldreferto', "!=", 'NULL');
        $refer_outs->groupBy('patient_type');
        $refer_outs_response = $refer_outs->get();

        if ($refer_outs_response->count() > 0) {
            $refer_out_outpatient_male = $refer_outs_response->where('patient_type', 'outpatient_male')->first();
            $refer_out_outpatient_female = $refer_outs_response->where('patient_type', 'outpatient_female')->first();
            $refer_out_inpatient_male = $refer_outs_response->where('patient_type', 'inpatient_male')->first();
            $refer_out_inpatient_female = $refer_outs_response->where('patient_type', 'inpatient_female')->first();
            $referals_final['refer_out_outpatient_male'] = $refer_out_outpatient_male ? $refer_out_outpatient_male->total : 0;
            $referals_final['refer_out_outpatient_female'] = $refer_out_outpatient_female ? $refer_out_outpatient_female->total : 0;
            $referals_final['refer_out_inpatient_male'] = $refer_out_inpatient_male ? $refer_out_inpatient_male->total : 0;
            $referals_final['refer_out_inpatient_female'] = $refer_out_inpatient_female ? $refer_out_inpatient_female->total : 0;
        }


        //REFER OUTS : Emergencies
        $refer_out_emergecy = DB::select(DB::raw(" SELECT COUNT(*) AS total,
            CASE WHEN pt.fldptsex = 'Male' THEN 'emergency_male'
                 WHEN pt.fldptsex = 'Female' THEN 'emergency_female'
                 ELSE 'Others'
            END AS patient_sex
   FROM tblencounter e

 JOIN tblpatientinfo pt on (e.fldpatientval = pt.fldpatientval)
 JOIN tbldepartment d on (e.fldcurrlocat = d.flddept)
JOIN tbldepartmentbed db on (e.fldcurrlocat = db.flddept or e.fldcurrlocat = db.fldbed)
 JOIN hmis_mapping hm on (e.fldadmitlocat = hm.service_name)
 WHERE hm.category ='emergency'
 AND e.fldreferto is NOT null
 GROUP BY patient_sex"));

        $refer_out_emergecy = collect($refer_out_emergecy);

        $refer_out_emergency_male = $refer_out_emergecy->where('patient_sex', 'emergency_male')->first() ?? null;
        $refer_out_emergency_female = $refer_out_emergecy->where('patient_sex', 'emergency_female')->first() ?? null;

        $referals_final['refer_out_emergency_male'] = $refer_out_emergency_male ? $refer_out_emergency_male->total : 0;
        $referals_final['refer_out_emergency_female'] = $refer_out_emergency_female ? $refer_out_emergency_female->total : 0;
//        dd($referals_final);


        /** REFER OUTS : Emergencies */
//        $refer_outs_emergency = DB::table('tblencounter')
//            ->selectRaw("COUNT(*) AS total,
//            CASE WHEN tblpatientinfo.fldptsex = 'Male' THEN 'emergency_male'
//                 WHEN tblpatientinfo.fldptsex = 'Female' THEN 'emergency_female'
//                 ELSE 'Others'
//            END AS patient_sex");

//        $refer_outs_emergency->join('tblpatientinfo', 'tblencounter.fldpatientval',
//            'tblpatientinfo.fldpatientval');
//        //tbldepartment joined
//        $refer_outs_emergency->join('tbldepartment', 'tblencounter.fldcurrlocat',
//            'tbldepartment.flddept');
//        //tbldepartmentbed join gareko
//
//        $refer_outs_emergency->join('tbldepartmentbed', function ($join){
//            $join->on('tblencounter.fldcurrlocat','=','tbldepartmentbed.flddept')->orOn('tblencounter.fldcurrlocat','=','tbldepartmentbed.fldbed');
//        });
//        'tblencounter.fldcurrlocat' , 'tbldepartmentbed.flddept',DB::raw('or tblencounter.fldcurrlocat = tbldepartmentbed.fldbed'));

//        $refer_outs_emergency->join('tbldepartmentbed','tblencounter.fldcurrlocat' , 'tbldepartmentbed.flddept',DB::raw('or tblencounter.fldcurrlocat = tbldepartmentbed.fldbed'));

//        $refer_outs_emergency->join('hmis_mapping', 'hmis_mapping.service_name',
//            'tblencounter.fldadmitlocat');
//        $refer_outs_emergency->where('tblencounter.fldregdate', '>=', $from_date);
//        $refer_outs_emergency->where('tblencounter.fldregdate', '<=', $to_date);
//        $refer_outs_emergency->where('hmis_mapping.category', '=', 'emergency');
//        $refer_outs_emergency->where('tblencounter.fldreferto', "!=", 'NULL');
//        $refer_outs_emergency->groupBy('patient_sex');
//        $refer_outs_emergency_response = $refer_outs_emergency->get();

//        if ($refer_outs_emergency_response->count() > 0) {
//            $refer_out_emergency_male = $refer_outs_emergency_response->where('patient_sex', 'emergency_male')->first();
//            $refer_out_emergency_female = $refer_outs_emergency_response->where('patient_sex', 'emergency_female')->first();
//            $referals_final['refer_out_emergency_male'] = $refer_out_emergency_male ? $refer_out_emergency_male->total : 0;
//            $referals_final['refer_out_emergency_female'] = $refer_out_emergency_female ? $refer_out_emergency_female->total : 0;
//        }
//        dd($referals_final);
        return $referals_final;

    }


    /** Function for Emergency Service */

    private function emergencyService($from_date, $to_date)
    {
        /** NEW MALE & FEMALE */


        $emergency_services = [
            'male_female_emergency' => null,
        ];
        $new_male_female = DB::table('tblencounter')
            ->selectRaw("COUNT(*) as total,
            CASE WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 0 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 3285) AND tblpatientinfo.fldptsex = 'Male' THEN '0_9_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 3285 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 6935) AND tblpatientinfo.fldptsex = 'Male' THEN '10_19_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 6935 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 21535) AND tblpatientinfo.fldptsex = 'Male' THEN '20_59_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 21535 ) AND tblpatientinfo.fldptsex = 'Male' THEN '60_above_male'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 0 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 3285) AND tblpatientinfo.fldptsex = 'Female' THEN '0_9_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 3285 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 6935) AND tblpatientinfo.fldptsex = 'Female' THEN '10_19_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 6935 and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) <= 21535) AND tblpatientinfo.fldptsex = 'Female' THEN '20_59_female'
                 WHEN (DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) > 21535 ) AND tblpatientinfo.fldptsex = 'Female' THEN '60_above_female'
                 ELSE 'Others'
            END AS age_group");
        $new_male_female->join('tblpatientinfo', 'tblencounter.fldpatientval', 'tblpatientinfo.fldpatientval');
        $new_male_female->join('hmis_mapping', 'hmis_mapping.service_name', 'tblencounter.fldadmitlocat');
        $new_male_female->where('tblencounter.fldregdate', '>=', $from_date . ' 00:00:00');
        $new_male_female->where('tblencounter.fldregdate', '<=', $to_date . ' 23:59:59.99');
        $new_male_female->where('hmis_mapping.category', 'emergency');



        $new_male_female->groupBy('age_group');

        $emergency_service_response = $new_male_female->get();

        if ($emergency_service_response->count() > 0) {
            $emergency_services['male_female_emergency'] = $emergency_service_response;
        }

        return $emergency_services;

    }

//    private function emergencyService($from_date, $to_date)
//    {
//
//
//
//
//
//
//
////        /** age group 0-9 */
////        $zero_to_nine_total_male = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                             INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Male'
////                                             and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=0
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<3285"));
////
////        $zero_to_nine_total_female = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                             INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Female'
////                                                and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=0
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<3285"));
////
////        /** @var 10-19 Age group */
////
////        $ten_to_nineteen_total_female = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                             INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Female'
////                                                and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=3286
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<6935"));
////
////        $ten_to_nineteen_total_male = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                             INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Male'
////                                                and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=3286
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<6935"));
////
////        /** 20-50 age group */
////
////        $twenty_to_fifty_total_male = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                             INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Male'
////                                                and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=6935
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<21535"));
////
////        $twenty_to_fifty_total_female = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                             INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Female'
////                                                and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=6935
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<21535"));
////        /** Age group greate than 60 */
////
////        $greater_than_sixty_total_male = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                             INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Male'
////                                                and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=21535
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<43800"));
////
////        $greater_than_sixty_total_female = DB::select(DB::raw("select COUNT(tblencounter.fldencounterval)
////                                            as tot from tblencounter
////                                            inner join tblpatientinfo
////                                             on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
////                                              INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
////                                             where tblencounter.fldregdate>='$from_date'
////                                             and tblencounter.fldregdate<='$to_date'
////                                             and tblpatientinfo.fldptsex='Female'
////                                                and hmis_mapping.category='emergency'
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)>=21535
////                                             and DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday)<43800"));
////
////        return [
////            'ten_to_nineteen_total_female' => $ten_to_nineteen_total_female ?? null,
////            'ten_to_nineteen_total_male' => $ten_to_nineteen_total_male ?? null,
////            'twenty_to_fifty_total_male' => $twenty_to_fifty_total_male ?? null,
////            'twenty_to_fifty_total_female' => $twenty_to_fifty_total_female ?? null,
////            'greater_than_sixty_total_male' => $greater_than_sixty_total_male ?? null,
////            'greater_than_sixty_total_female' => $greater_than_sixty_total_female ?? null,
////            'zero_to_nine_total_male' => $zero_to_nine_total_male ?? null,
////            'zero_to_nine_total_female' => $zero_to_nine_total_female ?? null,
////        ];
//
//
//    }

    /** Function for in patient */
    private function Inpatient($from_date, $to_date)
    {
        if (!($from_date && $to_date)) {
            return false;
        }
        $in_patient = Encounter::whereDate('flddoa', '>=', $from_date)
            ->whereDate('flddoa', '<=', $to_date)->distinct()->get();
        if ($in_patient->count() > 0) {
            $sum = 0;
            foreach ($in_patient as $patient) {
                $discharge_date = Carbon::parse($patient->flddod) ?? null;
                $admission_date = Carbon::parse($patient->flddoa) ?? null;
                $days = $discharge_date->diffInDays($admission_date) ?? null;
                $sum = $sum + $days;
            }
            return $sum ?? null;
        }
        return false;
    }

    /** Function for Inpatient outcome  <28*/
    private function lessThanTwentyEight($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                        as cnt from (tblpatientdate
                                                                        inner join tblencounter
                                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo
                                                                        on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                        and tblpatientdate.fldhead='Discharged'
                                                                        and (tblpatientdate.fldcomment='Recovered'
                                                                        or tblpatientdate.fldcomment='Improved')
                                                                        and tblpatientinfo.fldptsex='Male'
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                        as cnt from (tblpatientdate
                                                                        inner join tblencounter
                                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo
                                                                        on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                        and tblpatientdate.fldhead='Discharged'
                                                                        and (tblpatientdate.fldcomment='Recovered'
                                                                        or tblpatientdate.fldcomment='Improved')
                                                                        and tblpatientinfo.fldptsex='Female'
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<28
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome 29 to 1 years*/
    private function twentyNineToYear($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=29
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<365
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome 1 to 4 years*/
    private function oneToFourYears($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>366
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=366
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<1460
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome 5 to 14 years*/
    private function fiveToFourTeenYears($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>1461
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=1461
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<5110
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome 15 to 19 years*/
    private function fifteenToNinteenYear($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>5111
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=5111
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<6935
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }


    /** Function for Inpatient outcome 20 to 29 years*/
    private function twentyToTwentyNine($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>6936
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=6936
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<10585
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome 30 to 39 years*/
    private function thirtyTothirtyNine($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>10586
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=10586
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<14235
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome 40 to 49 years*/
    private function fourtyToFourtyNine($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>14235
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=14235
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<17885
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome 50 to 59 years*/
    private function fiftyToFififtyNine($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>17886
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=17886
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<21535
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Inpatient outcome >60 years*/
    private function inpatientGreaterThanSixty($from_date, $to_date)
    {
        $improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Male'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                 as cnt from (tblpatientdate
                                                 inner join tblencounter
                                                 on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                 inner join tblpatientinfo
                                                 on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                 where tblpatientdate.fldtime>='$from_date'
                                                 and tblpatientdate.fldtime<='$to_date'
                                                 and tblpatientdate.fldhead='Discharged'
                                                 and (tblpatientdate.fldcomment='Recovered'
                                                 or tblpatientdate.fldcomment='Improved')
                                                 and tblpatientinfo.fldptsex='Female'
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                 and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $not_improved_male = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Male'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $not_improved_female = DB::select(DB::raw("select count(tblpatientdate.fldid) as cnt
                                                                        from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                        where tblpatientdate.fldtime>='$from_date'
                                                                        and tblpatientdate.fldtime<='$to_date'
                                                                         and tblpatientdate.fldhead='Discharged'
                                                                         and (tblpatientdate.fldcomment='Unchanged'
                                                                         or tblpatientdate.fldcomment='Worse')
                                                                         and tblpatientinfo.fldptsex='Female'
                                                                         and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $refer_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>21536
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $refer_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='Refer'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));


        $lama_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Male'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $lama_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                as cnt from (tblpatientdate
                                                inner join tblencounter
                                                on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                inner join tblpatientinfo
                                                on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                where tblpatientdate.fldtime>='$from_date'
                                                and tblpatientdate.fldtime<='$to_date'
                                                and tblpatientdate.fldhead='LAMA'
                                                and tblpatientinfo.fldptsex='Female'
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $absconder_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Male'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $absconder_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                    as cnt from (tblpatientdate
                                                    inner join tblencounter
                                                    on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                    inner join tblpatientinfo
                                                    on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                    where tblpatientdate.fldtime>='$from_date'
                                                    and tblpatientdate.fldtime<='$to_date'
                                                    and tblpatientdate.fldhead='Absconder'
                                                    and tblpatientinfo.fldptsex='Female'
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800"));

        $death_less_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Male'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));

        $death_less_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                        as cnt from (tblpatientdate inner join tblencounter
                                                        on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                        inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                        where tblpatientdate.fldtime>='$from_date' and tblpatientdate.fldtime<='$to_date'
                                                        and tblpatientdate.fldhead='Death' and tblpatientinfo.fldptsex='Female'
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                        and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=0
                                                        and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)<2"));


        $death_greater_than_two_days_male = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Male'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));

        $death_greater_than_two_days_female = DB::select(DB::raw("select count(tblpatientdate.fldid)
                                                                    as cnt from (tblpatientdate inner join tblencounter on tblpatientdate.fldencounterval=tblencounter.fldencounterval)
                                                                    inner join tblpatientinfo on tblencounter.fldpatientval=tblpatientinfo.fldpatientval
                                                                    where tblpatientdate.fldtime>='$from_date'
                                                                    and tblpatientdate.fldtime<='$to_date'
                                                                    and tblpatientdate.fldhead='Death'
                                                                    and tblpatientinfo.fldptsex='Female'
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=21536
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<43800
                                                                    and DATEDIFF(tblpatientdate.fldtime, tblencounter.fldregdate)>=2"));
        return [
            'improved_male' => $improved_male ?? null,
            'improved_female' => $improved_female ?? null,
            'not_improved_male' => $not_improved_male ?? null,
            'not_improved_female' => $not_improved_female ?? null,
            'refer_male' => $refer_male ?? null,
            'refer_female' => $refer_female ?? null,
            'lama_male' => $lama_male ?? null,
            'lama_female' => $lama_female ?? null,
            'absconder_male' => $absconder_male ?? null,
            'absconder_female' => $absconder_female ?? null,
            'death_less_two_male' => $death_less_than_two_days_male ?? null,
            'death_less_two_female' => $death_less_than_two_days_female ?? null,
            'death_greater_two_male' => $death_greater_than_two_days_male ?? null,
            'death_greater_two_female' => $death_greater_than_two_days_female ?? null,
        ];
    }

    /** Function for Death Information */
    private function deathInformation($from_date, $to_date)
    {

        $early_neonatal_female = DB::select(DB::raw("select count(fldencounterval)
                                                            as col from tblpatientdate where fldtime>='$from_date'
                                                            and fldtime<='$to_date' and fldhead='Death'
                                                            and fldencounterval
                                                            in(select fldencounterval from tblencounter where fldpatientval
                                                            in(select fldpatientval from tblpatientinfo where fldptsex like 'Female'
                                                            and fldptbirday like '%' and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                            and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<7))"));

        $early_neonatal_male = DB::select(DB::raw("select count(fldencounterval)
                                                            as col from tblpatientdate where fldtime>='$from_date'
                                                            and fldtime<='$to_date' and fldhead='Death'
                                                            and fldencounterval
                                                            in(select fldencounterval from tblencounter where fldpatientval
                                                            in(select fldpatientval from tblpatientinfo where fldptsex like 'Male'
                                                            and fldptbirday like '%' and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=0
                                                            and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<7))"));

        $late_neonatal_female = DB::select(DB::raw("select count(fldencounterval)
                                                            as col from tblpatientdate where fldtime>='$from_date'
                                                            and fldtime<='$to_date' and fldhead='Death'
                                                            and fldencounterval
                                                            in(select fldencounterval from tblencounter where fldpatientval
                                                            in(select fldpatientval from tblpatientinfo where fldptsex like 'Female'
                                                            and fldptbirday like '%' and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=7
                                                            and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<27))"));

        $late_neonatal_male = DB::select(DB::raw("select count(fldencounterval)
                                                            as col from tblpatientdate where fldtime>='$from_date'
                                                            and fldtime<='$to_date' and fldhead='Death'
                                                            and fldencounterval
                                                            in(select fldencounterval from tblencounter where fldpatientval
                                                            in(select fldpatientval from tblpatientinfo where fldptsex like 'Male'
                                                            and fldptbirday like '%' and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)>=7
                                                            and DATEDIFF(tblpatientdate.fldtime, tblpatientinfo.fldptbirday)<27))"));

        $maternal_all = DB::select(DB::raw("select count(fldencounterval)
                                                    as col from tblpatientdate
                                                    where fldtime>='$from_date'
                                                    and fldtime<='$to_date'
                                                    and fldhead='Death'
                                                     and fldencounterval in(select fldencounterval from tblconfinement)"));

        $post_operative_female = DB::select(DB::raw("select count(fldencounterval)
                                                        as col from tblpatientdate where fldtime>='$from_date'
                                                        and fldtime<='$to_date' and fldhead='Death'
                                                        and fldencounterval in(select fldencounterval
                                                        from tblpatgeneral where fldreportquali='Done')
                                                        and fldencounterval in(select fldencounterval from tblencounter
                                                        where fldpatientval in(select fldpatientval
                                                        from tblpatientinfo where fldptsex like 'Female'))"));

        $post_operative_male = DB::select(DB::raw("select count(fldencounterval)
                                                        as col from tblpatientdate where fldtime>='$from_date'
                                                        and fldtime<='$to_date' and fldhead='Death'
                                                        and fldencounterval in(select fldencounterval
                                                        from tblpatgeneral where fldreportquali='Done')
                                                        and fldencounterval in(select fldencounterval from tblencounter
                                                        where fldpatientval in(select fldpatientval
                                                        from tblpatientinfo where fldptsex like 'Male'))"));

        $others_female = DB::select(DB::raw("select count(fldencounterval) as
                                                    col from tblpatientdate where fldtime>='$from_date'
                                                    and fldtime<='$to_date' and fldhead='Death'
                                                    and fldencounterval in(select fldencounterval from tblencounter
                                                    where fldpatientval in(select fldpatientval
                                                    from tblpatientinfo where fldptsex like 'Female'))"));

        $others_male = DB::select(DB::raw("select count(fldencounterval) as
                                                    col from tblpatientdate where fldtime>='$from_date'
                                                    and fldtime<='$to_date' and fldhead='Death'
                                                    and fldencounterval in(select fldencounterval from tblencounter
                                                    where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Male'))"));

        $brought_dead_female = DB::select(DB::raw("select count(fldencounterval) as
                                                    col from tblpatientdate where fldtime>='$from_date'
                                                    and fldtime<='$to_date'
                                                    and fldhead='Death'
                                                    and fldhead='Brought Dead'
                                                    and fldencounterval in(select fldencounterval from tblencounter
                                                    where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Female'))"));

        $brought_dead_male = DB::select(DB::raw("select count(fldencounterval) as
                                                    col from tblpatientdate where fldtime>='$from_date'
                                                    and fldtime<='$to_date' and fldhead='Death'
                                                    and fldhead='Brought Dead'
                                                    and fldencounterval in(select fldencounterval from tblencounter
                                                    where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Male'))"));

        $postmortem_female = DB::select(DB::raw("select count(fldencounterval) as
                                                    col from tblpatientdate where fldtime>='$from_date'
                                                    and fldtime<='$to_date'
                                                    and fldhead='Death'
                                                    and fldhead='Postmortem Done'
                                                    and fldencounterval in(select fldencounterval from tblencounter
                                                    where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Female'))"));

        $postmortem_male = DB::select(DB::raw("select count(fldencounterval) as
                                                    col from tblpatientdate where fldtime>='$from_date'
                                                    and fldtime<='$to_date' and fldhead='Death'
                                                    and fldhead='Postmortem Done'
                                                    and fldencounterval in(select fldencounterval from tblencounter
                                                    where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Male'))"));


        return [
            'early_neonatal_female' => $early_neonatal_female ?? null,
            'early_neonatal_male' => $early_neonatal_male ?? null,
            'late_neonatal_female' => $late_neonatal_female ?? null,
            'late_neonatal_male' => $late_neonatal_male ?? null,
            'maternal_all' => $maternal_all ?? null,
            'post_operative_female' => $post_operative_female ?? null,
            'post_operative_male' => $post_operative_male ?? null,
            'others_female' => $others_female ?? null,
            'others_male' => $others_male ?? null,
            'brought_dead_male' => $brought_dead_male ?? null,
            'brought_dead_female' => $brought_dead_female ?? null,
            'postmortem_female' => $postmortem_female ?? null,
            'postmortem_male' => $postmortem_male ?? null,

        ];
    }

    /** function for plotting fourth page not needed for now*/
    private function vaccination($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }

        $vacctination = Vaccination::whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->get();
        /** BCG count */
        $bcgCount = $vacctination->where('vaccination_type', 'bcg')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();
        /** Dpt counts*/
        $dptFirstCount = $vacctination->where('vaccination_type', '=', 'dpt')
            ->where('visit', '=', 'first')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $dptSecondCount = $vacctination->where('vaccination_type', '=', 'dpt')
            ->where('visit', '=', 'second')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $dptThirdCount = $vacctination->where('vaccination_type', '=', 'dpt')
            ->where('visit', '=', 'third')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();
        /** Polio Counts*/
        $polioFirstCount = $vacctination->where('vaccination_type', '=', 'polio')
            ->where('visit', '=', 'first')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $polioSecondCount = $vacctination->where('vaccination_type', '=', 'polio')
            ->where('visit', '=', 'second')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $polioThirdCount = $vacctination->where('vaccination_type', '=', 'polio')
            ->where('visit', '=', 'third')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();
        /** PCV */

        $pcvFirstCount = $vacctination->where('vaccination_type', '=', 'pcv')
            ->where('visit', '=', 'first')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $pcvSecondCount = $vacctination->where('vaccination_type', '=', 'pcv')
            ->where('visit', '=', 'second')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $pcvThirdCount = $vacctination->where('vaccination_type', '=', 'pcv')
            ->where('visit', '=', 'third')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();
        /** Rota */

        $rotaFirstCount = $vacctination->where('vaccination_type', '=', 'rota')
            ->where('visit', '=', 'first')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $rotaSecondCount = $vacctination->where('vaccination_type', '=', 'rota')
            ->where('visit', '=', 'second')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        /** FIPV */

        $fipvFirstCount = $vacctination->where('vaccination_type', '=', 'fipv')
            ->where('visit', '=', 'first')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $fipvSecondCount = $vacctination->where('vaccination_type', '=', 'fipv')
            ->where('visit', '=', 'second')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();
        /** Dadura */

        $daduraFirstCount = $vacctination->where('vaccination_type', '=', 'dadura')
            ->where('visit', '=', 'first')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $daduraSecondCount = $vacctination->where('vaccination_type', '=', 'dadura')
            ->where('visit', '=', 'second')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();


        /** je */

        $jeFirstCount = $vacctination->where('vaccination_type', '=', 'je')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        /** TD */

        $tdFirstCount = $vacctination->where('vaccination_type', '=', 'td')
            ->where('visit', '=', 'first')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $tdSecondCount = $vacctination->where('vaccination_type', '=', 'td')
            ->where('visit', '=', 'second')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        $tdThirdCount = $vacctination->where('vaccination_type', '=', 'td')
            ->where('visit', '=', 'second_plus')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        /** Dpt  more than year*/

        $dptAndPolioFirstCount = $vacctination->where('vaccination_type', '=', 'dpt_and_polio_one_year')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->count();

        /** Dose QUery */

        $bcgDoseTaken = $vacctination->where('vaccination_type', '=', 'bcg')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $bcgDoseGiven = $vacctination->where('vaccination_type', '=', 'bcg')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $dptDoseTaken = $vacctination->where('vaccination_type', '=', 'dpt')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $dptDoseGiven = $vacctination->where('vaccination_type', '=', 'dpt')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $polioDoseTaken = $vacctination->where('vaccination_type', '=', 'polio')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $polioDoseGiven = $vacctination->where('vaccination_type', '=', 'polio')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $pcvDoseTaken = $vacctination->where('vaccination_type', '=', 'pcv')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');


        $pcvDoseGiven = $vacctination->where('vaccination_type', '=', 'pcv')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $rotaDoseTaken = $vacctination->where('vaccination_type', '=', 'rota')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $rotaDoseGiven = $vacctination->where('vaccination_type', '=', 'rota')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $fipvDoseTaken = $vacctination->where('vaccination_type', '=', 'fipv')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $fipvDoseGiven = $vacctination->where('vaccination_type', '=', 'fipv')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $daduraDoseTaken = $vacctination->where('vaccination_type', '=', 'dadura')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $daduraDoseGiven = $vacctination->where('vaccination_type', '=', 'dadura')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $jeDoseTaken = $vacctination->where('vaccination_type', '=', 'je')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $jeDoseGiven = $vacctination->where('vaccination_type', '=', 'je')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        $tdDoseTaken = $vacctination->where('vaccination_type', '=', 'td')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_received');

        $tdDoseGiven = $vacctination->where('vaccination_type', '=', 'td')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->sum('total_dose_spent');

        /** Aefvi cases */
        $bcgAefi = $vacctination->where('vaccination_type', '=', 'bcg')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $dptAefi = $vacctination->where('vaccination_type', '=', 'dpt')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $polioAefi = $vacctination->where('vaccination_type', '=', 'polio')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $pcvAefi = $vacctination->where('vaccination_type', '=', 'pcv')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $rotaAefi = $vacctination->where('vaccination_type', '=', 'rota')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $fipvAefi = $vacctination->where('vaccination_type', '=', 'fipv')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $daduraAefi = $vacctination->where('vaccination_type', '=', 'dadura')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $jeAefi = $vacctination->where('vaccination_type', '=', 'je')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        $tdAefi = $vacctination->where('vaccination_type', '=', 'td')
//            ->where('created_at', '>=', $from_date)
//            ->where('created_at', '<=', $to_date)
            ->where('aefi', '=', 'yes')
            ->count();

        return [
            'bcgCount' => $bcgCount ?? null,
            'bcgAefi' => $bcgAefi ?? null,
            'bcgDoseGiven' => $bcgDoseGiven ?? null,
            'bcgDoseTaken' => $bcgDoseTaken ?? null,

            'dptFirstCount' => $dptFirstCount ?? null,
            'dptSecondCount' => $dptSecondCount ?? null,
            'dptThirdCount' => $dptThirdCount ?? null,
            'dptAefi' => $dptAefi ?? null,
            'dptDoseGiven' => $dptDoseGiven ?? null,
            'dptDoseTaken' => $dptDoseTaken ?? null,

            'polioFirstCount' => $polioFirstCount ?? null,
            'polioSecondCount' => $polioSecondCount ?? null,
            'polioThirdCount' => $polioThirdCount ?? null,
            'polioAefi' => $polioAefi ?? null,
            'polioDoseGiven' => $polioDoseGiven ?? null,
            'polioDoseTaken' => $polioDoseTaken ?? null,

            'pcvFirstCount' => $pcvFirstCount ?? null,
            'pcvSecondCount' => $pcvSecondCount ?? null,
            'pcvThirdCount' => $pcvThirdCount ?? null,
            'pcvAefi' => $pcvAefi ?? null,
            'pcvDoseGiven' => $pcvDoseGiven ?? null,
            'pcvDoseTaken' => $pcvDoseTaken ?? null,

            'rotaFirstCount' => $rotaFirstCount ?? null,
            'rotaSecondCount' => $rotaSecondCount ?? null,
            'rotaAefi' => $rotaAefi ?? null,
            'rotaDoseGiven' => $rotaDoseGiven ?? null,
            'rotaDoseTaken' => $rotaDoseTaken ?? null,

            'fipvFirstCount' => $fipvFirstCount ?? null,
            'fipvSecondCount' => $fipvSecondCount ?? null,
            'fipvAefi' => $fipvAefi ?? null,
            'fipvDoseGiven' => $fipvDoseGiven ?? null,
            'fipvDoseTaken' => $fipvDoseTaken ?? null,

            'daduraFirstCount' => $daduraFirstCount ?? null,
            'daduraSecondCount' => $daduraSecondCount ?? null,
            'daduraAefi' => $daduraAefi ?? null,
            'daduraDoseGiven' => $daduraDoseGiven ?? null,
            'daduraDoseTaken' => $daduraDoseTaken ?? null,

            'jeCount' => $jeFirstCount ?? null,
            'jeAefi' => $jeAefi ?? null,
            'jeDoseGiven' => $jeDoseGiven ?? null,
            'jeDoseTaken' => $jeDoseTaken ?? null,

            'dptAndPolio' => $dptAndPolioFirstCount ?? null,

            'tdFirstCount' => $tdFirstCount ?? null,
            'tdSecondCount' => $tdSecondCount ?? null,
            'tdThirdCount' => $tdThirdCount ?? null,
            'tdAefi' => $tdAefi ?? null,
            'tdDoseTaken' => $tdDoseTaken ?? null,
            'tdDoseGiven' => $tdDoseGiven ?? null,
        ];


    }

    private function imnc($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }
        $imnc = Tblimnci::whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->get();

        /**  Health Post */
        $total_patientHealth = $imnc->where('greater_than_two_classification', '!=', null)->where('greater_than_two_place', '=', 'health_post')->count();
        $noPnemoniaHealth = $imnc->where('greater_than_two_classification', '=', 'no_pneumonia')->where('greater_than_two_place', '=', 'health_post')->count();
        $pnemoniaHealth = $imnc->where('greater_than_two_classification', '=', 'pneumonia')->where('greater_than_two_place', '=', 'health_post')->count();
        $hardPnemoniaHealth = $imnc->where('greater_than_two_classification', '=', 'hard_pneumonia')->where('greater_than_two_place', '=', 'health_post')->count();


        $dehydrationHealth = $imnc->where('greater_than_two_classification', '=', 'dehydration')->where('greater_than_two_place', '=', 'health_post')->count();
        $some_dehydrationHealth = $imnc->where('greater_than_two_classification', '=', 'some_dehydration')->where('greater_than_two_place', '=', 'health_post')->count();
        $extreme_dehydrationHealth = $imnc->where('greater_than_two_classification', '=', 'extreme_dehydration')->where('greater_than_two_place', '=', 'health_post')->count();

        $long_diarrheaHealth = $imnc->where('greater_than_two_classification', '=', 'long_diarrhea')->where('greater_than_two_place', '=', 'health_post')->count();
        $bloodHealth = $imnc->where('greater_than_two_classification', '=', 'blood')->where('greater_than_two_place', '=', 'health_post')->count();

        $malariaHealth = $imnc->where('greater_than_two_classification', '=', 'malaria')->where('greater_than_two_place', '=', 'health_post')->count();
        $no_malariaHealth = $imnc->where('greater_than_two_classification', '=', 'no_malaria')->where('greater_than_two_place', '=', 'health_post')->count();

        $auloHealth = $imnc->where('greater_than_two_classification', '=', 'aulo')->where('greater_than_two_place', '=', 'health_post')->count();
        $daduraHealth = $imnc->where('greater_than_two_classification', '=', 'dadura')->where('greater_than_two_place', '=', 'health_post')->count();

        $ear_problemHealth = $imnc->where('greater_than_two_classification', '=', 'ear_problem')->where('greater_than_two_place', '=', 'health_post')->count();
        $feverHealth = $imnc->where('greater_than_two_classification', '=', 'fever')->where('greater_than_two_place', '=', 'health_post')->count();
        $malnutritionHealth = $imnc->where('greater_than_two_classification', '=', 'malnutrition')->where('greater_than_two_place', '=', 'health_post')->count();
        $anemiaHealth = $imnc->where('greater_than_two_classification', '=', 'anemia')->where('greater_than_two_place', '=', 'health_post')->count();
        $otherHealth = $imnc->where('greater_than_two_classification', '=', 'other')->where('greater_than_two_place', '=', 'health_post')->count();

        $amoxicillinHealth = $imnc->where('greater_than_two_treatment', '=', 'amoxicillin')->where('greater_than_two_place', '=', 'health_post')->count();
        $kotrimHealth = $imnc->where('greater_than_two_treatment', '=', 'kotrim_pi')->where('greater_than_two_place', '=', 'health_post')->count();
        $antibioticHealth = $imnc->where('greater_than_two_treatment', '=', 'other_antibiotic')->where('greater_than_two_place', '=', 'health_post')->count();
        $orsHealth = $imnc->where('greater_than_two_treatment', '=', 'ors')->where('greater_than_two_place', '=', 'health_post')->count();
        $iv_fluidHealth = $imnc->where('greater_than_two_treatment', '=', 'iv_fluid')->where('greater_than_two_place', '=', 'health_post')->count();
        $juka_medicineHealth = $imnc->where('greater_than_two_treatment', '=', 'juka_medicine')->where('greater_than_two_place', '=', 'health_post')->count();
        $vitamin_aHealth = $imnc->where('greater_than_two_treatment', '=', 'vitamin_a')->where('greater_than_two_place', '=', 'health_post')->count();

        $breathingHealth = $imnc->where('greater_than_two_refer', '=', 'breathing')->where('greater_than_two_place', '=', 'health_post')->count();
        $diarrheaHealth = $imnc->where('greater_than_two_refer', '=', 'diarrhea')->where('greater_than_two_place', '=', 'health_post')->count();
        $otherReferHealth = $imnc->where('greater_than_two_refer', '=', 'other')->where('greater_than_two_place', '=', 'health_post')->count();

        $followUpHealth = $imnc->where('greater_than_two_follow_up', '=', 'yes')->where('greater_than_two_place', '=', 'health_post')->count();

        $deathbreathingHealth = $imnc->where('greater_than_two_death', '=', 'breathing')->where('greater_than_two_place', '=', 'health_post')->count();
        $deathdiarrheaHealth = $imnc->where('greater_than_two_death', '=', 'diarrhea')->where('greater_than_two_place', '=', 'health_post')->count();
        $deathotherHealth = $imnc->where('greater_than_two_death', '=', 'other')->where('greater_than_two_place', '=', 'health_post')->count();

        /** CLinic  */

        $total_patientClinic = $imnc->where('greater_than_two_classification', '!=', null)->where('greater_than_two_place', '=', 'clinic')->count();
        $noPnemoniaClinic = $imnc->where('greater_than_two_classification', '=', 'no_pneumonia')->where('greater_than_two_place', '=', 'clinic')->count();
        $pnemoniaClinic = $imnc->where('greater_than_two_classification', '=', 'pneumonia')->where('greater_than_two_place', '=', 'clinic')->count();
        $hardPnemoniaClinic = $imnc->where('greater_than_two_classification', '=', 'hard_pneumonia')->where('greater_than_two_place', '=', 'clinic')->count();


        $dehydrationClinic = $imnc->where('greater_than_two_classification', '=', 'dehydration')->where('greater_than_two_place', '=', 'clinic')->count();
        $some_dehydrationClinic = $imnc->where('greater_than_two_classification', '=', 'some_dehydration')->where('greater_than_two_place', '=', 'clinic')->count();
        $extreme_dehydrationClinic = $imnc->where('greater_than_two_classification', '=', 'extreme_dehydration')->where('greater_than_two_place', '=', 'clinic')->count();

        $long_diarrheaClinic = $imnc->where('greater_than_two_classification', '=', 'long_diarrhea')->where('greater_than_two_place', '=', 'clinic')->count();
        $bloodClinic = $imnc->where('greater_than_two_classification', '=', 'blood')->where('greater_than_two_place', '=', 'clinic')->count();

        $malariaClinic = $imnc->where('greater_than_two_classification', '=', 'malaria')->where('greater_than_two_place', '=', 'clinic')->count();
        $no_malariaClinic = $imnc->where('greater_than_two_classification', '=', 'no_malaria')->where('greater_than_two_place', '=', 'clinic')->count();

        $auloClinic = $imnc->where('greater_than_two_classification', '=', 'aulo')->where('greater_than_two_place', '=', 'clinic')->count();
        $daduraClinic = $imnc->where('greater_than_two_classification', '=', 'dadura')->where('greater_than_two_place', '=', 'clinic')->count();

        $ear_problemClinic = $imnc->where('greater_than_two_classification', '=', 'ear_problem')->where('greater_than_two_place', '=', 'clinic')->count();
        $feverClinic = $imnc->where('greater_than_two_classification', '=', 'fever')->where('greater_than_two_place', '=', 'clinic')->count();
        $malnutritionClinic = $imnc->where('greater_than_two_classification', '=', 'malnutrition')->where('greater_than_two_place', '=', 'clinic')->count();
        $anemiaClinic = $imnc->where('greater_than_two_classification', '=', 'anemia')->where('greater_than_two_place', '=', 'clinic')->count();
        $otherClinic = $imnc->where('greater_than_two_classification', '=', 'other')->where('greater_than_two_place', '=', 'clinic')->count();

        $amoxicillinClinic = $imnc->where('greater_than_two_treatment', '=', 'amoxicillin')->where('greater_than_two_place', '=', 'clinic')->count();
        $kotrimClinic = $imnc->where('greater_than_two_treatment', '=', 'kotrim_pi')->where('greater_than_two_place', '=', 'clinic')->count();
        $antibioticClinic = $imnc->where('greater_than_two_treatment', '=', 'other_antibiotic')->where('greater_than_two_place', '=', 'clinic')->count();
        $orsClinic = $imnc->where('greater_than_two_treatment', '=', 'ors')->where('greater_than_two_place', '=', 'clinic')->count();
        $iv_fluidClinic = $imnc->where('greater_than_two_treatment', '=', 'iv_fluid')->where('greater_than_two_place', '=', 'clinic')->count();
        $juka_medicineClinic = $imnc->where('greater_than_two_treatment', '=', 'juka_medicine')->where('greater_than_two_place', '=', 'clinic')->count();
        $vitamin_aClinic = $imnc->where('greater_than_two_treatment', '=', 'vitamin_a')->where('greater_than_two_place', '=', 'clinic')->count();

        $breathingClinic = $imnc->where('greater_than_two_refer', '=', 'breathing')->where('greater_than_two_place', '=', 'clinic')->count();
        $diarrheaClinic = $imnc->where('greater_than_two_refer', '=', 'diarrhea')->where('greater_than_two_place', '=', 'clinic')->count();
        $otherReferClinic = $imnc->where('greater_than_two_refer', '=', 'other')->where('greater_than_two_place', '=', 'clinic')->count();

        $followUpClinic = $imnc->where('greater_than_two_follow_up', '=', 'yes')->where('greater_than_two_place', '=', 'clinic')->count();

        $deathbreathingClinic = $imnc->where('greater_than_two_death', '=', 'breathing')->where('greater_than_two_place', '=', 'clinic')->count();
        $deathdiarrheaClinic = $imnc->where('greater_than_two_death', '=', 'diarrhea')->where('greater_than_two_place', '=', 'clinic')->count();
        $deathotherClinic = $imnc->where('greater_than_two_death', '=', 'other')->where('greater_than_two_place', '=', 'clinic')->count();


        return [
            'totalPatientHealth' => $total_patientHealth ?? null,
            'noPnemoniaHealth' => $noPnemoniaHealth ?? null,
            'pnemoniaHealth' => $pnemoniaHealth ?? null,
            'hardPnemoniaHealth' => $hardPnemoniaHealth ?? null,
            'dehydrationHealth' => $dehydrationHealth ?? null,
            'some_dehydrationHealth' => $some_dehydrationHealth ?? null,
            'extreme_dehydrationHealth' => $extreme_dehydrationHealth ?? null,
            'long_diarrheaHealth' => $long_diarrheaHealth ?? null,
            'bloodHealth' => $bloodHealth ?? null,
            'malariaHealth' => $malariaHealth ?? null,
            'no_malariaHealth' => $no_malariaHealth ?? null,
            'auloHealth' => $auloHealth ?? null,
            'daduraHealth' => $daduraHealth ?? null,
            'ear_problemHealth' => $ear_problemHealth ?? null,
            'feverHealth' => $feverHealth ?? null,
            'malnutritionHealth' => $malnutritionHealth ?? null,
            'anemiaHealth' => $anemiaHealth ?? null,
            'otherHealth' => $otherHealth ?? null,
            'amoxicillinHealth' => $amoxicillinHealth ?? null,
            'kotrimHealth' => $kotrimHealth ?? null,
            'antibioticHealth' => $antibioticHealth ?? null,
            'orsHealth' => $orsHealth ?? null,
            'iv_fluidHealth' => $iv_fluidHealth ?? null,
            'juka_medicineHealth' => $juka_medicineHealth ?? null,
            'vitamin_aHealth' => $vitamin_aHealth ?? null,
            'breathingHealth' => $breathingHealth ?? null,
            'diarrheaHealth' => $diarrheaHealth ?? null,
            'otherReferHealth' => $otherReferHealth ?? null,
            'followUpHealth' => $followUpHealth ?? null,
            'deathbreathingHealth' => $deathbreathingHealth ?? null,
            'deathdiarrheaHealth' => $deathdiarrheaHealth ?? null,
            'deathotherHealth' => $deathotherHealth ?? null,

            /** Clinic */
            'totalPatientClinic' => $total_patientClinic ?? null,
            'noPnemoniaClinic' => $noPnemoniaClinic ?? null,
            'pnemoniaClinic' => $pnemoniaClinic ?? null,
            'hardPnemoniaClinic' => $hardPnemoniaClinic ?? null,
            'dehydrationClinic' => $dehydrationClinic ?? null,
            'some_dehydrationClinic' => $some_dehydrationClinic ?? null,
            'extreme_dehydrationClinic' => $extreme_dehydrationClinic ?? null,
            'long_diarrheaClinic' => $long_diarrheaClinic ?? null,
            'bloodClinic' => $bloodClinic ?? null,
            'malariaClinic' => $malariaClinic ?? null,
            'no_malariaClinic' => $no_malariaClinic ?? null,
            'auloClinic' => $auloClinic ?? null,
            'daduraClinic' => $daduraClinic ?? null,
            'ear_problemClinic' => $ear_problemClinic ?? null,
            'feverClinic' => $feverClinic ?? null,
            'malnutritionClinic' => $malnutritionClinic ?? null,
            'anemiaClinic' => $anemiaClinic ?? null,
            'otherClinic' => $otherClinic ?? null,
            'amoxicillinClinic' => $amoxicillinClinic ?? null,
            'kotrimClinic' => $kotrimClinic ?? null,
            'antibioticClinic' => $antibioticClinic ?? null,
            'orsClinic' => $orsClinic ?? null,
            'iv_fluidClinic' => $iv_fluidClinic ?? null,
            'juka_medicineClinic' => $juka_medicineClinic ?? null,
            'vitamin_aClinic' => $vitamin_aClinic ?? null,
            'breathingClinic' => $breathingClinic ?? null,
            'diarrheaClinic' => $diarrheaClinic ?? null,
            'otherReferClinic' => $otherReferClinic ?? null,
            'followUpClinic' => $followUpClinic ?? null,
            'deathbreathingClinic' => $deathbreathingClinic ?? null,
            'deathdiarrheaClinic' => $deathdiarrheaClinic ?? null,
            'deathotherClinic' => $deathotherClinic ?? null,
        ];

    }

    private function inmcLessthanTwo($from_date, $to_date)
    {

        $imnc = Tblimnci::whereBetween('created_at', [Carbon::parse($from_date)->subDays(27), Carbon::parse($to_date)->subDays(27)])->get();

//        dd($imnc);

//        $test =  $imnc->where('created_at', '<=', Carbon::parse($from_date)->subDays(1))->get();
//           $test = $imnc->whereDate('created_at', '>=')
    }

    private function nutrition($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }

        $nutrition = Tblnutrition::where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->get();
        $firsVisitNormalZeroToEleven = $nutrition->where('visit', 'first_time')
            ->where('month', 'zero_to_eleven')
            ->where('status', 'normal')
            ->count();
        $firsVisitRiskZeroToEleven = $nutrition->where('visit', 'first_time')
            ->where('month', 'zero_to_eleven')
            ->where('status', 'risky')
            ->count();

        $firsVisitEtxemeRiskZeroToEleven = $nutrition->where('visit', 'first_time')
            ->where('month', 'zero_to_eleven')
            ->where('status', 'extreme_risk')
            ->count();

        $firsVisitNormalTwelve = $nutrition->where('visit', 'first_time')
            ->where('month', 'twelve')
            ->where('status', 'normal')
            ->count();
        $firsVisitRiskTwelve = $nutrition->where('visit', 'first_time')
            ->where('month', 'twelve')
            ->where('status', 'risky')
            ->count();

        $firsVisitEtxemeRiskTwelve = $nutrition->where('visit', 'second')
            ->where('month', 'twelve')
            ->where('status', 'extreme_risk')
            ->count();

        /** Second Visit */
        $secondVisitNormalZeroToEleven = $nutrition->where('visit', 'second')
            ->where('month', 'zero_to_eleven')
            ->where('status', 'normal')
            ->count();
        $secondVisitRiskZeroToEleven = $nutrition->where('visit', 'second')
            ->where('month', 'zero_to_eleven')
            ->where('status', 'risky')
            ->count();

        $secondVisitEtxemeRiskZeroToEleven = $nutrition->where('visit', 'second')
            ->where('month', 'zero_to_eleven')
            ->where('status', 'extreme_risk')
            ->count();

        $secondVisitNormalTwelve = $nutrition->where('visit', 'second')
            ->where('month', 'twelve')
            ->where('status', 'normal')
            ->count();
        $secondVisitRiskTwelve = $nutrition->where('visit', 'second')
            ->where('month', 'twelve')
            ->where('status', 'risky')
            ->count();

        $secondVisitEtxemeRiskTwelve = $nutrition->where('visit', 'second')
            ->where('month', 'twelve')
            ->where('status', 'extreme_risk')
            ->count();

        $firstTimeIron = $nutrition->where('first_time_iron', 'yes')->count();
        $onSevenTimeIron = $nutrition->where('one_seven_iron', 'yes')->count();
        $jukaMedicine = $nutrition->where('juka_medicine', 'yes')->count();
        $fourtyFiveIron = $nutrition->where('fourty_five_iron', 'yes')->count();
        $vitaminA = $nutrition->where('vitamin_a', 'yes')->count();
        $vitaminASixToEleven = $nutrition->where('vitamin_a_six_to_eleven', 'yes')->count();
        $jukaMedicineFive = $nutrition->where('juka_medicine_five_years', 'yes')->count();
        $vitaminATweleve = $nutrition->where('vitamin_a_twelve_to_five', 'yes')->count();
        $male = $nutrition->where('male', 'yes')->count();
        $female = $nutrition->where('female', 'yes')->count();


        return [
            'firstNormalZero' => $firsVisitNormalZeroToEleven ?? null,
            'firstRiskZero' => $firsVisitRiskZeroToEleven ?? null,
            'firstExtremeRiskZero' => $firsVisitEtxemeRiskZeroToEleven ?? null,
            'firstNormalTweleve' => $firsVisitNormalTwelve ?? null,
            'firstRiskTwelve' => $firsVisitRiskTwelve ?? null,
            'firstExtremeRiskTwelve' => $firsVisitEtxemeRiskTwelve ?? null,

            'secondNormalZero' => $secondVisitNormalZeroToEleven ?? null,
            'secondRiskZero' => $secondVisitRiskZeroToEleven ?? null,
            'secondExtremeRiskZero' => $secondVisitEtxemeRiskZeroToEleven ?? null,
            'secondNormalTweleve' => $secondVisitNormalTwelve ?? null,
            'secondRiskTwelve' => $secondVisitRiskTwelve ?? null,
            'secondExtremeRiskTwelve' => $secondVisitEtxemeRiskTwelve ?? null,

            'iron_first' => $firstTimeIron ?? null,
            'onSevenTimeIron' => $onSevenTimeIron ?? null,
            'jukaMedicine' => $jukaMedicine ?? null,
            'jukaMedicineFive' => $jukaMedicineFive ?? null,
            'fourtyFiveIron' => $fourtyFiveIron ?? null,
            'vitaminA' => $vitaminA ?? null,
            'vitaminASixToEleven' => $vitaminASixToEleven ?? null,
            'vitaminATweleve' => $vitaminATweleve ?? null,
            'male' => $male ?? null,
            'female' => $female ?? null,
        ];


    }

    /** End Fucntion for plotting fourth page */

    /** Fifth Page Population section */

    private function population($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }

        $population = Tblpopulation::where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->get();
        $appliedSchool = $population->sum('applied_school');
        $report_school = $population->sum('report_school');
        $supervised_school = $population->sum('supervised_school');
        $operation_school = $population->sum('operation_school');
        $male = $population->sum('male');
        $female = $population->sum('female');

        return [
            'applied' => $appliedSchool ?? null,
            'report' => $report_school ?? null,
            'supervised' => $supervised_school ?? null,
            'operation_school' => $operation_school ?? null,
            'male' => $male ?? null,
            'female' => $female ?? null,
        ];

    }

    /** Function for plotting 3rd page */
    private function inpatientMorbidity($from_date, $to_date)

    {
        try {
            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            if (!$handle) {
                return false;
            }
            $data = [];
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
                    $data[trim($csvLine[1])] = trim($csvLine[3]);
                }
            }

            $patfindings = PatFindings::with('encounter', 'encounter.patientInfo', 'encounter.currentDepartment')
                ->whereDate('fldtime', '>=', $from_date)
                ->whereDate('fldtime', '<=', $to_date)
                ->get();
            $ret_data = [];
            if ($patfindings) {
                foreach ($patfindings as $patfinding) {
                    if (isset($patfinding->encounter->department->fldcateg) && $patfinding->encounter->department->fldcateg = 'Patient ward') {
                        $age = Carbon::parse($patfinding->encounter->patientInfo->fldptbirday)->diffInDays(Carbon::now()) ?? null;
                        $sex = $patfinding->encounter->patientInfo->fldptsex ?? null;
                        $disease = (isset($data[$patfinding->fldcodeid])) ? $data[$patfinding->fldcodeid] : '';
                        $code = $patfinding->fldcodeid ?? null;

                        $age_range = '';
                        if ($age > 0 && $age <= 28)
                            $age_range = '0-29 days';
                        elseif ($age >= 29 && $age < 365)
                            $age_range = '1-12 month';
                        elseif ($age >= 366 && $age < 1460)
                            $age_range = '1-4 years';
                        elseif ($age >= 1461 && $age < 5110)
                            $age_range = '5-14 years';
                        elseif ($age >= 5111 && $age < 6935)
                            $age_range = '15-19 years';
                        elseif ($age >= 6936 && $age < 10585)
                            $age_range = '20-29 years';
                        elseif ($age >= 10586 && $age < 14235)
                            $age_range = '30-39 years';
                        elseif ($age >= 14236 && $age < 17885)
                            $age_range = '40-49 years';
                        elseif ($age >= 17886 && $age < 21535)
                            $age_range = '50-59 years';
                        elseif ($age >= 21536 && $age < 21900)
                            $age_range = '60 years';

                        if ($disease == '' || $age_range == '')
                            continue;

                        if (!isset($ret_data[$code])) {
                            $ret_data[$code] = [
                                'code' => $code,
                                'disease' => $disease,
                            ];
                        }
                        if (isset($ret_data[$code][$age_range][$sex]))
                            $ret_data[$code][$age_range][$sex] += 1;
                        else
                            $ret_data[$code][$age_range][$sex] = 1;
                    }
                }
                return [
                    'ret_data' => $ret_data,
                    'age_group' =>
                        [
                            '0-29 days',
                            '1-12 month',
                            '1-4 years',
                            '5-14 years',
                            '15-19 years',
                            '20-29 years',
                            '30-39 years',
                            '40-49 years',
                            '50-59 years',
                            '60 years',
                        ],
                    'sex ' => [
                        'male',
                        'female',
                    ],
                ];
            }

        } catch (\Exception $exception) {
            dd($exception);
            return false;
        }

    }

    private function outPateint($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }
        try {
            $outpatient_array = DB::select(DB::raw("SELECT tbl_pf.fldcodeid,tbl_pinf.fldptsex FROM `tblpatfindings` AS `tbl_pf`
                            JOIN `tblencounter` AS `tbl_enc` ON tbl_pf.fldencounterval = tbl_enc.fldencounterval
                            JOIN `tblpatientinfo` AS `tbl_pinf` ON tbl_pinf.fldpatientval = tbl_enc.fldpatientval
                            WHERE `fldcodeid` IN (
                            'A37.9','A33','B05.9','A36.9','B01.9', 'A35','A16.9','G83','B06.9','B26.9','B16.9',
                             'A86','B74.9','B54','B50.9','B51.9','A90','B55.9','A01.0','A09','A06.9',
                             'A03.9','K52.9','A00.9','B82.9','R17','B15.9','B17','E86','A30.9','G03.9',
                             'B20','A54','N49','N89.8','N74','A54.3','N76.6','N50.8','A55','A51','J22',
                             'J06','J18','J15','J40','N39','J11','N99','N51*','E04','E14','E46','E50','E66',
                             'D64','G62','L70','B07','L81.1','L50','L30.9','L65','L80','E70.3','B00','B02',
                             'L53.9','L01.0','L02','L02.0','L43','B86','L81.5','L40','L04','H66.0','H66.1',
                             'J32','J03','J02','T16','T17.1','T17.2','H61.2','J33','H02.1','H02','H35','E14.3†','H11.0',
                             'H00.1','H00.0','H05.2','H53.5','H40','H52','H54','H26','A71','H10','K04','B37','K13.2','K00.4',
                             'K01.1','K12','K08.9','K05','K08.8','K02','K21.0','H60','J31','J34.2','H26.1','H20','H35.3','H53.0',
                             'H50','H35.5','H53.6','C69.2','O00','O08','O13','O14','O15.0','O15.1','O15.2','O21','O46','O63','O64-O66',
                             'S37','O72','O73','O75','O85','N73','N81.4','N92','N93','N97','N46','C11','C67','C56','C85','C06','C19','C23',
                             'C79.5','C25','C22','C73','C16','C15','C34','C53','C50','F99','G43','G40','F79','F48','F44','F42','F41','F40',
                             'F32','F31','F23','F20','F10','F03','C49.0','C80','I10','I50.0','I50.9','J44','I01','I09','I24','I52*','J45',
                             'N17','N18','N05','N04','R51','R50','K29','W57','R10','K74','T30','T65','W54','A82','T63.0','W59','Z73','T14',
                             'V89','M06','M13','M19','M54.9','K27','K60.2','K60.3','K63.2','N20.0','N63','N61','N64.4','D17','L72.1','L05',
                             'K37','K81','K80','K46','N43','N47','I84','N45','N41','R69')
                            AND (tbl_enc.fldadmission='Registered' or tbl_enc.fldadmission='Recorded')
                            AND tbl_pf.fldtime >='$from_date 00:00:00'
                            AND tbl_pf.fldtime<='$to_date 23:59:59.99'
                            "));
            if ($outpatient_array) {
                $outpatient_collection = collect($outpatient_array);
                return $outpatient_collection ?? null;
            }
        } catch (\Exception $exception) {

            return false;
        }
    }

    private function typeofSurgeries($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }
        try {

            $surgeries_array = DB::select(DB::raw("SELECT tblpatientinfo.fldptsex,tblservicecost.flditemtype,tblservicecost.fldtarget
                                                         FROM ( tblpatbilling INNER JOIN tblservicecost
                                                         ON tblpatbilling.flditemname = tblservicecost.flditemname
                                                         INNER JOIN tblencounter ON tblpatbilling.fldencounterval = tblencounter.fldencounterval )
                                                         INNER JOIN tblpatientinfo ON tblencounter.fldpatientval = tblpatientinfo.fldpatientval
                                                         WHERE tblpatbilling.fldtime >= '$from_date'
                                                         AND tblpatbilling.fldtime<='$to_date'"));

            $surgery_department = DB::select(DB::raw("SELECT tblpatientinfo.fldptsex,tblservicecost.flditemtype,tblservicecost.fldtarget,tbldepartment.fldcateg
                                                        FROM ( tblpatbilling INNER JOIN tblservicecost
                                                         ON tblpatbilling.flditemname = tblservicecost.flditemname
                                                         INNER JOIN tblencounter ON tblpatbilling.fldencounterval = tblencounter.fldencounterval )
                                                         INNER JOIN tblpatientinfo ON tblencounter.fldpatientval = tblpatientinfo.fldpatientval
                                                         INNER JOIN tbldepartment ON tblencounter.fldcurrlocat = tbldepartment.flddept
                                                         WHERE tblpatbilling.fldtime >= '$from_date'
                                                         AND tblpatbilling.fldtime<='$to_date'
                                                         "));

            $emergency_array = DB::select(DB::raw("SELECT tblpatientinfo.fldptsex, tblservicecost.flditemtype, tblservicecost.fldtarget, hmis_mapping.service_name
                                                    FROM ( tblpatbilling
                                                        INNER JOIN tblservicecost ON tblpatbilling.flditemname = tblservicecost.flditemname
                                                        INNER JOIN tblencounter ON tblpatbilling.fldencounterval = tblencounter.fldencounterval)
                                                        INNER JOIN tblpatientinfo ON tblencounter.fldpatientval = tblpatientinfo.fldpatientval
                                                        INNER JOIN hmis_mapping ON tblencounter.fldadmitlocat = hmis_mapping.service_name
                                                        WHERE tblpatbilling.fldtime >= '$from_date'
                                                         AND tblpatbilling.fldtime<='$to_date'"));

            $department = collect($surgery_department) ?? null;
            $emergency = collect($emergency_array) ?? null;
            $surgeries = collect($surgeries_array) ?? null;
            return ['department' => $department ?? null, 'surgeries' => $surgeries ?? null, 'emergency' => $emergency ?? null] ?? null;

        } catch (\Exception $exception) {
            return false;
        }
    }

    /** function for maternal */
    private function maternal($from_date, $to_date)
    {
//        dd('Here');
        $startTime = Carbon::parse($from_date)->setTime(00, 00, 00);
        $endTime = Carbon::parse($to_date)->setTime(23, 59, 59);
        $birday_from = 7300;
        $birday_to = 12775;

        try {

            $patientInfo = PatientInfo::select('fldpatientval')
                ->where('fldptbirday', 'LIKE', '%')
                ->whereRaw('DATEDIFF(current_date,fldptbirday) >= ' . $birday_from)
                ->pluck('fldpatientval');

            $encounter = Encounter::select('fldencounterval')
                ->whereIn('fldpatientval', $patientInfo)
                ->pluck('fldencounterval');

            $data['greatertwenty'] = Confinement::select('fldencounterval as col', 'flddeltime')
                ->where('fldtime', '>=', $startTime)
                ->where('fldtime', '<=', $endTime)
                ->whereIn('fldencounterval', $encounter)
                ->distinct('fldencounterval')->get();

            $firstWeek = $secondWeek = $thirdWeek = $fourthWeek = 0;
            if ($data['greatertwenty']) {
                foreach (array_chunk($data['greatertwenty'], 100) as $d) {
                    $first = \Carbon\Carbon::parse($d->flddeltime);
                    $second = \Carbon\Carbon::parse(now());
                    $interval = $first->diff($second);
                    $week = round($interval->format('%a') / 7);
                    if ($week >= 22 and $week < 27)
                        $firstWeek = $firstWeek + 1;
                    elseif ($week >= 28 and $week < 36)
                        $secondWeek = $secondWeek + 1;
                    elseif ($week >= 37 and $week < 41)
                        $thirdWeek = $thirdWeek + 1;
                    elseif ($week >= 42)
                        $fourthWeek = $fourthWeek + 1;
                }
            }
//            dd($data);
            $data['greatertwentydata'] = [
                $firstWeek, $secondWeek, $thirdWeek, $fourthWeek

            ];

            $patientInfo = PatientInfo::select('fldpatientval')
                ->where('fldptbirday', 'LIKE', '%')
                ->whereRaw('DATEDIFF(current_date, fldptbirday) >= ' . $birday_from)
                ->whereRaw('DATEDIFF(current_date, fldptbirday) < ' . $birday_to)
                ->pluck('fldpatientval');

            $encounter = Encounter::select('fldencounterval')
                ->whereIn('fldpatientval', $patientInfo)
                ->pluck('fldencounterval');

            $data['mid'] = Confinement::select('fldencounterval as col', 'flddeltime')
                ->where('fldtime', '>=', $startTime)
                ->where('fldtime', '<=', $endTime)
                ->whereIn('fldencounterval', $encounter)
                ->distinct('fldencounterval')->get();

            $firstWeek = $secondWeek = $thirdWeek = $fourthWeek = 0;
            if ($data['mid']) {
                foreach (array_chunk($data['greatertwenty'], 100) as $d) {
                    $first = \Carbon\Carbon::parse($d->flddeltime);
                    $second = \Carbon\Carbon::parse(now());
                    $interval = $first->diff($second);
                    $week = round($interval->format('%a') / 7);
                    if ($week >= 22 and $week < 27)
                        $firstWeek = $firstWeek + 1;
                    elseif ($week >= 28 and $week < 36)
                        $secondWeek = $secondWeek + 1;
                    elseif ($week >= 37 and $week < 41)
                        $thirdWeek = $thirdWeek + 1;
                    elseif ($week >= 42)
                        $fourthWeek = $fourthWeek + 1;
                }
            }
            $data['mid'] = [
                $firstWeek, $secondWeek, $thirdWeek, $fourthWeek

            ];


            $patientInfo = PatientInfo::select('fldpatientval')
                ->where('fldptbirday', 'LIKE', '%')
                ->whereRaw('DATEDIFF(current_date, fldptbirday) < ' . $birday_to)
                ->pluck('fldpatientval');

            $encounter = Encounter::select('fldencounterval')
                ->whereIn('fldpatientval', $patientInfo)
                ->pluck('fldencounterval');

            $data['greaterthrityfive'] = Confinement::select('fldencounterval as col', 'flddeltime')
                ->where('fldtime', '>=', $startTime)
                ->where('fldtime', '<=', $endTime)
                ->whereIn('fldencounterval', $encounter)
                ->distinct('fldencounterval')->get();

            $firstWeek = $secondWeek = $thirdWeek = $fourthWeek = 0;
            if ($data['greaterthrityfive']) {
                foreach (array_chunk($data['greatertwenty'], 100) as $d) {
                    $first = \Carbon\Carbon::parse($d->flddeltime);
                    $second = \Carbon\Carbon::parse(now());
                    $interval = $first->diff($second);
                    $week = round($interval->format('%a') / 7);
                    // echo  $week . '<br>';
                    if ($week >= 22 and $week < 27)
                        $firstWeek = $firstWeek + 1;
                    elseif ($week >= 28 and $week < 36)
                        $secondWeek = $secondWeek + 1;
                    elseif ($week >= 37 and $week < 41)
                        $thirdWeek = $thirdWeek + 1;
                    elseif ($week >= 42)
                        $fourthWeek = $fourthWeek + 1;
                }
            }
            $data['greaterthrityfive'] = [
                $firstWeek, $secondWeek, $thirdWeek, $fourthWeek

            ];

            return $data ?? null;

        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    /** Function for DIgnostic Service */

    private function diagnostic($from_date, $to_date)
    {
        $dignostic_no_array = DB::select(DB::raw("SELECT tblpatbilling.flditemqty,tblpatbilling.flditemname,hmis_mapping.sub_category
                                                            FROM tblpatbilling
                                                        JOIN hmis_mapping
                                                         ON tblpatbilling.flditemname = hmis_mapping.service_value
                                                        WHERE tblpatbilling.flditemtype='Radio Diagnostics'
                                                           OR tblpatbilling.fldsample='Waiting'
                                                            OR tblpatbilling.fldsample='Reported'
                                                        OR tblpatbilling.fldsample='Verified'


                                                        AND tblpatbilling.fldtime >= '$from_date'
                                                         AND tblpatbilling.fldtime <='$to_date'"));

        $dignostic_person = DB::select(DB::raw("SELECT tblpatbilling.flditemqty,tblpatbilling.flditemname,hmis_mapping.sub_category
                                                            FROM tblpatbilling
                                                        JOIN hmis_mapping
                                                         ON tblpatbilling.flditemname = hmis_mapping.service_value
                                                        WHERE tblpatbilling.flditemtype='Radio Diagnostics'
                                                           OR tblpatbilling.fldsample='Waiting'
                                                            OR tblpatbilling.fldsample='Reported'
                                                        OR tblpatbilling.fldsample='Verified'
                                                        AND tblpatbilling.fldtime >= '$from_date'
                                                         AND tblpatbilling.fldtime <='$to_date'"));

        $data['total_lab_service'] = PatBilling::select('fldencounterval')
            ->where('flditemtype', '=', 'Diagnostic Tests')
            ->where(function ($query) {
                $query->where('fldsample', '=', 'Sampled');
                $query->orWhere('fldsample', '=', 'Reported');
                $query->orWhere('fldsample', '=', 'Verified');
            })->where('fldtime', '>=', $from_date)
            ->where('fldtime', '<=', $to_date)
            ->count();
        $data['dignostic_number'] = collect($dignostic_no_array) ?? null;
        $data['dignostic_person'] = collect($dignostic_person) ?? null;
        return $data ?? null;
    }

    /** Function for Family Planning */
    private function familyPlanning($from_date, $to_date)
    {

//        dd($from_date, $to_date);
        if (!$from_date && !$to_date) {
            return false;
        }
        try {
            $family_health = FamilyHealth::with('patientInfo')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->get();
            $data = [];
            if (isset($family_health) && $family_health) {
                foreach ($family_health as $family) {
                    $age[] = Carbon::parse($family->patientInfo->fldptbirday)->age;

                    $data ['service_using'] = [
                            'pills' => $family->where('type_of_service', '=', 'pills')->count() ?? null,

                            'depo' => $family->where('type_of_service', '=', 'depo')->count() ?? null,

                            'iucd' => $family->where('type_of_service', '=', 'IUCD')->count() ?? null,

                            'Implant' => $family->where('type_of_service', '=', 'Implant')->count() ?? null,
                        ] ?? null;

                    $data ['service_continue'] = [
                            'pills' => $family->where('type_of_service', '=', 'pills')->where('service_status', '=', 'continue')->count() ?? null,

                            'depo' => $family->where('type_of_service', '=', 'depo')->where('service_status', '=', 'continue')->count() ?? null,

                            'iucd' => $family->where('type_of_service', '=', 'IUCD')->where('service_status', '=', 'continue')->count() ?? null,

                            'Implant' => $family->where('type_of_service', '=', 'Implant')->where('service_status', '=', 'continue')->count() ?? null,
                        ] ?? null;

                    $data ['service_count'] = [
                            'condom' => $family->where('type_of_service', '=', 'condom')->sum('quantity') ?? null,

                            'pills' => $family->where('type_of_service', '=', 'pills')->sum('quantity') ?? null,

                            'depo' => $family->where('type_of_service', '=', 'depo')->sum('quantity') ?? null,

                            'iucd' => $family->where('type_of_service', '=', 'IUCD')->sum('quantity') ?? null,

                            'Implant' => $family->where('type_of_service', '=', 'Implant')->sum('quantity') ?? null,
                        ] ?? null;

                    if ($age < 20) {

                        $data ['less_than_twenty'] = [
                            'pills' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'pills')->count() ?? null,

                            'depo' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'depo')->count() ?? null,

                            'iucd' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'IUCD')->count() ?? null,

                            'Implant' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'Implant')->count() ?? null,


                        ];
                    }

                    if ($age >= 20) {
                        $data ['greater_than_twenty'] = [
                            'pills' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'pills')->count() ?? null,

                            'depo' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'depo')->count() ?? null,

                            'iucd' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'IUCD')->count() ?? null,

                            'Implant' => $family->where('user_type', '=', 'new')
                                    ->where('type_of_service', '=', 'Implant')->count() ?? null,

                        ];
                    }
                }

                return $data;

            }

        } catch (\Exception $exception) {
            return false;
        }

    }

    /** Function for ploting mch data of 6th page */
    private function mchData($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }

        $compli = DB::select(DB::raw("SELECT hmis_obstetric_complication.encounter_no,hmis_obstetric_complication.obstetric_complication
                                                            FROM hmis_obstetric_complication
                                                            INNER JOIN hmis_delivery_complication on hmis_obstetric_complication.obstetric_complication = hmis_delivery_complication.delivery_complication
                                                            INNER JOIN hmis_pnc ON hmis_obstetric_complication.obstetric_complication = hmis_pnc.obstetric_complication_pnc
                                                            WHERE hmis_obstetric_complication.created_at >='$from_date'
                                                            AND hmis_obstetric_complication.created_at <='$to_date'"));
        $data['complications'] = collect($compli);
        $delivery = DeliveryInfo::
        where('created_at', '>=', $from_date)
            ->where('created_at', '<=', $to_date)
            ->get();
        if ($delivery) {
            $forcep_cephalic = DeliveryInfo::where('presentation', '=', 'Cephalic')
                    ->where(function ($query) {
                        $query->where('delivery_type', '=', 'Vacuum');
                        $query->orWhere('delivery_type', '=', 'Forcep');
                    })->where('created_at', '>=', $from_date)
                    ->where('created_at', '<=', $to_date)->count() ?? null;
            $forcep_shoulder = DeliveryInfo::where('presentation', '=', 'Shoulder')->where(function ($query) {
                    $query->where('delivery_type', '=', 'Vacuum');
                    $query->orWhere('delivery_type', '=', 'Forcep');
                })->where('created_at', '>=', $from_date)
                    ->where('created_at', '<=', $to_date)->count() ?? null;
            $forcep_breeche = DeliveryInfo::where('presentation', '=', 'Breech')->where(function ($query) {
                    $query->where('delivery_type', '=', 'Vacuum');
                    $query->orWhere('delivery_type', '=', 'Forcep');
                })->where('created_at', '>=', $from_date)
                    ->where('created_at', '<=', $to_date)->count() ?? null;
        }

        $data['delivery_info'] = $delivery ?? null;
        $data['force_cephalic'] = $forcep_cephalic ?? null;
        $data['force_shoulder'] = $forcep_shoulder ?? null;
        $data['force_breeche'] = $forcep_breeche ?? null;

        $data['other_complication'] = OtherComplication::where('created_at', '>=', $from_date)
                ->where('created_at', '<=', $to_date)
                ->count() ?? null;

        return $data;
    }

    /** FUnction to plot 19th page */
    private function laboratoryServices($from_date, $to_date)
    {
        $test_array = DB::select(DB::raw("SELECT tblpatlabtest.fldtestid,hmis_mapping.sub_category,hmis_mapping.service_name
        FROM tblpatlabtest
        JOIN  hmis_mapping ON tblpatlabtest.fldtestid=hmis_mapping.service_value
                WHERE hmis_mapping.category='Test Mapping'
        AND tblpatlabtest.fldtime_sample >='$from_date 00:00:00'
                AND tblpatlabtest.fldtime_sample <='$to_date 23:59:59.99'

      "));
        $test_collection = collect($test_array);
        return $test_collection ?? null;

    }


    public function getDate(Request $request)
    {
        if (!$request->get('name')) {
            return \response()->json(['error' => 'Please select date']);
        }

        $date = Year::where('fldname', $request->get('name'))->first();
        if ($date) {
            $data['fldfirst'] = Carbon::parse($date->fldfirst)->format('Y-m-d');
            $data['fldlast'] = Carbon::parse($date->fldlast)->format('Y-m-d');
            return \response()->json($data);
        } else {
            return \response()->json(['error', 'Date not found']);
        }
    }


    /** Fucntion for Gastation week  */
    private function gestationWeek($from_date, $to_date)
    {
        if (!$from_date && !$to_date) {
            return false;
        }

        $gestattions = DB::select(DB::raw("SELECT COUNT(*) as total,WEEK(tblconfinement.fldtime) as week,
                                            CASE
                                                 WHEN tblexamgeneral.flditem='Gravida' AND tblexamgeneral.fldreportquali=1 THEN 'primi'
                                                 WHEN tblexamgeneral.flditem='Gravida' AND (tblexamgeneral.fldreportquali>1 AND tblexamgeneral.fldreportquali<=4) THEN 'multi'
                                                 WHEN tblexamgeneral.flditem='Gravida' AND (tblexamgeneral.fldreportquali>5)  THEN 'grand'

                                            ELSE 'others'
                                            END as gastation
                                            FROM  tblconfinement
                                            join tblencounter on tblconfinement.fldencounterval = tblencounter.fldencounterval
                                            JOIN tblexamgeneral on tblconfinement.fldencounterval = tblencounter.fldencounterval
                                            WHERE tblexamgeneral.fldinput='Obstetrics'
                                            AND tblconfinement.fldtime >='$from_date'
                                            AND tblconfinement.fldtime <='$to_date'
                                            GROUP BY gastation,week"));

        $gestattions = $gestattions ? collect($gestattions) : '';

        return $gestattions;

    }

}
