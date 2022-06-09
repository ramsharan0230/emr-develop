<?php

namespace App\Exports;

use App\Encounter;
use App\ExamGeneral;
use App\PatAccGeneral;
use App\PatDosing;
use App\PatFindings;
use App\PatientExam;
use App\PatLabTest;
use App\PatRadioTest;
use App\PatSubGeneral;
use App\PatTiming;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MedicalReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $category,string $finalfrom, string $finalto, string $item_name, string $diagnosis, string $gender, int $minAge, int $maxAge, string $time, string $proctype, string $procname, string $method)
    {
        $this->category = $category;
        $this->finalfrom = $finalfrom;
        $this->finalto = $finalto;
        $this->item_name = $item_name;
        $this->diagnosis = $diagnosis;
        $this->gender = $gender;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
        $this->time = $time;
        $this->proctype = $proctype;
        $this->procname = $procname;
        $this->method = $method;
    }

    public function drawings()
    {
        if(Options::get('brand_image')){
            if(file_exists(public_path('uploads/config/'.Options::get('brand_image')))){
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'');
                $drawing->setPath(public_path('uploads/config/'.Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
            }else{
                $drawing = [];
            }
        }else{
            $drawing = [];
        }
        return $drawing;
    }
    
    public function view(): View
    {
        $data['category'] = $category = $this->category;
        $data['finalfrom'] = $finalfrom = $this->finalfrom;
        $data['finalto'] = $finalto = $this->finalto;
        $data['item_name'] = $item_name = $this->item_name;
        $data['diagnosis'] = $diagnosis = $this->diagnosis;
        $data['gender'] = $gender = $this->gender;
        $data['minAge'] = $minAge = $this->minAge;
        $data['maxAge'] = $maxAge = $this->maxAge;
        $data['time'] = $time = $this->time;
        $data['proctype'] = $proctype = $this->proctype;
        $data['procname'] = $procname = $this->procname;
        $data['method'] = $method = $this->method;
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })            
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        ->where('tbldiagnogroup.fldgroupname',$item_name)
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                        })
                        ->get();
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
                    })
                    ->get();
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
                        })
                        ->get();
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
        return view('reports::medicalreport.medical-report-excel',$data);
    }

}
