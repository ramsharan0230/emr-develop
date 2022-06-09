<?php

namespace Modules\ConsultPatientData\Http\Controllers;

use App\BillingSet;
use App\Encounter;
use App\EthnicGroup;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\VisitReportExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class VisitReportController
 * @package Modules\ConsultPatientData\Http\Controllers
 */
class VisitReportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayReport()
    {
        if (\App\Utils\Permission::checkPermissionFrontendAdmin('visit-report')) {
            Helpers::jobRecord('fmPatAdmit', 'Visit Report');
            $data['addresses'] = $this->_getAllAddress();
            $data['districts'] = \App\Municipal::select("flddistrict", "fldprovince")->groupBy("flddistrict")->orderBy("flddistrict")->get();
            $data['discounts'] = Helpers::getDiscounts();
            $data['department'] = Helpers::getDepartmentByCategory('Consultation');
            $data['comp'] = Helpers::getCompName();
            $data['mode'] = BillingSet::all();
            // dd($data);

            return view('consultpatientdata::visit-report', $data);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchData(Request $request)
    {
        $encounter_id = $request->encounter_id;
        $comp = $request->comp;
        $department = $request->department;
        $province = $request->province;
        $district = $request->district;
        $freetext = $request->freetext;
        $to_date = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $from_date = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $gender = $request->gender;
        $last_status = $request->last_status;
        $mode = $request->mode;
        $type = $request->type;
        $age_from = $request->age_from * 365;
        $age_to = $request->age_to * 365;
        $noofdays = 0;

        $resultData = Encounter::select('fldpatientval','flddoa','flddod' , 'fldencounterval', 'fldregdate', 'fldadmission', 'flduserid', 'fldrank')
            ->when($encounter_id != "", function($query) use ($encounter_id) {
                $query->where(function($query) use ($encounter_id){
                    $query->where('fldencounterval',$encounter_id)->orWhere('fldpatientval',$encounter_id);
                });
            })
            ->when($comp != "%", function($query) use ($comp) {
                $query->where("fldcomp", 'LIKE', $comp);
            })
            ->when($department != "%", function($query) use ($department) {
                $query->where("fldadmitlocat", 'LIKE', $department);
            })
            ->when($last_status == "%", function($query) use ($from_date,$to_date) {
                $query->where(function($query) use ($from_date,$to_date){
                    $query->whereBetween('flddoa', [$from_date, $to_date])
                    ->orWhereBetween('fldregdate', [$from_date, $to_date])
                    ->orWhereBetween('flddod', [$from_date, $to_date]);
                });
            })
            ->when($last_status != "%", function($query) use ($last_status,$from_date,$to_date) {
                $query->where('fldadmission',$last_status)
                    ->when($last_status == 'Admitted', function($query) use ($from_date,$to_date) {
                        $query->where('flddoa', '>=', $from_date)->where('flddoa', '<=', $to_date);
                    })
                    ->when($last_status == 'Discharged' || $last_status == 'Registered', function($query) use ($from_date,$to_date) {
                        $query->where('fldregdate', '>=', $from_date)->where('fldregdate', '<=', $to_date);
                    })
                    ->when($last_status == 'Absconder' || $last_status == 'LAMA', function($query) use ($from_date,$to_date) {
                        $query->where('flddod', '>=', $from_date)->where('flddod', '<=', $to_date);
                    });
            })
            ->when($mode != "%", function($query) use ($mode) {
                $query->where("fldbillingmode", 'LIKE', $mode);
            })
            ->when($type == "Age", function($query) use ($age_from,$age_to) {
                $resultDataPatientValAge = DB::table('tblpatientinfo')->select('tblpatientinfo.fldpatientval')
                ->join('tblencounter', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->where('tblpatientinfo.fldptbirday', 'LIKE', '%')
                ->whereRaw('DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) >= ' . $age_from)
                ->whereRaw('DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) < ' . $age_to)
                ->pluck('tblpatientinfo.fldpatientval');
            
                $query->whereIn('fldpatientval', $resultDataPatientValAge);
            })
            ->when($type == "Discount Type" && $freetext != "", function($query) use ($freetext) {
                $query->where('flddisctype', 'LIKE', $freetext);
            })
            ->when($type == "Ethnic Group" && $freetext != "", function($query) use ($freetext) {
                $query->whereHas('patientInfo', function ($q) use ($freetext) {
                    $q->where('fldethnicgroup', $freetext);
                });
            })
            ->when($gender != "", function($query) use ($gender) {
                $query->whereHas('patientInfo', function ($q) use ($gender) {
                    $q->where('fldptsex', $gender);
                });
            })
            ->when($district != "", function($query) use ($district) {
                $query->whereHas('patientInfo', function ($q) use ($district) {
                    $q->where('fldptadddist', $district);
                });
            })
            ->when($province != "", function($query) use ($province) {
                $query->whereHas('patientInfo', function ($q) use ($province) {
                    $q->where('fldprovince', $province);
                });
            });

        if ($request->has('typePdf')) {
            $resultArray = $resultData->with(['patientInfo'])->get();
        } else {
            $resultArray = $resultData->with(['patientInfo'])->paginate(25);
        }

//        $resultArray = $resultData->with(['patientInfo'])->get();
        $html = '';
        $count = 1;
        foreach ($resultArray as $patient) {
            $getconsult = Helpers::getEncounterConsultantVisit($patient->fldencounterval);
            if ($last_status == 'Admitted' || $patient->fldadmission == 'Admitted') {

                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddoa)));
                $now = \Carbon\Carbon::now();
                $noofdays = $datework->diffInDays($now);

            }
            if ($last_status == 'Registered' || $patient->fldadmission == 'Registered') {
                $noofdays =0 ;
            }
            if ($last_status == 'Discharged' || $patient->fldadmission == 'Discharged') {
                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddoa)) . '00:00:00');
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }
            if ($last_status == 'Absconder' || $patient->fldadmission == 'Absconder') {
                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddod)) . '00:00:00');
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }
            if ($last_status == 'LAMA' || $patient->fldadmission == 'LAMA') {
                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddod)) . '00:00:00');
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }


            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
            if ($patient->patientInfo) {
                $html .= '<td>' . $user_rank . ' ' . $patient->patientInfo->fldptnamefir . ' ' . $patient->patientInfo->fldmidname . ' ' . $patient->patientInfo->fldptnamelast . '</td>';
                $html .= '<td>' . $patient->patientInfo->fldagestyle . '</td>';
                // $html .= '<td>' . Helpers::ageCalculation($patient->patientInfo->fldptbirday) . '</td>';
                $html .= '<td>' . $patient->patientInfo->fldptsex . '</td>';
            } else {
                $html .= '<td></td><td></td><td></td>';
            }

            /*if admitted count starting day as 1 and add to difference*/
            $noofdays = $noofdays != 0 ? $noofdays + 1 : 0;

            $html .= $patient->fldregdate ? '<td>' . \Carbon\Carbon::parse($patient->fldregdate)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->fldregdate)->format('H:i:s') . '</td>' : '<td></td>';
            $html .= $patient->flddoa ? '<td>' . \Carbon\Carbon::parse($patient->flddoa)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->flddoa)->format('H:i:s') . '</td>' : '<td></td>';
            $html .= $patient->flddod ? '<td>' . \Carbon\Carbon::parse($patient->flddod)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->flddod)->format('H:i:s') . '</td>' : '<td></td>';
            $html .= '<td>' . $noofdays . '</td>';
            $html .= '<td>' . $patient->fldadmission . '</td>';
            $html .= $getconsult ? '<td>' . $getconsult . '</td>' : '<td></td>';
            $html .= '</tr>';
            $count++;
        }

        $pdf = '';
        $countpdf = 1;
        foreach ($resultArray as $patient) {
            $getconsultDep = Helpers::getEncounterConsultantVisitDepartment($patient->fldencounterval);
            $getconsultDoc = Helpers::getEncounterConsultantVisitDocName($patient->fldencounterval);
            $getconsultReg = Helpers::getEncounterConsultantVisitReg($patient->fldencounterval);

            if ($last_status == 'Admitted' || $patient->fldadmission == 'Admitted') {

                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddoa)));
                $now = \Carbon\Carbon::now();
                $noofdays = $datework->diffInDays($now);

            }
            if ($last_status == 'Registered' || $patient->fldadmission == 'Registered') {
                $noofdays =0 ;
            }
            if ($last_status == 'Discharged' || $patient->fldadmission == 'Discharged') {
                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddoa)) . '00:00:00');
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }
            if ($last_status == 'Absconder' || $patient->fldadmission == 'Absconder') {
                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddod)) . '00:00:00');
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }
            if ($last_status == 'LAMA' || $patient->fldadmission == 'LAMA') {
                $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddod)) . '00:00:00');
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }


            $pdf .= '<tr>';
            $pdf .= '<td>' . $countpdf . '</td>';
            $pdf .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
            if ($patient->patientInfo) {
                $pdf .= '<td>' . $user_rank . ' ' . $patient->patientInfo->fldptnamefir . ' ' . $patient->patientInfo->fldmidname . ' ' . $patient->patientInfo->fldptnamelast . '</td>';
                $pdf .= '<td>' . $patient->patientInfo->fldagestyle . '</td>';
                // $html .= '<td>' . Helpers::ageCalculation($patient->patientInfo->fldptbirday) . '</td>';
                $pdf .= '<td>' . $patient->patientInfo->fldptsex . '</td>';
            } else {
                $pdf .= '<td></td><td></td><td></td>';
            }

            /*if admitted count starting day as 1 and add to difference*/
            $noofdays = $noofdays != 0 ? $noofdays + 1 : 0;

            $pdf .= $patient->fldregdate ? '<td>' . \Carbon\Carbon::parse($patient->fldregdate)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->fldregdate)->format('H:i:s') . '</td>' : '<td></td>';
            $pdf .= $patient->flddoa ? '<td>' . \Carbon\Carbon::parse($patient->flddoa)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->flddoa)->format('H:i:s') . '</td>' : '<td></td>';
            $pdf .= $patient->flddod ? '<td>' . \Carbon\Carbon::parse($patient->flddod)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->flddod)->format('H:i:s') . '</td>' : '<td></td>';
            $pdf .= '<td>' . $noofdays . '</td>';
            $pdf .= '<td>' . $patient->fldadmission . '</td>';
            $pdf .= $getconsultDep ? '<td>' . $getconsultDep . '</td>' : '<td></td>';
            $pdf .= $getconsultDoc ? '<td>' . $getconsultDoc . '</td>' : '<td></td>';
            $pdf .= $getconsultReg ? '<td>' . $getconsultReg . '</td>' : '<td></td>';
            $pdf .= '</tr>';
            $countpdf++;
        }
        if (!$request->has('typePdf')) {
            $html .= '<tr><td colspan="20">' . $resultArray->appends(request()->all())->links() . '</td></tr>';
        }

        if ($request->has('typePdf')) {
            $data = [];
            $data['html'] = $pdf;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['certificate'] = "VISIT REPORT";
            return view('consultpatientdata::pdf.visit-report-pdf', $data);
        } else {
            return $html;
        }
    }

    public function excel(Request $request)
    {
        $export = new VisitReportExport($request->encounter_id, $request->comp, $request->department, $request->province, $request->district, $request->freetext, $request->to_date, $request->from_date, $request->gender, $request->last_status, $request->mode, $request->type, $request->age_from, $request->age_to, $request->noofdays);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'VisitReportExport.xlsx');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function searchDataName(Request $request)
    {
        //        select fldencounterval,fldencounterval,fldencounterval,fldencounterval,fldregdate,fldadmission,flduserid,fldencounterval from tblencounter where fldpatientval in(select fldpatientval from tblpatientinfo where lower(fldptnamefir) like 't' and lower(fldptnamelast) like '%')

        $patientInfo = PatientInfo::select('fldpatientval')
            ->whereRaw('lower(fldptnamefir) like \'' . $request->firstname . '\'')
            ->whereRaw('lower(fldptnamelast) like \'' . $request->lastname . '\'')
            ->pluck('fldpatientval');

        $resultArray = Encounter::select('fldregdate','flddoa','flddod' ,'fldadmission', 'flduserid', 'fldencounterval', 'fldpatientval', 'fldrank')
            ->whereIn('fldpatientval', $patientInfo)
            ->with(['patientInfo'])
            ->get();

        $html = '';
        $count = 1;
        $noofdays = 0;

        foreach ($resultArray as $patient) {
            $getconsult = Helpers::getEncounterConsultantVisit($patient->fldencounterval);
            if ($patient->fldadmission == 'Admitted') {

                $datework = \Carbon\Carbon::createFromDate($patient->flddoa);
                $now = \Carbon\Carbon::now();
                $noofdays = $datework->diffInDays($now);

            }
            if ($patient->fldadmission == 'Registered') {

                $noofdays =0 ;
            }
            if ($patient->fldadmission == 'Discharged') {
                $datework = \Carbon\Carbon::createFromDate($patient->flddod);
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }
            if ($patient->fldadmission == 'Absconder') {
                $datework = \Carbon\Carbon::createFromDate($patient->flddod);
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }
            if ($patient->fldadmission == 'LAMA') {
                $datework = \Carbon\Carbon::createFromDate($patient->flddod);
                $now = $patient->flddod;
                $noofdays = $datework->diffInDays($now);
            }
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->patientInfo->fldptnamefir . ' ' . $patient->patientInfo->fldmidname . ' ' . $patient->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . \Carbon\Carbon::parse($patient->fldregdate)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->fldregdate)->format('H:i:s') . '</td>';
            $html .= '<td>' . \Carbon\Carbon::parse($patient->flddoa)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->flddoa)->format('H:i:s') . '</td>';
            $html .= '<td>' . \Carbon\Carbon::parse($patient->flddod)->format('Y-m-d').'<br>'.\Carbon\Carbon::parse($patient->flddod)->format('H:i:s') . '</td>';
            $html .= '<td>' . $noofdays . '</td>';
            $html .= '<td>' . $patient->fldadmission . '</td>';
            $html .= '<td>'.$getconsult.'</td>';
            $html .= '</tr>';
            $count++;
        }
        return $html;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displaySearchNameForm()
    {
        $data['routeName'] = route('display.consultation.view.report.search.name');
        $data['appendId'] = 'visit_report_data';
        return view('consultpatientdata::common.search-name', $data);
    }

    public function generatePdf(Request $request)
    {
        try {
            $type = $data['pdfType'] = $request->typePdf;
            $to = Carbon::parse($request->to_date)->setTime(23, 59, 59);
            $from = Carbon::parse($request->from_date)->setTime(00, 00, 00);
            if ($encounterPatientVal = Encounter::select("fldpatientval")->whereBetween('fldregdate', array($from, $to))->pluck('fldpatientval')) {
                /*GENDER, SURNAME AND DISTRICT*/
                if ($type == 'Gender' || $type == "Surname" || $type == "District") {
                    $data['field'] = '';
                    if ($type == "Gender") {
                        $data['field'] = 'fldptsex';
                    } elseif ($type == "Surname") {
                        $data['field'] = 'fldptnamelast';
                    } elseif ($type == "District") {
                        $data['field'] = 'fldptadddist';
                    }

                    $patientInfo = PatientInfo::select($data['field'] . ' as fld')
                        ->whereIn('fldpatientval', $encounterPatientVal)
                        ->distinct($data['field'])
                        ->orderBy($data['field'])
                        ->get();

                    foreach ($patientInfo as $PI) {
                        $dataLoop[$PI->fld]['total'] = Encounter::select(DB::raw('fldencounterval', 'fldrank', "COUNT(fldencounterval) as total"))
                            ->whereBetween('fldregdate', array($from, $to))
                            ->whereIn('fldpatientval', PatientInfo::select('fldpatientval')->where($data['field'], $PI->fld)->pluck('fldpatientval'))
                            ->count();
                    }

                    $totaldata = 0;
                    if (isset($dataLoop) and count($dataLoop) > 0) {
                        foreach ($dataLoop as $r) {
                            $totaldata = $totaldata + (int)$r['total'];
                        }
                    }

                    $data['date_from'] = date('m/d/Y', strtotime($from));
                    $data['date_to'] = date('m/d/Y', strtotime($to));
                    $data['total'] = $totaldata;
                    $data['result'] = $dataLoop;

                    return view('consultpatientdata::pdf.sex-gender-district', $data);
                }
                /*ETHNIC GROUP*/
                if ($type == "Ethnic Group") {
                    $patientInfo = PatientInfo::select('fldptnamelast')
                        ->whereIn('fldpatientval', $encounterPatientVal)
                        ->distinct('fldptnamelast')
                        ->orderBy('fldptnamelast')
                        ->pluck('fldptnamelast');

                    $dataLoop = EthnicGroup::select(DB::raw('fldgroupname', "COUNT(fldgroupname) as total"))
                        ->whereIn('flditemname', $patientInfo)
                        ->distinct('fldgroupname')
                        ->get();

                    $totaldata = 0;
                    if (isset($dataLoop) and count($dataLoop) > 0) {
                        foreach ($dataLoop as $r) {
                            $totaldata = $totaldata + (int)$r['total'];
                        }
                    }

                    $data['date_from'] = date('m/d/Y', strtotime($from));
                    $data['date_to'] = date('m/d/Y', strtotime($to));
                    $data['total'] = $totaldata;
                    $data['result'] = $dataLoop;
                    return view('consultpatientdata::pdf.ethnic', $data);
                }
                /*AGE GROUP*/
                if ($type == "Age Group") {
                    $ageArray = [
                        0 => 3285,
                        3286 => 6935,
                        6936 => 21535,
                        21536 => 43800,
                    ];
                    foreach ($ageArray as $start => $end) {
                        $dataLoop[$start . '-' . $end] = \DB::table('tblencounter')->select(DB::raw('tblencounter.fldencounterval', "COUNT(tblencounter.fldencounterval) as total"))
                            ->join('tblpatientinfo', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                            ->whereBetween('tblencounter.fldregdate', array($from, $to))
                            ->where('tblpatientinfo.fldptbirday', '!=', NULL)
                            ->whereRaw('DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) >= ' . $start)
                            ->whereRaw('DATEDIFF(tblencounter.fldregdate, tblpatientinfo.fldptbirday) < ' . $end)
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
                    //                    return $data;
                    return view('consultpatientdata::pdf.age', $data);
                }
                /*REG DEPARTMENT*/
                if ($type == 'Regd Depart') {
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
                    //                    return $data;
                    return view('consultpatientdata::pdf.sex-gender-district', $data);
                }
                /*REG LOCATION*/
                if ($type == 'Regd Location') {
                    $locationReg = Encounter::select("fldcomp")
                        ->whereBetween('fldregdate', array($from, $to))
                        ->orderBy('fldcomp', 'ASC')
                        ->get();

                    foreach ($locationReg as $PI) {
                        $dataLoop[$PI->fldcomp]['total'] = Encounter::select(DB::raw('fldencounterval', 'fldrank', "COUNT(fldencounterval) as total"))
                            ->whereBetween('fldregdate', array($from, $to))
                            ->where('fldcomp', $PI->fldcomp)
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
                    //                    return $data;
                    return view('consultpatientdata::pdf.sex-gender-district', $data);
                }
                /*BILLING GROUP*/
                if ($type == 'Billing Group') {
                    $billingMode = Encounter::select("fldbillingmode")
                        ->whereBetween('fldregdate', array($from, $to))
                        ->orderBy('fldbillingmode', 'ASC')
                        ->get();

                    foreach ($billingMode as $PI) {
                        $dataLoop[$PI->fldbillingmode]['total'] = Encounter::select(DB::raw('fldencounterval', 'fldrank', "COUNT(fldencounterval) as total"))
                            ->whereBetween('fldregdate', array($from, $to))
                            ->where('fldbillingmode', $PI->fldbillingmode)
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
                    //                    return $data;
                    return view('consultpatientdata::pdf.sex-gender-district', $data);
                }

                /*VISIT TYPE*/
                if ($type == 'Visit Type') {
                    $billingMode = Encounter::select("fldvisit")
                        ->whereBetween('fldregdate', array($from, $to))
                        ->orderBy('fldvisit', 'ASC')
                        ->get();

                    foreach ($billingMode as $PI) {
                        $dataLoop[$PI->fldvisit]['total'] = Encounter::select(DB::raw('fldencounterval', 'fldrank', "COUNT(fldencounterval) as total"))
                            ->whereBetween('fldregdate', array($from, $to))
                            ->where('fldvisit', $PI->fldvisit)
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
                    //                    return $data;
                    return view('consultpatientdata::pdf.sex-gender-district', $data);
                }
                /*LAST STATUS*/
                if ($type == 'Last Status') {
                }
                /*DISCOUNT*/
                if ($type == 'Discount') {
                }
            } else {
                return "No data found.";
            }
        } catch (\GearmanException $e) {
            return $e;
        }
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
}
