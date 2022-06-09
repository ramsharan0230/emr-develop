<?php

namespace Modules\DocDepartmentWiseStatistics\Http\Controllers;

use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Consult;
use App\Encounter;
use App\Exports\DepartmentWiseStatisticsExport;
use Maatwebsite\Excel\Facades\Excel;

class DocDepartmentWiseStatisticsController extends Controller
{
    public function dynamicReport()
    {
        $data['departments'] = Helpers::getDepartments();
        $data['consultantList'] = Helpers::getConsultantList();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['html'] = '';
        return view('docdepartmentwisestatistics::index', $data);
    }

    public function filterReport(Request $request)
    {
        try {
            
            $data['from_date'] = Helpers::dateNepToEng($request->from_date)->full_date;
            // $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $data['to_date'] = Helpers::dateNepToEng($request->to_date)->full_date;
            // $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $department = $request->get('department');
            $consultant = $request->get('consultant');
            $from_date = $request->get('from_date');
            $from_date = Helpers::dateNepToEng($from_date)->full_date;
            $to_date = $request->get('to_date');
            $to_date = Helpers::dateNepToEng($to_date)->full_date;
            
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
            
            if ($request->get('consultant')) {
                $patients = $patients->where('tblconsult.flduserid', $request->get('consultant'));
            }
            if ($request->get('from_date')) {
                $patients = $patients->where('fldconsulttime', '>=', $from_date. " 00:00:00");
            } else {
                $patients = $patients->where('fldconsulttime', '>=', date('Y-m-d'). " 00:00:00");
            }
            if ($request->get('to_date')) {
                $patients = $patients->where('fldconsulttime', '<=', $to_date. " 23:59:59");
            } else {
                $patients = $patients->where('fldconsulttime', '<=', date('Y-m-d'). " 23:59:59");
            }
            if ($request->get('department')) {
                $patients = $patients->where('fldconsultname', $request->get('department'));
            }
            if ($request->get('type')) {
                if ($request->get('type') == 'IP') {
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
            
            if ($request->get('type')) {
                if ($request->get('type') == 'IP') {
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

            $html = view('docdepartmentwisestatistics::dynamic-statistics', $data)->render();
            return response([
                'html' => $html,
                'status' => true
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => false
            ]);
        }
    }

    public function dynamicReportPdf(Request $request)
    {
        try {
            
            $data['from_date'] = Helpers::dateNepToEng($request->from_date)->full_date;
            // $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $data['to_date'] = Helpers::dateNepToEng($request->to_date)->full_date;
            // $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $department = $request->get('department');
            $consultant = $request->get('consultant');
            
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

            if ($request->get('consultant')) {
                $patients = $patients->where('tblconsult.flduserid', $request->get('consultant'));
            }
            if ($request->get('from_date')) {
                $from_date = $request->get('from_date');
                $from_date = Helpers::dateNepToEng($from_date)->full_date;
                $patients = $patients->where('fldconsulttime', '>=', $from_date. " 00:00:00");
            } else {
                $patients = $patients->where('fldconsulttime', '>=', date('Y-m-d'). " 00:00:00");
            }
            if ($request->get('to_date')) {
                $to_date = $request->get('to_date');
                $to_date = Helpers::dateNepToEng($to_date)->full_date;
                $patients = $patients->where('fldconsulttime', '<=', $to_date. " 23:59:59");
            } else {
                $patients = $patients->where('fldconsulttime', '<=', date('Y-m-d'). " 23:59:59");
            }
            if ($request->get('department')) {
                $patients = $patients->where('fldconsultname', $request->get('department'));
            }
            if ($request->get('type')) {
                if ($request->get('type') == 'IP') {
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
            
            if ($request->get('type')) {
                if ($request->get('type') == 'IP') {
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
            // dd($data['patients']);
            // $html = view('docdepartmentwisestatistics::dynamic-statistics', $data)->render();
            return view('docdepartmentwisestatistics::pdf', $data);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function excelReport(Request $request)
    {
        $from_date = Helpers::dateNepToEng($request->from_date)->full_date;
        $to_date = Helpers::dateNepToEng($request->to_date)->full_date;
        $department = $request->get('department');
        $consultant = $request->get('consultant');
        $type = $request->get('type');
        $export = new DepartmentWiseStatisticsExport($from_date, $to_date, $department, $consultant, $type);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'DepartmentWiseStatisticsExport.xlsx');
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
