<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;


class PurchaseVatREportsExport implements FromView,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(String $fromdateeng, String $todateeng)
    {
        $this->fromdateeng = $fromdateeng;
        $this->todateeng =  $todateeng;
        // dd($todate);
    }

    public function view(): view
    {

        $fromdateeng = $this->fromdateeng;
        $todateeng = $this->todateeng;
              
        try{

        $result = DB::table('tblpurchasebill')
            ->where('fldpurdate','>=',$fromdateeng)
            ->where('fldpurdate','<=', $todateeng)
            ->get();

            // dd($result);
        }catch(\Exception $e){
            dd($e);
        }

        return view('inventory::pdf.purchase-vat-pdf',compact('result'));
    }
}
