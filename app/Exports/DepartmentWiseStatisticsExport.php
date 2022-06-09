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

class DepartmentWiseStatisticsExport implements FromView, WithDrawings, ShouldAutoSize
{
    protected $from_date;
    protected $to_date;
    protected $department;
    protected $consultant;
    protected $type;

    public function __construct($from_date = null,$to_date = null,$department = null,$consultant = null,$type = null)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->department = $department;
        $this->consultant = $consultant;
        $this->type = $type;
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
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $department = $this->department;
        $consultant = $this->consultant;
        $type = $this->type;
        
        $patients = Encounter::select(
            'tblconsult.fldencounterval',
            'tblconsult.fldconsultname',
            'tblconsult.flduserid',
            'tblconsult.fldconsulttime',
            DB::raw("count(DISTINCT(fldpatientval)) as patientcount"),
            DB::raw('DATE_FORMAT(tblconsult.fldconsulttime, "%Y-%m-%d") as formatted_date')
        )
            ->join('tblconsult', 'tblconsult.fldencounterval', '=', 'tblencounter.fldencounterval')
            ->groupBy('tblconsult.fldconsultname','formatted_date');

        if ($consultant) {
            $patients = $patients->where('tblconsult.flduserid', $consultant);
        }
        if ($from_date) {
            $patients = $patients->where('fldconsulttime', '>=', $from_date. " 00:00:00");
        } else {
            $patients = $patients->where('fldconsulttime', '>=', date('Y-m-d'). " 00:00:00");
        }
        if ($to_date) {
            $patients = $patients->where('fldconsulttime', '<=', $to_date. " 23:59:59");
        } else {
            $patients = $patients->where('fldconsulttime', '<=', date('Y-m-d'). " 23:59:59");
        }
        if ($department) {
            $patients = $patients->where('fldconsultname', $department);
        }
        if ($type) {
            if ($type == 'IP') {
                $patients = $patients->where(function ($query) {
                                $query->where('tblconsult.fldencounterval','LIKE',"%IP%")
                                ->orWhere('tblconsult.fldencounterval','LIKE',"%ER%"); 
                });                    
            } else {
                $patients = $patients->where('tblconsult.fldencounterval','LIKE',"%OP%");
            }
        }
        $patientList = $patients->get();
        $data['patients'] = $this->groupData($patientList);

        if ($type) {
            if ($type == 'IP') {
                $others_arr = DB::select(DB::raw("select count(fldencounterval) as patientcount,
                    DATE_FORMAT(fldregdate, '%Y-%m-%d') as formatted_date
                    from tblencounter where fldencounterval not in (select fldencounterval from tblconsult)
                    and cast(fldregdate as date) >= '$from_date' and cast(fldregdate as date) <= '$to_date'
                    and (fldencounterval like 'IP%' or fldencounterval like 'ER%')
                    group by fldregdate"));
                $others = collect($others_arr)->toArray();
                // dd($others);
                $data['others'] = $this->groupotherData($others);                   
            } else {
                $others_arr = DB::select(DB::raw("select count(fldencounterval) as patientcount,
                    DATE_FORMAT(fldregdate, '%Y-%m-%d') as formatted_date
                    from tblencounter where fldencounterval not in (select fldencounterval from tblconsult)
                    and cast(fldregdate as date) >= '$from_date' and cast(fldregdate as date) <= '$to_date'
                    and fldencounterval like 'OP%'
                    group by fldregdate"));
                $others = collect($others_arr)->toArray();
                $data['others'] = $this->groupotherData($others);
            }
        } else {
            $others_arr = DB::select(DB::raw("select count(fldencounterval) as patientcount,
                DATE_FORMAT(fldregdate, '%Y-%m-%d') as formatted_date
                from tblencounter where fldencounterval not in (select fldencounterval from tblconsult)
                and cast(fldregdate as date) >= '$from_date' and cast(fldregdate as date) <= '$to_date'
                group by fldregdate"));
            $others = collect($others_arr)->toArray();
            $data['others'] = $this->groupotherData($others);
        }

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['certificate'] = "Department Wise Statistics";
        
        return view('docdepartmentwisestatistics::excel', $data);
    }

    public function groupData($data = [])
    {
        $groupedData = [];
        foreach ($data as $d) {
            $groupedData[$d->fldconsultname]['name'] = $d->fldconsultname;
            $groupedData[$d->fldconsultname]['dates'][$d->formatted_date] = $d->patientcount;
        }
        return array_values($groupedData);
    }

    public function groupotherData($data = [])
    {
        $groupotherData = [];
        foreach ($data as $d) {
            $groupotherData[$d->formatted_date] = $d->patientcount;
        }
        return $groupotherData;
    }
}
