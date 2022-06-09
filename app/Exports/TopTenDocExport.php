<?php

namespace App\Exports;

use App\CogentUsers;
use App\HospitalDepartment;
use App\PatBilling;
use App\PatLabTest;
use App\User;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TopTenDocExport implements FromView,ShouldAutoSize
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
        $patBilling =  PatBilling::select(DB::raw('count(tblpatbilling.fldencounterval) as test_count'),DB::raw("'Others' as fullnames"))
            ->distinct('fldencounterval')
            ->whereDate('fldtime','>=', $this->eng_from_date)->whereDate('fldtime','<=', $this->eng_to_date)
            ->whereNotExists(function($q){
                $q->select('fldencounterval')
                    ->from('tblconsult')
                    ->whereRaw('tblconsult.fldencounterval = tblpatbilling.fldencounterval');;
                // more where conditions
            });
        $top_ten_doc=CogentUsers::
        select(

            DB::raw('sum(tblpatbilling.fldditemamt) as total_amount'), DB::raw('CONCAT(firstname,\' \',COALESCE(middlename,"") ,\' \',lastname) as fullnames')
        )
            ->leftJoin('tblpatbilling','tblpatbilling.flduserid','users.flduserid')
            ->whereDate('tblpatbilling.fldtime','>=', $this->eng_from_date)->whereDate('tblpatbilling.fldtime','<=', $this->eng_to_date)
            ->when(isset($this->encounter_type) , function ($q) {
                return $q->where('tblpatbilling.fldencounterval','like','IP%');
            })
            ->when(!isset($this->encounter_type) , function ($q) {
                return $q->where('tblpatbilling.fldencounterval','like','OP%');
            })
            ->groupBy('users.id')
            ->orderBy('total_amount','desc')
        ->get();
        $from_date = $this->eng_from_date;
        $to_date = $this->eng_to_date;
        return view('department::top-ten-doc.excel.top-ten-doc-export',compact('top_ten_doc','from_date','to_date'));
    }

    public function paginate($items, $options = [], $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
