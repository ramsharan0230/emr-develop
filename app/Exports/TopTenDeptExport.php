<?php

namespace App\Exports;

use App\Department;
use App\HospitalDepartment;
use App\PatBilling;
use App\PatLabTest;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TopTenDeptExport implements FromView,ShouldAutoSize
{
    public function __construct($eng_from_date,$eng_to_date,$encounter_type,$page)
    {
        $this->eng_from_date=$eng_from_date??'';
        $this->eng_to_date=$eng_to_date??'';
        $this->encounter_type=$encounter_type;
        $this->page=$page;

    }

    public function view(): View
    {
        $from_date = $this->eng_from_date;
        $to_date = $this->eng_to_date;
        $patBilling = PatBilling::select(DB::raw("'others' as name"),DB::raw('count(tblpatbilling.fldencounterval) as test_count'))
            ->distinct('fldencounterval')
            ->whereDate('fldtime','>=', $this->eng_from_date)->whereDate('fldtime','<=',  $this->eng_to_date)
            ->whereNotExists(function($q){
                $q->select('fldencounterval')
                    ->from('tblconsult')
                    ->whereRaw('tblconsult.fldencounterval = tblpatbilling.fldencounterval');;
                // more where conditions
            });
        $dept=Department::
        select(
            'flddept as name', DB::raw('count(tblconsult.fldconsultname) as test_count')
        )
            ->leftJoin('tblconsult','tblconsult.fldconsultname','tbldepartment.flddept')
            ->whereDate('tblconsult.fldconsulttime','>=', $from_date)->whereDate('tblconsult.fldconsulttime','<=', $to_date)
            ->when(isset($this->encounter_type) , function ($q) {
                return $q->where('tblconsult.fldencounterval','like','IP%');
            })
            ->when(!isset($this->encounter_type) , function ($q) {
                return $q->where('tblconsult.fldencounterval','like','OP%');
            })
            ->groupBy('tbldepartment.flddept')
            ->union($patBilling)
            ->orderBy('test_count','desc');
        $options = ['path' => route('toptendepartment',['eng_from_date' => $this->eng_from_date,'eng_to_date' => $this->eng_to_date,'encounter_type' => $this->encounter_type])];

        $top_ten_dept =  $this->paginate($dept->get(),$options,10,$this->page);


        return view('department::top-ten.excel.top-ten-dept-export',compact('top_ten_dept','from_date','to_date'));
    }

    public function paginate($items, $options = [], $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
