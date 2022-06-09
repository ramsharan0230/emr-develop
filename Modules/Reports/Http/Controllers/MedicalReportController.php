<?php

namespace Modules\Reports\Http\Controllers;

use App\ExamGeneral;
use App\Exports\MedicalReportExport;
use App\PatAccGeneral;
use App\PatDosing;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\PatFindings;
use App\PatGeneral;
use App\PatientExam;
use App\PatLabTest;
use App\PatRadioTest;
use App\PatSubGeneral;
use App\PatTiming;
use App\Utils\Options;
use DB;
use Excel;

class MedicalReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year.'-'.$datevalue->month.'-'.$datevalue->date;
        $data['diagnosisDatas'] = PatFindings::select('fldcodeid','fldcode')
                                            ->wherein('fldtype',['Final Diagnosis','Provisional Diagnosis'])
                                            ->where('fldsave',1)
                                            ->distinct('fldcodeid')
                                            ->get();
        $data['procNames'] = PatGeneral::select('flditem')->where('fldinput','Procedures')->groupBy('flditem')->get();
        return view('reports::medicalreport.medical-report',$data);
    }

    public function loadData(Request $request)
    {
        try{
            $category = $request->category;
            $result = [];
            if($category == "Patient Demographics"){
                $result = DB::table('tblpataccgeneral')->select('flditem as item')
                                                    ->where('fldinput','Demographics')
                                                    ->distinct('flditem')
                                                    ->get();
            }elseif($category == "Clinical Demographics"){
                $result = DB::table('tblexamgeneral')->select('flditem as item')
                                                ->where('fldinput','Demographics')
                                                ->distinct('flditem')
                                                ->get();
            }elseif($category == "Presenting Complaints" || $category == "Patient Symptoms"){
                $result = DB::table('tblexamgeneral')->select('flditem as item')
                                                ->where('fldinput',$category)
                                                ->where('fldsave',1)
                                                ->distinct('flditem')
                                                ->get();
            }elseif($category == "Provisional Diagnosis" || $category == "Final Diagnosis"){
                $result = DB::table('tblpatfindings')->select('fldcodeid as itemValue','fldcode as item')
                                                ->where('fldtype',$category)
                                                ->where('fldsave',1)
                                                ->distinct('fldcodeid')
                                                ->get();
            }elseif($category == "Disease Surveillance"){
                $result = DB::table('tblsurveillance')->select('flddisease as item')
                                                ->distinct('flddisease')
                                                ->get();
            }elseif($category == "Prov Diagnosis Groups" || $category == "Final Diagnosis Groups"){
                $result = DB::table('tbldiagnogroup')->select('fldgroupname as item')
                                                ->distinct('fldgroupname')
                                                ->get();
            }elseif($category == "Examination"){
                $result = DB::table('tblpatientexam')->select('fldhead as item')
                                                ->where('fldsave',1)
                                                ->distinct('fldhead')
                                                ->get();
            }elseif($category == "Diagnostic Tests"){
                $result = DB::table('tblpatlabtest')->select('fldtestid as item')
                                                ->whereIn('fldstatus',['Reported','Verified'])
                                                ->distinct('fldtestid')
                                                ->get();
            }elseif($category == "Radio Diagnostics"){
                $result = DB::table('tblpatradiotest')->select('fldtestid as item')
                                                ->whereIn('fldstatus',['Reported','Verified'])
                                                ->distinct('fldtestid')
                                                ->get();
            }elseif($category == "Allergic Drugs"){
                $result = DB::table('tblpatfindings')->select('fldcode as item')
                                                ->where('fldtype','Allergic Drugs')
                                                ->where('fldsave',1)
                                                ->distinct('fldcode')
                                                ->get();
            }elseif($category == "Narcotic Drugs"){
                $result = DB::table('tblpatdosing')->select('tblmedbrand.fldbrandid as item')
                                                ->leftJoin('tblmedbrand','tblmedbrand.fldbrandid','=','tblpatdosing.flditem')
                                                ->where('tblmedbrand.fldnarcotic','Yes')
                                                ->distinct('flditem')
                                                ->get();
            }elseif($category == "Prescribed Drugs"){
                $result = DB::table('tbldrug')->select('tblpatdosing.flditem as item')
                                                ->leftJoin('tblmedbrand','tblmedbrand.flddrug','=','tbldrug.flddrug')
                                                ->leftJoin('tblpatdosing','tblpatdosing.flditem','=','tblmedbrand.fldbrandid')
                                                ->where('tblpatdosing.fldsave_order',1)
                                                ->distinct('fldcodename')
                                                ->get();
            }elseif($category == "Major Procedures"){
                $result = DB::table('tblpatsubgeneral')->select('tblpatgeneral.fldid as item')
                                                ->leftJoin('tblpatgeneral','tblpatgeneral.fldid','=','tblpatsubgeneral.flditemid')
                                                ->where('tblpatsubgeneral.fldchapter','Components')
                                                ->where('tblpatgeneral.fldinput','Procedures')
                                                ->where('tblpatgeneral.fldreportquali','Done')
                                                ->distinct('fldreportquali')
                                                ->get();
            }elseif($category == "Equipment"){
                $result = DB::table('tblpattiming')->select('flditem as item')
                                                ->where('fldtype','Equipment')
                                                ->where('fldsecondsave',1)
                                                ->distinct('flditem')
                                                ->get();
            }elseif($category == "Obstetrics"){
                $result = DB::table('tblexamgeneral')->select('flditem as item')
                                                ->where('fldtype','Obstetrics')
                                                ->distinct('flditem')
                                                ->get();
            }
            $html = "";
            foreach($result as $res){
                $itemvalue = (isset($res->itemValue)) ? $res->itemValue : $res->item;
                $html .= '<tr>
                            <td class="item-td" data-item="'.$itemvalue.'"><i class="fas fa-angle-right mr-2"></i>'.$res->item.'</td>
                        </tr>';
            }
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        }catch(\Exception $e){
            dd($e);
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function selectItem(Request $request){
        try{
            $selectedItem = $request->selectedItem;
            $category = $request->category;
            $result = [];
            if($category == "Examination"){
                $type = DB::table('tblexam')->select('fldtype')
                        ->where('fldexamid',$selectedItem)
                        ->first();
                if(isset($type)){
                    if($type->fldtype == "Qualitative"){
                        $result = DB::table('tblexamlimit')->select('fldmethod as option')
                                    ->where('fldexamid',$selectedItem)
                                    ->distinct('fldmethod')
                                    ->get();
                    }   
                }
            }elseif($category == "Diagnostic Tests"){
                $type = DB::table('tbltest')->select('fldtype')
                        ->where('fldtestid',$selectedItem)
                        ->first();
                if(isset($type)){
                    if($type->fldtype == "Qualitative"){
                        $result = DB::table('tbltestlimit')->select('fldmethod as option')
                                    ->where('fldtestid',$selectedItem)
                                    ->distinct('fldmethod')
                                    ->get();
                    }
                }
            }elseif($category == "Radio Diagnostics"){
                $type = DB::table('tblradio')->select('fldtype')
                        ->where('fldexamid',$selectedItem)
                        ->first();
                if(isset($type)){
                    if($type->fldtype == "Qualitative"){
                        $result = DB::table('tblradiolimit')->select('fldmethod as option')
                                    ->where('fldexamid',$selectedItem)
                                    ->distinct('fldmethod')
                                    ->get();
                    }
                }
            }elseif($category == "Clinical Demographics"){
                $type = DB::table('tbldemographic')->select('fldoption')
                        ->where('flddemoid',$selectedItem)
                        ->first();
                if(isset($type)){
                    $result = DB::table('tbldemogoption')->select('fldanswer as option')
                                ->where('flddemoid',$selectedItem)
                                ->where('fldanswertype',$type->fldoption)
                                ->get();
                }
            }elseif($category == "Patient Demographics"){
                $type = DB::table('tblaccdemograp')->select('fldoption')
                        ->where('flddemoid',$selectedItem)
                        ->first();
                if(isset($type)){
                    $result = DB::table('tblaccdemogoption')->select('fldanswer as option')
                                ->where('flddemoid',$selectedItem)
                                ->where('fldanswertype',$type->fldoption)
                                ->get();
                }
            }
            $options = "";
            foreach($result as $r){
                $options .= "<option value='".$r->option."'>".$r->option."</option>";
            }
            return response()->json([
                'data' => [
                    'status' => TRUE,
                    'options' => $options
                ]
            ]);
        }catch(\Exception $e){
            dd($e);
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function getRefreshData(Request $request){
        try{
            $data['category'] = $category = $request->category;
            $from_date = Helpers::dateNepToEng($request->from_date);
            $data['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $data['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $data['item_name'] = $item_name = $request->selectedItem;
            $data['diagnosis'] = $diagnosis = $request->diagnosis;
            $data['gender'] = $gender = $request->gender;
            $data['minAge'] = $minAge = ($request->minAge) * 365;
            $data['maxAge'] = $maxAge = ($request->maxAge) * 365;
            $data['time'] = $time = $request->time;
            $data['proctype'] = $proctype = $request->proctype;
            $data['procname'] = $procname = $request->procname;
            $data['method'] = $method = $request->method;
            $data['isExport'] = $isExport = ($request->has('isExport')) ? true : false;
            $result = [];
            if($category == "Patient Demographics"){
                $result = PatAccGeneral::select('tblpataccgeneral.fldid as index','tblpataccgeneral.fldtime as date','tblpataccgeneral.fldencounterval as encounter','tblpataccgeneral.fldreportquali as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpataccgeneral.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpataccgeneral.fldencounterval')
                            ->where('tblpataccgeneral.fldtime','>=',$finalfrom)
                            ->where('tblpataccgeneral.fldtime','<=',$finalto)
                            ->where('tblpataccgeneral.fldinput','Demographics')
                            ->where('tblpataccgeneral.flditem',$item_name)
                            ->when($method != "", function ($q) use ($method){
                                return $q->where('tblpataccgeneral.fldreportquali','like',$method);
                            })
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Clinical Demographics"){
                $result = ExamGeneral::select('tblexamgeneral.fldid as index','tblexamgeneral.fldtime as date','tblexamgeneral.fldencounterval as encounter','tblexamgeneral.fldreportquali as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblexamgeneral.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblexamgeneral.fldencounterval')
                            ->where('tblexamgeneral.fldtime','>=',$finalfrom)
                            ->where('tblexamgeneral.fldtime','<=',$finalto)
                            ->where('tblexamgeneral.fldinput','Demographics')
                            ->where('tblexamgeneral.flditem',$item_name)
                            ->when($method != "", function ($q) use ($method){
                                return $q->where('tblexamgeneral.fldreportquali','like',$method);
                            })
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Presenting Complaints"){
                $result = ExamGeneral::select('tblexamgeneral.fldid as index','tblexamgeneral.fldtime as date','tblexamgeneral.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblexamgeneral.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblexamgeneral.fldencounterval','=','tblpatfindings.fldencounterval')
                            ->where('tblexamgeneral.fldtime','>=',$finalfrom)
                            ->where('tblexamgeneral.fldtime','<=',$finalto)
                            ->where('tblexamgeneral.fldinput','Presenting Symptoms')
                            ->where('tblexamgeneral.fldsave',1)
                            ->where('tblexamgeneral.flditem',$item_name)
                            ->where('tblpatientinfo.fldptsex','like',$gender)            
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                return $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblexamgeneral.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblexamgeneral.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Patient Symptoms"){
                $result = ExamGeneral::select('tblexamgeneral.fldid as index','tblexamgeneral.fldtime as date','tblexamgeneral.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblexamgeneral.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblexamgeneral.fldencounterval')
                            ->where('tblexamgeneral.fldtime','>=',$finalfrom)
                            ->where('tblexamgeneral.fldtime','<=',$finalto)
                            ->where('tblexamgeneral.fldinput','Patient Symptoms')
                            ->where('tblexamgeneral.flditem',$item_name)
                            ->when($method != "", function ($q) use ($method){
                                return $q->where('tblexamgeneral.fldreportquali','like',$method);
                            })
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                return $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });            
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Provisional Diagnosis"){
                $result = PatFindings::select('tblpatfindings.fldid as index','tblpatfindings.fldtime as date','tblpatfindings.fldencounterval as encounter','tblpatfindings.fldcode as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatfindings.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->where('tblpatfindings.fldtime','>=',$finalfrom)
                            ->where('tblpatfindings.fldtime','<=',$finalto)
                            ->where('tblpatfindings.fldtype','Provisional Diagnosis')
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)            
                            ->where('tblpatfindings.fldcodeid','like',$item_name)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });    
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Final Diagnosis"){
                $result = PatFindings::select('tblpatfindings.fldid as index','tblpatfindings.fldtime as date','tblpatfindings.fldencounterval as encounter','tblpatfindings.fldcode as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatfindings.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->where('tblpatfindings.fldtime','>=',$finalfrom)
                            ->where('tblpatfindings.fldtime','<=',$finalto)
                            ->where('tblpatfindings.fldtype','Final Diagnosis')
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)            
                            ->where('tblpatfindings.fldcodeid','like',$item_name)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Disease Surveillance"){
                $result = PatFindings::select('tblpatfindings.fldid as index','tblpatfindings.fldtime as date','tblpatfindings.fldencounterval as encounter','tblpatfindings.fldcode as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatfindings.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->where('tblpatfindings.fldtime','>=',$finalfrom)
                            ->where('tblpatfindings.fldtime','<=',$finalto)
                            ->whereIn('tblpatfindings.fldtype',['Provisional Diagnosis','Final Diagnosis'])
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime_sample,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime_sample,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Prov Diagnosis Groups"){
                $result = PatFindings::select('tblpatfindings.fldid as index','tblpatfindings.fldtime as date','tblpatfindings.fldencounterval as encounter','tblpatfindings.fldcode as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatfindings.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tbldiagnogroup','tblpatfindings.fldcodeid','=','tbldiagnogroup.fldcodeid')
                            ->where('tblpatfindings.fldtime','>=',$finalfrom)
                            ->where('tblpatfindings.fldtime','<=',$finalto)
                            ->where('tblpatfindings.fldtype','Provisional Diagnosis')
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            })            
                            ->where('tbldiagnogroup.fldgroupname',$item_name);
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Final Diagnosis Groups"){
                $result = PatFindings::select('tblpatfindings.fldid as index','tblpatfindings.fldtime as date','tblpatfindings.fldencounterval as encounter','tblpatfindings.fldcode as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatfindings.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tbldiagnogroup','tblpatfindings.fldcodeid','=','tbldiagnogroup.fldcodeid')
                            ->where('tblpatfindings.fldtime','>=',$finalfrom)
                            ->where('tblpatfindings.fldtime','<=',$finalto)
                            ->where('tblpatfindings.fldtype','Final Diagnosis')
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)            
                            ->where('tbldiagnogroup.fldgroupname',$item_name)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Examination"){
                $result = PatientExam::select('tblpatientexam.fldid as index','tblpatientexam.fldtime as date','tblpatientexam.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatientexam.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpatientexam.fldencounterval')
                            ->join('tblconfinement','tblconfinement.fldencounterval','=','tblpatientexam.fldencounterval')
                            ->join('tblpatgeneral','tblpatgeneral.fldencounterval','=','tblpatientexam.fldencounterval')
                            ->when($time == "AnyTime", function ($q) use ($finalfrom,$finalto){
                                return $q->where('tblpatientexam.fldtime','>=',$finalfrom)
                                        ->where('tblpatientexam.fldtime','<=',$finalto);
                            })
                            ->when($time != "AnyTime" && $time != "", function ($q) use ($finalfrom,$finalto,$time){
                                $raw = ($time == "Before") ? 'tblconfinement.flddeltime>tblpatientexam.fldtime' : "tblconfinement.flddeltime<tblpatientexam.fldtime";
                                return $q->whereRaw($raw)
                                        ->where('tblpatientexam.fldtime','>=',$finalfrom)
                                        ->where('tblpatientexam.fldtime','<=',$finalto);
                            })
                            ->when($proctype == "Procedure", function ($q) use ($procname){
                                return $q->where('tblpatgeneral.fldinput','Procedures')
                                        ->when($procname != "", function ($qr) use ($procname){
                                            $qr->where('tblpatgeneral.flditem','like',$procname);
                                        });
                            })
                            ->where('tblpatientexam.fldhead',$item_name)
                            ->when($method != "", function ($q) use ($method){
                                $q->where('tblpatientexam.fldmethod','like',$method);
                            })
                            ->where('tblpatientexam.fldsave',1)
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)            
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatientexam.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatientexam.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Diagnostic Tests"){
                $result = PatLabTest::select('tblpatlabtest.fldid as index','tblpatlabtest.fldtime_sample as date','tblpatlabtest.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatlabtest.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpatlabtest.fldencounterval')
                            ->join('tblconfinement','tblconfinement.fldencounterval','=','tblpatlabtest.fldencounterval')
                            ->join('tblpatgeneral','tblpatgeneral.fldencounterval','=','tblpatlabtest.fldencounterval')
                            ->when($time == "AnyTime", function ($q) use ($finalfrom,$finalto){
                                return $q->where('tblpatlabtest.fldtime_sample','>=',$finalfrom)
                                        ->where('tblpatlabtest.fldtime_sample','<=',$finalto);
                            })
                            ->when($time != "AnyTime" && $time != "", function ($q) use ($finalfrom,$finalto,$time){
                                $raw = ($time == "Before") ? 'tblconfinement.flddeltime>tblpatlabtest.fldtime_sample' : "tblconfinement.flddeltime<tblpatlabtest.fldtime_sample";
                                return $q->whereRaw($raw)
                                        ->where('tblpatlabtest.fldtime_sample','>=',$finalfrom)
                                        ->where('tblpatlabtest.fldtime_sample','<=',$finalto);
                            })
                            ->when($proctype == "Procedure", function ($q) use ($procname){
                                return $q->where('tblpatgeneral.fldinput','Procedures')
                                        ->when($procname != "", function ($qr) use ($procname){
                                            $qr->where('tblpatgeneral.flditem','like',$procname);
                                        });
                            })
                            ->whereIn('tblpatlabtest.fldstatus',['Reported','Verified'])
                            ->where('tblpatlabtest.fldtestid',$item_name)
                            ->when($method != "", function ($q) use ($method){
                                $q->where('tblpatlabtest.fldmethod','like',$method);
                            })
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatlabtest.fldtime_sample')
                            ->where('tblpatientinfo.fldptsex','like',$gender)            
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatlabtest.fldtime_sample,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatlabtest.fldtime_sample,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Radio Diagnostics"){
                $result = PatRadioTest::select('tblpatradiotest.fldid as index','tblpatradiotest.fldtime_report as date','tblpatradiotest.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatradiotest.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpatradiotest.fldencounterval')
                            ->where('tblpatradiotest.fldtime_sample','>=',$finalfrom)
                            ->where('tblpatradiotest.fldtime_sample','<=',$finalto)
                            ->whereIn('tblpatradiotest.fldstatus',['Reported','Verified'])
                            ->where('tblpatradiotest.fldtestid',$item_name)
                            ->when($method != "", function ($q) use ($method){
                                $q->where('tblpatradiotest','like',$method);
                            })
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)   
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatlabtest.fldtime_sample,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatlabtest.fldtime_sample,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Allergic Drugs"){
                $result = PatFindings::select('tblpatfindings.fldid as index','tblpatfindings.fldtime as date','tblpatfindings.fldencounterval as encounter','tblpatfindings.fldcode as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatfindings.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->where('tblpatfindings.fldtime','>=',$finalfrom)
                            ->where('tblpatfindings.fldtime','<=',$finalto)
                            ->where('tblpatfindings.fldtype','Allergic Drugs')
                            ->where('tblpatfindings.fldcode',$item_name)
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime_sample,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatfindings.fldtime_sample,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Narcotic Drugs"){
                $result = PatDosing::select('tblpatdosing.fldid as index','tblpatdosing.fldtime as date','tblpatdosing.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatdosing.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblmedbrand','tblmedbrand.fldbrandid','=','tblpatdosing.flditem')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpatdosing.fldencounterval')
                            ->where('tblpatdosing.fldtime','>=',$finalfrom)
                            ->where('tblpatdosing.fldtime','<=',$finalto)
                            ->where('tblpatdosing.flditem',$item_name)
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblmedbrand.fldnarcotic','Yes')
                            ->where('tblpatientinfo.fldptsex','like',$gender)    
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatdosing.fldtime_sample,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatdosing.fldtime_sample,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Prescribed Drugs"){
                $result = PatDosing::select('tblpatdosing.fldid as index','tblpatdosing.fldtime as date','tblpatdosing.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatdosing.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblmedbrand','tblmedbrand.fldbrandid','=','tblpatdosing.flditem')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpatdosing.fldencounterval')
                            ->join('tbldrug','tbldrug.flddrug','=','tblmedbrand.flddrug')
                            ->where('tblpatdosing.fldtime','>=',$finalfrom)
                            ->where('tblpatdosing.fldtime','<=',$finalto)
                            ->where('tblpatdosing.fldsave_order',1)
                            ->where('tbldrug.fldcodename',$item_name)
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)    
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpatdosing.fldtime_sample,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpatdosing.fldtime_sample,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Major Procedures"){
                $result = PatSubGeneral::select('tblpatsubgeneral.fldid as index','tblpatsubgeneral.fldtime as date','tblpatsubgeneral.fldencounterval as encounter','tblpatsubgeneral.fldreport as observation','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                            ->join('tblencounter','tblpatsubgeneral.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpatsubgeneral.fldencounterval')
                            ->join('tblpatgeneral','tblpatgeneral.fldid','=','tblpatsubgeneral.flditemid')
                            ->where('tblpatsubgeneral.fldchapter','Components')
                            ->where('tblpatsubgeneral.fldtime','>=',$finalfrom)
                            ->where('tblpatsubgeneral.fldtime','<=',$finalto)
                            ->where('tblpatgeneral.fldinput','Procedures')
                            ->where('tblpatgeneral.fldreportquali','Done')
                            ->where('tblpatsubgeneral.fldreportquali',$item_name)
                            ->when($method != "", function ($q) use ($method){
                                return $q->where('tblexamgeneral.fldreportquali','like',$method);
                            })
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                return $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Equipment"){
                $result = PatTiming::select('tblpattiming.fldid as index','tblpattiming.fldfirsttime as date','tblpattiming.fldencounterval as encounter','tblpatientinfo.fldpatientval as patientNo','tblpatientinfo.fldptnamefir as fname','tblpatientinfo.fldmidname as mname','tblpatientinfo.fldptnamelast as lname','tblpatientinfo.fldptsex as gender','tblpatientinfo.fldrank as rank','tblpatientinfo.fldptbirday as dob','tblencounter.fldregdate as regdate')
                        ->join('tblencounter','tblpattiming.fldencounterval','=','tblencounter.fldencounterval')
                        ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                        ->join('tblpatfindings','tblpatfindings.fldencounterval','=','tblpattiming.fldencounterval')
                        ->join('tblpatgeneral','tblpatgeneral.fldid','=','tblpatsubgeneral.flditemid')
                        ->where('tblpattiming.fldtype','Equipment')
                        ->where('tblpattiming.fldfirsttime','>=',$finalfrom)
                        ->where('tblpattiming.fldfirsttime','<=',$finalto)
                        ->where('tblpattiming.flditem',$item_name)
                        ->when($method != "", function ($q) use ($method){
                            return $q->where('tblexamgeneral.fldreportquali','like',$method);
                        })
                        ->when($diagnosis != "", function ($q) use ($diagnosis){
                            return $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                        })
                        ->where('tblpattiming.fldsecondsave',1)
                        ->where('tblpatfindings.fldsave',1)
                        ->where('tblpatientinfo.fldptsex','like',$gender)
                        ->when($minAge > 0, function ($q) use ($minAge){
                            $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) >= '.$minAge);
                        })
                        ->when($maxAge > 0, function ($q) use ($maxAge){
                            $q->whereRaw('DATEDIFF(tblpataccgeneral.fldtime,tblpatientinfo.fldptbirday) < '.$maxAge);         
                        });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }elseif($category == "Obstetrics"){
                $result = ExamGeneral::select('tblexamgeneral.fldid as index','tblexamgeneral.fldtime as date','tblexamgeneral.fldencounterval as encounter','tblexamgeneral.fldreportquali as observation')
                            ->join('tblencounter','tblexamgeneral.fldencounterval','=','tblencounter.fldencounterval')
                            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval')
                            ->where('tblexamgeneral.fldtime','>=',$finalfrom)
                            ->where('tblexamgeneral.fldtime','<=',$finalto)
                            ->where('tblexamgeneral.fldinput','Obstetrics')
                            ->where('tblexamgeneral.flditem',$item_name)
                            ->where('tblpatfindings.fldsave',1)
                            ->where('tblpatientinfo.fldptsex','like',$gender)    
                            ->when($diagnosis != "", function ($q) use ($diagnosis){
                                $q->where('tblpatfindings.fldcodeid','like',$diagnosis);
                            })
                            ->when($minAge > 0, function ($q) use ($minAge){
                                $q->whereRaw('DATEDIFF(tblexamgeneral.fldtime_sample,tblpatientinfo.fldptbirday) >= '.$minAge);
                            })
                            ->when($maxAge > 0, function ($q) use ($maxAge){
                                $q->whereRaw('DATEDIFF(tblexamgeneral.fldtime_sample,tblpatientinfo.fldptbirday) < '.$maxAge);         
                            });
                $result = ($isExport == true) ? $result->get() : $result->paginate(10);
            }
            $data['result'] = $result;
            $html = "";
            foreach($result as $r){
                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($r) && isset($r->rank)) ? $r->rank : '';
                $patient_name = $user_rank . ' ' . $r->fname . ' ' . $r->mname . ' ' . $r->lname;
                $age = \Carbon\Carbon::parse($r->dob)->diffInYears(\Carbon\Carbon::now());
                $html .= '<tr>
                            <td>'.$r->index.'</td>
                            <td>'.$r->date.'</td>
                            <td>'.$r->encounter.'</td>
                            <td>'.$patient_name.'</td>
                            <td>'.$age.'</td>
                            <td>'.$r->gender.'</td>
                            <td>'.$r->regdate.'</td>
                            <td>'.$r->patientNo.'</td>
                            <td></td>
                        </tr>';
            }
            $data['html'] = $html;
            if(!$isExport){
                $html .='<tr><td colspan="9">'.$result->appends(request()->all())->links().'</td></tr>';
                return response()->json([
                    'data' => [
                        'status' => true,
                        'html' => $html,
                    ]
                ]);
            }else{
                return view('reports::medicalreport.medical-report-pdf',$data);
            }
        }catch(\Exception $e){
            dd($e);
            if($request->has('isExport')){
                return redirect()->back();
            }else{
                return response()->json([
                    'data' => [
                        'status' => false
                    ]
                ]);
            }
        }
    }

    public function exportReport(Request $request){
        $data['category'] = $category = $request->category;
        $from_date = Helpers::dateNepToEng($request->from_date);
        $data['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
        $to_date = Helpers::dateNepToEng($request->to_date);
        $data['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
        $data['item_name'] = $item_name = $request->selectedItem;
        $data['diagnosis'] = $diagnosis = ($request->diagnosis != null) ? $request->diagnosis : "";
        $data['gender'] = $gender = $request->gender;
        $data['minAge'] = $minAge = ($request->minAge) * 365;
        $data['maxAge'] = $maxAge = ($request->maxAge) * 365;
        $data['time'] = $time = $request->time;
        $data['proctype'] = $proctype = ($request->proctype != null) ? $request->proctype : "";
        $data['procname'] = $procname = ($request->procname != null) ? $request->procname : "";
        $data['method'] = $method = ($request->method != null) ? $request->method : "";
        $export = new MedicalReportExport($category,$finalfrom,$finalto,$item_name,$diagnosis,$gender,$minAge,$maxAge,$time,$proctype,$procname,$method);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'MedicalReport.xlsx');
    }
}