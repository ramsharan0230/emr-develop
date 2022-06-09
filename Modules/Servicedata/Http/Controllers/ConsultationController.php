<?php

namespace Modules\Servicedata\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Consult;
use App\Districts;
use App\Encounter;
use App\EthnicGroup;
use App\PatientInfo;
use App\PersonImage;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function displayConsultation()
    {
        $data['addresses'] = $this->_getAllAddress();
        $data['districts'] = \App\Municipal::select("flddistrict", "fldprovince")->groupBy("flddistrict")->orderBy("flddistrict")->get();
        $data['discounts'] = Helpers::getDiscounts();
        $data['department'] = Helpers::getDepartmentByCategory('Consultation');
        $data['comp'] = Helpers::getCompName();
        $data['mode'] = BillingSet::all();
        $data['consultants'] = CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
        return view('servicedata::consultation', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listConsultation(Request $request)
    {
        try {
            $date_from = $request->date_from . ' 00:00:00';
            $date_to = $request->date_to . ' 23:59:59';
            $department = $request->department;
            $mode = $request->mode;
            $comp = $request->comp;
            $gender = $request->gender;
            $province = $request->province;
            $district = $request->district;
            $type = $request->type;
            $freetext = $request->freetext;
            $agefrom = $request->age_from;
            $ageto = $request->age_to;
            $consultant = $request->consult;
            $html = '';
            if ($type === 'Ethnic Group') {
                $patientinfo = PatientInfo::select('fldpatientval')
                    ->where('fldptsex', 'LIKE', $gender)
                    ->where('fldprovince', 'LIKE', $province)
                    ->where('fldptadddist', 'LIKE', $district)
                    ->where('fldethnicgroup', 'LIKE', $freetext)
                    ->pluck('fldpatientval');

                if (is_countable($patientinfo) && count($patientinfo) == 0) {
                    return "No data available";
                }

                $encounter = Encounter::select('fldencounterval', 'fldrank')
                    ->whereIn('fldpatientval', $patientinfo)
                    ->get();

                if (is_countable($encounter) && count($encounter) == 0) {
                    return "No data available";
                }

                $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'fldconsultname', 'flduserid', 'fldstatus')
                    ->whereBetween('fldconsulttime', array($date_from, $date_to))
                    ->where('fldconsultname', 'LIKE', $department)
                    ->where('fldbillingmode', 'LIKE', $mode)
                    /*->where('fldcomp', 'LIKE', $comp)*/
                    ->where('fldstatus', '!=', 'Cancelled')
                    ->where('flduserid', 'LIKE', $consultant)
                    ->whereIn('fldencounterval', $encounter->pluck('fldencounterval'))
                    ->get();
            } elseif ($type === 'Discount Type') {

                $patientinfo = PatientInfo::select('fldpatientval')
                    ->where('fldptsex', 'LIKE', $gender)
                    ->where('fldprovince', 'LIKE', $province)
                    ->where('fldptadddist', 'LIKE', $district)
                    ->pluck('fldpatientval');

                if (is_countable($patientinfo) && count($patientinfo) == 0) {
                    return "No data available";
                }

                /*$patientval = array();

                if (isset($patientinfo) and count($patientinfo) > 0) {
                    foreach ($patientinfo as $key => $value) {
                        $patientval[] = $value->fldpatientval;
                    }
                }*/

                $encounter = Encounter::select('fldencounterval')
                    ->whereIn('fldpatientval', $patientinfo)
                    ->where('flddisctype', $freetext)
                    ->get();

                if (is_countable($encounter) && count($encounter) == 0) {
                    return "No data available";
                }

                $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'fldconsultname', 'flduserid', 'fldstatus')
                    ->whereBetween('fldconsulttime', array($date_from, $date_to))
                    ->where('fldconsultname', 'LIKE', $department)
                    ->where('fldbillingmode', 'LIKE', $mode)
                    /*->where('fldcomp', 'LIKE', $comp)*/
                    ->where('fldstatus', '!=', 'Cancelled')
                    ->where('flduserid', 'LIKE', $consultant)
                    ->whereIn('fldencounterval', $encounter)
                    ->get();

            } elseif ($type === 'Age') {
                $agefrom = $agefrom * 365;
                $ageto = $ageto * 365;

                $patientInfoData = PatientInfo::select('fldpatientval')
                    ->where(function ($query) use ($gender) {
                        if ($gender) {
                            return $query->where('fldptsex', 'LIKE', $gender);
                        }
                    })
                    ->where(function ($query) use ($district) {
                        if ($district) {
                            return $query->where('fldptadddist', 'LIKE', $district);
                        }
                    })
                    ->where(function ($query) use ($province) {
                        if ($province) {
                            return $query->where('fldprovince', 'LIKE', $province);
                        }
                    })
                    ->where(function ($query) use ($agefrom) {
                        if ($agefrom) {
                            return $query->whereRaw("DATEDIFF(t.fldconsulttime, p.fldptbirday)>=$agefrom");
                        }
                    })
                    ->where(function ($query) use ($ageto) {
                        if ($ageto) {
                            return $query->whereRaw("DATEDIFF(t.fldconsulttime, p.fldptbirday)<$ageto");
                        }
                    })
                    ->where('fldptbirday', 'LIKE', '%')
                    ->limit(100)
                    ->pluck('fldpatientval');

                $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'flduserid', 'fldstatus', 'fldconsultname')
                    ->where(function ($query) use ($date_from) {
                        if ($date_from) {
                            return $query->where('fldconsulttime', '>=', $date_from);
                        }
                    })
                    ->where(function ($query) use ($date_to) {
                        if ($date_to) {
                            return $query->where('fldconsulttime', '<=', $date_to);
                        }
                    })
                    ->where(function ($query) use ($department) {
                        if ($department) {
                            return $query->where('fldconsultname', 'LIKE', $department);
                        }
                    })
                    ->where(function ($query) use ($mode) {
                        if ($mode) {
                            return $query->where('fldbillingmode', 'LIKE', $mode);
                        }
                    })
                    /*->where(function ($query) use ($comp) {
                        if ($comp) {
                            return $query->where('fldcomp', 'LIKE', $comp);
                        }
                    })*/
                    ->where(function ($query) use ($consultant) {
                        if ($consultant) {
                            return $query->where('flduserid', $consultant);
                        }
                    })
                    ->where('fldstatus', '<>', 'Cancelled')
                    ->whereHas('encounter', function ($query) use ($patientInfoData) {
                        $query->whereIn('fldpatientval', $patientInfoData);
                    })
                    ->get();
            } else {
                if ($gender) {
                    $patientInfoData = Encounter::select('fldpatientval')
                        ->whereHas('allConsultant', static function ($query) use ($date_from, $date_to) {
                            if ($date_from) {
                                $query->where('fldconsulttime', '>=', $date_from);
                            }
                            if ($date_to) {
                                $query->where('fldconsulttime', '<=', $date_to);
                            }
                        })
                        ->whereHas('patientInfo', function ($query) use ($gender,$district,$province) {
                            return $query->where('fldptsex', 'LIKE', $gender)
                                        ->where('fldptadddist', 'LIKE', $district)
                                        ->where('fldprovince', 'LIKE', $province);
                        })
                        ->pluck('fldpatientval');
                } else {
                    $patientInfoData = Encounter::select('fldpatientval')
                        ->whereHas('allConsultant', static function ($query) use ($date_from, $date_to) {
                            if ($date_from) {
                                $query->where('fldconsulttime', '>=', $date_from);
                            }
                            if ($date_to) {
                                $query->where('fldconsulttime', '<=', $date_to);
                            }
                        })
                        ->whereHas('patientInfo', function ($query) use ($district,$province) {
                            return $query->where('fldptadddist', 'LIKE', $district)
                                        ->where('fldprovince', 'LIKE', $province);
                        })
                        ->pluck('fldpatientval');
                }


                $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'flduserid', 'fldstatus', 'fldconsultname')
                    ->where(function ($query) use ($date_from) {
                        if ($date_from) {
                            return $query->where('fldconsulttime', '>=', $date_from);
                        }
                    })
                    ->where(function ($query) use ($date_to) {
                        if ($date_to) {
                            return $query->where('fldconsulttime', '<=', $date_to);
                        }
                    })
                    ->where(function ($query) use ($department) {
                        if ($department) {
                            return $query->where('fldconsultname', 'LIKE', $department);
                        }
                    })
                    ->where(function ($query) use ($mode) {
                        if ($mode) {
                            return $query->where('fldbillingmode', 'LIKE', $mode);
                        }
                    })
                    /*->where(function ($query) use ($comp) {
                        if ($comp) {
                            return $query->where('fldcomp', 'LIKE', $comp);
                        }
                    })*/
                    ->where(function ($query) use ($consultant) {
                        if ($consultant) {
                            return $query->where('flduserid', 'LIKE', $consultant);
                        }
                    })
                    ->where('fldstatus', '<>', 'Cancelled')
                    ->whereHas('encounter', function ($query) use ($patientInfoData) {
                        $query->whereIn('fldpatientval', $patientInfoData);
                    });
            }

            if($request->has('typePdf')){
                $result = $result->get();
            }else{
                $result = $result->paginate(25);
            }

            if (is_countable($result) && count($result) == 0) {
                return "No data available";
            }

            if (isset($result) and !empty($result)) {
                foreach ($result as $k => $data) {
                    if ($data) {
                        $count = $k + 1;
                        $encounter = Encounter::select('fldpatientval', 'fldrank')->where('fldencounterval', $data->fldencounterval)->first();
                        $patient = PatientInfo::select('fldptnamefir', 'fldptnamelast', 'fldptsex', 'fldptbirday', 'fldpatientval', 'fldmidname', 'fldrank')->where('fldpatientval', $encounter->fldpatientval)->first();
                        $years = $patient->fldagestyle;
                        // $years = Carbon::parse($patient->fldptbirday)->age;
                        $html .= '<tr>';
                        $html .= '<td>' . $count . '</td>';
                        $html .= '<td>' . $data->fldencounterval . '</td>';
                        $user_rank = ((Options::get('system_patient_rank') == 1) && isset($encounter) && isset($encounter->fldrank)) ? $encounter->fldrank : '';
                        $html .= '<td>' . $user_rank . ' ' . $patient->fldptnamefir . ' ' . $patient->fldmidname . ' ' . $patient->fldptnamelast . '</td>';
                        $html .= '<td>' . $years . '</td>';
                        $html .= '<td>' . $patient->fldptsex . '</td>';
                        $html .= '<td>' . $encounter->fldpatientval . '</td>';
                        $html .= '<td>' . $data->fldconsulttime . '</td>';
                        $html .= '<td>' . $data->fldconsultname . '</td>';
                        $html .= '<td>' . $data->flduserid . '</td>';
                        if(!$request->has('typePdf')){
                            $html .= '<td><a href="javascript:void(0);" onclick="displayPatientImage(' . $encounter->fldpatientval . ')" title="Patient Image"><i class="fas fa-image"></i></a> | <a href="javascript:void(0);" onclick="lastEncounter(' . $encounter->fldpatientval . ')" title="Last Encounter"><i class="far fa-file-alt"></i></a> | <a href="javascript:void(0);" onclick="lastAllEncounter(' . $encounter->fldpatientval . ')" title="All Encounter"><i class="fas fa-book"></i></a></td>';
                        }
                        $html .= '</tr>';
                    }
                }
                if(!$request->has('typePdf')){
                    $html .= '<tr><td colspan="20">' . $result->appends(request()->all())->links() . '</td></tr>';
                }
            }

            if($request->has('typePdf')){
                $data = [];
                $data['html'] = $html;
                $data['from_date'] = $date_from;
                $data['to_date'] = $date_to;
                $data['certificate'] = "CONSULTATION REPORT";
                return view('servicedata::pdf.consultation', $data);
            }else{
                return $html;
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            echo "Something went wrong.";
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function generatepdf(Request $request)
    {
        $date_from = $request->date_from . ' 00:00:00';
        $date_to = $request->date_to . ' 23:59:59';
        $department = $request->department;
        $mode = $request->mode;
        $comp = $request->comp;
        $gender = $request->gender;
        $district = $request->district;
        $type = $request->type;
        $freetext = $request->freetext;
        $agefrom = $request->age_from;
        $ageto = $request->age_to;
        $consultant = $request->consult;
        // echo $consult; exit;
        $html = '';
        try {
            if ($type == 'Surname' or $type == 'Ethnic Group') {
                $ethnicgroup = EthnicGroup::select('flditemname')->where('fldgroupname', 'LIKE', $freetext)->get();
                $patientinfo = PatientInfo::select('fldpatientval')->whereIn('fldptnamelast', $ethnicgroup)->where('fldptsex', $gender)->where('fldptadddist', $district)->get();
                $encounter = Encounter::select('fldencounterval')->whereIn('fldpatientval', $patientinfo)->get();

                $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'fldconsultname', 'flduserid', 'fldstatus')->whereBetween('fldconsulttime', array($date_from, $date_to))->where('fldconsultname', 'LIKE', $department)->where('fldbillingmode', 'LIKE', $mode)/*->where('fldcomp', 'LIKE', $comp)*/ ->where('fldstatus', '!=', 'Cancelled')->where('flduserid', 'LIKE', $consultant)->whereIn('fldencounterval', $encounter)->get();
            } elseif ($type == 'Discount Type') {

                $patientinfo = PatientInfo::select('fldpatientval')->where('fldptsex', 'LIKE', $gender)->where('fldptadddist', $district)->get();
                $patientval = array();
                if (isset($patientinfo) and count($patientinfo) > 0) {
                    foreach ($patientinfo as $key => $value) {
                        $patientval[] = $value->fldpatientval;
                    }
                }
                // dd($patientval);
                $encounter = Encounter::select('fldencounterval')->whereIn('fldpatientval', $patientval)->where('flddisctype', $freetext)->get();
                // dd($encounter); exit;
                $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'fldconsultname', 'flduserid', 'fldstatus')->whereBetween('fldconsulttime', array($date_from, $date_to))->where('fldconsultname', 'LIKE', $department)->where('fldbillingmode', 'LIKE', $mode)/*->where('fldcomp', 'LIKE', $comp)*/ ->where('fldstatus', '!=', 'Cancelled')->where('flduserid', 'LIKE', $consultant)->whereIn('fldencounterval', $encounter)->get();
                // echo $result; exit;
            } elseif ($type == 'Visit Type') {

                $patientinfo = PatientInfo::select('fldpatientval')->where('fldptsex', 'LIKE', $gender)->where('fldptadddist', $district)->get();
                $encounter = Encounter::select('fldencounterval')->whereIn('fldpatientval', $patientinfo)->where('fldvisit', $freetext)->get();

                $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'fldconsultname', 'flduserid', 'fldstatus')->whereBetween('fldconsulttime', array($date_from, $date_to))->where('fldconsultname', 'LIKE', $department)->where('fldbillingmode', 'LIKE', $mode)/*->where('fldcomp', 'LIKE', $comp)*/ ->where('fldstatus', '!=', 'Cancelled')->where('flduserid', 'LIKE', $consultant)->whereIn('fldencounterval', $encounter)->toSql();
            } elseif ($type == 'Age') {
                $agefrom = $agefrom * 365;
                $ageto = $ageto * 365;

                $result = DB::select(
                    "select t.fldid,t.fldencounterval,t.fldconsulttime,t.flduserid,t.fldstatus, t.fldconsultname from tblconsult as t where t.fldconsulttime>='" . $date_from . "' and t.fldconsulttime<='" . $date_to . "' and t.fldconsultname like '" . $department . "' and t.fldbillingmode like '" . $mode . "' and t.fldstatus<> 'Cancelled' and t.flduserid LIKE '" . $consultant . "' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptadddist like '" . $district . "' and p.fldptbirday like '%' and DATEDIFF(t.fldconsulttime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldconsulttime, p.fldptbirday)<" . $ageto . "))"
                );
            } else {
                $result = DB::select("select t.fldid,t.fldencounterval,t.fldconsulttime,t.flduserid,t.fldstatus, t.fldconsultname from tblconsult as t where t.fldconsulttime>='" . $date_from . "' and t.fldconsulttime<='" . $date_to . "' and t.fldconsultname like '" . $department . "' and t.fldbillingmode like '" . $mode . "' and t.fldstatus<> 'Cancelled' and t.flduserid LIKE '" . $consultant . "' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "'))
            ");

            }
            $data['result'] = $result;
            $data['total'] = count($result);
            $data['from_date'] = $date_from;
            $data['to_date'] = $date_to;
            return view('servicedata::pdf.consultation', $data)/*->setPaper('a4')->stream('consultation_report.pdf')*/ ;

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displaySeachEncForm()
    {
        $data['districts'] = Districts::all();
        $data['department'] = Helpers::getDepartmentByCategory('Consultation');
        $data['comp'] = Helpers::getCompName();
        $data['mode'] = BillingSet::all();
        // dd($data);
        return view('servicedata::search-encounter', $data);
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchPatient(Request $request)
    {
        // echo "kljdkj"; exit;
        $fileno = $request->fileno;
        $encounterID = $request->encounterID;
        $patientID = $request->patientno;
        $htmlview = '';
        $patientval = '';
        $encounterval = '';
        $regdate = '';
        $nepaliregdate = '';
        if (isset($fileno) && $fileno != '') {
            $patient = PatientInfo::select('fldpatientval')->where('fldadmitfile', $fileno)->first();
            $encounter = Encounter::select('fldencounterval', 'fldpatientval', 'fldregdate', 'fldrank')->where('fldpatientval', $patient->fldpatientval)->orderBy('fldregdate', 'DESC')->get();

        } elseif (isset($encounterID) && $encounterID != '') {
            $encounter = Encounter::select('fldencounterval', 'fldpatientval', 'fldregdate', 'fldrank')->where('fldencounterval', $encounterID)->orderBy('fldregdate', 'DESC')->get();

        } elseif (isset($patientID) && $patientID != '') {
            $encounter = Encounter::select('fldencounterval', 'fldpatientval', 'fldregdate', 'fldrank')->where('fldpatientval', $patientID)->orderBy('fldregdate', 'DESC')->get();

        }

        // dd($encounter);
        if (isset($encounter) and count($encounter) > 0) {
            foreach ($encounter as $enc) {

                $htmlview .= "<a href='javascript:void(0);' onclick='encounterDetail(\"$enc->fldencounterval\")' class=''>$enc->fldencounterval</a><br/>";
            }

            $patientval = $encounter[0]->fldpatientval;
            $encounterval = $encounter[0]->fldencounterval;
            $regdate = date("Y-m-d", strtotime($encounter[0]->fldregdate));
            // echo $regdate; exit;
            $ndate = Helpers::dateEngToNepdash($regdate);
            $nepaliregdate = $ndate->year . '-' . $ndate->month . '-' . $ndate->date;
            // echo $nepaliregdate; exit;
        }
        // echo $html; exit;
        $data['html'] = $htmlview;
        $data['patientval'] = $patientval;
        $data['encounterval'] = $encounterval;
        $data['regdate'] = $regdate;
        $data['nepaliregdate'] = $nepaliregdate;

        return $data;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function encounterDetail(Request $request)
    {
        $encounter = $request->encounterID;
        $detail = Encounter::select('fldpatientval', 'fldencounterval', 'fldregdate', 'fldrank')->where('fldencounterval', $encounter)->first();
        // dd($encounter)
        $regdate = date("Y-m-d", strtotime($detail->fldregdate));
        // echo $regdate; exit;
        $ndate = Helpers::dateEngToNepdash($regdate);
        $nepaliregdate = $ndate->year . '-' . $ndate->month . '-' . $ndate->date;
        $data['regdate'] = $regdate;
        $data['nepaliregdate'] = $nepaliregdate;

        return $data;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displaySeachNameForm()
    {
        return view('servicedata::search-name');
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function searchPatientByName(Request $request)
    {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $html = '';
        $patient = PatientInfo::select('fldpatientval')->where('fldptnamefir', 'LIKE', '%' . $firstname . '%')->where('fldptnamelast', 'LIKE', '%' . $lastname . '%')->get();
        // dd($patient);
        $encounter = Encounter::select('fldencounterval')->whereIn('fldpatientval', $patient)->get();
        $result = Consult::select('fldid', 'fldencounterval', 'fldconsulttime', 'fldconsultname', 'flduserid', 'fldstatus')->whereIn('fldencounterval', $encounter)->get();

        if (isset($result) and !empty($result)) {
            foreach ($result as $k => $data) {
                $encounter = Encounter::select('fldpatientval', 'fldrank')->where('fldencounterval', $data->fldencounterval)->first();
                $patient = PatientInfo::select('fldptnamefir', 'fldptnamelast', 'fldptsex', 'fldptbirday', 'fldpatientval', 'fldmidname', 'fldrank')->where('fldpatientval', $encounter->fldpatientval)->first();
                $years = $patient->fldagestyle;
                // $years = Carbon::parse($patient->fldptbirday)->age;
                $html .= '<tr>';
                $html .= '<td>' . $k . '</td>';
                $html .= '<td>' . $data->fldencounterval . '</td>';
                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($encounter) && isset($encounter->fldrank)) ? $encounter->fldrank : '';
                $html .= '<td>' . $user_rank . ' ' . $patient->fldptnamefir . ' ' . $patient->fldmidname . ' ' . $patient->fldptnamelast . '</td>';
                $html .= '<td>' . $years . '</td>';
                $html .= '<td>' . $patient->fldptsex . '</td>';
                $html .= '<td>' . $encounter->fldpatientval . '</td>';
                $html .= '<td>' . $data->fldconsulttime . '</td>';
                $html .= '<td>' . $data->fldconsultname . '</td>';
                $html .= '<td></td>';
                $html .= '<td><a href="javascript:void(0);" onclick="displayPatientImage(' . $encounter->fldpatientval . ')"><i class="fas fa-eye"></i></a><a href="javascript:void(0);" onclick="lastEncounter(' . $encounter->fldpatientval . ')"><i class="fas fa-eye"></i></a><a href="javascript:void(0);" onclick="lastAllEncounter(' . $encounter->fldpatientval . ')"><i class="fas fa-eye"></i></a></td>';
                $html . '</tr>';
            }
        }
        echo $html;
        exit;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function displayPatientImage(Request $request)
    {

        $data['imagedata'] = PersonImage::where('fldname', $request->fldpatientval)->first();
        // dd($data);
        $html = view('servicedata::common.patient-image', $data)->render();
        return $html;

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function displayLastEncounter(Request $request)
    {

        $data['encounter'] = Encounter::where('fldpatientval', $request->fldpatientval)->first();
        // dd($data);
        $html = view('servicedata::common.last-encounter', $data)->render();
        return $html;

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function displayAllEncounter(Request $request)
    {
        $data['encounter'] = Encounter::where('fldpatientval', $request->fldpatientval)->get();
        // dd($data);
        $html = view('servicedata::common.all-encounter', $data)->render();
        return $html;

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

    public function getDeptWiseConsultant(Request $request){
        $deptid = $request->deptid;
        if($deptid){
            $user_ids = DB::table('department_users')->where('department_id',$deptid)->pluck('user_id')->toArray();
            $consultants = CogentUsers::whereIn('id', $user_ids)->get();
        }else{
            $consultants = CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
        }
        $options = "<option value='%'>%</option>";
        foreach($consultants as $consultant){
            $options .= "<option value='".$consultant->username."'>".$consultant->getFldtitlefullnameAttribute()."</option>";
        }
        return $options;
    }
}
