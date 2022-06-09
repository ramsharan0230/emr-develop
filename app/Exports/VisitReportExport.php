<?php

namespace App\Exports;

use App\BillingSet;
use App\Encounter;
use App\EthnicGroup;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class VisitReportExport implements FromView, WithDrawings, ShouldAutoSize
{
    protected $encounter_id;
    protected $comp;
    protected $department;
    protected $province;
    protected $district;
    protected $freetext;
    protected $to_date;
    protected $from_date;
    protected $gender;
    protected $last_status;
    protected $mode;
    protected $type;
    protected $age_from;
    protected $age_to;
    protected $noofdays;

    public function __construct($encounter_id = null,$comp = null,$department = null,$province = null,$district = null,$freetext = null,$to_date = null,$from_date = null,$gender = null,$last_status = null,$mode = null,$type = null,$age_from = null,$age_to = null,$noofdays = null)
    {
        $this->encounter_id = $encounter_id;
        $this->comp = $comp;
        $this->department = $department;
        $this->province = $province;
        $this->district = $district;
        $this->freetext = $freetext;
        $this->to_date = $to_date;
        $this->from_date = $from_date;
        $this->gender = $gender;
        $this->last_status = $last_status;
        $this->mode = $mode;
        $this->type = $type;
        $this->age_from = $age_from;
        $this->age_to = $age_to;
        $this->noofdays = $noofdays;
    }

    public function drawings()
    {
        if (Options::get('brand_image')) {
            if (file_exists(public_path('uploads/config/' . Options::get('brand_image')))) {
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan']) ? Options::get('siteconfig')['system_slogan'] : '');
                $drawing->setPath(public_path('uploads/config/' . Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('A2');
            } else {
                $drawing = [];
            }
        } else {
            $drawing = [];
        }
        return $drawing;
    }

    public function view(): View
    {
        $encounter_id = $this->encounter_id;
        $comp = $this->comp;
        $department = $this->department;
        $from_date = Carbon::parse($this->from_date)->setTime(00, 00, 00);
        $to_date = Carbon::parse($this->to_date)->setTime(23, 59, 59);
        $last_status = $this->last_status;
        $mode = $this->mode;
        $age_from = $this->age_from * 365;
        $age_to = $this->age_to * 365;
        $type = $this->type;
        $freetext = $this->freetext;
        $gender = $this->gender;
        $district = $this->district;
        $province = $this->province;
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

            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['last_status'] = $last_status;
            $data['resultArray'] = $resultData->with(['patientInfo'])->get();
            $data['certificate'] = "PATIENT VISIT REPORT";
            return view('consultpatientdata::pdf.visit-report-excel', $data);
    }
}
