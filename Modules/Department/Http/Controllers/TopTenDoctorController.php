<?php

namespace Modules\Department\Http\Controllers;

use App\CogentUsers;
use App\Exports\TopTenDeptExport;
use App\Exports\TopTenDocExport;
use App\HospitalDepartment;
use App\PatBilling;
use App\User;
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

class TopTenDoctorController extends Controller
{

    public function index(Request $request) {
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $top_ten_doc=collect();
        if($eng_from_date){
            $patBilling = $this->patBillingQuery($eng_from_date,$eng_to_date);
            $top_ten_doc=$this->getDocQuery($request,$patBilling);
		}
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('department::top-ten-doc.index',compact('top_ten_doc','date'));
    }

    public function pdfTopTenDoc(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $top_ten_doc=collect();
        if($eng_from_date){
            $patBilling = $this->patBillingQuery($eng_from_date,$eng_to_date);
            $top_ten_doc=$this->getDocQuery($request,$patBilling);
            $encountertype ='OPDs';
            if(isset($request->encounter_type)){
                $encountertype ='IPDs';
            }


		}
        return view('department::top-ten-doc.pdf.top-ten-doc-pdf',compact('top_ten_doc','userid','encountertype'));
    }

    public function exportTopTenDoc(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $encounter_type=$request->encounter_type;

        $export = new TopTenDocExport($eng_from_date,$eng_to_date,$encounter_type,$request->page);
        ob_end_clean();
        ob_start();

        return Excel::download($export, 'top-ten-doc-report.xlsx');
    }

    public function patBillingQuery($eng_from_date,$eng_to_date){
        return PatBilling::select(DB::raw('count(tblpatbilling.fldencounterval) as test_count'),DB::raw("'Others' as fullnames"))
            ->distinct('fldencounterval')
            ->whereDate('fldtime','>=', $eng_from_date)->whereDate('fldtime','<=', $eng_to_date)
            ->whereNotExists(function($q){
                $q->select('fldencounterval')
                    ->from('tblconsult')
                    ->whereRaw('tblconsult.fldencounterval = tblpatbilling.fldencounterval');
                // more where conditions
            });
    }

    public function getDocQuery($request,$patBilling){
        $users = CogentUsers::
        select(

             'users.id',DB::raw('sum(tblpatbilling.fldditemamt) as total_amount'), DB::raw('CONCAT(firstname,\' \',COALESCE(middlename,"") ,\' \',lastname) as fullnames')
        )
            ->leftJoin('tblpatbilling','tblpatbilling.flduserid','users.flduserid')
            ->whereDate('tblpatbilling.fldtime','>=', $request->eng_from_date)->whereDate('tblpatbilling.fldtime','<=', $request->eng_to_date)
            ->when(isset($request->encounter_type) , function ($q) {
                return $q->where('tblpatbilling.fldencounterval','like','IP%');
            })
            ->when(!isset($request->encounter_type) , function ($q) {
                return $q->where('tblpatbilling.fldencounterval','like','OP%');
            })
            ->whereNotNull('lastname')
            ->groupBy('users.id')
            ->orderBy('total_amount','desc')
            ->get();


        return $users;
    }

    public function paginate($items, $options = [], $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
