<?php

namespace Modules\Department\Http\Controllers;

use App\Department;
use App\Exports\TopTenDeptExport;
use App\HospitalDepartment;
use App\PatBilling;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\PatLabTest;
use App\Utils\Helpers;
use DB;
use Excel;
use App\Exports\TopLabTestExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class TopTenDepartmentController extends Controller
{

    public function index(Request $request) {
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $top_ten_dept=collect();
        $patBilling=collect();
        if($eng_from_date){
            $patBilling = $this->patBillingQuery($eng_from_date,$eng_to_date);
            $top_ten_dept=$this->getDeptQuery($request,$patBilling);
		}
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('department::top-ten.index',compact('top_ten_dept','date'));
    }

    public function pdfTopTenDept(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $top_ten_dept=collect();
        if($eng_from_date){
            $patBilling = $this->patBillingQuery($eng_from_date,$eng_to_date);
            $top_ten_dept=$this->getDeptQuery($request,$patBilling);
            $encountertype ='OPDs';
            if(isset($request->encounter_type)){
                $encountertype ='IPDs';
            }
		}
        return view('department::top-ten.pdf.top-ten-dept-pdf',compact('top_ten_dept','userid','encountertype'));
    }

    public function exportTopTenDept(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $encounter_type=$request->encounter_type;
        $page=$request->page;

        $export = new TopTenDeptExport($eng_from_date,$eng_to_date,$encounter_type,$page);
        ob_end_clean();
        ob_start();

        return Excel::download($export, 'top-ten-dept-report.xlsx');
    }

    public function patBillingQuery($eng_from_date,$eng_to_date){
        return PatBilling::select(DB::raw("'Others' as name"),DB::raw('count(tblpatbilling.fldencounterval) as test_count'))
            ->distinct('fldencounterval')
            ->whereDate('fldtime','>=', $eng_from_date)->whereDate('fldtime','<=', $eng_to_date)
            ->whereNotExists(function($q){
                $q->select('fldencounterval')
                    ->from('tblconsult')
                    ->whereRaw('tblconsult.fldencounterval = tblpatbilling.fldencounterval');
                // more where conditions
            });
    }

    public function getDeptQuery($request,$patBilling){

        $departments =  Department::
        select(
            'flddept as name', DB::raw('count(tblconsult.fldconsultname) as test_count')
        )
            ->leftJoin('tblconsult','tblconsult.fldconsultname','tbldepartment.flddept')
            ->whereDate('tblconsult.fldconsulttime','>=', $request->eng_from_date)->whereDate('tblconsult.fldconsulttime','<=', $request->eng_to_date)
            ->when(isset($request->encounter_type) , function ($q) {
                return $q->where('tblconsult.fldencounterval','like','IP%');
            })
            ->when(!isset($request->encounter_type) , function ($q) {
                return $q->where('tblconsult.fldencounterval','like','OP%');
            })
            ->groupBy('tbldepartment.flddept')
//            ->union($patBilling)
            ->orderBy('test_count','desc');
        $options = ['path' => route('toptendepartment',['eng_from_date' => $request->eng_from_date,'eng_to_date' => $request->eng_to_date,'encounter_type' => $request->encounter_type])];

        return $this->paginate($departments->get(),$options,10,$request->page);

    }

    public function paginate($items, $options = [], $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
